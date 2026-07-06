<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Core\Database;
use Rinnsan\RinnSanWeb\Models\ProductVariant;

class ProductController extends ApiController
{
    /**
     * Lấy danh sách sản phẩm (Admin/Public)
     */
    public function index()
    {
        try {
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
            $search = $_GET['search'] ?? null;
            
            $sql = "SELECT * FROM products WHERE is_active = 1";
            $params = [];

            if ($search) {
                $sql .= " AND name LIKE ?";
                $params[] = "%$search%";
            }
            $sql .= " ORDER BY created_at DESC";
            
            $products = Database::fetchAll($sql, $params);
            
            foreach ($products as &$p) {
                if (!empty($p['images'])) {
                    $decoded = json_decode($p['images'], true);
                    $p['image'] = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded[0] : $p['images'];
                }
            }
            
            return $this->success($products);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * API Best Sellers cho Home
     */
    public function getBestSellers()
    {
        try {
            $sql = "SELECT TOP 8 
                        p.id, 
                        p.name, 
                        p.price, 
                        p.slug,
                        CAST(MAX(CAST(p.images AS NVARCHAR(MAX))) AS NVARCHAR(MAX)) as image_raw,
                        COALESCE(SUM(oi.quantity), 0) as sales
                    FROM order_items oi
                    JOIN orders o ON oi.order_id = o.id
                    JOIN products p ON oi.product_id = p.id
                    WHERE o.order_status = 'completed' AND p.is_active = 1
                    GROUP BY p.id, p.name, p.price, p.slug
                    ORDER BY sales DESC";

            $products = Database::fetchAll($sql);

            foreach ($products as &$product) {
                $imageUrl = null;
                if (!empty($product['image_raw'])) {
                    $decoded = json_decode($product['image_raw'], true);
                    $imageUrl = (is_array($decoded) && count($decoded) > 0) ? $decoded[0] : $product['image_raw'];
                }
                $product['image'] = $imageUrl; 
                unset($product['image_raw']);
                
                $sales = (int)$product['sales'];
                if ($sales >= 50) $product['tag'] = 'BEST SELLER';
                else if ($sales >= 20) $product['tag'] = 'HOT';
                else if ($sales >= 10) $product['tag'] = 'POPULAR';
                else $product['tag'] = null;
            }

            return $this->success($products);
        } catch (\Exception $e) {
            return $this->success([]); 
        }
    }

    /**
     * [QUAN TRỌNG - ĐÃ SỬA] Chi tiết sản phẩm + Kèm theo Variants
     */
    public function show($id) {
        try {
            // 1. Lấy thông tin sản phẩm
            $product = Database::fetch("SELECT * FROM products WHERE id = ?", [$id]);
            
            if (!$product) {
                return $this->error('Sản phẩm không tồn tại', 404);
            }

            // Xử lý ảnh (decode JSON)
            if (!empty($product['images'])) {
                $decodedImg = json_decode($product['images'], true);
                $product['image'] = (is_array($decodedImg) && count($decodedImg) > 0) ? $decodedImg[0] : $product['images'];
                $product['images'] = $decodedImg; 
            }

            // 2. LẤY DANH SÁCH BIẾN THỂ (Size, Đường, Đá...)
            // Đây là đoạn code bạn bị thiếu trước đó
            $variants = Database::fetchAll("SELECT * FROM product_variants WHERE product_id = ? ORDER BY sort_order ASC", [$id]);
            
            // Xử lý dữ liệu Variants (Decode JSON string thành Array)
            foreach ($variants as &$v) {
                if (!empty($v['variant_values'])) {
                    $v['variant_values'] = json_decode($v['variant_values'], true);
                }
            }

            // 3. Gắn variants vào kết quả trả về
            $product['variants'] = $variants;

            return $this->success($product);
            
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Tạo sản phẩm (Admin)
     */
    public function store() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['id'])) unset($data['id']); 

            if (empty($data['name']) || empty($data['price'])) {
                return $this->error('Tên và giá là bắt buộc', 400);
            }

            if (empty($data['slug'])) {
                $data['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['name'])));
            }

            $sql = "INSERT INTO products (name, slug, description, price, compare_price, category_id, is_active, quantity, images) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            Database::query($sql, [
                $data['name'], $data['slug'], $data['description'] ?? '', 
                $data['price'], $data['compare_price'] ?? 0, 
                $data['category_id'], 1, 
                $data['quantity'] ?? 100,
                json_encode($data['images'] ?? [])
            ]);

            return $this->success([], 'Tạo sản phẩm thành công', 201);
        } catch (\Exception $e) { return $this->error($e->getMessage(), 500); }
    }

    /**
     * Cập nhật sản phẩm (Admin)
     */
    public function update($id) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['id'])) unset($data['id']);

            $exists = Database::fetch("SELECT id FROM products WHERE id = ?", [$id]);
            if (!$exists) return $this->error('Sản phẩm không tồn tại', 404);

            $sql = "UPDATE products SET 
                    name = ?, price = ?, compare_price = ?, 
                    description = ?, quantity = ?, category_id = ?, 
                    updated_at = GETDATE() 
                    WHERE id = ?";
            
            Database::query($sql, [
                $data['name'], 
                $data['price'], 
                $data['compare_price'] ?? 0,
                $data['description'] ?? '', 
                $data['quantity'], 
                $data['category_id'], 
                $id
            ]);
            
            return $this->success([], 'Cập nhật thành công');
        } catch (\Exception $e) { return $this->error($e->getMessage(), 500); }
    }

    /**
     * Xóa sản phẩm
     */
    public function destroy($id) {
        try {
            Database::query("DELETE FROM products WHERE id = ?", [$id]);
            return $this->success([], 'Xóa thành công');
        } catch (\Exception $e) { return $this->error($e->getMessage(), 500); }
    }

    public function variants($id) {
        $variants = ProductVariant::getByProductId($id);
        return $this->success($variants);
    }
}