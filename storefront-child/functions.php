<?php
/**
 * Smoke Iran Theme - functions.php
 * 
 * Premium e-commerce child theme for Storefront
 * 
 * @package Smoke_Iran
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Theme Setup
 */
define('SMOKE_IRAN_VERSION', '1.0.0');
define('SMOKE_IRAN_THEME_DIR', get_stylesheet_directory());
define('SMOKE_IRAN_THEME_URI', get_stylesheet_directory_uri());

/**
 * Enqueue Parent and Child Theme Styles
 */
add_action('wp_enqueue_scripts', 'smoke_iran_enqueue_styles', 999);
function smoke_iran_enqueue_styles() {
    // Parent theme style
    wp_enqueue_style('storefront-style', get_template_directory_uri() . '/style.css', array(), SMOKE_IRAN_VERSION);
    
    // Child theme style
    wp_enqueue_style('smoke-iran-style', get_stylesheet_uri(), array('storefront-style'), SMOKE_IRAN_VERSION);
    
    // Font Awesome 7 Pro
    wp_enqueue_style('fontawesome', SMOKE_IRAN_THEME_URI . '/assets/fontawesome/css/all.css', array(), '7.0.0');
    
    // Irancell Fonts
    wp_enqueue_style('irancell-fonts', SMOKE_IRAN_THEME_URI . '/assets/fonts/irancell.css', array(), SMOKE_IRAN_VERSION);
    
    // Custom JavaScript
    wp_enqueue_script('smoke-iran-main', SMOKE_IRAN_THEME_URI . '/assets/js/main.js', array('jquery'), SMOKE_IRAN_VERSION, true);
    
    // Localize script for AJAX
    wp_localize_script('smoke-iran-main', 'smokeIranAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('smoke_iran_nonce'),
    ));
}

/**
 * Theme Support
 */
add_action('after_setup_theme', 'smoke_iran_setup');
function smoke_iran_setup() {
    // Add theme support
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    add_theme_support('custom-logo');
    add_theme_support('custom-header');
    add_theme_support('custom-background');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'smoke-iran'),
        'mobile' => __('Mobile Menu', 'smoke-iran'),
        'footer' => __('Footer Menu', 'smoke-iran'),
    ));
    
    // Set content width
    if (!isset($content_width)) {
        $content_width = 1400;
    }
}

/**
 * Remove Storefront Default Header and Footer
 */
add_action('init', 'smoke_iran_remove_storefront_header_footer', 10);
function smoke_iran_remove_storefront_header_footer() {
    // Remove header
    remove_action('storefront_header', 'storefront_header_container', 0);
    remove_action('storefront_header', 'storefront_skip_links', 5);
    remove_action('storefront_header', 'storefront_social_icons', 10);
    remove_action('storefront_header', 'storefront_site_branding', 20);
    remove_action('storefront_header', 'storefront_secondary_navigation', 30);
    remove_action('storefront_header', 'storefront_product_search', 40);
    remove_action('storefront_header', 'storefront_header_container_close', 41);
    remove_action('storefront_header', 'storefront_primary_navigation_wrapper', 42);
    remove_action('storefront_header', 'storefront_primary_navigation', 50);
    remove_action('storefront_header', 'storefront_header_cart', 60);
    remove_action('storefront_header', 'storefront_primary_navigation_wrapper_close', 68);
    
    // Remove footer
    remove_action('storefront_footer', 'storefront_footer_widgets', 10);
    remove_action('storefront_footer', 'storefront_credit', 20);
}

/**
 * Add Custom Header
 */
add_action('storefront_header', 'smoke_iran_custom_header', 10);
function smoke_iran_custom_header() {
    get_template_part('template-parts/header');
}

/**
 * Add Custom Footer
 */
add_action('storefront_footer', 'smoke_iran_custom_footer', 10);
function smoke_iran_custom_footer() {
    get_template_part('template-parts/footer');
}

/**
 * Register Widget Areas
 */
add_action('widgets_init', 'smoke_iran_widgets_init');
function smoke_iran_widgets_init() {
    // Footer widgets
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar(array(
            'name' => sprintf(__('Footer Column %d', 'smoke-iran'), $i),
            'id' => 'footer-' . $i,
            'description' => sprintf(__('Widget area for footer column %d', 'smoke-iran'), $i),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
    }
    
    // Sidebar
    register_sidebar(array(
        'name' => __('Sidebar', 'smoke-iran'),
        'id' => 'sidebar-1',
        'description' => __('Main sidebar widget area', 'smoke-iran'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}

/**
 * Customizer Settings
 */
add_action('customize_register', 'smoke_iran_customize_register');
function smoke_iran_customize_register($wp_customize) {
    // Primary Color
    $wp_customize->add_setting('smoke_iran_primary_color', array(
        'default' => '#29853A',
        'sanitize_callback' => 'sanitize_hex_color',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'smoke_iran_primary_color', array(
        'label' => __('Primary Color', 'smoke-iran'),
        'section' => 'colors',
        'priority' => 10,
    )));
    
    // Contact Information Section
    $wp_customize->add_section('smoke_iran_contact', array(
        'title' => __('Contact Information', 'smoke-iran'),
        'priority' => 30,
    ));
    
    // Phone
    $wp_customize->add_setting('smoke_iran_phone', array(
        'default' => '021-91097201',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('smoke_iran_phone', array(
        'label' => __('Phone Number', 'smoke-iran'),
        'section' => 'smoke_iran_contact',
        'type' => 'text',
    ));
    
    // Email
    $wp_customize->add_setting('smoke_iran_email', array(
        'default' => 'info@Herfeei.com',
        'sanitize_callback' => 'sanitize_email',
    ));
    
    $wp_customize->add_control('smoke_iran_email', array(
        'label' => __('Email Address', 'smoke-iran'),
        'section' => 'smoke_iran_contact',
        'type' => 'email',
    ));
    
    // WhatsApp
    $wp_customize->add_setting('smoke_iran_whatsapp', array(
        'default' => '989372919617',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('smoke_iran_whatsapp', array(
        'label' => __('WhatsApp Number', 'smoke-iran'),
        'section' => 'smoke_iran_contact',
        'type' => 'text',
    ));
    
    // Address
    $wp_customize->add_setting('smoke_iran_address', array(
        'default' => 'تهران، خیابان انقلاب، نبش خیابان کارگر',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    
    $wp_customize->add_control('smoke_iran_address', array(
        'label' => __('Address', 'smoke-iran'),
        'section' => 'smoke_iran_contact',
        'type' => 'textarea',
    ));
}

/**
 * WooCommerce Customization
 */
if (class_exists('WooCommerce')) {
    
    // Change product columns
    add_filter('loop_shop_columns', 'smoke_iran_loop_columns');
    function smoke_iran_loop_columns() {
        return 4; // 4 columns
    }
    
    // Products per page
    add_filter('loop_shop_per_page', 'smoke_iran_products_per_page', 20);
    function smoke_iran_products_per_page($cols) {
        return 12;
    }
    
    // Update cart count
    add_filter('woocommerce_add_to_cart_fragments', 'smoke_iran_cart_count_fragments');
    function smoke_iran_cart_count_fragments($fragments) {
        $cart_count = WC()->cart->get_cart_contents_count();
        
        ob_start();
        ?>
        <span class="cart-count"><?php echo esc_html($cart_count); ?></span>
        <?php
        $fragments['.cart-count'] = ob_get_clean();
        
        return $fragments;
    }
}

/**
 * Newsletter Subscription Handler
 */
add_action('wp_ajax_smoke_iran_newsletter_subscribe', 'smoke_iran_newsletter_subscribe');
add_action('wp_ajax_nopriv_smoke_iran_newsletter_subscribe', 'smoke_iran_newsletter_subscribe');

function smoke_iran_newsletter_subscribe() {
    check_ajax_referer('smoke_iran_nonce', 'nonce');
    
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    
    if (!is_email($email)) {
        wp_send_json_error(array('message' => __('Invalid email address', 'smoke-iran')));
    }
    
    // Get subscribers
    $subscribers = get_option('smoke_iran_newsletter_subscribers', array());
    
    if (in_array($email, $subscribers)) {
        wp_send_json_error(array('message' => __('Email already subscribed', 'smoke-iran')));
    }
    
    // Add subscriber
    $subscribers[] = $email;
    update_option('smoke_iran_newsletter_subscribers', $subscribers);
    
    // Send confirmation email
    $to = $email;
    $subject = __('Newsletter Subscription Confirmed', 'smoke-iran');
    $message = __('Thank you for subscribing to our newsletter!', 'smoke-iran');
    $headers = array('Content-Type: text/plain; charset=UTF-8');
    
    wp_mail($to, $subject, $message, $headers);
    
    wp_send_json_success(array('message' => __('Successfully subscribed!', 'smoke-iran')));
}

/**
 * Custom Walker for Navigation Menu
 */
class Smoke_Iran_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    function start_lvl(&$output, $depth = 0, $args = null) {
        if ($depth === 0) {
            $output .= '<div class="mega-menu"><div class="mega-menu-inner">';
        } else {
            $output .= '<ul class="sub-menu">';
        }
    }
    
    function end_lvl(&$output, $depth = 0, $args = null) {
        if ($depth === 0) {
            $output .= '</div></div>';
        } else {
            $output .= '</ul>';
        }
    }
    
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $output .= '<li' . $class_names . '>';
        
        $attributes = '';
        $attributes .= !empty($item->url) ? ' href="' . esc_url($item->url) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        
        $item_output = '<a' . $attributes . '>';
        $item_output .= apply_filters('the_title', $item->title, $item->ID);
        
        if (in_array('menu-item-has-children', $classes)) {
            $item_output .= '<i class="fa-solid fa-chevron-down"></i>';
        }
        
        $item_output .= '</a>';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

/**
 * Breadcrumbs
 */
function smoke_iran_breadcrumbs() {
    if (is_front_page()) return;
    
    echo '<nav class="breadcrumbs" aria-label="Breadcrumb">';
    echo '<ol itemscope itemtype="https://schema.org/BreadcrumbList">';
    
    // Home
    echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
    echo '<a itemprop="item" href="' . esc_url(home_url('/')) . '"><span itemprop="name">خانه</span></a>';
    echo '<meta itemprop="position" content="1" />';
    echo '</li>';
    
    if (is_category() || is_single()) {
        $position = 2;
        $categories = get_the_category();
        if (!empty($categories)) {
            $category = $categories[0];
            echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            echo '<a itemprop="item" href="' . esc_url(get_category_link($category->term_id)) . '"><span itemprop="name">' . esc_html($category->name) . '</span></a>';
            echo '<meta itemprop="position" content="' . $position . '" />';
            echo '</li>';
            $position++;
        }
        
        if (is_single()) {
            echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            echo '<span itemprop="name">' . get_the_title() . '</span>';
            echo '<meta itemprop="position" content="' . $position . '" />';
            echo '</li>';
        }
    } elseif (is_page()) {
        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . get_the_title() . '</span>';
        echo '<meta itemprop="position" content="2" />';
        echo '</li>';
    } elseif (is_search()) {
        echo '<li><span>نتایج جستجو برای: ' . get_search_query() . '</span></li>';
    } elseif (is_404()) {
        echo '<li><span>صفحه پیدا نشد</span></li>';
    }
    
    echo '</ol>';
    echo '</nav>';
}

/**
 * Pagination
 */
function smoke_iran_pagination() {
    global $wp_query;
    
    if ($wp_query->max_num_pages <= 1) return;
    
    $big = 999999999;
    
    $paginate_links = paginate_links(array(
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages,
        'prev_text' => '<i class="fa-solid fa-chevron-right"></i>',
        'next_text' => '<i class="fa-solid fa-chevron-left"></i>',
        'type' => 'array',
        'mid_size' => 2,
    ));
    
    if ($paginate_links) {
        echo '<nav class="pagination" aria-label="Page navigation">';
        echo '<ul class="page-numbers">';
        foreach ($paginate_links as $link) {
            echo '<li>' . $link . '</li>';
        }
        echo '</ul>';
        echo '</nav>';
    }
}

/**
 * Reading Time Calculator
 */
function smoke_iran_reading_time() {
    $content = get_post_field('post_content', get_the_ID());
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // 200 words per minute
    
    return sprintf(__('%d min read', 'smoke-iran'), $reading_time);
}

/**
 * Post Views Counter
 */
function smoke_iran_set_post_views($post_id) {
    $count_key = 'smoke_iran_post_views';
    $count = get_post_meta($post_id, $count_key, true);
    
    if ($count == '') {
        $count = 0;
        delete_post_meta($post_id, $count_key);
        add_post_meta($post_id, $count_key, '0');
    } else {
        $count++;
        update_post_meta($post_id, $count_key, $count);
    }
}

function smoke_iran_get_post_views($post_id) {
    $count_key = 'smoke_iran_post_views';
    $count = get_post_meta($post_id, $count_key, true);
    
    if ($count == '') {
        delete_post_meta($post_id, $count_key);
        add_post_meta($post_id, $count_key, '0');
        return '0';
    }
    
    return $count;
}

// Track post views
add_action('wp_head', 'smoke_iran_track_post_views');
function smoke_iran_track_post_views() {
    if (is_single() && !is_user_logged_in()) {
        smoke_iran_set_post_views(get_the_ID());
    }
}

/**
 * Security Enhancements
 */
// Remove WordPress version
remove_action('wp_head', 'wp_generator');

// Disable XML-RPC
add_filter('xmlrpc_enabled', '__return_false');

// Remove RSD link
remove_action('wp_head', 'rsd_link');

// Remove wlwmanifest link
remove_action('wp_head', 'wlwmanifest_link');

/**
 * Performance Optimization
 */
// Defer JavaScript
add_filter('script_loader_tag', 'smoke_iran_defer_scripts', 10, 2);
function smoke_iran_defer_scripts($tag, $handle) {
    $scripts_to_defer = array('smoke-iran-main');
    
    if (in_array($handle, $scripts_to_defer)) {
        return str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}

// Disable emoji
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// Optimize WooCommerce Scripts
add_action('wp_enqueue_scripts', 'smoke_iran_optimize_woocommerce_scripts', 99);
function smoke_iran_optimize_woocommerce_scripts() {
    if (!is_woocommerce() && !is_cart() && !is_checkout()) {
        wp_dequeue_style('woocommerce-general');
        wp_dequeue_style('woocommerce-layout');
        wp_dequeue_style('woocommerce-smallscreen');
        wp_dequeue_script('wc-cart-fragments');
        wp_dequeue_script('woocommerce');
        wp_dequeue_script('wc-add-to-cart');
    }
}

/**
 * Helper Functions
 */
function smoke_iran_get_option($key, $default = '') {
    return get_theme_mod($key, $default);
}

function smoke_iran_posted_on() {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    
    $time_string = sprintf($time_string,
        esc_attr(get_the_date(DATE_W3C)),
        esc_html(get_the_date())
    );
    
    echo '<span class="posted-on">' . $time_string . '</span>';
}

function smoke_iran_posted_by() {
    echo '<span class="byline"> توسط <a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>';
}
