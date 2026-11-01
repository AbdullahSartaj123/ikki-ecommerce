<?php
require_once __DIR__.'/config/bootstrap.php';
$pageTitle='Contact Us — Ikki';
$description='Get in touch with Ikki for fragrance guidance, order enquiries and customer care.';
require __DIR__.'/includes/header.php';
?>
<section class="contact-hero">
 <div class="contact-hero-glow"></div>
 <div class="contact-hero-copy reveal"><span class="story-kicker">WE ARE HERE FOR YOU</span><h1>Contact <em>Us.</em></h1><p>A question, a thought, or a little help finding your signature scent. We would love to hear from you.</p></div>
 <span class="contact-hero-foot">IKKI CUSTOMER CARE · PAKISTAN</span>
</section>
<section class="contact-main">
 <div class="contact-form-column reveal">
  <span class="story-kicker">SEND US A NOTE</span>
  <h2>Let's start a<br><em>conversation.</em></h2>
  <p class="contact-intro">Fill in the details below and our team will be glad to help.</p>
  <form class="ikki-contact-form" id="ikki-contact-form" novalidate>
   <div class="contact-form-grid">
    <label class="contact-field"><span>Your name</span><input type="text" name="name" autocomplete="name" placeholder="Name" required><small class="field-error" aria-live="polite"></small></label>
    <label class="contact-field"><span>Email address</span><input type="email" name="email" autocomplete="email" placeholder="you@example.com" required><small class="field-error" aria-live="polite"></small></label>
    <label class="contact-field contact-field-full"><span>Subject</span><input type="text" name="subject" placeholder="What can we help you with?" required><small class="field-error" aria-live="polite"></small></label>
    <label class="contact-field contact-field-full"><span>Your message</span><textarea name="message" rows="5" placeholder="Write your message here..." required></textarea><small class="field-error" aria-live="polite"></small></label>
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
