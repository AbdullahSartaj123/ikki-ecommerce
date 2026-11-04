<?php
require_once __DIR__.'/../config/bootstrap.php';
require_admin();
$pid=(int)($_GET['product_id']??0);
$p=db()->prepare('SELECT name FROM products WHERE id=?');$p->execute([$pid]);$product=$p->fetch();
if(!$product){http_response_code(404);exit('Product not found');}
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();
 $variantId=(int)($_POST['variant_id']??0);
 $label=trim((string)($_POST['variant_name']??''));$sku=trim((string)($_POST['sku']??''));
 $price=filter_var($_POST['price']??null,FILTER_VALIDATE_FLOAT);
 $volumeRaw=trim((string)($_POST['volume_ml']??''));$volume=$volumeRaw===''?null:filter_var($volumeRaw,FILTER_VALIDATE_FLOAT);
 $stock=filter_var($_POST['stock_quantity']??null,FILTER_VALIDATE_INT);
 $active=isset($_POST['is_active'])?1:0;
 if($label===''||mb_strlen($label)>80||$sku===''||mb_strlen($sku)>80||$price===false||$price<=0||($volumeRaw!==''&&($volume===false||$volume<=0))||$stock===false||$stock<0){
  $err='Enter a valid size, SKU, positive price, optional positive volume and non-negative stock.';
 }else{
  try{
   if($variantId){
    $q=db()->prepare('UPDATE product_variants SET variant_name=?,volume_ml=?,sku=?,price=?,stock_quantity=?,is_active=? WHERE id=? AND product_id=?');
    $q->execute([$label,$volume,$sku,$price,$stock,$active,$variantId,$pid]);
    if(!$q->rowCount()){ $check=db()->prepare('SELECT id FROM product_variants WHERE id=? AND product_id=?');$check->execute([$variantId,$pid]);if(!$check->fetch())throw new RuntimeException('Variant not found.');}
   }else{
    $q=db()->prepare('INSERT INTO product_variants(product_id,variant_name,volume_ml,sku,price,stock_quantity,is_active) VALUES(?,?,?,?,?,?,?)');
    $q->execute([$pid,$label,$volume,$sku,$price,$stock,$active]);
   }
   header('Location: /admin/variants.php?product_id='.$pid.'&saved=1');exit;
  }catch(Throwable $ex){error_log('[IKKI variant save] '.$ex->getMessage());$err='Could not save this size. Check that its SKU is unique and try again.';}
 }
}
$editId=(int)($_GET['edit']??0);$editing=null;
if($editId){$q=db()->prepare('SELECT * FROM product_variants WHERE id=? AND product_id=?');$q->execute([$editId,$pid]);$editing=$q->fetch();if(!$editing){http_response_code(404);exit('Variant not found');}}
$rows=db()->prepare('SELECT * FROM product_variants WHERE product_id=? ORDER BY id DESC');$rows->execute([$pid]);$rows=$rows->fetchAll();
$pageTitle='Product sizes — Ikki';require __DIR__.'/../includes/header.php';
?>
<div class="admin-shell"><h1 class="page-title">Sizes · <?=e($product['name'])?></h1><nav class="admin-nav"><a href="/admin/">Dashboard</a><a href="/admin/products.php">Products</a><a href="/admin/product-edit.php?id=<?=$pid?>">Edit fragrance</a></nav>
<?php if($err):?><p class="notice" role="alert"><?=e($err)?></p><?php endif;?><?php if(isset($_GET['saved'])):?><p class="notice" role="status">Variant saved.</p><?php endif;?>
<h2><?= $editing?'Edit size':'Add a size'?></h2>
<form method="post" class="site-form"><?=csrf_field()?><input type="hidden" name="variant_id" value="<?=(int)($editing['id']??0)?>">
<div class="form-grid"><div><label class="field-label">Size label</label><input class="field" name="variant_name" maxlength="80" value="<?=e($editing['variant_name']??'')?>" placeholder="50 ml" required></div><div><label class="field-label">Volume in ml</label><input class="field" name="volume_ml" type="number" step="0.01" min="0.01" value="<?=e((string)($editing['volume_ml']??''))?>"></div><div><label class="field-label">Variant SKU</label><input class="field" name="sku" maxlength="80" value="<?=e($editing['sku']??'')?>" required></div><div><label class="field-label">Price (PKR)</label><input class="field" name="price" type="number" step="0.01" min="0.01" value="<?=e((string)($editing['price']??''))?>" required></div><div><label class="field-label">Stock quantity</label><input class="field" name="stock_quantity" type="number" min="0" step="1" value="<?=e((string)($editing['stock_quantity']??0))?>" required></div></div>
<?php if($editing):?><p><label><input type="checkbox" name="is_active" value="1" <?=((int)$editing['is_active']?'checked':'')?>> Available for sale</label></p><?php else:?><input type="hidden" name="is_active" value="1"><?php endif;?>
<p><button class="btn" type="submit"><?= $editing?'Save changes':'Add size'?></button> <?php if($editing):?><a class="btn btn-outline" href="/admin/variants.php?product_id=<?=$pid?>">Cancel</a><?php endif;?></p></form>
<h2>Current sizes and inventory</h2><div style="overflow-x:auto"><table class="cart-table"><thead><tr><th>Variant</th><th>SKU</th><th>Price</th><th>Stock</th><th>Availability</th><th></th></tr></thead><tbody>
<?php foreach($rows as $r):?><tr><td><?=e($r['variant_name'])?></td><td><?=e($r['sku'])?></td><td><?=money($r['price'])?></td><td><?=(int)$r['stock_quantity']?></td><td><?=(int)$r['is_active']?'Active':'Hidden'?></td><td><a href="/admin/variants.php?product_id=<?=$pid?>&edit=<?=(int)$r['id']?>">Edit</a></td></tr><?php endforeach;?>
<?php if(!$rows):?><tr><td colspan="6">No sizes added yet.</td></tr><?php endif;?></tbody></table></div></div>
<?php require __DIR__.'/../includes/footer.php';?>