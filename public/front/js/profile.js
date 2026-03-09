/**
 * Multi-instance carousel initializer.
 * Works for ANY number of .profile-carousel blocks on the page.
 * Just add more <div class="carousel-slide">...</div> inside .carousel-track.
 */
(function initCarousels(){
  document.querySelectorAll('.profile-carousel').forEach((carousel) => {
    const track  = carousel.querySelector('.carousel-track');
    const slides = Array.from(track.querySelectorAll('.carousel-slide'));
    const prev   = carousel.querySelector('.carousel-btn.prev');
    const next   = carousel.querySelector('.carousel-btn.next');
    const dotsEl = carousel.querySelector('.carousel-dots');

    if (!track || slides.length === 0) return;

    let index = 0;

    // Build dots to match slide count
    function buildDots(){
      dotsEl.innerHTML = '';
      slides.forEach((_, i) => {
        const b = document.createElement('button');
        b.className = 'dot' + (i === 0 ? ' active' : '');
        b.type = 'button';
        b.setAttribute('aria-label', `Go to slide ${i+1}`);
        b.addEventListener('click', () => go(i));
        dotsEl.appendChild(b);
      });
    }

    function update(){
      track.style.setProperty('--idx', index);
      [...dotsEl.children].forEach((d, i) => d.classList.toggle('active', i === index));
    }

    function go(to){
      index = (to + slides.length) % slides.length; // wrap
      update();
    }

    // controls
    prev?.addEventListener('click', () => go(index - 1));
    next?.addEventListener('click', () => go(index + 1));

    // swipe (nice on mobile)
    let startX = null;
    track.addEventListener('pointerdown', (e) => {
      startX = e.clientX;
      track.setPointerCapture(e.pointerId);
    });
    track.addEventListener('pointerup', (e) => {
      if (startX == null) return;
      const dx = e.clientX - startX;
      startX = null;
      if (Math.abs(dx) > 40) go(index + (dx < 0 ? 1 : -1));
    });

    // Init
    buildDots();
    update();
  });
})();
