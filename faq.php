/**
 * =============================================================================
 * SI-FAQ-System
 * =============================================================================
 * FAQ Accordion with Schema.org markup
 * Priority: 10 | Location: Run Everywhere
 * =============================================================================
 */

if (!defined('ABSPATH')) exit;

// Meta Box
add_action('add_meta_boxes', 'si_faq_add_metabox');
function si_faq_add_metabox() {
    add_meta_box(
        'si_faq_metabox',
        '<span style="display:flex;align-items:center;gap:8px;"><i class="fa-solid fa-circle-question" style="color:#29853A;"></i> سوالات متداول (FAQ)</span>',
        'si_faq_metabox_html',
        'post',
        'normal',
        'high'
    );
}

function si_faq_metabox_html($post) {
    wp_nonce_field('si_faq_nonce_action', 'si_faq_nonce');
    $enabled = get_post_meta($post->ID, '_si_faq_enabled', true);
    $faqs = get_post_meta($post->ID, '_si_faqs', true) ?: [];
    ?>
    <style>
    .si-faq-admin { padding: 20px; background: #f8fafc; border-radius: 12px; }
    .si-faq-admin__toggle { display: flex; align-items: center; gap: 12px; padding-bottom: 20px; margin-bottom: 20px; border-bottom: 2px solid #e2e8f0; }
    .si-faq-admin__toggle input[type="checkbox"] { width: 20px; height: 20px; accent-color: #29853A; }
    .si-faq-admin__toggle label { font-weight: 600; font-size: 15px; cursor: pointer; }
    .si-faq-admin__items { display: grid; gap: 16px; }
    .si-faq-admin__item { background: white; padding: 20px; border-radius: 10px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .si-faq-admin__item-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
    .si-faq-admin__item-num { width: 28px; height: 28px; background: linear-gradient(135deg, #29853A, #1e6b2d); color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; }
    .si-faq-admin__item-remove { background: #fee2e2; color: #dc2626; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 13px; transition: all 0.2s; }
    .si-faq-admin__item-remove:hover { background: #dc2626; color: white; }
    .si-faq-admin__field { margin-bottom: 12px; }
    .si-faq-admin__field:last-child { margin-bottom: 0; }
    .si-faq-admin__field label { display: block; font-weight: 600; margin-bottom: 6px; color: #374151; font-size: 13px; }
    .si-faq-admin__field input, .si-faq-admin__field textarea { width: 100%; padding: 10px 14px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 14px; transition: border-color 0.2s; }
    .si-faq-admin__field input:focus, .si-faq-admin__field textarea:focus { outline: none; border-color: #29853A; }
    .si-faq-admin__field textarea { min-height: 80px; resize: vertical; }
    .si-faq-admin__add { display: inline-flex; align-items: center; gap: 8px; margin-top: 16px; padding: 12px 24px; background: linear-gradient(135deg, #29853A, #1e6b2d); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
    .si-faq-admin__add:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(41,133,58,0.3); }
    </style>
    
    <div class="si-faq-admin">
        <div class="si-faq-admin__toggle">
            <input type="checkbox" id="si_faq_enabled" name="si_faq_enabled" value="1" <?php checked($enabled, '1'); ?>>
            <label for="si_faq_enabled">فعال‌سازی بخش سوالات متداول در این نوشته</label>
        </div>
        
        <div id="si-faq-items" class="si-faq-admin__items">
            <?php foreach ($faqs as $i => $faq): ?>
            <div class="si-faq-admin__item" data-index="<?php echo $i; ?>">
                <div class="si-faq-admin__item-header">
                    <span class="si-faq-admin__item-num"><?php echo $i + 1; ?></span>
                    <button type="button" class="si-faq-admin__item-remove" onclick="this.closest('.si-faq-admin__item').remove();siFaqReindex();">
                        <i class="fa-solid fa-trash"></i> حذف
                    </button>
                </div>
                <div class="si-faq-admin__field">
                    <label>سوال:</label>
                    <input type="text" name="si_faqs[<?php echo $i; ?>][question]" value="<?php echo esc_attr($faq['question']); ?>" placeholder="سوال را وارد کنید...">
                </div>
                <div class="si-faq-admin__field">
                    <label>پاسخ:</label>
                    <textarea name="si_faqs[<?php echo $i; ?>][answer]" placeholder="پاسخ را وارد کنید..."><?php echo esc_textarea($faq['answer']); ?></textarea>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <button type="button" class="si-faq-admin__add" onclick="siFaqAddItem()">
            <i class="fa-solid fa-plus"></i> افزودن سوال جدید
        </button>
    </div>
    
    <script>
    let siFaqCounter = <?php echo count($faqs); ?>;
    
    function siFaqAddItem() {
        const container = document.getElementById('si-faq-items');
        const html = `
        <div class="si-faq-admin__item" data-index="${siFaqCounter}">
            <div class="si-faq-admin__item-header">
                <span class="si-faq-admin__item-num">${siFaqCounter + 1}</span>
                <button type="button" class="si-faq-admin__item-remove" onclick="this.closest('.si-faq-admin__item').remove();siFaqReindex();">
                    <i class="fa-solid fa-trash"></i> حذف
                </button>
            </div>
            <div class="si-faq-admin__field">
                <label>سوال:</label>
                <input type="text" name="si_faqs[${siFaqCounter}][question]" placeholder="سوال را وارد کنید...">
            </div>
            <div class="si-faq-admin__field">
                <label>پاسخ:</label>
                <textarea name="si_faqs[${siFaqCounter}][answer]" placeholder="پاسخ را وارد کنید..."></textarea>
            </div>
        </div>`;
        container.insertAdjacentHTML('beforeend', html);
        siFaqCounter++;
    }
    
    function siFaqReindex() {
        document.querySelectorAll('.si-faq-admin__item').forEach((item, index) => {
            item.querySelector('.si-faq-admin__item-num').textContent = index + 1;
        });
    }
    </script>
    <?php
}

// Save
add_action('save_post', 'si_faq_save');
function si_faq_save($post_id) {
    if (!isset($_POST['si_faq_nonce']) || !wp_verify_nonce($_POST['si_faq_nonce'], 'si_faq_nonce_action')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    
    update_post_meta($post_id, '_si_faq_enabled', isset($_POST['si_faq_enabled']) ? '1' : '0');
    
    if (isset($_POST['si_faqs']) && is_array($_POST['si_faqs'])) {
        $faqs = array_values(array_filter(array_map(function($item) {
            return [
                'question' => sanitize_text_field($item['question'] ?? ''),
                'answer' => wp_kses_post($item['answer'] ?? '')
            ];
        }, $_POST['si_faqs']), function($faq) {
            return !empty($faq['question']) && !empty($faq['answer']);
        }));
        update_post_meta($post_id, '_si_faqs', $faqs);
    } else {
        delete_post_meta($post_id, '_si_faqs');
    }
}

// Frontend Display
add_filter('the_content', 'si_faq_display', 95);
function si_faq_display($content) {
    if (!is_singular('post') || !in_the_loop() || !is_main_query()) return $content;
    
    global $post;
    if (get_post_meta($post->ID, '_si_faq_enabled', true) !== '1') return $content;
    
    $faqs = get_post_meta($post->ID, '_si_faqs', true);
    if (empty($faqs)) return $content;
    
    ob_start();
    ?>
    <section class="si-faq" itemscope itemtype="https://schema.org/FAQPage">
        <div class="si-faq__header">
            <div class="si-faq__icon">
                <i class="fa-solid fa-circle-question"></i>
            </div>
            <div class="si-faq__title-wrap">
                <h3 class="si-faq__title">سوالات متداول</h3>
                <p class="si-faq__subtitle">پاسخ سوالات رایج شما</p>
            </div>
        </div>
        
        <div class="si-faq__list">
            <?php foreach ($faqs as $index => $faq): ?>
            <div class="si-faq__item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                <button type="button" class="si-faq__question" aria-expanded="false">
                    <span class="si-faq__num"><?php echo $index + 1; ?></span>
                    <span class="si-faq__q-text" itemprop="name"><?php echo esc_html($faq['question']); ?></span>
                    <span class="si-faq__q-icon">
                        <i class="fa-solid fa-plus"></i>
                    </span>
                </button>
                <div class="si-faq__answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                    <div class="si-faq__a-content" itemprop="text">
                        <?php echo wpautop($faq['answer']); ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
    
    return $content . ob_get_clean();
}

// Styles & Scripts
add_action('wp_head', 'si_faq_assets');
function si_faq_assets() {
    if (!is_singular('post')) return;
    ?>
    <style>
    /* FAQ Section */
    .si-faq {
        margin: var(--si-space-12) 0;
        background: linear-gradient(135deg, var(--si-surface) 0%, var(--si-bg-secondary) 100%);
        border-radius: var(--si-radius-2xl);
        padding: var(--si-space-8);
        border: 1px solid var(--si-border-light);
        box-shadow: var(--si-shadow-md);
    }
    
    /* Header */
    .si-faq__header {
        display: flex;
        align-items: center;
        gap: var(--si-space-4);
        margin-bottom: var(--si-space-8);
        padding-bottom: var(--si-space-6);
        border-bottom: 2px solid var(--si-border-light);
    }
    
    .si-faq__icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, var(--si-primary), var(--si-primary-dark));
        border-radius: var(--si-radius-xl);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: var(--si-shadow-primary-lg);
        flex-shrink: 0;
    }
    
    .si-faq__title-wrap {
        flex: 1;
    }
    
    .si-faq__title {
        margin: 0 0 var(--si-space-1);
        font-size: var(--si-text-xl);
        font-weight: var(--si-font-bold);
        color: var(--si-text);
    }
    
    .si-faq__subtitle {
        margin: 0;
        font-size: var(--si-text-sm);
        color: var(--si-text-tertiary);
    }
    
    /* List */
    .si-faq__list {
        display: grid;
        gap: var(--si-space-3);
    }
    
    /* Item */
    .si-faq__item {
        background: var(--si-surface);
        border-radius: var(--si-radius-xl);
        border: 1px solid var(--si-border);
        overflow: hidden;
        transition: all var(--si-transition);
    }
    
    .si-faq__item:hover {
        border-color: var(--si-primary);
        box-shadow: var(--si-shadow);
    }
    
    .si-faq__item--open {
        border-color: var(--si-primary);
        box-shadow: var(--si-shadow-lg), 0 0 0 4px rgba(41, 133, 58, 0.1);
    }
    
    /* Question Button */
    .si-faq__question {
        width: 100%;
        display: flex;
        align-items: center;
        gap: var(--si-space-4);
        padding: var(--si-space-5) var(--si-space-6);
        background: transparent;
        border: none;
        cursor: pointer;
        text-align: right;
        transition: all var(--si-transition-fast);
    }
    
    .si-faq__question:hover {
        background: var(--si-bg-secondary);
    }
    
    .si-faq__num {
        width: 32px;
        height: 32px;
        background: var(--si-bg-tertiary);
        color: var(--si-text-muted);
        border-radius: var(--si-radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: var(--si-text-sm);
        font-weight: var(--si-font-bold);
        flex-shrink: 0;
        transition: all var(--si-transition-fast);
    }
    
    .si-faq__item--open .si-faq__num {
        background: var(--si-primary);
        color: white;
        box-shadow: var(--si-shadow-primary);
    }
    
    .si-faq__q-text {
        flex: 1;
        font-size: var(--si-text-base);
        font-weight: var(--si-font-semibold);
        color: var(--si-text);
        line-height: 1.5;
    }
    
    .si-faq__q-icon {
        width: 32px;
        height: 32px;
        background: var(--si-bg-tertiary);
        border-radius: var(--si-radius-full);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--si-text-muted);
        font-size: 0.875rem;
        flex-shrink: 0;
        transition: all var(--si-transition);
    }
    
    .si-faq__item--open .si-faq__q-icon {
        background: var(--si-danger-light);
        color: var(--si-danger);
        transform: rotate(45deg);
    }
    
    /* Answer */
    .si-faq__answer {
        display: grid;
        grid-template-rows: 0fr;
        transition: grid-template-rows var(--si-duration-400) var(--si-ease);
    }
    
    .si-faq__item--open .si-faq__answer {
        grid-template-rows: 1fr;
    }
    
    .si-faq__a-content {
        overflow: hidden;
        padding: 0 var(--si-space-6);
        opacity: 0;
        transform: translateY(-10px);
        transition: all var(--si-transition);
    }
    
    .si-faq__item--open .si-faq__a-content {
        padding: var(--si-space-5) var(--si-space-6);
        padding-top: 0;
        padding-right: calc(var(--si-space-6) + 32px + var(--si-space-4));
        opacity: 1;
        transform: translateY(0);
    }
    
    .si-faq__a-content p {
        margin: 0 0 var(--si-space-3);
        color: var(--si-text-secondary);
        line-height: 1.8;
    }
    
    .si-faq__a-content p:last-child {
        margin-bottom: var(--si-space-4);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .si-faq {
            padding: var(--si-space-5);
            margin: var(--si-space-8) 0;
        }
        
        .si-faq__header {
            flex-direction: column;
            text-align: center;
            gap: var(--si-space-3);
        }
        
        .si-faq__icon {
            width: 48px;
            height: 48px;
            font-size: 1.25rem;
        }
        
        .si-faq__question {
            padding: var(--si-space-4);
            gap: var(--si-space-3);
        }
        
        .si-faq__num {
            width: 28px;
            height: 28px;
            font-size: var(--si-text-xs);
        }
        
        .si-faq__q-text {
            font-size: var(--si-text-sm);
        }
        
        .si-faq__item--open .si-faq__a-content {
            padding-right: var(--si-space-4);
        }
    }
    </style>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const faqItems = document.querySelectorAll('.si-faq__item');
        
        faqItems.forEach(item => {
            const question = item.querySelector('.si-faq__question');
            
            question.addEventListener('click', function() {
                const isOpen = item.classList.contains('si-faq__item--open');
                
                // Close all other items (accordion behavior)
                faqItems.forEach(otherItem => {
                    if (otherItem !== item && otherItem.classList.contains('si-faq__item--open')) {
                        otherItem.classList.remove('si-faq__item--open');
                        otherItem.querySelector('.si-faq__question').setAttribute('aria-expanded', 'false');
                    }
                });
                
                // Toggle current item
                item.classList.toggle('si-faq__item--open');
                question.setAttribute('aria-expanded', !isOpen);
            });
        });
    });
    </script>
    <?php
}
