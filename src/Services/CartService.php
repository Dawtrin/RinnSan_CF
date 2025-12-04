<?php

namespace Rinnsan\RinnSanWeb\Services;

use Rinnsan\RinnSanWeb\Models\Product;

class CartService extends Service
{
    private function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function getCart()
    {
        $this->startSession();
        $cart = $_SESSION['cart'] ?? [];
        return $this->formatCart($cart);
    }

    public function addItem($productId, $quantity = 1, $options = [])
    {
        $this->startSession();
        $product = Product::find($productId);
        if (!$product) {
            return false;
        }
        $cart = $_SESSION['cart'] ?? [];
        $key = (string)$productId;
        if (!isset($cart[$key])) {
            $cart[$key] = [
                'product_id' => $productId,
                'name' => $product['name'],
                'price' => (float)$product['price'],
                'quantity' => 0,
                'options' => $options,
            ];
        }
        $cart[$key]['quantity'] += (int)$quantity;
        $_SESSION['cart'] = $cart;
        return $this->formatCart($cart);
    }

    public function updateItem($productId, $quantity)
    {
        $this->startSession();
        $cart = $_SESSION['cart'] ?? [];
        $key = (string)$productId;
        if (!isset($cart[$key])) {
            return false;
        }
        $cart[$key]['quantity'] = max(0, (int)$quantity);
        if ($cart[$key]['quantity'] === 0) {
            unset($cart[$key]);
        }
        $_SESSION['cart'] = $cart;
        return $this->formatCart($cart);
    }

    public function removeItem($productId)
    {
        $this->startSession();
        $cart = $_SESSION['cart'] ?? [];
        $key = (string)$productId;
        if (isset($cart[$key])) {
            unset($cart[$key]);
        }
        $_SESSION['cart'] = $cart;
        return $this->formatCart($cart);
    }

    public function clear()
    {
        $this->startSession();
        $_SESSION['cart'] = [];
        return $this->formatCart([]);
    }

    private function formatCart($cart)
    {
        $items = array_values($cart);
        $subtotal = 0;
        $quantityTotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            $quantityTotal += $item['quantity'];
        }
        return [
            'items' => $items,
            'summary' => [
                'item_count' => count($items),
                'quantity_total' => $quantityTotal,
                'subtotal' => $subtotal
            ]
        ];
    }
}

