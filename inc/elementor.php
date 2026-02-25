<?php
/**
 * Elementor Compatibility
 *
 * @package dante-burba
 */

defined( 'ABSPATH' ) || exit;

// ── Declare Elementor compatibility ──
add_action( 'elementor/theme/register_locations', 'db_elementor_register_locations' );
function db_elementor_register_locations( $manager ): void {
    $manager->register_all_core_location();
}

// ── Register custom fonts with Elementor ──
add_action( 'elementor/fonts/additional_fonts', 'db_elementor_custom_fonts' );
function db_elementor_custom_fonts( array $fonts ): array {
    $fonts['Bebas Neue'] = 'googlefonts';
    $fonts['DM Sans']    = 'googlefonts';
    $fonts['DM Mono']    = 'googlefonts';
    return $fonts;
}

// ── Inject theme CSS variables into the Elementor editor ──
add_action( 'elementor/editor/after_enqueue_styles', function () {
    ?>
    <style>
        :root {
            --db-orange: #FF3D00;
            --db-orange-dim: #CC3100;
            --db-surface: #0A0A0B;
            --db-surface-2: #111113;
            --db-text: #F0F0F2;
            --db-muted: #6B6B72;
            --db-font-display: 'Bebas Neue', sans-serif;
            --db-font-body: 'DM Sans', sans-serif;
            --db-font-mono: 'DM Mono', monospace;
        }
    </style>
    <?php
} );

// ── Global Elementor colours sync ──
add_action( 'after_setup_theme', 'db_elementor_global_colors' );
function db_elementor_global_colors(): void {
    if ( ! class_exists( '\Elementor\Plugin' ) ) return;

    add_option( 'elementor_disable_color_schemes', 'yes' );
    add_option( 'elementor_disable_typography_schemes', 'yes' );
}

// ── Custom Elementor widgets ──
add_action( 'elementor/widgets/register', 'db_register_elementor_widgets' );
function db_register_elementor_widgets( $manager ): void {
    // Register custom widgets here if needed
    // Example: $manager->register( new \DB_Hero_Widget() );
}

// ── Elementor: load font in frontend ──
add_action( 'wp_head', function () {
    if ( ! defined( 'ELEMENTOR_VERSION' ) ) return;
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
} );
