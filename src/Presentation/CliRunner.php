<?php

declare(strict_types=1);

/**
 * CliRunner — Punto de entrada de demostración del Micro-HIS RBAC.
 *
 * Ejecuta los 3 escenarios requeridos por la consigna:
 *   Escenario 1: Asignación de rol (CU-RBAC-01)
 *   Escenario 2: Autorización exitosa / camino feliz (CU-RBAC-02)
 *   Escenario 3: Denegación trazable con trace_id (CU-RBAC-03)
 *
 * Uso:
 *   php src/Presentation/CliRunner.php
 *
 * Todos los datos son ficticios; no hay información clínica real.
 */

require_once __DIR__ . '/../../bootstrap.php';

use MicroHIS\Application\DTO\AssignRoleRequest;
use MicroHIS\Application\DTO\AuthorizeRequest;
use MicroHIS\Domain\Exception\AccessDeniedException;
use MicroHIS\Domain\Exception\RoleNotFoundException;

// Cargar contenedor DI desde bootstrap
$container = require __DIR__ . '/../../bootstrap.php';

$assignRoleUseCase        = $container['assignRoleUseCase'];
$authorizeOperationUseCase = $container['authorizeOperationUseCase'];

// Colores ANSI para terminal
$GREEN  = "\033[32m";
$RED    = "\033[31m";
$YELLOW = "\033[33m";
$CYAN   = "\033[36m";
$BOLD   = "\033[1m";
$RESET  = "\033[0m";

echo "\n";
echo "{$BOLD}{$CYAN}════════════════════════════════════════════════════════════{$RESET}\n";
echo "{$BOLD}{$CYAN}   Micro-HIS RBAC — Demostración de Flujos                {$RESET}\n";
echo "{$BOLD}{$CYAN}   Estudiante: Luis David Aroche Contreras (Luis890D)     {$RESET}\n";
echo "{$BOLD}{$CYAN}════════════════════════════════════════════════════════════{$RESET}\n";
echo "\n";

// =============================================================================
// ESCENARIO 1: Asignación de Rol (CU-RBAC-01)
// Asignar rol 'medico' (id=1) al usuario dr.garcia (id=1)
// El seed ya lo tiene asignado, así que se ejercerá el INSERT OR IGNORE.
// =============================================================================
echo "{$BOLD}── Escenario 1: Asignación de Rol ──────────────────────────{$RESET}\n";
try {
    // Asignar rol 'laboratorio' (id=3) al médico ficticio dr.garcia para demostración
    $request = new AssignRoleRequest(userId: 1, roleId: 1);
    $assignRoleUseCase->execute($request);
    echo "{$GREEN}[ASSIGN OK]{$RESET}  Rol 'medico' (id=1) asignado a 'dr.garcia' (id=1)\n";
    echo "            → INSERT OR IGNORE ejecutado con sentencia preparada PDO\n\n";
} catch (RoleNotFoundException $e) {
    echo "{$RED}[ASSIGN ERR]{$RESET} {$e->getMessage()}\n\n";
}

// =============================================================================
// ESCENARIO 2: Autorización exitosa — Camino feliz (CU-RBAC-02)
// dr.garcia tiene rol 'medico' que incluye permiso 'emr.soap.write'
// =============================================================================
echo "{$BOLD}── Escenario 2: Autorización Exitosa (Happy Path) ──────────{$RESET}\n";
try {
    $request  = new AuthorizeRequest(userId: 1, permission: 'emr.soap.write', ip: '192.168.10.55');
    $response = $authorizeOperationUseCase->execute($request);
    echo "{$GREEN}[ALLOW  200]{$RESET}  dr.garcia → emr.soap.write\n";
    echo "            → HTTP 200 OK | {$response->message}\n\n";
} catch (AccessDeniedException $e) {
    echo "{$RED}[DENY   403]{$RESET} {$e->getMessage()}\n\n";
}

// =============================================================================
// ESCENARIO 3: Denegación trazable (CU-RBAC-03)
// dr.garcia NO tiene el permiso 'admin.user.delete' → 403 + trace_id
// =============================================================================
echo "{$BOLD}── Escenario 3: Denegación Trazable ────────────────────────{$RESET}\n";
try {
    $request  = new AuthorizeRequest(userId: 1, permission: 'admin.user.delete', ip: '192.168.10.55');
    $authorizeOperationUseCase->execute($request);
} catch (AccessDeniedException $e) {
    echo "{$RED}[DENY   403]{$RESET}  dr.garcia → admin.user.delete\n";
    echo "            → HTTP 403 Forbidden\n";
    echo "            → {$YELLOW}trace_id: {$e->getTraceId()}{$RESET}\n";
    echo "            → Evento persistido en rbac_audit_logs (SQLite)\n\n";
}

// =============================================================================
// VERIFICACIÓN: Mostrar el log de auditoría del usuario 1
// =============================================================================
echo "{$BOLD}── Auditoría persistida en base de datos ────────────────────{$RESET}\n";
$pdo = $container['pdo'];
$stmt = $pdo->query(
    'SELECT trace_id, user_id, permission, decision, ip, occurred_at
     FROM rbac_audit_logs ORDER BY occurred_at DESC LIMIT 5'
);
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($logs)) {
    echo "  (Sin registros en rbac_audit_logs)\n";
} else {
    foreach ($logs as $row) {
        $icon = $row['decision'] === 'GRANTED' ? $GREEN . '✓' : $RED . '✗';
        echo "  {$icon}{$RESET} [{$row['decision']}]"
           . " user={$row['user_id']}"
           . " perm={$row['permission']}"
           . " ip={$row['ip']}"
           . " trace={$YELLOW}{$row['trace_id']}{$RESET}"
           . " at={$row['occurred_at']}\n";
    }
}

echo "\n{$BOLD}{$CYAN}════════════════════════════════════════════════════════════{$RESET}\n";
echo "{$BOLD}  Demostración completada exitosamente.{$RESET}\n";
echo "{$BOLD}{$CYAN}════════════════════════════════════════════════════════════{$RESET}\n\n";
