/**
 * =============================================================================
 * SI-Content-Badges
 * =============================================================================
 * Content Type & Difficulty Level Badges
 * Priority: 10 | Location: Run Everywhere
 * =============================================================================
 */

if (!defined('ABSPATH')) exit;

// Content Types
function si_content_types() {
    return [
        'tutorial'   => ['label' => 'آموزش', 'icon' => 'fa-graduation-cap', 'color' => '#10b981', 'bg' => '#d1fae5'],
        'news'       => ['label' => 'خبر', 'icon' => 'fa-newspaper', 'color' => '#3b82f6', 'bg' => '#dbeafe'],
        'review'     => ['label' => 'بررسی', 'icon' => 'fa-star-half-stroke', 'color' => '#f59e0b', 'bg' => '#fef3c7'],
        'guide'      => ['label' => 'راهنما', 'icon' => 'fa-map-signs', 'color' => '#8b5cf6', 'bg' => '#ede9fe'],
        'analysis'   => ['label' => 'تحلیل', 'icon' => 'fa-chart-line', 'color' => '#ef4444', 'bg' => '#fee2e2'],
        'opinion'    => ['label' => 'دیدگاه', 'icon' => 'fa-comment-dots', 'color' => '#ec4899', 'bg' => '#fce7f3'],
        'case_study' => ['label' => 'مطالعه موردی', 'icon' => 'fa-microscope', 'color' => '#06b6d4', 'bg' => '#cffafe'],
        'interview'  => ['label' => 'مصاحبه', 'icon' => 'fa-microphone', 'color' => '#6366f1', 'bg' => '#e0e7ff'],
    ];
}

// Difficulty Levels
function si_difficulty_levels() {
    return [
        'beginner'     => ['label' => 'مبتدی', 'icon' => 'fa-seedling', 'color' => '#22c55e', 'bg' => '#dcfce7'],
        'elementary'   => ['label' => 'ابتدایی', 'icon' => 'fa-leaf', 'color' => '#84cc16', 'bg' => '#ecfccb'],
        'intermediate' => ['label' => 'متوسط', 'icon' => 'fa-fire', 'color' => '#f59e0b', 'bg' => '#fef3c7'],
        'advanced'     => ['label' => 'پیشرفته', 'icon' => 'fa-rocket', 'color' => '#ef4444', 'bg' => '#fee2e2'],
        'expert'       => ['label' => 'حرفه‌ای', 'icon' => 'fa-crown', 'color' => '#8b5cf6', 'bg' => '#ede9fe'],
        'master'       => ['label' => 'استادی', 'icon' => 'fa-gem', 'color' => '#0ea5e9', 'bg' => '#e0f2fe'],
    ];
}

// Meta Boxes
add_action('add_meta_boxes', 'si_badges_add_metaboxes');
function si_badges_add_metaboxes() {
    add_meta_box('si_content_type_box', '<span style="display:flex;align-items:center;gap:8px;"><i class="fa-solid fa-tags" style="color:#3b82f6;"></i> نوع محتوا</span>', 'si_content_type_metabox_html', 'post', 'side', 'high');
    add_meta_box('si_difficulty_box', '<span style="display:flex;align-items:center;gap:8px;"><i class="fa-solid fa-layer-group" style="color:#f59e0b;"></i> سطح دشواری</span>', 'si_difficulty_metabox_html', 'post', 'side', 'high');
}

function si_content_type_metabox_html($post) {
    wp_nonce_field('si_badges_nonce_action', 'si_badges_nonce');
    $selected = get_post_meta($post->ID, '_si_content_type', true);
    $types = si_content_types();
    ?>
    <style>
    .si-badge-options { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 12px; }
    .si-badge-option { position: relative; }
    .si-badge-option input { position: absolute; opacity: 0; pointer-events: none; }
    .si-badge-option label { display: flex; flex-direction: column; align-items: center; gap: 6px; padding: 12px 8px; background: #f9fafb; border: 2px solid #e5e7eb; border-radius: 10px; cursor: pointer; transition: all 0.2s; font-size: 11px; text-align: center; }
    .si-badge-option label:hover { border-color: var(--badge-color); background: var(--badge-bg); }
    .si-badge-option input:checked + label { border-color: var(--badge-color); background: var(--badge-bg); box-shadow: 0 0 0 3px var(--badge-bg); }
    .si-badge-option label i { font-size: 20px; color: var(--badge-color); }
    .si-badge-option label span { color: #374151; font-weight: 600; }
    </style>
    <div class="si-badge-options">
        <?php foreach ($types as $key => $type): ?>
        <div class="si-badge-option" style="--badge-color:<?php echo $type['color']; ?>;--badge-bg:<?php echo $type['bg']; ?>;">
            <input type="radio" id="ct_<?php echo $key; ?>" name="si_content_type" value="<?php echo $key; ?>" <?php checked($selected, $key); ?>>
            <label for="ct_<?php echo $key; ?>">
                <i class="fa-solid <?php echo $type['icon']; ?>"></i>
                <span><?php echo $type['label']; ?></span>
            </label>
        </div>
        <?php endforeach; ?>
    </div>
    <?php
}

function si_difficulty_metabox_html($post) {
    $selected = get_post_meta($post->ID, '_si_difficulty', true);
    $levels = si_difficulty_levels();
    ?>
    <div class="si-badge-options">
        <?php foreach ($levels as $key => $level): ?>
        <div class="si-badge-option" style="--badge-color:<?php echo $level['color']; ?>;--badge-bg:<?php echo $level['bg']; ?>;">
            <input type="radio" id="df_<?php echo $key; ?>" name="si_difficulty" value="<?php echo $key; ?>" <?php checked($selected, $key); ?>>
            <label for="df_<?php echo $key; ?>">
                <i class="fa-solid <?php echo $level['icon']; ?>"></i>
                <span><?php echo $level['label']; ?></span>
            </label>
        </div>
        <?php endforeach; ?>
    </div>
    <?php
}

// Save
add_action('save_post', 'si_badges_save');
function si_badges_save($post_id) {
    if (!isset($_POST['si_badges_nonce']) || !wp_verify_nonce($_POST['si_badges_nonce'], 'si_badges_nonce_action')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    if (isset($_POST['si_content_type'])) {
        update_post_meta($post_id, '_si_content_type', sanitize_text_field($_POST['si_content_type']));
    } else {
        delete_post_meta($post_id, '_si_content_type');
    }
    
    if (isset($_POST['si_difficulty'])) {
        update_post_meta($post_id, '_si_difficulty', sanitize_text_field($_POST['si_difficulty']));
    } else {
        delete_post_meta($post_id, '_si_difficulty');
    }
}

// Frontend Display
add_filter('the_content', 'si_badges_display', 5);
function si_badges_display($content) {
    if (!is_singular('post') || !in_the_loop() || !is_main_query()) return $content;
    
    global $post;
    $type_key = get_post_meta($post->ID, '_si_content_type', true);
    $diff_key = get_post_meta($post->ID, '_si_difficulty', true);
    
    if (!$type_key && !$diff_key) return $content;
    
    $types = si_content_types();
    $levels = si_difficulty_levels();
    
    ob_start();
    ?>
    <div class="si-badges">
        <?php if ($type_key && isset($types[$type_key])): $t = $types[$type_key]; ?>
        <span class="si-badges__item" style="--badge-color:<?php echo $t['color']; ?>;--badge-bg:<?php echo $t['bg']; ?>;">
            <span class="si-badges__icon"><i class="fa-solid <?php echo $t['icon']; ?>"></i></span>
            <span class="si-badges__label"><?php echo $t['label']; ?></span>
        </span>
        <?php endif; ?>
        
        <?php if ($diff_key && isset($levels[$diff_key])): $d = $levels[$diff_key]; ?>
        <span class="si-badges__item si-badges__item--difficulty" style="--badge-color:<?php echo $d['color']; ?>;--badge-bg:<?php echo $d['bg']; ?>;">
            <span class="si-badges__icon"><i class="fa-solid <?php echo $d['icon']; ?>"></i></span>
            <span class="si-badges__label">سطح: <?php echo $d['label']; ?></span>
        </span>
        <?php endif; ?>
    </div>
    <?php
    
    return ob_get_clean() . $content;
}

// Styles
add_action('wp_head', 'si_badges_assets');
function si_badges_assets() {
    if (!is_singular('post')) return;
    ?>
    <style>
    .si-badges {
        display: flex;
        flex-wrap: wrap;
        gap: var(--si-space-3);
        margin-bottom: var(--si-space-6);
        animation: si-fadeInUp var(--si-duration-400) var(--si-ease);
    }
    
    .si-badges__item {
        display: inline-flex;
        align-items: center;
        gap: var(--si-space-2);
        padding: var(--si-space-2) var(--si-space-4);
        background: var(--badge-bg);
        border-radius: var(--si-radius-full);
        font-size: var(--si-text-sm);
        font-weight: var(--si-font-semibold);
        color: var(--badge-color);
        transition: all var(--si-transition-fast);
        position: relative;
        overflow: hidden;
    }
    
    .si-badges__item::before {
        content: '';
        position: absolute;
        inset: 0;
        background: var(--badge-color);
        opacity: 0;
        transition: opacity var(--si-transition-fast);
    }
    
    .si-badges__item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px color-mix(in srgb, var(--badge-color) 30%, transparent);
    }
    
    .si-badges__item:hover::before {
        opacity: 0.1;
    }
    
    .si-badges__icon {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 1;
    }
    
    .si-badges__label {
        position: relative;
        z-index: 1;
    }
    
    /* Animation */
    .si-badges__item:nth-child(1) { animation: si-slideInRight var(--si-duration-300) var(--si-ease) 0.1s backwards; }
    .si-badges__item:nth-child(2) { animation: si-slideInRight var(--si-duration-300) var(--si-ease) 0.2s backwards; }
    
    @media (max-width: 768px) {
        .si-badges {
            gap: var(--si-space-2);
        }
        
        .si-badges__item {
            font-size: var(--si-text-xs);
            padding: var(--si-space-1) var(--si-space-3);
        }
    }
    </style>
    <?php
}
