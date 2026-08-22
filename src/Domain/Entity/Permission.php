<?php

declare(strict_types=1);

namespace MicroHIS\Domain\Entity;

/**
 * Value Object que representa un permiso dentro del sistema RBAC.
 *
 * Inmutable por diseño: una vez creado, su nombre no cambia.
 * Datos usados: exclusivamente ficticios, sin información clínica real.
 */
final class Permission
{
    public function __construct(
        private readonly int    $id,
        private readonly string $name,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function equals(self $other): bool
    {
        return $this->name === $other->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
