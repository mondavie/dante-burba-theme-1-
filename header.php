<!DOCTYPE html>
<html <?php language_attributes(); ?> class="scroll-smooth">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Custom Cursor (hidden on touch devices via CSS) -->
<div id="db-cursor" aria-hidden="true"></div>
<div id="db-cursor-ring" aria-hidden="true"></div>

<!-- Scroll Progress Bar -->
<div id="db-progress-bar" aria-hidden="true"></div>

<!-- Skip to content -->
<a class="screen-reader-text" href="#main-content"><?php esc_html_e( 'Skip to content', 'dante-burba' ); ?></a>

<!-- ======================================================
     NAVIGATION
====================================================== -->
<nav id="db-nav" aria-label="<?php esc_attr_e( 'Main navigation', 'dante-burba' ); ?>">
    <div class="db-nav-inner">

        <!-- Logo -->
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="db-logo" aria-label="<?php bloginfo( 'name' ); ?>">
            <div class="db-logo-mark" aria-hidden="true">DB</div>
            <div class="db-logo-text">
                <span class="db-logo-name"><?php bloginfo( 'name' ); ?></span>
                <span class="db-logo-sub"><?php echo esc_html( get_theme_mod( 'db_tagline', 'Diesel Injection Ltd.' ) ); ?></span>
            </div>
        </a>

        <!-- Desktop Navigation -->
        <ul class="db-nav-links" role="list">
            <?php
            wp_nav_menu( [
                'theme_location' => 'primary',
                'container'      => false,
                'items_wrap'     => '%3$s',
                'walker'         => new DB_Nav_Walker(),
                'fallback_cb'    => 'db_default_nav',
            ] );
            ?>
        </ul>

        <!-- Actions -->
        <div class="db-nav-actions">

            <!-- Cart -->
            <?php if ( db_is_woocommerce_active() && function_exists( 'wc_get_cart_url' ) ) : ?>
            <button class="db-cart-btn"
                    onclick="dbToggleCart()"
                    aria-label="<?php esc_attr_e( 'Open cart', 'dante-burba' ); ?>"
                    aria-expanded="false"
                    id="db-cart-toggle">
                <?php echo db_icon( 'shopping-cart' ); ?>
                <span class="db-cart-count" id="db-cart-count">
                    <?php echo absint( WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ); ?>
                </span>
            </button>
            <?php endif; ?>

            <!-- Book CTA -->
            <a href="#contact" class="db-nav-book">
                <?php esc_html_e( 'Book Diagnostic', 'dante-burba' ); ?>
            </a>

            <!-- Mobile Hamburger -->
            <button class="db-menu-toggle"
                    id="db-menu-toggle"
                    aria-label="<?php esc_attr_e( 'Open menu', 'dante-burba' ); ?>"
                    aria-expanded="false"
                    aria-controls="db-mobile-menu">
                <?php echo db_icon( 'menu' ); ?>
            </button>
        </div>

    </div>
</nav>

<!-- ======================================================
     MOBILE MENU
====================================================== -->
<div id="db-mobile-menu"
     role="dialog"
     aria-label="<?php esc_attr_e( 'Mobile menu', 'dante-burba' ); ?>"
     aria-modal="true">

    <div class="db-mobile-header">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="db-logo" onclick="dbCloseMobileMenu()">
            <div class="db-logo-mark">DB</div>
            <span class="db-logo-name"><?php bloginfo( 'name' ); ?></span>
        </a>
        <button class="db-mobile-close" id="db-menu-close" aria-label="<?php esc_attr_e( 'Close menu', 'dante-burba' ); ?>">
            <?php echo db_icon( 'x' ); ?>
        </button>
    </div>

    <ul class="db-mobile-nav" role="list">
        <?php
        wp_nav_menu( [
            'theme_location' => 'mobile',
            'container'      => false,
            'items_wrap'     => '%3$s',
            'fallback_cb'    => 'db_default_mobile_nav',
        ] );
        ?>
    </ul>

    <div class="db-mobile-footer">
        <p><?php esc_html_e( 'Est. 1954 · Nairobi, Kenya', 'dante-burba' ); ?></p>
        <p><?php esc_html_e( "East Africa's Premier Diesel Specialist.", 'dante-burba' ); ?></p>
    </div>
</div>

<!-- ======================================================
     MAIN CONTENT STARTS
====================================================== -->
<div id="main-content">
<?php

// ── Fallback nav menus ──

function db_default_nav(): void {
    // Build shop URL safely — WooCommerce may not be active
    $shop_url = ( db_is_woocommerce_active() && function_exists( 'wc_get_page_id' ) )
        ? get_permalink( wc_get_page_id( 'shop' ) )
        : '#shop';

    $items = [
        home_url( '/' ) => __( 'Home',       'dante-burba' ),
        '#services'     => __( 'Services',   'dante-burba' ),
        '#tech'         => __( 'Technology', 'dante-burba' ),
        $shop_url       => __( 'Shop',       'dante-burba' ),
        '#about'        => __( 'Legacy',     'dante-burba' ),
    ];

    foreach ( $items as $url => $label ) {
        $is_shop = ( $url === $shop_url && $shop_url !== home_url( '/' ) );
        $class   = $is_shop ? ' class="shop-active"' : '';
        echo '<li><a href="' . esc_url( $url ) . '"' . $class . '>' . esc_html( $label ) . '</a></li>';
    }
}

function db_default_mobile_nav(): void {
    $shop_url = ( db_is_woocommerce_active() && function_exists( 'wc_get_page_id' ) )
        ? get_permalink( wc_get_page_id( 'shop' ) )
        : '#shop';

    $items = [
        home_url( '/' ) => [ __( 'Home',         'dante-burba' ), '' ],
        '#services'     => [ __( 'Services',     'dante-burba' ), '' ],
        '#tech'         => [ __( 'Technology',   'dante-burba' ), '' ],
        $shop_url       => [ __( 'Shop Catalog', 'dante-burba' ), 'shop-link' ],
        '#about'        => [ __( 'Our Legacy',   'dante-burba' ), '' ],
        '#contact'      => [ __( 'Contact',      'dante-burba' ), '' ],
    ];

    foreach ( $items as $url => $info ) {
        echo '<li><a href="' . esc_url( $url ) . '" class="' . esc_attr( $info[1] ) . '" onclick="dbCloseMobileMenu()">' . esc_html( $info[0] ) . '</a></li>';
    }
}
