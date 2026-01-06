/**
 * =============================================================================
 * SI-Key-Takeaways
 * =============================================================================
 * Summary/Key Points Section with Schema.org markup
 * Priority: 10 | Location: Run Everywhere
 * =============================================================================
 */

if (!defined('ABSPATH')) exit;

// Meta Box
add_action('add_meta_boxes', 'si_takeaways_add_metabox');
function si_takeaways_add_metabox() {
    add_meta_box(
        'si_takeaways_metabox',
        '<span style="display:flex;align-items:center;gap:8px;"><i class="fa-solid fa-lightbulb" style="color:#f59e0b;"></i> خلاصه مطلب (Key Takeaways)</span>',
        'si_takeaways_metabox_html',
        array('post', 'page'),
        'normal',
        'high'
    );
}

function si_takeaways_metabox_html($post) {
    wp_nonce_field('si_takeaways_nonce_action', 'si_takeaways_nonce');
    $takeaways = get_post_meta($post->ID, '_si_takeaways', true);
    $heading = get_post_meta($post->ID, '_si_takeaways_heading', true) ?: 'در این مقاله خواهید خواند:';
    $style = get_post_meta($post->ID, '_si_takeaways_style', true) ?: 'modern';
    ?>
    <style>
    .si-takeaways-admin { padding: 20px; background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border-radius: 12px; }
    .si-takeaways-admin__row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    .si-takeaways-admin__field { }
    .si-takeaways-admin__field label { display: block; font-weight: 600; margin-bottom: 8px; color: #374151; font-size: 14px; }
    .si-takeaways-admin__field input, .si-takeaways-admin__field select, .si-takeaways-admin__field textarea { width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 14px; transition: all 0.2s; background: white; }
    .si-takeaways-admin__field input:focus, .si-takeaways-admin__field select:focus, .si-takeaways-admin__field textarea:focus { outline: none; border-color: #f59e0b; box-shadow: 0 0 0 4px rgba(245,158,11,0.1); }
    .si-takeaways-admin__field textarea { min-height: 150px; resize: vertical; }
    .si-takeaways-admin__help { font-size: 12px; color: #6b7280; margin-top: 6px; display: flex; align-items: center; gap: 6px; }
    .si-takeaways-admin__help i { color: #f59e0b; }
    @media (max-width: 782px) { .si-takeaways-admin__row { grid-template-columns: 1fr; } }
    </style>
    
    <div class="si-takeaways-admin">
        <div class="si-takeaways-admin__row">
            <div class="si-takeaways-admin__field">
                <label for="si_takeaways_heading">عنوان بخش:</label>
                <input type="text" id="si_takeaways_heading" name="si_takeaways_heading" value="<?php echo esc_attr($heading); ?>" placeholder="در این مقاله خواهید خواند:">
            </div>
            <div class="si-takeaways-admin__field">
                <label for="si_takeaways_style">استایل نمایش:</label>
                <select id="si_takeaways_style" name="si_takeaways_style">
                    <option value="modern" <?php selected($style, 'modern'); ?>>🎨 مدرن (کارت)</option>
                    <option value="minimal" <?php selected($style, 'minimal'); ?>>✨ مینیمال</option>
                    <option value="gradient" <?php selected($style, 'gradient'); ?>>🌈 گرادیانت</option>
                    <option value="glass" <?php selected($style, 'glass'); ?>>💎 شیشه‌ای</option>
                </select>
            </div>
        </div>
        
        <div class="si-takeaways-admin__field">
            <label for="si_takeaways_content">نکات کلیدی (هر نکته در یک خط جدید):</label>
            <textarea id="si_takeaways_content" name="si_takeaways_content" placeholder="نکته اول&#10;نکته دوم&#10;نکته سوم..."><?php echo esc_textarea($takeaways); ?></textarea>
            <p class="si-takeaways-admin__help">
                <i class="fa-solid fa-info-circle"></i>
                هر نکته کلیدی را در یک خط جدید بنویسید. این بخش به خوانندگان کمک می‌کند تا خلاصه مطلب را سریع‌تر درک کنند.
            </p>
        </div>
    </div>
    <?php
}

// Save
add_action('save_post', 'si_takeaways_save');
function si_takeaways_save($post_id) {
    if (!isset($_POST['si_takeaways_nonce']) || !wp_verify_nonce($_POST['si_takeaways_nonce'], 'si_takeaways_nonce_action')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    if (isset($_POST['si_takeaways_content'])) {
        update_post_meta($post_id, '_si_takeaways', sanitize_textarea_field($_POST['si_takeaways_content']));
    }
    if (isset($_POST['si_takeaways_heading'])) {
        update_post_meta($post_id, '_si_takeaways_heading', sanitize_text_field($_POST['si_takeaways_heading']));
    }
    if (isset($_POST['si_takeaways_style'])) {
        update_post_meta($post_id, '_si_takeaways_style', sanitize_text_field($_POST['si_takeaways_style']));
    }
}

// Frontend Display
add_filter('the_content', 'si_takeaways_display', 8);
function si_takeaways_display($content) {
    if (!is_singular(array('post', 'page')) || !in_the_loop() || !is_main_query()) return $content;
    
    global $post;
    $takeaways = get_post_meta($post->ID, '_si_takeaways', true);
    if (empty(trim($takeaways))) return $content;
    
    $heading = get_post_meta($post->ID, '_si_takeaways_heading', true) ?: 'در این مقاله خواهید خواند:';
    $style = get_post_meta($post->ID, '_si_takeaways_style', true) ?: 'modern';
    
    $lines = array_filter(array_map('trim', explode("\n", $takeaways)));
    if (empty($lines)) return $content;
    
    ob_start();
    ?>
    <aside class="si-takeaways si-takeaways--<?php echo esc_attr($style); ?>" itemscope itemtype="https://schema.org/ItemList">
        <div class="si-takeaways__header">
            <div class="si-takeaways__icon">
                <i class="fa-solid fa-lightbulb"></i>
            </div>
            <h3 class="si-takeaways__title" itemprop="name"><?php echo esc_html($heading); ?></h3>
        </div>
        
        <ul class="si-takeaways__list">
            <?php foreach ($lines as $index => $line): ?>
            <li class="si-takeaways__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <span class="si-takeaways__check">
                    <i class="fa-solid fa-check"></i>
                </span>
                <span class="si-takeaways__text" itemprop="name"><?php echo esc_html($line); ?></span>
                <meta itemprop="position" content="<?php echo $index + 1; ?>">
            </li>
            <?php endforeach; ?>
        </ul>
        
        <meta itemprop="numberOfItems" content="<?php echo count($lines); ?>">
    </aside>
    <?php
    
    return ob_get_clean() . $content;
}

// Styles
add_action('wp_head', 'si_takeaways_assets');
function si_takeaways_assets() {
    if (!is_singular(array('post', 'page'))) return;
    ?>
    <style>
    /* Base Styles */
    .si-takeaways {
        margin: 0 0 var(--si-space-10);
        padding: var(--si-space-6);
        border-radius: var(--si-radius-2xl);
        position: relative;
        overflow: hidden;
    }
    
    .si-takeaways__header {
        display: flex;
        align-items: center;
        gap: var(--si-space-4);
        margin-bottom: var(--si-space-5);
    }
    
    .si-takeaways__icon {
        width: 44px;
        height: 44px;
        border-radius: var(--si-radius-xl);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    
    .si-takeaways__title {
        margin: 0;
        font-size: var(--si-text-lg);
        font-weight: var(--si-font-bold);
        line-height: 1.3;
    }
    
    .si-takeaways__list {
        list-style: none;
        margin: 0;
        padding: 0;
        display: grid;
        gap: var(--si-space-3);
    }
    
    .si-takeaways__item {
        display: flex;
        align-items: flex-start;
        gap: var(--si-space-3);
        padding: var(--si-space-3) var(--si-space-4);
        border-radius: var(--si-radius-lg);
        transition: all var(--si-transition-fast);
    }
    
    .si-takeaways__check {
        width: 24px;
        height: 24px;
        border-radius: var(--si-radius-full);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        flex-shrink: 0;
        margin-top: 2px;
    }
    
    .si-takeaways__text {
        flex: 1;
        line-height: 1.6;
    }
    
    /* Modern Style */
    .si-takeaways--modern {
        background: linear-gradient(135deg, var(--si-bg-secondary) 0%, var(--si-surface) 100%);
        border: 1px solid var(--si-border-light);
        box-shadow: var(--si-shadow-md);
    }
    
    .si-takeaways--modern .si-takeaways__icon {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
    
    .si-takeaways--modern .si-takeaways__title {
        color: var(--si-text);
    }
    
    .si-takeaways--modern .si-takeaways__item {
        background: var(--si-surface);
        border: 1px solid var(--si-border-light);
    }
    
    .si-takeaways--modern .si-takeaways__item:hover {
        border-color: var(--si-primary);
        transform: translateX(-4px);
        box-shadow: var(--si-shadow-sm);
    }
    
    .si-takeaways--modern .si-takeaways__check {
        background: linear-gradient(135deg, var(--si-primary), var(--si-primary-dark));
        color: white;
    }
    
    .si-takeaways--modern .si-takeaways__text {
        color: var(--si-text-secondary);
    }
    
    /* Minimal Style */
    .si-takeaways--minimal {
        background: var(--si-bg-secondary);
        border-right: 4px solid var(--si-primary);
        border-radius: 0 var(--si-radius-xl) var(--si-radius-xl) 0;
    }
    
    .si-takeaways--minimal .si-takeaways__icon {
        background: var(--si-primary-lighter);
        color: var(--si-primary);
    }
    
    .si-takeaways--minimal .si-takeaways__title {
        color: var(--si-text);
    }
    
    .si-takeaways--minimal .si-takeaways__item:hover {
        background: var(--si-surface);
    }
    
    .si-takeaways--minimal .si-takeaways__check {
        background: var(--si-primary);
        color: white;
    }
    
    .si-takeaways--minimal .si-takeaways__text {
        color: var(--si-text-secondary);
    }
    
    /* Gradient Style */
    .si-takeaways--gradient {
        background: linear-gradient(135deg, var(--si-primary) 0%, #059669 100%);
        color: white;
    }
    
    .si-takeaways--gradient .si-takeaways__icon {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        backdrop-filter: blur(10px);
    }
    
    .si-takeaways--gradient .si-takeaways__title {
        color: white;
    }
    
    .si-takeaways--gradient .si-takeaways__item {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
    }
    
    .si-takeaways--gradient .si-takeaways__item:hover {
        background: rgba(255, 255, 255, 0.2);
    }
    
    .si-takeaways--gradient .si-takeaways__check {
        background: white;
        color: var(--si-primary);
    }
    
    .si-takeaways--gradient .si-takeaways__text {
        color: rgba(255, 255, 255, 0.95);
    }
    
    /* Glass Style */
    .si-takeaways--glass {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }
    
    [data-theme="dark"] .si-takeaways--glass {
        background: rgba(30, 30, 50, 0.7);
        border-color: rgba(255, 255, 255, 0.1);
    }
    
    .si-takeaways--glass .si-takeaways__icon {
        background: linear-gradient(135deg, #a855f7, #6366f1);
        color: white;
        box-shadow: 0 4px 12px rgba(168, 85, 247, 0.3);
    }
    
    .si-takeaways--glass .si-takeaways__title {
        color: var(--si-text);
    }
    
    .si-takeaways--glass .si-takeaways__item {
        background: rgba(255, 255, 255, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    
    [data-theme="dark"] .si-takeaways--glass .si-takeaways__item {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.1);
    }
    
    .si-takeaways--glass .si-takeaways__item:hover {
        background: rgba(255, 255, 255, 0.8);
        transform: translateX(-4px);
    }
    
    [data-theme="dark"] .si-takeaways--glass .si-takeaways__item:hover {
        background: rgba(255, 255, 255, 0.1);
    }
    
    .si-takeaways--glass .si-takeaways__check {
        background: linear-gradient(135deg, #a855f7, #6366f1);
        color: white;
    }
    
    .si-takeaways--glass .si-takeaways__text {
        color: var(--si-text-secondary);
    }
    
    /* Animation */
    .si-takeaways {
        animation: si-fadeInUp var(--si-duration-500) var(--si-ease);
    }
    
    .si-takeaways__item {
        animation: si-slideInRight var(--si-duration-400) var(--si-ease) backwards;
    }
    
    .si-takeaways__item:nth-child(1) { animation-delay: 0.1s; }
    .si-takeaways__item:nth-child(2) { animation-delay: 0.15s; }
    .si-takeaways__item:nth-child(3) { animation-delay: 0.2s; }
    .si-takeaways__item:nth-child(4) { animation-delay: 0.25s; }
    .si-takeaways__item:nth-child(5) { animation-delay: 0.3s; }
    .si-takeaways__item:nth-child(6) { animation-delay: 0.35s; }
    .si-takeaways__item:nth-child(7) { animation-delay: 0.4s; }
    .si-takeaways__item:nth-child(8) { animation-delay: 0.45s; }
    
    /* Responsive */
    @media (max-width: 768px) {
        .si-takeaways {
            padding: var(--si-space-5);
            margin-bottom: var(--si-space-8);
        }
        
        .si-takeaways__icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
        
        .si-takeaways__title {
            font-size: var(--si-text-base);
        }
        
        .si-takeaways__item {
            padding: var(--si-space-2) var(--si-space-3);
        }
    }
    </style>
    <?php
}
