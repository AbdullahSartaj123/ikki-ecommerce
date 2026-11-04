<?php
require_once __DIR__.'/../config/bootstrap.php';
require_admin();
$pid=(int)($_GET['product_id']??0);
$s=db()->prepare('SELECT name FROM products WHERE id=?');$s->execute([$pid]);$product=$s->fetch();
if(!$product){http_response_code(404);exit('Product not found');}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();
 $action=(string)($_POST['action']??'upload');
 if($action==='primary'||$action==='delete'){
  $imageId=(int)($_POST['image_id']??0);
  $q=db()->prepare('SELECT id,image_path FROM product_images WHERE id=? AND product_id=?');$q->execute([$imageId,$pid]);$image=$q->fetch();
  if(!$image){$error='Image not found.';}
  elseif($action==='primary'){
   db()->beginTransaction();
   try{db()->prepare('UPDATE product_images SET is_primary=0 WHERE product_id=?')->execute([$pid]);db()->prepare('UPDATE product_images SET is_primary=1 WHERE id=? AND product_id=?')->execute([$imageId,$pid]);db()->commit();header('Location: /admin/product-images.php?product_id='.$pid.'&saved=1');exit;}
   catch(Throwable $ex){if(db()->inTransaction())db()->rollBack();error_log('[IKKI image primary] '.$ex->getMessage());$error='Unable to update the primary image.';}
  }else{
   db()->beginTransaction();
   try{
    db()->prepare('DELETE FROM product_images WHERE id=? AND product_id=?')->execute([$imageId,$pid]);
    if((int)$imageId>0){$remaining=db()->prepare('SELECT id FROM product_images WHERE product_id=? ORDER BY id DESC LIMIT 1');$remaining->execute([$pid]);$fallback=$remaining->fetchColumn();if($fallback)db()->prepare('UPDATE product_images SET is_primary=1 WHERE id=?')->execute([$fallback]);}
    db()->commit();
    $prefix='/storage/uploads/';$path=(string)$image['image_path'];$base=basename($path);$file=__DIR__.'/../storage/uploads/'.$base;
    if(str_starts_with($path,$prefix)&&preg_match('/^[a-f0-9]{32}\.(jpg|png|webp)$/',$base)&&is_file($file)){@unlink($file);}
    header('Location: /admin/product-images.php?product_id='.$pid.'&deleted=1');exit;
   }catch(Throwable $ex){if(db()->inTransaction())db()->rollBack();error_log('[IKKI image delete] '.$ex->getMessage());$error='Unable to delete this image.';}
  }
 }elseif($action==='upload'){
  if(!isset($_FILES['image'])||$_FILES['image']['error']!==UPLOAD_ERR_OK){$error='Choose an image file.';}
  else{
   $tmp=$_FILES['image']['tmp_name'];$size=(int)$_FILES['image']['size'];$mime=is_uploaded_file($tmp)?(new finfo(FILEINFO_MIME_TYPE))->file($tmp):'';
   $allowed=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
   if($size<1||$size>4*1024*1024||!isset($allowed[$mime])){$error='Only valid JPG, PNG or WebP images up to 4MB are allowed.';}
   else{
    $dir=__DIR__.'/../storage/uploads';
    if(!is_dir($dir)&&!mkdir($dir,0755,true)&&!is_dir($dir)){$error='Upload directory is unavailable.';}
    else{
     $filename=bin2hex(random_bytes(16)).'.'.$allowed[$mime];$path='/storage/uploads/'.$filename;
     if(move_uploaded_file($tmp,$dir.'/'.$filename)){
      $alt=mb_substr(trim((string)($_POST['alt_text']??$product['name'])),0,255);
      $makePrimary=empty($_POST['not_primary']);
      try{
       db()->beginTransaction();
       if($makePrimary)db()->prepare('UPDATE product_images SET is_primary=0 WHERE product_id=?')->execute([$pid]);
       db()->prepare('INSERT INTO product_images(product_id,image_path,alt_text,sort_order,is_primary) VALUES(?,?,?,?,?)')->execute([$pid,$path,$alt,0,$makePrimary?1:0]);
       db()->commit();header('Location: /admin/product-images.php?product_id='.$pid.'&uploaded=1');exit;
      }catch(Throwable $ex){if(db()->inTransaction())db()->rollBack();@unlink($dir.'/'.$filename);error_log('[IKKI image upload] '.$ex->getMessage());$error='Image uploaded but could not be added to the gallery.';}
     }else{$error='Unable to store the uploaded image.';}
    }
   }
  }
 }else{$error='Invalid image action.';}
}
$s=db()->prepare('SELECT * FROM product_images WHERE product_id=? ORDER BY is_primary DESC,sort_order,id DESC');$s->execute([$pid]);$images=$s->fetchAll();
$pageTitle='Product images — Ikki';require __DIR__.'/../includes/header.php';
?>
<div class="admin-shell"><h1 class="page-title">Images · <?=e($product['name'])?></h1><nav class="admin-nav"><a href="/admin/">Dashboard</a><a href="/admin/products.php">Products</a><a href="/admin/product-edit.php?id=<?=$pid?>">Edit fragrance</a></nav>
<?php if($error):?><p class="notice" role="alert"><?=e($error)?></p><?php endif;?><?php if(isset($_GET['uploaded'])):?><p class="notice" role="status">Image uploaded.</p><?php elseif(isset($_GET['saved'])):?><p class="notice" role="status">Primary image updated.</p><?php elseif(isset($_GET['deleted'])):?><p class="notice" role="status">Image deleted.</p><?php endif;?>
<form method="post" enctype="multipart/form-data" class="site-form"><?=csrf_field()?><input type="hidden" name="action" value="upload"><label class="field-label">Image (JPG, PNG, WebP; max 4MB)</label><input class="field" type="file" name="image" accept="image/jpeg,image/png,image/webp" required><label class="field-label">Alt text</label><input class="field" name="alt_text" maxlength="255" value="<?=e($product['name'])?>"><label><input type="checkbox" name="not_primary" value="1"> Keep current primary image</label><p><button class="btn" type="submit">Upload image</button></p></form>
<div class="product-grid"><?php foreach($images as $im):?><div><img src="<?=e($im['image_path'])?>" alt="<?=e($im['alt_text'])?>" loading="lazy" style="width:100%;aspect-ratio:1;object-fit:cover"><p class="muted"><?=e($im['alt_text'])?> <?=((int)$im['is_primary']?'· Primary':'')?></p><div style="display:flex;gap:8px;flex-wrap:wrap"><?php if(!(int)$im['is_primary']):?><form method="post"><?=csrf_field()?><input type="hidden" name="action" value="primary"><input type="hidden" name="image_id" value="<?=(int)$im['id']?>"><button class="btn btn-outline" type="submit">Set primary</button></form><?php endif;?><form method="post" onsubmit="return confirm('Delete this product image?')"><?=csrf_field()?><input type="hidden" name="action" value="delete"><input type="hidden" name="image_id" value="<?=(int)$im['id']?>"><button class="btn btn-outline" type="submit">Delete</button></form></div></div><?php endforeach;?></div>
</div>
<?php require __DIR__.'/../includes/footer.php';?>