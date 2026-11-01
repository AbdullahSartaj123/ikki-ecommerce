<?php
require_once __DIR__ . '/config/bootstrap.php';
$pageTitle='The Collection — Ikki';
$sql="SELECT p.id,p.name,p.slug,p.short_description,MIN(v.price) AS price,(SELECT pi.image_path FROM product_images pi WHERE pi.product_id=p.id ORDER BY pi.is_primary DESC,pi.sort_order ASC LIMIT 1) AS image_path FROM products p LEFT JOIN product_variants v ON v.product_id=p.id AND v.is_active=1 WHERE p.is_active=1 GROUP BY p.id ORDER BY p.is_featured DESC,p.name";
$products=db()->query($sql)->fetchAll();
require __DIR__ . '/includes/header.php';
?>
<div class="page-wrap"><div class="page-intro"><span class="eyebrow">A considered collection</span><h1 class="page-title">The fragrances</h1><p class="muted">Discover the Ikki signature collection.</p></div>
<?php if($products): ?><div class="product-grid"><?php foreach($products as $p): ?><a class="product-card reveal" href="/product.php?slug=<?= e($p['slug']) ?>"><div class="product-image"><img loading="lazy" src="<?= e($p['image_path'] ?: 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=900&q=80') ?>" alt="<?= e($p['name']) ?>"></div><div class="product-info"><h3><?= e($p['name']) ?></h3><p><?= e($p['short_description'] ?? '') ?></p><div class="product-price"><?= $p['price']!==null?money($p['price']):'View details' ?></div></div></a><?php endforeach; ?></div><?php else: ?><div class="notice">No fragrances are published yet. Add products from the admin dashboard.</div><?php endif; ?></div>
<?php require __DIR__ . '/includes/footer.php'; ?>
