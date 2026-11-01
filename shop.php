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
  $variants=[];
  if($dbProduct){$variantQuery=db()->prepare("SELECT id,variant_name,volume_ml,price,stock_quantity FROM product_variants WHERE product_id=? AND is_active=1 ORDER BY volume_ml");$variantQuery->execute([(int)$dbProduct['id']]);$variants=$variantQuery->fetchAll();}
  $topNote=trim(explode(',',$p['notes'])[0]??'');
  $noteParts=array_map('trim',explode(',',$p['notes']));
  $heartNote=$noteParts[1]??'Signature accord';
  $baseNote=$noteParts[2]??'Warm woods';
  $modalDescription=$p['name'].' opens with '.$topNote.', unfolds into '.$heartNote.', and settles into '.$baseNote.'. A considered scent composition for a memorable signature.';
 ?>
  <article class="collection-card collection-reveal" data-category="<?=e($p['category'])?>" data-is-new="<?=$p['is_new']?'true':'false'?>" data-price="<?=e((string)$p['price'])?>" data-product-id="<?=e((string)($dbProduct['id']??(8-$n)))?>">
   <div class="collection-card-image">
    <?php if($p['is_new']): ?><span class="collection-new-badge">New</span><?php endif; ?>
    <img src="<?=e($imagePath)?>" alt="<?=e($p['name'])?> fragrance bottle" loading="lazy">
    <button class="collection-quick-view" type="button" data-quick-view
     data-name="<?=e($p['name'])?>" data-notes="<?=e($p['notes'])?>" data-price="<?=e(money($p['price']))?>"
     data-image="<?=e($imagePath)?>" data-description="<?=e($modalDescription)?>"
     data-top="<?=e($topNote)?>" data-heart="<?=e($heartNote)?>" data-base="<?=e($baseNote)?>"
     data-url="<?=e($productUrl)?>" data-variants="<?=e(json_encode($variants,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES))?>"
     data-available="<?=$dbProduct&&count($variants)?'true':'false'?>"
     aria-label="Quick view <?=e($p['name'])?>">
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
 
 <div class="collection-pagination" id="collection-pagination"><button type="button" class="collection-load-more" id="collection-load-more">Load More</button><span class="collection-all-viewed" id="collection-all-viewed" hidden>You’ve viewed our full collection</span></div>
 <dialog class="collection-quick-dialog" id="collection-quick-dialog" role="dialog" aria-modal="true" aria-labelledby="quick-view-title">
  <button class="collection-dialog-close" type="button" data-quick-close aria-label="Close quick view"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg></button>
  <div class="quick-view-layout">
   <div class="quick-view-visual"><img data-quick-image src="/assets/images/noor.svg" alt="Ikki fragrance bottle artwork for the selected scent"></div>
   <div class="quick-view-content">
    <span class="quick-view-kicker">THE IKKI FRAGRANCE HOUSE</span>
    <h2 id="quick-view-title"></h2>
    <strong class="quick-view-price" data-quick-price></strong>
    <div class="quick-view-rating" aria-label="Customer ratings are not available yet"><span aria-hidden="true">★★★★★</span><small>Customer rating coming soon</small></div>
    <div class="quick-view-notes"><span class="quick-view-label">THE OLFACTIVE STORY</span><div class="quick-view-note-pills"><span data-note-top></span><span data-note-heart></span><span data-note-base></span></div></div>
    <p class="quick-view-description" data-quick-description></p>
    <form class="quick-view-purchase" method="post" action="/actions/cart-add.php" data-quick-form>
     <?=csrf_field()?>
     <input type="hidden" name="variant_id" value="">
     <div class="quick-view-options" data-quick-sizes hidden><span class="quick-view-label">CHOOSE YOUR SIZE</span><div class="quick-view-size-buttons" data-size-buttons></div></div>
     <div class="quick-view-quantity"><span class="quick-view-label">QUANTITY</span><div class="quick-view-quantity-control"><button type="button" data-quantity-minus aria-label="Decrease quantity">−</button><output data-quantity-value>1</output><button type="button" data-quantity-plus aria-label="Increase quantity">+</button></div><input type="hidden" name="quantity" value="1"></div>
     <button class="quick-view-add" type="submit" disabled data-quick-add>Add to Cart</button>
     <p class="quick-view-unavailable" data-quick-unavailable hidden>This fragrance is coming soon and is not available to add yet.</p>
    </form>
    <a class="quick-view-detail-link" data-quick-detail href="/shop.php" hidden>Explore fragrance details ↗</a>
   </div>
  </div>
 </dialog>
 <div class="collection-no-results" id="collection-no-results" role="status" aria-live="polite" hidden><svg viewBox="0 0 48 48" aria-hidden="true"><path d="M19 5h10m-8 0v8l-7 8v19h22V21l-7-8V5M14 21h20M18 29h12"/></svg><p>No products found in this category</p><button type="button" class="collection-empty-reset" data-reset-filter>View All Products</button></div><div class="collection-skeleton-grid" id="collection-skeleton" aria-hidden="true" hidden><div class="collection-skeleton-card"><span></span><i></i><i></i></div><div class="collection-skeleton-card"><span></span><i></i><i></i></div><div class="collection-skeleton-card"><span></span><i></i><i></i></div><div class="collection-skeleton-card"><span></span><i></i><i></i></div></div>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>
