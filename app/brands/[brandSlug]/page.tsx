import type { Metadata } from "next";
import Link from "next/link";
import { notFound } from "next/navigation";
import { ArrowUpRight } from "lucide-react";
import ProductCard from "@/components/product/ProductCard";
import { brands, brandBySlug } from "@/lib/data/brands";
import { productsByBrand } from "@/lib/data/products";

export function generateStaticParams() {
  return brands.map((brand) => ({ brandSlug: brand.slug }));
}

export async function generateMetadata({ params }: { params: { brandSlug: string } }): Promise<Metadata> {
  const brand = brandBySlug(params.brandSlug);
  return { title: brand?.name ?? "Brand", description: brand?.description };
}

const preview: Record<string, string> = {
  "legendary-profile": "/images/brand-previews/legendary-profile.svg",
  informal: "/images/brand-previews/informal.svg",
  amane: "/images/brand-previews/amane.svg",
  sasha: "/images/brand-previews/sasha.svg",
};

export default function BrandPage({ params }: { params: { brandSlug: string } }) {
  const brand = brandBySlug(params.brandSlug);
  if (!brand) notFound();
  const collection = productsByBrand(brand.slug);
  return (
    <section className="container-luxe py-16 md:py-24">
      <Link href="/collection/" className="eyebrow text-[#9b7d55]">All fragrances / {brand.name}</Link>
      <div className="mt-10 grid items-center gap-10 md:grid-cols-[.9fr_1.1fr] md:gap-16">
        <div className="overflow-hidden" style={{ backgroundColor: brand.palette.surface }}>
          <img src={preview[brand.slug]} alt={`${brand.name} brand artwork`} className="block aspect-[.95] w-full object-cover" />
        </div>
        <div>
          <p className="eyebrow" style={{ color: brand.palette.primary }}>A house within IKKI · 0{brands.findIndex((item) => item.slug === brand.slug) + 1}</p>
          <h1 className="display-font mt-6 text-6xl leading-[.98] md:text-8xl">{brand.name}<span style={{ color: brand.palette.primary }}>.</span></h1>
          <p className="display-font mt-7 max-w-xl text-2xl leading-relaxed md:text-3xl">{brand.tagline}</p>
          <p className="mt-6 max-w-lg text-sm leading-8 text-[#8e887d]">{brand.description}</p>
          <Link href="#fragrances" className="mt-9 inline-flex items-center gap-3 border-b pb-2 text-[9px] uppercase tracking-[.2em]" style={{ borderColor: brand.palette.primary }}>Explore the house <ArrowUpRight size={14} /></Link>
        </div>
      </div>
      <div id="fragrances" className="mt-24 scroll-mt-28 md:mt-32">
        <p className="eyebrow" style={{ color: brand.palette.primary }}>The collection</p>
        <h2 className="display-font mt-4 text-5xl md:text-6xl">The signatures.</h2>
        <div className="gold-rule my-10" />
        <div className="grid gap-x-7 gap-y-14 sm:grid-cols-2 lg:grid-cols-3">
          {collection.map((product, index) => <ProductCard key={product.slug} product={product} index={index} />)}
        </div>
      </div>
    </section>
  );
}
