<?php
require_once __DIR__.'/config/bootstrap.php';
$pageTitle='Contact Us — IKKI';
$description='Get in touch with IKKI for fragrance guidance, order enquiries and customer care.';
require __DIR__.'/includes/header.php';
?>
<section class="page-banner page-banner-contact" aria-labelledby="page-banner-title">
 <div class="page-banner-copy fade-up">
  <nav class="page-banner-breadcrumb" aria-label="Breadcrumb"><a href="/">Home</a><span aria-hidden="true">›</span><span aria-current="page">Contact Us</span></nav>
  <h1 id="page-banner-title">Contact Us</h1>
  <p>Questions about a fragrance or an order? Our team is here to help.</p>
 </div>
</section>
<section class="contact-main">
 <div class="contact-form-column slide-left">
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
   <button class="contact-submit ikki-btn--primary" type="submit">Send Message <svg class="contact-submit-icon" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg></button>
   <p class="contact-form-feedback" id="contact-form-feedback" role="status" aria-live="polite"></p>
   <p class="contact-form-note">Your message will open in WhatsApp, where you can review it before sending. No message is sent until you press Send in WhatsApp.</p>
  </form>
 </div>
 <aside class="contact-info-column slide-right">
  <div class="contact-details-list" aria-label="IKKI contact information">
   <div class="contact-detail-item fade-up">
    <span class="contact-detail-icon" aria-hidden="true">WA</span>
    <div class="contact-detail-copy"><h3>WhatsApp Customer Care</h3><a href="https://wa.me/923094733789" target="_blank" rel="noopener noreferrer">+92 309 4733789</a></div>
   </div>
   <div class="contact-detail-item fade-up">
    <span class="contact-detail-icon" aria-hidden="true">✦</span>
    <div class="contact-detail-copy"><h3>Fragrance Guidance</h3><p>Ask us about the collection, fragrance guidance or an existing order.</p></div>
   </div>
  </div>
  <a class="contact-submit" href="https://wa.me/923094733789" target="_blank" rel="noopener noreferrer">Chat with IKKI on WhatsApp ↗</a>
 </aside></section>
<?php require __DIR__.'/includes/footer.php'; ?>
