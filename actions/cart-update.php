<?php
require_once __DIR__ . '/../config/bootstrap.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);exit;}verify_csrf();
foreach(($_POST['qty']??[]) as $id=>$qty){$id=(int)$id;$qty=max(0,min(20,(int)$qty));if($qty===0){unset($_SESSION['cart'][$id]);continue;}$st=db()->prepare("SELECT stock_quantity FROM product_variants WHERE id=? AND is_active=1");$st->execute([$id]);$row=$st->fetch();if($row)$_SESSION['cart'][$id]=min($qty,(int)$row['stock_quantity']);}
header('Location: /cart.php');exit;
