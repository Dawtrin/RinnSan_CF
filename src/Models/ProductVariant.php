<?php

namespace Rinnsan\RinnSanWeb\Models;

use Rinnsan\RinnSanWeb\Core\Database;

class ProductVariant extends Model
{
    protected $table = 'product_variants';
    protected $fillable = [
        'product_id', 'name', 'variant_values', 'is_required', 'sort_order'
    ];

    /**
     * Lấy variants theo product_id
     */
    public static function getByProductId($productId)
    {
        $sql = "SELECT * FROM product_variants WHERE product_id = ? ORDER BY sort_order ASC";
        return Database::fetchAll($sql, [$productId]);
    }

    /**
     * Parse variant_values từ JSON
     */
    public static function parseVariantValues($variant)
    {
        if (isset($variant['variant_values']) && is_string($variant['variant_values'])) {
            $variant['variant_values'] = json_decode($variant['variant_values'], true) ?: [];
        }
        return $variant;
    }
}

