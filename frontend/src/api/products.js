import api from './axios';

export async function fetchProducts() {
  const response = await api.get('/products');
  return response.data;
}
