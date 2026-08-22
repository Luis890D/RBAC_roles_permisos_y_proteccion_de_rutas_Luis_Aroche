<?php

declare(strict_types=1);

namespace MicroHIS\Tests\Unit;

use MicroHIS\Application\DTO\AuthorizeRequest;
use MicroHIS\Application\UseCase\AuthorizeOperationUseCase;
use MicroHIS\Domain\Entity\Permission;
use MicroHIS\Domain\Entity\Role;
use MicroHIS\Domain\Entity\User;
use MicroHIS\Domain\Exception\AccessDeniedException;
use MicroHIS\Domain\Exception\UserNotFoundException;
use MicroHIS\Tests\Stub\InMemoryAuditLogRepository;
use MicroHIS\Tests\Stub\InMemoryUserRepository;
use PHPUnit\Framework\TestCase;

/**
 * Pruebas unitarias para AuthorizeOperationUseCase.
 *
 * Cubre los tres escenarios requeridos por la consigna:
 *   1. Camino feliz (happy path) — usuario autorizado
 *   2. Regla de dominio — denegación trazable con trace_id
 *   3. Error de persistencia — el repositorio de auditoría falla
 *
 * Todos los datos son exclusivamente ficticios.
 */
class AuthorizeOperationTest extends TestCase
{
    // -------------------------------------------------------------------------
    // Helpers de fixture
    // -------------------------------------------------------------------------

    /**
     * Crea un usuario ficticio Dr. García con rol 'medico'
     * que incluye el permiso 'emr.soap.write'.
     */
    private function buildDrGarcia(): User
    {
        $permission = new Permission(1, 'emr.soap.write');
        $role       = new Role(1, 'medico', [$permission]);
        $user       = new User(1, 'dr.garcia');
        $user->assignRole($role);
        return $user;
    }

    // =========================================================================
    // PRUEBA 1: Camino feliz — usuario CON permiso → granted = true
    // =========================================================================
    public function testHappyPath_userWithPermission_returnsGranted(): void
    {
        // Arrange
        $userRepo  = new InMemoryUserRepository();
        $auditRepo = new InMemoryAuditLogRepository();
        $userRepo->addUser($this->buildDrGarcia());

        $useCase = new AuthorizeOperationUseCase($userRepo, $auditRepo);
        $request = new AuthorizeRequest(
            userId:     1,
            permission: 'emr.soap.write',
            ip:         '192.168.10.55',
        );

        // Act
        $response = $useCase->execute($request);

        // Assert
        $this->assertTrue($response->granted, 'El usuario con permiso debe ser autorizado.');
        $this->assertEmpty($auditRepo->all(), 'No debe persistirse auditoría en camino feliz.');
    }

    // =========================================================================
    // PRUEBA 2: Regla de dominio — usuario SIN permiso → AccessDeniedException
    //           con trace_id único persistido en auditoría
    // =========================================================================
    public function testDomainRule_userWithoutPermission_throwsAccessDeniedWithTraceId(): void
    {
        // Arrange
        $userRepo  = new InMemoryUserRepository();
        $auditRepo = new InMemoryAuditLogRepository();
        $userRepo->addUser($this->buildDrGarcia());

        $useCase = new AuthorizeOperationUseCase($userRepo, $auditRepo);
        $request = new AuthorizeRequest(
            userId:     1,
            permission: 'admin.user.delete',  // permiso que dr.garcia NO tiene
            ip:         '192.168.10.55',
        );

        // Assert: esperar la excepción de dominio
        $this->expectException(AccessDeniedException::class);

        // Act
        try {
            $useCase->execute($request);
        } catch (AccessDeniedException $e) {
            // Verificar trace_id en la excepción
            $this->assertNotEmpty($e->getTraceId(), 'La excepción debe incluir un trace_id.');
            $this->assertStringStartsWith('TRC-890D-', $e->getTraceId());
            $this->assertSame('admin.user.delete', $e->getPermission());
            $this->assertSame(1, $e->getUserId());

            // Verificar que se persistió un AuditLog en el repositorio
            $logs = $auditRepo->findByUserId(1);
            $this->assertCount(1, $logs, 'Debe persistirse exactamente 1 registro de auditoría.');
            $this->assertSame($e->getTraceId(), $logs[0]->getTraceId());
            $this->assertSame('DENIED', $logs[0]->getDecision());

            throw $e; // Re-lanzar para que PHPUnit valide expectException
        }
    }

    // =========================================================================
    // PRUEBA 3: Error de persistencia — el repositorio de auditoría falla
    //           El caso de uso no debe silenciar el error
    // =========================================================================
    public function testPersistenceError_auditLogFails_throwsRuntimeException(): void
    {
        // Arrange
        $userRepo  = new InMemoryUserRepository();
        $auditRepo = new InMemoryAuditLogRepository();
        $auditRepo->failOnSave();  // Simular fallo de infraestructura
        $userRepo->addUser($this->buildDrGarcia());

        $useCase = new AuthorizeOperationUseCase($userRepo, $auditRepo);
        $request = new AuthorizeRequest(
            userId:     1,
            permission: 'admin.user.delete',
            ip:         '192.168.10.55',
        );

        // Assert: RuntimeException del repositorio debe propagarse
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/auditoría/i');

        // Act
        $useCase->execute($request);
    }

    // =========================================================================
    // PRUEBA 4: Usuario no encontrado → UserNotFoundException
    // =========================================================================
    public function testUserNotFound_throwsUserNotFoundException(): void
    {
        $userRepo  = new InMemoryUserRepository(); // vacío
        $auditRepo = new InMemoryAuditLogRepository();
        $useCase   = new AuthorizeOperationUseCase($userRepo, $auditRepo);
        $request   = new AuthorizeRequest(userId: 99, permission: 'emr.soap.write');

        $this->expectException(UserNotFoundException::class);
        $useCase->execute($request);
    }
}
