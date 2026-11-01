<?php
require_once __DIR__.'/config/bootstrap.php';
$pageTitle='Ikki — Leave a little mystery.';
$description='Discover Ikki, a modern fragrance house in Pakistan. Find a signature scent that feels unmistakably yours.';
$products=[];
try{
 $products=db()->query("SELECT p.id,p.name,p.slug,p.short_description,MIN(v.price) price,(SELECT pi.image_path FROM product_images pi WHERE pi.product_id=p.id ORDER BY pi.is_primary DESC,pi.sort_order LIMIT 1) image_path FROM products p LEFT JOIN product_variants v ON v.product_id=p.id AND v.is_active=1 WHERE p.is_active=1 GROUP BY p.id ORDER BY p.is_featured DESC,p.id LIMIT 4")->fetchAll();
}catch(Throwable $ex){error_log('[Ikki home] '.$ex->getMessage());}
if(!$products){$products=[
 ['name'=>'Noor','slug'=>'noor','short_description'=>'Light, made lasting.','price'=>null,'image_path'=>'/assets/images/noor.svg'],
 ['name'=>'Sahra','slug'=>'sahra','short_description'=>'Warmth after sunset.','price'=>null,'image_path'=>'/assets/images/sahra.svg'],
 ['name'=>'Velvet Dusk','slug'=>'velvet-dusk','short_description'=>'The quiet after hours.','price'=>null,'image_path'=>'/assets/images/velvet-dusk.svg'],
 ['name'=>'Azure 04','slug'=>'azure-04','short_description'=>'A breath of open air.','price'=>null,'image_path'=>'/assets/images/azure-04.svg']
];}
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
 <div class="home-hero-visual" role="img" aria-label="Ikki Noor fragrance bottle">
  <div class="home-visual-glow" aria-hidden="true"></div>
  <div class="home-hero-orbit" aria-hidden="true"></div>
  <img class="home-hero-bottle" src="/assets/images/noor.svg" alt="Ikki Noor fragrance bottle">
  <span class="home-hero-edition" aria-hidden="true">IKKI · Nº 01<br><strong>NOOR</strong></span>
 </div>
 <a class="home-scroll-cue" href="#signature-scents" aria-label="Scroll to discover"><span></span></a>
</section>

<section class="home-collection" id="signature-scents" aria-labelledby="signature-title">
 <div class="home-section-heading home-reveal"><div><span class="home-kicker">01 / FIND YOUR SIGNATURE</span><h2 id="signature-title">Our signature<br><em>scents.</em></h2></div><a class="home-text-link" href="/shop.php">VIEW THE COLLECTION <span>↗</span></a></div>
 <div class="home-products">
 <?php foreach($products as $n=>$p): ?>
  <a class="home-product home-reveal" href="/product.php?slug=<?=e($p['slug'])?>">
   <div class="home-product-art"><span class="home-product-number">0<?=($n+1)?></span><img src="<?=e($p['image_path']?:'/assets/images/noor.svg')?>" alt="<?=e($p['name'])?> fragrance bottle" loading="lazy"><span class="home-product-open">DISCOVER THIS SCENT ↗</span></div>
   <div class="home-product-meta"><div><h3><?=e($p['name'])?></h3><p><?=e($p['short_description']??'An Ikki signature')?></p></div><strong><?=$p['price']!==null?money($p['price']):'DISCOVER'?></strong></div>
  </a>
 <?php endforeach; ?>
 </div>
 <p class="home-collection-note home-reveal">Distinct expressions. One unmistakable point of view.</p>
</section>

<section class="home-story-teaser" aria-labelledby="story-title">
 <img class="home-story-image" src="/assets/images/sahra.svg" alt="" loading="lazy">
 <div class="home-story-overlay"></div>
 <div class="home-story-content home-reveal"><span class="home-kicker">02 / THE WORLD OF IKKI</span><h2 id="story-title">A story told<br><em>without words.</em></h2><p>We believe fragrance is more than a finishing touch. It is memory, mood and quiet self-expression — composed into something entirely your own.</p><a class="home-button" href="/our-story.php">Discover our story <span>↗</span></a></div>
 <span class="home-story-caption">THE ART OF PRESENCE · IKKI</span>
</section>

<section class="home-values" aria-labelledby="values-title">
 <div class="home-values-heading home-reveal"><span class="home-kicker">03 / THE IKKI PROMISE</span><h2 id="values-title">The details<br>make the <em>difference.</em></h2><p>Every part of the experience deserves intention.</p></div>
 <article class="home-value home-reveal">
  <span class="home-value-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="M8 37 24 8l16 29H8Z"/><path d="M15 25h18M18 31h12M24 8v29"/></svg></span><span class="home-value-number">01 / THE MAKING</span><h3>Considered craftsmanship</h3><p>Thoughtful presentation, careful details and a point of view that feels unmistakably Ikki.</p>
 </article>
 <article class="home-value home-reveal">
  <span class="home-value-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="M24 40S8 30 8 18a8 8 0 0 1 16-2 8 8 0 0 1 16 2c0 12-16 22-16 22Z"/><path d="M24 16c-4 7-4 13 0 19"/></svg></span><span class="home-value-number">02 / THE FORMULA</span><h3>Quality-led ingredients</h3><p>We value balanced scent compositions and a considered selection of fragrance ingredients.</p>
 </article>
 <article class="home-value home-reveal">
  <span class="home-value-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="M24 5 39 11v11c0 10-6 17-15 21C15 39 9 32 9 22V11l15-6Z"/><path d="M17 24l5 5 10-11"/></svg></span><span class="home-value-number">03 / THE IMPRESSION</span><h3>Made to linger</h3><p>Distinctive fragrance experiences designed to leave a memorable impression.</p>
 </article>
</section>

<section class="home-newsletter" aria-labelledby="newsletter-title">
 <div class="home-newsletter-inner home-reveal"><span class="home-kicker">A LETTER FROM IKKI</span><h2 id="newsletter-title">A little note.<br><em>Now and then.</em></h2><p>Be the first to hear about new scents, thoughtful stories and notes from our world.</p>
  <form class="home-newsletter-form" action="/contact.php" method="get">
   <label class="visually-hidden" for="newsletter-email">Your email address</label><input id="newsletter-email" type="email" name="email" placeholder="Your email address" autocomplete="email" required><button type="submit" aria-label="Continue to contact Ikki">Join us <span>↗</span></button>
  </form>
  <small>By joining, you agree to hear from Ikki. You can opt out at any time.</small>
 </div>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>
