<?php
declare(strict_types=1);
$products = [
 ['name'=>'Legendary Profile','number'=>'01','notes'=>'Amber · Woods · Spice','mood'=>'THE SIGNATURE','tone'=>'amber','description'=>'A confident, lasting signature with warmth, depth and unmistakable presence.','image'=>'assets/images/legendary-profile.svg'],
 ['name'=>'Informal','number'=>'02','notes'=>'Citrus · Aromatic · Musk','mood'=>'THE EVERYDAY ICON','tone'=>'sage','description'=>'Effortlessly composed. Fresh character for the moments that become memories.','image'=>'assets/images/informal.svg'],
 ['name'=>'Amane','number'=>'03','notes'=>'Floral · Soft woods · Musk','mood'=>'THE QUIET STATEMENT','tone'=>'rose','description'=>'An intimate balance of softness and strength, made to linger beautifully.','image'=>'assets/images/amane.svg'],
 ['name'=>'Sasha','number'=>'04','notes'=>'Fresh · Spiced · Modern woods','mood'=>'THE BOLD ORIGINAL','tone'=>'smoke','description'=>'Modern, expressive and magnetic — a scent that leaves its own impression.','image'=>'assets/images/sasha.svg'],
];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#f5efe4">
  <title>IKKI — Fragrance, with a point of view</title>
  <meta name="description" content="Discover IKKI. Four distinctive fragrance signatures, composed for presence and made to be remembered.">
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500;1,600&display=swap" rel="stylesheet">
  <link rel="icon" href="assets/images/favicon.svg" type="image/svg+xml"><link rel="stylesheet" href="assets/css/site.css">
</head>
<body>
<div class="announcement"><span>COMPOSED TO BE REMEMBERED</span><span class="announcement-center">COMPLIMENTARY DELIVERY ON QUALIFYING ORDERS</span><span>MADE FOR YOUR MOMENT</span></div>
<header class="site-header" id="top">
  <a class="wordmark" href="#top" aria-label="IKKI home">IKKI<span>®</span></a>
  <button class="menu-toggle" aria-label="Open navigation" aria-expanded="false"><i></i><i></i></button>
  <nav class="main-nav" aria-label="Main navigation"><a href="#collection">THE COLLECTION</a><a href="#philosophy">OUR PHILOSOPHY</a><a href="#craft">THE IKKI STANDARD</a></nav>
  <a class="header-cta" href="#collection">DISCOVER THE SCENTS <span>↗</span></a>
</header>
<main>
<section class="hero">
  <div class="hero-copy">
    <p class="eyebrow"><span class="eyebrow-line"></span> A NEW LANGUAGE OF FRAGRANCE</p>
    <h1>Leave a little<br><em>of yourself</em><br>in the air.</h1>
    <p class="hero-intro">Fragrance is more than a finishing touch. It is a feeling, a memory, a signature. Meet four distinct expressions of IKKI.</p>
    <div class="hero-actions"><a class="button button-dark" href="#collection">EXPLORE THE COLLECTION <span>↗</span></a><a class="text-link" href="#philosophy">THE WORLD OF IKKI <span>↓</span></a></div>
    <div class="hero-index"><span>01 — 04</span><span>FOUR SIGNATURES. ONE POINT OF VIEW.</span></div>
  </div>
  <div class="hero-art" aria-label="IKKI fragrance still life">
    <div class="hero-orbit orbit-one"></div><div class="hero-orbit orbit-two"></div>
    <div class="hero-stamp">SCENT<br>IS A<br>STATEMENT</div>
    <img class="hero-bottle" src="assets/images/legendary-profile.svg" alt="IKKI Legendary Profile fragrance bottle" fetchpriority="high">
    <div class="hero-art-caption"><span>IKKI / NO. 01</span><span>LEGENDARY PROFILE</span></div>
    <div class="hero-side-note">EAU DE PARFUM · AN EXPRESSION OF YOU</div>
  </div>
  <div class="hero-bottom"><span>SCROLL TO DISCOVER</span><span class="scroll-line"></span><span>PAKISTAN · EST. WITH INTENTION</span></div>
</section>
<section class="intro-band" id="philosophy"><p class="eyebrow">THE IKKI POINT OF VIEW</p><h2>Not made to fit in.<br><em>Made to stay with you.</em></h2><p>We believe the best fragrance does not announce itself loudly. It becomes part of how you are remembered — personal, distinctive, entirely your own.</p></section>
<section class="collection section-pad" id="collection">
 <div class="section-heading"><div><p class="eyebrow">FOUR WAYS TO BE REMEMBERED</p><h2>The signature<br><em>collection.</em></h2></div><p>Four carefully considered characters.<br>Find the one that feels like you.</p></div>
 <div class="product-grid">
 <?php foreach ($products as $product): ?>
  <article class="product-card">
   <a class="product-visual tone-<?= htmlspecialchars($product['tone']) ?>" href="mailto:hello@ikki.pk?subject=Enquiry%20about%20<?= rawurlencode($product['name']) ?>" aria-label="Enquire about <?= htmlspecialchars($product['name']) ?>">
    <span class="product-number"><?= htmlspecialchars($product['number']) ?> / 04</span><img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?> perfume bottle" loading="lazy" width="420" height="520"><span class="product-discover">DISCOVER SCENT <b>↗</b></span>
   </a>
   <div class="product-meta"><div><p class="product-mood"><?= htmlspecialchars($product['mood']) ?></p><h3><?= htmlspecialchars($product['name']) ?></h3></div><span class="product-arrow">↗</span></div>
   <p class="product-notes"><?= htmlspecialchars($product['notes']) ?></p><p class="product-description"><?= htmlspecialchars($product['description']) ?></p>
  </article>
 <?php endforeach; ?>
 </div>
 <div class="collection-foot"><span>01—04 / THE IKKI SIGNATURES</span><span>YOUR SCENT. YOUR STORY.</span></div>
</section>
<section class="manifesto" id="craft"><div class="manifesto-image"><div class="manifesto-ring"></div><div class="manifesto-mark">I<span>.</span></div><p>THE ART OF<br>FIRST IMPRESSIONS</p></div><div class="manifesto-copy"><p class="eyebrow">THE IKKI STANDARD</p><h2>Presence is<br>an <em>art form.</em></h2><p>Every detail matters. The first impression. The quiet confidence. The memory that follows. IKKI is built around the belief that fragrance should feel considered, individual and unmistakably yours.</p><a class="text-link" href="#collection">FIND YOUR SIGNATURE <span>↗</span></a></div></section>
<section class="closing"><p class="eyebrow">FOUR SIGNATURES. ENDLESS IMPRESSIONS.</p><h2>Make it<br><em>memorable.</em></h2><a class="button button-light" href="#collection">MEET THE COLLECTION <span>↗</span></a></section>
</main>
<footer class="site-footer"><a class="footer-logo" href="#top">IKKI<span>®</span></a><p>Iconic Kyenotes. Kinetic Impression.</p><div class="footer-links"><a href="#collection">COLLECTION</a><a href="#philosophy">OUR PHILOSOPHY</a><a href="mailto:hello@ikki.pk">CONTACT</a></div><small>© <?= date('Y') ?> IKKI. ALL RIGHTS RESERVED.</small></footer>
<script src="assets/js/site.js" defer></script>
</body></html>