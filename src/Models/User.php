<?php

namespace Rinnsan\RinnSanWeb\Models;

use Rinnsan\RinnSanWeb\Core\Database;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'username', 'email', 'password', 'full_name', 'phone', 
        'avatar', 'role_id', 'email_verified_at', 'is_active', 'last_login_at'
    ];
    protected $hidden = ['password'];

    public static function findByEmail($email)
    {
        $results = self::where('email', '=', $email);
        return $results[0] ?? null;
    }

    public static function findByUsername($username)
    {
        $sql = "SELECT * FROM users WHERE username = ?";
        return Database::fetch($sql, [$username]);
    }

    public static function create($data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return parent::create($data);
    }

    public static function update($id, $data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $data['updated_at'] = date('Y-m-d H:i:s');
        return parent::update($id, $data);
    }

    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

    /**
     * Lấy user kèm role
     */
    public static function findWithRole($id)
    {
        $sql = "SELECT u.*, r.name as role_name 
                FROM users u 
                LEFT JOIN roles r ON u.role_id = r.id 
                WHERE u.id = ?";
        
        return Database::fetch($sql, [$id]);
    }
}