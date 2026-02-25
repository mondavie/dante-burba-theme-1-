<?php
/**
 * Navigation Menus registration
 *
 * @package dante-burba
 */

defined( 'ABSPATH' ) || exit;

add_action( 'after_setup_theme', 'db_register_menus' );
function db_register_menus(): void {
    register_nav_menus( [
        'primary'   => __( 'Primary Navigation', 'dante-burba' ),
        'mobile'    => __( 'Mobile Navigation',  'dante-burba' ),
        'footer'    => __( 'Footer Links',        'dante-burba' ),
    ] );
}

/**
 * Custom nav walker for the primary desktop menu.
 */
class DB_Nav_Walker extends Walker_Nav_Menu {

    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $classes    = empty( $item->classes ) ? [] : (array) $item->classes;
        $is_current = in_array( 'current-menu-item', $classes, true );
        $is_shop    = has_term( 'shop', 'nav_menu', $item ) || ( defined( 'ABSPATH' ) && db_is_woocommerce_active() && is_shop() && $is_current );
        $link_class = $is_shop ? 'shop-active' : ( $is_current ? 'active' : '' );

        $output .= '<li>';
        $output .= '<a href="' . esc_url( $item->url ) . '" class="' . esc_attr( $link_class ) . '">';
        $output .= esc_html( $item->title );
        $output .= '</a>';
        $output .= '</li>';
    }
}
