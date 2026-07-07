/**
 * Salon Win — main.js
 * Obsługa: header scroll, parallax hero, animacje wejścia,
 * formularz rezerwacji (AJAX), slider opinii, filtry win,
 * mobilne menu, newsletter.
 */

(function () {
  'use strict';

  /* ============================================================
     HELPERS
  ============================================================ */
  const qs  = (sel, ctx = document) => ctx.querySelector(sel);
  const qsa = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];
  const on  = (el, ev, fn, opts) => el && el.addEventListener(ev, fn, opts);

  /* ============================================================
     1. HEADER — scroll behaviour
  ============================================================ */
  const initHeader = () => {
    const header = qs('#site-header');
    if (!header) return;

    const update = () => {
      const scrolled = window.scrollY > 60;
      header.classList.toggle('scrolled', scrolled);
    };

    on(window, 'scroll', update, { passive: true });
    update();
  };

  /* ============================================================
     2. HERO PARALLAX
  ============================================================ */
  const initParallax = () => {
    const bg = qs('#hero-bg');
    if (!bg || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const move = () => {
      const y = window.scrollY;
      bg.style.transform = `translateY(${y * 0.35}px) scale(1.05)`;
    };

    on(window, 'scroll', move, { passive: true });
  };

  /* ============================================================
     3. INTERSECTION OBSERVER — fade-up / fade-left
  ============================================================ */
  const initAnimations = () => {
    if (!('IntersectionObserver' in window)) {
      qsa('.fade-up, .fade-left').forEach(el => el.classList.add('visible'));
      return;
    }

    const obs = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    qsa('.fade-up, .fade-left').forEach(el => obs.observe(el));
  };

  /* ============================================================
     4. MOBILE NAVIGATION
  ============================================================ */
  const initMobileNav = () => {
    const hamburger = qs('#hamburger');
    const mobileNav = qs('#mobile-nav');
    if (!hamburger || !mobileNav) return;

    const toggle = (open) => {
      hamburger.classList.toggle('open', open);
      mobileNav.classList.toggle('open', open);
      hamburger.setAttribute('aria-expanded', String(open));
      document.body.style.overflow = open ? 'hidden' : '';
    };

    on(hamburger, 'click', () => toggle(!hamburger.classList.contains('open')));

    // Close on link click
    qsa('a', mobileNav).forEach(link => on(link, 'click', () => toggle(false)));

    // Close on Escape
    on(document, 'keydown', e => {
      if (e.key === 'Escape' && mobileNav.classList.contains('open')) toggle(false);
    });
  };

  /* ============================================================
     5. BOOKING FORM — AJAX
  ============================================================ */
  const initBookingForm = () => {
    const form     = qs('#booking-form');
    const feedback = qs('#booking-feedback');
    if (!form) return;

    // Min date = tomorrow
    const dateInput = qs('#booking-date', form);
    if (dateInput && !dateInput.value) {
      const tomorrow = new Date();
      tomorrow.setDate(tomorrow.getDate() + 1);
      dateInput.min = tomorrow.toISOString().split('T')[0];
    }

    const showFeedback = (msg, isError = false) => {
      if (!feedback) return;
      feedback.textContent = msg;
      feedback.style.display = 'block';
      feedback.style.color   = isError ? '#e74c3c' : 'var(--color-gold)';
    };

    const setLoading = (loading) => {
      const btn = qs('button[type="submit"]', form);
      if (!btn) return;
      btn.disabled = loading;
      const span = qs('span', btn);
      if (span) span.textContent = loading ? 'Wysyłanie...' : 'Wyślij rezerwację';
    };

    on(form, 'submit', async (e) => {
      e.preventDefault();

      // Client-side validation
      const name  = qs('#booking-name',  form).value.trim();
      const email = qs('#booking-email', form).value.trim();
      const date  = qs('#booking-date',  form).value;

      if (!name || !email || !date) {
        showFeedback('Wypełnij wymagane pola: imię, e-mail i datę.', true);
        return;
      }

      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showFeedback('Podaj poprawny adres e-mail.', true);
        return;
      }

      setLoading(true);

      const data = new FormData(form);
      data.append('action', 'salon_win_booking');

      if (typeof salonWin !== 'undefined') {
        data.append('nonce', salonWin.nonce);
      }

      try {
        const url = (typeof salonWin !== 'undefined') ? salonWin.ajaxUrl : '/wp-admin/admin-ajax.php';
        const res  = await fetch(url, { method: 'POST', body: data });
        const json = await res.json();

        if (json.success) {
          showFeedback(json.data.message || 'Rezerwacja przyjęta. Potwierdzenie wysłane na e-mail.');
          form.reset();
          if (dateInput) dateInput.min = new Date(Date.now() + 86400000).toISOString().split('T')[0];
          // Scroll to feedback
          feedback && feedback.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
          showFeedback(json.data.message || 'Wystąpił błąd. Spróbuj ponownie.', true);
        }
      } catch (err) {
        showFeedback('Problem z połączeniem. Zadzwoń do nas bezpośrednio.', true);
        console.error('[SalonWin] Booking error:', err);
      } finally {
        setLoading(false);
      }
    });
  };

  /* ============================================================
     6. NEWSLETTER FORM — AJAX
  ============================================================ */
  const initNewsletter = () => {
    const form     = qs('#newsletter-form');
    const feedback = qs('#newsletter-feedback');
    if (!form) return;

    const showFeedback = (msg, isError = false) => {
      if (!feedback) return;
      feedback.textContent = msg;
      feedback.style.display = 'block';
      feedback.style.color   = isError ? '#e74c3c' : 'var(--color-ink)';
    };

    on(form, 'submit', async (e) => {
      e.preventDefault();

      const email = qs('#newsletter-email', form).value.trim();
      if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showFeedback('Podaj poprawny adres e-mail.', true);
        return;
      }

      const btn = qs('.btn-newsletter', form);
      if (btn) { btn.disabled = true; btn.textContent = 'Zapisuję...'; }

      const data = new FormData();
      data.append('action', 'salon_win_newsletter');
      data.append('email',  email);
      if (typeof salonWin !== 'undefined') data.append('nonce', salonWin.nonce);

      try {
        const url  = (typeof salonWin !== 'undefined') ? salonWin.ajaxUrl : '/wp-admin/admin-ajax.php';
        const res  = await fetch(url, { method: 'POST', body: data });
        const json = await res.json();

        if (json.success) {
          showFeedback(json.data.message || 'Dziękujemy za zapis!');
          form.reset();
        } else {
          showFeedback(json.data.message || 'Wystąpił błąd. Spróbuj ponownie.', true);
        }
      } catch {
        showFeedback('Problem z połączeniem. Spróbuj ponownie.', true);
      } finally {
        if (btn) { btn.disabled = false; btn.textContent = 'Zapisuję się'; }
      }
    });
  };

  /* ============================================================
     7. REVIEWS SLIDER
  ============================================================ */
  const initReviewsSlider = () => {
    const track    = qs('#reviews-track');
    const btnPrev  = qs('#review-prev');
    const btnNext  = qs('#review-next');
    if (!track) return;

    const cards     = qsa('.review-card', track);
    const cardWidth = () => {
      const c = cards[0];
      if (!c) return 380;
      return c.offsetWidth + parseInt(getComputedStyle(track).gap || 24);
    };

    let current  = 0;
    let autoplay = null;
    const max    = () => Math.max(0, cards.length - getVisible());

    const getVisible = () => {
      const w = window.innerWidth;
      if (w < 640)  return 1;
      if (w < 1024) return 2;
      return 3;
    };

    const go = (idx) => {
      current = Math.max(0, Math.min(idx, max()));
      track.style.transform = `translateX(-${current * cardWidth()}px)`;
      track.style.transition = 'transform 0.55s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
    };

    on(btnPrev, 'click', () => { go(current - 1); resetAuto(); });
    on(btnNext, 'click', () => { go(current + 1 > max() ? 0 : current + 1); resetAuto(); });

    // Touch / swipe
    let touchStartX = 0;
    on(track, 'touchstart', e => { touchStartX = e.touches[0].clientX; }, { passive: true });
    on(track, 'touchend',   e => {
      const diff = touchStartX - e.changedTouches[0].clientX;
      if (Math.abs(diff) > 50) go(diff > 0 ? current + 1 : current - 1);
    });

    // Keyboard navigation
    on(track.closest('.reviews-section'), 'keydown', e => {
      if (e.key === 'ArrowLeft')  go(current - 1);
      if (e.key === 'ArrowRight') go(current + 1);
    });

    const startAuto = () => {
      autoplay = setInterval(() => go(current + 1 > max() ? 0 : current + 1), 5000);
    };

    const resetAuto = () => {
      clearInterval(autoplay);
      startAuto();
    };

    startAuto();

    // Pause on hover
    const section = track.closest('.reviews-section');
    if (section) {
      on(section, 'mouseenter', () => clearInterval(autoplay));
      on(section, 'mouseleave', startAuto);
    }

    // Recalculate on resize
    let resizeTimer;
    on(window, 'resize', () => {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(() => go(Math.min(current, max())), 200);
    });
  };

  /* ============================================================
     8. WINE FILTER TABS
  ============================================================ */
  const initWineFilter = () => {
    const tabs = qsa('.filter-tab');
    const grid = qs('#wine-grid');
    if (!tabs.length || !grid) return;

    tabs.forEach(tab => {
      on(tab, 'click', () => {
        // Update active tab
        tabs.forEach(t => {
          t.classList.remove('active');
          t.setAttribute('aria-selected', 'false');
        });
        tab.classList.add('active');
        tab.setAttribute('aria-selected', 'true');

        const filter = tab.dataset.filter;
        const cards  = qsa('.wine-card', grid);

        cards.forEach(card => {
          const type = card.dataset.type || '';
          const show = filter === 'all' || type.includes(filter);
          card.style.opacity    = '0';
          card.style.transform  = 'translateY(12px)';

          setTimeout(() => {
            card.style.display = show ? '' : 'none';
            if (show) {
              requestAnimationFrame(() => {
                card.style.opacity   = '1';
                card.style.transform = 'translateY(0)';
                card.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
              });
            }
          }, 200);
        });
      });
    });
  };

  /* ============================================================
     9. ADD TO CART — WooCommerce AJAX
  ============================================================ */
  const initAddToCart = () => {
    on(document, 'click', async (e) => {
      const btn = e.target.closest('.btn-add-cart');
      if (!btn) return;
      e.preventDefault();

      const productId = btn.dataset.productId;
      if (!productId) return;

      const icon = qs('i', btn);
      if (icon) { icon.className = 'fas fa-spinner fa-spin'; }
      btn.disabled = true;

      const data = new FormData();
      data.append('action', 'woocommerce_ajax_add_to_cart');
      data.append('product_id', productId);
      data.append('quantity', '1');
      if (typeof salonWin !== 'undefined') data.append('nonce', salonWin.nonce);

      try {
        const url  = (typeof salonWin !== 'undefined') ? salonWin.ajaxUrl : '/wp-admin/admin-ajax.php';
        const res  = await fetch(url, { method: 'POST', body: data });
        const json = await res.json();

        if (icon) {
          icon.className = json.error ? 'fas fa-times' : 'fas fa-check';
          setTimeout(() => { icon.className = 'fas fa-plus'; btn.disabled = false; }, 2000);
        }

        // Update cart count
        const count = qs('.cart-count');
        if (count && json.cart_quantity !== undefined) {
          count.textContent = json.cart_quantity;
        }

      } catch {
        if (icon) icon.className = 'fas fa-plus';
        btn.disabled = false;
      }
    });
  };

  /* ============================================================
     10. SMOOTH SCROLL for anchor links
  ============================================================ */
  const initSmoothScroll = () => {
    on(document, 'click', (e) => {
      const link = e.target.closest('a[href^="#"]');
      if (!link) return;

      const id = link.getAttribute('href').slice(1);
      if (!id) return;

      const target = document.getElementById(id);
      if (!target) return;

      e.preventDefault();
      const headerH = qs('#site-header')?.offsetHeight || 80;
      const top     = target.getBoundingClientRect().top + window.scrollY - headerH - 16;
      window.scrollTo({ top, behavior: 'smooth' });
    });
  };

  /* ============================================================
     11. ACTIVE NAV LINK on scroll (section spy)
  ============================================================ */
  const initScrollSpy = () => {
    const sections = qsa('section[id]');
    const navLinks = qsa('.main-nav a, .mobile-nav a');
    if (!sections.length || !navLinks.length) return;

    const headerH = () => (qs('#site-header')?.offsetHeight || 80) + 20;

    const spy = () => {
      let current = '';
      sections.forEach(sec => {
        if (window.scrollY >= sec.offsetTop - headerH()) current = sec.id;
      });
      navLinks.forEach(link => {
        const href = link.getAttribute('href') || '';
        link.classList.toggle('active', href.includes(current) && current !== '');
      });
    };

    on(window, 'scroll', spy, { passive: true });
  };

  /* ============================================================
     12. LAZY LOAD — native + polyfill
  ============================================================ */
  const initLazyLoad = () => {
    if ('loading' in HTMLImageElement.prototype) return; // native support

    const imgs = qsa('img[loading="lazy"]');
    const obs  = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const img = entry.target;
          if (img.dataset.src) img.src = img.dataset.src;
          obs.unobserve(img);
        }
      });
    }, { rootMargin: '200px' });

    imgs.forEach(img => obs.observe(img));
  };

  /* ============================================================
     13. GALLERY hover — cursor
  ============================================================ */
  const initGallery = () => {
    const items = qsa('.gallery-item');
    if (!items.length) return;

    items.forEach(item => {
      on(item, 'click', () => {
        const img = qs('img', item);
        if (!img) return;

        // Simple lightbox — open full image in a new tab
        // Replace with a proper lightbox plugin (e.g., Fancybox) in production
        const src  = img.getAttribute('src');
        const alt  = img.getAttribute('alt') || '';
        if (!src) return;

        const overlay = document.createElement('div');
        overlay.setAttribute('role', 'dialog');
        overlay.setAttribute('aria-modal', 'true');
        overlay.setAttribute('aria-label', alt);
        overlay.style.cssText = `
          position:fixed; inset:0; z-index:9999;
          background:rgba(10,5,0,0.95);
          display:flex; align-items:center; justify-content:center;
          cursor:zoom-out; animation: fadeIn 0.2s ease;
        `;

        const picture = document.createElement('img');
        picture.src = src;
        picture.alt = alt;
        picture.style.cssText = `
          max-width:90vw; max-height:90vh;
          object-fit:contain; border-radius:4px;
          box-shadow:0 30px 80px rgba(0,0,0,0.5);
        `;

        const closeBtn = document.createElement('button');
        closeBtn.setAttribute('aria-label', 'Zamknij');
        closeBtn.style.cssText = `
          position:absolute; top:1.5rem; right:1.5rem;
          background:none; border:1px solid rgba(255,255,255,0.3);
          color:#fff; width:44px; height:44px; border-radius:50%;
          font-size:1.25rem; cursor:pointer; display:flex;
          align-items:center; justify-content:center;
        `;
        closeBtn.innerHTML = '&times;';

        const close = () => { overlay.style.opacity = '0'; setTimeout(() => overlay.remove(), 200); };
        on(overlay, 'click', (e) => { if (e.target === overlay) close(); });
        on(closeBtn, 'click', close);
        on(document, 'keydown', function handler(e) {
          if (e.key === 'Escape') { close(); document.removeEventListener('keydown', handler); }
        });

        overlay.append(picture, closeBtn);
        document.body.appendChild(overlay);

        // Trap focus
        closeBtn.focus();
      });
    });
  };

  /* ============================================================
     14. COOKIE NOTICE (GDPR)
  ============================================================ */
  const initCookieNotice = () => {
    if (localStorage.getItem('sw_cookies_accepted')) return;

    const notice = document.createElement('div');
    notice.setAttribute('role', 'alert');
    notice.setAttribute('aria-live', 'polite');
    notice.style.cssText = `
      position:fixed; bottom:1.5rem; left:1.5rem; right:1.5rem;
      max-width:520px; z-index:9000;
      background:rgba(26,10,0,0.97); backdrop-filter:blur(12px);
      border:1px solid rgba(201,168,76,0.2); border-radius:6px;
      padding:1.25rem 1.5rem; color:rgba(245,240,232,0.8);
      font-size:0.82rem; line-height:1.6;
      box-shadow:0 8px 40px rgba(0,0,0,0.4);
      animation: slideUp 0.4s ease;
      display:flex; flex-wrap:wrap; gap:1rem; align-items:center;
    `;

    notice.innerHTML = `
      <p style="flex:1; min-width:200px; margin:0;">
        Używamy plików cookie, aby zapewnić najlepsze doświadczenie na naszej stronie.
        <a href="/cookies" style="color:var(--color-gold); text-decoration:underline;">Dowiedz się więcej</a>
      </p>
      <div style="display:flex; gap:0.5rem; flex-shrink:0;">
        <button id="sw-cookie-reject"
          style="padding:0.5rem 1rem; background:transparent; color:rgba(245,240,232,0.5);
                 border:1px solid rgba(245,240,232,0.2); border-radius:2px;
                 font-size:0.7rem; letter-spacing:0.1em; cursor:pointer; font-family:inherit; text-transform:uppercase;">
          Odrzuć
        </button>
        <button id="sw-cookie-accept"
          style="padding:0.5rem 1.25rem; background:var(--color-gold); color:var(--color-ink);
                 border:none; border-radius:2px;
                 font-size:0.7rem; font-weight:600; letter-spacing:0.1em; cursor:pointer; font-family:inherit; text-transform:uppercase;">
          Akceptuję
        </button>
      </div>
    `;

    document.body.appendChild(notice);

    const dismiss = (accepted) => {
      if (accepted) localStorage.setItem('sw_cookies_accepted', '1');
      notice.style.opacity = '0';
      notice.style.transform = 'translateY(20px)';
      notice.style.transition = 'opacity 0.3s, transform 0.3s';
      setTimeout(() => notice.remove(), 320);
    };

    on(qs('#sw-cookie-accept', notice), 'click', () => dismiss(true));
    on(qs('#sw-cookie-reject', notice), 'click', () => dismiss(false));
  };

  /* ============================================================
     15. NUMBER COUNT-UP ANIMATION
  ============================================================ */
  const initCountUp = () => {
    const stats = qsa('.stat-number, .badge-num');
    if (!stats.length || !('IntersectionObserver' in window)) return;

    const animate = (el) => {
      const text   = el.textContent.trim();
      const num    = parseFloat(text.replace(/[^0-9.]/g, ''));
      const suffix = text.replace(/[0-9.,]/g, '').trim();
      if (isNaN(num)) return;

      const duration = 1400;
      const start    = performance.now();

      const step = (now) => {
        const progress = Math.min((now - start) / duration, 1);
        const eased    = 1 - Math.pow(1 - progress, 3); // cubic ease-out
        const val      = eased * num;
        el.textContent = (Number.isInteger(num) ? Math.round(val) : val.toFixed(1)) + suffix;
        if (progress < 1) requestAnimationFrame(step);
      };

      requestAnimationFrame(step);
    };

    const obs = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          animate(entry.target);
          obs.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });

    stats.forEach(el => obs.observe(el));
  };

  /* ============================================================
     16. CSS KEYFRAMES injection (for overlays & cookie)
  ============================================================ */
  const injectKeyframes = () => {
    const style = document.createElement('style');
    style.textContent = `
      @keyframes fadeIn  { from { opacity:0 } to { opacity:1 } }
      @keyframes slideUp { from { opacity:0; transform:translateY(20px) } to { opacity:1; transform:translateY(0) } }
    `;
    document.head.appendChild(style);
  };

  /* ============================================================
     INIT — DOMContentLoaded
  ============================================================ */
  const init = () => {
    injectKeyframes();
    initHeader();
    initParallax();
    initAnimations();
    initMobileNav();
    initBookingForm();
    initNewsletter();
    initReviewsSlider();
    initWineFilter();
    initAddToCart();
    initSmoothScroll();
    initScrollSpy();
    initLazyLoad();
    initGallery();
    initCountUp();

    // Cookie notice with a small delay so it doesn't flash immediately
    setTimeout(initCookieNotice, 1200);
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();
