<?php

namespace App\Services;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../../config/database.php';
            
            try {
                // Determine driver and DSN
                $driver = $config['driver'] ?? 'mysql';
                if ($driver === 'mysql') {
                    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset=utf8mb4";
                } else {
                    $dsn = sprintf(
                        '%s:host=%s;port=%s;dbname=%s',
                        $driver,
                        $config['host'],
                        $config['port'],
                        $config['database']
                    );
                }
                
                self::$instance = new PDO(
                    $dsn,
                    $config['username'],
                    $config['password'],
                    $config['options'] ?? [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );
            } catch (PDOException $e) {
                error_log("Database connection failed: " . $e->getMessage());
                throw new \Exception("Database connection failed. Please check your configuration.");
            }
        }
        
        return self::$instance;
    }

    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetch(string $sql, array $params = []): ?array
    {
        $result = self::query($sql, $params)->fetch();
        return $result ?: null;
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    public static function tableExists(string $tableName): bool
    {
        try {
            $result = self::fetchAll("SHOW TABLES LIKE ?", [$tableName]);
            return !empty($result);
        } catch (\Exception $e) {
            return true; 
        }
    }

    public static function insert(string $table, array $data): int
    {
        $data = self::convertBooleans($data);
        $columnsEscaped = [];
        foreach (array_keys($data) as $key) {
            $columnsEscaped[] = $key; // Removed backticks for PostgreSQL compatibility
        }
        $columns = implode(', ', $columnsEscaped);
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        self::query($sql, array_values($data));
        
        return (int) self::getInstance()->lastInsertId();
    }

    public static function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $data = self::convertBooleans($data);
        $setParts = [];
        foreach (array_keys($data) as $key) {
            $setParts[] = "$key = ?"; // Removed backticks for PostgreSQL compatibility
        }
        $set = implode(', ', $setParts);
        $sql = "UPDATE {$table} SET {$set} WHERE {$where}";
        
        return self::query($sql, array_merge(array_values($data), $whereParams))->rowCount();
    }

    public static function delete(string $table, string $where, array $params = []): int
    {
        $sql = "DELETE FROM {$table} WHERE {$where}"; // Removed backticks for PostgreSQL compatibility
        return self::query($sql, $params)->rowCount();
    }
}
