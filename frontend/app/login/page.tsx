"use client";

import { useState } from "react";
import { useRouter } from "next/navigation";
import { apiPost } from "@/lib/api";

export default function LoginPage() {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [remember, setRemember] = useState(false);
  const [message, setMessage] = useState<string | null>(null);
  const [error, setError] = useState<string | null>(null);
  const [loading, setLoading] = useState(false);

  const router = useRouter();

  async function handleSubmit(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setMessage(null);
    setError(null);
    setLoading(true);

    const response = await apiPost<{ message: string; token?: string }>("/auth/login", {
      email,
      password,
      remember,
    });

    setLoading(false);

    if (!response) {
      setError("Unable to sign in. Please check your credentials and try again.");
      return;
    }

    if (response.token) {
      localStorage.setItem("northstar_token", response.token);
      router.replace("/account");
      return;
    }

    setMessage(response.message ?? "Login successful.");
  }

  return (
    <div className="mx-auto grid max-w-6xl gap-8 lg:grid-cols-2">
      <div className="glass-panel bg-hero-glow p-10">
        <div className="text-xs font-bold uppercase tracking-[0.2em] text-brand-accent">Secure authentication</div>
        <h1 className="mt-4 text-5xl font-black tracking-tight">Sign in like a marketplace leader.</h1>
        <p className="mt-5 text-base leading-8 text-slate-600">
          Email-password login, OTP fallback, 2FA-ready flow, remember-me sessions, and device management hooks.
        </p>
      </div>
      <div className="glass-panel p-10">
        <h2 className="text-3xl font-black tracking-tight">Welcome back</h2>
        <form onSubmit={handleSubmit} className="mt-6 grid gap-4">
          <input
            className="rounded-full border border-black/10 bg-white px-5 py-4 outline-none"
            placeholder="Email address"
            value={email}
            onChange={(event) => setEmail(event.target.value)}
            type="email"
            required
          />
          <input
            className="rounded-full border border-black/10 bg-white px-5 py-4 outline-none"
            placeholder="Password"
            value={password}
            onChange={(event) => setPassword(event.target.value)}
            type="password"
            required
          />
          <label className="flex items-center gap-2 text-sm text-slate-600">
            <input
              type="checkbox"
              checked={remember}
              onChange={(event) => setRemember(event.target.checked)}
            />
            Remember this device
          </label>
          <button
            type="submit"
            className="rounded-full bg-brand px-6 py-4 text-sm font-bold text-white"
            disabled={loading}
          >
            {loading ? "Signing in..." : "Login"}
          </button>
          <button type="button" className="rounded-full border border-black/10 bg-white px-6 py-4 text-sm font-bold">
            Login with OTP
          </button>
          <button type="button" className="rounded-full border border-black/10 bg-white px-6 py-4 text-sm font-bold">
            Continue with Google
          </button>
          {message ? <p className="text-sm text-emerald-700">{message}</p> : null}
          {error ? <p className="text-sm text-rose-600">{error}</p> : null}
        </form>
      </div>
    </div>
  );
}
