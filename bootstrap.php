<?php

declare(strict_types=1);

/**
 * Bootstrap del Micro-HIS RBAC.
 *
 * Responsabilidades:
 *  1. Registrar el autoloader PSR-4 (sin Composer en entornos sin él).
 *  2. Crear la conexión PDO a SQLite.
 *  3. Inicializar el esquema si la base de datos no existe.
 *  4. Construir el contenedor DI manual (no se usa ningún framework).
 *
 * Decisión: se usa SQLite para que el proyecto sea 100 % autocontenido.
 * Para producción, basta cambiar el DSN y las credenciales en .env.
 */

// ---------------------------------------------------------------------------
// 1. Autoloader PSR-4 manual
// ---------------------------------------------------------------------------
spl_autoload_register(function (string $class): void {
    // Prefijo del namespace raíz
    $prefix    = 'MicroHIS\\';
    $baseDir   = __DIR__ . '/src/';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// ---------------------------------------------------------------------------
// 2. Conexión PDO a SQLite (autocontenida, sin servidor)
// ---------------------------------------------------------------------------
$dbPath = __DIR__ . '/database/rbac_his.sqlite';
$isNew  = !file_exists($dbPath);

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

// Activar claves foráneas en SQLite (desactivadas por defecto)
$pdo->exec('PRAGMA foreign_keys = ON');

// ---------------------------------------------------------------------------
// 3. Inicializar esquema y seed si la base de datos es nueva
// ---------------------------------------------------------------------------
if ($isNew) {
    $schema = file_get_contents(__DIR__ . '/database/schema.sql');
    $seed   = file_get_contents(__DIR__ . '/database/seed.sql');
    $pdo->exec($schema);
    $pdo->exec($seed);
}

// ---------------------------------------------------------------------------
// 4. Contenedor DI manual — inversión de dependencias sin framework
// ---------------------------------------------------------------------------
use MicroHIS\Application\UseCase\AssignRoleUseCase;
use MicroHIS\Application\UseCase\AuthorizeOperationUseCase;
use MicroHIS\Persistence\PdoAuditLogRepository;
use MicroHIS\Persistence\PdoRoleRepository;
use MicroHIS\Persistence\PdoUserRepository;

$userRepository     = new PdoUserRepository($pdo);
$roleRepository     = new PdoRoleRepository($pdo);
$auditLogRepository = new PdoAuditLogRepository($pdo);

$assignRoleUseCase        = new AssignRoleUseCase($userRepository, $roleRepository);
$authorizeOperationUseCase = new AuthorizeOperationUseCase($userRepository, $auditLogRepository);

// Exponer para el script de presentación
return [
    'pdo'                       => $pdo,
    'assignRoleUseCase'         => $assignRoleUseCase,
    'authorizeOperationUseCase' => $authorizeOperationUseCase,
];
