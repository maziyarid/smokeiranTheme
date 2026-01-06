<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package SmokeIranTheme
 */

get_header();
?>

<div class="site-content">
    <main class="content-area">
        
        <section class="error-404 not-found">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'smokeiranTheme' ); ?></h1>
            </header>

            <div class="page-content">
                <p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'smokeiranTheme' ); ?></p>

                <?php get_search_form(); ?>

                <div class="widget-area">
                    <h2><?php esc_html_e( 'Most Used Categories', 'smokeiranTheme' ); ?></h2>
                    <ul>
                        <?php
                        wp_list_categories( array(
                            'orderby'    => 'count',
                            'order'      => 'DESC',
                            'show_count' => 1,
                            'title_li'   => '',
                            'number'     => 10,
                        ) );
                        ?>
                    </ul>
                </div>

                <div class="widget-area">
                    <h2><?php esc_html_e( 'Recent Posts', 'smokeiranTheme' ); ?></h2>
                    <ul>
                        <?php
                        wp_get_archives( array(
                            'type'  => 'postbypost',
                            'limit' => 10,
                        ) );
                        ?>
                    </ul>
                </div>
            </div>
        </section>

    </main>
</div>

<?php
get_footer();
