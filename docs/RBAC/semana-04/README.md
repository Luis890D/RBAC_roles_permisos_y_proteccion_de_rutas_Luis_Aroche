# Semana 04 — Arquitectura en Capas y Patrón Repositorio
## Módulo 02: RBAC (Roles, Permisos y Protección de Rutas) — HIS

**Curso:** Análisis de Sistemas II (ASII) — Ciclo 2026  
**Estudiante:** Luis David Aroche Contreras (`@Luis890D`)  
**Módulo Asignado:** Módulo 02 — Control de Acceso Basado en Roles (RBAC), Permisos y Protección de Rutas  
**Rama de Trabajo:** `feature/asii-02-rbac-roles-permisos-y-proteccion-de-rutas-luis890d`  
**Estado:** ✅ **Completado y Validado**

---

### 📌 Objetivo de la Semana
Definir con rigor técnico las responsabilidades y límites de cada capa de software (UI/Presentación, API Gateway/HTTP, Lógica de Negocio/Dominio, Abstracción de Datos/Repositorio e Infraestructura), catalogar los objetos reutilizables (DTOs, Value Objects, Enums), modelar los flujos de validación en diagramas de secuencia y formalizar el diseño del Patrón Repositorio con inversión de dependencias (DIP) y esquema SQL/PostgreSQL para multi-tenancy.

---

### 📂 Documentos de la Semana 04

| Documento | Descripción / Entregable | Enlace |
|---|---|:---:|
| **Documento Técnico Principal** | Arquitectura en 5 capas, tabla de responsabilidades por componente, catálogo de objetos reutilizables (DTOs de entrada/salida, Enums de roles y estados, Form Requests), diagrama de secuencia de verificación y contratos de interfaz. | [`semana-04-arquitectura-capas-repositorio.md`](semana-04-arquitectura-capas-repositorio.md) |
| **Especificación Maestra del Repositorio** | Documento exhaustivo de arquitectura de software, especificación técnica de contratos de repositorio (`RoleRepositoryInterface`, `PermissionRepositoryInterface`), reglas de Clean Architecture (DDD), DDL estructurado para PostgreSQL 16 y matriz de pruebas. | [`TAREA_ARQUITECTURA_REPOSITORY_RBAC.md`](TAREA_ARQUITECTURA_REPOSITORY_RBAC.md) |
| **Esquema de Tablas SQL y Persistencia** | Sentencias DDL SQL compatibles con PostgreSQL y MySQL 8.0+, estructura relacional de tablas de roles, permisos, usuarios y tenants, índices de rendimiento y plan de ejecución de migraciones. | [`esquema-tablas-y-plan-de-implementacion.md`](esquema-tablas-y-plan-de-implementacion.md) |

---

### 🏛️ Estructura por Capas (Layered Architecture + DDD)

1. **Capa 1: Presentación / UI (Vue 3 / Pinia):** Vistas de gestión (`RolesManagementView.vue`), almacén reactivo (`rbacStore.js`), navigation guards (`router/index.js`) y directivas (`v-can`).
2. **Capa 2: Interfaz API / HTTP (Laravel 12):** Rutas `/api/v1/rbac/*`, Middlewares (`PermissionMiddleware`, `TenantScope`), Form Requests (`StoreRoleRequest`) y API Resources (`RoleResource`).
3. **Capa 3: Lógica de Dominio (Domain Services):** `RbacAuthorizationService`, `RoleManagementService`, `RbacCacheManager` y eventos de dominio (`RoleCreated`, `PermissionsSynced`).
4. **Capa 4: Abstracción de Persistencia (Repository Pattern):** Contratos de interfaz (`RoleRepositoryInterface`), implementaciones Eloquent, modelos de dominio y adaptadores de caché Redis.
5. **Capa 5: Almacenamiento e Infraestructura:** Motores relacionales (PostgreSQL 16 / MySQL 8.0+) y clúster en memoria (Redis 7.2+).

---

### 📦 Catálogo de Objetos Reutilizables

- **DTOs:** `RoleCreateDTO`, `RoleUpdateDTO`, `PermissionAssignmentDTO`, `UserRoleSyncDTO`, `RoleResponseDTO`.
- **Enums:** `SystemRole` (SuperAdmin, Medico, Enfermero, etc.), `PermissionAction` (Create, Read, Update, Delete, Validate), `RoleScope` (Global, Tenant).
- **Form Requests:** `StoreRoleRequest`, `UpdateRoleRequest`, `AssignPermissionsRequest`, `SyncUserRolesRequest`.

---

### 🔗 Documentos Relacionados
- [Consolidado de Asignación Semanas 3, 4 y 5](../asignacion-individual-semana-3-5.md)
- [Índice General de Documentación RBAC](../README.md)
