export default function LoginPage() {
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
        <div className="mt-6 grid gap-4">
          <input className="rounded-full border border-black/10 bg-white px-5 py-4 outline-none" placeholder="Email address" />
          <input className="rounded-full border border-black/10 bg-white px-5 py-4 outline-none" placeholder="Password" type="password" />
          <label className="flex items-center gap-2 text-sm text-slate-600">
            <input type="checkbox" />
            Remember this device
          </label>
          <button className="rounded-full bg-brand px-6 py-4 text-sm font-bold text-white">Login</button>
          <button className="rounded-full border border-black/10 bg-white px-6 py-4 text-sm font-bold">Login with OTP</button>
          <button className="rounded-full border border-black/10 bg-white px-6 py-4 text-sm font-bold">Continue with Google</button>
        </div>
      </div>
    </div>
  );
}
