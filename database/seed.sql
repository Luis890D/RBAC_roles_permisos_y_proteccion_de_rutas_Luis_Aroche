-- =============================================================================
-- Micro-HIS RBAC — Seed de datos ficticios
-- IMPORTANTE: Todos los nombres, roles y datos son COMPLETAMENTE FICTICIOS.
-- No representan ninguna persona real ni datos clínicos identificables.
-- =============================================================================

-- Usuarios ficticios de demostración
INSERT OR IGNORE INTO users (id, username) VALUES
    (1, 'dr.garcia'),        -- Médico ficticio
    (2, 'enf.ramirez'),      -- Enfermera ficticia
    (3, 'lab.mendez'),       -- Técnico de laboratorio ficticio
    (4, 'admin.torres');     -- Administrador IT ficticio

-- Roles del sistema RBAC ficticio
INSERT OR IGNORE INTO roles (id, name) VALUES
    (1, 'medico'),
    (2, 'enfermera'),
    (3, 'laboratorio'),
    (4, 'admin_it');

-- Permisos atómicos (notación dominio.recurso.accion)
INSERT OR IGNORE INTO permissions (id, name) VALUES
    (1,  'emr.soap.write'),          -- Escribir nota SOAP en HCE
    (2,  'emr.soap.read'),           -- Leer notas SOAP
    (3,  'emr.prescription.write'),  -- Emitir prescripción
    (4,  'emr.prescription.read'),   -- Leer prescripciones
    (5,  'lab.result.write'),        -- Registrar resultado de laboratorio
    (6,  'lab.result.read'),         -- Leer resultados de laboratorio
    (7,  'admin.user.create'),       -- Crear usuarios
    (8,  'admin.user.delete'),       -- Eliminar usuarios (permiso sensible)
    (9,  'admin.role.assign'),       -- Asignar roles
    (10, 'vitals.read'),             -- Leer signos vitales
    (11, 'vitals.write');            -- Registrar signos vitales

-- Asignación de permisos a roles
-- Rol: medico
INSERT OR IGNORE INTO role_permissions (role_id, permission_id) VALUES
    (1, 1),   -- emr.soap.write
    (1, 2),   -- emr.soap.read
    (1, 3),   -- emr.prescription.write
    (1, 4),   -- emr.prescription.read
    (1, 6),   -- lab.result.read
    (1, 10),  -- vitals.read
    (1, 11);  -- vitals.write

-- Rol: enfermera
INSERT OR IGNORE INTO role_permissions (role_id, permission_id) VALUES
    (2, 2),   -- emr.soap.read
    (2, 4),   -- emr.prescription.read
    (2, 6),   -- lab.result.read
    (2, 10),  -- vitals.read
    (2, 11);  -- vitals.write

-- Rol: laboratorio
INSERT OR IGNORE INTO role_permissions (role_id, permission_id) VALUES
    (3, 5),   -- lab.result.write
    (3, 6);   -- lab.result.read

-- Rol: admin_it (gestión del sistema, NO acceso clínico)
INSERT OR IGNORE INTO role_permissions (role_id, permission_id) VALUES
    (4, 7),   -- admin.user.create
    (4, 8),   -- admin.user.delete
    (4, 9);   -- admin.role.assign

-- Asignación de roles a usuarios ficticios
INSERT OR IGNORE INTO user_roles (user_id, role_id) VALUES
    (1, 1),   -- dr.garcia -> medico
    (2, 2),   -- enf.ramirez -> enfermera
    (3, 3),   -- lab.mendez -> laboratorio
    (4, 4);   -- admin.torres -> admin_it
