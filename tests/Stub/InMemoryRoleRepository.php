<?php

declare(strict_types=1);

namespace MicroHIS\Tests\Stub;

use MicroHIS\Domain\Entity\Role;
use MicroHIS\Domain\Repository\RoleRepositoryInterface;

/**
 * Stub en memoria del repositorio de Rol para pruebas unitarias.
 */
final class InMemoryRoleRepository implements RoleRepositoryInterface
{
    /** @var Role[] */
    private array $roles = [];

    public function addRole(Role $role): void
    {
        $this->roles[$role->getId()] = $role;
    }

    public function findById(int $id): ?Role
    {
        return $this->roles[$id] ?? null;
    }

    public function findByName(string $name): ?Role
    {
        foreach ($this->roles as $role) {
            if ($role->getName() === $name) {
                return $role;
            }
        }
        return null;
    }
}
