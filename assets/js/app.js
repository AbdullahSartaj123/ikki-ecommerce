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
 const reveal=document.querySelectorAll('.reveal');
 if(reducedMotion||!('IntersectionObserver'in window)){
  reveal.forEach(el=>el.classList.add('visible'));
 }else{
  const observer=new IntersectionObserver(entries=>entries.forEach(entry=>{
   if(entry.isIntersecting){entry.target.classList.add('visible');observer.unobserve(entry.target);}
  }),{threshold:.12,rootMargin:'0px 0px -35px 0px'});
  reveal.forEach(el=>observer.observe(el));
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
