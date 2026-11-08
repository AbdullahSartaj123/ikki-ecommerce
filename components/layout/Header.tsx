"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { AnimatePresence, motion, useReducedMotion } from "framer-motion";
import { ArrowUpRight, ChevronDown, Search, ShoppingBag, X } from "lucide-react";
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
  const brandsTrigger = useRef<HTMLButtonElement>(null);
  const searchInput = useRef<HTMLInputElement>(null);
  const searchPanel = useRef<HTMLDivElement>(null);
  const mobilePanel = useRef<HTMLDivElement>(null);
  const [mobileBrandsOpen, setMobileBrandsOpen] = useState(false);
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
      else if (mobileOpen) mobilePanel.current?.querySelector("a[href], button:not([disabled])")?.focus();
    }, reduceMotion ? 0 : 180);
    const onKeyDown = (event: KeyboardEvent) => {
      if (event.key === "Escape") {
        if (searchOpen) searchInput.current?.blur();
        setSearchOpen(false);
        setMobileOpen(false);
        setMegaOpen(false);
        if (mobileOpen) mobileTrigger.current?.focus();
      }
      const activePanel = searchOpen ? searchPanel.current : mobileOpen ? mobilePanel.current : null;
      if (event.key === "Tab" && activePanel) {
        const items = activePanel.querySelectorAll<HTMLElement>('a[href], button:not([disabled]), input:not([disabled])');
        if (!items.length) return;
        const first = items[0];
        const last = items[items.length - 1];
        if (event.shiftKey && (document.activeElement === first || !activePanel.contains(document.activeElement))) {
          event.preventDefault();
          last.focus();
        } else if (!event.shiftKey && (document.activeElement === last || !activePanel.contains(document.activeElement))) {
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

  useEffect(() => { if (!mobileOpen) setMobileBrandsOpen(false); }, [mobileOpen]);
  useEffect(() => { setMobileOpen(false); setMobileBrandsOpen(false); setMegaOpen(false); setSearchOpen(false); }, [pathname]);
  const solid = !isHome || scrolled;

  return (
    <>
      <a href="#main-content" className="skip-link">Skip to content</a>
      <header
        className={`ikki-site-header fixed inset-x-0 top-0 z-[100] transition-[background-color,border-color,backdrop-filter,box-shadow] duration-500 ${solid ? "is-solid" : "is-transparent"}`}
        data-scrolled={solid}
        onMouseLeave={closeMega}
        onBlurCapture={(event) => { const next = event.relatedTarget as Node | null; if (!next || !event.currentTarget.contains(next)) setMegaOpen(false); }}
      >
        <div className="ikki-nav-shell">
          <div className="ikki-nav-inner">
            <div className="ikki-nav-left">
              <button
                type="button"
                className={`ikki-mobile-trigger ${mobileOpen ? "is-open" : ""}`}
                aria-label={mobileOpen ? "Close navigation menu" : "Open navigation menu"}
                aria-expanded={mobileOpen}
                onClick={() => { setMobileOpen((value) => !value); setMegaOpen(false); }}
              >
                <span className="ikki-hamburger-lines" aria-hidden="true"><span /><span /></span>
                <span className="ikki-nav-caption">{mobileOpen ? "Close" : "Menu"}</span>
              </button>
              <nav className="ikki-desktop-links" aria-label="Main navigation">
                <div
                  className="ikki-brands-nav"
                  onMouseEnter={() => { if (megaTimer.current) clearTimeout(megaTimer.current); setMegaOpen(true); }}
                >
                  <button
                    type="button"
                    ref={brandsTrigger}
                    className="ikki-nav-link"
                    aria-haspopup="true"
                    aria-controls="ikki-brands-mega"
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
              id="ikki-brands-mega-panel"
              className="ikki-mega-backdrop"
              onKeyDown={(event) => { if (event.key === "Escape") { event.preventDefault(); setMegaOpen(false); brandsTrigger.current?.focus(); } }}
              initial={reduceMotion ? false : { opacity: 0, y: -8 }}
              animate={{ opacity: 1, y: 0 }}
              exit={reduceMotion ? { opacity: 0 } : { opacity: 0, y: -8 }}
              transition={{ duration: reduceMotion ? 0.01 : 0.24, ease: [0.22, 1, 0.36, 1] }}
              onMouseEnter={() => { if (megaTimer.current) clearTimeout(megaTimer.current); }}
              onMouseLeave={closeMega}
            >
              <nav id="ikki-brands-mega" className="ikki-mega-grid" aria-label="Explore IKKI brands">
                  {brands.map((brand, index) => (
                    <motion.article
                      key={brand.slug}
                      className="ikki-mega-card"
                      style={{ ["--brand-primary" as string]: brand.palette.primary, ["--brand-surface" as string]: brand.palette.surface }}
                      initial={reduceMotion ? false : { opacity: 0, y: 16 }}
                      animate={{ opacity: 1, y: 0 }}
                      transition={{ duration: reduceMotion ? 0.01 : 0.38, delay: reduceMotion ? 0 : index * 0.075, ease: [0.22, 1, 0.36, 1] }}
                    >
                      <Link
                        href={`/brands/${brand.slug}/`}
                        className="ikki-mega-card-link"
                        onClick={() => setMegaOpen(false)}
                      >
                        <span className="ikki-mega-card-top">
                          <span className="ikki-mega-card-index">0{index + 1} / 04</span>
                          <span className="ikki-mega-card-wordmark">{brand.name}</span>
                          <span className="ikki-mega-card-tagline">{brand.tagline}</span>
                        </span>
                        <span className="ikki-mega-card-image">
                          <img src={brandPreview[brand.slug]} alt={`${brand.name} visual identity`} loading="lazy" />
                        </span>
                        <span className="ikki-mega-card-shop">Shop {brand.name}<ArrowUpRight size={14} /></span>
                      </Link>
                    </motion.article>
                  ))}
                </nav>
              <div className="ikki-mega-footer"><span>Selected world</span><strong>{activeBrandData.name}</strong><span>{activeBrandData.tagline}</span></div>
            </motion.div>
          )}
        </AnimatePresence>
      </header>

      <AnimatePresence>
        {mobileOpen && (
          <motion.div ref={mobilePanel} className="ikki-mobile-panel" role="dialog" aria-modal="true" aria-label="Mobile navigation"
            initial={reduceMotion ? false : { opacity: 0, y: -18 }} animate={{ opacity: 1, y: 0 }}
            exit={reduceMotion ? { opacity: 0 } : { opacity: 0, y: -12 }}
            transition={{ duration: reduceMotion ? 0.01 : 0.42, ease: [0.22, 1, 0.36, 1] }}>
            <div className="ikki-mobile-menu-inner">
              <div className="ikki-mobile-panel-top"><span className="ikki-overline">Explore the house</span><span className="ikki-mobile-edition">IKKI · EST. IN SCENT</span></div>
              <nav className="ikki-mobile-navigation" aria-label="Mobile main navigation">
                <motion.div initial={reduceMotion ? false : { opacity: 0, y: 22 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: reduceMotion ? 0 : 0.05 }}>
                  <Link href="/collection/" onClick={() => setMobileOpen(false)} className="ikki-mobile-main-link"><span>Shop all</span><ArrowUpRight aria-hidden="true" /></Link>
                </motion.div>
                <motion.div initial={reduceMotion ? false : { opacity: 0, y: 22 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: reduceMotion ? 0 : 0.1 }}>
                  <button type="button" className="ikki-mobile-accordion-trigger" aria-expanded={mobileBrandsOpen} aria-controls="ikki-mobile-brand-list" onClick={() => setMobileBrandsOpen((open) => !open)}><span>The four houses</span><ChevronDown className={mobileBrandsOpen ? "is-open" : ""} aria-hidden="true" /></button>
                  <AnimatePresence initial={false}>
                    {mobileBrandsOpen && (
                      <motion.div id="ikki-mobile-brand-list" className="ikki-mobile-brand-list" initial={reduceMotion ? false : { height: 0, opacity: 0 }} animate={{ height: "auto", opacity: 1 }} exit={reduceMotion ? { opacity: 0 } : { height: 0, opacity: 0 }} transition={{ duration: reduceMotion ? 0.01 : 0.28 }}>
                        {brands.map((brand, index) => (
                          <Link key={brand.slug} href={`/brands/${brand.slug}/`} onClick={() => setMobileOpen(false)} className="ikki-mobile-brand-link" style={{ ["--brand-primary" as string]: brand.palette.primary }}>
                            <span className="ikki-mobile-brand-index">0{index + 1}</span><span className="ikki-mobile-brand-name">{brand.name}</span><ArrowUpRight aria-hidden="true" />
                          </Link>
                        ))}
                      </motion.div>
                    )}
                  </AnimatePresence>
                </motion.div>
                {navLinks.slice(1).map((link, index) => (
                  <motion.div key={link.href} initial={reduceMotion ? false : { opacity: 0, y: 22 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: reduceMotion ? 0 : 0.15 + index * 0.055 }}>
                    <Link href={link.href} onClick={() => setMobileOpen(false)} className="ikki-mobile-main-link"><span>{link.label}</span><ArrowUpRight aria-hidden="true" /></Link>
                  </motion.div>
                ))}
                <motion.button type="button" className="ikki-mobile-search-link" onClick={() => { setMobileOpen(false); setSearchOpen(true); }} initial={reduceMotion ? false : { opacity: 0, y: 18 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: reduceMotion ? 0 : 0.32 }}><Search size={16} /> Search fragrances</motion.button>
              </nav>
            </div>
            <div className="ikki-mobile-cta-wrap"><Link href="/collection/" className="ikki-mobile-shop-cta" onClick={() => setMobileOpen(false)}>Shop now <ArrowUpRight size={17} aria-hidden="true" /></Link><span className="ikki-mobile-cta-note">Find the scent that feels like you.</span></div>
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
