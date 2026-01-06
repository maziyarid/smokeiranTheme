<?php
/**
 * Search form template
 *
 * @package SmokeIranTheme
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label>
        <span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'smokeiranTheme' ); ?></span>
        <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'smokeiranTheme' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" />
    </label>
    <button type="submit" class="search-submit">
        <span class="screen-reader-text"><?php esc_html_e( 'Search', 'smokeiranTheme' ); ?></span>
        <span aria-hidden="true">&#128269;</span>
    </button>
</form>
