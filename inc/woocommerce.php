<?php
/**
 * WooCommerce Integration
 *
 * @package dante-burba
 */

defined( 'ABSPATH' ) || exit;

// ── Remove default WooCommerce wrappers (we supply our own) ──
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content',  'woocommerce_output_content_wrapper_end', 10 );

add_action( 'woocommerce_before_main_content', 'db_woo_wrapper_start', 10 );
function db_woo_wrapper_start(): void {
    echo '<div class="db-shop-container"><div class="db-shop-inner max-w-7xl mx-auto px-6 lg:px-14 pt-32 pb-20">';
}

add_action( 'woocommerce_after_main_content', 'db_woo_wrapper_end', 10 );
function db_woo_wrapper_end(): void {
    echo '</div></div>';
}

// ── Remove sidebar from WooCommerce ──
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// ── Change product columns ──
add_filter( 'loop_shop_columns', fn() => 4 );
add_filter( 'loop_shop_per_page', fn() => 12 );

// ── Remove breadcrumbs on shop (optional) ──
// remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

// ── Custom breadcrumb separator ──
add_filter( 'woocommerce_breadcrumb_defaults', function ( $defaults ) {
    $defaults['delimiter'] = ' <span style="color:var(--db-orange);margin:0 6px;">/</span> ';
    return $defaults;
} );

// ── Cart fragments for AJAX cart count ──
add_filter( 'woocommerce_add_to_cart_fragments', 'db_cart_count_fragment' );
function db_cart_count_fragment( array $fragments ): array {
    ob_start();
    $count = WC()->cart->get_cart_contents_count();
    echo '<span class="db-cart-count" id="db-cart-count">' . esc_html( $count ) . '</span>';
    $fragments['#db-cart-count'] = ob_get_clean();
    return $fragments;
}

// ── Mini cart fragment (for the drawer) ──
add_filter( 'woocommerce_add_to_cart_fragments', 'db_mini_cart_fragment' );
function db_mini_cart_fragment( array $fragments ): array {
    ob_start();
    woocommerce_mini_cart();
    $fragments['div.db-cart-items'] = '<div class="db-cart-items">' . ob_get_clean() . '</div>';
    return $fragments;
}

// ── Add to cart AJAX handler for custom button ──
add_action( 'wp_ajax_db_add_to_cart',        'db_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_db_add_to_cart', 'db_ajax_add_to_cart' );
function db_ajax_add_to_cart(): void {
    check_ajax_referer( 'db_nonce', 'nonce' );

    $product_id = absint( $_POST['product_id'] ?? 0 );
    $quantity   = absint( $_POST['quantity']   ?? 1 );

    if ( ! $product_id ) {
        wp_send_json_error( [ 'message' => __( 'Invalid product.', 'dante-burba' ) ] );
    }

    $added = WC()->cart->add_to_cart( $product_id, $quantity );

    if ( $added ) {
        WC_AJAX::get_refreshed_fragments();
    } else {
        wp_send_json_error( [ 'message' => __( 'Could not add to cart.', 'dante-burba' ) ] );
    }
}

// ── Ensure cart widget HTML is output ──
add_action( 'wp_footer', 'db_render_cart_drawer' );
function db_render_cart_drawer(): void {
    if ( ! db_is_woocommerce_active() ) return;
    if ( ! function_exists( 'wc_get_checkout_url' ) ) return;
    if ( ! WC()->cart ) return;
    ?>
    <!-- DB Cart Overlay -->
    <div class="db-cart-overlay" id="db-cart-overlay" onclick="dbToggleCart()"></div>

    <!-- DB Cart Drawer -->
    <div class="db-cart-drawer" id="db-cart-drawer">
        <div class="db-cart-header">
            <h3>
                <?php echo db_icon( 'shopping-cart' ); ?>
                <?php esc_html_e( 'Your Cart', 'dante-burba' ); ?>
            </h3>
            <button class="db-cart-close" onclick="dbToggleCart()" aria-label="<?php esc_attr_e( 'Close cart', 'dante-burba' ); ?>">
                <?php echo db_icon( 'x' ); ?>
            </button>
        </div>

        <div class="db-cart-items" id="db-cart-items">
            <?php woocommerce_mini_cart(); ?>
        </div>

        <div class="db-cart-footer">
            <div class="db-cart-subtotal">
                <span><?php esc_html_e( 'Subtotal', 'dante-burba' ); ?></span>
                <span id="db-cart-total">
                    <?php
                    echo wp_kses_post( WC()->cart->get_cart_subtotal() );
                    ?>
                </span>
            </div>
            <a href="<?php echo esc_url( function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '#' ); ?>" class="btn-primary" style="width:100%;justify-content:center;">
                <?php esc_html_e( 'Proceed to Checkout', 'dante-burba' ); ?>
                <?php echo db_icon( 'arrow-right' ); ?>
            </a>
        </div>
    </div>
    <?php
}

// ── Customise mini cart item template ──
add_filter( 'woocommerce_cart_item_thumbnail', function ( $thumbnail, $cart_item, $key ) {
    // Keep default thumbnail but we restyle via CSS
    return $thumbnail;
}, 10, 3 );

// ── Single product: move tabs below add to cart ──
// (default is fine; customise if needed)

// ── Remove default WooCommerce styles (we provide our own) ──
add_filter( 'woocommerce_enqueue_styles', function ( $styles ) {
    // Keep core WC styles but remove layout ones we override
    unset( $styles['woocommerce-general'] );    // we provide our own
    unset( $styles['woocommerce-smallscreen'] );
    return $styles;
} );

// ── Related products: show 4 ──
add_filter( 'woocommerce_output_related_products_args', function ( $args ) {
    $args['posts_per_page'] = 4;
    $args['columns']        = 4;
    return $args;
} );

// ── Product structured data: nothing extra needed ──
