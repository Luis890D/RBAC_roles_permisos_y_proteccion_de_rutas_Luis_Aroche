<?php

declare(strict_types=1);

namespace MicroHIS\Tests\Stub;

use MicroHIS\Domain\Entity\AuditLog;
use MicroHIS\Domain\Repository\AuditLogRepositoryInterface;

/**
 * Stub en memoria del repositorio de auditoría para pruebas unitarias.
 *
 * Permite verificar que el caso de uso persiste el log de denegación,
 * y también simular un error de persistencia (PDOException envuelta).
 */
final class InMemoryAuditLogRepository implements AuditLogRepositoryInterface
{
    /** @var AuditLog[] */
    private array $logs = [];

    /** @var bool Controla si save() lanza excepción (test de error de persistencia) */
    private bool $shouldFailOnSave = false;

    public function failOnSave(): void
    {
        $this->shouldFailOnSave = true;
    }

    /** @return AuditLog[] */
    public function all(): array
    {
        return $this->logs;
    }

    // ---- Implementación del contrato ----------------------------------------

    public function save(AuditLog $log): void
    {
        if ($this->shouldFailOnSave) {
            throw new \RuntimeException(
                'Error al persistir el registro de auditoría: database is locked'
            );
        }
        $this->logs[] = $log;
    }

    /** @return AuditLog[] */
    public function findByUserId(int $userId): array
    {
        return array_values(
            array_filter($this->logs, fn(AuditLog $l) => $l->getUserId() === $userId)
        );
    }
}
