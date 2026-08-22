<?php

declare(strict_types=1);

namespace MicroHIS\Persistence;

use MicroHIS\Domain\Entity\Permission;
use MicroHIS\Domain\Entity\Role;
use MicroHIS\Domain\Entity\User;
use MicroHIS\Domain\Repository\UserRepositoryInterface;
use PDO;

/**
 * Implementación PDO del repositorio de Usuario.
 *
 * Usa SQLite (o cualquier motor compatible con PDO) y sentencias
 * preparadas para prevenir inyección SQL.
 *
 * Decisión: la carga de roles y permisos se hace en una sola consulta
 * con JOIN para minimizar round-trips a la base de datos.
 */
final class PdoUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly PDO $pdo,
    ) {}

    public function findById(int $id): ?User
    {
        return $this->buildUser('u.id = :id', [':id' => $id]);
    }

    public function findByUsername(string $username): ?User
    {
        return $this->buildUser('u.username = :username', [':username' => $username]);
    }

    public function assignRole(int $userId, int $roleId): void
    {
        // SQLite usa INSERT OR IGNORE para evitar duplicados
        $stmt = $this->pdo->prepare(
            'INSERT OR IGNORE INTO user_roles (user_id, role_id) VALUES (:user_id, :role_id)'
        );
        $stmt->execute([':user_id' => $userId, ':role_id' => $roleId]);
    }

    // -----------------------------------------------------------------------
    // Métodos privados de construcción
    // -----------------------------------------------------------------------

    /**
     * Construye una entidad User con sus roles y permisos anidados
     * a partir de una condición WHERE dinámica.
     *
     * @param array<string,mixed> $params
     */
    private function buildUser(string $condition, array $params): ?User
    {
        // Paso 1: obtener el registro base del usuario
        $stmt = $this->pdo->prepare(
            "SELECT id, username FROM users WHERE {$condition} LIMIT 1"
        );
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        $user = new User((int) $row['id'], $row['username']);

        // Paso 2: cargar roles y permisos con un único JOIN
        $roleStmt = $this->pdo->prepare(
            'SELECT r.id   AS role_id,
                    r.name AS role_name,
                    p.id   AS perm_id,
                    p.name AS perm_name
             FROM   user_roles ur
             JOIN   roles r      ON r.id = ur.role_id
             LEFT JOIN role_permissions rp ON rp.role_id = r.id
             LEFT JOIN permissions p       ON p.id = rp.permission_id
             WHERE  ur.user_id = :uid
             ORDER  BY r.id, p.id'
        );
        $roleStmt->execute([':uid' => $row['id']]);

        $rolesMap = [];
        foreach ($roleStmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $roleId = (int) $r['role_id'];
            if (!isset($rolesMap[$roleId])) {
                $rolesMap[$roleId] = new Role($roleId, $r['role_name']);
            }
            if ($r['perm_id'] !== null) {
                $rolesMap[$roleId]->addPermission(
                    new Permission((int) $r['perm_id'], $r['perm_name'])
                );
            }
        }

        foreach ($rolesMap as $role) {
            $user->assignRole($role);
        }

        return $user;
    }
}
