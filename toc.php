/**
 * =============================================================================
 * SI-Table-Of-Contents
 * =============================================================================
 * Auto-generated Table of Contents with smooth scroll & active tracking
 * Priority: 10 | Location: Frontend Only
 * =============================================================================
 */

if (!defined('ABSPATH')) exit;

add_filter('the_content', 'si_toc_generate', 15);
function si_toc_generate($content) {
    if (!is_singular('post') || !in_the_loop() || !is_main_query()) {
        return $content;
    }
    
    // Find headings
    preg_match_all('/<h([2-3])[^>]*>(.*?)<\/h[2-3]>/si', $content, $matches, PREG_SET_ORDER);
    
    if (count($matches) < 3) {
        return $content;
    }
    
    // Add IDs to headings
    $counter = 0;
    $content = preg_replace_callback('/<(h[2-3])([^>]*)>(.*?)<\/\1>/si', function($m) use (&$counter) {
        $counter++;
        $id = 'section-' . $counter;
        $existing_id = '';
        if (preg_match('/id=["\']([^"\']+)["\']/', $m[2], $id_match)) {
            $existing_id = $id_match[1];
        }
        $final_id = $existing_id ?: $id;
        $attrs = preg_replace('/id=["\'][^"\']+["\']/', '', $m[2]);
        return '<' . $m[1] . $attrs . ' id="' . $final_id . '">' . $m[3] . '</' . $m[1] . '>';
    }, $content);
    
    // Rebuild matches with IDs
    preg_match_all('/<h([2-3])[^>]*id=["\']([^"\']+)["\'][^>]*>(.*?)<\/h[2-3]>/si', $content, $matches, PREG_SET_ORDER);
    
    // Build TOC HTML
    $toc = '<nav class="si-toc" aria-label="فهرست مطالب">
        <div class="si-toc__header">
            <div class="si-toc__icon">
                <i class="fa-solid fa-list-ul"></i>
            </div>
            <h4 class="si-toc__title">فهرست مطالب</h4>
            <span class="si-toc__count">' . count($matches) . ' بخش</span>
            <button type="button" class="si-toc__toggle" aria-expanded="true" aria-label="باز/بسته کردن">
                <i class="fa-solid fa-chevron-up"></i>
            </button>
        </div>
        <div class="si-toc__body">
            <div class="si-toc__progress">
                <div class="si-toc__progress-bar"></div>
            </div>
            <ol class="si-toc__list">';
    
    foreach ($matches as $index => $match) {
        $level = $match[1];
        $id = $match[2];
        $title = strip_tags($match[3]);
        $num = $index + 1;
        
        $toc .= '<li class="si-toc__item si-toc__item--h' . $level . '">
            <a href="#' . esc_attr($id) . '" class="si-toc__link" data-target="' . esc_attr($id) . '">
                <span class="si-toc__num">' . $num . '</span>
                <span class="si-toc__text">' . esc_html($title) . '</span>
                <i class="fa-solid fa-chevron-left si-toc__arrow"></i>
            </a>
        </li>';
    }
    
    $toc .= '</ol>
        </div>
    </nav>';
    
    // Insert after first paragraph
    $pos = strpos($content, '</p>');
    if ($pos !== false) {
        $content = substr_replace($content, '</p>' . $toc, $pos, 4);
    } else {
        $content = $toc . $content;
    }
    
    return $content;
}

add_action('wp_head', 'si_toc_assets');
function si_toc_assets() {
    if (!is_singular('post')) return;
    ?>
    <style>
    /* TOC Container */
    .si-toc {
        background: var(--si-surface);
        border-radius: var(--si-radius-2xl);
        margin: var(--si-space-8) 0;
        overflow: hidden;
        box-shadow: var(--si-shadow-md);
        border: 1px solid var(--si-border-light);
        position: relative;
    }
    
    .si-toc::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, var(--si-primary), var(--si-primary-light));
        border-radius: 0 var(--si-radius-2xl) var(--si-radius-2xl) 0;
    }
    
    /* Header */
    .si-toc__header {
        display: flex;
        align-items: center;
        gap: var(--si-space-3);
        padding: var(--si-space-5) var(--si-space-6);
        background: linear-gradient(135deg, var(--si-bg-secondary) 0%, var(--si-surface) 100%);
        border-bottom: 1px solid var(--si-border-light);
        cursor: pointer;
        user-select: none;
        transition: background var(--si-transition-fast);
    }
    
    .si-toc__header:hover {
        background: var(--si-bg-tertiary);
    }
    
    .si-toc__icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--si-primary), var(--si-primary-dark));
        border-radius: var(--si-radius-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
        box-shadow: var(--si-shadow-primary);
        flex-shrink: 0;
    }
    
    .si-toc__title {
        flex: 1;
        margin: 0;
        font-size: var(--si-text-base);
        font-weight: var(--si-font-bold);
        color: var(--si-text);
    }
    
    .si-toc__count {
        font-size: var(--si-text-xs);
        color: var(--si-text-muted);
        background: var(--si-bg-tertiary);
        padding: var(--si-space-1) var(--si-space-3);
        border-radius: var(--si-radius-full);
    }
    
    .si-toc__toggle {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--si-radius-full);
        background: var(--si-surface);
        color: var(--si-text-tertiary);
        border: 1px solid var(--si-border);
        transition: all var(--si-transition-fast);
    }
    
    .si-toc__toggle:hover {
        background: var(--si-bg-tertiary);
        color: var(--si-text);
    }
    
    .si-toc__toggle i {
        transition: transform var(--si-transition);
    }
    
    .si-toc--collapsed .si-toc__toggle i {
        transform: rotate(180deg);
    }
    
    /* Body */
    .si-toc__body {
        position: relative;
        padding: var(--si-space-4) var(--si-space-6);
        max-height: 500px;
        overflow: hidden;
        transition: all var(--si-duration-400) var(--si-ease);
    }
    
    .si-toc--collapsed .si-toc__body {
        max-height: 0;
        padding-top: 0;
        padding-bottom: 0;
        opacity: 0;
    }
    
    /* Progress Bar */
    .si-toc__progress {
        position: absolute;
        top: 0;
        right: var(--si-space-6);
        width: 3px;
        height: calc(100% - var(--si-space-8));
        background: var(--si-border-light);
        border-radius: var(--si-radius-full);
    }
    
    .si-toc__progress-bar {
        width: 100%;
        height: 0%;
        background: linear-gradient(180deg, var(--si-primary), var(--si-accent));
        border-radius: var(--si-radius-full);
        transition: height var(--si-transition);
    }
    
    /* List */
    .si-toc__list {
        list-style: none;
        margin: 0;
        padding: 0 var(--si-space-4) 0 0;
        counter-reset: toc-counter;
    }
    
    .si-toc__item {
        margin-bottom: var(--si-space-1);
    }
    
    .si-toc__item--h3 {
        padding-right: var(--si-space-6);
    }
    
    .si-toc__item--h3 .si-toc__link {
        font-size: var(--si-text-sm);
    }
    
    .si-toc__item--h3 .si-toc__num {
        width: 24px;
        height: 24px;
        font-size: var(--si-text-xs);
    }
    
    /* Link */
    .si-toc__link {
        display: flex;
        align-items: center;
        gap: var(--si-space-3);
        padding: var(--si-space-3) var(--si-space-4);
        border-radius: var(--si-radius-lg);
        color: var(--si-text-secondary);
        text-decoration: none;
        transition: all var(--si-transition-fast);
        position: relative;
    }
    
    .si-toc__link::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: var(--si-radius-lg);
        background: var(--si-primary);
        opacity: 0;
        transform: scale(0.95);
        transition: all var(--si-transition-fast);
    }
    
    .si-toc__link:hover {
        color: var(--si-primary);
    }
    
    .si-toc__link:hover::before {
        opacity: 0.05;
        transform: scale(1);
    }
    
    .si-toc__link:hover .si-toc__arrow {
        opacity: 1;
        transform: translateX(-4px);
    }
    
    /* Active State */
    .si-toc__link--active {
        color: var(--si-primary);
        font-weight: var(--si-font-semibold);
    }
    
    .si-toc__link--active::before {
        opacity: 0.1;
        transform: scale(1);
    }
    
    .si-toc__link--active .si-toc__num {
        background: var(--si-primary);
        color: white;
        box-shadow: var(--si-shadow-primary);
    }
    
    /* Number */
    .si-toc__num {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--si-bg-tertiary);
        color: var(--si-text-muted);
        border-radius: var(--si-radius);
        font-size: var(--si-text-xs);
        font-weight: var(--si-font-bold);
        flex-shrink: 0;
        position: relative;
        z-index: 1;
        transition: all var(--si-transition-fast);
    }
    
    /* Text */
    .si-toc__text {
        flex: 1;
        position: relative;
        z-index: 1;
        line-height: 1.4;
    }
    
    /* Arrow */
    .si-toc__arrow {
        font-size: 0.7em;
        color: var(--si-text-muted);
        opacity: 0;
        transform: translateX(0);
        transition: all var(--si-transition-fast);
        position: relative;
        z-index: 1;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .si-toc {
            margin: var(--si-space-6) 0;
        }
        
        .si-toc__header {
            padding: var(--si-space-4);
        }
        
        .si-toc__body {
            padding: var(--si-space-3) var(--si-space-4);
            max-height: 400px;
        }
        
        .si-toc__icon {
            width: 36px;
            height: 36px;
        }
        
        .si-toc__count {
            display: none;
        }
    }
    </style>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const toc = document.querySelector('.si-toc');
        if (!toc) return;
        
        const header = toc.querySelector('.si-toc__header');
        const toggle = toc.querySelector('.si-toc__toggle');
        const links = toc.querySelectorAll('.si-toc__link');
        const progressBar = toc.querySelector('.si-toc__progress-bar');
        
        // Toggle
        header.addEventListener('click', function(e) {
            if (e.target === toggle || toggle.contains(e.target)) return;
            toc.classList.toggle('si-toc--collapsed');
        });
        
        toggle.addEventListener('click', function() {
            toc.classList.toggle('si-toc--collapsed');
        });
        
        // Smooth scroll
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.dataset.target;
                const target = document.getElementById(targetId);
                
                if (target) {
                    const headerOffset = 100;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                    
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Active tracking
        const sections = Array.from(links).map(link => ({
            link: link,
            target: document.getElementById(link.dataset.target)
        })).filter(s => s.target);
        
        if (sections.length === 0) return;
        
        let ticking = false;
        
        function updateActiveLink() {
            const scrollPos = window.scrollY + 150;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const scrollPercent = (window.scrollY / docHeight) * 100;
            
            // Update progress bar
            progressBar.style.height = Math.min(scrollPercent, 100) + '%';
            
            // Find active section
            let activeSection = sections[0];
            
            sections.forEach(section => {
                if (section.target.offsetTop <= scrollPos) {
                    activeSection = section;
                }
            });
            
            // Update active class
            links.forEach(link => link.classList.remove('si-toc__link--active'));
            activeSection.link.classList.add('si-toc__link--active');
            
            ticking = false;
        }
        
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(updateActiveLink);
                ticking = true;
            }
        });
        
        // Initial update
        updateActiveLink();
    });
    </script>
    <?php
}
