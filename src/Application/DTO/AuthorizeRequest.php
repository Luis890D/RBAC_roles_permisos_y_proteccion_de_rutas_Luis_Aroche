<?php

declare(strict_types=1);

namespace MicroHIS\Application\DTO;

/**
 * DTO de entrada para el caso de uso AuthorizeOperation.
 *
 * @param int    $userId     ID del usuario que solicita la operación
 * @param string $permission Nombre canónico del permiso, ej. "emr.soap.write"
 * @param string $ip         IP de origen (ficticia en pruebas, ej. "192.168.10.55")
 */
final class AuthorizeRequest
{
    public function __construct(
        public readonly int    $userId,
        public readonly string $permission,
        public readonly string $ip = '127.0.0.1',
    ) {}
}
