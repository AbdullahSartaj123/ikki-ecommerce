<?php
require_once __DIR__ . '/../config/bootstrap.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);exit;}verify_csrf();
$name=trim((string)($_POST['customer_name']??''));$phone=trim((string)($_POST['phone']??''));$wa=trim((string)($_POST['whatsapp_phone']??''));$email=trim((string)($_POST['email']??''));$province=trim((string)($_POST['province']??''));$city=trim((string)($_POST['city']??''));$address=trim((string)($_POST['address']??''));$postal=trim((string)($_POST['postal_code']??''));$instructions=trim((string)($_POST['instructions']??''));
if($name===''||$phone===''||$province===''||$city===''||$address===''||($email!==''&&!filter_var($email,FILTER_VALIDATE_EMAIL))){http_response_code(422);exit('Please complete all required fields with valid information.');}
$cart=$_SESSION['cart']??[];if(!$cart){header('Location: /cart.php');exit;}
try{
 db()->beginTransaction();$ids=array_values(array_filter(array_map('intval',array_keys($cart))));$marks=implode(',',array_fill(0,count($ids),'?'));
 $st=db()->prepare("SELECT v.id,v.variant_name,v.sku,v.price,v.stock_quantity,p.id product_id,p.name FROM product_variants v JOIN products p ON p.id=v.product_id WHERE v.id IN ($marks) AND v.is_active=1 AND p.is_active=1 FOR UPDATE");$st->execute($ids);$rows=$st->fetchAll();
 if(count($rows)!==count($ids))throw new RuntimeException('A selected fragrance is no longer available.');
 $subtotal=0;$lines=[];foreach($rows as $r){$qty=(int)$cart[$r['id']];if($qty<1||$qty>(int)$r['stock_quantity'])throw new RuntimeException('One of your selected quantities is no longer available.');$line=(float)$r['price']*$qty;$subtotal+=$line;$r['quantity']=$qty;$r['line_total']=$line;$lines[]=$r;}
 $fee=(float)setting('default_delivery_fee','0');$total=$subtotal+$fee;$orderNo='IKK-'.date('ymd').'-'.strtoupper(bin2hex(random_bytes(3)));
 $customer=db()->prepare("INSERT INTO customers(full_name,phone,whatsapp_phone,email) VALUES(?,?,?,?)");$customer->execute([$name,$phone,$wa?:null,$email?:null]);$customerId=(int)db()->lastInsertId();
 $order=db()->prepare("INSERT INTO orders(order_number,customer_id,customer_name,customer_phone,customer_whatsapp,customer_email,delivery_address,city,province,postal_code,delivery_instructions,payment_method,subtotal,delivery_fee,grand_total,order_status) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,'awaiting_whatsapp_confirmation')");
 $order->execute([$orderNo,$customerId,$name,$phone,$wa?:null,$email?:null,$address,$city,$province,$postal?:null,$instructions?:null,'cod',$subtotal,$fee,$total]);$orderId=(int)db()->lastInsertId();
 $itemStmt=db()->prepare("INSERT INTO order_items(order_id,product_id,variant_id,product_name_snapshot,variant_name_snapshot,sku_snapshot,unit_price,quantity,line_total) VALUES(?,?,?,?,?,?,?,?,?)");
 foreach($lines as $r)$itemStmt->execute([$orderId,$r['product_id'],$r['id'],$r['name'],$r['variant_name'],$r['sku'],$r['price'],$r['quantity'],$r['line_total']]);
 db()->prepare("INSERT INTO order_status_history(order_id,new_status,note) VALUES(?,'awaiting_whatsapp_confirmation','Order created; WhatsApp handoff initiated.')")->execute([$orderId]);
 db()->commit();unset($_SESSION['cart']);
 $msg=["*New Ikki Order*","Order: ".$orderNo,"","*Customer*",$name,"Phone: ".$phone,"WhatsApp: ".($wa?:$phone),"Address: ".$address.", ".$city.", ".$province,"","*Items*"];
 foreach($lines as $r)$msg[]=$r['name'].' — '.$r['variant_name'].' x '.$r['quantity'].' | '.money($r['line_total']);
 $msg[]="";$msg[]="Subtotal: ".money($subtotal);$msg[]="Delivery: ".money($fee);$msg[]="*Total: ".money($total)."*";$msg[]="Payment: Cash on Delivery";if($instructions)$msg[]="Instructions: ".$instructions;
 $_SESSION['last_order_number']=$orderNo;$number=preg_replace('/\D/','',setting('whatsapp_number','923094733789'));
 header('Location: https://wa.me/'.$number.'?text='.rawurlencode(implode("\n",$msg)));exit;
}catch(Throwable $ex){if(db()->inTransaction())db()->rollBack();http_response_code(400);echo 'Unable to place this order: '.e($ex->getMessage()).' <a href="/cart.php">Return to bag</a>';}
