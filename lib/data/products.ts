import type { BrandSlug } from "@/lib/data/brands";

export type FragranceNotes = {
  top: string[];
  heart: string[];
  base: string[];
};

export type FragranceSize = {
  label: string;
  volumeMl: number;
  price: number;
};

export type FragranceImageSet = {
  bottleShot: string;
  lifestyleShot: string;
  ingredientShots: string[];
};

export type Product = {
  slug: string;
  name: string;
  brand: BrandSlug;
  price: number;
  sizeOptions: FragranceSize[];
  notes: FragranceNotes;
  description: string;
  moodKeywords: string[];
  images: FragranceImageSet;

  /** Compatibility fields used by the existing collection and detail UI. */
  size: string;
  tone: string;
  category: string;
  notesSummary: string;
};

const imageSet = (slug: string, ingredientCount = 3): FragranceImageSet => ({
  bottleShot: `/images/products/${slug}/bottle.webp`,
  lifestyleShot: `/images/products/${slug}/lifestyle.webp`,
  ingredientShots: Array.from(
    { length: ingredientCount },
    (_, index) => `/images/products/${slug}/ingredient-${index + 1}.webp`,
  ),
});

const sizes = (price30: number, price50: number, price100: number): FragranceSize[] => [
  { label: "30 ml", volumeMl: 30, price: price30 },
  { label: "50 ml", volumeMl: 50, price: price50 },
  { label: "100 ml", volumeMl: 100, price: price100 },
];

const fragrance = (
  slug: string,
  name: string,
  brand: BrandSlug,
  price: number,
  sizeOptions: FragranceSize[],
  notes: FragranceNotes,
  description: string,
  moodKeywords: string[],
  category: string,
  tone: string,
): Product => ({
  slug,
  name,
  brand,
  price,
  sizeOptions,
  notes,
  description,
  moodKeywords,
  images: imageSet(slug),
  size: "100 ml",
  tone,
  category,
  notesSummary: [...notes.top, ...notes.heart, ...notes.base].slice(0, 3).join(" · "),
});

export const products: Product[] = [
  // LEGENDARY PROFILE — obsidian, woods and warm metallic depth
  fragrance(
    "sovereign",
    "Sovereign",
    "legendary-profile",
    8990,
    sizes(3990, 5990, 8990),
    { top: ["Black pepper", "Bergamot", "Cardamom"], heart: ["Cedarwood", "Leather", "Clary sage"], base: ["Amber", "Patchouli", "Vetiver"] },
    "A commanding opening of spice settles into textured woods and supple leather, leaving a warm, assured trail.",
    ["Commanding", "Woody", "Confident", "After-dark"],
    "Woody amber",
    "bg-[#211c15]",
  ),
  fragrance(
    "nocturne",
    "Nocturne",
    "legendary-profile",
    9290,
    sizes(4190, 6190, 9290),
    { top: ["Saffron", "Pink pepper", "Mandarin"], heart: ["Oud accord", "Rose", "Incense"], base: ["Benzoin", "Smoked woods", "Musk"] },
    "A dusky composition where saffron and rose meet incense, dark woods and a lingering resinous warmth.",
    ["Mysterious", "Smoky", "Resinous", "Magnetic"],
    "Amber oud",
    "bg-[#29211a]",
  ),
  fragrance(
    "atlas",
    "Atlas",
    "legendary-profile",
    8490,
    sizes(3790, 5690, 8490),
    { top: ["Grapefruit", "Juniper", "Ginger"], heart: ["Cypress", "Lavender", "Geranium"], base: ["Vetiver", "Oakmoss", "Cedar"] },
    "Bright aromatic energy meets grounded woods in a composed, expansive scent with enduring character.",
    ["Aromatic", "Grounded", "Fresh woods", "Day-to-night"],
    "Aromatic woods",
    "bg-[#292820]",
  ),

  // INFORMAL — sunlit citrus, relaxed aromatics and soft textures
  fragrance(
    "off-duty",
    "Off Duty",
    "informal",
    6990,
    sizes(3290, 4790, 6990),
    { top: ["Lemon zest", "Neroli", "Petitgrain"], heart: ["Orange blossom", "Green tea", "Basil"], base: ["White musk", "Blonde woods", "Ambrette"] },
    "An easy citrus opening drifts into green tea and clean woods, like a slow morning with nowhere to be.",
    ["Effortless", "Clean", "Sunlit", "Everyday"],
    "Citrus musk",
    "bg-[#30231d]",
  ),
  fragrance(
    "sunday-club",
    "Sunday Club",
    "informal",
    7290,
    sizes(3390, 4990, 7290),
    { top: ["Mandarin", "Pear", "Bergamot"], heart: [" fig", "Jasmine tea", "Coconut water"], base: ["Sandalwood", "Cashmere woods", "Musk"] },
    "Juicy fruit and airy tea notes soften into creamy woods for a relaxed, close-to-skin signature.",
    ["Relaxed", "Soft", "Comforting", "Easygoing"],
    "Fruity woods",
    "bg-[#33251f]",
  ),
  fragrance(
    "open-air",
    "Open Air",
    "informal",
    6790,
    sizes(3190, 4690, 6790),
    { top: ["Mint", "Lime", "Sea salt"], heart: ["Lavender", "Coriander", "Cyclamen"], base: ["Driftwood", "Musk", "Ambergris accord"] },
    "A breezy aromatic blend with mineral freshness and a gentle driftwood finish.",
    ["Airy", "Fresh", "Coastal", "Uncomplicated"],
    "Aquatic aromatic",
    "bg-[#26302d]",
  ),

  // AMANE — luminous petals, sheer musks and romantic warmth
  fragrance(
    "petal-veil",
    "Petal Veil",
    "amane",
    7790,
    sizes(3590, 5290, 7790),
    { top: ["Lychee", "Pink pepper", "Pear"], heart: ["Peony", "Rose petals", "Magnolia"], base: ["White musk", "Soft amber", "Cashmere wood"] },
    "A sheer veil of petals and luminous fruit settles into a tender, warm embrace of musk and cashmere woods.",
    ["Romantic", "Luminous", "Petal-soft", "Elegant"],
    "Floral musk",
    "bg-[#33252b]",
  ),
  fragrance(
    "moonmilk",
    "Moonmilk",
    "amane",
    8190,
    sizes(3790, 5590, 8190),
    { top: ["Almond", "Heliotrope", "Mandarin"], heart: ["Iris", "Jasmine", "Rice accord"], base: ["Vanilla", "Sandalwood", "Skin musk"] },
    "A creamy, powder-soft fragrance with almond and iris wrapped in vanilla and smooth sandalwood.",
    ["Intimate", "Creamy", "Powdery", "Serene"],
    "Powdery floral",
    "bg-[#30242a]",
  ),
  fragrance(
    "rose-dawn",
    "Rose Dawn",
    "amane",
    7990,
    sizes(3690, 5490, 7990),
    { top: ["Bergamot", "Raspberry", "Dewy greens"], heart: ["Damask rose", "Freesia", "Violet"], base: ["Musk", "Cedar", "Vanilla"] },
    "Fresh morning light over a garden of rose and violet, with a softly sweet, polished finish.",
    ["Fresh floral", "Graceful", "Tender", "Radiant"],
    "Fresh floral",
    "bg-[#38262b]",
  ),

  // SASHA — vivid contrasts, mineral facets and electric florals
  fragrance(
    "static-bloom",
    "Static Bloom",
    "sasha",
    8290,
    sizes(3890, 5690, 8290),
    { top: ["Yuzu", "Blackcurrant", "Pink pepper"], heart: ["Iris", "Electric violet accord", "Jasmine"], base: ["Ambrox", "Cedar", "White musk"] },
    "A vivid flash of citrus and dark fruit cuts through cool violet and mineral woods.",
    ["Electric", "Modern", "Expressive", "Unisex"],
    "Woody floral",
    "bg-[#252033]",
  ),
  fragrance(
    "violet-voltage",
    "Violet Voltage",
    "sasha",
    8790,
    sizes(4090, 5990, 8790),
    { top: ["Black pepper", "Bergamot", "Juniper"], heart: ["Violet leaf", "Lavender", "Sage"], base: ["Suede", "Patchouli", "Metallic musk accord"] },
    "Cool aromatic greens and violet leaf collide with suede and a sleek, metallic musk finish.",
    ["Edgy", "Cool", "Aromatic", "Unconventional"],
    "Aromatic suede",
    "bg-[#211d30]",
  ),
  fragrance(
    "chrome-kiss",
    "Chrome Kiss",
    "sasha",
    8590,
    sizes(3990, 5890, 8590),
    { top: ["Blood orange", "Aldehydes", "Ginger"], heart: ["Rose oxide accord", "Magnolia", "Pink pepper"], base: ["Cashmeran", "Amberwood", "Musk"] },
    "A bright metallic sparkle gives way to vivid florals and a smooth, modern amberwood trail.",
    ["Bold", "Bright", "Metallic", "Playful"],
    "Amber floral",
    "bg-[#292438]",
  ),
];

export const productBySlug = (slug: string): Product | undefined =>
  products.find((product) => product.slug === slug);

export const getProduct = productBySlug;

export const productsByBrand = (brandSlug: BrandSlug): Product[] =>
  products.filter((product) => product.brand === brandSlug);

export const getProductsByBrand = productsByBrand;
