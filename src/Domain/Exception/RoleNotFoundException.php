<?php

declare(strict_types=1);

namespace MicroHIS\Domain\Exception;

use RuntimeException;

/**
 * Excepción de dominio lanzada cuando se intenta asignar un rol
 * que no existe en el sistema.
 */
final class RoleNotFoundException extends RuntimeException
{
    public function __construct(int|string $roleId)
    {
        parent::__construct(
            sprintf('El rol con identificador "%s" no existe en el sistema.', $roleId)
        );
    }
}
