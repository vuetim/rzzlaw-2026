(() => {
 'use strict';
 // Bump the policy version when adding a provider or changing processing purposes.
 const key='rzz-privacy-v2', version=2, expiry=180*86400000;
 const banner=document.querySelector('[data-cookie-banner]');
 const dialog=document.querySelector('#privacy-dialog');
 const external=document.querySelector('#consent-external');
 const analytics=document.querySelector('#consent-analytics');
 let choice=null;
 try {
  const saved=JSON.parse(localStorage.getItem(key));
  if(saved?.version===version && typeof saved.time==='number' && saved.time<=Date.now() && Date.now()-saved.time<expiry) {
   choice={version,essential:true,external:saved.external===true,analytics:saved.analytics===true,time:saved.time};
  }
 } catch(e) {}
 function state(){return choice?{...choice}:{version,essential:true,external:false,analytics:false};}
 function notify(){window.dispatchEvent(new CustomEvent('rzz:consent',{detail:state()}));}
 // Future integrations must check state before loading any analytics resource.
 window.rzzConsent=Object.freeze({get:state});
 function maps(allow){
  document.querySelectorAll('[data-map-src]').forEach(el=>{
   if(!el.dataset.original)el.dataset.original=el.innerHTML;
   if(allow){
    if(el.querySelector('iframe'))return;
    const frame=document.createElement('iframe');
    frame.src=el.dataset.mapSrc;frame.title='Map · '+(el.dataset.mapLabel||'Prishtina, Kosovo');
    frame.loading='lazy';frame.referrerPolicy='no-referrer';el.replaceChildren(frame);
   } else if(el.querySelector('iframe'))el.innerHTML=el.dataset.original;
  });
 }
 function save(allowExternal,allowAnalytics){
  choice={version,essential:true,external:allowExternal===true,analytics:allowAnalytics===true,time:Date.now()};
  try{localStorage.setItem(key,JSON.stringify(choice));}catch(e){}
  banner.hidden=true;dialog.close();external.checked=choice.external;analytics.checked=choice.analytics;
  maps(choice.external);notify();
 }
 document.addEventListener('click',e=>{
  if(e.target.closest('[data-privacy-open]')){
   external.checked=!!choice?.external;analytics.checked=!!choice?.analytics;
   dialog.showModal();document.body.classList.add('modal-open');
  }
  if(e.target.closest('[data-privacy-close]'))dialog.close();
  if(e.target.closest('[data-cookie-accept]'))save(true,true);
  if(e.target.closest('[data-cookie-reject]'))save(false,false);
  if(e.target.closest('[data-cookie-save]'))save(external.checked,analytics.checked);
 });
 if(!choice)banner.hidden=false;
 maps(!!choice?.external);notify();
})();
