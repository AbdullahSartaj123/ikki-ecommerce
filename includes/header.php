<?php
$pageTitle=$pageTitle??'Ikki — Fragrance, composed.';
$description=$description??'Discover Ikki: a modern fragrance house creating expressive scent stories in Pakistan.';
$currentPage=basename($_SERVER['SCRIPT_NAME']??'');
$isHomePage=($currentPage==='index.php');
$navItems=[
 ['label'=>'Home','href'=>'/','match'=>['index.php']],
 ['label'=>'Collection','href'=>'/shop.php','match'=>['shop.php']],
 ['label'=>'Our Story','href'=>'/our-story.php','match'=>['our-story.php']],
 ['label'=>'Contact','href'=>'/contact.php','match'=>['contact.php']]
];
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="theme-color" content="#0a0a0a">
<meta name="color-scheme" content="dark">
<meta property="og:site_name" content="Ikki">
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
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css?v=20261101-8">
<?php if($isHomePage): ?><link rel="stylesheet" href="/assets/css/home.css?v=20261101-2"><?php endif; ?>
</head>
<body class="<?= $isHomePage?'has-home-hero':'' ?> page-enter">
<div class="announcement"><span>Complimentary fragrance consultation</span><i></i><span>Pakistan-wide order enquiries</span></div>
<header class="site-header<?= $isHomePage?' is-home-header':'' ?>" data-site-header>
<a class="maison-brand" href="/" aria-label="Ikki home"><span class="maison-monogram" aria-hidden="true">M</span><span class="maison-wordmark">MAISON</span></a>
<button class="menu-toggle" type="button" aria-label="Open navigation" aria-expanded="false" aria-controls="primary-navigation"><span></span><span></span></button>
<nav class="main-nav" id="primary-navigation" aria-label="Main navigation">
<?php foreach($navItems as $item): $active=in_array($currentPage,$item['match'],true); ?>
<a href="<?=e($item['href'])?>"<?= $active?' class="active" aria-current="page"':'' ?>><?=e($item['label'])?></a>
<?php endforeach; ?>
</nav>
<div class="header-actions">
<a class="header-icon-link header-search-link" href="/shop.php" aria-label="Explore and search the collection" title="Explore collection">
<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.7"></circle><path d="m16 16 4.5 4.5"></path></svg>
</a>
<a class="header-icon-link header-bag-link" href="/cart.php" aria-label="Shopping bag, <?=cart_count()?> items" title="Shopping bag">
<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 8h14l1 13H4L5 8Z"></path><path d="M9 9V6a3 3 0 0 1 6 0v3"></path></svg>
<span class="bag-count"><?=cart_count()?></span>
</a>
</div>
</header>
<main>
