/**
 * Professional Single Product Page - JavaScript
 * Version: 2.0
 * Features: Gallery, Zoom, Lightbox, Tabs, Sticky Cart
 */

(function () {
    "use strict";

    // Wait for DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        initGallery();
        initZoom();
        initLightbox();
        initThumbnails();
        initTabs();
        initQuantity();
        initShare();
        initModals();
        initStickyCart();
        initWishlistCompare();
    }

    /* ===================================================================
       GALLERY - Thumbnail Click
       =================================================================== */
    function initGallery() {
        const mainImage = document.getElementById("fsp-main-img");
        const thumbs = document.querySelectorAll(".fsp-thumb:not(.fsp-thumb-video)");

        if (!mainImage || !thumbs.length) return;

        thumbs.forEach(function (thumb) {
            thumb.addEventListener("click", function () {
                // Remove active from all
                thumbs.forEach(t => t.classList.remove("active"));

                // Add active to clicked
                this.classList.add("active");

                // Get image URLs
                const largeSrc = this.getAttribute("data-large");
                const fullSrc = this.getAttribute("data-full");

                if (largeSrc) {
                    mainImage.src = largeSrc;
                    if (fullSrc) {
                        mainImage.setAttribute("data-full", fullSrc);
                    }
                }
            });
        });
    }

    /* ===================================================================
       ZOOM - Mouse hover zoom
       =================================================================== */
    function initZoom() {
        const mainImage = document.getElementById("fsp-main-img");
        const mainImageWrap = document.getElementById("fsp-main-image");
        const zoomLens = document.getElementById("fsp-zoom-lens");
        const zoomResult = document.getElementById("fsp-zoom-result");

        if (!mainImage || !zoomLens || !zoomResult || !mainImageWrap) return;

        const zoomRatio = 2.5;

        mainImageWrap.addEventListener("mouseenter", function () {
            const fullSrc = mainImage.getAttribute("data-full") || mainImage.src;
            if (!fullSrc) return;

            zoomResult.style.backgroundImage = `url('${fullSrc}')`;
            zoomResult.style.backgroundSize = `${mainImage.width * zoomRatio}px ${mainImage.height * zoomRatio}px`;
            mainImageWrap.classList.add("fsp-zoom-active");
        });

        mainImageWrap.addEventListener("mouseleave", function () {
            mainImageWrap.classList.remove("fsp-zoom-active");
        });

        mainImageWrap.addEventListener("mousemove", function (e) {
            if (!mainImageWrap.classList.contains("fsp-zoom-active")) return;

            const rect = mainImageWrap.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            let lensX = x - zoomLens.offsetWidth / 2;
            let lensY = y - zoomLens.offsetHeight / 2;

            // Constrain lens
            lensX = Math.max(0, Math.min(lensX, mainImageWrap.offsetWidth - zoomLens.offsetWidth));
            lensY = Math.max(0, Math.min(lensY, mainImageWrap.offsetHeight - zoomLens.offsetHeight));

            zoomLens.style.left = `${lensX}px`;
            zoomLens.style.top = `${lensY}px`;

            // Update background position
            const bgX = -lensX * zoomRatio;
            const bgY = -lensY * zoomRatio;
            zoomResult.style.backgroundPosition = `${bgX}px ${bgY}px`;
        });
    }

    /* ===================================================================
       LIGHTBOX - Full screen image viewer
       =================================================================== */
    function initLightbox() {
        const lightbox = document.getElementById("fsp-lightbox");
        const lightboxImg = document.getElementById("fsp-lightbox-img");
        const lightboxClose = document.querySelector(".fsp-lightbox-close");
        const lightboxPrev = document.querySelector(".fsp-lightbox-prev");
        const lightboxNext = document.querySelector(".fsp-lightbox-next");
        const lightboxCounterCurrent = document.getElementById("fsp-lightbox-current");
        const lightboxCounterTotal = document.getElementById("fsp-lightbox-total");
        const fullscreenBtn = document.getElementById("fsp-fullscreen-btn");
        const galleryItems = Array.from(document.querySelectorAll(".fsp-thumb:not(.fsp-thumb-video)"));

        if (!lightbox || !lightboxImg || !galleryItems.length) return;

        let currentIndex = 0;

        function openLightbox(index) {
            currentIndex = index;
            const img = galleryItems[currentIndex];
            if (!img) return;

            const full = img.getAttribute("data-full") || img.getAttribute("data-large");
            if (!full) return;

            lightboxImg.src = full;
            lightbox.classList.add("active");

            if (lightboxCounterCurrent && lightboxCounterTotal) {
                lightboxCounterCurrent.textContent = currentIndex + 1;
                lightboxCounterTotal.textContent = galleryItems.length;
            }

            document.body.style.overflow = "hidden";
        }

        function closeLightbox() {
            lightbox.classList.remove("active");
            document.body.style.overflow = "";
        }

        function goPrev() {
            currentIndex = (currentIndex - 1 + galleryItems.length) % galleryItems.length;
            openLightbox(currentIndex);
        }

        function goNext() {
            currentIndex = (currentIndex + 1) % galleryItems.length;
            openLightbox(currentIndex);
        }

        // Fullscreen button
        if (fullscreenBtn) {
            fullscreenBtn.addEventListener("click", function () {
                const activeThumb = document.querySelector(".fsp-thumb.active");
                const activeIndex = galleryItems.indexOf(activeThumb);
                openLightbox(activeIndex >= 0 ? activeIndex : 0);
            });
        }

        // Double click on thumbnails
        galleryItems.forEach(function (thumb, index) {
            thumb.addEventListener("dblclick", function () {
                openLightbox(index);
            });
        });

        // Close button
        if (lightboxClose) {
            lightboxClose.addEventListener("click", closeLightbox);
        }

        // Navigation buttons
        if (lightboxPrev) {
            lightboxPrev.addEventListener("click", function (e) {
                e.stopPropagation();
                goPrev();
            });
        }

        if (lightboxNext) {
            lightboxNext.addEventListener("click", function (e) {
                e.stopPropagation();
                goNext();
            });
        }

        // Click outside to close
        lightbox.addEventListener("click", function (e) {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });

        // Keyboard navigation
        document.addEventListener("keydown", function (e) {
            if (!lightbox.classList.contains("active")) return;

            if (e.key === "Escape") closeLightbox();
            if (e.key === "ArrowRight") goNext();
            if (e.key === "ArrowLeft") goPrev();
        });
    }

    /* ===================================================================
       THUMBNAILS - Scroll navigation
       =================================================================== */
    function initThumbnails() {
        const thumbsTrack = document.getElementById("fsp-thumbs-track");
        const thumbsPrev = document.getElementById("fsp-thumb-prev");
        const thumbsNext = document.getElementById("fsp-thumb-next");

        if (!thumbsTrack || !thumbsPrev || !thumbsNext) return;

        thumbsPrev.addEventListener("click", function () {
            thumbsTrack.scrollBy({ left: -120, behavior: "smooth" });
        });

        thumbsNext.addEventListener("click", function () {
            thumbsTrack.scrollBy({ left: 120, behavior: "smooth" });
        });

        // Video button/thumb opens video tab
        const videoBtn = document.getElementById("fsp-video-btn");
        const videoThumb = document.getElementById("fsp-thumb-video");
        const videoTabBtn = document.querySelector(".fsp-tab-btn[data-tab='video']") ||
                            document.querySelector("[data-tab='video']");

        function openVideoTab() {
            if (!videoTabBtn) return;
            videoTabBtn.click();

            // Smooth scroll to tabs
            const tabsWrap = document.getElementById("fsp-tabs");
            if (tabsWrap) {
                const rect = tabsWrap.getBoundingClientRect();
                window.scrollTo({
                    top: window.scrollY + rect.top - 100,
                    behavior: "smooth"
                });
            }
        }

        if (videoBtn) videoBtn.addEventListener("click", openVideoTab);
        if (videoThumb) videoThumb.addEventListener("click", openVideoTab);
    }

    /* ===================================================================
       TABS - Content switching
       =================================================================== */
    function initTabs() {
        const tabButtons = document.querySelectorAll(".fsp-tab-btn");
        const tabPanels = document.querySelectorAll(".fsp-tab-panel");

        if (!tabButtons.length || !tabPanels.length) return;

        tabButtons.forEach(function (btn) {
            btn.addEventListener("click", function () {
                const target = this.getAttribute("data-tab");
                if (!target) return;

                // Remove active from all
                tabButtons.forEach(b => b.classList.remove("active"));
                tabPanels.forEach(p => p.classList.remove("active"));

                // Add active to clicked
                this.classList.add("active");
                const panel = document.getElementById("tab-" + target);
                if (panel) {
                    panel.classList.add("active");
                }
            });
        });
    }

    /* ===================================================================
       QUANTITY - Plus/Minus buttons
       =================================================================== */
    function initQuantity() {
        document.querySelectorAll(".fsp-cart-form").forEach(function (form) {
            const minus = form.querySelector(".fsp-qty-minus");
            const plus = form.querySelector(".fsp-qty-plus");
            const input = form.querySelector(".fsp-qty-input");

            if (!input) return;

            const min = parseInt(input.getAttribute("min") || "1", 10);
            const max = parseInt(input.getAttribute("max") || "999", 10);

            if (minus) {
                minus.addEventListener("click", function () {
                    let val = parseInt(input.value || "1", 10);
                    if (isNaN(val)) val = min;
                    if (val > min) {
                        input.value = val - 1;
                    }
                });
            }

            if (plus) {
                plus.addEventListener("click", function () {
                    let val = parseInt(input.value || "1", 10);
                    if (isNaN(val)) val = min;
                    if (val < max) {
                        input.value = val + 1;
                    }
                });
            }

            // Validate on input
            input.addEventListener("change", function () {
                let val = parseInt(this.value, 10);
                if (isNaN(val) || val < min) {
                    this.value = min;
                } else if (val > max) {
                    this.value = max;
                }
            });
        });
    }

    /* ===================================================================
       SHARE - Dropdown & Copy link
       =================================================================== */
    function initShare() {
        const shareBtn = document.getElementById("fsp-share-btn");
        const shareDropdown = document.getElementById("fsp-share-dropdown");
        const copyBtn = document.querySelector(".fsp-share-copy");

        if (shareBtn && shareDropdown) {
            shareBtn.addEventListener("click", function (e) {
                e.stopPropagation();
                shareDropdown.classList.toggle("active");
            });

            // Close on outside click
            document.addEventListener("click", function (e) {
                if (!shareDropdown.contains(e.target) && e.target !== shareBtn) {
                    shareDropdown.classList.remove("active");
                }
            });
        }

        // Copy link
        if (copyBtn) {
            copyBtn.addEventListener("click", function () {
                const url = this.getAttribute("data-url");
                if (!url) return;

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(url).then(() => {
                        copyBtn.classList.add("copied");
                        const icon = copyBtn.querySelector("i");
                        if (icon) {
                            icon.className = "fa-solid fa-check";
                        }

                        setTimeout(() => {
                            copyBtn.classList.remove("copied");
                            if (icon) {
                                icon.className = "fa-solid fa-link";
                            }
                        }, 2000);
                    }).catch(err => {
                        console.error("Copy failed:", err);
                    });
                } else {
                    // Fallback
                    const textarea = document.createElement("textarea");
                    textarea.value = url;
                    textarea.style.position = "fixed";
                    textarea.style.opacity = "0";
                    document.body.appendChild(textarea);
                    textarea.select();
                    document.execCommand("copy");
                    document.body.removeChild(textarea);

                    copyBtn.classList.add("copied");
                    setTimeout(() => {
                        copyBtn.classList.remove("copied");
                    }, 2000);
                }
            });
        }
    }

    /* ===================================================================
       MODALS - Size guide, etc.
       =================================================================== */
    function initModals() {
        const sizeBtn = document.getElementById("fsp-size-guide-btn");
        const sizeModal = document.getElementById("fsp-size-modal");

        if (sizeBtn && sizeModal) {
            const sizeOverlay = sizeModal.querySelector(".fsp-modal-overlay");
            const sizeClose = sizeModal.querySelector(".fsp-modal-close");

            function openSizeModal() {
                sizeModal.classList.add("active");
                document.body.style.overflow = "hidden";
            }

            function closeSizeModal() {
                sizeModal.classList.remove("active");
                document.body.style.overflow = "";
            }

            sizeBtn.addEventListener("click", openSizeModal);
            if (sizeOverlay) sizeOverlay.addEventListener("click", closeSizeModal);
            if (sizeClose) sizeClose.addEventListener("click", closeSizeModal);

            document.addEventListener("keydown", function (e) {
                if (e.key === "Escape" && sizeModal.classList.contains("active")) {
                    closeSizeModal();
                }
            });
        }
    }

    /* ===================================================================
       STICKY CART - Show/hide on scroll (mobile)
       =================================================================== */
    function initStickyCart() {
        const stickyCart = document.getElementById("fsp-sticky-cart");
        if (!stickyCart) return;

        function toggleStickyCart() {
            // Only show on mobile/tablet
            if (window.innerWidth > 991) {
                stickyCart.classList.remove("visible");
                return;
            }

            const threshold = 400;
            if (window.scrollY > threshold) {
                stickyCart.classList.add("visible");
            } else {
                stickyCart.classList.remove("visible");
            }
        }

        window.addEventListener("scroll", toggleStickyCart, { passive: true });
        window.addEventListener("resize", toggleStickyCart);
        toggleStickyCart();
    }

    /* ===================================================================
       WISHLIST & COMPARE - Toggle active state
       =================================================================== */
    function initWishlistCompare() {
        document.querySelectorAll(".fsp-btn-wishlist, .fsp-wishlist-btn").forEach(function (btn) {
            btn.addEventListener("click", function (e) {
                e.preventDefault();
                this.classList.toggle("active");

                // Change icon
                const icon = this.querySelector("i");
                if (icon) {
                    if (this.classList.contains("active")) {
                        icon.className = "fa-solid fa-heart";
                    } else {
                        icon.className = "fa-regular fa-heart";
                    }
                }
            });
        });

        document.querySelectorAll(".fsp-btn-compare").forEach(function (btn) {
            btn.addEventListener("click", function (e) {
                e.preventDefault();
                this.classList.toggle("active");
            });
        });
    }

})();
