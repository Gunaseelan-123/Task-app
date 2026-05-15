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

export const heroSlides = [
  {
    title: "Premium electronics, startup-speed checkout.",
    subtitle: "Flash deals, sticky search, conversion-first merchandising, and ultra-clean shopping flows.",
    cta: "Shop Electronics",
    image: "https://placehold.co/1200x720/e8dfd3/17212b?text=Hero+Banner+1",
  },
  {
    title: "Fashion and lifestyle with marketplace scale.",
    subtitle: "Curated category tiles, slider-driven discovery, and product cards that feel retail-grade.",
    cta: "Explore Fashion",
    image: "https://placehold.co/1200x720/f2e6d6/17212b?text=Hero+Banner+2",
  },
];

export const categories = [
  { label: "Mobiles", icon: "Smartphone" },
  { label: "Electronics", icon: "Laptop" },
  { label: "Fashion", icon: "Shirt" },
  { label: "Home", icon: "Lamp" },
  { label: "Beauty", icon: "Sparkles" },
  { label: "Appliances", icon: "Tv" },
  { label: "Travel", icon: "Briefcase" },
  { label: "Grocery", icon: "Package" },
];

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
];
