"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { AnimatePresence, motion, useReducedMotion } from "framer-motion";
import { ArrowUpRight, ChevronDown, Menu, Search, ShoppingBag, X } from "lucide-react";
import { useCallback, useEffect, useMemo, useRef, useState } from "react";
import { brands } from "@/lib/data/brands";
import { products } from "@/lib/data/products";

const navLinks = [
  { href: "/collection/", label: "Shop" },
  { href: "/journal/", label: "Journal" },
  { href: "/our-story/", label: "About" },
  { href: "/contact/", label: "Contact" },
];

const brandPreview = {
  "legendary-profile": "/images/brand-previews/legendary-profile.svg",
  informal: "/images/brand-previews/informal.svg",
  amane: "/images/brand-previews/amane.svg",
  sasha: "/images/brand-previews/sasha.svg",
} as const;

export default function Header() {
  const pathname = usePathname();
  const reduceMotion = useReducedMotion();
  const isHome = pathname === "/";
  const [scrolled, setScrolled] = useState(false);
  const [megaOpen, setMegaOpen] = useState(false);
  const [mobileOpen, setMobileOpen] = useState(false);
  const [searchOpen, setSearchOpen] = useState(false);
  const [query, setQuery] = useState("");
  const [activeBrand, setActiveBrand] = useState(brands[0].slug);
  const searchInput = useRef<HTMLInputElement>(null);
  const searchPanel = useRef<HTMLDivElement>(null);
  const megaTimer = useRef<ReturnType<typeof setTimeout> | null>(null);

  useEffect(() => {
    let frame = 0;
    const update = () => {
      if (frame) cancelAnimationFrame(frame);
      frame = requestAnimationFrame(() => setScrolled(window.scrollY > 100));
    };
    update();
    window.addEventListener("scroll", update, { passive: true });
    return () => {
      window.removeEventListener("scroll", update);
      if (frame) cancelAnimationFrame(frame);
    };
  }, []);

  useEffect(() => {
    if (!searchOpen && !mobileOpen) return;
    const previous = document.body.style.overflow;
    document.body.style.overflow = "hidden";
    const focusTimer = window.setTimeout(() => {
      if (searchOpen) searchInput.current?.focus();
    }, reduceMotion ? 0 : 180);
    const onKeyDown = (event: KeyboardEvent) => {
      if (event.key === "Escape") {
        setSearchOpen(false);
        setMobileOpen(false);
        setMegaOpen(false);
      }
      if (event.key === "Tab" && searchOpen && searchPanel.current) {
        const items = searchPanel.current.querySelectorAll<HTMLElement>(
          'a[href], button:not([disabled]), input:not([disabled])',
        );
        if (!items.length) return;
        const first = items[0];
        const last = items[items.length - 1];
        if (event.shiftKey && document.activeElement === first) {
          event.preventDefault();
          last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
          event.preventDefault();
          first.focus();
        }
      }
    };
    document.addEventListener("keydown", onKeyDown);
    return () => {
      document.body.style.overflow = previous;
      window.clearTimeout(focusTimer);
      document.removeEventListener("keydown", onKeyDown);
    };
  }, [searchOpen, mobileOpen, reduceMotion]);

  useEffect(() => () => {
    if (megaTimer.current) clearTimeout(megaTimer.current);
  }, []);

  const closeMega = useCallback(() => {
    if (megaTimer.current) clearTimeout(megaTimer.current);
    megaTimer.current = setTimeout(() => setMegaOpen(false), 120);
  }, []);

  const filteredProducts = useMemo(() => {
    const normalized = query.trim().toLowerCase();
    if (!normalized) return products.slice(0, 4);
    return products.filter((product) => {
      const brand = brands.find((item) => item.slug === product.brand);
      return [product.name, product.category, product.notesSummary, brand?.name]
        .join(" ")
        .toLowerCase()
        .includes(normalized);
    }).slice(0, 6);
  }, [query]);

  const filteredBrands = useMemo(() => {
    const normalized = query.trim().toLowerCase();
    return normalized
      ? brands.filter((brand) => [brand.name, brand.tagline].join(" ").toLowerCase().includes(normalized))
      : brands;
  }, [query]);

  const solid = !isHome || scrolled;
  const activeBrandData = brands.find((brand) => brand.slug === activeBrand) ?? brands[0];

  return (
    <>
      <a href="#main-content" className="skip-link">Skip to content</a>
      <header
        className={`ikki-site-header fixed inset-x-0 top-0 z-[80] transition-[background-color,border-color,backdrop-filter,box-shadow] duration-500 ${solid ? "is-solid" : "is-transparent"}`}
        data-scrolled={solid}
        onMouseLeave={closeMega}
      >
        <div className="ikki-nav-shell">
          <div className="ikki-nav-inner">
            <div className="ikki-nav-left">
              <button
                type="button"
                className="ikki-mobile-trigger"
                aria-label={mobileOpen ? "Close navigation menu" : "Open navigation menu"}
                aria-expanded={mobileOpen}
                onClick={() => { setMobileOpen((value) => !value); setMegaOpen(false); }}
              >
                {mobileOpen ? <X size={20} strokeWidth={1.4} /> : <Menu size={20} strokeWidth={1.4} />}
                <span className="ikki-nav-caption">Menu</span>
              </button>
              <nav className="ikki-desktop-links" aria-label="Main navigation">
                <div
                  className="ikki-brands-nav"
                  onMouseEnter={() => { if (megaTimer.current) clearTimeout(megaTimer.current); setMegaOpen(true); }}
                >
                  <button
                    type="button"
                    className="ikki-nav-link"
                    aria-haspopup="true"
                    aria-expanded={megaOpen}
                    onClick={() => setMegaOpen((value) => !value)}
                    onFocus={() => setMegaOpen(true)}
                  >
                    Brands <ChevronDown size={12} className={`ikki-chevron ${megaOpen ? "is-open" : ""}`} />
                  </button>
                </div>
                {navLinks.map((link) => (
                  <Link key={link.href} href={link.href} className="ikki-nav-link">{link.label}</Link>
                ))}
              </nav>
            </div>

            <Link href="/" aria-label="IKKI — Home" className="ikki-wordmark">
              <span>IKKI</span>
              <small>HOUSE OF FRAGRANCE</small>
            </Link>

            <div className="ikki-nav-actions">
              <button type="button" className="ikki-icon-button" aria-label="Open search" onClick={() => { setSearchOpen(true); setMegaOpen(false); }}>
                <Search size={19} strokeWidth={1.45} />
              </button>
              <Link href="/cart/" className="ikki-cart-link" aria-label="Shopping bag, 0 items">
                <span className="ikki-cart-icon"><ShoppingBag size={19} strokeWidth={1.4} /><span className="ikki-cart-count">0</span></span>
                <span className="ikki-cart-label">Bag</span>
              </Link>
            </div>
          </div>
        </div>

        <AnimatePresence>
          {megaOpen && (
            <motion.div
              className="ikki-mega-backdrop"
              initial={reduceMotion ? false : { opacity: 0, y: -8 }}
              animate={{ opacity: 1, y: 0 }}
              exit={reduceMotion ? { opacity: 0 } : { opacity: 0, y: -8 }}
              transition={{ duration: reduceMotion ? 0.01 : 0.24, ease: [0.22, 1, 0.36, 1] }}
              onMouseEnter={() => { if (megaTimer.current) clearTimeout(megaTimer.current); }}
              onMouseLeave={closeMega}
            >
              <div className="ikki-mega-inner">
                <div className="ikki-mega-intro">
                  <span className="ikki-overline">Four worlds. One house.</span>
                  <h2>Find your<br /><i>signature.</i></h2>
                  <p>Distinct expressions, each with a world of its own.</p>
                  <Link href="/collection/" className="ikki-mega-all" onClick={() => setMegaOpen(false)}>Explore all fragrances <ArrowUpRight size={14} /></Link>
                </div>
                <div className="ikki-mega-brands">
                  {brands.map((brand, index) => (
                    <motion.div
                      key={brand.slug}
                      className="ikki-mega-brand"
                      initial={reduceMotion ? false : { opacity: 0, y: 16 }}
                      animate={{ opacity: 1, y: 0 }}
                      transition={{ duration: reduceMotion ? 0.01 : 0.38, delay: reduceMotion ? 0 : index * 0.075, ease: [0.22, 1, 0.36, 1] }}
                    >
                      <Link
                        href={`/brands/${brand.slug}/`}
                        className="ikki-mega-brand-link"
                        onMouseEnter={() => setActiveBrand(brand.slug)}
                        onFocus={() => setActiveBrand(brand.slug)}
                        onClick={() => setMegaOpen(false)}
                      >
                        <span className="ikki-mega-image" style={{ ["--brand-accent" as string]: brand.palette.primary }}>
                          <img src={brandPreview[brand.slug]} alt="" aria-hidden="true" />
                          <span className="ikki-mega-image-index">0{index + 1}</span>
                          <span className="ikki-mega-image-arrow"><ArrowUpRight size={15} /></span>
                        </span>
                        <span className="ikki-mega-brand-name">{brand.name}</span>
                        <span className="ikki-mega-brand-tagline">{brand.tagline}</span>
                      </Link>
                    </motion.div>
                  ))}
                </div>
              </div>
              <div className="ikki-mega-footer"><span>Selected world</span><strong>{activeBrandData.name}</strong><span>{activeBrandData.tagline}</span></div>
            </motion.div>
          )}
        </AnimatePresence>
      </header>

      <AnimatePresence>
        {mobileOpen && (
          <motion.div
            className="ikki-mobile-panel"
            initial={reduceMotion ? false : { opacity: 0, y: -12 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -12 }}
            transition={{ duration: reduceMotion ? 0.01 : 0.3 }}
          >
            <div className="ikki-mobile-panel-top"><span className="ikki-overline">Explore IKKI</span><button type="button" onClick={() => setMobileOpen(false)} aria-label="Close menu"><X size={22} /></button></div>
            <Link href="/collection/" onClick={() => setMobileOpen(false)} className="ikki-mobile-main-link">Shop <ArrowUpRight size={18} /></Link>
            <p className="ikki-mobile-label">The four houses</p>
            {brands.map((brand, index) => (
              <Link key={brand.slug} href={`/brands/${brand.slug}/`} onClick={() => setMobileOpen(false)} className="ikki-mobile-brand-link">
                <span>0{index + 1}</span>{brand.name}<ArrowUpRight size={15} />
              </Link>
            ))}
            <div className="ikki-mobile-secondary">
              {navLinks.slice(1).map((link) => <Link key={link.href} href={link.href} onClick={() => setMobileOpen(false)}>{link.label}</Link>)}
              <button type="button" onClick={() => { setMobileOpen(false); setSearchOpen(true); }}><Search size={15} /> Search</button>
            </div>
          </motion.div>
        )}
      </AnimatePresence>

      <AnimatePresence>
        {searchOpen && (
          <motion.div
            className="ikki-search-overlay"
            initial={reduceMotion ? false : { opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            transition={{ duration: reduceMotion ? 0.01 : 0.25 }}
            role="presentation"
            onMouseDown={(event) => { if (event.target === event.currentTarget) setSearchOpen(false); }}
          >
            <motion.div
              ref={searchPanel}
              className="ikki-search-panel"
              role="dialog"
              aria-modal="true"
              aria-labelledby="ikki-search-title"
              initial={reduceMotion ? false : { opacity: 0, y: 28 }}
              animate={{ opacity: 1, y: 0 }}
              exit={reduceMotion ? { opacity: 0 } : { opacity: 0, y: 18 }}
              transition={{ duration: reduceMotion ? 0.01 : 0.42, ease: [0.22, 1, 0.36, 1] }}
            >
              <div className="ikki-search-top">
                <span className="ikki-overline" id="ikki-search-title">A world of fragrance</span>
                <button type="button" className="ikki-search-close" onClick={() => setSearchOpen(false)} aria-label="Close search"><X size={22} /></button>
              </div>
              <label className="ikki-search-field">
                <Search size={23} strokeWidth={1.35} />
                <input ref={searchInput} type="search" value={query} onChange={(event) => setQuery(event.target.value)} placeholder="Search a fragrance, note or house..." autoComplete="off" />
                {query && <button type="button" onClick={() => setQuery("")} aria-label="Clear search"><X size={17} /></button>}
              </label>
              <div className="ikki-search-results">
                <div className="ikki-search-column">
                  <span className="ikki-search-section-label">{query ? "Matching houses" : "Explore the houses"}</span>
                  {filteredBrands.length ? filteredBrands.map((brand) => (
                    <Link key={brand.slug} href={`/brands/${brand.slug}/`} onClick={() => setSearchOpen(false)} className="ikki-search-brand-result">
                      <span>{brand.name}</span><ArrowUpRight size={14} />
                    </Link>
                  )) : <p className="ikki-search-empty">No matching houses.</p>}
                </div>
                <div className="ikki-search-column">
                  <span className="ikki-search-section-label">{query ? "Fragrance results" : "Consider these signatures"}</span>
                  {filteredProducts.length ? filteredProducts.map((product) => (
                    <Link key={product.slug} href={`/fragrance/${product.slug}/`} onClick={() => setSearchOpen(false)} className="ikki-search-product-result">
                      <span><strong>{product.name}</strong><small>{brands.find((brand) => brand.slug === product.brand)?.name} · {product.category}</small></span>
                      <ArrowUpRight size={14} />
                    </Link>
                  )) : <p className="ikki-search-empty">No fragrances found. Try another note or name.</p>}
                </div>
              </div>
              <div className="ikki-search-bottom"><span>IKKI · HOUSE OF FRAGRANCE</span><span>Search by name, mood or note</span></div>
            </motion.div>
          </motion.div>
        )}
      </AnimatePresence>
    </>
  );
}
