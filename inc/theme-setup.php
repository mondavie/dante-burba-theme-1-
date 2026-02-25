<?php
/**
 * Theme Setup — add_theme_support declarations, image sizes, etc.
 *
 * @package dante-burba
 */

defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', 'db_theme_setup' );
function db_theme_setup(): void {

    load_theme_textdomain( 'dante-burba', DB_THEME_DIR . '/languages' );

    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'comment-list',
        'gallery', 'caption', 'style', 'script',
    ] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'editor-styles' );

    // ── WooCommerce ──
    add_theme_support( 'woocommerce', [
        'thumbnail_image_width' => 600,
        'single_image_width'    => 900,
        'product_grid'          => [
            'default_rows'    => 3,
            'min_rows'        => 1,
            'default_columns' => 4,
            'min_columns'     => 1,
            'max_columns'     => 6,
        ],
    ] );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    // ── Elementor ──
    add_theme_support( 'elementor' );

    // ── Image sizes ──
    set_post_thumbnail_size( 800, 600, true );
    add_image_size( 'db-hero',     1920, 1080, true );
    add_image_size( 'db-product',  600,  600,  true );
    add_image_size( 'db-card',     800,  600,  true );
    add_image_size( 'db-portrait', 600,  800,  true );

    // ── Content width ──
    $GLOBALS['content_width'] = 1280;
}
