<?php

namespace Rinnsan\RinnSanWeb\Core;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;

    public function __construct()
    {
        $this->connect();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function connect()
    {
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $port = $_ENV['DB_PORT'] ?? '1433';
        $database = $_ENV['DB_DATABASE'] ?? 'RinnSanCF';
        $username = $_ENV['DB_USERNAME'] ?? 'sa';
        $password = $_ENV['DB_PASSWORD'] ?? '';
        $dbType = $_ENV['DB_TYPE'] ?? 'sqlsrv'; // sqlsrv or mysql

        try {
            if ($dbType === 'sqlsrv') {
                // SQL Server connection
                $dsn = "sqlsrv:Server=$host,$port;Database=$database;Encrypt=no;TrustServerCertificate=yes";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_UTF8,
                ];
            } else {
                // MySQL connection
                $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';
                $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=$charset";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
            }

            $this->connection = new PDO($dsn, $username, $password, $options);
            
        } catch (PDOException $e) {
            throw new \Exception("Database connection failed: " . $e->getMessage() . 
                "\nPlease check your .env file and ensure the database server is running.");
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public static function query($sql, $params = [])
    {
        $db = self::getInstance();
        try {
            $stmt = $db->getConnection()->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            throw new \Exception("Query execution failed: " . $e->getMessage());
        }
    }

    public static function fetchAll($sql, $params = [])
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetchAll();
    }

    public static function fetch($sql, $params = [])
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetch();
    }

    public static function lastInsertId()
    {
        $db = self::getInstance();
        $dbType = $_ENV['DB_TYPE'] ?? 'sqlsrv';
        
        if ($dbType === 'sqlsrv') {
            // SQL Server uses SCOPE_IDENTITY() or OUTPUT clause
            $stmt = $db->getConnection()->query("SELECT SCOPE_IDENTITY() as id");
            $result = $stmt->fetch();
            return $result['id'] ?? null;
        } else {
            return $db->getConnection()->lastInsertId();
        }
    }
}