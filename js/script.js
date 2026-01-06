/**
 * SmokeIran Theme JavaScript
 * 
 * Handles mobile menu toggle and smooth scrolling
 */

(function() {
    'use strict';

    // Mobile Menu Toggle
    function initMobileMenu() {
        const menuToggle = document.querySelector('.menu-toggle');
        const mainNav = document.querySelector('.main-navigation');

        if (menuToggle && mainNav) {
            menuToggle.addEventListener('click', function() {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !isExpanded);
                mainNav.classList.toggle('active');
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!mainNav.contains(event.target) && !menuToggle.contains(event.target)) {
                    mainNav.classList.remove('active');
                    menuToggle.setAttribute('aria-expanded', 'false');
                }
            });

            // Close menu on escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && mainNav.classList.contains('active')) {
                    mainNav.classList.remove('active');
                    menuToggle.setAttribute('aria-expanded', 'false');
                    menuToggle.focus();
                }
            });
        }
    }

    // Smooth Scroll for Anchor Links
    function initSmoothScroll() {
        const links = document.querySelectorAll('a[href^="#"]');
        
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                
                // Skip if it's just "#"
                if (href === '#') return;
                
                const target = document.querySelector(href);
                
                if (target) {
                    e.preventDefault();
                    const headerOffset = 100;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    // Form Validation Enhancement
    function initFormValidation() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            const inputs = form.querySelectorAll('input[required], textarea[required]');
            
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (!this.value.trim()) {
                        this.classList.add('error');
                    } else {
                        this.classList.remove('error');
                    }
                });

                input.addEventListener('input', function() {
                    if (this.value.trim()) {
                        this.classList.remove('error');
                    }
                });
            });
        });
    }

    // Add to Cart Button Enhancement
    function initCartButtons() {
        const addToCartButtons = document.querySelectorAll('.add_to_cart_button');
        
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Add loading state
                this.classList.add('loading');
                this.textContent = 'Adding...';
                
                // The actual WooCommerce AJAX will handle the rest
                // This just provides visual feedback
            });
        });

        // Update cart count on AJAX complete
        if (typeof jQuery !== 'undefined' && typeof wc_cart_fragments_params !== 'undefined') {
            jQuery(document.body).on('added_to_cart', function() {
                // Reload cart fragments to update count
                jQuery.get(wc_cart_fragments_params.ajax_url + '?wc-ajax=get_refreshed_fragments')
                    .done(function(data) {
                        if (data && data.fragments) {
                            jQuery.each(data.fragments, function(key, value) {
                                jQuery(key).replaceWith(value);
                            });
                        }
                    })
                    .fail(function() {
                        // Silently fail - cart count will update on page reload
                        if (window.console && window.console.log) {
                            console.log('Failed to update cart fragments');
                        }
                    });
            });
        }
    }

    // Image Lazy Loading (for older browsers)
    function initLazyLoading() {
        if ('loading' in HTMLImageElement.prototype) {
            // Browser supports native lazy loading
            const images = document.querySelectorAll('img[loading="lazy"]');
            images.forEach(img => {
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                }
            });
        } else {
            // Fallback for older browsers
            const images = document.querySelectorAll('img[data-src]');
            
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            images.forEach(img => imageObserver.observe(img));
        }
    }

    // Sticky Header on Scroll
    function initStickyHeader() {
        const header = document.querySelector('.site-header');
        let lastScroll = 0;

        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;

            if (currentScroll <= 0) {
                header.classList.remove('scroll-up');
                return;
            }

            if (currentScroll > lastScroll && !header.classList.contains('scroll-down')) {
                // Scrolling down
                header.classList.remove('scroll-up');
                header.classList.add('scroll-down');
            } else if (currentScroll < lastScroll && header.classList.contains('scroll-down')) {
                // Scrolling up
                header.classList.remove('scroll-down');
                header.classList.add('scroll-up');
            }
            
            lastScroll = currentScroll;
        });
    }

    // Accessibility: Keyboard Navigation for Dropdowns
    function initKeyboardNav() {
        const menuItems = document.querySelectorAll('.nav-menu li');
        
        menuItems.forEach(item => {
            const link = item.querySelector('a');
            const submenu = item.querySelector('ul');
            
            if (submenu) {
                link.addEventListener('focus', function() {
                    item.classList.add('focused');
                });
                
                link.addEventListener('blur', function() {
                    item.classList.remove('focused');
                });
            }
        });
    }

    // Initialize all functions when DOM is ready
    function init() {
        initMobileMenu();
        initSmoothScroll();
        initFormValidation();
        initCartButtons();
        initLazyLoading();
        initStickyHeader();
        initKeyboardNav();
    }

    // Run initialization
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
