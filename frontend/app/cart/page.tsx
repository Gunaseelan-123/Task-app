"use client";

import { useCart } from "@/context/CartContext";

export default function CartPage() {
  const { items } = useCart();

  return (
    <div className="grid gap-8 lg:grid-cols-[1fr_360px]">
      <div className="glass-panel p-8">
        <h1 className="text-4xl font-black tracking-tight">Your Cart</h1>
        <div className="mt-6 space-y-4">
          {items.length ? items.map((item) => (
            <div key={item.id} className="flex items-center justify-between rounded-[24px] border border-black/5 bg-brand-sand/60 p-5">
              <div>
                <div className="font-bold">{item.name}</div>
                <div className="text-sm text-slate-500">Qty: {item.quantity}</div>
              </div>
              <div className="text-lg font-black">INR {(item.price * item.quantity).toLocaleString()}</div>
            </div>
          )) : (
            <div className="rounded-[24px] border border-dashed border-black/10 p-8 text-slate-500">
              Your cart is empty.
            </div>
          )}
        </div>
      </div>
      <aside className="glass-panel h-fit p-8">
        <h2 className="text-2xl font-black">Order Summary</h2>
        <div className="mt-5 space-y-3 text-sm text-slate-600">
          <div className="flex justify-between"><span>Subtotal</span><span>INR {items.reduce((sum, item) => sum + item.price * item.quantity, 0).toLocaleString()}</span></div>
          <div className="flex justify-between"><span>Shipping</span><span>INR 0</span></div>
          <div className="flex justify-between"><span>Taxes</span><span>Calculated at checkout</span></div>
        </div>
        <button className="mt-6 w-full rounded-full bg-brand px-6 py-4 text-sm font-bold text-white">Proceed to Checkout</button>
      </aside>
    </div>
  );
}
