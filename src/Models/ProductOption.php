<?php

namespace Rinnsan\RinnSanWeb\Models;

use Rinnsan\RinnSanWeb\Core\Database;

class ProductOption extends Model
{
    protected $table = 'product_options';
    protected $fillable = [
        'product_id', 'variant_combination', 'price_modifier', 'sku', 'quantity'
    ];

    /**
     * Lấy options theo product_id
     */
    public static function getByProductId($productId)
    {
        $sql = "SELECT * FROM product_options WHERE product_id = ?";
        return Database::fetchAll($sql, [$productId]);
    }

    /**
     * Tìm option theo variant combination
     */
    public static function findByVariant($productId, $variantCombination)
    {
        $combinationJson = is_array($variantCombination) 
            ? json_encode($variantCombination) 
            : $variantCombination;
        
        $sql = "SELECT * FROM product_options 
                WHERE product_id = ? AND variant_combination = ?";
        
        return Database::fetch($sql, [$productId, $combinationJson]);
    }

    /**
     * Parse variant_combination từ JSON
     */
    public static function parseVariantCombination($option)
    {
        if (isset($option['variant_combination']) && is_string($option['variant_combination'])) {
            $option['variant_combination'] = json_decode($option['variant_combination'], true) ?: [];
        }
        return $option;
    }
}

