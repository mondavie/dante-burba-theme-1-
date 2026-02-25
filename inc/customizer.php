<?php
/**
 * Theme Customizer — Full typography, colors, and content settings.
 *
 * @package dante-burba
 */

defined( 'ABSPATH' ) || exit;

add_action( 'customize_register', 'db_customizer_register' );
function db_customizer_register( WP_Customize_Manager $wp_customize ): void {

    /* ================================================================
       PANEL: Dante Burba Theme
    ================================================================ */
    $wp_customize->add_panel( 'db_theme_panel', [
        'title'    => __( 'Dante Burba Theme', 'dante-burba' ),
        'priority' => 30,
    ] );

    /* ================================================================
       SECTION: Brand & Identity
    ================================================================ */
    $wp_customize->add_section( 'db_brand', [ 'title' => __( 'Brand & Identity', 'dante-burba' ), 'panel' => 'db_theme_panel' ] );
    db_add_text( $wp_customize, 'db_brand_name',       'db_brand', __( 'Brand Name', 'dante-burba' ),         'Dante Burba' );
    db_add_text( $wp_customize, 'db_tagline',          'db_brand', __( 'Nav Sub-Tagline', 'dante-burba' ),    'Diesel Injection Ltd.' );
    db_add_text( $wp_customize, 'db_est_year',         'db_brand', __( 'Established Year', 'dante-burba' ),   '1954' );
    db_add_text( $wp_customize, 'db_location_short',   'db_brand', __( 'Location (short)', 'dante-burba' ),   'Nairobi, Kenya' );
    db_add_text( $wp_customize, 'db_footer_copyright', 'db_brand', __( 'Footer Copyright Line', 'dante-burba' ), 'All Rights Reserved.' );

    /* ================================================================
       SECTION: Color Scheme
    ================================================================ */
    $wp_customize->add_section( 'db_colors', [
        'title'       => __( 'Color Scheme', 'dante-burba' ),
        'panel'       => 'db_theme_panel',
        'description' => __( 'All colors apply site-wide via CSS variables.', 'dante-burba' ),
    ] );

    $colors = [
        'db_color_accent'           => [ __( 'Accent / Primary',          'dante-burba' ), '#FF3D00' ],
        'db_color_accent_hover'     => [ __( 'Accent Hover',              'dante-burba' ), '#CC3100' ],
        'db_color_surface'          => [ __( 'Background Dark',           'dante-burba' ), '#0A0A0B' ],
        'db_color_surface_2'        => [ __( 'Background Surface 2',      'dante-burba' ), '#111113' ],
        'db_color_surface_3'        => [ __( 'Background Surface 3',      'dante-burba' ), '#18181B' ],
        'db_color_border'           => [ __( 'Border Color',              'dante-burba' ), '#1f1f22' ],
        'db_color_text'             => [ __( 'Body Text',                 'dante-burba' ), '#F0F0F2' ],
        'db_color_text_muted'       => [ __( 'Muted Text',                'dante-burba' ), '#6B6B72' ],
        'db_color_heading'          => [ __( 'Heading Text',              'dante-burba' ), '#FFFFFF' ],
        'db_color_link'             => [ __( 'Link Color',                'dante-burba' ), '#FF3D00' ],
        'db_color_link_hover'       => [ __( 'Link Hover',                'dante-burba' ), '#CC3100' ],
        'db_color_btn_bg'           => [ __( 'Button Background',         'dante-burba' ), '#FF3D00' ],
        'db_color_btn_text'         => [ __( 'Button Text',               'dante-burba' ), '#FFFFFF' ],
        'db_color_btn_hover_bg'     => [ __( 'Button Hover Background',   'dante-burba' ), '#CC3100' ],
        'db_color_btn_ghost_border' => [ __( 'Ghost Button Border',       'dante-burba' ), '#333336' ],
        'db_color_shop_bg'          => [ __( 'Shop Background',           'dante-burba' ), '#F8F8F6' ],
        'db_color_product_bg'       => [ __( 'Product Card Background',   'dante-burba' ), '#FFFFFF' ],
        'db_color_stats_strip'      => [ __( 'Stats Strip Background',    'dante-burba' ), '#FF3D00' ],
        'db_color_nav_scrolled'     => [ __( 'Nav Background (scrolled)', 'dante-burba' ), '#0A0A0B' ],
    ];

    foreach ( $colors as $key => [ $label, $default ] ) {
        $wp_customize->add_setting( $key, [ 'default' => $default, 'sanitize_callback' => 'sanitize_hex_color', 'transport' => 'postMessage' ] );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $key, [ 'label' => $label, 'section' => 'db_colors' ] ) );
    }

    /* ================================================================
       SECTION: Typography
    ================================================================ */
    $wp_customize->add_section( 'db_typography', [
        'title'       => __( 'Typography', 'dante-burba' ),
        'panel'       => 'db_theme_panel',
        'description' => __( 'Control all fonts, sizes, weights, and spacing globally.', 'dante-burba' ),
    ] );

    $fonts = [
        "'Bebas Neue', sans-serif"   => 'Bebas Neue (Display)',
        "'DM Sans', sans-serif"      => 'DM Sans',
        "'DM Mono', monospace"       => 'DM Mono',
        "'Inter', sans-serif"        => 'Inter',
        "'Oswald', sans-serif"       => 'Oswald',
        "'Montserrat', sans-serif"   => 'Montserrat',
        "'Raleway', sans-serif"      => 'Raleway',
        "'Roboto', sans-serif"       => 'Roboto',
        "'Poppins', sans-serif"      => 'Poppins',
        "'Playfair Display', serif"  => 'Playfair Display (Serif)',
        "'Georgia', serif"           => 'Georgia (Serif)',
        "system-ui, sans-serif"      => 'System UI',
    ];

    $weights = [
        '300' => 'Light (300)',
        '400' => 'Regular (400)',
        '500' => 'Medium (500)',
        '600' => 'SemiBold (600)',
        '700' => 'Bold (700)',
        '800' => 'ExtraBold (800)',
        '900' => 'Black (900)',
    ];

    // ── Font families ──
    db_add_select( $wp_customize, 'db_font_display', 'db_typography', __( 'Display / Heading Font', 'dante-burba' ), "'Bebas Neue', sans-serif", $fonts );
    db_add_select( $wp_customize, 'db_font_body',    'db_typography', __( 'Body Font',               'dante-burba' ), "'DM Sans', sans-serif",    $fonts );
    db_add_select( $wp_customize, 'db_font_mono',    'db_typography', __( 'Label / Mono Font',       'dante-burba' ), "'DM Mono', monospace",      $fonts );

    // ── Body ──
    db_add_range( $wp_customize, 'db_font_size_body',    'db_typography', __( 'Body Font Size (px)',    'dante-burba' ), 16, 12, 22, 1 );
    db_add_range( $wp_customize, 'db_line_height_body',  'db_typography', __( 'Body Line Height × 100','dante-burba' ), 165, 120, 200, 5 );
    db_add_select( $wp_customize, 'db_font_weight_body', 'db_typography', __( 'Body Font Weight',       'dante-burba' ), '400', $weights );

    // ── Headings ──
    db_add_range( $wp_customize, 'db_font_size_h1',       'db_typography', __( 'H1 Min Size (px)', 'dante-burba' ), 64, 32, 120, 4 );
    db_add_range( $wp_customize, 'db_font_size_h2',       'db_typography', __( 'H2 Min Size (px)', 'dante-burba' ), 40, 24, 80,  4 );
    db_add_range( $wp_customize, 'db_font_size_h3',       'db_typography', __( 'H3 Size (px)',     'dante-burba' ), 28, 18, 56,  2 );
    db_add_range( $wp_customize, 'db_font_size_h4',       'db_typography', __( 'H4 Size (px)',     'dante-burba' ), 20, 14, 40,  2 );
    db_add_range( $wp_customize, 'db_line_height_heading','db_typography', __( 'Heading Line Height × 100', 'dante-burba' ), 95, 70, 130, 5 );
    db_add_range( $wp_customize, 'db_letter_spacing_h',   'db_typography', __( 'Heading Letter Spacing × 100 em', 'dante-burba' ), 3, 0, 20, 1 );

    // ── Buttons ──
    db_add_range(  $wp_customize, 'db_font_size_btn',      'db_typography', __( 'Button Font Size (px)',      'dante-burba' ), 11, 9, 18, 1 );
    db_add_select( $wp_customize, 'db_font_weight_btn',    'db_typography', __( 'Button Font Weight',         'dante-burba' ), '700', $weights );
    db_add_text(   $wp_customize, 'db_letter_spacing_btn', 'db_typography', __( 'Button Letter Spacing (em)', 'dante-burba' ), '0.15em' );
    db_add_text(   $wp_customize, 'db_btn_padding',        'db_typography', __( 'Button Padding',             'dante-burba' ), '16px 36px' );

    /* ================================================================
       SECTION: Layout & Spacing
    ================================================================ */
    $wp_customize->add_section( 'db_layout', [ 'title' => __( 'Layout & Spacing', 'dante-burba' ), 'panel' => 'db_theme_panel' ] );
    db_add_range(  $wp_customize, 'db_section_padding',   'db_layout', __( 'Section Vertical Padding (px)', 'dante-burba' ), 96, 40, 160, 8 );
    db_add_range(  $wp_customize, 'db_container_width',   'db_layout', __( 'Container Max Width (px)',      'dante-burba' ), 1280, 960, 1600, 40 );
    db_add_range(  $wp_customize, 'db_nav_height',        'db_layout', __( 'Nav Height (px)',               'dante-burba' ), 72,  48,  100, 4 );
    db_add_select( $wp_customize, 'db_border_radius',     'db_layout', __( 'Global Border Radius',          'dante-burba' ), '0px', [
        '0px' => 'Sharp (0px)', '2px' => 'Minimal (2px)', '4px' => 'Rounded (4px)', '8px' => 'Soft (8px)',
    ] );

    /* ================================================================
       CONTENT SECTIONS
    ================================================================ */

    // ── Hero ──
    $wp_customize->add_section( 'db_hero', [ 'title' => __( 'Hero Section', 'dante-burba' ), 'panel' => 'db_theme_panel' ] );
    db_add_text(     $wp_customize, 'db_hero_badge',          'db_hero', __( 'Badge Text',           'dante-burba' ), "Est. 1954 · East Africa's Premier Workshop" );
    db_add_text(     $wp_customize, 'db_hero_line1',          'db_hero', __( 'Headline Line 1',      'dante-burba' ), 'PRECISION' );
    db_add_text(     $wp_customize, 'db_hero_line2',          'db_hero', __( 'Headline Line 2 (accent)','dante-burba' ), 'ENGINEERED.' );
    db_add_textarea( $wp_customize, 'db_hero_sub',            'db_hero', __( 'Subheading',           'dante-burba' ), 'Seven decades of diesel mastery, now augmented with future-ready diagnostics. The gold standard across East Africa.' );
    db_add_text(     $wp_customize, 'db_hero_cta_primary',    'db_hero', __( 'Primary CTA Label',    'dante-burba' ), 'Book Diagnostic' );
    db_add_text(     $wp_customize, 'db_hero_cta_secondary',  'db_hero', __( 'Secondary CTA Label',  'dante-burba' ), 'Browse Shop' );
    db_add_range(    $wp_customize, 'db_hero_img_opacity',    'db_hero', __( 'BG Image Opacity %',   'dante-burba' ), 20, 5, 60, 5 );
    $wp_customize->add_setting( 'db_hero_image', [ 'sanitize_callback' => 'absint' ] );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'db_hero_image', [
        'label' => __( 'Hero Background Image', 'dante-burba' ), 'section' => 'db_hero', 'mime_type' => 'image',
    ] ) );

    // ── Stats ──
    $wp_customize->add_section( 'db_stats', [ 'title' => __( 'Stats Strip', 'dante-burba' ), 'panel' => 'db_theme_panel' ] );
    $sd = [ '1' => [ '70+', 'Years Experience' ], '2' => [ '120k+', 'Systems Serviced' ], '3' => [ '6', 'OEM Certifications' ], '4' => [ '1', 'Clean-Room Lab' ] ];
    for ( $i = 1; $i <= 4; $i++ ) {
        db_add_text( $wp_customize, "db_stat_{$i}_num",   'db_stats', "Stat $i Number", $sd[$i][0] );
        db_add_text( $wp_customize, "db_stat_{$i}_label", 'db_stats', "Stat $i Label",  $sd[$i][1] );
    }

    // ── Marquee ──
    $wp_customize->add_section( 'db_marquee', [ 'title' => __( 'Partners Marquee', 'dante-burba' ), 'panel' => 'db_theme_panel' ] );
    $md = [ 'BOSCH AUTHORIZED', 'DELPHI TECHNOLOGIES', 'DENSO CERTIFIED', 'STANADYNE', 'GARRETT TURBO', 'CUMMINS', '', '' ];
    for ( $i = 1; $i <= 8; $i++ ) {
        db_add_text( $wp_customize, "db_marquee_item_{$i}", 'db_marquee', "Partner $i Name", $md[$i-1] );
    }

    // ── Services ──
    $wp_customize->add_section( 'db_services', [ 'title' => __( 'Services Section', 'dante-burba' ), 'panel' => 'db_theme_panel' ] );
    db_add_text(     $wp_customize, 'db_services_label',   'db_services', __( 'Section Label',   'dante-burba' ), 'Core Capabilities' );
    db_add_text(     $wp_customize, 'db_services_title_1', 'db_services', __( 'Title Line 1',    'dante-burba' ), 'PRECISION IN' );
    db_add_text(     $wp_customize, 'db_services_title_2', 'db_services', __( 'Title Line 2',    'dante-burba' ), 'EVERY BURST.' );
    db_add_textarea( $wp_customize, 'db_services_intro',   'db_services', __( 'Intro Text',      'dante-burba' ), 'Our workshop utilizes Bosch EPS 815 and 708 test benches, ensuring your fuel injection system meets exact OEM specifications to the micron.' );
    $svd = [ '1'=>['DIESEL INJECTION','Complete overhaul and calibration of rotary, inline, and common rail pumps. We specialize in EUI, EUP, and HEUI systems for all major brands.'],'2'=>['TURBOCHARGERS','Advanced diagnostic and repair of variable geometry (VNT) and wastegate turbos. High-speed core balancing up to 200,000 RPM.'],'3'=>['ELECTRONIC SYSTEMS','Diagnostic solutions for modern EFI, GDI, and common rail electronics. Hartridge Sabre CRi Master for solenoid and piezo injectors.'] ];
    for ( $i = 1; $i <= 3; $i++ ) {
        db_add_text(     $wp_customize, "db_service_{$i}_title", 'db_services', "Service $i Title",       $svd[$i][0] );
        db_add_textarea( $wp_customize, "db_service_{$i}_text",  'db_services', "Service $i Description", $svd[$i][1] );
    }

    // ── About ──
    $wp_customize->add_section( 'db_about', [ 'title' => __( 'About / Legacy Section', 'dante-burba' ), 'panel' => 'db_theme_panel' ] );
    db_add_text(     $wp_customize, 'db_about_label',             'db_about', 'Section Label',          'The 1954 Legacy' );
    db_add_text(     $wp_customize, 'db_about_title_1',           'db_about', 'Title Line 1',            'BUILT ON RELIABILITY.' );
    db_add_text(     $wp_customize, 'db_about_title_2',           'db_about', 'Title Line 2 (accent)',   'SUSTAINED BY MASTERY.' );
    db_add_textarea( $wp_customize, 'db_about_body',              'db_about', 'Body Text',               'Dante Burba Diesel Injection Ltd was founded with a singular vision: to bring world-class precision engineering to East Africa. We are an AA-appointed workshop and authorized service partner for global leaders including Bosch and Delphi.' );
    db_add_text(     $wp_customize, 'db_about_quote',             'db_about', 'Pull Quote',              "We don't just repair," );
    db_add_text(     $wp_customize, 'db_about_quote_sub',         'db_about', 'Pull Quote Sub',          'We restore engineering intent.' );
    db_add_textarea( $wp_customize, 'db_about_testimonial',       'db_about', 'Testimonial Text',        '"The only place in Nairobi I trust for my fleet\'s injection systems. Precision is their religion."' );
    db_add_text(     $wp_customize, 'db_about_testimonial_author','db_about', 'Testimonial Author',      '— James M., Transport Director' );
    $wp_customize->add_setting( 'db_about_image', [ 'sanitize_callback' => 'absint' ] );
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'db_about_image', [
        'label' => 'About Section Image', 'section' => 'db_about', 'mime_type' => 'image',
    ] ) );

    // ── Contact ──
    $wp_customize->add_section( 'db_contact', [ 'title' => __( 'Contact Information', 'dante-burba' ), 'panel' => 'db_theme_panel' ] );
    db_add_text( $wp_customize, 'db_contact_title',          'db_contact', 'Section Title (accent)',  'PERFORMANCE?' );
    db_add_text( $wp_customize, 'db_contact_address_line1',  'db_contact', 'Address Line 1',          'Nairobi, Kenya' );
    db_add_text( $wp_customize, 'db_contact_address_line2',  'db_contact', 'Address Line 2',          'Industrial Area' );
    db_add_text( $wp_customize, 'db_contact_phone',          'db_contact', 'Phone Number',            '+254 7XX XXX XXX' );
    db_add_text( $wp_customize, 'db_contact_email',          'db_contact', 'Email Address',           'info@danteburba.com' );
    db_add_text( $wp_customize, 'db_contact_hours_weekday',  'db_contact', 'Weekday Hours',           'Mon – Fri: 8am – 5pm' );
    db_add_text( $wp_customize, 'db_contact_hours_weekend',  'db_contact', 'Weekend Hours',           'Sat: 8am – 12pm' );
    db_add_text( $wp_customize, 'db_contact_cta_label',      'db_contact', 'CTA Button Label',        'Request a Callback' );
    db_add_text( $wp_customize, 'db_contact_map_embed',      'db_contact', 'Google Maps Embed URL',   '' );

    // ── Social ──
    $wp_customize->add_section( 'db_social', [ 'title' => __( 'Social Links', 'dante-burba' ), 'panel' => 'db_theme_panel' ] );
    foreach ( [ 'instagram', 'linkedin', 'facebook', 'twitter', 'youtube' ] as $p ) {
        $wp_customize->add_setting( "db_social_{$p}", [ 'default' => '', 'sanitize_callback' => 'esc_url_raw' ] );
        $wp_customize->add_control( "db_social_{$p}", [ 'label' => ucfirst($p).' URL', 'section' => 'db_social', 'type' => 'url' ] );
    }
}

/* ================================================================
   HELPER SHORTHAND FUNCTIONS
================================================================ */
function db_add_text( $m, $key, $section, $label, $default ): void {
    $m->add_setting( $key, [ 'default' => $default, 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'refresh' ] );
    $m->add_control( $key, [ 'label' => $label, 'section' => $section, 'type' => 'text' ] );
}
function db_add_textarea( $m, $key, $section, $label, $default ): void {
    $m->add_setting( $key, [ 'default' => $default, 'sanitize_callback' => 'sanitize_textarea_field', 'transport' => 'refresh' ] );
    $m->add_control( $key, [ 'label' => $label, 'section' => $section, 'type' => 'textarea' ] );
}
function db_add_select( $m, $key, $section, $label, $default, $choices ): void {
    $m->add_setting( $key, [ 'default' => $default, 'sanitize_callback' => 'sanitize_text_field', 'transport' => 'postMessage' ] );
    $m->add_control( $key, [ 'label' => $label, 'section' => $section, 'type' => 'select', 'choices' => $choices ] );
}
function db_add_range( $m, $key, $section, $label, $default, $min, $max, $step ): void {
    $m->add_setting( $key, [ 'default' => $default, 'sanitize_callback' => 'absint', 'transport' => 'postMessage' ] );
    $m->add_control( $key, [ 'label' => $label, 'section' => $section, 'type' => 'range', 'input_attrs' => [ 'min' => $min, 'max' => $max, 'step' => $step ] ] );
}
