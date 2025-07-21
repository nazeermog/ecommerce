import api from './axios';

export async function login(email, password) {
  const response = await api.post('/login', { email, password });
  const token = response.data.access_token;
  localStorage.setItem('token', token);

  api.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}
