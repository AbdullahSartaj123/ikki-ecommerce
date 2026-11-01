<?php
require_once __DIR__.'/config/bootstrap.php';
$pageTitle='Our Collection — Ikki';
$description='Explore the Ikki fragrance collection: individual scent stories, composed to become part of your signature.';
$products=[];
try{
 $products=db()->query("SELECT p.id,p.name,p.slug,p.short_description,p.gender,MIN(v.price) price,(SELECT image_path FROM product_images WHERE product_id=p.id ORDER BY is_primary DESC,sort_order LIMIT 1) image_path FROM products p LEFT JOIN product_variants v ON v.product_id=p.id AND v.is_active=1 WHERE p.is_active=1 GROUP BY p.id ORDER BY p.is_featured DESC,p.name")->fetchAll();
}catch(Throwable $ex){error_log('[Ikki collection] '.$ex->getMessage());}
$genderLabel=static function($gender){
 $value=strtolower(trim((string)$gender));
 if(in_array($value,['female','women','woman','for her'],true))return 'For Her';
 if(in_array($value,['male','men','man','for him'],true))return 'For Him';
 return 'Unisex';
};
require __DIR__.'/includes/header.php';
?>
<section class="collection-page-hero">
 <span class="eyebrow">THE IKKI FRAGRANCE HOUSE / THE COLLECTION</span>
 <h1>Our <em>Collection.</em></h1>
 <p>Discover scent stories with their own character. Find the one that feels unmistakably yours.</p>
 <span class="collection-hero-rule" aria-hidden="true"></span>
</section>
<section class="collection-catalog" aria-label="Fragrance collection">
 <div class="collection-toolbar">
  <div class="collection-filters" role="group" aria-label="Filter fragrances by category">
   <button class="collection-filter is-active" type="button" data-filter="all" aria-pressed="true">All fragrances</button>
   <button class="collection-filter" type="button" data-filter="her" aria-pressed="false">For Her</button>
   <button class="collection-filter" type="button" data-filter="him" aria-pressed="false">For Him</button>
   <button class="collection-filter" type="button" data-filter="unisex" aria-pressed="false">Unisex</button>
  </div>
  <span class="collection-count"><b><?=count($products)?></b> FRAGRANCES</span>
 </div>
 <div class="collection-grid" id="collection-grid">
 <?php if($products): foreach($products as $n=>$p):
  $category=$genderLabel($p['gender']??'Unisex');
  $filterKey=$category==='For Her'?'her':($category==='For Him'?'him':'unisex');
 ?>
  <article class="collection-card reveal" data-category="<?=e($filterKey)?>">
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
