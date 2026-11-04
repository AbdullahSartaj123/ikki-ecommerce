<?php
$pageTitle=$pageTitle??'IKKI — Fragrance, composed.';
$description=$description??'Discover IKKI: a modern fragrance house creating expressive scent stories in Pakistan.';
$currentPage=basename($_SERVER['SCRIPT_NAME']??'');
$isHomePage=($currentPage==='index.php');
$siteUrl=rtrim(defined('APP_URL')?APP_URL:'https://ikki.pk','/');
$requestPath=parse_url($_SERVER['REQUEST_URI']??'/',PHP_URL_PATH)?:'/';
if($requestPath==='/index.php')$requestPath='/';
$canonicalUrl=$siteUrl.$requestPath;
if($currentPage==='product.php'&&!empty($_GET['slug'])&&is_string($_GET['slug']))$canonicalUrl.='?slug='.rawurlencode($_GET['slug']);
$socialImage=$siteUrl.'/assets/images/ikki-hero.svg';
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
<meta property="og:url" content="<?=e($canonicalUrl)?>">
<meta property="og:image" content="<?=e($socialImage)?>">
<meta property="og:image:alt" content="IKKI — The Art of Fragrance">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?=e($pageTitle)?>">
<meta name="twitter:description" content="<?=e($description)?>">
<meta name="twitter:image" content="<?=e($socialImage)?>">
<link rel="canonical" href="<?=e($canonicalUrl)?>">
<meta name="robots" content="index,follow">
<link rel="icon" type="image/svg+xml" href="/assets/images/favicon.svg">
<link rel="apple-touch-icon" href="/assets/images/favicon.svg">
<meta name="description" content="<?=e($description)?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?=e($pageTitle)?>">
<meta property="og:description" content="<?=e($description)?>">
<title><?=e($pageTitle)?></title>
<script type="application/ld+json">
<?=json_encode([
 '@context'=>'https://schema.org',
 '@type'=>'Organization',
 'name'=>'IKKI',
 'url'=>$siteUrl.'/',
 'logo'=>$siteUrl.'/assets/images/favicon.svg',
 'description'=>'A modern fragrance house serving Pakistan.',
 'contactPoint'=>[
  '@type'=>'ContactPoint',
  'telephone'=>'+923094733789',
  'contactType'=>'customer service',
  'areaServed'=>'PK',
  'availableLanguage'=>['English','Urdu']
 ]
],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT)?>
</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css?v=20261104-10">
<?php if($isHomePage): ?><link rel="stylesheet" href="/assets/css/home.css?v=20261104-1"><link rel="stylesheet" href="/assets/css/home-layout.css?v=20261104-1"><?php endif; ?>
<link rel="stylesheet" href="/assets/css/ikki-luxury.css?v=20261104-11">
</head>
<body class="<?= $isHomePage?'has-home-hero':'' ?> page-enter is-loading">
<div class="ikki-scroll-progress" data-ikki-scroll-progress aria-hidden="true"><span></span></div>
<div class="home-preloader" data-home-preloader role="status" aria-label="Loading IKKI">
 <div class="home-preloader-content"><span class="home-preloader-mark" aria-label="IKKI">ikki<span>.</span></span><span class="home-preloader-track" aria-hidden="true"><span class="home-preloader-progress"></span></span></div>
</div>
<script>
/* Keep the brand transition brief; never hold shopping content behind an intro. */
(function(){
 var overlay=document.querySelector('[data-home-preloader]');
 var finish=function(){
  if(!overlay)return;
  overlay.classList.add('is-finished');
  document.body.classList.remove('is-loading');
  document.body.classList.add('page-transition-in');
  document.dispatchEvent(new CustomEvent('ikki:preloader-finished'));
  window.setTimeout(function(){if(overlay.parentNode)overlay.remove();},280);
 };
 var reduced=window.matchMedia&&window.matchMedia('(prefers-reduced-motion: reduce)').matches;
 window.setTimeout(finish,reduced?0:120);
})();
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
<button class="header-icon-link header-search-link" type="button" data-search-toggle aria-label="Open search" title="Search collection">
<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.7"></circle><path d="m16 16 4.5 4.5"></path></svg>
</button>
<a class="header-icon-link header-bag-link" href="/cart.php" data-cart-drawer-trigger aria-label="Shopping bag, <?=cart_count()?> items" title="Shopping bag">
<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 8h14l1 13H4L5 8Z"></path><path d="M9 9V6a3 3 0 0 1 6 0v3"></path></svg>
<span class="bag-count" data-bag-count><?=cart_count()?></span>
</a>
</div>
<div class="header-search-panel" data-search-panel aria-hidden="true"><form action="/shop.php" method="get"><input type="search" name="q" placeholder="Search fragrances…" aria-label="Search fragrances" data-header-search-input><button type="submit" aria-label="Submit search">↗</button></form><button type="button" data-search-close aria-label="Close search">×</button></div>
</header>
<div class="mobile-menu-overlay" id="mobile-navigation" aria-hidden="true">
 <button class="mobile-menu-close" type="button" aria-label="Close navigation"><span></span><span></span></button>
 <nav class="mobile-menu-links" aria-label="Mobile navigation">
 <?php foreach($navItems as $index=>$item): $active=in_array($currentPage,$item['match'],true); ?>
 <a href="<?=e($item['href'])?>" style="--menu-index:<?=$index?>"<?= $active?' class="active" aria-current="page"':'' ?>><?=e($item['label'])?></a>
 <?php endforeach; ?>
 </nav>
 <div class="mobile-menu-actions" aria-label="Shopping actions">
  <button class="header-icon-link header-search-link" type="button" data-search-toggle aria-label="Open search" title="Search collection"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.7"></circle><path d="m16 16 4.5 4.5"></path></svg><span>SEARCH</span></button>
  <button class="header-icon-link header-bag-link" type="button" data-cart-drawer-trigger aria-label="Open shopping bag"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 8h14l1 13H4L5 8Z"></path><path d="M9 9V6a3 3 0 0 1 6 0v3"></path></svg><span>BAG (<span data-bag-count><?=cart_count()?></span>)</span></button>
 </div>
 <div class="mobile-menu-social">
  <a href="https://wa.me/923094733789" target="_blank" rel="noopener noreferrer" aria-label="Contact IKKI on WhatsApp">
   <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 11.8a8.5 8.5 0 0 1-12.6 7.4L3 20.5l1.3-4.7a8.5 8.5 0 1 1 16.2-4ZM8.8 7.7c-.2-.5-.5-.5-.7-.5h-.6c-.2 0-.5.1-.8.4s-1 1-1 2.4 1 2.8 1.1 3c.1.2 2 3.2 5 4.3 2.5 1 3 .8 3.6.7.5-.1 1.7-.7 1.9-1.4.2-.7.2-1.2.1-1.4-.1-.1-.3-.2-.6-.4l-1.9-.9c-.3-.1-.5-.2-.7.2-.2.3-.8.9-.9 1.1-.2.2-.3.2-.6.1-.3-.1-1.2-.4-2.3-1.4-.8-.7-1.4-1.6-1.6-1.9-.2-.3 0-.5.1-.6l.4-.5.3-.5c.1-.2 0-.4 0-.5l-.8-1.9Z"/></svg>
  </a>
  <span>CONNECT WITH US</span>
 </div>
</div>

<div class="ikki-cart-scrim" data-cart-scrim></div>
<aside class="ikki-cart-drawer" data-cart-drawer aria-hidden="true" aria-label="Shopping bag"><div class="ikki-cart-drawer-head"><div><span class="eyebrow">YOUR SELECTION</span><h2>Your bag<span data-drawer-count></span></h2></div><button type="button" data-cart-close aria-label="Close shopping bag">×</button></div><div class="ikki-cart-drawer-content" data-cart-content><p class="muted">Loading your selection…</p></div></aside>
<main>
