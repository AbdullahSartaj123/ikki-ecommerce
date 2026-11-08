import type { Config } from "tailwindcss";

/**
 * IKKI — House of Fragrance
 * Cinematic editorial luxury design tokens.
 *
 * Fonts are loaded in the application shell:
 *   --font-display: "Fraunces"
 *   --font-sans: "Manrope"
 *
 * Keep these tokens semantic so shared components can use the same utilities
 * while brand experiences opt into their own accent palettes.
 */
const config: Config = {
  content: [
    "./app/**/*.{js,ts,jsx,tsx,mdx}",
    "./components/**/*.{js,ts,jsx,tsx,mdx}",
    "./lib/**/*.{js,ts,jsx,tsx,mdx}",
  ],
  theme: {
    extend: {
      colors: {
        // Core house palette
        ink: "#080807",
        paper: "#F4F0E8",
        cream: "#E9E1D4",
        brass: "#B08A57",
        muted: "#A39A8D",

        midnight: "#080807",
        obsidian: "#10100F",
        charcoal: "#191816",
        graphite: "#252320",
        smoke: "#393631",
        ash: "#777168",

        // Warm metallics — use as accents, rules and fine details
        metal: {
          gold: "#C6A36B",
          bronze: "#956B45",
          champagne: "#E2C99E",
          antique: "#80613E",
          pale: "#F0DFC0",
        },

        // Editorial light surfaces
        ivory: "#F4F0E8",
        parchment: "#E9E1D4",
        porcelain: "#FBF9F5",

        // Brand identities
        brand: {
          legendary: {
            DEFAULT: "#C6A36B",
            soft: "#E0C18B",
            deep: "#80613E",
            surface: "#211C15",
            contrast: "#080807",
          },
          informal: {
            DEFAULT: "#C77B5B",
            soft: "#D9A58B",
            deep: "#96563F",
            surface: "#261B17",
            contrast: "#100C0A",
          },
          amane: {
            DEFAULT: "#D9A9AD",
            soft: "#F0D8D6",
            deep: "#A8757D",
            surface: "#251B20",
            contrast: "#100B0E",
          },
          sasha: {
            DEFAULT: "#9B78F5",
            soft: "#C4B0FF",
            deep: "#6544C7",
            chrome: "#C8CDD5",
            surface: "#1C1927",
            contrast: "#09080D",
          },
        },

        // Functional UI colors
        line: {
          DEFAULT: "rgba(226, 201, 158, 0.18)",
          strong: "rgba(226, 201, 158, 0.34)",
          subtle: "rgba(255, 255, 255, 0.09)",
        },
        state: {
          success: "#78947A",
          warning: "#D2A35E",
          danger: "#B96F68",
          info: "#8199B2",
        },
      },

      fontFamily: {
        display: ["Fraunces", "Georgia", "serif"],
        serif: ["Fraunces", "Georgia", "serif"],
        sans: ["Manrope", "Arial", "sans-serif"],
        body: ["Manrope", "Arial", "sans-serif"],
        mono: ["ui-monospace", "SFMono-Regular", "monospace"],
      },

      fontSize: {
        "2xs": ["0.625rem", { lineHeight: "0.9rem", letterSpacing: "0.12em" }],
        xs: ["0.75rem", { lineHeight: "1.1rem" }],
        sm: ["0.875rem", { lineHeight: "1.4rem" }],
        base: ["1rem", { lineHeight: "1.75rem" }],
        lg: ["1.125rem", { lineHeight: "1.85rem" }],
        xl: ["1.25rem", { lineHeight: "1.9rem" }],
        "2xl": ["1.5rem", { lineHeight: "2rem" }],
        "3xl": ["1.875rem", { lineHeight: "2.3rem" }],
        "4xl": ["2.25rem", { lineHeight: "2.6rem" }],
        "5xl": ["3rem", { lineHeight: "1.08" }],
        "6xl": ["3.75rem", { lineHeight: "1.04" }],
        "7xl": ["4.5rem", { lineHeight: "1.02" }],
        "8xl": ["6rem", { lineHeight: "0.98" }],
        "9xl": ["8rem", { lineHeight: "0.94" }],
        "display-sm": ["clamp(2.5rem, 5vw, 4.5rem)", { lineHeight: "1.02", letterSpacing: "-0.045em" }],
        "display-md": ["clamp(3.5rem, 8vw, 7rem)", { lineHeight: "0.98", letterSpacing: "-0.055em" }],
        "display-lg": ["clamp(4rem, 11vw, 10rem)", { lineHeight: "0.9", letterSpacing: "-0.065em" }],
      },

      spacing: {
        "0.5": "0.125rem",
        "1.5": "0.375rem",
        "2.5": "0.625rem",
        "3.5": "0.875rem",
        "4.5": "1.125rem",
        "5.5": "1.375rem",
        "6.5": "1.625rem",
        "7.5": "1.875rem",
        "18": "4.5rem",
        "22": "5.5rem",
        "26": "6.5rem",
        "30": "7.5rem",
        "34": "8.5rem",
        "section": "6rem",
        "gutter": "clamp(1.25rem, 5vw, 5rem)",
      },

      borderRadius: {
        none: "0",
        xs: "0.125rem",
        sm: "0.25rem",
        DEFAULT: "0.375rem",
        md: "0.5rem",
        lg: "0.75rem",
        xl: "1rem",
        "2xl": "1.5rem",
        "3xl": "2rem",
        "4xl": "2.5rem",
        pill: "9999px",
        editorial: "0.125rem",
      },

      letterSpacing: {
        tighter: "-0.055em",
        tight: "-0.035em",
        normal: "0",
        wide: "0.08em",
        wider: "0.14em",
        widest: "0.22em",
        luxury: "0.28em",
        micro: "0.32em",
      },

      lineHeight: {
        editorial: "0.92",
        heading: "1.04",
        copy: "1.75",
        relaxed: "1.9",
      },

      maxWidth: {
        prose: "42rem",
        editorial: "56rem",
        "site-narrow": "72rem",
        "site": "90rem",
        "site-wide": "100rem",
      },

      boxShadow: {
        "soft-gold": "0 0 36px rgba(198, 163, 107, 0.12)",
        "gold-glow": "0 0 70px rgba(198, 163, 107, 0.18)",
        "violet-glow": "0 0 70px rgba(155, 120, 245, 0.2)",
        "card-dark": "0 24px 80px rgba(0, 0, 0, 0.38)",
        "inset-line": "inset 0 0 0 1px rgba(226, 201, 158, 0.16)",
      },

      backgroundImage: {
        "metallic-gold": "linear-gradient(115deg, #80613E 0%, #E2C99E 42%, #A77B49 72%, #EAD8B7 100%)",
        "obsidian-fade": "linear-gradient(180deg, rgba(8,8,7,0) 0%, #080807 100%)",
        "cinematic-glow": "radial-gradient(ellipse at 50% 0%, rgba(198,163,107,0.14) 0%, rgba(8,8,7,0) 65%)",
        "violet-glow": "radial-gradient(ellipse at 50% 50%, rgba(155,120,245,0.18) 0%, rgba(9,8,13,0) 70%)",
      },

      transitionDuration: {
        400: "400ms",
        600: "600ms",
        800: "800ms",
      },

      transitionTimingFunction: {
        luxury: "cubic-bezier(0.22, 1, 0.36, 1)",
        reveal: "cubic-bezier(0.16, 1, 0.3, 1)",
      },

      zIndex: {
        "1": "1",
        "5": "5",
        "60": "60",
        "70": "70",
        "100": "100",
      },
    },
  },
  plugins: [],
};

export default config;
