<?php
require_once __DIR__.'/config/bootstrap.php';
$slug=trim((string)($_GET['slug']??''));
$s=db()->prepare("SELECT * FROM products WHERE slug=? AND is_active=1 LIMIT 1");
$s->execute([$slug]);$product=$s->fetch();
if(!$product){
 http_response_code(404);$pageTitle='Fragrance not found — IKKI';require __DIR__.'/includes/header.php';
 ?><main class="ikki-product-not-found"><span class="eyebrow">IKKI / THE SIGNATURE COLLECTION</span><h1>This scent is elusive.</h1><a class="button-gold" href="/shop.php">Return to the collection ↗</a></main><?php
 require __DIR__.'/includes/footer.php';exit;
}
$v=db()->prepare("SELECT * FROM product_variants WHERE product_id=? AND is_active=1 ORDER BY volume_ml");
$v->execute([$product['id']]);
$variants=array_values(array_filter($v->fetchAll(),static fn($variant)=>(int)$variant['stock_quantity']>0));
$i=db()->prepare("SELECT * FROM product_images WHERE product_id=? ORDER BY is_primary DESC,sort_order");
$i->execute([$product['id']]);$images=$i->fetchAll();
$slugImage='/assets/images/'.preg_replace('/[^a-z0-9-]/','',$slug).'.svg';
$fallbackImage=is_file(__DIR__.$slugImage)?$slugImage:'/assets/images/legendary-profile.svg';
$image=$images[0]['image_path']??$fallbackImage;
$pageTitle=$product['name'].' — IKKI';
$description=trim((string)($product['short_description']??''))?:'Discover '.$product['name'].', part of the IKKI signature fragrance collection.';
$productSiteUrl=rtrim(defined('APP_URL')?APP_URL:'https://ikki.pk','/');
$productCanonical=$productSiteUrl.'/product.php?slug='.rawurlencode($slug);
$productImageUrl=preg_match('~^https?://~i',(string)$image)?$image:$productSiteUrl.'/'.ltrim((string)$image,'/');
$productOffers=[];
foreach($variants as $variant){
 $productOffers[]=[
  '@type'=>'Offer','priceCurrency'=>'PKR','price'=>(float)$variant['price'],
  'availability'=>'https://schema.org/InStock','url'=>$productCanonical,
  'itemCondition'=>'https://schema.org/NewCondition'
 ];
}
$productStructuredData=[
 '@context'=>'https://schema.org','@type'=>'Product',
 'name'=>(string)$product['name'],'description'=>$description,'image'=>[$productImageUrl],
 'brand'=>['@type'=>'Brand','name'=>'IKKI'],
 'category'=>(string)($product['fragrance_family']??'Fragrance')
];
if($productOffers)$productStructuredData['offers']=$productOffers;
$editionNumber=['legendary-profile'=>'01','informal'=>'02','amane'=>'03','sasha'=>'04'][$slug]??'—';
$whatsappProductUrl='https://wa.me/923094733789?text='.rawurlencode('Hello IKKI, I would like to enquire about '.$product['name'].' (IKKI Nº '.$editionNumber.').');
require __DIR__.'/includes/header.php';
?>
<?php
$signatureEditions=[
 "legendary-profile"=>["number"=>"01","edition"=>"THE BOLD EXPRESSION","tone"=>"azure"],
 "informal"=>["number"=>"02","edition"=>"THE EVERYDAY EXPRESSION","tone"=>"olive"],
 "amane"=>["number"=>"03","edition"=>"THE CONTEMPORARY EXPRESSION","tone"=>"aqua"],
 "sasha"=>["number"=>"04","edition"=>"THE EXPRESSIVE SIGNATURE","tone"=>"rose"]
];
$signature=$signatureEditions[$slug]??["number"=>"—","edition"=>"AN IKKI SIGNATURE","tone"=>"classic"];
?>
<main class="ikki-product-page ikki-campaign ikki-campaign--<?=e($signature["tone"])?>">
 <script type="application/ld+json"><?=json_encode($productStructuredData,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT)?></script>
 <nav class="ikki-product-breadcrumb" aria-label="Breadcrumb"><a href="/">Home</a><span> / </span><a href="/shop.php">The Collection</a><span> / </span><span aria-current="page"><?=e($product['name'])?></span></nav>
 <section class="ikki-product-layout" data-product-gallery>
  <div class="ikki-product-gallery">
   <div class="ikki-product-main-image">
    <span class="ikki-product-object">IKKI / SIGNATURE Nº <?=e($signature["number"])?></span>
    <img data-product-main-image src="<?=e($image)?>" alt="<?=e($product['name'])?> fragrance bottle" width="900" height="1125" loading="eager" fetchpriority="high" decoding="async">
    <span class="ikki-product-image-caption">AN IKKI ORIGINAL <i>·</i> SIGNATURE <?=e($signature["number"])?></span>
   </div>
   <?php if(count($images)>1): ?><div class="ikki-product-thumbnails" aria-label="Product images">
    <?php foreach($images as $index=>$im): ?><button type="button" class="ikki-product-thumb<?=$index===0?' is-active':''?>" data-product-thumb data-src="<?=e($im['image_path'])?>" data-alt="<?=e($im['alt_text']??$product['name'].' fragrance bottle')?>" aria-label="View product image <?=($index+1)?>" aria-pressed="<?=$index===0?'true':'false'?>"><img src="<?=e($im['image_path'])?>" alt="" width="120" height="150" loading="lazy" decoding="async"></button><?php endforeach; ?>
   </div><?php endif; ?>
  </div>
  <div class="ikki-product-information">
   <span class="eyebrow">THE HOUSE OF IKKI <i></i> <?=e($signature["edition"])?></span>
   <span class="ikki-campaign-edition">SIGNATURE <?=e($signature["number"])?> <span>—</span> FOUR EXPRESSIONS, ONE HOUSE</span>
   <h1><?=e($product['name'])?></h1>
   <?php if(trim((string)($product['short_description']??''))!==''): ?><p class="ikki-product-deck"><?=e($product['short_description'])?></p><?php endif; ?>
   <div class="ikki-product-price" aria-live="polite" aria-atomic="true"><?=$variants?money($variants[0]['price']):'Price on request'?></div>
   <div class="ikki-product-rule"></div>
   <?php if(!empty($product['description'])): ?><div class="ikki-product-description"><?=nl2br(e($product['description']))?></div><?php endif; ?>
   <?php if($variants): ?>
   <form class="ikki-product-purchase" method="post" action="/actions/cart-add.php">
    <?=csrf_field()?>
    <input type="hidden" name="product_id" value="<?=(int)$product['id']?>">
    <label class="ikki-product-label" for="variant">CHOOSE YOUR SIZE</label>
    <div class="ikki-product-select-wrap"><select id="variant" name="variant_id" required><?php foreach($variants as $index=>$variant): ?><option value="<?=(int)$variant['id']?>" data-price="<?=e((string)$variant['price'])?>" <?=$index===0?'selected':''?>><?=e($variant['variant_name'])?> · <?=money($variant['price'])?></option><?php endforeach; ?></select><span aria-hidden="true">⌄</span></div>
    <div class="ikki-product-purchase-row"><label class="ikki-product-quantity">QUANTITY <input type="number" name="quantity" value="1" min="1" max="5" inputmode="numeric"></label><button class="ikki-product-add ikki-btn--primary" type="submit">ADD TO BAG <span>↗</span></button></div>
   </form>
   <?php else: ?><div class="ikki-product-unavailable"><span>THIS SIGNATURE IS NOT CURRENTLY AVAILABLE TO ORDER.</span><a class="ikki-product-enquiry-link" href="<?=e($whatsappProductUrl)?>" target="_blank" rel="noopener noreferrer">ENQUIRE ABOUT <?=e(strtoupper($product['name']))?> <span aria-hidden="true">↗</span></a></div><?php endif; ?>
   <?php
    $olfactiveNotes=[
     'top_notes'=>['label'=>'01 / OPENING','value'=>trim((string)($product['top_notes']??''))],
     'heart_notes'=>['label'=>'02 / HEART','value'=>trim((string)($product['heart_notes']??''))],
     'base_notes'=>['label'=>'03 / DRY DOWN','value'=>trim((string)($product['base_notes']??''))]
    ];
    $hasOlfactiveNotes=(bool)array_filter($olfactiveNotes,static fn($note)=>$note['value']!=='');
    $productAttributes=[
     'FRAGRANCE FAMILY'=>trim((string)($product['fragrance_family']??'')),
     'CONCENTRATION'=>trim((string)($product['concentration']??'')),
     'CHARACTER'=>trim((string)($product['gender']??''))
    ];
    $productAttributes=array_filter($productAttributes,static fn($value)=>$value!=='');
   ?>
   <?php if($hasOlfactiveNotes): ?>
   <div class="ikki-product-notes">
    <span class="ikki-product-label">THE OLFACTIVE STORY</span>
    <div class="ikki-note-pyramid">
     <?php foreach($olfactiveNotes as $note): if($note['value']==='')continue; ?>
     <div><span><?=e($note['label'])?></span><p><?=e($note['value'])?></p></div>
     <?php endforeach; ?>
    </div>
   </div>
   <?php endif; ?>
   <?php if($productAttributes): ?>
   <div class="ikki-product-details">
    <?php foreach($productAttributes as $label=>$value): ?>
    <div><span><?=e($label)?></span><b><?=e($value)?></b></div>
    <?php endforeach; ?>
   </div>
   <?php endif; ?>
   <div class="ikki-product-help"><span class="ikki-product-help-mark" aria-hidden="true">IKKI</span><div><b>Need a little guidance?</b><p>Speak with us about <?=e($product['name'])?> before placing your order.</p></div><a href="<?=e($whatsappProductUrl)?>" target="_blank" rel="noopener noreferrer">ASK IKKI <span aria-hidden="true">↗</span></a></div><p class="ikki-product-disclaimer">Fragrance notes and characteristics are presented as product descriptions. Please refer to the final product packaging for ingredient and formulation details.</p>
  </div>
 </section>
 <nav class="ikki-product-signature-nav" aria-label="Explore the four IKKI signatures"><span>THE HOUSE OF FOUR</span><a href="/shop.php">VIEW ALL FOUR <span aria-hidden="true">↗</span></a></nav>
 <section class="ikki-product-after ikki-campaign-end">
  <span class="eyebrow">FOUR SIGNATURES · ONE HOUSE</span>
  <h2>Discover the<br><em>IKKI point of view.</em></h2>
  <p>Four individual expressions. One fragrance house. Find the signature that speaks to you.</p>
  <a class="ikki-btn--secondary" href="/shop.php">MEET THE OTHER SIGNATURES <span>↗</span></a>
 </section>
</main>
<script>
document.addEventListener('DOMContentLoaded',function(){
 const gallery=document.querySelector('[data-product-gallery]');
 if(!gallery)return;
 const main=gallery.querySelector('[data-product-main-image]');
 gallery.querySelectorAll('[data-product-thumb]').forEach(function(button){
  button.addEventListener('click',function(){
   main.src=button.dataset.src;main.alt=button.dataset.alt||main.alt;
   gallery.querySelectorAll('[data-product-thumb]').forEach(function(item){item.classList.remove('is-active');item.setAttribute('aria-pressed','false');});
   button.classList.add('is-active');button.setAttribute('aria-pressed','true');
  });
 });
 const select=gallery.querySelector('#variant');
 const price=gallery.querySelector('.ikki-product-price');
 if(select&&price)select.addEventListener('change',function(){
  const option=select.options[select.selectedIndex];
  const formatted=option.textContent.split('·').slice(-1)[0].trim();
  price.textContent=formatted;
 });
});
</script>
<?php require __DIR__.'/includes/footer.php'; ?>
