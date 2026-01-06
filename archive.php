<?php
/**
 * The template for displaying archive pages
 *
 * @package SmokeIranTheme
 */

get_header();
?>

<div class="site-content">
    <main class="content-area">
        
        <?php if ( have_posts() ) : ?>

            <header class="page-header">
                <?php
                the_archive_title( '<h1 class="page-title">', '</h1>' );
                the_archive_description( '<div class="archive-description">', '</div>' );
                ?>
            </header>

            <div class="posts-grid">
                <?php
                while ( have_posts() ) :
                    the_post();
                    get_template_part( 'template-parts/content', get_post_format() );
                endwhile;
                ?>
            </div>

            <?php
            smokeiranTheme_pagination();

        else :
            get_template_part( 'template-parts/content', 'none' );
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
