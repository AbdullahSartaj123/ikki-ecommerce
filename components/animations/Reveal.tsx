"use client";

import { motion } from "framer-motion";
import type { ReactNode } from "react";
import { riseIn } from "@/lib/motion/variants";

type RevealProps = {
  children: ReactNode;
  delay?: number;
  className?: string;
};

export default function Reveal({ children, delay = 0, className = "" }: RevealProps) {
  return (
    <motion.div
      className={className}
      variants={riseIn}
      initial="hidden"
      whileInView="visible"
      viewport={{ once: true, amount: 0.18 }}
      transition={{ delay }}
    >
      {children}
    </motion.div>
  );
}