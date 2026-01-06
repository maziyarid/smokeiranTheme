/**
 * =============================================================================
 * SI-Chat-Widget
 * =============================================================================
 * Beautiful Floating Contact Widget
 * Priority: 10 | Location: Frontend Only
 * =============================================================================
 */

if (!defined('ABSPATH')) exit;

add_action('wp_footer', 'si_chat_widget_render');
function si_chat_widget_render() {
    if (is_admin()) return;
    
    // Configure your contact info here
    $phone = '+989123456789';
    $email = 'info@smokeiran.com';
    $whatsapp = '989123456789';
    $telegram = 'smokeiran';
    $instagram = 'smokeiran';
    ?>
    <div class="si-chat" id="si-chat">
        <!-- Main Button -->
        <button type="button" class="si-chat__btn" id="si-chat-btn" aria-label="تماس با ما">
            <span class="si-chat__btn-icon si-chat__btn-icon--open">
                <i class="fa-solid fa-comments"></i>
            </span>
            <span class="si-chat__btn-icon si-chat__btn-icon--close">
                <i class="fa-solid fa-xmark"></i>
            </span>
            <span class="si-chat__badge">5</span>
            <span class="si-chat__pulse"></span>
        </button>
        
        <!-- Widget Panel -->
        <div class="si-chat__panel" id="si-chat-panel">
            <div class="si-chat__header">
                <div class="si-chat__header-icon">
                    <i class="fa-solid fa-headset"></i>
                </div>
                <div class="si-chat__header-text">
                    <h4>نیاز به کمک دارید؟</h4>
                    <p>از طریق راه‌های زیر با ما در تماس باشید</p>
                </div>
            </div>
            
            <div class="si-chat__body">
                <!-- Phone -->
                <a href="tel:<?php echo esc_attr($phone); ?>" class="si-chat__item" style="--item-color:#10b981;">
                    <div class="si-chat__item-icon">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <div class="si-chat__item-content">
                        <span class="si-chat__item-title">تماس تلفنی</span>
                        <span class="si-chat__item-subtitle">پاسخگویی سریع</span>
                    </div>
                    <i class="fa-solid fa-arrow-left si-chat__item-arrow"></i>
                </a>
                
                <!-- WhatsApp -->
                <a href="https://wa.me/<?php echo esc_attr($whatsapp); ?>" target="_blank" class="si-chat__item" style="--item-color:#25d366;">
                    <div class="si-chat__item-icon">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <div class="si-chat__item-content">
                        <span class="si-chat__item-title">واتساپ</span>
                        <span class="si-chat__item-subtitle">آنلاین هستیم</span>
                    </div>
                    <i class="fa-solid fa-arrow-left si-chat__item-arrow"></i>
                </a>
                
                <!-- Telegram -->
                <a href="https://t.me/<?php echo esc_attr($telegram); ?>" target="_blank" class="si-chat__item" style="--item-color:#0088cc;">
                    <div class="si-chat__item-icon">
                        <i class="fa-brands fa-telegram"></i>
                    </div>
                    <div class="si-chat__item-content">
                        <span class="si-chat__item-title">تلگرام</span>
                        <span class="si-chat__item-subtitle">پیام سریع</span>
                    </div>
                    <i class="fa-solid fa-arrow-left si-chat__item-arrow"></i>
                </a>
                
                <!-- Instagram -->
                <a href="https://instagram.com/<?php echo esc_attr($instagram); ?>" target="_blank" class="si-chat__item" style="--item-color:#e4405f;">
                    <div class="si-chat__item-icon">
                        <i class="fa-brands fa-instagram"></i>
                    </div>
                    <div class="si-chat__item-content">
                        <span class="si-chat__item-title">اینستاگرام</span>
                        <span class="si-chat__item-subtitle">دایرکت</span>
                    </div>
                    <i class="fa-solid fa-arrow-left si-chat__item-arrow"></i>
                </a>
                
                <!-- Email -->
                <a href="mailto:<?php echo esc_attr($email); ?>" class="si-chat__item" style="--item-color:#ea4335;">
                    <div class="si-chat__item-icon">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <div class="si-chat__item-content">
                        <span class="si-chat__item-title">ایمیل</span>
                        <span class="si-chat__item-subtitle"><?php echo esc_html($email); ?></span>
                    </div>
                    <i class="fa-solid fa-arrow-left si-chat__item-arrow"></i>
                </a>
            </div>
            
            <div class="si-chat__footer">
                <span><i class="fa-solid fa-clock"></i> پاسخگویی: ۹ صبح تا ۹ شب</span>
            </div>
        </div>
    </div>
    
    <style>
    /* Container */
    .si-chat {
        position: fixed;
        bottom: var(--si-space-6);
        left: var(--si-space-6);
        z-index: var(--si-z-fixed);
        font-family: var(--si-font);
    }
    
    /* Main Button */
    .si-chat__btn {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--si-primary) 0%, var(--si-primary-dark) 100%);
        border: none;
        border-radius: var(--si-radius-full);
        cursor: pointer;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 20px rgba(41, 133, 58, 0.4);
        transition: all var(--si-transition);
        z-index: 10;
    }
    
    .si-chat__btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 30px rgba(41, 133, 58, 0.5);
    }
    
    .si-chat__btn-icon {
        color: white;
        font-size: 1.5rem;
        transition: all var(--si-transition);
        position: absolute;
    }
    
    .si-chat__btn-icon--close {
        opacity: 0;
        transform: rotate(-90deg) scale(0.5);
    }
    
    .si-chat--open .si-chat__btn-icon--open {
        opacity: 0;
        transform: rotate(90deg) scale(0.5);
    }
    
    .si-chat--open .si-chat__btn-icon--close {
        opacity: 1;
        transform: rotate(0) scale(1);
    }
    
    /* Badge */
    .si-chat__badge {
        position: absolute;
        top: -4px;
        right: -4px;
        width: 22px;
        height: 22px;
        background: #ef4444;
        color: white;
        font-size: 11px;
        font-weight: 700;
        border-radius: var(--si-radius-full);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
        animation: si-bounce 2s infinite;
    }
    
    .si-chat--open .si-chat__badge {
        display: none;
    }
    
    /* Pulse */
    .si-chat__pulse {
        position: absolute;
        inset: 0;
        border-radius: var(--si-radius-full);
        background: var(--si-primary);
        animation: si-pulse-ring 2s ease-out infinite;
        z-index: -1;
    }
    
    @keyframes si-pulse-ring {
        0% { transform: scale(1); opacity: 0.4; }
        100% { transform: scale(1.8); opacity: 0; }
    }
    
    .si-chat--open .si-chat__pulse {
        display: none;
    }
    
    /* Panel */
    .si-chat__panel {
        position: absolute;
        bottom: calc(100% + var(--si-space-4));
        left: 0;
        width: 340px;
        background: var(--si-surface);
        border-radius: var(--si-radius-2xl);
        box-shadow: var(--si-shadow-2xl);
        border: 1px solid var(--si-border-light);
        overflow: hidden;
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px) scale(0.95);
        transform-origin: bottom left;
        transition: all var(--si-duration-300) var(--si-ease);
    }
    
    .si-chat--open .si-chat__panel {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }
    
    /* Header */
    .si-chat__header {
        background: linear-gradient(135deg, var(--si-primary) 0%, #059669 100%);
        padding: var(--si-space-5);
        display: flex;
        align-items: center;
        gap: var(--si-space-4);
        color: white;
    }
    
    .si-chat__header-icon {
        width: 48px;
        height: 48px;
        background: rgba(255,255,255,0.2);
        border-radius: var(--si-radius-xl);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    
    .si-chat__header-text h4 {
        margin: 0 0 var(--si-space-1);
        font-size: var(--si-text-base);
        color: white;
    }
    
    .si-chat__header-text p {
        margin: 0;
        font-size: var(--si-text-xs);
        opacity: 0.9;
    }
    
    /* Body */
    .si-chat__body {
        padding: var(--si-space-4);
        display: grid;
        gap: var(--si-space-2);
    }
    
    /* Item */
    .si-chat__item {
        display: flex;
        align-items: center;
        gap: var(--si-space-3);
        padding: var(--si-space-3) var(--si-space-4);
        background: var(--si-bg-secondary);
        border-radius: var(--si-radius-xl);
        border: 1px solid transparent;
        transition: all var(--si-transition-fast);
        text-decoration: none;
    }
    
    .si-chat__item:hover {
        background: color-mix(in srgb, var(--item-color) 10%, transparent);
        border-color: color-mix(in srgb, var(--item-color) 30%, transparent);
        transform: translateX(-4px);
    }
    
    .si-chat__item-icon {
        width: 44px;
        height: 44px;
        background: color-mix(in srgb, var(--item-color) 15%, transparent);
        color: var(--item-color);
        border-radius: var(--si-radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
        transition: all var(--si-transition-fast);
    }
    
    .si-chat__item:hover .si-chat__item-icon {
        background: var(--item-color);
        color: white;
        box-shadow: 0 4px 12px color-mix(in srgb, var(--item-color) 40%, transparent);
    }
    
    .si-chat__item-content {
        flex: 1;
        min-width: 0;
    }
    
    .si-chat__item-title {
        display: block;
        font-weight: var(--si-font-semibold);
        color: var(--si-text);
        font-size: var(--si-text-sm);
    }
    
    .si-chat__item-subtitle {
        display: block;
        font-size: var(--si-text-xs);
        color: var(--si-text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .si-chat__item-arrow {
        color: var(--si-text-muted);
        font-size: 0.75rem;
        opacity: 0;
        transform: translateX(8px);
        transition: all var(--si-transition-fast);
    }
    
    .si-chat__item:hover .si-chat__item-arrow {
        opacity: 1;
        transform: translateX(0);
        color: var(--item-color);
    }
    
    /* Footer */
    .si-chat__footer {
        padding: var(--si-space-3) var(--si-space-4);
        background: var(--si-bg-tertiary);
        text-align: center;
        font-size: var(--si-text-xs);
        color: var(--si-text-muted);
        border-top: 1px solid var(--si-border-light);
    }
    
    .si-chat__footer i {
        margin-left: var(--si-space-1);
        color: var(--si-primary);
    }
    
    /* Animation for items */
    .si-chat--open .si-chat__item {
        animation: si-slideInRight var(--si-duration-300) var(--si-ease) backwards;
    }
    
    .si-chat--open .si-chat__item:nth-child(1) { animation-delay: 0.05s; }
    .si-chat--open .si-chat__item:nth-child(2) { animation-delay: 0.1s; }
    .si-chat--open .si-chat__item:nth-child(3) { animation-delay: 0.15s; }
    .si-chat--open .si-chat__item:nth-child(4) { animation-delay: 0.2s; }
    .si-chat--open .si-chat__item:nth-child(5) { animation-delay: 0.25s; }
    
    /* Responsive */
    @media (max-width: 480px) {
        .si-chat {
            bottom: var(--si-space-4);
            left: var(--si-space-4);
        }
        
        .si-chat__panel {
            width: calc(100vw - 2 * var(--si-space-4));
            max-width: 340px;
        }
        
        .si-chat__btn {
            width: 56px;
            height: 56px;
        }
    }
    </style>
    
    <script>
    (function() {
        const chat = document.getElementById('si-chat');
        const btn = document.getElementById('si-chat-btn');
        
        if (!chat || !btn) return;
        
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            chat.classList.toggle('si-chat--open');
        });
        
        // Close on outside click
        document.addEventListener('click', function(e) {
            if (!chat.contains(e.target)) {
                chat.classList.remove('si-chat--open');
            }
        });
        
        // Close on escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                chat.classList.remove('si-chat--open');
            }
        });
    })();
    </script>
    <?php
}
