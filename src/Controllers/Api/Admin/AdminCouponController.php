<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api\Admin;

use Rinnsan\RinnSanWeb\Controllers\Api\ApiController;
use Rinnsan\RinnSanWeb\Core\Database;

class AdminCouponController extends ApiController
{
    /**
     * Lấy danh sách Voucher
     */
    public function index() {
        try {
            // Lấy tất cả, mới nhất lên đầu
            $coupons = Database::fetchAll("SELECT * FROM coupons ORDER BY created_at DESC");
            return $this->success($coupons, 'Lấy danh sách thành công');
        } catch (\Exception $e) { 
            return $this->error($e->getMessage(), 500); 
        }
    }

    /**
     * Tạo Voucher mới
     */
    public function store() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['id'])) unset($data['id']);

            // 1. Xử lý dữ liệu
            // Nếu ngày rỗng -> chuyển thành NULL để tránh lỗi SQL
            if (empty($data['valid_from'])) $data['valid_from'] = null;
            if (empty($data['valid_to'])) $data['valid_to'] = null;

            // Kiểm tra mã trùng
            if (empty($data['code'])) return $this->error('Thiếu mã giảm giá', 400);
            $exists = Database::fetch("SELECT id FROM coupons WHERE code = ?", [$data['code']]);
            if ($exists) return $this->error('Mã này đã tồn tại', 400);

            // 2. Insert trực tiếp
            // Lọc chỉ lấy các trường có trong DB để tránh lỗi thừa trường
            $allowFields = ['code', 'discount_type', 'discount_value', 'min_order_amount', 'max_discount_amount', 'usage_limit', 'valid_from', 'valid_to', 'is_active'];
            $insertData = array_intersect_key($data, array_flip($allowFields));

            $columns = implode(", ", array_keys($insertData));
            $placeholders = implode(", ", array_fill(0, count($insertData), "?"));
            $values = array_values($insertData);
            
            $sql = "INSERT INTO coupons ($columns) VALUES ($placeholders)";
            Database::query($sql, $values);

            // 3. Trả về thành công ngay lập tức
            return $this->success([], 'Tạo voucher thành công', 201);

        } catch (\Exception $e) { 
            return $this->error("Lỗi tạo Voucher: " . $e->getMessage(), 500); 
        }
    }

    /**
     * Cập nhật Voucher
     */
    public function update($id) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['id'])) unset($data['id']);

            // Xử lý ngày null
            if (array_key_exists('valid_from', $data) && empty($data['valid_from'])) $data['valid_from'] = null;
            if (array_key_exists('valid_to', $data) && empty($data['valid_to'])) $data['valid_to'] = null;

            // Kiểm tra tồn tại
            $curr = Database::fetch("SELECT id FROM coupons WHERE id = ?", [$id]);
            if (!$curr) return $this->error('Voucher không tồn tại', 404);

            // Build SQL Update
            $allowFields = ['code', 'discount_type', 'discount_value', 'min_order_amount', 'max_discount_amount', 'usage_limit', 'valid_from', 'valid_to', 'is_active'];
            $updateData = array_intersect_key($data, array_flip($allowFields));

            if (empty($updateData)) return $this->success([], 'Không có gì thay đổi');

            $sets = [];
            $values = [];
            foreach ($updateData as $key => $val) {
                $sets[] = "$key = ?";
                $values[] = $val;
            }
            $values[] = $id;
            
            $sql = "UPDATE coupons SET " . implode(", ", $sets) . " WHERE id = ?";
            Database::query($sql, $values);

            return $this->success([], 'Cập nhật voucher thành công');

        } catch (\Exception $e) { 
            return $this->error($e->getMessage(), 500); 
        }
    }

    /**
     * Xóa Voucher
     */
    public function destroy($id) {
        try {
            Database::query("DELETE FROM coupons WHERE id = ?", [$id]);
            return $this->success([], 'Xóa thành công');
        } catch (\Exception $e) { 
            return $this->error($e->getMessage(), 500); 
        }
    }
}