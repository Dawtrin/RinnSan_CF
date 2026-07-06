<?php

namespace Rinnsan\RinnSanWeb\Core;

class Database
{
    private static $instance = null;
    protected static $connection = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        self::connect(); 
        return self::$instance;
    }

    public static function connect()
    {
        if (self::$connection) {
            return self::$connection;
        }

        // 1. Lấy cấu hình từ .env
        $type = $_ENV['DB_TYPE'] ?? 'mysql'; // sqlsrv hoặc mysql
        $host = $_ENV['DB_HOST'] ?? 'localhost';
        $port = $_ENV['DB_PORT'] ?? '1433';
        $dbname = $_ENV['DB_DATABASE'] ?? 'RinnSanCF';
        $user = $_ENV['DB_USERNAME'] ?? '';
        $pass = $_ENV['DB_PASSWORD'] ?? '';

        try {
            // 2. Tạo chuỗi kết nối dựa trên loại Database
            if ($type === 'sqlsrv') {
                // --- Cấu hình cho SQL SERVER ---
                // Format: sqlsrv:Server=localhost,1433;Database=TenDB
                $dsn = "sqlsrv:Server={$host},{$port};Database={$dbname};TrustServerCertificate=true";
            } else {
                // --- Cấu hình cho MySQL (Dự phòng) ---
                $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
            }
            
            // 3. Kết nối
            $options = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ];

            // Nếu dùng MySQL thì tắt Emulate Prepares để an toàn hơn
            if ($type === 'mysql') {
                $options[\PDO::ATTR_EMULATE_PREPARES] = false;
            }

            self::$connection = new \PDO($dsn, $user, $pass, $options);
            return self::$connection;

        } catch (\PDOException $e) {
            // Ném lỗi ra để index.php bắt và trả về JSON
            throw new \Exception("Lỗi kết nối Database ({$type}): " . $e->getMessage());
        }
    }

    public static function query($sql, $params = [])
    {
        $pdo = self::connect();
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (\PDOException $e) {
            throw new \Exception("Lỗi truy vấn SQL: " . $e->getMessage());
        }
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