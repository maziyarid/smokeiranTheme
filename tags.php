/**
 * =============================================================================
 * SI-Tag-Hub
 * =============================================================================
 * Beautiful Tag Archive Hub Page
 * Priority: 10 | Location: Frontend Only
 * =============================================================================
 */

if (!defined('ABSPATH')) exit;

// Register Page Template
add_filter('theme_page_templates', 'si_taghub_add_template');
function si_taghub_add_template($templates) {
    $templates['si-tag-hub'] = 'مرکز برچسب‌ها';
    return $templates;
}

add_filter('template_include', 'si_taghub_load_template');
function si_taghub_load_template($template) {
    global $post;
    if ($post && get_page_template_slug($post->ID) === 'si-tag-hub') {
        add_filter('the_content', 'si_taghub_render', 99);
    }
    return $template;
}

function si_taghub_render($content) {
    $tags = get_tags([
        'orderby' => 'count',
        'order' => 'DESC',
        'hide_empty' => true
    ]);
    
    if (empty($tags)) {
        return '<div class="si-taghub__empty"><i class="fa-solid fa-tags"></i><p>هنوز برچسبی ایجاد نشده است.</p></div>';
    }
    
    $total_posts = array_sum(array_column($tags, 'count'));
    $colors = ['#29853A', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4', '#10b981'];
    
    ob_start();
    ?>
    <div class="si-taghub">
        <!-- Hero -->
        <div class="si-taghub__hero">
            <div class="si-taghub__hero-bg"></div>
            <div class="si-taghub__hero-content">
                <div class="si-taghub__hero-icon">
                    <i class="fa-solid fa-tags"></i>
                </div>
                <h1 class="si-taghub__hero-title">مرکز برچسب‌ها</h1>
                <p class="si-taghub__hero-desc">مرور محتوا بر اساس موضوعات مختلف</p>
                
                <div class="si-taghub__hero-stats">
                    <div class="si-taghub__hero-stat">
                        <span class="si-taghub__hero-stat-num"><?php echo count($tags); ?></span>
                        <span class="si-taghub__hero-stat-label">برچسب</span>
                    </div>
                    <div class="si-taghub__hero-stat-divider"></div>
                    <div class="si-taghub__hero-stat">
                        <span class="si-taghub__hero-stat-num"><?php echo $total_posts; ?></span>
                        <span class="si-taghub__hero-stat-label">نوشته</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Search -->
        <div class="si-taghub__search-wrap">
            <div class="si-taghub__search">
                <i class="fa-solid fa-search"></i>
                <input type="text" id="si-taghub-search" placeholder="جستجوی برچسب...">
                <span class="si-taghub__search-count"><span id="si-taghub-count"><?php echo count($tags); ?></span> برچسب</span>
            </div>
        </div>
        
        <!-- Tags Grid -->
        <div class="si-taghub__grid" id="si-taghub-grid">
            <?php foreach ($tags as $index => $tag):
                $color = $colors[$index % count($colors)];
                $posts = get_posts(['tag_id' => $tag->term_id, 'numberposts' => 3]);
            ?>
            <article class="si-taghub__card" data-name="<?php echo esc_attr(strtolower($tag->name)); ?>" style="--tag-color:<?php echo $color; ?>;">
                <div class="si-taghub__card-header">
                    <a href="<?php echo get_tag_link($tag); ?>" class="si-taghub__card-title">
                        <span class="si-taghub__card-hash">#</span>
                        <?php echo esc_html($tag->name); ?>
                    </a>
                    <span class="si-taghub__card-count"><?php echo $tag->count; ?></span>
                </div>
                
                <?php if ($posts): ?>
                <ul class="si-taghub__card-posts">
                    <?php foreach ($posts as $p): ?>
                    <li>
                        <a href="<?php echo get_permalink($p); ?>">
                            <i class="fa-solid fa-angle-left"></i>
                            <?php echo esc_html(wp_trim_words($p->post_title, 8)); ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                
                <a href="<?php echo get_tag_link($tag); ?>" class="si-taghub__card-link">
                    مشاهده همه
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
            </article>
            <?php endforeach; ?>
        </div>
        
        <!-- Empty State -->
        <div class="si-taghub__empty-search" id="si-taghub-empty" style="display:none;">
            <i class="fa-solid fa-search"></i>
            <p>برچسبی با این عنوان یافت نشد</p>
        </div>
    </div>
    <?php
    
    return ob_get_clean();
}

add_action('wp_head', 'si_taghub_assets');
function si_taghub_assets() {
    global $post;
    if (!$post || get_page_template_slug($post->ID) !== 'si-tag-hub') return;
    ?>
    <style>
    .si-taghub {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--si-space-6);
    }
    
    /* Hero */
    .si-taghub__hero {
        position: relative;
        border-radius: var(--si-radius-3xl);
        padding: var(--si-space-16) var(--si-space-8);
        margin-bottom: var(--si-space-8);
        overflow: hidden;
        text-align: center;
    }
    
    .si-taghub__hero-bg {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, var(--si-primary) 0%, #059669 50%, #0d9488 100%);
        z-index: 0;
    }
    
    .si-taghub__hero-bg::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.5;
    }
    
    .si-taghub__hero-content {
        position: relative;
        z-index: 1;
        color: white;
    }
    
    .si-taghub__hero-icon {
        width: 80px;
        height: 80px;
        background: rgba(255,255,255,0.15);
        border-radius: var(--si-radius-2xl);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--si-space-5);
        font-size: 2rem;
        backdrop-filter: blur(10px);
        animation: si-bounce 2s infinite;
    }
    
    .si-taghub__hero-title {
        font-size: var(--si-text-4xl);
        margin: 0 0 var(--si-space-2);
        color: white;
    }
    
    .si-taghub__hero-desc {
        font-size: var(--si-text-lg);
        margin: 0 0 var(--si-space-8);
        opacity: 0.9;
    }
    
    .si-taghub__hero-stats {
        display: inline-flex;
        align-items: center;
        gap: var(--si-space-6);
        background: rgba(255,255,255,0.1);
        padding: var(--si-space-4) var(--si-space-8);
        border-radius: var(--si-radius-full);
        backdrop-filter: blur(10px);
    }
    
    .si-taghub__hero-stat {
        text-align: center;
    }
    
    .si-taghub__hero-stat-num {
        display: block;
        font-size: var(--si-text-2xl);
        font-weight: var(--si-font-extrabold);
    }
    
    .si-taghub__hero-stat-label {
        font-size: var(--si-text-sm);
        opacity: 0.8;
    }
    
    .si-taghub__hero-stat-divider {
        width: 1px;
        height: 40px;
        background: rgba(255,255,255,0.3);
    }
    
    /* Search */
    .si-taghub__search-wrap {
        margin-bottom: var(--si-space-8);
    }
    
    .si-taghub__search {
        max-width: 500px;
        margin: 0 auto;
        position: relative;
        display: flex;
        align-items: center;
        background: var(--si-surface);
        border: 2px solid var(--si-border);
        border-radius: var(--si-radius-full);
        padding: var(--si-space-1);
        transition: all var(--si-transition-fast);
    }
    
    .si-taghub__search:focus-within {
        border-color: var(--si-primary);
        box-shadow: 0 0 0 4px rgba(41, 133, 58, 0.1);
    }
    
    .si-taghub__search i {
        padding: 0 var(--si-space-4);
        color: var(--si-text-muted);
    }
    
    .si-taghub__search input {
        flex: 1;
        border: none;
        background: none;
        padding: var(--si-space-3) 0;
        font-size: var(--si-text-base);
        outline: none;
    }
    
    .si-taghub__search-count {
        padding: var(--si-space-2) var(--si-space-4);
        background: var(--si-bg-tertiary);
        border-radius: var(--si-radius-full);
        font-size: var(--si-text-sm);
        color: var(--si-text-muted);
        margin-left: var(--si-space-2);
    }
    
    /* Grid */
    .si-taghub__grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: var(--si-space-5);
    }
    
    /* Card */
    .si-taghub__card {
        background: var(--si-surface);
        border-radius: var(--si-radius-2xl);
        padding: var(--si-space-6);
        border: 1px solid var(--si-border-light);
        box-shadow: var(--si-shadow-sm);
        transition: all var(--si-transition);
        position: relative;
        overflow: hidden;
    }
    
    .si-taghub__card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 4px;
        height: 100%;
        background: var(--tag-color);
    }
    
    .si-taghub__card:hover {
        transform: translateY(-4px);
        box-shadow: var(--si-shadow-xl);
        border-color: var(--tag-color);
    }
    
    .si-taghub__card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: var(--si-space-4);
        padding-bottom: var(--si-space-4);
        border-bottom: 1px solid var(--si-border-light);
    }
    
    .si-taghub__card-title {
        font-size: var(--si-text-lg);
        font-weight: var(--si-font-bold);
        color: var(--si-text);
        transition: color var(--si-transition-fast);
    }
    
    .si-taghub__card-title:hover {
        color: var(--tag-color);
    }
    
    .si-taghub__card-hash {
        color: var(--tag-color);
        margin-left: var(--si-space-1);
    }
    
    .si-taghub__card-count {
        background: color-mix(in srgb, var(--tag-color) 15%, transparent);
        color: var(--tag-color);
        padding: var(--si-space-1) var(--si-space-3);
        border-radius: var(--si-radius-full);
        font-size: var(--si-text-sm);
        font-weight: var(--si-font-bold);
    }
    
    .si-taghub__card-posts {
        list-style: none;
        margin: 0 0 var(--si-space-4);
        padding: 0;
    }
    
    .si-taghub__card-posts li {
        margin-bottom: var(--si-space-2);
    }
    
    .si-taghub__card-posts a {
        display: flex;
        align-items: center;
        gap: var(--si-space-2);
        padding: var(--si-space-2) var(--si-space-3);
        background: var(--si-bg-secondary);
        border-radius: var(--si-radius-lg);
        font-size: var(--si-text-sm);
        color: var(--si-text-secondary);
        transition: all var(--si-transition-fast);
    }
    
    .si-taghub__card-posts a:hover {
        background: color-mix(in srgb, var(--tag-color) 10%, transparent);
        color: var(--tag-color);
        transform: translateX(-4px);
    }
    
    .si-taghub__card-posts a i {
        font-size: 0.7em;
        color: var(--si-text-muted);
    }
    
    .si-taghub__card-link {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--si-space-2);
        padding: var(--si-space-3);
        background: linear-gradient(135deg, var(--tag-color), color-mix(in srgb, var(--tag-color) 80%, black));
        color: white;
        border-radius: var(--si-radius-lg);
        font-size: var(--si-text-sm);
        font-weight: var(--si-font-semibold);
        transition: all var(--si-transition-fast);
    }
    
    .si-taghub__card-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px color-mix(in srgb, var(--tag-color) 40%, transparent);
        color: white;
    }
    
    .si-taghub__card-link i {
        transition: transform var(--si-transition-fast);
    }
    
    .si-taghub__card-link:hover i {
        transform: translateX(-4px);
    }
    
    /* Empty States */
    .si-taghub__empty,
    .si-taghub__empty-search {
        text-align: center;
        padding: var(--si-space-16);
        color: var(--si-text-muted);
    }
    
    .si-taghub__empty i,
    .si-taghub__empty-search i {
        font-size: 3rem;
        margin-bottom: var(--si-space-4);
        opacity: 0.5;
    }
    
    /* Hidden */
    .si-taghub__card--hidden {
        display: none !important;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .si-taghub {
            padding: var(--si-space-4);
        }
        
        .si-taghub__hero {
            padding: var(--si-space-10) var(--si-space-5);
        }
        
        .si-taghub__hero-title {
            font-size: var(--si-text-2xl);
        }
        
        .si-taghub__hero-stats {
            flex-direction: column;
            gap: var(--si-space-2);
            padding: var(--si-space-4);
        }
        
        .si-taghub__hero-stat-divider {
            width: 40px;
            height: 1px;
        }
        
        .si-taghub__grid {
            grid-template-columns: 1fr;
        }
    }
    </style>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const search = document.getElementById('si-taghub-search');
        const grid = document.getElementById('si-taghub-grid');
        const count = document.getElementById('si-taghub-count');
        const empty = document.getElementById('si-taghub-empty');
        
        if (!search || !grid) return;
        
        const cards = grid.querySelectorAll('.si-taghub__card');
        
        search.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let visible = 0;
            
            cards.forEach(card => {
                const name = card.dataset.name;
                const match = !query || name.includes(query);
                card.classList.toggle('si-taghub__card--hidden', !match);
                if (match) visible++;
            });
            
            count.textContent = visible;
            empty.style.display = visible === 0 ? 'block' : 'none';
            grid.style.display = visible === 0 ? 'none' : 'grid';
        });
    });
    </script>
    <?php
}
