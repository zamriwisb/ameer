(function () {
  'use strict';

  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduced) document.documentElement.classList.add('reduced-motion');

  // === Sticky header shrink ===
  const header = document.getElementById('siteHeader');
  if (header) {
    const onScroll = () => header.classList.toggle('scrolled', window.scrollY > 80);
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  // === Scroll progress bar ===
  const progressBar = document.getElementById('scrollProgressBar');
  if (progressBar) {
    const updateProgress = () => {
      const scrollTop = window.scrollY;
      const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
      const pct = scrollHeight > 0 ? (scrollTop / scrollHeight) * 100 : 0;
      progressBar.style.width = pct + '%';
    };
    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();
  }

  // === Hero parallax (scroll-driven + mouse) ===
  const parallaxEls = document.querySelectorAll('[data-parallax]');
  let mouseX = 0, mouseY = 0;
  const applyParallax = () => {
    const y = window.scrollY;
    parallaxEls.forEach(el => {
      const speed = parseFloat(el.getAttribute('data-parallax')) || 0.2;
      const tx = mouseX * speed * 30;
      const ty = mouseY * speed * 20 + (-y * speed);
      el.style.transform = `translate3d(${tx}px, ${ty}px, 0)`;
    });
  };
  if (!reduced && parallaxEls.length) {
    let ticking = false;
    window.addEventListener('scroll', () => {
      if (!ticking) {
        requestAnimationFrame(() => { applyParallax(); ticking = false; });
        ticking = true;
      }
    }, { passive: true });
    applyParallax();

    const heroSection = document.querySelector('.hero');
    if (heroSection) {
      heroSection.addEventListener('mousemove', (e) => {
        const rect = heroSection.getBoundingClientRect();
        mouseX = (e.clientX - rect.left) / rect.width - 0.5;
        mouseY = (e.clientY - rect.top) / rect.height - 0.5;
        if (!ticking) {
          requestAnimationFrame(() => { applyParallax(); ticking = false; });
          ticking = true;
        }
      });
      heroSection.addEventListener('mouseleave', () => {
        mouseX = 0; mouseY = 0;
        applyParallax();
      });
    }
  }

  // === Reveal animations via IntersectionObserver ===
  if (!reduced && 'IntersectionObserver' in window) {
    const anims = document.querySelectorAll('[data-anim]');
    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const delay = entry.target.getAttribute('data-anim-delay');
          if (delay) {
            setTimeout(() => entry.target.classList.add('in-view'), parseInt(delay, 10));
          } else {
            entry.target.classList.add('in-view');
          }
          io.unobserve(entry.target);
        }
      });
    }, { rootMargin: '0px 0px -10% 0px', threshold: 0.1 });
    anims.forEach(el => io.observe(el));

    // Safety fallback
    setTimeout(() => anims.forEach(el => el.classList.add('in-view')), 2200);
  } else {
    document.querySelectorAll('[data-anim]').forEach(el => el.classList.add('in-view'));
  }

  // === Number counters ===
  const counters = document.querySelectorAll('[data-count]');
  const formatCount = (val, target, divide, suffix) => {
    if (divide > 1) return val.toFixed(1);
    if (target >= 10000) return Math.round(val / 1000) + (suffix ? '' : 'k');
    return Math.round(val);
  };
  if (!reduced && counters.length && 'IntersectionObserver' in window) {
    const countObs = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        const el = entry.target;
        const target = parseFloat(el.getAttribute('data-count')) || 0;
        const divide = parseFloat(el.getAttribute('data-count-divide')) || 1;
        const suffix = el.getAttribute('data-count-suffix') || '';
        const prefix = el.getAttribute('data-count-prefix') || '';
        const dur = 1800;
        const start = performance.now();
        const step = (now) => {
          const t = Math.min(1, (now - start) / dur);
          const eased = 1 - Math.pow(1 - t, 3);
          const val = target * eased / divide;
          el.textContent = prefix + formatCount(val, target, divide, suffix) + suffix;
          if (t < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
        countObs.unobserve(el);
      });
    }, { threshold: 0.5 });
    counters.forEach(el => countObs.observe(el));
  } else {
    counters.forEach(el => {
      const target = parseFloat(el.getAttribute('data-count')) || 0;
      const divide = parseFloat(el.getAttribute('data-count-divide')) || 1;
      const suffix = el.getAttribute('data-count-suffix') || '';
      const prefix = el.getAttribute('data-count-prefix') || '';
      el.textContent = prefix + formatCount(target / divide * divide, target, divide, suffix) + suffix;
    });
  }

  // === Feedback marquee — duplicate items for seamless loop ===
  const marqueeTrack = document.querySelector('.theme-v2 .feedback [data-track]');
  if (marqueeTrack && !reduced) {
    const items = Array.from(marqueeTrack.children);
    items.forEach(item => {
      const clone = item.cloneNode(true);
      clone.setAttribute('aria-hidden', 'true');
      marqueeTrack.appendChild(clone);
    });
  }

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
