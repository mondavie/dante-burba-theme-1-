<?php
/**
 * WooCommerce Content Product (Product Card)
 * Renders a single product card in the shop grid.
 *
 * @package dante-burba
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) return;

$product_id  = $product->get_id();
$product_url = get_permalink( $product_id );
$categories  = wc_get_product_category_list( $product_id, ', ', '', '' );
// Get first category name for badge
$cat_terms   = get_the_terms( $product_id, 'product_cat' );
$badge_label = ( $cat_terms && ! is_wp_error( $cat_terms ) ) ? $cat_terms[0]->name : __( 'Part', 'dante-burba' );

?>
<article class="db-product-card <?php echo esc_attr( implode( ' ', wc_get_product_class( '', $product ) ) ); ?>"
         data-id="p<?php echo esc_attr( $product_id ); ?>"
         data-title="<?php echo esc_attr( $product->get_name() ); ?>"
         data-price="<?php echo esc_attr( $product->get_price() ); ?>"
         data-image="<?php echo esc_url( wp_get_attachment_url( $product->get_image_id() ) ); ?>"
         data-description="<?php echo esc_attr( wp_strip_all_tags( $product->get_short_description() ) ); ?>"
         data-url="<?php echo esc_url( $product_url ); ?>"
         onclick="dbOpenProductModal(this)">

    <!-- Product Image -->
    <div class="db-product-card__img-wrap">
        <?php if ( $product->get_image_id() ) : ?>
            <?php echo wp_get_attachment_image( $product->get_image_id(), 'db-product', false, [ 'alt' => esc_attr( $product->get_name() ), 'loading' => 'lazy' ] ); ?>
        <?php else : ?>
            <img src="<?php echo esc_url( wc_placeholder_img_src( 'db-product' ) ); ?>"
                 alt="<?php esc_attr_e( 'Product placeholder', 'dante-burba' ); ?>" loading="lazy">
        <?php endif; ?>

        <!-- Diagonal badge -->
        <div class="db-product-card__badge-wrap" aria-hidden="true">
            <span><?php echo esc_html( $badge_label ); ?></span>
        </div>

        <?php if ( $product->is_on_sale() ) : ?>
        <span class="db-sale-badge" style="position:absolute;bottom:8px;left:8px;background:var(--db-orange);color:white;font-family:var(--db-font-mono);font-size:9px;padding:3px 8px;text-transform:uppercase;letter-spacing:.1em;">
            <?php esc_html_e( 'Sale', 'dante-burba' ); ?>
        </span>
        <?php endif; ?>
    </div>

    <!-- Card Body -->
    <div class="db-product-card__body">
        <h2 class="db-product-card__title"><?php echo esc_html( $product->get_name() ); ?></h2>
        <p class="db-product-card__desc">
            <?php
            $short_desc = $product->get_short_description();
            echo wp_kses_post( wp_trim_words( $short_desc ? $short_desc : $product->get_description(), 14 ) );
            ?>
        </p>

        <!-- Footer: price + add to cart -->
        <div class="db-product-card__footer">
            <div>
                <span class="db-product-card__price-label"><?php esc_html_e( 'Price', 'dante-burba' ); ?></span>
                <div class="db-product-card__price">
                    <?php echo wp_kses_post( $product->get_price_html() ); ?>
                </div>
            </div>

            <?php if ( $product->is_in_stock() && $product->is_purchasable() ) : ?>
            <button class="db-product-card__add"
                    onclick="event.stopPropagation(); dbAddToCart(<?php echo esc_attr( $product_id ); ?>, this)"
                    aria-label="<?php echo esc_attr( sprintf( __( 'Add %s to cart', 'dante-burba' ), $product->get_name() ) ); ?>"
                    data-product-id="<?php echo esc_attr( $product_id ); ?>">
                <?php echo db_icon( 'plus' ); ?>
            </button>
            <?php else : ?>
            <span style="font-family:var(--db-font-mono);font-size:9px;color:var(--db-muted);text-transform:uppercase;letter-spacing:.1em;">
                <?php esc_html_e( 'Out of Stock', 'dante-burba' ); ?>
            </span>
            <?php endif; ?>
        </div>
    </div>

</article>
