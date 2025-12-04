<?php

namespace Rinnsan\RinnSanWeb\Services;

use Rinnsan\RinnSanWeb\Models\Product;
use Rinnsan\RinnSanWeb\Core\Database;
use Rinnsan\RinnSanWeb\Helpers\CacheHelper;

class ProductService extends Service
{
    public function list($params = [])
    {
        $pagination = $params['pagination'] ?? ['page' => 1, 'per_page' => 20];
        $filters = $params['filters'] ?? [];
        $sort = $params['sort'] ?? ['sort' => 'created_at', 'order' => 'DESC'];
        $search = $params['search'] ?? null;
        $featured = $params['featured'] ?? false;
        $categoryId = $params['category_id'] ?? null;
        $cacheKey = 'products_' . md5(json_encode([$pagination, $filters, $sort, $search, $featured, $categoryId]));
        $products = CacheHelper::remember($cacheKey, function() use ($pagination, $filters, $sort, $search, $featured, $categoryId) {
            if ($search) {
                return Product::search($search, $pagination['per_page']);
            }
            if ($featured) {
                return Product::getFeatured($pagination['per_page']);
            }
            if ($categoryId) {
                return Product::getByCategory($categoryId);
            }
            return Product::paginate($pagination['page'], $pagination['per_page'], $filters, $sort['sort'] . ' ' . $sort['order']);
        }, 300);
        return $products;
    }

    public function get($id)
    {
        $product = Product::findWithCategory($id);
        if (!$product) {
            return null;
        }
        $product['variants'] = Product::getVariants($id);
        $product['options'] = Product::getOptions($id);
        if (isset($product['images']) && is_string($product['images'])) {
            $product['images'] = json_decode($product['images'], true) ?: [];
        }
        if (isset($product['tags']) && is_string($product['tags'])) {
            $product['tags'] = json_decode($product['tags'], true) ?: [];
        }
        foreach ($product['variants'] as &$variant) {
            if (isset($variant['variant_values']) && is_string($variant['variant_values'])) {
                $variant['variant_values'] = json_decode($variant['variant_values'], true) ?: [];
            }
        }
        foreach ($product['options'] as &$option) {
            if (isset($option['variant_combination']) && is_string($option['variant_combination'])) {
                $option['variant_combination'] = json_decode($option['variant_combination'], true) ?: [];
            }
        }
        return $product;
    }

    public function create($data)
    {
        if (isset($data['images']) && is_array($data['images'])) {
            $data['images'] = json_encode($data['images']);
        }
        if (isset($data['tags']) && is_array($data['tags'])) {
            $data['tags'] = json_encode($data['tags']);
        }
        Product::create($data);
        $id = Database::lastInsertId();
        return Product::findWithCategory($id);
    }

    public function update($id, $data)
    {
        if (isset($data['images']) && is_array($data['images'])) {
            $data['images'] = json_encode($data['images']);
        }
        if (isset($data['tags']) && is_array($data['tags'])) {
            $data['tags'] = json_encode($data['tags']);
        }
        Product::update($id, $data);
        return Product::findWithCategory($id);
    }

    public function delete($id)
    {
        Product::delete($id);
        return true;
    }
}

