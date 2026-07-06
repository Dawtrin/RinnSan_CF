import { apiUrl, USE_MOCK } from '../config/api.js';
import { MOCK_MENU, MOCK_BEST_SELLERS, getMockProduct } from '../data/mockData.js';
import {
  mockGetCart,
  mockAddToCart,
  mockUpdateCart,
  mockRemoveFromCart,
  mockClearCart,
} from './mockStore.js';

function parseBody(init) {
  if (!init?.body) return {};
  try {
    return JSON.parse(init.body);
  } catch {
    return {};
  }
}

function mockResponse(path, method, init) {
  const body = parseBody(init);

  if (path === '/api/menu' && method === 'GET') {
    return { success: true, data: MOCK_MENU, message: 'Lấy thực đơn thành công (mock)' };
  }

  if (path === '/api/products/best-sellers' && method === 'GET') {
    return { success: true, data: MOCK_BEST_SELLERS };
  }

  const productMatch = path.match(/^\/api\/products\/(\d+)$/);
  if (productMatch && method === 'GET') {
    const product = getMockProduct(productMatch[1]);
    if (!product) return { success: false, message: 'Sản phẩm không tồn tại' };
    return { success: true, data: product };
  }

  if (path === '/api/cart' && method === 'GET') return mockGetCart();
  if (path === '/api/cart/add' && method === 'POST') return mockAddToCart(body);
  if (path === '/api/cart/update' && method === 'PUT') return mockUpdateCart(body);
  if (path === '/api/cart/remove' && method === 'DELETE') return mockRemoveFromCart(body);
  if (path === '/api/cart/clear' && method === 'DELETE') return mockClearCart();

  if (path === '/api/coupons/validate' && method === 'POST') {
    const code = (body.code || '').toUpperCase();
    if (code === 'RINNSAN10') {
      const orderAmount = Number(body.order_amount) || 0;
      const discountAmount = Math.round(orderAmount * 0.1);
      return {
        success: true,
        data: {
          coupon: { code, discount_type: 'percent', discount_value: 10 },
          discount_amount: discountAmount,
        },
      };
    }
    return { success: false, message: 'Mã không hợp lệ. Thử RINNSAN10' };
  }

  if (path === '/api/orders' && method === 'POST') {
    const subtotal = (body.items || []).reduce((sum, i) => sum + Number(i.total || 0), 0);
    const totalAmount = Math.max(0, subtotal + Number(body.shipping_fee || 0) - Number(body.discount_amount || 0));
    return {
      success: true,
      data: {
        order_id: `DEMO-${Date.now()}`,
        order_code: `RS${String(Date.now()).slice(-6)}`,
        total_amount: totalAmount,
        status: 'pending',
      },
      message: 'Đặt hàng demo thành công',
    };
  }

  if (path === '/api/auth/login' && method === 'POST') {
    return {
      success: true,
      data: {
        token: 'mock-demo-token',
        user: { id: 1, username: body.username || 'demo', role: 'admin' },
      },
    };
  }

  return { success: false, message: `Mock API chưa hỗ trợ: ${method} ${path}` };
}

/**
 * Gọi API thật hoặc mock (khi VITE_USE_MOCK=true)
 */
export async function apiFetch(path, init = {}) {
  const normalized = path.startsWith('/') ? path : `/${path}`;
  const method = (init.method || 'GET').toUpperCase();

  if (USE_MOCK) {
    await new Promise((r) => setTimeout(r, 200));
    return mockResponse(normalized, method, init);
  }

  try {
    const res = await fetch(apiUrl(normalized), init);
    const json = await res.json();
    if (json?.success !== false && (res.ok || json?.data)) return json;
  } catch {
    // fallback below
  }

  // Production FE-only: fallback mock nếu API không có
  if (import.meta.env.PROD) {
    return mockResponse(normalized, method, init);
  }

  throw new Error(`Không thể kết nối API: ${normalized}`);
}
