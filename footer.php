/**
 * Professional Full-Width Footer - Fixed Version
 * Snippet Name: SI-Footer-Pro
 * Priority: 10
 * Location: Run Everywhere
 */

if (!defined('ABSPATH')) exit;

// ============================================
// REMOVE DEFAULT STOREFRONT FOOTER
// ============================================
add_action('init', 'si_remove_default_footer', 20);
function si_remove_default_footer() {
    remove_action('storefront_footer', 'storefront_footer_widgets', 10);
    remove_action('storefront_footer', 'storefront_credit', 20);
}

// ============================================
// REGISTER FOOTER WIDGETS
// ============================================
add_action('widgets_init', 'si_register_footer_widgets');
function si_register_footer_widgets() {
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar(array(
            'name'          => sprintf('فوتر - ستون %d', $i),
            'id'            => 'si-footer-' . $i,
            'description'   => sprintf('ویجت ستون %d فوتر', $i),
            'before_widget' => '<div id="%1$s" class="si-fw %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="si-fw-title">',
            'after_title'   => '</h4>',
        ));
    }
}

// ============================================
// RENDER FOOTER
// ============================================
add_action('storefront_footer', 'si_render_footer', 10);
function si_render_footer() {
    $logo_id = get_theme_mod('custom_logo');
    $logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : '';
    $site_name = get_bloginfo('name');
    $site_desc = get_bloginfo('description');
    
    $phone = get_theme_mod('si_footer_phone', '۰۲۱-۸۸۷۷۶۶۵۵');
    $mobile = get_theme_mod('si_footer_mobile', '۰۹۱۲-۳۴۵-۶۷۸۹');
    $email = get_theme_mod('si_footer_email', 'info@flavor-flavor.ir');
    $address = get_theme_mod('si_footer_address', 'تهران، میدان ونک، خیابان ملاصدرا');
    
    $instagram = get_theme_mod('si_social_instagram', '#');
    $telegram = get_theme_mod('si_social_telegram', '#');
    $whatsapp = get_theme_mod('si_social_whatsapp', '#');
    $twitter = get_theme_mod('si_social_twitter', '');
    $youtube = get_theme_mod('si_social_youtube', '');
    $aparat = get_theme_mod('si_social_aparat', '');
    ?>

    <!-- FEATURES BAR - Full Width -->
    <section class="sif-features">
        <div class="sif-features-inner">
            <div class="sif-feature">
                <div class="sif-feature-icon">
                    <i class="fa-solid fa-truck-fast"></i>
                </div>
                <div class="sif-feature-content">
                    <strong>ارسال سریع</strong>
                    <span>به سراسر کشور</span>
                </div>
            </div>
            <div class="sif-feature">
                <div class="sif-feature-icon">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div class="sif-feature-content">
                    <strong>ضمانت اصالت</strong>
                    <span>۱۰۰٪ اورجینال</span>
                </div>
            </div>
            <div class="sif-feature">
                <div class="sif-feature-icon">
                    <i class="fa-solid fa-arrows-rotate"></i>
                </div>
                <div class="sif-feature-content">
                    <strong>۷ روز بازگشت</strong>
                    <span>بدون قید و شرط</span>
                </div>
            </div>
            <div class="sif-feature">
                <div class="sif-feature-icon">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <div class="sif-feature-content">
                    <strong>پرداخت امن</strong>
                    <span>درگاه معتبر بانکی</span>
                </div>
            </div>
            <div class="sif-feature">
                <div class="sif-feature-icon">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div class="sif-feature-content">
                    <strong>پشتیبانی</strong>
                    <span>۲۴ ساعته</span>
                </div>
            </div>
        </div>
    </section>

    <!-- NEWSLETTER - Full Width -->
    <section class="sif-newsletter">
        <div class="sif-newsletter-inner">
            <div class="sif-newsletter-text">
                <i class="fa-solid fa-envelope-open-text"></i>
                <div>
                    <h3>عضویت در خبرنامه</h3>
                    <p>از آخرین تخفیف‌ها و محصولات جدید مطلع شوید</p>
                </div>
            </div>
            <form class="sif-newsletter-form" id="sif-newsletter-form">
                <input type="email" name="email" placeholder="ایمیل خود را وارد کنید..." required>
                <button type="submit">
                    <span>عضویت</span>
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </section>

    <!-- MAIN FOOTER - Full Width -->
    <footer class="sif-footer" id="sif-footer">
        <div class="sif-footer-inner">
            <div class="sif-footer-grid">
                
                <!-- Column 1: About -->
                <div class="sif-col sif-col-about">
                    <div class="sif-logo">
                        <?php if ($logo_url) : ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>">
                                <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($site_name); ?>">
                            </a>
                        <?php else : ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="sif-logo-text">
                                <?php echo esc_html($site_name); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <p class="sif-desc">
                        <?php echo esc_html($site_desc ?: 'فروشگاه آنلاین با ارائه بهترین محصولات با کیفیت عالی و قیمت مناسب. تجربه خریدی امن و راحت با ارسال به سراسر ایران.'); ?>
                    </p>
                    
                    <ul class="sif-contact">
                        <li>
                            <i class="fa-solid fa-phone"></i>
                            <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $phone); ?>"><?php echo esc_html($phone); ?></a>
                        </li>
                        <li>
                            <i class="fa-solid fa-mobile-screen"></i>
                            <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $mobile); ?>"><?php echo esc_html($mobile); ?></a>
                        </li>
                        <li>
                            <i class="fa-solid fa-envelope"></i>
                            <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
                        </li>
                        <li>
                            <i class="fa-solid fa-location-dot"></i>
                            <span><?php echo esc_html($address); ?></span>
                        </li>
                    </ul>
                    
                    <div class="sif-social">
                        <?php if ($instagram) : ?>
                        <a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener" aria-label="اینستاگرام" class="sif-social-instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <?php endif; ?>
                        <?php if ($telegram) : ?>
                        <a href="<?php echo esc_url($telegram); ?>" target="_blank" rel="noopener" aria-label="تلگرام" class="sif-social-telegram">
                            <i class="fa-brands fa-telegram"></i>
                        </a>
                        <?php endif; ?>
                        <?php if ($whatsapp) : ?>
                        <a href="<?php echo esc_url($whatsapp); ?>" target="_blank" rel="noopener" aria-label="واتساپ" class="sif-social-whatsapp">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                        <?php endif; ?>
                        <?php if ($twitter) : ?>
                        <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener" aria-label="توییتر" class="sif-social-twitter">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                        <?php endif; ?>
                        <?php if ($youtube) : ?>
                        <a href="<?php echo esc_url($youtube); ?>" target="_blank" rel="noopener" aria-label="یوتیوب" class="sif-social-youtube">
                            <i class="fa-brands fa-youtube"></i>
                        </a>
                        <?php endif; ?>
                        <?php if ($aparat) : ?>
                        <a href="<?php echo esc_url($aparat); ?>" target="_blank" rel="noopener" aria-label="آپارات" class="sif-social-aparat">
                            <i class="fa-solid fa-play"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Column 2: Quick Links -->
                <div class="sif-col">
                    <?php if (is_active_sidebar('si-footer-1')) : ?>
                        <?php dynamic_sidebar('si-footer-1'); ?>
                    <?php else : ?>
                        <h4 class="sif-title">دسترسی سریع</h4>
                        <ul class="sif-links">
                            <li><a href="<?php echo esc_url(home_url('/')); ?>">صفحه اصلی</a></li>
                            <li><a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">فروشگاه</a></li>
                            <li><a href="<?php echo esc_url(home_url('/blog/')); ?>">وبلاگ</a></li>
                            <li><a href="<?php echo esc_url(home_url('/about-us/')); ?>">درباره ما</a></li>
                            <li><a href="<?php echo esc_url(home_url('/contact-us/')); ?>">تماس با ما</a></li>
                        </ul>
                    <?php endif; ?>
                </div>
                
                <!-- Column 3: Customer Service -->
                <div class="sif-col">
                    <?php if (is_active_sidebar('si-footer-2')) : ?>
                        <?php dynamic_sidebar('si-footer-2'); ?>
                    <?php else : ?>
                        <h4 class="sif-title">خدمات مشتریان</h4>
                        <ul class="sif-links">
                            <li><a href="<?php echo esc_url(get_permalink(wc_get_page_id('myaccount'))); ?>">حساب کاربری</a></li>
                            <li><a href="<?php echo esc_url(home_url('/order-tracking/')); ?>">پیگیری سفارش</a></li>
                            <li><a href="<?php echo esc_url(home_url('/faq/')); ?>">سوالات متداول</a></li>
                            <li><a href="<?php echo esc_url(home_url('/shipping-policy/')); ?>">شیوه‌های ارسال</a></li>
                            <li><a href="<?php echo esc_url(home_url('/return-policy/')); ?>">شرایط بازگشت کالا</a></li>
                            <li><a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>">حریم خصوصی</a></li>
                        </ul>
                    <?php endif; ?>
                </div>
                
                <!-- Column 4: Categories -->
                <div class="sif-col">
                    <?php if (is_active_sidebar('si-footer-3')) : ?>
                        <?php dynamic_sidebar('si-footer-3'); ?>
                    <?php else : ?>
                        <h4 class="sif-title">دسته‌بندی‌ها</h4>
                        <ul class="sif-links">
                            <?php
                            $cats = get_terms(array(
                                'taxonomy'   => 'product_cat',
                                'hide_empty' => true,
                                'parent'     => 0,
                                'number'     => 6,
                                'orderby'    => 'count',
                                'order'      => 'DESC'
                            ));
                            if (!empty($cats) && !is_wp_error($cats)) :
                                foreach ($cats as $cat) :
                            ?>
                            <li><a href="<?php echo esc_url(get_term_link($cat)); ?>"><?php echo esc_html($cat->name); ?></a></li>
                            <?php 
                                endforeach;
                            endif;
                            ?>
                        </ul>
                    <?php endif; ?>
                </div>
                
                <!-- Column 5: Trust & Apps -->
                <div class="sif-col sif-col-trust">
                    <?php if (is_active_sidebar('si-footer-4')) : ?>
                        <?php dynamic_sidebar('si-footer-4'); ?>
                    <?php else : ?>
                        <h4 class="sif-title">نماد اعتماد</h4>
                        <div class="sif-badges">
                            <a href="#" target="_blank" rel="noopener" class="sif-badge">
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 120'%3E%3Crect fill='%23ffffff' width='100' height='120' rx='8'/%3E%3Ctext x='50' y='55' text-anchor='middle' fill='%23666' font-size='9' font-family='Tahoma'%3Eنماد اعتماد%3C/text%3E%3Ctext x='50' y='70' text-anchor='middle' fill='%23666' font-size='9' font-family='Tahoma'%3Eالکترونیکی%3C/text%3E%3C/svg%3E" alt="نماد اعتماد">
                            </a>
                            <a href="#" target="_blank" rel="noopener" class="sif-badge">
                                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 120'%3E%3Crect fill='%23ffffff' width='100' height='120' rx='8'/%3E%3Ctext x='50' y='60' text-anchor='middle' fill='%23666' font-size='10' font-family='Tahoma'%3Eساماندهی%3C/text%3E%3C/svg%3E" alt="ساماندهی">
                            </a>
                        </div>
                        
                        <h4 class="sif-title sif-title-mt">دانلود اپلیکیشن</h4>
                        <div class="sif-apps">
                            <a href="#" class="sif-app-btn">
                                <i class="fa-brands fa-google-play"></i>
                                <div>
                                    <small>دانلود از</small>
                                    <span>گوگل پلی</span>
                                </div>
                            </a>
                            <a href="#" class="sif-app-btn">
                                <i class="fa-brands fa-apple"></i>
                                <div>
                                    <small>دانلود از</small>
                                    <span>اپ استور</span>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
            </div>
        </div>
        
        <!-- Footer Bottom Bar -->
        <div class="sif-bottom">
            <div class="sif-bottom-inner">
                <div class="sif-copyright">
                    © <?php echo date_i18n('Y'); ?> 
                    <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html($site_name); ?></a>
                    - تمامی حقوق محفوظ است.
                </div>
                <div class="sif-payments">
                    <span>پرداخت امن:</span>
                    <i class="fa-solid fa-credit-card"></i>
                    <i class="fa-solid fa-building-columns"></i>
                    <i class="fa-solid fa-wallet"></i>
                    <i class="fa-solid fa-money-bill-transfer"></i>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top -->
    <button type="button" class="sif-totop" id="sif-totop" aria-label="بازگشت به بالا">
        <i class="fa-solid fa-chevron-up"></i>
    </button>

    <?php
}

// ============================================
// FOOTER CSS - COMPLETE STYLES
// ============================================
add_action('wp_head', 'si_footer_css', 50);
function si_footer_css() {
    ?>
    <style id="sif-styles">
    /*
     * FOOTER STYLES
     * Full-Width | White Text on Dark | Clean Design
     * Primary: #29853A
     */
    
    /* ========================================
       FEATURES BAR
       ======================================== */
    .sif-features {
        width: 100%;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        padding: 0;
    }
    
    [data-theme="dark"] .sif-features {
        background: #16161f;
        border-top-color: #252532;
    }
    
    .sif-features-inner {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 40px 30px;
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
    }
    
    .sif-feature {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 20px;
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    [data-theme="dark"] .sif-feature {
        background: #1e1e2d;
        border-color: #2a2a3d;
    }
    
    .sif-feature:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(41, 133, 58, 0.15);
        border-color: #29853A;
    }
    
    .sif-feature-icon {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #29853A, #1e6b2d);
        border-radius: 12px;
        flex-shrink: 0;
    }
    
    .sif-feature-icon i {
        font-size: 22px;
        color: #fff;
    }
    
    .sif-feature-content strong {
        display: block;
        font-size: 14px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 4px;
    }
    
    [data-theme="dark"] .sif-feature-content strong {
        color: #fff;
    }
    
    .sif-feature-content span {
        display: block;
        font-size: 13px;
        color: #6b7280;
    }
    
    [data-theme="dark"] .sif-feature-content span {
        color: #9ca3af;
    }
    
    /* ========================================
       NEWSLETTER
       ======================================== */
    .sif-newsletter {
        width: 100%;
        background: linear-gradient(135deg, #29853A 0%, #1e6b2d 50%, #165524 100%);
        padding: 0;
        position: relative;
        overflow: hidden;
    }
    
    .sif-newsletter::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M20 20.5V18H0v-2h20v-2l4 3.5-4 3.5zM0 20h2v2H0v-2zm4 0h2v2H4v-2zm4 0h2v2H8v-2zm4 0h2v2h-2v-2zm4 0h2v2h-2v-2z' fill='%23ffffff' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
    }
    
    .sif-newsletter-inner {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 45px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 40px;
        position: relative;
        z-index: 1;
    }
    
    .sif-newsletter-text {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    
    .sif-newsletter-text > i {
        font-size: 48px;
        color: #fff;
        opacity: 0.9;
        animation: sif-float 3s ease-in-out infinite;
    }
    
    @keyframes sif-float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
    
    .sif-newsletter-text h3 {
        font-size: 22px;
        font-weight: 700;
        color: #fff;
        margin: 0 0 6px;
    }
    
    .sif-newsletter-text p {
        font-size: 15px;
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
    }
    
    .sif-newsletter-form {
        display: flex;
        flex: 1;
        max-width: 480px;
        background: #fff;
        border-radius: 50px;
        padding: 5px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }
    
    .sif-newsletter-form input {
        flex: 1;
        padding: 15px 22px;
        border: none;
        background: transparent;
        font-family: inherit;
        font-size: 15px;
        color: #333;
        outline: none;
    }
    
    .sif-newsletter-form input::placeholder {
        color: #888;
    }
    
    .sif-newsletter-form button {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 15px 28px;
        background: #1a1a2e;
        color: #fff;
        border: none;
        border-radius: 50px;
        font-family: inherit;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .sif-newsletter-form button:hover {
        background: #252542;
    }
    
    /* ========================================
       MAIN FOOTER - FULL WIDTH
       ======================================== */
    .sif-footer {
        width: 100%;
        background: #1a1a2e;
        padding: 0;
    }
    
    [data-theme="dark"] .sif-footer {
        background: #0c0c12;
    }
    
    .sif-footer-inner {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 60px 30px 50px;
    }
    
    .sif-footer-grid {
        display: grid;
        grid-template-columns: 1.6fr repeat(4, 1fr);
        gap: 40px;
    }
    
    /* Footer Titles */
    .sif-title,
    .sif-footer .si-fw-title,
    .sif-footer .widget-title {
        font-size: 16px;
        font-weight: 700;
        color: #fff !important;
        margin: 0 0 22px;
        padding-bottom: 12px;
        position: relative;
    }
    
    .sif-title::after,
    .sif-footer .si-fw-title::after,
    .sif-footer .widget-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        right: 0;
        width: 40px;
        height: 3px;
        background: #29853A;
        border-radius: 3px;
    }
    
    .sif-title-mt {
        margin-top: 28px;
    }
    
    /* About Column */
    .sif-col-about {
        padding-left: 25px;
    }
    
    .sif-logo {
        margin-bottom: 18px;
    }
    
    .sif-logo img {
        max-height: 48px;
        width: auto;
        filter: brightness(0) invert(1);
    }
    
    .sif-logo-text {
        font-size: 26px;
        font-weight: 700;
        color: #fff;
        text-decoration: none;
    }
    
    .sif-desc {
        font-size: 14px;
        line-height: 1.9;
        color: rgba(255, 255, 255, 0.8);
        margin: 0 0 20px;
    }
    
    /* Contact List - WHITE TEXT */
    .sif-contact {
        list-style: none;
        padding: 0;
        margin: 0 0 22px;
    }
    
    .sif-contact li {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 12px;
        font-size: 14px;
    }
    
    .sif-contact li i {
        width: 16px;
        color: #29853A;
        margin-top: 4px;
        flex-shrink: 0;
    }
    
    .sif-contact li a,
    .sif-contact li span {
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .sif-contact li a:hover {
        color: #29853A;
    }
    
    /* Social Links */
    .sif-social {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .sif-social a {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        color: #fff;
        font-size: 18px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .sif-social a:hover {
        transform: translateY(-4px);
        border-color: transparent;
    }
    
    .sif-social-instagram:hover { background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888) !important; }
    .sif-social-telegram:hover { background: #0088cc !important; }
    .sif-social-whatsapp:hover { background: #25d366 !important; }
    .sif-social-twitter:hover { background: #000 !important; }
    .sif-social-youtube:hover { background: #ff0000 !important; }
    .sif-social-aparat:hover { background: #ed145b !important; }
    
    /* Footer Links - WHITE TEXT */
    .sif-links,
    .sif-footer .widget ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .sif-links li,
    .sif-footer .widget ul li {
        margin-bottom: 12px;
    }
    
    .sif-links li a,
    .sif-footer .widget ul li a {
        display: inline-flex;
        align-items: center;
        font-size: 14px;
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        transition: all 0.2s ease;
        position: relative;
        padding-right: 18px;
    }
    
    .sif-links li a::before,
    .sif-footer .widget ul li a::before {
        content: '\f104';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: 0;
        font-size: 11px;
        opacity: 0.5;
        transition: all 0.2s ease;
    }
    
    .sif-links li a:hover,
    .sif-footer .widget ul li a:hover {
        color: #29853A;
        padding-right: 24px;
    }
    
    .sif-links li a:hover::before,
    .sif-footer .widget ul li a:hover::before {
        opacity: 1;
        color: #29853A;
    }
    
    /* Trust Badges */
    .sif-badges {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    
    .sif-badge {
        display: block;
        width: 85px;
        height: 95px;
        background: #fff;
        border-radius: 10px;
        padding: 6px;
        transition: transform 0.3s ease;
    }
    
    .sif-badge:hover {
        transform: scale(1.05);
    }
    
    .sif-badge img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    
    /* App Buttons */
    .sif-apps {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .sif-app-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        color: #fff;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .sif-app-btn:hover {
        background: #29853A;
        border-color: #29853A;
        transform: translateY(-2px);
    }
    
    .sif-app-btn i {
        font-size: 24px;
    }
    
    .sif-app-btn div {
        display: flex;
        flex-direction: column;
        line-height: 1.3;
    }
    
    .sif-app-btn small {
        font-size: 11px;
        opacity: 0.8;
    }
    
    .sif-app-btn span {
        font-size: 14px;
        font-weight: 600;
    }
    
    /* ========================================
       FOOTER BOTTOM - FULL WIDTH
       ======================================== */
    .sif-bottom {
        width: 100%;
        background: rgba(0, 0, 0, 0.25);
    }
    
    [data-theme="dark"] .sif-bottom {
        background: rgba(0, 0, 0, 0.4);
    }
    
    .sif-bottom-inner {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        padding: 20px 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }
    
    .sif-copyright {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.7);
    }
    
    .sif-copyright a {
        color: #29853A;
        text-decoration: none;
        font-weight: 600;
    }
    
    .sif-copyright a:hover {
        text-decoration: underline;
    }
    
    .sif-payments {
        display: flex;
        align-items: center;
        gap: 14px;
    }
    
    .sif-payments span {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.6);
    }
    
    .sif-payments i {
        font-size: 20px;
        color: rgba(255, 255, 255, 0.5);
        transition: color 0.2s ease;
    }
    
    .sif-payments i:hover {
        color: #29853A;
    }
    
    /* ========================================
       BACK TO TOP
       ======================================== */
    .sif-totop {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #29853A, #1e6b2d);
        color: #fff;
        border: none;
        border-radius: 14px;
        font-size: 18px;
        cursor: pointer;
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px);
        transition: all 0.4s ease;
        z-index: 99;
        box-shadow: 0 8px 25px rgba(41, 133, 58, 0.4);
    }
    
    .sif-totop.visible {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    
    .sif-totop:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(41, 133, 58, 0.5);
    }
    
    /* ========================================
       RESPONSIVE STYLES
       ======================================== */
    @media (max-width: 1200px) {
        .sif-features-inner,
        .sif-newsletter-inner,
        .sif-footer-inner,
        .sif-bottom-inner {
            max-width: 100%;
            padding-left: 25px;
            padding-right: 25px;
        }
        
        .sif-features-inner {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .sif-footer-grid {
            grid-template-columns: 1.4fr repeat(2, 1fr);
            gap: 35px;
        }
        
        .sif-col:nth-child(4) {
            grid-column: 2;
        }
        
        .sif-col:nth-child(5) {
            grid-column: 3;
        }
    }
    
    @media (max-width: 991px) {
        .sif-features-inner {
            grid-template-columns: repeat(2, 1fr);
            padding: 35px 20px;
        }
        
        .sif-features-inner .sif-feature:last-child {
            grid-column: span 2;
            justify-content: center;
            max-width: 400px;
            margin: 0 auto;
        }
        
        .sif-newsletter-inner {
            flex-direction: column;
            text-align: center;
            padding: 40px 20px;
        }
        
        .sif-newsletter-text {
            flex-direction: column;
        }
        
        .sif-newsletter-form {
            width: 100%;
            max-width: 420px;
        }
        
        .sif-footer-inner {
            padding: 50px 20px 40px;
        }
        
        .sif-footer-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 35px;
        }
        
        .sif-col-about {
            grid-column: span 2;
            padding-left: 0;
            text-align: center;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .sif-desc {
            text-align: center;
        }
        
        .sif-contact {
            display: inline-block;
            text-align: right;
        }
        
        .sif-social {
            justify-content: center;
        }
        
        .sif-col:not(.sif-col-about) {
            text-align: center;
        }
        
        .sif-title::after,
        .sif-footer .si-fw-title::after,
        .sif-footer .widget-title::after {
            right: 50%;
            transform: translateX(50%);
        }
        
        .sif-links li a,
        .sif-footer .widget ul li a {
            justify-content: center;
            padding-right: 0;
        }
        
        .sif-links li a::before,
        .sif-footer .widget ul li a::before {
            display: none;
        }
        
        .sif-col:nth-child(4),
        .sif-col:nth-child(5) {
            grid-column: auto;
        }
        
        .sif-badges {
            justify-content: center;
        }
        
        .sif-apps {
            align-items: center;
        }
        
        .sif-app-btn {
            width: 100%;
            max-width: 200px;
            justify-content: center;
        }
        
        .sif-bottom-inner {
            flex-direction: column;
            text-align: center;
            padding: 18px 20px;
        }
        
        .sif-payments {
            flex-direction: column;
            gap: 10px;
        }
    }
    
    @media (max-width: 768px) {
        .sif-features-inner {
            grid-template-columns: 1fr;
            gap: 12px;
            padding: 30px 16px;
        }
        
        .sif-features-inner .sif-feature:last-child {
            grid-column: span 1;
            max-width: none;
        }
        
        .sif-feature {
            justify-content: flex-start;
        }
        
        .sif-newsletter-inner {
            padding: 35px 16px;
        }
        
        .sif-newsletter-text > i {
            font-size: 38px;
        }
        
        .sif-newsletter-text h3 {
            font-size: 19px;
        }
        
        .sif-newsletter-form {
            flex-direction: column;
            border-radius: 14px;
            padding: 0;
            overflow: hidden;
        }
        
        .sif-newsletter-form input {
            border-radius: 0;
            text-align: center;
            padding: 16px;
            border-bottom: 1px solid #eee;
        }
        
        .sif-newsletter-form button {
            border-radius: 0;
            justify-content: center;
            padding: 16px;
        }
        
        .sif-footer-inner {
            padding: 40px 16px 35px;
        }
        
        .sif-footer-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }
        
        .sif-col-about {
            grid-column: span 1;
        }
        
        .sif-bottom-inner {
            padding: 16px;
        }
        
        .sif-totop {
            width: 46px;
            height: 46px;
            bottom: 20px;
            left: 20px;
            border-radius: 12px;
        }
    }
    
    @media (max-width: 480px) {
        .sif-feature-icon {
            width: 46px;
            height: 46px;
        }
        
        .sif-feature-icon i {
            font-size: 18px;
        }
        
        .sif-feature-content strong {
            font-size: 13px;
        }
        
        .sif-feature-content span {
            font-size: 12px;
        }
    }
    
    /* Print */
    @media print {
        .sif-features,
        .sif-newsletter,
        .sif-footer,
        .sif-totop {
            display: none !important;
        }
    }
		.site-footer a:not(.button):not(.components-button) {
			color: #d3d3d3;
		}
    </style>
    <?php
}

// ============================================
// FOOTER JAVASCRIPT
// ============================================
add_action('wp_footer', 'si_footer_js', 99);
function si_footer_js() {
    ?>
    <script id="sif-js">
    (function(){
        'use strict';
        
        // Scroll to top
        var totop = document.getElementById('sif-totop');
        if (totop) {
            function checkScroll() {
                if (window.scrollY > 400) {
                    totop.classList.add('visible');
                } else {
                    totop.classList.remove('visible');
                }
            }
            window.addEventListener('scroll', checkScroll, {passive: true});
            checkScroll();
            
            totop.addEventListener('click', function() {
                window.scrollTo({top: 0, behavior: 'smooth'});
            });
        }
        
        // Newsletter
        var form = document.getElementById('sif-newsletter-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var btn = this.querySelector('button');
                var btnSpan = btn.querySelector('span');
                var btnIcon = btn.querySelector('i');
                var input = this.querySelector('input');
                var original = btnSpan.textContent;
                
                btnSpan.textContent = 'در حال ثبت...';
                btnIcon.className = 'fa-solid fa-spinner fa-spin';
                btn.disabled = true;
                
                setTimeout(function() {
                    btnSpan.textContent = 'ثبت شد!';
                    btnIcon.className = 'fa-solid fa-check';
                    btn.style.background = '#10b981';
                    input.value = '';
                    
                    setTimeout(function() {
                        btnSpan.textContent = original;
                        btnIcon.className = 'fa-solid fa-paper-plane';
                        btn.style.background = '';
                        btn.disabled = false;
                    }, 2500);
                }, 1200);
            });
        }
    })();
    </script>
    <?php
}

// ============================================
// CUSTOMIZER SETTINGS
// ============================================
add_action('customize_register', 'si_footer_customizer');
function si_footer_customizer($wp_customize) {
    // Footer Info Section
    $wp_customize->add_section('si_footer_info', array(
        'title' => 'اطلاعات فوتر',
        'priority' => 150,
    ));
    
    $fields = array(
        'si_footer_phone' => array('تلفن ثابت', '۰۲۱-۸۸۷۷۶۶۵۵', 'text'),
        'si_footer_mobile' => array('موبایل', '۰۹۱۲-۳۴۵-۶۷۸۹', 'text'),
        'si_footer_email' => array('ایمیل', 'info@flavor-flavor.ir', 'email'),
        'si_footer_address' => array('آدرس', 'تهران، میدان ونک، خیابان ملاصدرا', 'textarea'),
    );
    
    foreach ($fields as $id => $data) {
        $wp_customize->add_setting($id, array(
            'default' => $data[1],
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control($id, array(
            'label' => $data[0],
            'section' => 'si_footer_info',
            'type' => $data[2],
        ));
    }
    
    // Social Links Section
    $wp_customize->add_section('si_social_links', array(
        'title' => 'شبکه‌های اجتماعی',
        'priority' => 151,
    ));
    
    $socials = array(
        'instagram' => 'اینستاگرام',
        'telegram' => 'تلگرام',
        'whatsapp' => 'واتساپ',
        'twitter' => 'توییتر',
        'youtube' => 'یوتیوب',
        'aparat' => 'آپارات',
    );
    
    foreach ($socials as $key => $label) {
        $wp_customize->add_setting('si_social_' . $key, array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control('si_social_' . $key, array(
            'label' => $label,
            'section' => 'si_social_links',
            'type' => 'url',
        ));
    }
}
