import type { Variants, Transition } from "framer-motion";

export const luxuryEase = [0.22, 1, 0.36, 1] as const;
export const revealEase = [0.16, 1, 0.3, 1] as const;

export const transitions = {
  fast: { duration: 0.35, ease: luxuryEase },
  base: { duration: 0.65, ease: luxuryEase },
  slow: { duration: 0.95, ease: luxuryEase },
  page: { duration: 0.8, ease: revealEase },
} satisfies Record<string, Transition>;

export const fadeIn: Variants = {
  hidden: { opacity: 0 },
  visible: { opacity: 1, transition: transitions.base },
};

export const riseIn: Variants = {
  hidden: { opacity: 0, y: 36 },
  visible: { opacity: 1, y: 0, transition: transitions.slow },
};

export const revealFromLeft: Variants = {
  hidden: { opacity: 0, x: -42 },
  visible: { opacity: 1, x: 0, transition: transitions.slow },
};

export const revealFromRight: Variants = {
  hidden: { opacity: 0, x: 42 },
  visible: { opacity: 1, x: 0, transition: transitions.slow },
};

export const scaleReveal: Variants = {
  hidden: { opacity: 0, scale: 0.94, y: 18 },
  visible: { opacity: 1, scale: 1, y: 0, transition: transitions.slow },
};

export const staggerContainer: Variants = {
  hidden: {},
  visible: { transition: { staggerChildren: 0.12, delayChildren: 0.08 } },
};

export const wordStagger: Variants = {
  hidden: { opacity: 0, y: "0.7em", rotateX: -35 },
  visible: {
    opacity: 1,
    y: 0,
    rotateX: 0,
    transition: { duration: 0.75, ease: luxuryEase },
  },
};

export const letterStagger: Variants = {
  hidden: { opacity: 0, y: "0.55em", filter: "blur(8px)" },
  visible: {
    opacity: 1,
    y: 0,
    filter: "blur(0px)",
    transition: { duration: 0.6, ease: luxuryEase },
  },
};

export const pageVariants: Variants = {
  initial: { opacity: 0, y: 14, filter: "blur(5px)" },
  enter: {
    opacity: 1,
    y: 0,
    filter: "blur(0px)",
    transition: { duration: 0.7, ease: revealEase },
  },
  exit: {
    opacity: 0,
    y: -10,
    filter: "blur(3px)",
    transition: { duration: 0.28, ease: [0.4, 0, 1, 1] },
  },
};

export const hoverLift = {
  y: -7,
  transition: { duration: 0.4, ease: luxuryEase },
};

export const buttonPress = {
  scale: 0.97,
  transition: { duration: 0.18, ease: "easeOut" },
};