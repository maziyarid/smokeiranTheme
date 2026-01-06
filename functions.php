<?php
/**
 * SmokeIran Theme functions and definitions
 *
 * @package SmokeIranTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Theme setup
 */
function smokeiranTheme_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 1200, 675, true );

    // Register navigation menus
    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Menu', 'smokeiranTheme' ),
        'footer'  => esc_html__( 'Footer Menu', 'smokeiranTheme' ),
    ) );

    // Switch default core markup for search form, comment form, and comments
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // Add theme support for selective refresh for widgets
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Add support for custom logo
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    // Add support for full and wide align images
    add_theme_support( 'align-wide' );

    // Add support for responsive embeds
    add_theme_support( 'responsive-embeds' );

    // Add support for editor styles
    add_theme_support( 'editor-styles' );
    add_editor_style( 'editor-style.css' );

    // WooCommerce support
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'smokeiranTheme_setup' );

/**
 * Set the content width in pixels
 */
function smokeiranTheme_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'smokeiranTheme_content_width', 1200 );
}
add_action( 'after_setup_theme', 'smokeiranTheme_content_width', 0 );

/**
 * Register widget areas
 */
function smokeiranTheme_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'smokeiranTheme' ),
        'id'            => 'sidebar-1',
        'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'smokeiranTheme' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget 1', 'smokeiranTheme' ),
        'id'            => 'footer-1',
        'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'smokeiranTheme' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget 2', 'smokeiranTheme' ),
        'id'            => 'footer-2',
        'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'smokeiranTheme' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );

    register_sidebar( array(
        'name'          => esc_html__( 'Footer Widget 3', 'smokeiranTheme' ),
        'id'            => 'footer-3',
        'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'smokeiranTheme' ),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'smokeiranTheme_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function smokeiranTheme_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style( 'smokeiranTheme-style', get_stylesheet_uri(), array(), '1.0.0' );

    // Enqueue custom JavaScript
    wp_enqueue_script( 'smokeiranTheme-script', get_template_directory_uri() . '/js/script.js', array(), '1.0.0', true );

    // Enqueue comment reply script
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'smokeiranTheme_scripts' );

/**
 * Custom excerpt length
 */
function smokeiranTheme_excerpt_length( $length ) {
    return 30;
}
add_filter( 'excerpt_length', 'smokeiranTheme_excerpt_length' );

/**
 * Custom excerpt more text
 */
function smokeiranTheme_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'smokeiranTheme_excerpt_more' );

/**
 * Add body classes for better styling control
 */
function smokeiranTheme_body_classes( $classes ) {
    // Adds a class if the site has more than one published author
    if ( is_multi_author() ) {
        $classes[] = 'group-blog';
    }

    // Adds a class if the sidebar is active
    if ( is_active_sidebar( 'sidebar-1' ) ) {
        $classes[] = 'has-sidebar';
    }

    return $classes;
}
add_filter( 'body_class', 'smokeiranTheme_body_classes' );

/**
 * Customizer additions
 */
function smokeiranTheme_customize_register( $wp_customize ) {
    // Add color scheme section
    $wp_customize->add_section( 'smokeiranTheme_colors', array(
        'title'    => esc_html__( 'Theme Colors', 'smokeiranTheme' ),
        'priority' => 30,
    ) );

    // Primary color setting
    $wp_customize->add_setting( 'smokeiranTheme_primary_color', array(
        'default'           => '#007bff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'smokeiranTheme_primary_color', array(
        'label'    => esc_html__( 'Primary Color', 'smokeiranTheme' ),
        'section'  => 'smokeiranTheme_colors',
        'settings' => 'smokeiranTheme_primary_color',
    ) ) );

    // Footer text setting
    $wp_customize->add_section( 'smokeiranTheme_footer', array(
        'title'    => esc_html__( 'Footer Settings', 'smokeiranTheme' ),
        'priority' => 40,
    ) );

    $wp_customize->add_setting( 'smokeiranTheme_footer_text', array(
        'default'           => '',
        'sanitize_callback' => 'wp_kses_post',
    ) );

    $wp_customize->add_control( 'smokeiranTheme_footer_text', array(
        'label'    => esc_html__( 'Footer Copyright Text', 'smokeiranTheme' ),
        'section'  => 'smokeiranTheme_footer',
        'type'     => 'textarea',
    ) );
}
add_action( 'customize_register', 'smokeiranTheme_customize_register' );

/**
 * Output custom colors CSS
 */
function smokeiranTheme_custom_colors_css() {
    $primary_color = get_theme_mod( 'smokeiranTheme_primary_color', '#007bff' );
    
    ?>
    <style type="text/css">
        a,
        .nav-menu a:hover,
        .product .price {
            color: <?php echo esc_attr( $primary_color ); ?>;
        }
        
        .button,
        .add_to_cart_button {
            background-color: <?php echo esc_attr( $primary_color ); ?>;
        }
        
        .button:hover,
        .add_to_cart_button:hover {
            background-color: <?php echo esc_attr( $primary_color ); ?>;
            opacity: 0.9;
        }
        
        input:focus,
        textarea:focus {
            border-color: <?php echo esc_attr( $primary_color ); ?>;
        }
    </style>
    <?php
}
add_action( 'wp_head', 'smokeiranTheme_custom_colors_css' );

/**
 * WooCommerce customizations
 */
if ( class_exists( 'WooCommerce' ) ) {
    /**
     * Remove default WooCommerce wrappers
     */
    remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
    remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

    /**
     * Add custom WooCommerce wrappers
     */
    function smokeiranTheme_woocommerce_wrapper_start() {
        echo '<div class="site-content"><div class="content-area">';
    }
    add_action( 'woocommerce_before_main_content', 'smokeiranTheme_woocommerce_wrapper_start', 10 );

    function smokeiranTheme_woocommerce_wrapper_end() {
        echo '</div></div>';
    }
    add_action( 'woocommerce_after_main_content', 'smokeiranTheme_woocommerce_wrapper_end', 10 );

    /**
     * Change number of products per row
     */
    function smokeiranTheme_loop_columns() {
        return 4;
    }
    add_filter( 'loop_shop_columns', 'smokeiranTheme_loop_columns' );

    /**
     * Change number of products per page
     */
    function smokeiranTheme_products_per_page() {
        return 12;
    }
    add_filter( 'loop_shop_per_page', 'smokeiranTheme_products_per_page', 20 );
}

/**
 * Security: Remove WordPress version from head
 */
remove_action( 'wp_head', 'wp_generator' );

/**
 * Optimize WordPress for better performance
 */
function smokeiranTheme_optimize() {
    // Remove emoji scripts
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'smokeiranTheme_optimize' );

/**
 * Add async/defer attributes to scripts
 */
function smokeiranTheme_add_async_defer_attributes( $tag, $handle ) {
    $scripts_to_async = array( 'smokeiranTheme-script' );
    
    foreach ( $scripts_to_async as $script ) {
        if ( $script === $handle ) {
            return str_replace( ' src', ' defer src', $tag );
        }
    }
    
    return $tag;
}
add_filter( 'script_loader_tag', 'smokeiranTheme_add_async_defer_attributes', 10, 2 );

/**
 * Sanitize and validate user inputs
 */
function smokeiranTheme_sanitize_text( $input ) {
    return sanitize_text_field( $input );
}

/**
 * Custom pagination
 */
function smokeiranTheme_pagination() {
    if ( $GLOBALS['wp_query']->max_num_pages <= 1 ) {
        return;
    }

    $args = array(
        'mid_size'           => 2,
        'prev_text'          => esc_html__( '&larr; Previous', 'smokeiranTheme' ),
        'next_text'          => esc_html__( 'Next &rarr;', 'smokeiranTheme' ),
        'screen_reader_text' => esc_html__( 'Posts navigation', 'smokeiranTheme' ),
        'type'               => 'list',
        'class'              => 'pagination',
    );

    the_posts_pagination( $args );
}

/**
 * Breadcrumbs function
 */
function smokeiranTheme_breadcrumbs() {
    $show_on_home = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
    $delimiter    = '&raquo;';
    $home         = esc_html__( 'Home', 'smokeiranTheme' );
    $show_current = 1;
    $before       = '<span class="current">';
    $after        = '</span>';

    global $post;
    $home_link = home_url( '/' );

    if ( is_home() || is_front_page() ) {
        if ( $show_on_home == 1 ) {
            echo '<div class="breadcrumbs"><a href="' . esc_url( $home_link ) . '">' . esc_html( $home ) . '</a></div>';
        }
    } else {
        echo '<div class="breadcrumbs"><a href="' . esc_url( $home_link ) . '">' . esc_html( $home ) . '</a> ' . $delimiter . ' ';

        if ( is_category() ) {
            $this_cat = get_category( get_query_var( 'cat' ), false );
            if ( $this_cat->parent != 0 ) {
                echo wp_kses_post( get_category_parents( $this_cat->parent, true, ' ' . $delimiter . ' ' ) );
            }
            echo $before . single_cat_title( '', false ) . $after;
        } elseif ( is_search() ) {
            echo $before . esc_html__( 'Search results for', 'smokeiranTheme' ) . ' "' . esc_html( get_search_query() ) . '"' . $after;
        } elseif ( is_day() ) {
            echo '<a href="' . esc_url( get_year_link( get_the_time( 'Y' ) ) ) . '">' . get_the_time( 'Y' ) . '</a> ' . $delimiter . ' ';
            echo '<a href="' . esc_url( get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) ) . '">' . get_the_time( 'F' ) . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time( 'd' ) . $after;
        } elseif ( is_month() ) {
            echo '<a href="' . esc_url( get_year_link( get_the_time( 'Y' ) ) ) . '">' . get_the_time( 'Y' ) . '</a> ' . $delimiter . ' ';
            echo $before . get_the_time( 'F' ) . $after;
        } elseif ( is_year() ) {
            echo $before . get_the_time( 'Y' ) . $after;
        } elseif ( is_single() && ! is_attachment() ) {
            if ( get_post_type() != 'post' ) {
                $post_type = get_post_type_object( get_post_type() );
                $slug      = $post_type->rewrite;
                echo '<a href="' . esc_url( $home_link ) . '/' . $slug['slug'] . '/">' . esc_html( $post_type->labels->singular_name ) . '</a>';
                if ( $show_current == 1 ) {
                    echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
                }
            } else {
                $cat  = get_the_category();
                if ( ! empty( $cat ) ) {
                    $cat  = $cat[0];
                    $cats = get_category_parents( $cat, true, ' ' . $delimiter . ' ' );
                    if ( $show_current == 0 ) {
                        $cats = preg_replace( "#^(.+)\s$delimiter\s$#", "$1", $cats );
                    }
                    echo wp_kses_post( $cats );
                }
                if ( $show_current == 1 ) {
                    echo $before . get_the_title() . $after;
                }
            }
        } elseif ( ! is_single() && ! is_page() && get_post_type() != 'post' && ! is_404() ) {
            $post_type = get_post_type_object( get_post_type() );
            echo $before . esc_html( $post_type->labels->singular_name ) . $after;
        } elseif ( is_attachment() ) {
            $parent = get_post( $post->post_parent );
            echo '<a href="' . esc_url( get_permalink( $parent ) ) . '">' . esc_html( $parent->post_title ) . '</a>';
            if ( $show_current == 1 ) {
                echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
            }
        } elseif ( is_page() && ! $post->post_parent ) {
            if ( $show_current == 1 ) {
                echo $before . get_the_title() . $after;
            }
        } elseif ( is_page() && $post->post_parent ) {
            $parent_id   = $post->post_parent;
            $breadcrumbs = array();
            while ( $parent_id ) {
                $page          = get_post( $parent_id );
                $breadcrumbs[] = '<a href="' . esc_url( get_permalink( $page->ID ) ) . '">' . get_the_title( $page->ID ) . '</a>';
                $parent_id     = $page->post_parent;
            }
            $breadcrumbs = array_reverse( $breadcrumbs );
            for ( $i = 0; $i < count( $breadcrumbs ); $i++ ) {
                echo $breadcrumbs[ $i ];
                if ( $i != count( $breadcrumbs ) - 1 ) {
                    echo ' ' . $delimiter . ' ';
                }
            }
            if ( $show_current == 1 ) {
                echo ' ' . $delimiter . ' ' . $before . get_the_title() . $after;
            }
        } elseif ( is_tag() ) {
            echo $before . single_tag_title( '', false ) . $after;
        } elseif ( is_author() ) {
            global $author;
            $userdata = get_userdata( $author );
            echo $before . esc_html__( 'Articles posted by ', 'smokeiranTheme' ) . esc_html( $userdata->display_name ) . $after;
        } elseif ( is_404() ) {
            echo $before . esc_html__( 'Error 404', 'smokeiranTheme' ) . $after;
        }

        if ( get_query_var( 'paged' ) ) {
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
                echo ' (';
            }
            echo esc_html__( 'Page', 'smokeiranTheme' ) . ' ' . get_query_var( 'paged' );
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
                echo ')';
            }
        }

        echo '</div>';
    }
}
