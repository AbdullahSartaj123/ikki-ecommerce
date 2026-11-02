<?php
$pageTitle=$pageTitle??'IKKI — Fragrance, composed.';
$description=$description??'Discover IKKI: a modern fragrance house creating expressive scent stories in Pakistan.';
$currentPage=basename($_SERVER['SCRIPT_NAME']??'');
$isHomePage=($currentPage==='index.php');
$navItems=[
 ['label'=>'Home','href'=>'/','match'=>['index.php']],
 ['label'=>'Collection','href'=>'/shop.php','match'=>['shop.php']],
 ['label'=>'Our Story','href'=>'/our-story.php','match'=>['our-story.php']],
 ['label'=>'Contact Us','href'=>'/contact.php','match'=>['contact.php']]
];
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="theme-color" content="#ffffff">
<meta name="color-scheme" content="light">
<meta property="og:site_name" content="IKKI">
<meta property="og:locale" content="en_PK">
<meta name="robots" content="index,follow">
<link rel="icon" type="image/svg+xml" href="/assets/images/favicon.svg">
<link rel="apple-touch-icon" href="/assets/images/favicon.svg">
<meta name="description" content="<?=e($description)?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?=e($pageTitle)?>">
<meta property="og:description" content="<?=e($description)?>">
<title><?=e($pageTitle)?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css?v=20261101-32">
<?php if($isHomePage): ?><link rel="stylesheet" href="/assets/css/home.css?v=20261101-9"><link rel="stylesheet" href="/assets/css/home-layout.css?v=20261102-2"><?php endif; ?>
<link rel="stylesheet" href="/assets/css/ikki-luxury.css?v=20261102-27">
</head>
<body class="<?= $isHomePage?'has-home-hero':'' ?> page-enter is-loading">
<div class="ikki-scroll-progress" data-ikki-scroll-progress aria-hidden="true"><span></span></div>
<div class="home-preloader" data-home-preloader role="status" aria-label="Loading IKKI">
 <div class="home-preloader-content"><span class="home-preloader-mark" aria-label="IKKI">ikki<span>.</span></span><span class="home-preloader-track" aria-hidden="true"><span class="home-preloader-progress"></span></span></div>
</div>
<script>
/* Global, session-aware preloader: 1.5s progress + 0.5s exit; 0.3s on repeat visits. */
(function(){
 var overlay=document.querySelector('[data-home-preloader]');
 if(!overlay)return;
 var repeatVisit=false;
 try{
  repeatVisit=sessionStorage.getItem('ikki-preloader-seen')==='1';
  if(!repeatVisit)sessionStorage.setItem('ikki-preloader-seen','1');
 }catch(error){/* Storage may be unavailable; play the full intro safely. */}
 var reduced=window.matchMedia&&window.matchMedia('(prefers-reduced-motion: reduce)').matches;
 var fast=repeatVisit||reduced;
 if(fast)overlay.classList.add('is-fast');
 var progressTime=fast?0:1500;
 var exitTime=fast?300:500;
 window.setTimeout(function(){
  overlay.classList.add('is-finished');
  document.body.classList.remove('is-loading');
  document.body.classList.add('page-transition-in');
  document.dispatchEvent(new CustomEvent('ikki:preloader-finished'));
  window.setTimeout(function(){
   if(overlay.parentNode)overlay.remove();
  },exitTime);
 },progressTime);
})();
</script>
<div class="ikki-cursor-dot" data-ikki-cursor-dot aria-hidden="true"></div>
<div class="ikki-cursor-ring" data-ikki-cursor-ring aria-hidden="true"></div>
<div class="announcement"><span>Complimentary fragrance consultation</span><i></i><span>Pakistan-wide order enquiries</span></div>
<header class="site-header<?= $isHomePage?' is-home-header':'' ?>" data-site-header>
<a class="maison-brand" href="/" aria-label="IKKI — Iconic Kyenotes. Kinetic Impression"><span class="maison-wordmark">IKKI</span><span class="maison-brand-tagline"><strong>I</strong>conic <strong>K</strong>yenotes. <strong>K</strong>inetic <strong>I</strong>mpression</span></a>
<button class="menu-toggle" type="button" aria-label="Open navigation" aria-expanded="false" aria-controls="mobile-navigation"><span></span><span></span><span></span></button>
<nav class="main-nav" id="primary-navigation" aria-label="Main navigation">
<?php foreach($navItems as $item): $active=in_array($currentPage,$item['match'],true); ?>
<a href="<?=e($item['href'])?>"<?= $active?' class="active" aria-current="page"':'' ?>><?=e($item['label'])?></a>
<?php endforeach; ?>
</nav>
<div class="header-actions">
<a class="header-icon-link header-search-link" href="/shop.php#collection-search" aria-label="Search the collection" title="Search collection">
<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.7"></circle><path d="m16 16 4.5 4.5"></path></svg>
</a>
<a class="header-icon-link header-bag-link" href="/cart.php" aria-label="Shopping bag, <?=cart_count()?> items" title="Shopping bag">
<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 8h14l1 13H4L5 8Z"></path><path d="M9 9V6a3 3 0 0 1 6 0v3"></path></svg>
<span class="bag-count"><?=cart_count()?></span>
</a>
</div>
</header>
<div class="mobile-menu-overlay" id="mobile-navigation" aria-hidden="true">
 <button class="mobile-menu-close" type="button" aria-label="Close navigation"><span></span><span></span></button>
 <nav class="mobile-menu-links" aria-label="Mobile navigation">
 <?php foreach($navItems as $index=>$item): $active=in_array($currentPage,$item['match'],true); ?>
 <a href="<?=e($item['href'])?>" style="--menu-index:<?=$index?>"<?= $active?' class="active" aria-current="page"':'' ?>><?=e($item['label'])?></a>
 <?php endforeach; ?>
 </nav>
 <div class="mobile-menu-social">
  <a href="https://wa.me/923094733789" target="_blank" rel="noopener noreferrer" aria-label="Contact IKKI on WhatsApp">
   <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 11.8a8.5 8.5 0 0 1-12.6 7.4L3 20.5l1.3-4.7a8.5 8.5 0 1 1 16.2-4ZM8.8 7.7c-.2-.5-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4s-1 1-1 2.4 1 2.8 1.1 3c.1.2 2 3.2 5 4.3 2.5 1 3 .8 3.6.7.5-.1 1.7-.7 1.9-1.4.2-.7.2-1.2.1-1.4-.1-.1-.3-.2-.6-.4l-1.9-.9c-.3-.1-.5-.2-.7.2-.2.3-.8.9-.9 1.1-.2.2-.3.2-.6.1-.3-.1-1.2-.4-2.3-1.4-.8-.7-1.4-1.6-1.6-1.9-.2-.3 0-.5.1-.6l.4-.5.3-.5c.1-.2 0-.4 0-.5l-.8-1.9Z"/></svg>
  </a>
  <span>CONNECT WITH US</span>
 </div>
</div>
<main>
