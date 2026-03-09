/* =========================
   UTIL: Footer year
========================= */
(function setFooterYear(){
  const y = document.getElementById('year');
  if (y) y.textContent = new Date().getFullYear();
})();

/* =========================
   Smooth scroll for same-page anchors
========================= */
(function smoothAnchors(){
  const links = document.querySelectorAll('a[href^="#"]');
  links.forEach(a => {
    a.addEventListener('click', (e) => {
      const id = a.getAttribute('href');
      if (!id || id.length <= 1) return;
      const target = document.querySelector(id);
      if (!target) return;

      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });

      // collapse Bootstrap navbar on mobile after click
      const open = document.querySelector('.navbar-collapse.show');
      if (open && typeof bootstrap !== 'undefined') {
        new bootstrap.Collapse(open).hide();
      }
    });
  });
})();

/* =========================
   Pricing slider (Next button)
   - HTML expects:
     .pricing-slides (flex container)
     .pricing-slide  (each slide, 100% width)
     #planNext       (Next button)
========================= */
/* =========================
   Pricing slider (Next button + label)
========================= */
(function initPricingSliders(){
  function mountSlider(root){
    const slidesWrap = root.querySelector('.pricing-slides');
    const slides     = root.querySelectorAll('.pricing-slide');
    const labelEl    = root.closest('.pricing-section').querySelector('.plansLabel');
    const nextBtn    = root.closest('.pricing-section').querySelector('.btn-next-plan');
    if(!slidesWrap || !slides.length || !labelEl || !nextBtn) return;

    let index = 0;
    function setLabel(i){ labelEl.textContent = slides[i]?.dataset?.label || ''; }
    function go(to){
      index = (to + slides.length) % slides.length;
      slidesWrap.style.transform = `translateX(-${index * 100}%)`;
      slidesWrap.style.transition = 'transform .45s ease';
      setLabel(index);
    }
    nextBtn.addEventListener('click', () => go(index + 1));
    document.addEventListener('keydown', (e) => {
      const rect = root.getBoundingClientRect();
      const inView = rect.top < innerHeight && rect.bottom > 0;
      if(!inView) return;
      if(e.key === 'ArrowRight') go(index + 1);
      if(e.key === 'ArrowLeft')  go(index - 1);
    });
    go(0);
  }
  document.querySelectorAll('.pricing-slider').forEach(mountSlider);
})();

/* =========================
   Gallery lightbox (Bootstrap Modal)
========================= */
(function(){
  const items = Array.from(document.querySelectorAll('.gallery-item'));
  if(!items.length) return;

  const img = document.getElementById('gmImage');
  const cap = document.getElementById('gmCap');
  const modalEl = document.getElementById('galleryModal');
  const prevBtn = modalEl.querySelector('.gm-prev');
  const nextBtn = modalEl.querySelector('.gm-next');

  let idx = 0;

  function show(i){
    idx = (i + items.length) % items.length;
    const a = items[idx];
    img.src = a.getAttribute('data-src') || a.querySelector('img').src;
    img.alt = a.querySelector('img').alt || '';
    cap.textContent = a.getAttribute('data-cap') || img.alt || 'Photo';
  }

  items.forEach(a=>{
    a.addEventListener('click', (e)=>{
      const i = Number(a.getAttribute('data-gallery-index')) || items.indexOf(a);
      show(i);
    });
  });

  prevBtn.addEventListener('click', ()=> show(idx - 1));
  nextBtn.addEventListener('click', ()=> show(idx + 1));

  document.addEventListener('keydown', (e)=>{
    if (!modalEl.classList.contains('show')) return;
    if (e.key === 'ArrowLeft') show(idx - 1);
    if (e.key === 'ArrowRight') show(idx + 1);
  });
})();


/* =========================
   Floating Chatbot
/* =========================
   Floating Chatbot (with persistent history)
========================= */
(function chatbot() {
  const $ = (s, r=document) => r.querySelector(s);
  const panel   = $('#cbPanel');
  const launch  = $('#cbLaunch');
  const close   = $('#cbClose');
  const msgsEl  = $('#cbMsgs');
  const form    = $('#cbForm');
  const input   = $('#cbInput');

  if (!panel || !launch || !close || !msgsEl || !form || !input) return;

  // If previous version saved anything, wipe it
  try {
    localStorage.removeItem('cb_history_v2');
    localStorage.removeItem('cb_history_v1');
  } catch(_) {}

  // --- Tiny FAQ brain (same as before; tweak freely)
  const FAQ = [
    { k:/\b(price|pricing|cost|plan|package)s?\b/i, a:() =>
      "We’ve got Shared, VPS and Cloud tiers. Use the arrow in the Pricing section to switch sets, then hit “Get It Now”. Tell me your country for exact monthly price." },
    { k:/\bshared\b/i, a:() =>
      "Shared Hosting: Starter ৳300/$2.5, Business ৳550/$4.5, Pro ৳950/$7.5 (BD rates shown first). See card for other locations." },
    { k:/\bvps\b|\bvirtual\b|\bserver\b/i, a:() =>
      "VPS: VPS-1 (2vCPU/4GB/50GB), VPS-2 (4vCPU/8GB/100GB), VPS-3 (8vCPU/16GB/200GB). Use the Pricing arrow to view the VPS slide." },
    { k:/\bcloud\b|\bstorage\b|\bdrive\b/i, a:() =>
      "Cloud/Storage: Basic 100GB, Pro 500GB, Enterprise 1TB. Slide to the Cloud set under Pricing." },
    { k:/\bcontact\b|\bsupport\b|\bhelp\b|\bemail\b|\btel\b|\bphone\b/i, a:() =>
      "Contact: +8801975363707, barabdinfo@gmail.com. Hours: Sat–Thu, 9:00–18:00 (BDT)." },
    { k:/\bpayment\b|\bbkash\b|\bnagad\b|\brocket\b|\bbank\b|\bpaypal\b|\bcard\b/i, a:() =>
      "Payments: bKash, Nagad, Rocket, Bank, Card, PayPal. Use checkout to submit Txn ID/receipt." },
    { k:/\brefund\b|\bcancel\b/i, a:() =>
      "Refunds within 7 days if we can’t deliver (fees excluded). See checkout Terms & Conditions." },
    { k:/\buptime|reliable|downtime\b/i, a:() =>
      "Target 99.9% uptime with enterprise infra and 24/7 monitoring." },
    { k:/\bbarabd|BARABD|Baktier Ahmed Rony & Associates|BARA\b/i, a:() =>
          "Baktier Ahmed Rony & Associates is a government-approved software provider" },
    { k:/\bowner |Founder|founder of the firm\b/i, a:() =>
          "The founder of the firm is Mr. Baktier Ahmed Rony" },
  ];

  // --- UI helpers (stateless)
  function timeTag(){
    const d = new Date();
    const hh = d.getHours().toString().padStart(2,'0');
    const mm = d.getMinutes().toString().padStart(2,'0');
    return `<span class="cb-time">${hh}:${mm}</span>`;
  }
  function addMsg(text, who='bot'){
    const row = document.createElement('div');
    row.className = `cb-row ${who}`;
    row.innerHTML = `<div class="cb-bubble">${escapeHTML(text)}${timeTag()}</div>`;
    msgsEl.appendChild(row);
    msgsEl.scrollTop = msgsEl.scrollHeight;
  }
  function addTyping(){
    const row = document.createElement('div');
    row.className = 'cb-row bot';
    row.id = 'cbTyping';
    row.innerHTML = `<div class="cb-bubble"><span class="cb-typing"><span></span><span></span><span></span></span></div>`;
    msgsEl.appendChild(row);
    msgsEl.scrollTop = msgsEl.scrollHeight;
  }
  function removeTyping(){
    const t = document.getElementById('cbTyping');
    if (t) t.remove();
  }
  function escapeHTML(s){
    return s.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
  }

  // --- Minimal brain / stub for future API
  function localBrain(q){
    for (const f of FAQ) if (f.k.test(q)) return f.a(q);
    return "I didn’t quite get that. Ask about pricing, VPS, cloud storage, payments, or type “contact”.";
  }
  async function fetchChat(message){
    // Hook for real backend if needed later
    await new Promise(r => setTimeout(r, 500));
    return localBrain(message);
  }

  // --- Open/Close
  function open(){ panel.hidden = false; setTimeout(()=>input.focus(), 50); }
  function closePanel(){ panel.hidden = true; }
  launch.addEventListener('click', open);
  close.addEventListener('click', closePanel);

  // --- Submit (fresh every time)
  form.addEventListener('submit', async (e)=>{
    e.preventDefault();
    const text = input.value.trim();
    if (!text) return;
    addMsg(text, 'user');
    input.value = '';

    addTyping();
    try{
      const reply = await fetchChat(text);
      removeTyping();
      addMsg(reply, 'bot');
    }catch(err){
      removeTyping();
      addMsg("Sorry, I’m having trouble right now. Please try again or use the Contact section.", 'bot');
    }
  });

  // --- Reset chat content on load (and on BFCache return)
  function resetChat(){
    msgsEl.innerHTML = '';
    addMsg("Hi! I’m your assistant. Ask me about plans, pricing, VPS, or how to pay. 👋", 'bot');
  }
  resetChat();
  // If page is restored from back/forward cache, also reset
  window.addEventListener('pageshow', (e) => { if (e.persisted) resetChat(); });
})();




// "contact"
// footer-year.js
(function setFooterYear(){
  var el = document.getElementById('year');
  if (el) el.textContent = new Date().getFullYear();
})();


// Scroll to Top logic
const scrollBtn = document.getElementById('scrollTopBtn');
window.addEventListener('scroll', () => {
  if (window.scrollY > 300) {
    scrollBtn.classList.add('show');
  } else {
    scrollBtn.classList.remove('show');
  }
});
scrollBtn.addEventListener('click', () => {
  window.scrollTo({ top: 0, behavior: 'smooth' });
});


