<?php
require_once __DIR__.'/config/bootstrap.php';
$pageTitle='The IKKI House — Our Story';
$description='Meet IKKI, a fragrance house centred on four signature expressions: Legendary Profile, Informal, Amane and Sasha.';
$liveSignatures=[];
try{
 $signatureQuery=db()->query("SELECT name,slug FROM products WHERE is_active=1 AND name IN ('Legendary Profile','Informal','Amane','Sasha')");
 foreach($signatureQuery->fetchAll() as $row)$liveSignatures[$row['name']]=$row['slug'];
}catch(Throwable $ex){error_log('[IKKI story] '.$ex->getMessage());}
$signatureCards=[
 ['name'=>'Legendary Profile','number'=>'01','image'=>'legendary-profile.svg'],
 ['name'=>'Informal','number'=>'02','image'=>'informal.svg'],
 ['name'=>'Amane','number'=>'03','image'=>'amane.svg'],
 ['name'=>'Sasha','number'=>'04','image'=>'sasha.svg']
];
require __DIR__.'/includes/header.php';
?>
<section class="page-banner page-banner-story" aria-labelledby="page-banner-title">
 <div class="page-banner-copy reveal">
  <nav class="page-banner-breadcrumb" aria-label="Breadcrumb"><a href="/">Home</a><span aria-hidden="true">›</span><span aria-current="page">Our Story</span></nav>
  <h1 id="page-banner-title">The House of <em>Four.</em></h1>
  <p>Four signature fragrances. One considered point of view.</p>
 </div>
</section>

<section class="story-narrative" aria-label="The IKKI story">
 <article class="story-narrative-block">
  <div class="story-narrative-visual slide-right"><img src="/assets/images/legendary-profile.svg" alt="Legendary Profile fragrance concept artwork" width="800" height="1000" loading="lazy" decoding="async"></div>
  <div class="story-narrative-copy fade-up" data-delay="200ms">
   <span class="story-narrative-eyebrow">THE IKKI POINT OF VIEW</span>
   <span class="story-narrative-rule" aria-hidden="true"></span>
   <h2>Fragrance is <em>personal.</em></h2>
   <p>A fragrance is more than a finishing touch. It can become part of a mood, a memory or the impression you leave behind. IKKI puts that personal connection at the centre of the fragrance experience.</p>
   <p>Rather than an endless catalogue, the house is presented through four named signatures. Each offers its own point of discovery, while all belong to one IKKI identity.</p>
  </div>
 </article>
 <article class="story-narrative-block is-reversed">
  <div class="story-narrative-visual slide-left"><img src="/assets/images/informal.svg" alt="Informal fragrance concept artwork" width="800" height="1000" loading="lazy" decoding="async"></div>
  <div class="story-narrative-copy fade-up" data-delay="200ms">
   <span class="story-narrative-eyebrow">THE EXPERIENCE</span>
   <span class="story-narrative-rule" aria-hidden="true"></span>
   <h2>Discover your <em>own expression.</em></h2>
   <p>There is no single way to experience fragrance. Some people begin with a particular note; others are drawn to a feeling, a setting or the character of a scent.</p>
   <p>Our approach is to make discovery feel clear and considered. Explore each signature, review the fragrance information provided, and take the time to find the one that feels right.</p>
  </div>
 </article>
 <article class="story-narrative-block">
  <div class="story-narrative-visual slide-right"><img src="/assets/images/amane.svg" alt="Amane fragrance concept artwork" width="800" height="1000" loading="lazy" decoding="async"></div>
  <div class="story-narrative-copy fade-up" data-delay="200ms">
   <span class="story-narrative-eyebrow">THE COLLECTION</span>
   <span class="story-narrative-rule" aria-hidden="true"></span>
   <h2>Four names. <em>One house.</em></h2>
   <p>Legendary Profile, Informal, Amane and Sasha form the IKKI signature collection. Four distinct names give the house its focused identity.</p>
   <p>Explore the collection at your own pace. If you would like help choosing or have a question about an order, our team is available through WhatsApp.</p>
  </div>
 </article>
</section>

<section class="ikki-house-signatures" aria-labelledby="house-signatures-title">
 <div class="ikki-house-signatures-heading">
  <span class="story-kicker">THE HOUSE OF FOUR</span>
  <h2 id="house-signatures-title">Four expressions.<br><em>One IKKI identity.</em></h2>
  <p>Our signature collection is intentionally focused: four names, each with its own place in the IKKI house.</p>
 </div>
 <div class="ikki-house-signatures-grid">
  <?php foreach($signatureCards as $signatureCard):
   $liveSlug=$liveSignatures[$signatureCard['name']]??null;
   $signatureHref=$liveSlug?'/product.php?slug='.rawurlencode($liveSlug):'/shop.php';
  ?>
  <a href="<?=e($signatureHref)?>" class="ikki-house-signature">
   <img src="/assets/images/<?=e($signatureCard['image'])?>" alt="<?=e($signatureCard['name'])?> IKKI signature concept artwork" width="600" height="750" loading="lazy" decoding="async">
   <span><?=e($signatureCard['number'])?> / THE SIGNATURE</span><h3><?=e($signatureCard['name'])?></h3><i><?= $liveSlug?'EXPLORE THIS SIGNATURE':'VIEW THE COLLECTION' ?> ↗</i>
  </a>
  <?php endforeach; ?>
 </div>
</section>

<section class="story-values" aria-labelledby="story-values-title">
 <div class="story-values-heading reveal"><span class="story-kicker">WHAT GUIDES US</span><h2 id="story-values-title">Our <em>values.</em></h2><p>The principles behind every choice we make.</p></div>
 <div class="story-value-grid">
  <article class="story-value-card reveal" style="--value-delay:0ms">
   <span class="story-value-symbol" aria-hidden="true"><svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"><path d="M24 5 28 17 40 12 31 23 43 29 29 29 25 43 20 29 6 34 16 23 7 15 20 18Z"/><circle cx="24" cy="24" r="5"/></svg></span>
   <h3>Composition</h3><p>Discover the character and layers that make each fragrance distinct.</p>
  </article>
  <article class="story-value-card reveal" style="--value-delay:100ms">
   <span class="story-value-symbol" aria-hidden="true"><svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"><path d="M24 42S7 32 7 19C7 11 17 6 24 15 31 6 41 11 41 19c0 13-17 23-17 23Z"/><path d="M24 15c-3 7-3 14 0 21M15 25c3-1 6-1 9 0m0 0c3-1 6-1 9 0"/></svg></span>
   <h3>Thoughtful Discovery</h3><p>Take your time exploring different fragrance identities.</p>
  </article>
  <article class="story-value-card reveal" style="--value-delay:200ms">
   <span class="story-value-symbol" aria-hidden="true"><svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"><path d="M24 5 39 13.5V30.5L24 39 9 30.5V13.5Z"/><path d="m16 23 5 5 11-12"/><path d="M24 39v5"/></svg></span>
   <h3>Personal Expression</h3><p>Choose a fragrance that feels aligned with your own style.</p>
  </article>
  <article class="story-value-card reveal" style="--value-delay:300ms">
   <span class="story-value-symbol" aria-hidden="true"><svg viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"><path d="M24 5 29 18 43 18 32 27 36 41 24 33 12 41 16 27 5 18 19 18Z"/><circle cx="24" cy="24" r="4"/></svg></span>
   <h3>Connection</h3><p>Let scent become part of the moments you make your own.</p>
  </article>
 </div>
</section>

<section class="story-mission" aria-labelledby="story-mission-title">
 <div class="story-mission-inner">
  <span class="story-mission-rule" aria-hidden="true"></span>
  <h2 id="story-mission-title" class="story-mission-quote" data-mission-quote aria-label="A fragrance can become part of the memories you carry.">“A fragrance can become part of the memories you carry.”</h2>
  <span class="story-mission-rule" aria-hidden="true"></span>
 </div>
</section>

<section class="story-cta">
 <div class="story-cta-copy reveal"><span class="story-kicker">YOUR NEXT CHAPTER</span><h2>Discover your<br><em>signature scent.</em></h2><p>Explore the collection and find the fragrance that feels like you.</p><a class="story-cta-button" href="/shop.php">Explore Our Collection <span>↗</span></a></div>
 <div class="story-cta-art reveal"><img src="/assets/images/sasha.svg" alt="Sasha fragrance concept artwork" width="800" height="1000" loading="lazy" decoding="async"><span>IKKI / THE SIGNATURE COLLECTION</span></div>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>
