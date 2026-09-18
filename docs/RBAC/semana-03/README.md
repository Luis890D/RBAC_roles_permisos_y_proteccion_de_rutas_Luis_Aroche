# Semana 03 — Diseño Arquitectónico, Vistas C4 y Patrones de Diseño
## Módulo 02: RBAC (Roles, Permisos y Protección de Rutas) — HIS

**Curso:** Análisis de Sistemas II (ASII) — Ciclo 2026  
**Estudiante:** Luis David Aroche Contreras (`@Luis890D`)  
**Módulo Asignado:** Módulo 02 — Control de Acceso Basado en Roles (RBAC), Permisos y Protección de Rutas  
**Rama de Trabajo:** `feature/asii-02-rbac-roles-permisos-y-proteccion-de-rutas-luis890d`  
**Estado:** ✅ **Completado y Validado**

---

### 📌 Objetivo de la Semana
Diseñar la vista arquitectónica formal del módulo RBAC utilizando el **Modelo C4** (Diagramas de Contexto C1, Contenedores C2 y Componentes C3), modelar el mapa exhaustivo de dependencias autorizativas con los 28 módulos clínicos del HIS, y fundamentar los patrones de diseño de software adoptados (Pipeline Middleware, Cache-Aside en Redis, Decorator UI `v-can`, Strategy de Autorización).

---

### 📂 Documentos de la Semana 03

| Documento | Descripción / Entregable | Enlace |
|---|---|:---:|
| **Documento Técnico Principal** | Especificación formal del Modelo C4 en tres niveles jerárquicos (Contexto, Contenedores y Componentes internos del backend Laravel / frontend Vue 3), matriz de acoplamiento con módulos clínicos y catálogo de patrones arquitectónicos. | [`semana-03-arquitectura-vistas-patrones.md`](semana-03-arquitectura-vistas-patrones.md) |

---

### 🏗️ Síntesis del Modelo C4

1. **Nivel 1 — Diagrama de Contexto (C1):** Ubica al Módulo RBAC como el motor transversal de seguridad consumido por personal médico, enfermería, técnicos, administración, pacientes y auditores.
2. **Nivel 2 — Diagrama de Contenedores (C2):** Ilustra la interacción entre la SPA Vue 3, la API REST en Laravel 12, el motor de caché Redis 7.2+, la base de datos relacional y el motor de Tenancy.
3. **Nivel 3 — Diagrama de Componentes (C3):** Desglosa la arquitectura interna del contenedor backend: Router, Middleware Pipeline, Permission Guards, Domain Services, Repositorios e invalidación de caché.

---

### 🎯 Patrones de Diseño Arquitectónicos Adoptados

- **Pipeline Middleware:** Cadena de filtros secuenciales para autenticación JWT, resolución de tenant y verificación de rol/permiso en tiempo de request.
- **Cache-Aside (Lazy Loading):** Resolución de matriz de permisos consultando primero Redis (< 2 ms) y repoblando desde base de datos únicamente ante fallos de caché o modificaciones de rol.
- **Decorator / Custom Directive (`v-can`):** Decoración del árbol de componentes en Vue 3 para ocultar o deshabilitar dinámicamente botones y acciones según la matriz reactiva de Pinia.
- **Repository Pattern:** Desacoplamiento estricto de la persistencia de datos respecto a la lógica de negocio.

---

### 🔗 Documentos Relacionados
- [Consolidado de Asignación Semanas 3, 4 y 5](../asignacion-individual-semana-3-5.md)
- [Cronograma Gantt Completo](../gantt-rbac-completo.md)
- [Índice General de Documentación RBAC](../README.md)
