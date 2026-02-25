<?php
/**
 * WooCommerce page wrapper — used for pages WooCommerce controls.
 * Our custom wrappers are in inc/woocommerce.php (action hooks).
 * This file just calls the standard WooCommerce content.
 *
 * @package dante-burba
 */

get_header();

woocommerce_content();

get_footer();
