<?php
require_once __DIR__.'/config/bootstrap.php';
$pageTitle='IKKI — The Art of Fragrance';
$description='Discover IKKI, a considered fragrance house. Explore expressive scents and find a fragrance that feels unmistakably yours.';
$products=[];
try{
 $products=db()->query("SELECT p.id,p.name,p.slug,p.short_description,MIN(v.price) price,(SELECT pi.image_path FROM product_images pi WHERE pi.product_id=p.id ORDER BY pi.is_primary DESC,pi.sort_order LIMIT 1) image_path FROM products p LEFT JOIN product_variants v ON v.product_id=p.id AND v.is_active=1 WHERE p.is_active=1 GROUP BY p.id ORDER BY p.is_featured DESC,p.id LIMIT 4")->fetchAll();
}catch(Throwable $ex){error_log('[IKKI home] '.$ex->getMessage());}
require __DIR__.'/includes/header.php';
?>
<section class="home-hero" aria-labelledby="home-title">
 <div class="home-hero-copy">
  <span class="home-hero-eyebrow">IKKI · THE ART OF FRAGRANCE</span>
  <p class="home-overline">A PERSONAL EXPRESSION, BEAUTIFULLY COMPOSED</p>
  <h1 id="home-title">Elegance<br>is <em>timeless.</em></h1>
  <p class="home-hero-subtext">Discover a world of refined fragrances. Explore expressive scent stories and find the one that feels unmistakably yours.</p>
  <div class="home-hero-actions">
   <a class="ikki-btn ikki-btn--primary" href="/shop.php">Explore Collection <span aria-hidden="true">↗</span></a>
   <a class="ikki-btn ikki-btn--secondary home-quiet-link" href="/our-story.php">Discover IKKI <span aria-hidden="true">↗</span></a>
  </div>
  <div class="home-hero-foot" aria-label="IKKI fragrance collection"><span>CONSIDERED COMPOSITIONS</span><span>PERSONAL EXPRESSION</span><span>IKKI · EST. IN EVERY MOMENT</span></div>
 </div>
 <div class="home-hero-visual" role="img" aria-label="IKKI Noor fragrance bottle">
  <div class="home-visual-glow" aria-hidden="true"></div>
  <div class="home-hero-orbit" aria-hidden="true"></div>
  <div class="home-ring home-ring-b" aria-hidden="true"></div>
  <img class="home-hero-bottle" src="/assets/images/noor.svg" alt="IKKI Noor fragrance bottle" width="500" height="690" loading="eager" fetchpriority="high" decoding="async">
  <span class="home-visual-stamp" aria-hidden="true"><small>IKKI · Nº 01</small><strong>NOOR</strong><small>THE SIGNATURE COLLECTION</small></span>
  <span class="home-vertical-note" aria-hidden="true">A SCENT THAT STAYS WITH YOU</span>
 </div>
 <a class="home-scroll-cue" href="#home-philosophy" aria-label="Scroll to discover"><span></span> DISCOVER IKKI</a>
</section>

<div class="home-marquee" aria-label="IKKI fragrance philosophy"><div>THE ART OF FRAGRANCE <b>✳</b> ELEGANCE IS TIMELESS <b>✳</b> FIND YOUR SIGNATURE <b>✳</b> THE ART OF FRAGRANCE <b>✳</b> ELEGANCE IS TIMELESS <b>✳</b> FIND YOUR SIGNATURE <b>✳</b></div></div>

<section class="home-philosophy" id="home-philosophy" aria-labelledby="philosophy-title">
 <span class="home-section-index">01 / THE IKKI POINT OF VIEW</span>
 <div class="home-philosophy-title"><span class="home-kicker">MORE THAN A FRAGRANCE</span><h2 id="philosophy-title">A signature<br>is <em>personal.</em></h2></div>
 <div class="home-philosophy-copy"><p>A fragrance is more than the notes that compose it. It is a feeling, a memory, a quiet expression of who you are. IKKI invites you to discover the scent that becomes part of your story.</p><a class="home-text-link" href="/our-story.php">THE IKKI STORY <span>↗</span></a></div>
</section>

<section class="home-collection" id="signature-scents" aria-labelledby="signature-title">
 <div class="home-section-heading home-reveal"><div><span class="home-kicker">02 / FIND YOUR SIGNATURE</span><h2 id="signature-title">The signature<br><em>collection.</em></h2></div><a class="home-text-link" href="/shop.php">VIEW THE COLLECTION <span>↗</span></a></div>
 <div class="home-products">
 <?php if($products): foreach($products as $n=>$p): ?>
  <a class="home-product home-reveal" href="/product.php?slug=<?=e($p['slug'])?>">
   <div class="home-product-art"><span class="home-product-number">0<?=($n+1)?></span><img src="<?=e($p['image_path']?:'/assets/images/noor.svg')?>" alt="<?=e($p['name'])?> fragrance bottle" width="800" height="1000" loading="lazy" decoding="async"><span class="home-product-open">DISCOVER THIS SCENT ↗</span></div>
   <div class="home-product-meta"><div><h3><?=e($p['name'])?></h3><p><?=e($p['short_description']??'An IKKI fragrance')?></p></div><strong><?=$p['price']!==null?money($p['price']):'DISCOVER'?></strong></div>
  </a>
 <?php endforeach; else: ?>
  <p class="home-collection-note">Our fragrance collection is being prepared. <a href="/contact.php">Contact us for assistance.</a></p>
 <?php endif; ?>
 </div>
 <p class="home-collection-note home-reveal">Distinct expressions. One unmistakable point of view.</p>
</section>

<section class="home-feature" aria-labelledby="home-feature-title">
 <div class="home-feature-art"><img src="/assets/images/sahra.svg" alt="Sahra fragrance bottle from the IKKI collection" width="800" height="1000" loading="lazy" decoding="async"><span>IKKI · A STUDY IN SCENT</span></div>
 <div class="home-feature-copy home-reveal"><span class="home-kicker">03 / THE EXPERIENCE</span><h2 id="home-feature-title">Find a scent<br>that feels like <em>you.</em></h2><p>From the first impression to the final lingering note, discover a fragrance that complements your individuality and makes every moment feel your own.</p><a class="home-outline-button" href="/shop.php">Explore the fragrances <span>↗</span></a></div>
</section>

<section class="home-values" aria-labelledby="home-values-title">
 <div class="home-values-heading home-reveal"><span class="home-kicker">04 / THE IKKI APPROACH</span><h2 id="home-values-title">The details<br>make the <em>difference.</em></h2><p>A thoughtful approach to choosing, wearing and enjoying fragrance.</p></div>
 <article class="home-value home-reveal"><span class="home-value-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="M24 7c-6 8-12 14-12 22a12 12 0 0 0 24 0C36 21 30 15 24 7Z"/><path d="M18 30c1 4 3 6 7 7"/></svg></span><span class="home-value-number">01 / DISCOVERY</span><h3>Explore with curiosity</h3><p>Discover different fragrance profiles and find the notes that speak to you.</p></article>
 <article class="home-value home-reveal"><span class="home-value-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="M24 6 29 18 42 19 32 28 35 41 24 34 13 41 16 28 6 19 19 18Z"/></svg></span><span class="home-value-number">02 / EXPRESSION</span><h3>Make it personal</h3><p>Choose a scent that reflects your mood, your style and your own point of view.</p></article>
 <article class="home-value home-reveal"><span class="home-value-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="M24 6v36M6 24h36M11 11l26 26M37 11 11 37"/><circle cx="24" cy="24" r="15"/></svg></span><span class="home-value-number">03 / MOMENTS</span><h3>Wear the memory</h3><p>Let fragrance become part of the moments and memories that matter to you.</p></article>
</section>

<section class="home-manifesto" aria-label="IKKI brand statement"><span class="home-kicker">THE IKKI SIGNATURE</span><p>“Elegance is not simply seen. It is <em>felt.</em>”</p><span>IKKI · THE ART OF FRAGRANCE</span></section>

<section class="home-final-cta home-contact-cta" aria-labelledby="home-contact-title">
 <div class="home-instagram-heading home-reveal"><span class="home-kicker">YOUR NEXT SIGNATURE STARTS HERE</span><h2 id="home-contact-title">A fragrance that feels like <em>you.</em></h2><p>Explore the IKKI collection or speak with us for help choosing your fragrance.</p><div class="home-hero-actions home-final-actions"><a class="ikki-btn ikki-btn--primary" href="/shop.php">Shop the Collection <span aria-hidden="true">↗</span></a><a class="ikki-btn ikki-btn--secondary" href="https://wa.me/923094733789" target="_blank" rel="noopener noreferrer">Chat with IKKI <span aria-hidden="true">↗</span></a></div></div>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>
