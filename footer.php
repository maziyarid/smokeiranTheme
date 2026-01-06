    </div><!-- #content -->

    <footer class="site-footer">
        <div class="footer-inner">
            <?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) ) : ?>
                <div class="footer-widgets">
                    <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-1' ); ?>
                    <?php endif; ?>
                    
                    <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-2' ); ?>
                    <?php endif; ?>
                    
                    <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-3' ); ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="site-info">
                <?php
                $footer_text = get_theme_mod( 'smokeiranTheme_footer_text' );
                if ( $footer_text ) {
                    echo wp_kses_post( $footer_text );
                } else {
                    ?>
                    <p>
                        &copy; <?php echo esc_html( date( 'Y' ) ); ?> 
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
                            <?php bloginfo( 'name' ); ?>
                        </a>
                        <?php esc_html_e( ' - All rights reserved.', 'smokeiranTheme' ); ?>
                    </p>
                    <p>
                        <?php
                        /* translators: %s: WordPress link */
                        printf(
                            esc_html__( 'Powered by %s', 'smokeiranTheme' ),
                            '<a href="' . esc_url( __( 'https://wordpress.org/', 'smokeiranTheme' ) ) . '">WordPress</a>'
                        );
                        ?>
                    </p>
                    <?php
                }
                ?>
            </div>
        </div>
    </footer>
</div><!-- .site-container -->

<?php wp_footer(); ?>
</body>
</html>
