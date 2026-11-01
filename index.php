<?php
require_once __DIR__.'/config/bootstrap.php';
$pageTitle='Ikki — Leave a little mystery.';
$description='Discover Ikki, a modern fragrance house in Pakistan. Find a signature scent that feels unmistakably yours.';
$products=[];
try{$products=db()->query("SELECT p.id,p.name,p.slug,p.short_description,MIN(v.price) price,(SELECT pi.image_path FROM product_images pi WHERE pi.product_id=p.id ORDER BY pi.is_primary DESC,pi.sort_order LIMIT 1) image_path FROM products p LEFT JOIN product_variants v ON v.product_id=p.id AND v.is_active=1 WHERE p.is_active=1 GROUP BY p.id ORDER BY p.is_featured DESC,p.id LIMIT 4")->fetchAll();}catch(Throwable $ex){error_log('[Ikki home] '.$ex->getMessage());}
if(!$products){$products=[
 ['name'=>'Noor','slug'=>'noor','short_description'=>'Light, made lasting.','price'=>null,'image_path'=>'/assets/images/noor.svg'],
 ['name'=>'Sahra','slug'=>'sahra','short_description'=>'Warmth after sunset.','price'=>null,'image_path'=>'/assets/images/sahra.svg'],
 ['name'=>'Velvet Dusk','slug'=>'velvet-dusk','short_description'=>'The quiet after hours.','price'=>null,'image_path'=>'/assets/images/velvet-dusk.svg'],
 ['name'=>'Azure 04','slug'=>'azure-04','short_description'=>'A breath of open air.','price'=>null,'image_path'=>'/assets/images/azure-04.svg']
];}
require __DIR__.'/includes/header.php';
?>
<section class="home-hero">
 <div class="home-hero-backdrop" aria-hidden="true"></div>
 <div class="home-hero-copy">
  <span class="home-kicker"><i></i> AN INDEPENDENT FRAGRANCE HOUSE · PAKISTAN</span>
  <p class="home-overline">THE ART OF BEING REMEMBERED</p>
  <h1>Leave a little<br><em>mystery.</em></h1>
  <p class="home-lead">Some things are better felt than explained. Discover a fragrance that says everything, without a word.</p>
  <div class="home-hero-actions"><a class="home-button" href="/shop.php">Explore the collection <span>↗</span></a><a class="home-quiet-link" href="#our-world">Discover our world <span>↓</span></a></div>
  <div class="home-hero-foot"><span>01 / THE SIGNATURE COLLECTION</span><span>SCENT IS A LANGUAGE</span></div>
 </div>
 <div class="home-hero-visual" aria-label="Ikki Noor fragrance bottle">
  <div class="home-visual-glow"></div><div class="home-ring home-ring-a"></div><div class="home-ring home-ring-b"></div>
  <div class="home-bottle-shadow"></div>
  <img class="home-hero-bottle" src="/assets/images/noor.svg" alt="Ikki Noor fragrance bottle concept artwork">
  <div class="home-visual-stamp"><span>IKKI / Nº 01</span><strong>NOOR</strong><small>LIGHT, MADE LASTING</small></div>
  <span class="home-vertical-note">A STUDY IN SCENT · VOL. 01</span>
 </div>
 <a class="home-scroll-cue" href="#our-world"><span></span> SCROLL TO DISCOVER</a>
</section>
<div class="home-marquee" aria-label="Ikki fragrance philosophy"><div>FRAGRANCE, COMPOSED <b>✳</b> A SIGNATURE IS PERSONAL <b>✳</b> THE ART OF PRESENCE <b>✳</b> FRAGRANCE, COMPOSED <b>✳</b> A SIGNATURE IS PERSONAL <b>✳</b></div></div>
<section class="home-philosophy" id="our-world">
 <div class="home-section-index">01 / OUR WORLD</div>
 <div class="home-philosophy-title"><span class="home-kicker">MORE THAN A FINISHING TOUCH</span><h2>A scent can say<br>what words <em>never could.</em></h2></div>
 <div class="home-philosophy-copy"><p>Fragrance is a feeling, a memory in the making, a quiet signature that arrives before you and lingers after. Ikki is for the moments that become yours — and the details that make them unforgettable.</p><a class="home-text-link" href="/contact.php">A little more about Ikki <span>↗</span></a></div>
</section>
<section class="home-collection" id="collection">
 <div class="home-section-heading"><div><span class="home-kicker">02 / FIND YOUR SIGNATURE</span><h2>The signature<br><em>collection.</em></h2></div><a class="home-text-link" href="/shop.php">VIEW ALL FRAGRANCES <span>↗</span></a></div>
 <div class="home-products"><?php foreach($products as $n=>$p):?><a class="home-product home-reveal" href="/product.php?slug=<?=e($p['slug'])?>"><div class="home-product-art"><span class="home-product-number">0<?=($n+1)?></span><img src="<?=e($p['image_path']?:'/assets/images/noor.svg')?>" alt="<?=e($p['name'])?> fragrance bottle" loading="lazy"><span class="home-product-open">DISCOVER THIS SCENT ↗</span></div><div class="home-product-meta"><div><h3><?=e($p['name'])?></h3><p><?=e($p['short_description']??'An Ikki signature')?></p></div><strong><?=$p['price']!==null?money($p['price']):'DISCOVER'?></strong></div></a><?php endforeach;?></div>
 <p class="home-collection-note">Four individual expressions. One unmistakable point of view.</p>
</section>
<section class="home-feature">
 <div class="home-feature-art"><img src="/assets/images/sahra.svg" alt="Ikki Sahra warm amber fragrance concept" loading="lazy"><span>THE MOOD OF SAHRA / 02</span></div>
 <div class="home-feature-copy"><span class="home-kicker">03 / A MOMENT, BOTTLED</span><h2>Golden hour<br>has a <em>feeling.</em></h2><p>Warm. Intimate. Unhurried. Sahra is imagined in the glow of the last light — a fragrance story with warmth at its heart.</p><a class="home-outline-button" href="/product.php?slug=sahra">Meet Sahra <span>↗</span></a></div>
</section>
<section class="home-values">
 <div class="home-values-heading"><span class="home-kicker">04 / THE IKKI PROMISE</span><h2>Considered in every<br><em>detail.</em></h2></div>
 <div class="home-value"><span>01</span><div><h3>Find your feeling</h3><p>Explore scent stories and choose the character that feels like you.</p></div></div>
 <div class="home-value"><span>02</span><div><h3>Made for your moments</h3><p>A considered fragrance experience, from first impression to final note.</p></div></div>
 <div class="home-value"><span>03</span><div><h3>Here for you</h3><p>Our team is available for fragrance and order enquiries across Pakistan.</p></div></div>
</section>
<section class="home-manifesto"><span class="home-kicker">THE IKKI POINT OF VIEW</span><p>The most memorable thing about you might be the thing <em>no one can see.</em></p><span>— THE IKKI PHILOSOPHY</span></section>
<section class="home-final-cta"><span class="home-kicker">YOUR NEXT SIGNATURE STARTS HERE</span><h2>Make it <em>memorable.</em></h2><a class="home-button" href="/shop.php">Discover Ikki <span>↗</span></a></section>
<?php require __DIR__.'/includes/footer.php';?>