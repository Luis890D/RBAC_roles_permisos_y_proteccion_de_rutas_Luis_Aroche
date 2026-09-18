# Declaración Transparente de Uso de IA, Registro de Prompts y Evidencia Git

**Curso:** Análisis de Sistemas II (ASII) — 2026  
**Sistema:** Sistema Hospitalario Integrado (HIS)  
**Módulo:** 02 — Control de Acceso Basado en Roles (RBAC)  
**Estudiante:** Luis David Aroche Contreras (`Luis890D`)  
**Repositorio:** `https://github.com/Luis890D/shi-documentacion-rbac`  
**Rama evaluada:** `shi-documentacion-rbac-Luis-Aroche` / `feature/asii-02-rbac-roles-permisos-y-proteccion-de-rutas-luis890d`  
**Commit Evaluado:** `89cdf3e` (Mejora de Diagrama y Cambio de nombre de rama)  

---

## 1. Declaración Transparente de Uso de IA

En cumplimiento con las normas de evaluación académica del curso Análisis de Sistemas II, declaro de forma transparente que para la elaboración de la documentación técnica, diagramado arquitectónico y estructuración del plan de implementación del módulo **RBAC**, se utilizó la herramienta de Inteligencia Artificial Asistente:

- **Herramienta de IA:** Antigravity AI Assistant (Motor Gemini 3.6 Flash / DeepMind).
- **Propósito:** Asistencia en la estructuración de la documentación de requerimientos funcionales/no funcionales, formateo de diagramas de secuencia y flujo en sintaxis Mermaid, verificación de principios SOLID y consolidación del plan técnico de implementación.
- **Grado de Autonomía:** Supervisado y validado en su totalidad por el estudiante. La IA actuó como herramienta de pair-programming y redacción analítica.

---

## 2. Registro de Prompts Relevantes Utilizados

A continuación se detallan los prompts principales enviados a la IA, su propósito y las modificaciones humanas aplicadas:

### Prompt 1: Análisis de Actores, Alcance y Casos de Uso (Semana 1)
> **Prompt:**  
> *"Actúa como un arquitecto de software senior para un Sistema Hospitalario Integrado (HIS) multi-tenant desarrollado en Laravel 12 y Vue 3. Necesito estructurar el documento de Semana 1 para el Módulo 02: RBAC (Roles, Permisos y Protección de Rutas). Incluye caracterización de actores asistenciales y administrativos, tabla de límites de alcance (Scope boundaries), flujo de proceso principal y diagramas de secuencia en sintaxis Mermaid."*

- **Partes Aceptadas:** Estructura general de actores, delimitación del alcance in-scope/out-of-scope.
- **Partes Modificadas/Ajustadas por el Estudiante:** Se ajustó la integración estricta con `stancl/tenancy` para asegurar que el aislamiento de roles fuera por tenant y no global, y se añadieron los actores específicos del catálogo del hospital (Bioquímico, Auditor de Gobernanza).

### Prompt 2: Requerimientos, Criterios de Aceptación y Principios SOLID (Semana 2)
> **Prompt:**  
> *"Genera la tabla de Requerimientos Funcionales (RF-01 al RF-06) y No Funcionales (RNF-01 al RNF-04) con Criterios de Aceptación en formato Gherkin (Given-When-Then) para el módulo RBAC del HIS. Adicionalmente, fundamenta la aplicación del principio de Inversión de Dependencias (DIP) y Segregación de Interfaces (ISP) de SOLID según la referencia técnica de MVPCluster."*

- **Partes Aceptadas:** Formato Gherkin para los criterios de aceptación de RBAC.
- **Partes Modificadas por el Estudiante:** Se especificó explícitamente el uso de `spatie/laravel-permission` acoplado con tokens JWT (`/api/v1/auth/me`) y la directiva personalizada de Vue 3 `v-can`.

### Prompt 3: Consolidación de Evidencia Git y Plan de Implementación
> **Prompt:**  
> *"Ayúdame a revisar la lista de cotejo de evaluación del docente, organizar la evidencia Git con git log --oneline y armar el plan de implementación detallado para el desarrollo backend y frontend del módulo RBAC."*

- **Partes Aceptadas:** Plan estructurado por componentes backend/frontend y verificación automatizada.
- **Validación Humana:** Revisión visual del árbol de archivos, ejecución de comandos en la CLI local de Windows y confirmación de commits.

---

## 3. Matriz de Validación Humana y Responsabilidad

| Componente / Documento | Generado por IA (%) | Revisado/Modificado por Humano (%) | Tipo de Validación Efectuada |
|---|---:|---:|---|
| **Estructuración Markdown** | 60% | 40% | Corrección de formato, rutas absolutas/relativas del repo. |
| **Diagramas Mermaid (UML/Secuencia)** | 50% | 50% | Verificación sintáctica, alineación con casos de uso del HIS. |
| **Definición de Permisos y Roles** | 30% | 70% | Ajuste de permisos específicos del contexto clínico hospitalario. |
| **Pruebas y Comandos Git** | 10% | 90% | Ejecución real en consola PowerShell/Bash y verificación del historial. |

---

## 4. Evidencia Git del Repositorio

### 4.1. Historial de Commits (`git log --oneline`)

```text
89cdf3e Mejora de Diagrama y Cambio de nombre de rama
229dbdb Mejora de Diagrama
e6db19f Tomando dos Puntos de la Semana 1 y 2
406eb81 docs(readme): assign Lis Rosales GitHub user
7084395 docs(readme): refine governance audit module
c2a7eae docs(readme): add missing ASII student assignments
3bc0d41 docs(asii): initialize hospital final project base
4307b39 chore: seed repository
```

### 4.2. Estructura del Árbol de Documentación (`docs/`)

```text
docs/
├── README-INSTALACION-BACKEND.md
├── weekly-plan.md
├── worktree-guide.md
└── RBAC/
    ├── README.md
    ├── gantt-rbac-completo.md
    ├── asignacion-individual-semana-1-2.md
    ├── asignacion-individual-semana-3-5.md
    ├── semana-01/
    ├── semana-02/
    ├── semana-03/
    ├── semana-04/
    ├── semana-05/
    ├── semana-06/
    └── Planificacion/
        ├── declaracion-ia-y-prompts.md
        └── implementation_plan.md
```

---
