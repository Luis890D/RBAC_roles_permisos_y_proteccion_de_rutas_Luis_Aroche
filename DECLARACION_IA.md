# DECLARACION_IA.md — Uso Transparente de Inteligencia Artificial

**Proyecto:** Micro-HIS RBAC: roles, permisos y protección de rutas  
**Estudiante:** Luis David Aroche Contreras  
**GitHub:** `Luis890D`  
**Fecha:** 2026-08-21  

---

## 1. Herramienta de IA Utilizada

- **Herramienta:** Antigravity AI (Google DeepMind / Gemini — Claude Sonnet 4.6 Thinking)
- **Interfaz:** Antigravity IDE (par de programación asistida)

---

## 2. Propósito del Uso

La IA fue utilizada como **asistente de escritura de código estructural** para:

- Generar la estructura base de los archivos PHP por capas (Domain, Application, Persistence, Presentation).
- Redactar los stubs de prueba (InMemoryUserRepository, InMemoryRoleRepository, InMemoryAuditLogRepository).
- Generar el esquema SQLite y los datos de seed ficticios.
- Dar formato al `CliRunner.php` y a la documentación en Markdown.

---

## 3. Prompts Relevantes Utilizados

> "Implementa el caso de uso AuthorizeOperationUseCase en PHP 8.2+ vanilla siguiendo arquitectura limpia con capas Domain, Application, Persistence y Presentation. Usa interfaces en el dominio, repositorios PDO con sentencias preparadas y SQLite como motor. Incluye generación de trace_id en denegaciones y persistencia de AuditLog."

> "Crea pruebas PHPUnit para los escenarios: happy path (autorización), regla de dominio (denegación con trace_id) y error de persistencia (RuntimeException del repositorio de auditoría)."

---

## 4. Partes Aceptadas y Partes Modificadas

| Componente | Estado | Decisión del Estudiante |
|---|---|---|
| Arquitectura por capas (plan) | ✅ Aceptado | Revisé que coincida con la consigna y los diagramas UML previos |
| `AuthorizeOperationUseCase.php` | ✅ Aceptado con ajuste | Verifiqué la lógica del trace_id y el orden de las operaciones |
| `PdoUserRepository.php` (JOIN) | ✅ Aceptado con ajuste | Revisé la consulta JOIN para que fuera correcta en SQLite |
| Prueba 2 (`testDomainRule_...`) | ✅ Aceptado | Revisé que el re-throw sea correcto para PHPUnit |
| Prueba 3 (error persistencia) | ✅ Aceptado | Verifiqué que el mensaje de excepción coincida con el stub |
| Schema SQL | ✅ Aceptado | Revisé las restricciones CHECK y los PRAGMA de SQLite |
| Seed data | ✅ Aceptado | Confirmé que todos los datos son ficticios |
| `bootstrap.php` | ✅ Aceptado con ajuste | Revisé el autoloader PSR-4 manual y el orden de inicialización |
| `CliRunner.php` | ✅ Aceptado | Revisé la salida coloreada y el doble `require` (corregido) |

---

## 5. Validación Humana Realizada

- **Revisé** que la arquitectura por capas sea coherente con los diagramas UML entregados en la semana anterior.
- **Verifiqué** que el flujo de denegación trazable (trace_id → AuditLog → excepción) sea fiel al diagrama de secuencia (CU-RBAC-03).
- **Confirmé** que ningún dato (usuarios, roles, permisos, IPs) sea real o identificable.
- **Comprobé** que no se usa ningún framework (sin Laravel, Symfony ni similares).
- **Revisé** las sentencias SQL preparadas en los repositorios PDO para prevenir inyección.
- **Validé** que los tests cubran exactamente los tres escenarios requeridos por la consigna: camino feliz, regla de dominio y error de persistencia.

---

## 6. Limitaciones Reconocidas

- El generador de `trace_id` no usa UUID v4 estándar; se usa `random_bytes()` + formato personalizado `TRC-890D-YYYYMMDD-XXXXXX` por legibilidad educativa.
- La cobertura de código no incluye las implementaciones PDO (se priorizan las pruebas unitarias con stubs).
- El `CliRunner.php` usa ANSI colors que pueden no verse en terminales Windows sin configuración de `ANSICON` o `Windows Terminal`.

---

## 7. Declaración Final

> El estudiante **Luis David Aroche Contreras** declara haber utilizado IA como herramienta de asistencia, habiendo revisado, comprendido y validado cada componente generado. El diseño de la arquitectura, la selección de patrones, la alineación con los diagramas UML previos y la decisión de usar SQLite para portabilidad fueron decisiones propias del estudiante. El estudiante está preparado para defender oralmente cualquier parte de este código.
