<?php
require_once __DIR__.'/config/bootstrap.php';
$pageTitle='IKKI — The Art of Fragrance';
$description='Discover IKKI, a considered fragrance house. Find a signature scent that feels unmistakably yours.';
$products=[];
try{
 $products=db()->query("SELECT p.id,p.name,p.slug,p.short_description,MIN(v.price) price,(SELECT pi.image_path FROM product_images pi WHERE pi.product_id=p.id ORDER BY pi.is_primary DESC,pi.sort_order LIMIT 1) image_path FROM products p LEFT JOIN product_variants v ON v.product_id=p.id AND v.is_active=1 WHERE p.is_active=1 GROUP BY p.id ORDER BY p.is_featured DESC,p.id LIMIT 4")->fetchAll();
}catch(Throwable $ex){error_log('[IKKI home] '.$ex->getMessage());}
require __DIR__.'/includes/header.php';
?>
<section class="home-hero" aria-labelledby="home-title">
 <div class="home-hero-copy">
  <span class="home-hero-eyebrow">NEW FRAGRANCE</span>
  <h1 id="home-title">The Essence<br>of <em>Elegance</em></h1>
  <p class="home-hero-subtext">A fragrance that speaks before you do. Discover a considered expression of character, crafted for the moments that become memories.</p>
  <div class="home-hero-actions">
   <a class="ikki-btn ikki-btn--primary" href="/shop.php">Shop Collection <span aria-hidden="true">↗</span></a>
   <a class="ikki-btn ikki-btn--secondary" href="/our-story.php">Our Story <span aria-hidden="true">↗</span></a>
  </div>
 </div>
 <div class="home-hero-visual" role="img" aria-label="IKKI Noor fragrance bottle">
  <div class="home-visual-glow" aria-hidden="true"></div>
  <div class="home-hero-orbit" aria-hidden="true"></div>
  <img class="home-hero-bottle" src="/assets/images/noor.svg" alt="IKKI Noor fragrance bottle" width="500" height="690" loading="eager" fetchpriority="high" decoding="async">
  <span class="home-hero-edition" aria-hidden="true">IKKI · Nº 01<br><strong>NOOR</strong></span>
 </div>
 <a class="home-scroll-cue" href="#signature-scents" aria-label="Scroll to discover"><span></span></a>
</section>

<section class="home-collection" id="signature-scents" aria-labelledby="signature-title">
 <div class="home-section-heading home-reveal"><div><span class="home-kicker">01 / FIND YOUR SIGNATURE</span><h2 id="signature-title">Our signature<br><em>scents.</em></h2></div><a class="home-text-link" href="/shop.php">VIEW THE COLLECTION <span>↗</span></a></div>
 <div class="home-products">
 <?php if($products): foreach($products as $n=>$p): ?>
  <a class="home-product home-reveal" href="/product.php?slug=<?=e($p['slug'])?>">
   <div class="home-product-art"><span class="home-product-number">0<?=($n+1)?></span><img src="<?=e($p['image_path']?:'/assets/images/noor.svg')?>" alt="<?=e($p['name'])?> fragrance bottle" width="800" height="1000" loading="lazy" decoding="async"><span class="home-product-open">DISCOVER THIS SCENT ↗</span></div>
   <div class="home-product-meta"><div><h3><?=e($p['name'])?></h3><p><?=e($p['short_description']??'An IKKI signature')?></p></div><strong><?=$p['price']!==null?money($p['price']):'DISCOVER'?></strong></div>
  </a>
 <?php endforeach; else: ?>
  <p class="home-collection-note">Our fragrance collection is being prepared. <a href="/contact.php">Contact us for assistance.</a></p>
 <?php endif; ?>
 </div>
 <p class="home-collection-note home-reveal">Distinct expressions. One unmistakable point of view.</p>
</section>

<section class="home-story-teaser" aria-labelledby="story-title">
 <div class="home-story-parallax" aria-hidden="true"><img class="home-story-image" src="/assets/images/sahra.svg" alt="Sahra fragrance bottle artwork displayed in the IKKI brand story" width="800" height="1000" loading="lazy" decoding="async"></div>
 <div class="home-story-overlay" aria-hidden="true"></div>
 <div class="home-story-content home-reveal" data-story-reveal>
  <span class="home-story-eyebrow">THE IKKI FRAGRANCE HOUSE</span>
  <h2 id="story-title">The Art of Fragrance</h2>
  <p><em>Every fragrance begins with an idea, takes shape through thoughtful composition, and becomes part of a story only you can tell. Discover the art behind the scent.</em></p>
  <a class="ikki-btn ikki-btn--secondary home-story-button" href="/our-story.php">Discover Our Story <span aria-hidden="true">↗</span></a>
 </div>
</section>

<section class="home-perfumery" aria-labelledby="perfumery-title">
 <div class="home-perfumery-heading home-reveal">
  <span class="home-kicker">THE SCIENCE OF SCENT</span>
  <h2 id="perfumery-title">The Art of Perfumery</h2>
  <p>Where nature, expertise and time come together to create something unforgettable.</p>
 </div>
 <div class="home-perfumery-grid">
  <article class="home-perfumery-item home-reveal">
   <span class="home-perfumery-icon" aria-hidden="true">
    <svg viewBox="0 0 48 48"><path d="M24 26c-5-4-8-9-5-13 3-4 7-2 5 2 0-6 5-8 8-4 3 4-1 10-8 15Z"/><path d="M24 26c-7-2-13-1-14 4-1 4 4 7 7 3-2 5 2 9 6 6 4-3 4-9 1-13ZM24 26c7-2 13-1 14 4 1 4-4 7-7 3 2 5-2 9-6 6"/><path d="M24 26v15M19 41h10"/></svg>
   </span>
   <h3>Premium Ingredients</h3>
   <p>Exceptional raw materials, thoughtfully selected for their character, depth and beauty.</p>
  </article>
  <article class="home-perfumery-item home-reveal">
   <span class="home-perfumery-icon" aria-hidden="true">
    <svg viewBox="0 0 48 48"><path d="M12 25 8 21a3 3 0 0 1 4-4l7 6"/><path d="m19 23-5-7a3 3 0 0 1 5-3l7 8"/><path d="m26 21-3-8a3 3 0 0 1 6-2l4 10"/><path d="m33 21 1-5a3 3 0 0 1 6 1l-2 13c-1 7-6 11-13 11h-4c-5 0-8-3-11-8l-4-7a3 3 0 0 1 5-3l5 6"/></svg>
   </span>
   <h3>The Art of Blending</h3>
   <p>Thoughtful composition brings individual notes together in a balanced fragrance story.</p>
  </article>
  <article class="home-perfumery-item home-reveal">
   <span class="home-perfumery-icon" aria-hidden="true">
    <svg viewBox="0 0 48 48"><path d="M16 7h16M16 41h16M18 7c0 8 2 11 6 17-4 6-6 9-6 17M30 7c0 8-2 11-6 17 4 6 6 9 6 17"/><path d="M19 17h10M19 31h10M20 34l4-3 4 3"/></svg>
   </span>
   <h3>Lasting Elegance</h3>
   <p>Beautifully balanced compositions designed to unfold gracefully and linger in memory.</p>
  </article>
 </div>
</section>

<section class="home-instagram home-contact-cta" aria-labelledby="home-contact-title">
 <div class="home-instagram-heading home-reveal">
  <span class="home-kicker">A PERSONAL FRAGRANCE EXPERIENCE</span>
  <h2 id="home-contact-title">Find the scent that feels like you.</h2>
  <p>Need help choosing? Speak with IKKI about the collection.</p>
  <a class="ikki-btn ikki-btn--primary" href="https://wa.me/923094733789" target="_blank" rel="noopener noreferrer">Chat with IKKI <span aria-hidden="true">↗</span></a>
 </div>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>
