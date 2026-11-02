<?php
require_once __DIR__.'/../config/bootstrap.php';
header('Content-Type: application/json; charset=utf-8');
$cart=$_SESSION['cart']??[];$items=[];$subtotal=0;$count=0;
if($cart){
 $ids=array_values(array_filter(array_map('intval',array_keys($cart))));
 if($ids){
  $marks=implode(',',array_fill(0,count($ids),'?'));
  $st=db()->prepare("SELECT v.id,v.variant_name,v.price,v.stock_quantity,p.name,(SELECT image_path FROM product_images WHERE product_id=p.id ORDER BY is_primary DESC,sort_order LIMIT 1) image_path FROM product_variants v JOIN products p ON p.id=v.product_id WHERE v.id IN ($marks) AND v.is_active=1 AND p.is_active=1");
  $st->execute($ids);
  foreach($st->fetchAll() as $row){
   $qty=min((int)$row['stock_quantity'],max(1,(int)$cart[$row['id']]));
   if($qty<1)continue;
   $line=$qty*(float)$row['price'];$subtotal+=$line;$count+=$qty;
   $items[]=['name'=>$row['name'],'variant'=>$row['variant_name'],'quantity'=>$qty,'price'=>money($row['price']),'line_total'=>money($line),'image'=>$row['image_path']?:'/assets/images/noor.svg'];
  }
 }
}
echo json_encode(['count'=>$count,'subtotal'=>money($subtotal),'items'=>$items],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
