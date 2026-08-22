<?php

declare(strict_types=1);

namespace MicroHIS\Domain\Exception;

use RuntimeException;

/**
 * Excepción de dominio lanzada cuando no se encuentra un usuario.
 */
final class UserNotFoundException extends RuntimeException
{
    public function __construct(int|string $userId)
    {
        parent::__construct(
            sprintf('El usuario con identificador "%s" no existe en el sistema.', $userId)
        );
    }
}
