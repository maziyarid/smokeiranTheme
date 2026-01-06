<?php
/**
 * Template for displaying single posts
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
                    
                    <div class="entry-meta">
                        <span class="posted-on">
                            <?php
                            /* translators: %s: post date */
                            printf(
                                esc_html__( 'Posted on %s', 'smokeiranTheme' ),
                                '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>'
                            );
                            ?>
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
                        <?php
                        $categories = get_the_category();
                        if ( ! empty( $categories ) ) {
                            ?>
                            <span class="cat-links">
                                <?php esc_html_e( 'in', 'smokeiranTheme' ); ?> 
                                <?php
                                foreach ( $categories as $category ) {
                                    echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a> ';
                                }
                                ?>
                            </span>
                            <?php
                        }
                        ?>
                    </div>
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

                <footer class="entry-footer">
                    <?php
                    $tags = get_the_tags();
                    if ( $tags ) {
                        echo '<div class="tags-links">';
                        echo '<span>' . esc_html__( 'Tags:', 'smokeiranTheme' ) . ' </span>';
                        foreach ( $tags as $tag ) {
                            echo '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">' . esc_html( $tag->name ) . '</a> ';
                        }
                        echo '</div>';
                    }
                    ?>
                </footer>

            </article>

            <?php
            // Post navigation
            the_post_navigation( array(
                'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'smokeiranTheme' ) . '</span> <span class="nav-title">%title</span>',
                'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'smokeiranTheme' ) . '</span> <span class="nav-title">%title</span>',
            ) );

            // Comments
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;

        endwhile;
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
