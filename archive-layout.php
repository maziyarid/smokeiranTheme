if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * =============================================================================
 * SMOKE IRAN - Unified WooCommerce Archive Layout
 * =============================================================================
 * Handles all WooCommerce taxonomy archives:
 * - Product Brands (product_brand, pwb-brand)
 * - Product Categories (product_cat)
 * - Product Tags (product_tag)
 * =============================================================================
 */

/**
 * Check if current page is a supported archive
 */
function si_is_wc_archive() {
    return is_tax( 'product_brand' ) || 
           is_tax( 'pwb-brand' ) || 
           is_product_category() || 
           is_product_tag();
}

/**
 * Get archive type for conditional display
 */
function si_get_archive_type() {
    if ( is_tax( 'product_brand' ) || is_tax( 'pwb-brand' ) ) {
        return 'brand';
    } elseif ( is_product_category() ) {
        return 'category';
    } elseif ( is_product_tag() ) {
        return 'tag';
    }
    return 'archive';
}

/**
 * Get archive label in Persian
 */
function si_get_archive_label( $type = null ) {
    if ( ! $type ) {
        $type = si_get_archive_type();
    }
    
    $labels = array(
        'brand'    => 'برند',
        'category' => 'دسته‌بندی',
        'tag'      => 'برچسب',
        'archive'  => 'آرشیو',
    );
    
    return isset( $labels[ $type ] ) ? $labels[ $type ] : $labels['archive'];
}

/**
 * Get archive icon
 */
function si_get_archive_icon( $type = null ) {
    if ( ! $type ) {
        $type = si_get_archive_type();
    }
    
    $icons = array(
        'brand'    => 'fa-solid fa-certificate',
        'category' => 'fa-solid fa-folder',
        'tag'      => 'fa-solid fa-tag',
        'archive'  => 'fa-solid fa-archive',
    );
    
    return isset( $icons[ $type ] ) ? $icons[ $type ] : $icons['archive'];
}

/**
 * Get parent categories for breadcrumb (recursive)
 */
function si_get_term_parents( $term_id, $taxonomy, $separator = '', $visited = array() ) {
    $chain = array();
    $parent = get_term( $term_id, $taxonomy );
    
    if ( is_wp_error( $parent ) || ! $parent ) {
        return $chain;
    }
    
    if ( $parent->parent && ( $parent->parent != $parent->term_id ) && ! in_array( $parent->parent, $visited ) ) {
        $visited[] = $parent->parent;
        $chain = si_get_term_parents( $parent->parent, $taxonomy, $separator, $visited );
    }
    
    $chain[] = array(
        'name' => $parent->name,
        'link' => get_term_link( $parent, $taxonomy ),
    );
    
    return $chain;
}

/**
 * =============================================================================
 * CLEANUP: Remove ALL default Storefront/WooCommerce elements
 * =============================================================================
 */
add_action( 'wp', function() {
    if ( ! si_is_wc_archive() ) return;
    
    // Remove Storefront breadcrumbs
    remove_action( 'storefront_content_top', 'woocommerce_breadcrumb', 10 );
    
    // Remove WooCommerce breadcrumbs
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
    
    // Remove any other duplicate elements
    for ( $priority = 1; $priority <= 100; $priority++ ) {
        remove_action( 'woocommerce_before_main_content', 'si_archive_breadcrumb', $priority );
        remove_action( 'woocommerce_before_main_content', 'si_archive_header', $priority );
        remove_action( 'woocommerce_before_main_content', 'si_archive_layout_start', $priority );
        remove_action( 'woocommerce_before_main_content', 'si_sidebar_output', $priority );
        remove_action( 'woocommerce_before_shop_loop', 'si_toolbar_output', $priority );
        remove_action( 'woocommerce_before_shop_loop', 'si_archive_toolbar', $priority );
        remove_action( 'woocommerce_after_shop_loop', 'si_archive_layout_end', $priority );
        remove_action( 'woocommerce_after_main_content', 'si_archive_layout_end', $priority );
    }
    
    // Force full-width layout
    add_filter( 'storefront_page_template', function() { return 'full-width'; } );
    add_filter( 'body_class', function( $classes ) {
        $classes[] = 'storefront-full-width-content';
        $classes[] = 'si-wc-archive-page';
        $classes[] = 'si-archive-' . si_get_archive_type();
        return $classes;
    } );
    
}, 5 );

/**
 * =============================================================================
 * OUTPUT BUFFER CLEANUP - Remove duplicate content
 * =============================================================================
 */
add_action( 'template_redirect', function() {
    if ( ! si_is_wc_archive() ) return;
    
    ob_start( function( $html ) {
        // Remove duplicate breadcrumb
        $html = preg_replace( 
            '/<nav class="si-breadcrumb"[^>]*>.*?<\/nav>/s', 
            '', 
            $html 
        );
        
        // Remove duplicate archive header
        $html = preg_replace( 
            '/<header class="si-archive-header[^>]*>.*?<\/header>/s', 
            '', 
            $html 
        );
        
        // Remove duplicate sidebar (not si-wc-sidebar)
        $html = preg_replace( 
            '/<aside class="si-sidebar"[^>]*>.*?<\/aside>/s', 
            '', 
            $html 
        );
        
        // Remove si-archive-main wrapper
        $html = preg_replace( 
            '/<div class="si-archive-main">.*?<div class="si-archive-layout"[^>]*>/s', 
            '', 
            $html 
        );
        
        // Remove si-products-main wrapper
        $html = preg_replace( 
            '/<main class="si-products-main">/s', 
            '', 
            $html 
        );
        
        // Remove woocommerce-products-header
        $html = preg_replace( 
            '/<header class="woocommerce-products-header">.*?<\/header>/s', 
            '', 
            $html 
        );
        
        // Remove duplicate storefront-sorting divs
        $html = preg_replace( 
            '/<div class="storefront-sorting">.*?<\/div>\s*(?=<ul class="products|<\/main)/s', 
            '', 
            $html 
        );
        
        // Remove storefront-sorting after products
        $html = preg_replace( 
            '/<\/ul>\s*<div class="storefront-sorting">.*?<\/div>/s', 
            '</ul>', 
            $html 
        );
        
        return $html;
    } );
}, 1 );

/**
 * =============================================================================
 * HERO SECTION WITH BREADCRUMB
 * =============================================================================
 */
add_action( 'woocommerce_before_main_content', function() {
    if ( ! si_is_wc_archive() ) return;
    
    $term = get_queried_object();
    if ( ! $term || empty( $term->term_id ) ) return;
    
    $archive_type = si_get_archive_type();
    $archive_label = si_get_archive_label();
    $archive_icon = si_get_archive_icon();
    
    // Get term data
    $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
    $thumbnail = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'medium' ) : '';
    $count = number_format_i18n( $term->count );
    $term_link = get_term_link( $term );
    
    // Brand-specific data
    $site_url = '';
    $country = '';
    if ( $archive_type === 'brand' ) {
        $site_url = get_term_meta( $term->term_id, 'brand_website', true );
        $country = get_term_meta( $term->term_id, 'brand_country', true );
    }
    
    // Category-specific: get parent categories
    $parent_terms = array();
    if ( $archive_type === 'category' && $term->parent ) {
        $parent_terms = si_get_term_parents( $term->parent, 'product_cat' );
    }
    
    // Get subcategories for category pages
    $subcategories = array();
    if ( $archive_type === 'category' ) {
        $subcategories = get_terms( array(
            'taxonomy'   => 'product_cat',
            'parent'     => $term->term_id,
            'hide_empty' => true,
        ) );
    }
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
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">فروشگاه</a>
                
                <?php if ( $archive_type === 'brand' ) : ?>
                    <i class="fa-solid fa-chevron-left si-breadcrumb-sep"></i>
                    <a href="<?php echo esc_url( home_url( '/brands/' ) ); ?>">برندها</a>
                    
                <?php elseif ( $archive_type === 'category' ) : ?>
                    <?php if ( ! empty( $parent_terms ) ) : ?>
                        <?php foreach ( $parent_terms as $parent ) : ?>
                            <i class="fa-solid fa-chevron-left si-breadcrumb-sep"></i>
                            <a href="<?php echo esc_url( $parent['link'] ); ?>"><?php echo esc_html( $parent['name'] ); ?></a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                <?php elseif ( $archive_type === 'tag' ) : ?>
                    <i class="fa-solid fa-chevron-left si-breadcrumb-sep"></i>
                    <span>برچسب‌ها</span>
                <?php endif; ?>
                
                <i class="fa-solid fa-chevron-left si-breadcrumb-sep"></i>
                <span class="si-breadcrumb-current"><?php echo esc_html( $term->name ); ?></span>
            </nav>
        </div>
    </div>
    
    <!-- ========== HERO SECTION ========== -->
    <section class="si-wc-hero si-wc-hero--<?php echo esc_attr( $archive_type ); ?>">
        <div class="si-container">
            <div class="si-wc-hero-grid">
                <!-- Thumbnail / Logo -->
                <div class="si-wc-hero-thumb-wrap">
                    <?php if ( $thumbnail ) : ?>
                        <div class="si-wc-hero-thumb">
                            <img src="<?php echo esc_url( $thumbnail ); ?>" alt="<?php echo esc_attr( $term->name ); ?>">
                        </div>
                    <?php else : ?>
                        <div class="si-wc-hero-thumb si-wc-hero-thumb--placeholder">
                            <i class="<?php echo esc_attr( $archive_icon ); ?>"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Info -->
                <div class="si-wc-hero-info">
                    <!-- Badges -->
                    <div class="si-wc-hero-badges">
                        <span class="si-badge si-badge-primary">
                            <i class="<?php echo esc_attr( $archive_icon ); ?>"></i>
                            <?php echo esc_html( $archive_label ); ?>
                        </span>
                        <span class="si-badge">
                            <i class="fa-solid fa-box-open"></i>
                            <?php echo esc_html( $count ); ?> محصول
                        </span>
                        <?php if ( $archive_type === 'brand' && $country ) : ?>
                            <span class="si-badge">
                                <i class="fa-solid fa-globe"></i>
                                <?php echo esc_html( $country ); ?>
                            </span>
                        <?php endif; ?>
                        <?php if ( $archive_type === 'category' && ! empty( $subcategories ) ) : ?>
                            <span class="si-badge">
                                <i class="fa-solid fa-sitemap"></i>
                                <?php echo count( $subcategories ); ?> زیردسته
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Title -->
                    <h1 class="si-wc-hero-title"><?php echo esc_html( $term->name ); ?></h1>
                    
                    <!-- Description -->
                    <?php if ( ! empty( $term->description ) ) : ?>
                        <div class="si-wc-hero-desc">
                            <?php echo wp_kses_post( wpautop( $term->description ) ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Subcategories (for category pages) -->
                    <?php if ( $archive_type === 'category' && ! empty( $subcategories ) && ! is_wp_error( $subcategories ) ) : ?>
                        <div class="si-wc-subcats">
                            <span class="si-wc-subcats-label">
                                <i class="fa-solid fa-folder-tree"></i>
                                زیردسته‌ها:
                            </span>
                            <div class="si-wc-subcats-list">
                                <?php foreach ( array_slice( $subcategories, 0, 6 ) as $subcat ) : ?>
                                    <a href="<?php echo esc_url( get_term_link( $subcat ) ); ?>" class="si-wc-subcat-item">
                                        <?php echo esc_html( $subcat->name ); ?>
                                        <span class="si-wc-subcat-count"><?php echo esc_html( $subcat->count ); ?></span>
                                    </a>
                                <?php endforeach; ?>
                                <?php if ( count( $subcategories ) > 6 ) : ?>
                                    <span class="si-wc-subcat-more">+<?php echo count( $subcategories ) - 6; ?> دیگر</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Actions -->
                    <div class="si-wc-hero-actions">
                        <a href="#si-products" class="si-btn si-btn-primary">
                            <i class="fa-solid fa-bag-shopping"></i>
                            مشاهده محصولات
                        </a>
                        <?php if ( $archive_type === 'brand' && $site_url ) : ?>
                            <a href="<?php echo esc_url( $site_url ); ?>" target="_blank" rel="noopener" class="si-btn si-btn-secondary">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                وب‌سایت برند
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- ========== MAIN LAYOUT ========== -->
    <section class="si-wc-main" id="si-products">
        <div class="si-container">
            <div class="si-wc-layout">
                
                <!-- ===== SIDEBAR ===== -->
                <aside class="si-wc-sidebar" id="si-wc-sidebar">
                    <div class="si-sidebar-inner">
                        
                        <!-- Sidebar Header -->
                        <div class="si-sidebar-header">
                            <div class="si-sidebar-header-title">
                                <i class="fa-solid fa-sliders"></i>
                                <span>فیلترها</span>
                            </div>
                            <a href="<?php echo esc_url( $term_link ); ?>" class="si-sidebar-reset">
                                <i class="fa-solid fa-rotate-left"></i>
                                <span>پاک‌سازی</span>
                            </a>
                            <button type="button" class="si-sidebar-close" id="si-sidebar-close">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        
                        <!-- Sidebar Body -->
                        <div class="si-sidebar-body">
                            
                            <?php 
                            // For category pages, show subcategories filter
                            if ( $archive_type === 'category' && ! empty( $subcategories ) && ! is_wp_error( $subcategories ) ) : 
                            ?>
                            <!-- FILTER: Subcategories -->
                            <div class="si-filter-section">
                                <div class="si-filter-header">
                                    <i class="fa-solid fa-folder-tree"></i>
                                    <span>زیردسته‌ها</span>
                                </div>
                                <div class="si-filter-content">
                                    <?php foreach ( $subcategories as $subcat ) : ?>
                                        <a href="<?php echo esc_url( get_term_link( $subcat ) ); ?>" class="si-subcat-row">
                                            <span class="si-subcat-name"><?php echo esc_html( $subcat->name ); ?></span>
                                            <span class="si-subcat-count"><?php echo esc_html( $subcat->count ); ?></span>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <!-- FILTER: Price Range -->
                            <div class="si-filter-section">
                                <div class="si-filter-header">
                                    <i class="fa-solid fa-coins"></i>
                                    <span>محدوده قیمت</span>
                                </div>
                                <div class="si-filter-content">
                                    <?php
                                    if ( class_exists( 'WC_Widget_Price_Filter' ) ) {
                                        the_widget( 'WC_Widget_Price_Filter', array( 'title' => '' ) );
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <!-- FILTER: Stock Status -->
                            <div class="si-filter-section">
                                <div class="si-filter-header">
                                    <i class="fa-solid fa-warehouse"></i>
                                    <span>وضعیت موجودی</span>
                                </div>
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
                                <div class="si-filter-header">
                                    <i class="fa-solid fa-star"></i>
                                    <span>امتیاز محصول</span>
                                </div>
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
                            
                            <?php 
                            // For non-brand pages, show brand filter
                            if ( $archive_type !== 'brand' ) :
                                $brands = get_terms( array(
                                    'taxonomy'   => 'product_brand',
                                    'hide_empty' => true,
                                    'number'     => 20,
                                ) );
                                if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) :
                            ?>
                            <!-- FILTER: Brands -->
                            <div class="si-filter-section">
                                <div class="si-filter-header">
                                    <i class="fa-solid fa-certificate"></i>
                                    <span>برندها</span>
                                </div>
                                <div class="si-filter-content si-filter-scrollable">
                                    <?php 
                                    $current_brand = isset( $_GET['filter_brand'] ) ? sanitize_text_field( $_GET['filter_brand'] ) : '';
                                    foreach ( $brands as $brand ) : 
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
                            <?php 
                                endif;
                            endif; 
                            ?>
                            
                        </div>
                    </div>
                </aside>
                
                <!-- Sidebar Overlay -->
                <div class="si-sidebar-overlay" id="si-sidebar-overlay"></div>
                
                <!-- ===== CONTENT ===== -->
                <div class="si-wc-content">
    <?php
}, 10 );

/**
 * =============================================================================
 * TOOLBAR
 * =============================================================================
 */
add_action( 'woocommerce_before_shop_loop', function() {
    if ( ! si_is_wc_archive() ) return;
    
    global $wp_query;
    $total = $wp_query->found_posts;
    ?>
    <div class="si-wc-toolbar">
        <div class="si-toolbar-start">
            <button type="button" class="si-btn si-btn-secondary si-btn-sm si-filter-toggle-btn" id="si-filter-toggle">
                <i class="fa-solid fa-sliders"></i>
                <span>فیلترها</span>
            </button>
            <div class="si-toolbar-count">
                <i class="fa-solid fa-grid-2"></i>
                <span><?php echo esc_html( $total ); ?> محصول</span>
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
 * NO PRODUCTS FOUND MESSAGE
 * =============================================================================
 */
add_action( 'woocommerce_no_products_found', function() {
    if ( ! si_is_wc_archive() ) return;
    ?>
    <div class="si-no-products">
        <div class="si-no-products-icon">
            <i class="fa-solid fa-box-open"></i>
        </div>
        <h3 class="si-no-products-title">محصولی یافت نشد</h3>
        <p class="si-no-products-text">متأسفانه محصولی مطابق با فیلترهای انتخابی شما پیدا نشد.</p>
        <a href="<?php echo esc_url( get_term_link( get_queried_object() ) ); ?>" class="si-btn si-btn-primary">
            <i class="fa-solid fa-rotate-left"></i>
            پاک کردن فیلترها
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
    if ( ! si_is_wc_archive() ) return;
    ?>
                </div><!-- .si-wc-content -->
            </div><!-- .si-wc-layout -->
        </div><!-- .si-container -->
    </section><!-- .si-wc-main -->
    <?php
}, 15 );

/**
 * =============================================================================
 * PRODUCT COLUMNS
 * =============================================================================
 */
add_filter( 'loop_shop_columns', function( $cols ) {
    if ( si_is_wc_archive() ) return 4;
    return $cols;
}, 99 );

/**
 * =============================================================================
 * QUERY MODIFICATIONS
 * =============================================================================
 */
add_action( 'woocommerce_product_query', function( $q ) {
    if ( ! si_is_wc_archive() ) return;
    
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
    
    // Brand filter (for category/tag pages)
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
} );

/**
 * =============================================================================
 * CSS STYLES
 * =============================================================================
 */
add_action( 'wp_head', function() {
    if ( ! si_is_wc_archive() ) return;
    ?>
    <style id="si-wc-archive-styles">
    /* ==========================================================================
       UNIFIED WC ARCHIVE STYLES
       ========================================================================== */
    
    /* --- HIDE DEFAULT ELEMENTS --- */
    .si-wc-archive-page .site-main { padding-top: 0 !important; }
    .si-wc-archive-page .storefront-sorting,
    .si-wc-archive-page .site-main > .woocommerce-result-count,
    .si-wc-archive-page .site-main > .woocommerce-ordering,
    .si-wc-archive-page .woocommerce-products-header,
    .si-wc-archive-page .term-description,
    .si-wc-archive-page .page-title,
    .si-wc-archive-page .woocommerce-products-header__title,
    .si-wc-archive-page .site-main > .woocommerce-breadcrumb,
    .si-wc-archive-page .site-main > nav.woocommerce-breadcrumb,
    .si-wc-archive-page .si-breadcrumb,
    .si-wc-archive-page nav.si-breadcrumb,
    .si-wc-archive-page .si-archive-header,
    .si-wc-archive-page aside.si-sidebar:not(.si-wc-sidebar),
    .si-wc-archive-page .si-filter-widget,
    .si-wc-archive-page .si-filter-body,
    .si-wc-archive-page .si-products-main,
    .si-wc-archive-page .si-toolbar:not(.si-wc-toolbar),
    .si-wc-archive-page .si-archive-main,
    .si-wc-archive-page .si-archive-layout { 
        display: none !important; 
    }
    
    /* Hide extra ordering forms */
    .si-wc-archive-page .si-wc-content > .woocommerce-ordering,
    .si-wc-archive-page ul.products + .woocommerce-ordering {
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
       BADGES & BUTTONS
       ========================================================================== */
    .si-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: var(--si-bg-secondary, #f3f4f6);
        border: 1px solid var(--si-border-light, #e5e7eb);
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        color: var(--si-text-secondary, #6b7280);
    }
    .si-badge i { font-size: 11px; }
    .si-badge-primary {
        background: var(--si-primary-lighter, #ecfdf5);
        border-color: var(--si-primary-light, #86efac);
        color: var(--si-primary, #29853A);
    }
    [data-theme="dark"] .si-badge {
        background: #1a1a24;
        border-color: #2d2d3d;
        color: #9ca3af;
    }
    [data-theme="dark"] .si-badge-primary {
        background: #1a2e1f;
        border-color: #2d5a3d;
        color: #4ade80;
    }
    
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
    }
    .si-btn-primary {
        background: var(--si-primary, #29853A);
        color: #fff;
    }
    .si-btn-primary:hover {
        background: var(--si-primary-dark, #1e6b2d);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(41,133,58,0.3);
    }
    .si-btn-secondary {
        background: var(--si-bg-secondary, #f3f4f6);
        color: var(--si-text, #374151);
        border: 1px solid var(--si-border, #d1d5db);
    }
    .si-btn-secondary:hover {
        background: var(--si-bg-tertiary, #e5e7eb);
        color: var(--si-text, #374151);
    }
    [data-theme="dark"] .si-btn-secondary {
        background: #1a1a24;
        border-color: #2d2d3d;
        color: #e5e7eb;
    }
    .si-btn-sm { padding: 8px 16px; font-size: 13px; }
    
    /* ==========================================================================
       HERO SECTION
       ========================================================================== */
    .si-wc-hero {
        background: linear-gradient(135deg, #f0fdf4 0%, #f8fafc 100%);
        padding: 50px 0;
        border-bottom: 1px solid var(--si-border-light, #e5e7eb);
    }
    .si-wc-hero--category {
        background: linear-gradient(135deg, #eff6ff 0%, #f8fafc 100%);
    }
    .si-wc-hero--tag {
        background: linear-gradient(135deg, #fef3c7 0%, #fffbeb 100%);
    }
    [data-theme="dark"] .si-wc-hero {
        background: linear-gradient(135deg, #0d1f12 0%, #111827 100%);
        border-color: #1f2937;
    }
    [data-theme="dark"] .si-wc-hero--category {
        background: linear-gradient(135deg, #0d1520 0%, #111827 100%);
    }
    [data-theme="dark"] .si-wc-hero--tag {
        background: linear-gradient(135deg, #1a1708 0%, #111827 100%);
    }
    
    .si-wc-hero-grid {
        display: grid;
        grid-template-columns: 160px 1fr;
        gap: 40px;
        align-items: start;
    }
    .si-wc-hero-thumb-wrap { display: flex; justify-content: center; }
    .si-wc-hero-thumb {
        width: 160px;
        height: 160px;
        background: #fff;
        border: 2px solid var(--si-border-light, #e5e7eb);
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    .si-wc-hero-thumb:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 50px rgba(41,133,58,0.15);
        border-color: var(--si-primary, #29853A);
    }
    [data-theme="dark"] .si-wc-hero-thumb {
        background: #1a1a27;
        border-color: #2d2d3d;
    }
    .si-wc-hero-thumb img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .si-wc-hero-thumb--placeholder {
        font-size: 48px;
        color: var(--si-text-muted, #ccc);
    }
    .si-wc-hero--category .si-wc-hero-thumb--placeholder { color: #3b82f6; }
    .si-wc-hero--tag .si-wc-hero-thumb--placeholder { color: #f59e0b; }
    
    .si-wc-hero-info { display: flex; flex-direction: column; gap: 16px; }
    .si-wc-hero-badges { display: flex; flex-wrap: wrap; gap: 10px; }
    
    .si-wc-hero--category .si-badge-primary {
        background: #eff6ff;
        border-color: #93c5fd;
        color: #2563eb;
    }
    .si-wc-hero--tag .si-badge-primary {
        background: #fef3c7;
        border-color: #fcd34d;
        color: #d97706;
    }
    [data-theme="dark"] .si-wc-hero--category .si-badge-primary {
        background: #1e3a5f;
        border-color: #3b82f6;
        color: #60a5fa;
    }
    [data-theme="dark"] .si-wc-hero--tag .si-badge-primary {
        background: #422006;
        border-color: #d97706;
        color: #fbbf24;
    }
    
    .si-wc-hero-title {
        font-size: 32px;
        font-weight: 800;
        color: var(--si-text, #1f2937);
        margin: 0;
        line-height: 1.3;
    }
    [data-theme="dark"] .si-wc-hero-title { color: #f3f4f6; }
    .si-wc-hero-desc {
        font-size: 15px;
        line-height: 1.9;
        color: var(--si-text-secondary, #4b5563);
        max-width: 800px;
    }
    .si-wc-hero-desc p { margin: 0 0 10px; }
    .si-wc-hero-desc p:last-child { margin-bottom: 0; }
    [data-theme="dark"] .si-wc-hero-desc { color: #9ca3af; }
    .si-wc-hero-actions { display: flex; gap: 12px; margin-top: 8px; }
    
    /* Subcategories in Hero */
    .si-wc-subcats {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        padding: 16px;
        background: rgba(255,255,255,0.7);
        border-radius: 12px;
        border: 1px solid var(--si-border-light, #e5e7eb);
    }
    [data-theme="dark"] .si-wc-subcats {
        background: rgba(26,26,36,0.7);
        border-color: #2d2d3d;
    }
    .si-wc-subcats-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 600;
        color: var(--si-text-secondary, #6b7280);
    }
    .si-wc-subcats-label i { color: var(--si-primary, #29853A); }
    .si-wc-subcats-list { display: flex; flex-wrap: wrap; gap: 8px; }
    .si-wc-subcat-item {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: var(--si-bg, #fff);
        border: 1px solid var(--si-border, #d1d5db);
        border-radius: 8px;
        font-size: 13px;
        color: var(--si-text, #374151);
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .si-wc-subcat-item:hover {
        border-color: var(--si-primary, #29853A);
        color: var(--si-primary, #29853A);
        transform: translateY(-1px);
    }
    [data-theme="dark"] .si-wc-subcat-item {
        background: #16161f;
        border-color: #2d2d3d;
        color: #d1d5db;
    }
    .si-wc-subcat-count {
        font-size: 11px;
        padding: 2px 6px;
        background: var(--si-bg-secondary, #f3f4f6);
        border-radius: 4px;
        color: var(--si-text-muted, #9ca3af);
    }
    .si-wc-subcat-more {
        font-size: 12px;
        color: var(--si-primary, #29853A);
        font-weight: 600;
    }
    
    /* ==========================================================================
       MAIN LAYOUT
       ========================================================================== */
    .si-wc-main {
        padding: 40px 0 60px;
        background: var(--si-bg, #fff);
    }
    [data-theme="dark"] .si-wc-main { background: #0c0c12; }
    .si-wc-layout {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 30px;
        align-items: start;
    }
    
    /* ==========================================================================
       SIDEBAR
       ========================================================================== */
    .si-wc-sidebar {
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
    
    .si-filter-section {
        border-bottom: 1px solid var(--si-border-light, #e9ecef);
    }
    .si-filter-section:last-child { border-bottom: none; }
    [data-theme="dark"] .si-filter-section { border-color: #252532; }
    
    .si-filter-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 16px 20px;
        font-size: 14px;
        font-weight: 700;
        color: var(--si-text, #1f2937);
        background: var(--si-bg-secondary, #fafafa);
        border-bottom: 1px solid var(--si-border-light, #f0f0f0);
    }
    [data-theme="dark"] .si-filter-header { 
        color: #e5e7eb; 
        background: #1a1a24;
        border-color: #252532;
    }
    .si-filter-header i {
        width: 16px;
        color: var(--si-primary, #29853A);
        font-size: 13px;
        text-align: center;
    }
    
    .si-filter-content {
        padding: 16px 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .si-filter-scrollable {
        max-height: 250px;
        overflow-y: auto;
    }
    
    /* Subcategory rows in sidebar */
    .si-subcat-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px;
        background: var(--si-bg-secondary, #f9fafb);
        border: 1px solid transparent;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .si-subcat-row:hover {
        border-color: var(--si-primary, #29853A);
        background: var(--si-primary-lighter, #ecfdf5);
    }
    [data-theme="dark"] .si-subcat-row { background: #1a1a24; }
    [data-theme="dark"] .si-subcat-row:hover { background: #1a2e1f; }
    .si-subcat-name {
        font-size: 14px;
        color: var(--si-text, #374151);
    }
    [data-theme="dark"] .si-subcat-name { color: #d1d5db; }
    .si-subcat-count {
        font-size: 12px;
        padding: 2px 8px;
        background: var(--si-bg, #fff);
        border-radius: 6px;
        color: var(--si-text-muted, #9ca3af);
    }
    [data-theme="dark"] .si-subcat-count { background: #252532; }
    
    /* Checkbox Filters */
    .si-filter-checkbox {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
        padding: 12px 14px;
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
        width: 20px;
        height: 20px;
        border: 2px solid var(--si-border, #d1d5db);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    .si-checkbox-box i {
        font-size: 10px;
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
        font-size: 14px;
        color: var(--si-text, #374151);
        flex: 1;
    }
    [data-theme="dark"] .si-checkbox-text { color: #d1d5db; }
    .si-checkbox-count {
        font-size: 12px;
        padding: 2px 8px;
        background: var(--si-bg, #fff);
        border-radius: 6px;
        color: var(--si-text-muted, #9ca3af);
    }
    [data-theme="dark"] .si-checkbox-count { background: #252532; }
    
    /* Rating Filter */
    .si-rating-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px;
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
    .si-rating-stars i { font-size: 13px; color: #fbbf24; }
    .si-rating-stars i.fa-regular { color: #d1d5db; }
    .si-rating-text { font-size: 13px; color: var(--si-text-secondary, #6b7280); }
    
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
        width: 22px !important;
        height: 22px !important;
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
        font-size: 13px;
        color: var(--si-text-secondary, #6b7280);
        text-align: center;
        padding: 10px;
        background: var(--si-bg-secondary, #f9fafb);
        border-radius: 8px;
    }
    [data-theme="dark"] .si-filter-content .price_slider_amount .price_label {
        background: #1a1a24;
        color: #9ca3af;
    }
    .si-filter-content .price_slider_amount .button {
        width: 100%;
        padding: 12px 16px;
        background: var(--si-primary, #29853A) !important;
        color: #fff !important;
        border: none !important;
        border-radius: 10px !important;
        font-size: 14px;
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
    .si-wc-content { min-width: 0; }
    
    /* TOOLBAR */
    .si-wc-toolbar {
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
    [data-theme="dark"] .si-wc-toolbar { background: #16161f; border-color: #252532; }
    
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
    
    .si-wc-toolbar .si-view-btn,
    .si-wc-toolbar .si-col-btn {
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
    }
    .si-wc-toolbar .si-view-btn:hover,
    .si-wc-toolbar .si-col-btn:hover { color: var(--si-primary, #29853A); }
    .si-wc-toolbar .si-view-btn.is-active,
    .si-wc-toolbar .si-col-btn.is-active {
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
    
    .si-wc-toolbar .woocommerce-ordering { display: block !important; margin: 0; }
    .si-wc-toolbar .woocommerce-ordering select {
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
    .si-wc-toolbar .woocommerce-ordering select:focus {
        outline: none;
        border-color: var(--si-primary, #29853A);
        box-shadow: 0 0 0 3px rgba(41,133,58,0.1);
    }
    [data-theme="dark"] .si-wc-toolbar .woocommerce-ordering select {
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
    .si-wc-content ul.products {
        display: grid !important;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 24px;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .si-wc-content ul.products.columns-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .si-wc-content ul.products.columns-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .si-wc-content ul.products.columns-4 { grid-template-columns: repeat(4, minmax(0, 1fr)); }
    
    .si-wc-content ul.products li.product {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        float: none !important;
    }
    
    /* List View */
    .si-wc-content ul.products.list-view {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    /* ==========================================================================
       RESPONSIVE
       ========================================================================== */
    @media (max-width: 1200px) {
        .si-wc-layout { grid-template-columns: 280px 1fr; gap: 24px; }
        .si-wc-content ul.products { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 20px; }
    }
    
    @media (max-width: 991px) {
        .si-wc-layout { grid-template-columns: 1fr; }
        .si-wc-sidebar {
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
        .si-wc-sidebar.is-active { right: 0; }
        .si-sidebar-inner { border-radius: 0; border: none; height: 100%; }
        .si-sidebar-close { display: flex; }
        .si-filter-toggle-btn { display: inline-flex; }
        .si-toolbar-center { display: none; }
        .si-wc-content ul.products { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
    }
    
    @media (max-width: 768px) {
        .si-wc-hero { padding: 30px 0; }
        .si-wc-hero-grid { grid-template-columns: 1fr; text-align: center; gap: 24px; }
        .si-wc-hero-thumb { width: 120px; height: 120px; margin: 0 auto; }
        .si-wc-hero-badges { justify-content: center; }
        .si-wc-hero-title { font-size: 24px; }
        .si-wc-hero-actions { justify-content: center; flex-wrap: wrap; }
        .si-wc-subcats { justify-content: center; }
        .si-wc-subcats-list { justify-content: center; }
        
        .si-wc-toolbar { flex-wrap: wrap; padding: 12px 16px; gap: 12px; }
        .si-toolbar-start { width: 100%; justify-content: space-between; }
        .si-toolbar-end { width: 100%; }
        .si-toolbar-sort { width: 100%; }
        .si-wc-toolbar .woocommerce-ordering select { width: 100%; }
    }
    
    @media (max-width: 480px) {
        .si-wc-content ul.products { grid-template-columns: 1fr; }
        .si-wc-sidebar { width: 100%; right: -100%; }
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
    if ( ! si_is_wc_archive() ) return;
    ?>
    <script id="si-wc-archive-js">
    (function() {
        'use strict';
        
        var sidebar = document.getElementById('si-wc-sidebar');
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
        
        // Column switcher
        var colBtns = document.querySelectorAll('.si-wc-toolbar .si-col-btn');
        var productList = document.querySelector('.si-wc-content ul.products');
        
        colBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var cols = this.getAttribute('data-cols');
                colBtns.forEach(function(b) { b.classList.remove('is-active'); });
                this.classList.add('is-active');
                if (productList) {
                    productList.classList.remove('columns-2', 'columns-3', 'columns-4');
                    productList.classList.add('columns-' + cols);
                }
                // Save preference
                localStorage.setItem('si_product_cols', cols);
            });
        });
        
        // View switcher
        var viewBtns = document.querySelectorAll('.si-wc-toolbar .si-view-btn');
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
                // Save preference
                localStorage.setItem('si_product_view', view);
            });
        });
        
        // Restore preferences
        var savedCols = localStorage.getItem('si_product_cols');
        var savedView = localStorage.getItem('si_product_view');
        
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
