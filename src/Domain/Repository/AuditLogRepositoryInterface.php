<?php

declare(strict_types=1);

namespace MicroHIS\Domain\Repository;

use MicroHIS\Domain\Entity\AuditLog;

/**
 * Puerto (interfaz) que define el contrato de persistencia de auditoría.
 *
 * Toda denegación de acceso debe ser persistida de forma trazable
 * con un trace_id único para permitir soporte y auditoría forense.
 */
interface AuditLogRepositoryInterface
{
    /**
     * Persiste un evento de auditoría (concesión o denegación de acceso).
     *
     * @throws \RuntimeException Si la persistencia falla (ej. PDOException envuelta)
     */
    public function save(AuditLog $log): void;

    /**
     * Recupera todos los registros de auditoría de un usuario.
     *
     * @return AuditLog[]
     */
    public function findByUserId(int $userId): array;
}
