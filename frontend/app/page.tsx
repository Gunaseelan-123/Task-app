import { Briefcase, CreditCard, Headphones, ShieldCheck } from "lucide-react";
import { CategoryRow } from "@/components/category-row";
import { FlashDeals } from "@/components/flash-deals";
import { HeroCarousel } from "@/components/hero-carousel";
import { ProductSlider } from "@/components/product-slider";
import { apiGet } from "@/lib/api";
import { products, transformProducts } from "../lib/data";

const featureHighlights = [
  {
    icon: Briefcase,
    title: "Fast delivery",
    description: "Get orders delivered quickly across major cities.",
  },
  {
    icon: ShieldCheck,
    title: "Easy returns",
    description: "30-day returns with simple, transparent policies.",
  },
  {
    icon: Headphones,
    title: "Expert support",
    description: "Dedicated customer care for every purchase.",
  },
  {
    icon: CreditCard,
    title: "Secure checkout",
    description: "Encrypted payments and trusted checkout flow.",
  },
];

export default async function HomePage() {
  const apiHome = await apiGet<{
    flash_deals?: any[];
    best_of_electronics?: any[];
    trending?: any[];
  }>("/home");

  const flashDeals = apiHome?.flash_deals ? transformProducts(apiHome.flash_deals) : products;
  const bestOfElectronics = apiHome?.best_of_electronics ? transformProducts(apiHome.best_of_electronics) : products;
  const trendingProducts = apiHome?.trending ? transformProducts(apiHome.trending) : products;

  return (
    <div className="space-y-10">
      <HeroCarousel />

      <section className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        {featureHighlights.map((item) => {
          const Icon = item.icon;
          return (
            <article key={item.title} className="glass-panel flex items-start gap-4 p-6">
              <div className="flex h-12 w-12 items-center justify-center rounded-3xl bg-brand-sand text-brand">
                <Icon className="size-5" />
              </div>
              <div>
                <h2 className="text-base font-semibold text-brand-ink">{item.title}</h2>
                <p className="mt-1 text-sm leading-6 text-slate-600">{item.description}</p>
              </div>
            </article>
          );
        })}
      </section>

      <CategoryRow />
      <FlashDeals items={flashDeals} />
      <ProductSlider title="Best of Electronics" subtitle="Marketplace favorites" items={bestOfElectronics} previewCount={3} />
      <ProductSlider title="Trending Now" subtitle="Top-rated picks" items={trendingProducts} previewCount={3} />
    </div>
  );
}
