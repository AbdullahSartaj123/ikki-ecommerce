import type { Metadata } from "next";
import "./globals.css";
import Header from "@/components/layout/Header";
import Footer from "@/components/layout/Footer";
import MotionProvider from "@/components/motion/MotionProvider";

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
      <body>
        <MotionProvider>
          <Header />
          <main>{children}</main>
          <Footer />
        </MotionProvider>
      </body>
    </html>
  );
}