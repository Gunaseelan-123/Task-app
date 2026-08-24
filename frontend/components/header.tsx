"use client";

import { Heart, Search, ShoppingCart, User } from "lucide-react";
import Link from "next/link";
import Image from "next/image";
import { useCart } from "@/context/CartContext";

export function Header() {
  const { itemCount } = useCart();

  return (
    <header className="sticky top-0 z-50 bg-gradient-to-r from-brand-dark to-brand shadow-md">
      <div className="container-shell grid grid-cols-1 items-center gap-4 py-4 lg:grid-cols-[180px_1fr_auto]">
        <Link href="/" className="flex items-center gap-3">
                  <Image src="/flytrack-logo.svg" alt="FlyTrack logo" width={48} height={48} className="rounded-md shadow-sm" />
                  <span className="text-xl font-extrabold uppercase tracking-[0.08em] text-white drop-shadow">FlyTrack</span>
        </Link>

        <div className="relative">
          <div className="flex items-center gap-3 rounded-full bg-white/10 px-4 py-2 backdrop-blur-sm transition-shadow shadow-sm">
            <Search className="size-5 text-white/90" />
            <input
              className="w-full bg-transparent text-sm outline-none placeholder:text-white/70 text-white"
              placeholder="Search for products, brands and more"
            />
          </div>
          <div className="absolute mt-2 hidden w-full rounded-3xl bg-white p-3 shadow-card md:block">
            <div className="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Suggestions</div>
            <div className="mt-3 grid gap-2 text-sm text-slate-700">
              <div>Northstar X1 Pro Smartphone</div>
              <div>Orbit ANC Wireless Headphones</div>
              <div>Monarch Knit Polo</div>
            </div>
          </div>
        </div>

        <div className="flex items-center gap-3">
          <Link href="/login" className="inline-flex items-center gap-2 rounded-full bg-white/20 px-4 py-2 text-sm font-semibold text-white transition-transform hover:-translate-y-0.5 hover:shadow-lg">
            <User className="size-4 text-white" />
            Login
          </Link>
          <Link href="/wishlist" className="rounded-full bg-white/20 p-3 text-white transition hover:bg-white/30">
            <Heart className="size-4 text-white" />
          </Link>
          <Link href="/cart" className="relative rounded-full bg-brand px-4 py-2 text-white transition hover:scale-105">
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
