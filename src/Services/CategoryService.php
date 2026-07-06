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

    /**
     * [MỚI] Lấy Full Menu và xử lý JSON từ SQL Server
     */
    public function getFullMenu()
    {
        // 1. Gọi xuống Model để chạy EXEC GetFullMenu
        $rawResult = Category::getMenuFromProc();

        if (empty($rawResult)) {
            return [];
        }

        // 2. Xử lý kết quả từ SQL Server FOR JSON
        // SQL Server có thể trả về chuỗi JSON bị chia thành nhiều dòng nếu quá dài
        $jsonString = '';
        foreach ($rawResult as $row) {
            // Lấy giá trị của cột đầu tiên (thường không có tên hoặc tên ngẫu nhiên)
            $jsonString .= reset($row);
        }

        // 3. Chuyển đổi chuỗi JSON thành Mảng PHP
        $menuData = json_decode($jsonString, true);

        return $menuData ?: [];
    }
}