import { useEffect, useState } from 'react';
import { fetchProducts } from '../api/products';
import { addToCart } from '../api/cart';
import { Link } from 'react-router-dom';

export default function ProductsPage() {
  const [products, setProducts] = useState([]);
  const [message, setMessage] = useState('');

  useEffect(() => {
    fetchProducts().then(setProducts);
  }, []);

  const handleAddToCart = async (productId) => {
    const data = await addToCart(productId);
    setMessage(data.message || 'Added to cart');
  };

  return (
    <div>
      <nav style={{ marginBottom: '20px' }}>
        <Link to="/cart" style={{ fontWeight: 'bold', fontSize: '18px' }}>
          My Cart
        </Link>
      </nav>

      {message && <p style={{ color: 'green' }}>{message}</p>}

      {products.map((product) => (
        <div key={product.id} style={{ borderBottom: '1px solid #ddd', padding: '10px 0' }}>
          <p>{product.name}</p>
          <button onClick={() => handleAddToCart(product.id)}>Add to Cart</button>
        </div>
      ))}
    </div>
  );
}
