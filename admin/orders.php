<?php
require_once __DIR__.'/../config/bootstrap.php';
require_admin();
$allowedStatuses=['awaiting_whatsapp_confirmation'=>'Awaiting WhatsApp confirmation','confirmed'=>'Confirmed','processing'=>'Processing','shipped'=>'Shipped','delivered'=>'Delivered','cancelled'=>'Cancelled'];
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();
 $orderId=(int)($_POST['order_id']??0);
 $newStatus=(string)($_POST['order_status']??'');
 $note=trim((string)($_POST['note']??''));
 if($orderId<1||!isset($allowedStatuses[$newStatus])||mb_strlen($note)>500){
  $error='Please select a valid order status and keep the note under 500 characters.';
 }else{
  try{
   db()->beginTransaction();
   $lock=db()->prepare('SELECT order_status FROM orders WHERE id=? FOR UPDATE');
   $lock->execute([$orderId]);$current=$lock->fetchColumn();
   if($current===false)throw new RuntimeException('Order not found.');
   if($current!==$newStatus){
    db()->prepare('UPDATE orders SET order_status=? WHERE id=?')->execute([$newStatus,$orderId]);
    db()->prepare('INSERT INTO order_status_history(order_id,old_status,new_status,note) VALUES(?,?,?,?)')->execute([$orderId,$current,$newStatus,$note?:null]);
   }
   db()->commit();
   header('Location: /admin/orders.php?updated=1');exit;
  }catch(Throwable $ex){
   if(db()->inTransaction())db()->rollBack();
   error_log('[IKKI admin order update] '.$ex->getMessage());
   $error='The order could not be updated. Please try again.';
  }
 }
}
$rows=db()->query('SELECT id,order_number,customer_name,customer_phone,grand_total,order_status,created_at FROM orders ORDER BY id DESC LIMIT 100')->fetchAll();
$pageTitle='Orders — Ikki';require __DIR__.'/../includes/header.php';
?>
<div class="admin-shell">
 <h1 class="page-title">Orders</h1>
 <nav class="admin-nav"><a href="/admin/">Overview</a><a href="/admin/products.php">Products</a><a href="/admin/orders.php">Orders</a><a href="/admin/logout.php">Sign out</a></nav>
 <?php if($error):?><div class="notice" role="alert"><?=e($error)?></div><?php endif;?>
 <?php if(isset($_GET['updated'])):?><div class="notice" role="status">Order status updated.</div><?php endif;?>
 <p class="muted">Update the operational status after confirming each order. Every change is recorded in the order history.</p>
 <div style="overflow-x:auto"><table class="cart-table"><thead><tr><th>Order</th><th>Customer</th><th>Phone</th><th>Total</th><th>Status</th><th>Created</th><th>Update</th></tr></thead><tbody>
 <?php foreach($rows as $r):?><tr>
 <td><?=e($r['order_number'])?></td><td><?=e($r['customer_name'])?></td><td><?=e($r['customer_phone'])?></td><td><?=money($r['grand_total'])?></td><td><?=e($allowedStatuses[$r['order_status']]??$r['order_status'])?></td><td><?=e($r['created_at'])?></td>
 <td><form method="post" style="min-width:220px"><?=csrf_field()?><input type="hidden" name="order_id" value="<?=(int)$r['id']?>"><label class="field-label" for="status-<?=(int)$r['id']?>">New status</label><select id="status-<?=(int)$r['id']?>" name="order_status" required><?php foreach($allowedStatuses as $value=>$label):?><option value="<?=e($value)?>" <?=$r['order_status']===$value?'selected':''?>><?=e($label)?></option><?php endforeach;?></select><input class="field" name="note" maxlength="500" placeholder="Optional internal note" aria-label="Internal note for <?=e($r['order_number'])?>"><button class="btn" type="submit">Save status</button></form></td>
 </tr><?php endforeach;?>
 <?php if(!$rows):?><tr><td colspan="7">No orders have been placed yet.</td></tr><?php endif;?>
 </tbody></table></div>
</div>
<?php require __DIR__.'/../includes/footer.php';?>