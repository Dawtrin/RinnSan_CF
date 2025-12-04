<?php

namespace Rinnsan\RinnSanWeb\Controllers\Api;

use Rinnsan\RinnSanWeb\Models\Product;
use Rinnsan\RinnSanWeb\Services\CartService;

class CartController extends ApiController
{
    /**
     * Lấy giỏ hàng
     * GET /api/cart
     */
    public function index()
    {
        try {
            $service = new CartService();
            return $this->success($service->getCart(), 'Lấy giỏ hàng thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     * POST /api/cart/add
     */
    public function add()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['product_id']) || !isset($data['quantity'])) {
                return $this->error('Thiếu product_id hoặc quantity', 400);
            }
            
            $service = new CartService();
            $result = $service->addItem($data['product_id'], (int)$data['quantity'], [
                'variant_combination' => $data['variant_combination'] ?? null,
                'note' => $data['note'] ?? null,
            ]);
            if ($result === false) {
                return $this->error('Sản phẩm không tồn tại', 404);
            }
            return $this->success($result, 'Thêm vào giỏ hàng thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Cập nhật số lượng trong giỏ hàng
     * PUT /api/cart/update
     */
    public function update()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['product_id']) || !isset($data['quantity'])) {
                return $this->error('Thiếu product_id hoặc quantity', 400);
            }
            $service = new CartService();
            $result = $service->updateItem($data['product_id'], (int)$data['quantity']);
            if ($result === false) {
                return $this->error('Sản phẩm không có trong giỏ hàng', 404);
            }
            return $this->success($result, 'Cập nhật giỏ hàng thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     * DELETE /api/cart/remove
     */
    public function remove()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!isset($data['product_id'])) {
                return $this->error('Thiếu product_id', 400);
            }
            $service = new CartService();
            $result = $service->removeItem($data['product_id']);
            return $this->success($result, 'Xóa khỏi giỏ hàng thành công');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Xóa toàn bộ giỏ hàng
     * DELETE /api/cart/clear
     */
    public function clear()
    {
        try {
            $service = new CartService();
            $result = $service->clear();
            return $this->success($result, 'Đã xóa toàn bộ giỏ hàng');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Tạo key cho giỏ hàng
     */
    private function getCartKey($productId, $variantCombination = null)
    {
        $key = (string)$productId;
        if ($variantCombination) {
            if (is_array($variantCombination)) {
                $key .= '_' . md5(json_encode($variantCombination));
            } else {
                $key .= '_' . md5($variantCombination);
            }
        }
        return $key;
    }
}

