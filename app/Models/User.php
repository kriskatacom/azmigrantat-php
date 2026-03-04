<?php

namespace App\Models;

class User extends Model
{
    protected string $table = 'users';

    public function register(string $name, string $email, string $password): bool
    {
        return $this->create([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'status' => 'active'
        ]);
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
        $sql = "UPDATE {$this->table} SET role_id = :role_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'role_id' => $roleId,
            'id' => $userId
        ]);
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
            $newData['password'] = password_hash($newData['password'], PASSWORD_DEFAULT);
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