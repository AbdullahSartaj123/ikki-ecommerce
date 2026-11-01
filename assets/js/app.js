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
 // === Collection Page: Filtering ===
 // Category filters, sorting, empty state and skeleton transitions.
 const collectionFilters=[...document.querySelectorAll('[data-filter]')];
 let collectionCards=[...document.querySelectorAll('.collection-card[data-category]')];
 const collectionRevealObserver=('IntersectionObserver'in window)?new IntersectionObserver(entries=>entries.forEach(entry=>{if(!entry.isIntersecting)return;const card=entry.target;card.classList.add('is-revealed');collectionRevealObserver.unobserve(card);}),{threshold:.12,rootMargin:'0px 0px -30px 0px'}):null;
 collectionCards.forEach((card,index)=>{card.style.transitionDelay=(index*100)+'ms';if(collectionRevealObserver)collectionRevealObserver.observe(card);else card.classList.add('is-revealed');});
 const collectionCount=document.querySelector('.collection-count b');
 const noCollectionResults=document.querySelector('#collection-no-results');
 const skeleton=document.querySelector('#collection-skeleton');
 const resetFilter=document.querySelector('[data-reset-filter]');
 const filterTrack=document.querySelector('[data-filter-track]');
 const filterIndicator=filterTrack?.querySelector('.collection-filter-indicator');
 const sortSelect=document.querySelector('#collection-sort');
 const toolbarWrap=document.querySelector('.collection-toolbar-wrap');
 const loadMoreButton=document.querySelector('#collection-load-more');
 const allViewedMessage=document.querySelector('#collection-all-viewed');
 const paginationWrap=document.querySelector('#collection-pagination');
 const perPage=8; // Increase/decrease this to change the number of products shown per batch.
 let visibleLimit=perPage,loadMoreBusy=false;
 if(collectionFilters.length&&collectionCards.length){
  let activeCollectionFilter='all',filterTimer=null,filterRun=0;
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
   collectionCards=sorted;
  };
  collectionCards.forEach((card,index)=>card.dataset.originalIndex=String(index));
  const matchesFilter=(card,selected)=>selected==='all'||(selected==='new'?card.dataset.isNew==='true':card.dataset.category===selected);
  const updatePagination=(matchingCount)=>{
   const hasMore=matchingCount>visibleLimit;
   if(loadMoreButton){loadMoreButton.hidden=!hasMore;loadMoreButton.disabled=loadMoreBusy||!hasMore;loadMoreButton.setAttribute('aria-busy',String(loadMoreBusy));}
   if(allViewedMessage){allViewedMessage.hidden=matchingCount===0||hasMore;}
   if(paginationWrap)paginationWrap.hidden=matchingCount===0;
  };
  const revealPage=(selected,animate=true)=>{
   const matching=collectionCards.filter(card=>matchesFilter(card,selected));
   const visible=matching.slice(0,visibleLimit);
   const visibleSet=new Set(visible);
   collectionCards.forEach((card,index)=>{
    const show=visibleSet.has(card);
    card.hidden=!show;
    card.classList.remove('is-filtering-out','is-filtering-in','is-revealed');
    if(show){
     card.style.transitionDelay=(animate?(visible.indexOf(card)%perPage)*65:0)+'ms';
     void card.offsetWidth;
     if(collectionRevealObserver)collectionRevealObserver.observe(card);else card.classList.add('is-revealed');
     if(animate)card.classList.add('is-filtering-in');
    }
   });
   updatePagination(matching.length);
  };
  const applyFilter=(button,showLoading=true)=>{
   const selected=button.dataset.filter;
   activeCollectionFilter=selected;
   visibleLimit=perPage;
   loadMoreBusy=false;
   const run=++filterRun;
   if(filterTimer)window.clearTimeout(filterTimer);
   collectionFilters.forEach(item=>{const active=item===button;item.classList.toggle('is-active',active);item.setAttribute('aria-pressed',String(active));});
   positionIndicator(button);
   const matches=collectionCards.filter(card=>matchesFilter(card,selected));
   if(collectionCount)collectionCount.textContent=String(matches.length);
   if(noCollectionResults){noCollectionResults.hidden=true;noCollectionResults.classList.remove('is-visible');}
   if(showLoading&&skeleton){
    collectionCards.forEach(card=>{card.hidden=true;card.classList.remove('is-filtering-out','is-filtering-in');});
    skeleton.hidden=false;skeleton.classList.remove('is-visible');
    void skeleton.offsetWidth;skeleton.classList.add('is-visible');
   }
   const revealResults=()=>{
    if(run!==filterRun)return;
    if(skeleton){skeleton.classList.remove('is-visible');skeleton.hidden=true;}
    revealPage(selected,true);
    if(noCollectionResults&&matches.length===0){
     noCollectionResults.hidden=false;
     requestAnimationFrame(()=>noCollectionResults.classList.add('is-visible'));
    }
   };
   filterTimer=window.setTimeout(revealResults,showLoading?480:0);
  };
  // === Collection Page: Load More ===
  // Progressive batches use perPage; new products are picked up automatically.
  loadMoreButton?.addEventListener('click',()=>{
   if(loadMoreBusy)return;
   const total=collectionCards.filter(card=>matchesFilter(card,activeCollectionFilter)).length;
   if(visibleLimit>=total){updatePagination(total);return;}
   loadMoreBusy=true;
   updatePagination(total);
   loadMoreButton.classList.add('is-loading');
   loadMoreButton.innerHTML='<span class="collection-button-spinner" aria-hidden="true"></span><span>Loading</span>';
   window.setTimeout(()=>{
    visibleLimit=Math.min(visibleLimit+perPage,total);
    loadMoreBusy=false;
    revealPage(activeCollectionFilter,true);
    loadMoreButton.classList.remove('is-loading');
    loadMoreButton.textContent='Load More';
   },480);
  });
   collectionFilters.forEach(button=>button.addEventListener('click',()=>applyFilter(button,true)));
  resetFilter?.addEventListener('click',()=>{
   const allButton=collectionFilters.find(button=>button.dataset.filter==='all');
   if(allButton)applyFilter(allButton,true);
  });
  sortSelect?.addEventListener('change',sortCards);
  positionIndicator(collectionFilters.find(button=>button.classList.contains('is-active'))||collectionFilters[0]);
  revealPage('all',false); // Progressive enhancement: show the first perPage products on initial load.
  if(collectionCount)collectionCount.textContent=String(collectionCards.length);
  window.addEventListener('resize',()=>positionIndicator(collectionFilters.find(button=>button.classList.contains('is-active'))||collectionFilters[0]));
  const toolbarSentinel=document.querySelector('.collection-toolbar-sentinel');
  if(toolbarSentinel&&'IntersectionObserver'in window){
   const stickyObserver=new IntersectionObserver(([entry])=>toolbarWrap?.classList.toggle('is-stuck',!entry.isIntersecting),{threshold:0});
   stickyObserver.observe(toolbarSentinel);
  }
 }
 // === Collection Page: Quick View Modal ===
 // Hidden native dialog; product content and purchase options are populated on trigger.
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
 // Contact form validation and simulated submission states (front-end only).
 const contactForm=document.querySelector('#ikki-contact-form');
 if(contactForm){
  const feedback=document.querySelector('#contact-form-feedback');
  const fieldGrid=contactForm.querySelector('.contact-form-grid');
  const submitButton=contactForm.querySelector('.contact-submit');
  const successPanel=document.querySelector('#contact-success-panel');
  const sendAnother=document.querySelector('#contact-send-another');
  const note=contactForm.querySelector('.contact-form-note');
  const emailPattern=/^[A-Z0-9.!#$%&'*+/=?^`{|}~-]+@[A-Z0-9](?:[A-Z0-9-]{0,61}[A-Z0-9])?(?:\\.[A-Z0-9](?:[A-Z0-9-]{0,61}[A-Z0-9])?)+$/i;
  const fields=[...contactForm.querySelectorAll('#contact-name,#contact-email,#contact-subject,#contact-message')];
  let isSubmitting=false,resetTimer=null;
  const validateField=field=>{
   const value=field.value.trim();
   let message='';
   if(field.id==='contact-name'&&value.length<2)message=value?'Name must be at least 2 characters.':'Please enter your full name.';
   else if(field.id==='contact-email'&&!value)message='Please enter your email address.';
   else if(field.id==='contact-email'&&!emailPattern.test(value))message='Please enter a valid email address.';
   else if(field.id==='contact-subject'&&!value)message='Please select a subject.';
   else if(field.id==='contact-message'&&value.length<10)message=value?'Message must be at least 10 characters.':'Please enter your message.';
   const wrap=field.closest('.contact-field');
   wrap.classList.toggle('is-invalid',Boolean(message));
   const error=wrap.querySelector('.field-error');
   if(error)error.textContent=message;
   field.setAttribute('aria-invalid',String(Boolean(message)));
   return !message;
  };
  const clearShake=wrap=>{wrap.classList.remove('is-shaking');void wrap.offsetWidth;};
  fields.forEach(field=>{
   field.addEventListener('blur',()=>{if(!isSubmitting)validateField(field);});
   field.addEventListener('input',()=>{if(wrapHasError(field))validateField(field);});
   field.addEventListener('change',()=>{if(field.id==='contact-subject'&&wrapHasError(field))validateField(field);});
  });
  function wrapHasError(field){return field.closest('.contact-field').classList.contains('is-invalid');}
  const resetForm=()=>{
   if(resetTimer){window.clearTimeout(resetTimer);resetTimer=null;}
   contactForm.reset();
   fields.forEach(field=>{
    const wrap=field.closest('.contact-field');
    wrap.classList.remove('is-invalid','is-shaking');
    const error=wrap.querySelector('.field-error');if(error)error.textContent='';
    field.removeAttribute('aria-invalid');
   });
   fieldGrid.hidden=false;fieldGrid.classList.remove('is-fading-out');successPanel.hidden=true;successPanel.classList.remove('is-visible');
   if(note){note.hidden=false;note.classList.remove('is-fading-out');}
   contactForm.closest('.contact-form-column').classList.remove('is-sending');
   submitButton.disabled=false;submitButton.classList.remove('is-loading','is-success');
   submitButton.innerHTML='Send Message <svg class="contact-submit-icon" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>';
   feedback.classList.remove('is-visible','is-error');feedback.textContent='';
   if(note)note.hidden=false;
   isSubmitting=false;
  };
  const showSuccess=()=>{
   submitButton.classList.remove('is-loading');submitButton.classList.add('is-success');
   submitButton.innerHTML='<svg class="contact-button-check" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 5 5L20 7"/></svg><span>Message Sent</span>';
   submitButton.disabled=true;
   fieldGrid.classList.add('is-fading-out');
   if(note)note.classList.add('is-fading-out');
   window.setTimeout(()=>{
    fieldGrid.hidden=true;
    if(note)note.hidden=true;
    successPanel.hidden=false;
    requestAnimationFrame(()=>successPanel.classList.add('is-visible'));
   },320);
   resetTimer=window.setTimeout(resetForm,5000);
  };
  sendAnother.addEventListener('click',resetForm);
  contactForm.addEventListener('submit',event=>{
   event.preventDefault();
   if(isSubmitting||submitButton.classList.contains('is-success'))return;
   let firstInvalid=null;
   fields.forEach(field=>{
    const valid=validateField(field);
    if(!valid){
     const wrap=field.closest('.contact-field');
     clearShake(wrap);wrap.classList.add('is-shaking');
     if(!firstInvalid)firstInvalid=field;
    }
   });
   if(firstInvalid){
    feedback.classList.add('is-visible','is-error');
    feedback.textContent='Please correct the highlighted fields before sending.';
    firstInvalid.focus();return;
   }
   feedback.classList.remove('is-visible','is-error');feedback.textContent='';
   isSubmitting=true;contactForm.closest('.contact-form-column').classList.add('is-sending');submitButton.disabled=true;submitButton.classList.add('is-loading');
   submitButton.innerHTML='<span class="contact-loading-ring" aria-hidden="true"></span><span>Sending</span>';
   window.setTimeout(showSuccess,1200);
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

 // Our Story mission statement: reveal the quotation word by word once in view.
 const missionQuote=document.querySelector('[data-mission-quote]');
 if(missionQuote){
  const quoteText=missionQuote.getAttribute('aria-label')||missionQuote.textContent.trim();
  const words=quoteText.match(/\\S+/g)||[];
  if(reducedMotion||!('IntersectionObserver' in window)){
   missionQuote.textContent=quoteText;
   missionQuote.classList.add('is-visible');
  }else{
   missionQuote.textContent='';
   words.forEach((word,index)=>{
    const span=document.createElement('span');
    span.className='story-mission-word';
    span.style.setProperty('--word-delay',(index*50)+'ms');
    span.textContent=word;
    missionQuote.appendChild(span);
    if(index<words.length-1)missionQuote.appendChild(document.createTextNode(' '));
   });
   const missionSection=missionQuote.closest('.story-mission');
   const missionObserver=new IntersectionObserver(entries=>{
    entries.forEach(entry=>{
     if(entry.isIntersecting){
      missionSection.classList.add('is-visible');
      missionObserver.unobserve(entry.target);
     }
    });
   },{threshold:.25,rootMargin:'0px 0px -8% 0px'});
   missionObserver.observe(missionSection);
  }
 }

 // Our Story milestone timeline: activate each milestone once as it enters view.
 const storyTimeline=document.querySelector('.story-timeline-track');
 if(storyTimeline){
  const milestones=[...storyTimeline.querySelectorAll('.story-timeline-item')];
  const updateTimelineProgress=()=>{
   const active=milestones.filter(item=>item.classList.contains('is-active')).length;
   const progress=milestones.length>1?(active-1)/(milestones.length-1):active?1:0;
   storyTimeline.style.setProperty('--timeline-progress',String(Math.max(0,progress)));
  };
  if('IntersectionObserver' in window){
   const milestoneObserver=new IntersectionObserver(entries=>{
    entries.forEach(entry=>{
     if(entry.isIntersecting){
      entry.target.classList.add('is-active');
      milestoneObserver.unobserve(entry.target);
      updateTimelineProgress();
     }
    });
   },{root:null,rootMargin:'0px 0px -18% 0px',threshold:.45});
   milestones.forEach(item=>milestoneObserver.observe(item));
  }else{
   milestones.forEach(item=>item.classList.add('is-active'));
   updateTimelineProgress();
  }
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
