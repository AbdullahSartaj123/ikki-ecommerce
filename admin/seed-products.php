<?php
require_once __DIR__.'/../config/bootstrap.php';
require_admin();
$catalogue=[
 ['Noor','noor','IKK-NOOR-01','A luminous floral-amber concept with a soft, golden finish.','Noor is imagined as first light across warm stone: bright, graceful and quietly memorable. A polished scent concept for everyday rituals and considered evenings.','Floral Amber','Bergamot, pear accord','Jasmine, orange blossom accord','Soft amber, musk accord',3490,'/assets/images/noor.svg'],
 ['Sahra','sahra','IKK-SAHRA-01','Warm woods and amber-inspired character, made for golden-hour moods.','Sahra takes its cue from the last light of the day: a warm, enveloping fragrance concept with a confident woody character.','Woody Amber','Saffron accord, mandarin','Rose accord, cedar','Amber accord, sandalwood',3790,'/assets/images/sahra.svg'],
 ['Velvet Dusk','velvet-dusk','IKK-VELVET-01','A deep, smooth evening concept with a modern, velvety mood.','Velvet Dusk is a study in contrast: dark fruit-like brightness meets a soft, textured woody base. Designed as an expressive evening scent concept.','Ambery Woods','Blackcurrant accord, pink pepper','Iris accord, rose','Vanilla, patchouli accord',3990,'/assets/images/velvet-dusk.svg'],
 ['Azure 04','azure-04','IKK-AZURE-01','A fresh, airy aromatic concept with a clean, modern finish.','Azure 04 evokes open air after rain: crisp citrus, aromatic clarity and a calm woody foundation. A versatile daytime fragrance concept.','Fresh Aromatic','Bergamot, marine accord','Neroli, aromatic herbs','Cedar, clean musk accord',3290,'/assets/images/azure-04.svg']
];
$done=false;$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();
 try{
  db()->beginTransaction();
  db()->exec("INSERT INTO categories(name,slug,sort_order,is_active) VALUES('Signature fragrances','signature-fragrances',1,1) ON DUPLICATE KEY UPDATE name=VALUES(name),is_active=1");
  $cat=(int)db()->query("SELECT id FROM categories WHERE slug='signature-fragrances'")->fetchColumn();
  $product=db()->prepare("INSERT INTO products(category_id,name,slug,sku,short_description,description,fragrance_family,concentration,gender,top_notes,heart_notes,base_notes,is_featured,is_active) VALUES(?,?,?,?,?,?,?,'Eau de Parfum','Unisex',?,?,?,1,1) ON DUPLICATE KEY UPDATE category_id=VALUES(category_id),name=VALUES(name),short_description=VALUES(short_description),description=VALUES(description),fragrance_family=VALUES(fragrance_family),top_notes=VALUES(top_notes),heart_notes=VALUES(heart_notes),base_notes=VALUES(base_notes),is_featured=1,is_active=1");
  $variant=db()->prepare("INSERT INTO product_variants(product_id,variant_name,volume_ml,sku,price,stock_quantity,is_active) VALUES(?,'50 ml',50,?,?,20,1) ON DUPLICATE KEY UPDATE price=VALUES(price),is_active=1");
  $image=db()->prepare("SELECT id FROM product_images WHERE product_id=? AND image_path=? LIMIT 1");
  $addImage=db()->prepare("INSERT INTO product_images(product_id,image_path,alt_text,sort_order,is_primary) VALUES(?,?,?,1,1)");
  foreach($catalogue as $p){
   $product->execute([$cat,$p[0],$p[1],$p[2],$p[3],$p[4],$p[5],$p[6],$p[7],$p[8],$p[9]]);
   $q=db()->prepare("SELECT id FROM products WHERE slug=?");$q->execute([$p[1]]);$pid=(int)$q->fetchColumn();
   $variant->execute([$pid,'IKK-'.strtoupper(str_replace('-','',$p[1])).'-50',$p[10]]);
   $image->execute([$pid,$p[11]]);if(!$image->fetch())$addImage->execute([$pid,$p[11],'Ikki '.$p[0].' signature bottle concept']);
  }
  db()->commit();$done=true;
 }catch(Throwable $ex){if(db()->inTransaction())db()->rollBack();$error='Could not install the collection: '.$ex->getMessage();}
}
$pageTitle='Install launch collection — Ikki';require __DIR__.'/../includes/header.php';
?>
<div class="admin-shell"><span class="eyebrow">CATALOGUE SETUP</span><h1 class="page-title">The launch collection.</h1><nav class="admin-nav"><a href="/admin/">Overview</a><a href="/admin/products.php">Products</a><a href="/admin/orders.php">Orders</a><a href="/admin/settings.php">Settings</a></nav>
<?php if($done):?><div class="notice">The four concept fragrances have been added or refreshed. Visit the storefront to preview them.</div><?php elseif($error):?><div class="notice"><?=e($error)?></div><?php endif;?>
<p class="muted">Install four proposed fragrance concepts with custom local SVG bottle artwork and one 50 ml variant each. This can be safely run again without duplicating products.</p>
<div class="stats"><?php foreach($catalogue as $n=>$p):?><div class="stat"><span class="muted">0<?=($n+1)?> / PROPOSED</span><b style="font-size:30px"><?=e($p[0])?></b><span class="muted"><?=money($p[10])?> · 50 ml</span></div><?php endforeach;?></div>
<div class="notice">These are sample product concepts, notes, prices and starter stock—not confirmed manufactured goods or final retail prices. Review every product and set actual inventory before accepting customer orders.</div>
<form method="post" class="site-form"><?=csrf_field()?><button class="button-gold" type="submit">Install / refresh four concepts ↗</button></form>
<p><a class="text-link" href="/admin/products.php">Open product manager ↗</a></p></div>
<?php require __DIR__.'/../includes/footer.php';?>