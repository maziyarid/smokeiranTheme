/**
 * =============================================================================
 * SI-Star-Rating
 * =============================================================================
 * Beautiful IP-Based Star Rating System
 * Priority: 10 | Location: Run Everywhere
 * =============================================================================
 */

if (!defined('ABSPATH')) exit;

// Database Setup
add_action('init', 'si_rating_setup_db');
function si_rating_setup_db() {
    global $wpdb;
    $table = $wpdb->prefix . 'si_ratings';
    
    if (get_option('si_rating_db_ver') !== '1.1') {
        $sql = "CREATE TABLE $table (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            post_id BIGINT(20) UNSIGNED NOT NULL,
            ip_address VARCHAR(45) NOT NULL,
            rating TINYINT(1) UNSIGNED NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY unique_rating (post_id, ip_address),
            KEY post_id (post_id)
        ) " . $wpdb->get_charset_collate() . ";";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        update_option('si_rating_db_ver', '1.1');
    }
}

// Display Rating Box
add_filter('the_content', 'si_rating_render', 99);
function si_rating_render($content) {
    if (!is_singular('post') || !in_the_loop() || !is_main_query()) {
        return $content;
    }
    
    global $post, $wpdb;
    $table = $wpdb->prefix . 'si_ratings';
    $ip = si_get_user_ip();
    
    $user_rating = $wpdb->get_var($wpdb->prepare(
        "SELECT rating FROM $table WHERE post_id = %d AND ip_address = %s",
        $post->ID, $ip
    ));
    
    $stats = $wpdb->get_row($wpdb->prepare(
        "SELECT AVG(rating) as average, COUNT(*) as total FROM $table WHERE post_id = %d",
        $post->ID
    ));
    
    $avg = $stats->average ? round($stats->average, 1) : 0;
    $total = $stats->total ?: 0;
    $has_voted = !empty($user_rating);
    
    ob_start();
    ?>
    <div class="si-rating <?php echo $has_voted ? 'si-rating--voted' : ''; ?>" 
         data-post="<?php echo $post->ID; ?>"
         data-voted="<?php echo $has_voted ? '1' : '0'; ?>">
        
        <div class="si-rating__header">
            <div class="si-rating__icon">
                <i class="fa-solid fa-star"></i>
            </div>
            <div class="si-rating__title">
                <?php echo $has_voted ? 'ممنون از امتیاز شما!' : 'به این مطلب امتیاز دهید'; ?>
            </div>
        </div>
        
        <div class="si-rating__body">
            <div class="si-rating__stars">
                <?php for ($i = 5; $i >= 1; $i--): ?>
                <button type="button" 
                        class="si-rating__star <?php echo ($user_rating >= $i) ? 'si-rating__star--active' : ''; ?>"
                        data-value="<?php echo $i; ?>"
                        aria-label="امتیاز <?php echo $i; ?>"
                        <?php echo $has_voted ? 'disabled' : ''; ?>>
                    <i class="fa-solid fa-star"></i>
                    <span class="si-rating__star-glow"></span>
                </button>
                <?php endfor; ?>
            </div>
            
            <div class="si-rating__stats">
                <div class="si-rating__score">
                    <span class="si-rating__avg"><?php echo $avg; ?></span>
                    <span class="si-rating__max">/ 5</span>
                </div>
                <div class="si-rating__count">
                    از <strong class="si-rating__total"><?php echo $total; ?></strong> رای
                </div>
            </div>
        </div>
        
        <div class="si-rating__message"></div>
    </div>
    <?php
    
    return $content . ob_get_clean();
}

// Get User IP
function si_get_user_ip() {
    $ip_keys = array('HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR');
    foreach ($ip_keys as $key) {
        if (!empty($_SERVER[$key])) {
            $ip = explode(',', $_SERVER[$key]);
            return trim($ip[0]);
        }
    }
    return '0.0.0.0';
}

// AJAX Handler
add_action('wp_ajax_si_rate', 'si_rating_ajax');
add_action('wp_ajax_nopriv_si_rate', 'si_rating_ajax');

function si_rating_ajax() {
    check_ajax_referer('si_rating_nonce', 'nonce');
    
    $post_id = absint($_POST['post_id']);
    $rating = absint($_POST['rating']);
    $ip = si_get_user_ip();
    
    if ($rating < 1 || $rating > 5) {
        wp_send_json_error(['message' => 'امتیاز نامعتبر است.']);
    }
    
    global $wpdb;
    $table = $wpdb->prefix . 'si_ratings';
    
    // Check existing vote
    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table WHERE post_id = %d AND ip_address = %s",
        $post_id, $ip
    ));
    
    if ($exists) {
        wp_send_json_error(['message' => 'شما قبلاً امتیاز داده‌اید.']);
    }
    
    // Insert rating
    $wpdb->insert($table, [
        'post_id' => $post_id,
        'ip_address' => $ip,
        'rating' => $rating
    ]);
    
    // Get updated stats
    $stats = $wpdb->get_row($wpdb->prepare(
        "SELECT AVG(rating) as average, COUNT(*) as total FROM $table WHERE post_id = %d",
        $post_id
    ));
    
    wp_send_json_success([
        'message' => 'ممنون از امتیاز شما!',
        'average' => round($stats->average, 1),
        'total' => $stats->total
    ]);
}

// Styles & Scripts
add_action('wp_head', 'si_rating_assets');
function si_rating_assets() {
    if (!is_singular('post')) return;
    
    $nonce = wp_create_nonce('si_rating_nonce');
    ?>
    <style>
    /* Rating Container */
    .si-rating {
        background: linear-gradient(135deg, var(--si-surface) 0%, var(--si-bg-secondary) 100%);
        border: 1px solid var(--si-border);
        border-radius: var(--si-radius-2xl);
        padding: var(--si-space-8);
        margin: var(--si-space-10) 0;
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: var(--si-shadow-md);
        transition: all var(--si-transition);
    }
    
    .si-rating::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--si-primary), var(--si-accent), var(--si-primary));
        background-size: 200% 100%;
        animation: si-shimmer 3s linear infinite;
    }
    
    .si-rating:hover {
        box-shadow: var(--si-shadow-xl);
        transform: translateY(-2px);
    }
    
    /* Header */
    .si-rating__header {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--si-space-3);
        margin-bottom: var(--si-space-6);
    }
    
    .si-rating__icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        border-radius: var(--si-radius-xl);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        box-shadow: 0 4px 12px rgba(251, 191, 36, 0.4);
        animation: si-bounce 2s infinite;
    }
    
    .si-rating__title {
        font-size: var(--si-text-lg);
        font-weight: var(--si-font-bold);
        color: var(--si-text);
    }
    
    /* Stars */
    .si-rating__body {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: var(--si-space-5);
    }
    
    .si-rating__stars {
        display: flex;
        flex-direction: row-reverse;
        gap: var(--si-space-2);
    }
    
    .si-rating__star {
        position: relative;
        width: 48px;
        height: 48px;
        background: transparent;
        border: none;
        cursor: pointer;
        font-size: 2rem;
        color: var(--si-gray-300);
        transition: all var(--si-duration-200) var(--si-ease);
        border-radius: var(--si-radius-full);
    }
    
    .si-rating__star i {
        position: relative;
        z-index: 1;
        transition: transform var(--si-duration-200) var(--si-ease-bounce);
    }
    
    .si-rating__star-glow {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle, rgba(251, 191, 36, 0.3) 0%, transparent 70%);
        border-radius: var(--si-radius-full);
        opacity: 0;
        transform: scale(0.5);
        transition: all var(--si-duration-300) var(--si-ease);
    }
    
    /* Star Hover Effects */
    .si-rating:not(.si-rating--voted) .si-rating__star:hover,
    .si-rating:not(.si-rating--voted) .si-rating__star:hover ~ .si-rating__star {
        color: #fbbf24;
    }
    
    .si-rating:not(.si-rating--voted) .si-rating__star:hover i {
        transform: scale(1.2);
    }
    
    .si-rating:not(.si-rating--voted) .si-rating__star:hover .si-rating__star-glow {
        opacity: 1;
        transform: scale(1.5);
    }
    
    /* Active Stars */
    .si-rating__star--active {
        color: #fbbf24 !important;
    }
    
    .si-rating__star--active .si-rating__star-glow {
        opacity: 0.5;
        transform: scale(1);
    }
    
    /* Voted State */
    .si-rating--voted .si-rating__star {
        cursor: default;
        pointer-events: none;
    }
    
    .si-rating--voted .si-rating__icon {
        background: linear-gradient(135deg, var(--si-success), #059669);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }
    
    .si-rating--voted .si-rating__icon i::before {
        content: "\\f00c";
    }
    
    /* Stats */
    .si-rating__stats {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: var(--si-space-1);
    }
    
    .si-rating__score {
        display: flex;
        align-items: baseline;
        gap: var(--si-space-1);
    }
    
    .si-rating__avg {
        font-size: var(--si-text-3xl);
        font-weight: var(--si-font-extrabold);
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .si-rating__max {
        font-size: var(--si-text-lg);
        color: var(--si-text-muted);
        font-weight: var(--si-font-medium);
    }
    
    .si-rating__count {
        font-size: var(--si-text-sm);
        color: var(--si-text-tertiary);
    }
    
    .si-rating__total {
        color: var(--si-primary);
    }
    
    /* Message */
    .si-rating__message {
        margin-top: var(--si-space-4);
        font-size: var(--si-text-sm);
        font-weight: var(--si-font-semibold);
        min-height: 1.5em;
        opacity: 0;
        transform: translateY(10px);
        transition: all var(--si-transition);
    }
    
    .si-rating__message--visible {
        opacity: 1;
        transform: translateY(0);
    }
    
    .si-rating__message--success {
        color: var(--si-success);
    }
    
    .si-rating__message--error {
        color: var(--si-danger);
    }
    
    /* Responsive */
    @media (max-width: 640px) {
        .si-rating {
            padding: var(--si-space-6);
        }
        
        .si-rating__star {
            width: 40px;
            height: 40px;
            font-size: 1.5rem;
        }
        
        .si-rating__icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
        
        .si-rating__title {
            font-size: var(--si-text-base);
        }
    }
    </style>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const rating = document.querySelector('.si-rating');
        if (!rating || rating.dataset.voted === '1') return;
        
        const stars = rating.querySelectorAll('.si-rating__star');
        const message = rating.querySelector('.si-rating__message');
        const titleEl = rating.querySelector('.si-rating__title');
        const avgEl = rating.querySelector('.si-rating__avg');
        const totalEl = rating.querySelector('.si-rating__total');
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const value = parseInt(this.dataset.value);
                const postId = rating.dataset.post;
                
                // Immediate visual feedback
                rating.classList.add('si-rating--voted');
                stars.forEach(s => {
                    s.classList.toggle('si-rating__star--active', parseInt(s.dataset.value) <= value);
                    s.disabled = true;
                });
                
                // Add ripple effect
                this.style.transform = 'scale(1.3)';
                setTimeout(() => this.style.transform = '', 200);
                
                // Send AJAX
                fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        action: 'si_rate',
                        nonce: '<?php echo $nonce; ?>',
                        post_id: postId,
                        rating: value
                    })
                })
                .then(r => r.json())
                .then(res => {
                    message.classList.add('si-rating__message--visible');
                    
                    if (res.success) {
                        message.classList.add('si-rating__message--success');
                        message.textContent = res.data.message;
                        avgEl.textContent = res.data.average;
                        totalEl.textContent = res.data.total;
                        titleEl.textContent = 'ممنون از امتیاز شما!';
                    } else {
                        message.classList.add('si-rating__message--error');
                        message.textContent = res.data.message;
                    }
                })
                .catch(() => {
                    message.classList.add('si-rating__message--visible', 'si-rating__message--error');
                    message.textContent = 'خطا در ارتباط با سرور';
                });
            });
        });
    });
    </script>
    <?php
}
