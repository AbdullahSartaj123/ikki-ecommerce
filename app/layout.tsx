import type { Metadata } from "next";
import { Fraunces, Manrope } from "next/font/google";
import "./globals.css";
import Header from "@/components/layout/Header";
import Footer from "@/components/layout/Footer";
import MotionProvider from "@/components/motion/MotionProvider";

const displayFont = Fraunces({ subsets: ["latin"], variable: "--font-display", display: "swap" });
const bodyFont = Manrope({ subsets: ["latin"], variable: "--font-sans", display: "swap" });

export const metadata: Metadata = {
  title: { default: "IKKI — Fragrance, considered.", template: "%s | IKKI" },
  description: "A considered collection of distinctive fragrances. Discover the world of IKKI.",
  metadataBase: new URL("https://ikki.pk"),
  openGraph: {
    title: "IKKI — Fragrance, considered.",
    description: "A considered collection of distinctive fragrances.",
    type: "website",
  },
};

export default function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return (
    <html lang="en">
      <body className={`${displayFont.variable} ${bodyFont.variable}`}>
        <MotionProvider>
          <Header />
          <main id="main-content">{children}</main>
          <Footer />
        </MotionProvider>
      </body>
    </html>
  );
}