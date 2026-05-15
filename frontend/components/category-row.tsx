import { Briefcase, Lamp, Laptop, Package, Shirt, Smartphone, Sparkles, Tv } from "lucide-react";
import { categories } from "@/lib/data";

const iconMap = {
  Smartphone,
  Laptop,
  Shirt,
  Lamp,
  Sparkles,
  Tv,
  Briefcase,
  Package,
};

export function CategoryRow() {
  return (
    <section className="glass-panel p-4 md:p-6">
      <div className="grid grid-cols-2 gap-4 md:grid-cols-4 xl:grid-cols-8">
        {categories.map((category) => {
          const Icon = iconMap[category.icon as keyof typeof iconMap];
          return (
            <div key={category.label} className="flex flex-col items-center justify-center rounded-3xl border border-black/5 bg-brand-sand/60 px-4 py-5 text-center transition hover:-translate-y-1 hover:bg-white">
              <Icon className="size-6 text-brand" />
              <div className="mt-3 text-sm font-semibold">{category.label}</div>
            </div>
          );
        })}
      </div>
    </section>
  );
}
