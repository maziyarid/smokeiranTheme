<?php
/**
 * The template for displaying product content within loops
 *
 * @package SmokeIranTheme
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}
?>
<li <?php wc_product_class( '', $product ); ?>>
    <div class="product-inner">
        <?php
        /**
         * Hook: woocommerce_before_shop_loop_item.
         */
        do_action( 'woocommerce_before_shop_loop_item' );
        ?>

        <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>" class="woocommerce-LoopProduct-link">
            <?php
            /**
             * Hook: woocommerce_before_shop_loop_item_title.
             */
            do_action( 'woocommerce_before_shop_loop_item_title' );
            ?>

            <h2 class="woocommerce-loop-product__title"><?php echo esc_html( $product->get_name() ); ?></h2>

            <?php
            /**
             * Hook: woocommerce_after_shop_loop_item_title.
             */
            do_action( 'woocommerce_after_shop_loop_item_title' );
            ?>
        </a>

        <?php
        /**
         * Hook: woocommerce_after_shop_loop_item.
         */
        do_action( 'woocommerce_after_shop_loop_item' );
        ?>
    </div>
</li>
