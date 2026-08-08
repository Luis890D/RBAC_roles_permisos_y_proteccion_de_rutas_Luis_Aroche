# Plan de Implementación Módulo RBAC (Roles, Permisos y Protección de Rutas)

**Sistema:** Sistema Hospitalario Integrado (HIS)  
**Módulo:** 02 — Control de Acceso Basado en Roles (RBAC)  
**Estudiante:** Luis David Aroche Contreras (`Luis890D`)  
**Rama:** `shi-documentacion-rbac-Luis-Aroche` / `feature/asii-02-rbac-roles-permisos-y-proteccion-de-rutas-luis890d`  

---

## 1. Visión General del Plan

El módulo RBAC provee la infraestructura autorizativa transversal para el Sistema Hospitalario Integrado (HIS). Garantiza la segregación estricta de funciones entre los distintos actores de la salud (médicos, enfermeros, laboratoristas, recepcionistas, administradores) y asegura el aislamiento multi-tenant.

## 2. Decisiones Arquitectónicas y Tecnológicas

- **Backend Framework:** Laravel 12 (PHP 8.2+)
- **Motor RBAC Core:** `spatie/laravel-permission` (versión adaptada para multi-tenant o guard JWT).
- **Aislamiento Multi-Tenant:** `stancl/tenancy` (los permisos y roles operan bajo el contexto del tenant activo identificado por `X-Tenant-ID` o dominio).
- **Autenticación:** JWT (JSON Web Tokens) vía `tymon/jwt-auth` o guard JWT configurado.
- **Frontend Stack:** Vue 3 (Composition API) + Pinia (State Management) + Vue Router 4.
- **Protección de Rutas Frontend:** Navigation Guards `beforeEach` con verificación de matriz de permisos cargada en Pinia.

---

## 3. Matriz Inicial de Roles y Permisos Granulares

| Rol | Permisos Asignados (Ejemplos) |
|---|---|
| **SuperAdmin / Admin Hospitalario** | `rbac.roles.manage`, `rbac.permissions.assign`, `users.manage`, `audit.view`, `*` |
| **Médico Especialista / General** | `patients.read`, `patients.update`, `emr.soap.write`, `prescriptions.create`, `lab.orders.create` |
| **Personal de Enfermería** | `patients.read`, `vitals.write`, `beds.update`, `emr.read` |
| **Técnico de Laboratorio** | `lab.orders.read`, `lab.samples.receive`, `lab.results.write`, `lab.results.validate` |
| **Recepcionista / Admisión** | `patients.create`, `patients.read`, `patients.update`, `appointments.schedule`, `admissions.create` |

---

## 4. Cambios Propuestos por Componente

### Componente Backend (Laravel 12)

#### [NEW] [RbacController.php](file:///d:/U%202024%20DAVID/U%20David%202026/Octavo%20Semestre/AN%C3%81LISIS%20DE%20SISTEMAS%20II/shi-documentacion-rbac/app/Http/Controllers/Api/V1/RbacController.php)
Controller encargado de los endpoints CRUD de roles, consulta de permisos del catálogo, asignación/revocación de roles a usuarios y el endpoint consolidado `/auth/me/permissions`.

#### [NEW] [RbacSeeder.php](file:///d:/U%202024%20DAVID/U%20David%202026/Octavo%20Semestre/AN%C3%81LISIS%20DE%20SISTEMAS%20II/shi-documentacion-rbac/database/seeders/RbacSeeder.php)
Seeder inicial que siembra los roles por defecto (`Admin`, `Médico`, `Enfermera`, `TecnicoLab`, `Recepcionista`) y el árbol completo de permisos granulares por módulo.

#### [MODIFY] [api.php](file:///d:/U%202024%20DAVID/U%20David%202026/Octavo%20Semestre/AN%C3%81LISIS%20DE%20SISTEMAS%20II/shi-documentacion-rbac/routes/api.php)
Registrar el grupo de rutas `/api/v1/rbac/*` protegidas por autenticación JWT y middleware de permisos (`permission:rbac.roles.manage`).

#### [MODIFY] [User.php](file:///d:/U%202024%20DAVID/U%20David%202026/Octavo%20Semestre/AN%C3%81LISIS%20DE%20SISTEMAS%20II/shi-documentacion-rbac/app/Models/User.php)
Asegurar el trait `HasRoles` de Spatie y la inclusión de `roles` y `permissions` en el payload retornado en `/auth/me`.

---

### Componente Frontend (Vue 3 / Vite)

#### [NEW] [rbacStore.js](file:///d:/U%202024%20DAVID/U%20David%202026/Octavo%20Semestre/AN%C3%81LISIS%20DE%20SISTEMAS%20II/shi-documentacion-rbac/resources/js/stores/rbacStore.js)
Store Pinia que almacena la lista de permisos del usuario activo, verifica autorizaciones (`hasPermission('patients.create')`, `hasRole('Médico')`) y reacciona al cambio de tenant.

#### [NEW] [can.js (Directiva Vue)](file:///d:/U%202024%20DAVID/U%20David%202026/Octavo%20Semestre/AN%C3%81LISIS%20DE%20SISTEMAS%20II/shi-documentacion-rbac/resources/js/directives/can.js)
Directiva personalizada `v-can="'patients.create'"` para ocultar o deshabilitar elementos visuales (botones, formularios, secciones) según permisos.

#### [MODIFY] [router/index.js](file:///d:/U%202024%20DAVID/U%20David%202026/Octavo%20Semestre/AN%C3%81LISIS%20DE%20SISTEMAS%20II/shi-documentacion-rbac/resources/js/router/index.js)
Configuración de `router.beforeEach` para evaluar la meta-propiedad `meta: { requiresPermission: 'rbac.roles.manage' }` y redirigir a vista `/403` en caso de acceso no autorizado.

#### [NEW] [RolesManagementView.vue](file:///d:/U%202024%20DAVID/U%20David%202026/Octavo%20Semestre/AN%C3%81LISIS%20DE%20SISTEMAS%20II/shi-documentacion-rbac/resources/js/views/rbac/RolesManagementView.vue)
Vista administrativa para crear/editar roles, seleccionar permisos con checkboxes y asignar roles a usuarios.

---

## 5. Plan de Verificación y Pruebas

### Pruebas Automatizadas (Backend)
- `php artisan test --filter=RbacTest`: Validar asignación de permisos, denegación 403 HTTP en endpoints protegidos y aislamiento por tenant.

### Verificación Manual (Frontend & API)
- **Caso 1:** Login como Recepcionista -> Intentar ingresar a `/admin/roles` en frontend -> Redirección automática a `/403 Forbidden`.
- **Caso 2:** Login como Admin -> Crear rol "Bioquímico Asistente", asignar permisos `lab.results.write` -> Verificar sincronización en BD.
- **Caso 3:** Petición HTTP directa vía Postman/Curl a `/api/v1/rbac/roles` sin token o sin permiso -> Respuesta 401/403 formateada en JSON.

---
