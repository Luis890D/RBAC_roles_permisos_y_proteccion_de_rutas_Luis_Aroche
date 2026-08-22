<?php

declare(strict_types=1);

namespace MicroHIS\Domain\Repository;

use MicroHIS\Domain\Entity\Role;

/**
 * Puerto (interfaz) que define el contrato de acceso a datos de Rol.
 */
interface RoleRepositoryInterface
{
    /**
     * Busca un rol por su ID, incluyendo sus permisos asociados.
     * Retorna null si el rol no existe.
     */
    public function findById(int $id): ?Role;

    /**
     * Busca un rol por nombre. Retorna null si no existe.
     */
    public function findByName(string $name): ?Role;
}
