<?php
require_once __DIR__.'/config/bootstrap.php';
$pageTitle='Our Collection — Ikki';
$description='Explore the Ikki fragrance collection: individual scent stories, composed to become part of your signature.';
$products=[];
try{
 $products=db()->query("SELECT p.id,p.name,p.slug,p.short_description,p.gender,MIN(v.price) price,(SELECT image_path FROM product_images WHERE product_id=p.id ORDER BY is_primary DESC,sort_order LIMIT 1) image_path FROM products p LEFT JOIN product_variants v ON v.product_id=p.id AND v.is_active=1 WHERE p.is_active=1 GROUP BY p.id ORDER BY p.is_featured DESC,p.name")->fetchAll();
}catch(Throwable $ex){error_log('[Ikki collection] '.$ex->getMessage());}
$newArrivalIds=[];\nif($products){$newest=$products;usort($newest,static fn($a,$b)=>(int)$b['id']<=>(int)$a['id']);$newArrivalIds=array_column(array_slice($newest,0,4),'id');}\n$genderLabel=static function($gender){
 $value=strtolower(trim((string)$gender));
 if(in_array($value,['female','women','woman','for her'],true))return 'For Her';
 if(in_array($value,['male','men','man','for him'],true))return 'For Him';
 return 'Unisex';
};
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
 <?php if($products): foreach($products as $n=>$p):
  $category=$genderLabel($p['gender']??'Unisex');
  $filterKey=$category==='For Her'?'her':($category==='For Him'?'him':'unisex');\n  $isNew=in_array((int)$p['id'],$newArrivalIds,true);
 ?>
  <article class="collection-card reveal" data-category="<?=e($filterKey)?>" data-is-new="<?= $isNew?'true':'false' ?>" data-price="<?=e((string)($p['price']??0)) ?>" data-product-id="<?=e((string)$p['id']) ?>">
   <a class="collection-card-image" href="/product.php?slug=<?=e($p['slug'])?>" aria-label="View <?=e($p['name'])?>">
    <span class="collection-card-index"><?=str_pad((string)($n+1),2,'0',STR_PAD_LEFT)?></span>
    <img src="<?=e($p['image_path']?:'/assets/images/noor.svg')?>" alt="<?=e($p['name'])?> fragrance bottle" loading="lazy">
    <span class="collection-card-discover">DISCOVER THIS SCENT <b>↗</b></span>
   </a>
   <div class="collection-card-info">
    <div class="collection-card-heading"><span><?=e(strtoupper($category))?></span><h2><?=e($p['name'])?></h2><p><?=e($p['short_description']??'A signature fragrance by Ikki.')?></p></div>
    <div class="collection-card-bottom"><strong><?=$p['price']!==null?money($p['price']):'PRICE ON REQUEST'?></strong><a href="/product.php?slug=<?=e($p['slug'])?>">VIEW DETAILS <span>↗</span></a></div>
   </div>
  </article>
 <?php endforeach; else: ?>
  <div class="collection-empty"><span class="eyebrow">A MOMENT OF ANTICIPATION</span><h2>Something beautiful<br>is being <em>composed.</em></h2><p>Our fragrances will be here soon. In the meantime, we'd love to hear from you.</p><a class="button-gold" href="/contact.php">Contact Ikki <span>↗</span></a></div>
 <?php endif; ?>
 </div>
 <p class="collection-no-results" id="collection-no-results" hidden>No fragrances in this category just yet.</p>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>
