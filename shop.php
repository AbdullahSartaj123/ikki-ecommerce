<?php
require_once __DIR__.'/config/bootstrap.php';
$pageTitle='Collection — IKKI';
$description='Explore the IKKI fragrance collection: individual scent stories, composed to become part of your signature.';
$products=[];
try{
 $products=db()->query("SELECT p.id,p.name,p.slug,p.short_description,p.description,p.gender,p.top_notes,p.heart_notes,p.base_notes,MIN(v.price) price,(SELECT image_path FROM product_images WHERE product_id=p.id ORDER BY is_primary DESC,sort_order LIMIT 1) image_path FROM products p LEFT JOIN product_variants v ON v.product_id=p.id AND v.is_active=1 WHERE p.is_active=1 AND p.name IN ('Legendary Profile','Informal','Amane','Sasha') GROUP BY p.id ORDER BY FIELD(p.name,'Legendary Profile','Informal','Amane','Sasha') LIMIT 4")->fetchAll();
}catch(Throwable $ex){error_log('[IKKI collection] '.$ex->getMessage());}
/*
 * Keep the editorial collection visible even before the live catalogue is populated.
 * These are non-purchasable concept previews: no price, stock or cart variant is implied.
 */
if(!$products){
 $products=[
 ['name'=>'Legendary Profile','slug'=>'legendary-profile','short_description'=>'A confident signature with a distinctive, memorable presence.','description'=>'Legendary Profile is the bold signature fragrance from IKKI.','gender'=>'Unisex','top_notes'=>'Fragrance profile to be confirmed','heart_notes'=>'Fragrance profile to be confirmed','base_notes'=>'Fragrance profile to be confirmed','price'=>null,'image_path'=>'/assets/images/legendary-profile.svg'],
 ['name'=>'Informal','slug'=>'informal','short_description'=>'An effortless everyday expression, relaxed yet refined.','description'=>'Informal brings a relaxed, modern character to everyday moments.','gender'=>'Unisex','top_notes'=>'Fragrance profile to be confirmed','heart_notes'=>'Fragrance profile to be confirmed','base_notes'=>'Fragrance profile to be confirmed','price'=>null,'image_path'=>'/assets/images/informal.svg'],
 ['name'=>'Amane','slug'=>'amane','short_description'=>'A fresh, contemporary character with a smooth finish.','description'=>'Amane is a fresh and contemporary IKKI signature fragrance.','gender'=>'Unisex','top_notes'=>'Fragrance profile to be confirmed','heart_notes'=>'Fragrance profile to be confirmed','base_notes'=>'Fragrance profile to be confirmed','price'=>null,'image_path'=>'/assets/images/amane.svg'],
 ['name'=>'Sasha','slug'=>'sasha','short_description'=>'A poised, expressive fragrance with a lasting impression.','description'=>'Sasha is an expressive signature with a graceful, memorable trail.','gender'=>'Unisex','top_notes'=>'Fragrance profile to be confirmed','heart_notes'=>'Fragrance profile to be confirmed','base_notes'=>'Fragrance profile to be confirmed','price'=>null,'image_path'=>'/assets/images/sasha.svg']

 ];
}
$catalog=[];
$databaseProducts=[];
foreach($products as $product){
 if(isset($product['id']))$databaseProducts[$product['slug']]=$product;
 $gender=strtolower(trim((string)($product['gender']??'')));
 $category=in_array($gender,['female','women','woman','her'],true)?'her':(in_array($gender,['male','men','man','him'],true)?'him':'unisex');
 $catalog[]=[
  'name'=>$product['name'],'slug'=>$product['slug'],
  'notes'=>trim((string)($product['short_description']??''))?:'An IKKI signature fragrance',
  'top_notes'=>(string)($product['top_notes']??''),'heart_notes'=>(string)($product['heart_notes']??''),'base_notes'=>(string)($product['base_notes']??''),'description'=>(string)($product['description']??''),
  'price'=>$product['price']!==null?(float)$product['price']:null,'image_path'=>$product['image_path']??null,
  'category'=>$category,'is_new'=>false
 ];
}
$products=$catalog;
$availableCategories=array_values(array_unique(array_column($products,'category')));
require __DIR__.'/includes/header.php';
?>
<section class="ikki-catalog-hero" aria-labelledby="page-banner-title">
 <div class="ikki-catalog-hero-inner">
  <nav class="page-banner-breadcrumb" aria-label="Breadcrumb"><a href="/">Home</a><span aria-hidden="true">›</span><span aria-current="page">The Collection</span></nav>
  <span class="home-kicker">IKKI · THE SIGNATURE EDIT</span>
  <h1 id="page-banner-title">A few scents.<br><em>Endless expression.</em></h1>
  <p>A considered collection of signature fragrances. Discover the scent that feels unmistakably yours.</p>
  <span class="ikki-catalog-edition">THE SIGNATURE COLLECTION <i>—</i> 01 / 04</span>
 </div>
 <div class="ikki-catalog-hero-side"><img src="/assets/images/collection-hero.svg" alt="IKKI signature perfume bottle" width="420" height="460" fetchpriority="high"><span>SCENT IS A FORM OF MEMORY</span><b>IKKI</b></div>
</section>
<section class="collection-catalog ikki-premium-catalog" aria-label="Fragrance collection">
 <div class="ikki-catalog-intro"><span>THE IKKI SIGNATURE EDIT</span><p>Each fragrance tells its own story. Take your time, explore the notes, and find your signature.</p><?php if(empty($databaseProducts)): ?><small class="ikki-preview-note">COLLECTION PREVIEW · FRAGRANCES AND PRICING WILL BE CONFIRMED BEFORE ORDERS OPEN</small><?php endif; ?></div>
 <div class="collection-grid ikki-signature-grid" id="collection-grid">
 <?php if($products): foreach($products as $n=>$p):
  $dbProduct=$databaseProducts[$p['slug']]??null;
  $productUrl=$dbProduct?'/product.php?slug='.rawurlencode($p['slug']):'';
  $signatureImages=['legendary-profile'=>'/assets/images/legendary-profile.svg','informal'=>'/assets/images/informal.svg','amane'=>'/assets/images/amane.svg','sasha'=>'/assets/images/sasha.svg'];
  $imagePath=trim((string)($p['image_path']??''))?:($signatureImages[$p['slug']]??'/assets/images/legendary-profile.svg');
  $variants=[];
  if($dbProduct){$variantQuery=db()->prepare("SELECT id,variant_name,volume_ml,price,stock_quantity FROM product_variants WHERE product_id=? AND is_active=1 ORDER BY volume_ml");$variantQuery->execute([(int)$dbProduct['id']]);$variants=$variantQuery->fetchAll();}
  $topNote=trim((string)($p['top_notes']??''))?:'Opening notes';
  $heartNote=trim((string)($p['heart_notes']??''))?:'Heart notes';
  $baseNote=trim((string)($p['base_notes']??''))?:'Base notes';
  $modalDescription=trim((string)($p['description']??''))?:'Discover the olfactive story of '.$p['name'].', an IKKI fragrance.';
 ?>
  <article class="collection-card collection-reveal ikki-signature-card" data-category="<?=e($p['category'])?>" data-is-new="<?=$p['is_new']?'true':'false'?>" data-price="<?=e((string)$p['price'])?>" data-product-id="<?=e((string)($dbProduct['id']??(8-$n)))?>">
   <div class="collection-card-image">
    <?php if($p['is_new']): ?><span class="collection-new-badge">New</span><?php endif; ?>
    <a class="ikki-card-image-link" href="<?=e($productUrl?:'/shop.php')?>"><img src="<?=e($imagePath)?>" alt="<?=e($p['name'])?> fragrance bottle" width="800" height="1000" loading="lazy" decoding="async"></a>
    <button class="collection-quick-view" type="button" aria-label="View <?=e($p['name'])?> fragrance details" data-quick-view
     data-name="<?=e($p['name'])?>" data-notes="<?=e($p['notes'])?>" data-price="<?=e($p['price']!==null?money($p['price']):'Price on request')?>"
     data-image="<?=e($imagePath)?>" data-description="<?=e($modalDescription)?>"
     data-top="<?=e($topNote)?>" data-heart="<?=e($heartNote)?>" data-base="<?=e($baseNote)?>"
     data-url="<?=e($productUrl)?>" data-variants="<?=e(json_encode($variants,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES))?>"
     data-available="<?=$dbProduct&&count($variants)?'true':'false'?>"
     aria-label="Quick view <?=e($p['name'])?>">
     <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.4-6 9.5-6 9.5 6 9.5 6-3.4 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.7"/></svg>
    </button>
   </div>
   <div class="collection-card-info">
    <h2><a href="<?=e($productUrl?:'/shop.php')?>"><?=e($p['name'])?></a></h2>
    <p><?=e($p['notes'])?></p>
    <strong><?=$p['price']!==null?money($p['price']):'Price on request'?></strong><a class="ikki-card-discover" href="<?=e($productUrl?:'/shop.php')?>">DISCOVER FRAGRANCE <span>↗</span></a>
   </div>
  </article>
 <?php endforeach; else: ?>
  <p class="collection-empty-message">Our collection is being prepared. Please contact IKKI for current fragrance availability.</p>
 <?php endif; ?>
 </div>
 
 
 <dialog class="collection-quick-dialog" id="collection-quick-dialog" role="dialog" aria-modal="true" aria-labelledby="quick-view-title">
  <button class="collection-dialog-close" type="button" data-quick-close aria-label="Close quick view"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg></button>
  <div class="quick-view-layout">
   <div class="quick-view-visual"><img data-quick-image src="/assets/images/legendary-profile.svg" alt="IKKI fragrance bottle artwork for the selected scent" width="800" height="1000" loading="lazy" decoding="async"></div>
   <div class="quick-view-content">
    <span class="quick-view-kicker">THE IKKI FRAGRANCE HOUSE</span>
    <h2 id="quick-view-title"></h2>
    <strong class="quick-view-price" data-quick-price></strong>
    
    <div class="quick-view-notes"><span class="quick-view-label">THE OLFACTIVE STORY</span><div class="quick-view-note-pills"><span data-note-top></span><span data-note-heart></span><span data-note-base></span></div></div>
    <p class="quick-view-description" data-quick-description></p>
    <form class="quick-view-purchase" method="post" action="/actions/cart-add.php" data-quick-form>
     <?=csrf_field()?>
     <input type="hidden" name="variant_id" value="">
     <div class="quick-view-options" data-quick-sizes hidden><span class="quick-view-label">CHOOSE YOUR SIZE</span><div class="quick-view-size-buttons" data-size-buttons></div></div>
     <div class="quick-view-quantity"><span class="quick-view-label">QUANTITY</span><div class="quick-view-quantity-control"><button type="button" data-quantity-minus aria-label="Decrease quantity">−</button><output data-quantity-value>1</output><button type="button" data-quantity-plus aria-label="Increase quantity">+</button></div><input type="hidden" name="quantity" value="1"></div>
     <button class="quick-view-add ikki-btn--primary" type="submit" disabled data-quick-add>Add to Cart <span aria-hidden="true">↗</span></button>
     <p class="quick-view-unavailable" data-quick-unavailable hidden>This fragrance is coming soon and is not available to add yet.</p>
    </form>
    <a class="quick-view-detail-link" data-quick-detail href="/shop.php" hidden>Explore fragrance details ↗</a>
   </div>
  </div>
 </dialog>
 <div class="collection-no-results" id="collection-no-results" role="status" aria-live="polite" hidden><svg viewBox="0 0 48 48" aria-hidden="true"><circle cx="21" cy="21" r="12.5"/><path d="m30.5 30.5 10 10M16 21h10M21 16v10"/></svg><p>No fragrances match this filter</p><button type="button" class="collection-empty-reset" data-reset-filter>View All Fragrances</button></div><div class="collection-skeleton-grid" id="collection-skeleton" aria-hidden="true" hidden><div class="collection-skeleton-card"><span></span><i></i><i></i></div><div class="collection-skeleton-card"><span></span><i></i><i></i></div><div class="collection-skeleton-card"><span></span><i></i><i></i></div><div class="collection-skeleton-card"><span></span><i></i><i></i></div></div>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>
