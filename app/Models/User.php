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
        $results = $this->where('email', $email);
        $user = $results[0] ?? null;

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
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
            'is_logged_in' => true
        ];
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
}