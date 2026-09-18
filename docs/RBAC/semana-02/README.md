# Semana 02 — RF, RNF, Criterios BDD, SOLID e Infraestructura Hospitalaria
## Módulo 02: RBAC (Roles, Permisos y Protección de Rutas) — HIS

**Curso:** Análisis de Sistemas II (ASII) — Ciclo 2026  
**Estudiante:** Luis David Aroche Contreras (`@Luis890D`)  
**Módulo Asignado:** Módulo 02 — Control de Acceso Basado en Roles (RBAC), Permisos y Protección de Rutas  
**Rama de Trabajo:** `feature/asii-02-rbac-roles-permisos-y-proteccion-de-rutas-luis890d`  
**Estado:** ✅ **Completado y Validado**

---

### 📌 Objetivo de la Semana
Definir la matriz exhaustiva de Requerimientos Funcionales (RF) y No Funcionales (RNF), redactar los criterios de aceptación en sintaxis BDD (Gherkin: Given-When-Then), fundamentar la aplicación práctica de principios SOLID (SRP, OCP, ISP, DIP) con base técnica en [MVP Cluster](https://mvpcluster.com/diseno-de-software-2/), y proveer la documentación de arquitectura de red y procesos asistenciales globales del hospital.

---

### 📂 Documentos de la Semana 02

| Documento | Descripción / Entregable | Enlace |
|---|---|:---:|
| **Documento Técnico Principal** | Matriz de RF (RF-01 al RF-06), RNF (RNF-01 al RNF-04), Criterios BDD en formato Gherkin y fundamentación con código de principios SOLID aplicados a Laravel 12 y Vue 3. | [`semana-02-rf-rnf-criterios-aceptacion-solid.md`](semana-02-rf-rnf-criterios-aceptacion-solid.md) |
| **Topología de Red y Servidores** | Diagrama de segmentación por VLANs hospitalarias (Médica, Enfermería, Laboratorio, Admin), DMZ, Clúster Redis, Base de Datos y Servidores de Aplicación. | [`01-diagrama-infraestructura-red-y-servidores.md`](01-diagrama-infraestructura-red-y-servidores.md) |
| **Procesos Globales del Hospital** | Diagrama del ciclo asistencial completo del paciente (Admisión, Triaje, Consulta, Prescripción, Laboratorio, Alta) con compuertas autorizativas RBAC. | [`02-diagrama-procesos-general-hospital-rbac.md`](02-diagrama-procesos-general-hospital-rbac.md) |
| **Consolidado de Infraestructura y Procesos** | Documento técnico unificado con ambos diagramas arquitectónicos, matrices de puertos, especificaciones de hardware y políticas transversales de seguridad. | [`diagrama-infraestructura-y-procesos-globales.md`](diagrama-infraestructura-y-procesos-globales.md) |

---

### 🏛️ Principios SOLID Aplicados en el Módulo

- **SRP (Single Responsibility):** Servicios desacoplados (`RbacAuthorizationService`, `RoleManagementService`, `RbacCacheManager`).
- **OCP (Open/Closed):** Sistema extensible mediante Middlewares y Policies sin alterar el núcleo de autorización.
- **LSP (Liskov Substitution):** Adaptadores de repositorio intercambiables que implementan contratos de persistencia comunes.
- **ISP (Interface Segregation):** Interfaces pequeñas y específicas (`RoleReaderInterface`, `RoleWriterInterface`, `PermissionCheckerInterface`).
- **DIP (Dependency Inversion):** Controladores y servicios dependen de interfaces de repositorio abstractas y no de implementaciones concretas de Eloquent.

---

### 🔗 Documentos Relacionados
- [Consolidado de Asignación Semanas 1 y 2](../asignacion-individual-semana-1-2.md)
- [Índice General de Documentación RBAC](../README.md)
