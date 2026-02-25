<?php
/**
 * Template helper functions — all data sourced from Customizer.
 *
 * @package dante-burba
 */

defined( 'ABSPATH' ) || exit;

/**
 * Stats strip — from Customizer.
 */
function db_render_stats_strip(): void {
    $stats = [];
    for ( $i = 1; $i <= 4; $i++ ) {
        $num   = get_theme_mod( "db_stat_{$i}_num",   [ '70+', '120k+', '6', '1' ][ $i - 1 ] );
        $label = get_theme_mod( "db_stat_{$i}_label", [ 'Years Experience', 'Systems Serviced', 'OEM Certifications', 'Clean-Room Lab' ][ $i - 1 ] );
        if ( $num ) $stats[] = [ 'num' => $num, 'label' => $label ];
    }
    if ( empty( $stats ) ) return;
    ?>
    <section class="db-stats-strip" aria-label="<?php esc_attr_e( 'Key statistics', 'dante-burba' ); ?>">
        <div class="db-stats-strip__inner">
            <?php foreach ( $stats as $stat ) : ?>
            <div>
                <span class="db-stats-strip__num"><?php echo esc_html( $stat['num'] ); ?></span>
                <span class="db-stats-strip__label"><?php echo esc_html( $stat['label'] ); ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
}

/**
 * Partners marquee — from Customizer.
 */
function db_render_marquee(): void {
    $partners = [];
    for ( $i = 1; $i <= 8; $i++ ) {
        $val = get_theme_mod( "db_marquee_item_{$i}", '' );
        if ( $val ) $partners[] = $val;
    }
    if ( empty( $partners ) ) {
        $partners = [ 'BOSCH AUTHORIZED', 'DELPHI TECHNOLOGIES', 'DENSO CERTIFIED', 'STANADYNE', 'GARRETT TURBO', 'CUMMINS' ];
    }
    $all = array_merge( $partners, $partners ); // duplicate for loop
    ?>
    <section class="db-marquee-wrap" aria-label="<?php esc_attr_e( 'Authorized partners', 'dante-burba' ); ?>">
        <div class="db-marquee" aria-hidden="true">
            <?php foreach ( $all as $partner ) : ?>
            <span class="db-marquee__item"><?php echo esc_html( $partner ); ?></span>
            <span class="db-marquee__sep">✦</span>
            <?php endforeach; ?>
        </div>
    </section>
    <?php
}

/**
 * Contact info cards — from Customizer (with per-page meta override if $post_id given).
 */
function db_render_contact_cards( int $post_id = 0 ): void {
    $phone  = ( $post_id ? get_post_meta( $post_id, 'db_contact_phone',  true ) : '' ) ?: get_theme_mod( 'db_contact_phone',  '+254 7XX XXX XXX' );
    $email  = ( $post_id ? get_post_meta( $post_id, 'db_contact_email',  true ) : '' ) ?: get_theme_mod( 'db_contact_email',  'info@danteburba.com' );
    $wkdy   = get_theme_mod( 'db_contact_hours_weekday', 'Mon – Fri: 8am – 5pm' );
    $wknd   = get_theme_mod( 'db_contact_hours_weekend', 'Sat: 8am – 12pm' );
    $addr1  = get_theme_mod( 'db_contact_address_line1', 'Nairobi, Kenya' );
    $addr2  = get_theme_mod( 'db_contact_address_line2', 'Industrial Area' );

    $cards = [
        [ 'map-pin', __( 'Location', 'dante-burba' ), esc_html( $addr1 ) . '<br>' . esc_html( $addr2 ) ],
        [ 'phone',   __( 'Hotline',  'dante-burba' ), '<a href="tel:' . esc_attr( preg_replace('/\s+/', '', $phone) ) . '">' . esc_html( $phone ) . '</a><br><a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>' ],
        [ 'clock',   __( 'Hours',    'dante-burba' ), esc_html( $wkdy ) . '<br>' . esc_html( $wknd ) ],
    ];
    ?>
    <div class="db-contact__cards">
        <?php foreach ( $cards as $card ) : ?>
        <div class="db-contact-card">
            <div class="db-contact-card__icon"><?php echo db_icon( $card[0] ); ?></div>
            <div class="db-contact-card__label"><?php echo esc_html( $card[1] ); ?></div>
            <div class="db-contact-card__value"><?php echo wp_kses( $card[2], [ 'br' => [], 'a' => [ 'href' => [] ] ] ); ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php
}

/**
 * Standard pagination.
 */
function db_pagination(): void {
    echo paginate_links( [
        'prev_text' => db_icon( 'arrow-left' ) . ' ' . __( 'Previous', 'dante-burba' ),
        'next_text' => __( 'Next', 'dante-burba' ) . ' ' . db_icon( 'arrow-right' ),
        'type'      => 'plain',
        'before_page_number' => '<span>',
        'after_page_number'  => '</span>',
    ] );
}

/**
 * Is this a WooCommerce page?
 */
function db_is_woo_page(): bool {
    return db_is_woocommerce_active() && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() );
}
