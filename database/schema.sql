-- =============================================================================
-- Micro-HIS RBAC — Schema SQLite
-- Datos: exclusivamente ficticios, sin información clínica identificable.
-- Compatibilidad: SQLite 3.x (sin servidor, autocontenido).
-- =============================================================================

PRAGMA foreign_keys = ON;

-- -----------------------------------------------------------------------------
-- Tabla: users
-- Usuarios ficticios del sistema hospitalario de demostración.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id       INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT    NOT NULL UNIQUE,
    created_at TEXT  NOT NULL DEFAULT (datetime('now'))
);

-- -----------------------------------------------------------------------------
-- Tabla: roles
-- Roles disponibles en el sistema RBAC ficticio.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS roles (
    id   INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT    NOT NULL UNIQUE
);

-- -----------------------------------------------------------------------------
-- Tabla: permissions
-- Permisos atómicos del sistema, con nombres en notación dominio.operacion.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS permissions (
    id   INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT    NOT NULL UNIQUE
);

-- -----------------------------------------------------------------------------
-- Tabla: role_permissions  (N:M entre roles y permisos)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS role_permissions (
    role_id       INTEGER NOT NULL REFERENCES roles(id)       ON DELETE CASCADE,
    permission_id INTEGER NOT NULL REFERENCES permissions(id) ON DELETE CASCADE,
    PRIMARY KEY (role_id, permission_id)
);

-- -----------------------------------------------------------------------------
-- Tabla: user_roles  (N:M entre usuarios y roles)
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS user_roles (
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    role_id INTEGER NOT NULL REFERENCES roles(id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, role_id)
);

-- -----------------------------------------------------------------------------
-- Tabla: rbac_audit_logs
-- Registro trazable de cada evento de autorización/denegación.
-- trace_id: identificador único de denegación para soporte y auditoría.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS rbac_audit_logs (
    id          INTEGER PRIMARY KEY AUTOINCREMENT,
    trace_id    TEXT    NOT NULL UNIQUE,
    user_id     INTEGER NOT NULL REFERENCES users(id),
    permission  TEXT    NOT NULL,
    decision    TEXT    NOT NULL CHECK(decision IN ('GRANTED', 'DENIED')),
    ip          TEXT    NOT NULL DEFAULT '127.0.0.1',
    occurred_at TEXT    NOT NULL DEFAULT (datetime('now'))
);

-- Índice para consultas de auditoría por usuario
CREATE INDEX IF NOT EXISTS idx_audit_user_id ON rbac_audit_logs(user_id);
