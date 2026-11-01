<?php
require_once __DIR__.'/config/bootstrap.php';
$pageTitle='Our Collection — Ikki';
$description='Explore the Ikki fragrance collection: individual scent stories, composed to become part of your signature.';
$products=[];
try{
 $products=db()->query("SELECT p.id,p.name,p.slug,p.short_description,p.gender,MIN(v.price) price,(SELECT image_path FROM product_images WHERE product_id=p.id ORDER BY is_primary DESC,sort_order LIMIT 1) image_path FROM products p LEFT JOIN product_variants v ON v.product_id=p.id AND v.is_active=1 WHERE p.is_active=1 GROUP BY p.id ORDER BY p.is_featured DESC,p.name")->fetchAll();
}catch(Throwable $ex){error_log('[Ikki collection] '.$ex->getMessage());}
$catalog=[
 ['name'=>'Noir Orchid','slug'=>'noir-orchid','notes'=>'Dark floral, oud, black amber','price'=>185,'category'=>'her','is_new'=>false],
 ['name'=>'Velvet Rose','slug'=>'velvet-rose','notes'=>'Rose, patchouli, vanilla','price'=>165,'category'=>'her','is_new'=>false],
 ['name'=>'Golden Oud','slug'=>'golden-oud','notes'=>'Oud, saffron, sandalwood','price'=>210,'category'=>'unisex','is_new'=>false],
 ['name'=>'Citrus Soleil','slug'=>'citrus-soleil','notes'=>'Bergamot, neroli, musk','price'=>145,'category'=>'unisex','is_new'=>false],
 ['name'=>'Amber Nights','slug'=>'amber-nights','notes'=>'Amber, leather, tobacco','price'=>195,'category'=>'him','is_new'=>false],
 ['name'=>'Iron & Leather','slug'=>'iron-leather','notes'=>'Vetiver, leather, cedarwood','price'=>175,'category'=>'him','is_new'=>false],
 ['name'=>'White Jasmine','slug'=>'white-jasmine','notes'=>'Jasmine, white musk, pear','price'=>155,'category'=>'her','is_new'=>true],
 ['name'=>'Midnight Spice','slug'=>'midnight-spice','notes'=>'Black pepper, cardamom, oud','price'=>220,'category'=>'him','is_new'=>true],
];
$databaseProducts=[];
foreach($products as $product){$databaseProducts[$product['slug']]=$product;}
$products=$catalog;
require __DIR__.'/includes/header.php';
?>
<section class="page-banner page-banner-collection" aria-labelledby="page-banner-title">
 <div class="page-banner-copy reveal">
  <nav class="page-banner-breadcrumb" aria-label="Breadcrumb"><a href="/">Home</a><span aria-hidden="true">›</span><span aria-current="page">Our Collection</span></nav>
  <h1 id="page-banner-title">Our Collection</h1>
  <p>Discover fragrances crafted for every moment</p>
 </div>
</section>
<section class="collection-catalog" aria-label="Fragrance collection">
 <div class="collection-toolbar-wrap">
 <div class="collection-toolbar" data-collection-toolbar>
  <div class="collection-filters" role="group" aria-label="Filter fragrances by category" data-filter-track>
   <span class="collection-filter-indicator" aria-hidden="true"></span>
   <button class="collection-filter is-active" type="button" data-filter="all" aria-pressed="true">All</button>
   <button class="collection-filter" type="button" data-filter="her" aria-pressed="false">For Her</button>
   <button class="collection-filter" type="button" data-filter="him" aria-pressed="false">For Him</button>
   <button class="collection-filter" type="button" data-filter="unisex" aria-pressed="false">Unisex</button>
   <button class="collection-filter" type="button" data-filter="new" aria-pressed="false">New Arrivals</button>
  </div>
  <div class="collection-toolbar-controls">
   <span class="collection-count"><b><?=count($products)?></b> FRAGRANCES</span>
   <label class="collection-sort"><span>Sort by</span>
    <span class="collection-sort-select"><select id="collection-sort" aria-label="Sort fragrances"><option value="featured">Featured</option><option value="price-asc">Price: Low–High</option><option value="price-desc">Price: High–Low</option><option value="newest">Newest</option></select><svg viewBox="0 0 16 16" aria-hidden="true"><path d="m3.5 5.75 4.5 4.5 4.5-4.5"/></svg></span>
   </label>
  </div>
 </div>
 </div>
 <div class="collection-grid" id="collection-grid">
 <?php foreach($products as $n=>$p):
  $dbProduct=$databaseProducts[$p['slug']]??null;
  $productUrl=$dbProduct?'/product.php?slug='.rawurlencode($p['slug']):'';
  $imagePath=$dbProduct['image_path']??'/assets/images/noor.svg';
 ?>
  <article class="collection-card collection-reveal" data-category="<?=e($p['category'])?>" data-is-new="<?=$p['is_new']?'true':'false'?>" data-price="<?=e((string)$p['price'])?>" data-product-id="<?=e((string)($dbProduct['id']??(8-$n)))?>">
   <div class="collection-card-image">
    <?php if($p['is_new']): ?><span class="collection-new-badge">New</span><?php endif; ?>
    <img src="<?=e($imagePath)?>" alt="<?=e($p['name'])?> fragrance bottle" loading="lazy">
    <button class="collection-quick-view" type="button" data-quick-view data-name="<?=e($p['name'])?>" data-notes="<?=e($p['notes'])?>" data-price="<?=e(money($p['price']))?>" data-url="<?=e($productUrl)?>" aria-label="Quick view <?=e($p['name'])?>">
     <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.4-6 9.5-6 9.5 6 9.5 6-3.4 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.7"/></svg>
    </button>
   </div>
   <div class="collection-card-info">
    <h2><?=e($p['name'])?></h2>
    <p><?=e($p['notes'])?></p>
    <strong><?=money($p['price'])?></strong>
   </div>
  </article>
 <?php endforeach; ?>
 </div>
 <dialog class="collection-quick-dialog" id="collection-quick-dialog" aria-labelledby="quick-view-title">
  <button class="collection-dialog-close" type="button" data-quick-close aria-label="Close quick view">×</button>
  <span class="eyebrow">THE IKKI FRAGRANCE HOUSE</span>
  <h2 id="quick-view-title"></h2><p class="collection-dialog-notes"></p><strong class="collection-dialog-price"></strong>
  <a class="button-gold collection-dialog-link" href="/shop.php" hidden>View fragrance <span>↗</span></a>
 </dialog>
 <p class="collection-no-results" id="collection-no-results" hidden>No fragrances in this category just yet.</p>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>
