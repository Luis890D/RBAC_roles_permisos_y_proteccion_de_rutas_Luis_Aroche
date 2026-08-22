<?php

declare(strict_types=1);

namespace MicroHIS\Domain\Exception;

use RuntimeException;

/**
 * Excepción de dominio lanzada cuando un usuario intenta ejecutar
 * una operación para la que no posee el permiso requerido.
 *
 * Incluye el trace_id UUID generado y persistido en la auditoría,
 * de modo que la capa de presentación pueda mostrarlo al operador.
 */
final class AccessDeniedException extends RuntimeException
{
    public function __construct(
        private readonly string $traceId,
        private readonly string $permission,
        private readonly int    $userId,
    ) {
        parent::__construct(
            sprintf(
                'Acceso denegado al permiso "%s" para el usuario #%d. trace_id: %s',
                $permission,
                $userId,
                $traceId,
            )
        );
    }

    public function getTraceId(): string
    {
        return $this->traceId;
    }

    public function getPermission(): string
    {
        return $this->permission;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
