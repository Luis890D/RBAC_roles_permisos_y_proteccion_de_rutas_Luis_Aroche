<?php

declare(strict_types=1);

namespace MicroHIS\Tests\Unit;

use MicroHIS\Application\DTO\AssignRoleRequest;
use MicroHIS\Application\UseCase\AssignRoleUseCase;
use MicroHIS\Domain\Entity\Role;
use MicroHIS\Domain\Entity\User;
use MicroHIS\Domain\Exception\RoleNotFoundException;
use MicroHIS\Domain\Exception\UserNotFoundException;
use MicroHIS\Tests\Stub\InMemoryRoleRepository;
use MicroHIS\Tests\Stub\InMemoryUserRepository;
use PHPUnit\Framework\TestCase;

/**
 * Pruebas unitarias para AssignRoleUseCase.
 *
 * Cubre:
 *   1. Camino feliz — rol asignado exitosamente
 *   2. Regla de dominio — rol no existe → RoleNotFoundException
 *   3. Regla de dominio — usuario no existe → UserNotFoundException
 *   4. Error de persistencia — assignRole lanza RuntimeException
 *
 * Todos los datos son exclusivamente ficticios.
 */
class AssignRoleTest extends TestCase
{
    // =========================================================================
    // PRUEBA 1: Camino feliz — rol y usuario existen → asignación sin error
    // =========================================================================
    public function testHappyPath_assignExistingRoleToUser_noExceptionThrown(): void
    {
        // Arrange
        $userRepo = new InMemoryUserRepository();
        $roleRepo = new InMemoryRoleRepository();

        $user = new User(1, 'dr.garcia');
        $role = new Role(1, 'medico');

        $userRepo->addUser($user);
        $roleRepo->addRole($role);

        $useCase = new AssignRoleUseCase($userRepo, $roleRepo);
        $request = new AssignRoleRequest(userId: 1, roleId: 1);

        // Act & Assert: no debe lanzar excepción
        $useCase->execute($request);
        $this->addToAssertionCount(1); // confirmar que se ejecutó
    }

    // =========================================================================
    // PRUEBA 2: Regla de dominio — rol no existe → RoleNotFoundException
    // =========================================================================
    public function testDomainRule_roleDoesNotExist_throwsRoleNotFoundException(): void
    {
        // Arrange
        $userRepo = new InMemoryUserRepository();
        $roleRepo = new InMemoryRoleRepository(); // vacío: ningún rol registrado

        $user = new User(1, 'dr.garcia');
        $userRepo->addUser($user);

        $useCase = new AssignRoleUseCase($userRepo, $roleRepo);
        $request = new AssignRoleRequest(userId: 1, roleId: 999); // rol inexistente

        // Assert
        $this->expectException(RoleNotFoundException::class);
        $this->expectExceptionMessageMatches('/999/');

        // Act
        $useCase->execute($request);
    }

    // =========================================================================
    // PRUEBA 3: Usuario no existe → UserNotFoundException
    // =========================================================================
    public function testUserDoesNotExist_throwsUserNotFoundException(): void
    {
        $userRepo = new InMemoryUserRepository(); // vacío
        $roleRepo = new InMemoryRoleRepository();
        $roleRepo->addRole(new Role(1, 'medico'));

        $useCase = new AssignRoleUseCase($userRepo, $roleRepo);
        $request = new AssignRoleRequest(userId: 99, roleId: 1);

        $this->expectException(UserNotFoundException::class);
        $useCase->execute($request);
    }

    // =========================================================================
    // PRUEBA 4: Error de persistencia en assignRole → RuntimeException
    // =========================================================================
    public function testPersistenceError_assignRoleFails_throwsRuntimeException(): void
    {
        // Arrange
        $userRepo = new InMemoryUserRepository();
        $roleRepo = new InMemoryRoleRepository();

        $userRepo->addUser(new User(1, 'dr.garcia'));
        $roleRepo->addRole(new Role(1, 'medico'));
        $userRepo->failOnAssign(); // activar modo de fallo

        $useCase = new AssignRoleUseCase($userRepo, $roleRepo);
        $request = new AssignRoleRequest(userId: 1, roleId: 1);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/persistencia/i');

        // Act
        $useCase->execute($request);
    }
}
