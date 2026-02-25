<?php
/**
 * Template Name: Contact Page
 * Template Post Type: page
 *
 * @package dante-burba
 */

get_header();

$post_id = get_the_ID();

// ── Meta overrides → Customizer fallbacks ──
$phone   = get_post_meta( $post_id, 'db_contact_phone',          true ) ?: get_theme_mod( 'db_contact_phone',         '+254 7XX XXX XXX' );
$email   = get_post_meta( $post_id, 'db_contact_email',          true ) ?: get_theme_mod( 'db_contact_email',         'info@danteburba.com' );
$addr1   = get_theme_mod( 'db_contact_address_line1', 'Nairobi, Kenya' );
$addr2   = get_theme_mod( 'db_contact_address_line2', 'Industrial Area' );
$address = get_post_meta( $post_id, 'db_contact_address', true ) ?: "$addr1, $addr2";
$wkdy    = get_post_meta( $post_id, 'db_contact_hours_weekday',  true ) ?: get_theme_mod( 'db_contact_hours_weekday', 'Mon – Fri: 8am – 5pm' );
$wknd    = get_post_meta( $post_id, 'db_contact_hours_weekend',  true ) ?: get_theme_mod( 'db_contact_hours_weekend', 'Sat: 8am – 12pm' );
$map_url = get_post_meta( $post_id, 'db_contact_map_embed',      true ) ?: get_theme_mod( 'db_contact_map_embed',     '' );

// Page hero
$hero_title = get_post_meta( $post_id, 'db_page_hero_title', true ) ?: get_the_title();
$hero_sub   = get_post_meta( $post_id, 'db_page_hero_sub',   true ) ?: __( 'We are here to help. Reach out or visit our facility.', 'dante-burba' );
$hero_label = get_post_meta( $post_id, 'db_page_hero_label', true ) ?: __( 'Get In Touch', 'dante-burba' );
$hero_img   = absint( get_post_meta( $post_id, 'db_page_hero_image', true ) );
$hero_img_url = $hero_img ? wp_get_attachment_image_url( $hero_img, 'db-hero' ) : '';
?>

<!-- ── Page Hero ── -->
<section class="db-page-hero" <?php if ( $hero_img_url ) echo 'style="--hero-img:url(' . esc_url( $hero_img_url ) . ')"'; ?>>
    <div class="db-page-hero__inner reveal active">
        <p class="db-page-hero__label">// <?php echo esc_html( $hero_label ); ?></p>
        <h1 class="db-page-hero__title"><?php echo esc_html( $hero_title ); ?></h1>
        <p class="db-page-hero__sub"><?php echo esc_html( $hero_sub ); ?></p>
    </div>
</section>

<!-- ── Contact Body ── -->
<section class="db-contact-page" style="background:var(--db-shop-bg);padding:var(--db-section-pad) 24px;">
    <div style="max-width:var(--db-container-width);margin:0 auto;">

        <div class="db-contact-page__grid">

            <!-- Left: Info Cards -->
            <div class="reveal">
                <p style="font-family:var(--db-font-mono);font-size:10px;color:var(--db-orange);text-transform:uppercase;letter-spacing:.25em;margin-bottom:32px;">
                    <?php esc_html_e( '// Contact Details', 'dante-burba' ); ?>
                </p>

                <?php
                $cards = [
                    [ 'map-pin', __( 'Location', 'dante-burba' ),  nl2br( esc_html( $address ) ) ],
                    [ 'phone',   __( 'Hotline',  'dante-burba' ),  esc_html( $phone ) . '<br>' . esc_html( $email ) ],
                    [ 'clock',   __( 'Hours',    'dante-burba' ),  esc_html( $wkdy ) . '<br>' . esc_html( $wknd ) ],
                ];
                foreach ( $cards as $card ) : ?>
                <div class="db-contact-card" style="margin-bottom:20px;">
                    <div class="db-contact-card__icon"><?php echo db_icon( $card[0] ); ?></div>
                    <div class="db-contact-card__label"><?php echo esc_html( $card[1] ); ?></div>
                    <div class="db-contact-card__value"><?php echo wp_kses_post( $card[2] ); ?></div>
                </div>
                <?php endforeach; ?>

                <!-- Page content (editor blocks) -->
                <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
                    the_content();
                endwhile; endif; ?>
            </div>

            <!-- Right: Contact Form -->
            <div class="reveal" style="transition-delay:150ms;">
                <div style="background:white;border:1px solid #e5e7eb;padding:40px 36px;">
                    <p style="font-family:var(--db-font-mono);font-size:10px;color:var(--db-orange);text-transform:uppercase;letter-spacing:.25em;margin-bottom:8px;"><?php esc_html_e( 'Send a Message', 'dante-burba' ); ?></p>
                    <h2 style="font-family:var(--db-font-display);font-size:32px;color:#111;margin-bottom:32px;line-height:1;"><?php esc_html_e( 'REQUEST A CALLBACK', 'dante-burba' ); ?></h2>

                    <?php if ( shortcode_exists( 'contact-form-7' ) || shortcode_exists( 'wpforms' ) || shortcode_exists( 'gravityforms' ) ) : ?>
                        <?php /* Drop your form shortcode here, e.g.: echo do_shortcode('[contact-form-7 id="123"]'); */ ?>
                    <?php endif; ?>

                    <!-- Native HTML5 form (fallback / default) -->
                    <form class="db-contact-form" id="db-contact-form" novalidate>
                        <div class="db-contact-form__row">
                            <div class="db-contact-form__field">
                                <label><?php esc_html_e( 'First Name', 'dante-burba' ); ?></label>
                                <input type="text" name="first_name" required placeholder="John">
                            </div>
                            <div class="db-contact-form__field">
                                <label><?php esc_html_e( 'Last Name', 'dante-burba' ); ?></label>
                                <input type="text" name="last_name" required placeholder="Doe">
                            </div>
                        </div>
                        <div class="db-contact-form__field">
                            <label><?php esc_html_e( 'Email Address', 'dante-burba' ); ?></label>
                            <input type="email" name="email" required placeholder="you@company.com">
                        </div>
                        <div class="db-contact-form__field">
                            <label><?php esc_html_e( 'Phone Number', 'dante-burba' ); ?></label>
                            <input type="tel" name="phone" placeholder="+254 7XX XXX XXX">
                        </div>
                        <div class="db-contact-form__field">
                            <label><?php esc_html_e( 'Service Required', 'dante-burba' ); ?></label>
                            <select name="service">
                                <option value=""><?php esc_html_e( 'Select a service…', 'dante-burba' ); ?></option>
                                <option><?php esc_html_e( 'Diesel Injection Calibration', 'dante-burba' ); ?></option>
                                <option><?php esc_html_e( 'Turbocharger Repair', 'dante-burba' ); ?></option>
                                <option><?php esc_html_e( 'Electronic Diagnostics', 'dante-burba' ); ?></option>
                                <option><?php esc_html_e( 'Parts / Lubricants', 'dante-burba' ); ?></option>
                                <option><?php esc_html_e( 'General Inquiry', 'dante-burba' ); ?></option>
                            </select>
                        </div>
                        <div class="db-contact-form__field">
                            <label><?php esc_html_e( 'Message', 'dante-burba' ); ?></label>
                            <textarea name="message" rows="5" placeholder="<?php esc_attr_e( 'Describe your vehicle and issue…', 'dante-burba' ); ?>"></textarea>
                        </div>
                        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;margin-top:8px;" id="db-contact-submit">
                            <?php echo esc_html( get_theme_mod( 'db_contact_cta_label', 'Request a Callback' ) ); ?>
                            <?php echo db_icon( 'arrow-right' ); ?>
                        </button>
                    </form>
                </div>
            </div>

        </div>

        <!-- Map Embed -->
        <?php if ( $map_url ) : ?>
        <div style="margin-top:64px;reveal">
            <iframe src="<?php echo esc_url( $map_url ); ?>"
                    width="100%" height="400"
                    style="border:0;display:block;filter:grayscale(.3);" allowfullscreen loading="lazy">
            </iframe>
        </div>
        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>
