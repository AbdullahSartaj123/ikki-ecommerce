<?php
require_once __DIR__.'/config/bootstrap.php';
$pageTitle='Contact Us — Ikki';
$description='Get in touch with Ikki for fragrance guidance, order enquiries and customer care.';
require __DIR__.'/includes/header.php';
?>
<section class="page-banner page-banner-contact" aria-labelledby="page-banner-title">
 <div class="page-banner-copy reveal">
  <nav class="page-banner-breadcrumb" aria-label="Breadcrumb"><a href="/">Home</a><span aria-hidden="true">›</span><span aria-current="page">Contact Us</span></nav>
  <h1 id="page-banner-title">Contact Us</h1>
  <p>A question, a thought, or a little help finding your signature scent. We would love to hear from you.</p>
 </div>
</section>
<section class="contact-main">
 <div class="contact-form-column reveal">
  <span class="story-kicker">SEND US A NOTE</span>
  <h2>Let's start a<br><em>conversation.</em></h2>
  <p class="contact-intro">Fill in the details below and our team will be glad to help.</p>
  <form class="ikki-contact-form" id="ikki-contact-form" novalidate>
   <div class="contact-form-grid">
    <div class="contact-field contact-field-full ikki-form-field"><input id="contact-name" type="text" name="name" autocomplete="name" placeholder=" " required><label for="contact-name">Your name</label><small class="field-error" aria-live="polite"></small></div>
    <div class="contact-field ikki-form-field"><input id="contact-email" type="email" name="email" autocomplete="email" placeholder=" " required><label for="contact-email">Email address</label><small class="field-error" aria-live="polite"></small></div>
    <div class="contact-field ikki-form-field ikki-form-select"><select id="contact-subject" name="subject" required><option value="" selected disabled hidden></option><option value="Order enquiry">Order enquiry</option><option value="Product guidance">Product guidance</option><option value="Delivery support">Delivery support</option><option value="Other">Other</option></select><label for="contact-subject">How can we help?</label><small class="field-error" aria-live="polite"></small></div>
    <div class="contact-field contact-field-full ikki-form-field"><textarea id="contact-message" name="message" rows="5" placeholder=" " required></textarea><label for="contact-message">Your message</label><small class="field-error" aria-live="polite"></small></div>
    <div class="contact-field contact-field-full"><label class="ikki-form-checkbox"><input type="checkbox" name="whatsapp_consent" value="yes"><span class="ikki-form-checkbox__box" aria-hidden="true"></span><span>You may contact me about this enquiry on WhatsApp.</span></label></div>
   </div>
   <button class="contact-submit" type="submit">Send your message <span>↗</span></button>
   <p class="contact-form-feedback" id="contact-form-feedback" role="status" aria-live="polite"></p>
   <p class="contact-form-note">This form currently provides front-end validation only. For a response, please contact us directly on WhatsApp.</p>
  </form>
 </div>
 <aside class="contact-info-column reveal">
  <div class="contact-info-block"><span class="story-kicker">GET IN TOUCH</span><h3>We’re just<br>a message away.</h3>
   <div class="contact-info-item"><span class="contact-info-icon">↗</span><div><small>PHONE / WHATSAPP</small><a href="https://wa.me/923094733789" target="_blank" rel="noopener noreferrer">+92 309 4733789</a></div></div>
   <div class="contact-info-item"><span class="contact-info-icon">✉</span><div><small>EMAIL</small><span class="contact-info-pending">Official email address to be added</span></div></div>
   <div class="contact-info-item"><span class="contact-info-icon">⌖</span><div><small>LOCATION</small><span>Pakistan · Online fragrance house</span></div></div>
  </div>
  <div class="contact-social"><span class="story-kicker">CONNECT WITH IKKI</span><a href="https://wa.me/923094733789" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"> <span>◉</span> WhatsApp <b>↗</b></a><p>More social channels will be added soon.</p></div>
  <div class="contact-map-wrap"><div class="contact-map-heading"><span class="story-kicker">FIND US</span><span>PAKISTAN</span></div><iframe title="Map showing Pakistan" src="https://www.google.com/maps?q=Pakistan&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe><small>Map shown for general location only. A specific store address has not yet been provided.</small></div>
 </aside>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>
