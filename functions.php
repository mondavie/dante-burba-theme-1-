<?php
/**
 * Dante Burba Theme — functions.php
 *
 * @package dante-burba
 */

defined( 'ABSPATH' ) || exit;

define( 'DB_THEME_VERSION', '2.0.0' );
define( 'DB_THEME_DIR',     get_template_directory() );
define( 'DB_THEME_URI',     get_template_directory_uri() );
define( 'DB_ASSETS_URI',    DB_THEME_URI . '/assets' );

// ── Core includes ──
require_once DB_THEME_DIR . '/inc/theme-setup.php';
require_once DB_THEME_DIR . '/inc/enqueue.php';
require_once DB_THEME_DIR . '/inc/nav-menus.php';
require_once DB_THEME_DIR . '/inc/customizer.php';
require_once DB_THEME_DIR . '/inc/css-vars.php';
require_once DB_THEME_DIR . '/inc/meta-boxes.php';
require_once DB_THEME_DIR . '/inc/template-functions.php';

// ── Optional integrations ──
if ( db_is_woocommerce_active() ) {
    require_once DB_THEME_DIR . '/inc/woocommerce.php';
}
if ( class_exists( '\Elementor\Plugin' ) ) {
    require_once DB_THEME_DIR . '/inc/elementor.php';
}

// ── Helpers ──
function db_is_woocommerce_active(): bool {
    return class_exists( 'WooCommerce' );
}

function db_get_option( string $key, $default = '' ) {
    return get_theme_mod( 'db_' . $key, $default );
}

/**
 * Inline SVG icon library.
 */
function db_icon( string $name, string $class = '' ): string {
    $icons = [
        'shopping-cart' => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/>',
        'menu'          => '<line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/>',
        'x'             => '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>',
        'arrow-right'   => '<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>',
        'arrow-left'    => '<line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>',
        'map-pin'       => '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>',
        'phone'         => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.15 12 19.79 19.79 0 0 1 1.07 3.37 2 2 0 0 1 3.05 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 8.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 16z"/>',
        'clock'         => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
        'instagram'     => '<rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>',
        'linkedin'      => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/>',
        'facebook'      => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
        'twitter'       => '<path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/>',
        'youtube'       => '<path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58A2.78 2.78 0 0 0 3.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/>',
        'settings-2'    => '<path d="M20 7h-9"/><path d="M14 17H5"/><circle cx="17" cy="17" r="3"/><circle cx="7" cy="7" r="3"/>',
        'wind'          => '<path d="M9.59 4.59A2 2 0 1 1 11 8H2m10.59 11.41A2 2 0 1 0 14 16H2m15.73-8.27A2.5 2.5 0 1 1 19.5 12H2"/>',
        'cpu'           => '<rect x="4" y="4" width="16" height="16" rx="2" ry="2"/><rect x="9" y="9" width="6" height="6"/><line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/><line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/><line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="14" x2="23" y2="14"/><line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="14" x2="4" y2="14"/>',
        'plus'          => '<line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>',
        'check'         => '<polyline points="20 6 9 17 4 12"/>',
        'trash-2'       => '<polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>',
        'shield-check'  => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/>',
        'info'          => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
        'loader-2'      => '<path d="M21 12a9 9 0 1 1-6.219-8.56"/>',
        'zap'           => '<polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>',
        'alert-circle'  => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
    ];

    $path  = $icons[ $name ] ?? '';
    $attrs = $class ? ' class="' . esc_attr( $class ) . '"' : '';
    return '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"' . $attrs . '>' . $path . '</svg>';
}

/**
 * Retrieve hero image URL with Customizer and meta fallbacks.
 */
function db_get_hero_image_url( int $post_id = 0 ): string {
    // 1. Page-level meta override
    if ( $post_id ) {
        $meta_id = absint( get_post_meta( $post_id, 'db_hero_override_image', true ) );
        if ( $meta_id ) {
            $src = wp_get_attachment_image_url( $meta_id, 'db-hero' );
            if ( $src ) return $src;
        }
    }
    // 2. Customizer
    $customizer_id = get_theme_mod( 'db_hero_image' );
    if ( $customizer_id ) {
        $src = wp_get_attachment_image_url( $customizer_id, 'db-hero' );
        if ( $src ) return $src;
    }
    // 3. Default stock photo
    return 'https://images.unsplash.com/photo-1530046339160-ce3e530c7d2f?auto=format&fit=crop&q=80&w=2200';
}
