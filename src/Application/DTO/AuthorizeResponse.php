<?php

declare(strict_types=1);

namespace MicroHIS\Application\DTO;

/**
 * DTO de salida para el caso de uso AuthorizeOperation.
 *
 * En caso de autorización exitosa, granted = true y traceId es null.
 * En caso de denegación, la excepción AccessDeniedException lleva el traceId.
 */
final class AuthorizeResponse
{
    public function __construct(
        public readonly bool    $granted,
        public readonly ?string $traceId = null,
        public readonly string  $message = '',
    ) {}

    public static function granted(): self
    {
        return new self(true, null, 'Operación autorizada correctamente.');
    }
}
