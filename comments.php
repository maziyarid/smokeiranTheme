<?php
/**
 * Comments template
 *
 * @package SmokeIranTheme
 */

if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            if ( '1' === $comment_count ) {
                printf(
                    /* translators: 1: title */
                    esc_html__( 'One thought on &ldquo;%1$s&rdquo;', 'smokeiranTheme' ),
                    '<span>' . get_the_title() . '</span>'
                );
            } else {
                printf(
                    /* translators: 1: comment count number, 2: title */
                    esc_html( _nx( '%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $comment_count, 'comments title', 'smokeiranTheme' ) ),
                    number_format_i18n( $comment_count ),
                    '<span>' . get_the_title() . '</span>'
                );
            }
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments( array(
                'style'      => 'ol',
                'short_ping' => true,
            ) );
            ?>
        </ol>

        <?php
        the_comments_navigation();

        if ( ! comments_open() ) :
            ?>
            <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'smokeiranTheme' ); ?></p>
            <?php
        endif;

    endif;

    comment_form();
    ?>

</div>
