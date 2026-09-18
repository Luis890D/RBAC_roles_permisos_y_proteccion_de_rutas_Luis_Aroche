# Semana 05 — API REST, Contratos, RFC 7807 y Plan de Integración Git
## Módulo 02: RBAC (Roles, Permisos y Protección de Rutas) — HIS

**Curso:** Análisis de Sistemas II (ASII) — Ciclo 2026  
**Estudiante:** Luis David Aroche Contreras (`@Luis890D`)  
**Módulo Asignado:** Módulo 02 — Control de Acceso Basado en Roles (RBAC), Permisos y Protección de Rutas  
**Rama de Trabajo:** `feature/asii-02-rbac-roles-permisos-y-proteccion-de-rutas-luis890d`  
**Estado:** ✅ **Completado y Validado**

---

### 📌 Objetivo de la Semana
Diseñar la especificación exhaustiva del contrato de API RESTful para la administración de roles, asignación de permisos e inspección de privilegios, estandarizar los payloads JSON de petición y respuesta, definir el catálogo de errores bajo el estándar RFC 7807 (*Problem Details for HTTP APIs*), y trazar el plan técnico de integración en Git mediante ramas feature, Git Worktrees y Pull Requests hacia la rama base.

---

### 📂 Documentos de la Semana 05

| Documento | Descripción / Entregable | Enlace |
|---|---|:---:|
| **Documento Técnico Principal** | Especificación formal de 6 endpoints clave de la API REST, payloads JSON de entrada y salida, códigos de estado HTTP, catálogo de errores RFC 7807, matriz de permisos requeridos y guía operativa paso a paso de Git Worktree, branching y checklist para Pull Request. | [`semana-05-api-rest-contrato-integracion.md`](semana-05-api-rest-contrato-integracion.md) |
| **Plan Maestro de Integración e Implementación** | Hoja de ruta completa de desarrollo semana por semana (S1 a S5), cronograma en diagrama de Gantt, decisiones arquitectónicas, validaciones de seguridad multi-tenant y DoD para el cierre del Bloque Parcial 1. | [`plan-de-implementacion-rbac.md`](plan-de-implementacion-rbac.md) |

---

### 🌐 Endpoints Principales del Módulo RBAC

| Método | Endpoint | Descripción | Permiso Requerido |
|:---:|---|---|---|
| `GET` | `/api/v1/rbac/roles` | Listar catálogo de roles del tenant con conteo de permisos | `rbac.roles.read` |
| `POST` | `/api/v1/rbac/roles` | Crear un nuevo rol vinculado al tenant activo | `rbac.roles.create` |
| `GET` | `/api/v1/rbac/roles/{id}` | Obtener detalle de rol y matriz de permisos asignados | `rbac.roles.read` |
| `PUT` | `/api/v1/rbac/roles/{id}` | Actualizar nombre o descripción de un rol existente | `rbac.roles.update` |
| `POST` | `/api/v1/rbac/roles/{id}/permissions` | Asignar o sincronizar matriz de permisos de un rol | `rbac.permissions.assign` |
| `POST` | `/api/v1/rbac/users/{id}/roles` | Asignar roles a un usuario hospitalario | `rbac.users.assign` |

---

### 🛡️ Catálogo de Respuestas y Errores (RFC 7807)

Todas las respuestas de error implementan el formato estándar `application/problem+json` con los campos: `type`, `title`, `status`, `detail`, `instance`, `invalid_params` y `trace_id`.

---

### 🔗 Documentos Relacionados
- [Consolidado de Asignación Semanas 3, 4 y 5](../asignacion-individual-semana-3-5.md)
- [Cronograma Gantt Completo](../gantt-rbac-completo.md)
- [Índice General de Documentación RBAC](../README.md)
