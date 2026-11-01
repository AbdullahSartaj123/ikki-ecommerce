<?php
$pageTitle=$pageTitle??'Ikki — Fragrance, composed.';
$description=$description??'Discover Ikki: a modern fragrance house creating expressive scent stories in Pakistan.';
$currentPage=basename($_SERVER['SCRIPT_NAME']??'');
$isHomePage=($currentPage==='index.php');
$navItems=[
 ['label'=>'Home','href'=>'/','match'=>['index.php']],
 ['label'=>'Collection','href'=>'/shop.php','match'=>['shop.php']],
 ['label'=>'Our Story','href'=>'/#our-world','match'=>['our-story.php']],
 ['label'=>'Contact','href'=>'/contact.php','match'=>['contact.php']]
];
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="theme-color" content="#11100e">
<meta name="description" content="<?=e($description)?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?=e($pageTitle)?>">
<meta property="og:description" content="<?=e($description)?>">
<title><?=e($pageTitle)?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css?v=20261101-3">
<?php if($isHomePage): ?><link rel="stylesheet" href="/assets/css/home.css?v=20261101-1"><?php endif; ?>
</head>
<body class="<?= $isHomePage?'has-home-hero':'' ?>">
<div class="announcement"><span>Complimentary fragrance consultation</span><i></i><span>Pakistan-wide order enquiries</span></div>
<header class="site-header<?= $isHomePage?' is-home-header':'' ?>" data-site-header>
<a class="wordmark" href="/" aria-label="Ikki home">ikki<span>®</span></a>
<button class="menu-toggle" type="button" aria-label="Open navigation" aria-expanded="false" aria-controls="primary-navigation"><span></span><span></span></button>
<nav class="main-nav" id="primary-navigation" aria-label="Main navigation">
<?php foreach($navItems as $item): $active=in_array($currentPage,$item['match'],true); ?>
<a href="<?=e($item['href'])?>"<?= $active?' class="active" aria-current="page"':'' ?>><?=e($item['label'])?></a>
<?php endforeach; ?>
</nav>
<a class="bag-link" href="/cart.php" aria-label="Shopping bag, <?=cart_count()?> items"><span>Bag</span><b><?=cart_count()?></b></a>
</header>
<main>
