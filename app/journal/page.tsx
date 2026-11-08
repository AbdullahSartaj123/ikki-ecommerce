import type { Metadata } from "next";
import Link from "next/link";
import { ArrowUpRight } from "lucide-react";

export const metadata: Metadata = {
  title: "Journal",
  description: "Notes on fragrance, personal rituals and the art of a signature.",
};

const stories = [
  { number: "01", category: "THE LANGUAGE OF SCENT", title: "A fragrance is a memory in the making.", copy: "Why certain notes stay with us, and how a scent becomes part of the way we are remembered.", href: "/collection/", link: "Discover the collection", tone: "journal-panel-one" },
  { number: "02", category: "THE PERSONAL EDIT", title: "Finding a signature, not following a trend.", copy: "A more considered way to choose fragrance: start with how you want to feel, then follow the notes.", href: "/our-story/", link: "Our point of view", tone: "journal-panel-two" },
  { number: "03", category: "FOUR DISTINCT WORLDS", title: "One house. Four expressions.", copy: "Meet the individual worlds that make up the IKKI house of fragrance.", href: "/collection/", link: "Explore the houses", tone: "journal-panel-three" },
];

export default function JournalPage() {
  return (
    <section className="container-luxe py-20 md:py-28">
      <p className="eyebrow text-[#9b7d55]">The IKKI journal · Notes & perspectives</p>
      <h1 className="display-font mt-6 text-7xl leading-none md:text-9xl">The journal.</h1>
      <p className="mt-7 max-w-lg text-sm leading-8 text-[#8e887d]">A closer look at the art of fragrance, the rituals around it and the details that make a signature personal.</p>
      <div className="gold-rule my-14" />
      <div className="grid gap-12 md:grid-cols-3 md:gap-7">
        {stories.map((story) => (
          <article key={story.number}>
            <div className={`relative flex aspect-[.9] items-end overflow-hidden p-7 ${story.tone}`}>
              <span className="absolute right-6 top-5 display-font text-6xl text-white/15">{story.number}</span>
              <span className="eyebrow relative text-[#e2c99e]">{story.category}</span>
            </div>
            <h2 className="display-font mt-7 text-3xl leading-tight">{story.title}</h2>
            <p className="mt-4 text-xs leading-7 text-[#8e887d]">{story.copy}</p>
            <Link href={story.href} className="mt-6 inline-flex items-center gap-3 border-b border-[#9b7d55] pb-2 text-[9px] uppercase tracking-[.18em]">{story.link} <ArrowUpRight size={13} /></Link>
          </article>
        ))}
      </div>
    </section>
  );
}
