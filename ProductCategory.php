if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * =============================================================================
 * SMOKE IRAN - Product Categories Hub Page
 * =============================================================================
 * Beautiful categories listing page at /product-category/
 * =============================================================================
 */

/**
 * Check if current page is categories hub
 */
function si_is_categories_hub() {
    $current_url = trim( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
    return ( $current_url === 'product-category' || $current_url === 'categories' );
}

/**
 * Register custom page template for categories hub
 */
add_action( 'init', function() {
    add_rewrite_rule( 'product-category/?$', 'index.php?si_categories_hub=1', 'top' );
    add_rewrite_rule( 'categories/?$', 'index.php?si_categories_hub=1', 'top' );
} );

add_filter( 'query_vars', function( $vars ) {
    $vars[] = 'si_categories_hub';
    return $vars;
} );

/**
 * =============================================================================
 * REMOVE DUPLICATE BREADCRUMBS
 * =============================================================================
 */
add_action( 'wp', function() {
    if ( ! get_query_var( 'si_categories_hub' ) && ! si_is_categories_hub() ) return;
    
    // Remove WooCommerce breadcrumb
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
    
    // Remove Storefront breadcrumb
    remove_action( 'storefront_content_top', 'woocommerce_breadcrumb', 10 );
    remove_action( 'storefront_content_top', 'storefront_breadcrumb', 10 );
    
    // Add body class
    add_filter( 'body_class', function( $classes ) {
        $classes[] = 'si-categories-hub-page';
        return $classes;
    } );
}, 5 );

/**
 * Hide breadcrumbs via CSS as fallback
 */
add_action( 'wp_head', function() {
    if ( ! get_query_var( 'si_categories_hub' ) && ! si_is_categories_hub() ) return;
    ?>
    <style>
    /* Hide duplicate breadcrumbs */
    .si-categories-hub-page .woocommerce-breadcrumb,
    .si-categories-hub-page .storefront-breadcrumb,
    .si-categories-hub-page nav.woocommerce-breadcrumb,
    .si-categories-hub-page .col-full > .woocommerce-breadcrumb {
        display: none !important;
    }
    </style>
    <?php
}, 1 );

/**
 * Load custom template for categories hub
 */
add_action( 'template_redirect', function() {
    if ( get_query_var( 'si_categories_hub' ) || si_is_categories_hub() ) {
        si_render_categories_hub();
        exit;
    }
} );

/**
 * Render the categories hub page
 */
function si_render_categories_hub() {
    // Get all parent categories
    $parent_categories = get_terms( array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
        'parent'     => 0,
        'orderby'    => 'count',
        'order'      => 'DESC',
    ) );
    
    // Get stats
    $total_categories = wp_count_terms( 'product_cat' );
    $total_products = wp_count_posts( 'product' )->publish;
    
    // Get popular categories (by product count)
    $popular_categories = get_terms( array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'orderby'    => 'count',
        'order'      => 'DESC',
        'number'     => 6,
    ) );
    
    // Get brands
    $brands = get_terms( array(
        'taxonomy'   => 'product_brand',
        'hide_empty' => true,
        'number'     => 12,
        'orderby'    => 'count',
        'order'      => 'DESC',
    ) );
    
    // Remove breadcrumbs before getting header
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
    remove_action( 'storefront_content_top', 'woocommerce_breadcrumb', 10 );
    remove_action( 'storefront_content_top', 'storefront_breadcrumb', 10 );
    
    get_header( 'shop' );
    ?>
    
    <div id="primary" class="content-area si-categories-hub-page">
        <main id="main" class="site-main" role="main">
            
            <!-- ========== BREADCRUMB ========== -->
            <div class="si-breadcrumb-wrap">
                <div class="si-container">
                    <nav class="si-breadcrumb-nav" aria-label="مسیر ناوبری">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                            <i class="fa-solid fa-house"></i>
                            <span>خانه</span>
                        </a>
                        <i class="fa-solid fa-chevron-left si-breadcrumb-sep"></i>
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">فروشگاه</a>
                        <i class="fa-solid fa-chevron-left si-breadcrumb-sep"></i>
                        <span class="si-breadcrumb-current">دسته‌بندی محصولات</span>
                    </nav>
                </div>
            </div>
            
            <!-- ========== HERO SECTION ========== -->
            <section class="si-cathub-hero">
                <div class="si-cathub-hero-bg"></div>
                <div class="si-container">
                    <div class="si-cathub-hero-content">
                        <div class="si-cathub-hero-badge">
                            <i class="fa-solid fa-folder-tree"></i>
                            <span>مرکز دسته‌بندی‌ها</span>
                        </div>
                        <h1 class="si-cathub-hero-title">دسته‌بندی محصولات</h1>
                        <p class="si-cathub-hero-subtitle">
                            تمامی محصولات ما را در دسته‌بندی‌های متنوع مشاهده کنید و به راحتی محصول مورد نظر خود را پیدا کنید
                        </p>
                        
                        <!-- Stats -->
                        <div class="si-cathub-hero-stats">
                            <div class="si-cathub-stat">
                                <span class="si-cathub-stat-number"><?php echo number_format_i18n( $total_categories ); ?></span>
                                <span class="si-cathub-stat-label">دسته‌بندی</span>
                            </div>
                            <div class="si-cathub-stat-divider"></div>
                            <div class="si-cathub-stat">
                                <span class="si-cathub-stat-number"><?php echo number_format_i18n( $total_products ); ?></span>
                                <span class="si-cathub-stat-label">محصول</span>
                            </div>
                            <div class="si-cathub-stat-divider"></div>
                            <div class="si-cathub-stat">
                                <span class="si-cathub-stat-number"><?php echo number_format_i18n( is_array( $parent_categories ) ? count( $parent_categories ) : 0 ); ?></span>
                                <span class="si-cathub-stat-label">دسته اصلی</span>
                            </div>
                        </div>
                        
                        <!-- Search -->
                        <div class="si-cathub-search">
                            <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                                <div class="si-cathub-search-box">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    <input type="search" 
                                           placeholder="جستجو در دسته‌بندی‌ها یا محصولات..." 
                                           value="<?php echo get_search_query(); ?>" 
                                           name="s">
                                    <input type="hidden" name="post_type" value="product">
                                    <button type="submit">
                                        <span>جستجو</span>
                                        <i class="fa-solid fa-arrow-left"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- ========== POPULAR CATEGORIES ========== -->
            <?php if ( ! empty( $popular_categories ) && ! is_wp_error( $popular_categories ) ) : ?>
            <section class="si-cathub-popular">
                <div class="si-container">
                    <div class="si-section-header">
                        <div class="si-section-header-text">
                            <h2 class="si-section-title">
                                <i class="fa-solid fa-fire"></i>
                                پرطرفدارترین دسته‌بندی‌ها
                            </h2>
                            <p class="si-section-subtitle">دسته‌بندی‌هایی که بیشترین محصولات را دارند</p>
                        </div>
                    </div>
                    
                    <div class="si-cathub-popular-grid">
                        <?php foreach ( $popular_categories as $index => $cat ) : 
                            $thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
                            $image = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'medium' ) : '';
                            $color = si_get_cathub_color( $index );
                        ?>
                        <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" 
                           class="si-cathub-popular-card" 
                           style="--card-color: <?php echo esc_attr( $color ); ?>">
                            <div class="si-cathub-popular-bg"></div>
                            <div class="si-cathub-popular-content">
                                <div class="si-cathub-popular-icon">
                                    <?php if ( $image ) : ?>
                                        <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $cat->name ); ?>">
                                    <?php else : ?>
                                        <i class="fa-solid fa-folder"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="si-cathub-popular-info">
                                    <h3 class="si-cathub-popular-name"><?php echo esc_html( $cat->name ); ?></h3>
                                    <span class="si-cathub-popular-count">
                                        <i class="fa-solid fa-box"></i>
                                        <?php echo number_format_i18n( $cat->count ); ?> محصول
                                    </span>
                                </div>
                                <div class="si-cathub-popular-arrow">
                                    <i class="fa-solid fa-arrow-left"></i>
                                </div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- ========== ALL CATEGORIES ========== -->
            <section class="si-cathub-all">
                <div class="si-container">
                    <div class="si-section-header">
                        <div class="si-section-header-text">
                            <h2 class="si-section-title">
                                <i class="fa-solid fa-th-large"></i>
                                همه دسته‌بندی‌ها
                            </h2>
                            <p class="si-section-subtitle">مرور تمام دسته‌بندی‌ها و زیردسته‌ها</p>
                        </div>
                        
                        <!-- View Options -->
                        <div class="si-cathub-view-options">
                            <button type="button" class="si-cathub-view-btn is-active" data-view="grid">
                                <i class="fa-solid fa-grid-2"></i>
                            </button>
                            <button type="button" class="si-cathub-view-btn" data-view="list">
                                <i class="fa-solid fa-list"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="si-cathub-all-grid" id="si-cathub-grid">
                        <?php 
                        if ( ! empty( $parent_categories ) && ! is_wp_error( $parent_categories ) ) :
                            foreach ( $parent_categories as $index => $parent_cat ) : 
                                $thumbnail_id = get_term_meta( $parent_cat->term_id, 'thumbnail_id', true );
                                $image = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'medium' ) : '';
                                $color = si_get_cathub_color( $index );
                                
                                // Get subcategories
                                $subcategories = get_terms( array(
                                    'taxonomy'   => 'product_cat',
                                    'hide_empty' => false,
                                    'parent'     => $parent_cat->term_id,
                                    'orderby'    => 'count',
                                    'order'      => 'DESC',
                                ) );
                                
                                $total_products_in_cat = $parent_cat->count;
                                if ( ! empty( $subcategories ) && ! is_wp_error( $subcategories ) ) {
                                    foreach ( $subcategories as $sub ) {
                                        $total_products_in_cat += $sub->count;
                                    }
                                }
                        ?>
                        <div class="si-cathub-card" style="--card-color: <?php echo esc_attr( $color ); ?>">
                            <!-- Card Header -->
                            <a href="<?php echo esc_url( get_term_link( $parent_cat ) ); ?>" class="si-cathub-card-header">
                                <div class="si-cathub-card-thumb">
                                    <?php if ( $image ) : ?>
                                        <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $parent_cat->name ); ?>">
                                    <?php else : ?>
                                        <div class="si-cathub-card-thumb-placeholder">
                                            <i class="fa-solid fa-folder"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="si-cathub-card-header-content">
                                    <h3 class="si-cathub-card-title"><?php echo esc_html( $parent_cat->name ); ?></h3>
                                    <div class="si-cathub-card-meta">
                                        <span class="si-cathub-card-products">
                                            <i class="fa-solid fa-box"></i>
                                            <?php echo number_format_i18n( $total_products_in_cat ); ?> محصول
                                        </span>
                                        <?php if ( ! empty( $subcategories ) && ! is_wp_error( $subcategories ) ) : ?>
                                        <span class="si-cathub-card-subs">
                                            <i class="fa-solid fa-folder-tree"></i>
                                            <?php echo count( $subcategories ); ?> زیردسته
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="si-cathub-card-arrow">
                                    <i class="fa-solid fa-chevron-left"></i>
                                </div>
                            </a>
                            
                            <!-- Description -->
                            <?php if ( ! empty( $parent_cat->description ) ) : ?>
                            <div class="si-cathub-card-desc">
                                <?php echo wp_trim_words( $parent_cat->description, 20, '...' ); ?>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Subcategories -->
                            <?php if ( ! empty( $subcategories ) && ! is_wp_error( $subcategories ) ) : ?>
                            <div class="si-cathub-card-subcats">
                                <div class="si-cathub-card-subcats-header">
                                    <span>زیردسته‌ها</span>
                                </div>
                                <div class="si-cathub-card-subcats-list">
                                    <?php foreach ( array_slice( $subcategories, 0, 6 ) as $subcat ) : ?>
                                    <a href="<?php echo esc_url( get_term_link( $subcat ) ); ?>" class="si-cathub-subcat-item">
                                        <span class="si-cathub-subcat-name"><?php echo esc_html( $subcat->name ); ?></span>
                                        <span class="si-cathub-subcat-count"><?php echo number_format_i18n( $subcat->count ); ?></span>
                                    </a>
                                    <?php endforeach; ?>
                                    
                                    <?php if ( count( $subcategories ) > 6 ) : ?>
                                    <a href="<?php echo esc_url( get_term_link( $parent_cat ) ); ?>" class="si-cathub-subcat-more">
                                        <span>+<?php echo count( $subcategories ) - 6; ?> دسته دیگر</span>
                                        <i class="fa-solid fa-arrow-left"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Card Footer -->
                            <div class="si-cathub-card-footer">
                                <a href="<?php echo esc_url( get_term_link( $parent_cat ) ); ?>" class="si-cathub-card-btn">
                                    <span>مشاهده محصولات</span>
                                    <i class="fa-solid fa-arrow-left"></i>
                                </a>
                            </div>
                        </div>
                        <?php 
                            endforeach;
                        endif; 
                        ?>
                    </div>
                </div>
            </section>
            
            <!-- ========== BRANDS SECTION ========== -->
            <?php if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) : ?>
            <section class="si-cathub-brands">
                <div class="si-container">
                    <div class="si-section-header">
                        <div class="si-section-header-text">
                            <h2 class="si-section-title">
                                <i class="fa-solid fa-certificate"></i>
                                برندهای محبوب
                            </h2>
                            <p class="si-section-subtitle">محصولات را بر اساس برند مورد علاقه خود پیدا کنید</p>
                        </div>
                        <a href="<?php echo esc_url( home_url( '/brands/' ) ); ?>" class="si-section-link">
                            مشاهده همه برندها
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                    </div>
                    
                    <div class="si-cathub-brands-grid">
                        <?php foreach ( $brands as $brand ) : 
                            $thumbnail_id = get_term_meta( $brand->term_id, 'thumbnail_id', true );
                            $image = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'thumbnail' ) : '';
                        ?>
                        <a href="<?php echo esc_url( get_term_link( $brand ) ); ?>" class="si-cathub-brand-card">
                            <div class="si-cathub-brand-logo">
                                <?php if ( $image ) : ?>
                                    <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $brand->name ); ?>">
                                <?php else : ?>
                                    <i class="fa-solid fa-building"></i>
                                <?php endif; ?>
                            </div>
                            <div class="si-cathub-brand-info">
                                <span class="si-cathub-brand-name"><?php echo esc_html( $brand->name ); ?></span>
                                <span class="si-cathub-brand-count"><?php echo number_format_i18n( $brand->count ); ?> محصول</span>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- ========== CTA SECTION ========== -->
            <section class="si-cathub-cta">
                <div class="si-container">
                    <div class="si-cathub-cta-content">
                        <div class="si-cathub-cta-icon">
                            <i class="fa-solid fa-rocket"></i>
                        </div>
                        <div class="si-cathub-cta-text">
                            <h3>نمی‌دانید چه محصولی می‌خواهید؟</h3>
                            <p>از بخش فروشگاه دیدن کنید و تمام محصولات ما را با فیلترهای پیشرفته مشاهده کنید</p>
                        </div>
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="si-cathub-cta-btn">
                            <span>رفتن به فروشگاه</span>
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
            </section>
            
        </main>
    </div>
    
    <style id="si-cathub-styles">
    /* ==========================================================================
       CATEGORIES HUB PAGE STYLES
       ========================================================================== */
    
    /* HIDE DUPLICATE BREADCRUMBS */
    .si-categories-hub-page .woocommerce-breadcrumb,
    .si-categories-hub-page nav.woocommerce-breadcrumb,
    .si-categories-hub-page .storefront-breadcrumb,
    .si-categories-hub-page .storefront-breadcrumb .col-full,
    .si-categories-hub-page .col-full > nav.woocommerce-breadcrumb,
    .woocommerce-breadcrumb[aria-label="مسیر راهنما"],
    body.si-categories-hub-page .storefront-breadcrumb {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        overflow: hidden !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .si-categories-hub-page #primary { padding: 0; }
    .si-categories-hub-page .site-main { padding: 0 !important; }
    
    .si-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    /* ==========================================================================
       BREADCRUMB (Our Custom One)
       ========================================================================== */
    .si-breadcrumb-wrap {
        background: var(--si-bg-secondary, #f8f9fa);
        border-bottom: 1px solid var(--si-border-light, #e9ecef);
        padding: 14px 0;
    }
    [data-theme="dark"] .si-breadcrumb-wrap {
        background: #111118;
        border-color: #252532;
    }
    .si-breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        color: var(--si-text-secondary, #666);
        flex-wrap: wrap;
        margin: 0;
        padding: 0;
    }
    .si-breadcrumb-nav a {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--si-text-secondary, #666);
        text-decoration: none;
        transition: color 0.2s ease;
    }
    .si-breadcrumb-nav a:hover { color: var(--si-primary, #29853A); }
    .si-breadcrumb-nav a i { font-size: 11px; }
    .si-breadcrumb-sep { font-size: 9px; color: var(--si-text-muted, #aaa); }
    .si-breadcrumb-current {
        color: var(--si-primary, #29853A);
        font-weight: 600;
    }
    
    /* ==========================================================================
       HERO SECTION
       ========================================================================== */
    .si-cathub-hero {
        position: relative;
        padding: 80px 0;
        background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
        text-align: center;
        overflow: hidden;
    }
    .si-cathub-hero-bg {
        position: absolute;
        inset: 0;
        background-image: 
            radial-gradient(circle at 30% 30%, rgba(59, 130, 246, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 70% 70%, rgba(139, 92, 246, 0.1) 0%, transparent 50%);
        pointer-events: none;
    }
    .si-cathub-hero-bg::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .si-cathub-hero-content {
        position: relative;
        z-index: 1;
        max-width: 800px;
        margin: 0 auto;
    }
    
    .si-cathub-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: rgba(59, 130, 246, 0.2);
        border: 1px solid rgba(59, 130, 246, 0.3);
        border-radius: 30px;
        font-size: 13px;
        font-weight: 600;
        color: #93c5fd;
        margin-bottom: 20px;
    }
    .si-cathub-hero-badge i { font-size: 14px; }
    
    .si-cathub-hero-title {
        font-size: 42px;
        font-weight: 800;
        color: #fff;
        margin: 0 0 16px;
        line-height: 1.3;
    }
    
    .si-cathub-hero-subtitle {
        font-size: 17px;
        line-height: 1.8;
        color: rgba(255,255,255,0.7);
        margin: 0 0 32px;
    }
    
    /* Stats */
    .si-cathub-hero-stats {
        display: inline-flex;
        align-items: center;
        gap: 24px;
        padding: 20px 40px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 16px;
        backdrop-filter: blur(10px);
        margin-bottom: 32px;
    }
    .si-cathub-stat {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
    }
    .si-cathub-stat-number {
        font-size: 32px;
        font-weight: 800;
        color: #fff;
    }
    .si-cathub-stat-label {
        font-size: 13px;
        color: rgba(255,255,255,0.6);
    }
    .si-cathub-stat-divider {
        width: 1px;
        height: 40px;
        background: rgba(255,255,255,0.15);
    }
    
    /* Search */
    .si-cathub-search {
        max-width: 600px;
        margin: 0 auto;
    }
    .si-cathub-search-box {
        display: flex;
        align-items: center;
        background: #fff;
        border-radius: 16px;
        padding: 6px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    .si-cathub-search-box > i {
        padding: 0 16px;
        font-size: 18px;
        color: var(--si-text-muted, #9ca3af);
    }
    .si-cathub-search-box input[type="search"] {
        flex: 1;
        border: none;
        background: transparent;
        font-family: inherit;
        font-size: 15px;
        color: var(--si-text, #1f2937);
        padding: 14px 0;
        outline: none;
    }
    .si-cathub-search-box input::placeholder {
        color: var(--si-text-muted, #9ca3af);
    }
    .si-cathub-search-box button {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 14px 28px;
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        border: none;
        border-radius: 12px;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        color: #fff;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .si-cathub-search-box button:hover {
        transform: translateX(-4px);
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
    }
    .si-cathub-search-box button i {
        font-size: 12px;
        transition: transform 0.2s ease;
    }
    .si-cathub-search-box button:hover i {
        transform: translateX(-4px);
    }
    
    /* ==========================================================================
       SECTION HEADER
       ========================================================================== */
    .si-section-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 32px;
    }
    .si-section-header-text { flex: 1; }
    .si-section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 24px;
        font-weight: 800;
        color: var(--si-text, #1f2937);
        margin: 0 0 8px;
    }
    [data-theme="dark"] .si-section-title { color: #f3f4f6; }
    .si-section-title i {
        font-size: 20px;
        color: var(--si-primary, #29853A);
    }
    .si-section-subtitle {
        font-size: 14px;
        color: var(--si-text-secondary, #6b7280);
        margin: 0;
    }
    .si-section-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
        color: var(--si-primary, #29853A);
        text-decoration: none;
        padding: 10px 20px;
        background: var(--si-primary-lighter, #ecfdf5);
        border-radius: 10px;
        transition: all 0.2s ease;
    }
    .si-section-link:hover {
        background: var(--si-primary, #29853A);
        color: #fff;
    }
    .si-section-link i {
        font-size: 12px;
        transition: transform 0.2s ease;
    }
    .si-section-link:hover i {
        transform: translateX(-4px);
    }
    
    /* ==========================================================================
       POPULAR CATEGORIES
       ========================================================================== */
    .si-cathub-popular {
        padding: 60px 0;
        background: var(--si-bg, #fff);
    }
    [data-theme="dark"] .si-cathub-popular { background: #0c0c12; }
    
    .si-cathub-popular-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
    
    .si-cathub-popular-card {
        position: relative;
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 24px;
        background: var(--si-surface, #fff);
        border: 1px solid var(--si-border-light, #e5e7eb);
        border-radius: 20px;
        text-decoration: none;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .si-cathub-popular-card:hover {
        border-color: var(--card-color);
        transform: translateY(-4px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.1);
    }
    [data-theme="dark"] .si-cathub-popular-card {
        background: #16161f;
        border-color: #252532;
    }
    
    .si-cathub-popular-bg {
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, var(--card-color) 0%, transparent 70%);
        opacity: 0.1;
        transition: opacity 0.3s ease;
    }
    .si-cathub-popular-card:hover .si-cathub-popular-bg { opacity: 0.2; }
    
    .si-cathub-popular-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 16px;
        width: 100%;
    }
    
    .si-cathub-popular-icon {
        width: 70px;
        height: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: color-mix(in srgb, var(--card-color) 15%, transparent);
        border-radius: 16px;
        flex-shrink: 0;
        overflow: hidden;
    }
    .si-cathub-popular-icon img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 8px;
    }
    .si-cathub-popular-icon i {
        font-size: 28px;
        color: var(--card-color);
    }
    
    .si-cathub-popular-info { flex: 1; }
    .si-cathub-popular-name {
        font-size: 18px;
        font-weight: 700;
        color: var(--si-text, #1f2937);
        margin: 0 0 6px;
    }
    [data-theme="dark"] .si-cathub-popular-name { color: #f3f4f6; }
    .si-cathub-popular-count {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: var(--si-text-muted, #9ca3af);
    }
    .si-cathub-popular-count i {
        font-size: 11px;
        color: var(--card-color);
    }
    
    .si-cathub-popular-arrow {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--si-bg-secondary, #f3f4f6);
        border-radius: 10px;
        color: var(--si-text-muted, #9ca3af);
        transition: all 0.3s ease;
    }
    [data-theme="dark"] .si-cathub-popular-arrow { background: #1a1a24; }
    .si-cathub-popular-card:hover .si-cathub-popular-arrow {
        background: var(--card-color);
        color: #fff;
        transform: translateX(-4px);
    }
    
    /* ==========================================================================
       ALL CATEGORIES
       ========================================================================== */
    .si-cathub-all {
        padding: 60px 0;
        background: var(--si-bg-secondary, #f8f9fa);
    }
    [data-theme="dark"] .si-cathub-all { background: #0a0a0f; }
    
    .si-cathub-view-options {
        display: flex;
        background: var(--si-bg, #fff);
        border: 1px solid var(--si-border-light, #e5e7eb);
        border-radius: 10px;
        padding: 4px;
    }
    [data-theme="dark"] .si-cathub-view-options {
        background: #16161f;
        border-color: #252532;
    }
    .si-cathub-view-btn {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        border-radius: 8px;
        color: var(--si-text-muted, #9ca3af);
        font-size: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .si-cathub-view-btn:hover { color: var(--si-primary, #29853A); }
    .si-cathub-view-btn.is-active {
        background: var(--si-primary, #29853A);
        color: #fff;
    }
    
    .si-cathub-all-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }
    .si-cathub-all-grid.list-view { grid-template-columns: 1fr; }
    
    /* Category Card */
    .si-cathub-card {
        background: var(--si-surface, #fff);
        border: 1px solid var(--si-border-light, #e5e7eb);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .si-cathub-card:hover {
        border-color: var(--card-color);
        box-shadow: 0 15px 50px rgba(0,0,0,0.08);
        transform: translateY(-4px);
    }
    [data-theme="dark"] .si-cathub-card {
        background: #16161f;
        border-color: #252532;
    }
    
    .si-cathub-card-header {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
        text-decoration: none;
        border-bottom: 1px solid var(--si-border-light, #e9ecef);
        transition: background 0.2s ease;
    }
    .si-cathub-card-header:hover { background: var(--si-bg-secondary, #f9fafb); }
    [data-theme="dark"] .si-cathub-card-header { border-color: #252532; }
    [data-theme="dark"] .si-cathub-card-header:hover { background: #1a1a24; }
    
    .si-cathub-card-thumb {
        width: 72px;
        height: 72px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: color-mix(in srgb, var(--card-color) 10%, transparent);
        border-radius: 16px;
        flex-shrink: 0;
        overflow: hidden;
    }
    .si-cathub-card-thumb img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 8px;
    }
    .si-cathub-card-thumb-placeholder {
        font-size: 32px;
        color: var(--card-color);
    }
    
    .si-cathub-card-header-content {
        flex: 1;
        min-width: 0;
    }
    .si-cathub-card-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--si-text, #1f2937);
        margin: 0 0 8px;
    }
    [data-theme="dark"] .si-cathub-card-title { color: #f3f4f6; }
    
    .si-cathub-card-meta {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .si-cathub-card-products,
    .si-cathub-card-subs {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: var(--si-text-muted, #9ca3af);
    }
    .si-cathub-card-products i,
    .si-cathub-card-subs i {
        font-size: 11px;
        color: var(--card-color);
    }
    
    .si-cathub-card-arrow {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--si-bg-secondary, #f3f4f6);
        border-radius: 10px;
        color: var(--si-text-muted, #9ca3af);
        font-size: 12px;
        transition: all 0.2s ease;
    }
    [data-theme="dark"] .si-cathub-card-arrow { background: #1a1a24; }
    .si-cathub-card-header:hover .si-cathub-card-arrow {
        background: var(--card-color);
        color: #fff;
        transform: translateX(-4px);
    }
    
    .si-cathub-card-desc {
        padding: 16px 20px;
        font-size: 13px;
        line-height: 1.7;
        color: var(--si-text-secondary, #6b7280);
        border-bottom: 1px solid var(--si-border-light, #e9ecef);
    }
    [data-theme="dark"] .si-cathub-card-desc { border-color: #252532; }
    
    /* Subcategories */
    .si-cathub-card-subcats { padding: 16px 20px; }
    .si-cathub-card-subcats-header {
        font-size: 12px;
        font-weight: 600;
        color: var(--si-text-muted, #9ca3af);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }
    .si-cathub-card-subcats-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .si-cathub-subcat-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        background: var(--si-bg-secondary, #f3f4f6);
        border: 1px solid transparent;
        border-radius: 10px;
        font-size: 13px;
        color: var(--si-text, #374151);
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .si-cathub-subcat-item:hover {
        border-color: var(--card-color);
        background: color-mix(in srgb, var(--card-color) 10%, transparent);
        color: var(--card-color);
    }
    [data-theme="dark"] .si-cathub-subcat-item {
        background: #1a1a24;
        color: #d1d5db;
    }
    .si-cathub-subcat-count {
        font-size: 11px;
        padding: 2px 6px;
        background: var(--si-bg, #fff);
        border-radius: 6px;
        color: var(--si-text-muted, #9ca3af);
    }
    [data-theme="dark"] .si-cathub-subcat-count { background: #252532; }
    
    .si-cathub-subcat-more {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        font-size: 13px;
        font-weight: 600;
        color: var(--card-color);
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .si-cathub-subcat-more:hover { gap: 10px; }
    .si-cathub-subcat-more i { font-size: 10px; }
    
    /* Card Footer */
    .si-cathub-card-footer {
        padding: 16px 20px;
        background: var(--si-bg-secondary, #f9fafb);
        border-top: 1px solid var(--si-border-light, #e9ecef);
    }
    [data-theme="dark"] .si-cathub-card-footer {
        background: #1a1a24;
        border-color: #252532;
    }
    .si-cathub-card-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 14px 20px;
        background: var(--card-color);
        border: none;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        color: #fff;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .si-cathub-card-btn:hover {
        filter: brightness(1.1);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px color-mix(in srgb, var(--card-color) 40%, transparent);
        color: #fff;
    }
    .si-cathub-card-btn i {
        font-size: 12px;
        transition: transform 0.2s ease;
    }
    .si-cathub-card-btn:hover i { transform: translateX(-4px); }
    
    /* ==========================================================================
       BRANDS SECTION
       ========================================================================== */
    .si-cathub-brands {
        padding: 60px 0;
        background: var(--si-bg, #fff);
    }
    [data-theme="dark"] .si-cathub-brands { background: #0c0c12; }
    
    .si-cathub-brands-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 16px;
    }
    
    .si-cathub-brand-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        padding: 24px 16px;
        background: var(--si-surface, #fff);
        border: 1px solid var(--si-border-light, #e5e7eb);
        border-radius: 16px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .si-cathub-brand-card:hover {
        border-color: var(--si-primary, #29853A);
        transform: translateY(-4px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
    }
    [data-theme="dark"] .si-cathub-brand-card {
        background: #16161f;
        border-color: #252532;
    }
    
    .si-cathub-brand-logo {
        width: 64px;
        height: 64px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--si-bg-secondary, #f3f4f6);
        border-radius: 14px;
        overflow: hidden;
    }
    [data-theme="dark"] .si-cathub-brand-logo { background: #1a1a24; }
    .si-cathub-brand-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 8px;
    }
    .si-cathub-brand-logo i {
        font-size: 24px;
        color: var(--si-text-muted, #9ca3af);
    }
    
    .si-cathub-brand-info { text-align: center; }
    .si-cathub-brand-name {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: var(--si-text, #1f2937);
        margin-bottom: 4px;
    }
    [data-theme="dark"] .si-cathub-brand-name { color: #f3f4f6; }
    .si-cathub-brand-count {
        font-size: 12px;
        color: var(--si-text-muted, #9ca3af);
    }
    
    /* ==========================================================================
       CTA SECTION
       ========================================================================== */
    .si-cathub-cta {
        padding: 60px 0;
        background: linear-gradient(135deg, #0d1f12 0%, #1a3a20 100%);
    }
    .si-cathub-cta-content {
        display: flex;
        align-items: center;
        gap: 24px;
        padding: 32px 40px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 20px;
        backdrop-filter: blur(10px);
    }
    .si-cathub-cta-icon {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--si-primary, #29853A);
        border-radius: 20px;
        font-size: 32px;
        color: #fff;
        flex-shrink: 0;
    }
    .si-cathub-cta-text { flex: 1; }
    .si-cathub-cta-text h3 {
        font-size: 22px;
        font-weight: 700;
        color: #fff;
        margin: 0 0 8px;
    }
    .si-cathub-cta-text p {
        font-size: 14px;
        color: rgba(255,255,255,0.7);
        margin: 0;
    }
    .si-cathub-cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 16px 32px;
        background: #fff;
        border-radius: 14px;
        font-size: 15px;
        font-weight: 700;
        color: var(--si-primary, #29853A);
        text-decoration: none;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    .si-cathub-cta-btn:hover {
        background: var(--si-primary, #29853A);
        color: #fff;
        transform: translateX(-4px);
    }
    .si-cathub-cta-btn i {
        font-size: 12px;
        transition: transform 0.2s ease;
    }
    .si-cathub-cta-btn:hover i { transform: translateX(-4px); }
    
    /* ==========================================================================
       RESPONSIVE
       ========================================================================== */
    @media (max-width: 1200px) {
        .si-cathub-all-grid { grid-template-columns: repeat(2, 1fr); }
        .si-cathub-brands-grid { grid-template-columns: repeat(4, 1fr); }
    }
    
    @media (max-width: 991px) {
        .si-cathub-hero { padding: 60px 0; }
        .si-cathub-hero-title { font-size: 32px; }
        .si-cathub-popular-grid { grid-template-columns: repeat(2, 1fr); }
        .si-cathub-brands-grid { grid-template-columns: repeat(3, 1fr); }
        .si-cathub-cta-content {
            flex-direction: column;
            text-align: center;
        }
    }
    
    @media (max-width: 768px) {
        .si-cathub-hero { padding: 50px 0; }
        .si-cathub-hero-title { font-size: 28px; }
        .si-cathub-hero-stats {
            flex-direction: column;
            gap: 16px;
            padding: 20px 30px;
        }
        .si-cathub-stat-divider {
            width: 60px;
            height: 1px;
        }
        .si-section-header { flex-direction: column; align-items: flex-start; }
        .si-cathub-popular-grid { grid-template-columns: 1fr; }
        .si-cathub-all-grid { grid-template-columns: 1fr; }
        .si-cathub-brands-grid { grid-template-columns: repeat(2, 1fr); }
    }
    
    @media (max-width: 480px) {
        .si-cathub-hero-stats { width: 100%; }
        .si-cathub-brands-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
        .si-cathub-brand-card { padding: 16px 12px; }
    }
    </style>
    
    <script id="si-cathub-js">
    (function() {
        'use strict';
        
        // View toggle
        var viewBtns = document.querySelectorAll('.si-cathub-view-btn');
        var grid = document.getElementById('si-cathub-grid');
        
        viewBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var view = this.getAttribute('data-view');
                viewBtns.forEach(function(b) { b.classList.remove('is-active'); });
                this.classList.add('is-active');
                
                if (grid) {
                    if (view === 'list') {
                        grid.classList.add('list-view');
                    } else {
                        grid.classList.remove('list-view');
                    }
                }
                localStorage.setItem('si_cathub_view', view);
            });
        });
        
        // Restore view preference
        var savedView = localStorage.getItem('si_cathub_view');
        if (savedView && grid) {
            viewBtns.forEach(function(b) {
                b.classList.remove('is-active');
                if (b.getAttribute('data-view') === savedView) {
                    b.classList.add('is-active');
                }
            });
            if (savedView === 'list') {
                grid.classList.add('list-view');
            }
        }
    })();
    </script>
    
    <?php
    get_footer( 'shop' );
}

/**
 * Helper: Get color for category card
 */
function si_get_cathub_color( $index ) {
    $colors = array(
        '#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ef4444',
        '#06b6d4', '#ec4899', '#6366f1', '#14b8a6', '#f97316',
    );
    return $colors[ $index % count( $colors ) ];
}

/**
 * Flush rewrite rules
 */
add_action( 'after_switch_theme', 'flush_rewrite_rules' );
