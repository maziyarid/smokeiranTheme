if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * =============================================================================
 * SMOKE IRAN - Shop Page Layout
 * =============================================================================
 * Beautiful shop page with:
 * - Hero section with search
 * - Featured categories
 * - Sidebar filters
 * - Modern toolbar
 * - Beautiful product grid
 * =============================================================================
 */

/**
 * Check if current page is main shop page (not taxonomy)
 */
function si_is_main_shop() {
    return is_shop() && ! is_product_category() && ! is_product_tag() && ! is_tax( 'product_brand' ) && ! is_tax( 'pwb-brand' );
}

/**
 * Check if products are being filtered (renamed to avoid WooCommerce conflict)
 */
function si_is_filtered() {
    return isset( $_GET['min_price'] ) || 
           isset( $_GET['max_price'] ) || 
           isset( $_GET['rating_filter'] ) || 
           isset( $_GET['stock_status'] ) || 
           isset( $_GET['on_sale'] ) ||
           isset( $_GET['filter_brand'] ) ||
           isset( $_GET['filter_cat'] ) ||
           isset( $_GET['orderby'] ) ||
           isset( $_GET['s'] );
}

/**
 * =============================================================================
 * CLEANUP: Remove default elements on shop page
 * =============================================================================
 */
add_action( 'wp', function() {
    if ( ! si_is_main_shop() ) return;
    
    // Remove Storefront breadcrumbs
    remove_action( 'storefront_content_top', 'woocommerce_breadcrumb', 10 );
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
    
    // Remove Storefront sidebar
    remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
    
    // Remove default result count and ordering
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
    
    // Remove Storefront sorting wrapper
    remove_action( 'woocommerce_before_shop_loop', 'storefront_sorting_wrapper', 9 );
    remove_action( 'woocommerce_before_shop_loop', 'storefront_sorting_wrapper_close', 31 );
    
    // Remove after shop loop sorting
    remove_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );
    remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 30 );
    remove_action( 'woocommerce_after_shop_loop', 'storefront_sorting_wrapper', 9 );
    remove_action( 'woocommerce_after_shop_loop', 'storefront_sorting_wrapper_close', 31 );
    
    // Remove archive header/description
    remove_action( 'woocommerce_archive_description', 'woocommerce_taxonomy_archive_description', 10 );
    remove_action( 'woocommerce_archive_description', 'woocommerce_product_archive_description', 10 );
    
    // Force full-width layout
    add_filter( 'storefront_page_template', function() { return 'full-width'; } );
    add_filter( 'body_class', function( $classes ) {
        $classes[] = 'storefront-full-width-content';
        $classes[] = 'si-shop-page';
        return $classes;
    } );
    
}, 5 );

/**
 * =============================================================================
 * OUTPUT BUFFER CLEANUP
 * =============================================================================
 */
add_action( 'template_redirect', function() {
    if ( ! si_is_main_shop() ) return;
    
    ob_start( function( $html ) {
        // Remove woocommerce-products-header
        $html = preg_replace( '/<header class="woocommerce-products-header">.*?<\/header>/s', '', $html );
        
        // Remove storefront-sorting
        $html = preg_replace( '/<div class="storefront-sorting">.*?<\/div>/s', '', $html );
        
        return $html;
    } );
}, 1 );

/**
 * =============================================================================
 * SHOP HERO SECTION
 * =============================================================================
 */
add_action( 'woocommerce_before_main_content', function() {
    if ( ! si_is_main_shop() ) return;
    
    // Get stats
    $product_count = wp_count_posts( 'product' );
    $total_products = $product_count->publish;
    
    $categories = get_terms( array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => 0,
    ) );
    $total_categories = is_wp_error( $categories ) ? 0 : count( $categories );
    
    $brands = get_terms( array(
        'taxonomy'   => 'product_brand',
        'hide_empty' => true,
    ) );
    $total_brands = is_wp_error( $brands ) ? 0 : count( $brands );
    
    // Get featured categories (with images)
    $featured_cats = get_terms( array(
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => 0,
        'number'     => 8,
        'orderby'    => 'count',
        'order'      => 'DESC',
    ) );
    ?>
    
    <!-- ========== BREADCRUMB ========== -->
    <div class="si-breadcrumb-wrap">
        <div class="si-container">
            <nav class="si-breadcrumb-nav" aria-label="مسیر ناوبری">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <i class="fa-solid fa-house"></i>
                    <span>خانه</span>
                </a>
                <i class="fa-solid fa-chevron-left si-breadcrumb-sep"></i>
                <span class="si-breadcrumb-current">فروشگاه</span>
            </nav>
        </div>
    </div>
    
    <!-- ========== HERO SECTION ========== -->
    <section class="si-shop-hero">
        <div class="si-shop-hero-bg"></div>
        <div class="si-container">
            <div class="si-shop-hero-content">
                <div class="si-shop-hero-text">
                    <h1 class="si-shop-hero-title">
                        <i class="fa-solid fa-store"></i>
                        فروشگاه اسموک ایران
                    </h1>
                    <p class="si-shop-hero-subtitle">
                        بهترین محصولات ویپ و سیگار الکترونیکی با ضمانت اصالت و گارانتی
                    </p>
                    
                    <!-- Stats -->
                    <div class="si-shop-hero-stats">
                        <div class="si-stat-item">
                            <div class="si-stat-icon">
                                <i class="fa-solid fa-box-open"></i>
                            </div>
                            <div class="si-stat-info">
                                <span class="si-stat-number"><?php echo number_format_i18n( $total_products ); ?></span>
                                <span class="si-stat-label">محصول</span>
                            </div>
                        </div>
                        <div class="si-stat-item">
                            <div class="si-stat-icon">
                                <i class="fa-solid fa-folder"></i>
                            </div>
                            <div class="si-stat-info">
                                <span class="si-stat-number"><?php echo number_format_i18n( $total_categories ); ?></span>
                                <span class="si-stat-label">دسته‌بندی</span>
                            </div>
                        </div>
                        <?php if ( $total_brands > 0 ) : ?>
                        <div class="si-stat-item">
                            <div class="si-stat-icon">
                                <i class="fa-solid fa-certificate"></i>
                            </div>
                            <div class="si-stat-info">
                                <span class="si-stat-number"><?php echo number_format_i18n( $total_brands ); ?></span>
                                <span class="si-stat-label">برند</span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Search Box -->
                <div class="si-shop-hero-search">
                    <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <div class="si-search-box">
                            <i class="fa-solid fa-magnifying-glass si-search-icon"></i>
                            <input type="search" 
                                   class="si-search-input" 
                                   placeholder="جستجو در محصولات..." 
                                   value="<?php echo get_search_query(); ?>" 
                                   name="s">
                            <input type="hidden" name="post_type" value="product">
                            <button type="submit" class="si-search-btn">
                                <span>جستجو</span>
                                <i class="fa-solid fa-arrow-left"></i>
                            </button>
                        </div>
                        <div class="si-search-suggestions">
                            <span>جستجوی پرطرفدار:</span>
                            <a href="<?php echo esc_url( home_url( '/?s=پاد&post_type=product' ) ); ?>">پاد</a>
                            <a href="<?php echo esc_url( home_url( '/?s=ویپ&post_type=product' ) ); ?>">ویپ</a>
                            <a href="<?php echo esc_url( home_url( '/?s=جویس&post_type=product' ) ); ?>">جویس</a>
                            <a href="<?php echo esc_url( home_url( '/?s=کویل&post_type=product' ) ); ?>">کویل</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    
    <!-- ========== FEATURED CATEGORIES ========== -->
    <?php if ( ! empty( $featured_cats ) && ! is_wp_error( $featured_cats ) ) : ?>
    <section class="si-shop-categories">
        <div class="si-container">
            <div class="si-section-header">
                <h2 class="si-section-title">
                    <i class="fa-solid fa-folder-tree"></i>
                    دسته‌بندی محصولات
                </h2>
                <a href="<?php echo esc_url( home_url( '/product-category/' ) ); ?>" class="si-section-link">
                    مشاهده همه
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>
            
            <div class="si-categories-grid">
                <?php foreach ( $featured_cats as $cat ) : 
                    $thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
                    $image = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'medium' ) : '';
                    $cat_color = si_get_shop_category_color( $cat->slug );
                ?>
                <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="si-category-card" style="--cat-color: <?php echo esc_attr( $cat_color ); ?>">
                    <div class="si-category-icon">
                        <?php if ( $image ) : ?>
                            <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $cat->name ); ?>">
                        <?php else : ?>
                            <i class="fa-solid fa-folder"></i>
                        <?php endif; ?>
                    </div>
                    <div class="si-category-info">
                        <h3 class="si-category-name"><?php echo esc_html( $cat->name ); ?></h3>
                        <span class="si-category-count"><?php echo number_format_i18n( $cat->count ); ?> محصول</span>
                    </div>
                    <i class="fa-solid fa-chevron-left si-category-arrow"></i>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <!-- ========== MAIN LAYOUT ========== -->
    <section class="si-shop-main" id="si-products">
        <div class="si-container">
            <div class="si-shop-layout">
                
                <!-- ===== SIDEBAR ===== -->
                <aside class="si-shop-sidebar" id="si-shop-sidebar">
                    <div class="si-sidebar-inner">
                        
                        <!-- Sidebar Header -->
                        <div class="si-sidebar-header">
                            <div class="si-sidebar-header-title">
                                <i class="fa-solid fa-sliders"></i>
                                <span>فیلترها</span>
                            </div>
                            <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="si-sidebar-reset">
                                <i class="fa-solid fa-rotate-left"></i>
                                <span>پاک‌سازی</span>
                            </a>
                            <button type="button" class="si-sidebar-close" id="si-sidebar-close">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        
                        <!-- Sidebar Body -->
                        <div class="si-sidebar-body">
                            
                            <!-- FILTER: Categories -->
                            <div class="si-filter-section si-filter-open">
                                <button type="button" class="si-filter-header si-filter-toggle">
                                    <i class="fa-solid fa-folder"></i>
                                    <span>دسته‌بندی</span>
                                    <i class="fa-solid fa-chevron-down si-filter-arrow"></i>
                                </button>
                                <div class="si-filter-content">
                                    <?php
                                    $shop_categories = get_terms( array(
                                        'taxonomy'   => 'product_cat',
                                        'hide_empty' => true,
                                        'parent'     => 0,
                                        'number'     => 15,
                                    ) );
                                    $current_cat = isset( $_GET['filter_cat'] ) ? sanitize_text_field( $_GET['filter_cat'] ) : '';
                                    
                                    if ( ! empty( $shop_categories ) && ! is_wp_error( $shop_categories ) ) :
                                        foreach ( $shop_categories as $cat ) :
                                            $is_selected = $current_cat === $cat->slug;
                                            $cat_url = $is_selected ? remove_query_arg( 'filter_cat' ) : add_query_arg( 'filter_cat', $cat->slug );
                                    ?>
                                        <label class="si-filter-checkbox">
                                            <input type="checkbox" 
                                                   onchange="window.location.href='<?php echo esc_url( $cat_url ); ?>'"
                                                   <?php checked( $is_selected ); ?>>
                                            <span class="si-checkbox-box">
                                                <i class="fa-solid fa-check"></i>
                                            </span>
                                            <span class="si-checkbox-text"><?php echo esc_html( $cat->name ); ?></span>
                                            <span class="si-checkbox-count"><?php echo esc_html( $cat->count ); ?></span>
                                        </label>
                                    <?php 
                                        endforeach;
                                    endif;
                                    ?>
                                </div>
                            </div>
                            
                            <!-- FILTER: Brands -->
                            <?php
                            $shop_brands = get_terms( array(
                                'taxonomy'   => 'product_brand',
                                'hide_empty' => true,
                                'number'     => 20,
                            ) );
                            if ( ! empty( $shop_brands ) && ! is_wp_error( $shop_brands ) ) :
                            ?>
                            <div class="si-filter-section">
                                <button type="button" class="si-filter-header si-filter-toggle">
                                    <i class="fa-solid fa-certificate"></i>
                                    <span>برند</span>
                                    <i class="fa-solid fa-chevron-down si-filter-arrow"></i>
                                </button>
                                <div class="si-filter-content si-filter-scrollable">
                                    <?php
                                    $current_brand = isset( $_GET['filter_brand'] ) ? sanitize_text_field( $_GET['filter_brand'] ) : '';
                                    foreach ( $shop_brands as $brand ) :
                                        $is_selected = $current_brand === $brand->slug;
                                        $brand_url = $is_selected ? remove_query_arg( 'filter_brand' ) : add_query_arg( 'filter_brand', $brand->slug );
                                    ?>
                                        <label class="si-filter-checkbox">
                                            <input type="checkbox" 
                                                   onchange="window.location.href='<?php echo esc_url( $brand_url ); ?>'"
                                                   <?php checked( $is_selected ); ?>>
                                            <span class="si-checkbox-box">
                                                <i class="fa-solid fa-check"></i>
                                            </span>
                                            <span class="si-checkbox-text"><?php echo esc_html( $brand->name ); ?></span>
                                            <span class="si-checkbox-count"><?php echo esc_html( $brand->count ); ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- FILTER: Price Range -->
                            <div class="si-filter-section">
                                <button type="button" class="si-filter-header si-filter-toggle">
                                    <i class="fa-solid fa-coins"></i>
                                    <span>محدوده قیمت</span>
                                    <i class="fa-solid fa-chevron-down si-filter-arrow"></i>
                                </button>
                                <div class="si-filter-content">
                                    <?php
                                    if ( class_exists( 'WC_Widget_Price_Filter' ) ) {
                                        the_widget( 'WC_Widget_Price_Filter', array( 'title' => '' ) );
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <!-- FILTER: Stock & Sale -->
                            <div class="si-filter-section si-filter-open">
                                <button type="button" class="si-filter-header si-filter-toggle">
                                    <i class="fa-solid fa-warehouse"></i>
                                    <span>وضعیت کالا</span>
                                    <i class="fa-solid fa-chevron-down si-filter-arrow"></i>
                                </button>
                                <div class="si-filter-content">
                                    <?php
                                    $stock_filter = isset( $_GET['stock_status'] ) ? sanitize_text_field( $_GET['stock_status'] ) : '';
                                    $sale_filter = isset( $_GET['on_sale'] ) ? true : false;
                                    ?>
                                    <label class="si-filter-checkbox">
                                        <input type="checkbox" 
                                               onchange="window.location.href='<?php echo esc_url( $stock_filter === 'instock' ? remove_query_arg( 'stock_status' ) : add_query_arg( 'stock_status', 'instock' ) ); ?>'"
                                               <?php checked( $stock_filter, 'instock' ); ?>>
                                        <span class="si-checkbox-box">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                        <span class="si-checkbox-text">فقط کالاهای موجود</span>
                                    </label>
                                    <label class="si-filter-checkbox">
                                        <input type="checkbox"
                                               onchange="window.location.href='<?php echo esc_url( $sale_filter ? remove_query_arg( 'on_sale' ) : add_query_arg( 'on_sale', '1' ) ); ?>'"
                                               <?php checked( $sale_filter ); ?>>
                                        <span class="si-checkbox-box">
                                            <i class="fa-solid fa-check"></i>
                                        </span>
                                        <span class="si-checkbox-text">فقط کالاهای تخفیف‌دار</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- FILTER: Rating -->
                            <div class="si-filter-section">
                                <button type="button" class="si-filter-header si-filter-toggle">
                                    <i class="fa-solid fa-star"></i>
                                    <span>امتیاز</span>
                                    <i class="fa-solid fa-chevron-down si-filter-arrow"></i>
                                </button>
                                <div class="si-filter-content">
                                    <?php
                                    $rating_filter = isset( $_GET['rating_filter'] ) ? absint( $_GET['rating_filter'] ) : 0;
                                    for ( $i = 5; $i >= 1; $i-- ) :
                                        $is_active = $rating_filter === $i;
                                        $filter_url = $is_active ? remove_query_arg( 'rating_filter' ) : add_query_arg( 'rating_filter', $i );
                                    ?>
                                        <a href="<?php echo esc_url( $filter_url ); ?>" class="si-rating-row <?php echo $is_active ? 'is-active' : ''; ?>">
                                            <span class="si-rating-stars">
                                                <?php for ( $s = 1; $s <= 5; $s++ ) : ?>
                                                    <i class="fa-<?php echo $s <= $i ? 'solid' : 'regular'; ?> fa-star"></i>
                                                <?php endfor; ?>
                                            </span>
                                            <span class="si-rating-text"><?php echo $i; ?> و بالاتر</span>
                                        </a>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </aside>
                
                <!-- Sidebar Overlay -->
                <div class="si-sidebar-overlay" id="si-sidebar-overlay"></div>
                
                <!-- ===== CONTENT ===== -->
                <div class="si-shop-content">
                    
                    <!-- Active Filters -->
                    <?php if ( si_is_filtered() ) : ?>
                    <div class="si-active-filters">
                        <span class="si-active-filters-label">
                            <i class="fa-solid fa-filter"></i>
                            فیلترهای فعال:
                        </span>
                        <div class="si-active-filters-list">
                            <?php if ( isset( $_GET['filter_cat'] ) ) : ?>
                                <a href="<?php echo esc_url( remove_query_arg( 'filter_cat' ) ); ?>" class="si-active-filter">
                                    <span>دسته: <?php echo esc_html( sanitize_text_field( $_GET['filter_cat'] ) ); ?></span>
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ( isset( $_GET['filter_brand'] ) ) : ?>
                                <a href="<?php echo esc_url( remove_query_arg( 'filter_brand' ) ); ?>" class="si-active-filter">
                                    <span>برند: <?php echo esc_html( sanitize_text_field( $_GET['filter_brand'] ) ); ?></span>
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ( isset( $_GET['stock_status'] ) ) : ?>
                                <a href="<?php echo esc_url( remove_query_arg( 'stock_status' ) ); ?>" class="si-active-filter">
                                    <span>فقط موجود</span>
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ( isset( $_GET['on_sale'] ) ) : ?>
                                <a href="<?php echo esc_url( remove_query_arg( 'on_sale' ) ); ?>" class="si-active-filter">
                                    <span>تخفیف‌دار</span>
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ( isset( $_GET['rating_filter'] ) ) : ?>
                                <a href="<?php echo esc_url( remove_query_arg( 'rating_filter' ) ); ?>" class="si-active-filter">
                                    <span><?php echo absint( $_GET['rating_filter'] ); ?> ستاره و بالاتر</span>
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ( isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) ) : ?>
                                <a href="<?php echo esc_url( remove_query_arg( array( 'min_price', 'max_price' ) ) ); ?>" class="si-active-filter">
                                    <span>محدوده قیمت</span>
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="si-clear-all-filters">
                            <i class="fa-solid fa-trash"></i>
                            پاک کردن همه
                        </a>
                    </div>
                    <?php endif; ?>
    <?php
}, 10 );

/**
 * Helper: Get category color (renamed to avoid conflicts)
 */
function si_get_shop_category_color( $slug ) {
    $colors = array(
        'pod-systems'    => '#10b981',
        'vape-devices'   => '#3b82f6',
        'e-liquids'      => '#f59e0b',
        'accessories'    => '#8b5cf6',
        'coils'          => '#ef4444',
        'batteries'      => '#06b6d4',
        'kits'           => '#ec4899',
        'mods'           => '#6366f1',
    );
    
    return isset( $colors[ $slug ] ) ? $colors[ $slug ] : '#29853A';
}

/**
 * =============================================================================
 * TOOLBAR
 * =============================================================================
 */
add_action( 'woocommerce_before_shop_loop', function() {
    if ( ! si_is_main_shop() ) return;
    
    global $wp_query;
    $total = $wp_query->found_posts;
    ?>
    <div class="si-shop-toolbar">
        <div class="si-toolbar-start">
            <button type="button" class="si-btn si-btn-secondary si-btn-sm si-filter-toggle-btn" id="si-filter-toggle">
                <i class="fa-solid fa-sliders"></i>
                <span>فیلترها</span>
            </button>
            <div class="si-toolbar-count">
                <i class="fa-solid fa-box-open"></i>
                <span><?php echo esc_html( number_format_i18n( $total ) ); ?> محصول</span>
            </div>
        </div>
        
        <div class="si-toolbar-center">
            <div class="si-toolbar-views">
                <button type="button" class="si-view-btn is-active" data-view="grid" title="نمایش شبکه‌ای">
                    <i class="fa-solid fa-grid-2"></i>
                </button>
                <button type="button" class="si-view-btn" data-view="list" title="نمایش لیستی">
                    <i class="fa-solid fa-list"></i>
                </button>
            </div>
            <div class="si-toolbar-cols">
                <button type="button" class="si-col-btn" data-cols="2" title="۲ ستونه">۲</button>
                <button type="button" class="si-col-btn" data-cols="3" title="۳ ستونه">۳</button>
                <button type="button" class="si-col-btn is-active" data-cols="4" title="۴ ستونه">۴</button>
            </div>
        </div>
        
        <div class="si-toolbar-end">
            <div class="si-toolbar-sort">
                <label class="si-sort-label">
                    <i class="fa-solid fa-arrow-up-arrow-down"></i>
                    <span>مرتب‌سازی:</span>
                </label>
                <?php woocommerce_catalog_ordering(); ?>
            </div>
        </div>
    </div>
    <?php
}, 15 );

/**
 * =============================================================================
 * NO PRODUCTS FOUND
 * =============================================================================
 */
add_action( 'woocommerce_no_products_found', function() {
    if ( ! si_is_main_shop() ) return;
    ?>
    <div class="si-no-products">
        <div class="si-no-products-icon">
            <i class="fa-solid fa-box-open"></i>
        </div>
        <h3 class="si-no-products-title">محصولی یافت نشد</h3>
        <p class="si-no-products-text">متأسفانه محصولی مطابق با فیلترهای انتخابی شما پیدا نشد.</p>
        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="si-btn si-btn-primary">
            <i class="fa-solid fa-rotate-left"></i>
            مشاهده همه محصولات
        </a>
    </div>
    <?php
}, 10 );

/**
 * =============================================================================
 * CLOSE LAYOUT
 * =============================================================================
 */
add_action( 'woocommerce_after_main_content', function() {
    if ( ! si_is_main_shop() ) return;
    ?>
                </div><!-- .si-shop-content -->
            </div><!-- .si-shop-layout -->
        </div><!-- .si-container -->
    </section><!-- .si-shop-main -->
    <?php
}, 15 );

/**
 * =============================================================================
 * PRODUCT COLUMNS
 * =============================================================================
 */
add_filter( 'loop_shop_columns', function( $cols ) {
    if ( si_is_main_shop() ) return 4;
    return $cols;
}, 99 );

/**
 * =============================================================================
 * QUERY MODIFICATIONS
 * =============================================================================
 */
add_action( 'woocommerce_product_query', function( $q ) {
    if ( ! si_is_main_shop() ) return;
    
    // Category filter
    if ( isset( $_GET['filter_cat'] ) && ! empty( $_GET['filter_cat'] ) ) {
        $cat_slug = sanitize_text_field( $_GET['filter_cat'] );
        $tax_query = $q->get( 'tax_query' ) ?: array();
        $tax_query[] = array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $cat_slug,
        );
        $q->set( 'tax_query', $tax_query );
    }
    
    // Brand filter
    if ( isset( $_GET['filter_brand'] ) && ! empty( $_GET['filter_brand'] ) ) {
        $brand_slug = sanitize_text_field( $_GET['filter_brand'] );
        $tax_query = $q->get( 'tax_query' ) ?: array();
        $tax_query[] = array(
            'taxonomy' => 'product_brand',
            'field'    => 'slug',
            'terms'    => $brand_slug,
        );
        $q->set( 'tax_query', $tax_query );
    }
    
    // Stock filter
    if ( isset( $_GET['stock_status'] ) && $_GET['stock_status'] === 'instock' ) {
        $q->set( 'meta_query', array_merge(
            $q->get( 'meta_query' ) ?: array(),
            array(
                array(
                    'key'     => '_stock_status',
                    'value'   => 'instock',
                    'compare' => '=',
                ),
            )
        ) );
    }
    
    // Sale filter
    if ( isset( $_GET['on_sale'] ) ) {
        $q->set( 'post__in', array_merge( array( 0 ), wc_get_product_ids_on_sale() ) );
    }
    
    // Rating filter
    if ( isset( $_GET['rating_filter'] ) ) {
        $rating = absint( $_GET['rating_filter'] );
        $q->set( 'meta_query', array_merge(
            $q->get( 'meta_query' ) ?: array(),
            array(
                array(
                    'key'     => '_wc_average_rating',
                    'value'   => $rating,
                    'compare' => '>=',
                    'type'    => 'DECIMAL',
                ),
            )
        ) );
    }
} );

/**
 * =============================================================================
 * CSS STYLES
 * =============================================================================
 */
add_action( 'wp_head', function() {
    if ( ! si_is_main_shop() ) return;
    ?>
    <style id="si-shop-page-styles">
    /* ==========================================================================
       SHOP PAGE STYLES
       ========================================================================== */
    
    /* --- HIDE DEFAULTS --- */
    .si-shop-page .site-main { padding-top: 0 !important; }
    .si-shop-page .storefront-sorting,
    .si-shop-page .woocommerce-products-header,
    .si-shop-page .site-main > .woocommerce-result-count,
    .si-shop-page .site-main > .woocommerce-ordering,
    .si-shop-page .site-main > .woocommerce-breadcrumb { 
        display: none !important; 
    }
    
    /* --- Container --- */
    .si-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    /* ==========================================================================
       BREADCRUMB
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
    .si-shop-hero {
        position: relative;
        padding: 60px 0;
        background: linear-gradient(135deg, #0d1f12 0%, #1a3a20 50%, #0d1f12 100%);
        overflow: hidden;
    }
    .si-shop-hero-bg {
        position: absolute;
        inset: 0;
        background-image: 
            radial-gradient(circle at 20% 50%, rgba(41,133,58,0.15) 0%, transparent 50%),
            radial-gradient(circle at 80% 50%, rgba(41,133,58,0.1) 0%, transparent 50%);
        pointer-events: none;
    }
    .si-shop-hero-bg::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .si-shop-hero-content {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
    }
    
    .si-shop-hero-title {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 36px;
        font-weight: 800;
        color: #fff;
        margin: 0 0 16px;
    }
    .si-shop-hero-title i {
        font-size: 32px;
        color: var(--si-primary, #29853A);
    }
    
    .si-shop-hero-subtitle {
        font-size: 16px;
        line-height: 1.8;
        color: rgba(255,255,255,0.7);
        margin: 0 0 24px;
    }
    
    /* Stats */
    .si-shop-hero-stats {
        display: flex;
        gap: 24px;
    }
    .si-stat-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 20px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 12px;
        backdrop-filter: blur(10px);
    }
    .si-stat-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--si-primary, #29853A);
        border-radius: 12px;
        font-size: 20px;
        color: #fff;
    }
    .si-stat-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .si-stat-number {
        font-size: 24px;
        font-weight: 800;
        color: #fff;
    }
    .si-stat-label {
        font-size: 13px;
        color: rgba(255,255,255,0.6);
    }
    
    /* Search Box */
    .si-shop-hero-search {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 20px;
        padding: 32px;
        backdrop-filter: blur(10px);
    }
    .si-search-box {
        display: flex;
        align-items: center;
        background: #fff;
        border-radius: 14px;
        padding: 6px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }
    .si-search-icon {
        padding: 0 16px;
        font-size: 18px;
        color: var(--si-text-muted, #9ca3af);
    }
    .si-search-input {
        flex: 1;
        border: none;
        background: transparent;
        font-family: inherit;
        font-size: 15px;
        color: var(--si-text, #1f2937);
        padding: 12px 0;
        outline: none;
    }
    .si-search-input::placeholder {
        color: var(--si-text-muted, #9ca3af);
    }
    .si-search-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: var(--si-primary, #29853A);
        border: none;
        border-radius: 10px;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        color: #fff;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .si-search-btn:hover {
        background: var(--si-primary-dark, #1e6b2d);
        transform: translateX(-4px);
    }
    .si-search-btn i {
        font-size: 12px;
        transition: transform 0.2s ease;
    }
    .si-search-btn:hover i {
        transform: translateX(-4px);
    }
    
    .si-search-suggestions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 16px;
        font-size: 13px;
        color: rgba(255,255,255,0.5);
    }
    .si-search-suggestions a {
        padding: 6px 12px;
        background: rgba(255,255,255,0.1);
        border-radius: 20px;
        color: rgba(255,255,255,0.8);
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .si-search-suggestions a:hover {
        background: var(--si-primary, #29853A);
        color: #fff;
    }
    
    /* ==========================================================================
       FEATURED CATEGORIES
       ========================================================================== */
    .si-shop-categories {
        padding: 50px 0;
        background: var(--si-bg, #fff);
        border-bottom: 1px solid var(--si-border-light, #e5e7eb);
    }
    [data-theme="dark"] .si-shop-categories {
        background: #0c0c12;
        border-color: #1f2937;
    }
    
    .si-section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }
    .si-section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 20px;
        font-weight: 700;
        color: var(--si-text, #1f2937);
        margin: 0;
    }
    [data-theme="dark"] .si-section-title { color: #f3f4f6; }
    .si-section-title i {
        color: var(--si-primary, #29853A);
        font-size: 18px;
    }
    .si-section-link {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        font-weight: 600;
        color: var(--si-primary, #29853A);
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .si-section-link:hover {
        gap: 10px;
    }
    .si-section-link i {
        font-size: 12px;
        transition: transform 0.2s ease;
    }
    .si-section-link:hover i {
        transform: translateX(-4px);
    }
    
    .si-categories-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }
    
    .si-category-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px;
        background: var(--si-surface, #fff);
        border: 1px solid var(--si-border-light, #e5e7eb);
        border-radius: 14px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .si-category-card:hover {
        border-color: var(--cat-color, var(--si-primary));
        box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    [data-theme="dark"] .si-category-card {
        background: #16161f;
        border-color: #252532;
    }
    
    .si-category-icon {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: color-mix(in srgb, var(--cat-color, var(--si-primary)) 10%, transparent);
        border-radius: 12px;
        flex-shrink: 0;
        overflow: hidden;
    }
    .si-category-icon img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .si-category-icon i {
        font-size: 24px;
        color: var(--cat-color, var(--si-primary));
    }
    
    .si-category-info {
        flex: 1;
        min-width: 0;
    }
    .si-category-name {
        font-size: 14px;
        font-weight: 700;
        color: var(--si-text, #1f2937);
        margin: 0 0 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    [data-theme="dark"] .si-category-name { color: #f3f4f6; }
    .si-category-count {
        font-size: 12px;
        color: var(--si-text-muted, #9ca3af);
    }
    
    .si-category-arrow {
        font-size: 12px;
        color: var(--si-text-muted, #d1d5db);
        transition: all 0.2s ease;
    }
    .si-category-card:hover .si-category-arrow {
        color: var(--cat-color, var(--si-primary));
        transform: translateX(-4px);
    }
    
    /* ==========================================================================
       MAIN LAYOUT
       ========================================================================== */
    .si-shop-main {
        padding: 40px 0 60px;
        background: var(--si-bg-secondary, #f8f9fa);
    }
    [data-theme="dark"] .si-shop-main { background: #0a0a0f; }
    
    .si-shop-layout {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 30px;
        align-items: start;
    }
    
    /* ==========================================================================
       SIDEBAR
       ========================================================================== */
    .si-shop-sidebar {
        position: sticky;
        top: 100px;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
    }
    .si-sidebar-inner {
        background: var(--si-surface, #fff);
        border: 1px solid var(--si-border-light, #e5e7eb);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
    }
    [data-theme="dark"] .si-sidebar-inner {
        background: #16161f;
        border-color: #252532;
    }
    
    .si-sidebar-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 20px;
        background: var(--si-bg-secondary, #f8f9fa);
        border-bottom: 1px solid var(--si-border-light, #e5e7eb);
    }
    [data-theme="dark"] .si-sidebar-header {
        background: #1a1a24;
        border-color: #252532;
    }
    .si-sidebar-header-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 15px;
        font-weight: 700;
        color: var(--si-text, #1f2937);
    }
    [data-theme="dark"] .si-sidebar-header-title { color: #f3f4f6; }
    .si-sidebar-header-title i { color: var(--si-primary, #29853A); font-size: 14px; }
    .si-sidebar-reset {
        margin-right: auto;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #ef4444;
        text-decoration: none;
        padding: 6px 10px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    .si-sidebar-reset:hover { background: #fef2f2; color: #dc2626; }
    [data-theme="dark"] .si-sidebar-reset:hover { background: #451a1a; }
    .si-sidebar-close {
        display: none;
        width: 32px;
        height: 32px;
        align-items: center;
        justify-content: center;
        background: var(--si-bg, #fff);
        border: 1px solid var(--si-border, #e5e7eb);
        border-radius: 8px;
        color: var(--si-text, #1f2937);
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .si-sidebar-close:hover { background: #fef2f2; border-color: #ef4444; color: #ef4444; }
    
    .si-sidebar-body { padding: 0; }
    
    /* Filter Sections with Toggle */
    .si-filter-section {
        border-bottom: 1px solid var(--si-border-light, #e9ecef);
    }
    .si-filter-section:last-child { border-bottom: none; }
    [data-theme="dark"] .si-filter-section { border-color: #252532; }
    
    .si-filter-header {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
        padding: 16px 20px;
        font-size: 14px;
        font-weight: 700;
        color: var(--si-text, #1f2937);
        background: transparent;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: right;
        font-family: inherit;
    }
    .si-filter-header:hover {
        background: var(--si-bg-secondary, #f9fafb);
    }
    [data-theme="dark"] .si-filter-header { color: #e5e7eb; }
    [data-theme="dark"] .si-filter-header:hover { background: #1a1a24; }
    .si-filter-header i:first-child {
        width: 16px;
        color: var(--si-primary, #29853A);
        font-size: 13px;
        text-align: center;
    }
    .si-filter-header span { flex: 1; text-align: right; }
    .si-filter-arrow {
        font-size: 10px;
        color: var(--si-text-muted, #9ca3af);
        transition: transform 0.3s ease;
    }
    .si-filter-section.si-filter-open .si-filter-arrow {
        transform: rotate(180deg);
    }
    
    .si-filter-content {
        display: none;
        padding: 0 20px 16px;
        flex-direction: column;
        gap: 8px;
    }
    .si-filter-section.si-filter-open .si-filter-content {
        display: flex;
    }
    .si-filter-scrollable {
        max-height: 200px;
        overflow-y: auto;
    }
    
    /* Checkbox Filters */
    .si-filter-checkbox {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        padding: 10px 12px;
        background: var(--si-bg-secondary, #f9fafb);
        border: 1px solid transparent;
        border-radius: 10px;
        transition: all 0.2s ease;
    }
    .si-filter-checkbox:hover {
        border-color: var(--si-primary, #29853A);
        background: var(--si-primary-lighter, #ecfdf5);
    }
    [data-theme="dark"] .si-filter-checkbox { background: #1a1a24; }
    [data-theme="dark"] .si-filter-checkbox:hover { background: #1a2e1f; border-color: var(--si-primary); }
    .si-filter-checkbox input[type="checkbox"] { display: none; }
    .si-checkbox-box {
        width: 18px;
        height: 18px;
        border: 2px solid var(--si-border, #d1d5db);
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    .si-checkbox-box i {
        font-size: 9px;
        color: #fff;
        opacity: 0;
        transform: scale(0);
        transition: all 0.2s ease;
    }
    .si-filter-checkbox input[type="checkbox"]:checked + .si-checkbox-box {
        background: var(--si-primary, #29853A);
        border-color: var(--si-primary, #29853A);
    }
    .si-filter-checkbox input[type="checkbox"]:checked + .si-checkbox-box i {
        opacity: 1;
        transform: scale(1);
    }
    .si-checkbox-text {
        font-size: 13px;
        color: var(--si-text, #374151);
        flex: 1;
    }
    [data-theme="dark"] .si-checkbox-text { color: #d1d5db; }
    .si-checkbox-count {
        font-size: 11px;
        padding: 2px 6px;
        background: var(--si-bg, #fff);
        border-radius: 4px;
        color: var(--si-text-muted, #9ca3af);
    }
    [data-theme="dark"] .si-checkbox-count { background: #252532; }
    
    /* Rating Filter */
    .si-rating-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        background: var(--si-bg-secondary, #f9fafb);
        border: 1px solid transparent;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .si-rating-row:hover,
    .si-rating-row.is-active {
        border-color: var(--si-primary, #29853A);
        background: var(--si-primary-lighter, #ecfdf5);
    }
    [data-theme="dark"] .si-rating-row { background: #1a1a24; }
    [data-theme="dark"] .si-rating-row:hover,
    [data-theme="dark"] .si-rating-row.is-active { background: #1a2e1f; }
    .si-rating-stars { display: flex; gap: 3px; }
    .si-rating-stars i { font-size: 12px; color: #fbbf24; }
    .si-rating-stars i.fa-regular { color: #d1d5db; }
    .si-rating-text { font-size: 12px; color: var(--si-text-secondary, #6b7280); }
    
    /* Price Filter Widget */
    .si-filter-content .widget_price_filter,
    .si-filter-content .widget { margin: 0 !important; padding: 0 !important; }
    .si-filter-content .widget-title,
    .si-filter-content .widgettitle { display: none !important; }
    
    .si-filter-content .price_slider_wrapper { padding: 5px 0 0; }
    .si-filter-content .ui-slider {
        height: 8px !important;
        background: var(--si-bg-tertiary, #e5e7eb) !important;
        border: none !important;
        border-radius: 10px !important;
        margin: 0 0 20px !important;
        position: relative;
    }
    .si-filter-content .ui-slider .ui-slider-range {
        background: linear-gradient(90deg, var(--si-primary, #29853A), var(--si-primary-light, #34a853)) !important;
        border-radius: 10px !important;
        height: 100% !important;
    }
    .si-filter-content .ui-slider .ui-slider-handle {
        width: 20px !important;
        height: 20px !important;
        background: #fff !important;
        border: 3px solid var(--si-primary, #29853A) !important;
        border-radius: 50% !important;
        top: 50% !important;
        transform: translateY(-50%);
        cursor: grab;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        outline: none !important;
    }
    .si-filter-content .ui-slider .ui-slider-handle:active { cursor: grabbing; }
    
    .si-filter-content .price_slider_amount {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .si-filter-content .price_slider_amount .price_label {
        font-size: 12px;
        color: var(--si-text-secondary, #6b7280);
        text-align: center;
        padding: 8px;
        background: var(--si-bg-secondary, #f9fafb);
        border-radius: 8px;
    }
    [data-theme="dark"] .si-filter-content .price_slider_amount .price_label {
        background: #1a1a24;
        color: #9ca3af;
    }
    .si-filter-content .price_slider_amount .button {
        width: 100%;
        padding: 10px 16px;
        background: var(--si-primary, #29853A) !important;
        color: #fff !important;
        border: none !important;
        border-radius: 10px !important;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .si-filter-content .price_slider_amount .button:hover {
        background: var(--si-primary-dark, #1e6b2d) !important;
    }
    .si-filter-content .price_slider_amount .clear { display: none !important; }
    
    .si-sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 998;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .si-sidebar-overlay.is-active { display: block; opacity: 1; }
    
    /* ==========================================================================
       CONTENT AREA
       ========================================================================== */
    .si-shop-content { min-width: 0; }
    
    /* Active Filters */
    .si-active-filters {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding: 16px 20px;
        background: var(--si-surface, #fff);
        border: 1px solid var(--si-border-light, #e5e7eb);
        border-radius: 14px;
        margin-bottom: 20px;
    }
    [data-theme="dark"] .si-active-filters {
        background: #16161f;
        border-color: #252532;
    }
    .si-active-filters-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 600;
        color: var(--si-text-secondary, #6b7280);
    }
    .si-active-filters-label i { color: var(--si-primary, #29853A); }
    .si-active-filters-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        flex: 1;
    }
    .si-active-filter {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        background: var(--si-primary-lighter, #ecfdf5);
        border: 1px solid var(--si-primary-light, #86efac);
        border-radius: 8px;
        font-size: 12px;
        color: var(--si-primary, #29853A);
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .si-active-filter:hover {
        background: #fef2f2;
        border-color: #fca5a5;
        color: #ef4444;
    }
    [data-theme="dark"] .si-active-filter {
        background: #1a2e1f;
        border-color: #2d5a3d;
    }
    .si-active-filter i { font-size: 10px; }
    .si-clear-all-filters {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #ef4444;
        text-decoration: none;
        padding: 6px 12px;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    .si-clear-all-filters:hover {
        background: #fef2f2;
    }
    
    /* TOOLBAR */
    .si-shop-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 14px 20px;
        background: var(--si-surface, #fff);
        border: 1px solid var(--si-border-light, #e5e7eb);
        border-radius: 14px;
        margin-bottom: 24px;
    }
    [data-theme="dark"] .si-shop-toolbar { background: #16161f; border-color: #252532; }
    
    .si-toolbar-start,
    .si-toolbar-center,
    .si-toolbar-end {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    
    .si-filter-toggle-btn { display: none; }
    
    .si-toolbar-count {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: var(--si-text-secondary, #6b7280);
    }
    .si-toolbar-count i { color: var(--si-primary, #29853A); }
    
    /* Buttons */
    .si-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
        font-family: inherit;
    }
    .si-btn-primary {
        background: var(--si-primary, #29853A);
        color: #fff;
    }
    .si-btn-primary:hover {
        background: var(--si-primary-dark, #1e6b2d);
        color: #fff;
    }
    .si-btn-secondary {
        background: var(--si-bg-secondary, #f3f4f6);
        color: var(--si-text, #374151);
        border: 1px solid var(--si-border, #d1d5db);
    }
    .si-btn-secondary:hover {
        background: var(--si-bg-tertiary, #e5e7eb);
    }
    [data-theme="dark"] .si-btn-secondary {
        background: #1a1a24;
        border-color: #2d2d3d;
        color: #e5e7eb;
    }
    .si-btn-sm { padding: 8px 16px; font-size: 13px; }
    
    .si-toolbar-views,
    .si-toolbar-cols {
        display: flex;
        align-items: center;
        background: var(--si-bg-secondary, #f3f4f6);
        border-radius: 10px;
        padding: 4px;
    }
    [data-theme="dark"] .si-toolbar-views,
    [data-theme="dark"] .si-toolbar-cols { background: #1a1a24; }
    
    .si-shop-toolbar .si-view-btn,
    .si-shop-toolbar .si-col-btn {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        border-radius: 8px;
        color: var(--si-text-secondary, #6b7280);
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        font-family: inherit;
    }
    .si-shop-toolbar .si-view-btn:hover,
    .si-shop-toolbar .si-col-btn:hover { color: var(--si-primary, #29853A); }
    .si-shop-toolbar .si-view-btn.is-active,
    .si-shop-toolbar .si-col-btn.is-active {
        background: var(--si-primary, #29853A);
        color: #fff;
        box-shadow: 0 2px 8px rgba(41,133,58,0.3);
    }
    
    .si-toolbar-sort {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .si-sort-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: var(--si-text-secondary, #6b7280);
        white-space: nowrap;
    }
    .si-sort-label i { color: var(--si-primary, #29853A); }
    
    .si-shop-toolbar .woocommerce-ordering { display: block !important; margin: 0; }
    .si-shop-toolbar .woocommerce-ordering select {
        padding: 10px 36px 10px 16px;
        border: 1px solid var(--si-border, #d1d5db);
        border-radius: 10px;
        font-family: inherit;
        font-size: 13px;
        color: var(--si-text, #374151);
        background-color: var(--si-bg, #fff);
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%236b7280'%3E%3Cpath d='M7 10l5 5 5-5H7z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: left 10px center;
        background-size: 18px;
        cursor: pointer;
        min-width: 180px;
        transition: all 0.2s ease;
    }
    .si-shop-toolbar .woocommerce-ordering select:focus {
        outline: none;
        border-color: var(--si-primary, #29853A);
        box-shadow: 0 0 0 3px rgba(41,133,58,0.1);
    }
    [data-theme="dark"] .si-shop-toolbar .woocommerce-ordering select {
        background-color: #1a1a24;
        border-color: #2d2d3d;
        color: #e5e7eb;
    }
    
    /* No Products */
    .si-no-products {
        text-align: center;
        padding: 60px 20px;
        background: var(--si-surface, #fff);
        border: 1px solid var(--si-border-light, #e5e7eb);
        border-radius: 16px;
    }
    [data-theme="dark"] .si-no-products {
        background: #16161f;
        border-color: #252532;
    }
    .si-no-products-icon {
        font-size: 64px;
        color: var(--si-text-muted, #d1d5db);
        margin-bottom: 20px;
    }
    .si-no-products-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--si-text, #1f2937);
        margin: 0 0 10px;
    }
    [data-theme="dark"] .si-no-products-title { color: #f3f4f6; }
    .si-no-products-text {
        font-size: 14px;
        color: var(--si-text-secondary, #6b7280);
        margin: 0 0 24px;
    }
    
    /* ==========================================================================
       PRODUCTS GRID
       ========================================================================== */
    .si-shop-content ul.products {
        display: grid !important;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 20px;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .si-shop-content ul.products.columns-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .si-shop-content ul.products.columns-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .si-shop-content ul.products.columns-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    
    .si-shop-content ul.products li.product {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        float: none !important;
    }
    
    /* List View */
    .si-shop-content ul.products.list-view {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    /* ==========================================================================
       RESPONSIVE
       ========================================================================== */
    @media (max-width: 1200px) {
        .si-shop-layout { grid-template-columns: 280px 1fr; gap: 24px; }
        .si-shop-content ul.products { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 16px; }
        .si-categories-grid { grid-template-columns: repeat(3, 1fr); }
    }
    
    @media (max-width: 991px) {
        .si-shop-layout { grid-template-columns: 1fr; }
        .si-shop-sidebar {
            position: fixed;
            top: 0;
            right: -340px;
            width: 320px;
            height: 100%;
            max-height: 100%;
            z-index: 999;
            transition: right 0.3s ease;
            background: var(--si-bg, #fff);
            border-radius: 0;
        }
        .si-shop-sidebar.is-active { right: 0; }
        .si-sidebar-inner { border-radius: 0; border: none; height: 100%; }
        .si-sidebar-close { display: flex; }
        .si-filter-toggle-btn { display: inline-flex; }
        .si-toolbar-center { display: none; }
        .si-shop-content ul.products { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .si-categories-grid { grid-template-columns: repeat(2, 1fr); }
        
        .si-shop-hero-content { grid-template-columns: 1fr; gap: 40px; }
        .si-shop-hero-stats { justify-content: center; }
    }
    
    @media (max-width: 768px) {
        .si-shop-hero { padding: 40px 0; }
        .si-shop-hero-title { font-size: 28px; justify-content: center; }
        .si-shop-hero-subtitle { text-align: center; }
        .si-shop-hero-stats { flex-wrap: wrap; justify-content: center; }
        .si-stat-item { flex: 1; min-width: 140px; }
        
        .si-shop-toolbar { flex-wrap: wrap; padding: 12px 16px; gap: 12px; }
        .si-toolbar-start { width: 100%; justify-content: space-between; }
        .si-toolbar-end { width: 100%; }
        .si-toolbar-sort { width: 100%; }
        .si-shop-toolbar .woocommerce-ordering select { width: 100%; }
        
        .si-categories-grid { grid-template-columns: 1fr; }
    }
    
    @media (max-width: 480px) {
        .si-shop-content ul.products { grid-template-columns: 1fr; }
        .si-shop-sidebar { width: 100%; right: -100%; }
        .si-shop-hero-stats { flex-direction: column; }
        .si-stat-item { width: 100%; justify-content: center; }
    }
    </style>
    <?php
}, 99 );

/**
 * =============================================================================
 * JAVASCRIPT
 * =============================================================================
 */
add_action( 'wp_footer', function() {
    if ( ! si_is_main_shop() ) return;
    ?>
    <script id="si-shop-page-js">
    (function() {
        'use strict';
        
        // Sidebar toggle
        var sidebar = document.getElementById('si-shop-sidebar');
        var overlay = document.getElementById('si-sidebar-overlay');
        var toggleBtn = document.getElementById('si-filter-toggle');
        var closeBtn = document.getElementById('si-sidebar-close');
        
        function openSidebar() {
            if (sidebar) sidebar.classList.add('is-active');
            if (overlay) overlay.classList.add('is-active');
            document.body.style.overflow = 'hidden';
        }
        
        function closeSidebar() {
            if (sidebar) sidebar.classList.remove('is-active');
            if (overlay) overlay.classList.remove('is-active');
            document.body.style.overflow = '';
        }
        
        if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeSidebar();
        });
        
        // Filter section toggles
        var filterToggles = document.querySelectorAll('.si-filter-toggle');
        filterToggles.forEach(function(toggle) {
            toggle.addEventListener('click', function() {
                var section = this.closest('.si-filter-section');
                if (section) {
                    section.classList.toggle('si-filter-open');
                }
            });
        });
        
        // Column switcher
        var colBtns = document.querySelectorAll('.si-shop-toolbar .si-col-btn');
        var productList = document.querySelector('.si-shop-content ul.products');
        
        colBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var cols = this.getAttribute('data-cols');
                colBtns.forEach(function(b) { b.classList.remove('is-active'); });
                this.classList.add('is-active');
                if (productList) {
                    productList.classList.remove('columns-2', 'columns-3', 'columns-4');
                    productList.classList.add('columns-' + cols);
                }
                localStorage.setItem('si_shop_cols', cols);
            });
        });
        
        // View switcher
        var viewBtns = document.querySelectorAll('.si-shop-toolbar .si-view-btn');
        viewBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var view = this.getAttribute('data-view');
                viewBtns.forEach(function(b) { b.classList.remove('is-active'); });
                this.classList.add('is-active');
                if (productList) {
                    if (view === 'list') {
                        productList.classList.add('list-view');
                    } else {
                        productList.classList.remove('list-view');
                    }
                }
                localStorage.setItem('si_shop_view', view);
            });
        });
        
        // Restore preferences
        var savedCols = localStorage.getItem('si_shop_cols');
        var savedView = localStorage.getItem('si_shop_view');
        
        if (savedCols && productList) {
            colBtns.forEach(function(b) {
                b.classList.remove('is-active');
                if (b.getAttribute('data-cols') === savedCols) {
                    b.classList.add('is-active');
                }
            });
            productList.classList.remove('columns-2', 'columns-3', 'columns-4');
            productList.classList.add('columns-' + savedCols);
        }
        
        if (savedView && productList) {
            viewBtns.forEach(function(b) {
                b.classList.remove('is-active');
                if (b.getAttribute('data-view') === savedView) {
                    b.classList.add('is-active');
                }
            });
            if (savedView === 'list') {
                productList.classList.add('list-view');
            }
        }
    })();
    </script>
    <?php
}, 99 );
