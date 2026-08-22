<?php

declare(strict_types=1);

namespace MicroHIS\Application\DTO;

/**
 * DTO de entrada para el caso de uso AssignRole.
 * Solo datos ficticios; no contiene información clínica real.
 */
final class AssignRoleRequest
{
    public function __construct(
        public readonly int $userId,
        public readonly int $roleId,
    ) {}
}
