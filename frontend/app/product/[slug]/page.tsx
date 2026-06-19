import Image from "next/image";
import { Star, Truck } from "lucide-react";
import { apiGet } from "@/lib/api";
import { products, transformProduct, type Product } from "../../../lib/data";

export default async function ProductPage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  const apiProduct = await apiGet<{ product?: any }>(`/products/${slug}`);
  const product: Product = apiProduct?.product ? transformProduct(apiProduct.product) : products.find((item: Product) => item.slug === slug) ?? products[0];
  const colorOptions = product.colors ?? ["Default"];
  const sizeOptions = product.sizes ?? ["Standard"];

  return (
    <div className="grid gap-8 lg:grid-cols-[1.1fr_.9fr]">
      <div className="glass-panel p-6">
        <div className="grid gap-4">
          <div className="relative aspect-square overflow-hidden rounded-[24px] bg-[#EFE4D7]">
            <Image src={product.image} alt={product.name} fill className="object-cover" />
          </div>
          <div className="grid grid-cols-4 gap-3">
            {[product.image, product.hoverImage, product.image, product.hoverImage].map((src, index) => (
              <div key={`${src}-${index}`} className="relative aspect-square overflow-hidden rounded-2xl border border-black/5 bg-[#F4ECE1]">
                <Image src={src} alt={`${product.name} thumb ${index + 1}`} fill className="object-cover" />
              </div>
            ))}
          </div>
        </div>
      </div>

      <div className="glass-panel p-8">
        <div className="text-xs font-bold uppercase tracking-[0.2em] text-brand">{product.brand}</div>
        <h1 className="mt-3 text-4xl font-black tracking-tight">{product.name}</h1>
        <p className="mt-4 text-base leading-8 text-slate-600">{product.description}</p>
        <div className="mt-5 flex items-center gap-3">
          <span className="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1.5 font-bold text-emerald-700">
            <Star className="size-4 fill-current" />
            {product.rating}
          </span>
          <span className="text-sm text-slate-500">{product.reviews} ratings & reviews</span>
        </div>
        <div className="mt-6 flex items-center gap-3">
          <span className="text-3xl font-black">INR {product.price.toLocaleString()}</span>
          {product.comparePrice ? (
            <span className="text-lg text-slate-400 line-through">INR {product.comparePrice.toLocaleString()}</span>
          ) : null}
        </div>

        <div className="mt-8 space-y-4">
          <div>
            <div className="text-sm font-bold uppercase tracking-[0.18em] text-slate-500">Colors</div>
            <div className="mt-3 flex flex-wrap gap-3">
              {(product.colors ?? ["Default"]).map((color) => (
                <button key={color} className="rounded-full border border-black/10 bg-white px-4 py-2 text-sm font-semibold">
                  {color}
                </button>
              ))}
            </div>
          </div>
          <div>
            <div className="text-sm font-bold uppercase tracking-[0.18em] text-slate-500">Variants</div>
            <div className="mt-3 flex flex-wrap gap-3">
              {(product.sizes ?? ["Standard"]).map((size) => (
                <button key={size} className="rounded-full border border-black/10 bg-white px-4 py-2 text-sm font-semibold">
                  {size}
                </button>
              ))}
            </div>
          </div>
          <div className="rounded-[24px] border border-black/5 bg-brand-sand/70 p-5">
            <div className="flex items-center gap-3 text-sm font-semibold text-slate-700">
              <Truck className="size-5 text-brand" />
              Delivery check
            </div>
            <div className="mt-4 flex gap-3">
              <input className="w-full rounded-full border border-black/10 bg-white px-4 py-3 outline-none" placeholder="Enter pincode" />
              <button className="rounded-full bg-brand px-5 py-3 text-sm font-bold text-white">Check</button>
            </div>
          </div>
        </div>

        <div className="mt-8 flex flex-wrap gap-4">
          <button className="rounded-full bg-brand px-6 py-4 text-sm font-bold text-white">Add to Cart</button>
          <button className="rounded-full bg-brand-accent px-6 py-4 text-sm font-bold text-white">Buy Now</button>
        </div>
      </div>
    </div>
  );
}
