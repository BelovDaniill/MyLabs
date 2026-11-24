<?php
declare(strict_types=1);

/** Provides the SQLite PDO connection and initializes the schema. */
class Database
{
    private static ?PDO $connection = null;

    /** Returns the shared PDO connection. */
    public static function connection(): PDO
    {
        if (self::$connection === null) {
            $dir = BASE_PATH . '/database';
            if (!is_dir($dir)) {
                mkdir($dir, 0775, true);
            }
            self::$connection = new PDO('sqlite:' . $dir . '/database.sqlite');
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            self::$connection->exec('PRAGMA foreign_keys = ON');
        }
        return self::$connection;
    }

    /** Creates application tables and inserts the first administrator when needed. */
    public static function initialize(): void
    {
        $pdo = self::connection();
        $schema = file_get_contents(BASE_PATH . '/database/schema.sql');
        $pdo->exec($schema);
        $count = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        if ($count === 0) {
            $stmt = $pdo->prepare('INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)');
            $stmt->execute(['admin', 'admin@example.com', password_hash('Admin123!', PASSWORD_DEFAULT), 'admin']);
        }
    }
}
