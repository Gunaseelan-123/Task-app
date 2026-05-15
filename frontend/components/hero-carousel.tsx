import Image from "next/image";
import { heroSlides } from "@/lib/data";

export function HeroCarousel() {
  const activeSlide = heroSlides[0];

  return (
    <section className="grid gap-6 lg:grid-cols-[1.1fr_.9fr]">
      <div className="glass-panel bg-hero-glow p-8 md:p-12">
        <div className="text-xs font-bold uppercase tracking-[0.24em] text-brand-accent">
          Flipkart-level retail experience
        </div>
        <h1 className="mt-4 max-w-3xl text-4xl font-black tracking-tight text-brand-ink md:text-6xl">
          {activeSlide.title}
        </h1>
        <p className="mt-5 max-w-2xl text-base leading-8 text-slate-600 md:text-lg">
          {activeSlide.subtitle}
        </p>
        <div className="mt-8 flex flex-wrap gap-4">
          <button className="rounded-full bg-brand px-6 py-4 text-sm font-bold text-white">
            {activeSlide.cta}
          </button>
          <button className="rounded-full border border-black/10 bg-white px-6 py-4 text-sm font-bold text-brand-ink">
            View offers
          </button>
        </div>
      </div>

      <div className="glass-panel overflow-hidden">
        <div className="relative min-h-[360px]">
          <Image src={activeSlide.image} alt={activeSlide.title} fill className="object-cover" />
        </div>
      </div>
    </section>
  );
}
