# Semana 01 — Diagnóstico, Actores, Alcance y Casos de Uso UML
## Módulo 02: RBAC (Roles, Permisos y Protección de Rutas) — HIS

**Curso:** Análisis de Sistemas II (ASII) — Ciclo 2026  
**Estudiante:** Luis David Aroche Contreras (`@Luis890D`)  
**Módulo Asignado:** Módulo 02 — Control de Acceso Basado en Roles (RBAC), Permisos y Protección de Rutas  
**Rama de Trabajo:** `feature/asii-02-rbac-roles-permisos-y-proteccion-de-rutas-luis890d`  
**Estado:** ✅ **Completado y Validado**

---

### 📌 Objetivo de la Semana
Identificar y caracterizar los actores (humanos y de sistema), definir el alcance y los límites (*In Scope / Out of Scope*) del subsistema autorizativo, y modelar los casos de uso principales en UML con su respectiva narrativa técnica y diagramas de flujo.

---

### 📂 Documentos de la Semana 01

| Documento | Descripción / Contenido | Enlace |
|---|---|:---:|
| **Documento Técnico Principal** | Especificación completa de actores, límites del sistema, casos de uso UML (`CU-RBAC-01` a `CU-RBAC-06`), diagramas de secuencia e integración multi-tenant. | [`semana-01-actores-alcance-casos-de-uso.md`](semana-01-actores-alcance-casos-de-uso.md) |
| **Ficha de Evidencia Semana 1** | Resumen ejecutivo de cumplimiento, verificación de criterios DoD, matriz de actores y flujo de casos de uso para el Issue de seguimiento. | [`HIS_Arquitectura_RBAC.md`](HIS_Arquitectura_RBAC.md) |

---

### 👥 Resumen de Actores Identificados

1. **SuperAdministrador / Administrador de TI:** Gestión centralizada de roles, jerarquías y matriz de permisos por tenant.
2. **Personal Médico:** Consumo de permisos clínicos (EMR, diagnósticos, recetas, órdenes).
3. **Personal de Enfermería:** Consumo de permisos de asistencia clínica (signos vitales, triaje, camas).
4. **Técnico de Laboratorio:** Consumo de permisos de procesamiento de muestras y validación analítica.
5. **Recepcionista / Admisión:** Consumo de permisos administrativos (citas, admisión, registro inicial de pacientes).
6. **Auditor de Seguridad:** Acceso de sólo lectura a la matriz de permisos y trazabilidad de eventos.
7. **Auth Subsystem (JWT):** Subsistema que inyecta los claims de identidad (`user_id`, `tenant_id`) en cada request.
8. **Stancl Tenancy Engine:** Motor que asegura el aislamiento multi-tenant estricto.

---

### 🎯 Casos de Uso del Módulo (`CU-RBAC`)

- **`CU-RBAC-01`:** Administrar Catálogo de Roles (Crear, listar, actualizar, eliminar roles con scope tenant).
- **`CU-RBAC-02`:** Asignar Matriz de Permisos a Rol (Configurar privilegios granulares por módulo).
- **`CU-RBAC-03`:** Asignar Roles a Usuarios (Vincular personal hospitalario con roles vigentes).
- **`CU-RBAC-04`:** Validar Permisos en Request Backend (Middlewares Laravel `role:name`, `permission:name`).
- **`CU-RBAC-05`:** Proteger Rutas y Elementos UI en Frontend (Guards Vue Router y directivas `v-can`).
- **`CU-RBAC-06`:** Sincronizar Permisos de Usuario en Sesión (Endpoint `/api/v1/auth/me` con refresco en caché Redis).

---

### 🔗 Documentos Relacionados
- [Consolidado de Asignación Semanas 1 y 2](../asignacion-individual-semana-1-2.md)
- [Índice General de Documentación RBAC](../README.md)
