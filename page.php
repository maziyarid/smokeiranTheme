<?php
/**
 * Template for displaying pages
 *
 * @package SmokeIranTheme
 */

get_header();
?>

<div class="site-content">
    <main class="content-area">
        
        <?php
        while ( have_posts() ) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                <header class="entry-header">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail( 'large' ); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages( array(
                        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'smokeiranTheme' ),
                        'after'  => '</div>',
                    ) );
                    ?>
                </div>

            </article>

            <?php
            // Comments
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;

        endwhile;
        ?>

    </main>
</div>

<?php
get_footer();
