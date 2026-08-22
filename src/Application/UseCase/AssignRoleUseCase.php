<?php

declare(strict_types=1);

namespace MicroHIS\Application\UseCase;

use MicroHIS\Application\DTO\AssignRoleRequest;
use MicroHIS\Domain\Exception\RoleNotFoundException;
use MicroHIS\Domain\Exception\UserNotFoundException;
use MicroHIS\Domain\Repository\RoleRepositoryInterface;
use MicroHIS\Domain\Repository\UserRepositoryInterface;

/**
 * Caso de Uso: CU-RBAC-01 — Asignar Rol a Usuario.
 *
 * Orquesta la lógica de asignación: valida que el usuario y el rol
 * existan en el sistema antes de persistir la relación.
 *
 * Decisión de diseño: el caso de uso solo coordina, no contiene
 * lógica de dominio propia. La regla "el rol debe existir" vive aquí
 * porque es una invariante de la operación de asignación.
 */
final class AssignRoleUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly RoleRepositoryInterface $roleRepository,
    ) {}

    /**
     * Asigna el rol al usuario indicado.
     *
     * @throws UserNotFoundException Si el usuario no existe.
     * @throws RoleNotFoundException Si el rol no existe.
     */
    public function execute(AssignRoleRequest $request): void
    {
        // 1. Verificar que el usuario exista
        $user = $this->userRepository->findById($request->userId);
        if ($user === null) {
            throw new UserNotFoundException($request->userId);
        }

        // 2. Verificar que el rol exista — regla de dominio clave
        $role = $this->roleRepository->findById($request->roleId);
        if ($role === null) {
            throw new RoleNotFoundException($request->roleId);
        }

        // 3. Persistir la asignación (relación user_roles)
        $this->userRepository->assignRole($request->userId, $request->roleId);
    }
}
