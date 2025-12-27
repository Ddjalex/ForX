<?php

namespace App\Services;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            try {
                $host = getenv('DB_HOST') ?: 'localhost';
                $port = getenv('DB_PORT') ?: '5432';
                $dbname = getenv('DB_DATABASE') ?: 'alphadb_ForX';
                $user = getenv('DB_USER') ?: 'postgres';
                $password = getenv('DB_PASSWORD') ?: '';
                
                // Build DSN with SSL support for production databases
                $dsn = sprintf(
                    'pgsql:host=%s;port=%s;dbname=%s;sslmode=prefer',
                    $host,
                    $port,
                    $dbname
                );

                error_log("Connecting to: $host:$port/$dbname");

                self::$connection = new PDO(
                    $dsn,
                    $user,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
                
                error_log("Database connection successful!");
            } catch (PDOException $e) {
                error_log("Database Connection Error: " . $e->getMessage());
                throw new \RuntimeException("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$connection;
    }

    public static function insert(string $table, array $data): int
    {
        try {
            $keys = array_keys($data);
            $placeholders = array_map(fn($k) => '?', $keys);
            
            $sql = sprintf(
                'INSERT INTO %s (%s) VALUES (%s)',
                $table,
                implode(', ', $keys),
                implode(', ', $placeholders)
            );

            error_log("INSERT SQL: $sql | Data: " . json_encode($data));

            $stmt = self::getConnection()->prepare($sql);
            $stmt->execute(array_values($data));

            return (int)self::getConnection()->lastInsertId();
        } catch (PDOException $e) {
            error_log("INSERT Error: " . $e->getMessage());
            throw new \RuntimeException("Insert failed: " . $e->getMessage());
        }
    }

    public static function update(string $table, array $data, string $where, array $params = []): int
    {
        try {
            $sets = array_map(fn($k) => "$k = ?", array_keys($data));
            
            $sql = sprintf(
                'UPDATE %s SET %s WHERE %s',
                $table,
                implode(', ', $sets),
                $where
            );

            error_log("UPDATE SQL: $sql");

            $stmt = self::getConnection()->prepare($sql);
            $stmt->execute(array_merge(array_values($data), $params));

            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log("UPDATE Error: " . $e->getMessage());
            throw new \RuntimeException("Update failed: " . $e->getMessage());
        }
    }

    public static function fetch(string $sql, array $params = []): ?array
    {
        try {
            $stmt = self::getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch() ?: null;
        } catch (PDOException $e) {
            error_log("FETCH Error: " . $e->getMessage());
            throw new \RuntimeException("Fetch failed: " . $e->getMessage());
        }
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        try {
            $stmt = self::getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll() ?: [];
        } catch (PDOException $e) {
            error_log("FETCHALL Error: " . $e->getMessage());
            throw new \RuntimeException("FetchAll failed: " . $e->getMessage());
        }
    }

    public static function query(string $sql, array $params = []): void
    {
        try {
            $stmt = self::getConnection()->prepare($sql);
            $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("QUERY Error: " . $e->getMessage());
            throw new \RuntimeException("Query failed: " . $e->getMessage());
        }
    }

    public static function beginTransaction(): void
    {
        self::getConnection()->beginTransaction();
    }

    public static function commit(): void
    {
        self::getConnection()->commit();
    }

    public static function rollback(): void
    {
        self::getConnection()->rollBack();
    }
}
