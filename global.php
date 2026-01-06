/**
 * =============================================================================
 * SMOKE IRAN - Global Foundation
 * =============================================================================
 * Version: 3.0.0
 * Priority: 1 (Must Load First)
 * Location: Run Everywhere
 * 
 * This file establishes the complete design system for SmokeIran
 * =============================================================================
 */

// Prevent direct access
if (!defined('ABSPATH')) exit;

// =============================================================================
// SECTION 1: CLEANUP & FIX 404 ERRORS
// =============================================================================

/**
 * Remove problematic theme enqueues that cause 404 errors
 * This runs early to prevent the errors
 */
add_action('wp_enqueue_scripts', 'si_cleanup_broken_assets', 1);
function si_cleanup_broken_assets() {
    // Remove potentially broken parent/child theme assets
    $handles_to_remove = array(
        // CSS handles
        'storefront-child-style',
        'storefront-child-single',
        'storefront-child-single-css',
        'single-css',
        'irancell-css',
        'storefront-child-irancell',
        // JS handles
        'storefront-child-single-js',
        'storefront-child-main-js',
        'single-js',
        'main-js',
    );
    
    foreach ($handles_to_remove as $handle) {
        wp_dequeue_style($handle);
        wp_deregister_style($handle);
        wp_dequeue_script($handle);
        wp_deregister_script($handle);
    }
}

/**
 * Remove any hardcoded broken assets via output buffer
 */
add_action('template_redirect', 'si_start_cleanup_buffer', 1);
function si_start_cleanup_buffer() {
    ob_start('si_cleanup_buffer_callback');
}

function si_cleanup_buffer_callback($buffer) {
    // Remove broken asset references
    $patterns = array(
        '/<link[^>]*\/css\/single\.css[^>]*>\s*/i',
        '/<link[^>]*\/fonts\/irancell\.css[^>]*>\s*/i',
        '/<link[^>]*irancell\.css[^>]*>\s*/i',
        '/<script[^>]*\/js\/single\.js[^>]*><\/script>\s*/i',
        '/<script[^>]*\/js\/main\.js[^>]*><\/script>\s*/i',
    );
    return preg_replace($patterns, '', $buffer);
}

add_action('shutdown', 'si_end_cleanup_buffer', 999);
function si_end_cleanup_buffer() {
    if (ob_get_level() > 0) {
        ob_end_flush();
    }
}

// =============================================================================
// SECTION 2: DESIGN SYSTEM CONSTANTS
// =============================================================================

// Primary Colors
define('SI_PRIMARY', '#29853A');
define('SI_PRIMARY_DARK', '#1e6b2d');
define('SI_PRIMARY_LIGHT', '#3da34d');
define('SI_PRIMARY_LIGHTER', '#e8f5ea');

// Secondary Colors
define('SI_SECONDARY', '#1a1a2e');
define('SI_SECONDARY_LIGHT', '#252542');

// Accent Colors
define('SI_ACCENT', '#f4a261');
define('SI_ACCENT_LIGHT', '#fef3e8');

// Semantic Colors
define('SI_SUCCESS', '#10b981');
define('SI_WARNING', '#f59e0b');
define('SI_DANGER', '#ef4444');
define('SI_INFO', '#3b82f6');

// Neutral Colors
define('SI_WHITE', '#ffffff');
define('SI_BLACK', '#000000');
define('SI_GRAY_50', '#f9fafb');
define('SI_GRAY_100', '#f3f4f6');
define('SI_GRAY_200', '#e5e7eb');
define('SI_GRAY_300', '#d1d5db');
define('SI_GRAY_400', '#9ca3af');
define('SI_GRAY_500', '#6b7280');
define('SI_GRAY_600', '#4b5563');
define('SI_GRAY_700', '#374151');
define('SI_GRAY_800', '#1f2937');
define('SI_GRAY_900', '#111827');

// =============================================================================
// SECTION 3: ASSET LOADING
// =============================================================================

add_action('wp_enqueue_scripts', 'si_enqueue_assets', 10);
function si_enqueue_assets() {
    // Font Awesome 6 Pro/Free
    wp_enqueue_style(
        'si-fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
        array(),
        '6.5.1'
    );
    
    // Main Styles (inline)
    wp_register_style('si-main', false);
    wp_enqueue_style('si-main');
    wp_add_inline_style('si-main', si_get_main_css());
}

// Admin Font Awesome
add_action('admin_enqueue_scripts', 'si_admin_assets');
function si_admin_assets() {
    wp_enqueue_style(
        'si-fontawesome-admin',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
        array(),
        '6.5.1'
    );
}

// =============================================================================
// SECTION 4: MAIN CSS (Complete Design System)
// =============================================================================

function si_get_main_css() {
    $primary = SI_PRIMARY;
    $primary_dark = SI_PRIMARY_DARK;
    $primary_light = SI_PRIMARY_LIGHT;
    
    return '
/* ==========================================================================
   CSS RESET & NORMALIZATION
   ========================================================================== */

*, *::before, *::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

html {
    font-size: 16px;
    scroll-behavior: smooth;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-size-adjust: 100%;
    -webkit-text-size-adjust: 100%;
}

body {
    margin: 0 !important;
    padding: 0 !important;
    min-height: 100vh;
    line-height: 1.7;
    font-family: var(--si-font);
    font-size: var(--si-text-base);
    color: var(--si-text);
    background-color: var(--si-bg);
    direction: rtl;
    text-align: right;
    overflow-x: hidden;
}

/* Fix white space issues */
body > *:first-child {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

body > *:last-child {
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}

#page, .site, #content, .site-content {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

.site-footer, #colophon, footer {
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}

/* Storefront specific fixes */
.storefront-full-width-content .site-main,
.storefront-full-width-content .content-area {
    margin: 0;
    padding: 0;
}

.site-header, .storefront-primary-navigation {
    margin-top: 0 !important;
}

article, aside, details, figcaption, figure, footer, header, hgroup, main, menu, nav, section {
    display: block;
}

img, picture, video, canvas, svg {
    display: block;
    max-width: 100%;
    height: auto;
}

input, button, textarea, select {
    font: inherit;
    color: inherit;
}

button {
    cursor: pointer;
    background: none;
    border: none;
}

a {
    color: inherit;
    text-decoration: none;
}

ul, ol {
    list-style: none;
}

table {
    border-collapse: collapse;
    border-spacing: 0;
}

/* ==========================================================================
   CSS CUSTOM PROPERTIES (Design Tokens)
   ========================================================================== */

:root {
    /* ===== COLORS ===== */
    --si-primary: ' . $primary . ';
    --si-primary-dark: ' . $primary_dark . ';
    --si-primary-light: ' . $primary_light . ';
    --si-primary-rgb: 41, 133, 58;
    --si-primary-lighter: #e8f5ea;
    
    --si-secondary: #1a1a2e;
    --si-secondary-light: #252542;
    
    --si-accent: #f4a261;
    --si-accent-light: #fef3e8;
    
    /* Semantic */
    --si-success: #10b981;
    --si-success-light: #d1fae5;
    --si-warning: #f59e0b;
    --si-warning-light: #fef3c7;
    --si-danger: #ef4444;
    --si-danger-light: #fee2e2;
    --si-info: #3b82f6;
    --si-info-light: #dbeafe;
    
    /* Neutrals */
    --si-white: #ffffff;
    --si-black: #000000;
    --si-gray-50: #f9fafb;
    --si-gray-100: #f3f4f6;
    --si-gray-200: #e5e7eb;
    --si-gray-300: #d1d5db;
    --si-gray-400: #9ca3af;
    --si-gray-500: #6b7280;
    --si-gray-600: #4b5563;
    --si-gray-700: #374151;
    --si-gray-800: #1f2937;
    --si-gray-900: #111827;
    
    /* ===== SEMANTIC COLORS ===== */
    --si-bg: var(--si-white);
    --si-bg-secondary: var(--si-gray-50);
    --si-bg-tertiary: var(--si-gray-100);
    --si-surface: var(--si-white);
    --si-surface-elevated: var(--si-white);
    
    --si-text: var(--si-gray-800);
    --si-text-secondary: var(--si-gray-600);
    --si-text-tertiary: var(--si-gray-500);
    --si-text-muted: var(--si-gray-400);
    --si-text-inverse: var(--si-white);
    
    --si-border: var(--si-gray-200);
    --si-border-light: var(--si-gray-100);
    --si-border-dark: var(--si-gray-300);
    
    --si-link: var(--si-primary);
    --si-link-hover: var(--si-primary-dark);
    
    /* ===== TYPOGRAPHY ===== */
    --si-font: "IRANSans", "Irancell", "Vazirmatn", "Tahoma", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    --si-font-mono: "Fira Code", "Monaco", "Consolas", monospace;
    
    --si-text-xs: 0.75rem;      /* 12px */
    --si-text-sm: 0.875rem;     /* 14px */
    --si-text-base: 1rem;       /* 16px */
    --si-text-lg: 1.125rem;     /* 18px */
    --si-text-xl: 1.25rem;      /* 20px */
    --si-text-2xl: 1.5rem;      /* 24px */
    --si-text-3xl: 1.875rem;    /* 30px */
    --si-text-4xl: 2.25rem;     /* 36px */
    --si-text-5xl: 3rem;        /* 48px */
    
    --si-leading-none: 1;
    --si-leading-tight: 1.25;
    --si-leading-snug: 1.375;
    --si-leading-normal: 1.5;
    --si-leading-relaxed: 1.625;
    --si-leading-loose: 2;
    
    --si-font-light: 300;
    --si-font-normal: 400;
    --si-font-medium: 500;
    --si-font-semibold: 600;
    --si-font-bold: 700;
    --si-font-extrabold: 800;
    
    /* ===== SPACING ===== */
    --si-space-0: 0;
    --si-space-1: 0.25rem;   /* 4px */
    --si-space-2: 0.5rem;    /* 8px */
    --si-space-3: 0.75rem;   /* 12px */
    --si-space-4: 1rem;      /* 16px */
    --si-space-5: 1.25rem;   /* 20px */
    --si-space-6: 1.5rem;    /* 24px */
    --si-space-7: 1.75rem;   /* 28px */
    --si-space-8: 2rem;      /* 32px */
    --si-space-10: 2.5rem;   /* 40px */
    --si-space-12: 3rem;     /* 48px */
    --si-space-16: 4rem;     /* 64px */
    --si-space-20: 5rem;     /* 80px */
    --si-space-24: 6rem;     /* 96px */
    
    /* ===== BORDER RADIUS ===== */
    --si-radius-none: 0;
    --si-radius-sm: 0.25rem;    /* 4px */
    --si-radius: 0.5rem;        /* 8px */
    --si-radius-md: 0.625rem;   /* 10px */
    --si-radius-lg: 0.75rem;    /* 12px */
    --si-radius-xl: 1rem;       /* 16px */
    --si-radius-2xl: 1.25rem;   /* 20px */
    --si-radius-3xl: 1.5rem;    /* 24px */
    --si-radius-full: 9999px;
    
    /* ===== SHADOWS ===== */
    --si-shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.05);
    --si-shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
    --si-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --si-shadow-md: 0 6px 12px -2px rgba(0, 0, 0, 0.1), 0 3px 6px -2px rgba(0, 0, 0, 0.05);
    --si-shadow-lg: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 10px -2px rgba(0, 0, 0, 0.05);
    --si-shadow-xl: 0 20px 40px -5px rgba(0, 0, 0, 0.1), 0 8px 16px -5px rgba(0, 0, 0, 0.04);
    --si-shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    --si-shadow-inner: inset 0 2px 4px rgba(0, 0, 0, 0.06);
    --si-shadow-primary: 0 4px 14px rgba(41, 133, 58, 0.3);
    --si-shadow-primary-lg: 0 10px 30px rgba(41, 133, 58, 0.35);
    
    /* ===== TRANSITIONS ===== */
    --si-ease: cubic-bezier(0.4, 0, 0.2, 1);
    --si-ease-in: cubic-bezier(0.4, 0, 1, 1);
    --si-ease-out: cubic-bezier(0, 0, 0.2, 1);
    --si-ease-in-out: cubic-bezier(0.4, 0, 0.2, 1);
    --si-ease-bounce: cubic-bezier(0.68, -0.55, 0.265, 1.55);
    
    --si-duration-75: 75ms;
    --si-duration-100: 100ms;
    --si-duration-150: 150ms;
    --si-duration-200: 200ms;
    --si-duration-300: 300ms;
    --si-duration-400: 400ms;
    --si-duration-500: 500ms;
    --si-duration-700: 700ms;
    
    --si-transition-fast: var(--si-duration-150) var(--si-ease);
    --si-transition: var(--si-duration-300) var(--si-ease);
    --si-transition-slow: var(--si-duration-500) var(--si-ease);
    
    /* ===== Z-INDEX ===== */
    --si-z-dropdown: 100;
    --si-z-sticky: 200;
    --si-z-fixed: 300;
    --si-z-drawer: 400;
    --si-z-modal-backdrop: 500;
    --si-z-modal: 600;
    --si-z-popover: 700;
    --si-z-tooltip: 800;
    --si-z-toast: 900;
    
    /* ===== LAYOUT ===== */
    --si-container-sm: 640px;
    --si-container-md: 768px;
    --si-container-lg: 1024px;
    --si-container-xl: 1280px;
    --si-container-2xl: 1400px;
    --si-header-height: 70px;
    --si-sidebar-width: 280px;
}

/* ==========================================================================
   DARK MODE
   ========================================================================== */

[data-theme="dark"],
.dark-mode,
body.dark-skin {
    --si-bg: #0f0f17;
    --si-bg-secondary: #1a1a27;
    --si-bg-tertiary: #252536;
    --si-surface: #1a1a27;
    --si-surface-elevated: #252536;
    
    --si-text: #f3f4f6;
    --si-text-secondary: #d1d5db;
    --si-text-tertiary: #9ca3af;
    --si-text-muted: #6b7280;
    
    --si-border: #374151;
    --si-border-light: #2d2d44;
    --si-border-dark: #4b5563;
    
    --si-shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.4);
    --si-shadow: 0 4px 6px rgba(0, 0, 0, 0.5);
    --si-shadow-md: 0 6px 12px rgba(0, 0, 0, 0.5);
    --si-shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.6);
    --si-shadow-xl: 0 20px 40px rgba(0, 0, 0, 0.6);
    
    color-scheme: dark;
}

/* ==========================================================================
   TYPOGRAPHY
   ========================================================================== */

h1, h2, h3, h4, h5, h6 {
    font-family: var(--si-font);
    font-weight: var(--si-font-bold);
    line-height: var(--si-leading-tight);
    color: var(--si-text);
    margin: 0 0 var(--si-space-4);
}

h1 { font-size: var(--si-text-4xl); }
h2 { font-size: var(--si-text-3xl); }
h3 { font-size: var(--si-text-2xl); }
h4 { font-size: var(--si-text-xl); }
h5 { font-size: var(--si-text-lg); }
h6 { font-size: var(--si-text-base); font-weight: var(--si-font-semibold); }

p {
    margin: 0 0 var(--si-space-4);
    color: var(--si-text-secondary);
    line-height: var(--si-leading-relaxed);
}

strong, b { font-weight: var(--si-font-semibold); }

small { font-size: var(--si-text-sm); }

/* Links */
a {
    color: var(--si-link);
    text-decoration: none;
    transition: color var(--si-transition-fast);
}

a:hover {
    color: var(--si-link-hover);
}

/* Selection */
::selection {
    background: var(--si-primary);
    color: var(--si-white);
}

/* Focus styles */
:focus-visible {
    outline: 2px solid var(--si-primary);
    outline-offset: 2px;
}

/* ==========================================================================
   LAYOUT UTILITIES
   ========================================================================== */

.si-container {
    width: 100%;
    max-width: var(--si-container-2xl);
    margin: 0 auto;
    padding: 0 var(--si-space-6);
}

.si-section {
    padding: var(--si-space-16) 0;
}

/* Flexbox */
.si-flex { display: flex; }
.si-flex-col { flex-direction: column; }
.si-flex-row { flex-direction: row; }
.si-flex-wrap { flex-wrap: wrap; }
.si-items-center { align-items: center; }
.si-items-start { align-items: flex-start; }
.si-items-end { align-items: flex-end; }
.si-justify-center { justify-content: center; }
.si-justify-between { justify-content: space-between; }
.si-justify-start { justify-content: flex-start; }
.si-justify-end { justify-content: flex-end; }
.si-flex-1 { flex: 1; }
.si-flex-shrink-0 { flex-shrink: 0; }

/* Grid */
.si-grid { display: grid; }
.si-grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
.si-grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
.si-grid-cols-4 { grid-template-columns: repeat(4, 1fr); }

/* Gap */
.si-gap-1 { gap: var(--si-space-1); }
.si-gap-2 { gap: var(--si-space-2); }
.si-gap-3 { gap: var(--si-space-3); }
.si-gap-4 { gap: var(--si-space-4); }
.si-gap-6 { gap: var(--si-space-6); }
.si-gap-8 { gap: var(--si-space-8); }

/* Text alignment */
.si-text-right { text-align: right; }
.si-text-left { text-align: left; }
.si-text-center { text-align: center; }

/* Display */
.si-hidden { display: none !important; }
.si-block { display: block; }
.si-inline-block { display: inline-block; }
.si-inline-flex { display: inline-flex; }

/* Position */
.si-relative { position: relative; }
.si-absolute { position: absolute; }
.si-fixed { position: fixed; }
.si-sticky { position: sticky; }

/* ==========================================================================
   COMPONENTS
   ========================================================================== */

/* --- Cards --- */
.si-card {
    background: var(--si-surface);
    border-radius: var(--si-radius-xl);
    box-shadow: var(--si-shadow-sm);
    border: 1px solid var(--si-border-light);
    overflow: hidden;
    transition: all var(--si-transition);
}

.si-card:hover {
    box-shadow: var(--si-shadow-lg);
    transform: translateY(-4px);
    border-color: var(--si-border);
}

.si-card-body {
    padding: var(--si-space-6);
}

.si-card-header {
    padding: var(--si-space-5) var(--si-space-6);
    border-bottom: 1px solid var(--si-border-light);
    background: var(--si-bg-secondary);
}

.si-card-footer {
    padding: var(--si-space-4) var(--si-space-6);
    border-top: 1px solid var(--si-border-light);
    background: var(--si-bg-secondary);
}

/* --- Buttons --- */
.si-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--si-space-2);
    padding: var(--si-space-3) var(--si-space-6);
    font-family: var(--si-font);
    font-size: var(--si-text-sm);
    font-weight: var(--si-font-semibold);
    line-height: 1.5;
    border: none;
    border-radius: var(--si-radius-lg);
    cursor: pointer;
    transition: all var(--si-transition-fast);
    text-decoration: none;
    white-space: nowrap;
    user-select: none;
}

.si-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.si-btn-primary {
    background: linear-gradient(135deg, var(--si-primary) 0%, var(--si-primary-dark) 100%);
    color: var(--si-white);
    box-shadow: var(--si-shadow-primary);
}

.si-btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: var(--si-shadow-primary-lg);
    color: var(--si-white);
}

.si-btn-secondary {
    background: var(--si-surface);
    color: var(--si-text);
    border: 2px solid var(--si-border);
}

.si-btn-secondary:hover:not(:disabled) {
    border-color: var(--si-primary);
    color: var(--si-primary);
    background: var(--si-primary-lighter);
}

.si-btn-ghost {
    background: transparent;
    color: var(--si-text-secondary);
}

.si-btn-ghost:hover:not(:disabled) {
    background: var(--si-bg-tertiary);
    color: var(--si-text);
}

.si-btn-sm {
    padding: var(--si-space-2) var(--si-space-4);
    font-size: var(--si-text-xs);
}

.si-btn-lg {
    padding: var(--si-space-4) var(--si-space-8);
    font-size: var(--si-text-base);
}

.si-btn-icon {
    width: 40px;
    height: 40px;
    padding: 0;
    border-radius: var(--si-radius-full);
}

.si-btn-icon.si-btn-sm {
    width: 32px;
    height: 32px;
}

.si-btn-icon.si-btn-lg {
    width: 48px;
    height: 48px;
}

/* --- Badges --- */
.si-badge {
    display: inline-flex;
    align-items: center;
    gap: var(--si-space-1);
    padding: var(--si-space-1) var(--si-space-3);
    font-size: var(--si-text-xs);
    font-weight: var(--si-font-semibold);
    border-radius: var(--si-radius-full);
    background: var(--si-bg-tertiary);
    color: var(--si-text-secondary);
    line-height: 1.5;
}

.si-badge-primary {
    background: var(--si-primary-lighter);
    color: var(--si-primary-dark);
}

.si-badge-success {
    background: var(--si-success-light);
    color: #065f46;
}

.si-badge-warning {
    background: var(--si-warning-light);
    color: #92400e;
}

.si-badge-danger {
    background: var(--si-danger-light);
    color: #991b1b;
}

.si-badge-info {
    background: var(--si-info-light);
    color: #1e40af;
}

/* --- Form Elements --- */
.si-input,
.si-select,
.si-textarea {
    width: 100%;
    padding: var(--si-space-3) var(--si-space-4);
    font-family: var(--si-font);
    font-size: var(--si-text-base);
    color: var(--si-text);
    background: var(--si-surface);
    border: 2px solid var(--si-border);
    border-radius: var(--si-radius-lg);
    transition: all var(--si-transition-fast);
    outline: none;
}

.si-input:focus,
.si-select:focus,
.si-textarea:focus {
    border-color: var(--si-primary);
    box-shadow: 0 0 0 4px rgba(41, 133, 58, 0.1);
}

.si-input::placeholder,
.si-textarea::placeholder {
    color: var(--si-text-muted);
}

.si-textarea {
    min-height: 120px;
    resize: vertical;
}

.si-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'%236b7280\'%3E%3Cpath d=\'M7 10l5 5 5-5H7z\'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: left 12px center;
    background-size: 20px;
    padding-left: 40px;
}

.si-label {
    display: block;
    margin-bottom: var(--si-space-2);
    font-size: var(--si-text-sm);
    font-weight: var(--si-font-medium);
    color: var(--si-text);
}

/* --- Alerts --- */
.si-alert {
    display: flex;
    align-items: flex-start;
    gap: var(--si-space-3);
    padding: var(--si-space-4) var(--si-space-5);
    border-radius: var(--si-radius-lg);
    border: 1px solid;
}

.si-alert-success {
    background: var(--si-success-light);
    border-color: var(--si-success);
    color: #065f46;
}

.si-alert-warning {
    background: var(--si-warning-light);
    border-color: var(--si-warning);
    color: #92400e;
}

.si-alert-danger {
    background: var(--si-danger-light);
    border-color: var(--si-danger);
    color: #991b1b;
}

.si-alert-info {
    background: var(--si-info-light);
    border-color: var(--si-info);
    color: #1e40af;
}

/* --- Dividers --- */
.si-divider {
    height: 1px;
    background: var(--si-border);
    border: none;
    margin: var(--si-space-6) 0;
}

/* --- Avatar --- */
.si-avatar {
    width: 40px;
    height: 40px;
    border-radius: var(--si-radius-full);
    object-fit: cover;
    border: 2px solid var(--si-border-light);
}

.si-avatar-sm { width: 32px; height: 32px; }
.si-avatar-lg { width: 56px; height: 56px; }
.si-avatar-xl { width: 80px; height: 80px; }

/* ==========================================================================
   ANIMATIONS
   ========================================================================== */

@keyframes si-fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes si-fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes si-fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes si-scaleIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes si-slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes si-slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes si-pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

@keyframes si-bounce {
    0%, 100% {
        transform: translateY(0);
        animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
    }
    50% {
        transform: translateY(-10px);
        animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
    }
}

@keyframes si-spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes si-shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

/* Animation utilities */
.si-animate-fadeIn { animation: si-fadeIn var(--si-duration-300) var(--si-ease) forwards; }
.si-animate-fadeInUp { animation: si-fadeInUp var(--si-duration-400) var(--si-ease) forwards; }
.si-animate-fadeInDown { animation: si-fadeInDown var(--si-duration-400) var(--si-ease) forwards; }
.si-animate-scaleIn { animation: si-scaleIn var(--si-duration-300) var(--si-ease) forwards; }
.si-animate-slideInRight { animation: si-slideInRight var(--si-duration-400) var(--si-ease) forwards; }
.si-animate-slideInLeft { animation: si-slideInLeft var(--si-duration-400) var(--si-ease) forwards; }
.si-animate-pulse { animation: si-pulse 2s var(--si-ease-in-out) infinite; }
.si-animate-bounce { animation: si-bounce 1s infinite; }
.si-animate-spin { animation: si-spin 1s linear infinite; }

/* Stagger children animations */
.si-stagger-children > * {
    opacity: 0;
    animation: si-fadeInUp var(--si-duration-400) var(--si-ease) forwards;
}

.si-stagger-children > *:nth-child(1) { animation-delay: 0.05s; }
.si-stagger-children > *:nth-child(2) { animation-delay: 0.1s; }
.si-stagger-children > *:nth-child(3) { animation-delay: 0.15s; }
.si-stagger-children > *:nth-child(4) { animation-delay: 0.2s; }
.si-stagger-children > *:nth-child(5) { animation-delay: 0.25s; }
.si-stagger-children > *:nth-child(6) { animation-delay: 0.3s; }
.si-stagger-children > *:nth-child(7) { animation-delay: 0.35s; }
.si-stagger-children > *:nth-child(8) { animation-delay: 0.4s; }
.si-stagger-children > *:nth-child(9) { animation-delay: 0.45s; }
.si-stagger-children > *:nth-child(10) { animation-delay: 0.5s; }

/* Loading skeleton */
.si-skeleton {
    background: linear-gradient(
        90deg,
        var(--si-bg-tertiary) 0%,
        var(--si-bg-secondary) 50%,
        var(--si-bg-tertiary) 100%
    );
    background-size: 200% 100%;
    animation: si-shimmer 1.5s infinite;
    border-radius: var(--si-radius);
}

/* Loading spinner */
.si-spinner {
    width: 20px;
    height: 20px;
    border: 2px solid var(--si-border);
    border-top-color: var(--si-primary);
    border-radius: var(--si-radius-full);
    animation: si-spin 0.8s linear infinite;
}

.si-spinner-lg {
    width: 32px;
    height: 32px;
    border-width: 3px;
}

/* ==========================================================================
   SCROLLBAR
   ========================================================================== */

::-webkit-scrollbar {
    width: 10px;
    height: 10px;
}

::-webkit-scrollbar-track {
    background: var(--si-bg-secondary);
}

::-webkit-scrollbar-thumb {
    background: var(--si-gray-400);
    border-radius: var(--si-radius-full);
    border: 2px solid var(--si-bg-secondary);
}

::-webkit-scrollbar-thumb:hover {
    background: var(--si-gray-500);
}

/* Firefox */
* {
    scrollbar-width: thin;
    scrollbar-color: var(--si-gray-400) var(--si-bg-secondary);
}

/* ==========================================================================
   RESPONSIVE UTILITIES
   ========================================================================== */

@media (max-width: 1280px) {
    .si-container { max-width: 100%; }
}

@media (max-width: 1024px) {
    :root {
        --si-text-4xl: 2rem;
        --si-text-3xl: 1.5rem;
        --si-text-2xl: 1.25rem;
    }
    
    .si-hide-lg { display: none !important; }
}

@media (max-width: 768px) {
    html { font-size: 15px; }
    
    :root {
        --si-space-16: 3rem;
        --si-space-12: 2.5rem;
    }
    
    .si-container {
        padding: 0 var(--si-space-4);
    }
    
    .si-hide-md { display: none !important; }
    
    .si-grid-cols-2,
    .si-grid-cols-3,
    .si-grid-cols-4 {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    html { font-size: 14px; }
    
    .si-hide-sm { display: none !important; }
}

/* ==========================================================================
   PRINT STYLES
   ========================================================================== */

@media print {
    body {
        background: white !important;
        color: black !important;
    }
    
    .si-no-print {
        display: none !important;
    }
    
    a[href]:after {
        content: " (" attr(href) ")";
    }
}
';
}

// =============================================================================
// SECTION 5: DARK MODE FUNCTIONALITY
// =============================================================================

add_action('wp_footer', 'si_dark_mode_script', 5);
function si_dark_mode_script() {
    ?>
    <script>
    (function() {
        'use strict';
        
        const STORAGE_KEY = 'si-theme';
        const DARK = 'dark';
        const LIGHT = 'light';
        
        function getPreferredTheme() {
            const saved = localStorage.getItem(STORAGE_KEY);
            if (saved) return saved;
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? DARK : LIGHT;
        }
        
        function setTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem(STORAGE_KEY, theme);
            
            // Update toggle buttons
            document.querySelectorAll('[data-theme-toggle]').forEach(btn => {
                const icon = btn.querySelector('i');
                if (icon) {
                    icon.className = theme === DARK 
                        ? 'fa-solid fa-sun' 
                        : 'fa-solid fa-moon';
                }
            });
            
            // Dispatch event for other scripts
            window.dispatchEvent(new CustomEvent('themechange', { detail: { theme } }));
        }
        
        function toggleTheme() {
            const current = document.documentElement.getAttribute('data-theme');
            setTheme(current === DARK ? LIGHT : DARK);
        }
        
        // Initialize
        setTheme(getPreferredTheme());
        
        // Expose globally
        window.siTheme = {
            toggle: toggleTheme,
            set: setTheme,
            get: () => document.documentElement.getAttribute('data-theme')
        };
        
        // Listen for system preference changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
            if (!localStorage.getItem(STORAGE_KEY)) {
                setTheme(e.matches ? DARK : LIGHT);
            }
        });
        
        // Auto-attach to toggle buttons
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-theme-toggle]').forEach(btn => {
                btn.addEventListener('click', toggleTheme);
            });
        });
    })();
    </script>
    <?php
}

// =============================================================================
// SECTION 6: WOOCOMMERCE SUPPORT
// =============================================================================

add_action('after_setup_theme', 'si_woocommerce_support');
function si_woocommerce_support() {
    add_theme_support('woocommerce', array(
        'thumbnail_image_width' => 400,
        'gallery_thumbnail_image_width' => 150,
        'single_image_width' => 600,
        'product_grid' => array(
            'default_rows' => 4,
            'default_columns' => 4,
        ),
    ));
    
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}

// =============================================================================
// SECTION 7: HELPER FUNCTIONS
// =============================================================================

/**
 * Get primary color
 */
function si_primary() {
    return SI_PRIMARY;
}

/**
 * Calculate reading time
 */
function si_reading_time($post_id = null) {
    if (!$post_id) $post_id = get_the_ID();
    $content = get_post_field('post_content', $post_id);
    $words = str_word_count(strip_tags($content));
    return max(1, ceil($words / 200));
}

/**
 * Format large numbers
 */
function si_format_number($num) {
    if ($num >= 1000000) {
        return round($num / 1000000, 1) . 'M';
    }
    if ($num >= 1000) {
        return round($num / 1000, 1) . 'K';
    }
    return number_format($num);
}

/**
 * Get icon HTML
 */
function si_icon($name, $type = 'solid') {
    $prefix = $type === 'brands' ? 'fa-brands' : "fa-{$type}";
    return "<i class=\"{$prefix} fa-{$name}\"></i>";
}

/**
 * Sanitize hex color
 */
function si_sanitize_color($color) {
    if (preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color)) {
        return $color;
    }
    return SI_PRIMARY;
}
