export type BrandSlug = "legendary-profile" | "informal" | "amane" | "sasha";

export type BrandPalette = {
  primary: string;
  secondary: string;
  accent: string;
  highlight: string;
  background: string;
  surface: string;
  text: string;
};

export type Brand = {
  slug: BrandSlug;
  name: string;
  tagline: string;
  description: string;
  palette: BrandPalette;
  heroImage: string;
  logoPlaceholder: string;
};

export const brands: Brand[] = [
  {
    slug: "legendary-profile",
    name: "Legendary Profile",
    tagline: "Leave a mark. Never an explanation.",
    description:
      "A study in presence and quiet power. Legendary Profile explores shadow, polished woods and warm resins through fragrances with a confident, unmistakable signature.",
    palette: {
      primary: "#C6A36B",
      secondary: "#80613E",
      accent: "#E2C99E",
      highlight: "#F0DFC0",
      background: "#080807",
      surface: "#211C15",
      text: "#F4F0E8",
    },
    heroImage: "/images/brands/legendary-profile/hero.webp",
    logoPlaceholder: "/images/brands/legendary-profile/logo-placeholder.svg",
  },
  {
    slug: "informal",
    name: "Informal",
    tagline: "Ease is its own kind of elegance.",
    description:
      "Uncomplicated, considered and quietly magnetic. Informal is made for the in-between moments: sun-warmed skin, open air and the confidence of feeling entirely yourself.",
    palette: {
      primary: "#C77B5B",
      secondary: "#96563F",
      accent: "#D9A58B",
      highlight: "#EBD0BF",
      background: "#17100D",
      surface: "#261B17",
      text: "#F5EDE5",
    },
    heroImage: "/images/brands/informal/hero.webp",
    logoPlaceholder: "/images/brands/informal/logo-placeholder.svg",
  },
  {
    slug: "amane",
    name: "Amane",
    tagline: "A softer kind of unforgettable.",
    description:
      "Luminous florals, delicate textures and intimate warmth. Amane captures the poetry of softness without losing its sense of character.",
    palette: {
      primary: "#D9A9AD",
      secondary: "#A8757D",
      accent: "#F0D8D6",
      highlight: "#F7E9E5",
      background: "#160F13",
      surface: "#251B20",
      text: "#F8F0EF",
    },
    heroImage: "/images/brands/amane/hero.webp",
    logoPlaceholder: "/images/brands/amane/logo-placeholder.svg",
  },
  {
    slug: "sasha",
    name: "Sasha",
    tagline: "Made to break the expected.",
    description:
      "Electric contrasts and modern self-expression. Sasha moves between vivid brightness, cool mineral facets and unexpected depth, without asking permission.",
    palette: {
      primary: "#9B78F5",
      secondary: "#6544C7",
      accent: "#C4B0FF",
      highlight: "#C8CDD5",
      background: "#09080D",
      surface: "#1C1927",
      text: "#F3F0FA",
    },
    heroImage: "/images/brands/sasha/hero.webp",
    logoPlaceholder: "/images/brands/sasha/logo-placeholder.svg",
  },
];

export const brandBySlug = (slug: string): Brand | undefined =>
  brands.find((brand) => brand.slug === slug);

export const getBrand = brandBySlug;
