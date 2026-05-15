"use client";

import { createContext, useContext, useMemo, useState } from "react";
import type { Product } from "@/lib/data";

type CartItem = Product & { quantity: number };

type CartContextValue = {
  items: CartItem[];
  addItem: (product: Product) => void;
  itemCount: number;
};

const CartContext = createContext<CartContextValue | null>(null);

export function CartProvider({ children }: { children: React.ReactNode }) {
  const [items, setItems] = useState<CartItem[]>([]);

  const value = useMemo(() => ({
    items,
    addItem(product: Product) {
      setItems((current) => {
        const existing = current.find((item) => item.id === product.id);
        if (existing) {
          return current.map((item) =>
            item.id === product.id ? { ...item, quantity: item.quantity + 1 } : item
          );
        }
        return [...current, { ...product, quantity: 1 }];
      });
    },
    itemCount: items.reduce((sum, item) => sum + item.quantity, 0),
  }), [items]);

  return <CartContext.Provider value={value}>{children}</CartContext.Provider>;
}

export function useCart() {
  const context = useContext(CartContext);
  if (!context) {
    throw new Error("useCart must be used within CartProvider");
  }
  return context;
}
