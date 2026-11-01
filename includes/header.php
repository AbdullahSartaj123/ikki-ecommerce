<?php
$pageTitle = $pageTitle ?? 'Ikki — The Art of Fragrance';
$description = $description ?? 'Discover Ikki signature fragrances. A curated fragrance experience.';
$menuItems = [];
try { $menuItems = db()->query("SELECT id,label,link_value,parent_id FROM navigation_menus WHERE menu_location='header' AND is_active=1 ORDER BY parent_id,sort_order,id")->fetchAll(); } catch (Throwable $ex) {}
$menuParents = array_values(array_filter($menuItems, fn($item) => empty($item['parent_id'])));
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle) ?></title><meta name="description" content="<?= e($description) ?>">
<meta name="theme-color" content="#11100f">
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/style.css">
</head><body>
<div class="announcement">A signature scent. An unforgettable presence.</div>
<header class="site-header">
<a class="wordmark" href="/">ikki<span>.</span></a>
<button class="menu-toggle" aria-label="Toggle menu" aria-expanded="false"><span></span><span></span></button>
<nav class="main-nav">
<?php if($menuParents): foreach($menuParents as $parent): $children=array_values(array_filter($menuItems,fn($item)=>(int)$item['parent_id']===(int)$parent['id'])); ?>
<div class="nav-group"><a href="<?=e($parent['link_value'])?>"><?=e($parent['label'])?></a><?php if($children):?><div class="nav-submenu"><?php foreach($children as $child):?><a href="<?=e($child['link_value'])?>"><?=e($child['label'])?></a><?php endforeach;?></div><?php endif;?></div>
<?php endforeach; else: ?><a href="/">Home</a><a href="/shop.php">Collection</a><a href="/#story">Our Story</a><a href="/contact.php">Contact</a><?php endif; ?>
</nav>
<a class="cart-link" href="/cart.php" aria-label="Shopping bag">Bag <span><?= cart_count() ?></span></a>
</header><main>
