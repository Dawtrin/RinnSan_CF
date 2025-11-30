<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Models\Product;
use Rinnsan\RinnSanWeb\Core\Database;
use Rinnsan\RinnSanWeb\Helpers\RequestHelper;
use Rinnsan\RinnSanWeb\Helpers\PaginationHelper;
use Rinnsan\RinnSanWeb\Helpers\CacheHelper;

class ProductController extends ApiController
{
    /**
     * Lấy danh sách sản phẩm
     * GET /api/products
     */
    public function index()
    {
        try {
            $categoryId = RequestHelper::input('category_id');
            $featured = RequestHelper::input('featured') == '1';
            $search = RequestHelper::input('search');
            $pagination = RequestHelper::getPaginationParams();
            $filters = RequestHelper::getFilters(['category_id', 'is_featured', 'is_active']);
            $sort = RequestHelper::getSortParams('created_at', 'DESC');
            
            // Cache key
            $cacheKey = 'products_' . md5(json_encode([$categoryId, $featured, $search, $pagination, $filters, $sort]));
            
            // Try cache first
            $products = CacheHelper::remember($cacheKey, function() use ($categoryId, $featured, $search, $pagination, $filters, $sort) {
                if ($search) {
                    return Product::search($search, $pagination['per_page']);
                } elseif ($featured) {
                    return Product::getFeatured($pagination['per_page']);
                } elseif ($categoryId) {
                    return Product::getByCategory($categoryId);
                } else {
                    // Use pagination
                    $conditions = [];
                    if (isset($filters['is_active'])) {
                        $conditions['is_active'] = $filters['is_active'];
                    }
                    if (isset($filters['is_featured'])) {
                        $conditions['is_featured'] = $filters['is_featured'];
                    }
                    
                    $result = Product::paginate($pagination['page'], $pagination['per_page'], $conditions, $sort['sort'] . ' ' . $sort['order']);
                    return $result;
                }
            }, 300); // Cache 5 minutes
            
            // Format response
            if (isset($products['data'])) {
                // Paginated result
                return $this->success($products['data'], 'Lấy danh sách sản phẩm thành công', 200, [
                    'pagination' => $products['pagination']
                ]);
            } else {
                // Simple array
                return $this->success($products, 'Lấy danh sách sản phẩm thành công');
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Lấy chi tiết sản phẩm
     * GET /api/products/{id}
     */
    public function show($id)
    {
        try {
            $product = Product::findWithCategory($id);
            
            if (!$product) {
                return $this->error('Sản phẩm không tồn tại', 404);
            }
            
            // Lấy variants và options
            $product['variants'] = Product::getVariants($id);
            $product['options'] = Product::getOptions($id);
            
            // Parse JSON fields
            if (isset($product['images']) && is_string($product['images'])) {
                $product['images'] = json_decode($product['images'], true) ?: [];
            }
            if (isset($product['tags']) && is_string($product['tags'])) {
                $product['tags'] = json_decode($product['tags'], true) ?: [];
            }
            
            return $this->success($product, 'Lấy chi tiết sản phẩm thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Tạo sản phẩm mới
     * POST /api/products
     */
    public function store()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data) {
                return $this->error('Dữ liệu không hợp lệ', 400);
            }
            
            // Validate required fields
            $required = ['name', 'slug', 'price', 'category_id'];
            foreach ($required as $field) {
                if (!isset($data[$field])) {
                    return $this->error("Thiếu trường bắt buộc: $field", 400);
                }
            }
            
            // Convert arrays to JSON
            if (isset($data['images']) && is_array($data['images'])) {
                $data['images'] = json_encode($data['images']);
            }
            if (isset($data['tags']) && is_array($data['tags'])) {
                $data['tags'] = json_encode($data['tags']);
            }
            
            Product::create($data);
            $product = Product::findWithCategory(Database::lastInsertId());
            
            return $this->success($product, 'Tạo sản phẩm thành công', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Cập nhật sản phẩm
     * PUT /api/products/{id}
     */
    public function update($id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return $this->error('Sản phẩm không tồn tại', 404);
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data) {
                return $this->error('Dữ liệu không hợp lệ', 400);
            }
            
            // Convert arrays to JSON
            if (isset($data['images']) && is_array($data['images'])) {
                $data['images'] = json_encode($data['images']);
            }
            if (isset($data['tags']) && is_array($data['tags'])) {
                $data['tags'] = json_encode($data['tags']);
            }
            
            Product::update($id, $data);
            $product = Product::findWithCategory($id);
            
            return $this->success($product, 'Cập nhật sản phẩm thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Lấy variants của sản phẩm
     * GET /api/products/{id}/variants
     */
    public function variants($id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return $this->error('Sản phẩm không tồn tại', 404);
            }
            
            $variants = Product::getVariants($id);
            
            // Parse JSON fields
            foreach ($variants as &$variant) {
                if (isset($variant['variant_values']) && is_string($variant['variant_values'])) {
                    $variant['variant_values'] = json_decode($variant['variant_values'], true) ?: [];
                }
            }
            
            return $this->success($variants, 'Lấy variants thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Lấy options của sản phẩm
     * GET /api/products/{id}/options
     */
    public function options($id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return $this->error('Sản phẩm không tồn tại', 404);
            }
            
            $options = Product::getOptions($id);
            
            // Parse JSON fields
            foreach ($options as &$option) {
                if (isset($option['variant_combination']) && is_string($option['variant_combination'])) {
                    $option['variant_combination'] = json_decode($option['variant_combination'], true) ?: [];
                }
            }
            
            return $this->success($options, 'Lấy options thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Xóa sản phẩm
     * DELETE /api/products/{id}
     */
    public function destroy($id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return $this->error('Sản phẩm không tồn tại', 404);
            }
            
            Product::delete($id);
            
            return $this->success([], 'Xóa sản phẩm thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}

