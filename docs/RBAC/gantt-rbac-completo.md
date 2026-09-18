# Diagrama de Gantt - Modulo RBAC (Semanas 1 a 18)
## Roles, Permisos y Proteccion de Rutas - Sistema Hospitalario Integrado (HIS)

**Curso:** Analisis de Sistemas II (ASII) — Ciclo 2026  
**Estudiante:** Luis David Aroche Contreras (`Luis890D`)  
**Modulo:** 02 — Control de Acceso Basado en Roles (RBAC)  
**Rama:** `feature/asii-02-rbac-roles-permisos-y-proteccion-de-rutas-luis890d`  

---

## Gantt General — Ciclo Completo ASII (Semanas 1 a 18)

```mermaid
gantt
    title Gantt General - Modulo RBAC ASII 2026 (Semanas 1 a 18)
    dateFormat  YYYY-MM-DD
    axisFormat  %d %b

    section Parcial 1 UML y Arquitectura S1 a S5
    S1 Actores Alcance y Casos de Uso UML        :done,    s1,  2026-08-01, 2026-08-07
    S2 RF y RNF Criterios BDD Gherkin y SOLID    :done,    s2,  2026-08-08, 2026-08-14
    S3 Arquitectura C4 Vistas y Patrones         :done,    s3,  2026-08-15, 2026-08-21
    S4 Capas Repositorio DTOs y Enums            :done,    s4,  2026-08-22, 2026-08-28
    S5 Contrato API REST Errores RFC 7807 y Git  :active,  s5,  2026-08-29, 2026-09-04

    section Hito Parcial 1
    Parcial 1 Defensa Conceptual y Arquitectura  :crit,    p1,  2026-09-05, 2026-09-11

    section Parcial 2 Componentes y UX S7 a S11
    S7 Diseno de Componentes y Refactorizacion   :         s7,  2026-09-12, 2026-09-18
    S8 Diseno de Experiencia de Usuario UX       :         s8,  2026-09-19, 2026-09-25
    S9 Usabilidad Accesibilidad y Checklist      :         s9,  2026-09-26, 2026-10-02
    S10 Diseno Responsive y Movilidad            :         s10, 2026-10-03, 2026-10-09
    S11 Mockups y Prototipo Navegable            :         s11, 2026-10-10, 2026-10-16

    section Hito Parcial 2
    Parcial 2 Defensa Componentes UX Movilidad   :crit,    p2,  2026-10-17, 2026-10-23

    section Evaluacion Final SQA y Seguridad S13 a S17
    S13 Plan de Revision Tecnica Formal SQA      :         s13, 2026-10-24, 2026-10-30
    S14 Plan SQA Metricas y Estrategia Control   :         s14, 2026-10-31, 2026-11-06
    S15 Pruebas Unitarias Caja Blanca y Negra    :         s15, 2026-11-07, 2026-11-13
    S16 Integracion Validacion y Entrega         :         s16, 2026-11-14, 2026-11-20
    S17 Modelado de Amenazas y Pruebas Seguridad :         s17, 2026-11-21, 2026-11-27

    section Hito Evaluacion Final
    S18 Demo PR Integrado y Defensa Final        :crit,    p3,  2026-11-28, 2026-12-04
```

---

## Gantt Detallado — Parcial 1 (Semanas 1 a 5)

```mermaid
gantt
    title Detalle Parcial 1 - Modulo RBAC (Semanas 1 a 5)
    dateFormat  YYYY-MM-DD
    axisFormat  %d %b

    section Semana 1 Analisis y Dominio
    Identificar actores humanos y de software          :done, s1a, 2026-08-01, 1d
    Delimitar alcance In Scope y Out of Scope          :done, s1b, 2026-08-01, 1d
    Elaborar casos de uso CU-RBAC-01 al 06            :done, s1c, 2026-08-02, 3d
    Redactar narrativa del diagrama UML                :done, s1d, 2026-08-05, 2d
    Entregable semana-01-actores-alcance-casos-uso     :done, s1e, 2026-08-07, 1d

    section Semana 2 Requerimientos y SOLID
    Redactar RF-RBAC-01 al RF-RBAC-07                 :done, s2a, 2026-08-08, 2d
    Redactar RNF-RBAC-01 al RNF-RBAC-05               :done, s2b, 2026-08-10, 2d
    Criterios de Aceptacion Gherkin BDD                :done, s2c, 2026-08-11, 2d
    Aplicar principios SOLID SRP OCP LSP ISP DIP       :done, s2d, 2026-08-12, 2d
    Entregable semana-02-rf-rnf-criterios-solid        :done, s2e, 2026-08-14, 1d

    section Semana 3 Arquitectura y C4
    Modelo C4 Nivel 1 Contexto del Sistema             :done, s3a, 2026-08-15, 1d
    Modelo C4 Nivel 2 Contenedores SPA API Redis       :done, s3b, 2026-08-16, 2d
    Modelo C4 Nivel 3 Componentes y Middlewares        :done, s3c, 2026-08-18, 2d
    Mapa de dependencias con modulos HIS M01 a M22     :done, s3d, 2026-08-19, 2d
    Patrones RBAC Core Pipeline Cache-Aside Repo       :done, s3e, 2026-08-20, 1d
    Entregable semana-03-arquitectura-vistas-patrones  :done, s3f, 2026-08-21, 1d

    section Semana 4 Capas y Repositorio
    Capa UI Vue 3 Pinia Guards Directiva v-can         :done, s4a, 2026-08-22, 1d
    Capa API Controllers FormRequests Resources        :done, s4b, 2026-08-23, 1d
    Capa Dominio Services RbacAuthorizationService     :done, s4c, 2026-08-23, 2d
    Capa Repositorio Interfaces y Adaptadores          :done, s4d, 2026-08-24, 2d
    Capa Infraestructura MySQL PostgreSQL Redis         :done, s4e, 2026-08-25, 1d
    DTOs CreateRoleDTO UpdateRolePermissionsDTO        :done, s4f, 2026-08-25, 2d
    Enums SystemRole y PermissionModule PHP 8.2        :done, s4g, 2026-08-26, 1d
    Diagrama de Secuencia UI Middleware DB Audit        :done, s4h, 2026-08-27, 1d
    Entregable semana-04-arquitectura-capas-repo       :done, s4i, 2026-08-28, 1d

    section Semana 5 API REST y Git Integration
    Especificar cabeceras HTTP JWT y Tenant            :active, s5a, 2026-08-29, 1d
    Endpoints CRUD de Roles api-v1-rbac-roles          :active, s5b, 2026-08-29, 2d
    Endpoint Catalogo Permisos y Asignacion Usuario    :active, s5c, 2026-08-30, 2d
    Endpoint Perfil Permisos auth-me-permissions       :active, s5d, 2026-08-31, 1d
    Payloads JSON Request y Response 200 201           :active, s5e, 2026-08-31, 2d
    Catalogo Errores RFC 7807 403 401 422              :active, s5f, 2026-09-01, 1d
    Plan Git Worktree GitFlow DoD y PR Template        :active, s5g, 2026-09-02, 2d
    Entregable semana-05-api-rest-contrato             :active, s5h, 2026-09-04, 1d

    section Hito Parcial 1
    Preparacion defensa conceptual y practica          :crit, p1a, 2026-09-05, 3d
    Evaluacion Parcial 1 Semana 6                      :crit, p1b, 2026-09-08, 3d
```

---

## Gantt Detallado — Parcial 2 (Semanas 7 a 11)

```mermaid
gantt
    title Detalle Parcial 2 - Modulo RBAC (Semanas 7 a 11)
    dateFormat  YYYY-MM-DD
    axisFormat  %d %b

    section Semana 7 Diseno de Componentes
    Disenar componentes backend Services Repos         :  s7a, 2026-09-12, 2d
    Disenar componentes frontend rbacStore v-can       :  s7b, 2026-09-14, 2d
    Propuesta refactorizacion reducir acoplamiento     :  s7c, 2026-09-15, 2d
    Diagrama antes y despues de componentes            :  s7d, 2026-09-16, 2d
    Entregable semana-07-componentes-refactorizacion   :  s7e, 2026-09-18, 1d

    section Semana 8 Diseno UX
    Flujo UX por rol Admin Medico Recepcionista        :  s8a, 2026-09-19, 2d
    Disenar estados carga error vacio exito            :  s8b, 2026-09-21, 2d
    Wireframes iniciales de vista RolesManagement      :  s8c, 2026-09-22, 2d
    Reglas de interaccion y permisos de botones        :  s8d, 2026-09-23, 1d
    Entregable semana-08-ux-user-flow                  :  s8e, 2026-09-25, 1d

    section Semana 9 Usabilidad y Accesibilidad
    Checklist de usabilidad WCAG 2.1 y SUS             :  s9a, 2026-09-26, 2d
    Evaluacion de flujo con criterios accesibilidad    :  s9b, 2026-09-28, 2d
    Priorizar mejoras identificadas                    :  s9c, 2026-09-29, 2d
    Entregable semana-09-usabilidad-accesibilidad      :  s9d, 2026-10-02, 1d

    section Semana 10 Diseno Responsive y Movil
    Adaptar flujo de roles a resolucion movil          :  s10a, 2026-10-03, 2d
    Escenarios de uso en tablets y smartphones         :  s10b, 2026-10-05, 2d
    Propuesta navegacion adaptada al contexto movil    :  s10c, 2026-10-06, 2d
    Entregable semana-10-responsive-movilidad          :  s10d, 2026-10-09, 1d

    section Semana 11 Prototipo Navegable
    Crear mockup desktop en Figma Canva Excalidraw     :  s11a, 2026-10-10, 3d
    Crear mockup movil con flujo RBAC completo         :  s11b, 2026-10-12, 3d
    Validar prototipo con checklist SUS                :  s11c, 2026-10-14, 2d
    Entregable semana-11-mockups-prototipo             :  s11e, 2026-10-16, 1d

    section Hito Parcial 2
    Preparacion defensa componentes y UX               :crit, p2a, 2026-10-17, 3d
    Evaluacion Parcial 2 Semana 12                     :crit, p2b, 2026-10-20, 3d
```

---

## Gantt Detallado — Evaluacion Final SQA Pruebas y Seguridad (Semanas 13 a 18)

```mermaid
gantt
    title Detalle Evaluacion Final - Modulo RBAC (Semanas 13 a 18)
    dateFormat  YYYY-MM-DD
    axisFormat  %d %b

    section Semana 13 Revision Tecnica Formal
    Definir checklist de revision tecnica del modulo   :  s13a, 2026-10-24, 2d
    Asignar responsables por area del modulo           :  s13b, 2026-10-26, 2d
    Documentar evidencias esperadas por criterio       :  s13c, 2026-10-27, 2d
    Entregable semana-13-revision-tecnica-formal       :  s13d, 2026-10-30, 1d

    section Semana 14 Plan SQA y Metricas
    Definir metricas de calidad cobertura y latencia   :  s14a, 2026-10-31, 2d
    Identificar riesgos tecnicos del modulo RBAC       :  s14b, 2026-11-02, 2d
    Estrategia de control de calidad CI CD y PHPStan   :  s14c, 2026-11-03, 2d
    Entregable semana-14-plan-sqa-metricas             :  s14d, 2026-11-06, 1d

    section Semana 15 Pruebas Unitarias
    Test usuario con permiso accede endpoint HTTP 200  :  s15a, 2026-11-07, 1d
    Test usuario sin permiso recibe 403 Forbidden      :  s15b, 2026-11-08, 1d
    Test aislamiento multi-tenant Tenant A vs Tenant B :  s15c, 2026-11-09, 1d
    Test proteccion SuperAdmin SYSTEM_ROLE_PROTECTED   :  s15d, 2026-11-10, 1d
    Test invalidacion cache Redis tras cambio permisos :  s15e, 2026-11-11, 1d
    Evidencia php artisan test filter RbacTest         :  s15f, 2026-11-12, 1d
    Entregable semana-15-pruebas-unitarias             :  s15g, 2026-11-13, 1d

    section Semana 16 Integracion y Entrega Funcional
    Integrar backend con modulos HIS M01 M03 M22       :  s16a, 2026-11-14, 2d
    Validar flujos E2E Recepcionista hacia admin roles  :  s16b, 2026-11-15, 2d
    Corregir errores detectados en integracion         :  s16c, 2026-11-17, 2d
    Bitacora inicial de despliegue en staging          :  s16d, 2026-11-18, 2d
    PR hacia develop con evidencia completa            :  s16e, 2026-11-19, 1d
    Entregable semana-16-integracion-entrega           :  s16f, 2026-11-20, 1d

    section Semana 17 Seguridad y Amenazas
    Modelar matriz de amenazas RBAC Tenant JWT         :  s17a, 2026-11-21, 2d
    Pruebas seguridad cross-tenant y privilege esc     :  s17b, 2026-11-22, 2d
    Fail-Closed caida de cache denegacion por defecto  :  s17c, 2026-11-24, 1d
    Chaos Testing de permisos en produccion            :  s17d, 2026-11-24, 2d
    Evidencia final de despliegue en produccion        :  s17e, 2026-11-26, 1d
    Entregable semana-17-seguridad-amenazas            :  s17f, 2026-11-27, 1d

    section Hito Evaluacion Final
    Preparacion demo funcional y documentacion final   :crit, p3a, 2026-11-28, 3d
    Defensa individual del Proyecto Modulo RBAC        :crit, p3b, 2026-12-01, 4d
```

---

## Resumen de Bloques Evaluativos

| Bloque | Semanas | Tareas Principales | Puntos | Estado |
|---|:---:|---|:---:|:---:|
| **Parcial 1** | 1-5 | Actores, RF/RNF, C4, Capas, API REST | 6 pts | Completado |
| **Evaluacion Parcial 1** | 6 | Defensa conceptual y practica arquitectonica | Parcial | Pendiente |
| **Parcial 2** | 7-11 | Componentes, UX, Usabilidad, Responsive, Mockups | 7 pts | Planificado |
| **Evaluacion Parcial 2** | 12 | Defensa componentes, UX y movilidad | Parcial | Pendiente |
| **Evaluacion Final** | 13-17 | SQA, Pruebas, Integracion, Seguridad, Despliegue | 7 pts | Planificado |
| **Proyecto Individual** | 1-18 | Modulo RBAC completo implementado | 15 pts | En curso |
| **Defensa Final** | 18 | Demo, PR integrado a develop y defensa individual | Final | Pendiente |

---

## Hitos Criticos del Proyecto

| Fecha | Hito | Entregables Clave |
|:---:|---|---|
| **2026-09-04** | Fin Semana 5 | Contrato API REST completo, Plan Git/Worktree |
| **2026-09-11** | PARCIAL 1 | Defensa UML y Arquitectura, Semanas 1-5 validadas |
| **2026-10-16** | Fin Semana 11 | Prototipo navegable, Mockups desktop y movil |
| **2026-10-23** | PARCIAL 2 | Defensa Componentes y UX, Semanas 7-11 validadas |
| **2026-11-20** | Semana 16 | Primera entrega funcional, PR integrado en develop |
| **2026-11-27** | Semana 17 | Pruebas de seguridad, Despliegue final verificado |
| **2026-12-04** | EVALUACION FINAL | Demo funcional completa, Defensa individual Modulo RBAC |

---

## Trazabilidad de Documentos por Semana

| Semana | Entregable | Carpeta / Archivo | Estado |
|:---:|---|---|:---:|
| **S1** | Actores, Alcance y Casos de Uso UML | [`semana-01/semana-01-actores-alcance-casos-de-uso.md`](semana-01/semana-01-actores-alcance-casos-de-uso.md) | ✅ Completado |
| **S2** | RF/RNF, Criterios BDD Gherkin y SOLID | [`semana-02/semana-02-rf-rnf-criterios-aceptacion-solid.md`](semana-02/semana-02-rf-rnf-criterios-aceptacion-solid.md) | ✅ Completado |
| **S3** | Arquitectura C4, Vistas y Patrones | [`semana-03/semana-03-arquitectura-vistas-patrones.md`](semana-03/semana-03-arquitectura-vistas-patrones.md) | ✅ Completado |
| **S4** | Capas, Repositorio, DTOs y Enums | [`semana-04/semana-04-arquitectura-capas-repositorio.md`](semana-04/semana-04-arquitectura-capas-repositorio.md) | ✅ Completado |
| **S5** | Contrato API REST y Plan Git/Worktree | [`semana-05/semana-05-api-rest-contrato-integracion.md`](semana-05/semana-05-api-rest-contrato-integracion.md) | ✅ Completado |
| **S6** | Evaluación Parcial 1 | [`semana-06/README.md`](semana-06/README.md) | ⏳ Pendiente |
| **S7** | Diseño Componentes y Refactorización | [`semana-07/README.md`](semana-07/README.md) | ⏳ Planificado |
| **S8** | Flujo UX por Rol y Wireframes | [`semana-08/README.md`](semana-08/README.md) | ⏳ Planificado |
| **S9** | Usabilidad, Accesibilidad y Checklist | [`semana-09/README.md`](semana-09/README.md) | ⏳ Planificado |
| **S10** | Diseño Responsive y Movilidad | [`semana-10/README.md`](semana-10/README.md) | ⏳ Planificado |
| **S11** | Mockup y Prototipo Navegable | [`semana-11/README.md`](semana-11/README.md) | ⏳ Planificado |
| **S12** | Evaluación Parcial 2 | [`semana-12/README.md`](semana-12/README.md) | ⏳ Pendiente |
| **S13** | Plan de Revisión Técnica Formal | [`semana-13/README.md`](semana-13/README.md) | ⏳ Planificado |
| **S14** | Plan SQA, Métricas y Riesgos | [`semana-14/README.md`](semana-14/README.md) | ⏳ Planificado |
| **S15** | Pruebas Unitarias y Casos de Prueba | [`semana-15/README.md`](semana-15/README.md) | ⏳ Planificado |
| **S16** | Integración, Validación y Entrega Funcional | [`semana-16/README.md`](semana-16/README.md) | ⏳ Planificado |
| **S17** | Modelado de Amenazas y Pruebas de Seguridad | [`semana-17/README.md`](semana-17/README.md) | ⏳ Planificado |
| **S18** | Demo Final, PR y Defensa | [`semana-18/README.md`](semana-18/README.md) | ⏳ Pendiente |

---

## Leyenda de Estados

| Simbolo | Estado |
|:---:|---|
| ✅ | Completado y documentado |
| 🔄 | Activo / En ejecucion |
| ⏳ | Planificado / Pendiente |

---

*Documento del Modulo 02 — RBAC — Sistema Hospitalario Integrado (HIS)*  
*Analisis de Sistemas II — Ciclo 2026 — Luis David Aroche Contreras (Luis890D)*
