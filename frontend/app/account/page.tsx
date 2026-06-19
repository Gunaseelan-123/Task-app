"use client";

import { useEffect, useState } from "react";
import { ArrowRight, CreditCard, Lock, MapPin, Shield, ShoppingBag, Sparkles, UserCheck } from "lucide-react";
import { useRouter } from "next/navigation";
import { apiGet } from "@/lib/api";

const accountSections = [
  { title: "Your Orders", description: "Track order status, returns, and purchase history.", icon: ShoppingBag },
  { title: "Login & Security", description: "Update your password, email, and device access.", icon: Lock },
  { title: "Prime", description: "View membership benefits, delivery preferences, and offers.", icon: Sparkles },
  { title: "Your Addresses", description: "Add and manage shipping and billing addresses.", icon: MapPin },
  { title: "Payment Options", description: "Edit saved cards, payment methods, and billing info.", icon: CreditCard },
  { title: "Business Account", description: "Manage business settings, invoices, and corporate access.", icon: UserCheck },
];

const supportSections = [
  { title: "Digital content and devices", description: "App downloads, device settings, and gift preferences." },
  { title: "Email alerts, messages, and ads", description: "Control communication preferences and ad settings." },
  { title: "More ways to pay", description: "Manage coupons, saved payment methods, and Amazon Pay." },
  { title: "Ordering and shopping preferences", description: "Manage lists, packaging preferences, and language settings." },
  { title: "Other accounts", description: "Link other accounts and manage third-party access." },
  { title: "Manage your data", description: "Request your data, privacy settings, and account access." },
];

type User = {
  id: number;
  name: string;
  email: string;
};

export default function AccountPage() {
  const router = useRouter();
  const [loading, setLoading] = useState(true);
  const [user, setUser] = useState<User | null>(null);

  useEffect(() => {
    async function loadUser() {
      const token = localStorage.getItem("northstar_token");
      if (!token) {
        router.replace("/login");
        return;
      }

      const response = await apiGet<{ id: number; name: string; email: string }>("/auth/me", token);
      setLoading(false);

      if (!response) {
        localStorage.removeItem("northstar_token");
        router.replace("/login");
        return;
      }

      setUser(response);
    }

    loadUser();
  }, [router]);

  function handleLogout() {
    localStorage.removeItem("northstar_token");
    router.replace("/login");
  }

  if (loading) {
    return <div className="pt-20 text-center text-lg text-slate-600">Checking your account...</div>;
  }

  if (!user) {
    return null;
  }

  return (
    <div className="space-y-10">
      <section className="glass-panel p-10">
        <div className="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
          <div>
            <div className="text-xs font-bold uppercase tracking-[0.2em] text-brand-accent">Your Account</div>
            <h1 className="mt-4 text-5xl font-black tracking-tight">Hello, {user.name}</h1>
            <p className="mt-4 max-w-3xl text-base leading-8 text-slate-600">
              Your account area is secured. If you are not logged in, access is blocked and you are redirected to login for privacy and security.
            </p>
            <div className="mt-4 flex flex-wrap gap-3 text-sm text-slate-500">
              <span className="rounded-full border border-slate-200 bg-slate-50 px-4 py-2">Signed in as {user.email}</span>
              <button
                type="button"
                onClick={handleLogout}
                className="rounded-full border border-black/10 bg-white px-4 py-2 text-sm font-semibold text-slate-800"
              >
                Logout
              </button>
            </div>
          </div>
          <div className="rounded-[32px] border border-black/5 bg-brand-sand/70 p-8 shadow-sm">
            <div className="flex items-center gap-3 text-sm font-semibold uppercase tracking-[0.2em] text-brand-accent">
              <Shield className="size-5 text-brand" />
              Account security
            </div>
            <p className="mt-4 text-sm leading-7 text-slate-600">
              Security features are active and account access is protected. Only authenticated users can see this page.
            </p>
          </div>
        </div>
      </section>

      <section className="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        {accountSections.map(({ title, description, icon: Icon }) => (
          <div key={title} className="glass-panel rounded-[24px] p-6 transition hover:border-brand hover:shadow-lg">
            <div className="flex items-center gap-3 text-brand-ink">
              <span className="inline-flex h-11 w-11 items-center justify-center rounded-3xl bg-brand-sand text-brand">
                <Icon className="size-5" />
              </span>
              <div>
                <h2 className="text-lg font-semibold">{title}</h2>
                <p className="mt-2 text-sm text-slate-600">{description}</p>
              </div>
            </div>
          </div>
        ))}
      </section>

      <section className="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        {supportSections.map(({ title, description }) => (
          <div key={title} className="glass-panel rounded-[24px] p-6">
            <div className="flex items-center justify-between gap-4">
              <div>
                <h3 className="text-base font-semibold">{title}</h3>
                <p className="mt-3 text-sm leading-7 text-slate-600">{description}</p>
              </div>
              <ArrowRight className="size-5 text-slate-400" />
            </div>
          </div>
        ))}
      </section>
    </div>
  );
}
