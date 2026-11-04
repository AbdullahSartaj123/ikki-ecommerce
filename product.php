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
$pageTitle=$product['name'].' — IKKI';$description=$product['short_description'];
require __DIR__.'/includes/header.php';
?>
<main class="ikki-product-page">
 <nav class="ikki-product-breadcrumb" aria-label="Breadcrumb"><a href="/">Home</a><span> / </span><a href="/shop.php">The Collection</a><span> / </span><span aria-current="page"><?=e($product['name'])?></span></nav>
 <section class="ikki-product-layout" data-product-gallery>
  <div class="ikki-product-gallery">
   <div class="ikki-product-main-image">
    <span class="ikki-product-object">IKKI / OBJECT Nº <?=str_pad((string)(int)$product['id'],2,'0',STR_PAD_LEFT)?></span>
    <img data-product-main-image src="<?=e($image)?>" alt="<?=e($product['name'])?> fragrance bottle" width="900" height="1125" loading="eager" fetchpriority="high" decoding="async">
    <span class="ikki-product-image-caption">THE SIGNATURE COLLECTION</span>
   </div>
   <?php if(count($images)>1): ?><div class="ikki-product-thumbnails" aria-label="Product images">
    <?php foreach($images as $index=>$im): ?><button type="button" class="ikki-product-thumb<?=$index===0?' is-active':''?>" data-product-thumb data-src="<?=e($im['image_path'])?>" data-alt="<?=e($im['alt_text']??$product['name'].' fragrance bottle')?>" aria-label="View product image <?=($index+1)?>" aria-pressed="<?=$index===0?'true':'false'?>"><img src="<?=e($im['image_path'])?>" alt="" width="120" height="150" loading="lazy" decoding="async"></button><?php endforeach; ?>
   </div><?php endif; ?>
  </div>
  <div class="ikki-product-information">
   <span class="eyebrow">THE IKKI SIGNATURE COLLECTION</span>
   <h1><?=e($product['name'])?></h1>
   <p class="ikki-product-deck"><?=e($product['short_description']??'A fragrance composed to become part of your story.')?></p>
   <div class="ikki-product-price"><?=$variants?money($variants[0]['price']):'Price on request'?></div>
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
   <?php else: ?><div class="ikki-product-unavailable">Currently unavailable. <a href="/contact.php">Contact us for availability.</a></div><?php endif; ?>
   <div class="ikki-product-notes">
    <span class="ikki-product-label">THE OLFACTIVE STORY</span>
    <div class="ikki-note-pyramid">
     <div><span>01 / OPENING</span><p><?=e($product['top_notes']??'To be confirmed')?></p></div>
     <div><span>02 / HEART</span><p><?=e($product['heart_notes']??'To be confirmed')?></p></div>
     <div><span>03 / DRY DOWN</span><p><?=e($product['base_notes']??'To be confirmed')?></p></div>
    </div>
   </div>
   <div class="ikki-product-details">
    <div><span>FRAGRANCE FAMILY</span><b><?=e($product['fragrance_family']??'Signature')?></b></div>
    <div><span>CONCENTRATION</span><b><?=e($product['concentration']??'To be confirmed')?></b></div>
    <div><span>CHARACTER</span><b><?=e($product['gender']??'Unisex')?></b></div>
   </div>
   <div class="ikki-product-help"><span class="ikki-product-help-mark" aria-hidden="true">IKKI</span><div><b>Need a little guidance?</b><p>Speak with us about this fragrance before placing your order.</p></div><a href="https://wa.me/923094733789" target="_blank" rel="noopener noreferrer">ASK IKKI <span aria-hidden="true">↗</span></a></div><p class="ikki-product-disclaimer">Fragrance notes and characteristics are presented as product descriptions. Please refer to the final product packaging for ingredient and formulation details.</p>
  </div>
 </section>
 <section class="ikki-product-after"><span class="eyebrow">THE IKKI SIGNATURE COLLECTION</span><h2>One more scent<br><em>to discover.</em></h2><a class="ikki-btn--secondary" href="/shop.php">EXPLORE THE COLLECTION <span>↗</span></a></section>
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
