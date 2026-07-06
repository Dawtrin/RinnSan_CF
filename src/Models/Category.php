<?php

namespace Rinnsan\RinnSanWeb\Models;

use Rinnsan\RinnSanWeb\Core\Database;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'name', 'slug', 'description', 'image', 'parent_id', 
        'sort_order', 'is_active'
    ];

    /**
     * Lấy tất cả danh mục active
     */
    public static function getAllActive()
    {
        $sql = "SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order ASC, name ASC";
        return Database::fetchAll($sql);
    }

    /**
     * Lấy danh mục cha (parent = null)
     */
    public static function getParents()
    {
        $sql = "SELECT * FROM categories WHERE parent_id IS NULL AND is_active = 1 
                ORDER BY sort_order ASC, name ASC";
        return Database::fetchAll($sql);
    }

    /**
     * Lấy danh mục con
     */
    public static function getChildren($parentId)
    {
        $sql = "SELECT * FROM categories WHERE parent_id = ? AND is_active = 1 
                ORDER BY sort_order ASC, name ASC";
        return Database::fetchAll($sql, [$parentId]);
    }

    /**
     * Lấy danh mục theo slug
     */
    public static function findBySlug($slug)
    {
        $sql = "SELECT * FROM categories WHERE slug = ? AND is_active = 1";
        return Database::fetch($sql, [$slug]);
    }

    /**
     * Lấy danh mục kèm số lượng sản phẩm
     */
    public static function getAllWithProductCount()
    {
        $sql = "SELECT c.*, COUNT(p.id) as product_count 
                FROM categories c 
                LEFT JOIN products p ON c.id = p.category_id AND p.is_active = 1
                WHERE c.is_active = 1
                GROUP BY c.id, c.name, c.slug, c.description, c.image, c.parent_id, 
                         c.sort_order, c.is_active, c.created_at, c.updated_at
                ORDER BY c.sort_order ASC, c.name ASC";
        
        return Database::fetchAll($sql);
    }

    /**
     * [MỚI] Gọi Stored Procedure lấy Full Menu
     * SQL Server trả về JSON thông qua FOR JSON PATH
     */
    public static function getMenuFromProc()
    {
        // Gọi thủ tục GetFullMenu đã tạo trong SQL Server
        $sql = "EXEC GetFullMenu";
        return Database::fetchAll($sql);
    }
}