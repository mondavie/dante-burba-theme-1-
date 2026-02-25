<?php
/**
 * Dynamic CSS Variables output.
 * Converts all Customizer settings into a single <style> block of CSS custom properties.
 *
 * @package dante-burba
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_head', 'db_output_css_vars', 5 );
function db_output_css_vars(): void {

    // ── Colors ──
    $c = [
        '--db-orange'           => get_theme_mod( 'db_color_accent',           '#FF3D00' ),
        '--db-orange-dim'       => get_theme_mod( 'db_color_accent_hover',     '#CC3100' ),
        '--db-surface'          => get_theme_mod( 'db_color_surface',          '#0A0A0B' ),
        '--db-surface-2'        => get_theme_mod( 'db_color_surface_2',        '#111113' ),
        '--db-surface-3'        => get_theme_mod( 'db_color_surface_3',        '#18181B' ),
        '--db-border'           => get_theme_mod( 'db_color_border',           '#1f1f22' ),
        '--db-text'             => get_theme_mod( 'db_color_text',             '#F0F0F2' ),
        '--db-muted'            => get_theme_mod( 'db_color_text_muted',       '#6B6B72' ),
        '--db-heading'          => get_theme_mod( 'db_color_heading',          '#FFFFFF' ),
        '--db-link'             => get_theme_mod( 'db_color_link',             '#FF3D00' ),
        '--db-link-hover'       => get_theme_mod( 'db_color_link_hover',       '#CC3100' ),
        '--db-btn-bg'           => get_theme_mod( 'db_color_btn_bg',           '#FF3D00' ),
        '--db-btn-text'         => get_theme_mod( 'db_color_btn_text',         '#FFFFFF' ),
        '--db-btn-hover'        => get_theme_mod( 'db_color_btn_hover_bg',     '#CC3100' ),
        '--db-btn-ghost-border' => get_theme_mod( 'db_color_btn_ghost_border', '#333336' ),
        '--db-shop-bg'          => get_theme_mod( 'db_color_shop_bg',          '#F8F8F6' ),
        '--db-product-bg'       => get_theme_mod( 'db_color_product_bg',       '#FFFFFF' ),
        '--db-stats-bg'         => get_theme_mod( 'db_color_stats_strip',      '#FF3D00' ),
        '--db-nav-scrolled-bg'  => get_theme_mod( 'db_color_nav_scrolled',     '#0A0A0B' ),
    ];

    // ── Typography ──
    $body_size    = absint( get_theme_mod( 'db_font_size_body',     16 ) );
    $lh_body_raw  = absint( get_theme_mod( 'db_line_height_body',  165 ) );
    $h1_size      = absint( get_theme_mod( 'db_font_size_h1',       64 ) );
    $h2_size      = absint( get_theme_mod( 'db_font_size_h2',       40 ) );
    $h3_size      = absint( get_theme_mod( 'db_font_size_h3',       28 ) );
    $h4_size      = absint( get_theme_mod( 'db_font_size_h4',       20 ) );
    $lh_h_raw     = absint( get_theme_mod( 'db_line_height_heading', 95 ) );
    $ls_h_raw     = absint( get_theme_mod( 'db_letter_spacing_h',    3  ) );
    $btn_size     = absint( get_theme_mod( 'db_font_size_btn',       11 ) );
    $section_pad  = absint( get_theme_mod( 'db_section_padding',     96 ) );
    $container_w  = absint( get_theme_mod( 'db_container_width',   1280 ) );
    $nav_h        = absint( get_theme_mod( 'db_nav_height',          72 ) );

    $c += [
        '--db-font-display'       => sanitize_text_field( get_theme_mod( 'db_font_display', "'Bebas Neue', sans-serif" ) ),
        '--db-font-body'          => sanitize_text_field( get_theme_mod( 'db_font_body',    "'DM Sans', sans-serif" ) ),
        '--db-font-mono'          => sanitize_text_field( get_theme_mod( 'db_font_mono',    "'DM Mono', monospace" ) ),
        '--db-body-size'          => $body_size . 'px',
        '--db-body-lh'            => round( $lh_body_raw / 100, 2 ),
        '--db-body-weight'        => sanitize_text_field( get_theme_mod( 'db_font_weight_body', '400' ) ),
        '--db-h1-size'            => 'clamp(' . round($h1_size * 0.5) . 'px, ' . round($h1_size * 0.07, 2) . 'vw, ' . $h1_size . 'px)',
        '--db-h2-size'            => 'clamp(' . round($h2_size * 0.6) . 'px, ' . round($h2_size * 0.05, 2) . 'vw, ' . $h2_size . 'px)',
        '--db-h3-size'            => $h3_size . 'px',
        '--db-h4-size'            => $h4_size . 'px',
        '--db-heading-lh'         => round( $lh_h_raw / 100, 2 ),
        '--db-heading-ls'         => round( $ls_h_raw / 100, 2 ) . 'em',
        '--db-btn-size'           => $btn_size . 'px',
        '--db-btn-weight'         => sanitize_text_field( get_theme_mod( 'db_font_weight_btn', '700' ) ),
        '--db-btn-ls'             => sanitize_text_field( get_theme_mod( 'db_letter_spacing_btn', '0.15em' ) ),
        '--db-btn-padding'        => sanitize_text_field( get_theme_mod( 'db_btn_padding', '16px 36px' ) ),
        '--db-section-pad'        => $section_pad . 'px',
        '--db-container-width'    => $container_w . 'px',
        '--db-nav-height'         => $nav_h . 'px',
        '--db-radius'             => sanitize_text_field( get_theme_mod( 'db_border_radius', '0px' ) ),
        '--db-orange-glow'        => 'rgba(' . db_hex_to_rgb( get_theme_mod( 'db_color_accent', '#FF3D00' ) ) . ',.25)',
    ];

    // ── Build output ──
    echo "\n<style id=\"db-css-vars\">\n:root{\n";
    foreach ( $c as $var => $val ) {
        echo '  ' . esc_attr( $var ) . ':' . esc_attr( $val ) . ";\n";
    }
    echo "}\n</style>\n";
}

/**
 * Convert hex color to r,g,b triplet string.
 */
function db_hex_to_rgb( string $hex ): string {
    $hex = ltrim( $hex, '#' );
    if ( strlen( $hex ) === 3 ) {
        $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    }
    $r = hexdec( substr( $hex, 0, 2 ) );
    $g = hexdec( substr( $hex, 2, 2 ) );
    $b = hexdec( substr( $hex, 4, 2 ) );
    return "$r,$g,$b";
}

/**
 * Output Google Fonts link based on selected font families.
 * Called from wp_head with priority 2 (before main stylesheet).
 */
add_action( 'wp_head', 'db_output_google_fonts', 2 );
function db_output_google_fonts(): void {
    $google_font_map = [
        "'Bebas Neue', sans-serif"   => 'Bebas+Neue',
        "'DM Sans', sans-serif"      => 'DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,600;0,9..40,700;1,9..40,300',
        "'DM Mono', monospace"       => 'DM+Mono:wght@400;500',
        "'Inter', sans-serif"        => 'Inter:wght@300;400;600;700;800',
        "'Oswald', sans-serif"       => 'Oswald:wght@400;500;600;700',
        "'Montserrat', sans-serif"   => 'Montserrat:wght@300;400;600;700;800',
        "'Raleway', sans-serif"      => 'Raleway:wght@300;400;600;700',
        "'Roboto', sans-serif"       => 'Roboto:wght@300;400;500;700',
        "'Poppins', sans-serif"      => 'Poppins:ital,wght@0,300;0,400;0,600;0,700;1,300',
        "'Playfair Display', serif"  => 'Playfair+Display:ital,wght@0,400;0,700;1,400',
    ];

    $selected = array_unique( [
        get_theme_mod( 'db_font_display', "'Bebas Neue', sans-serif" ),
        get_theme_mod( 'db_font_body',    "'DM Sans', sans-serif" ),
        get_theme_mod( 'db_font_mono',    "'DM Mono', monospace" ),
    ] );

    $families = [];
    foreach ( $selected as $font ) {
        if ( isset( $google_font_map[ $font ] ) ) {
            $families[] = $google_font_map[ $font ];
        }
    }

    if ( empty( $families ) ) return;

    $url = 'https://fonts.googleapis.com/css2?family=' . implode( '&family=', $families ) . '&display=swap';
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="stylesheet" href="' . esc_url( $url ) . '">' . "\n";
}
