/**
 * Dante Burba Theme — main.js
 * Cursor, nav, scroll animations, mobile menu, product modal, cart drawer.
 */
(function () {
    'use strict';

    // ── Wait for Lucide to be ready ──
    function initLucide() {
        if (window.lucide) {
            lucide.createIcons();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        initLucide();
        initCursor();
        initScrollProgress();
        initNavScroll();
        initMobileMenu();
        initReveal();
        initProductModal();
    });

    // ============================================================
    // CUSTOM CURSOR
    // ============================================================
    function initCursor() {
        const cursor = document.getElementById('db-cursor');
        const ring   = document.getElementById('db-cursor-ring');
        if (!cursor || !ring) return;

        let mouseX = 0, mouseY = 0;
        let ringX  = 0, ringY  = 0;

        document.addEventListener('mousemove', function (e) {
            mouseX = e.clientX;
            mouseY = e.clientY;
            cursor.style.left = mouseX + 'px';
            cursor.style.top  = mouseY + 'px';
        });

        (function animateRing() {
            ringX += (mouseX - ringX) * 0.12;
            ringY += (mouseY - ringY) * 0.12;
            ring.style.left = ringX + 'px';
            ring.style.top  = ringY + 'px';
            requestAnimationFrame(animateRing);
        })();
    }

    // ============================================================
    // SCROLL PROGRESS BAR
    // ============================================================
    function initScrollProgress() {
        const bar = document.getElementById('db-progress-bar');
        if (!bar) return;
        window.addEventListener('scroll', function () {
            const scrolled = window.scrollY / (document.documentElement.scrollHeight - window.innerHeight);
            bar.style.transform = 'scaleX(' + Math.min(scrolled, 1) + ')';
        }, { passive: true });
    }

    // ============================================================
    // NAV SCROLL EFFECT
    // ============================================================
    function initNavScroll() {
        const nav = document.getElementById('db-nav');
        if (!nav) return;
        window.addEventListener('scroll', function () {
            nav.classList.toggle('scrolled', window.scrollY > 60);
        }, { passive: true });
    }

    // ============================================================
    // MOBILE MENU
    // ============================================================
    window.dbToggleMobileMenu = function () {
        const menu   = document.getElementById('db-mobile-menu');
        const toggle = document.getElementById('db-menu-toggle');
        if (!menu) return;
        const isOpen = menu.classList.toggle('open');
        document.body.style.overflow = isOpen ? 'hidden' : '';
        if (toggle) toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    };

    window.dbCloseMobileMenu = function () {
        const menu = document.getElementById('db-mobile-menu');
        if (!menu) return;
        menu.classList.remove('open');
        document.body.style.overflow = '';
    };

    function initMobileMenu() {
        const toggle = document.getElementById('db-menu-toggle');
        const close  = document.getElementById('db-menu-close');
        if (toggle) toggle.addEventListener('click', window.dbToggleMobileMenu);
        if (close)  close.addEventListener('click',  window.dbCloseMobileMenu);

        // Close on mobile link click
        document.querySelectorAll('#db-mobile-menu a').forEach(function (a) {
            a.addEventListener('click', window.dbCloseMobileMenu);
        });

        // Keyboard: Escape closes menu
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                window.dbCloseMobileMenu();
                dbCloseProductModal();
                dbCloseCartDrawer();
            }
        });
    }

    // ============================================================
    // REVEAL ON SCROLL (Intersection Observer)
    // ============================================================
    function initReveal() {
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.08 });

        document.querySelectorAll('.reveal').forEach(function (el) {
            observer.observe(el);
        });
    }

    // ============================================================
    // PRODUCT FILTER (Shop page — JS filter by data-category)
    // ============================================================
    window.dbFilterProducts = function (category, btn) {
        document.querySelectorAll('.db-filter-tab').forEach(function (t) {
            t.classList.remove('active');
        });
        btn.classList.add('active');

        document.querySelectorAll('[data-category]').forEach(function (card) {
            var match = category === 'all' || card.getAttribute('data-category') === category;
            card.style.display = match ? '' : 'none';
        });
    };

    // ============================================================
    // PRODUCT QUICK-VIEW MODAL
    // ============================================================
    var currentProduct = {};

    function initProductModal() {
        // Create modal DOM if it doesn't exist (non-WooCommerce fallback)
        if (!document.getElementById('db-product-modal')) {
            var overlay = document.createElement('div');
            overlay.className = 'db-modal-overlay';
            overlay.id = 'db-product-modal';
            overlay.setAttribute('role', 'dialog');
            overlay.setAttribute('aria-modal', 'true');
            overlay.setAttribute('aria-label', 'Product details');
            overlay.innerHTML = [
                '<div class="db-modal-content" id="db-modal-content">',
                  '<div class="db-modal-img-wrap">',
                    '<img id="db-modal-img" src="" alt="" loading="lazy">',
                    '<div class="db-modal-badge" id="db-modal-badge"></div>',
                  '</div>',
                  '<button class="db-modal-close" onclick="dbCloseProductModal()" aria-label="Close">',
                    '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
                  '</button>',
                  '<div class="db-modal-body">',
                    '<h2 class="db-modal-title" id="db-modal-title"></h2>',
                    '<p class="db-modal-desc"  id="db-modal-desc"></p>',
                    '<div class="db-modal-footer">',
                      '<div>',
                        '<span class="db-modal-price-label">Price</span>',
                        '<span class="db-modal-price-val" id="db-modal-price"></span>',
                      '</div>',
                      '<div class="db-modal-actions">',
                        '<button class="db-modal-details-btn" id="db-modal-details-btn">Full Details</button>',
                        '<button class="btn-primary" id="db-modal-add-btn" style="padding:14px 20px;font-size:10px;">',
                          'Add to Cart',
                        '</button>',
                      '</div>',
                    '</div>',
                  '</div>',
                '</div>',
            ].join('');

            // Close on backdrop click
            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) dbCloseProductModal();
            });

            document.body.appendChild(overlay);
        }
    }

    window.dbOpenProductModal = function (cardEl) {
        var modal = document.getElementById('db-product-modal');
        if (!modal) return;

        currentProduct = {
            id:    cardEl.getAttribute('data-id')    || '',
            title: cardEl.getAttribute('data-title') || '',
            price: cardEl.getAttribute('data-price') || '0',
            image: cardEl.getAttribute('data-image') || '',
            desc:  cardEl.getAttribute('data-description') || '',
            url:   cardEl.getAttribute('data-url')   || '#',
        };

        modal.querySelector('#db-modal-img').src         = currentProduct.image;
        modal.querySelector('#db-modal-img').alt         = currentProduct.title;
        modal.querySelector('#db-modal-title').textContent = currentProduct.title;
        modal.querySelector('#db-modal-desc').textContent  = currentProduct.desc;
        modal.querySelector('#db-modal-price').textContent = (window.dbData && window.dbData.currency ? window.dbData.currency : '$') + parseFloat(currentProduct.price).toFixed(2);

        var badge = modal.querySelector('#db-modal-badge');
        if (badge) badge.textContent = 'Part';

        var detailsBtn = modal.querySelector('#db-modal-details-btn');
        if (detailsBtn) {
            detailsBtn.onclick = function () {
                if (currentProduct.url && currentProduct.url !== '#') {
                    window.location.href = currentProduct.url;
                }
            };
        }

        var addBtn = modal.querySelector('#db-modal-add-btn');
        if (addBtn) {
            addBtn.onclick = function () {
                dbAddToCart(parseInt(currentProduct.id.replace('p', '')), addBtn);
            };
        }

        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    };

    window.dbCloseProductModal = function () {
        var modal = document.getElementById('db-product-modal');
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }
    };

    // ============================================================
    // CART DRAWER
    // ============================================================
    window.dbToggleCart = function () {
        var drawer  = document.getElementById('db-cart-drawer');
        var overlay = document.getElementById('db-cart-overlay');
        if (!drawer) return;

        var isOpen = drawer.classList.toggle('open');
        if (overlay) overlay.classList.toggle('show', isOpen);
        document.body.style.overflow = isOpen ? 'hidden' : '';

        var cartToggle = document.getElementById('db-cart-toggle');
        if (cartToggle) cartToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    };

    window.dbCloseCartDrawer = function () {
        var drawer  = document.getElementById('db-cart-drawer');
        var overlay = document.getElementById('db-cart-overlay');
        if (drawer)  drawer.classList.remove('open');
        if (overlay) overlay.classList.remove('show');
        document.body.style.overflow = '';
    };

    // ============================================================
    // ADD TO CART (AJAX)
    // ============================================================
    window.dbAddToCart = function (productId, btnEl) {
        if (!productId) return;

        var data = window.dbData;
        if (!data) return;

        // Visual feedback
        var orig = btnEl.innerHTML;
        btnEl.innerHTML = '<svg class="animate-spin" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>';
        btnEl.disabled = true;

        var formData = new FormData();
        formData.append('action',     'db_add_to_cart');
        formData.append('product_id', productId);
        formData.append('quantity',   1);
        formData.append('nonce',      data.nonce);

        fetch(data.ajaxUrl, { method: 'POST', body: formData })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.success !== false) {
                    // Success feedback
                    btnEl.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>';
                    btnEl.style.background = '#16a34a';

                    // Update cart count via WooCommerce fragments
                    jQuery(document.body).trigger('wc_fragment_refresh');

                    // Bump badge
                    dbBumpCartBadge();
                    dbShowToast('Added to Cart', 'Item successfully added to your cart.');

                    setTimeout(function () {
                        btnEl.innerHTML = orig;
                        btnEl.style.background = '';
                        btnEl.disabled = false;
                    }, 1500);
                } else {
                    btnEl.innerHTML = orig;
                    btnEl.disabled  = false;
                    dbShowToast('Error', res.data && res.data.message ? res.data.message : 'Could not add to cart.', true);
                }
            })
            .catch(function () {
                btnEl.innerHTML = orig;
                btnEl.disabled  = false;
            });
    };

    // ============================================================
    // CART BADGE BUMP
    // ============================================================
    function dbBumpCartBadge() {
        var badge = document.getElementById('db-cart-count');
        if (!badge) return;
        badge.classList.add('bump');
        setTimeout(function () { badge.classList.remove('bump'); }, 300);
    }

    // ============================================================
    // TOAST NOTIFICATION
    // ============================================================
    window.dbShowToast = function (title, body, isError) {
        var toast = document.createElement('div');
        toast.className = 'db-toast';
        toast.innerHTML = [
            '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:' + (isError ? '#ef4444' : 'var(--db-orange)') + '"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
            '<div>',
              '<div class="db-toast__title">' + escHtml(title) + '</div>',
              '<div class="db-toast__body">'  + escHtml(body)  + '</div>',
            '</div>',
        ].join('');
        document.body.appendChild(toast);
        requestAnimationFrame(function () { toast.classList.add('show'); });
        setTimeout(function () {
            toast.classList.remove('show');
            setTimeout(function () { toast.remove(); }, 350);
        }, 4000);
    };

    function escHtml(str) {
        var d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    // ============================================================
    // WooCommerce: listen for fragment refresh to update count
    // ============================================================
    document.addEventListener('DOMContentLoaded', function () {
        if (window.jQuery) {
            jQuery(document.body).on('wc_fragments_refreshed wc_fragment_refresh', function () {
                initLucide();
            });
        }
    });

})();
