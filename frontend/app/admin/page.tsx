export default function AdminPage() {
  return (
    <div className="space-y-8">
      <div>
        <div className="text-xs font-bold uppercase tracking-[0.2em] text-brand-accent">Admin Console</div>
        <h1 className="mt-3 text-5xl font-black tracking-tight">Commerce operations dashboard</h1>
      </div>

      <div className="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        {[
          ["Revenue", "INR 14.8L"],
          ["Orders", "1,284"],
          ["Customers", "4,912"],
          ["Conversion", "3.8%"],
        ].map(([label, value]) => (
          <div key={label} className="glass-panel p-6">
            <div className="text-sm font-semibold text-slate-500">{label}</div>
            <div className="mt-3 text-3xl font-black">{value}</div>
          </div>
        ))}
      </div>

      <div className="grid gap-6 lg:grid-cols-2">
        <div className="glass-panel p-6">
          <h2 className="text-2xl font-black">Quick Actions</h2>
          <div className="mt-5 grid gap-3">
            {["Create Product", "Manage Categories", "Review Orders", "Update Banners", "Issue Coupon"].map((action) => (
              <button key={action} className="rounded-full border border-black/10 bg-white px-5 py-3 text-left text-sm font-bold">
                {action}
              </button>
            ))}
          </div>
        </div>
        <div className="glass-panel p-6">
          <h2 className="text-2xl font-black">System Notes</h2>
          <ul className="mt-5 space-y-3 text-sm leading-7 text-slate-600">
            <li>API-first backend with Laravel Sanctum authentication.</li>
            <li>SEO-friendly frontend with Next.js App Router and Tailwind UI system.</li>
            <li>Product, coupon, banner, and order domains are ready for CRUD expansion.</li>
          </ul>
        </div>
      </div>
    </div>
  );
}
