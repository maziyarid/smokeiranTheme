<?php
/**
 * Template part for displaying search results
 *
 * @package SmokeIranTheme
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-item search-result' ); ?>>
    <header class="entry-header">
        <?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
        
        <div class="entry-meta">
            <span class="posted-on">
                <?php echo esc_html( get_the_date() ); ?>
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
</article>
