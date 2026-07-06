/**
 * Mock data khớp schema API backend (từ database/04, 05_insert_*.sql)
 */

const RAW = [
  { catId: 1, catName: 'Cafe', catSlug: 'cafe', catImage: '/images/categories/cafe.jpg', products: [
    [1, 'Cafe Espresso', 35000, '/images/products/espresso.jpg', 'Espresso đậm đà nguyên chất', 1, 45],
    [2, 'Cafe Latte', 45000, '/images/products/latte.jpg', 'Latte với sữa tươi hảo hạng', 1, 67],
    [3, 'Cappuccino', 45000, '/images/products/cappuccino.jpg', 'Cappuccino foam sữa mịn', 1, 52],
    [4, 'Americano', 40000, '/images/products/americano.jpg', 'Americano đậm vị nhẹ nhàng', 0, 38],
    [5, 'Cold Brew', 50000, '/images/products/cold-brew.jpg', 'Cold Brew ủ lạnh 24h', 1, 28],
    [6, 'Vietnamese Coconut Coffee', 55000, '/images/products/cafe-cot-dua.jpg', 'Cafe cốt dừa Việt Nam', 1, 41],
    [7, 'Hazelnut Latte', 50000, '/images/products/hazelnut-latte.jpg', 'Latte hạnh nhân thơm ngon', 0, 23],
    [8, 'Caramel Macchiato', 50000, '/images/products/caramel-macchiato.jpg', 'Caramel Macchiato ngọt ngào', 1, 34],
    [9, 'Mocha', 50000, '/images/products/mocha.jpg', 'Mocha socola thơm ngon', 0, 29],
  ]},
  { catId: 2, catName: 'Trà', catSlug: 'tra', catImage: '/images/categories/tra.jpg', products: [
    [10, 'Trà sữa trân châu', 55000, '/images/products/trasua-tranchau.jpg', 'Trà sữa trân châu đường đen', 1, 89],
    [11, 'Trà đào cam sả', 45000, '/images/products/tra-dao-cam-sa.jpg', 'Trà đào cam sả thanh mát', 1, 63],
    [12, 'Trà vải', 40000, '/images/products/tra-vai.jpg', 'Trà vải ngọt thanh', 0, 41],
    [13, 'Trà sen vàng', 60000, '/images/products/tra-sen-vang.jpg', 'Trà sen vàng cao cấp', 1, 19],
    [14, 'Trà đen sữa', 40000, '/images/products/tra-den-sua.jpg', 'Trà đen sữa đậm đà', 0, 32],
    [15, 'Trà xanh matcha latte', 50000, '/images/products/matcha-latte.jpg', 'Matcha latte Nhật Bản', 1, 47],
    [16, 'Trà sữa oolong', 45000, '/images/products/tra-sua-oolong.jpg', 'Trà sữa oolong thơm ngon', 0, 25],
    [17, 'Trà đào dâu tây', 48000, '/images/products/tra-dao-dau-tay.jpg', 'Trà đào dâu tây tươi mát', 1, 31],
  ]},
  { catId: 3, catName: 'Đá xay', catSlug: 'da-xay', catImage: '/images/categories/da-xay.jpg', products: [
    [18, 'Chocolate đá xay', 55000, '/images/products/chocolate-da-xay.jpg', 'Chocolate đá xay thơm béo', 1, 72],
    [19, 'Matcha đá xay', 50000, '/images/products/matcha-da-xay.jpg', 'Matcha đá xay trà xanh Nhật', 1, 58],
    [20, 'Caramel đá xay', 55000, '/images/products/caramel-da-xay.jpg', 'Caramel đá xay ngọt ngào', 0, 34],
    [21, 'Cookies & Cream', 55000, '/images/products/cookies-cream.jpg', 'Cookies & Cream đá xay', 1, 51],
    [22, 'Strawberry đá xay', 50000, '/images/products/strawberry-da-xay.jpg', 'Đá xay dâu tươi', 0, 29],
    [23, 'Mango đá xay', 50000, '/images/products/mango-da-xay.jpg', 'Đá xay xoài nhiệt đới', 0, 26],
    [24, 'Blueberry đá xay', 52000, '/images/products/blueberry-da-xay.jpg', 'Đá xay việt quất', 1, 18],
    [25, 'Vanilla đá xay', 48000, '/images/products/vanilla-da-xay.jpg', 'Đá xay vani thơm ngon', 0, 22],
  ]},
  { catId: 4, catName: 'Bánh ngọt', catSlug: 'banh-ngot', catImage: '/images/categories/banh-ngot.jpg', products: [
    [26, 'Tiramisu', 65000, '/images/products/tiramisu.jpg', 'Tiramisu Ý béo ngậy', 1, 95],
    [27, 'Cheesecake dâu', 55000, '/images/products/cheesecake-dau.jpg', 'Cheesecake dâu tươi mát lạnh', 1, 78],
    [28, 'Macaron', 35000, '/images/products/macaron.jpg', 'Macaron Pháp nhiều hương vị', 1, 112],
    [29, 'Croissant', 25000, '/images/products/croissant.jpg', 'Croissant Pháp giòn tan', 0, 67],
    [30, 'Red Velvet', 70000, '/images/products/red-velvet.jpg', 'Red Velvet kem cheese', 1, 63],
    [31, 'Mousse socola', 45000, '/images/products/mousse-socola.jpg', 'Mousse socola mềm mịn', 1, 54],
    [32, 'Bánh flan phô mai', 30000, '/images/products/flan-pho-mai.jpg', 'Flan phô mai béo ngậy', 0, 48],
    [33, 'Donut', 25000, '/images/products/donut.jpg', 'Donut nhiều vị thơm ngon', 0, 72],
    [34, 'Bánh su kem', 20000, '/images/products/banh-su-kem.jpg', 'Bánh su kem nhân kem tươi', 1, 89],
    [35, 'Opera Cake', 75000, '/images/products/opera-cake.jpg', 'Opera Cake nhiều lớp', 1, 26],
  ]},
  { catId: 5, catName: 'Bánh mì', catSlug: 'banh-mi', catImage: '/images/categories/banh-mi.jpg', products: [
    [36, 'Bánh mì que', 15000, '/images/products/banh-mi-que.jpg', 'Bánh mì que giòn rụm', 1, 145],
    [37, 'Bánh mì gối', 20000, '/images/products/banh-mi-goi.jpg', 'Bánh mì gối tươi mềm', 0, 89],
    [38, 'Bánh mì baguette', 18000, '/images/products/baguette.jpg', 'Baguette Pháp giòn tan', 1, 67],
    [39, 'Bánh mì ngọt', 12000, '/images/products/banh-mi-ngot.jpg', 'Bánh mì ngọt mềm xốp', 0, 98],
    [40, 'Bánh mì sandwich', 22000, '/images/products/sandwich-bread.jpg', 'Bánh mì sandwich mềm', 0, 45],
  ]},
  { catId: 6, catName: 'Bánh Âu', catSlug: 'banh-au', catImage: '/images/categories/banh-au.jpg', products: [
    [41, 'Bánh tart trứng', 35000, '/images/products/tart-trung.jpg', 'Tart trứng bồ đào', 1, 56],
    [42, 'Bánh su', 28000, '/images/products/banh-su.jpg', 'Bánh su nhân kem tươi', 0, 43],
    [43, 'Bánh phô mai chanh leo', 45000, '/images/products/pho-mai-chanh-leo.jpg', 'Phô mai chanh leo thanh mát', 1, 38],
    [44, 'Bánh cam', 18000, '/images/products/banh-cam.jpg', 'Bánh cam nhân đậu xanh', 0, 72],
  ]},
  { catId: 7, catName: 'Nước ép', catSlug: 'nuoc-ep', catImage: '/images/categories/nuoc-ep.jpg', products: [
    [45, 'Nước ép cam', 35000, '/images/products/nuoc-ep-cam.jpg', 'Nước ép cam tươi nguyên chất', 1, 56],
    [46, 'Nước ép táo', 30000, '/images/products/nuoc-ep-tao.jpg', 'Nước ép táo tươi ngọt thanh', 0, 43],
    [47, 'Nước ép dưa hấu', 32000, '/images/products/nuoc-ep-dua-hau.jpg', 'Nước ép dưa hấu thanh mát', 1, 39],
    [48, 'Nước ép cà rốt', 28000, '/images/products/nuoc-ep-ca-rot.jpg', 'Nước ép cà rốt tốt cho sức khỏe', 0, 27],
    [49, 'Sinh tố bơ', 40000, '/images/products/sinh-to-bo.jpg', 'Sinh tố bơ béo ngậy', 1, 51],
    [50, 'Sinh tố xoài', 38000, '/images/products/sinh-to-xoai.jpg', 'Sinh tố xoài chín vàng', 0, 44],
  ]},
];

export const MOCK_MENU = RAW.map((cat) => ({
  CategoryID: cat.catId,
  CategoryName: cat.catName,
  CategorySlug: cat.catSlug,
  ImageHeader: cat.catImage,
  items: cat.products.map(([id, name, price, image, desc, featured]) => ({
    ProductID: id,
    ProductName: name,
    Price: price,
    MainImage: image,
    ShortDesc: desc,
    BadgeTag: featured ? 'BEST SELLER' : null,
  })),
}));

const PRODUCT_MAP = new Map();

RAW.forEach((cat) => {
  cat.products.forEach(([id, name, price, image, desc, featured, sold]) => {
    const slug = name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
    PRODUCT_MAP.set(id, {
      id,
      name,
      price,
      image,
      images: [image],
      short_description: desc,
      description: desc,
      slug,
      category_id: cat.catId,
      is_featured: featured,
      sold_count: sold,
    });
  });
});

export const MOCK_BEST_SELLERS = [...PRODUCT_MAP.values()]
  .sort((a, b) => b.sold_count - a.sold_count)
  .slice(0, 8)
  .map((p) => ({
    id: p.id,
    name: p.name,
    price: p.price,
    slug: p.slug,
    image: p.image,
    sales: p.sold_count,
    tag: p.sold_count >= 50 ? 'BEST SELLER' : p.sold_count >= 20 ? 'HOT' : 'POPULAR',
  }));

export const DEFAULT_VARIANTS = [
  { name: 'Size', is_required: 1, variant_values: ['S', 'M', 'L'] },
  { name: 'Đường', is_required: 0, variant_values: ['Ít đường', 'Vừa', 'Nhiều đường'] },
  { name: 'Đá', is_required: 0, variant_values: ['Ít đá', 'Vừa', 'Nhiều đá'] },
];

export function getMockProduct(id) {
  const product = PRODUCT_MAP.get(Number(id));
  if (!product) return null;
  return { ...product, variants: DEFAULT_VARIANTS };
}

export function findMenuProduct(id) {
  for (const cat of MOCK_MENU) {
    const item = cat.items.find((p) => p.ProductID === Number(id));
    if (item) return item;
  }
  return null;
}
