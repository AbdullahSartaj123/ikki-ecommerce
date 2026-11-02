<?php
require_once __DIR__.'/config/bootstrap.php';
$pageTitle='IKKI — The Art of Fragrance';
$description='Discover IKKI, a considered fragrance house. Explore expressive scents and find a fragrance that feels unmistakably yours.';
$products=[];
try{
 $products=db()->query("SELECT p.id,p.name,p.slug,p.short_description,MIN(v.price) price,(SELECT pi.image_path FROM product_images pi WHERE pi.product_id=p.id ORDER BY pi.is_primary DESC,pi.sort_order LIMIT 1) image_path FROM products p LEFT JOIN product_variants v ON v.product_id=p.id AND v.is_active=1 WHERE p.is_active=1 GROUP BY p.id ORDER BY p.is_featured DESC,p.id LIMIT 4")->fetchAll();
}catch(Throwable $ex){error_log('[IKKI home] '.$ex->getMessage());}
$editorialImages=[
 'https://images.unsplash.com/photo-1587304946976-cbbbafce2133?auto=format&fit=crop&w=1000&q=85',
 'https://images.unsplash.com/photo-1673710672680-944563ff9cce?auto=format&fit=crop&w=900&q=85',
 'https://images.unsplash.com/photo-1718068838055-3a8311f0e8ba?auto=format&fit=crop&w=900&q=85',
 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=900&q=85'
];
$conceptProducts=[
 ['name'=>'NOOR','family'=>'AMBER · FLORAL','description'=>'A luminous, softly enveloping fragrance story.','image'=>'/assets/images/noor.svg'],
 ['name'=>'SAHRA','family'=>'WARM · SPICED','description'=>'A warm, sunlit composition with a graceful trail.','image'=>'/assets/images/sahra.svg'],
 ['name'=>'VELVET DUSK','family'=>'WOODY · AMBER','description'=>'A deeper, intimate mood for the evening.','image'=>'/assets/images/velvet-dusk.svg'],
 ['name'=>'AZURE 04','family'=>'FRESH · AROMATIC','description'=>'A crisp, modern expression with effortless clarity.','image'=>'/assets/images/azure-04.svg']
];
require __DIR__.'/includes/header.php';
?>
<div class="home-marquee" aria-label="IKKI fragrance philosophy"><div class="home-marquee-track"><span>ELEGANCE IN EVERY DETAIL <b aria-hidden="true">✳</b> FIND YOUR SIGNATURE <b aria-hidden="true">✳</b> THE ART OF FRAGRANCE <b aria-hidden="true">✳</b></span><span aria-hidden="true">ELEGANCE IN EVERY DETAIL <b>✳</b> FIND YOUR SIGNATURE <b>✳</b> THE ART OF FRAGRANCE <b>✳</b></span></div></div>

<section class="home-hero home-hero--editorial" aria-labelledby="home-title">
 <div class="home-hero-copy">
  <span class="home-hero-eyebrow">IKKI <i></i> THE ART OF FRAGRANCE</span>
  <p class="home-overline">A PERSONAL EXPRESSION, BEAUTIFULLY COMPOSED</p>
  <h1 id="home-title"><span class="hero-title-line hero-title-line--first">Wear your</span><span class="hero-title-line hero-title-line--second"><em>own story.</em></span></h1>
  <p class="home-hero-subtext">Fragrance is the quietest form of self-expression. Discover refined scent stories made to linger long after the moment has passed.</p>
  <div class="home-hero-actions">
   <a class="ikki-btn ikki-btn--primary" href="/shop.php">Explore the collection <span aria-hidden="true">↗</span></a>
   <a class="ikki-btn ikki-btn--secondary home-quiet-link" href="/our-story.php">The IKKI story <span aria-hidden="true">↗</span></a>
  </div>
  <div class="home-hero-foot" aria-label="IKKI fragrance collection"><span>THOUGHTFUL COMPOSITIONS</span><span>PERSONAL EXPRESSION</span><span>MADE TO BE REMEMBERED</span></div>
 </div>
 <div class="home-hero-visual" role="img" aria-label="Editorial photograph of a fine fragrance bottle">
  <div class="home-visual-glow" aria-hidden="true"></div>
  <div class="home-hero-orbit" aria-hidden="true"></div>
  <img class="home-hero-bottle" src="<?=e($editorialImages[0])?>" alt="Elegant glass perfume bottle with golden details" width="1000" height="1250" loading="eager" fetchpriority="high" decoding="async">
  <span class="home-hero-photo-caption"><small>THE SIGNATURE EDIT</small><strong>SCENT, <em>IN FORM.</em></strong></span>
  <span class="home-vertical-note" aria-hidden="true">A SCENT THAT STAYS WITH YOU</span>
 </div>
 <a class="home-scroll-cue" href="#home-philosophy" aria-label="Scroll to discover"><span></span> DISCOVER IKKI</a>
</section>

<section class="home-philosophy" id="home-philosophy" aria-labelledby="philosophy-title">
 <div class="home-philosophy-title home-circle-anchor"><span class="home-circle-motif home-circle-motif--80" aria-hidden="true"><i></i></span><div class="home-section-marker"><span class="home-section-number">01</span><span class="home-kicker">MORE THAN A FRAGRANCE</span></div><h2 id="philosophy-title">A signature<br>is <em>personal.</em></h2></div>
 <div class="home-philosophy-copy"><p>A fragrance is more than the notes that compose it. It is a feeling, a memory, a quiet expression of who you are. IKKI invites you to discover the scent that becomes part of your story.</p><a class="home-text-link" href="/our-story.php">THE IKKI STORY <span>↗</span></a></div>
</section>

<section class="home-collection" id="signature-scents" aria-labelledby="signature-title">
 <div class="home-section-heading home-reveal"><div class="home-circle-anchor"><span class="home-circle-motif home-circle-motif--60" aria-hidden="true"><i></i></span><div class="home-section-marker"><span class="home-section-number">02</span><span class="home-kicker">FIND YOUR SIGNATURE</span></div><h2 id="signature-title">The signature<br><em>collection.</em></h2></div><a class="home-text-link" href="/shop.php">VIEW THE COLLECTION <span>↗</span></a></div>
 <div class="home-products home-products--editorial">
 <?php if($products): foreach($products as $n=>$p):
   $image=$p['image_path']??'';
   if($image==='' || preg_match('/\.svg(?:$|\?)/i',$image)) $image=$editorialImages[$n%count($editorialImages)];
 ?>
  <a class="home-product" href="/product.php?slug=<?=e($p['slug'])?>">
   <div class="home-product-art"><span class="home-product-number">0<?=($n+1)?></span><img src="<?=e($image)?>" alt="<?=e($p['name'])?> fragrance editorial photograph" width="800" height="1000" loading="lazy" decoding="async"><span class="home-product-open">DISCOVER THIS SCENT ↗</span></div>
   <div class="home-product-meta"><div><h3><?=e($p['name'])?></h3><p><?=e($p['short_description']??'An IKKI fragrance')?></p></div><strong><?=$p['price']!==null?money($p['price']):'DISCOVER'?></strong></div>
  </a>
 <?php endforeach; else: foreach($conceptProducts as $n=>$p): ?>
  <a class="home-product home-reveal home-product--concept" href="/shop.php">
   <div class="home-product-art"><span class="home-product-number">0<?=($n+1)?></span><img src="<?=e($p['image'])?>" alt="<?=e($p['name'])?> IKKI fragrance concept artwork" width="800" height="1000" loading="lazy" decoding="async"><span class="home-product-open">EXPLORE THE COLLECTION ↗</span></div>
   <div class="home-product-meta"><div><h3><?=e($p['name'])?></h3><p><?=e($p['family'])?></p></div><strong>COMING SOON</strong></div>
   <p class="home-concept-description"><?=e($p['description'])?></p>
  </a>
 <?php endforeach; endif; ?>
 </div>
 <p class="home-collection-note home-reveal"><?= $products?'Distinct expressions. One unmistakable point of view.':'Concept imagery for the signature collection. Final product presentation will follow confirmed IKKI packaging.' ?></p>
</section>

<section class="home-feature" aria-labelledby="home-feature-title">
 <div class="home-feature-art"><img src="<?=e($editorialImages[1])?>" alt="Perfume styled in a soft, warm editorial setting" width="1000" height="1100" loading="lazy" decoding="async"><span>IKKI · A STUDY IN SCENT</span></div>
 <div class="home-feature-copy home-reveal home-circle-anchor"><span class="home-circle-motif home-circle-motif--100" aria-hidden="true"><i></i></span><div class="home-section-marker"><span class="home-section-number">03</span><span class="home-kicker">THE EXPERIENCE</span></div><h2 id="home-feature-title">Find a scent<br>that feels like <em>you.</em></h2><p>From the first impression to the final lingering note, discover a fragrance that complements your individuality and makes every moment feel your own.</p><a class="home-outline-button" href="/shop.php">Explore the fragrances <span>↗</span></a></div>
</section>

<section class="home-values" aria-labelledby="home-values-title">
 <div class="home-values-heading home-reveal home-circle-anchor"><span class="home-circle-motif home-circle-motif--80" aria-hidden="true"><i></i></span><div class="home-section-marker"><span class="home-section-number">04</span><span class="home-kicker">THE IKKI APPROACH</span></div><h2 id="home-values-title">The details<br>make the <em>difference.</em></h2><p>A thoughtful approach to choosing, wearing and enjoying fragrance.</p></div>
 <article class="home-value home-reveal"><span class="home-value-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="M24 7c-6 8-12 14-12 22a12 12 0 0 0 24 0C36 21 30 15 24 7Z"/><path d="M18 30c1 4 3 6 7 7"/></svg></span><span class="home-value-number">01 / DISCOVERY</span><h3>Explore with curiosity</h3><p>Discover different fragrance profiles and find the notes that speak to you.</p></article>
 <article class="home-value home-reveal"><span class="home-value-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="M24 6 29 18 42 19 32 28 35 41 24 34 13 41 16 28 6 19 19 18Z"/></svg></span><span class="home-value-number">02 / EXPRESSION</span><h3>Make it personal</h3><p>Choose a scent that reflects your mood, your style and your own point of view.</p></article>
 <article class="home-value home-reveal"><span class="home-value-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="M24 6v36M6 24h36M11 11l26 26M37 11 11 37"/><circle cx="24" cy="24" r="15"/></svg></span><span class="home-value-number">03 / MOMENTS</span><h3>Wear the memory</h3><p>Let fragrance become part of the moments and memories that matter to you.</p></article>
</section>

<section class="home-manifesto home-circle-anchor" aria-label="IKKI brand statement"><span class="home-circle-motif home-circle-motif--100" aria-hidden="true"><i></i></span><div class="home-section-marker"><span class="home-section-number">05</span><span class="home-kicker">THE IKKI SIGNATURE</span></div><p>“Elegance is not simply seen. It is <em>felt.</em>”</p><span>IKKI · THE ART OF FRAGRANCE</span></section>

<section class="home-final-cta home-contact-cta" aria-labelledby="home-contact-title">
 <div class="home-instagram-heading home-reveal home-circle-anchor"><span class="home-circle-motif home-circle-motif--60" aria-hidden="true"><i></i></span><div class="home-section-marker"><span class="home-section-number">06</span><span class="home-kicker">YOUR NEXT SIGNATURE STARTS HERE</span></div><h2 id="home-contact-title">A fragrance that feels like <em>you.</em></h2><p>Explore the IKKI collection or speak with us for help choosing your fragrance.</p><div class="home-hero-actions home-final-actions"><a class="ikki-btn ikki-btn--primary" href="/shop.php">Shop the Collection <span aria-hidden="true">↗</span></a><a class="ikki-btn ikki-btn--secondary" href="https://wa.me/923094733789" target="_blank" rel="noopener noreferrer">Chat with IKKI <span aria-hidden="true">↗</span></a></div></div>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>
