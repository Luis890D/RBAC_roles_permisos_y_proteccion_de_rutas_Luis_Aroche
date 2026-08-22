# 🚀 Guía de Instalación y Ejecución — Micro-HIS RBAC

**Proyecto:** Micro-HIS RBAC: roles, permisos y protección de rutas  
**PHP:** 8.2+ vanilla (sin framework)  
**BD:** SQLite 3 (autocontenida, sin servidor)  
**Estudiante:** Luis David Aroche Contreras — `Luis890D`

---

## 📋 Requisitos del Sistema

| Herramienta | Versión Mínima | Estado en tu máquina |
|---|---|---|
| PHP | 8.2+ | ✅ PHP 8.2.12 (XAMPP) |
| Extensión `pdo_sqlite` | cualquiera | ✅ Incluida |
| Extensión `pdo` | cualquiera | ✅ Incluida |
| Composer | 2.x | ✅ Instalado |
| Git | 2.x | ✅ Instalado |

> [!NOTE]
> **No se necesita MySQL, Apache ni ningún servidor web.** El proyecto corre completamente desde la línea de comandos con PHP CLI y SQLite.

---

## 📥 Paso 1 — Clonar el Repositorio

```bash
git clone https://github.com/Luis890D/RBAC_roles_permisos_y_proteccion_de_rutas_Luis_Aroche.git
cd RBAC_roles_permisos_y_proteccion_de_rutas_Luis_Aroche
```

> Si ya tienes el repositorio local, solo ejecuta:
> ```bash
> git pull origin main
> ```

---

## 📦 Paso 2 — Instalar Dependencias (solo PHPUnit para pruebas)

```bash
composer install
```

Esto instala **únicamente PHPUnit** como dependencia de desarrollo.  
El código de producción (`src/`) no tiene ninguna dependencia externa.

> [!TIP]
> Si `composer` no está en tu PATH, usa la ruta completa:  
> `C:\xampp\php\composer.phar install`

---

## ⚙️ Paso 3 — Configuración (Opcional)

El proyecto está listo para ejecutar sin ninguna configuración adicional.  
Si quieres revisar/ajustar la config de entorno:

```bash
# En Windows:
copy .env.example .env

# En Linux/macOS:
cp .env.example .env
```

El archivo `.env.example` contiene:
```ini
DB_CONNECTION=sqlite
DB_PATH=database/rbac_his.sqlite
```

> [!NOTE]
> La base de datos SQLite (`database/rbac_his.sqlite`) **se crea automáticamente** la primera vez que ejecutes el proyecto. No necesitas importar nada manualmente.

---

## ▶️ Paso 4 — Ejecutar la Demostración CLI

```bash
php src/Presentation/CliRunner.php
```

**Salida esperada:**

```
════════════════════════════════════════════════════════════
   Micro-HIS RBAC — Demostración de Flujos
   Estudiante: Luis David Aroche Contreras (Luis890D)
════════════════════════════════════════════════════════════

── Escenario 1: Asignación de Rol ──────────────────────────
[ASSIGN OK]  Rol 'medico' (id=1) asignado a 'dr.garcia' (id=1)
            → INSERT OR IGNORE ejecutado con sentencia preparada PDO

── Escenario 2: Autorización Exitosa (Happy Path) ──────────
[ALLOW  200]  dr.garcia → emr.soap.write
            → HTTP 200 OK | Operación autorizada correctamente.

── Escenario 3: Denegación Trazable ────────────────────────
[DENY   403]  dr.garcia → admin.user.delete
            → HTTP 403 Forbidden
            → trace_id: TRC-890D-YYYYMMDD-XXXXXX
            → Evento persistido en rbac_audit_logs (SQLite)

── Auditoría persistida en base de datos ────────────────────
  ✗ [DENIED] user=1 perm=admin.user.delete ip=192.168.10.55
             trace=TRC-890D-YYYYMMDD-XXXXXX at=YYYY-MM-DD HH:MM:SS

════════════════════════════════════════════════════════════
  Demostración completada exitosamente.
════════════════════════════════════════════════════════════
```

### ¿Qué ocurre internamente?

```
CliRunner
  │
  ├─► AssignRoleUseCase ──► PdoUserRepository  ──► SQLite (user_roles)
  │                   └──► PdoRoleRepository   ──► SQLite (roles)
  │
  ├─► AuthorizeOperationUseCase
  │       │
  │       ├─► PdoUserRepository (carga User + Roles + Permisos via JOIN)
  │       │
  │       ├─► [ALLOW] User.isAuthorized() == true  → AuthorizeResponse(granted=true)
  │       │
  │       └─► [DENY]  User.isAuthorized() == false
  │                   → generateTraceId() → TRC-890D-YYYYMMDD-XXXXXX
  │                   → PdoAuditLogRepository.save() → INSERT rbac_audit_logs
  │                   → throw AccessDeniedException(traceId)
  │
  └─► PDO SQLite QUERY: SELECT audit log → mostrar evidencia
```

---

## 🧪 Paso 5 — Ejecutar Pruebas Unitarias (PHPUnit)

```bash
# Windows:
vendor\bin\phpunit tests/ --testdox

# Linux/macOS:
./vendor/bin/phpunit tests/ --testdox
```

**Resultado esperado:**

```
PHPUnit 11.5.56 by Sebastian Bergmann and contributors.

Assign Role (MicroHIS\Tests\Unit\AssignRole)
 ✔ HappyPath assignExistingRoleToUser noExceptionThrown
 ✔ DomainRule roleDoesNotExist throwsRoleNotFoundException
 ✔ UserDoesNotExist throwsUserNotFoundException
 ✔ PersistenceError assignRoleFails throwsRuntimeException

Authorize Operation (MicroHIS\Tests\Unit\AuthorizeOperation)
 ✔ HappyPath userWithPermission returnsGranted
 ✔ DomainRule userWithoutPermission throwsAccessDeniedWithTraceId
 ✔ PersistenceError auditLogFails throwsRuntimeException
 ✔ UserNotFound throwsUserNotFoundException

Tests: 8, Assertions: 19
```

> [!IMPORTANT]
> Las pruebas **no tocan la base de datos real**. Usan stubs en memoria (`tests/Stub/`). Puedes ejecutarlas sin tener SQLite configurado.

---

## 🔄 Resetear la Base de Datos

Si quieres empezar desde cero (limpiar los registros de auditoría):

```bash
# Windows:
del database\rbac_his.sqlite

# Linux/macOS:
rm database/rbac_his.sqlite
```

La próxima ejecución del CLI recreará el schema y el seed automáticamente.

---

## 🗄️ Inspeccionar la Base de Datos (Opcional)

Si tienes **SQLite3 CLI** instalado:

```bash
sqlite3 database/rbac_his.sqlite

# Dentro de sqlite3:
.tables
SELECT * FROM users;
SELECT * FROM roles;
SELECT * FROM permissions;
SELECT * FROM role_permissions;
SELECT * FROM user_roles;
SELECT * FROM rbac_audit_logs;
.quit
```

También puedes usar **DB Browser for SQLite** (gratis): https://sqlitebrowser.org/  
Abre el archivo `database/rbac_his.sqlite`.

---

## 🌿 Estructura de Ramas y Tags

```bash
git tag -n          # Ver todas las etiquetas con descripción
git show v1.1.0     # Ver detalles de la entrega evaluada
git log --oneline   # Historial de commits
```

| Tag | Descripción |
|---|---|
| `v1.0.0` | Entrega UML (diagramas de casos de uso, actividad, secuencia) |
| `v1.1.0` | Código PHP 8.2 ejecutable + 8 pruebas PHPUnit |

---

## ❓ Solución de Problemas Comunes

### ⚠️ "Module openssl is already loaded"
**No es un error.** Es un warning de XAMPP (doble carga del módulo en php.ini). El código funciona correctamente.

### ⚠️ "Xdebug: Time-out connecting to debugging client"
**No es un error.** Xdebug intenta conectar al depurador de tu IDE. Para silenciarlo:
```bash
# Ejecutar sin Xdebug:
php -d xdebug.mode=off src/Presentation/CliRunner.php
vendor\bin\phpunit tests/ --testdox  # PHPUnit lo maneja automáticamente
```

### ❌ "Class not found"
Asegúrate de ejecutar desde el directorio raíz del proyecto (donde está `bootstrap.php`):
```bash
cd "d:\U 2024 DAVID\U David 2026\Octavo Semestre\ANÁLISIS DE SISTEMAS II\Tarea UML"
php src/Presentation/CliRunner.php
```

### ❌ "no such table: users"
La BD está corrupta o vacía. Bórrala y re-ejecuta:
```bash
del database\rbac_his.sqlite
php src/Presentation/CliRunner.php
```

### ❌ "composer: command not found"
Usa la ruta completa de XAMPP:
```bash
C:\xampp\php\php.exe C:\xampp\php\composer.phar install
```

---

## 📤 Publicar en GitHub

```bash
git remote add origin https://github.com/Luis890D/RBAC_roles_permisos_y_proteccion_de_rutas_Luis_Aroche.git
git branch -M main
git push -u origin main --tags
```

---

## ✅ Lista de Verificación de Ejecución

- [ ] `composer install` ejecutado sin errores
- [ ] `php src/Presentation/CliRunner.php` muestra los 3 escenarios
- [ ] `[ASSIGN OK]` aparece en Escenario 1
- [ ] `[ALLOW  200]` aparece en Escenario 2
- [ ] `[DENY   403]` con `trace_id` aparece en Escenario 3
- [ ] Tabla de auditoría muestra el registro `DENIED`
- [ ] `vendor\bin\phpunit tests/ --testdox` → 8/8 pruebas verdes
