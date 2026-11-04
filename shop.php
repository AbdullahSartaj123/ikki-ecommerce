<?php
require_once __DIR__.'/config/bootstrap.php';
$pageTitle='Collection — IKKI';
$description='Explore the IKKI fragrance collection: individual scent stories, composed to become part of your signature.';
$products=[];
try{
 $products=db()->query("SELECT p.id,p.name,p.slug,p.short_description,p.description,p.gender,p.top_notes,p.heart_notes,p.base_notes,MIN(v.price) price,(SELECT image_path FROM product_images WHERE product_id=p.id ORDER BY is_primary DESC,sort_order LIMIT 1) image_path FROM products p LEFT JOIN product_variants v ON v.product_id=p.id AND v.is_active=1 WHERE p.is_active=1 AND p.name IN ('Legendary Profile','Informal','Amane','Sasha') GROUP BY p.id ORDER BY FIELD(p.name,'Legendary Profile','Informal','Amane','Sasha') LIMIT 4")->fetchAll();
}catch(Throwable $ex){error_log('[IKKI collection] '.$ex->getMessage());}
/* The house has four permanent signature identities. Merge live catalogue data
   into this fixed editorial order; a missing/inactive product remains a preview,
   never a fake purchasable listing. */
$conceptProducts=[
 ['name'=>'Legendary Profile','slug'=>'legendary-profile','short_description'=>'A confident signature with a distinctive, memorable presence.','description'=>'Legendary Profile is an IKKI signature fragrance.','gender'=>'','top_notes'=>'','heart_notes'=>'','base_notes'=>'','price'=>null,'image_path'=>'/assets/images/legendary-profile.svg'],
 ['name'=>'Informal','slug'=>'informal','short_description'=>'An effortless everyday expression, relaxed yet refined.','description'=>'Informal is an IKKI signature fragrance.','gender'=>'','top_notes'=>'','heart_notes'=>'','base_notes'=>'','price'=>null,'image_path'=>'/assets/images/informal.svg'],
 ['name'=>'Amane','slug'=>'amane','short_description'=>'A fresh, contemporary character with a smooth finish.','description'=>'Amane is an IKKI signature fragrance.','gender'=>'','top_notes'=>'','heart_notes'=>'','base_notes'=>'','price'=>null,'image_path'=>'/assets/images/amane.svg'],
 ['name'=>'Sasha','slug'=>'sasha','short_description'=>'A poised, expressive fragrance with a lasting impression.','description'=>'Sasha is an IKKI signature fragrance.','gender'=>'','top_notes'=>'','heart_notes'=>'','base_notes'=>'','price'=>null,'image_path'=>'/assets/images/sasha.svg']
];
$liveBySlug=[];
foreach($products as $liveProduct){$liveBySlug[$liveProduct['slug']]=$liveProduct;}
$products=[];
foreach($conceptProducts as $concept){
 $live=$liveBySlug[$concept['slug']]??null;
 $products[]=$live?array_merge($concept,$live,['is_live'=>true]):array_merge($concept,['is_live'=>false]);
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
  'category'=>$category,'is_new'=>false,'is_live'=>(bool)($product['is_live']??true)
 ];
}
$products=$catalog;
$availableCategories=array_values(array_unique(array_column($products,'category')));
require __DIR__.'/includes/header.php';
?>
<section class="ikki-catalog-hero" aria-labelledby="page-banner-title">
 <div class="ikki-catalog-hero-inner">
  <nav class="page-banner-breadcrumb" aria-label="Breadcrumb"><a href="/">Home</a><span aria-hidden="true">›</span><span aria-current="page">The Collection</span></nav>
  <span class="home-kicker">THE HOUSE OF FOUR · IKKI</span>
  <h1 id="page-banner-title">Four signatures.<br><em>One identity.</em></h1>
  <p>Meet the four expressions that define the IKKI fragrance house. Each has its own character; together, they tell one story.</p>
  <span class="ikki-catalog-edition">LEGENDARY PROFILE <i>·</i> INFORMAL <i>·</i> AMANE <i>·</i> SASHA</span>
 </div>
 <div class="ikki-catalog-hero-side"><img src="/assets/images/collection-hero.svg" alt="IKKI signature perfume bottle" width="420" height="460" fetchpriority="high"><span>SCENT IS A FORM OF MEMORY</span><b>IKKI</b></div>
</section>
<section class="collection-catalog ikki-premium-catalog" aria-label="Fragrance collection">
 <div class="ikki-catalog-intro"><span>FOUR EXPRESSIONS · ONE HOUSE</span><p>A focused collection, created to be discovered one signature at a time.</p><?php if(count($databaseProducts)<4): ?><small class="ikki-preview-note">Some signatures are presented as collection previews. Contact IKKI to enquire about current availability.</small><?php endif; ?></div>
 <nav class="ikki-signature-index" aria-label="Explore the four IKKI signatures">
  <span>THE FOUR SIGNATURES</span>
  <?php foreach($products as $index=>$signature): ?>
   <a href="#signature-<?=e($signature["slug"])?>"><i>0<?=($index+1)?></i><?=e($signature["name"])?></a>
  <?php endforeach; ?>
 </nav>
 <div class="collection-grid ikki-signature-grid" id="collection-grid">
 <?php if($products): foreach($products as $n=>$p):
  $dbProduct=$databaseProducts[$p['slug']]??null;
  $productUrl=$dbProduct?'/product.php?slug='.rawurlencode($p['slug']):'';
  $detailUrl=$dbProduct?$productUrl:'https://wa.me/923094733789?text='.rawurlencode('Hello IKKI, I would like to enquire about '.$p['name'].'.');
  $signatureImages=['legendary-profile'=>'/assets/images/legendary-profile.svg','informal'=>'/assets/images/informal.svg','amane'=>'/assets/images/amane.svg','sasha'=>'/assets/images/sasha.svg'];
  $imagePath=trim((string)($p['image_path']??''))?:($signatureImages[$p['slug']]??'/assets/images/legendary-profile.svg');
  $variants=[];
  if($dbProduct){$variantQuery=db()->prepare("SELECT id,variant_name,volume_ml,price,stock_quantity FROM product_variants WHERE product_id=? AND is_active=1 ORDER BY volume_ml");$variantQuery->execute([(int)$dbProduct['id']]);$variants=$variantQuery->fetchAll();}
  $topNote=trim((string)($p['top_notes']??''));
  $heartNote=trim((string)($p['heart_notes']??''));
  $baseNote=trim((string)($p['base_notes']??''));
  $modalDescription=trim((string)($p['description']??''))?:'Discover the olfactive story of '.$p['name'].', an IKKI fragrance.';
 ?>
  <article id="signature-<?=e($p["slug"])?>" class="collection-card collection-reveal ikki-signature-card" data-signature="<?=e($p['slug'])?>" data-category="<?=e($p['category'])?>" data-is-new="<?=$p['is_new']?'true':'false'?>" data-price="<?=e((string)$p['price'])?>" data-product-id="<?=e((string)($dbProduct['id']??(8-$n)))?>">
   <div class="collection-card-image">
    <?php if($p['is_new']): ?><span class="collection-new-badge">New</span><?php endif; ?>
    <a class="ikki-card-image-link" href="<?=e($detailUrl)?>"><img src="<?=e($imagePath)?>" alt="<?=e($p['name'])?> fragrance bottle" width="800" height="1000" loading="lazy" decoding="async"></a>
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
    <h2><a href="<?=e($detailUrl)?>"><?=e($p['name'])?></a></h2>
    <p><?=e($p['notes'])?></p>
    <strong><?=$dbProduct?($p['price']!==null?money($p['price']):'Price on request'):'A SIGNATURE OF IKKI'?></strong><a class="ikki-card-discover" href="<?=$dbProduct?e($productUrl):'https://wa.me/923094733789?text='.rawurlencode('Hello IKKI, I would like to enquire about '.$p['name'].'.')?>"><?=$dbProduct?'DISCOVER THIS SCENT':'ENQUIRE ABOUT THIS SCENT'?> <span>↗</span></a>
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
    
    <div class="quick-view-notes" data-quick-notes hidden><span class="quick-view-label">THE OLFACTIVE STORY</span><div class="quick-view-note-pills"><span data-note-top hidden></span><span data-note-heart hidden></span><span data-note-base hidden></span></div></div>
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
