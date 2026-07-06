<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api\Admin;

use Rinnsan\RinnSanWeb\Controllers\Api\ApiController;
use Rinnsan\RinnSanWeb\Core\Database;

class AdminSupplierController extends ApiController
{
    /**
     * Lấy danh sách NCC
     */
    public function index() {
        try {
            // Sắp xếp mới nhất lên đầu
            $suppliers = Database::fetchAll("SELECT * FROM suppliers ORDER BY id DESC");
            return $this->success($suppliers);
        } catch (\Exception $e) { 
            return $this->error($e->getMessage(), 500); 
        }
    }

    /**
     * Tạo NCC mới
     */
    public function store() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['name'])) return $this->error('Tên NCC là bắt buộc', 400);
            
            if (isset($data['id'])) unset($data['id']);
            
            // Lọc trường an toàn
            $allowFields = ['name', 'contact_person', 'phone', 'email', 'address', 'is_active'];
            $insertData = array_intersect_key($data, array_flip($allowFields));

            $columns = implode(", ", array_keys($insertData));
            $placeholders = implode(", ", array_fill(0, count($insertData), "?"));
            $values = array_values($insertData);
            
            $sql = "INSERT INTO suppliers ($columns) VALUES ($placeholders)";
            Database::query($sql, $values);

            return $this->success([], 'Tạo nhà cung cấp thành công', 201);

        } catch (\Exception $e) { 
            return $this->error("Lỗi tạo NCC: " . $e->getMessage(), 500); 
        }
    }

    /**
     * Cập nhật NCC
     */
    public function update($id) {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['id'])) unset($data['id']);

            $exists = Database::fetch("SELECT id FROM suppliers WHERE id = ?", [$id]);
            if (!$exists) return $this->error('NCC không tồn tại', 404);

            $allowFields = ['name', 'contact_person', 'phone', 'email', 'address', 'is_active'];
            $updateData = array_intersect_key($data, array_flip($allowFields));

            $sets = [];
            $values = [];
            foreach ($updateData as $key => $val) {
                $sets[] = "$key = ?";
                $values[] = $val;
            }
            $values[] = $id;

            $sql = "UPDATE suppliers SET " . implode(", ", $sets) . " WHERE id = ?";
            Database::query($sql, $values);

            return $this->success([], 'Cập nhật NCC thành công');

        } catch (\Exception $e) { 
            return $this->error($e->getMessage(), 500); 
        }
    }

    /**
     * Xóa NCC
     */
    public function destroy($id) {
        try {
            // Kiểm tra xem NCC có đang cung cấp nguyên liệu trong kho không (tùy chọn)
            // $hasInventory = Database::fetch("SELECT id FROM inventory WHERE supplier_id = ?", [$id]);
            // if ($hasInventory) return $this->error('Không thể xóa NCC đang có hàng trong kho', 400);

            Database::query("DELETE FROM suppliers WHERE id = ?", [$id]);
            return $this->success([], 'Xóa NCC thành công');
        } catch (\Exception $e) { 
            return $this->error($e->getMessage(), 500); 
        }
    }
}