import type { Metadata } from "next";
import Link from "next/link";
export const metadata:Metadata={title:"Your Bag"};
export default function CartPage(){return <section className="container-luxe min-h-[65vh] py-24 text-center md:py-36"><p className="eyebrow text-[#9b7d55]">Your selection</p><h1 className="display-font mt-6 text-6xl">Your bag is waiting.</h1><p className="mt-6 text-sm text-[#77746d]">Your chosen signatures will appear here.</p><Link href="/collection/" data-magnetic data-cursor-label="Shop" className="outline-button mt-10 inline-flex">Discover the collection</Link></section>;}