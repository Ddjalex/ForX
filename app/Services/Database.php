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
                $host = 'localhost';
                $port = '3306';
                $dbname = 'alphacp_ForX';
                $user = 'alphacp_ForX';
                $password = 'ale2y3t4h5';

                $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

                error_log("Connecting to MySQL: $host:$port/$dbname with user: $user");

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

                error_log("✓ Database connection successful!");
            } catch (PDOException $e) {
                error_log("✗ Database Connection Error: " . $e->getMessage());
                throw $e;
            }
        }

        return self::$connection;
    }

    public static function insert(string $table, array $data): int
    {
        try {
            $keys = array_keys($data);
            $placeholders = array_fill(0, count($keys), '?');
            $keyList = implode('`, `', $keys);

            $sql = "INSERT INTO `$table` (`$keyList`) VALUES (" . implode(', ', $placeholders) . ")";

            error_log("INSERT SQL: $sql");
            error_log("INSERT DATA: " . json_encode($data));

            $stmt = self::getConnection()->prepare($sql);
            
            $success = $stmt->execute(array_values($data));
            error_log("Execute result: " . ($success ? "TRUE" : "FALSE"));

            $lastId = self::getConnection()->lastInsertId();
            error_log("Last Insert ID: $lastId");

            return (int)$lastId;
        } catch (PDOException $e) {
            error_log("✗ INSERT Error: " . $e->getMessage());
            throw $e;
        }
    }

    public static function update(string $table, array $data, string $where, array $params = []): int
    {
        try {
            $sets = [];
            foreach (array_keys($data) as $key) {
                $sets[] = "`$key` = ?";
            }
            $setClause = implode(', ', $sets);

            $sql = "UPDATE `$table` SET $setClause WHERE $where";

            error_log("UPDATE SQL: $sql");
            error_log("UPDATE DATA: " . json_encode($data) . " PARAMS: " . json_encode($params));

            $stmt = self::getConnection()->prepare($sql);
            $stmt->execute(array_merge(array_values($data), $params));

            $affected = $stmt->rowCount();
            error_log("Rows affected: $affected");

            return $affected;
        } catch (PDOException $e) {
            error_log("✗ UPDATE Error: " . $e->getMessage());
            throw $e;
        }
    }

    public static function fetch(string $sql, array $params = []): ?array
    {
        try {
            $stmt = self::getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch() ?: null;
        } catch (PDOException $e) {
            error_log("✗ FETCH Error: " . $e->getMessage());
            throw $e;
        }
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        try {
            $stmt = self::getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll() ?: [];
        } catch (PDOException $e) {
            error_log("✗ FETCHALL Error: " . $e->getMessage());
            throw $e;
        }
    }

    public static function query(string $sql, array $params = []): void
    {
        try {
            $stmt = self::getConnection()->prepare($sql);
            $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("✗ QUERY Error: " . $e->getMessage());
            throw $e;
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
