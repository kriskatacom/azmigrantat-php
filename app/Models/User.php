<?php

namespace App\Models;

class User extends Model
{
    protected string $table = 'users';

    public function register(string $name, string $email, string $password): string|bool
    {
        $id = $this->generateUuid();

        $username = $this->generateUniqueUsername($name);

        $result = $this->create([
            'id'            => $id,
            'name'          => $name,
            'username'      => $username,
            'email'         => $email,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'is_active'     => 1,
            'role_id'       => 3,
        ]);

        return $result ? $id : false;
    }

    private function generateUniqueUsername(string $name): string
    {
        $latinName = $this->generateSlug($name);

        $baseUsername = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $latinName)));
        $baseUsername = trim($baseUsername, '-');

        $username = $baseUsername;
        $counter = 1;

        while ($this->where('username', $username)) {
            $username = $baseUsername . '-' . $counter;
            $counter++;
        }

        return $username;
    }

    public function login(string $email, string $password): ?array
    {
        $sql = "SELECT u.*, r.name as role_name 
            FROM users u 
            LEFT JOIN roles r ON u.role_id = r.id 
            WHERE u.email = :email LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            unset($user['password_hash']);
            $this->setSession($user);
            return $user;
        }
        return null;
    }

    private function setSession(array $user): void
    {
        $_SESSION['user'] = [
            'id'    => $user['id'],
            'name'  => $user['name'],
            'username'  => $user['username'],
            'email' => $user['email'],
            'role'  => $user['role_name'],
            'is_logged_in' => true
        ];
    }

    public function getAllWithRoles(): array
    {
        $sql = "SELECT u.*, r.label as role_label 
            FROM users u 
            LEFT JOIN roles r ON u.role_id = r.id 
            WHERE u.deleted_at IS NULL 
            ORDER BY u.created_at DESC";

        return $this->db->query($sql)->fetchAll();
    }

    public function updateRole(string $userId, int $roleId): bool
    {
        try {
            $this->db->beginTransaction();

            $sql = "UPDATE {$this->table} SET role_id = :role_id WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'role_id' => $roleId,
                'id' => $userId
            ]);

            $driverRoleId = 2;

            if ($roleId == $driverRoleId) {
                $checkSql = "SELECT id FROM drivers WHERE user_id = :user_id LIMIT 1";
                $checkStmt = $this->db->prepare($checkSql);
                $checkStmt->execute(['user_id' => $userId]);

                if (!$checkStmt->fetch()) {
                    $user = $this->findUuid($userId);

                    $driverSql = "INSERT INTO drivers (user_id, name, travel_starts_at, status) 
                              VALUES (:user_id, :name, NOW(), 'active')";

                    $driverStmt = $this->db->prepare($driverSql);
                    $driverStmt->execute([
                        'user_id' => $userId,
                        'name'    => $user['name'],
                    ]);
                }
            }

            return $this->db->commit();
        } catch (\Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
            return false;
        }
    }

    private function findUuid(string $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
        session_destroy();
    }

    public static function auth(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public function updateProfile(int $id, array $newData): bool
    {
        if (isset($newData['password'])) {
            $newData['password_hash'] = password_hash($newData['password'], PASSWORD_DEFAULT);
        }

        return $this->update($id, $newData);
    }

    public function getPaginatedWithRoles(int $limit, int $offset): array
    {
        $sql = "SELECT u.*, r.name as role_name, r.label as role_label 
            FROM {$this->table} u 
            LEFT JOIN roles r ON u.role_id = r.id 
            WHERE u.deleted_at IS NULL
            ORDER BY u.created_at DESC 
            LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}