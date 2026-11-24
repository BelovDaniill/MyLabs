<?php
declare(strict_types=1);

/** Database operations for users. */
class User
{
    /** Finds a user by email. */
    public static function findByEmail(string $email): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    /** Finds a user by ID. */
    public static function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT id, username, email, role, created_at FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /** Creates a user with a securely hashed password. */
    public static function create(string $username, string $email, string $password, string $role = 'user'): void
    {
        $stmt = Database::connection()->prepare('INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)');
        $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT), $role]);
    }

    /** Returns all users without password hashes. */
    public static function all(): array
    {
        return Database::connection()->query('SELECT id, username, email, role, created_at FROM users ORDER BY id DESC')->fetchAll();
    }

    /** Updates a user's public fields and optional password. */
    public static function update(int $id, string $username, string $email, string $role, string $password = ''): void
    {
        if ($password !== '') {
            $stmt = Database::connection()->prepare('UPDATE users SET username=?, email=?, role=?, password=? WHERE id=?');
            $stmt->execute([$username, $email, $role, password_hash($password, PASSWORD_DEFAULT), $id]);
        } else {
            $stmt = Database::connection()->prepare('UPDATE users SET username=?, email=?, role=? WHERE id=?');
            $stmt->execute([$username, $email, $role, $id]);
        }
    }

    /** Deletes a user; their notes are deleted by the foreign-key cascade. */
    public static function delete(int $id): void
    {
        $stmt = Database::connection()->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$id]);
    }
}
