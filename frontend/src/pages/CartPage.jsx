import { useEffect, useState } from 'react';
import { fetchCart } from '../api/cart';

export default function CartPage() {
  const [cart, setCart] = useState(null);

  useEffect(() => {
    fetchCart().then(setCart);
  }, []);

  if (!cart) return <p>Loading cart...</p>;

  if (!cart.products || cart.products.length === 0) {
    return <p>Your cart is empty.</p>;
  }

  return (
    <div>
      <h2>Your Cart</h2>
      {cart.products.map((product) => (
        <div key={product.id}>
          <p>{product.name} x {product.quantity}</p>
          <p>Price: ${product.price}</p>
          <hr />
        </div>
      ))}
    </div>
  );
}
