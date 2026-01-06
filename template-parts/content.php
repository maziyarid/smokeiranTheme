/**
 * Template part for displaying post content
 *
 * @package SmokeIranTheme
 */
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
                    <?php echo esc_html( get_the_date() ); ?>
                </span>
                <span class="byline">
                    <?php
                    /* translators: %s: post author */
                    printf(
                        esc_html__( 'by %s', 'smokeiranTheme' ),
                        '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>'
                    );
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
