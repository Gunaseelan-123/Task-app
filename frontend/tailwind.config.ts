import type { Config } from "tailwindcss";

export default {
  content: [
    "./app/**/*.{js,ts,jsx,tsx,mdx}",
    "./components/**/*.{js,ts,jsx,tsx,mdx}",
    "./context/**/*.{js,ts,jsx,tsx,mdx}",
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          DEFAULT: "#0F4C81",
          dark: "#0A3357",
          accent: "#F97316",
          sand: "#F7F1E8",
          ink: "#17212B",
        },
      },
      boxShadow: {
        card: "0 20px 50px rgba(23, 33, 43, 0.08)",
      },
      backgroundImage: {
        "hero-glow":
          "radial-gradient(circle at top left, rgba(249,115,22,.16), transparent 28%), radial-gradient(circle at top right, rgba(15,76,129,.18), transparent 32%)",
      },
    },
  },
  plugins: [],
} satisfies Config;
