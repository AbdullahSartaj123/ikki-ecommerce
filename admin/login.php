<?php
require_once __DIR__.'/../config/bootstrap.php';
if(!empty($_SESSION['admin_id'])){header('Location: /admin/');exit;}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){verify_csrf();$email=trim((string)($_POST['email']??''));$password=(string)($_POST['password']??'');$st=db()->prepare('SELECT id,name,password_hash,is_active FROM admins WHERE email=? LIMIT 1');$st->execute([$email]);$a=$st->fetch();if($a&&(int)$a['is_active']===1&&password_verify($password,$a['password_hash'])){session_regenerate_id(true);$_SESSION['admin_id']=$a['id'];$_SESSION['admin_name']=$a['name'];header('Location: /admin/');exit;}$error='Invalid login details.';}
$pageTitle='Admin Login — Ikki';require __DIR__.'/../includes/header.php';
?>
<div class="page-wrap"><form class="site-form" method="post"><div class="page-intro"><span class="eyebrow">Store administration</span><h1 class="page-title">Admin sign in</h1></div><?php if($error):?><div class="notice"><?=e($error)?></div><?php endif;?><?=csrf_field()?><label class="field-label">Email</label><input class="field" type="email" name="email" required autocomplete="username"><label class="field-label">Password</label><input class="field" type="password" name="password" required autocomplete="current-password"><p><button class="btn" type="submit">Sign in</button></p></form></div>
<?php require __DIR__.'/../includes/footer.php';?>
