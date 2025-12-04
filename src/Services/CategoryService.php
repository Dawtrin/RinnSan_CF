<?php

namespace Rinnsan\RinnSanWeb\Services;

use Rinnsan\RinnSanWeb\Models\Category;
use Rinnsan\RinnSanWeb\Models\Product;
use Rinnsan\RinnSanWeb\Core\Database;

class CategoryService extends Service
{
    public function list($withCount = false, $parentOnly = false)
    {
        if ($withCount) {
            return Category::getAllWithProductCount();
        }
        if ($parentOnly) {
            return Category::getParents();
        }
        return Category::getAllActive();
    }

    public function get($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return null;
        }
        $category['products'] = Product::getByCategory($id);
        if (!$category['parent_id']) {
            $category['children'] = Category::getChildren($id);
        }
        return $category;
    }

    public function getBySlug($slug)
    {
        $category = Category::findBySlug($slug);
        if (!$category) {
            return null;
        }
        $category['products'] = Product::getByCategory($category['id']);
        return $category;
    }

    public function create($data)
    {
        Category::create($data);
        return Category::find(Database::lastInsertId());
    }

    public function update($id, $data)
    {
        Category::update($id, $data);
        return Category::find($id);
    }
}

