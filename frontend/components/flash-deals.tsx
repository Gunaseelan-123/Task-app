"use client";

import { useEffect, useState } from "react";
import { ProductSlider } from "@/components/product-slider";
import type { Product } from "@/lib/data";

export function FlashDeals({ items }: { items: Product[] }) {
  const [secondsLeft, setSecondsLeft] = useState(6 * 60 * 60 + 32 * 60 + 15);

  useEffect(() => {
    const timer = setInterval(() => {
      setSecondsLeft((value) => (value > 0 ? value - 1 : 0));
    }, 1000);

    return () => clearInterval(timer);
  }, []);

  const hours = String(Math.floor(secondsLeft / 3600)).padStart(2, "0");
  const minutes = String(Math.floor((secondsLeft % 3600) / 60)).padStart(2, "0");
  const seconds = String(secondsLeft % 60).padStart(2, "0");

  return (
    <div className="space-y-5">
      <div className="glass-panel flex flex-col gap-4 p-6 md:flex-row md:items-center md:justify-between">
        <div>
          <div className="text-xs font-bold uppercase tracking-[0.2em] text-brand-accent">Flash Deals</div>
          <h2 className="mt-2 text-3xl font-black tracking-tight">Deals ending soon</h2>
        </div>
        <div className="flex gap-3">
          {[hours, minutes, seconds].map((value, index) => (
            <div key={`${value}-${index}`} className="rounded-3xl bg-brand px-4 py-3 text-center text-white">
              <div className="text-2xl font-black">{value}</div>
              <div className="text-[11px] uppercase tracking-[0.2em] opacity-70">
                {index === 0 ? "Hours" : index === 1 ? "Minutes" : "Seconds"}
              </div>
            </div>
          ))}
        </div>
      </div>
      <ProductSlider title="Limited-time price drops" subtitle="Smart merchandising" items={items} />
    </div>
  );
}
