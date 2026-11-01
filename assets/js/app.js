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
  element.style.transitionDelay=/^(?:0|[1-9]\\d{0,2}|1000)ms$/.test(delay)?delay:'0ms';
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
 // Collection filtering, animated pill indicator and client-side sorting.
 const collectionFilters=[...document.querySelectorAll('[data-filter]')];
 const collectionCards=[...document.querySelectorAll('.collection-card[data-category]')];
 const collectionRevealObserver=('IntersectionObserver'in window)?new IntersectionObserver(entries=>entries.forEach(entry=>{if(!entry.isIntersecting)return;const card=entry.target;card.classList.add('is-revealed');collectionRevealObserver.unobserve(card);}),{threshold:.12,rootMargin:'0px 0px -30px 0px'}):null;
 collectionCards.forEach((card,index)=>{card.style.transitionDelay=(index*100)+'ms';if(collectionRevealObserver)collectionRevealObserver.observe(card);else card.classList.add('is-revealed');});
 const collectionCount=document.querySelector('.collection-count b');
 const noCollectionResults=document.querySelector('#collection-no-results');
 const filterTrack=document.querySelector('[data-filter-track]');
 const filterIndicator=filterTrack?.querySelector('.collection-filter-indicator');
 const sortSelect=document.querySelector('#collection-sort');
 const toolbarWrap=document.querySelector('.collection-toolbar-wrap');
 if(collectionFilters.length&&collectionCards.length){
  let activeCollectionFilter='all';
  const positionIndicator=button=>{
   if(!filterTrack||!filterIndicator||!button)return;
   const track=filterTrack.getBoundingClientRect(),pill=button.getBoundingClientRect();
   filterIndicator.style.width=pill.width+'px';
   filterIndicator.style.transform='translateX('+(pill.left-track.left)+'px)';
  };
  const sortCards=()=>{
   const mode=sortSelect?.value||'featured';
   const sorted=[...collectionCards].sort((a,b)=>{
    if(mode==='price-asc')return Number(a.dataset.price)-Number(b.dataset.price);
    if(mode==='price-desc')return Number(b.dataset.price)-Number(a.dataset.price);
    if(mode==='newest')return Number(b.dataset.productId)-Number(a.dataset.productId);
    return Number(a.dataset.originalIndex)-Number(b.dataset.originalIndex);
   });
   sorted.forEach(card=>card.parentElement.appendChild(card));
  };
  collectionCards.forEach((card,index)=>card.dataset.originalIndex=String(index));
  const applyFilter=button=>{
   const selected=button.dataset.filter;
   activeCollectionFilter=selected;
   collectionFilters.forEach(item=>{const active=item===button;item.classList.toggle('is-active',active);item.setAttribute('aria-pressed',String(active));});
   positionIndicator(button);
   const matches=card=>selected==='all'||(selected==='new'?card.dataset.isNew==='true':card.dataset.category===selected);
   collectionCards.forEach((card,index)=>{
    const show=matches(card);
    if(!show&&!card.hidden){
     card.classList.add('is-filtering-out');
     window.setTimeout(()=>{if(activeCollectionFilter!=='all'&&(activeCollectionFilter==='new'?card.dataset.isNew!=='true':card.dataset.category!==activeCollectionFilter))card.hidden=true;card.classList.remove('is-filtering-out');},200);
    }else if(show&&(card.hidden||card.classList.contains('is-filtering-out'))){
     card.hidden=false;card.classList.remove('is-filtering-out');card.classList.remove('is-filtering-in','is-revealed');
     card.style.transitionDelay=(index*100)+'ms';
     void card.offsetWidth;
     if(collectionRevealObserver)collectionRevealObserver.observe(card);else card.classList.add('is-revealed');
     card.classList.add('is-filtering-in');
    }
   });
   const visible=collectionCards.filter(matches).length;
   if(collectionCount)collectionCount.textContent=String(visible);
   if(noCollectionResults)noCollectionResults.hidden=visible!==0;
  };
  collectionFilters.forEach(button=>button.addEventListener('click',()=>applyFilter(button)));
  sortSelect?.addEventListener('change',sortCards);
  positionIndicator(collectionFilters.find(button=>button.classList.contains('is-active'))||collectionFilters[0]);
  window.addEventListener('resize',()=>positionIndicator(collectionFilters.find(button=>button.classList.contains('is-active'))||collectionFilters[0]));
  sortCards();
 }
 if(toolbarWrap&&'IntersectionObserver'in window){
  const sentinel=document.createElement('span');
  sentinel.className='collection-sticky-sentinel';sentinel.setAttribute('aria-hidden','true');
  toolbarWrap.before(sentinel);
  const stickyObserver=new IntersectionObserver(([entry])=>toolbarWrap.classList.toggle('is-stuck',!entry.isIntersecting),{threshold:0,rootMargin:'-70px 0px 0px 0px'});
  stickyObserver.observe(sentinel);
 }

 // Product quick-view modal: animated lifecycle, product data, focus trap and purchase controls.
 const quickDialog=document.querySelector('#collection-quick-dialog');
 if(quickDialog){
  const $=selector=>quickDialog.querySelector(selector);
  const quickForm=$('[data-quick-form]'),variantInput=quickForm.querySelector('[name="variant_id"]'),qtyInput=quickForm.querySelector('[name="quantity"]');
  const qtyOutput=$('[data-quantity-value]'),sizeWrap=$('[data-quick-sizes]'),sizeButtons=$('[data-size-buttons]');
  const addButton=$('[data-quick-add]'),unavailable=$('[data-quick-unavailable]');
  const products=[
   {name:'Noir Orchid',price:185,image:'/assets/images/noor.svg',top:'Dark floral',heart:'Oud',base:'Black amber',description:'A deep floral composition with oud and black amber, created for a confident, lingering signature.'},
   {name:'Velvet Rose',price:165,image:'/assets/images/noor.svg',top:'Rose',heart:'Patchouli',base:'Vanilla',description:'Velvet rose petals meet earthy patchouli and a soft vanilla finish in an elegant floral trail.'},
   {name:'Golden Oud',price:210,image:'/assets/images/noor.svg',top:'Oud',heart:'Saffron',base:'Sandalwood',description:'A warm, opulent blend of oud, saffron and creamy sandalwood with a smooth, enduring character.'},
   {name:'Citrus Soleil',price:145,image:'/assets/images/noor.svg',top:'Bergamot',heart:'Neroli',base:'Musk',description:'Bright bergamot and delicate neroli settle into clean musk for a fresh, luminous everyday scent.'},
   {name:'Amber Nights',price:195,image:'/assets/images/noor.svg',top:'Amber',heart:'Leather',base:'Tobacco',description:'Rich amber, supple leather and tobacco create a warm, atmospheric fragrance with depth.'},
   {name:'Iron & Leather',price:175,image:'/assets/images/noor.svg',top:'Vetiver',heart:'Leather',base:'Cedarwood',description:'Earthy vetiver and leather are grounded by cedarwood in a bold, woody composition.'},
   {name:'White Jasmine',price:155,image:'/assets/images/noor.svg',top:'Jasmine',heart:'White musk',base:'Pear',description:'Airy jasmine and pear soften into white musk for a delicate, modern floral signature.'},
   {name:'Midnight Spice',price:220,image:'/assets/images/noor.svg',top:'Black pepper',heart:'Cardamom',base:'Oud',description:'Black pepper and cardamom add a lively opening before rich oud brings a dark, distinctive finish.'}
  ];
  let closing=false,returnFocus=null,closeTimer=null;
  const moneyLabel=value=>'Rs. '+new Intl.NumberFormat('en-PK',{maximumFractionDigits:0}).format(Number(value)||0);
  const setQuantity=value=>{const qty=Math.max(1,Math.min(10,Number(value)||1));qtyInput.value=String(qty);qtyOutput.textContent=String(qty);};
  const renderVariants=variants=>{
   sizeButtons.replaceChildren();sizeWrap.hidden=!variants.length;variantInput.value='';
   variants.forEach((variant,index)=>{
    const button=document.createElement('button');button.type='button';
    button.textContent=variant.volume_ml?variant.volume_ml+'ml':(variant.variant_name||'Size');
    button.setAttribute('aria-pressed','false');button.disabled=Number(variant.stock_quantity)<1;
    button.addEventListener('click',()=>{
     sizeButtons.querySelectorAll('button').forEach(item=>item.setAttribute('aria-pressed','false'));
     button.setAttribute('aria-pressed','true');variantInput.value=String(variant.id);
     $('[data-quick-price]').textContent=moneyLabel(variant.price);
     addButton.disabled=false;unavailable.hidden=true;
    });
    sizeButtons.append(button);
    if(index===0&&!button.disabled)button.click();
   });
  };
  const openQuickView=trigger=>{
   const product=products.find(item=>item.name===trigger.dataset.name);
   if(!product)return;
   returnFocus=trigger;closing=false;
   $('#quick-view-title').textContent=product.name;
   $('[data-quick-price]').textContent=trigger.dataset.price||moneyLabel(product.price);
   const image=$('[data-quick-image]');image.src=trigger.dataset.image||product.image;image.alt=product.name+' fragrance bottle';
   $('[data-quick-description]').textContent=trigger.dataset.description||product.description;
   $('[data-note-top]').textContent=trigger.dataset.top||product.top;
   $('[data-note-heart]').textContent=trigger.dataset.heart||product.heart;
   $('[data-note-base]').textContent=trigger.dataset.base||product.base;
   let variants=[];try{variants=JSON.parse(trigger.dataset.variants||'[]');}catch(error){variants=[];}
   renderVariants(variants);setQuantity(1);
   const available=trigger.dataset.available==='true'&&variants.some(item=>Number(item.stock_quantity)>0);
   addButton.disabled=!available;unavailable.hidden=available;
   const detail=$('[data-quick-detail]');detail.hidden=!trigger.dataset.url;if(trigger.dataset.url)detail.href=trigger.dataset.url;
   quickDialog.showModal();
   document.body.classList.add('quick-view-open');
   requestAnimationFrame(()=>quickDialog.classList.add('is-visible'));
   window.setTimeout(()=>{if(quickDialog.open)$('[data-quick-close]').focus();},80);
  };
  const closeQuickView=()=>{
   if(!quickDialog.open||closing)return;
   closing=true;quickDialog.classList.remove('is-visible');quickDialog.classList.add('is-closing');
   if(closeTimer)window.clearTimeout(closeTimer);
   closeTimer=window.setTimeout(()=>{
    quickDialog.close();quickDialog.classList.remove('is-closing');
    document.body.classList.remove('quick-view-open');closing=false;
    if(returnFocus?.isConnected)returnFocus.focus();
   },400);
  };
  document.querySelectorAll('[data-quick-view]').forEach(button=>button.addEventListener('click',()=>openQuickView(button)));
  $('[data-quantity-minus]').addEventListener('click',()=>setQuantity(Number(qtyInput.value)-1));
  $('[data-quantity-plus]').addEventListener('click',()=>setQuantity(Number(qtyInput.value)+1));
  $('[data-quick-close]').addEventListener('click',closeQuickView);
  quickDialog.addEventListener('click',event=>{if(event.target===quickDialog)closeQuickView();});
  quickDialog.addEventListener('cancel',event=>{event.preventDefault();closeQuickView();});
  document.addEventListener('keydown',event=>{
   if(!quickDialog.open||event.key!=='Tab')return;
   const focusable=[...quickDialog.querySelectorAll('a[href]:not([hidden]),button:not(:disabled):not([hidden]),input:not(:disabled):not([type="hidden"]):not([hidden]),select:not(:disabled),textarea:not(:disabled),[tabindex]:not([tabindex="-1"])')].filter(el=>el.getClientRects().length);
   if(!focusable.length){event.preventDefault();return;}
   const first=focusable[0],last=focusable[focusable.length-1];
   if(event.shiftKey&&(document.activeElement===first||!quickDialog.contains(document.activeElement))){event.preventDefault();last.focus();}
   else if(!event.shiftKey&&(document.activeElement===last||!quickDialog.contains(document.activeElement))){event.preventDefault();first.focus();}
  });
  quickDialog.addEventListener('close',()=>{document.body.classList.remove('quick-view-open');quickDialog.classList.remove('is-visible','is-closing');});
  quickForm.addEventListener('submit',event=>{if(!variantInput.value){event.preventDefault();addButton.disabled=true;}});
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
+Number(variant.price).toFixed(2)):quickDialog.dataset.basePrice;
     addButton.disabled=button.disabled;
    });
    sizeButtons.append(button);
   });
   const first=currentVariants.find(item=>Number(item.stock_quantity)>0);
   variantInput.value=first?String(first.id):'';
   if(first){const chosen=[...sizeButtons.children].find(button=>button.textContent===(first.volume_ml?first.volume_ml+'ml':first.variant_name));chosen?.setAttribute('aria-pressed','true');$('[data-quick-price]').textContent=first.price?'
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
+Number(first.price).toFixed(2):quickDialog.dataset.basePrice;}
  };
  document.querySelectorAll('[data-quick-view]').forEach(button=>button.addEventListener('click',()=>{
   $('#quick-view-title').textContent=button.dataset.name||'';
   $('[data-quick-price]').textContent=button.dataset.price||'';
   $('[data-quick-image]').src=button.dataset.image||'/assets/images/noor.svg';
   $('[data-quick-image]').alt=(button.dataset.name||'Fragrance')+' bottle';
   $('[data-quick-description]').textContent=button.dataset.description||'';
   $('[data-note-top]').textContent=button.dataset.top||'Top note';
   $('[data-note-heart]').textContent=button.dataset.heart||'Heart note';
   $('[data-note-base]').textContent=button.dataset.base||'Base note';
   const variants=JSON.parse(button.dataset.variants||'[]');
   renderVariants(variants);setQuantity(1);
   const available=button.dataset.available==='true'&&variants.some(item=>Number(item.stock_quantity)>0);
   addButton.disabled=!available;unavailable.hidden=available;
   const detail=$('[data-quick-detail]');
   detail.hidden=!button.dataset.url;if(button.dataset.url)detail.href=button.dataset.url;
   quickDialog.showModal();
  }));
  $('[data-quantity-minus]').addEventListener('click',()=>setQuantity(Number(qtyInput.value)-1));
  $('[data-quantity-plus]').addEventListener('click',()=>setQuantity(Number(qtyInput.value)+1));
  $('[data-quick-close]').addEventListener('click',()=>quickDialog.close());
  quickDialog.addEventListener('click',event=>{if(event.target===quickDialog)quickDialog.close();});
  quickDialog.addEventListener('close',()=>{setQuantity(1);});
  quickForm.addEventListener('submit',event=>{if(!variantInput.value){event.preventDefault();addButton.disabled=true;}});
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
