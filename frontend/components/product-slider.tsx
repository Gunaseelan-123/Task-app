import { ProductCard } from "@/components/product-card";
import type { Product } from "@/lib/data";

export function ProductSlider({ title, subtitle, items }: { title: string; subtitle: string; items: Product[] }) {
  return (
    <section className="space-y-5">
      <div className="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
        <div>
          <div className="text-xs font-bold uppercase tracking-[0.2em] text-brand-accent">{subtitle}</div>
          <h2 className="section-title mt-2">{title}</h2>
        </div>
        <button className="w-fit rounded-full border border-black/10 bg-white px-5 py-3 text-sm font-bold text-brand-ink">
          View all
        </button>
      </div>
      <div className="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        {items.map((item) => (
          <ProductCard key={item.id} product={item} />
        ))}
      </div>
    </section>
  );
}
