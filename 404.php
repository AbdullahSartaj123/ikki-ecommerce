<?php
require_once __DIR__.'/config/bootstrap.php';
http_response_code(404);
$pageTitle='Page Not Found — Ikki';
$description='The page you are looking for could not be found. Return to the Ikki fragrance collection.';
require __DIR__.'/includes/header.php';
?>
<section class="ikki-error-page" aria-labelledby="ikki-error-title">
 <div class="ikki-error-content fade-up">
  <div class="ikki-error-number" aria-hidden="true">404</div>
  <div class="ikki-error-copy">
   <span class="ikki-error-eyebrow">A SCENT OF MYSTERY</span>
   <h1 id="ikki-error-title">This Page Has Lost Its Scent</h1>
   <p>The page you're looking for doesn't exist or has been moved</p>
   <a class="ikki-btn ikki-btn--primary" href="/">Return Home <span aria-hidden="true">↗</span></a>
  </div>
 </div>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>
