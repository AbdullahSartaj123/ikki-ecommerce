<?php
require_once __DIR__ . '/../config/bootstrap.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);exit;}
verify_csrf();
$variantId=(int)($_POST['variant_id']??0);$qty=max(1,min(5,(int)($_POST['quantity']??1)));
$stmt=db()->prepare("SELECT v.id,v.stock_quantity,p.is_active FROM product_variants v JOIN products p ON p.id=v.product_id WHERE v.id=? AND v.is_active=1 LIMIT 1");$stmt->execute([$variantId]);$variant=$stmt->fetch();
if(!$variant||!(int)$variant['is_active']||(int)$variant['stock_quantity']<1){header('Location: /shop.php');exit;}
$current=(int)($_SESSION['cart'][$variantId]??0);$_SESSION['cart'][$variantId]=min((int)$variant['stock_quantity'],$current+$qty);
header('Location: /cart.php?added=1');exit;
