<?php

namespace Rinnsan\RinnSanWeb\Core;

class Database
{
    protected static $connection = null;

    public static function connect()
    {
        if (self::$connection) {
            return self::$connection;
        }

        $config = require __DIR__ . '/../../config/config.php';
        $db = $config['database'];

        try {
            $dsn = "mysql:host={$db['host']}:{$db['port']};dbname={$db['database']};charset={$db['charset']}";
            self::$connection = new \PDO($dsn, $db['username'], $db['password'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ]);
            return self::$connection;
        } catch (\PDOException $e) {
            die('Database Error: ' . $e->getMessage());
        }
    }

    public static function query($sql, $params = [])
    {
        $pdo = self::connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetch($sql, $params = [])
    {
        return self::query($sql, $params)->fetch();
    }

    public static function fetchAll($sql, $params = [])
    {
        return self::query($sql, $params)->fetchAll();
    }
}
