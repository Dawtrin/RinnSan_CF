import { findMenuProduct, getMockProduct } from '../data/mockData.js';

const CART_KEY = 'rinnsan_mock_cart';

function makeKey(productId, options = {}) {
  return `${productId}_${btoa(JSON.stringify(options)).replace(/=/g, '')}`;
}

function loadCart() {
  try {
    const raw = localStorage.getItem(CART_KEY);
    const parsed = raw ? JSON.parse(raw) : [];
    return Array.isArray(parsed) ? parsed : [];
  } catch {
    return [];
  }
}

function saveCart(items) {
  localStorage.setItem(CART_KEY, JSON.stringify(items));
}

function buildCartResponse(items) {
  const cartItems = [];
  let subtotal = 0;
  let totalQuantity = 0;

  items.forEach(({ key, product_id: productId, quantity, options }) => {
    const product = getMockProduct(productId) || findMenuProduct(productId);
    if (!product) return;

    const price = Number(product.price ?? product.Price) || 0;
    const qty = Number(quantity) || 1;
    const lineTotal = price * qty;

    cartItems.push({
      key,
      product_id: Number(productId),
      name: product.name || product.ProductName,
      price,
      image: product.image || product.MainImage,
      quantity: qty,
      options: options || {},
      total: lineTotal,
    });

    subtotal += lineTotal;
    totalQuantity += qty;
  });

  return {
    success: true,
    data: {
      items: cartItems,
      summary: {
        item_count: cartItems.length,
        quantity_total: totalQuantity,
        subtotal,
      },
    },
  };
}

export function mockGetCart() {
  return buildCartResponse(loadCart());
}

export function mockAddToCart(body) {
  const items = loadCart();
  const productId = Number(body.product_id);
  const options = body.options || {};
  const key = makeKey(productId, options);
  const qty = Math.max(1, Number(body.quantity) || 1);
  const existing = items.find((item) => item.key === key);

  if (existing) {
    existing.quantity += qty;
  } else {
    items.push({ key, product_id: productId, quantity: qty, options });
  }

  saveCart(items);
  return mockGetCart();
}

export function mockUpdateCart(body) {
  const items = loadCart();
  const target = items.find((item) => item.key === body.key);
  if (target) {
    target.quantity = Math.max(1, Number(body.quantity) || 1);
    saveCart(items);
  }
  return mockGetCart();
}

export function mockRemoveFromCart(body) {
  const items = loadCart().filter((item) => item.key !== body.key);
  saveCart(items);
  return mockGetCart();
}

export function mockClearCart() {
  saveCart([]);
  return mockGetCart();
}
