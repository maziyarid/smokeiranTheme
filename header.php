/**
 * Professional Header for Storefront Child Theme
 * Snippet Name: SI-Header
 * Priority: 10
 * Location: Run Everywhere
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Navigation Menus
 */
add_action( 'after_setup_theme', 'flavor_register_menus', 5 );
function flavor_register_menus() {
    register_nav_menus( array(
        'flavor-primary'  => 'منوی اصلی',
        'flavor-mobile'   => 'منوی موبایل',
    ) );
}

/**
 * Remove Default Storefront Header
 */
add_action( 'init', 'flavor_remove_storefront_header', 20 );
function flavor_remove_storefront_header() {
    remove_action( 'storefront_header', 'storefront_header_container', 0 );
    remove_action( 'storefront_header', 'storefront_skip_links', 5 );
    remove_action( 'storefront_header', 'storefront_social_icons', 10 );
    remove_action( 'storefront_header', 'storefront_site_branding', 20 );
    remove_action( 'storefront_header', 'storefront_secondary_navigation', 30 );
    remove_action( 'storefront_header', 'storefront_product_search', 40 );
    remove_action( 'storefront_header', 'storefront_header_container_close', 41 );
    remove_action( 'storefront_header', 'storefront_primary_navigation_wrapper', 42 );
    remove_action( 'storefront_header', 'storefront_primary_navigation', 50 );
    remove_action( 'storefront_header', 'storefront_header_cart', 60 );
    remove_action( 'storefront_header', 'storefront_primary_navigation_wrapper_close', 68 );
}

/**
 * Render Custom Header
 */
add_action( 'storefront_header', 'flavor_render_header', 5 );
function flavor_render_header() {
    
    // Get logo
    $logo_id  = get_theme_mod( 'custom_logo' );
    $logo_url = '';
    if ( $logo_id ) {
        $logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
    }
    $site_name = get_bloginfo( 'name' );
    
    // Get settings
    $phone = get_theme_mod( 'flavor_phone', '۰۲۱-۸۸۷۷۶۶۵۵' );
    $email = get_theme_mod( 'flavor_email', 'info@flavor-flavor.ir' );
    
    // WooCommerce data
    $cart_count = 0;
    $cart_url   = home_url( '/cart/' );
    $shop_url   = home_url( '/shop/' );
    $account_url = home_url( '/my-account/' );
    
    if ( class_exists( 'WooCommerce' ) ) {
        if ( WC()->cart ) {
            $cart_count = WC()->cart->get_cart_contents_count();
        }
        $cart_url    = wc_get_cart_url();
        $shop_url    = wc_get_page_permalink( 'shop' );
        $account_url = wc_get_page_permalink( 'myaccount' );
    }
    
    // Wishlist count
    $wishlist_count = 0;
    if ( function_exists( 'yith_wcwl_count_products' ) ) {
        $wishlist_count = yith_wcwl_count_products();
    }
    
    ?>
    
    <!-- Top Bar -->
    <div class="fh-topbar">
        <div class="fh-container">
            <div class="fh-topbar-inner">
                <div class="fh-topbar-right">
                    <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="fh-topbar-link">
                        <i class="fa-solid fa-phone"></i>
                        <span><?php echo esc_html( $phone ); ?></span>
                    </a>
                    <span class="fh-topbar-sep"></span>
                    <a href="mailto:<?php echo esc_attr( $email ); ?>" class="fh-topbar-link">
                        <i class="fa-solid fa-envelope"></i>
                        <span><?php echo esc_html( $email ); ?></span>
                    </a>
                </div>
                <div class="fh-topbar-left">
                    <?php if ( is_user_logged_in() ) : ?>
                        <?php $current_user = wp_get_current_user(); ?>
                        <a href="<?php echo esc_url( $account_url ); ?>" class="fh-topbar-link">
                            <i class="fa-solid fa-user-check"></i>
                            <span><?php echo esc_html( $current_user->display_name ); ?></span>
                        </a>
                        <span class="fh-topbar-sep"></span>
                        <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>" class="fh-topbar-link fh-logout">
                            <i class="fa-solid fa-right-from-bracket"></i>
                            <span>خروج</span>
                        </a>
                    <?php else : ?>
                        <a href="<?php echo esc_url( $account_url ); ?>" class="fh-topbar-link">
                            <i class="fa-solid fa-right-to-bracket"></i>
                            <span>ورود / ثبت‌نام</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Main Header -->
    <header class="fh-header" id="fh-header">
        <div class="fh-container">
            <div class="fh-header-inner">
                
                <!-- Mobile Menu Button -->
                <button type="button" class="fh-mobile-btn" id="fh-mobile-btn" aria-label="منو">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                
                <!-- Logo -->
                <div class="fh-logo">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <?php if ( $logo_url ) : ?>
                            <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $site_name ); ?>" class="fh-logo-img">
                        <?php else : ?>
                            <span class="fh-logo-text"><?php echo esc_html( $site_name ); ?></span>
                        <?php endif; ?>
                    </a>
                </div>
                
                <!-- Search -->
                <div class="fh-search">
                    <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="fh-search-form">
                        <select name="product_cat" class="fh-search-cat">
                            <option value="">همه دسته‌ها</option>
                            <?php
                            $cats = get_terms( array(
                                'taxonomy'   => 'product_cat',
                                'hide_empty' => true,
                                'parent'     => 0,
                            ) );
                            if ( ! empty( $cats ) && ! is_wp_error( $cats ) ) :
                                foreach ( $cats as $cat ) :
                            ?>
                                <option value="<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></option>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </select>
                        <input type="search" name="s" class="fh-search-input" placeholder="جستجو در محصولات..." value="<?php echo esc_attr( get_search_query() ); ?>">
                        <input type="hidden" name="post_type" value="product">
                        <button type="submit" class="fh-search-btn">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                </div>
                
                <!-- Actions -->
                <div class="fh-actions">
                    
                    <!-- Dark Mode -->
                    <button type="button" class="fh-action fh-darkmode" id="fh-darkmode" aria-label="تغییر تم">
                        <i class="fa-solid fa-moon fh-icon-dark"></i>
                        <i class="fa-solid fa-sun fh-icon-light"></i>
                    </button>
                    
                    <!-- Wishlist -->
                    <a href="<?php echo esc_url( home_url( '/wishlist/' ) ); ?>" class="fh-action fh-wishlist">
                        <i class="fa-regular fa-heart"></i>
                        <?php if ( $wishlist_count > 0 ) : ?>
                            <span class="fh-badge"><?php echo esc_html( $wishlist_count ); ?></span>
                        <?php endif; ?>
                    </a>
                    
                    <!-- Account -->
                    <a href="<?php echo esc_url( $account_url ); ?>" class="fh-action fh-account">
                        <i class="fa-regular fa-user"></i>
                    </a>
                    
                    <!-- Cart -->
                    <a href="<?php echo esc_url( $cart_url ); ?>" class="fh-action fh-cart" id="fh-cart-btn">
                        <i class="fa-solid fa-bag-shopping"></i>
                        <span class="fh-badge fh-cart-count"><?php echo esc_html( $cart_count ); ?></span>
                    </a>
                    
                </div>
                
            </div>
        </div>
        
        <!-- Navigation -->
        <nav class="fh-nav" id="fh-nav">
            <div class="fh-container">
                <div class="fh-nav-inner">
                    
                    <!-- Categories Button -->
                    <div class="fh-cats" id="fh-cats">
                        <button type="button" class="fh-cats-btn">
                            <i class="fa-solid fa-grid-2"></i>
                            <span>دسته‌بندی‌ها</span>
                            <i class="fa-solid fa-chevron-down fh-cats-arrow"></i>
                        </button>
                        <div class="fh-cats-dropdown">
                            <ul class="fh-cats-list">
                                <?php
                                $product_cats = get_terms( array(
                                    'taxonomy'   => 'product_cat',
                                    'hide_empty' => false,
                                    'parent'     => 0,
                                    'number'     => 10,
                                ) );
                                if ( ! empty( $product_cats ) && ! is_wp_error( $product_cats ) ) :
                                    foreach ( $product_cats as $pcat ) :
                                        $pcat_link = get_term_link( $pcat );
                                        $pcat_thumb_id = get_term_meta( $pcat->term_id, 'thumbnail_id', true );
                                        $pcat_thumb = $pcat_thumb_id ? wp_get_attachment_url( $pcat_thumb_id ) : '';
                                        
                                        // Get subcategories
                                        $subcats = get_terms( array(
                                            'taxonomy'   => 'product_cat',
                                            'hide_empty' => false,
                                            'parent'     => $pcat->term_id,
                                            'number'     => 6,
                                        ) );
                                        $has_subcats = ! empty( $subcats ) && ! is_wp_error( $subcats );
                                ?>
                                <li class="fh-cat-item<?php echo $has_subcats ? ' has-sub' : ''; ?>">
                                    <a href="<?php echo esc_url( $pcat_link ); ?>" class="fh-cat-link">
                                        <?php if ( $pcat_thumb ) : ?>
                                            <img src="<?php echo esc_url( $pcat_thumb ); ?>" alt="" class="fh-cat-img">
                                        <?php else : ?>
                                            <i class="fa-solid fa-folder fh-cat-icon"></i>
                                        <?php endif; ?>
                                        <span class="fh-cat-name"><?php echo esc_html( $pcat->name ); ?></span>
                                        <span class="fh-cat-count">(<?php echo esc_html( $pcat->count ); ?>)</span>
                                        <?php if ( $has_subcats ) : ?>
                                            <i class="fa-solid fa-chevron-left fh-cat-arrow"></i>
                                        <?php endif; ?>
                                    </a>
                                    <?php if ( $has_subcats ) : ?>
                                    <div class="fh-cat-sub">
                                        <div class="fh-cat-sub-inner">
                                            <h4 class="fh-cat-sub-title">
                                                <a href="<?php echo esc_url( $pcat_link ); ?>">زیرمجموعه‌های <?php echo esc_html( $pcat->name ); ?></a>
                                            </h4>
                                            <ul class="fh-cat-sub-list">
                                                <?php foreach ( $subcats as $subcat ) : ?>
                                                <li>
                                                    <a href="<?php echo esc_url( get_term_link( $subcat ) ); ?>">
                                                        <?php echo esc_html( $subcat->name ); ?>
                                                        <span>(<?php echo esc_html( $subcat->count ); ?>)</span>
                                                    </a>
                                                </li>
                                                <?php endforeach; ?>
                                                <li class="fh-cat-sub-all">
                                                    <a href="<?php echo esc_url( $pcat_link ); ?>">مشاهده همه <i class="fa-solid fa-arrow-left"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </li>
                                <?php
                                    endforeach;
                                endif;
                                ?>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Main Menu -->
                    <ul class="fh-menu" id="fh-menu">
                        <li class="fh-menu-item">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="fh-menu-link">
                                <i class="fa-solid fa-house"></i>
                                <span>خانه</span>
                            </a>
                        </li>
                        <li class="fh-menu-item">
                            <a href="<?php echo esc_url( $shop_url ); ?>" class="fh-menu-link">
                                <i class="fa-solid fa-shop"></i>
                                <span>فروشگاه</span>
                            </a>
                        </li>
                        <li class="fh-menu-item fh-has-sub">
                            <a href="#" class="fh-menu-link">
                                <i class="fa-solid fa-percent"></i>
                                <span>تخفیف‌ها</span>
                                <i class="fa-solid fa-chevron-down fh-menu-arrow"></i>
                            </a>
                            <ul class="fh-submenu">
                                <li><a href="<?php echo esc_url( $shop_url . '?on_sale=1' ); ?>"><i class="fa-solid fa-fire"></i> حراج‌ها</a></li>
                                <li><a href="<?php echo esc_url( $shop_url . '?orderby=date' ); ?>"><i class="fa-solid fa-sparkles"></i> جدیدترین‌ها</a></li>
                                <li><a href="<?php echo esc_url( $shop_url . '?orderby=popularity' ); ?>"><i class="fa-solid fa-star"></i> پرفروش‌ترین‌ها</a></li>
                            </ul>
                        </li>
                        <li class="fh-menu-item">
                            <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="fh-menu-link">
                                <i class="fa-solid fa-newspaper"></i>
                                <span>مجله</span>
                            </a>
                        </li>
                        <li class="fh-menu-item">
                            <a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>" class="fh-menu-link">
                                <i class="fa-solid fa-circle-info"></i>
                                <span>درباره ما</span>
                            </a>
                        </li>
                        <li class="fh-menu-item">
                            <a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>" class="fh-menu-link">
                                <i class="fa-solid fa-headset"></i>
                                <span>تماس با ما</span>
                            </a>
                        </li>
                    </ul>
                    
                    <!-- Special Link -->
                    <div class="fh-nav-special">
                        <a href="<?php echo esc_url( $shop_url . '?on_sale=1' ); ?>" class="fh-special-btn">
                            <i class="fa-solid fa-fire-flame-curved"></i>
                            <span>پیشنهاد ویژه</span>
                        </a>
                    </div>
                    
                </div>
            </div>
        </nav>
    </header>
    
    <!-- Mobile Overlay -->
    <div class="fh-overlay" id="fh-overlay"></div>
    
    <!-- Mobile Menu -->
    <aside class="fh-mobile" id="fh-mobile">
        <div class="fh-mobile-header">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="fh-mobile-logo">
                <?php if ( $logo_url ) : ?>
                    <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $site_name ); ?>">
                <?php else : ?>
                    <span><?php echo esc_html( $site_name ); ?></span>
                <?php endif; ?>
            </a>
            <button type="button" class="fh-mobile-close" id="fh-mobile-close" aria-label="بستن">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        
        <div class="fh-mobile-search">
            <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <input type="hidden" name="post_type" value="product">
                <input type="search" name="s" placeholder="جستجو...">
                <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>
        
        <nav class="fh-mobile-nav">
            <ul class="fh-mobile-menu">
                <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="fa-solid fa-house"></i> خانه</a></li>
                <li><a href="<?php echo esc_url( $shop_url ); ?>"><i class="fa-solid fa-shop"></i> فروشگاه</a></li>
                <li><a href="<?php echo esc_url( $shop_url . '?on_sale=1' ); ?>"><i class="fa-solid fa-percent"></i> تخفیف‌ها</a></li>
                <li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><i class="fa-solid fa-newspaper"></i> مجله</a></li>
                <li><a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>"><i class="fa-solid fa-circle-info"></i> درباره ما</a></li>
                <li><a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"><i class="fa-solid fa-headset"></i> تماس با ما</a></li>
            </ul>
        </nav>
        
        <div class="fh-mobile-actions">
            <a href="<?php echo esc_url( $account_url ); ?>" class="fh-mobile-action">
                <i class="fa-solid fa-user"></i>
                <span>حساب کاربری</span>
            </a>
            <a href="<?php echo esc_url( home_url( '/wishlist/' ) ); ?>" class="fh-mobile-action">
                <i class="fa-solid fa-heart"></i>
                <span>علاقه‌مندی‌ها</span>
            </a>
            <a href="<?php echo esc_url( $cart_url ); ?>" class="fh-mobile-action">
                <i class="fa-solid fa-bag-shopping"></i>
                <span>سبد خرید</span>
            </a>
        </div>
        
        <div class="fh-mobile-bottom">
            <button type="button" class="fh-mobile-theme" id="fh-mobile-theme">
                <i class="fa-solid fa-moon fh-icon-dark"></i>
                <i class="fa-solid fa-sun fh-icon-light"></i>
                <span class="fh-theme-dark">حالت شب</span>
                <span class="fh-theme-light">حالت روز</span>
            </button>
            <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="fh-mobile-phone">
                <i class="fa-solid fa-phone"></i>
                <span><?php echo esc_html( $phone ); ?></span>
            </a>
        </div>
    </aside>
    
    <?php
}

/**
 * Header CSS
 */
add_action( 'wp_head', 'flavor_header_css', 20 );
function flavor_header_css() {
    ?>
    <style id="fh-css">
    /* =============================================
       HEADER STYLES
       Primary: #29853A | Font: Irancell
       ============================================= */
    
    :root {
        --fh-primary: #29853A;
        --fh-primary-dark: #1e6b2d;
        --fh-dark: #1a1a2e;
        --fh-text: #333;
        --fh-text-light: #666;
        --fh-border: #e5e7eb;
        --fh-bg: #fff;
        --fh-bg-light: #f8f9fa;
        --fh-radius: 10px;
        --fh-shadow: 0 4px 15px rgba(0,0,0,0.08);
        --fh-transition: 0.3s ease;
    }
    
    [data-theme="dark"] {
        --fh-text: #f4f4f5;
        --fh-text-light: #a1a1aa;
        --fh-border: #2d2d3a;
        --fh-bg: #13131a;
        --fh-bg-light: #1a1a24;
    }
    
    /* Container */
    .fh-container {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 24px;
    }
    
    /* =============================================
       TOP BAR
       ============================================= */
    .fh-topbar {
        background: var(--fh-dark);
        padding: 10px 0;
        font-size: 13px;
    }
    
    [data-theme="dark"] .fh-topbar {
        background: #0d0d12;
        border-bottom: 1px solid var(--fh-border);
    }
    
    .fh-topbar-inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .fh-topbar-right,
    .fh-topbar-left {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    .fh-topbar-link {
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgba(255,255,255,0.8);
        text-decoration: none;
        transition: color var(--fh-transition);
    }
    
    .fh-topbar-link:hover {
        color: #fff;
    }
    
    .fh-topbar-link i {
        font-size: 12px;
    }
    
    .fh-topbar-sep {
        width: 1px;
        height: 14px;
        background: rgba(255,255,255,0.2);
    }
    
    .fh-logout {
        color: #ef4444 !important;
    }
    
    /* =============================================
       MAIN HEADER
       ============================================= */
    .fh-header {
        background: var(--fh-bg);
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: var(--fh-shadow);
    }
    
    .fh-header-inner {
        display: flex;
        align-items: center;
        gap: 30px;
        padding: 16px 0;
    }
    
    /* Mobile Button */
    .fh-mobile-btn {
        display: none;
        flex-direction: column;
        justify-content: center;
        gap: 5px;
        width: 40px;
        height: 40px;
        background: transparent;
        border: none;
        cursor: pointer;
        padding: 8px;
    }
    
    .fh-mobile-btn span {
        display: block;
        height: 2px;
        background: var(--fh-text);
        border-radius: 2px;
        transition: var(--fh-transition);
    }
    
    /* Logo */
    .fh-logo a {
        display: flex;
        align-items: center;
    }
    
    .fh-logo-img {
        max-height: 50px;
        width: auto;
    }
    
    .fh-logo-text {
        font-size: 24px;
        font-weight: 700;
        color: var(--fh-primary);
    }
    
    /* Search */
    .fh-search {
        flex: 1;
        max-width: 550px;
    }
    
    .fh-search-form {
        display: flex;
        border: 2px solid var(--fh-border);
        border-radius: 50px;
        overflow: hidden;
        transition: border-color var(--fh-transition);
    }
    
    .fh-search-form:focus-within {
        border-color: var(--fh-primary);
    }
    
    .fh-search-cat {
        width: 130px;
        padding: 14px 16px;
        border: none;
        border-left: 1px solid var(--fh-border);
        background: var(--fh-bg-light);
        font-family: inherit;
        font-size: 13px;
        color: var(--fh-text-light);
        cursor: pointer;
        outline: none;
    }
    
    .fh-search-input {
        flex: 1;
        padding: 14px 18px;
        border: none;
        background: transparent;
        font-family: inherit;
        font-size: 14px;
        color: var(--fh-text);
        outline: none;
    }
    
    .fh-search-input::placeholder {
        color: var(--fh-text-light);
    }
    
    .fh-search-btn {
        padding: 14px 22px;
        background: var(--fh-primary);
        border: none;
        color: #fff;
        cursor: pointer;
        transition: background var(--fh-transition);
    }
    
    .fh-search-btn:hover {
        background: var(--fh-primary-dark);
    }
    
    /* Actions */
    .fh-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .fh-action {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        background: var(--fh-bg-light);
        border: none;
        border-radius: var(--fh-radius);
        color: var(--fh-text);
        text-decoration: none;
        cursor: pointer;
        transition: all var(--fh-transition);
    }
    
    .fh-action:hover {
        background: var(--fh-primary);
        color: #fff;
    }
    
    .fh-action i {
        font-size: 18px;
    }
    
    .fh-badge {
        position: absolute;
        top: -4px;
        left: -4px;
        min-width: 20px;
        height: 20px;
        padding: 0 6px;
        background: #ef4444;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        line-height: 20px;
        text-align: center;
        border-radius: 50px;
    }
    
    /* Dark Mode Toggle */
    .fh-darkmode .fh-icon-light,
    .fh-mobile-theme .fh-icon-light,
    .fh-mobile-theme .fh-theme-light {
        display: none;
    }
    
    [data-theme="dark"] .fh-darkmode .fh-icon-dark,
    [data-theme="dark"] .fh-mobile-theme .fh-icon-dark,
    [data-theme="dark"] .fh-mobile-theme .fh-theme-dark {
        display: none;
    }
    
    [data-theme="dark"] .fh-darkmode .fh-icon-light,
    [data-theme="dark"] .fh-mobile-theme .fh-icon-light,
    [data-theme="dark"] .fh-mobile-theme .fh-theme-light {
        display: inline;
    }
    
    [data-theme="dark"] .fh-darkmode .fh-icon-light {
        color: #fbbf24;
    }
    
    /* =============================================
       NAVIGATION
       ============================================= */
    .fh-nav {
        background: var(--fh-primary);
    }
    
    .fh-nav-inner {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    /* Categories Dropdown */
    .fh-cats {
        position: relative;
    }
    
    .fh-cats-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 20px;
        background: rgba(255,255,255,0.1);
        border: none;
        color: #fff;
        font-family: inherit;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background var(--fh-transition);
    }
    
    .fh-cats-btn:hover {
        background: rgba(255,255,255,0.2);
    }
    
    .fh-cats-arrow {
        font-size: 10px;
        transition: transform var(--fh-transition);
    }
    
    .fh-cats:hover .fh-cats-arrow {
        transform: rotate(180deg);
    }
    
    .fh-cats-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        width: 280px;
        background: var(--fh-bg);
        border-radius: 0 0 var(--fh-radius) var(--fh-radius);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all var(--fh-transition);
        z-index: 100;
    }
    
    .fh-cats:hover .fh-cats-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    
    .fh-cats-list {
        list-style: none;
        margin: 0;
        padding: 10px 0;
    }
    
    .fh-cat-item {
        position: relative;
    }
    
    .fh-cat-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 20px;
        color: var(--fh-text);
        text-decoration: none;
        transition: all var(--fh-transition);
    }
    
    .fh-cat-link:hover {
        background: var(--fh-bg-light);
        color: var(--fh-primary);
    }
    
    .fh-cat-img {
        width: 30px;
        height: 30px;
        object-fit: cover;
        border-radius: 6px;
    }
    
    .fh-cat-icon {
        width: 30px;
        text-align: center;
        color: var(--fh-primary);
    }
    
    .fh-cat-name {
        flex: 1;
        font-size: 14px;
    }
    
    .fh-cat-count {
        font-size: 12px;
        color: var(--fh-text-light);
    }
    
    .fh-cat-arrow {
        font-size: 10px;
        color: var(--fh-text-light);
    }
    
    /* Subcategory Panel */
    .fh-cat-sub {
        position: absolute;
        top: 0;
        right: 100%;
        width: 300px;
        background: var(--fh-bg);
        border-radius: var(--fh-radius);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        padding: 20px;
        opacity: 0;
        visibility: hidden;
        transform: translateX(10px);
        transition: all var(--fh-transition);
        z-index: 101;
    }
    
    .fh-cat-item:hover .fh-cat-sub {
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
    }
    
    .fh-cat-sub-title {
        font-size: 14px;
        font-weight: 600;
        color: var(--fh-primary);
        margin: 0 0 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--fh-primary);
    }
    
    .fh-cat-sub-title a {
        color: inherit;
        text-decoration: none;
    }
    
    .fh-cat-sub-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .fh-cat-sub-list li a {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 0;
        color: var(--fh-text-light);
        text-decoration: none;
        font-size: 13px;
        transition: all var(--fh-transition);
    }
    
    .fh-cat-sub-list li a:hover {
        color: var(--fh-primary);
        padding-right: 8px;
    }
    
    .fh-cat-sub-list li a span {
        font-size: 12px;
        opacity: 0.7;
    }
    
    .fh-cat-sub-all a {
        color: var(--fh-primary) !important;
        font-weight: 500;
        margin-top: 10px;
        padding-top: 10px !important;
        border-top: 1px dashed var(--fh-border);
    }
    
    /* Main Menu */
    .fh-menu {
        display: flex;
        align-items: center;
        list-style: none;
        margin: 0;
        padding: 0;
        flex: 1;
    }
    
    .fh-menu-item {
        position: relative;
    }
    
    .fh-menu-link {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 14px 16px;
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: background var(--fh-transition);
    }
    
    .fh-menu-link:hover {
        background: rgba(255,255,255,0.1);
    }
    
    .fh-menu-link i:first-child {
        font-size: 13px;
        opacity: 0.8;
    }
    
    .fh-menu-arrow {
        font-size: 10px;
        margin-right: 4px;
        transition: transform var(--fh-transition);
    }
    
    .fh-has-sub:hover .fh-menu-arrow {
        transform: rotate(180deg);
    }
    
    /* Submenu */
    .fh-submenu {
        position: absolute;
        top: 100%;
        right: 0;
        min-width: 200px;
        background: var(--fh-bg);
        border-radius: 0 0 var(--fh-radius) var(--fh-radius);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        list-style: none;
        margin: 0;
        padding: 10px 0;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all var(--fh-transition);
        z-index: 100;
    }
    
    .fh-has-sub:hover .fh-submenu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    
    .fh-submenu li a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 20px;
        color: var(--fh-text);
        text-decoration: none;
        font-size: 13px;
        transition: all var(--fh-transition);
    }
    
    .fh-submenu li a:hover {
        background: var(--fh-bg-light);
        color: var(--fh-primary);
    }
    
    .fh-submenu li a i {
        width: 18px;
        text-align: center;
        color: var(--fh-primary);
        font-size: 12px;
    }
    
    /* Special Button */
    .fh-nav-special {
        margin-right: auto;
    }
    
    .fh-special-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: rgba(255,255,255,0.15);
        border-radius: 50px;
        color: #fff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: all var(--fh-transition);
    }
    
    .fh-special-btn:hover {
        background: #fff;
        color: var(--fh-primary);
    }
    
    .fh-special-btn i {
        color: #ff6b35;
    }
    
    /* =============================================
       MOBILE MENU
       ============================================= */
    .fh-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.6);
        opacity: 0;
        visibility: hidden;
        transition: all var(--fh-transition);
        z-index: 998;
    }
    
    .fh-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    .fh-mobile {
        position: fixed;
        top: 0;
        right: -300px;
        width: 300px;
        height: 100%;
        background: var(--fh-bg);
        display: flex;
        flex-direction: column;
        transition: right var(--fh-transition);
        z-index: 999;
        overflow-y: auto;
    }
    
    .fh-mobile.active {
        right: 0;
    }
    
    .fh-mobile-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        border-bottom: 1px solid var(--fh-border);
    }
    
    .fh-mobile-logo img {
        max-height: 36px;
        width: auto;
    }
    
    .fh-mobile-close {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--fh-bg-light);
        border: none;
        border-radius: 8px;
        color: var(--fh-text);
        cursor: pointer;
    }
    
    .fh-mobile-search {
        padding: 16px 20px;
        border-bottom: 1px solid var(--fh-border);
    }
    
    .fh-mobile-search form {
        display: flex;
        background: var(--fh-bg-light);
        border-radius: 50px;
        overflow: hidden;
    }
    
    .fh-mobile-search input {
        flex: 1;
        padding: 12px 16px;
        border: none;
        background: transparent;
        font-family: inherit;
        font-size: 14px;
        color: var(--fh-text);
        outline: none;
    }
    
    .fh-mobile-search button {
        padding: 12px 16px;
        background: var(--fh-primary);
        border: none;
        color: #fff;
        cursor: pointer;
    }
    
    .fh-mobile-nav {
        flex: 1;
        padding: 16px 20px;
    }
    
    .fh-mobile-menu {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .fh-mobile-menu li {
        border-bottom: 1px solid var(--fh-border);
    }
    
    .fh-mobile-menu li a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 0;
        color: var(--fh-text);
        text-decoration: none;
        font-weight: 500;
    }
    
    .fh-mobile-menu li a i {
        width: 20px;
        text-align: center;
        color: var(--fh-text-light);
    }
    
    .fh-mobile-actions {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        padding: 16px 20px;
        border-top: 1px solid var(--fh-border);
    }
    
    .fh-mobile-action {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        padding: 12px 8px;
        background: var(--fh-bg-light);
        border-radius: 10px;
        color: var(--fh-text);
        text-decoration: none;
        font-size: 12px;
    }
    
    .fh-mobile-action i {
        font-size: 18px;
    }
    
    .fh-mobile-bottom {
        padding: 16px 20px;
        background: var(--fh-bg-light);
        border-top: 1px solid var(--fh-border);
    }
    
    .fh-mobile-theme {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 12px;
        background: var(--fh-bg);
        border: 1px solid var(--fh-border);
        border-radius: 10px;
        color: var(--fh-text);
        font-family: inherit;
        font-size: 14px;
        cursor: pointer;
        margin-bottom: 12px;
    }
    
    .fh-mobile-phone {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: var(--fh-primary);
        text-decoration: none;
        font-weight: 500;
    }
    
    /* =============================================
       RESPONSIVE
       ============================================= */
    @media (max-width: 1200px) {
        .fh-search {
            max-width: 400px;
        }
        
        .fh-cat-sub {
            width: 250px;
        }
    }
    
    @media (max-width: 991px) {
        .fh-topbar {
            display: none;
        }
        
        .fh-mobile-btn {
            display: flex;
        }
        
        .fh-search {
            display: none;
        }
        
        .fh-nav {
            display: none;
        }
        
        .fh-account {
            display: none;
        }
        
        .fh-header-inner {
            gap: 15px;
        }
    }
    
    @media (max-width: 576px) {
        .fh-container {
            padding: 0 16px;
        }
        
        .fh-header-inner {
            padding: 12px 0;
        }
        
        .fh-logo-img {
            max-height: 38px;
        }
        
        .fh-action {
            width: 40px;
            height: 40px;
        }
        
        .fh-action i {
            font-size: 16px;
        }
        
        .fh-wishlist {
            display: none;
        }
    }
    </style>
    <?php
}

/**
 * Header JavaScript
 */
add_action( 'wp_footer', 'flavor_header_js', 20 );
function flavor_header_js() {
    ?>
    <script id="fh-js">
    (function() {
        var mobileBtn = document.getElementById('fh-mobile-btn');
        var mobileClose = document.getElementById('fh-mobile-close');
        var mobile = document.getElementById('fh-mobile');
        var overlay = document.getElementById('fh-overlay');
        var darkBtn = document.getElementById('fh-darkmode');
        var darkMobile = document.getElementById('fh-mobile-theme');
        var header = document.getElementById('fh-header');
        
        // Mobile menu
        function openMobile() {
            mobile.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeMobile() {
            mobile.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        if (mobileBtn) mobileBtn.addEventListener('click', openMobile);
        if (mobileClose) mobileClose.addEventListener('click', closeMobile);
        if (overlay) overlay.addEventListener('click', closeMobile);
        
        // Dark mode
        function toggleDark() {
            var current = document.documentElement.getAttribute('data-theme');
            var next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
        }
        
        function initDark() {
            var saved = localStorage.getItem('theme');
            if (saved) {
                document.documentElement.setAttribute('data-theme', saved);
            } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        }
        
        initDark();
        
        if (darkBtn) darkBtn.addEventListener('click', toggleDark);
        if (darkMobile) darkMobile.addEventListener('click', toggleDark);
        
        // Sticky header shadow
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.style.boxShadow = '0 4px 20px rgba(0,0,0,0.12)';
            } else {
                header.style.boxShadow = '0 4px 15px rgba(0,0,0,0.08)';
            }
        }, {passive: true});
        
        // Close on escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMobile();
            }
        });
    })();
    </script>
    <?php
}

/**
 * Cart Count Fragment
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'flavor_cart_fragment' );
function flavor_cart_fragment( $fragments ) {
    $count = 0;
    if ( WC()->cart ) {
        $count = WC()->cart->get_cart_contents_count();
    }
    $fragments['.fh-cart-count'] = '<span class="fh-badge fh-cart-count">' . esc_html( $count ) . '</span>';
    return $fragments;
}

/**
 * Header Customizer
 */
add_action( 'customize_register', 'flavor_header_customizer' );
function flavor_header_customizer( $wp_customize ) {
    $wp_customize->add_section( 'flavor_header', array(
        'title'    => 'تنظیمات هدر',
        'priority' => 30,
    ) );
    
    $wp_customize->add_setting( 'flavor_phone', array(
        'default'           => '۰۲۱-۸۸۷۷۶۶۵۵',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'flavor_phone', array(
        'label'   => 'شماره تلفن',
        'section' => 'flavor_header',
        'type'    => 'text',
    ) );
    
    $wp_customize->add_setting( 'flavor_email', array(
        'default'           => 'info@flavor-flavor.ir',
        'sanitize_callback' => 'sanitize_email',
    ) );
    $wp_customize->add_control( 'flavor_email', array(
        'label'   => 'ایمیل',
        'section' => 'flavor_header',
        'type'    => 'email',
    ) );
}
