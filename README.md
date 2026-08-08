# Repositorio Evaluado: RBAC (Roles, Permisos y Protección de Rutas)

---

## 📌 Portada Oficial de Entrega

* **Estudiante:** LUIS DAVID AROCHE CONTRERAS
* **Usuario GitHub:** [`Luis890D`](https://github.com/Luis890D)
* **Nombre del Repositorio:** `RBAC_roles_permisos_y_proteccion_de_rutas_Luis_Aroche`
* **URL Oficial del Repositorio:** [`https://github.com/Luis890D/RBAC_roles_permisos_y_proteccion_de_rutas_Luis_Aroche`](https://github.com/Luis890D/RBAC_roles_permisos_y_proteccion_de_rutas_Luis_Aroche)
* **Rama Evaluada:** `main`
* **Etiqueta / Tag Evaluado:** `v1.0.0`
* **Módulo Oficial:** RBAC: roles, permisos y protección de rutas
* **Alcance Usado en la Actividad:** RBAC: roles, permisos y protección de rutas
* **Curso:** Análisis de Sistemas II (ASII) — 2026
* **Institución:** Universidad (ASII)

---

## 🎯 Consigna Individual Resuelta

> **"Modele el proceso «asignación de rol, autorización de una operación y denegación trazable». El diagrama de casos de uso debe delimitar actores y objetivo; el de actividad debe incluir decisiones, excepciones y resultado; el de secuencia debe mostrar participantes, mensajes, validaciones y respuesta. Mantenga trazabilidad entre los tres diagramas."**

---

## 📁 Estructura del Repositorio y Fuentes Editables

```text
RBAC_roles_permisos_y_proteccion_de_rutas_Luis_Aroche/
├── .gitignore                                   # Filtros de archivos
├── README.md                                    # Portada Oficial y Documento Principal
└── docs/
    ├── INFORME_EVALUACION_RBAC.md               # Informe Técnico Completo
    ├── PlantUML/                                # Fuentes Editables PlantUML (.puml)
    │   ├── casodeuso_rbac.puml
    │   ├── actividad_rbac.puml
    │   └── secuencia_rbac.puml
    └── diagramas/                               # Fuentes Editables Mermaid (.mmd)
        ├── casodeuso_rbac.mmd
        ├── actividad_rbac.mmd
        └── secuencia_rbac.mmd
```

---

## 📊 Visualización de Diagramas UML

### 1. Diagrama de Casos de Uso (Delimitación de Actores y Objetivos)

```mermaid
flowchart LR
    Admin(["👤 SuperAdministrador / IT Admin"])
    User(["👤 Usuario Autenticado\nMédico · Enfermera · Lab"])
    MW(["⚙️ Middleware API\nLaravel Permission Guard"])
    VG(["⚙️ Router Guard\nVue 3 Navigation Guard"])
    Audit(["🗄️ Servicio de Auditoría\nLog DB & Trazabilidad"])

    subgraph HIS ["🏥 Sistema Hospitalario Integrado — Módulo RBAC"]
        direction TB
        CU1(["CU-RBAC-01\nAsignar Rol y Permisos a Usuario"])
        CU2(["CU-RBAC-02\nValidar Permiso y Autorizar Operación en API"])
        CU3(["CU-RBAC-03\nDenegar Operación y Registrar Bitácora Trazable"])
        CU4(["CU-RBAC-04\nProteger Navegación y UI en Frontend"])
        CU5(["CU-RBAC-05\nConsultar Matriz de Roles y Permisos"])
    end

    Admin --> CU1
    Admin --> CU5

    User --> CU4

    MW --> CU2
    MW --> CU3

    VG --> CU4

    CU3 -. "«emitir trace_id»" .-> Audit

    CU1 -. "«include»" .-> CU5
    CU2 -. "«include»" .-> CU5
    CU3 -. "«extend»" .-> CU2
```

---

### 2. Diagrama de Actividad (Decisiones, Excepciones y Denegación Trazable)

```mermaid
flowchart TD
    A[Inicio: Usuario solicita ejecución de operación médica/admin] --> B{¿JWT Token Válido?}
    
    B -- No --> C[Redirigir a Login 401 Unauthorized]
    
    B -- Sí --> D[Vue Router Guard envía HTTP Request con Bearer Token]
    
    D --> E[Laravel Permission Guard intercepta solicitud en API]
    
    E --> F{¿Rol asignado existe y está activo?}
    
    F -- No --> G[Generar trace_id UUID único]
    G --> H[Registrar Evento Audit: ROLE_INACTIVE]
    H --> I[Responder HTTP 403 Forbidden con trace_id]
    
    F -- Sí --> J{¿El Rol posee el permiso específico?}
    
    J -- No: Denegación Trazable --> K[Generar trace_id UUID único ej. TRC-890D-2026-001]
    K --> L[Persistir Registro Audit: USER, TENANT, PERMISSION, IP, TIMESTAMP, TRACE_ID]
    L --> M[Responder JSON HTTP 403 Forbidden con trace_id]
    M --> N[Frontend muestra Pantalla Access Denied 403 con trace_id para soporte]
    
    J -- Sí: Autorización Concedida --> O[Ejecutar Controlador de la Operación]
    O --> P[Persistir Cambios en Base de Datos]
    P --> Q[Responder HTTP 200 OK / 201 Created]
    Q --> R[Renderizar Resultado Exitoso en UI Frontend]
    
    C --> S[Fin]
    I --> S
    N --> S
    R --> S
```

---

### 3. Diagrama de Secuencia (Participantes, Mensajes y Respuesta Trazable)

```mermaid
sequenceDiagram
    autonumber
    actor Client as Usuario / Cliente UI
    participant Router as VueRouterGuard (Vue 3)
    participant MW as PermissionMiddleware (Laravel)
    participant RBAC as SpatieRBACService (Core)
    participant Audit as AuditLogService (Gobernanza)
    participant DB as Base de Datos & Redis

    Note over Client, DB: FASE 1: Asignación de Rol a Usuario (Precondición)
    Client->>RBAC: assignRoleToUser(userId, roleId, tenantId)
    activate RBAC
    RBAC->>DB: INSERT INTO model_has_roles
    RBAC->>DB: forgetCachedPermissions()
    DB-->>RBAC: Commit OK
    RBAC-->>Client: 200 OK (Rol Asignado Exitosamente)
    deactivate RBAC

    Note over Client, DB: FASE 2: Solicitud de Operación Protegida y Evaluación
    Client->>Router: Ejecutar Operación (POST /api/v1/emr/soap-notes)
    activate Router
    Router->>MW: HTTP POST /api/v1/emr/soap-notes (Bearer Token)
    activate MW
    MW->>RBAC: evaluatePermission(userId, tenantId, 'emr.soap.write')
    activate RBAC
    RBAC->>DB: Query User Roles & Permissions
    activate DB
    DB-->>RBAC: Roles & Permissions Data
    deactivate DB

    alt Caso 1: Autorización Exitosa (Permiso Concedido)
        RBAC-->>MW: true (Permission Granted)
        MW->>MW: executeController()
        MW-->>Client: HTTP 201 Created { status: "success", data: ... }
    else Caso 2: Denegación Trazable (Permiso Denegado - 403)
        RBAC-->>MW: false (Permission Denied)
        deactivate RBAC
        MW->>Audit: logDeniedAccessEvent(userId, tenantId, permission, ip)
        activate Audit
        Audit->>Audit: generateTraceId() -> UUID "TRC-890D-20260808-001"
        Audit->>DB: INSERT INTO rbac_audit_logs (trace_id, user_id, tenant_id, permission, timestamp)
        activate DB
        DB-->>Audit: Audit Log Saved
        deactivate DB
        Audit-->>MW: traceId "TRC-890D-20260808-001"
        deactivate Audit
        MW-->>Client: HTTP 403 Forbidden { error: "Acceso denegado", trace_id: "TRC-890D-20260808-001" }
        deactivate MW
        Router-->>Client: Renderizar Pantalla 403 con trace_id
        deactivate Router
    end
```

---

## 🔗 Matriz de Trazabilidad UML

| Caso de Uso | Nodo en Diagrama de Actividad | Mensaje en Diagrama de Secuencia | Justificación |
|---|---|---|---|
| **CU-RBAC-01** (Asignar Rol) | `:Seleccionar Usuario y Asignar Rol en el Tenant` | `assignRoleToUser()` & `forgetCachedPermissions()` | Sincronización inmediata de permisos. |
| **CU-RBAC-02** (Autorizar Operación) | `:¿El Rol posee el permiso específico?` (Sí) | `evaluatePermission() -> true` & `HTTP 201 Created` | Continuidad del flujo asistencial autorizado. |
| **CU-RBAC-03** (Denegación Trazable) | `:Generar trace_id` & `:Persistir Registro Audit` | `logDeniedAccessEvent()` & `HTTP 403 Forbidden con trace_id` | Trazabilidad completa auditada en base de datos. |
| **CU-RBAC-04** (Protección UI) | `:Redirigir a Login 401` / `:Pantalla 403` | `RouterGuard` & `Renderizar Pantalla 403 con trace_id` | Protección defensiva en capas. |

---

## 🤖 Declaración Transparente de Uso de IA

* **Herramienta:** Antigravity AI Agent (Google DeepMind / Gemini 3.6 Flash).
* **Propósito:** Estructuración sintáctica PlantUML/Mermaid y formato de documentación técnica.
* **Validación Humana:** El estudiante revisó y ajustó el modelo multi-tenant, la invalidación de caché Spatie y el identificador `trace_id`.

---

## 🛠️ Comandos Git de Creación y Sincronización Remota

Para verificar y subir este repositorio a GitHub (`Luis890D`):

```bash
# 1. Verificar estado del repositorio e historial
git status
git log --oneline

# 2. Verificar la etiqueta evaluada
git tag -n

# 3. Vincular con el repositorio remoto de GitHub y publicar
git remote add origin https://github.com/Luis890D/RBAC_roles_permisos_y_proteccion_de_rutas_Luis_Aroche.git
git branch -M main
git push -u origin main --tags
```
