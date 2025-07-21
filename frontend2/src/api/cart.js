import api from './axios';

// دالة لجلب محتوى العربة
export async function fetchCart() {
  try {
    const response = await api.get('/cart');
    return response.data;
  } catch (error) {
    console.error('Error fetching cart:', error.response?.data || error.message);
    throw error;
  }
}

// دالة لإضافة منتج إلى العربة
export async function addToCart(productId, quantity = 1) {
  try {
    const response = await api.post('/cart/add', { product_id: productId, quantity });
    return response.data;
  } catch (error) {
    console.error('Error adding to cart:', error.response?.data || error.message);
    throw error;
  }
}
