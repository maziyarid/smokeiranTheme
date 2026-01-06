/**
 * ═══════════════════════════════════════════════════════════════════════════
 * PROFESSIONAL SINGLE PRODUCT TEMPLATE - ENHANCED WITH SHORTCODES
 * Version: 4.0.0
 * ═══════════════════════════════════════════════════════════════════════════
 * 
 * SHORTCODES INCLUDED (17 Total):
 * [fsp_info]           - Info/alert boxes (success, warning, error, info, tip)
 * [fsp_features]       - Feature icon lists
 * [fsp_highlight]      - Text highlights (marker, underline, glow)
 * [fsp_accordion]      - Collapsible content sections
 * [fsp_columns]        - Multi-column layouts
 * [fsp_cta]            - Call-to-action boxes
 * [fsp_button]         - Styled buttons
 * [fsp_badge]          - Custom badges/labels
 * [fsp_gallery]        - In-content image galleries
 * [fsp_video]          - Video embeds (YouTube, Aparat)
 * [fsp_specs]          - Specifications tables
 * [fsp_faq]            - FAQ sections with Schema.org
 * [fsp_comparison]     - Product comparison tables
 * [fsp_testimonial]    - Customer testimonials
 * [fsp_countdown]      - Sale countdown timers
 * [fsp_tabs]           - Custom tabbed content
 * [fsp_trust]          - Trust/benefit icons
 * 
 * UI/UX IMPROVEMENTS:
 * ✅ Dark mode support
 * ✅ Smooth animations & micro-interactions
 * ✅ Better mobile touch interactions
 * ✅ Accessibility (ARIA, focus states)
 * ✅ Loading states & skeleton screens
 * ✅ Improved typography & spacing
 * ✅ Better color system with CSS variables
 * ✅ Toast notifications
 * ✅ Sticky elements behavior
 * ✅ Image lazy loading with blur-up
 * 
 * ═══════════════════════════════════════════════════════════════════════════
 */

if (!defined('ABSPATH')) {
    exit;
}

/* ═══════════════════════════════════════════════════════════════════════════
   SECTION 1: REMOVE DEFAULT WOOCOMMERCE OUTPUT
   ═══════════════════════════════════════════════════════════════════════════ */

remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
remove_action('storefront_before_content', 'woocommerce_breadcrumb', 10);

/* ═══════════════════════════════════════════════════════════════════════════
   SECTION 2: SHORTCODE DEFINITIONS (17 SHORTCODES)
   ═══════════════════════════════════════════════════════════════════════════ */

/**
 * -----------------------------------------------------------------------------
 * [fsp_info] - Info/Alert Box Shortcode
 * -----------------------------------------------------------------------------
 * Usage:
 * [fsp_info type="success"]Message here[/fsp_info]
 * [fsp_info type="warning" title="Warning!" dismissible="true"]Content[/fsp_info]
 * Types: success, warning, error, info, tip
 */
function fsp_info_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'type' => 'info',
        'title' => '',
        'icon' => '',
        'dismissible' => 'false'
    ), $atts, 'fsp_info');
    
    $icons = array(
        'success' => 'fa-circle-check',
        'warning' => 'fa-triangle-exclamation',
        'error' => 'fa-circle-xmark',
        'info' => 'fa-circle-info',
        'tip' => 'fa-lightbulb'
    );
    
    $icon_class = !empty($atts['icon']) ? $atts['icon'] : ($icons[$atts['type']] ?? $icons['info']);
    $dismissible_attr = $atts['dismissible'] === 'true' ? ' data-dismissible="true"' : '';
    
    ob_start();
    ?>
    <div class="fsp-info-box fsp-info-<?php echo esc_attr($atts['type']); ?>"<?php echo $dismissible_attr; ?> role="alert">
        <div class="fsp-info-icon">
            <i class="fa-solid <?php echo esc_attr($icon_class); ?>"></i>
        </div>
        <div class="fsp-info-content">
            <?php if (!empty($atts['title'])) : ?>
                <div class="fsp-info-title"><?php echo esc_html($atts['title']); ?></div>
            <?php endif; ?>
            <div class="fsp-info-text"><?php echo wp_kses_post(do_shortcode($content)); ?></div>
        </div>
        <?php if ($atts['dismissible'] === 'true') : ?>
            <button type="button" class="fsp-info-dismiss" aria-label="بستن">
                <i class="fa-solid fa-xmark"></i>
            </button>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_info', 'fsp_info_shortcode');

/**
 * -----------------------------------------------------------------------------
 * [fsp_features] - Feature List Shortcode
 * -----------------------------------------------------------------------------
 * Usage:
 * [fsp_features columns="3" style="card"]
 * [fsp_feature icon="fa-shield" title="گارانتی"]توضیحات[/fsp_feature]
 * [fsp_feature icon="fa-truck" title="ارسال رایگان"]توضیحات[/fsp_feature]
 * [/fsp_features]
 */
function fsp_features_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'columns' => '4',
        'style' => 'default', // default, card, minimal, bordered
        'align' => 'center'
    ), $atts, 'fsp_features');
    
    $content = do_shortcode($content);
    
    ob_start();
    ?>
    <div class="fsp-features fsp-features-<?php echo esc_attr($atts['style']); ?> fsp-features-cols-<?php echo esc_attr($atts['columns']); ?> fsp-features-align-<?php echo esc_attr($atts['align']); ?>">
        <?php echo $content; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_features', 'fsp_features_shortcode');

function fsp_feature_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'icon' => 'fa-check',
        'title' => '',
        'color' => 'primary',
        'link' => ''
    ), $atts, 'fsp_feature');
    
    $wrapper_tag = !empty($atts['link']) ? 'a' : 'div';
    $link_attr = !empty($atts['link']) ? ' href="' . esc_url($atts['link']) . '"' : '';
    
    ob_start();
    ?>
    <<?php echo $wrapper_tag; ?> class="fsp-feature fsp-feature-<?php echo esc_attr($atts['color']); ?>"<?php echo $link_attr; ?>>
        <div class="fsp-feature-icon">
            <i class="fa-solid <?php echo esc_attr($atts['icon']); ?>"></i>
        </div>
        <?php if (!empty($atts['title'])) : ?>
            <h4 class="fsp-feature-title"><?php echo esc_html($atts['title']); ?></h4>
        <?php endif; ?>
        <?php if (!empty($content)) : ?>
            <p class="fsp-feature-desc"><?php echo wp_kses_post($content); ?></p>
        <?php endif; ?>
    </<?php echo $wrapper_tag; ?>>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_feature', 'fsp_feature_shortcode');

/**
 * -----------------------------------------------------------------------------
 * [fsp_highlight] - Text Highlight Shortcode
 * -----------------------------------------------------------------------------
 * Usage:
 * [fsp_highlight]متن هایلایت[/fsp_highlight]
 * [fsp_highlight color="green" type="marker"]متن[/fsp_highlight]
 * [fsp_highlight type="underline" color="red"]متن[/fsp_highlight]
 */
function fsp_highlight_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'color' => 'yellow',
        'type' => 'background' // background, marker, underline, glow, gradient
    ), $atts, 'fsp_highlight');
    
    return '<span class="fsp-highlight fsp-highlight-' . esc_attr($atts['color']) . ' fsp-highlight-' . esc_attr($atts['type']) . '">' . wp_kses_post(do_shortcode($content)) . '</span>';
}
add_shortcode('fsp_highlight', 'fsp_highlight_shortcode');

/**
 * -----------------------------------------------------------------------------
 * [fsp_accordion] - Accordion Shortcode
 * -----------------------------------------------------------------------------
 * Usage:
 * [fsp_accordion title="عنوان بخش"]
 * [fsp_accordion_item title="سوال اول" open="true"]پاسخ[/fsp_accordion_item]
 * [fsp_accordion_item title="سوال دوم"]پاسخ[/fsp_accordion_item]
 * [/fsp_accordion]
 */
function fsp_accordion_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'title' => '',
        'id' => 'fsp-accordion-' . uniqid(),
        'multiple' => 'false',
        'style' => 'default' // default, bordered, minimal, steps
    ), $atts, 'fsp_accordion');
    
    $GLOBALS['fsp_accordion_settings'] = array(
        'id' => $atts['id'],
        'multiple' => $atts['multiple'],
        'style' => $atts['style'],
        'index' => 0
    );
    
    $content = do_shortcode($content);
    unset($GLOBALS['fsp_accordion_settings']);
    
    ob_start();
    ?>
    <div class="fsp-accordion fsp-accordion-<?php echo esc_attr($atts['style']); ?>" id="<?php echo esc_attr($atts['id']); ?>" data-multiple="<?php echo esc_attr($atts['multiple']); ?>">
        <?php if (!empty($atts['title'])) : ?>
            <h3 class="fsp-accordion-title">
                <i class="fa-solid fa-list"></i>
                <?php echo esc_html($atts['title']); ?>
            </h3>
        <?php endif; ?>
        <div class="fsp-accordion-items">
            <?php echo $content; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_accordion', 'fsp_accordion_shortcode');

function fsp_accordion_item_shortcode($atts, $content = null) {
    $settings = isset($GLOBALS['fsp_accordion_settings']) ? $GLOBALS['fsp_accordion_settings'] : array('index' => 0);
    $settings['index']++;
    $GLOBALS['fsp_accordion_settings'] = $settings;
    
    $atts = shortcode_atts(array(
        'title' => 'آیتم',
        'icon' => '',
        'step' => '',
        'open' => 'false'
    ), $atts, 'fsp_accordion_item');
    
    $is_open = $atts['open'] === 'true';
    $item_id = ($settings['id'] ?? 'acc') . '-item-' . $settings['index'];
    
    ob_start();
    ?>
    <div class="fsp-accordion-item<?php echo $is_open ? ' active' : ''; ?>">
        <button type="button" class="fsp-accordion-header" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr($item_id); ?>">
            <?php if (!empty($atts['step'])) : ?>
                <span class="fsp-accordion-step"><?php echo esc_html($atts['step']); ?></span>
            <?php elseif (!empty($atts['icon'])) : ?>
                <span class="fsp-accordion-icon"><i class="fa-solid <?php echo esc_attr($atts['icon']); ?>"></i></span>
            <?php endif; ?>
            <span class="fsp-accordion-label"><?php echo esc_html($atts['title']); ?></span>
            <i class="fa-solid fa-chevron-down fsp-accordion-arrow"></i>
        </button>
        <div class="fsp-accordion-content<?php echo $is_open ? ' show' : ''; ?>" id="<?php echo esc_attr($item_id); ?>">
            <div class="fsp-accordion-body">
                <?php echo wp_kses_post(do_shortcode($content)); ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_accordion_item', 'fsp_accordion_item_shortcode');

/**
 * -----------------------------------------------------------------------------
 * [fsp_columns] - Column Layout Shortcode
 * -----------------------------------------------------------------------------
 * Usage:
 * [fsp_columns ratio="60-40" gap="24"]
 * [fsp_column]محتوای ستون اول[/fsp_column]
 * [fsp_column]محتوای ستون دوم[/fsp_column]
 * [/fsp_columns]
 */
function fsp_columns_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'ratio' => '50-50',
        'gap' => '24',
        'align' => 'top',
        'reverse_mobile' => 'false'
    ), $atts, 'fsp_columns');
    
    $GLOBALS['fsp_columns_settings'] = array(
        'ratio' => explode('-', $atts['ratio']),
        'index' => 0
    );
    
    $content = do_shortcode($content);
    unset($GLOBALS['fsp_columns_settings']);
    
    $classes = array(
        'fsp-columns',
        'fsp-columns-align-' . $atts['align']
    );
    if ($atts['reverse_mobile'] === 'true') {
        $classes[] = 'fsp-columns-reverse';
    }
    
    ob_start();
    ?>
    <div class="<?php echo esc_attr(implode(' ', $classes)); ?>" style="gap: <?php echo intval($atts['gap']); ?>px;">
        <?php echo $content; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_columns', 'fsp_columns_shortcode');

function fsp_column_shortcode($atts, $content = null) {
    $settings = isset($GLOBALS['fsp_columns_settings']) ? $GLOBALS['fsp_columns_settings'] : array('ratio' => array(50, 50), 'index' => 0);
    $index = $settings['index'];
    $GLOBALS['fsp_columns_settings']['index'] = $index + 1;
    
    $atts = shortcode_atts(array(
        'width' => '',
        'class' => ''
    ), $atts, 'fsp_column');
    
    $width = !empty($atts['width']) ? $atts['width'] : (isset($settings['ratio'][$index]) ? $settings['ratio'][$index] : 50);
    
    ob_start();
    ?>
    <div class="fsp-column <?php echo esc_attr($atts['class']); ?>" style="flex: 0 0 calc(<?php echo intval($width); ?>% - 12px);">
        <?php echo wp_kses_post(do_shortcode($content)); ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_column', 'fsp_column_shortcode');

/**
 * -----------------------------------------------------------------------------
 * [fsp_cta] - Call to Action Shortcode
 * -----------------------------------------------------------------------------
 * Usage:
 * [fsp_cta title="خرید کنید!" subtitle="تخفیف ویژه" button_text="مشاهده" button_url="/shop" style="gradient"]
 */
function fsp_cta_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'title' => '',
        'subtitle' => '',
        'icon' => 'fa-bag-shopping',
        'button_text' => 'مشاهده',
        'button_url' => '#',
        'button_target' => '_self',
        'style' => 'gradient', // gradient, solid, outline, dark, image
        'bg_image' => '',
        'align' => 'center'
    ), $atts, 'fsp_cta');
    
    if (empty($atts['subtitle']) && !empty($content)) {
        $atts['subtitle'] = strip_tags($content);
    }
    
    $style_attr = '';
    if (!empty($atts['bg_image'])) {
        $style_attr = ' style="background-image: url(' . esc_url($atts['bg_image']) . ');"';
    }
    
    ob_start();
    ?>
    <div class="fsp-cta fsp-cta-<?php echo esc_attr($atts['style']); ?> fsp-cta-align-<?php echo esc_attr($atts['align']); ?>"<?php echo $style_attr; ?>>
        <?php if (!empty($atts['icon'])) : ?>
            <div class="fsp-cta-icon">
                <i class="fa-solid <?php echo esc_attr($atts['icon']); ?>"></i>
            </div>
        <?php endif; ?>
        <div class="fsp-cta-content">
            <?php if (!empty($atts['title'])) : ?>
                <h3 class="fsp-cta-title"><?php echo esc_html($atts['title']); ?></h3>
            <?php endif; ?>
            <?php if (!empty($atts['subtitle'])) : ?>
                <p class="fsp-cta-subtitle"><?php echo esc_html($atts['subtitle']); ?></p>
            <?php endif; ?>
        </div>
        <?php if (!empty($atts['button_text']) && !empty($atts['button_url'])) : ?>
            <a href="<?php echo esc_url($atts['button_url']); ?>" class="fsp-cta-btn" target="<?php echo esc_attr($atts['button_target']); ?>">
                <?php echo esc_html($atts['button_text']); ?>
                <i class="fa-solid fa-arrow-left"></i>
            </a>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_cta', 'fsp_cta_shortcode');

/**
 * -----------------------------------------------------------------------------
 * [fsp_button] - Button Shortcode
 * -----------------------------------------------------------------------------
 * Usage:
 * [fsp_button url="/shop" style="solid" color="primary" size="large"]خرید[/fsp_button]
 * [fsp_button url="#" style="outline" icon="fa-cart-plus"]افزودن به سبد[/fsp_button]
 */
function fsp_button_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'url' => '#',
        'target' => '_self',
        'style' => 'solid', // solid, outline, gradient, ghost, link
        'color' => 'primary', // primary, secondary, success, danger, warning, dark, light
        'size' => 'medium', // small, medium, large, xlarge
        'icon' => '',
        'icon_position' => 'right',
        'full_width' => 'false',
        'id' => '',
        'class' => ''
    ), $atts, 'fsp_button');
    
    $classes = array(
        'fsp-btn',
        'fsp-btn-' . $atts['style'],
        'fsp-btn-' . $atts['color'],
        'fsp-btn-' . $atts['size']
    );
    
    if ($atts['full_width'] === 'true') {
        $classes[] = 'fsp-btn-full';
    }
    if (!empty($atts['class'])) {
        $classes[] = $atts['class'];
    }
    
    $id_attr = !empty($atts['id']) ? ' id="' . esc_attr($atts['id']) . '"' : '';
    $text = !empty($content) ? do_shortcode($content) : 'دکمه';
    
    ob_start();
    ?>
    <a href="<?php echo esc_url($atts['url']); ?>" class="<?php echo esc_attr(implode(' ', $classes)); ?>" target="<?php echo esc_attr($atts['target']); ?>"<?php echo $id_attr; ?>>
        <?php if (!empty($atts['icon']) && $atts['icon_position'] === 'right') : ?>
            <i class="fa-solid <?php echo esc_attr($atts['icon']); ?>"></i>
        <?php endif; ?>
        <span><?php echo wp_kses_post($text); ?></span>
        <?php if (!empty($atts['icon']) && $atts['icon_position'] === 'left') : ?>
            <i class="fa-solid <?php echo esc_attr($atts['icon']); ?>"></i>
        <?php endif; ?>
    </a>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_button', 'fsp_button_shortcode');

/**
 * -----------------------------------------------------------------------------
 * [fsp_badge] - Badge/Label Shortcode
 * -----------------------------------------------------------------------------
 * Usage:
 * [fsp_badge color="red" icon="fa-fire"]پرفروش[/fsp_badge]
 * [fsp_badge style="outline" color="green"]جدید[/fsp_badge]
 */
function fsp_badge_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'color' => 'primary', // primary, red, green, blue, orange, purple, gold, dark
        'style' => 'solid', // solid, outline, gradient
        'icon' => '',
        'size' => 'medium' // small, medium, large
    ), $atts, 'fsp_badge');
    
    ob_start();
    ?>
    <span class="fsp-badge fsp-badge-<?php echo esc_attr($atts['color']); ?> fsp-badge-<?php echo esc_attr($atts['style']); ?> fsp-badge-<?php echo esc_attr($atts['size']); ?>">
        <?php if (!empty($atts['icon'])) : ?>
            <i class="fa-solid <?php echo esc_attr($atts['icon']); ?>"></i>
        <?php endif; ?>
        <?php echo esc_html($content); ?>
    </span>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_badge', 'fsp_badge_shortcode');

/**
 * -----------------------------------------------------------------------------
 * [fsp_gallery] - In-Content Gallery Shortcode
 * -----------------------------------------------------------------------------
 * Usage:
 * [fsp_gallery ids="123,456,789" columns="3" lightbox="true"]
 */
function fsp_gallery_shortcode($atts) {
    $atts = shortcode_atts(array(
        'ids' => '',
        'columns' => '3',
        'lightbox' => 'true',
        'size' => 'medium',
        'gap' => '12'
    ), $atts, 'fsp_gallery');
    
    if (empty($atts['ids'])) {
        return '';
    }
    
    $ids = array_map('intval', explode(',', $atts['ids']));
    $lightbox_attr = $atts['lightbox'] === 'true' ? ' data-lightbox="true"' : '';
    
    ob_start();
    ?>
    <div class="fsp-gallery fsp-gallery-cols-<?php echo esc_attr($atts['columns']); ?>"<?php echo $lightbox_attr; ?> style="gap: <?php echo intval($atts['gap']); ?>px;">
        <?php foreach ($ids as $id) : 
            $thumb = wp_get_attachment_image_url($id, $atts['size']);
            $full = wp_get_attachment_image_url($id, 'full');
            $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
            if (!$thumb) continue;
        ?>
            <div class="fsp-gallery-item" data-full="<?php echo esc_url($full); ?>">
                <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($alt); ?>" loading="lazy">
                <?php if ($atts['lightbox'] === 'true') : ?>
                    <div class="fsp-gallery-overlay">
                        <i class="fa-solid fa-expand"></i>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_gallery', 'fsp_gallery_shortcode');

/**
 * -----------------------------------------------------------------------------
 * [fsp_video] - Video Embed Shortcode
 * -----------------------------------------------------------------------------
 * Usage:
 * [fsp_video url="https://www.youtube.com/watch?v=xxx" title="ویدیو محصول"]
 * [fsp_video url="https://www.aparat.com/v/xxx" autoplay="false"]
 */
function fsp_video_shortcode($atts) {
    $atts = shortcode_atts(array(
        'url' => '',
        'title' => '',
        'autoplay' => 'false',
        'ratio' => '16-9' // 16-9, 4-3, 1-1, 21-9
    ), $atts, 'fsp_video');
    
    if (empty($atts['url'])) {
        return '';
    }
    
    $embed_url = $atts['url'];
    
    // YouTube
    if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $atts['url'], $id)) {
        $embed_url = 'https://www.youtube.com/embed/' . $id[1];
    } elseif (preg_match('/youtu\.be\/([^\&\?\/]+)/', $atts['url'], $id)) {
        $embed_url = 'https://www.youtube.com/embed/' . $id[1];
    }
    
    // Aparat
    if (strpos($atts['url'], 'aparat.com') !== false) {
        if (preg_match('/aparat\.com\/v\/([^\&\?\/]+)/', $atts['url'], $id)) {
            $embed_url = 'https://www.aparat.com/video/video/embed/videohash/' . $id[1] . '/vt/frame';
        }
    }
    
    if ($atts['autoplay'] === 'true') {
        $embed_url .= (strpos($embed_url, '?') !== false ? '&' : '?') . 'autoplay=1';
    }
    
    ob_start();
    ?>
    <div class="fsp-video-wrapper fsp-video-ratio-<?php echo esc_attr($atts['ratio']); ?>">
        <?php if (!empty($atts['title'])) : ?>
            <div class="fsp-video-title">
                <i class="fa-solid fa-circle-play"></i>
                <?php echo esc_html($atts['title']); ?>
            </div>
        <?php endif; ?>
        <div class="fsp-video-container">
            <iframe src="<?php echo esc_url($embed_url); ?>" 
                    allowfullscreen
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    loading="lazy"></iframe>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_video', 'fsp_video_shortcode');

/**
 * -----------------------------------------------------------------------------
 * [fsp_specs] - Specifications Table Shortcode
 * -----------------------------------------------------------------------------
 * Usage:
 * [fsp_specs title="مشخصات فنی"]
 * [fsp_spec label="ظرفیت"]1800mAh[/fsp_spec]
 * [fsp_spec label="وزن"]250 گرم[/fsp_spec]
 * [/fsp_specs]
 */
function fsp_specs_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'title' => 'مشخصات فنی',
        'style' => 'striped', // striped, bordered, minimal
        'columns' => '1' // 1, 2
    ), $atts, 'fsp_specs');
    
    $content = do_shortcode($content);
    
    ob_start();
    ?>
    <div class="fsp-specs fsp-specs-<?php echo esc_attr($atts['style']); ?> fsp-specs-cols-<?php echo esc_attr($atts['columns']); ?>">
        <?php if (!empty($atts['title'])) : ?>
            <h3 class="fsp-specs-title">
                <i class="fa-solid fa-list-check"></i>
                <?php echo esc_html($atts['title']); ?>
            </h3>
        <?php endif; ?>
        <table class="fsp-specs-table">
            <tbody>
                <?php echo $content; ?>
            </tbody>
        </table>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_specs', 'fsp_specs_shortcode');

function fsp_spec_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'label' => '',
        'icon' => ''
    ), $atts, 'fsp_spec');
    
    ob_start();
    ?>
    <tr class="fsp-spec-row">
        <th>
            <?php if (!empty($atts['icon'])) : ?>
                <i class="fa-solid <?php echo esc_attr($atts['icon']); ?>"></i>
            <?php endif; ?>
            <?php echo esc_html($atts['label']); ?>
        </th>
        <td><?php echo wp_kses_post($content); ?></td>
    </tr>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_spec', 'fsp_spec_shortcode');

/**
 * -----------------------------------------------------------------------------
 * [fsp_faq] - FAQ Shortcode with Schema.org
 * -----------------------------------------------------------------------------
 * Usage:
 * [fsp_faq title="سوالات متداول"]
 * [fsp_faq_item question="سوال اول؟"]پاسخ اول[/fsp_faq_item]
 * [fsp_faq_item question="سوال دوم؟"]پاسخ دوم[/fsp_faq_item]
 * [/fsp_faq]
 */
function fsp_faq_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'title' => 'سوالات متداول',
        'schema' => 'true'
    ), $atts, 'fsp_faq');
    
    $content = do_shortcode($content);
    $schema_attr = $atts['schema'] === 'true' ? ' itemscope itemtype="https://schema.org/FAQPage"' : '';
    
    ob_start();
    ?>
    <section class="fsp-faq"<?php echo $schema_attr; ?>>
        <?php if (!empty($atts['title'])) : ?>
            <h3 class="fsp-faq-title">
                <i class="fa-solid fa-circle-question"></i>
                <?php echo esc_html($atts['title']); ?>
            </h3>
        <?php endif; ?>
        <div class="fsp-faq-list">
            <?php echo $content; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_faq', 'fsp_faq_shortcode');

function fsp_faq_item_shortcode($atts, $content = null) {
    static $faq_index = 0;
    $faq_index++;
    
    $atts = shortcode_atts(array(
        'question' => '',
        'open' => 'false'
    ), $atts, 'fsp_faq_item');
    
    if (empty($atts['question'])) return '';
    
    $is_open = $atts['open'] === 'true';
    
    ob_start();
    ?>
    <div class="fsp-faq-item<?php echo $is_open ? ' active' : ''; ?>" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
        <button type="button" class="fsp-faq-question" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>" aria-controls="fsp-faq-answer-<?php echo $faq_index; ?>">
            <span itemprop="name"><?php echo esc_html($atts['question']); ?></span>
            <i class="fa-solid fa-plus"></i>
        </button>
        <div class="fsp-faq-answer<?php echo $is_open ? ' show' : ''; ?>" id="fsp-faq-answer-<?php echo $faq_index; ?>" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
            <div itemprop="text"><?php echo wp_kses_post(do_shortcode($content)); ?></div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_faq_item', 'fsp_faq_item_shortcode');

/**
 * -----------------------------------------------------------------------------
 * [fsp_comparison] - Comparison Table Shortcode
 * -----------------------------------------------------------------------------
 * Usage:
 * [fsp_comparison]
 * [fsp_compare_row label="قیمت" product1="100,000" product2="120,000" highlight="1"]
 * [fsp_compare_row label="گارانتی" product1="18 ماه" product2="12 ماه"]
 * [/fsp_comparison]
 */
function fsp_comparison_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'title' => 'مقایسه محصولات',
        'product1_name' => 'محصول اول',
        'product2_name' => 'محصول دوم',
        'product1_image' => '',
        'product2_image' => ''
    ), $atts, 'fsp_comparison');
    
    $GLOBALS['fsp_comparison_settings'] = $atts;
    $content = do_shortcode($content);
    unset($GLOBALS['fsp_comparison_settings']);
    
    ob_start();
    ?>
    <div class="fsp-comparison">
        <?php if (!empty($atts['title'])) : ?>
            <h3 class="fsp-comparison-title">
                <i class="fa-solid fa-code-compare"></i>
                <?php echo esc_html($atts['title']); ?>
            </h3>
        <?php endif; ?>
        <div class="fsp-comparison-table-wrapper">
            <table class="fsp-comparison-table">
                <thead>
                    <tr>
                        <th class="fsp-compare-label">ویژگی</th>
                        <th class="fsp-compare-product">
                            <?php if (!empty($atts['product1_image'])) : ?>
                                <img src="<?php echo esc_url($atts['product1_image']); ?>" alt="">
                            <?php endif; ?>
                            <span><?php echo esc_html($atts['product1_name']); ?></span>
                        </th>
                        <th class="fsp-compare-product">
                            <?php if (!empty($atts['product2_image'])) : ?>
                                <img src="<?php echo esc_url($atts['product2_image']); ?>" alt="">
                            <?php endif; ?>
                            <span><?php echo esc_html($atts['product2_name']); ?></span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $content; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_comparison', 'fsp_comparison_shortcode');

function fsp_compare_row_shortcode($atts) {
    $atts = shortcode_atts(array(
        'label' => '',
        'product1' => '',
        'product2' => '',
        'highlight' => '' // 1, 2, or empty
    ), $atts, 'fsp_compare_row');
    
    ob_start();
    ?>
    <tr>
        <td class="fsp-compare-label"><?php echo esc_html($atts['label']); ?></td>
        <td class="fsp-compare-value<?php echo $atts['highlight'] === '1' ? ' fsp-compare-winner' : ''; ?>">
            <?php echo esc_html($atts['product1']); ?>
            <?php if ($atts['highlight'] === '1') : ?>
                <i class="fa-solid fa-crown"></i>
            <?php endif; ?>
        </td>
        <td class="fsp-compare-value<?php echo $atts['highlight'] === '2' ? ' fsp-compare-winner' : ''; ?>">
            <?php echo esc_html($atts['product2']); ?>
            <?php if ($atts['highlight'] === '2') : ?>
                <i class="fa-solid fa-crown"></i>
            <?php endif; ?>
        </td>
    </tr>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_compare_row', 'fsp_compare_row_shortcode');

/**
 * -----------------------------------------------------------------------------
 * [fsp_testimonial] - Testimonial Shortcode
 * -----------------------------------------------------------------------------
 * Usage:
 * [fsp_testimonial name="علی احمدی" title="خریدار" avatar="url" rating="5"]
 * نظر مشتری در اینجا
 * [/fsp_testimonial]
 */
function fsp_testimonial_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'name' => '',
        'title' => '',
        'avatar' => '',
        'rating' => '5',
        'date' => '',
        'verified' => 'true'
    ), $atts, 'fsp_testimonial');
    
    $rating = intval($atts['rating']);
    
    ob_start();
    ?>
    <div class="fsp-testimonial">
        <div class="fsp-testimonial-header">
            <div class="fsp-testimonial-avatar">
                <?php if (!empty($atts['avatar'])) : ?>
                    <img src="<?php echo esc_url($atts['avatar']); ?>" alt="<?php echo esc_attr($atts['name']); ?>">
                <?php else : ?>
                    <i class="fa-solid fa-user"></i>
                <?php endif; ?>
            </div>
            <div class="fsp-testimonial-info">
                <h4 class="fsp-testimonial-name">
                    <?php echo esc_html($atts['name']); ?>
                    <?php if ($atts['verified'] === 'true') : ?>
                        <span class="fsp-testimonial-verified" title="خریدار تأیید شده">
                            <i class="fa-solid fa-badge-check"></i>
                        </span>
                    <?php endif; ?>
                </h4>
                <?php if (!empty($atts['title'])) : ?>
                    <span class="fsp-testimonial-title"><?php echo esc_html($atts['title']); ?></span>
                <?php endif; ?>
            </div>
            <?php if ($rating > 0) : ?>
                <div class="fsp-testimonial-rating">
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <i class="fa-<?php echo $i <= $rating ? 'solid' : 'regular'; ?> fa-star"></i>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="fsp-testimonial-content">
            <i class="fa-solid fa-quote-right fsp-testimonial-quote"></i>
            <?php echo wp_kses_post(do_shortcode($content)); ?>
        </div>
        <?php if (!empty($atts['date'])) : ?>
            <div class="fsp-testimonial-footer">
                <span class="fsp-testimonial-date">
                    <i class="fa-regular fa-calendar"></i>
                    <?php echo esc_html($atts['date']); ?>
                </span>
            </div>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_testimonial', 'fsp_testimonial_shortcode');

/**
 * -----------------------------------------------------------------------------
 * [fsp_countdown] - Countdown Timer Shortcode
 * -----------------------------------------------------------------------------
 * Usage:
 * [fsp_countdown date="2025-01-31 23:59:59" title="پایان تخفیف" style="cards"]
 */
function fsp_countdown_shortcode($atts) {
    $atts = shortcode_atts(array(
        'date' => '',
        'title' => '',
        'style' => 'default', // default, cards, minimal, urgent
        'expired_text' => 'زمان به پایان رسید!'
    ), $atts, 'fsp_countdown');
    
    if (empty($atts['date'])) {
        return '';
    }
    
    $countdown_id = 'fsp-countdown-' . uniqid();
    
    ob_start();
    ?>
    <div class="fsp-countdown fsp-countdown-<?php echo esc_attr($atts['style']); ?>" id="<?php echo esc_attr($countdown_id); ?>" data-date="<?php echo esc_attr($atts['date']); ?>" data-expired="<?php echo esc_attr($atts['expired_text']); ?>">
        <?php if (!empty($atts['title'])) : ?>
            <div class="fsp-countdown-title">
                <i class="fa-solid fa-clock"></i>
                <?php echo esc_html($atts['title']); ?>
            </div>
        <?php endif; ?>
        <div class="fsp-countdown-timer">
            <div class="fsp-countdown-item">
                <span class="fsp-countdown-value" data-days>00</span>
                <span class="fsp-countdown-label">روز</span>
            </div>
            <span class="fsp-countdown-separator">:</span>
            <div class="fsp-countdown-item">
                <span class="fsp-countdown-value" data-hours>00</span>
                <span class="fsp-countdown-label">ساعت</span>
            </div>
            <span class="fsp-countdown-separator">:</span>
            <div class="fsp-countdown-item">
                <span class="fsp-countdown-value" data-minutes>00</span>
                <span class="fsp-countdown-label">دقیقه</span>
            </div>
            <span class="fsp-countdown-separator">:</span>
            <div class="fsp-countdown-item">
                <span class="fsp-countdown-value" data-seconds>00</span>
                <span class="fsp-countdown-label">ثانیه</span>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_countdown', 'fsp_countdown_shortcode');

/**
 * -----------------------------------------------------------------------------
 * [fsp_tabs] - Custom Tabs Shortcode
 * -----------------------------------------------------------------------------
 * Usage:
 * [fsp_tabs]
 * [fsp_tab title="تب اول" icon="fa-info"]محتوای تب اول[/fsp_tab]
 * [fsp_tab title="تب دوم" icon="fa-list"]محتوای تب دوم[/fsp_tab]
 * [/fsp_tabs]
 */
function fsp_tabs_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'style' => 'default', // default, pills, underline, vertical
        'id' => 'fsp-tabs-' . uniqid()
    ), $atts, 'fsp_tabs');
    
    $GLOBALS['fsp_tabs_settings'] = array(
        'id' => $atts['id'],
        'tabs' => array(),
        'index' => 0
    );
    
    $content = do_shortcode($content);
    $tabs = $GLOBALS['fsp_tabs_settings']['tabs'];
    unset($GLOBALS['fsp_tabs_settings']);
    
    ob_start();
    ?>
    <div class="fsp-tabs fsp-tabs-<?php echo esc_attr($atts['style']); ?>" id="<?php echo esc_attr($atts['id']); ?>">
        <div class="fsp-tabs-nav" role="tablist">
            <?php foreach ($tabs as $index => $tab) : ?>
                <button type="button" 
                        class="fsp-tab-btn<?php echo $index === 0 ? ' active' : ''; ?>" 
                        data-tab="<?php echo esc_attr($tab['id']); ?>"
                        role="tab"
                        aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                        aria-controls="<?php echo esc_attr($tab['id']); ?>">
                    <?php if (!empty($tab['icon'])) : ?>
                        <i class="fa-solid <?php echo esc_attr($tab['icon']); ?>"></i>
                    <?php endif; ?>
                    <span><?php echo esc_html($tab['title']); ?></span>
                    <?php if (!empty($tab['count'])) : ?>
                        <em class="fsp-tab-count"><?php echo esc_html($tab['count']); ?></em>
                    <?php endif; ?>
                </button>
            <?php endforeach; ?>
        </div>
        <div class="fsp-tabs-content">
            <?php echo $content; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_tabs', 'fsp_tabs_shortcode');

function fsp_tab_shortcode($atts, $content = null) {
    $settings = isset($GLOBALS['fsp_tabs_settings']) ? $GLOBALS['fsp_tabs_settings'] : array('index' => 0, 'tabs' => array());
    $index = $settings['index'];
    
    $atts = shortcode_atts(array(
        'title' => 'تب',
        'icon' => '',
        'count' => ''
    ), $atts, 'fsp_tab');
    
    $tab_id = $settings['id'] . '-panel-' . $index;
    
    $GLOBALS['fsp_tabs_settings']['tabs'][] = array(
        'id' => $tab_id,
        'title' => $atts['title'],
        'icon' => $atts['icon'],
        'count' => $atts['count']
    );
    $GLOBALS['fsp_tabs_settings']['index'] = $index + 1;
    
    ob_start();
    ?>
    <div class="fsp-tab-panel<?php echo $index === 0 ? ' active' : ''; ?>" 
         id="<?php echo esc_attr($tab_id); ?>"
         role="tabpanel"
         aria-hidden="<?php echo $index === 0 ? 'false' : 'true'; ?>">
        <?php echo wp_kses_post(do_shortcode($content)); ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_tab', 'fsp_tab_shortcode');

/**
 * -----------------------------------------------------------------------------
 * [fsp_trust] - Trust/Benefit Icons Shortcode
 * -----------------------------------------------------------------------------
 * Usage:
 * [fsp_trust style="cards"]
 * [fsp_trust_item icon="fa-shield" title="گارانتی اصالت"]توضیح[/fsp_trust_item]
 * [fsp_trust_item icon="fa-truck" title="ارسال رایگان"]توضیح[/fsp_trust_item]
 * [/fsp_trust]
 */
function fsp_trust_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'style' => 'default', // default, cards, minimal, horizontal
        'columns' => '4'
    ), $atts, 'fsp_trust');
    
    $content = do_shortcode($content);
    
    ob_start();
    ?>
    <div class="fsp-trust fsp-trust-<?php echo esc_attr($atts['style']); ?> fsp-trust-cols-<?php echo esc_attr($atts['columns']); ?>">
        <?php echo $content; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_trust', 'fsp_trust_shortcode');

function fsp_trust_item_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'icon' => 'fa-check',
        'title' => '',
        'color' => 'primary'
    ), $atts, 'fsp_trust_item');
    
    ob_start();
    ?>
    <div class="fsp-trust-item fsp-trust-<?php echo esc_attr($atts['color']); ?>">
        <div class="fsp-trust-icon">
            <i class="fa-solid <?php echo esc_attr($atts['icon']); ?>"></i>
        </div>
        <div class="fsp-trust-content">
            <?php if (!empty($atts['title'])) : ?>
                <h4 class="fsp-trust-title"><?php echo esc_html($atts['title']); ?></h4>
            <?php endif; ?>
            <?php if (!empty($content)) : ?>
                <p class="fsp-trust-desc"><?php echo wp_kses_post($content); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('fsp_trust_item', 'fsp_trust_item_shortcode');

/* ═══════════════════════════════════════════════════════════════════════════
   SECTION 3: ENHANCED BACKEND META BOXES
   ═══════════════════════════════════════════════════════════════════════════ */

add_action('add_meta_boxes', 'flavor_product_meta_boxes');
function flavor_product_meta_boxes() {
    add_meta_box(
        'flavor_product_sales',
        '📊 فانل فروش و اطلاعات کلیدی',
        'flavor_product_sales_callback',
        'product',
        'side',
        'high'
    );

    add_meta_box(
        'flavor_product_extra',
        '✨ اطلاعات تکمیلی محصول',
        'flavor_product_extra_callback',
        'product',
        'normal',
        'high'
    );

    add_meta_box(
        'flavor_product_specs',
        '📋 مشخصات فنی',
        'flavor_product_specs_callback',
        'product',
        'normal',
        'default'
    );

    add_meta_box(
        'flavor_product_badges',
        '🏷️ نشان‌ها و برچسب‌ها',
        'flavor_product_badges_callback',
        'product',
        'side',
        'default'
    );
}

/**
 * Sales Funnel Meta Box - Enhanced UI
 */
function flavor_product_sales_callback($post) {
    wp_nonce_field('flavor_product_sales_nonce', 'flavor_sales_nonce');

    $sales_badge = get_post_meta($post->ID, '_flavor_sales_badge', true);
    $urgency_text = get_post_meta($post->ID, '_flavor_urgency_text', true);
    $trust_score = get_post_meta($post->ID, '_flavor_trust_score', true);
    $conversion_target = get_post_meta($post->ID, '_flavor_conversion_target', true);
    ?>
    <style>
        .flavor-meta-box { padding: 12px 0; }
        .flavor-meta-box .flavor-field { margin-bottom: 18px; }
        .flavor-meta-box label { 
            display: block; 
            font-weight: 600; 
            margin-bottom: 8px;
            color: #1e293b;
            font-size: 13px;
        }
        .flavor-meta-box input[type="text"],
        .flavor-meta-box input[type="number"],
        .flavor-meta-box input[type="url"],
        .flavor-meta-box select,
        .flavor-meta-box textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.2s ease;
            background: #fff;
        }
        .flavor-meta-box input:focus,
        .flavor-meta-box select:focus,
        .flavor-meta-box textarea:focus {
            border-color: #29853A;
            box-shadow: 0 0 0 3px rgba(41, 133, 58, 0.1);
            outline: none;
        }
        .flavor-meta-box .description {
            font-size: 12px;
            color: #64748b;
            margin-top: 6px;
            line-height: 1.5;
        }
        .flavor-badge-preview {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: linear-gradient(135deg, #ef4444, #f97316);
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
        }
        .flavor-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 20px 0;
        }
    </style>
    <div class="flavor-meta-box">
        <div class="flavor-field">
            <label>🎯 برچسب فروش ویژه:</label>
            <select name="flavor_sales_badge">
                <option value="">بدون برچسب</option>
                <option value="bestseller" <?php selected($sales_badge, 'bestseller'); ?>>🔥 پرفروش‌ترین</option>
                <option value="trending" <?php selected($sales_badge, 'trending'); ?>>📈 ترند روز</option>
                <option value="limited" <?php selected($sales_badge, 'limited'); ?>>⏰ موجودی محدود</option>
                <option value="exclusive" <?php selected($sales_badge, 'exclusive'); ?>>💎 انحصاری</option>
                <option value="recommended" <?php selected($sales_badge, 'recommended'); ?>>⭐ توصیه می‌شود</option>
            </select>
            <?php if ($sales_badge) : 
                $badges = [
                    'bestseller' => '🔥 پرفروش‌ترین',
                    'trending' => '📈 ترند روز',
                    'limited' => '⏰ موجودی محدود',
                    'exclusive' => '💎 انحصاری',
                    'recommended' => '⭐ توصیه می‌شود'
                ];
            ?>
                <span class="flavor-badge-preview"><?php echo $badges[$sales_badge] ?? ''; ?></span>
            <?php endif; ?>
        </div>

        <div class="flavor-field">
            <label>⚡ متن فوریت (Urgency):</label>
            <input type="text" name="flavor_urgency_text" value="<?php echo esc_attr($urgency_text); ?>" placeholder="مثال: فقط 3 عدد باقی مانده!">
            <span class="description">این متن در کنار وضعیت موجودی نمایش داده می‌شود و حس فوریت ایجاد می‌کند.</span>
        </div>

        <div class="flavor-divider"></div>

        <div class="flavor-field">
            <label>🌟 امتیاز اعتماد (از 5):</label>
            <select name="flavor_trust_score">
                <option value="">بدون امتیاز</option>
                <?php for ($i = 1; $i <= 5; $i++) : ?>
                    <option value="<?php echo $i; ?>" <?php selected($trust_score, $i); ?>>
                        <?php echo str_repeat('⭐', $i); ?> (<?php echo $i; ?> از 5)
                    </option>
                <?php endfor; ?>
            </select>
            <span class="description">در صورت نبود نظرات، این امتیاز نمایش داده می‌شود.</span>
        </div>

        <div class="flavor-field">
            <label>🎯 هدف تبدیل:</label>
            <select name="flavor_conversion_target">
                <option value="direct_buy" <?php selected($conversion_target, 'direct_buy'); ?>>🛒 خرید مستقیم</option>
                <option value="add_to_cart" <?php selected($conversion_target, 'add_to_cart'); ?>>🛍️ افزودن به سبد</option>
                <option value="inquiry" <?php selected($conversion_target, 'inquiry'); ?>>📞 استعلام قیمت</option>
                <option value="pre_order" <?php selected($conversion_target, 'pre_order'); ?>>📦 پیش‌خرید</option>
            </select>
        </div>
    </div>
    <?php
}

/**
 * Extra Info Meta Box - Enhanced UI
 */
function flavor_product_extra_callback($post) {
    wp_nonce_field('flavor_product_extra_nonce', 'flavor_extra_nonce');

    $warranty = get_post_meta($post->ID, '_flavor_warranty', true);
    $warranty_type = get_post_meta($post->ID, '_flavor_warranty_type', true);
    $delivery_time = get_post_meta($post->ID, '_flavor_delivery_time', true);
    $delivery_type = get_post_meta($post->ID, '_flavor_delivery_type', true);
    $return_days = get_post_meta($post->ID, '_flavor_return_days', true);
    $video_url = get_post_meta($post->ID, '_flavor_video_url', true);
    $size_guide = get_post_meta($post->ID, '_flavor_size_guide', true);
    $ingredients = get_post_meta($post->ID, '_flavor_ingredients', true);
    $usage = get_post_meta($post->ID, '_flavor_usage', true);
    $country = get_post_meta($post->ID, '_flavor_country', true);
    $brand_story = get_post_meta($post->ID, '_flavor_brand_story', true);
    ?>
    <style>
        .flavor-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .flavor-grid-full {
            grid-column: 1 / -1;
        }
        .flavor-input-group {
            display: flex;
            gap: 10px;
        }
        .flavor-input-group select {
            flex: 0 0 40%;
        }
        .flavor-input-group input {
            flex: 1;
        }
        .flavor-section-title {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
            margin: 24px 0 16px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .flavor-section-title:first-child {
            margin-top: 0;
        }
        @media (max-width: 782px) {
            .flavor-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    <div class="flavor-meta-box">
        <h4 class="flavor-section-title">
            <span>🚚</span> اطلاعات ارسال و گارانتی
        </h4>
        
        <div class="flavor-grid">
            <div class="flavor-field">
                <label>🛡️ گارانتی:</label>
                <div class="flavor-input-group">
                    <select name="flavor_warranty_type">
                        <option value="">نوع گارانتی</option>
                        <option value="official" <?php selected($warranty_type, 'official'); ?>>گارانتی رسمی</option>
                        <option value="company" <?php selected($warranty_type, 'company'); ?>>گارانتی شرکتی</option>
                        <option value="seller" <?php selected($warranty_type, 'seller'); ?>>گارانتی فروشنده</option>
                        <option value="international" <?php selected($warranty_type, 'international'); ?>>گارانتی بین‌المللی</option>
                    </select>
                    <input type="text" name="flavor_warranty" value="<?php echo esc_attr($warranty); ?>" placeholder="مدت (مثال: 18 ماه)">
                </div>
            </div>

            <div class="flavor-field">
                <label>🚚 ارسال:</label>
                <div class="flavor-input-group">
                    <select name="flavor_delivery_type">
                        <option value="express" <?php selected($delivery_type, 'express'); ?>>ارسال فوری</option>
                        <option value="standard" <?php selected($delivery_type, 'standard'); ?>>ارسال عادی</option>
                        <option value="free" <?php selected($delivery_type, 'free'); ?>>ارسال رایگان</option>
                        <option value="next_day" <?php selected($delivery_type, 'next_day'); ?>>ارسال فردا</option>
                    </select>
                    <input type="text" name="flavor_delivery_time" value="<?php echo esc_attr($delivery_time); ?>" placeholder="زمان (مثال: 2 تا 3 روز)">
                </div>
            </div>

            <div class="flavor-field">
                <label>↩️ بازگشت کالا:</label>
                <div class="flavor-input-group">
                    <input type="number" name="flavor_return_days" value="<?php echo esc_attr($return_days); ?>" placeholder="7" min="0" max="365">
                    <span style="display: flex; align-items: center; padding: 0 10px; background: #f1f5f9; border-radius: 8px; font-size: 14px;">روز</span>
                </div>
            </div>

            <div class="flavor-field">
                <label>🌍 کشور سازنده:</label>
                <select name="flavor_country">
                    <option value="">انتخاب کنید</option>
                    <option value="ایران" <?php selected($country, 'ایران'); ?>>🇮🇷 ایران</option>
                    <option value="چین" <?php selected($country, 'چین'); ?>>🇨🇳 چین</option>
                    <option value="آلمان" <?php selected($country, 'آلمان'); ?>>🇩🇪 آلمان</option>
                    <option value="آمریکا" <?php selected($country, 'آمریکا'); ?>>🇺🇸 آمریکا</option>
                    <option value="ژاپن" <?php selected($country, 'ژاپن'); ?>>🇯🇵 ژاپن</option>
                    <option value="کره جنوبی" <?php selected($country, 'کره جنوبی'); ?>>🇰🇷 کره جنوبی</option>
                    <option value="ترکیه" <?php selected($country, 'ترکیه'); ?>>🇹🇷 ترکیه</option>
                    <option value="ایتالیا" <?php selected($country, 'ایتالیا'); ?>>🇮🇹 ایتالیا</option>
                    <option value="فرانسه" <?php selected($country, 'فرانسه'); ?>>🇫🇷 فرانسه</option>
                    <option value="انگلستان" <?php selected($country, 'انگلستان'); ?>>🇬🇧 انگلستان</option>
                </select>
            </div>
        </div>

        <h4 class="flavor-section-title">
            <span>🎬</span> محتوای چندرسانه‌ای
        </h4>

        <div class="flavor-field">
            <label>🎥 لینک ویدیو:</label>
            <input type="url" name="flavor_video_url" value="<?php echo esc_attr($video_url); ?>" placeholder="https://www.aparat.com/v/... یا https://youtube.com/watch?v=...">
            <span class="description">ویدیو معرفی محصول از یوتیوب یا آپارات</span>
        </div>

        <h4 class="flavor-section-title">
            <span>📝</span> اطلاعات تکمیلی
        </h4>

        <div class="flavor-field">
            <label>📏 راهنمای سایز:</label>
            <?php
            wp_editor($size_guide, 'flavor_size_guide', array(
                'textarea_rows' => 8,
                'media_buttons' => true,
                'teeny' => false,
                'quicktags' => true
            ));
            ?>
        </div>

        <div class="flavor-grid">
            <div class="flavor-field">
                <label>🧪 مواد تشکیل‌دهنده:</label>
                <textarea name="flavor_ingredients" rows="5" placeholder="لیست مواد و ترکیبات محصول..."><?php echo esc_textarea($ingredients); ?></textarea>
            </div>

            <div class="flavor-field">
                <label>📖 نحوه استفاده:</label>
                <textarea name="flavor_usage" rows="5" placeholder="دستورالعمل استفاده از محصول..."><?php echo esc_textarea($usage); ?></textarea>
            </div>
        </div>

        <div class="flavor-field flavor-grid-full">
            <label>📚 داستان برند:</label>
            <textarea name="flavor_brand_story" rows="4" placeholder="تاریخچه و داستان برند سازنده..."><?php echo esc_textarea($brand_story); ?></textarea>
        </div>
    </div>
    <?php
}

/**
 * Specs Meta Box - Enhanced with Drag & Drop
 */
function flavor_product_specs_callback($post) {
    wp_nonce_field('flavor_product_specs_nonce', 'flavor_specs_nonce');
    $specs = get_post_meta($post->ID, '_flavor_specs', true);
    if (!is_array($specs)) {
        $specs = array();
    }
    ?>
    <style>
        .flavor-specs-repeater { padding: 15px 0; }
        .flavor-spec-row {
            display: flex;
            gap: 12px;
            margin-bottom: 12px;
            padding: 14px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            align-items: center;
            transition: all 0.2s ease;
        }
        .flavor-spec-row:hover {
            border-color: #29853A;
            box-shadow: 0 2px 8px rgba(41, 133, 58, 0.1);
        }
        .flavor-spec-row.ui-sortable-helper {
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .flavor-spec-row input {
            flex: 1;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
        }
        .flavor-spec-row input:focus {
            border-color: #29853A;
            outline: none;
        }
        .flavor-spec-handle {
            cursor: move;
            color: #94a3b8;
            padding: 8px;
            transition: color 0.2s;
        }
        .flavor-spec-handle:hover {
            color: #29853A;
        }
        .flavor-spec-row .button {
            padding: 8px 12px;
            border-radius: 8px;
        }
        #flavor-add-spec {
            margin-top: 16px;
            background: linear-gradient(135deg, #29853A, #22c55e);
            border: none;
            color: white;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        #flavor-add-spec:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(41, 133, 58, 0.3);
        }
        .flavor-remove-spec {
            background: #fee2e2 !important;
            color: #ef4444 !important;
            border: 1px solid #fecaca !important;
        }
        .flavor-remove-spec:hover {
            background: #ef4444 !important;
            color: white !important;
        }
        .flavor-specs-empty {
            text-align: center;
            padding: 40px 20px;
            color: #94a3b8;
            font-size: 14px;
        }
        .flavor-specs-empty i {
            font-size: 48px;
            margin-bottom: 16px;
            display: block;
        }
    </style>
    <div class="flavor-specs-repeater">
        <div id="flavor-specs-list">
            <?php if (empty($specs)) : ?>
                <div class="flavor-specs-empty" id="flavor-specs-empty">
                    <i class="dashicons dashicons-list-view"></i>
                    <p>هنوز مشخصه‌ای اضافه نشده است.<br>روی دکمه زیر کلیک کنید.</p>
                </div>
            <?php else : ?>
                <?php foreach ($specs as $index => $spec) : ?>
                    <div class="flavor-spec-row">
                        <span class="flavor-spec-handle dashicons dashicons-move"></span>
                        <input type="text" name="flavor_specs[<?php echo $index; ?>][label]" 
                               value="<?php echo esc_attr($spec['label']); ?>" 
                               placeholder="عنوان (مثال: ظرفیت باتری)">
                        <input type="text" name="flavor_specs[<?php echo $index; ?>][value]" 
                               value="<?php echo esc_attr($spec['value']); ?>" 
                               placeholder="مقدار (مثال: 1800mAh)">
                        <button type="button" class="button flavor-remove-spec">
                            <span class="dashicons dashicons-trash"></span>
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button type="button" id="flavor-add-spec">
            <span class="dashicons dashicons-plus-alt2"></span>
            افزودن مشخصه جدید
        </button>
    </div>
    <script>
    jQuery(document).ready(function($) {
        var specIndex = <?php echo count($specs); ?>;

        // Sortable
        $('#flavor-specs-list').sortable({
            handle: '.flavor-spec-handle',
            placeholder: 'flavor-spec-row ui-state-highlight',
            opacity: 0.8
        });

        // Add new spec
        $('#flavor-add-spec').on('click', function() {
            $('#flavor-specs-empty').remove();
            
            var row = '<div class="flavor-spec-row" style="opacity:0;">' +
                '<span class="flavor-spec-handle dashicons dashicons-move"></span>' +
                '<input type="text" name="flavor_specs[' + specIndex + '][label]" placeholder="عنوان مشخصه">' +
                '<input type="text" name="flavor_specs[' + specIndex + '][value]" placeholder="مقدار">' +
                '<button type="button" class="button flavor-remove-spec"><span class="dashicons dashicons-trash"></span></button>' +
                '</div>';
            
            var $row = $(row);
            $('#flavor-specs-list').append($row);
            $row.animate({opacity: 1}, 300);
            $row.find('input:first').focus();
            specIndex++;
        });

        // Remove spec
        $(document).on('click', '.flavor-remove-spec', function() {
            var $row = $(this).closest('.flavor-spec-row');
            $row.animate({opacity: 0, height: 0}, 300, function() {
                $(this).remove();
                if ($('#flavor-specs-list .flavor-spec-row').length === 0) {
                    $('#flavor-specs-list').html('<div class="flavor-specs-empty" id="flavor-specs-empty"><i class="dashicons dashicons-list-view"></i><p>هنوز مشخصه‌ای اضافه نشده است.</p></div>');
                }
            });
        });
    });
    </script>
    <?php
}

/**
 * Badges Meta Box - Enhanced UI
 */
function flavor_product_badges_callback($post) {
    wp_nonce_field('flavor_product_badges_nonce', 'flavor_badges_nonce');

    $featured = get_post_meta($post->ID, '_flavor_featured_badge', true);
    $new_badge = get_post_meta($post->ID, '_flavor_new_badge', true);
    $custom_badge = get_post_meta($post->ID, '_flavor_custom_badge', true);
    $badge_color = get_post_meta($post->ID, '_flavor_badge_color', true);
    ?>
    <style>
        .flavor-badge-options { padding: 12px 0; }
        .flavor-checkbox-card {
            display: flex;
            align-items: center;
            padding: 14px;
            margin-bottom: 12px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .flavor-checkbox-card:hover {
            border-color: #29853A;
        }
        .flavor-checkbox-card.checked {
            border-color: #29853A;
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        }
        .flavor-checkbox-card input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-left: 12px;
            accent-color: #29853A;
        }
        .flavor-checkbox-card .badge-icon {
            font-size: 24px;
            margin-left: 12px;
        }
        .flavor-checkbox-card .badge-info {
            flex: 1;
        }
        .flavor-checkbox-card .badge-title {
            font-weight: 600;
            color: #1e293b;
            font-size: 14px;
        }
        .flavor-checkbox-card .badge-desc {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }
        .flavor-color-options {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-top: 10px;
        }
        .flavor-color-option {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 12px;
            font-weight: 500;
        }
        .flavor-color-option:hover {
            border-color: #94a3b8;
        }
        .flavor-color-option input {
            display: none;
        }
        .flavor-color-option.selected {
            border-color: #29853A;
            background: #f0fdf4;
        }
        .flavor-color-option .color-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            margin-left: 6px;
        }
    </style>
    <div class="flavor-badge-options">
        <label class="flavor-checkbox-card <?php echo $featured ? 'checked' : ''; ?>">
            <input type="checkbox" name="flavor_featured_badge" value="1" <?php checked($featured, '1'); ?>>
            <span class="badge-icon">⭐</span>
            <div class="badge-info">
                <div class="badge-title">محصول ویژه</div>
                <div class="badge-desc">نمایش در بخش محصولات ویژه</div>
            </div>
        </label>

        <label class="flavor-checkbox-card <?php echo $new_badge ? 'checked' : ''; ?>">
            <input type="checkbox" name="flavor_new_badge" value="1" <?php checked($new_badge, '1'); ?>>
            <span class="badge-icon">✨</span>
            <div class="badge-info">
                <div class="badge-title">محصول جدید</div>
                <div class="badge-desc">نمایش برچسب "جدید"</div>
            </div>
        </label>

        <div class="flavor-divider"></div>

        <div class="flavor-field">
            <label>🏷️ برچسب سفارشی:</label>
            <input type="text" name="flavor_custom_badge" value="<?php echo esc_attr($custom_badge); ?>" placeholder="مثال: پرفروش، محبوب، انتخاب سردبیر">
        </div>

        <div class="flavor-field">
            <label>🎨 رنگ برچسب:</label>
            <div class="flavor-color-options">
                <?php
                $colors = array(
                    'primary' => array('name' => 'سبز', 'color' => '#29853A'),
                    'red' => array('name' => 'قرمز', 'color' => '#ef4444'),
                    'blue' => array('name' => 'آبی', 'color' => '#3b82f6'),
                    'orange' => array('name' => 'نارنجی', 'color' => '#f97316'),
                    'purple' => array('name' => 'بنفش', 'color' => '#8b5cf6'),
                    'gold' => array('name' => 'طلایی', 'color' => '#eab308')
                );
                foreach ($colors as $value => $info) :
                ?>
                    <label class="flavor-color-option <?php echo $badge_color === $value ? 'selected' : ''; ?>">
                        <input type="radio" name="flavor_badge_color" value="<?php echo $value; ?>" <?php checked($badge_color, $value); ?>>
                        <span class="color-dot" style="background: <?php echo $info['color']; ?>;"></span>
                        <?php echo $info['name']; ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <script>
    jQuery(document).ready(function($) {
        // Checkbox cards
        $('.flavor-checkbox-card input').on('change', function() {
            $(this).closest('.flavor-checkbox-card').toggleClass('checked', this.checked);
        });

        // Color options
        $('.flavor-color-option input').on('change', function() {
            $('.flavor-color-option').removeClass('selected');
            $(this).closest('.flavor-color-option').addClass('selected');
        });
    });
    </script>
    <?php
}

/**
 * Save All Meta Data
 */
add_action('save_post', 'flavor_save_product_meta');
function flavor_save_product_meta($post_id) {
    // Verify nonces
    if (!isset($_POST['flavor_extra_nonce']) || !wp_verify_nonce($_POST['flavor_extra_nonce'], 'flavor_product_extra_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Sales funnel fields
    $sales_fields = array(
        'flavor_sales_badge', 'flavor_urgency_text', 
        'flavor_trust_score', 'flavor_conversion_target'
    );

    foreach ($sales_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }

    // Extra info fields
    $extra_fields = array(
        'flavor_warranty', 'flavor_warranty_type', 'flavor_delivery_time', 
        'flavor_delivery_type', 'flavor_return_days', 'flavor_video_url', 
        'flavor_size_guide', 'flavor_ingredients', 'flavor_usage', 
        'flavor_country', 'flavor_brand_story'
    );

    foreach ($extra_fields as $field) {
        if (isset($_POST[$field])) {
            if ($field == 'flavor_size_guide') {
                update_post_meta($post_id, '_' . $field, wp_kses_post($_POST[$field]));
            } else {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }
    }

    // Specs
    if (isset($_POST['flavor_specs']) && is_array($_POST['flavor_specs'])) {
        $specs = array();
        foreach ($_POST['flavor_specs'] as $spec) {
            if (!empty($spec['label']) || !empty($spec['value'])) {
                $specs[] = array(
                    'label' => sanitize_text_field($spec['label']),
                    'value' => sanitize_text_field($spec['value'])
                );
            }
        }
        update_post_meta($post_id, '_flavor_specs', $specs);
    } else {
        update_post_meta($post_id, '_flavor_specs', array());
    }

    // Badges
    update_post_meta($post_id, '_flavor_featured_badge', isset($_POST['flavor_featured_badge']) ? '1' : '');
    update_post_meta($post_id, '_flavor_new_badge', isset($_POST['flavor_new_badge']) ? '1' : '');
    update_post_meta($post_id, '_flavor_custom_badge', sanitize_text_field($_POST['flavor_custom_badge'] ?? ''));
    update_post_meta($post_id, '_flavor_badge_color', sanitize_text_field($_POST['flavor_badge_color'] ?? 'primary'));
}

/* ═══════════════════════════════════════════════════════════════════════════
   SECTION 4: CUSTOM SINGLE PRODUCT TEMPLATE OUTPUT
   ═══════════════════════════════════════════════════════════════════════════ */

add_action('woocommerce_before_single_product', 'flavor_render_custom_product_page', 1);
function flavor_render_custom_product_page() {
    global $product;

    if (!$product) {
        return;
    }

    // Track recently viewed
    flavor_track_recently_viewed($product->get_id());

    // Get all meta data
    $warranty = get_post_meta($product->get_id(), '_flavor_warranty', true);
    $warranty_type = get_post_meta($product->get_id(), '_flavor_warranty_type', true);
    $delivery = get_post_meta($product->get_id(), '_flavor_delivery_time', true);
    $delivery_type = get_post_meta($product->get_id(), '_flavor_delivery_type', true);
    $return_days = get_post_meta($product->get_id(), '_flavor_return_days', true);
    $country = get_post_meta($product->get_id(), '_flavor_country', true);
    $video_url = get_post_meta($product->get_id(), '_flavor_video_url', true);
    $size_guide = get_post_meta($product->get_id(), '_flavor_size_guide', true);
    $specs = get_post_meta($product->get_id(), '_flavor_specs', true);
    $ingredients = get_post_meta($product->get_id(), '_flavor_ingredients', true);
    $usage = get_post_meta($product->get_id(), '_flavor_usage', true);
    $brand_story = get_post_meta($product->get_id(), '_flavor_brand_story', true);

    $featured_badge = get_post_meta($product->get_id(), '_flavor_featured_badge', true);
    $new_badge = get_post_meta($product->get_id(), '_flavor_new_badge', true);
    $custom_badge = get_post_meta($product->get_id(), '_flavor_custom_badge', true);
    $badge_color = get_post_meta($product->get_id(), '_flavor_badge_color', true) ?: 'primary';

    $sales_badge = get_post_meta($product->get_id(), '_flavor_sales_badge', true);
    $urgency_text = get_post_meta($product->get_id(), '_flavor_urgency_text', true);
    $trust_score = get_post_meta($product->get_id(), '_flavor_trust_score', true);

    // Gallery
    $gallery_ids = $product->get_gallery_image_ids();
    $main_image_id = $product->get_image_id();

    // Product tags
    $product_tags = get_the_terms($product->get_id(), 'product_tag');

    ?>

    <!-- Breadcrumb -->
    <nav class="fsp-breadcrumb" aria-label="مسیر صفحه">
        <div class="fsp-container">
            <ol class="fsp-breadcrumb-list" itemscope itemtype="https://schema.org/BreadcrumbList">
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a href="<?php echo home_url(); ?>" itemprop="item">
                        <i class="fa-solid fa-house"></i>
                        <span itemprop="name">خانه</span>
                    </a>
                    <meta itemprop="position" content="1">
                </li>
                <li class="fsp-breadcrumb-sep"><i class="fa-solid fa-chevron-left"></i></li>
                <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" itemprop="item">
                        <span itemprop="name">فروشگاه</span>
                    </a>
                    <meta itemprop="position" content="2">
                </li>
                <?php
                $categories = get_the_terms($product->get_id(), 'product_cat');
                if ($categories && !is_wp_error($categories)) :
                    $category = array_shift($categories);
                ?>
                    <li class="fsp-breadcrumb-sep"><i class="fa-solid fa-chevron-left"></i></li>
                    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <a href="<?php echo get_term_link($category); ?>" itemprop="item">
                            <span itemprop="name"><?php echo esc_html($category->name); ?></span>
                        </a>
                        <meta itemprop="position" content="3">
                    </li>
                <?php endif; ?>
                <li class="fsp-breadcrumb-sep"><i class="fa-solid fa-chevron-left"></i></li>
                <li class="fsp-breadcrumb-current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span itemprop="name"><?php echo esc_html(wp_trim_words($product->get_name(), 8, '...')); ?></span>
                    <meta itemprop="position" content="4">
                </li>
            </ol>
        </div>
    </nav>

    <!-- Product Main Section -->
    <div class="fsp-product-wrapper">
        <div class="fsp-container">
            <div class="fsp-product-main">

                <!-- Gallery Column -->
                <div class="fsp-gallery-col">
                    <div class="fsp-gallery" id="fsp-gallery">

                        <!-- Badges -->
                        <div class="fsp-badges">
                            <?php if ($product->is_on_sale()) : ?>
                                <span class="fsp-badge fsp-badge-sale">
                                    <i class="fa-solid fa-badge-percent"></i>
                                    <?php
                                    if ($product->get_regular_price() && $product->get_sale_price()) {
                                        $percentage = round((($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price()) * 100);
                                        echo $percentage . '%';
                                    } else {
                                        echo 'حراج';
                                    }
                                    ?>
                                </span>
                            <?php endif; ?>

                            <?php if (!$product->is_in_stock()) : ?>
                                <span class="fsp-badge fsp-badge-out">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                    ناموجود
                                </span>
                            <?php endif; ?>

                            <?php if ($sales_badge) : 
                                $sales_icons = [
                                    'bestseller' => 'fa-fire-flame-curved',
                                    'trending' => 'fa-chart-line-up',
                                    'limited' => 'fa-clock',
                                    'exclusive' => 'fa-gem',
                                    'recommended' => 'fa-star'
                                ];
                                $sales_labels = [
                                    'bestseller' => 'پرفروش‌ترین',
                                    'trending' => 'ترند روز',
                                    'limited' => 'موجودی محدود',
                                    'exclusive' => 'انحصاری',
                                    'recommended' => 'توصیه می‌شود'
                                ];
                            ?>
                                <span class="fsp-badge fsp-badge-sales">
                                    <i class="fa-solid <?php echo $sales_icons[$sales_badge]; ?>"></i>
                                    <?php echo $sales_labels[$sales_badge]; ?>
                                </span>
                            <?php endif; ?>

                            <?php if ($featured_badge) : ?>
                                <span class="fsp-badge fsp-badge-featured">
                                    <i class="fa-solid fa-star"></i>
                                    ویژه
                                </span>
                            <?php endif; ?>

                            <?php if ($new_badge) : ?>
                                <span class="fsp-badge fsp-badge-new">
                                    <i class="fa-solid fa-sparkles"></i>
                                    جدید
                                </span>
                            <?php endif; ?>

                            <?php if ($custom_badge) : 
                                $color_classes = [
                                    'primary' => 'fsp-badge-primary',
                                    'red' => 'fsp-badge-red',
                                    'blue' => 'fsp-badge-blue',
                                    'orange' => 'fsp-badge-orange',
                                    'purple' => 'fsp-badge-purple',
                                    'gold' => 'fsp-badge-gold'
                                ];
                            ?>
                                <span class="fsp-badge <?php echo $color_classes[$badge_color] ?? 'fsp-badge-primary'; ?>">
                                    <?php echo esc_html($custom_badge); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Main Image with Zoom -->
                        <div class="fsp-gallery-main">
                            <div id="fsp-main-image" class="fsp-main-image" data-zoom="true">
                                <?php if ($main_image_id) : 
                                    $large_url = wp_get_attachment_image_url($main_image_id, 'large');
                                    $full_url = wp_get_attachment_image_url($main_image_id, 'full');
                                    $thumb_url = wp_get_attachment_image_url($main_image_id, 'thumbnail');
                                ?>
                                    <div class="fsp-image-placeholder" style="background-image: url(<?php echo esc_url($thumb_url); ?>);"></div>
                                    <img id="fsp-main-img" 
                                         class="fsp-main-img" 
                                         src="<?php echo esc_url($large_url); ?>" 
                                         data-large="<?php echo esc_url($large_url); ?>"
                                         data-full="<?php echo esc_url($full_url); ?>" 
                                         alt="<?php echo esc_attr($product->get_name()); ?>"
                                         loading="eager">
                                    <div id="fsp-zoom-lens" class="fsp-zoom-lens"></div>
                                    <div id="fsp-zoom-result" class="fsp-zoom-result"></div>
                                <?php else : ?>
                                    <div class="fsp-no-image">
                                        <i class="fa-solid fa-image"></i>
                                        <span>تصویر موجود نیست</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Gallery Controls -->
                            <div class="fsp-gallery-controls">
                                <button type="button" class="fsp-gallery-ctrl fsp-wishlist-btn" title="افزودن به علاقه‌مندی‌ها" aria-label="افزودن به علاقه‌مندی‌ها">
                                    <i class="fa-regular fa-heart"></i>
                                </button>
                                <button type="button" class="fsp-gallery-ctrl fsp-compare-btn" title="مقایسه" aria-label="افزودن به مقایسه">
                                    <i class="fa-solid fa-code-compare"></i>
                                </button>
                                <button type="button" id="fsp-fullscreen-btn" class="fsp-gallery-ctrl" title="تمام صفحه" aria-label="نمایش تمام صفحه">
                                    <i class="fa-solid fa-expand"></i>
                                </button>
                                <?php if ($video_url) : ?>
                                    <button type="button" id="fsp-video-btn" class="fsp-gallery-ctrl fsp-video-ctrl" title="مشاهده ویدیو" aria-label="پخش ویدیو">
                                        <i class="fa-solid fa-circle-play"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Thumbnails -->
                        <?php if (!empty($gallery_ids) || $main_image_id) : ?>
                            <div class="fsp-thumbs">
                                <button type="button" id="fsp-thumb-prev" class="fsp-thumb-nav" aria-label="تصویر قبلی">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </button>

                                <div id="fsp-thumbs-track" class="fsp-thumbs-track">
                                    <?php if ($main_image_id) : 
                                        $thumb_url = wp_get_attachment_image_url($main_image_id, 'thumbnail');
                                        $large_url = wp_get_attachment_image_url($main_image_id, 'large');
                                        $full_url = wp_get_attachment_image_url($main_image_id, 'full');
                                    ?>
                                        <button type="button" class="fsp-thumb active" 
                                             data-large="<?php echo esc_url($large_url); ?>" 
                                             data-full="<?php echo esc_url($full_url); ?>"
                                             aria-label="تصویر 1">
                                            <img src="<?php echo esc_url($thumb_url); ?>" alt="" loading="lazy">
                                        </button>
                                    <?php endif; ?>

                                    <?php 
                                    $thumb_index = 2;
                                    foreach ($gallery_ids as $gallery_id) : 
                                        $thumb_url = wp_get_attachment_image_url($gallery_id, 'thumbnail');
                                        $large_url = wp_get_attachment_image_url($gallery_id, 'large');
                                        $full_url = wp_get_attachment_image_url($gallery_id, 'full');
                                    ?>
                                        <button type="button" class="fsp-thumb" 
                                             data-large="<?php echo esc_url($large_url); ?>" 
                                             data-full="<?php echo esc_url($full_url); ?>"
                                             aria-label="تصویر <?php echo $thumb_index; ?>">
                                            <img src="<?php echo esc_url($thumb_url); ?>" alt="" loading="lazy">
                                        </button>
                                    <?php 
                                        $thumb_index++;
                                    endforeach; 
                                    ?>

                                    <?php if ($video_url) : ?>
                                        <button type="button" id="fsp-thumb-video" class="fsp-thumb fsp-thumb-video" aria-label="ویدیو محصول">
                                            <i class="fa-solid fa-circle-play"></i>
                                            <span>ویدیو</span>
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <button type="button" id="fsp-thumb-next" class="fsp-thumb-nav" aria-label="تصویر بعدی">
                                    <i class="fa-solid fa-chevron-left"></i>
                                </button>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

                <!-- Info Column -->
                <div class="fsp-info-col">
                    <div class="fsp-info">

                        <!-- Header -->
                        <header class="fsp-header">
                            <?php
                            $terms = get_the_terms($product->get_id(), 'product_brand');
                            if ($terms && !is_wp_error($terms)) :
                                $brand = array_shift($terms);
                            ?>
                                <a href="<?php echo esc_url(get_term_link($brand)); ?>" class="fsp-brand">
                                    <i class="fa-solid fa-award"></i>
                                    <?php echo esc_html($brand->name); ?>
                                </a>
                            <?php endif; ?>

                            <h1 class="fsp-title" itemprop="name"><?php echo esc_html($product->get_name()); ?></h1>

                            <div class="fsp-meta-row">
                                <?php
                                $rating_count = $product->get_rating_count();
                                $average = $product->get_average_rating();

                                if ($rating_count > 0) : ?>
                                    <div class="fsp-rating" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                                        <div class="fsp-stars">
                                            <?php
                                            for ($i = 1; $i <= 5; $i++) {
                                                if ($i <= floor($average)) {
                                                    echo '<i class="fa-solid fa-star"></i>';
                                                } elseif ($i == ceil($average) && $average - floor($average) >= 0.5) {
                                                    echo '<i class="fa-solid fa-star-half-stroke"></i>';
                                                } else {
                                                    echo '<i class="fa-regular fa-star"></i>';
                                                }
                                            }
                                            ?>
                                        </div>
                                        <span class="fsp-rating-value" itemprop="ratingValue"><?php echo number_format($average, 1); ?></span>
                                        <span class="fsp-rating-count">(<span itemprop="reviewCount"><?php echo $rating_count; ?></span> نظر)</span>
                                    </div>
                                <?php elseif ($trust_score) : ?>
                                    <div class="fsp-rating">
                                        <div class="fsp-stars">
                                            <?php
                                            for ($i = 1; $i <= 5; $i++) {
                                                echo $i <= $trust_score ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star"></i>';
                                            }
                                            ?>
                                        </div>
                                        <span class="fsp-rating-count">(امتیاز اعتماد)</span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($product->get_sku()) : ?>
                                    <div class="fsp-sku">
                                        <i class="fa-solid fa-barcode"></i>
                                        <span>کد:</span>
                                        <strong itemprop="sku"><?php echo esc_html($product->get_sku()); ?></strong>
                                    </div>
                                <?php endif; ?>

                                <div class="fsp-stock <?php echo $product->is_in_stock() ? 'in-stock' : 'out-stock'; ?>" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                    <link itemprop="availability" href="https://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>">
                                    <i class="fa-solid fa-<?php echo $product->is_in_stock() ? 'circle-check' : 'circle-xmark'; ?>"></i>
                                    <?php
                                    if ($product->is_in_stock()) {
                                        echo 'موجود در انبار';
                                        $stock = $product->get_stock_quantity();
                                        if ($stock && $stock <= 5 && $urgency_text) {
                                            echo ' <span class="fsp-low-stock">' . esc_html($urgency_text) . '</span>';
                                        } elseif ($stock && $stock <= 5) {
                                            echo ' <span class="fsp-low-stock">(تنها ' . $stock . ' عدد)</span>';
                                        }
                                    } else {
                                        echo 'ناموجود';
                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- Product Tags as Hashtags -->
                            <?php if ($product_tags && !is_wp_error($product_tags)) : ?>
                                <div class="fsp-hashtags">
                                    <?php foreach ($product_tags as $tag) : ?>
                                        <a href="<?php echo esc_url(get_term_link($tag)); ?>" class="fsp-hashtag">
                                            #<?php echo esc_html($tag->name); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </header>

                        <!-- Short Description -->
                        <?php if ($product->get_short_description()) : ?>
                            <div class="fsp-short-desc" itemprop="description">
                                <?php echo wp_kses_post($product->get_short_description()); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Quick Features -->
                        <?php if ($warranty || $delivery || $return_days || $country) : ?>
                            <div class="fsp-quick-features">
                                <?php if ($warranty) : 
                                    $warranty_icons = [
                                        'official' => 'fa-certificate',
                                        'company' => 'fa-building',
                                        'seller' => 'fa-store',
                                        'international' => 'fa-globe'
                                    ];
                                    $warranty_icon = $warranty_icons[$warranty_type] ?? 'fa-shield-check';
                                ?>
                                    <div class="fsp-quick-item">
                                        <div class="fsp-quick-icon">
                                            <i class="fa-solid <?php echo $warranty_icon; ?>"></i>
                                        </div>
                                        <div class="fsp-quick-content">
                                            <strong>گارانتی</strong>
                                            <span><?php echo esc_html($warranty); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($delivery) : 
                                    $delivery_icons = [
                                        'express' => 'fa-truck-fast',
                                        'standard' => 'fa-truck',
                                        'free' => 'fa-box-open',
                                        'next_day' => 'fa-rocket'
                                    ];
                                    $delivery_icon = $delivery_icons[$delivery_type] ?? 'fa-truck-fast';
                                ?>
                                    <div class="fsp-quick-item">
                                        <div class="fsp-quick-icon">
                                            <i class="fa-solid <?php echo $delivery_icon; ?>"></i>
                                        </div>
                                        <div class="fsp-quick-content">
                                            <strong>ارسال</strong>
                                            <span><?php echo esc_html($delivery); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($return_days) : ?>
                                    <div class="fsp-quick-item">
                                        <div class="fsp-quick-icon">
                                            <i class="fa-solid fa-rotate-left"></i>
                                        </div>
                                        <div class="fsp-quick-content">
                                            <strong>بازگشت کالا</strong>
                                            <span><?php echo $return_days; ?> روز ضمانت</span>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($country) : ?>
                                    <div class="fsp-quick-item">
                                        <div class="fsp-quick-icon">
                                            <i class="fa-solid fa-earth-americas"></i>
                                        </div>
                                        <div class="fsp-quick-content">
                                            <strong>کشور سازنده</strong>
                                            <span><?php echo esc_html($country); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Price Box -->
                        <div class="fsp-price-box">
                            <div class="fsp-price-inner">
                                <?php if ($product->is_on_sale() && $product->get_regular_price()) : ?>
                                    <div class="fsp-price-old">
                                        <del><?php echo wc_price($product->get_regular_price()); ?></del>
                                        <?php
                                        $saved = $product->get_regular_price() - $product->get_sale_price();
                                        if ($saved > 0) :
                                        ?>
                                            <span class="fsp-saved">
                                                <i class="fa-solid fa-badge-check"></i>
                                                <?php echo wc_price($saved); ?> سود شما
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="fsp-price-current">
                                    <span class="fsp-price-label">قیمت:</span>
                                    <span class="fsp-price-value" itemprop="price" content="<?php echo esc_attr($product->get_price()); ?>">
                                        <?php echo $product->get_price_html(); ?>
                                    </span>
                                    <meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>">
                                </div>

                                <div class="fsp-installment">
                                    <i class="fa-solid fa-credit-card"></i>
                                    <span>خرید اقساطی از ۱۲ ماه</span>
                                    <a href="#" class="fsp-installment-link">جزئیات</a>
                                </div>
                            </div>
                        </div>

                        <!-- Variations -->
                        <?php if ($product->is_type('variable')) : ?>
                            <div class="fsp-variations">
                                <?php woocommerce_variable_add_to_cart(); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Size Guide Button -->
                        <?php if ($size_guide) : ?>
                            <button type="button" id="fsp-size-guide-btn" class="fsp-size-guide-btn">
                                <i class="fa-solid fa-ruler"></i>
                                راهنمای انتخاب سایز
                            </button>
                        <?php endif; ?>

                        <!-- Add to Cart -->
                        <?php if ($product->is_in_stock()) : ?>
                            <div class="fsp-add-to-cart">
                                <?php if (!$product->is_type('variable')) : ?>
                                    <div class="fsp-quantity-wrapper">
                                        <button type="button" class="fsp-qty-btn fsp-qty-minus" aria-label="کاهش تعداد">
                                            <i class="fa-solid fa-minus"></i>
                                        </button>
                                        <input type="number" id="fsp-quantity" class="fsp-quantity" value="1" min="1" max="<?php echo $product->get_stock_quantity() ?: 99; ?>">
                                        <button type="button" class="fsp-qty-btn fsp-qty-plus" aria-label="افزایش تعداد">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </div>
                                <?php endif; ?>
                                <button type="button" 
                                        class="fsp-add-btn" 
                                        data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                                        data-product-type="<?php echo esc_attr($product->get_type()); ?>">
                                    <i class="fa-solid fa-cart-plus"></i>
                                    <span>افزودن به سبد خرید</span>
                                    <span class="fsp-btn-loading">
                                        <i class="fa-solid fa-spinner fa-spin"></i>
                                    </span>
                                </button>
                            </div>
                        <?php else : ?>
                            <div class="fsp-out-stock-box">
                                <i class="fa-solid fa-bell-exclamation"></i>
                                <strong>این محصول موجود نیست</strong>
                                <p>در صورت موجود شدن مجدد، به شما اطلاع خواهیم داد.</p>
                                <form class="fsp-notify-form">
                                    <input type="email" placeholder="ایمیل خود را وارد کنید" required>
                                    <button type="submit">
                                        <i class="fa-solid fa-bell"></i>
                                        اطلاع بده
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>

                        <!-- Action Buttons -->
                        <div class="fsp-action-buttons">
                            <button type="button" class="fsp-action-btn fsp-btn-wishlist" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                                <i class="fa-regular fa-heart"></i>
                                <span>علاقه‌مندی</span>
                            </button>

                            <button type="button" class="fsp-action-btn fsp-btn-compare" data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                                <i class="fa-solid fa-code-compare"></i>
                                <span>مقایسه</span>
                            </button>

                            <div class="fsp-share-wrapper">
                                <button type="button" id="fsp-share-btn" class="fsp-action-btn">
                                    <i class="fa-solid fa-share-nodes"></i>
                                    <span>اشتراک‌گذاری</span>
                                </button>

                                <div id="fsp-share-dropdown" class="fsp-share-dropdown">
                                    <?php
                                    $url = get_permalink();
                                    $title = get_the_title();
                                    ?>
                                                                        <a href="https://telegram.me/share/url?url=<?php echo rawurlencode($url); ?>&text=<?php echo rawurlencode($title); ?>" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="fsp-share-item fsp-share-telegram" 
                                       title="اشتراک در تلگرام">
                                        <i class="fa-brands fa-telegram"></i>
                                    </a>
                                    <a href="https://wa.me/?text=<?php echo rawurlencode($title . ' ' . $url); ?>" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="fsp-share-item fsp-share-whatsapp" 
                                       title="اشتراک در واتساپ">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode($url); ?>&text=<?php echo rawurlencode($title); ?>" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="fsp-share-item fsp-share-twitter" 
                                       title="اشتراک در توییتر">
                                        <i class="fa-brands fa-x-twitter"></i>
                                    </a>
                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo rawurlencode($url); ?>" 
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       class="fsp-share-item fsp-share-linkedin" 
                                       title="اشتراک در لینکدین">
                                        <i class="fa-brands fa-linkedin-in"></i>
                                    </a>
                                    <button type="button" 
                                            class="fsp-share-item fsp-share-copy" 
                                            data-url="<?php echo esc_url($url); ?>" 
                                            title="کپی لینک">
                                        <i class="fa-solid fa-link"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Trust Badges -->
                        <div class="fsp-trust-badges">
                            <div class="fsp-trust-item">
                                <div class="fsp-trust-icon">
                                    <i class="fa-solid fa-badge-check"></i>
                                </div>
                                <span>اصالت کالا</span>
                            </div>
                            <div class="fsp-trust-item">
                                <div class="fsp-trust-icon">
                                    <i class="fa-solid fa-truck-fast"></i>
                                </div>
                                <span>ارسال سریع</span>
                            </div>
                            <div class="fsp-trust-item">
                                <div class="fsp-trust-icon">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                <span>ضمانت بازگشت</span>
                            </div>
                            <div class="fsp-trust-item">
                                <div class="fsp-trust-icon">
                                    <i class="fa-solid fa-headset"></i>
                                </div>
                                <span>پشتیبانی ۲۴/۷</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Tabs Section -->
    <section class="fsp-tabs-section">
        <div class="fsp-container">
            <div id="fsp-tabs" class="fsp-tabs">

                <!-- Tabs Navigation -->
                <div class="fsp-tabs-nav" role="tablist">
                    <button type="button" 
                            class="fsp-tab-btn active" 
                            data-tab="description"
                            role="tab"
                            aria-selected="true"
                            aria-controls="tab-description">
                        <i class="fa-solid fa-align-right"></i>
                        <span>توضیحات</span>
                    </button>

                    <?php if (!empty($specs) && is_array($specs)) : ?>
                        <button type="button" 
                                class="fsp-tab-btn" 
                                data-tab="specs"
                                role="tab"
                                aria-selected="false"
                                aria-controls="tab-specs">
                            <i class="fa-solid fa-list-check"></i>
                            <span>مشخصات فنی</span>
                        </button>
                    <?php endif; ?>

                    <?php if ($ingredients || $usage) : ?>
                        <button type="button" 
                                class="fsp-tab-btn" 
                                data-tab="details"
                                role="tab"
                                aria-selected="false"
                                aria-controls="tab-details">
                            <i class="fa-solid fa-circle-info"></i>
                            <span>جزئیات بیشتر</span>
                        </button>
                    <?php endif; ?>

                    <?php if ($video_url) : ?>
                        <button type="button" 
                                class="fsp-tab-btn" 
                                data-tab="video"
                                role="tab"
                                aria-selected="false"
                                aria-controls="tab-video">
                            <i class="fa-solid fa-circle-play"></i>
                            <span>ویدیو</span>
                        </button>
                    <?php endif; ?>

                    <?php if ($product->get_review_count() > 0 || comments_open()) : ?>
                        <button type="button" 
                                class="fsp-tab-btn" 
                                data-tab="reviews"
                                role="tab"
                                aria-selected="false"
                                aria-controls="tab-reviews">
                            <i class="fa-solid fa-comments"></i>
                            <span>نظرات</span>
                            <?php if ($product->get_review_count() > 0) : ?>
                                <em class="fsp-tab-count"><?php echo $product->get_review_count(); ?></em>
                            <?php endif; ?>
                        </button>
                    <?php endif; ?>
                </div>

                <!-- Tabs Content -->
                <div class="fsp-tabs-content">

                    <!-- Description Tab -->
                    <div id="tab-description" class="fsp-tab-panel active" role="tabpanel" aria-hidden="false">
                        <div class="fsp-description-content">
                            <?php
                            if ($product->get_description()) {
                                echo wp_kses_post($product->get_description());
                            } else {
                                echo '<div class="fsp-no-content">';
                                echo '<i class="fa-solid fa-circle-info"></i>';
                                echo '<p>توضیحاتی برای این محصول ثبت نشده است.</p>';
                                echo '</div>';
                            }
                            ?>
                        </div>

                        <?php if ($brand_story) : ?>
                            <div class="fsp-brand-story">
                                <h3>
                                    <i class="fa-solid fa-book-open"></i>
                                    داستان برند
                                </h3>
                                <div class="fsp-brand-story-content">
                                    <?php echo wp_kses_post(nl2br($brand_story)); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Specifications Tab -->
                    <?php if (!empty($specs) && is_array($specs)) : ?>
                        <div id="tab-specs" class="fsp-tab-panel" role="tabpanel" aria-hidden="true">
                            <div class="fsp-specs-wrapper">
                                <table class="fsp-specs-table">
                                    <tbody>
                                        <?php foreach ($specs as $spec) : ?>
                                            <?php if (!empty($spec['label']) && !empty($spec['value'])) : ?>
                                                <tr>
                                                    <th>
                                                        <i class="fa-solid fa-circle-small"></i>
                                                        <?php echo esc_html($spec['label']); ?>
                                                    </th>
                                                    <td><?php echo esc_html($spec['value']); ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Details Tab -->
                    <?php if ($ingredients || $usage) : ?>
                        <div id="tab-details" class="fsp-tab-panel" role="tabpanel" aria-hidden="true">
                            <?php if ($ingredients) : ?>
                                <div class="fsp-detail-section">
                                    <h3>
                                        <i class="fa-solid fa-flask"></i>
                                        مواد تشکیل‌دهنده
                                    </h3>
                                    <div class="fsp-detail-content">
                                        <?php echo wp_kses_post(nl2br($ingredients)); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($usage) : ?>
                                <div class="fsp-detail-section">
                                    <h3>
                                        <i class="fa-solid fa-hand-holding-medical"></i>
                                        نحوه استفاده
                                    </h3>
                                    <div class="fsp-detail-content">
                                        <?php echo wp_kses_post(nl2br($usage)); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Video Tab -->
                    <?php if ($video_url) : ?>
                        <div id="tab-video" class="fsp-tab-panel" role="tabpanel" aria-hidden="true">
                            <div class="fsp-video-wrapper">
                                <?php
                                $embed_url = $video_url;

                                // YouTube
                                if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $video_url, $id)) {
                                    $embed_url = 'https://www.youtube.com/embed/' . $id[1];
                                } elseif (preg_match('/youtu\.be\/([^\&\?\/]+)/', $video_url, $id)) {
                                    $embed_url = 'https://www.youtube.com/embed/' . $id[1];
                                }

                                // Aparat
                                if (strpos($video_url, 'aparat.com') !== false) {
                                    if (preg_match('/aparat\.com\/v\/([^\&\?\/]+)/', $video_url, $id)) {
                                        $embed_url = 'https://www.aparat.com/video/video/embed/videohash/' . $id[1] . '/vt/frame';
                                    }
                                }
                                ?>
                                <iframe src="<?php echo esc_url($embed_url); ?>"
                                        allowfullscreen
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        loading="lazy"></iframe>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Reviews Tab -->
                    <?php if ($product->get_review_count() > 0 || comments_open()) : ?>
                        <div id="tab-reviews" class="fsp-tab-panel" role="tabpanel" aria-hidden="true">
                            <div class="fsp-reviews-wrapper">
                                <?php comments_template(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    <?php
    $related_ids = wc_get_related_products($product->get_id(), 4);
    if (!empty($related_ids)) :
    ?>
        <section class="fsp-related-section">
            <div class="fsp-container">
                <div class="fsp-section-header">
                    <h2>
                        <i class="fa-solid fa-layer-group"></i>
                        محصولات مرتبط
                    </h2>
                    <a href="<?php echo get_permalink(wc_get_page_id('shop')); ?>" class="fsp-view-all">
                        مشاهده همه
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                </div>

                <div class="fsp-products-grid">
                    <?php foreach ($related_ids as $related_id) :
                        $related_product = wc_get_product($related_id);
                        if (!$related_product) continue;
                    ?>
                        <article class="fsp-product-card">
                            <a href="<?php echo get_permalink($related_id); ?>" class="fsp-product-image">
                                <?php echo $related_product->get_image('woocommerce_thumbnail'); ?>
                                <?php if ($related_product->is_on_sale()) : ?>
                                    <span class="fsp-card-badge fsp-badge-sale">
                                        <?php
                                        if ($related_product->get_regular_price() && $related_product->get_sale_price()) {
                                            $percentage = round((($related_product->get_regular_price() - $related_product->get_sale_price()) / $related_product->get_regular_price()) * 100);
                                            echo $percentage . '%';
                                        } else {
                                            echo 'حراج';
                                        }
                                        ?>
                                    </span>
                                <?php endif; ?>
                                <div class="fsp-card-overlay">
                                    <button type="button" class="fsp-card-btn fsp-quick-view" data-product-id="<?php echo $related_id; ?>" title="مشاهده سریع">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <button type="button" class="fsp-card-btn fsp-add-wishlist" data-product-id="<?php echo $related_id; ?>" title="افزودن به علاقه‌مندی">
                                        <i class="fa-regular fa-heart"></i>
                                    </button>
                                </div>
                            </a>

                            <div class="fsp-product-info">
                                <?php
                                $related_rating = $related_product->get_average_rating();
                                if ($related_rating > 0) :
                                ?>
                                    <div class="fsp-card-rating">
                                        <i class="fa-solid fa-star"></i>
                                        <span><?php echo number_format($related_rating, 1); ?></span>
                                    </div>
                                <?php endif; ?>

                                <h3 class="fsp-product-title">
                                    <a href="<?php echo get_permalink($related_id); ?>">
                                        <?php echo esc_html($related_product->get_name()); ?>
                                    </a>
                                </h3>

                                <div class="fsp-product-price">
                                    <?php echo $related_product->get_price_html(); ?>
                                </div>

                                <?php if ($related_product->is_in_stock()) : ?>
                                    <button type="button" 
                                            class="fsp-card-add-btn" 
                                            data-product-id="<?php echo $related_id; ?>">
                                        <i class="fa-solid fa-cart-plus"></i>
                                        <span>افزودن</span>
                                    </button>
                                <?php else : ?>
                                    <span class="fsp-card-out-stock">ناموجود</span>
                                <?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Recently Viewed Products -->
    <?php
    $recently_viewed = flavor_get_recently_viewed();
    if (!empty($recently_viewed) && count($recently_viewed) > 1) :
    ?>
        <section class="fsp-recently-section">
            <div class="fsp-container">
                <div class="fsp-section-header">
                    <h2>
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        بازدیدهای اخیر شما
                    </h2>
                </div>

                <div class="fsp-products-grid fsp-products-slider">
                    <?php
                    $count = 0;
                    foreach ($recently_viewed as $viewed_id) :
                        if ($viewed_id == $product->get_id() || $count >= 6) continue;

                        $viewed_product = wc_get_product($viewed_id);
                        if (!$viewed_product) continue;
                        $count++;
                    ?>
                        <article class="fsp-product-card">
                            <a href="<?php echo get_permalink($viewed_id); ?>" class="fsp-product-image">
                                <?php echo $viewed_product->get_image('woocommerce_thumbnail'); ?>
                                <?php if ($viewed_product->is_on_sale()) : ?>
                                    <span class="fsp-card-badge fsp-badge-sale">
                                        <?php
                                        if ($viewed_product->get_regular_price() && $viewed_product->get_sale_price()) {
                                            $percentage = round((($viewed_product->get_regular_price() - $viewed_product->get_sale_price()) / $viewed_product->get_regular_price()) * 100);
                                            echo $percentage . '%';
                                        } else {
                                            echo 'حراج';
                                        }
                                        ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                            <div class="fsp-product-info">
                                <h3 class="fsp-product-title">
                                    <a href="<?php echo get_permalink($viewed_id); ?>">
                                        <?php echo esc_html($viewed_product->get_name()); ?>
                                    </a>
                                </h3>
                                <div class="fsp-product-price">
                                    <?php echo $viewed_product->get_price_html(); ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Lightbox Modal -->
    <div id="fsp-lightbox" class="fsp-lightbox" role="dialog" aria-modal="true" aria-label="گالری تصاویر">
        <button type="button" class="fsp-lightbox-close" aria-label="بستن">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <button type="button" class="fsp-lightbox-nav fsp-lightbox-prev" aria-label="تصویر قبلی">
            <i class="fa-solid fa-chevron-right"></i>
        </button>

        <div class="fsp-lightbox-content">
            <div class="fsp-lightbox-loader">
                <i class="fa-solid fa-spinner fa-spin"></i>
            </div>
            <img id="fsp-lightbox-img" src="" alt="">
        </div>

        <button type="button" class="fsp-lightbox-nav fsp-lightbox-next" aria-label="تصویر بعدی">
            <i class="fa-solid fa-chevron-left"></i>
        </button>

        <div class="fsp-lightbox-footer">
            <div class="fsp-lightbox-counter">
                <span id="fsp-lightbox-current">1</span>
                <span>/</span>
                <span id="fsp-lightbox-total">1</span>
            </div>
            <div class="fsp-lightbox-actions">
                <button type="button" class="fsp-lightbox-btn fsp-lightbox-zoom" title="بزرگنمایی">
                    <i class="fa-solid fa-magnifying-glass-plus"></i>
                </button>
                <button type="button" class="fsp-lightbox-btn fsp-lightbox-download" title="دانلود">
                    <i class="fa-solid fa-download"></i>
                </button>
            </div>
        </div>

        <!-- Thumbnails strip in lightbox -->
        <div class="fsp-lightbox-thumbs">
            <?php if ($main_image_id) : ?>
                <button type="button" class="fsp-lightbox-thumb active" data-index="0">
                    <?php echo wp_get_attachment_image($main_image_id, 'thumbnail'); ?>
                </button>
            <?php endif; ?>
            <?php 
            $thumb_idx = 1;
            foreach ($gallery_ids as $gallery_id) : 
            ?>
                <button type="button" class="fsp-lightbox-thumb" data-index="<?php echo $thumb_idx; ?>">
                    <?php echo wp_get_attachment_image($gallery_id, 'thumbnail'); ?>
                </button>
            <?php 
                $thumb_idx++;
            endforeach; 
            ?>
        </div>
    </div>

    <!-- Video Modal -->
    <?php if ($video_url) : ?>
        <div id="fsp-video-modal" class="fsp-modal" role="dialog" aria-modal="true" aria-label="ویدیو محصول">
            <div class="fsp-modal-overlay" data-close="true"></div>
            <div class="fsp-modal-content fsp-modal-video">
                <button type="button" class="fsp-modal-close" aria-label="بستن">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <div class="fsp-video-container">
                    <?php
                    $video_embed = $video_url;
                    if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $video_url, $vid)) {
                        $video_embed = 'https://www.youtube.com/embed/' . $vid[1] . '?autoplay=1';
                    } elseif (preg_match('/youtu\.be\/([^\&\?\/]+)/', $video_url, $vid)) {
                        $video_embed = 'https://www.youtube.com/embed/' . $vid[1] . '?autoplay=1';
                    } elseif (strpos($video_url, 'aparat.com') !== false) {
                        if (preg_match('/aparat\.com\/v\/([^\&\?\/]+)/', $video_url, $vid)) {
                            $video_embed = 'https://www.aparat.com/video/video/embed/videohash/' . $vid[1] . '/vt/frame?autoplay=true';
                        }
                    }
                    ?>
                    <iframe id="fsp-video-iframe" 
                            data-src="<?php echo esc_url($video_embed); ?>" 
                            src="" 
                            allowfullscreen
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Size Guide Modal -->
    <?php if ($size_guide) : ?>
        <div id="fsp-size-modal" class="fsp-modal" role="dialog" aria-modal="true" aria-label="راهنمای سایز">
            <div class="fsp-modal-overlay" data-close="true"></div>
            <div class="fsp-modal-content">
                <div class="fsp-modal-header">
                    <h3>
                        <i class="fa-solid fa-ruler-combined"></i>
                        راهنمای انتخاب سایز
                    </h3>
                    <button type="button" class="fsp-modal-close" aria-label="بستن">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="fsp-modal-body">
                    <?php echo wp_kses_post($size_guide); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Sticky Mobile Cart -->
    <?php if ($product->is_in_stock()) : ?>
        <div id="fsp-sticky-cart" class="fsp-sticky-cart" aria-hidden="true">
            <div class="fsp-sticky-inner">
                <div class="fsp-sticky-info">
                    <div class="fsp-sticky-image">
                        <?php echo $product->get_image('thumbnail'); ?>
                    </div>
                    <div class="fsp-sticky-details">
                        <h4><?php echo esc_html(wp_trim_words($product->get_name(), 5, '...')); ?></h4>
                        <div class="fsp-sticky-price"><?php echo $product->get_price_html(); ?></div>
                    </div>
                </div>
                <button type="button" class="fsp-sticky-btn" data-product-id="<?php echo $product->get_id(); ?>">
                    <i class="fa-solid fa-cart-plus"></i>
                    <span>خرید</span>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Toast Notification Container -->
    <div id="fsp-toast-container" class="fsp-toast-container" aria-live="polite"></div>

    <!-- Hide default WooCommerce output -->
    <style>
        .product > .onsale,
        .product > .woocommerce-product-gallery,
        .product > .summary,
        .product > .woocommerce-product-gallery + .summary,
        .woocommerce-breadcrumb,
        .woocommerce-tabs:not(.fsp-tabs) {
            display: none !important;
        }
    </style>

    <?php
    // Remove action to prevent infinite loop
    remove_action('woocommerce_before_single_product', 'flavor_render_custom_product_page', 1);
}

/* ═══════════════════════════════════════════════════════════════════════════
   SECTION 5: RECENTLY VIEWED TRACKING
   ═══════════════════════════════════════════════════════════════════════════ */

function flavor_track_recently_viewed($product_id) {
    if (!$product_id) return;

    $viewed = isset($_COOKIE['fsp_recently_viewed']) ? explode(',', $_COOKIE['fsp_recently_viewed']) : array();
    $viewed = array_filter(array_map('intval', $viewed));
    $viewed = array_diff($viewed, array($product_id));
    array_unshift($viewed, $product_id);
    $viewed = array_slice($viewed, 0, 12);

    setcookie('fsp_recently_viewed', implode(',', $viewed), time() + (30 * DAY_IN_SECONDS), '/');
}

function flavor_get_recently_viewed() {
    if (!isset($_COOKIE['fsp_recently_viewed'])) return array();
    
    $viewed = explode(',', $_COOKIE['fsp_recently_viewed']);
    return array_filter(array_map('intval', $viewed));
}

/* ═══════════════════════════════════════════════════════════════════════════
   SECTION 6: AJAX HANDLERS
   ═══════════════════════════════════════════════════════════════════════════ */

/**
 * AJAX Add to Cart
 */
add_action('wp_ajax_fsp_add_to_cart', 'fsp_ajax_add_to_cart');
add_action('wp_ajax_nopriv_fsp_add_to_cart', 'fsp_ajax_add_to_cart');
function fsp_ajax_add_to_cart() {
    check_ajax_referer('fsp_nonce', 'nonce');

    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $variation_id = isset($_POST['variation_id']) ? intval($_POST['variation_id']) : 0;
    $variation = isset($_POST['variation']) ? (array) $_POST['variation'] : array();

    if (!$product_id) {
        wp_send_json_error(array('message' => 'محصول نامعتبر است.'));
    }

    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error(array('message' => 'محصول یافت نشد.'));
    }

    if ($product->is_type('variable') && $variation_id) {
        $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
    } else {
        $cart_item_key = WC()->cart->add_to_cart($product_id, $quantity);
    }

    if ($cart_item_key) {
        do_action('woocommerce_ajax_added_to_cart', $product_id);

        wp_send_json_success(array(
            'message' => 'محصول به سبد خرید اضافه شد.',
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'cart_total' => WC()->cart->get_cart_total(),
            'cart_url' => wc_get_cart_url(),
            'checkout_url' => wc_get_checkout_url(),
            'fragments' => apply_filters('woocommerce_add_to_cart_fragments', array())
        ));
    } else {
        wp_send_json_error(array('message' => 'خطا در افزودن به سبد خرید.'));
    }
}

/**
 * AJAX Add to Wishlist
 */
add_action('wp_ajax_fsp_toggle_wishlist', 'fsp_ajax_toggle_wishlist');
add_action('wp_ajax_nopriv_fsp_toggle_wishlist', 'fsp_ajax_toggle_wishlist');
function fsp_ajax_toggle_wishlist() {
    check_ajax_referer('fsp_nonce', 'nonce');

    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

    if (!$product_id) {
        wp_send_json_error(array('message' => 'محصول نامعتبر است.'));
    }

    // Get wishlist from cookie
    $wishlist = isset($_COOKIE['fsp_wishlist']) ? explode(',', $_COOKIE['fsp_wishlist']) : array();
    $wishlist = array_filter(array_map('intval', $wishlist));

    $added = false;
    if (in_array($product_id, $wishlist)) {
        $wishlist = array_diff($wishlist, array($product_id));
        $message = 'از علاقه‌مندی‌ها حذف شد.';
    } else {
        $wishlist[] = $product_id;
        $added = true;
        $message = 'به علاقه‌مندی‌ها اضافه شد.';
    }

    setcookie('fsp_wishlist', implode(',', $wishlist), time() + (365 * DAY_IN_SECONDS), '/');

    wp_send_json_success(array(
        'message' => $message,
        'added' => $added,
        'count' => count($wishlist)
    ));
}

/* ═══════════════════════════════════════════════════════════════════════════
   SECTION 7: ENQUEUE ASSETS
   ═══════════════════════════════════════════════════════════════════════════ */

add_action('wp_enqueue_scripts', 'fsp_enqueue_assets', 20);
function fsp_enqueue_assets() {
    if (!is_product()) return;

    // CSS
    wp_enqueue_style(
        'fsp-single-product',
        get_stylesheet_directory_uri() . '/css/fsp-single-product.css',
        array(),
        '4.0.0'
    );

    // JS
    wp_enqueue_script(
        'fsp-single-product',
        get_stylesheet_directory_uri() . '/js/fsp-single-product.js',
        array('jquery'),
        '4.0.0',
        true
    );

    // Localize script
    wp_localize_script('fsp-single-product', 'fspData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('fsp_nonce'),
        'cartUrl' => wc_get_cart_url(),
        'checkoutUrl' => wc_get_checkout_url(),
        'i18n' => array(
            'addedToCart' => 'به سبد خرید اضافه شد',
            'addedToWishlist' => 'به علاقه‌مندی‌ها اضافه شد',
            'removedFromWishlist' => 'از علاقه‌مندی‌ها حذف شد',
            'linkCopied' => 'لینک کپی شد!',
            'error' => 'خطایی رخ داد',
            'loading' => 'در حال بارگذاری...',
            'viewCart' => 'مشاهده سبد',
            'checkout' => 'تسویه حساب'
        )
    ));
}

/* ═══════════════════════════════════════════════════════════════════════════
   SECTION 8: STRUCTURED DATA (SEO)
   ═══════════════════════════════════════════════════════════════════════════ */

add_action('wp_footer', 'fsp_product_structured_data');
function fsp_product_structured_data() {
    if (!is_product()) return;

    global $product;
    if (!$product) return;

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->get_name(),
        'description' => wp_strip_all_tags($product->get_short_description()),
        'sku' => $product->get_sku(),
        'url' => get_permalink($product->get_id()),
        'offers' => array(
            '@type' => 'Offer',
            'price' => $product->get_price(),
            'priceCurrency' => get_woocommerce_currency(),
            'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'url' => get_permalink($product->get_id()),
            'priceValidUntil' => date('Y-m-d', strtotime('+1 year'))
        )
    );

    // Image
    $image_id = $product->get_image_id();
    if ($image_id) {
        $schema['image'] = wp_get_attachment_image_url($image_id, 'full');
    }

    // Rating
    if ($product->get_rating_count() > 0) {
        $schema['aggregateRating'] = array(
            '@type' => 'AggregateRating',
            'ratingValue' => $product->get_average_rating(),
            'reviewCount' => $product->get_rating_count(),
            'bestRating' => '5',
            'worstRating' => '1'
        );
    }

    // Brand
    $brands = get_the_terms($product->get_id(), 'product_brand');
    if ($brands && !is_wp_error($brands)) {
        $brand = array_shift($brands);
        $schema['brand'] = array(
            '@type' => 'Brand',
            'name' => $brand->name
        );
    }

    // Category
    $categories = get_the_terms($product->get_id(), 'product_cat');
    if ($categories && !is_wp_error($categories)) {
        $category = array_shift($categories);
        $schema['category'] = $category->name;
    }

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
}

/* ═══════════════════════════════════════════════════════════════════════════
   SECTION 9: HELPER FUNCTIONS
   ═══════════════════════════════════════════════════════════════════════════ */

/**
 * Add body class
 */
add_filter('body_class', 'fsp_body_class');
function fsp_body_class($classes) {
    if (is_product()) {
        $classes[] = 'fsp-single-product';
        $classes[] = 'fsp-enhanced-template';
    }
    return $classes;
}

/**
 * Clear recently viewed on logout
 */
add_action('wp_logout', function() {
    setcookie('fsp_recently_viewed', '', time() - 3600, '/');
});

// ═══════════════════════════════════════════════════════════════════════════
// END OF PHP TEMPLATE
// ═══════════════════════════════════════════════════════════════════════════
