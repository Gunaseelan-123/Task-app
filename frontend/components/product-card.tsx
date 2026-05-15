"use client";

import Image from "next/image";
import Link from "next/link";
import { Star } from "lucide-react";
import type { Product } from "@/lib/data";
import { useCart } from "@/context/CartContext";

export function ProductCard({ product }: { product: Product }) {
  const { addItem } = useCart();

  return (
    <article className="group overflow-hidden rounded-[28px] border border-black/5 bg-white shadow-card">
      <Link href={`/product/${product.slug}`} className="block">
        <div className="relative aspect-square overflow-hidden bg-[#F2E8DB]">
          {product.badge ? (
            <span className="absolute left-4 top-4 z-10 rounded-full bg-brand-accent px-3 py-1 text-xs font-bold text-white">
              {product.badge}
            </span>
          ) : null}
          <Image
            src={product.image}
            alt={product.name}
            fill
            className="object-cover transition duration-300 group-hover:opacity-0"
          />
          <Image
            src={product.hoverImage}
            alt={`${product.name} alternate`}
            fill
            className="object-cover opacity-0 transition duration-300 group-hover:opacity-100"
          />
        </div>
      </Link>

      <div className="space-y-3 p-5">
        <div className="text-xs font-bold uppercase tracking-[0.18em] text-brand">{product.brand}</div>
        <Link href={`/product/${product.slug}`} className="block text-base font-bold leading-6 text-brand-ink">
          {product.name}
        </Link>
        <div className="flex items-center gap-2 text-sm text-slate-600">
          <span className="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 font-semibold text-emerald-700">
            <Star className="size-3 fill-current" />
            {product.rating}
          </span>
          <span>({product.reviews.toLocaleString()} reviews)</span>
        </div>
        <div className="flex items-center gap-2">
          <span className="text-lg font-black">INR {product.price.toLocaleString()}</span>
          {product.comparePrice ? (
            <span className="text-sm text-slate-400 line-through">INR {product.comparePrice.toLocaleString()}</span>
          ) : null}
        </div>
        <button
          onClick={() => addItem(product)}
          className="w-full rounded-full bg-brand-ink px-4 py-3 text-sm font-bold text-white opacity-100 transition md:opacity-0 md:group-hover:opacity-100"
        >
          Add to Cart
        </button>
      </div>
    </article>
  );
}
