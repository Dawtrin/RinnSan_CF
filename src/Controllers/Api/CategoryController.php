<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Models\Category;
use Rinnsan\RinnSanWeb\Models\Product;
use Rinnsan\RinnSanWeb\Core\Database;

class CategoryController extends ApiController
{
    /**
     * Lấy danh sách danh mục
     * GET /api/categories
     */
    public function index()
    {
        try {
            $withCount = isset($_GET['with_count']) && $_GET['with_count'] == '1';
            $parentOnly = isset($_GET['parent_only']) && $_GET['parent_only'] == '1';
            
            if ($withCount) {
                $categories = Category::getAllWithProductCount();
            } elseif ($parentOnly) {
                $categories = Category::getParents();
            } else {
                $categories = Category::getAllActive();
            }
            
            return $this->success($categories, 'Lấy danh sách danh mục thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Lấy chi tiết danh mục
     * GET /api/categories/{id}
     */
    public function show($id)
    {
        try {
            $category = Category::find($id);
            
            if (!$category) {
                return $this->error('Danh mục không tồn tại', 404);
            }
            
            // Lấy sản phẩm trong danh mục
            $category['products'] = Product::getByCategory($id);
            
            // Lấy danh mục con nếu có
            if (!$category['parent_id']) {
                $category['children'] = Category::getChildren($id);
            }
            
            return $this->success($category, 'Lấy chi tiết danh mục thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Lấy danh mục theo slug
     * GET /api/categories/slug/{slug}
     */
    public function showBySlug($slug)
    {
        try {
            $category = Category::findBySlug($slug);
            
            if (!$category) {
                return $this->error('Danh mục không tồn tại', 404);
            }
            
            $category['products'] = Product::getByCategory($category['id']);
            
            return $this->success($category, 'Lấy danh mục thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Tạo danh mục mới
     * POST /api/categories
     */
    public function store()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data) {
                return $this->error('Dữ liệu không hợp lệ', 400);
            }
            
            $required = ['name', 'slug'];
            foreach ($required as $field) {
                if (!isset($data[$field])) {
                    return $this->error("Thiếu trường bắt buộc: $field", 400);
                }
            }
            
            Category::create($data);
            $category = Category::find(Database::lastInsertId());
            
            return $this->success($category, 'Tạo danh mục thành công', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Cập nhật danh mục
     * PUT /api/categories/{id}
     */
    public function update($id)
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                return $this->error('Danh mục không tồn tại', 404);
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data) {
                return $this->error('Dữ liệu không hợp lệ', 400);
            }
            
            Category::update($id, $data);
            $category = Category::find($id);
            
            return $this->success($category, 'Cập nhật danh mục thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

