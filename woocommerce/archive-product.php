<?php
/**
 * WooCommerce Archive Product Template (Shop Page)
 * Overrides the default WooCommerce shop archive.
 *
 * @package dante-burba
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>

<div class="db-shop-wrap">
    <div class="db-shop-inner">

        <!-- Shop Header -->
        <div class="db-shop-header reveal active">
            <p class="db-shop-header__label">// <?php esc_html_e( 'Performance Shop', 'dante-burba' ); ?></p>
            <div class="db-shop-header__row">
                <h1 class="db-shop-header__title">
                    <?php woocommerce_page_title(); ?>
                </h1>
                <p class="db-shop-header__sub">
                    <?php esc_html_e( 'Direct access to our certified OEM inventory. All parts tested and warranted.', 'dante-burba' ); ?>
                </p>
            </div>
            <div class="db-shop-header__line"></div>

            <!-- Filter tabs (product categories) -->
            <div class="db-filter-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Product categories', 'dante-burba' ); ?>">
                <button class="db-filter-tab active"
                        role="tab"
                        onclick="dbFilterProducts('all', this)"
                        aria-selected="true">
                    <?php esc_html_e( 'All Items', 'dante-burba' ); ?>
                </button>
                <?php
                $product_cats = get_terms( [ 'taxonomy' => 'product_cat', 'hide_empty' => true, 'exclude' => [ get_option( 'default_product_cat' ) ] ] );
                foreach ( $product_cats as $cat ) :
                    $cat_url = get_term_link( $cat );
                ?>
                    <a href="<?php echo esc_url( $cat_url ); ?>"
                       class="db-filter-tab"
                       role="tab">
                        <?php echo esc_html( $cat->name ); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- WooCommerce Notices -->
        <?php woocommerce_output_all_notices(); ?>

        <?php if ( woocommerce_product_loop() ) : ?>

        <!-- Products Grid -->
        <div class="db-products-grid" id="db-products-grid">
            <?php
            woocommerce_product_loop_start();
            while ( have_posts() ) :
                the_post();
                wc_get_template_part( 'content', 'product' );
            endwhile;
            woocommerce_product_loop_end();
            ?>
        </div>

        <!-- Pagination -->
        <div style="margin-top:48px;">
            <?php
            woocommerce_pagination();
            ?>
        </div>

        <?php else : ?>
            <?php do_action( 'woocommerce_no_products_found' ); ?>
        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>
