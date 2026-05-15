"use client";

import { Heart, Search, ShoppingCart, User } from "lucide-react";
import Link from "next/link";
import { useCart } from "@/context/CartContext";

export function Header() {
  const { itemCount } = useCart();

  return (
    <header className="sticky top-0 z-50 border-b border-black/5 bg-white/80 backdrop-blur-xl">
      <div className="container-shell grid grid-cols-1 items-center gap-4 py-4 lg:grid-cols-[180px_1fr_auto]">
        <Link href="/" className="text-xl font-black uppercase tracking-[0.18em] text-brand">
          Northstar
        </Link>

        <div className="relative">
          <div className="flex items-center gap-3 rounded-full border border-black/10 bg-brand-sand/70 px-5 py-4">
            <Search className="size-5 text-brand" />
            <input
              className="w-full bg-transparent text-sm outline-none placeholder:text-slate-500"
              placeholder="Search for products, brands and more"
            />
          </div>
          <div className="absolute mt-2 hidden w-full rounded-3xl border border-black/10 bg-white p-3 shadow-card md:block">
            <div className="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Suggestions</div>
            <div className="mt-3 grid gap-2 text-sm text-slate-700">
              <div>Northstar X1 Pro Smartphone</div>
              <div>Orbit ANC Wireless Headphones</div>
              <div>Monarch Knit Polo</div>
            </div>
          </div>
        </div>

        <div className="flex items-center gap-3">
          <Link href="/login" className="inline-flex items-center gap-2 rounded-full border border-black/10 bg-white px-4 py-3 text-sm font-semibold">
            <User className="size-4" />
            Login
          </Link>
          <Link href="/wishlist" className="rounded-full border border-black/10 bg-white p-3">
            <Heart className="size-4" />
          </Link>
          <Link href="/cart" className="relative rounded-full border border-black/10 bg-brand px-4 py-3 text-white">
            <ShoppingCart className="size-4" />
            <span className="absolute -right-1 -top-1 inline-flex size-5 items-center justify-center rounded-full bg-brand-accent text-[10px] font-bold">
              {itemCount}
            </span>
          </Link>
        </div>
      </div>
    </header>
  );
}
