/**
 * =============================================================================
 * SI-Visual-Sitemap
 * =============================================================================
 * Beautiful Visual Sitemap Page
 * Priority: 10 | Location: Frontend Only
 * =============================================================================
 */

if (!defined('ABSPATH')) exit;

add_filter('the_content', 'si_sitemap_render', 99);
function si_sitemap_render($content) {
    global $post;
    
    if (!is_page() || $post->post_name !== 'sitemap') {
        return $content;
    }
    
    // Get data
    $pages = get_pages(['sort_column' => 'menu_order', 'number' => 20]);
    $posts = get_posts(['numberposts' => 12, 'orderby' => 'date', 'order' => 'DESC']);
    $categories = get_categories(['hide_empty' => true, 'number' => 12]);
    $tags = get_tags(['hide_empty' => true, 'number' => 20, 'orderby' => 'count', 'order' => 'DESC']);
    $archives = wp_get_archives(['type' => 'monthly', 'limit' => 12, 'echo' => false]);
    
    ob_start();
    ?>
    <div class="si-sitemap">
        <!-- Hero -->
        <div class="si-sitemap__hero">
            <div class="si-sitemap__hero-icon">
                <i class="fa-solid fa-sitemap"></i>
            </div>
            <h1 class="si-sitemap__hero-title">نقشه سایت</h1>
            <p class="si-sitemap__hero-desc">دسترسی سریع به تمام بخش‌های سایت</p>
            
            <!-- Search -->
            <div class="si-sitemap__search">
                <i class="fa-solid fa-search"></i>
                <input type="text" id="si-sitemap-search" placeholder="جستجو در نقشه سایت...">
            </div>
        </div>
        
        <!-- Stats -->
        <div class="si-sitemap__stats">
            <div class="si-sitemap__stat">
                <div class="si-sitemap__stat-icon" style="--stat-color:#3b82f6;"><i class="fa-solid fa-file-lines"></i></div>
                <div class="si-sitemap__stat-num"><?php echo count($pages); ?></div>
                <div class="si-sitemap__stat-label">صفحه</div>
            </div>
            <div class="si-sitemap__stat">
                <div class="si-sitemap__stat-icon" style="--stat-color:#10b981;"><i class="fa-solid fa-newspaper"></i></div>
                <div class="si-sitemap__stat-num"><?php echo wp_count_posts()->publish; ?></div>
                <div class="si-sitemap__stat-label">نوشته</div>
            </div>
            <div class="si-sitemap__stat">
                <div class="si-sitemap__stat-icon" style="--stat-color:#f59e0b;"><i class="fa-solid fa-folder"></i></div>
                <div class="si-sitemap__stat-num"><?php echo count($categories); ?></div>
                <div class="si-sitemap__stat-label">دسته‌بندی</div>
            </div>
            <div class="si-sitemap__stat">
                <div class="si-sitemap__stat-icon" style="--stat-color:#8b5cf6;"><i class="fa-solid fa-tags"></i></div>
                <div class="si-sitemap__stat-num"><?php echo count($tags); ?></div>
                <div class="si-sitemap__stat-label">برچسب</div>
            </div>
        </div>
        
        <!-- Grid -->
        <div class="si-sitemap__grid">
            <!-- Pages -->
            <?php if ($pages): ?>
            <section class="si-sitemap__section" data-section="pages">
                <div class="si-sitemap__section-header" style="--section-color:#3b82f6;">
                    <div class="si-sitemap__section-icon"><i class="fa-solid fa-file-lines"></i></div>
                    <h2 class="si-sitemap__section-title">صفحات</h2>
                    <span class="si-sitemap__section-count"><?php echo count($pages); ?></span>
                </div>
                <ul class="si-sitemap__list">
                    <?php foreach ($pages as $page): ?>
                    <li class="si-sitemap__item" data-search="<?php echo esc_attr(strtolower($page->post_title)); ?>">
                        <a href="<?php echo get_permalink($page); ?>">
                            <i class="fa-solid fa-chevron-left"></i>
                            <span><?php echo esc_html($page->post_title); ?></span>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </section>
            <?php endif; ?>
            
            <!-- Posts -->
            <?php if ($posts): ?>
            <section class="si-sitemap__section" data-section="posts">
                <div class="si-sitemap__section-header" style="--section-color:#10b981;">
                    <div class="si-sitemap__section-icon"><i class="fa-solid fa-newspaper"></i></div>
                    <h2 class="si-sitemap__section-title">آخرین نوشته‌ها</h2>
                    <span class="si-sitemap__section-count"><?php echo count($posts); ?></span>
                </div>
                <ul class="si-sitemap__list">
                    <?php foreach ($posts as $p): ?>
                    <li class="si-sitemap__item" data-search="<?php echo esc_attr(strtolower($p->post_title)); ?>">
                        <a href="<?php echo get_permalink($p); ?>">
                            <i class="fa-solid fa-chevron-left"></i>
                            <span><?php echo esc_html($p->post_title); ?></span>
                            <time><?php echo get_the_date('M j', $p); ?></time>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </section>
            <?php endif; ?>
            
            <!-- Categories -->
            <?php if ($categories): ?>
            <section class="si-sitemap__section" data-section="categories">
                <div class="si-sitemap__section-header" style="--section-color:#f59e0b;">
                    <div class="si-sitemap__section-icon"><i class="fa-solid fa-folder"></i></div>
                    <h2 class="si-sitemap__section-title">دسته‌بندی‌ها</h2>
                    <span class="si-sitemap__section-count"><?php echo count($categories); ?></span>
                </div>
                <ul class="si-sitemap__list si-sitemap__list--grid">
                    <?php foreach ($categories as $cat): ?>
                    <li class="si-sitemap__item si-sitemap__item--card" data-search="<?php echo esc_attr(strtolower($cat->name)); ?>">
                        <a href="<?php echo get_category_link($cat); ?>">
                            <span class="si-sitemap__card-name"><?php echo esc_html($cat->name); ?></span>
                            <span class="si-sitemap__card-count"><?php echo $cat->count; ?> نوشته</span>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </section>
            <?php endif; ?>
            
            <!-- Tags -->
            <?php if ($tags): ?>
            <section class="si-sitemap__section" data-section="tags">
                <div class="si-sitemap__section-header" style="--section-color:#8b5cf6;">
                    <div class="si-sitemap__section-icon"><i class="fa-solid fa-tags"></i></div>
                    <h2 class="si-sitemap__section-title">برچسب‌ها</h2>
                    <span class="si-sitemap__section-count"><?php echo count($tags); ?></span>
                </div>
                <div class="si-sitemap__tags">
                    <?php foreach ($tags as $tag): ?>
                    <a href="<?php echo get_tag_link($tag); ?>" class="si-sitemap__tag" data-search="<?php echo esc_attr(strtolower($tag->name)); ?>">
                        #<?php echo esc_html($tag->name); ?>
                        <span><?php echo $tag->count; ?></span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
            
            <!-- Archives -->
            <?php if ($archives): ?>
            <section class="si-sitemap__section" data-section="archives">
                <div class="si-sitemap__section-header" style="--section-color:#ec4899;">
                    <div class="si-sitemap__section-icon"><i class="fa-solid fa-calendar-alt"></i></div>
                    <h2 class="si-sitemap__section-title">آرشیو ماهانه</h2>
                </div>
                <ul class="si-sitemap__list si-sitemap__list--archives">
                    <?php echo $archives; ?>
                </ul>
            </section>
            <?php endif; ?>
        </div>
    </div>
    <?php
    
    return ob_get_clean();
}

add_action('wp_head', 'si_sitemap_assets');
function si_sitemap_assets() {
    global $post;
    if (!is_page() || !$post || $post->post_name !== 'sitemap') return;
    ?>
    <style>
    .si-sitemap {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--si-space-8) var(--si-space-4);
    }
    
    /* Hero */
    .si-sitemap__hero {
        text-align: center;
        padding: var(--si-space-12) var(--si-space-6);
        background: linear-gradient(135deg, var(--si-primary) 0%, #059669 100%);
        border-radius: var(--si-radius-3xl);
        margin-bottom: var(--si-space-8);
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .si-sitemap__hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    .si-sitemap__hero-icon {
        width: 80px;
        height: 80px;
        background: rgba(255,255,255,0.2);
        border-radius: var(--si-radius-2xl);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--si-space-5);
        font-size: 2rem;
        backdrop-filter: blur(10px);
        position: relative;
    }
    
    .si-sitemap__hero-title {
        font-size: var(--si-text-4xl);
        margin: 0 0 var(--si-space-2);
        color: white;
        position: relative;
    }
    
    .si-sitemap__hero-desc {
        font-size: var(--si-text-lg);
        margin: 0 0 var(--si-space-6);
        opacity: 0.9;
        position: relative;
    }
    
    .si-sitemap__search {
        max-width: 500px;
        margin: 0 auto;
        position: relative;
    }
    
    .si-sitemap__search i {
        position: absolute;
        right: var(--si-space-5);
        top: 50%;
        transform: translateY(-50%);
        color: var(--si-text-muted);
        font-size: 1.1rem;
    }
    
    .si-sitemap__search input {
        width: 100%;
        padding: var(--si-space-4) var(--si-space-5);
        padding-right: var(--si-space-12);
        border: none;
        border-radius: var(--si-radius-full);
        font-size: var(--si-text-base);
        background: white;
        box-shadow: var(--si-shadow-lg);
    }
    
    .si-sitemap__search input:focus {
        outline: none;
        box-shadow: var(--si-shadow-xl), 0 0 0 4px rgba(255,255,255,0.3);
    }
    
    /* Stats */
    .si-sitemap__stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: var(--si-space-4);
        margin-bottom: var(--si-space-10);
    }
    
    .si-sitemap__stat {
        background: var(--si-surface);
        padding: var(--si-space-5);
        border-radius: var(--si-radius-xl);
        text-align: center;
        border: 1px solid var(--si-border-light);
        box-shadow: var(--si-shadow-sm);
        transition: all var(--si-transition);
    }
    
    .si-sitemap__stat:hover {
        transform: translateY(-4px);
        box-shadow: var(--si-shadow-lg);
    }
    
    .si-sitemap__stat-icon {
        width: 48px;
        height: 48px;
        background: color-mix(in srgb, var(--stat-color) 15%, transparent);
        color: var(--stat-color);
        border-radius: var(--si-radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--si-space-3);
        font-size: 1.25rem;
    }
    
    .si-sitemap__stat-num {
        font-size: var(--si-text-2xl);
        font-weight: var(--si-font-extrabold);
        color: var(--si-text);
    }
    
    .si-sitemap__stat-label {
        font-size: var(--si-text-sm);
        color: var(--si-text-muted);
    }
    
    /* Grid */
    .si-sitemap__grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: var(--si-space-6);
    }
    
    /* Section */
    .si-sitemap__section {
        background: var(--si-surface);
        border-radius: var(--si-radius-2xl);
        overflow: hidden;
        border: 1px solid var(--si-border-light);
        box-shadow: var(--si-shadow);
        transition: all var(--si-transition);
    }
    
    .si-sitemap__section:hover {
        box-shadow: var(--si-shadow-lg);
    }
    
    .si-sitemap__section-header {
        display: flex;
        align-items: center;
        gap: var(--si-space-3);
        padding: var(--si-space-5) var(--si-space-6);
        background: linear-gradient(135deg, color-mix(in srgb, var(--section-color) 10%, transparent), color-mix(in srgb, var(--section-color) 5%, transparent));
        border-bottom: 1px solid var(--si-border-light);
    }
    
    .si-sitemap__section-icon {
        width: 40px;
        height: 40px;
        background: var(--section-color);
        color: white;
        border-radius: var(--si-radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    
    .si-sitemap__section-title {
        flex: 1;
        margin: 0;
        font-size: var(--si-text-lg);
        color: var(--si-text);
    }
    
    .si-sitemap__section-count {
        background: var(--section-color);
        color: white;
        padding: var(--si-space-1) var(--si-space-3);
        border-radius: var(--si-radius-full);
        font-size: var(--si-text-xs);
        font-weight: var(--si-font-bold);
    }
    
    /* List */
    .si-sitemap__list {
        list-style: none;
        margin: 0;
        padding: var(--si-space-4);
        max-height: 400px;
        overflow-y: auto;
    }
    
    .si-sitemap__item {
        margin-bottom: var(--si-space-1);
    }
    
    .si-sitemap__item a {
        display: flex;
        align-items: center;
        gap: var(--si-space-3);
        padding: var(--si-space-3) var(--si-space-4);
        border-radius: var(--si-radius-lg);
        color: var(--si-text-secondary);
        transition: all var(--si-transition-fast);
    }
    
    .si-sitemap__item a:hover {
        background: var(--si-bg-secondary);
        color: var(--si-primary);
        transform: translateX(-4px);
    }
    
    .si-sitemap__item a i {
        font-size: 0.7em;
        color: var(--si-text-muted);
        transition: color var(--si-transition-fast);
    }
    
    .si-sitemap__item a:hover i {
        color: var(--si-primary);
    }
    
    .si-sitemap__item a span {
        flex: 1;
    }
    
    .si-sitemap__item a time {
        font-size: var(--si-text-xs);
        color: var(--si-text-muted);
        background: var(--si-bg-tertiary);
        padding: var(--si-space-1) var(--si-space-2);
        border-radius: var(--si-radius);
    }
    
    /* Grid List (Categories) */
    .si-sitemap__list--grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: var(--si-space-3);
    }
    
    .si-sitemap__item--card a {
        flex-direction: column;
        align-items: flex-start;
        background: var(--si-bg-secondary);
        border: 1px solid var(--si-border-light);
    }
    
    .si-sitemap__item--card a:hover {
        transform: translateY(-2px);
        border-color: var(--si-primary);
    }
    
    .si-sitemap__card-name {
        font-weight: var(--si-font-semibold);
        color: var(--si-text);
    }
    
    .si-sitemap__card-count {
        font-size: var(--si-text-xs);
        color: var(--si-text-muted);
    }
    
    /* Tags */
    .si-sitemap__tags {
        display: flex;
        flex-wrap: wrap;
        gap: var(--si-space-2);
        padding: var(--si-space-5);
    }
    
    .si-sitemap__tag {
        display: inline-flex;
        align-items: center;
        gap: var(--si-space-2);
        padding: var(--si-space-2) var(--si-space-4);
        background: var(--si-bg-secondary);
        border: 1px solid var(--si-border);
        border-radius: var(--si-radius-full);
        font-size: var(--si-text-sm);
        color: var(--si-text-secondary);
        transition: all var(--si-transition-fast);
    }
    
    .si-sitemap__tag:hover {
        background: var(--si-primary);
        border-color: var(--si-primary);
        color: white;
        transform: translateY(-2px);
    }
    
    .si-sitemap__tag span {
        font-size: var(--si-text-xs);
        background: var(--si-bg-tertiary);
        padding: 2px 8px;
        border-radius: var(--si-radius-full);
        color: var(--si-text-muted);
        transition: all var(--si-transition-fast);
    }
    
    .si-sitemap__tag:hover span {
        background: rgba(255,255,255,0.2);
        color: white;
    }
    
    /* Archives */
    .si-sitemap__list--archives {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: var(--si-space-2);
    }
    
    .si-sitemap__list--archives li a {
        display: block;
        padding: var(--si-space-3);
        background: var(--si-bg-secondary);
        border-radius: var(--si-radius-lg);
        text-align: center;
        font-size: var(--si-text-sm);
        color: var(--si-text-secondary);
        transition: all var(--si-transition-fast);
    }
    
    .si-sitemap__list--archives li a:hover {
        background: var(--si-primary);
        color: white;
    }
    
    /* Hidden items */
    .si-sitemap__item--hidden,
    .si-sitemap__tag--hidden {
        display: none !important;
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .si-sitemap__grid {
            grid-template-columns: 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .si-sitemap__stats {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .si-sitemap__hero {
            padding: var(--si-space-8) var(--si-space-4);
        }
        
        .si-sitemap__hero-title {
            font-size: var(--si-text-2xl);
        }
        
        .si-sitemap__list--grid,
        .si-sitemap__list--archives {
            grid-template-columns: 1fr;
        }
    }
    </style>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const search = document.getElementById('si-sitemap-search');
        if (!search) return;
        
        const items = document.querySelectorAll('[data-search]');
        
        search.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            
            items.forEach(item => {
                const text = item.dataset.search;
                const match = !query || text.includes(query);
                
                if (item.classList.contains('si-sitemap__tag')) {
                    item.classList.toggle('si-sitemap__tag--hidden', !match);
                } else {
                    item.classList.toggle('si-sitemap__item--hidden', !match);
                }
            });
        });
    });
    </script>
    <?php
}
