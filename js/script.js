(function () {
  'use strict';

  // === Sticky header shrink ===
  const header = document.getElementById('siteHeader');
  if (header) {
    const onScroll = () => {
      header.classList.toggle('scrolled', window.scrollY > 80);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  // === Feedback slider ===
  document.querySelectorAll('[data-slider]').forEach((slider) => {
    const track = slider.querySelector('[data-track]');
    const prev = slider.querySelector('[data-prev]');
    const next = slider.querySelector('[data-next]');
    const dotsContainer = slider.querySelector('[data-dots]');
    if (!track) return;
    const items = Array.from(track.children);

    const scrollToIndex = (i) => {
      const item = items[i];
      if (item) item.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
    };

    if (dotsContainer) {
      items.forEach((_, i) => {
        const dot = document.createElement('button');
        dot.type = 'button';
        dot.setAttribute('aria-label', `Go to slide ${i + 1}`);
        dot.addEventListener('click', () => scrollToIndex(i));
        dotsContainer.appendChild(dot);
      });
    }

    const currentIndex = () => {
      const center = track.scrollLeft + track.clientWidth / 2;
      let closest = 0;
      let closestDist = Infinity;
      items.forEach((item, i) => {
        const c = item.offsetLeft + item.clientWidth / 2;
        const d = Math.abs(center - c);
        if (d < closestDist) { closestDist = d; closest = i; }
      });
      return closest;
    };

    const updateDots = () => {
      if (!dotsContainer) return;
      const idx = currentIndex();
      Array.from(dotsContainer.children).forEach((d, i) => d.classList.toggle('active', i === idx));
    };

    if (prev) prev.addEventListener('click', () => scrollToIndex(Math.max(0, currentIndex() - 1)));
    if (next) next.addEventListener('click', () => scrollToIndex(Math.min(items.length - 1, currentIndex() + 1)));
    track.addEventListener('scroll', updateDots, { passive: true });
    updateDots();
  });

  // === Subscribe popup ===
  const popup = document.getElementById('subscribePopup');
  if (popup) {
    const STORAGE_KEY = 'ameer_popup_dismissed';
    const SEVEN_DAYS = 7 * 24 * 60 * 60 * 1000;
    const dismissedAt = Number(localStorage.getItem(STORAGE_KEY) || 0);
    const isDismissed = dismissedAt && (Date.now() - dismissedAt) < SEVEN_DAYS;

    const open = () => {
      if (isDismissed) return;
      popup.hidden = false;
      const firstFocusable = popup.querySelector('input, button');
      if (firstFocusable) firstFocusable.focus();
    };
    const close = () => {
      popup.hidden = true;
      localStorage.setItem(STORAGE_KEY, String(Date.now()));
    };

    const closeBtn = popup.querySelector('[data-close]');
    if (closeBtn) closeBtn.addEventListener('click', close);
    popup.addEventListener('click', (e) => { if (e.target === popup) close(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !popup.hidden) close(); });

    if (!isDismissed) {
      setTimeout(open, 8000);
      document.addEventListener('mouseleave', (e) => {
        if (e.clientY <= 0 && popup.hidden) open();
      });
    }
  }

  // === Scroll reveal ===
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const reveals = document.querySelectorAll('.reveal');
  if (reduced) {
    document.documentElement.classList.add('reduced-motion');
    reveals.forEach(el => el.classList.add('in-view'));
  } else if ('IntersectionObserver' in window) {
    document.documentElement.classList.add('js-reveal');
    const io = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('in-view');
          io.unobserve(entry.target);
        }
      });
    }, { rootMargin: '0px 0px -10% 0px', threshold: 0.05 });
    reveals.forEach(el => io.observe(el));
    // Immediate check for already-visible elements (e.g. tall screens, programmatic scroll)
    requestAnimationFrame(() => {
      reveals.forEach(el => {
        const rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight && rect.bottom > 0) {
          el.classList.add('in-view');
          io.unobserve(el);
        }
      });
    });
    // Safety: if nothing has triggered after 2s (e.g. headless capture), force all
    setTimeout(() => reveals.forEach(el => el.classList.add('in-view')), 2000);
  } else {
    reveals.forEach(el => el.classList.add('in-view'));
  }
})();

(function initMobileMenu() {
  const header = document.getElementById('siteHeader');
  if (!header) return;
  const toggle = header.querySelector('.menu-toggle');
  const nav = header.querySelector('.site-nav');
  if (!toggle || !nav) return;

  const setOpen = (open) => {
    header.classList.toggle('menu-open', open);
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
  };

  toggle.addEventListener('click', () => {
    setOpen(!header.classList.contains('menu-open'));
  });
  nav.addEventListener('click', (e) => {
    if (e.target.closest('a')) setOpen(false);
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && header.classList.contains('menu-open')) setOpen(false);
  });
})();
