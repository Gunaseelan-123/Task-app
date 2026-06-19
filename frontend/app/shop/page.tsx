"use client";

import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { apiGet } from "@/lib/api";
import { ProductCard } from "@/components/product-card";
import { transformProducts, type Product } from "@/lib/data";

export default function ShopPage() {
  const router = useRouter();
  const [loading, setLoading] = useState(true);
  const [products, setProducts] = useState<Product[]>([]);

  useEffect(() => {
    async function loadProducts() {
      const token = localStorage.getItem("northstar_token");
      if (!token) {
        router.replace("/login");
        return;
      }

      const response = await apiGet<{ data?: any[] }>("/products", token);
      setLoading(false);

      if (!response) {
        router.replace("/login");
        return;
      }

      const items = response.data ?? [];
      setProducts(items);
    }

    loadProducts();
  }, [router]);

  if (loading) {
    return <div className="pt-20 text-center text-lg text-slate-600">Loading shop...</div>;
  }

  return (
    <div className="space-y-10">
      <div className="glass-panel p-10">
        <div className="text-xs font-bold uppercase tracking-[0.2em] text-brand-accent">Full Catalog</div>
        <h1 className="mt-4 text-5xl font-black tracking-tight">All products</h1>
        <p className="mt-4 max-w-3xl text-base leading-8 text-slate-600">
          This page is only accessible for logged-in users. Here you can browse the complete product catalog.
        </p>
      </div>

      <div className="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        {transformProducts(products).map((product) => (
          <ProductCard key={product.id} product={product} />
        ))}
      </div>
    </div>
  );
}
