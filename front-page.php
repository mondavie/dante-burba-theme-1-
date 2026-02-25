<?php
/**
 * Front Page Template
 * Reads from: 1) Page meta-boxes  2) Customizer  3) Hard defaults
 *
 * @package dante-burba
 */

get_header();

$post_id = get_queried_object_id();

// ── Hero: meta → customizer → default ──
$hero_badge  = get_post_meta( $post_id, 'db_hero_override_badge',         true ) ?: get_theme_mod( 'db_hero_badge',         "Est. 1954 · East Africa's Premier Workshop" );
$hero_line1  = get_post_meta( $post_id, 'db_hero_override_line1',         true ) ?: get_theme_mod( 'db_hero_line1',         'PRECISION' );
$hero_line2  = get_post_meta( $post_id, 'db_hero_override_line2',         true ) ?: get_theme_mod( 'db_hero_line2',         'ENGINEERED.' );
$hero_sub    = get_post_meta( $post_id, 'db_hero_override_sub',           true ) ?: get_theme_mod( 'db_hero_sub',           'Seven decades of diesel mastery, now augmented with future-ready diagnostics. The gold standard across East Africa.' );
$hero_cta1   = get_post_meta( $post_id, 'db_hero_override_cta_primary',   true ) ?: get_theme_mod( 'db_hero_cta_primary',   'Book Diagnostic' );
$hero_cta2   = get_post_meta( $post_id, 'db_hero_override_cta_secondary', true ) ?: get_theme_mod( 'db_hero_cta_secondary', 'Browse Shop' );
$hero_img    = db_get_hero_image_url( $post_id );
$hero_opacity = absint( get_theme_mod( 'db_hero_img_opacity', 20 ) ) / 100;

// ── Services meta ──
$services_items_meta = get_post_meta( $post_id, 'db_services_items', true );
$services_label  = get_theme_mod( 'db_services_label',   'Core Capabilities' );
$services_title1 = get_theme_mod( 'db_services_title_1', 'PRECISION IN' );
$services_title2 = get_theme_mod( 'db_services_title_2', 'EVERY BURST.' );
$services_intro  = get_theme_mod( 'db_services_intro',   'Our workshop utilizes Bosch EPS 815 and 708 test benches, ensuring your fuel injection system meets exact OEM specifications to the micron.' );
$services_icons  = [ 'settings-2', 'wind', 'cpu' ];

// Merge meta into default service data
$default_services = [
    [ 'title' => get_theme_mod( 'db_service_1_title', 'DIESEL INJECTION'  ), 'text' => get_theme_mod( 'db_service_1_text', 'Complete overhaul and calibration of rotary, inline, and common rail pumps. We specialize in EUI, EUP, and HEUI systems for all major brands.' ) ],
    [ 'title' => get_theme_mod( 'db_service_2_title', 'TURBOCHARGERS'     ), 'text' => get_theme_mod( 'db_service_2_text', 'Advanced diagnostic and repair of variable geometry (VNT) and wastegate turbos. High-speed core balancing up to 200,000 RPM.' ) ],
    [ 'title' => get_theme_mod( 'db_service_3_title', 'ELECTRONIC SYSTEMS'), 'text' => get_theme_mod( 'db_service_3_text', 'Diagnostic solutions for modern EFI, GDI, and common rail electronics. Hartridge Sabre CRi Master for solenoid and piezo injectors.' ) ],
];
$services = ( is_array( $services_items_meta ) && ! empty( $services_items_meta ) ) ? $services_items_meta : $default_services;

// ── Tech section ──
$tech_items_meta = get_post_meta( $post_id, 'db_tech_items', true );
$tech_body       = get_post_meta( $post_id, 'db_tech_body', true ) ?: 'Accuracy in diesel systems is measured in microns. We maintain the most advanced clean-room environment in the region, featuring world-leading testing apparatus.';
$tech_float_val  = get_post_meta( $post_id, 'db_tech_float_value', true ) ?: '±0.3';
$tech_float_lbl  = get_post_meta( $post_id, 'db_tech_float_label', true ) ?: 'Calibration Accuracy';
$tech_img_id     = absint( get_post_meta( $post_id, 'db_tech_image', true ) );
$tech_img_url    = $tech_img_id ? wp_get_attachment_image_url( $tech_img_id, 'db-portrait' ) : 'https://images.unsplash.com/photo-1581092160562-40aa08e78837?auto=format&fit=crop&q=80&w=1000';
$default_tech_items = [
    [ 'num' => '01', 'name' => 'Bosch EPS 815 & 708',        'desc' => 'Industry-standard benches for high-pressure common rail testing up to 2,500 bar.' ],
    [ 'num' => '02', 'name' => 'Hartridge Sabre CRi Master',  'desc' => 'Rapid, accurate testing for all makes of solenoid and piezo injectors.' ],
    [ 'num' => '03', 'name' => 'ISO Clean Room Environment',  'desc' => 'Particle-controlled assembly area ensuring contamination-free component handling.' ],
];
$tech_items = ( is_array( $tech_items_meta ) && ! empty( $tech_items_meta ) ) ? $tech_items_meta : $default_tech_items;

// ── About section ──
$about_label  = get_theme_mod( 'db_about_label',   'The 1954 Legacy' );
$about_t1     = get_theme_mod( 'db_about_title_1', 'BUILT ON RELIABILITY.' );
$about_t2     = get_theme_mod( 'db_about_title_2', 'SUSTAINED BY MASTERY.' );
$about_body   = get_post_meta( $post_id, 'db_about_body_override', true ) ?: get_theme_mod( 'db_about_body', 'Dante Burba Diesel Injection Ltd was founded with a singular vision: to bring world-class precision engineering to East Africa. We are an AA-appointed workshop and authorized service partner for global leaders including Bosch and Delphi.' );
$about_s1n    = get_post_meta( $post_id, 'db_about_stat1_num',   true ) ?: '70+';
$about_s1l    = get_post_meta( $post_id, 'db_about_stat1_label', true ) ?: 'Years Experience';
$about_s2n    = get_post_meta( $post_id, 'db_about_stat2_num',   true ) ?: '120k+';
$about_s2l    = get_post_meta( $post_id, 'db_about_stat2_label', true ) ?: 'Systems Serviced';
$about_quote  = get_post_meta( $post_id, 'db_about_quote',      true ) ?: get_theme_mod( 'db_about_quote',    "We don't just repair," );
$about_qsub   = get_post_meta( $post_id, 'db_about_quote_sub',  true ) ?: get_theme_mod( 'db_about_quote_sub','We restore engineering intent.' );
$about_test   = get_theme_mod( 'db_about_testimonial',        '"The only place in Nairobi I trust for my fleet\'s injection systems. Precision is their religion."' );
$about_auth   = get_theme_mod( 'db_about_testimonial_author', '— James M., Transport Director' );
$about_img_id = absint( get_post_meta( $post_id, 'db_about_image_override', true ) ) ?: absint( get_theme_mod( 'db_about_image' ) );
$about_img    = $about_img_id ? wp_get_attachment_image_url( $about_img_id, 'db-portrait' ) : 'https://images.unsplash.com/photo-1486006920555-c77dcf18193c?auto=format&fit=crop&q=80&w=1000';

// ── Contact section ──
$contact_title = get_theme_mod( 'db_contact_title', 'PERFORMANCE?' );
?>

<!-- ======================================================  HERO  -->
<section class="db-hero" id="home" aria-labelledby="hero-headline">
    <div class="db-hero__bg">
        <img src="<?php echo esc_url( $hero_img ); ?>" alt="" role="presentation"
             loading="eager" fetchpriority="high"
             style="opacity:<?php echo esc_attr( $hero_opacity ); ?>">
    </div>
    <div class="db-hero__diag" aria-hidden="true"></div>
    <div class="db-hero__ring db-hero__ring--1" aria-hidden="true"></div>
    <div class="db-hero__ring db-hero__ring--2" aria-hidden="true"></div>

    <div class="db-hero__content reveal active">
        <div class="db-hero__badge">
            <span class="db-hero__badge-dot" aria-hidden="true"></span>
            <span class="db-hero__badge-text"><?php echo esc_html( $hero_badge ); ?></span>
        </div>
        <h1 class="db-hero__headline" id="hero-headline">
            <?php echo esc_html( $hero_line1 ); ?><br>
            <span><?php echo esc_html( $hero_line2 ); ?></span>
        </h1>
        <p class="db-hero__sub"><?php echo esc_html( $hero_sub ); ?></p>
        <div class="db-hero__actions">
            <a href="#contact" class="btn-primary">
                <?php echo esc_html( $hero_cta1 ); ?>
                <?php echo db_icon( 'arrow-right' ); ?>
            </a>
            <?php if ( db_is_woocommerce_active() && function_exists( 'wc_get_page_id' ) ) : ?>
            <a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="btn-ghost">
                <?php echo esc_html( $hero_cta2 ); ?>
            </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="db-hero__scroll" aria-hidden="true">
        <span>Scroll</span>
        <div class="db-hero__scroll-line"></div>
    </div>
</section>

<!-- ======================================================  STATS  -->
<?php db_render_stats_strip(); ?>

<!-- ======================================================  MARQUEE  -->
<?php db_render_marquee(); ?>

<!-- ======================================================  SERVICES  -->
<section id="services" class="db-services" aria-labelledby="services-title">
    <div class="db-services__inner">
        <div class="db-services__header reveal">
            <div>
                <p class="db-services__label">// <?php echo esc_html( $services_label ); ?></p>
                <h2 class="db-services__title" id="services-title">
                    <?php echo esc_html( $services_title1 ); ?><br>
                    <span><?php echo esc_html( $services_title2 ); ?></span>
                </h2>
            </div>
            <p class="db-services__desc"><?php echo esc_html( $services_intro ); ?></p>
        </div>
        <div class="tech-line" style="margin-bottom:64px;" aria-hidden="true"></div>
        <div class="db-services__grid">
            <?php foreach ( $services as $i => $svc ) :
                $delay = $i * 100;
            ?>
            <article class="db-service-card reveal" style="transition-delay:<?php echo esc_attr($delay); ?>ms;">
                <div class="db-service-card__icon" aria-hidden="true">
                    <?php echo db_icon( $services_icons[ $i ] ?? 'settings-2' ); ?>
                </div>
                <p class="db-service-card__num">0<?php echo esc_html( $i + 1 ); ?></p>
                <h3 class="db-service-card__title"><?php echo esc_html( $svc['title'] ); ?></h3>
                <p class="db-service-card__text"><?php echo esc_html( $svc['text'] ); ?></p>
                <span class="db-service-card__ghost" aria-hidden="true">0<?php echo esc_html( $i + 1 ); ?></span>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ======================================================  TECHNOLOGY  -->
<section id="tech" class="db-tech" aria-labelledby="tech-title">
    <div class="db-tech__inner">
        <div class="db-tech__image reveal">
            <div class="db-tech__img-wrap">
                <img src="<?php echo esc_url( $tech_img_url ); ?>"
                     alt="<?php esc_attr_e( 'Precision machinery', 'dante-burba' ); ?>"
                     loading="lazy">
                <div class="db-tech__img-grid" aria-hidden="true"></div>
                <div class="db-tech__img-corner-tl" aria-hidden="true"></div>
                <div class="db-tech__img-corner-br" aria-hidden="true"></div>
            </div>
            <div class="db-tech__float-card">
                <p class="db-tech__float-label"><?php echo esc_html( $tech_float_lbl ); ?></p>
                <p class="db-tech__float-val"><?php echo esc_html( $tech_float_val ); ?> <span>μL</span></p>
            </div>
        </div>
        <div class="db-tech__content reveal" style="transition-delay:150ms;">
            <p class="db-tech__label">// <?php esc_html_e( 'Precision Infrastructure', 'dante-burba' ); ?></p>
            <h2 class="db-tech__title" id="tech-title">
                <?php esc_html_e( 'THE LAB:', 'dante-burba' ); ?><br>
                <?php esc_html_e( 'FUTURE-PROOF', 'dante-burba' ); ?><br>
                <span><?php esc_html_e( 'DIAGNOSTICS.', 'dante-burba' ); ?></span>
            </h2>
            <p class="db-tech__body"><?php echo esc_html( $tech_body ); ?></p>
            <div class="db-tech__items">
                <?php foreach ( $tech_items as $j => $item ) : ?>
                    <div class="db-tech__item" <?php if ( $j > 0 ) echo 'style="padding-top:20px;"'; ?>>
                        <span class="db-tech__item-num"><?php echo esc_html( $item['num'] ); ?></span>
                        <div>
                            <p class="db-tech__item-name"><?php echo esc_html( $item['name'] ); ?></p>
                            <p class="db-tech__item-desc"><?php echo esc_html( $item['desc'] ); ?></p>
                        </div>
                    </div>
                    <?php if ( $j < count( $tech_items ) - 1 ) : ?>
                    <div class="tech-line" style="margin:20px 0;" aria-hidden="true"></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================  ABOUT  -->
<section id="about" class="db-about" aria-labelledby="about-title">
    <div class="db-about__bg-num" aria-hidden="true"><?php echo esc_html( get_theme_mod( 'db_est_year', '1954' ) ); ?></div>
    <div class="db-about__inner">
        <div class="db-about__content reveal">
            <p class="db-about__label">// <?php echo esc_html( $about_label ); ?></p>
            <h2 class="db-about__title" id="about-title">
                <?php echo esc_html( $about_t1 ); ?><br>
                <span><?php echo esc_html( $about_t2 ); ?></span>
            </h2>
            <p class="db-about__body"><?php echo esc_html( $about_body ); ?></p>
            <div class="db-about__stats">
                <div class="db-stat-card"><span class="db-stat-card__num"><?php echo esc_html( $about_s1n ); ?></span><span class="db-stat-card__label"><?php echo esc_html( $about_s1l ); ?></span></div>
                <div class="db-stat-card"><span class="db-stat-card__num"><?php echo esc_html( $about_s2n ); ?></span><span class="db-stat-card__label"><?php echo esc_html( $about_s2l ); ?></span></div>
            </div>
            <blockquote class="db-testimonial">
                <p class="db-testimonial__quote"><?php echo esc_html( $about_test ); ?></p>
                <cite class="db-testimonial__author"><?php echo esc_html( $about_auth ); ?></cite>
            </blockquote>
        </div>
        <div class="db-about__image reveal" style="transition-delay:100ms;">
            <div class="db-about__img-wrap">
                <img src="<?php echo esc_url( $about_img ); ?>"
                     alt="<?php esc_attr_e( 'Dante Burba workshop', 'dante-burba' ); ?>"
                     loading="lazy">
                <div class="db-about__corner-tr" aria-hidden="true"></div>
                <div class="db-about__corner-bl" aria-hidden="true"></div>
            </div>
            <div class="db-about__quote-card">
                <em>"<?php echo esc_html( $about_quote ); ?>"</em>
                <span><?php echo esc_html( $about_qsub ); ?></span>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================  CONTACT  -->
<section id="contact" class="db-contact" aria-labelledby="contact-title">
    <div class="db-contact__inner reveal">
        <p class="db-contact__label">// <?php esc_html_e( 'Get In Touch', 'dante-burba' ); ?></p>
        <h2 class="db-contact__title" id="contact-title">
            <?php esc_html_e( 'READY FOR', 'dante-burba' ); ?><br>
            <?php esc_html_e( 'PEAK', 'dante-burba' ); ?><br>
            <span><?php echo esc_html( $contact_title ); ?></span>
        </h2>
        <p class="db-contact__sub">
            <?php esc_html_e( 'Visit our state-of-the-art facility in Nairobi for a comprehensive diagnostic of your fuel or turbo system.', 'dante-burba' ); ?>
        </p>
        <?php db_render_contact_cards(); ?>
        <a href="<?php
            $contact_page = get_page_by_path( 'contact' );
            echo esc_url( $contact_page ? get_permalink( $contact_page->ID ) : '#contact' );
        ?>" class="btn-primary" style="min-width:240px; display:inline-flex;">
            <?php echo esc_html( get_theme_mod( 'db_contact_cta_label', 'Request a Callback' ) ); ?>
            <?php echo db_icon( 'arrow-right' ); ?>
        </a>
    </div>
</section>

<?php get_footer(); ?>
