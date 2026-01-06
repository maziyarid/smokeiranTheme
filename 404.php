/**
 * =============================================================================
 * SI-404-Page
 * =============================================================================
 * Beautiful Custom 404 Error Page
 * Priority: 10 | Location: Frontend Only
 * =============================================================================
 */

if (!defined('ABSPATH')) exit;

add_filter('template_include', 'si_404_template', 99);
function si_404_template($template) {
    if (is_404()) {
        add_action('wp_head', 'si_404_assets', 5);
        
        // Override template
        ?>
        <!DOCTYPE html>
        <html <?php language_attributes(); ?>>
        <head>
            <meta charset="<?php bloginfo('charset'); ?>">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>صفحه یافت نشد | <?php bloginfo('name'); ?></title>
            <?php wp_head(); ?>
        </head>
        <body <?php body_class(); ?>>
        <?php wp_body_open(); ?>
        
        <div class="si-404">
            <!-- Animated Background -->
            <div class="si-404__bg">
                <div class="si-404__bg-shape si-404__bg-shape--1"></div>
                <div class="si-404__bg-shape si-404__bg-shape--2"></div>
                <div class="si-404__bg-shape si-404__bg-shape--3"></div>
            </div>
            
            <!-- Hero Section -->
            <div class="si-404__hero">
                <div class="si-404__code">
                    <span class="si-404__num">4</span>
                    <span class="si-404__icon">
                        <i class="fa-solid fa-ghost"></i>
                    </span>
                    <span class="si-404__num">4</span>
                </div>
                
                <h1 class="si-404__title">اوه! صفحه‌ای یافت نشد</h1>
                <p class="si-404__desc">صفحه‌ای که به دنبال آن هستید وجود ندارد، حذف شده یا آدرس آن تغییر کرده است.</p>
                
                <!-- Search -->
                <div class="si-404__search">
                    <form role="search" method="get" action="<?php echo home_url('/'); ?>">
                        <i class="fa-solid fa-search"></i>
                        <input type="search" name="s" placeholder="جستجو در سایت...">
                        <button type="submit">
                            <i class="fa-solid fa-arrow-left"></i>
                        </button>
                    </form>
                </div>
                
                <!-- Quick Actions -->
                <div class="si-404__actions">
                    <a href="<?php echo home_url(); ?>" class="si-404__btn si-404__btn--primary">
                        <i class="fa-solid fa-home"></i>
                        صفحه اصلی
                    </a>
                    <a href="javascript:history.back()" class="si-404__btn si-404__btn--secondary">
                        <i class="fa-solid fa-arrow-right"></i>
                        بازگشت
                    </a>
                </div>
            </div>
            
            <!-- Content Sections -->
            <div class="si-404__content">
                <!-- Popular Posts -->
                <section class="si-404__section">
                    <div class="si-404__section-header">
                        <div class="si-404__section-icon" style="--section-color:#29853A;">
                            <i class="fa-solid fa-fire"></i>
                        </div>
                        <h2 class="si-404__section-title">نوشته‌های محبوب</h2>
                    </div>
                    <div class="si-404__posts">
                        <?php
                        $popular = get_posts([
                            'numberposts' => 6,
                            'orderby' => 'comment_count',
                            'order' => 'DESC'
                        ]);
                        foreach ($popular as $post):
                            setup_postdata($post);
                        ?>
                        <a href="<?php the_permalink(); ?>" class="si-404__post">
                            <?php if (has_post_thumbnail()): ?>
                            <div class="si-404__post-img">
                                <?php the_post_thumbnail('thumbnail'); ?>
                            </div>
                            <?php else: ?>
                            <div class="si-404__post-img si-404__post-img--placeholder">
                                <i class="fa-solid fa-image"></i>
                            </div>
                            <?php endif; ?>
                            <div class="si-404__post-info">
                                <h3><?php echo wp_trim_words(get_the_title(), 8); ?></h3>
                                <span><i class="fa-regular fa-clock"></i> <?php echo get_the_date('j F'); ?></span>
                            </div>
                        </a>
                        <?php endforeach; wp_reset_postdata(); ?>
                    </div>
                </section>
                
                <!-- Categories -->
                <section class="si-404__section">
                    <div class="si-404__section-header">
                        <div class="si-404__section-icon" style="--section-color:#3b82f6;">
                            <i class="fa-solid fa-folder-open"></i>
                        </div>
                        <h2 class="si-404__section-title">دسته‌بندی‌ها</h2>
                    </div>
                    <div class="si-404__categories">
                        <?php
                        $cats = get_categories(['hide_empty' => true, 'number' => 8, 'orderby' => 'count', 'order' => 'DESC']);
                        $colors = ['#29853A', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4', '#10b981'];
                        foreach ($cats as $i => $cat):
                            $color = $colors[$i % count($colors)];
                        ?>
                        <a href="<?php echo get_category_link($cat); ?>" class="si-404__cat" style="--cat-color:<?php echo $color; ?>;">
                            <span class="si-404__cat-name"><?php echo $cat->name; ?></span>
                            <span class="si-404__cat-count"><?php echo $cat->count; ?></span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </section>
                
                <!-- Quick Links -->
                <section class="si-404__section si-404__section--links">
                    <div class="si-404__section-header">
                        <div class="si-404__section-icon" style="--section-color:#f59e0b;">
                            <i class="fa-solid fa-link"></i>
                        </div>
                        <h2 class="si-404__section-title">لینک‌های مفید</h2>
                    </div>
                    <div class="si-404__links">
                        <a href="<?php echo home_url(); ?>"><i class="fa-solid fa-home"></i> صفحه اصلی</a>
                        <a href="<?php echo home_url('/shop'); ?>"><i class="fa-solid fa-store"></i> فروشگاه</a>
                        <a href="<?php echo home_url('/blog'); ?>"><i class="fa-solid fa-newspaper"></i> وبلاگ</a>
                        <a href="<?php echo home_url('/contact'); ?>"><i class="fa-solid fa-envelope"></i> تماس با ما</a>
                        <a href="<?php echo home_url('/about'); ?>"><i class="fa-solid fa-info-circle"></i> درباره ما</a>
                        <a href="<?php echo home_url('/sitemap'); ?>"><i class="fa-solid fa-sitemap"></i> نقشه سایت</a>
                    </div>
                </section>
            </div>
        </div>
        
        <?php wp_footer(); ?>
        </body>
        </html>
        <?php
        exit;
    }
    return $template;
}

function si_404_assets() {
    ?>
    <style>
    .si-404 {
        min-height: 100vh;
        background: var(--si-bg);
        position: relative;
        overflow: hidden;
    }
    
    /* Animated Background */
    .si-404__bg {
        position: fixed;
        inset: 0;
        pointer-events: none;
        overflow: hidden;
    }
    
    .si-404__bg-shape {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.15;
        animation: si-float 20s ease-in-out infinite;
    }
    
    .si-404__bg-shape--1 {
        width: 600px;
        height: 600px;
        background: var(--si-primary);
        top: -200px;
        right: -200px;
        animation-delay: 0s;
    }
    
    .si-404__bg-shape--2 {
        width: 400px;
        height: 400px;
        background: #3b82f6;
        bottom: -100px;
        left: -100px;
        animation-delay: -7s;
    }
    
    .si-404__bg-shape--3 {
        width: 300px;
        height: 300px;
        background: #f59e0b;
        top: 50%;
        left: 50%;
        animation-delay: -14s;
    }
    
    @keyframes si-float {
        0%, 100% { transform: translate(0, 0) scale(1); }
        25% { transform: translate(50px, -50px) scale(1.1); }
        50% { transform: translate(-30px, 30px) scale(0.95); }
        75% { transform: translate(20px, 50px) scale(1.05); }
    }
    
    /* Hero */
    .si-404__hero {
        text-align: center;
        padding: var(--si-space-16) var(--si-space-6);
        position: relative;
        z-index: 1;
    }
    
    .si-404__code {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--si-space-4);
        margin-bottom: var(--si-space-6);
    }
    
    .si-404__num {
        font-size: clamp(80px, 15vw, 150px);
        font-weight: 900;
        background: linear-gradient(135deg, var(--si-primary) 0%, #059669 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        line-height: 1;
        text-shadow: 0 4px 30px rgba(41, 133, 58, 0.3);
    }
    
    .si-404__icon {
        font-size: clamp(60px, 12vw, 120px);
        color: var(--si-primary);
        animation: si-ghost 3s ease-in-out infinite;
        display: flex;
    }
    
    @keyframes si-ghost {
        0%, 100% { transform: translateY(0) rotate(-5deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }
    
    .si-404__title {
        font-size: var(--si-text-3xl);
        margin: 0 0 var(--si-space-3);
        color: var(--si-text);
    }
    
    .si-404__desc {
        font-size: var(--si-text-lg);
        color: var(--si-text-secondary);
        max-width: 500px;
        margin: 0 auto var(--si-space-8);
    }
    
    /* Search */
    .si-404__search {
        max-width: 500px;
        margin: 0 auto var(--si-space-6);
    }
    
    .si-404__search form {
        display: flex;
        align-items: center;
        background: var(--si-surface);
        border: 2px solid var(--si-border);
        border-radius: var(--si-radius-full);
        padding: var(--si-space-1);
        transition: all var(--si-transition-fast);
        box-shadow: var(--si-shadow-lg);
    }
    
    .si-404__search form:focus-within {
        border-color: var(--si-primary);
        box-shadow: var(--si-shadow-xl), 0 0 0 4px rgba(41, 133, 58, 0.1);
    }
    
    .si-404__search i {
        padding: 0 var(--si-space-4);
        color: var(--si-text-muted);
    }
    
    .si-404__search input {
        flex: 1;
        border: none;
        background: none;
        padding: var(--si-space-3);
        font-size: var(--si-text-base);
        outline: none;
    }
    
    .si-404__search button {
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, var(--si-primary), var(--si-primary-dark));
        color: white;
        border: none;
        border-radius: var(--si-radius-full);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all var(--si-transition-fast);
    }
    
    .si-404__search button:hover {
        transform: scale(1.05);
        box-shadow: var(--si-shadow-primary);
    }
    
    /* Actions */
    .si-404__actions {
        display: flex;
        justify-content: center;
        gap: var(--si-space-4);
        flex-wrap: wrap;
    }
    
    .si-404__btn {
        display: inline-flex;
        align-items: center;
        gap: var(--si-space-2);
        padding: var(--si-space-4) var(--si-space-6);
        border-radius: var(--si-radius-full);
        font-weight: var(--si-font-semibold);
        transition: all var(--si-transition-fast);
    }
    
    .si-404__btn--primary {
        background: linear-gradient(135deg, var(--si-primary), var(--si-primary-dark));
        color: white;
        box-shadow: var(--si-shadow-primary);
    }
    
    .si-404__btn--primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--si-shadow-primary-lg);
        color: white;
    }
    
    .si-404__btn--secondary {
        background: var(--si-surface);
        color: var(--si-text);
        border: 2px solid var(--si-border);
    }
    
    .si-404__btn--secondary:hover {
        border-color: var(--si-primary);
        color: var(--si-primary);
    }
    
    /* Content */
    .si-404__content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 var(--si-space-6) var(--si-space-16);
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: var(--si-space-6);
    }
    
    .si-404__section {
        background: var(--si-surface);
        border-radius: var(--si-radius-2xl);
        padding: var(--si-space-6);
        border: 1px solid var(--si-border-light);
        box-shadow: var(--si-shadow);
    }
    
    .si-404__section-header {
        display: flex;
        align-items: center;
        gap: var(--si-space-3);
        margin-bottom: var(--si-space-5);
        padding-bottom: var(--si-space-4);
        border-bottom: 1px solid var(--si-border-light);
    }
    
    .si-404__section-icon {
        width: 40px;
        height: 40px;
        background: color-mix(in srgb, var(--section-color) 15%, transparent);
        color: var(--section-color);
        border-radius: var(--si-radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .si-404__section-title {
        margin: 0;
        font-size: var(--si-text-lg);
        color: var(--si-text);
    }
    
    /* Posts */
    .si-404__posts {
        display: grid;
        gap: var(--si-space-3);
    }
    
    .si-404__post {
        display: flex;
        align-items: center;
        gap: var(--si-space-3);
        padding: var(--si-space-2);
        border-radius: var(--si-radius-lg);
        transition: all var(--si-transition-fast);
    }
    
    .si-404__post:hover {
        background: var(--si-bg-secondary);
        transform: translateX(-4px);
    }
    
    .si-404__post-img {
        width: 56px;
        height: 56px;
        border-radius: var(--si-radius-lg);
        overflow: hidden;
        flex-shrink: 0;
    }
    
    .si-404__post-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .si-404__post-img--placeholder {
        background: var(--si-bg-tertiary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--si-text-muted);
    }
    
    .si-404__post-info h3 {
        margin: 0 0 var(--si-space-1);
        font-size: var(--si-text-sm);
        font-weight: var(--si-font-semibold);
        color: var(--si-text);
        line-height: 1.4;
    }
    
    .si-404__post-info span {
        font-size: var(--si-text-xs);
        color: var(--si-text-muted);
        display: flex;
        align-items: center;
        gap: var(--si-space-1);
    }
    
    /* Categories */
    .si-404__categories {
        display: flex;
        flex-wrap: wrap;
        gap: var(--si-space-2);
    }
    
    .si-404__cat {
        display: inline-flex;
        align-items: center;
        gap: var(--si-space-2);
        padding: var(--si-space-2) var(--si-space-4);
        background: color-mix(in srgb, var(--cat-color) 10%, transparent);
        border: 1px solid color-mix(in srgb, var(--cat-color) 30%, transparent);
        border-radius: var(--si-radius-full);
        font-size: var(--si-text-sm);
        color: var(--cat-color);
        transition: all var(--si-transition-fast);
    }
    
    .si-404__cat:hover {
        background: var(--cat-color);
        color: white;
        transform: translateY(-2px);
    }
    
    .si-404__cat-count {
        background: color-mix(in srgb, var(--cat-color) 20%, transparent);
        padding: 2px 8px;
        border-radius: var(--si-radius-full);
        font-size: var(--si-text-xs);
        font-weight: var(--si-font-bold);
    }
    
    .si-404__cat:hover .si-404__cat-count {
        background: rgba(255,255,255,0.2);
    }
    
    /* Links */
    .si-404__links {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: var(--si-space-2);
    }
    
    .si-404__links a {
        display: flex;
        align-items: center;
        gap: var(--si-space-2);
        padding: var(--si-space-3);
        background: var(--si-bg-secondary);
        border-radius: var(--si-radius-lg);
        font-size: var(--si-text-sm);
        color: var(--si-text-secondary);
        transition: all var(--si-transition-fast);
    }
    
    .si-404__links a:hover {
        background: var(--si-primary);
        color: white;
        transform: translateX(-4px);
    }
    
    .si-404__links a i {
        width: 20px;
        text-align: center;
        color: var(--si-text-muted);
    }
    
    .si-404__links a:hover i {
        color: white;
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .si-404__content {
            grid-template-columns: 1fr 1fr;
        }
        
        .si-404__section--links {
            grid-column: span 2;
        }
    }
    
    @media (max-width: 768px) {
        .si-404__hero {
            padding: var(--si-space-10) var(--si-space-4);
        }
        
        .si-404__title {
            font-size: var(--si-text-2xl);
        }
        
        .si-404__content {
            grid-template-columns: 1fr;
            padding: 0 var(--si-space-4) var(--si-space-10);
        }
        
        .si-404__section--links {
            grid-column: auto;
        }
        
        .si-404__links {
            grid-template-columns: 1fr;
        }
    }
    </style>
    <?php
}
