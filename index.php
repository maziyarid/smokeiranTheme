<?php
/**
 * The main template file
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
                if ( is_home() && ! is_front_page() ) :
                    ?>
                    <h1 class="page-title"><?php single_post_title(); ?></h1>
                    <?php
                elseif ( is_archive() ) :
                    the_archive_title( '<h1 class="page-title">', '</h1>' );
                    the_archive_description( '<div class="archive-description">', '</div>' );
                endif;
                ?>
            </header>

            <div class="posts-grid">
                <?php
                while ( have_posts() ) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'post-item' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail( 'medium' ); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="post-content">
                            <header class="entry-header">
                                <?php
                                the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' );
                                ?>
                                
                                <div class="entry-meta">
                                    <span class="posted-on">
                                        <?php
                                        echo esc_html( get_the_date() );
                                        ?>
                                    </span>
                                    <span class="byline">
                                        <?php
                                        esc_html_e( 'by', 'smokeiranTheme' );
                                        echo ' <a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>';
                                        ?>
                                    </span>
                                </div>
                            </header>

                            <div class="entry-summary">
                                <?php the_excerpt(); ?>
                            </div>

                            <footer class="entry-footer">
                                <a href="<?php the_permalink(); ?>" class="read-more">
                                    <?php esc_html_e( 'Read More', 'smokeiranTheme' ); ?> &rarr;
                                </a>
                            </footer>
                        </div>
                    </article>
                    <?php
                endwhile;
                ?>
            </div>

            <?php
            smokeiranTheme_pagination();

        else :
            ?>
            <div class="no-results">
                <h1><?php esc_html_e( 'Nothing Found', 'smokeiranTheme' ); ?></h1>
                <p><?php esc_html_e( 'Sorry, but nothing matched your search criteria. Please try again with different keywords.', 'smokeiranTheme' ); ?></p>
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
