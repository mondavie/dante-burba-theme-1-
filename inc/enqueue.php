<?php
/**
 * Enqueue Scripts & Styles
 *
 * @package dante-burba
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_enqueue_scripts', 'db_enqueue_assets' );
function db_enqueue_assets(): void {

    // ── Google Fonts ──
    wp_enqueue_style(
        'db-google-fonts',
        'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,600;0,9..40,700;1,9..40,300&family=DM+Mono:wght@400;500&display=swap',
        [],
        null
    );

    // ── Main Stylesheet (style.css) ──
    wp_enqueue_style(
        'dante-burba-style',
        get_stylesheet_uri(),
        [ 'db-google-fonts' ],
        DB_THEME_VERSION
    );

    // ── WooCommerce extra styles ──
    if ( db_is_woocommerce_active() ) {
        wp_enqueue_style(
            'db-woocommerce',
            DB_ASSETS_URI . '/css/woocommerce.css',
            [ 'dante-burba-style' ],
            DB_THEME_VERSION
        );
    }

    // ── Lucide Icons (lightweight SVG icon lib) ──
    wp_enqueue_script(
        'lucide',
        'https://unpkg.com/lucide@latest',
        [],
        null,
        true
    );

    // ── Main JS ──
    wp_enqueue_script(
        'db-main',
        DB_ASSETS_URI . '/js/main.js',
        [ 'lucide' ],
        DB_THEME_VERSION,
        true
    );

    // ── Localise JS ──
    $woo_active = db_is_woocommerce_active() && function_exists( 'wc_get_cart_url' );
    wp_localize_script( 'db-main', 'dbData', [
        'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
        'nonce'       => wp_create_nonce( 'db_nonce' ),
        'cartUrl'     => $woo_active ? wc_get_cart_url()     : '#',
        'checkoutUrl' => $woo_active ? wc_get_checkout_url() : '#',
        'shopUrl'     => $woo_active ? get_permalink( wc_get_page_id( 'shop' ) ) : '#',
        'currency'    => $woo_active ? get_woocommerce_currency_symbol() : '$',
        'isShop'      => $woo_active && ( is_shop() || is_product_category() || is_product() ),
    ] );

    // ── WooCommerce JS ──
    if ( db_is_woocommerce_active() ) {
        wp_enqueue_script(
            'db-woocommerce-js',
            DB_ASSETS_URI . '/js/woocommerce.js',
            [ 'db-main', 'jquery', 'wc-cart-fragments' ],
            DB_THEME_VERSION,
            true
        );
    }

    // ── Comment reply ──
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

// ── Elementor: enqueue theme fonts in editor ──
add_action( 'elementor/editor/before_enqueue_scripts', function () {
    wp_enqueue_style( 'db-google-fonts' );
} );

// ── Block editor styles ──
add_action( 'admin_init', function () {
    add_editor_style( 'assets/css/editor.css' );
} );
