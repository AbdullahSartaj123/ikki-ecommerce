function initScrollAnimations(){
 const selector='.fade-up,.fade-in,.scale-in,.slide-left,.slide-right,.reveal,.home-reveal';
 const elements=[...document.querySelectorAll(selector)];
 const reduced=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
 const reveal=element=>{element.classList.add('in-view','visible');};
 if(!elements.length)return null;
 if(reduced||!('IntersectionObserver'in window)){elements.forEach(reveal);return null;}
 const observer=new IntersectionObserver(entries=>entries.forEach(entry=>{
  if(!entry.isIntersecting)return;
  const element=entry.target;
  const delay=element.getAttribute('data-delay')||'0ms';
  element.style.transitionDelay=['0ms','100ms','150ms','200ms','300ms','400ms'].includes(delay)?delay:'0ms';
  reveal(element);
  observer.unobserve(element);
 }),{threshold:.12,rootMargin:'0px 0px -35px 0px'});
 elements.forEach(element=>observer.observe(element));
 return observer;
}
document.addEventListener('DOMContentLoaded',()=>{
 const header=document.querySelector('[data-site-header]');
 const toggle=document.querySelector('.menu-toggle');
 const nav=document.querySelector('#primary-navigation');
 const reducedMotion=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
 initScrollAnimations();

 // Keep the branded preloader above the page until the DOM is ready.
 const preloader=document.querySelector('[data-home-preloader]');
 if(preloader){
  const finishLoading=()=>{preloader.classList.add('is-finished');document.body.classList.remove('is-loading');window.setTimeout(()=>preloader.remove(),650);};
  if(reducedMotion)finishLoading();else window.setTimeout(finishLoading,450);
 }

 // One delegated smooth-scroll handler for every same-page anchor.
 document.addEventListener('click',event=>{
  const link=event.target.closest('a[href*="#"]');
  if(!link)return;
  const url=new URL(link.href,window.location.href);
  if(url.pathname!==window.location.pathname||url.search!==window.location.search||!url.hash)return;
  const target=document.getElementById(decodeURIComponent(url.hash.slice(1)));
  if(!target)return;
  event.preventDefault();
  target.scrollIntoView({behavior:reducedMotion?'auto':'smooth',block:'start'});
  if(history.replaceState)history.replaceState(null,'',url.hash);
 });

 // Header transitions to its compact glass surface after 80px of scrolling.
 if(header){
  const updateHeader=()=>header.classList.toggle('is-scrolled',window.scrollY>80);
  updateHeader();
  window.addEventListener('scroll',updateHeader,{passive:true});
 }

 // Accessible full-screen mobile navigation with scroll lock and reversible overlay motion.
 const mobileOverlay=document.querySelector('#mobile-navigation');
 const mobileClose=mobileOverlay?.querySelector('.mobile-menu-close');
 if(toggle&&mobileOverlay){
  const mobileLinks=mobileOverlay.querySelectorAll('.mobile-menu-links a');
  const openMenu=()=>{
   mobileOverlay.classList.add('is-open');
   mobileOverlay.setAttribute('aria-hidden','false');
   document.body.classList.add('mobile-menu-open');
   toggle.setAttribute('aria-expanded','true');
   toggle.setAttribute('aria-label','Close navigation');
   mobileLinks.forEach(link=>{link.style.animation='none';void link.offsetWidth;link.style.animation='';});
   if(mobileClose)mobileClose.focus();
  };
  const closeMenu=()=>{
   if(!mobileOverlay.classList.contains('is-open'))return;
   mobileOverlay.classList.remove('is-open');
   mobileOverlay.setAttribute('aria-hidden','true');
   document.body.classList.remove('mobile-menu-open');
   toggle.setAttribute('aria-expanded','false');
   toggle.setAttribute('aria-label','Open navigation');
   toggle.focus();
  };
  toggle.addEventListener('click',()=>mobileOverlay.classList.contains('is-open')?closeMenu():openMenu());
  if(mobileClose)mobileClose.addEventListener('click',closeMenu);
  mobileOverlay.addEventListener('click',event=>{if(event.target===mobileOverlay)closeMenu();});
  mobileLinks.forEach(link=>link.addEventListener('click',closeMenu));
  document.addEventListener('keydown',event=>{if(event.key==='Escape')closeMenu();});
  window.addEventListener('resize',()=>{if(window.innerWidth>900)closeMenu();});
 }
 // Footer accordions: collapsed by default on small screens, expanded on desktop.
 const footerColumns=[...document.querySelectorAll('[data-footer-accordion]')];
 const setFooterAccordionMode=()=>{
  const mobile=window.innerWidth<=768;
  footerColumns.forEach(column=>{
   const button=column.querySelector('.ikki-footer-heading');
   const panel=column.querySelector('.ikki-footer-panel');
   if(!button||!panel)return;
   if(mobile){
    button.setAttribute('aria-expanded','false');
    column.classList.remove('is-expanded');
    panel.style.maxHeight='0px';
   }else{
    button.setAttribute('aria-expanded','true');
    column.classList.add('is-expanded');
    panel.style.maxHeight='none';
   }
  });
 };
 if(footerColumns.length){
  setFooterAccordionMode();
  footerColumns.forEach(column=>{
   const button=column.querySelector('.ikki-footer-heading');
   const panel=column.querySelector('.ikki-footer-panel');
   if(!button||!panel)return;
   button.addEventListener('click',()=>{
    if(window.innerWidth>768)return;
    const expanded=button.getAttribute('aria-expanded')==='true';
    button.setAttribute('aria-expanded',String(!expanded));
    column.classList.toggle('is-expanded',!expanded);
    panel.style.maxHeight=expanded?'0px':panel.scrollHeight+'px';
   });
  });
  window.addEventListener('resize',setFooterAccordionMode);
 }

 // Newsletter email feedback. The subscription endpoint is not connected yet.
 const newsletterForm=document.querySelector('#ikki-newsletter-form');
 if(newsletterForm){
  const email=newsletterForm.querySelector('input[type="email"]');
  const feedback=document.querySelector('#ikki-newsletter-feedback');
  newsletterForm.addEventListener('submit',event=>{
   event.preventDefault();
   feedback.classList.remove('is-visible','is-error');
   if(!email||!email.value.trim()||!email.checkValidity()){
    feedback.textContent='Please enter a valid email address.';
    feedback.classList.add('is-visible','is-error');
    if(email)email.focus();
    return;
   }
   feedback.textContent='Thank you for your interest. Our newsletter is coming soon.';
   feedback.classList.add('is-visible');
   newsletterForm.reset();
  });
 }
 // Shared floating back-to-top control.
 const backToTop=document.querySelector('[data-back-to-top]');
 if(backToTop){
  const updateBackToTop=()=>backToTop.classList.toggle('is-visible',window.scrollY>420);
  updateBackToTop();
  window.addEventListener('scroll',updateBackToTop,{passive:true});
  backToTop.addEventListener('click',()=>window.scrollTo({top:0,behavior:reducedMotion?'auto':'smooth'}));
 }
 // Lazy-load non-critical imagery while keeping hero and main product artwork eager.
 document.querySelectorAll('img:not([loading])').forEach(img=>{
  if(!img.closest('.home-hero')&&!img.closest('.product-visual'))img.loading='lazy';
 });

 // Desktop-only half-speed parallax for the full-bleed story artwork.
 const storyParallax=document.querySelector('.home-story-parallax');
 if(storyParallax&&!reducedMotion&&window.matchMedia('(min-width: 769px)').matches){
  let storyFrame=0;
  const updateStoryParallax=()=>{
   if(storyFrame)return;
   storyFrame=requestAnimationFrame(()=>{
    storyFrame=0;
    const section=storyParallax.closest('.home-story-teaser');
    if(!section)return;
    const rect=section.getBoundingClientRect();
    const offset=(rect.top+rect.height/2-window.innerHeight/2)*-.5;
    storyParallax.style.transform='translate3d(0,'+offset+'px,0)';
   });
  };
  updateStoryParallax();
  window.addEventListener('scroll',updateStoryParallax,{passive:true});
  window.addEventListener('resize',updateStoryParallax);
 }
 // Collection category filters.
 const collectionFilters=document.querySelectorAll('[data-filter]');
 const collectionCards=document.querySelectorAll('.collection-card[data-category]');
 const collectionCount=document.querySelector('.collection-count b');
 const noCollectionResults=document.querySelector('#collection-no-results');
 if(collectionFilters.length&&collectionCards.length){
  collectionFilters.forEach(button=>button.addEventListener('click',()=>{
   const selected=button.dataset.filter;
   collectionFilters.forEach(item=>{const active=item===button;item.classList.toggle('is-active',active);item.setAttribute('aria-pressed',String(active));});
   let visible=0;
   collectionCards.forEach(card=>{
    const show=selected==='all'||card.dataset.category===selected;
    card.hidden=!show;
    if(show)visible++;
   });
   if(collectionCount)collectionCount.textContent=String(visible);
   if(noCollectionResults)noCollectionResults.hidden=visible!==0;
  }));
 }



 // Contact form front-end validation. No message is transmitted until a delivery service is connected.
 const contactForm=document.querySelector('#ikki-contact-form');
 if(contactForm){
  const feedback=document.querySelector('#contact-form-feedback');
  const emailPattern=/^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  const fields=[...contactForm.querySelectorAll('input[required]:not([type="checkbox"]),textarea[required],select[required]')];
  const clearField=field=>{const wrap=field.closest('.contact-field');wrap.classList.remove('is-invalid');wrap.querySelector('.field-error').textContent='';};
  fields.forEach(field=>field.addEventListener('input',()=>clearField(field)));
  contactForm.addEventListener('submit',event=>{
   event.preventDefault();
   let firstInvalid=null;
   fields.forEach(field=>{
    clearField(field);
    const value=field.value.trim();
    let message='';
    if(!value)message='Please complete this field.';
    else if(field.type==='email'&&!emailPattern.test(value))message='Please enter a valid email address.';
    if(message){const wrap=field.closest('.contact-field');wrap.classList.add('is-invalid');wrap.querySelector('.field-error').textContent=message;if(!firstInvalid)firstInvalid=field;}
   });
   feedback.classList.add('is-visible');
   if(firstInvalid){
    feedback.classList.add('is-error');feedback.textContent='Please review the highlighted fields and try again.';firstInvalid.focus();return;
   }
   feedback.classList.remove('is-error');
   feedback.textContent='Thank you. Your details passed validation, but this form is not connected to email delivery yet. Please send your enquiry directly through our WhatsApp contact link.';
   contactForm.reset();
  });
 }

 // Auto-rotating customer testimonials; pause while hovered, focused or touched.
 const testimonialRoot=document.querySelector('[data-testimonial-carousel]');
 if(testimonialRoot){
  const slides=[...testimonialRoot.querySelectorAll('[data-testimonial-slide]')];
  const dots=[...testimonialRoot.querySelectorAll('[data-testimonial-dot]')];
  let activeIndex=Math.max(0,slides.findIndex(slide=>slide.classList.contains('is-active')));
  let rotation=null;
  let touchPaused=false;
  const showTestimonial=index=>{
   activeIndex=(index+slides.length)%slides.length;
   slides.forEach((slide,i)=>{
    const active=i===activeIndex;
    slide.classList.toggle('is-active',active);
    slide.setAttribute('aria-hidden',String(!active));
    if(active)slide.removeAttribute('inert');else slide.setAttribute('inert','');
   });
   dots.forEach((dot,i)=>{
    const active=i===activeIndex;
    dot.classList.toggle('is-active',active);
    dot.setAttribute('aria-pressed',String(active));
   });
  };
  const stopRotation=()=>{if(rotation){window.clearInterval(rotation);rotation=null;}};
  const startRotation=()=>{
   stopRotation();
   if(reducedMotion||slides.length<2||document.hidden||testimonialRoot.matches(':hover')||testimonialRoot.contains(document.activeElement)||touchPaused)return;
   rotation=window.setInterval(()=>showTestimonial(activeIndex+1),6000);
  };
  dots.forEach((dot,i)=>dot.addEventListener('click',()=>{showTestimonial(i);startRotation();}));
  testimonialRoot.addEventListener('mouseenter',stopRotation);
  testimonialRoot.addEventListener('mouseleave',startRotation);
  testimonialRoot.addEventListener('focusin',stopRotation);
  testimonialRoot.addEventListener('focusout',()=>window.setTimeout(startRotation,0));
  testimonialRoot.addEventListener('touchstart',()=>{touchPaused=true;stopRotation();},{passive:true});
  testimonialRoot.addEventListener('touchend',()=>{window.setTimeout(()=>{touchPaused=false;startRotation();},2500);},{passive:true});
  document.addEventListener('visibilitychange',startRotation);
  showTestimonial(activeIndex);
  startRotation();
 }

 // Homepage newsletter confirmation (front-end only; no subscription is stored).
 const homeNewsletter=document.querySelector('[data-home-newsletter]');
 if(homeNewsletter){
  const success=homeNewsletter.parentElement.querySelector('[data-home-newsletter-success]');
  const email=homeNewsletter.querySelector('input[type="email"]');
  let resetTimer=null;
  homeNewsletter.addEventListener('submit',event=>{
   event.preventDefault();
   if(!email||!email.checkValidity()){email?.reportValidity();return;}
   if(resetTimer)window.clearTimeout(resetTimer);
   homeNewsletter.classList.add('is-leaving');
   window.setTimeout(()=>{
    homeNewsletter.hidden=true;
    success.hidden=false;
    requestAnimationFrame(()=>success.classList.add('is-visible'));
   },350);
   resetTimer=window.setTimeout(()=>{
    success.classList.remove('is-visible');
    window.setTimeout(()=>{
     success.hidden=true;
     homeNewsletter.reset();
     homeNewsletter.classList.remove('is-leaving');
     homeNewsletter.hidden=false;
    },350);
    resetTimer=null;
   },4000);
  });
 }

 // Existing product-variant price update.
 const variant=document.querySelector('#variant');
 const price=document.querySelector('.detail-price');
 if(variant&&price){
  const update=()=>{
   const option=variant.options[variant.selectedIndex];
   if(option&&option.dataset.price){
    const amount=Number(option.dataset.price);
    price.textContent='Rs. '+new Intl.NumberFormat('en-PK',{maximumFractionDigits:0}).format(amount);
   }
  };
  variant.addEventListener('change',update);
 }
});
