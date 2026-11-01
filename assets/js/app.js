document.addEventListener('DOMContentLoaded',()=>{
 const header=document.querySelector('[data-site-header]');
 const toggle=document.querySelector('.menu-toggle');
 const nav=document.querySelector('#primary-navigation');
 const reducedMotion=window.matchMedia('(prefers-reduced-motion: reduce)').matches;

 // Keep the homepage header transparent over the hero, then give it a solid surface.
 if(header&&header.classList.contains('is-home-header')){
  const updateHeader=()=>header.classList.toggle('is-scrolled',window.scrollY>24);
  updateHeader();
  window.addEventListener('scroll',updateHeader,{passive:true});
 }

 // Accessible mobile navigation.
 if(toggle&&nav){
  const closeMenu=()=>{
   nav.classList.remove('open');
   toggle.setAttribute('aria-expanded','false');
   toggle.setAttribute('aria-label','Open navigation');
  };
  toggle.addEventListener('click',()=>{
   const isOpen=nav.classList.toggle('open');
   toggle.setAttribute('aria-expanded',String(isOpen));
   toggle.setAttribute('aria-label',isOpen?'Close navigation':'Open navigation');
  });
  nav.querySelectorAll('a').forEach(link=>link.addEventListener('click',closeMenu));
  document.addEventListener('keydown',event=>{if(event.key==='Escape')closeMenu();});
  document.addEventListener('click',event=>{
   if(nav.classList.contains('open')&&!nav.contains(event.target)&&!toggle.contains(event.target))closeMenu();
  });
  window.addEventListener('resize',()=>{if(window.innerWidth>700)closeMenu();});
 }

 // Consistent back-to-top behavior.
 document.querySelectorAll('[data-back-to-top]').forEach(link=>link.addEventListener('click',event=>{
  event.preventDefault();
  window.scrollTo({top:0,behavior:reducedMotion?'auto':'smooth'});
 }));

 // Existing editorial reveal behavior.
 const reveal=document.querySelectorAll('.reveal, .home-reveal');
 if(reducedMotion||!('IntersectionObserver'in window)){
  reveal.forEach(el=>el.classList.add('visible'));
 }else{
  const observer=new IntersectionObserver(entries=>entries.forEach(entry=>{
   if(entry.isIntersecting){entry.target.classList.add('visible');observer.unobserve(entry.target);}
  }),{threshold:.12,rootMargin:'0px 0px -35px 0px'});
  reveal.forEach(el=>observer.observe(el));
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
  const emailPattern=/^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;
  const fields=[...contactForm.querySelectorAll('input[required],textarea[required]')];
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
