<?php

declare(strict_types=1);

namespace MicroHIS\Domain\Entity;

/**
 * Entidad de dominio que representa un Rol dentro del sistema RBAC.
 *
 * Un rol agrupa un conjunto de permisos y puede ser asignado a uno o más usuarios.
 * Datos usados: exclusivamente ficticios, sin información clínica identificable.
 */
final class Role
{
    /** @param Permission[] $permissions */
    public function __construct(
        private readonly int    $id,
        private readonly string $name,
        private array           $permissions = [],
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /** @return Permission[] */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * Verifica si este rol posee el permiso indicado.
     *
     * @param string $permissionName Nombre del permiso, ej. "emr.soap.write"
     */
    public function hasPermission(string $permissionName): bool
    {
        foreach ($this->permissions as $permission) {
            if ($permission->getName() === $permissionName) {
                return true;
            }
        }
        return false;
    }

    public function addPermission(Permission $permission): void
    {
        $this->permissions[] = $permission;
    }
}
