<?php

declare(strict_types=1);

namespace MicroHIS\Persistence;

use DateTimeImmutable;
use MicroHIS\Domain\Entity\AuditLog;
use MicroHIS\Domain\Repository\AuditLogRepositoryInterface;
use PDO;
use RuntimeException;

/**
 * Implementación PDO del repositorio de auditoría.
 *
 * Cada denegación de acceso genera un registro en rbac_audit_logs con
 * trace_id, user_id, permission, decision, ip y timestamp.
 *
 * Decisión: cualquier error PDO se envuelve en RuntimeException para
 * mantener el dominio libre de dependencias de infraestructura.
 */
final class PdoAuditLogRepository implements AuditLogRepositoryInterface
{
    public function __construct(
        private readonly PDO $pdo,
    ) {}

    public function save(AuditLog $log): void
    {
        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO rbac_audit_logs
                    (trace_id, user_id, permission, decision, ip, occurred_at)
                 VALUES
                    (:trace_id, :user_id, :permission, :decision, :ip, :occurred_at)'
            );
            $stmt->execute([
                ':trace_id'    => $log->getTraceId(),
                ':user_id'     => $log->getUserId(),
                ':permission'  => $log->getPermission(),
                ':decision'    => $log->getDecision(),
                ':ip'          => $log->getIp(),
                ':occurred_at' => $log->getOccurredAt()->format('Y-m-d H:i:s'),
            ]);
        } catch (\PDOException $e) {
            // Envolver PDOException para no filtrar detalles de infraestructura
            throw new RuntimeException(
                'Error al persistir el registro de auditoría: ' . $e->getMessage(),
                (int) $e->getCode(),
                $e
            );
        }
    }

    /**
     * @return AuditLog[]
     */
    public function findByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT trace_id, user_id, permission, decision, ip, occurred_at
             FROM   rbac_audit_logs
             WHERE  user_id = :user_id
             ORDER  BY occurred_at DESC'
        );
        $stmt->execute([':user_id' => $userId]);

        $logs = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $logs[] = new AuditLog(
                traceId:    $row['trace_id'],
                userId:     (int) $row['user_id'],
                permission: $row['permission'],
                decision:   $row['decision'],
                ip:         $row['ip'],
                occurredAt: new DateTimeImmutable($row['occurred_at']),
            );
        }

        return $logs;
    }
}
