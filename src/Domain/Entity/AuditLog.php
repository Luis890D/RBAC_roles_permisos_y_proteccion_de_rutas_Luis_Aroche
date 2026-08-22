<?php

declare(strict_types=1);

namespace MicroHIS\Domain\Entity;

use DateTimeImmutable;

/**
 * Entidad de auditoría que registra cada evento de denegación de acceso.
 *
 * Permite trazabilidad completa: quién intentó qué operación, cuándo,
 * desde qué IP y cuál fue la decisión del sistema.
 *
 * Datos usados: exclusivamente ficticios, sin información clínica real.
 */
final class AuditLog
{
    public const DECISION_GRANTED = 'GRANTED';
    public const DECISION_DENIED  = 'DENIED';

    public function __construct(
        private readonly string            $traceId,
        private readonly int               $userId,
        private readonly string            $permission,
        private readonly string            $decision,
        private readonly string            $ip,
        private readonly DateTimeImmutable $occurredAt,
    ) {}

    public function getTraceId(): string
    {
        return $this->traceId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getPermission(): string
    {
        return $this->permission;
    }

    public function getDecision(): string
    {
        return $this->decision;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getOccurredAt(): DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
