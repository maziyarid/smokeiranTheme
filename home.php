/**
 * =============================================================================
 * SMOKE IRAN - Full-Width Home Page (Mobile Responsive)
 * =============================================================================
 * Snippet Name: SI-Home-Page-Responsive
 * Priority: 10
 * Location: Run Everywhere
 * =============================================================================
 */

if (!defined('ABSPATH')) exit;

function si_is_home() {
    return is_front_page() || is_page('home');
}

/**
 * =============================================================================
 * FORCE FULL-WIDTH LAYOUT
 * =============================================================================
 */
add_action('wp', function() {
    if (!si_is_home()) return;
    
    remove_action('storefront_content_top', 'woocommerce_breadcrumb', 10);
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
    remove_action('storefront_sidebar', 'storefront_get_sidebar', 10);
    
    add_filter('storefront_page_template', function() { return 'full-width'; });
    add_filter('body_class', function($classes) {
        $classes[] = 'storefront-full-width-content';
        $classes[] = 'si-home-page';
        $classes[] = 'page-template-template-fullwidth-php';
        return $classes;
    });
}, 5);

/**
 * =============================================================================
 * AUTO INSERT HOME CONTENT
 * =============================================================================
 */
add_filter('the_content', function($content) {
    if (!si_is_home()) return $content;
    if (!class_exists('WooCommerce')) {
        return '<div style="text-align:center;padding:80px 20px;">ووکامرس را نصب کنید.</div>';
    }
    ob_start();
    si_render_home();
    return ob_get_clean();
});

/**
 * =============================================================================
 * RENDER HOME PAGE
 * =============================================================================
 */
function si_render_home() {
    ?>
    <div class="si-home">
        <?php si_hero_section(); ?>
        <?php si_features_section(); ?>
        <?php si_categories_section(); ?>
        <?php si_products_section('bestseller', 'پرفروش‌ترین‌ها', 'fa-fire-flame-curved', 'popularity', '#ef4444'); ?>
        <?php si_banners_section(); ?>
        <?php si_products_section('new', 'جدیدترین محصولات', 'fa-sparkles', 'date', '#3b82f6'); ?>
        <?php si_sale_section(); ?>
        <?php si_brands_section(); ?>
        <?php si_why_choose_us_section(); ?>
        <?php si_blog_section(); ?>
    </div>
    <?php
}

/**
 * =============================================================================
 * HERO SECTION
 * =============================================================================
 */
function si_hero_section() {
    $slides = array(
        array(
            'bg' => 'linear-gradient(135deg, #0d2818 0%, #1a4d2e 30%, #2d6a4f 60%, #40916c 100%)',
            'badge' => 'پیشنهاد ویژه',
            'badge_icon' => 'fa-fire-flame-curved',
            'title' => 'بهترین محصولات با کیفیت برتر',
            'desc' => 'تجربه خریدی متفاوت با تضمین کیفیت و قیمت مناسب. ارسال سریع به سراسر کشور با بسته‌بندی ایمن.',
            'btn1' => array('text' => 'مشاهده محصولات', 'url' => wc_get_page_permalink('shop')),
            'btn2' => array('text' => 'تماس با ما', 'url' => home_url('/contact-us/')),
        ),
        array(
            'bg' => 'linear-gradient(135deg, #1e1b4b 0%, #312e81 30%, #4338ca 60%, #6366f1 100%)',
            'badge' => 'جدیدترین‌ها',
            'badge_icon' => 'fa-sparkles',
            'title' => 'محصولات جدید رسیدند!',
            'desc' => 'جدیدترین محصولات با طراحی منحصر به فرد و قیمت استثنایی. همین الان سفارش دهید.',
            'btn1' => array('text' => 'مشاهده جدیدها', 'url' => add_query_arg('orderby', 'date', wc_get_page_permalink('shop'))),
            'btn2' => array('text' => '', 'url' => ''),
        ),
        array(
            'bg' => 'linear-gradient(135deg, #4c1d2c 0%, #831843 30%, #be185d 60%, #ec4899 100%)',
            'badge' => 'تخفیف ویژه',
            'badge_icon' => 'fa-percent',
            'title' => 'فروش ویژه تا ۵۰٪ تخفیف',
            'desc' => 'فرصت محدود! محصولات منتخب با تخفیف‌های باورنکردنی. عجله کنید!',
            'btn1' => array('text' => 'مشاهده تخفیف‌ها', 'url' => add_query_arg('on_sale', 'true', wc_get_page_permalink('shop'))),
            'btn2' => array('text' => '', 'url' => ''),
        ),
        array(
            'bg' => 'linear-gradient(135deg, #134e5e 0%, #155e75 30%, #0891b2 60%, #22d3ee 100%)',
            'badge' => 'ارسال رایگان',
            'badge_icon' => 'fa-truck-fast',
            'title' => 'ارسال رایگان سراسر کشور',
            'desc' => 'برای خریدهای بالای ۵۰۰ هزار تومان، ارسال کاملاً رایگان است.',
            'btn1' => array('text' => 'شروع خرید', 'url' => wc_get_page_permalink('shop')),
            'btn2' => array('text' => '', 'url' => ''),
        ),
    );
    ?>
    <section class="si-hero">
        <div class="si-hero-slider" id="si-slider">
            <div class="si-hero-track" id="si-track">
                <?php foreach ($slides as $i => $slide) : ?>
                <div class="si-slide" style="background: <?php echo esc_attr($slide['bg']); ?>;">
                    <div class="si-slide-pattern"></div>
                    <div class="si-slide-shapes">
                        <div class="si-shape si-shape-1"></div>
                        <div class="si-shape si-shape-2"></div>
                    </div>
                    <div class="si-container">
                        <div class="si-hero-grid">
                            <div class="si-slide-content">
                                <span class="si-slide-badge">
                                    <i class="fa-solid <?php echo esc_attr($slide['badge_icon']); ?>"></i>
                                    <?php echo esc_html($slide['badge']); ?>
                                </span>
                                <h1 class="si-slide-title"><?php echo esc_html($slide['title']); ?></h1>
                                <p class="si-slide-desc"><?php echo esc_html($slide['desc']); ?></p>
                                <div class="si-slide-btns">
                                    <?php if (!empty($slide['btn1']['text'])) : ?>
                                    <a href="<?php echo esc_url($slide['btn1']['url']); ?>" class="si-slide-btn si-slide-btn-white">
                                        <?php echo esc_html($slide['btn1']['text']); ?>
                                        <i class="fa-solid fa-arrow-left"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (!empty($slide['btn2']['text'])) : ?>
                                    <a href="<?php echo esc_url($slide['btn2']['url']); ?>" class="si-slide-btn si-slide-btn-glass">
                                        <?php echo esc_html($slide['btn2']['text']); ?>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (count($slides) > 1) : ?>
            <button class="si-hero-nav si-hero-prev" id="si-prev"><i class="fa-solid fa-chevron-right"></i></button>
            <button class="si-hero-nav si-hero-next" id="si-next"><i class="fa-solid fa-chevron-left"></i></button>
            <div class="si-hero-dots" id="si-dots">
                <?php foreach ($slides as $i => $s) : ?>
                <button class="si-dot <?php echo $i === 0 ? 'is-active' : ''; ?>" data-i="<?php echo $i; ?>"></button>
                <?php endforeach; ?>
            </div>
            <div class="si-hero-progress"><div class="si-progress-bar" id="si-progress"></div></div>
            <?php endif; ?>
        </div>
    </section>
    <?php
}

/**
 * =============================================================================
 * FEATURES SECTION
 * =============================================================================
 */
function si_features_section() {
    $features = array(
        array('icon' => 'fa-truck-fast', 'color' => '#10b981', 'title' => 'ارسال سریع', 'desc' => 'به سراسر کشور'),
        array('icon' => 'fa-shield-check', 'color' => '#3b82f6', 'title' => 'ضمانت اصالت', 'desc' => '۱۰۰٪ اورجینال'),
        array('icon' => 'fa-rotate-left', 'color' => '#f59e0b', 'title' => 'بازگشت کالا', 'desc' => '۷ روز گارانتی'),
        array('icon' => 'fa-headset', 'color' => '#8b5cf6', 'title' => 'پشتیبانی ۲۴/۷', 'desc' => 'پاسخگویی سریع'),
    );
    ?>
    <section class="si-features">
        <div class="si-container">
            <div class="si-features-grid">
                <?php foreach ($features as $f) : ?>
                <div class="si-feature-card" style="--f-color: <?php echo esc_attr($f['color']); ?>;">
                    <div class="si-feature-icon">
                        <i class="fa-solid <?php echo esc_attr($f['icon']); ?>"></i>
                    </div>
                    <div class="si-feature-info">
                        <h4><?php echo esc_html($f['title']); ?></h4>
                        <p><?php echo esc_html($f['desc']); ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
}

/**
 * =============================================================================
 * CATEGORIES SECTION
 * =============================================================================
 */
function si_categories_section() {
    $cats = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'parent' => 0,
        'number' => 8,
        'orderby' => 'count',
        'order' => 'DESC',
    ));
    
    if (empty($cats) || is_wp_error($cats)) return;
    
    $colors = array('#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4', '#ef4444', '#84cc16');
    ?>
    <section class="si-section">
        <div class="si-container">
            <div class="si-section-head">
                <div class="si-section-title-wrap">
                    <span class="si-section-icon" style="--s-color: #10b981;">
                        <i class="fa-solid fa-grid-2"></i>
                    </span>
                    <div>
                        <h2 class="si-section-title">دسته‌بندی محصولات</h2>
                        <p class="si-section-subtitle">انتخاب از میان بهترین‌ها</p>
                    </div>
                </div>
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="si-section-link">
                    مشاهده همه
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>
            
            <div class="si-cats-grid">
                <?php foreach ($cats as $i => $cat) :
                    $thumb = get_term_meta($cat->term_id, 'thumbnail_id', true);
                    $img = $thumb ? wp_get_attachment_image_url($thumb, 'medium') : wc_placeholder_img_src();
                    $color = $colors[$i % count($colors)];
                ?>
                <a href="<?php echo esc_url(get_term_link($cat)); ?>" class="si-cat-card" style="--c-color: <?php echo esc_attr($color); ?>;">
                    <div class="si-cat-img">
                        <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($cat->name); ?>" loading="lazy">
                    </div>
                    <div class="si-cat-info">
                        <h3><?php echo esc_html($cat->name); ?></h3>
                        <span><?php echo number_format_i18n($cat->count); ?> محصول</span>
                    </div>
                    <div class="si-cat-arrow"><i class="fa-solid fa-arrow-left"></i></div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
}

/**
 * =============================================================================
 * PRODUCTS SECTION
 * =============================================================================
 */
function si_products_section($type, $title, $icon, $orderby, $color) {
    $products = wc_get_products(array(
        'limit' => 10,
        'status' => 'publish',
        'orderby' => $orderby,
        'order' => 'DESC',
    ));
    
    if (empty($products)) return;
    
    $link_param = ($orderby === 'popularity') ? 'popularity' : 'date';
    ?>
    <section class="si-section si-section-alt">
        <div class="si-container">
            <div class="si-section-head">
                <div class="si-section-title-wrap">
                    <span class="si-section-icon" style="--s-color: <?php echo esc_attr($color); ?>;">
                        <i class="fa-solid <?php echo esc_attr($icon); ?>"></i>
                    </span>
                    <div>
                        <h2 class="si-section-title"><?php echo esc_html($title); ?></h2>
                        <p class="si-section-subtitle"><?php echo ($type === 'bestseller') ? 'محبوب‌ترین انتخاب‌ها' : 'تازه‌ترین محصولات'; ?></p>
                    </div>
                </div>
                <a href="<?php echo esc_url(add_query_arg('orderby', $link_param, wc_get_page_permalink('shop'))); ?>" class="si-section-link">
                    مشاهده همه
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>
            
            <div class="si-products-grid">
                <?php foreach ($products as $product) {
                    si_product_card($product, $type);
                } ?>
            </div>
        </div>
    </section>
    <?php
}

/**
 * =============================================================================
 * SALE PRODUCTS SECTION
 * =============================================================================
 */
function si_sale_section() {
    $products = wc_get_products(array(
        'limit' => 10,
        'status' => 'publish',
        'on_sale' => true,
        'orderby' => 'rand',
    ));
    
    if (empty($products)) return;
    ?>
    <section class="si-sale-section">
        <div class="si-container">
            <div class="si-section-head si-section-head-light">
                <div class="si-section-title-wrap">
                    <span class="si-section-icon si-section-icon-light">
                        <i class="fa-solid fa-tags"></i>
                    </span>
                    <div>
                        <h2 class="si-section-title">تخفیف‌های ویژه</h2>
                        <p class="si-section-subtitle">فرصت محدود - عجله کنید!</p>
                    </div>
                </div>
                <a href="<?php echo esc_url(add_query_arg('on_sale', 'true', wc_get_page_permalink('shop'))); ?>" class="si-section-link si-section-link-light">
                    مشاهده همه
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </div>
            
            <div class="si-products-grid">
                <?php foreach ($products as $product) {
                    si_product_card($product, 'sale');
                } ?>
            </div>
        </div>
    </section>
    <?php
}

/**
 * =============================================================================
 * PRODUCT CARD
 * =============================================================================
 */
function si_product_card($product, $type = '') {
    if (!$product) return;
    
    $id = $product->get_id();
    $link = $product->get_permalink();
    $title = $product->get_name();
    $image = wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_thumbnail');
    if (!$image) {
        $image = wc_placeholder_img_src('woocommerce_thumbnail');
    }
    $price = $product->get_price_html();
    $stock = $product->is_in_stock();
    $rating = $product->get_average_rating();
    $count = $product->get_rating_count();
    
    $discount = 0;
    if ($product->is_on_sale() && !$product->is_type('variable')) {
        $reg = (float) $product->get_regular_price();
        $sale = (float) $product->get_sale_price();
        if ($reg > 0 && $sale > 0) {
            $discount = round((($reg - $sale) / $reg) * 100);
        }
    }
    
    $terms = get_the_terms($id, 'product_cat');
    $cat = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : '';
    $is_new = (time() - get_post_time('U', false, $id)) < (30 * DAY_IN_SECONDS);
    ?>
    <div class="si-pcard">
        <div class="si-pcard-thumb">
            <a href="<?php echo esc_url($link); ?>">
                <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>" loading="lazy">
            </a>
            
            <div class="si-pcard-badges">
                <?php if ($discount > 0) : ?>
                <span class="si-pbadge si-pbadge-sale"><?php echo $discount; ?>%−</span>
                <?php endif; ?>
                <?php if ($is_new && $type === 'new') : ?>
                <span class="si-pbadge si-pbadge-new">جدید</span>
                <?php endif; ?>
                <?php if (!$stock) : ?>
                <span class="si-pbadge si-pbadge-out">ناموجود</span>
                <?php endif; ?>
            </div>
            
            <div class="si-pcard-actions">
                <a href="<?php echo esc_url($link); ?>" class="si-paction"><i class="fa-regular fa-eye"></i></a>
                <?php if ($stock) : ?>
                <a href="<?php echo esc_url(add_query_arg('add-to-cart', $id)); ?>" class="si-paction si-paction-cart"><i class="fa-solid fa-cart-plus"></i></a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="si-pcard-body">
            <?php if ($cat) : ?>
            <span class="si-pcard-cat"><?php echo esc_html($cat); ?></span>
            <?php endif; ?>
            
            <h3 class="si-pcard-title">
                <a href="<?php echo esc_url($link); ?>"><?php echo esc_html($title); ?></a>
            </h3>
            
            <?php if ($count > 0) : ?>
            <div class="si-pcard-rating">
                <div class="si-stars">
                    <?php for ($i = 1; $i <= 5; $i++) :
                        $class = ($i <= $rating) ? 'fa-solid fa-star' : (($i - 0.5 <= $rating) ? 'fa-solid fa-star-half-stroke' : 'fa-regular fa-star');
                    ?>
                    <i class="<?php echo $class; ?>"></i>
                    <?php endfor; ?>
                </div>
                <span>(<?php echo $count; ?>)</span>
            </div>
            <?php endif; ?>
            
            <div class="si-pcard-footer">
                <div class="si-pcard-price"><?php echo $price; ?></div>
                <?php if ($stock) : ?>
                <a href="<?php echo esc_url(add_query_arg('add-to-cart', $id)); ?>" class="si-pcard-cart">
                    <i class="fa-solid fa-basket-shopping"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * =============================================================================
 * BANNERS SECTION
 * =============================================================================
 */
function si_banners_section() {
    $url = wc_get_page_permalink('shop');
    ?>
    <section class="si-section">
        <div class="si-container">
            <div class="si-banners">
                <a href="<?php echo esc_url(add_query_arg('on_sale', 'true', $url)); ?>" class="si-banner si-banner-lg si-banner-1">
                    <div class="si-banner-decor"></div>
                    <div class="si-banner-content">
                        <span class="si-banner-badge">پیشنهاد ویژه</span>
                        <h3>تخفیف‌های شگفت‌انگیز</h3>
                        <p>تا ۵۰٪ تخفیف روی محصولات منتخب</p>
                        <span class="si-banner-btn">
                            مشاهده تخفیف‌ها
                            <i class="fa-solid fa-arrow-left"></i>
                        </span>
                    </div>
                </a>
                <a href="<?php echo esc_url(add_query_arg('orderby', 'date', $url)); ?>" class="si-banner si-banner-lg si-banner-2">
                    <div class="si-banner-decor"></div>
                    <div class="si-banner-content">
                        <span class="si-banner-badge">تازه رسیده</span>
                        <h3>محصولات جدید</h3>
                        <p>جدیدترین محصولات را ببینید</p>
                        <span class="si-banner-btn">
                            مشاهده جدیدها
                            <i class="fa-solid fa-arrow-left"></i>
                        </span>
                    </div>
                </a>
            </div>
            
            <div class="si-banners-sm">
                <a href="<?php echo esc_url($url); ?>" class="si-banner si-banner-sm si-banner-3">
                    <div class="si-banner-icon"><i class="fa-solid fa-truck-fast"></i></div>
                    <div class="si-banner-text">
                        <h4>ارسال رایگان</h4>
                        <p>خرید بالای ۵۰۰ هزار</p>
                    </div>
                </a>
                <a href="<?php echo esc_url($url); ?>" class="si-banner si-banner-sm si-banner-4">
                    <div class="si-banner-icon"><i class="fa-solid fa-headset"></i></div>
                    <div class="si-banner-text">
                        <h4>پشتیبانی ۲۴/۷</h4>
                        <p>همیشه در خدمتیم</p>
                    </div>
                </a>
                <a href="<?php echo esc_url($url); ?>" class="si-banner si-banner-sm si-banner-5">
                    <div class="si-banner-icon"><i class="fa-solid fa-rotate-left"></i></div>
                    <div class="si-banner-text">
                        <h4>ضمانت بازگشت</h4>
                        <p>۷ روز گارانتی</p>
                    </div>
                </a>
                <a href="<?php echo esc_url($url); ?>" class="si-banner si-banner-sm si-banner-6">
                    <div class="si-banner-icon"><i class="fa-solid fa-shield-check"></i></div>
                    <div class="si-banner-text">
                        <h4>اصالت کالا</h4>
                        <p>۱۰۰٪ اورجینال</p>
                    </div>
                </a>
            </div>
        </div>
    </section>
    <?php
}

/**
 * =============================================================================
 * BRANDS SECTION
 * =============================================================================
 */
function si_brands_section() {
    $tax = taxonomy_exists('product_brand') ? 'product_brand' : (taxonomy_exists('pwb-brand') ? 'pwb-brand' : null);
    if (!$tax) return;
    
    $brands = get_terms(array('taxonomy' => $tax, 'hide_empty' => true, 'number' => 12));
    if (empty($brands) || is_wp_error($brands)) return;
    ?>
    <section class="si-section si-section-alt">
        <div class="si-container">
            <div class="si-section-head">
                <div class="si-section-title-wrap">
                    <span class="si-section-icon" style="--s-color: #f59e0b;">
                        <i class="fa-solid fa-award"></i>
                    </span>
                    <div>
                        <h2 class="si-section-title">برندهای محبوب</h2>
                        <p class="si-section-subtitle">همکاری با بهترین‌ها</p>
                    </div>
                </div>
            </div>
            
            <div class="si-brands-wrap">
                <div class="si-brands-track">
                    <?php 
                    $all = array_merge($brands, $brands);
                    foreach ($all as $b) :
                        $thumb = get_term_meta($b->term_id, 'thumbnail_id', true);
                        $img = $thumb ? wp_get_attachment_image_url($thumb, 'medium') : '';
                    ?>
                    <a href="<?php echo esc_url(get_term_link($b)); ?>" class="si-brand">
                        <?php if ($img) : ?>
                        <img src="<?php echo esc_url($img); ?>" alt="<?php echo esc_attr($b->name); ?>" loading="lazy">
                        <?php else : ?>
                        <span><?php echo esc_html($b->name); ?></span>
                        <?php endif; ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <?php
}

/**
 * =============================================================================
 * WHY CHOOSE US SECTION (Replaces Newsletter)
 * =============================================================================
 */
function si_why_choose_us_section() {
    $reasons = array(
        array(
            'icon' => 'fa-medal',
            'color' => '#f59e0b',
            'title' => 'کیفیت برتر',
            'desc' => 'تمامی محصولات ما از بهترین برندها و با کیفیت تضمین شده هستند.',
        ),
        array(
            'icon' => 'fa-hand-holding-dollar',
            'color' => '#10b981',
            'title' => 'قیمت مناسب',
            'desc' => 'بهترین قیمت‌ها را با تخفیف‌های ویژه و مداوم برای شما فراهم کرده‌ایم.',
        ),
        array(
            'icon' => 'fa-truck-fast',
            'color' => '#3b82f6',
            'title' => 'ارسال سریع',
            'desc' => 'سفارشات شما در کوتاه‌ترین زمان ممکن با بسته‌بندی ایمن ارسال می‌شود.',
        ),
        array(
            'icon' => 'fa-headset',
            'color' => '#8b5cf6',
            'title' => 'پشتیبانی حرفه‌ای',
            'desc' => 'تیم پشتیبانی ما ۲۴ ساعته آماده پاسخگویی به سوالات شما است.',
        ),
    );
    ?>
    <section class="si-why-section">
        <div class="si-container">
            <div class="si-why-header">
                <span class="si-why-badge">
                    <i class="fa-solid fa-star"></i>
                    چرا ما را انتخاب کنید؟
                </span>
                <h2 class="si-why-title">تجربه خریدی متفاوت و مطمئن</h2>
                <p class="si-why-desc">ما با سال‌ها تجربه در ارائه بهترین محصولات، همواره رضایت شما را در اولویت قرار داده‌ایم.</p>
            </div>
            
            <div class="si-why-grid">
                <?php foreach ($reasons as $r) : ?>
                <div class="si-why-card" style="--w-color: <?php echo esc_attr($r['color']); ?>;">
                    <div class="si-why-icon">
                        <i class="fa-solid <?php echo esc_attr($r['icon']); ?>"></i>
                    </div>
                    <h3><?php echo esc_html($r['title']); ?></h3>
                    <p><?php echo esc_html($r['desc']); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="si-why-cta">
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="si-why-btn">
                    <i class="fa-solid fa-bag-shopping"></i>
                    همین الان خرید کنید
                </a>
                <a href="<?php echo esc_url(home_url('/contact-us/')); ?>" class="si-why-btn si-why-btn-outline">
                    <i class="fa-solid fa-phone"></i>
                    تماس با ما
                </a>
            </div>
        </div>
    </section>
    <?php
}

/**
 * =============================================================================
 * BLOG SECTION
 * =============================================================================
 */
function si_blog_section() {
    $posts = get_posts(array('numberposts' => 3, 'post_status' => 'publish'));
    if (empty($posts)) return;
    ?>
    <section class="si-section">
        <div class="si-container">
            <div class="si-section-head">
                <div class="si-section-title-wrap">
                    <span class="si-section-icon" style="--s-color: #8b5cf6;">
                        <i class="fa-solid fa-newspaper"></i>
                    </span>
                    <div>
                        <h2 class="si-section-title">آخرین مقالات</h2>
                        <p class="si-section-subtitle">مطالب آموزشی و خواندنی</p>
                    </div>
                </div>
                <?php $blog = get_permalink(get_option('page_for_posts')); if ($blog) : ?>
                <a href="<?php echo esc_url($blog); ?>" class="si-section-link">
                    مشاهده همه
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <?php endif; ?>
            </div>
            
            <div class="si-blog-grid">
                <?php foreach ($posts as $p) :
                    $cats = get_the_category($p->ID);
                    $cat = !empty($cats) ? $cats[0]->name : 'مقاله';
                ?>
                <article class="si-blog-card">
                    <a href="<?php echo esc_url(get_permalink($p)); ?>" class="si-blog-thumb">
                        <?php if (has_post_thumbnail($p)) : ?>
                        <?php echo get_the_post_thumbnail($p, 'medium_large', array('loading' => 'lazy')); ?>
                        <?php else : ?>
                        <img src="<?php echo wc_placeholder_img_src(); ?>" alt="" loading="lazy">
                        <?php endif; ?>
                        <span class="si-blog-cat"><?php echo esc_html($cat); ?></span>
                    </a>
                    <div class="si-blog-body">
                        <div class="si-blog-meta">
                            <span><i class="fa-regular fa-calendar"></i> <?php echo get_the_date('j F Y', $p); ?></span>
                        </div>
                        <h3><a href="<?php echo esc_url(get_permalink($p)); ?>"><?php echo esc_html(get_the_title($p)); ?></a></h3>
                        <p><?php echo esc_html(wp_trim_words($p->post_excerpt ?: $p->post_content, 16, '...')); ?></p>
                    </div>
                </article>
                <?php endforeach; wp_reset_postdata(); ?>
            </div>
        </div>
    </section>
    <?php
}

/**
 * =============================================================================
 * CSS STYLES - FULL WIDTH + MOBILE RESPONSIVE + FIXED PRODUCT WIDTH
 * =============================================================================
 */
add_action('wp_head', function() {
    if (!si_is_home()) return;
    ?>
    <style id="si-home-styles">
    /* ==========================================================================
       CRITICAL: FULL-WIDTH + PARENT OVERRIDES
       ========================================================================== */
    .si-home-page #content,
    .si-home-page .site-content,
    .si-home-page .content-area,
    .si-home-page #primary,
    .si-home-page #main,
    .si-home-page .site-main,
    .si-home-page .hentry,
    .si-home-page article.page,
    .si-home-page .entry-content,
    .si-home-page .page .entry-content,
    .si-home-page .type-page .entry-content {
        max-width: 100% !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
        float: none !important;
    }
    
    .si-home-page .site-main {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    
    .si-home-page .col-full {
        max-width: 100% !important;
        padding: 0 !important;
    }
    
    .si-home-page .page-header,
    .si-home-page .entry-header,
    .si-home-page #secondary,
    .si-home-page .storefront-breadcrumb,
    .si-home-page .woocommerce-breadcrumb {
        display: none !important;
    }
    
    /* ==========================================================================
       BASE STYLES
       ========================================================================== */
    .si-home {
        width: 100%;
        max-width: 100%;
        overflow-x: hidden;
        background: var(--si-bg, #fff);
    }
    [data-theme="dark"] .si-home { background: #0a0a0f; }
    
    .si-home *, .si-home *::before, .si-home *::after { box-sizing: border-box; }
    
    /* Container */
    .si-home .si-container {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 16px;
    }
    
    /* ==========================================================================
       FULL-WIDTH SECTIONS
       ========================================================================== */
    .si-hero,
    .si-sale-section,
    .si-why-section {
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
    }
    
    /* ==========================================================================
       HERO SLIDER - MOBILE RESPONSIVE
       ========================================================================== */
    .si-hero {
        overflow: hidden;
    }
    
    .si-hero-slider {
        position: relative;
        width: 100%;
        overflow: hidden;
    }
    
    .si-hero-track {
        display: flex;
        transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .si-slide {
        flex: 0 0 100%;
        min-width: 100%;
        min-height: 400px;
        position: relative;
        display: flex;
        align-items: center;
        overflow: hidden;
        padding: 40px 0;
    }
    
    .si-slide-pattern {
        position: absolute;
        inset: 0;
        background: 
            radial-gradient(circle at 20% 80%, rgba(255,255,255,0.08) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255,255,255,0.06) 0%, transparent 40%);
        pointer-events: none;
    }
    
    .si-slide-shapes {
        position: absolute;
        inset: 0;
        pointer-events: none;
        overflow: hidden;
    }
    
    .si-shape {
        position: absolute;
        border-radius: 50%;
        background: rgba(255,255,255,0.03);
        animation: si-float 20s ease-in-out infinite;
    }
    .si-shape-1 { width: 300px; height: 300px; top: -100px; right: -50px; }
    .si-shape-2 { width: 200px; height: 200px; bottom: -50px; left: -30px; animation-delay: -5s; }
    
    @keyframes si-float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        50% { transform: translate(20px, -20px) scale(1.1); }
    }
    
    .si-hero-grid {
        display: block;
        position: relative;
        z-index: 10;
    }
    
    .si-slide-content { 
        color: #fff;
        max-width: 100%;
    }
    
    .si-slide-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        background: rgba(255,255,255,0.12);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 60px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 20px;
    }
    
    .si-slide-title {
        font-size: 26px;
        font-weight: 800;
        line-height: 1.3;
        margin: 0 0 16px;
        color: #fff;
    }
    
    .si-slide-desc {
        font-size: 14px;
        line-height: 1.8;
        color: rgba(255,255,255,0.85);
        margin: 0 0 24px;
    }
    
    .si-slide-btns {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    
    .si-slide-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 14px 24px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        width: 100%;
    }
    
    .si-slide-btn-white {
        background: #fff;
        color: #1a1a24;
        box-shadow: 0 8px 30px rgba(0,0,0,0.2);
    }
    .si-slide-btn-white:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.3);
        color: #1a1a24;
    }
    
    .si-slide-btn-glass {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255,255,255,0.25);
        color: #fff;
    }
    .si-slide-btn-glass:hover {
        background: rgba(255,255,255,0.2);
        border-color: rgba(255,255,255,0.4);
        color: #fff;
    }
    
    /* Nav Buttons - Hidden on Mobile */
    .si-hero-nav {
        display: none;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(20px);
        border: 2px solid rgba(255,255,255,0.15);
        border-radius: 50%;
        color: #fff;
        font-size: 18px;
        cursor: pointer;
        z-index: 20;
        transition: all 0.3s ease;
    }
    .si-hero-nav:hover {
        background: #fff;
        color: #1a1a24;
        transform: translateY(-50%) scale(1.1);
    }
    .si-hero-prev { right: 20px; }
    .si-hero-next { left: 20px; }
    
    /* Dots */
    .si-hero-dots {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 10px;
        z-index: 20;
    }
    .si-dot {
        width: 10px;
        height: 10px;
        padding: 0;
        background: rgba(255,255,255,0.3);
        border: none;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .si-dot:hover { background: rgba(255,255,255,0.6); }
    .si-dot.is-active {
        background: #fff;
        width: 30px;
        border-radius: 5px;
    }
    
    /* Progress */
    .si-hero-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: rgba(255,255,255,0.1);
    }
    .si-progress-bar {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, #fff, rgba(255,255,255,0.5));
        transition: width 0.1s linear;
    }
    
    /* ==========================================================================
       FEATURES - MOBILE RESPONSIVE
       ========================================================================== */
    .si-features {
        background: var(--si-surface, #fff);
        border-bottom: 1px solid var(--si-border-light, #e5e7eb);
        padding: 0;
    }
    [data-theme="dark"] .si-features {
        background: #111118;
        border-color: #1f2937;
    }
    
    .si-features-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0;
    }
    
    .si-feature-card {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 20px 16px;
        border-bottom: 1px solid var(--si-border-light, #e5e7eb);
        transition: all 0.3s ease;
    }
    .si-feature-card:nth-child(odd) {
        border-left: 1px solid var(--si-border-light, #e5e7eb);
    }
    [data-theme="dark"] .si-feature-card { border-color: #1f2937; }
    
    .si-feature-card:hover { background: var(--si-bg-secondary, #f9fafb); }
    [data-theme="dark"] .si-feature-card:hover { background: #1a1a24; }
    
    .si-feature-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--f-color);
        border-radius: 12px;
        color: #fff;
        font-size: 18px;
        flex-shrink: 0;
    }
    
    .si-feature-info h4 {
        font-size: 14px;
        font-weight: 700;
        color: var(--si-text, #1f2937);
        margin: 0 0 2px;
    }
    [data-theme="dark"] .si-feature-info h4 { color: #f3f4f6; }
    .si-feature-info p {
        font-size: 12px;
        color: var(--si-text-secondary, #6b7280);
        margin: 0;
    }
    
    /* ==========================================================================
       SECTIONS - MOBILE RESPONSIVE
       ========================================================================== */
    .si-section {
        padding: 50px 0;
        background: var(--si-bg, #fff);
    }
    [data-theme="dark"] .si-section { background: #0a0a0f; }
    
    .si-section-alt { background: var(--si-bg-secondary, #f8fafc); }
    [data-theme="dark"] .si-section-alt { background: #0f0f15; }
    
    .si-section-head {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 30px;
    }
    
    .si-section-title-wrap {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    
    .si-section-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--s-color);
        border-radius: 12px;
        color: #fff;
        font-size: 18px;
        flex-shrink: 0;
    }
    
    .si-section-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--si-text, #1f2937);
        margin: 0;
    }
    [data-theme="dark"] .si-section-title { color: #f3f4f6; }
    
    .si-section-subtitle {
        font-size: 13px;
        color: var(--si-text-secondary, #6b7280);
        margin: 2px 0 0;
    }
    
    .si-section-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        background: var(--si-surface, #fff);
        border: 2px solid var(--si-border, #e5e7eb);
        border-radius: 50px;
        font-size: 14px;
        font-weight: 600;
        color: var(--si-text, #1f2937);
        text-decoration: none;
        transition: all 0.3s ease;
    }
    [data-theme="dark"] .si-section-link {
        background: #16161f;
        border-color: #252532;
        color: #e5e7eb;
    }
    .si-section-link:hover {
        background: var(--si-primary, #29853A);
        border-color: var(--si-primary, #29853A);
        color: #fff;
        gap: 12px;
    }
    
    /* Light variants */
    .si-section-head-light .si-section-title { color: #fff; }
    .si-section-head-light .si-section-subtitle { color: rgba(255,255,255,0.7); }
    .si-section-icon-light {
        background: rgba(255,255,255,0.15) !important;
    }
    .si-section-link-light {
        background: rgba(255,255,255,0.1);
        border-color: rgba(255,255,255,0.2);
        color: #fff;
    }
    .si-section-link-light:hover {
        background: #fff;
        border-color: #fff;
        color: #1a1a24;
    }
    
    /* ==========================================================================
       CATEGORIES GRID - MOBILE RESPONSIVE
       ========================================================================== */
    .si-cats-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .si-cat-card {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px;
        background: var(--si-surface, #fff);
        border: 2px solid var(--si-border-light, #e5e7eb);
        border-radius: 16px;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    [data-theme="dark"] .si-cat-card {
        background: #16161f;
        border-color: #252532;
    }
    .si-cat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--c-color);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    .si-cat-card:hover::before { transform: scaleY(1); }
    .si-cat-card:hover {
        border-color: var(--c-color);
        transform: translateY(-4px);
    }
    
    .si-cat-img {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        overflow: hidden;
        background: var(--si-bg-secondary, #f8fafc);
        flex-shrink: 0;
        border: 2px solid var(--si-border-light, #e5e7eb);
    }
    [data-theme="dark"] .si-cat-img {
        background: #1a1a24;
        border-color: #252532;
    }
    .si-cat-card:hover .si-cat-img { border-color: var(--c-color); }
    .si-cat-img img { width: 100%; height: 100%; object-fit: cover; }
    
    .si-cat-info { flex: 1; min-width: 0; }
    .si-cat-info h3 {
        font-size: 15px;
        font-weight: 700;
        color: var(--si-text, #1f2937);
        margin: 0 0 4px;
    }
    [data-theme="dark"] .si-cat-info h3 { color: #f3f4f6; }
    .si-cat-info span {
        font-size: 12px;
        color: var(--si-text-secondary, #6b7280);
    }
    
    .si-cat-arrow {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--si-bg-secondary, #f3f4f6);
        border-radius: 50%;
        color: var(--si-text-secondary, #9ca3af);
        font-size: 12px;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    [data-theme="dark"] .si-cat-arrow { background: #1a1a24; }
    .si-cat-card:hover .si-cat-arrow {
        background: var(--c-color);
        color: #fff;
        transform: translateX(-4px);
    }
    
    /* ==========================================================================
       PRODUCTS GRID - FIXED WIDTH + MOBILE RESPONSIVE
       ========================================================================== */
    .si-products-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    /* CRITICAL: Product Card Fixed Width */
    .si-pcard {
        width: 100%;
        min-width: 150px;
        max-width: 100%;
        background: var(--si-surface, #fff);
        border: 2px solid var(--si-border-light, #e5e7eb);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.4s ease;
    }
    [data-theme="dark"] .si-pcard {
        background: #16161f;
        border-color: #252532;
    }
    .si-pcard:hover {
        border-color: var(--si-primary, #29853A);
        transform: translateY(-4px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    }
    [data-theme="dark"] .si-pcard:hover {
        box-shadow: 0 15px 40px rgba(0,0,0,0.4);
    }
    
    .si-pcard-thumb {
        position: relative;
        width: 100%;
        aspect-ratio: 1;
        overflow: hidden;
        background: var(--si-bg-secondary, #f8fafc);
    }
    [data-theme="dark"] .si-pcard-thumb { background: #1a1a24; }
    .si-pcard-thumb a { 
        display: block; 
        width: 100%; 
        height: 100%; 
    }
    .si-pcard-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .si-pcard:hover .si-pcard-thumb img { transform: scale(1.08); }
    
    .si-pcard-badges {
        position: absolute;
        top: 10px;
        right: 10px;
        display: flex;
        flex-direction: column;
        gap: 6px;
        z-index: 5;
    }
    .si-pbadge {
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        text-align: center;
    }
    .si-pbadge-sale { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; }
    .si-pbadge-new { background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: #fff; }
    .si-pbadge-out { background: #6b7280; color: #fff; }
    
    /* Hide actions on mobile */
    .si-pcard-actions {
        display: none;
    }
    
    .si-pcard-body { padding: 14px; }
    
    .si-pcard-cat {
        display: inline-block;
        font-size: 11px;
        font-weight: 600;
        color: var(--si-primary, #29853A);
        margin-bottom: 6px;
    }
    
    .si-pcard-title {
        font-size: 13px;
        font-weight: 600;
        line-height: 1.5;
        margin: 0 0 8px;
        height: 40px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    .si-pcard-title a {
        color: var(--si-text, #1f2937);
        text-decoration: none;
        transition: color 0.2s ease;
    }
    [data-theme="dark"] .si-pcard-title a { color: #f3f4f6; }
    .si-pcard-title a:hover { color: var(--si-primary, #29853A); }
    
    .si-pcard-rating {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 10px;
    }
    .si-stars { display: flex; gap: 1px; font-size: 11px; }
    .si-stars i { color: #fbbf24; }
    .si-stars i.fa-regular { color: #d1d5db; }
    [data-theme="dark"] .si-stars i.fa-regular { color: #4b5563; }
    .si-pcard-rating span { font-size: 11px; color: var(--si-text-secondary, #6b7280); }
    
    .si-pcard-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }
    .si-pcard-price { display: flex; flex-direction: column; gap: 2px; }
    .si-pcard-price del { font-size: 11px; color: var(--si-text-secondary, #9ca3af); }
    .si-pcard-price ins,
    .si-pcard-price .woocommerce-Price-amount {
        font-size: 14px;
        font-weight: 700;
        color: var(--si-primary, #29853A);
        text-decoration: none;
    }
    
    .si-pcard-cart {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--si-primary, #29853A);
        border: none;
        border-radius: 10px;
        color: #fff;
        font-size: 16px;
        text-decoration: none;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    .si-pcard-cart:hover {
        background: var(--si-primary-dark, #1e6b2d);
        transform: scale(1.05);
        color: #fff;
    }
    
    /* ==========================================================================
       SALE SECTION - MOBILE RESPONSIVE
       ========================================================================== */
    .si-sale-section {
        padding: 50px 0;
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
        overflow: hidden;
    }
    
    .si-sale-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
    }
    
    .si-sale-section > .si-container {
        position: relative;
        z-index: 5;
    }
    
    /* ==========================================================================
       BANNERS - MOBILE RESPONSIVE
       ========================================================================== */
    .si-banners {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }
    
    .si-banner {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        text-decoration: none;
        transition: all 0.4s ease;
    }
    .si-banner:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.2);
    }
    
    .si-banner-lg { min-height: 200px; display: flex; align-items: flex-end; }
    
    .si-banner-1 { background: linear-gradient(135deg, #6366f1 0%, #4f46e5 50%, #3730a3 100%); }
    .si-banner-2 { background: linear-gradient(135deg, #ec4899 0%, #db2777 50%, #9d174d 100%); }
    
    .si-banner-decor {
        position: absolute;
        inset: 0;
        background: 
            radial-gradient(circle at 70% 30%, rgba(255,255,255,0.15) 0%, transparent 50%),
            radial-gradient(circle at 30% 70%, rgba(255,255,255,0.1) 0%, transparent 40%);
    }
    
    .si-banner-content {
        position: relative;
        z-index: 5;
        padding: 24px;
        color: #fff;
        width: 100%;
    }
    
    .si-banner-badge {
        display: inline-block;
        padding: 6px 14px;
        background: rgba(255,255,255,0.2);
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 14px;
        color: #fff;
    }
    
    .si-banner-content h3,
    .si-banner-content h4 {
        color: #fff;
    }
    .si-banner-content h3 {
        font-size: 20px;
        font-weight: 800;
        margin: 0 0 8px;
    }
    .si-banner-content h4 {
        font-size: 16px;
        font-weight: 700;
        margin: 0 0 4px;
    }
    .si-banner-content p {
        font-size: 13px;
        margin: 0 0 16px;
        color: rgba(255,255,255,0.9);
    }
    
    .si-banner-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        background: #fff;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        color: #1f2937;
        transition: all 0.3s ease;
    }
    .si-banner:hover .si-banner-btn {
        gap: 12px;
    }
    
    /* Small Banners */
    .si-banners-sm {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .si-banner-sm {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        min-height: auto;
    }
    
    .si-banner-3 { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); }
    .si-banner-4 { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
    .si-banner-5 { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .si-banner-6 { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    
    .si-banner-icon {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.2);
        border-radius: 12px;
        font-size: 18px;
        color: #fff;
        flex-shrink: 0;
    }
    
    .si-banner-text h4 {
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        margin: 0 0 2px;
    }
    .si-banner-text p {
        font-size: 11px;
        color: rgba(255,255,255,0.9);
        margin: 0;
    }
    
    /* ==========================================================================
       BRANDS - MOBILE RESPONSIVE
       ========================================================================== */
    .si-brands-wrap { overflow: hidden; }
    
    .si-brands-track {
        display: flex;
        gap: 16px;
        animation: si-brands 40s linear infinite;
    }
    .si-brands-track:hover { animation-play-state: paused; }
    
    @keyframes si-brands {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    
    .si-brand {
        flex-shrink: 0;
        width: 140px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--si-surface, #fff);
        border: 2px solid var(--si-border-light, #e5e7eb);
        border-radius: 14px;
        padding: 16px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    [data-theme="dark"] .si-brand {
        background: #16161f;
        border-color: #252532;
    }
    .si-brand:hover {
        border-color: var(--si-primary, #29853A);
    }
    .si-brand img {
        max-width: 100%;
        max-height: 50px;
        object-fit: contain;
        filter: grayscale(100%);
        opacity: 0.5;
        transition: all 0.3s ease;
    }
    .si-brand:hover img { filter: grayscale(0%); opacity: 1; }
    .si-brand span {
        font-size: 14px;
        font-weight: 700;
        color: var(--si-text-secondary, #6b7280);
        transition: color 0.2s ease;
    }
    .si-brand:hover span { color: var(--si-primary, #29853A); }
    
    /* ==========================================================================
       WHY CHOOSE US SECTION (Replaces Newsletter)
       ========================================================================== */
    .si-why-section {
        padding: 60px 0;
        background: linear-gradient(135deg, #1a1a24 0%, #0a0a0f 100%);
        overflow: hidden;
    }
    
    .si-why-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background: 
            radial-gradient(circle at 20% 50%, rgba(41,133,58,0.12) 0%, transparent 40%),
            radial-gradient(circle at 80% 50%, rgba(99,102,241,0.08) 0%, transparent 40%);
        pointer-events: none;
    }
    
    .si-why-section > .si-container {
        position: relative;
        z-index: 5;
    }
    
    .si-why-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .si-why-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: rgba(41,133,58,0.15);
        border: 1px solid rgba(41,133,58,0.3);
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        color: #10b981;
        margin-bottom: 20px;
    }
    
    .si-why-title {
        font-size: 24px;
        font-weight: 800;
        color: #fff;
        margin: 0 0 12px;
    }
    
    .si-why-desc {
        font-size: 14px;
        color: rgba(255,255,255,0.6);
        margin: 0 auto;
        max-width: 500px;
        line-height: 1.7;
    }
    
    .si-why-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 40px;
    }
    
    .si-why-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 16px;
        padding: 24px 20px;
        text-align: center;
        transition: all 0.3s ease;
    }
    .si-why-card:hover {
        background: rgba(255,255,255,0.06);
        border-color: var(--w-color);
        transform: translateY(-4px);
    }
    
    .si-why-icon {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--w-color);
        border-radius: 14px;
        margin: 0 auto 16px;
        font-size: 22px;
        color: #fff;
    }
    
    .si-why-card h3 {
        font-size: 16px;
        font-weight: 700;
        color: #fff;
        margin: 0 0 8px;
    }
    
    .si-why-card p {
        font-size: 13px;
        color: rgba(255,255,255,0.6);
        margin: 0;
        line-height: 1.6;
    }
    
    .si-why-cta {
        display: flex;
        flex-direction: column;
        gap: 12px;
        justify-content: center;
        align-items: center;
    }
    
    .si-why-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 16px 28px;
        background: var(--si-primary, #29853A);
        border: 2px solid var(--si-primary, #29853A);
        border-radius: 12px;
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        text-decoration: none;
        transition: all 0.3s ease;
        width: 100%;
        max-width: 280px;
    }
    .si-why-btn:hover {
        background: var(--si-primary-dark, #1e6b2d);
        border-color: var(--si-primary-dark, #1e6b2d);
        transform: translateY(-2px);
        color: #fff;
    }
    
    .si-why-btn-outline {
        background: transparent;
        border-color: rgba(255,255,255,0.2);
        color: #fff;
    }
    .si-why-btn-outline:hover {
        background: rgba(255,255,255,0.1);
        border-color: rgba(255,255,255,0.3);
    }
    
    /* ==========================================================================
       BLOG - MOBILE RESPONSIVE
       ========================================================================== */
    .si-blog-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .si-blog-card {
        background: var(--si-surface, #fff);
        border: 2px solid var(--si-border-light, #e5e7eb);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.4s ease;
    }
    [data-theme="dark"] .si-blog-card {
        background: #16161f;
        border-color: #252532;
    }
    .si-blog-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    }
    [data-theme="dark"] .si-blog-card:hover {
        box-shadow: 0 15px 40px rgba(0,0,0,0.4);
    }
    
    .si-blog-thumb {
        position: relative;
        aspect-ratio: 16/10;
        overflow: hidden;
        display: block;
    }
    .si-blog-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .si-blog-card:hover .si-blog-thumb img { transform: scale(1.08); }
    
    .si-blog-cat {
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 6px 14px;
        background: var(--si-primary, #29853A);
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
        color: #fff;
    }
    
    .si-blog-body { padding: 20px; }
    
    .si-blog-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }
    .si-blog-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        color: var(--si-text-secondary, #6b7280);
    }
    .si-blog-meta i { color: var(--si-primary, #29853A); }
    
    .si-blog-body h3 {
        font-size: 16px;
        font-weight: 700;
        line-height: 1.5;
        margin: 0 0 10px;
    }
    .si-blog-body h3 a {
        color: var(--si-text, #1f2937);
        text-decoration: none;
        transition: color 0.2s ease;
    }
    [data-theme="dark"] .si-blog-body h3 a { color: #f3f4f6; }
    .si-blog-body h3 a:hover { color: var(--si-primary, #29853A); }
    
    .si-blog-body p {
        font-size: 13px;
        line-height: 1.7;
        color: var(--si-text-secondary, #6b7280);
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* ==========================================================================
       TABLET RESPONSIVE (min-width: 768px)
       ========================================================================== */
    @media (min-width: 768px) {
        .si-home .si-container { padding: 0 24px; }
        
        /* Hero */
        .si-slide {
            min-height: 480px;
            padding: 60px 0;
        }
        .si-slide-title { font-size: 36px; }
        .si-slide-desc { font-size: 16px; }
        .si-slide-btns { 
            flex-direction: row; 
            flex-wrap: wrap;
        }
        .si-slide-btn { width: auto; }
        .si-hero-nav { display: flex; }
        .si-dot { width: 12px; height: 12px; }
        .si-dot.is-active { width: 40px; }
        
        /* Features */
        .si-features-grid { grid-template-columns: repeat(4, 1fr); }
        .si-feature-card {
            border-bottom: none;
            border-left: 1px solid var(--si-border-light, #e5e7eb);
            padding: 28px 20px;
        }
        .si-feature-card:last-child { border-left: none; }
        .si-feature-card:nth-child(odd) { border-left: 1px solid var(--si-border-light, #e5e7eb); }
        
        /* Sections */
        .si-section { padding: 60px 0; }
        .si-section-head {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
        
        /* Categories */
        .si-cats-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; }
        
        /* Products - 3 columns on tablet */
        .si-products-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }
        .si-pcard { min-width: 180px; }
        .si-pcard-body { padding: 18px; }
        .si-pcard-title { font-size: 14px; height: 44px; }
        .si-pcard-price ins,
        .si-pcard-price .woocommerce-Price-amount { font-size: 15px; }
        .si-pcard-cart { width: 44px; height: 44px; }
        
        /* Banners */
        .si-banners { grid-template-columns: repeat(2, 1fr); }
        .si-banner-lg { min-height: 240px; }
        .si-banners-sm { grid-template-columns: repeat(4, 1fr); }
        
        /* Brands */
        .si-brand { width: 160px; height: 90px; }
        
        /* Why Section */
        .si-why-section { padding: 70px 0; }
        .si-why-title { font-size: 28px; }
        .si-why-grid { grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .si-why-cta {
            flex-direction: row;
        }
        .si-why-btn { width: auto; }
        
        /* Blog */
        .si-blog-grid { grid-template-columns: repeat(2, 1fr); }
    }
    
    /* ==========================================================================
       DESKTOP RESPONSIVE (min-width: 1024px)
       ========================================================================== */
    @media (min-width: 1024px) {
        .si-home .si-container { padding: 0 30px; }
        
        /* Hero */
        .si-slide {
            min-height: 560px;
        }
        .si-slide-badge { padding: 12px 24px; font-size: 14px; }
        .si-slide-title { font-size: 46px; }
        .si-slide-desc { font-size: 17px; max-width: 550px; }
        .si-slide-btn { padding: 18px 36px; font-size: 16px; }
        .si-hero-nav { width: 56px; height: 56px; font-size: 20px; }
        .si-hero-prev { right: 30px; }
        .si-hero-next { left: 30px; }
        
        /* Sections */
        .si-section { padding: 70px 0; }
        .si-section-icon { width: 56px; height: 56px; font-size: 22px; }
        .si-section-title { font-size: 26px; }
        
        /* Categories */
        .si-cats-grid { grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .si-cat-card { padding: 20px 24px; }
        .si-cat-img { width: 72px; height: 72px; }
        
        /* Products - 4 columns on desktop */
        .si-products-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 22px;
        }
        .si-pcard { min-width: 220px; border-radius: 20px; }
        .si-pcard-body { padding: 20px; }
        .si-pcard-title { font-size: 15px; height: 46px; }
        .si-pcard-actions { display: flex; }
        .si-pcard-price ins,
        .si-pcard-price .woocommerce-Price-amount { font-size: 16px; }
        .si-pcard-cart { width: 48px; height: 48px; font-size: 18px; border-radius: 12px; }
        
        /* Banners */
        .si-banner-lg { min-height: 280px; }
        .si-banner-content { padding: 32px; }
        .si-banner-content h3 { font-size: 24px; }
        
        /* Blog */
        .si-blog-grid { grid-template-columns: repeat(3, 1fr); gap: 24px; }
        .si-blog-body h3 { font-size: 18px; }
    }
    
    /* ==========================================================================
       LARGE DESKTOP (min-width: 1280px)
       ========================================================================== */
    @media (min-width: 1280px) {
        .si-home .si-container { padding: 0 40px; }
        
        /* Hero */
        .si-slide { min-height: 600px; }
        .si-slide-title { font-size: 52px; }
        .si-hero-prev { right: 40px; }
        .si-hero-next { left: 40px; }
        
        /* Products - 5 columns on large desktop */
        .si-products-grid {
            grid-template-columns: repeat(5, 1fr);
            gap: 24px;
        }
        .si-pcard { min-width: 240px; border-radius: 24px; }
        .si-pcard-body { padding: 22px; }
        .si-pcard-title { height: 48px; }
        .si-pcard-price ins,
        .si-pcard-price .woocommerce-Price-amount { font-size: 17px; }
        .si-pcard-cart { width: 50px; height: 50px; font-size: 20px; border-radius: 14px; }
        
        /* Sale Section */
        .si-sale-section { padding: 70px 0; }
        
        /* Brands */
        .si-brand { width: 200px; height: 110px; }
        
        /* Why Section */
        .si-why-section { padding: 80px 0; }
        .si-why-title { font-size: 32px; }
    }
    </style>
    <?php
}, 99);

/**
 * =============================================================================
 * JAVASCRIPT
 * =============================================================================
 */
add_action('wp_footer', function() {
    if (!si_is_home()) return;
    ?>
    <script id="si-home-js">
    (function() {
        'use strict';
        
        var track = document.getElementById('si-track');
        var dots = document.querySelectorAll('.si-dot');
        var prev = document.getElementById('si-prev');
        var next = document.getElementById('si-next');
        var progress = document.getElementById('si-progress');
        
        if (!track || dots.length === 0) return;
        
        var current = 0;
        var total = dots.length;
        var interval = null;
        var paused = false;
        var duration = 7000;
        var startTime = Date.now();
        
        function goTo(i) {
            if (i < 0) i = total - 1;
            if (i >= total) i = 0;
            current = i;
            track.style.transform = 'translateX(' + (current * 100) + '%)';
            dots.forEach(function(d, idx) { d.classList.toggle('is-active', idx === current); });
            startTime = Date.now();
        }
        
        function updateProgress() {
            if (paused || !progress) return;
            var elapsed = Date.now() - startTime;
            var pct = Math.min((elapsed / duration) * 100, 100);
            progress.style.width = pct + '%';
            if (pct >= 100) goTo(current + 1);
        }
        
        function startAuto() { interval = setInterval(updateProgress, 50); }
        
        if (prev) prev.addEventListener('click', function() { goTo(current - 1); });
        if (next) next.addEventListener('click', function() { goTo(current + 1); });
        
        dots.forEach(function(d) {
            d.addEventListener('click', function() { goTo(parseInt(this.dataset.i)); });
        });
        
        track.addEventListener('mouseenter', function() { paused = true; });
        track.addEventListener('mouseleave', function() { paused = false; startTime = Date.now() - (progress ? parseFloat(progress.style.width || 0) / 100 * duration : 0); });
        
        // Touch swipe support
        var touchX = 0;
        var touchY = 0;
        track.addEventListener('touchstart', function(e) { 
            touchX = e.changedTouches[0].screenX; 
            touchY = e.changedTouches[0].screenY;
            paused = true; 
        }, {passive: true});
        
        track.addEventListener('touchend', function(e) {
            var diffX = touchX - e.changedTouches[0].screenX;
            var diffY = touchY - e.changedTouches[0].screenY;
            
            // Only swipe if horizontal movement is greater than vertical
            if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                if (diffX > 0) {
                    goTo(current + 1);
                } else {
                    goTo(current - 1);
                }
            }
            paused = false;
            startTime = Date.now();
        }, {passive: true});
        
        startAuto();
    })();
    </script>
    <?php
}, 99);
