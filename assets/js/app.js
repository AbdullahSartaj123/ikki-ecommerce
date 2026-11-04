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
  element.style.transitionDelay=/^(?:0|[1-9]\d{0,2}|1000)ms$/.test(delay)?delay:'0ms';
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
 if(window.location.hash==='#collection-search')document.querySelector('#collection-search')?.focus({preventScroll:true});
 // === PRELOADER MODULE ===
 // Start page entrance/reveal animations only after the global preloader exits.
 document.addEventListener('ikki:preloader-finished',initScrollAnimations,{once:true});

 // The shared header owns preloader timing and emits the page-ready event.

 // === CURSOR MODULE ===
 // === CUSTOM CURSOR: DESKTOP POINTER DEVICES ONLY ===
 const cursorDot=document.querySelector('[data-ikki-cursor-dot]');
 const cursorRing=document.querySelector('[data-ikki-cursor-ring]');
 const desktopPointer=window.matchMedia('(min-width: 1024px) and (hover: hover) and (pointer: fine)');
 const hasTouch=('ontouchstart'in window)||(navigator.maxTouchPoints>0);
 if(cursorDot&&cursorRing&&desktopPointer.matches&&!hasTouch){
  document.body.classList.add('ikki-custom-cursor');
  let mouseX=window.innerWidth/2,mouseY=window.innerHeight/2;
  let ringX=mouseX,ringY=mouseY,rafId=0;
  const lerp=(current,target,amount)=>current+(target-current)*amount;
  const renderRing=()=>{
   ringX=lerp(ringX,mouseX,.14);
   ringY=lerp(ringY,mouseY,.14);
   cursorRing.style.transform='translate3d('+ringX+'px,'+ringY+'px,0) translate3d(-50%,-50%,0)';
   if(Math.abs(mouseX-ringX)>.1||Math.abs(mouseY-ringY)>.1)rafId=requestAnimationFrame(renderRing);
   else rafId=0;
  };
  document.addEventListener('pointermove',event=>{
   if(event.pointerType!=='mouse')return;
   mouseX=event.clientX;mouseY=event.clientY;
   cursorDot.style.transform='translate3d('+mouseX+'px,'+mouseY+'px,0) translate3d(-50%,-50%,0)';
   if(!rafId)rafId=requestAnimationFrame(renderRing);
  },{passive:true});
  const hoverSelector='a,button,[role="button"],input[type="submit"],.ikki-product-card,.home-product-card,.product-card,.collection-card,img';
  document.addEventListener('pointerover',event=>{
   if(event.pointerType&&event.pointerType!=='mouse')return;
   const target=event.target.closest(hoverSelector);
   if(!target)return;
   target.classList.add('cursor-hover');
   cursorRing.classList.add('is-hover');
  });
  document.addEventListener('pointerout',event=>{
   if(event.pointerType&&event.pointerType!=='mouse')return;
   const target=event.target.closest(hoverSelector);
   if(!target||target.contains(event.relatedTarget))return;
   target.classList.remove('cursor-hover');
   if(!document.querySelector('.cursor-hover'))cursorRing.classList.remove('is-hover');
  });
  document.addEventListener('pointerleave',()=>{cursorDot.style.opacity='0';cursorRing.style.opacity='0';});
  document.addEventListener('pointerenter',()=>{cursorDot.style.opacity='1';cursorRing.style.opacity='1';});
  desktopPointer.addEventListener?.('change',event=>{
   if(!event.matches){document.body.classList.remove('ikki-custom-cursor');cursorDot.style.display='none';cursorRing.style.display='none';if(rafId)cancelAnimationFrame(rafId);}
  });
 }

 // === NAVIGATION MODULE ===
 // Internal page transitions, mobile navigation and footer accordions.
 // === PAGE-TO-PAGE TRANSITIONS: DELEGATED INTERNAL NAVIGATION ===
 const transitionDuration=300;
 let pageNavigationPending=false;
 const handlePageTransition=url=>{
  if(pageNavigationPending)return;
  pageNavigationPending=true;
  document.body.classList.remove('page-transition-in');
  document.body.classList.add('page-transition-out');
  if(reducedMotion){window.location.assign(url.href);return;}
  window.setTimeout(()=>window.location.assign(url.href),transitionDuration);
 };
 document.addEventListener('click',event=>{
  if(event.defaultPrevented||event.button!==0||event.metaKey||event.ctrlKey||event.shiftKey||event.altKey)return;
  const link=event.target.closest('a[href]');
  if(!link||link.hasAttribute('data-cart-drawer-trigger')||link.hasAttribute('download')||(link.target&&link.target!=='_self'))return;
  const rawHref=link.getAttribute('href')||'';
  if(/^(?:mailto:|tel:|javascript:)/i.test(rawHref))return;
  let destination;
  try{destination=new URL(link.href,window.location.href);}catch(error){return;}
  if(!['http:','https:'].includes(destination.protocol)||destination.origin!==window.location.origin)return;
  if(destination.pathname===window.location.pathname&&destination.search===window.location.search)return;
  event.preventDefault();
  handlePageTransition(destination);
 });

 // === ANIMATIONS MODULE ===
 // Scroll progress, header state, back-to-top and parallax use RAF scheduling.
 // === GLOBAL SCROLL PROGRESS: RAF-THROTTLED ===
 const scrollProgress=document.querySelector('[data-ikki-scroll-progress] > span');
 if(scrollProgress){
  let progressFrame=0;
  const updateScrollProgress=()=>{
   const scrollableHeight=document.documentElement.scrollHeight-window.innerHeight;
   const progress=scrollableHeight>0?Math.min(100,Math.max(0,(window.scrollY/scrollableHeight)*100)):0;
   scrollProgress.style.transform='scaleX('+(progress/100)+')';
   progressFrame=0;
  };
  const requestProgressUpdate=()=>{
   if(progressFrame)return;
   progressFrame=requestAnimationFrame(updateScrollProgress);
  };
  window.addEventListener('scroll',requestProgressUpdate,{passive:true});
  window.addEventListener('resize',requestProgressUpdate,{passive:true});
  window.addEventListener('load',requestProgressUpdate,{once:true});
  requestProgressUpdate();
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

 // Header scroll state is RAF-throttled to at most one update per frame.
 if(header){
  let headerFrame=0;
  const updateHeader=()=>{
   header.classList.toggle('is-scrolled',window.scrollY>50);
   headerFrame=0;
  };
  const requestHeaderUpdate=()=>{
   if(headerFrame)return;
   headerFrame=requestAnimationFrame(updateHeader);
  };
  requestHeaderUpdate();
  window.addEventListener('scroll',requestHeaderUpdate,{passive:true});
  window.addEventListener('resize',requestHeaderUpdate,{passive:true});
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
  document.addEventListener('keydown',event=>{
   if(!mobileOverlay.classList.contains('is-open'))return;
   if(event.key==='Escape'){closeMenu();return;}
   if(event.key!=='Tab')return;
   const focusable=[...mobileOverlay.querySelectorAll('a[href],button:not(:disabled)')].filter(el=>el.getClientRects().length);
   if(!focusable.length)return;
   const first=focusable[0],last=focusable[focusable.length-1];
   if(event.shiftKey&&document.activeElement===first){event.preventDefault();last.focus();}
   else if(!event.shiftKey&&document.activeElement===last){event.preventDefault();first.focus();}
  });
  window.addEventListener('resize',()=>{if(window.innerWidth>900)closeMenu();});
 }
 // Inline header search and live shopping-bag drawer.
 const searchPanel=document.querySelector('[data-search-panel]');
 const searchInput=document.querySelector('[data-header-search-input]');
 const searchToggles=document.querySelectorAll('[data-search-toggle]');
 const closeSearch=()=>{searchPanel?.classList.remove('is-open');searchPanel?.setAttribute('aria-hidden','true')};
 searchToggles.forEach(btn=>btn.addEventListener('click',()=>{if(!searchPanel)return;searchPanel.classList.add('is-open');searchPanel.setAttribute('aria-hidden','false');window.setTimeout(()=>searchInput?.focus(),310)}));
 document.querySelector('[data-search-close]')?.addEventListener('click',closeSearch);
 document.addEventListener('keydown',e=>{if(e.key==='Escape')closeSearch()});
 const drawer=document.querySelector('[data-cart-drawer]'),scrim=document.querySelector('[data-cart-scrim]'),drawerContent=document.querySelector('[data-cart-content]');
 const closeDrawer=()=>{drawer?.classList.remove('is-open');scrim?.classList.remove('is-open');drawer?.setAttribute('aria-hidden','true');document.body.classList.remove('cart-drawer-open')};
 const escapeBagText=value=>String(value??'').replace(/[&<>"']/g,char=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[char]));
 const refreshBag=async()=>{if(!drawerContent)return;drawerContent.innerHTML='<p class="muted">Loading your selection…</p>';try{const response=await fetch('/actions/cart-summary.php',{headers:{'Accept':'application/json'}});if(!response.ok)throw new Error('Cart unavailable');const data=await response.json();document.querySelectorAll('[data-bag-count]').forEach(el=>el.textContent=data.count);const count=document.querySelector('[data-drawer-count]');if(count)count.textContent=' ('+data.count+')';if(!data.items.length){drawerContent.innerHTML='<div class="ikki-cart-empty"><p>Your next signature awaits.</p><a href="/shop.php" class="ikki-btn--primary">EXPLORE THE COLLECTION ↗</a></div>';return;}drawerContent.innerHTML=data.items.map(item=>'<article class="ikki-cart-line"><img src="'+escapeBagText(item.image)+'" alt=""><div class="ikki-cart-line-info"><strong>'+escapeBagText(item.name)+'</strong><span>'+escapeBagText(item.variant)+'</span><small>'+escapeBagText(item.price)+' × '+Number(item.quantity)+'</small></div><b>'+escapeBagText(item.line_total)+'</b></article>').join('')+'<div class="ikki-cart-subtotal"><span>Subtotal</span><strong>'+data.subtotal+'</strong></div><a class="ikki-btn--primary ikki-cart-checkout" href="/checkout.php">CHECKOUT ↗</a><a class="ikki-cart-view" href="/cart.php">VIEW / EDIT BAG</a>';}catch(error){drawerContent.innerHTML='<p class="muted">Your bag could not be loaded. <a href="/cart.php">View your bag</a>.</p>';}};
 const openDrawer=()=>{if(!drawer)return;drawer.classList.add('is-open');scrim?.classList.add('is-open');drawer.setAttribute('aria-hidden','false');document.body.classList.add('cart-drawer-open');refreshBag();};
 document.querySelectorAll('[data-cart-drawer-trigger]').forEach(btn=>btn.addEventListener('click',e=>{e.preventDefault();openDrawer()}));
 document.querySelector('[data-cart-close]')?.addEventListener('click',closeDrawer);scrim?.addEventListener('click',closeDrawer);
 document.addEventListener('keydown',e=>{if(e.key==='Escape')closeDrawer()});
 document.querySelectorAll('.ikki-product-purchase,.quick-view-purchase').forEach(form=>form.addEventListener('submit',async e=>{
  e.preventDefault();const button=form.querySelector('[type=submit]');if(button)button.disabled=true;
  try{const response=await fetch(form.action,{method:'POST',body:new FormData(form),headers:{'Accept':'application/json','X-Requested-With':'XMLHttpRequest'}});const data=await response.json();if(!response.ok||!data.success)throw new Error(data.message||'Unable to add this item');
   document.querySelectorAll('[data-bag-count]').forEach(el=>{el.textContent=data.count;el.classList.remove('is-pulsing');void el.offsetWidth;el.classList.add('is-pulsing')});openDrawer();
  }catch(error){form.submit()}finally{if(button)button.disabled=false;}
 }));

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


 // Footer content: reveal once as the footer enters the viewport.
 const footer=document.querySelector('.ikki-footer');
 if(footer){
  if(reducedMotion||!('IntersectionObserver' in window))footer.classList.add('is-footer-visible');
  else{
   const footerObserver=new IntersectionObserver(entries=>{
    if(!entries.some(entry=>entry.isIntersecting))return;
    footer.classList.add('is-footer-visible');
    footerObserver.disconnect();
   },{threshold:.08,rootMargin:'0px 0px -24px 0px'});
   footerObserver.observe(footer);
  }
 }

 // Newsletter email feedback. The subscription endpoint is not connected yet.
 // Shared floating back-to-top control with RAF-throttled visibility and click feedback.
 const backToTop=document.querySelector('[data-back-to-top]');
 if(backToTop){
  let backToTopFrame=0;
  const updateBackToTop=()=>{
   backToTop.classList.toggle('is-visible',window.scrollY>400);
   backToTopFrame=0;
  };
  const requestBackToTopUpdate=()=>{
   if(backToTopFrame)return;
   backToTopFrame=requestAnimationFrame(updateBackToTop);
  };
  requestBackToTopUpdate();
  window.addEventListener('scroll',requestBackToTopUpdate,{passive:true});
  window.addEventListener('resize',requestBackToTopUpdate,{passive:true});
  backToTop.addEventListener('click',()=>{
   backToTop.classList.remove('is-bouncing');
   void backToTop.offsetWidth;
   backToTop.classList.add('is-bouncing');
   window.scrollTo({top:0,behavior:reducedMotion?'auto':'smooth'});
  });
  backToTop.addEventListener('animationend',()=>backToTop.classList.remove('is-bouncing'));
 }
 // Lazy-load non-critical imagery while keeping hero and main product artwork eager.
 document.querySelectorAll('img:not([loading])').forEach(img=>{
  if(!img.closest('.home-hero')&&!img.closest('.product-visual'))img.loading='lazy';
 });

 // === COLLECTION FILTERING MODULE ===
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
 const collectionSearch=document.querySelector('#collection-search');
 let collectionSearchTerm='';

 // Collection toolbar: fade-up entrance and sticky-state shadow, RAF-throttled.
 const collectionToolbar=document.querySelector('[data-collection-toolbar]');
 const collectionToolbarWrap=document.querySelector('.collection-toolbar-wrap');
 const siteHeader=document.querySelector('[data-site-header]');
 if(collectionToolbar&&collectionToolbarWrap){
  const revealToolbar=()=>collectionToolbar.classList.add('is-toolbar-visible');
  if(reducedMotion||!('IntersectionObserver'in window))revealToolbar();
  else{const toolbarObserver=new IntersectionObserver(entries=>entries.forEach(entry=>{if(entry.isIntersecting){revealToolbar();toolbarObserver.disconnect();}}),{threshold:.08});toolbarObserver.observe(collectionToolbar);}
  const updateToolbarStickyOffset=()=>{
   const headerHeight=siteHeader?Math.round(siteHeader.getBoundingClientRect().height):68;
   document.documentElement.style.setProperty('--ikki-toolbar-sticky-top',headerHeight+'px');
  };
  let toolbarScrollFrame=0;
  const updateToolbarStuck=()=>{
   const headerHeight=siteHeader?siteHeader.getBoundingClientRect().height:68;
   const stuck=collectionToolbarWrap.getBoundingClientRect().top<=headerHeight+1;
   collectionToolbarWrap.classList.toggle('is-stuck',stuck);
   toolbarScrollFrame=0;
  };
  const requestToolbarUpdate=()=>{if(toolbarScrollFrame)return;toolbarScrollFrame=requestAnimationFrame(updateToolbarStuck);};
  updateToolbarStickyOffset();requestToolbarUpdate();
  window.addEventListener('scroll',requestToolbarUpdate,{passive:true});
  window.addEventListener('resize',()=>{updateToolbarStickyOffset();requestToolbarUpdate();},{passive:true});
 }

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
  const matchesFilter=(card,selected)=>{
   const categoryMatch=selected==='all'||card.dataset.category===selected;
   const haystack=(card.textContent||'').toLocaleLowerCase();
   return categoryMatch&&(!collectionSearchTerm||haystack.includes(collectionSearchTerm));
  };
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
   filterTimer=window.setTimeout(revealResults,showLoading?360:0);
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
  collectionSearch?.addEventListener('input',()=>{
   collectionSearchTerm=collectionSearch.value.trim().toLocaleLowerCase();
   const activeButton=collectionFilters.find(button=>button.classList.contains('is-active'))||collectionFilters[0];
   if(activeButton)applyFilter(activeButton,false);
  });
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
 // === MODALS MODULE ===
 // === Collection Page: Quick View Modal ===
 // Hidden native dialog; product content and purchase options are populated on trigger.
 const quickDialog=document.querySelector('#collection-quick-dialog');
 if(quickDialog){
  const $=selector=>quickDialog.querySelector(selector);
  const quickForm=$('[data-quick-form]'),variantInput=quickForm.querySelector('[name="variant_id"]'),qtyInput=quickForm.querySelector('[name="quantity"]');
  const qtyOutput=$('[data-quantity-value]'),sizeWrap=$('[data-quick-sizes]'),sizeButtons=$('[data-size-buttons]');
  const addButton=$('[data-quick-add]'),unavailable=$('[data-quick-unavailable]');
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
   const productName=trigger.dataset.name||'';
   if(!productName)return;
   returnFocus=trigger;closing=false;
   $('#quick-view-title').textContent=productName;
   $('[data-quick-price]').textContent=trigger.dataset.price||'Price on request';
   const image=$('[data-quick-image]');image.src=trigger.dataset.image||'/assets/images/noor.svg';image.alt=productName+' fragrance bottle';image.width=800;image.height=1000;image.loading='lazy';image.decoding='async';
   $('[data-quick-description]').textContent=trigger.dataset.description||'Discover the olfactive story of this IKKI fragrance.';
   const noteFields=[['[data-note-top]',trigger.dataset.top],['[data-note-heart]',trigger.dataset.heart],['[data-note-base]',trigger.dataset.base]];
   let visibleNotes=0;
   noteFields.forEach(([selector,value])=>{const node=$(selector);const note=(value||'').trim();node.textContent=note;node.hidden=!note;if(note)visibleNotes++;});
   const notesPanel=$('[data-quick-notes]');if(notesPanel)notesPanel.hidden=visibleNotes===0;
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
 // === FORMS MODULE ===
 // === CONTACT PAGE: FORM VALIDATION ===
 // Contact enquiries are validated and handed off to the official IKKI WhatsApp number.
 const contactForm=document.querySelector('#ikki-contact-form');
 if(contactForm){
  const feedback=document.querySelector('#contact-form-feedback');
  const submitButton=contactForm.querySelector('.contact-submit');
  const fields=[...contactForm.querySelectorAll('#contact-name,#contact-email,#contact-subject,#contact-message')];
  const validate=field=>{
   const value=field.value.trim();
   let message='';
   if(field.id==='contact-name'&&value.length<2)message='Please enter your name (at least 2 characters).';
   else if(field.id==='contact-email'&&!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value))message='Please enter a valid email address.';
   else if(field.id==='contact-subject'&&!value)message='Please select a subject.';
   else if(field.id==='contact-message'&&value.length<10)message='Please enter at least 10 characters.';
   const wrap=field.closest('.contact-field');
   wrap?.classList.toggle('is-invalid',Boolean(message));
   const error=wrap?.querySelector('.field-error');
   if(error)error.textContent=message;
   field.setAttribute('aria-invalid',String(Boolean(message)));
   return !message;
  };
  fields.forEach(field=>{
   field.addEventListener('blur',()=>validate(field));
   field.addEventListener('input',()=>{if(field.getAttribute('aria-invalid')==='true')validate(field);});
   field.addEventListener('change',()=>{if(field.getAttribute('aria-invalid')==='true')validate(field);});
  });
  contactForm.addEventListener('submit',event=>{
   event.preventDefault();
   let firstInvalid=null;
   fields.forEach(field=>{if(!validate(field)&&!firstInvalid)firstInvalid=field;});
   if(firstInvalid){
    feedback.textContent='Please correct the highlighted fields.';
    feedback.classList.add('is-visible','is-error');
    firstInvalid.focus();
    return;
   }
   const data=new FormData(contactForm);
   const message=[
    'IKKI WEBSITE ENQUIRY',
    'Name: '+String(data.get('name')||'').trim(),
    'Email: '+String(data.get('email')||'').trim(),
    'Subject: '+String(data.get('subject')||'').trim(),
    'Message: '+String(data.get('message')||'').trim(),
    data.get('whatsapp_consent')?'WhatsApp contact consent: Yes':''
   ].filter(Boolean).join('\n');
   const whatsappUrl='https://wa.me/923094733789?text='+encodeURIComponent(message);
   feedback.textContent='Opening WhatsApp. Review your message there and press Send to submit your enquiry.';
   feedback.classList.add('is-visible');
   submitButton.disabled=true;
   submitButton.textContent='Continue to WhatsApp ↗';
   window.location.assign(whatsappUrl);
  });
 }
 // Auto-rotating customer testimonials; // Auto-rotating customer testimonials; pause while hovered, focused or touched.
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
  const words=quoteText.match(/\S+/g)||[];
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

 // === CART MODULE ===
 // Shopping bag navigation uses the server-backed cart page.
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



 // Numbered homepage section markers: reveal the index and its gold connector once in view.
 const sectionMarkers=[...document.querySelectorAll('.home-section-marker')];
 if(sectionMarkers.length){
  if(reducedMotion||!('IntersectionObserver' in window)){
   sectionMarkers.forEach(marker=>marker.classList.add('is-number-visible'));
  }else{
   const markerObserver=new IntersectionObserver(entries=>{
    entries.forEach(entry=>{
     if(!entry.isIntersecting)return;
     entry.target.classList.add('is-number-visible');
     markerObserver.unobserve(entry.target);
    });
   },{threshold:.35,rootMargin:'0px 0px -8% 0px'});
   sectionMarkers.forEach(marker=>markerObserver.observe(marker));
  }
 }

 // Signature collection cards: reveal as one staggered row on scroll.
 const signatureCards=[...document.querySelectorAll('.home-products--editorial .home-product')];
 if(signatureCards.length){
  signatureCards.forEach((card,index)=>card.style.setProperty('--card-reveal-delay',(index*100)+'ms'));
  if(reducedMotion||!('IntersectionObserver'in window))signatureCards.forEach(card=>card.classList.add('is-card-visible'));
  else{
   const cardsObserver=new IntersectionObserver(entries=>{
    if(!entries.some(entry=>entry.isIntersecting))return;
    signatureCards.forEach(card=>card.classList.add('is-card-visible'));
    cardsObserver.disconnect();
   },{threshold:.12,rootMargin:'0px 0px -35px 0px'});
   const cardsRow=document.querySelector('.home-products--editorial');
   if(cardsRow)cardsObserver.observe(cardsRow);else signatureCards.forEach(card=>card.classList.add('is-card-visible'));
  }
 }

 // Lifestyle feature: image-first reveal, then copy in a 200ms stagger.
 const lifestyleFeature=document.querySelector('.home-feature');
 if(lifestyleFeature){
  lifestyleFeature.classList.add('feature-motion-ready');
  const showLifestyleFeature=()=>lifestyleFeature.classList.add('is-feature-visible');
  if(reducedMotion||!('IntersectionObserver'in window))showLifestyleFeature();
  else{
   const lifestyleObserver=new IntersectionObserver(entries=>{
    if(!entries.some(entry=>entry.isIntersecting))return;
    showLifestyleFeature();lifestyleObserver.disconnect();
   },{threshold:.16,rootMargin:'0px 0px -35px 0px'});
   lifestyleObserver.observe(lifestyleFeature);
  }
  // Desktop-only, RAF-coalesced parallax; the image drifts at a restrained fraction of scroll.
  const lifestyleImage=lifestyleFeature.querySelector('.home-feature-art img');
  if(lifestyleImage&&!reducedMotion&&window.matchMedia('(min-width: 900px) and (hover: hover)').matches){
   let lifestyleFrame=0;
   const updateLifestyleParallax=()=>{
    if(lifestyleFrame)return;
    lifestyleFrame=requestAnimationFrame(()=>{
     lifestyleFrame=0;
     const rect=lifestyleFeature.getBoundingClientRect();
     const travel=(rect.top+rect.height/2-window.innerHeight/2)*-.12;
     lifestyleImage.style.setProperty('--lifestyle-parallax-y',travel.toFixed(2)+'px');
    });
   };
   updateLifestyleParallax();
   window.addEventListener('scroll',updateLifestyleParallax,{passive:true});
   window.addEventListener('resize',updateLifestyleParallax);
  }
 }

 // Discovery / Expression / Moments: reveal the three cards in a 120ms sequence.
 const valueCards=[...document.querySelectorAll('.home-values .home-value')];
 if(valueCards.length){
  valueCards.forEach((card,index)=>{card.style.setProperty('--value-reveal-delay',(index*120)+'ms');card.classList.add('is-value-motion-ready')});
  const revealValueCards=()=>valueCards.forEach(card=>card.classList.add('is-value-visible'));
  if(reducedMotion||!('IntersectionObserver'in window))revealValueCards();
  else{
   const valueObserver=new IntersectionObserver(entries=>{
    if(!entries.some(entry=>entry.isIntersecting))return;
    revealValueCards();valueObserver.disconnect();
   },{threshold:.12,rootMargin:'0px 0px -30px 0px'});
   valueObserver.observe(valueCards[0].parentElement);
  }
 }

 // Homepage circle ornaments: reveal on entry, then rotate quietly at one revolution per minute.
 const circleMotifs=[...document.querySelectorAll('.home-circle-motif')];
 if(circleMotifs.length){
  if(reducedMotion||!('IntersectionObserver' in window)){
   circleMotifs.forEach(motif=>motif.classList.add('is-circle-visible'));
  }else{
   const circleObserver=new IntersectionObserver(entries=>{
    entries.forEach(entry=>{
     if(!entry.isIntersecting)return;
     entry.target.classList.add('is-circle-visible');
     circleObserver.unobserve(entry.target);
    });
   },{threshold:.2,rootMargin:'0px 0px -6% 0px'});
   circleMotifs.forEach(motif=>circleObserver.observe(motif));
  }
 }

 // Homepage-only depth motion: restrained pointer tilt and RAF-coalesced image drift.
 const homeHero=document.querySelector('.home-hero--editorial');
 if(homeHero&&!reducedMotion){
  const visual=homeHero.querySelector('.home-hero-visual');
  const bottle=homeHero.querySelector('.home-hero-bottle');
  const finePointer=window.matchMedia('(min-width: 900px) and (hover: hover) and (pointer: fine)');
  if(visual&&bottle&&finePointer.matches){
   let pointerX=0,pointerY=0,frame=0;
   const renderDepth=()=>{
    frame=0;
    visual.style.transform='perspective(1100px) rotateY('+pointerX+'deg) rotateX('+(-pointerY)+'deg)';
    bottle.style.translate=(pointerX*.55)+'px '+(pointerY*.45)+'px';
   };
   visual.addEventListener('pointermove',event=>{
    const rect=visual.getBoundingClientRect();
    pointerX=((event.clientX-rect.left)/rect.width-.5)*5;
    pointerY=((event.clientY-rect.top)/rect.height-.5)*4;
    if(!frame)frame=requestAnimationFrame(renderDepth);
   },{passive:true});
   visual.addEventListener('pointerleave',()=>{
    pointerX=0;pointerY=0;
    if(!frame)frame=requestAnimationFrame(renderDepth);
   });
  }
  // The philosophy statement has its own carefully ordered entrance sequence.
  const philosophy=document.querySelector('.home-philosophy');
  if(philosophy){
   const revealPhilosophy=()=>philosophy.classList.add('is-philosophy-visible');
   if(reducedMotion||!('IntersectionObserver'in window))revealPhilosophy();
   else{
    const philosophyObserver=new IntersectionObserver(entries=>{
     if(!entries.some(entry=>entry.isIntersecting))return;
     revealPhilosophy();
     philosophyObserver.disconnect();
    },{threshold:.18,rootMargin:'0px 0px -35px 0px'});
    philosophyObserver.observe(philosophy);
   }
  }

  // Closing destination CTA: ordered entrance for its eyebrow, headline, copy and actions.
  const finalCta=document.querySelector('.home-final-cta');
  if(finalCta){
   const revealFinalCta=()=>finalCta.classList.add('is-final-cta-visible');
   if(reducedMotion||!('IntersectionObserver'in window))revealFinalCta();
   else{
    const finalCtaObserver=new IntersectionObserver(entries=>{
     if(!entries.some(entry=>entry.isIntersecting))return;
     revealFinalCta();finalCtaObserver.disconnect();
    },{threshold:.16,rootMargin:'0px 0px -35px 0px'});
    finalCtaObserver.observe(finalCta);
   }
  }

  // Stagger home content as each section enters the viewport.
  const revealTargets=[...document.querySelectorAll('.home-section-heading,.home-values-heading,.home-manifesto')];
  if('IntersectionObserver'in window){
   const revealObserver=new IntersectionObserver(entries=>entries.forEach(entry=>{
    if(!entry.isIntersecting)return;
    entry.target.classList.add('ikki-in-view');
    revealObserver.unobserve(entry.target);
   }),{threshold:.14,rootMargin:'0px 0px -35px 0px'});
   revealTargets.forEach((element,index)=>{
    element.style.setProperty('--ikki-reveal-delay',Math.min(index%4,3)*85+'ms');
    element.classList.add('ikki-motion-ready');
    revealObserver.observe(element);
   });
  }
 }

});