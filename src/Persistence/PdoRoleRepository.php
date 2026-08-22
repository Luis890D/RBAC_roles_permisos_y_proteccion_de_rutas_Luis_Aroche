<?php

declare(strict_types=1);

namespace MicroHIS\Persistence;

use MicroHIS\Domain\Entity\Permission;
use MicroHIS\Domain\Entity\Role;
use MicroHIS\Domain\Repository\RoleRepositoryInterface;
use PDO;

/**
 * Implementación PDO del repositorio de Rol.
 *
 * Carga los permisos asociados al rol en la misma consulta mediante JOIN.
 */
final class PdoRoleRepository implements RoleRepositoryInterface
{
    public function __construct(
        private readonly PDO $pdo,
    ) {}

    public function findById(int $id): ?Role
    {
        return $this->buildRole('r.id = :param', [':param' => $id]);
    }

    public function findByName(string $name): ?Role
    {
        return $this->buildRole('r.name = :param', [':param' => $name]);
    }

    // -----------------------------------------------------------------------

    /**
     * @param array<string,mixed> $params
     */
    private function buildRole(string $condition, array $params): ?Role
    {
        $stmt = $this->pdo->prepare(
            "SELECT r.id   AS role_id,
                    r.name AS role_name,
                    p.id   AS perm_id,
                    p.name AS perm_name
             FROM   roles r
             LEFT JOIN role_permissions rp ON rp.role_id = r.id
             LEFT JOIN permissions p       ON p.id = rp.permission_id
             WHERE  {$condition}
             ORDER  BY p.id"
        );
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($rows)) {
            return null;
        }

        $role = new Role((int) $rows[0]['role_id'], $rows[0]['role_name']);

        foreach ($rows as $row) {
            if ($row['perm_id'] !== null) {
                $role->addPermission(
                    new Permission((int) $row['perm_id'], $row['perm_name'])
                );
            }
        }

        return $role;
    }
}
