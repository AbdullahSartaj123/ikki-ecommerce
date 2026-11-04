<?php
require_once __DIR__.'/config/bootstrap.php';
$pageTitle='IKKI — The Art of Fragrance';
$description='IKKI is a fragrance house defined by four signature expressions. Discover the identity, stories and artistry behind each scent.';
$products=[];
try{
 $products=db()->query("SELECT p.id,p.name,p.slug,p.short_description,MIN(v.price) price,(SELECT pi.image_path FROM product_images pi WHERE pi.product_id=p.id ORDER BY pi.is_primary DESC,pi.sort_order LIMIT 1) image_path FROM products p LEFT JOIN product_variants v ON v.product_id=p.id AND v.is_active=1 WHERE p.is_active=1 AND p.name IN ('Legendary Profile','Informal','Amane','Sasha') GROUP BY p.id ORDER BY FIELD(p.name,'Legendary Profile','Informal','Amane','Sasha') LIMIT 4")->fetchAll();
}catch(Throwable $ex){error_log('[IKKI home] '.$ex->getMessage());}
$editorialImages=[
 '/assets/images/legendary-profile.svg',
 '/assets/images/informal.svg',
 '/assets/images/amane.svg',
 '/assets/images/sasha.svg'
];
$conceptProducts=[
 ['name'=>'Legendary Profile','family'=>'IKKI SIGNATURE FRAGRANCE','description'=>'A confident signature with a distinctive, memorable presence.','image'=>'/assets/images/legendary-profile.svg','slug'=>'legendary-profile'],
 ['name'=>'Informal','family'=>'IKKI SIGNATURE FRAGRANCE','description'=>'An effortless everyday expression, relaxed yet refined.','image'=>'/assets/images/informal.svg','slug'=>'informal'],
 ['name'=>'Amane','family'=>'IKKI SIGNATURE FRAGRANCE','description'=>'A fresh, contemporary character with a smooth finish.','image'=>'/assets/images/amane.svg','slug'=>'amane'],
 ['name'=>'Sasha','family'=>'IKKI SIGNATURE FRAGRANCE','description'=>'A poised, expressive fragrance with a lasting impression.','image'=>'/assets/images/sasha.svg','slug'=>'sasha']
];
$liveBySlug=[];
foreach($products as $liveProduct){$liveBySlug[$liveProduct['slug']]=$liveProduct;}
$products=[];
foreach($conceptProducts as $concept){
 $live=$liveBySlug[$concept['slug']]??null;
 $products[]=$live?array_merge($concept,$live,['is_live'=>true]):array_merge($concept,['is_live'=>false,'short_description'=>$concept['description']]);
}

require __DIR__.'/includes/header.php';
?>
<div class="home-marquee" aria-label="IKKI fragrance philosophy"><div class="home-marquee-track"><span>ELEGANCE IN EVERY DETAIL <b aria-hidden="true">✳</b> FIND YOUR SIGNATURE <b aria-hidden="true">✳</b> THE ART OF FRAGRANCE <b aria-hidden="true">✳</b></span><span aria-hidden="true">ELEGANCE IN EVERY DETAIL <b>✳</b> FIND YOUR SIGNATURE <b>✳</b> THE ART OF FRAGRANCE <b>✳</b></span></div></div>

<section class="home-hero home-hero--editorial" aria-labelledby="home-title">
 <div class="home-hero-copy">
  <span class="home-hero-eyebrow">IKKI <i></i> THE HOUSE OF FOUR</span>
  <p class="home-overline">FOUR SIGNATURES. FOUR DISTINCT POINTS OF VIEW.</p>
  <h1 id="home-title"><span class="hero-title-line hero-title-line--first">The House</span><span class="hero-title-line hero-title-line--second"><em>of Four.</em></span></h1>
  <p class="home-hero-subtext">Meet four individual fragrance identities, each with its own character. Discover the signature that feels unmistakably yours.</p>
  <div class="home-hero-actions">
   <a class="ikki-btn ikki-btn--primary" href="#signature-scents">Discover the four <span aria-hidden="true">↗</span></a>
   <a class="ikki-btn ikki-btn--secondary home-quiet-link" href="/our-story.php">Our philosophy <span aria-hidden="true">↗</span></a>
  </div>
  <div class="home-hero-foot" aria-label="IKKI fragrance collection"><span>FOUR DISTINCT IDENTITIES</span><span>ONE CREATIVE HOUSE</span><span>THE IKKI SIGNATURE</span></div>
 </div>
 <div class="home-hero-visual">
  <div class="home-visual-glow" aria-hidden="true"></div>
  <div class="home-hero-orbit" aria-hidden="true"></div>
  <img class="home-hero-bottle" src="/assets/images/ikki-hero.svg" alt="Illustrated IKKI fragrance bottle in a champagne-toned campaign composition" width="1000" height="1200" loading="eager" fetchpriority="high" decoding="async">
  <span class="home-hero-photo-caption"><small>THE SIGNATURE EDIT</small><strong>SCENT, <em>IN FORM.</em></strong></span>
  <span class="home-vertical-note" aria-hidden="true">A SCENT THAT STAYS WITH YOU</span>
 </div>
 <a class="home-scroll-cue" href="#home-philosophy" aria-label="Scroll to discover"><span></span> DISCOVER IKKI</a>
</section>


<section class="ikki-home-edition-index" aria-label="Explore the four IKKI signatures">
 <div class="ikki-home-edition-intro"><span>THE IKKI SIGNATURE COLLECTION</span><i aria-hidden="true"></i></div>
 <nav class="ikki-home-edition-links">
  <a href="#signature-legendary-profile"><small>01</small><span>Legendary Profile</span><i aria-hidden="true">↗</i></a>
  <a href="#signature-informal"><small>02</small><span>Informal</span><i aria-hidden="true">↗</i></a>
  <a href="#signature-amane"><small>03</small><span>Amane</span><i aria-hidden="true">↗</i></a>
  <a href="#signature-sasha"><small>04</small><span>Sasha</span><i aria-hidden="true">↗</i></a>
 </nav>
</section>

<section class="home-philosophy" id="home-philosophy" aria-labelledby="philosophy-title">
 <div class="home-philosophy-title home-circle-anchor"><span class="home-circle-motif home-circle-motif--80" aria-hidden="true"><i></i></span><div class="home-section-marker"><span class="home-section-number">01</span><span class="home-kicker">THE HOUSE OF IKKI</span></div><h2 id="philosophy-title">A signature<br>is <em>personal.</em></h2></div>
 <div class="home-philosophy-copy"><p>IKKI is a fragrance house built around four distinct expressions. Each name carries its own character; together, they form the identity of the house. We believe a fragrance should be recognised not only by how it smells, but by how it makes you feel.</p><a class="home-text-link" href="/our-story.php">THE IKKI STORY <span>↗</span></a></div>
</section>

<section class="home-collection" id="signature-scents" aria-labelledby="signature-title">
 <div class="home-section-heading home-reveal"><div class="home-circle-anchor"><span class="home-circle-motif home-circle-motif--60" aria-hidden="true"><i></i></span><div class="home-section-marker"><span class="home-section-number">02</span><span class="home-kicker">FOUR EXPRESSIONS · ONE HOUSE</span></div><h2 id="signature-title">Four names.<br><em>One identity.</em></h2></div><a class="home-text-link" href="/shop.php">VIEW THE COLLECTION <span>↗</span></a></div>
 <div class="home-products home-products--editorial">
 <?php foreach($products as $n=>$p):
   $image=trim((string)($p['image_path']??''));
   if($image===''){
    $fallbackBySlug=['legendary-profile'=>'/assets/images/legendary-profile.svg','informal'=>'/assets/images/informal.svg','amane'=>'/assets/images/amane.svg','sasha'=>'/assets/images/sasha.svg'];
    $candidate=$fallbackBySlug[$p['slug']]??'';
    $image=($candidate!==''&&is_file(__DIR__.$candidate))?$candidate:'/assets/images/ikki-hero.svg';
   }
   $displayPrice=($p['is_live']&&isset($p['price'])&&is_numeric($p['price'])&&(float)$p['price']>0)?money($p['price']):'';
 ?>
  <a id="signature-<?=e($p['slug'])?>" class="home-product" data-signature="<?=e($p['slug'])?>" href="<?=$p['is_live']?'/product.php?slug='.rawurlencode($p['slug']):'/shop.php'?>" aria-label="<?=e($p['name'])?> — <?=$p['is_live']?'view fragrance':'explore the collection'?>">
   <div class="home-product-art">
    <span class="home-product-number">0<?=($n+1)?></span>
    <span class="home-product-edition">THE IKKI SIGNATURE</span>
    <img src="<?=e($image)?>" alt="<?=e($p['name'])?> fragrance campaign illustration" width="800" height="1000" loading="lazy" decoding="async">
    <span class="home-product-open">DISCOVER THIS SIGNATURE <i aria-hidden="true">↗</i></span>
   </div>
   <div class="home-product-meta">
    <div><span class="home-product-index">0<?=($n+1)?> / FOUR EXPRESSIONS</span><h3><?=e($p['name'])?></h3><p><?=e($p['short_description']??'An IKKI fragrance')?></p></div>
    <strong><span><?=$displayPrice!==''?'FROM '.e($displayPrice):($p['is_live']?'EXPLORE FRAGRANCE':'EXPLORE COLLECTION')?></span> <i aria-hidden="true">↗</i></strong>
   </div>
  </a>
 <?php endforeach; /* the four house signatures are always rendered above */ ?>

 </div>
 <p class="home-collection-note home-reveal">Four distinct expressions. One unmistakable point of view.</p>
</section>

<section class="home-feature" aria-labelledby="home-feature-title">
 <div class="home-feature-art"><img src="<?=e($editorialImages[1])?>" alt="IKKI Informal signature fragrance campaign illustration" width="1000" height="1100" loading="lazy" decoding="async"><span class="ikki-image-caption">IKKI · A STUDY IN SCENT</span></div>
 <div class="home-feature-copy home-reveal home-circle-anchor"><span class="home-circle-motif home-circle-motif--100" aria-hidden="true"><i></i></span><div class="home-section-marker"><span class="home-section-number">03</span><span class="home-kicker">A HOUSE WITH A POINT OF VIEW</span></div><h2 id="home-feature-title">Four signatures.<br><em>Infinite expression.</em></h2><p>Legendary Profile. Informal. Amane. Sasha. Four individual identities brought together under one name: IKKI.</p><a class="home-outline-button" href="/shop.php">Meet the four signatures <span>↗</span></a></div>
</section>

<section class="home-values" aria-labelledby="home-values-title">
 <div class="home-values-heading home-reveal home-circle-anchor"><span class="home-circle-motif home-circle-motif--80" aria-hidden="true"><i></i></span><div class="home-section-marker"><span class="home-section-number">04</span><span class="home-kicker">WHAT DEFINES THE HOUSE</span></div><h2 id="home-values-title">An identity<br>in every <em>detail.</em></h2><p>A considered point of view, expressed through four signature fragrances.</p></div>
 <article class="home-value home-reveal"><span class="home-value-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="M24 7c-6 8-12 14-12 22a12 12 0 0 0 24 0C36 21 30 15 24 7Z"/><path d="M18 30c1 4 3 6 7 7"/></svg></span><span class="home-value-number">01 / DISCOVERY</span><h3>Explore with curiosity</h3><p>Discover different fragrance profiles and find the notes that speak to you.</p></article>
 <article class="home-value home-reveal"><span class="home-value-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="M24 6 29 18 42 19 32 28 35 41 24 34 13 41 16 28 6 19 19 18Z"/></svg></span><span class="home-value-number">02 / EXPRESSION</span><h3>Make it personal</h3><p>Choose a scent that reflects your mood, your style and your own point of view.</p></article>
 <article class="home-value home-reveal"><span class="home-value-icon" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="M24 6v36M6 24h36M11 11l26 26M37 11 11 37"/><circle cx="24" cy="24" r="15"/></svg></span><span class="home-value-number">03 / MOMENTS</span><h3>Wear the memory</h3><p>Let fragrance become part of the moments and memories that matter to you.</p></article>
</section>

<section class="home-manifesto home-circle-anchor" aria-label="IKKI brand statement"><span class="home-circle-motif home-circle-motif--100" aria-hidden="true"><i></i></span><div class="home-section-marker"><span class="home-section-number">05</span><span class="home-kicker">THE IKKI SIGNATURE</span></div><p>“Elegance is not simply seen. It is <em>felt.</em>”</p><span>IKKI · THE ART OF FRAGRANCE</span></section>

<section class="home-final-cta home-contact-cta" aria-labelledby="home-contact-title">
 <div class="home-instagram-heading home-reveal home-circle-anchor"><span class="home-circle-motif home-circle-motif--60" aria-hidden="true"><i></i></span><div class="home-section-marker"><span class="home-section-number">06</span><span class="home-kicker">WELCOME TO THE WORLD OF IKKI</span></div><h2 id="home-contact-title">A fragrance that feels like <em>you.</em></h2><p>Get to know the four signature fragrances and the story behind the house.</p><div class="home-hero-actions home-final-actions"><a class="ikki-btn ikki-btn--primary" href="/shop.php">Discover the four signatures <span aria-hidden="true">↗</span></a><a class="ikki-btn ikki-btn--secondary" href="https://wa.me/923094733789" target="_blank" rel="noopener noreferrer">Chat with IKKI <span aria-hidden="true">↗</span></a></div></div>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>
