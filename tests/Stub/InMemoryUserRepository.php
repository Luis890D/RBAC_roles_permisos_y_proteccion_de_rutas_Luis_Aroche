<?php

declare(strict_types=1);

namespace MicroHIS\Tests\Stub;

use MicroHIS\Domain\Entity\Role;
use MicroHIS\Domain\Entity\User;
use MicroHIS\Domain\Repository\UserRepositoryInterface;

/**
 * Stub en memoria del repositorio de Usuario para pruebas unitarias.
 *
 * Evita dependencia de base de datos; permite inyectar datos ficticios
 * directamente o simular error de persistencia en assignRole.
 */
final class InMemoryUserRepository implements UserRepositoryInterface
{
    /** @var User[] */
    private array $users = [];

    /** @var bool Controla si assignRole lanza excepción (para test de error) */
    private bool $shouldFailOnAssign = false;

    public function addUser(User $user): void
    {
        $this->users[$user->getId()] = $user;
    }

    public function failOnAssign(): void
    {
        $this->shouldFailOnAssign = true;
    }

    // ---- Implementación del contrato ----------------------------------------

    public function findById(int $id): ?User
    {
        return $this->users[$id] ?? null;
    }

    public function findByUsername(string $username): ?User
    {
        foreach ($this->users as $user) {
            if ($user->getUsername() === $username) {
                return $user;
            }
        }
        return null;
    }

    public function assignRole(int $userId, int $roleId): void
    {
        if ($this->shouldFailOnAssign) {
            throw new \RuntimeException('Error simulado de persistencia en user_roles.');
        }
        // En el stub simplemente ignoramos; la relación ya está en el objeto User
    }
}
