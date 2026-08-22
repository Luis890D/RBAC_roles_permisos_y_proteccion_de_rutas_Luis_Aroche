<?php

declare(strict_types=1);

namespace MicroHIS\Domain\Entity;

/**
 * Entidad de dominio que representa un Usuario del sistema.
 *
 * El usuario puede tener uno o más roles asignados.
 * Todos los datos de ejemplo son completamente ficticios.
 */
final class User
{
    /** @param Role[] $roles */
    public function __construct(
        private readonly int    $id,
        private readonly string $username,
        private array           $roles = [],
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    /** @return Role[] */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * Verifica si el usuario posee el rol indicado.
     */
    public function hasRole(string $roleName): bool
    {
        foreach ($this->roles as $role) {
            if ($role->getName() === $roleName) {
                return true;
            }
        }
        return false;
    }

    /**
     * Evalúa si alguno de los roles del usuario contiene el permiso solicitado.
     *
     * @param string $permissionName Nombre canónico del permiso, ej. "emr.soap.write"
     */
    public function isAuthorized(string $permissionName): bool
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permissionName)) {
                return true;
            }
        }
        return false;
    }

    public function assignRole(Role $role): void
    {
        // Evita duplicados por nombre
        foreach ($this->roles as $existing) {
            if ($existing->getName() === $role->getName()) {
                return;
            }
        }
        $this->roles[] = $role;
    }
}
