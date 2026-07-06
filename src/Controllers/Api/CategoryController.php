<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Services\CategoryService;
use Rinnsan\RinnSanWeb\Core\Database;

class CategoryController extends ApiController
{
    /**
     * API Lấy Full Menu (Mapping dữ liệu khớp với MenuPage.jsx)
     * GET /api/menu
     */
    public function menu()
    {
        try {
            // 1. Lấy danh mục hoạt động
            $sqlCat = "SELECT * FROM categories WHERE is_active = 1 ORDER BY sort_order ASC";
            $categories = Database::fetchAll($sqlCat);
            
            $result = [];

            // 2. Lặp qua từng danh mục để lấy sản phẩm
            foreach ($categories as $cat) {
                // Lấy sản phẩm của danh mục đó
                $sqlProd = "SELECT * FROM products WHERE category_id = ? AND is_active = 1 ORDER BY created_at DESC";
                $products = Database::fetchAll($sqlProd, [$cat['id']]);

                // Map dữ liệu sản phẩm sang chuẩn MenuPage.jsx (PascalCase)
                $items = [];
                foreach ($products as $prod) {
                    // Xử lý ảnh JSON
                    $imageUrl = null;
                    if (!empty($prod['images'])) {
                        $decoded = json_decode($prod['images'], true);
                        $imageUrl = (is_array($decoded) && count($decoded) > 0) ? $decoded[0] : $prod['images'];
                    }

                    $items[] = [
                        'ProductID'   => $prod['id'],
                        'ProductName' => $prod['name'],
                        'Price'       => $prod['price'],
                        'MainImage'   => $imageUrl,
                        'ShortDesc'   => $prod['short_description'],
                        'BadgeTag'    => ($prod['is_featured'] == 1) ? 'BEST SELLER' : null,
                    ];
                }

                // Map dữ liệu danh mục
                $result[] = [
                    'CategoryID'   => $cat['id'],
                    'CategoryName' => $cat['name'],
                    'CategorySlug' => $cat['slug'],
                    'ImageHeader'  => $cat['image'], // Ảnh banner danh mục
                    'items'        => $items
                ];
            }

            return $this->success($result, 'Lấy thực đơn thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    // --- CÁC HÀM KHÁC GIỮ NGUYÊN ---
    public function index()
    {
        try {
            $withCount = isset($_GET['with_count']) && $_GET['with_count'] == '1';
            $parentOnly = isset($_GET['parent_only']) && $_GET['parent_only'] == '1';
            $service = new CategoryService();
            $categories = $service->list($withCount, $parentOnly);
            return $this->success($categories);
        } catch (\Exception $e) { return $this->error($e->getMessage(), 500); }
    }

    public function show($id)
    {
        try {
            $service = new CategoryService();
            $category = $service->get($id);
            if (!$category) return $this->error('Danh mục không tồn tại', 404);
            return $this->success($category);
        } catch (\Exception $e) { return $this->error($e->getMessage(), 500); }
    }

    public function showBySlug($slug)
    {
        try {
            $service = new CategoryService();
            $category = $service->getBySlug($slug);
            if (!$category) return $this->error('Danh mục không tồn tại', 404);
            return $this->success($category);
        } catch (\Exception $e) { return $this->error($e->getMessage(), 500); }
    }

    public function store()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $service = new CategoryService();
            $category = $service->create($data);
            return $this->success($category, 'Tạo danh mục thành công', 201);
        } catch (\Exception $e) { return $this->error($e->getMessage(), 500); }
    }

    public function update($id)
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $service = new CategoryService();
            $category = $service->update($id, $data);
            if (!$category) return $this->error('Danh mục không tồn tại', 404);
            return $this->success($category, 'Cập nhật danh mục thành công');
        } catch (\Exception $e) { return $this->error($e->getMessage(), 500); }
    }
}