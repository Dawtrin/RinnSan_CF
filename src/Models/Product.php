<?php

namespace Rinnsan\RinnSanWeb\Models;

use Rinnsan\RinnSanWeb\Core\Database;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'name', 'slug', 'description', 'short_description', 'price', 
        'compare_price', 'cost_price', 'sku', 'barcode', 'category_id',
        'is_featured', 'is_active', 'track_quantity', 'quantity', 
        'sold_count', 'images', 'weight', 'tags', 'meta_title', 'meta_description'
    ];

    /**
     * TÌM SẢN PHẨM THEO ID - PHƯƠNG THỨC QUAN TRỌNG
     */
    public static function find($id)
    {
        try {
            $sql = "SELECT * FROM products WHERE id = ?";
            $result = Database::fetch($sql, [$id]);
            
            if ($result) {
                error_log("Product::find - Found product ID: {$id}, name: " . ($result['name'] ?? 'N/A'));
            } else {
                error_log("Product::find - Product not found with ID: {$id}");
            }
            
            return $result;
        } catch (\Exception $e) {
            error_log("Product::find error for ID {$id}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Lấy tất cả sản phẩm
     */
    public static function all($activeOnly = false)
    {
        try {
            $sql = "SELECT * FROM products";
            if ($activeOnly) {
                $sql .= " WHERE is_active = 1";
            }
            $sql .= " ORDER BY created_at DESC";
            
            return Database::fetchAll($sql);
        } catch (\Exception $e) {
            error_log("Product::all error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Lấy sản phẩm theo danh mục
     */
    public static function getByCategory($categoryId, $activeOnly = true)
    {
        $sql = "SELECT * FROM products WHERE category_id = ?";
        $params = [$categoryId];
        
        if ($activeOnly) {
            $sql .= " AND is_active = 1";
        }
        
        $sql .= " ORDER BY sort_order ASC, created_at DESC";
        
        return Database::fetchAll($sql, $params);
    }

    /**
     * Lấy sản phẩm nổi bật
     */
    public static function getFeatured($limit = 10)
    {
        $sql = "SELECT * FROM products WHERE is_featured = 1 AND is_active = 1 
                ORDER BY created_at DESC";
        
        if ($limit > 0) {
            $sql .= " LIMIT $limit";
        }
        
        return Database::fetchAll($sql);
    }

    /**
     * Lấy sản phẩm mới nhất
     */
    public static function getLatest($limit = 10)
    {
        $sql = "SELECT * FROM products WHERE is_active = 1 
                ORDER BY created_at DESC 
                LIMIT $limit";
        
        return Database::fetchAll($sql);
    }

    /**
     * Tìm kiếm sản phẩm
     */
    public static function search($keyword, $limit = 20)
    {
        $sql = "SELECT * FROM products 
                WHERE (name LIKE ? OR description LIKE ? OR tags LIKE ?) 
                AND is_active = 1
                ORDER BY created_at DESC
                LIMIT $limit";
        
        $searchTerm = "%$keyword%";
        return Database::fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm]);
    }

    /**
     * Lấy sản phẩm kèm danh mục
     */
    public static function findWithCategory($id)
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.id = ?";
        
        return Database::fetch($sql, [$id]);
    }

    /**
     * Lấy variants của sản phẩm
     */
    public static function getVariants($productId)
    {
        $sql = "SELECT * FROM product_variants WHERE product_id = ? ORDER BY sort_order ASC";
        return Database::fetchAll($sql, [$productId]);
    }

    /**
     * Lấy options của sản phẩm
     */
    public static function getOptions($productId)
    {
        $sql = "SELECT * FROM product_options WHERE product_id = ?";
        return Database::fetchAll($sql, [$productId]);
    }

    /**
     * Cập nhật số lượng tồn kho
     */
    public static function updateQuantity($id, $quantity)
    {
        $sql = "UPDATE products SET quantity = ?, updated_at = NOW() WHERE id = ?";
        return Database::query($sql, [$quantity, $id]);
    }

    /**
     * Tăng số lượng đã bán
     */
    public static function incrementSold($id, $quantity = 1)
    {
        $sql = "UPDATE products SET sold_count = sold_count + ?, updated_at = NOW() WHERE id = ?";
        return Database::query($sql, [$quantity, $id]);
    }
}