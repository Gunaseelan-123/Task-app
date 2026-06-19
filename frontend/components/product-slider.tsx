"use client";

import { useEffect, useMemo, useState } from "react";
import Link from "next/link";
import { ProductCard } from "@/components/product-card";
import type { Product } from "@/lib/data";

export function ProductSlider({
  title,
  subtitle,
  items,
  viewAllHref = "/shop",
  previewCount,
}: {
  title: string;
  subtitle: string;
  items: Product[];
  viewAllHref?: string;
  previewCount?: number;
}) {
  const [isAuthenticated, setIsAuthenticated] = useState(false);

  useEffect(() => {
    setIsAuthenticated(Boolean(localStorage.getItem("northstar_token")));
  }, []);

  const displayItems = useMemo(() => {
    if (!isAuthenticated && typeof previewCount === "number") {
      return items.slice(0, previewCount);
    }
    return items;
  }, [isAuthenticated, items, previewCount]);

  const targetHref = isAuthenticated ? viewAllHref : "/login";
  const buttonText = isAuthenticated ? "View all" : "Login to view all";

  return (
    <section className="space-y-5">
      <div className="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
        <div>
          <div className="text-xs font-bold uppercase tracking-[0.2em] text-brand-accent">{subtitle}</div>
          <h2 className="section-title mt-2">{title}</h2>
        </div>
        <Link href={targetHref} className="w-fit rounded-full border border-black/10 bg-white px-5 py-3 text-sm font-bold text-brand-ink">
          {buttonText}
        </Link>
      </div>

      {!isAuthenticated && typeof previewCount === "number" && items.length > previewCount ? (
        <div className="rounded-[24px] border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-900">
          Showing {previewCount} of {items.length} products. Login to see the full catalog.
        </div>
      ) : null}

      <div className="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        {displayItems.map((item) => (
          <ProductCard key={item.id} product={item} />
        ))}
      </div>
    </section>
  );
}
