<?php
require_once __DIR__.'/config/bootstrap.php';
$pageTitle='Order reference — Ikki';require __DIR__.'/includes/header.php';
?>
<div class="page-wrap" style="text-align:center"><span class="eyebrow">Thank you</span><h1 class="page-title">Your order has been recorded.</h1><p class="muted">Your order reference is <?= e((string)($_SESSION['last_order_number']??'')) ?>. Please send the prefilled WhatsApp message to complete the handoff. Our team will confirm your order.</p><a class="btn" href="https://wa.me/<?= e(setting('whatsapp_number','923094733789')) ?>" target="_blank" rel="noopener">Open WhatsApp</a></div>
<?php require __DIR__.'/includes/footer.php'; ?>
