import type { Metadata } from "next";
import "./globals.css";
import { Header } from "@/components/header";
import { CartProvider } from "@/context/CartContext";

export const metadata: Metadata = {
  title: "Northstar Commerce",
  description: "Production-grade ecommerce frontend built with Next.js and Tailwind CSS.",
};

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en">
      <body>
        <CartProvider>
          <Header />
          <main className="container-shell space-y-10 py-8">{children}</main>
        </CartProvider>
      </body>
    </html>
  );
}
