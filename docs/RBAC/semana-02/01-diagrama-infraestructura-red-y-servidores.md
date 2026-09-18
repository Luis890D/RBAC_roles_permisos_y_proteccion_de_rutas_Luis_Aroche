# Diagrama de Infraestructura de Red y Servidores
## Sistema Hospitalario Integrado (HIS) — Módulo 02: RBAC

**Curso:** Análisis de Sistemas II (ASII) — 2026  
**Estudiante:** Luis David Aroche Contreras (`Luis890D`)  
**Módulo:** RBAC (Roles, Permisos y Protección de Rutas)  
**Entorno:** Arquitectura Multi-Tenant (Stancl Tenancy + Spatie Permissions)  

---

## 1. Descripción de la Infraestructura

La infraestructura del **Sistema Hospitalario Integrado (HIS)** está diseñada con base en una arquitectura por capas de seguridad (**Defense in Depth**), garantizando **alta disponibilidad**, **aislamiento multi-tenant** y **latencia mínima de autorización (< 15 ms)** para el módulo de control de acceso (RBAC).

### Capas y Zonas de Seguridad:
1. **Red Local Hospitalaria (VLANs Segmentadas):**
   - **VLAN 10 (Médica):** Estaciones de trabajo médicas para consulta de EMR, prescripciones y diagnósticos.
   - **VLAN 20 (Enfermería):** Tablets y dispositivos móviles conectados a red Wi-Fi WPA3 Enterprise para toma de signos vitales y visualización de camas.
   - **VLAN 30 (Laboratorio):** Equipos y analizadores clínicos para recepción de muestras e ingreso de resultados.
   - **VLAN 40 (Administración):** Terminales de admisión, facturación, auditoría y administración de roles RBAC.
   - **Switch Core / Gateway Hospitalario:** Concentrador de tráfico inter-VLAN con políticas de enrutamiento y QoS.
2. **Perímetro de Seguridad (DMZ & Edge):**
   - **Firewall UTM / WAF:** Filtrado de paquetes, mitigación DDoS, inspección HTTPS e inspección de túneles VPN WireGuard/IPSec para sedes remotas.
   - **Reverse Proxy & Load Balancer (Nginx):** Terminación TLS 1.3, compresión, balanceo HTTP/FastCGI e inyección de encabezados de auditoría (`X-Forwarded-For`).
3. **Zona de Servidores de Aplicación (Private App Subnet):**
   - Servidores balanceados ejecutando **Ubuntu Server 24.04 LTS**, **PHP 8.2-FPM** y el framework **Laravel 12**, alojando el frontend SPA compilado en **Vue 3**.
   - Los middlewares de autenticación JWT y autorización Spatie se ejecutan en esta capa.
4. **Zona de Caché y Gobernanza (In-Memory Tier):**
   - Clúster **Redis 7.2+** para almacenar en memoria ultrarrápida la matriz de roles y permisos, evitando consultas redundantes a la base de datos en cada petición HTTP, y gestionando la lista de revocación de tokens JWT.
5. **Zona de Persistencia y Base de Datos (Private Data Subnet):**
   - Clúster **MySQL 8.0+ / PostgreSQL 16** con replicación Maestro-Esclavo (Primary-Replica).
   - Servidor de almacenamiento de objetos **S3 / NFS** para expedientes, PDFs de laboratorio y adjuntos clínicos.
6. **Zona de Monitoreo y Auditoría:**
   - Centralización de logs de auditoría RBAC y métricas con Prometheus, Grafana y Loki/ELK.

---

## 2. Diagrama de Infraestructura (Topología de Red y Servidores)

```mermaid
flowchart TD
    %% ── Dispositivos de Red Local Hospitalaria ───────────────────
    subgraph LAN_HOSPITAL ["🏥 Red Local Hospitalaria (LAN / VLANs Segmentadas)"]
        VLAN_MED["🩺 VLAN 10 - Estaciones Médicas<br/>(EMR, Prescripciones, Diagnósticos)"]
        VLAN_ENF["📱 VLAN 20 - Tablets Enfermería (Wi-Fi WPA3)<br/>(Signos Vitales, Control Camas)"]
        VLAN_LAB["🔬 VLAN 30 - Terminales Laboratorio<br/>(Analizadores, Recepción Muestras)"]
        VLAN_ADM["🖥️ VLAN 40 - Recepción y Admin<br/>(Admisión, Facturación, Gestión RBAC)"]
        SW_CORE["🔀 Switch Core / Gateway Hospitalario<br/>(Enrutamiento Inter-VLAN y QoS)"]
    end

    %% ── Accesos Remotos y Multi-Tenant ─────────────────────────
    subgraph WAN_EXT ["🌐 Acceso Externo y Sedes Remotas (Multi-Tenant)"]
        CLI_EXT["💻 Sedes Clínicas Remotas / Usuarios Autorizados<br/>(Túnel VPN WireGuard / IPSec)"]
    end

    %% ── Perímetro de Seguridad (DMZ) ───────────────────────────
    subgraph DMZ_TIER ["🛡️ Perímetro de Seguridad y DMZ"]
        FW_EDGE["🔥 Firewall UTM / WAF Gateway<br/>(DDoS Protection, Rate Limiting, IDS/IPS)"]
        LB_NGINX["⚖️ Reverse Proxy & Load Balancer (Nginx)<br/>• Terminación TLS 1.3 (HTTPS 443)<br/>• SSL Offloading & Cache Estática<br/>• Inyección Header X-Forwarded-For"]
    end

    %% ── Servidores de Aplicación ───────────────────────────────
    subgraph APP_TIER ["⚙️ Zona de Aplicaciones (Private App Subnet)"]
        SRV_APP1["🖥️ Servidor Web/App 01<br/>• Nginx + PHP 8.2-FPM (Laravel 12 API)<br/>• SPA Vue 3 / Vite Bundles<br/>• Middleware JWT & RBAC Engine"]
        SRV_APP2["🖥️ Servidor Web/App 02 (Alta Disponibilidad)<br/>• Nginx + PHP 8.2-FPM (Laravel 12 API)<br/>• Horizon Workers / Colas en Background"]
    end

    %% ── Capa de Caché y Sesiones ────────────────────────────────
    subgraph CACHE_TIER ["⚡ Capa de Caché en Memoria (In-Memory Tier)"]
        REDIS_SRV["🗄️ Clúster Redis (Cache & Session Store)<br/>• Caché Spatie RBAC (Roles & Permisos)<br/>• Blacklist Tokens JWT Revocados<br/>• Rate Limiting por Tenant"]
    end

    %% ── Capa de Persistencia de Datos ───────────────────────────
    subgraph DATA_TIER ["💾 Zona de Datos y Persistencia (Private Data Subnet)"]
        DB_PRIMARY[("🛢️ BD Master / Primary (MySQL 8.0+)<br/>• Core RBAC: users, roles, permissions<br/>• Clínico: patients, admissions, emr<br/>• Escrituras y Transacciones ACID")]
        DB_REPLICA[("🛢️ BD Replica / Read-Only (MySQL 8.0+)<br/>• Consultas Analíticas y Reportes<br/>• Failover Automático")]
        STORAGE_SRV["📁 Servidor Almacenamiento (MinIO S3 / NFS)<br/>• Adjuntos EMR y Estudios Médicos<br/>• Resultados PDF de Laboratorio<br/>• Backups Cifrados Diarios"]
    end

    %% ── Capa de Monitoreo y Auditoría ───────────────────────────
    subgraph OPS_TIER ["📊 Monitoreo y Auditoría"]
        LOGS_SRV["📈 Servidor Central Logs y Métricas<br/>• Auditoría RBAC y Eventos 403<br/>• Prometheus, Grafana, Loki"]
    end

    %% ── Conexiones y Flujo de Tráfico ───────────────────────────
    VLAN_MED --> SW_CORE
    VLAN_ENF --> SW_CORE
    VLAN_LAB --> SW_CORE
    VLAN_ADM --> SW_CORE

    SW_CORE -->|HTTPS Red Interna| FW_EDGE
    CLI_EXT -->|HTTPS / VPN WireGuard| FW_EDGE

    FW_EDGE -->|Tráfico Inspeccionado| LB_NGINX

    LB_NGINX -->|Balanceo HTTP/FastCGI| SRV_APP1
    LB_NGINX -->|Balanceo HTTP/FastCGI| SRV_APP2

    SRV_APP1 <-->|Lectura/Escritura Permisos & Caché| REDIS_SRV
    SRV_APP2 <-->|Lectura/Escritura Permisos & Caché| REDIS_SRV

    SRV_APP1 -->|Escritura y Mutaciones| DB_PRIMARY
    SRV_APP2 -->|Lectura de Reportes| DB_REPLICA
    DB_PRIMARY -.->|Replicación Semi-síncrona| DB_REPLICA

    SRV_APP1 -->|Lectura/Escritura Archivos| STORAGE_SRV
    SRV_APP2 -->|Lectura/Escritura Archivos| STORAGE_SRV

    SRV_APP1 -.->|Métricas y Syslog| LOGS_SRV
    SRV_APP2 -.->|Métricas y Syslog| LOGS_SRV
    REDIS_SRV -.->|Métricas| LOGS_SRV
    DB_PRIMARY -.->|Métricas| LOGS_SRV

    %% ── Estilos Visuales ────────────────────────────────────────
    classDef clientStyle fill:#1e293b,stroke:#38bdf8,stroke-width:2px,color:#f8fafc;
    classDef switchStyle fill:#0f172a,stroke:#38bdf8,stroke-width:2px,color:#f8fafc;
    classDef edgeStyle fill:#0f172a,stroke:#f59e0b,stroke-width:2px,color:#f8fafc;
    classDef appStyle fill:#1e3a5f,stroke:#3b82f6,stroke-width:2px,color:#e0f2fe;
    classDef cacheStyle fill:#312e81,stroke:#818cf8,stroke-width:2px,color:#ede9fe;
    classDef dbStyle fill:#14532d,stroke:#22c55e,stroke-width:2px,color:#f0fdf4;
    classDef opsStyle fill:#3f3f46,stroke:#a1a1aa,stroke-width:2px,color:#fafafa;

    class VLAN_MED,VLAN_ENF,VLAN_LAB,VLAN_ADM,CLI_EXT clientStyle;
    class SW_CORE switchStyle;
    class FW_EDGE,LB_NGINX edgeStyle;
    class SRV_APP1,SRV_APP2 appStyle;
    class REDIS_SRV cacheStyle;
    class DB_PRIMARY,DB_REPLICA,STORAGE_SRV dbStyle;
    class LOGS_SRV opsStyle;
```

---

## 3. Especificaciones Técnicas y Dimensionamiento

| Servidor / Equipo | Función | Stack Tecnológico | Especificación de Hardware Recomendada | Puertos de Red |
|---|---|---|---|---|
| **Firewall / WAF Gateway** | Seguridad de borde, IDS/IPS y VPN | pfSense / OPNsense / Cloudflare | 4 vCPU, 8 GB RAM, 2x 10Gbps NIC | 443 (HTTPS), 51820 (WireGuard) |
| **Reverse Proxy & Load Balancer** | SSL Offloading, TLS 1.3 y balanceo | Nginx 1.24+ / HAProxy | 4 vCPU, 8 GB RAM, 50 GB SSD NVMe | 80 (Redirect), 443 (HTTPS) |
| **Servidores de Aplicación (x2)** | API Laravel 12, Vue 3 SPA y RBAC | Ubuntu 24.04, PHP 8.2-FPM, Vite | 8 vCPU, 16 GB RAM, 100 GB NVMe (c/u) | 9000 (PHP-FPM interno), 443 |
| **Servidor de Caché & Queues** | Caché Spatie RBAC y Blacklist JWT | Redis 7.2+ Standalone/Sentinel | 4 vCPU, 8 GB RAM, 30 GB SSD | 6379 (Redis AUTH + TLS) |
| **Clúster de Base de Datos** | Transacciones relacionales HIS | MySQL 8.0 Enterprise / PostgreSQL 16 | 8 vCPU, 32 GB RAM, 500 GB RAID 10 (c/u) | 3306 / 5432 (Solo App Subnet) |
| **Servidor de Almacenamiento** | Archivos EMR, PDFs y adjuntos | MinIO S3 Compatible / NFS cifrado | 4 vCPU, 8 GB RAM, 2 TB Storage Pool | 9000 / 9001 (S3 API interna) |
| **Monitoreo y Auditoría** | Monitoreo y bitácora de eventos | Prometheus, Grafana, Loki / ELK | 4 vCPU, 16 GB RAM, 200 GB SSD | 3000 (Grafana), 9090 (Prometheus) |
