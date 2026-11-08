"use client";

import { motion, type Variants } from "framer-motion";
import { letterStagger, wordStagger } from "@/lib/motion/variants";

type TextRevealProps = {
  text: string;
  by?: "word" | "letter";
  className?: string;
  delay?: number;
  stagger?: number;
  as?: "span" | "h1" | "h2" | "h3" | "p";
};

export default function TextReveal({
  text,
  by = "word",
  className = "",
  delay = 0,
  stagger = by === "letter" ? 0.025 : 0.075,
  as = "span",
}: TextRevealProps) {
  const Tag = motion.create(as);
  const pieces = by === "letter" ? Array.from(text) : text.split(" ");
  const childVariants = (by === "letter" ? letterStagger : wordStagger) as Variants;

  return (
    <Tag
      className={className}
      aria-label={text}
      initial="hidden"
      whileInView="visible"
      viewport={{ once: true, amount: 0.65 }}
      variants={{ hidden: {}, visible: { transition: { staggerChildren: stagger, delayChildren: delay } } }}
    >
      {pieces.map((piece, index) => (
        <motion.span
          key={`${piece}-${index}`}
          variants={childVariants}
          aria-hidden="true"
          className="inline-block"
          style={{ whiteSpace: "pre" }}
        >
          {by === "word" && index > 0 ? ` ${piece}` : piece === " " ? "\u00a0" : piece}
        </motion.span>
      ))}
    </Tag>
  );
}