(function () {
  'use strict';

  // === Custom gallery thumbnail switching ===
  const mainImg = document.getElementById('galleryMain');
  const thumbs = document.querySelectorAll('.product-thumbnails .thumb');
  if (mainImg && thumbs.length) {
    thumbs.forEach((thumb) => {
      thumb.addEventListener('click', () => {
        const full = thumb.getAttribute('data-full');
        if (!full) return;
        mainImg.src = full;
        thumbs.forEach((t) => t.classList.remove('is-active'));
        thumb.classList.add('is-active');
      });
    });
  }

  // === Quantity stepper ===
  // WooCommerce renders <div class="quantity"><input class="qty">…</div> without
  // +/- buttons. Inject the themed buttons and wire them to the real input.
  document.querySelectorAll('.quantity').forEach((box) => {
    const input = box.querySelector('input.qty');
    if (!input || box.querySelector('.qty-btn')) return;

    const makeBtn = (step, label) => {
      const b = document.createElement('button');
      b.type = 'button';
      b.className = 'qty-btn';
      b.setAttribute('data-step', step);
      b.setAttribute('aria-label', label);
      b.textContent = step < 0 ? '−' : '+';
      return b;
    };

    const minus = makeBtn(-1, 'Decrease quantity');
    const plus = makeBtn(1, 'Increase quantity');
    box.insertBefore(minus, input);
    box.appendChild(plus);

    box.querySelectorAll('.qty-btn').forEach((btn) => {
      btn.addEventListener('click', () => {
        const step = parseInt(btn.getAttribute('data-step'), 10) || 0;
        const min = parseInt(input.min, 10) || 1;
        const max = parseInt(input.max, 10) || (input.max === '' ? 9999 : 99);
        let val = (parseInt(input.value, 10) || min) + step;
        val = Math.max(min, Math.min(max, val));
        input.value = val;
        input.dispatchEvent(new Event('change', { bubbles: true }));
      });
    });
  });

  // === Add-to-cart toast on AJAX success (where enabled) ===
  const toast = document.getElementById('cartToast');
  let toastTimer = null;
  if (toast && window.jQuery) {
    window.jQuery(document.body).on('added_to_cart', () => {
      toast.hidden = false;
      toast.classList.add('is-visible');
      clearTimeout(toastTimer);
      toastTimer = setTimeout(() => toast.classList.remove('is-visible'), 2200);
    });
  }

  // === Reviews: reveal the submission form on "Write a review" ===
  // The form is collapsed by default; the toggle button(s) open it, scroll it
  // into view (CSS scroll-margin-top clears the sticky header) and focus it.
  const reviewForm = document.getElementById('review-form');
  const reviewToggles = document.querySelectorAll('[data-review-toggle]');
  if (reviewForm && reviewToggles.length) {
    const revealReviewForm = () => {
      reviewForm.classList.remove('is-collapsed');
      document.querySelectorAll('.review-cta').forEach((c) => { c.hidden = true; });
      reviewToggles.forEach((b) => b.setAttribute('aria-expanded', 'true'));
      reviewForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
      const field = reviewForm.querySelector('select, textarea, a');
      if (field) {
        setTimeout(() => {
          try { field.focus({ preventScroll: true }); } catch (e) { field.focus(); }
        }, 450);
      }
    };
    reviewToggles.forEach((btn) => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        revealReviewForm();
      });
    });
    // Deep link or return-from-submit (#review-form / #respond / #comment-…) opens it.
    if (/^#(review-form|respond|comment-)/.test(window.location.hash)) {
      revealReviewForm();
    }
  }

  // === Sticky mobile buy bar — show after scrolling past the main buy box ===
  const stickyBar = document.getElementById('stickyBuy');
  const buyBox = document.querySelector('.product-main form.cart');
  if (stickyBar && buyBox) {
    const onScroll = () => {
      const past = buyBox.getBoundingClientRect().bottom < 0;
      stickyBar.classList.toggle('is-visible', past);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }
})();
