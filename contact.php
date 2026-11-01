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
  <div class="contact-details-list" aria-label="Contact information">
   <div class="contact-detail-item">
    <span class="contact-detail-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg></span>
    <div class="contact-detail-copy"><h3>Visit Us</h3><p>123 Fragrance Avenue, Lahore, Pakistan</p></div>
   </div>
   <div class="contact-detail-item">
    <span class="contact-detail-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M21 16.5v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 10.2 18a19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 1.8 3.2 2 2 0 0 1 3.8 1h3a2 2 0 0 1 2 1.7l.5 3a2 2 0 0 1-.6 1.7L6.8 9.3a16 16 0 0 0 6 6l1.9-1.9a2 2 0 0 1 1.7-.6l3 .5a2 2 0 0 1 1.6 3.2Z"/></svg></span>
    <div class="contact-detail-copy"><h3>Call Us</h3><a href="tel:+923001234567">+92 300 1234567</a></div>
   </div>
   <div class="contact-detail-item">
    <span class="contact-detail-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 7L2 7"/></svg></span>
    <div class="contact-detail-copy"><h3>Email Us</h3><a href="mailto:hello@maisonperfume.com">hello@maisonperfume.com</a></div>
   </div>
   <div class="contact-detail-item">
    <span class="contact-detail-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.5 2"/></svg></span>
    <div class="contact-detail-copy"><h3>Store Hours</h3><p>Mon-Sat: 10am - 8pm<br>Sun: 12pm - 6pm</p></div>
   </div>
  </div>
  <div class="contact-social contact-info-social">
   <span class="story-kicker">FOLLOW ALONG</span>
   <div class="contact-info-socials" aria-label="Follow Maison Perfume">
    <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="18" cy="6.5" r="1"/></svg></a>
    <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><svg viewBox="0 0 24 24"><path class="social-fill" d="M13.5 21v-8h2.7l.4-3.1h-3.1v-2c0-.9.3-1.5 1.6-1.5h1.7V3.6c-.3 0-1.3-.1-2.5-.1-2.5 0-4.2 1.5-4.2 4.3v2.1H7.3V13h2.8v8z"/></svg></a>
    <a href="https://www.pinterest.com/" target="_blank" rel="noopener noreferrer" aria-label="Pinterest"><svg viewBox="0 0 24 24"><path class="social-fill" d="M12 2a10 10 0 0 0-3.6 19.3c0-.8 0-1.8.2-2.7l1.3-5.4s-.3-.6-.3-1.5c0-1.4.8-2.5 1.9-2.5.9 0 1.3.7 1.3 1.5 0 .9-.6 2.3-.9 3.6-.3 1.1.5 2 1.6 2 1.9 0 3.3-2.4 3.3-5.2 0-2.1-1.4-3.7-3.9-3.7-2.8 0-4.5 2.1-4.5 4.4 0 .8.2 1.5.6 2 .2.2.2.3.1.6l-.2.8c-.1.3-.3.4-.6.3-1.3-.5-1.9-1.9-1.9-3.5 0-2.6 2.2-5.8 6.6-5.8 3.5 0 5.8 2.5 5.8 5.2 0 3.6-2 6.3-4.9 6.3-1 0-2-.6-2.4-1.2l-.7 2.8c-.2 1-.8 1.9-1.2 2.6A10 10 0 1 0 12 2z"/></svg></a>
    <a href="https://www.tiktok.com/" target="_blank" rel="noopener noreferrer" aria-label="TikTok"><svg viewBox="0 0 24 24"><path class="social-fill" d="M19.6 7.2a5.6 5.6 0 0 1-3.5-1.2v7.8a5.8 5.8 0 1 1-5-5.7v3.2a2.7 2.7 0 1 0 1.8 2.5V2h3.2c.2 2 1.6 3.6 3.5 3.9z"/></svg></a>
   </div>
  </div>
  <div class="contact-map-wrap contact-info-map">
   <div class="contact-map-heading"><span class="story-kicker">FIND US</span><span>LAHORE, PAKISTAN</span></div>
   <div class="contact-map-frame"><iframe title="Map showing 123 Fragrance Avenue, Lahore, Pakistan" src="https://www.google.com/maps?q=123%20Fragrance%20Avenue%2C%20Lahore%2C%20Pakistan&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe></div>
  </div>
 </aside></section>
<?php require __DIR__.'/includes/footer.php'; ?>
