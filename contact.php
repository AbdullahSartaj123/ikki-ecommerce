<?php
require_once __DIR__.'/config/bootstrap.php';
$pageTitle='Contact Us — Ikki';
$description='Get in touch with Ikki for fragrance guidance, order enquiries and customer care.';
require __DIR__.'/includes/header.php';
?>
<section class="page-banner page-banner-contact" aria-labelledby="page-banner-title">
 <div class="page-banner-copy reveal">
  <nav class="page-banner-breadcrumb" aria-label="Breadcrumb"><a href="/">Home</a><span aria-hidden="true">›</span><span aria-current="page">Contact</span></nav>
  <h1 id="page-banner-title">Contact Us</h1>
  <p>We'd love to hear from you — reach out with any questions</p>
 </div>
</section>
<section class="contact-main">
 <div class="contact-form-column reveal">
  <span class="story-kicker">GET IN TOUCH</span>
  <h2 class="contact-form-title">Send Us a Message</h2>
  <form class="ikki-contact-form" id="ikki-contact-form" novalidate>
   <div class="contact-form-grid">
    <div class="contact-field contact-field-full ikki-form-field"><input id="contact-name" type="text" name="name" autocomplete="name" placeholder=" " required><label for="contact-name">Full Name</label><small class="field-error" aria-live="polite"></small></div>
    <div class="contact-field ikki-form-field"><input id="contact-email" type="email" name="email" autocomplete="email" placeholder=" " required><label for="contact-email">Email Address</label><small class="field-error" aria-live="polite"></small></div>
    <div class="contact-field ikki-form-field ikki-form-select"><select id="contact-subject" name="subject" required><option value="" selected disabled hidden></option><option value="General Inquiry">General Inquiry</option><option value="Order Support">Order Support</option><option value="Wholesale">Wholesale</option><option value="Press">Press</option><option value="Other">Other</option></select><label for="contact-subject">Subject</label><small class="field-error" aria-live="polite"></small></div>
    <div class="contact-field contact-field-full ikki-form-field"><textarea id="contact-message" name="message" rows="5" minlength="10" placeholder=" " required></textarea><label for="contact-message">Message</label><small class="field-error" aria-live="polite"></small></div>
    <div class="contact-field contact-field-full"><label class="ikki-form-checkbox"><input type="checkbox" name="whatsapp_consent" value="yes"><span class="ikki-form-checkbox__box" aria-hidden="true"></span><span>You may contact me about this enquiry on WhatsApp.</span></label></div>
   </div>
   <div class="contact-success-panel" id="contact-success-panel" hidden role="status" aria-live="polite">
    <span class="contact-success-icon" aria-hidden="true"><svg viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="21" stroke="currentColor" stroke-width="1.5"/><path d="m14 24 7 7 14-15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
    <h3>Thank you for reaching out.</h3>
    <p>Your message has been validated successfully. This is a demo confirmation; no message has been sent.</p>
    <button class="contact-send-another" type="button" id="contact-send-another">Send another message</button>
   </div>
   <button class="contact-submit" type="submit">Send Message <svg class="contact-submit-icon" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg></button>
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
