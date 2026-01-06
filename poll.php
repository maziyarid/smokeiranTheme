/**
 * =============================================================================
 * SI-Poll-System
 * =============================================================================
 * Advanced Poll System with IP-based voting
 * Priority: 10 | Location: Run Everywhere
 * =============================================================================
 */

if (!defined('ABSPATH')) exit;

// Database Setup
add_action('init', 'si_poll_setup_db');
function si_poll_setup_db() {
    global $wpdb;
    $table = $wpdb->prefix . 'si_poll_votes';
    
    if (get_option('si_poll_db_ver') !== '1.0') {
        $sql = "CREATE TABLE $table (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            post_id BIGINT(20) UNSIGNED NOT NULL,
            option_key VARCHAR(50) NOT NULL,
            ip_address VARCHAR(45) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY post_option (post_id, option_key),
            KEY post_ip (post_id, ip_address)
        ) " . $wpdb->get_charset_collate() . ";";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        update_option('si_poll_db_ver', '1.0');
    }
}

// Meta Box
add_action('add_meta_boxes', 'si_poll_add_metabox');
function si_poll_add_metabox() {
    add_meta_box(
        'si_poll_metabox',
        '<span style="display:flex;align-items:center;gap:8px;"><i class="fa-solid fa-chart-pie" style="color:#8b5cf6;"></i> نظرسنجی (Poll)</span>',
        'si_poll_metabox_html',
        'post',
        'normal',
        'high'
    );
}

function si_poll_metabox_html($post) {
    wp_nonce_field('si_poll_nonce_action', 'si_poll_nonce');
    $data = get_post_meta($post->ID, '_si_poll', true) ?: [];
    $enabled = isset($data['enabled']) ? $data['enabled'] : '0';
    $question = isset($data['question']) ? $data['question'] : '';
    $type = isset($data['type']) ? $data['type'] : 'single';
    $options = isset($data['options']) ? $data['options'] : [
        ['text' => '', 'color' => '#29853A'],
        ['text' => '', 'color' => '#3b82f6']
    ];
    ?>
    <style>
    .si-poll-admin { padding: 20px; background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%); border-radius: 12px; }
    .si-poll-admin__toggle { display: flex; align-items: center; gap: 12px; padding-bottom: 20px; margin-bottom: 20px; border-bottom: 2px solid #e9d5ff; }
    .si-poll-admin__toggle input[type="checkbox"] { width: 20px; height: 20px; accent-color: #8b5cf6; }
    .si-poll-admin__body { display: none; }
    .si-poll-admin__body.active { display: block; }
    .si-poll-admin__field { margin-bottom: 20px; }
    .si-poll-admin__field label { display: block; font-weight: 600; margin-bottom: 8px; color: #374151; }
    .si-poll-admin__field input, .si-poll-admin__field select { width: 100%; padding: 12px 16px; border: 2px solid #e9d5ff; border-radius: 10px; background: white; }
    .si-poll-admin__field input:focus, .si-poll-admin__field select:focus { outline: none; border-color: #8b5cf6; }
    .si-poll-admin__type { display: flex; gap: 16px; }
    .si-poll-admin__type label { display: flex; align-items: center; gap: 8px; cursor: pointer; font-weight: normal; }
    .si-poll-admin__options { display: grid; gap: 12px; margin-top: 12px; }
    .si-poll-admin__option { display: flex; gap: 12px; align-items: center; background: white; padding: 12px; border-radius: 10px; border: 1px solid #e9d5ff; }
    .si-poll-admin__option input[type="text"] { flex: 1; }
    .si-poll-admin__option input[type="color"] { width: 44px; height: 44px; padding: 4px; border: 2px solid #e9d5ff; border-radius: 8px; cursor: pointer; }
    .si-poll-admin__option-remove { background: #fee2e2; color: #dc2626; border: none; width: 36px; height: 36px; border-radius: 8px; cursor: pointer; }
    .si-poll-admin__add { display: inline-flex; align-items: center; gap: 8px; margin-top: 12px; padding: 10px 20px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; }
    </style>
    
    <div class="si-poll-admin">
        <div class="si-poll-admin__toggle">
            <input type="checkbox" id="si_poll_enabled" name="si_poll[enabled]" value="1" <?php checked($enabled, '1'); ?> onchange="document.querySelector('.si-poll-admin__body').classList.toggle('active', this.checked)">
            <label for="si_poll_enabled" style="font-weight:600;cursor:pointer;">فعال‌سازی نظرسنجی در این نوشته</label>
        </div>
        
        <div class="si-poll-admin__body <?php echo $enabled === '1' ? 'active' : ''; ?>">
            <div class="si-poll-admin__field">
                <label>سوال نظرسنجی:</label>
                <input type="text" name="si_poll[question]" value="<?php echo esc_attr($question); ?>" placeholder="نظر شما درباره این مطلب چیست؟">
            </div>
            
            <div class="si-poll-admin__field">
                <label>نوع انتخاب:</label>
                <div class="si-poll-admin__type">
                    <label><input type="radio" name="si_poll[type]" value="single" <?php checked($type, 'single'); ?>> تک انتخابی</label>
                    <label><input type="radio" name="si_poll[type]" value="multiple" <?php checked($type, 'multiple'); ?>> چند انتخابی</label>
                </div>
            </div>
            
            <div class="si-poll-admin__field">
                <label>گزینه‌ها:</label>
                <div id="si-poll-options" class="si-poll-admin__options">
                    <?php foreach ($options as $i => $opt): ?>
                    <div class="si-poll-admin__option">
                        <input type="text" name="si_poll[options][<?php echo $i; ?>][text]" value="<?php echo esc_attr($opt['text']); ?>" placeholder="متن گزینه...">
                        <input type="color" name="si_poll[options][<?php echo $i; ?>][color]" value="<?php echo esc_attr($opt['color']); ?>">
                        <button type="button" class="si-poll-admin__option-remove" onclick="this.parentElement.remove()"><i class="fa-solid fa-times"></i></button>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="si-poll-admin__add" onclick="siPollAddOption()">
                    <i class="fa-solid fa-plus"></i> افزودن گزینه
                </button>
            </div>
        </div>
    </div>
    
    <script>
    let siPollCounter = <?php echo count($options); ?>;
    const siPollColors = ['#29853A', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'];
    
    function siPollAddOption() {
        const color = siPollColors[siPollCounter % siPollColors.length];
        document.getElementById('si-poll-options').insertAdjacentHTML('beforeend', `
            <div class="si-poll-admin__option">
                <input type="text" name="si_poll[options][${siPollCounter}][text]" placeholder="متن گزینه...">
                <input type="color" name="si_poll[options][${siPollCounter}][color]" value="${color}">
                <button type="button" class="si-poll-admin__option-remove" onclick="this.parentElement.remove()"><i class="fa-solid fa-times"></i></button>
            </div>
        `);
        siPollCounter++;
    }
    </script>
    <?php
}

// Save
add_action('save_post', 'si_poll_save');
function si_poll_save($post_id) {
    if (!isset($_POST['si_poll_nonce']) || !wp_verify_nonce($_POST['si_poll_nonce'], 'si_poll_nonce_action')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    if (isset($_POST['si_poll'])) {
        $data = $_POST['si_poll'];
        $data['enabled'] = isset($data['enabled']) ? '1' : '0';
        if (isset($data['options'])) {
            $data['options'] = array_values(array_filter($data['options'], function($opt) {
                return !empty($opt['text']);
            }));
        }
        update_post_meta($post_id, '_si_poll', $data);
    }
}

// Frontend Display
add_filter('the_content', 'si_poll_display', 85);
function si_poll_display($content) {
    if (!is_singular('post') || !in_the_loop() || !is_main_query()) return $content;
    
    global $post, $wpdb;
    $data = get_post_meta($post->ID, '_si_poll', true);
    
    if (!isset($data['enabled']) || $data['enabled'] !== '1') return $content;
    if (empty($data['question']) || empty($data['options'])) return $content;
    
    $table = $wpdb->prefix . 'si_poll_votes';
    $ip = si_poll_get_ip();
    
    $has_voted = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table WHERE post_id = %d AND ip_address = %s",
        $post->ID, $ip
    ));
    
    $total = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE post_id = %d", $post->ID));
    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT option_key, COUNT(*) as votes FROM $table WHERE post_id = %d GROUP BY option_key",
        $post->ID
    ), OBJECT_K);
    
    $nonce = wp_create_nonce('si_poll_vote_nonce');
    
    ob_start();
    ?>
    <div class="si-poll <?php echo $has_voted ? 'si-poll--voted' : ''; ?>" data-post="<?php echo $post->ID; ?>" data-nonce="<?php echo $nonce; ?>">
        <div class="si-poll__header">
            <div class="si-poll__icon">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
            <h3 class="si-poll__question"><?php echo esc_html($data['question']); ?></h3>
            <div class="si-poll__meta">
                <i class="fa-solid fa-users"></i>
                <span class="si-poll__total"><?php echo $total; ?></span> رای
            </div>
        </div>
        
        <div class="si-poll__body">
            <?php foreach ($data['options'] as $key => $opt):
                $votes = isset($results[$key]) ? $results[$key]->votes : 0;
                $percent = $total > 0 ? round(($votes / $total) * 100) : 0;
            ?>
            <div class="si-poll__option" data-key="<?php echo $key; ?>" style="--opt-color:<?php echo esc_attr($opt['color']); ?>;">
                <label class="si-poll__label">
                    <input type="<?php echo $data['type'] === 'multiple' ? 'checkbox' : 'radio'; ?>" name="si_poll_vote" value="<?php echo $key; ?>">
                    <span class="si-poll__check"><i class="fa-solid fa-check"></i></span>
                    <span class="si-poll__text"><?php echo esc_html($opt['text']); ?></span>
                </label>
                <div class="si-poll__result">
                    <div class="si-poll__bar">
                        <div class="si-poll__fill" style="width:<?php echo $percent; ?>%;"></div>
                    </div>
                    <div class="si-poll__info">
                        <span class="si-poll__opt-text"><?php echo esc_html($opt['text']); ?></span>
                        <span class="si-poll__percent"><?php echo $percent; ?>%</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="si-poll__footer">
            <button type="button" class="si-poll__submit">
                <span>ثبت رای</span>
                <i class="fa-solid fa-paper-plane"></i>
            </button>
            <div class="si-poll__message"></div>
        </div>
    </div>
    <?php
    
    return $content . ob_get_clean();
}

function si_poll_get_ip() {
    $keys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR'];
    foreach ($keys as $key) {
        if (!empty($_SERVER[$key])) {
            return explode(',', $_SERVER[$key])[0];
        }
    }
    return '0.0.0.0';
}

// AJAX Handler
add_action('wp_ajax_si_poll_vote', 'si_poll_ajax');
add_action('wp_ajax_nopriv_si_poll_vote', 'si_poll_ajax');

function si_poll_ajax() {
    check_ajax_referer('si_poll_vote_nonce', 'nonce');
    
    $post_id = absint($_POST['post_id']);
    $votes = isset($_POST['votes']) ? array_map('sanitize_text_field', (array)$_POST['votes']) : [];
    $ip = si_poll_get_ip();
    
    global $wpdb;
    $table = $wpdb->prefix . 'si_poll_votes';
    
    if ($wpdb->get_var($wpdb->prepare("SELECT id FROM $table WHERE post_id = %d AND ip_address = %s", $post_id, $ip))) {
        wp_send_json_error(['message' => 'شما قبلاً رای داده‌اید.']);
    }
    
    if (empty($votes)) {
        wp_send_json_error(['message' => 'لطفاً یک گزینه انتخاب کنید.']);
    }
    
    foreach ($votes as $key) {
        $wpdb->insert($table, ['post_id' => $post_id, 'option_key' => $key, 'ip_address' => $ip]);
    }
    
    $total = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE post_id = %d", $post_id));
    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT option_key, COUNT(*) as votes FROM $table WHERE post_id = %d GROUP BY option_key",
        $post_id
    ));
    
    $stats = [];
    foreach ($results as $r) {
        $stats[$r->option_key] = ['votes' => $r->votes, 'percent' => round(($r->votes / $total) * 100)];
    }
    
    wp_send_json_success(['message' => 'رای شما ثبت شد!', 'total' => $total, 'stats' => $stats]);
}

// Styles & Scripts
add_action('wp_head', 'si_poll_assets');
function si_poll_assets() {
    if (!is_singular('post')) return;
    ?>
    <style>
    .si-poll {
        background: var(--si-surface);
        border-radius: var(--si-radius-2xl);
        margin: var(--si-space-10) 0;
        overflow: hidden;
        box-shadow: var(--si-shadow-lg);
        border: 1px solid var(--si-border-light);
    }
    
    .si-poll__header {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        padding: var(--si-space-6) var(--si-space-8);
        text-align: center;
        color: white;
    }
    
    .si-poll__icon {
        width: 56px;
        height: 56px;
        background: rgba(255,255,255,0.2);
        border-radius: var(--si-radius-xl);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--si-space-4);
        font-size: 1.5rem;
        backdrop-filter: blur(10px);
    }
    
    .si-poll__question {
        margin: 0 0 var(--si-space-3);
        font-size: var(--si-text-xl);
        color: white;
    }
    
    .si-poll__meta {
        font-size: var(--si-text-sm);
        opacity: 0.9;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--si-space-2);
    }
    
    .si-poll__body {
        padding: var(--si-space-6) var(--si-space-8);
        display: grid;
        gap: var(--si-space-4);
    }
    
    .si-poll__option {
        position: relative;
    }
    
    .si-poll__label {
        display: flex;
        align-items: center;
        gap: var(--si-space-4);
        padding: var(--si-space-5);
        background: var(--si-bg-secondary);
        border: 2px solid var(--si-border);
        border-radius: var(--si-radius-xl);
        cursor: pointer;
        transition: all var(--si-transition-fast);
    }
    
    .si-poll__label:hover {
        border-color: var(--opt-color);
        background: var(--si-surface);
    }
    
    .si-poll__label input {
        display: none;
    }
    
    .si-poll__check {
        width: 28px;
        height: 28px;
        border: 2px solid var(--si-border);
        border-radius: var(--si-radius-full);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.75rem;
        transition: all var(--si-transition-fast);
        flex-shrink: 0;
    }
    
    .si-poll__label input:checked ~ .si-poll__check {
        background: var(--opt-color);
        border-color: var(--opt-color);
        box-shadow: 0 2px 8px color-mix(in srgb, var(--opt-color) 40%, transparent);
    }
    
    .si-poll__text {
        flex: 1;
        font-weight: var(--si-font-semibold);
        color: var(--si-text);
    }
    
    .si-poll__result {
        display: none;
    }
    
    /* Voted State */
    .si-poll--voted .si-poll__label {
        display: none;
    }
    
    .si-poll--voted .si-poll__result {
        display: block;
    }
    
    .si-poll--voted .si-poll__submit {
        display: none;
    }
    
    .si-poll__bar {
        height: 14px;
        background: var(--si-bg-tertiary);
        border-radius: var(--si-radius-full);
        overflow: hidden;
        margin-bottom: var(--si-space-2);
    }
    
    .si-poll__fill {
        height: 100%;
        background: linear-gradient(90deg, var(--opt-color), color-mix(in srgb, var(--opt-color) 70%, white));
        border-radius: var(--si-radius-full);
        transition: width 1s var(--si-ease);
    }
    
    .si-poll__info {
        display: flex;
        justify-content: space-between;
        font-size: var(--si-text-sm);
    }
    
    .si-poll__opt-text {
        font-weight: var(--si-font-semibold);
        color: var(--si-text);
    }
    
    .si-poll__percent {
        font-weight: var(--si-font-bold);
        color: var(--opt-color);
    }
    
    .si-poll__footer {
        padding: 0 var(--si-space-8) var(--si-space-6);
        text-align: center;
    }
    
    .si-poll__submit {
        display: inline-flex;
        align-items: center;
        gap: var(--si-space-2);
        padding: var(--si-space-4) var(--si-space-8);
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
        border: none;
        border-radius: var(--si-radius-full);
        font-weight: var(--si-font-bold);
        font-size: var(--si-text-base);
        cursor: pointer;
        transition: all var(--si-transition-fast);
        box-shadow: 0 4px 14px rgba(139, 92, 246, 0.4);
    }
    
    .si-poll__submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(139, 92, 246, 0.5);
    }
    
    .si-poll__submit:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }
    
    .si-poll__message {
        margin-top: var(--si-space-4);
        font-weight: var(--si-font-semibold);
        min-height: 1.5em;
    }
    
    .si-poll__message--success { color: var(--si-success); }
    .si-poll__message--error { color: var(--si-danger); }
    
    @media (max-width: 768px) {
        .si-poll__header, .si-poll__body, .si-poll__footer {
            padding-left: var(--si-space-5);
            padding-right: var(--si-space-5);
        }
    }
    </style>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const poll = document.querySelector('.si-poll:not(.si-poll--voted)');
        if (!poll) return;
        
        const submitBtn = poll.querySelector('.si-poll__submit');
        const message = poll.querySelector('.si-poll__message');
        
        submitBtn.addEventListener('click', function() {
            const votes = [];
            poll.querySelectorAll('input:checked').forEach(i => votes.push(i.value));
            
            if (votes.length === 0) {
                message.textContent = 'لطفاً یک گزینه انتخاب کنید.';
                message.className = 'si-poll__message si-poll__message--error';
                return;
            }
            
            submitBtn.disabled = true;
            
            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams({
                    action: 'si_poll_vote',
                    nonce: poll.dataset.nonce,
                    post_id: poll.dataset.post,
                    'votes[]': votes.join('&votes[]=')
                }).toString().replace(/%5B%5D/g, '[]')
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    message.textContent = res.data.message;
                    message.className = 'si-poll__message si-poll__message--success';
                    poll.querySelector('.si-poll__total').textContent = res.data.total;
                    
                    Object.keys(res.data.stats).forEach(key => {
                        const opt = poll.querySelector(`.si-poll__option[data-key="${key}"]`);
                        if (opt) {
                            opt.querySelector('.si-poll__fill').style.width = res.data.stats[key].percent + '%';
                            opt.querySelector('.si-poll__percent').textContent = res.data.stats[key].percent + '%';
                        }
                    });
                    
                    poll.classList.add('si-poll--voted');
                } else {
                    message.textContent = res.data.message;
                    message.className = 'si-poll__message si-poll__message--error';
                    submitBtn.disabled = false;
                }
            });
        });
    });
    </script>
    <?php
}
