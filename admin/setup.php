<?php
require_once __DIR__.'/../config/bootstrap.php';
/*
 * TEMPORARY one-time installer. Define IKKI_ADMIN_SETUP_KEY in database.php
 * or server environment before visiting /admin/setup.php?key=...
 * Delete this file immediately after creating the owner account.
 */
$key = defined('IKKI_ADMIN_SETUP_KEY') ? IKKI_ADMIN_SETUP_KEY : (getenv('IKKI_ADMIN_SETUP_KEY') ?: '');
if ($key === '' || !hash_equals($key, (string)($_GET['key'] ?? ''))) { http_response_code(404); exit('Not found'); }
$count=(int)db()->query('SELECT COUNT(*) FROM admins')->fetchColumn();
if($count>0){http_response_code(403);exit('An admin already exists. Remove this setup file.');}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){verify_csrf();$name=trim((string)($_POST['name']??''));$email=trim((string)($_POST['email']??''));$pass=(string)($_POST['password']??'');if(!$name||!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($pass)<12){$error='Enter a name, valid email and password of at least 12 characters.';}else{$s=db()->prepare('INSERT INTO admins(name,email,password_hash,role) VALUES(?,?,?,?)');$s->execute([$name,$email,password_hash($pass,PASSWORD_DEFAULT),'owner']);exit('Owner account created. Delete admin/setup.php now, then sign in at /admin/login.php.');}}
?><!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width"><title>Ikki setup</title><link rel="stylesheet" href="/assets/css/style.css"></head><body><div class="page-wrap"><h1 class="page-title">Create Ikki owner account</h1><?php if($error):?><p class="notice"><?=e($error)?></p><?php endif;?><form method="post"><?=csrf_field()?><label class="field-label">Name</label><input class="field" name="name" required><label class="field-label">Email</label><input class="field" name="email" type="email" required><label class="field-label">Password (minimum 12 characters)</label><input class="field" name="password" type="password" minlength="12" required><p><button class="btn">Create owner</button></p></form><p class="muted">Delete this setup file immediately after use.</p></div></body></html>
