(function () {
  'use strict';

  // === Gallery thumbnail switching ===
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
  const qtyInput = document.querySelector('.quantity .qty');
  document.querySelectorAll('.quantity .qty-btn').forEach((btn) => {
    btn.addEventListener('click', () => {
      if (!qtyInput) return;
      const step = parseInt(btn.getAttribute('data-step'), 10) || 0;
      const min = parseInt(qtyInput.min, 10) || 1;
      const max = parseInt(qtyInput.max, 10) || 99;
      let val = (parseInt(qtyInput.value, 10) || min) + step;
      val = Math.max(min, Math.min(max, val));
      qtyInput.value = val;
    });
  });

  // === Add-to-cart toast (inert demo) ===
  const toast = document.getElementById('cartToast');
  let toastTimer = null;
  const showToast = () => {
    if (!toast) return;
    toast.classList.add('is-visible');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove('is-visible'), 2200);
  };
  document.querySelectorAll('[data-add-to-cart], [data-buy-now]').forEach((btn) => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      showToast();
    });
  });

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
