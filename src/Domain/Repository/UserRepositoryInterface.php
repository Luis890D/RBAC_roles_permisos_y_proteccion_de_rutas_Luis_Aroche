<?php

declare(strict_types=1);

namespace MicroHIS\Domain\Repository;

use MicroHIS\Domain\Entity\User;

/**
 * Puerto (interfaz) que define el contrato de acceso a datos de Usuario.
 *
 * Las implementaciones concretas (PDO, InMemory para tests) dependen
 * de esta interfaz; el dominio nunca conoce la capa de persistencia.
 */
interface UserRepositoryInterface
{
    /**
     * Busca un usuario por su ID. Retorna null si no existe.
     */
    public function findById(int $id): ?User;

    /**
     * Busca un usuario por nombre de usuario. Retorna null si no existe.
     */
    public function findByUsername(string $username): ?User;

    /**
     * Persiste la asignación de un rol a un usuario (relación user_roles).
     *
     * @param int $userId  ID del usuario receptor
     * @param int $roleId  ID del rol a asignar
     */
    public function assignRole(int $userId, int $roleId): void;
}
