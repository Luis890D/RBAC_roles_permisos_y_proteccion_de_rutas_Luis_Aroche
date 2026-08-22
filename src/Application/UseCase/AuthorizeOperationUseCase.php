<?php

declare(strict_types=1);

namespace MicroHIS\Application\UseCase;

use DateTimeImmutable;
use MicroHIS\Application\DTO\AuthorizeRequest;
use MicroHIS\Application\DTO\AuthorizeResponse;
use MicroHIS\Domain\Entity\AuditLog;
use MicroHIS\Domain\Exception\AccessDeniedException;
use MicroHIS\Domain\Exception\UserNotFoundException;
use MicroHIS\Domain\Repository\AuditLogRepositoryInterface;
use MicroHIS\Domain\Repository\UserRepositoryInterface;

/**
 * Caso de Uso: CU-RBAC-02 / CU-RBAC-03
 * Autorizar una operación o generar denegación trazable.
 *
 * Flujo:
 *   1. Recuperar el usuario con sus roles y permisos.
 *   2. Evaluar si alguno de sus roles posee el permiso solicitado.
 *   3a. Permiso concedido → retorna AuthorizeResponse(granted=true).
 *   3b. Permiso denegado → genera trace_id, persiste AuditLog,
 *       lanza AccessDeniedException con trace_id.
 *
 * Decisión: el trace_id se genera aquí (Application) porque es una
 * responsabilidad de coordinación, no de la entidad de dominio.
 * La generación usa uuid_create() si está disponible, o sprintf() como
 * fallback determinístico para entornos sin extensión uuid.
 */
final class AuthorizeOperationUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface    $userRepository,
        private readonly AuditLogRepositoryInterface $auditLogRepository,
    ) {}

    /**
     * Evalúa si el usuario puede ejecutar la operación indicada.
     *
     * @throws UserNotFoundException   Si el usuario no existe.
     * @throws AccessDeniedException   Si el permiso es denegado (incluye trace_id).
     */
    public function execute(AuthorizeRequest $request): AuthorizeResponse
    {
        // 1. Cargar usuario con roles y permisos precargados
        $user = $this->userRepository->findById($request->userId);
        if ($user === null) {
            throw new UserNotFoundException($request->userId);
        }

        // 2. Evaluar autorización en el dominio
        if ($user->isAuthorized($request->permission)) {
            // 2a. Camino feliz (happy path)
            return AuthorizeResponse::granted();
        }

        // 2b. Denegación trazable: generar trace_id único
        $traceId = $this->generateTraceId($request->userId);

        // 3. Persistir el evento de auditoría ANTES de lanzar la excepción
        $auditLog = new AuditLog(
            traceId:    $traceId,
            userId:     $request->userId,
            permission: $request->permission,
            decision:   AuditLog::DECISION_DENIED,
            ip:         $request->ip,
            occurredAt: new DateTimeImmutable(),
        );
        $this->auditLogRepository->save($auditLog);

        // 4. Lanzar excepción de dominio con trace_id para la presentación
        throw new AccessDeniedException(
            traceId:    $traceId,
            permission: $request->permission,
            userId:     $request->userId,
        );
    }

    /**
     * Genera un trace_id con formato legible: TRC-890D-YYYYMMDD-NNN.
     *
     * En producción se reemplazaría por UUID v4 real. Para este micro-monolito
     * educativo usamos un formato determinístico que facilita la lectura en logs.
     */
    private function generateTraceId(int $userId): string
    {
        $date    = (new DateTimeImmutable())->format('Ymd');
        $suffix  = strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
        return sprintf('TRC-890D-%s-%s', $date, $suffix);
    }
}
