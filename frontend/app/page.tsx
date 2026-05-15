import { CategoryRow } from "@/components/category-row";
import { FlashDeals } from "@/components/flash-deals";
import { HeroCarousel } from "@/components/hero-carousel";
import { ProductSlider } from "@/components/product-slider";
import { apiGet } from "@/lib/api";
import { products } from "@/lib/data";

export default async function HomePage() {
  const apiHome = await apiGet<{
    flash_deals?: typeof products;
    best_of_electronics?: typeof products;
    trending?: typeof products;
  }>("/home");

  return (
    <div className="space-y-10">
      <HeroCarousel />
      <CategoryRow />
      <FlashDeals items={apiHome?.flash_deals ?? products} />
      <ProductSlider
        title="Best of Electronics"
        subtitle="Marketplace favorites"
        items={apiHome?.best_of_electronics ?? products}
      />
      <ProductSlider
        title="Trending Now"
        subtitle="High-converting catalog"
        items={apiHome?.trending ?? products}
      />
    </div>
  );
}
