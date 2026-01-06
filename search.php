<?php
/**
 * The template for displaying search results
 *
 * @package SmokeIranTheme
 */

get_header();
?>

<div class="site-content">
    <main class="content-area">
        
        <?php if ( have_posts() ) : ?>

            <header class="page-header">
                <h1 class="page-title">
                    <?php
                    /* translators: %s: search query */
                    printf( esc_html__( 'Search Results for: %s', 'smokeiranTheme' ), '<span>' . esc_html( get_search_query() ) . '</span>' );
                    ?>
                </h1>
            </header>

            <div class="posts-grid">
                <?php
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/content', 'search' );
                endwhile;
                ?>
            </div>

            <?php
            smokeiranTheme_pagination();

        else :
            ?>
            <div class="no-results">
                <h1><?php esc_html_e( 'Nothing Found', 'smokeiranTheme' ); ?></h1>
                <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'smokeiranTheme' ); ?></p>
                <?php get_search_form(); ?>
            </div>
            <?php
        endif;
        ?>

    </main>

    <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
        <aside class="sidebar">
            <?php dynamic_sidebar( 'sidebar-1' ); ?>
        </aside>
    <?php endif; ?>
</div>

<?php
get_footer();
