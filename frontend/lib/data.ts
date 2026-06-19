export type Product = {
  id: number;
  name: string;
  slug: string;
  brand: string;
  price: number;
  comparePrice?: number;
  rating: number;
  reviews: number;
  image: string;
  hoverImage: string;
  badge?: string;
  category: string;
  description: string;
  colors?: string[];
  sizes?: string[];
};

export function transformProduct(product: any): Product {
  const image = product.images?.[0]?.path ?? product.primary_image ?? product.image ?? "https://placehold.co/900x900/f4efe5/17212b?text=Product";
  const hoverImage = product.images?.[1]?.path ?? image;

  return {
    id: product.id,
    name: product.name,
    slug: product.slug,
    brand: product.brand ?? "Northstar",
    price: Number(product.price ?? 0),
    comparePrice: product.compare_price ? Number(product.compare_price) : undefined,
    rating: Number(product.rating ?? 0),
    reviews: Number(product.reviews_count ?? product.reviews?.length ?? 0),
    image,
    hoverImage,
    badge: product.badge_text ?? product.badge ?? undefined,
    category: product.category?.name ?? product.category ?? "General",
    description: product.short_description ?? product.description ?? "",
    colors: product.colors ?? product.color_options ?? undefined,
    sizes: product.sizes ?? product.size_options ?? undefined,
  };
}

export const products: Product[] = [
  {
    id: 1,
    name: "Northstar X1 Pro Smartphone",
    slug: "northstar-x1-pro-smartphone",
    brand: "Northstar",
    price: 45999,
    comparePrice: 52999,
    rating: 4.6,
    reviews: 1824,
    image: "https://placehold.co/900x900/ece3d6/17212b?text=X1+Pro",
    hoverImage: "https://placehold.co/900x900/e6ddcf/17212b?text=X1+Back",
    badge: "13% off",
    category: "Electronics",
    description: "Flagship Android experience with AMOLED display, premium finish, and all-day battery.",
    colors: ["Midnight", "Silver", "Blue"],
    sizes: ["128GB", "256GB", "512GB"],
  },
  {
    id: 2,
    name: "Orbit ANC Wireless Headphones",
    slug: "orbit-anc-wireless-headphones",
    brand: "Orbit",
    price: 12999,
    comparePrice: 15999,
    rating: 4.4,
    reviews: 932,
    image: "https://placehold.co/900x900/f3ebdf/17212b?text=Orbit+ANC",
    hoverImage: "https://placehold.co/900x900/e9e1d4/17212b?text=Orbit+Case",
    badge: "19% off",
    category: "Electronics",
    description: "Noise-cancelling headphones tuned for commuting, focus sessions, and rich bass playback.",
    colors: ["Black", "Sand"],
  },
  {
    id: 3,
    name: "Avenue Leather Weekender",
    slug: "avenue-leather-weekender",
    brand: "Avenue",
    price: 6499,
    comparePrice: 7999,
    rating: 4.7,
    reviews: 421,
    image: "https://placehold.co/900x900/eee4d7/17212b?text=Weekender",
    hoverImage: "https://placehold.co/900x900/e4dacd/17212b?text=Weekender+Open",
    badge: "18% off",
    category: "Fashion",
    description: "Structured weekender bag with durable zips, padded handles, and premium travel styling.",
  },
  {
    id: 4,
    name: "Monarch Knit Polo",
    slug: "monarch-knit-polo",
    brand: "Monarch",
    price: 2499,
    comparePrice: 3299,
    rating: 4.2,
    reviews: 215,
    image: "https://placehold.co/900x900/f1e7da/17212b?text=Knit+Polo",
    hoverImage: "https://placehold.co/900x900/e8ddcf/17212b?text=Polo+Detail",
    badge: "24% off",
    category: "Fashion",
    description: "Refined cotton knit polo built for everyday wear with a luxe drape and modern collar.",
    sizes: ["S", "M", "L", "XL"],
  },
  {
    id: 5,
    name: "Nova Active Running Shoes",
    slug: "nova-active-running-shoes",
    brand: "Nova",
    price: 3999,
    comparePrice: 4999,
    rating: 4.5,
    reviews: 312,
    image: "https://placehold.co/900x900/dbe7f5/17212b?text=Running+Shoes",
    hoverImage: "https://placehold.co/900x900/c7dbf0/17212b?text=Running+Shoes+Side",
    badge: "20% off",
    category: "Sportswear",
    description: "Lightweight running shoes built for comfort and performance.",
    sizes: ["6", "7", "8", "9", "10"],
  },
  {
    id: 6,
    name: "Luma Desk Lamp",
    slug: "luma-desk-lamp",
    brand: "Luma",
    price: 2999,
    rating: 4.3,
    reviews: 128,
    image: "https://placehold.co/900x900/e9f1dc/17212b?text=Desk+Lamp",
    hoverImage: "https://placehold.co/900x900/d7e5ca/17212b?text=Desk+Lamp+Detail",
    category: "Home",
    description: "Minimal desk lighting with adjustable glow and modern styling.",
  },
  {
    id: 7,
    name: "Aster Bluetooth Speaker",
    slug: "aster-bluetooth-speaker",
    brand: "Aster",
    price: 5499,
    comparePrice: 6499,
    rating: 4.6,
    reviews: 284,
    image: "https://placehold.co/900x900/efe4f7/17212b?text=Bluetooth+Speaker",
    hoverImage: "https://placehold.co/900x900/e0d5f0/17212b?text=Speaker+Detail",
    badge: "15% off",
    category: "Audio",
    description: "Portable speaker with deep bass, Bluetooth 5.2, and 10 hours of playback.",
  },
  {
    id: 8,
    name: "Vela Yoga Mat",
    slug: "vela-yoga-mat",
    brand: "Vela",
    price: 2199,
    rating: 4.4,
    reviews: 198,
    image: "https://placehold.co/900x900/e7f2ee/17212b?text=Yoga+Mat",
    hoverImage: "https://placehold.co/900x900/d9e8dc/17212b?text=Yoga+Mat+Roll",
    category: "Fitness",
    description: "Cushioned yoga mat with textured grip and water-resistant finish.",
  },
  {
    id: 9,
    name: "Evermore Leather Wallet",
    slug: "evermore-leather-wallet",
    brand: "Evermore",
    price: 1599,
    rating: 4.7,
    reviews: 94,
    image: "https://placehold.co/900x900/f4e4dc/17212b?text=Leather+Wallet",
    hoverImage: "https://placehold.co/900x900/e8dad0/17212b?text=Wallet+Open",
    category: "Accessories",
    description: "Slim leather wallet with multiple card slots and RFID protection.",
  },
  {
    id: 10,
    name: "Solstice Sunglasses",
    slug: "solstice-sunglasses",
    brand: "Solstice",
    price: 2699,
    rating: 4.3,
    reviews: 164,
    image: "https://placehold.co/900x900/eef3f7/17212b?text=Sunglasses",
    hoverImage: "https://placehold.co/900x900/dfe7f0/17212b?text=Sunglasses+Side",
    category: "Fashion",
    description: "Polarized sunglasses with UV protection and lightweight frames.",
  },
];

export function transformProducts(products: any[]): Product[] {
  return products.map(transformProduct);
}

export const heroSlides = [
  {
    title: "Wireless headphones built for every playlist.",
    subtitle: "Shop premium audio, fast shipping, and effortless checkout in a clean marketplace experience.",
    cta: "Shop Headphones",
    image: "https://placehold.co/1200x720/ebe6f2/17212b?text=Wireless+Headphones",
  },
];

export const categories = [
  { label: "Earphones", icon: "Headphones" },
  { label: "Gadgets", icon: "Watch" },
  { label: "Laptop", icon: "Laptop" },
  { label: "Gaming", icon: "Gamepad" },
  { label: "Audio", icon: "Speaker" },
  { label: "Studio", icon: "Cpu" },
];
