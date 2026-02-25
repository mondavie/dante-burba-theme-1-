<?php
/**
 * Meta Boxes — Dynamic content management for page templates.
 * Registers editable content blocks for Home, Contact, and other pages.
 *
 * @package dante-burba
 */

defined( 'ABSPATH' ) || exit;

/* ================================================================
   REGISTER META BOXES
================================================================ */
add_action( 'add_meta_boxes', 'db_register_meta_boxes' );
function db_register_meta_boxes(): void {

    // ── Home page blocks ──
    add_meta_box( 'db_hero_block',     __( 'Hero Section Override', 'dante-burba' ),     'db_metabox_hero',     'page', 'normal', 'high' );
    add_meta_box( 'db_services_block', __( 'Services Section',      'dante-burba' ),     'db_metabox_services', 'page', 'normal', 'high' );
    add_meta_box( 'db_tech_block',     __( 'Technology Section',    'dante-burba' ),     'db_metabox_tech',     'page', 'normal', 'default' );
    add_meta_box( 'db_about_block',    __( 'About / Legacy Section','dante-burba' ),     'db_metabox_about',    'page', 'normal', 'default' );
    add_meta_box( 'db_page_hero',      __( 'Page Hero Banner',      'dante-burba' ),     'db_metabox_page_hero','page', 'normal', 'high' );

    // ── Contact page ──
    add_meta_box( 'db_contact_details',__( 'Contact Details',       'dante-burba' ),     'db_metabox_contact',  'page', 'side',   'high' );
}

/* ================================================================
   SAVE META
================================================================ */
add_action( 'save_post', 'db_save_meta_boxes', 10, 2 );
function db_save_meta_boxes( int $post_id, WP_Post $post ): void {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;
    if ( ! isset( $_POST['db_meta_nonce'] ) ) return;
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['db_meta_nonce'] ) ), 'db_meta_save_' . $post_id ) ) return;

    $text_fields = [
        'db_hero_override_badge', 'db_hero_override_line1', 'db_hero_override_line2',
        'db_hero_override_cta_primary', 'db_hero_override_cta_secondary',
        'db_page_hero_title', 'db_page_hero_sub', 'db_page_hero_label',
        'db_contact_phone', 'db_contact_email', 'db_contact_address',
        'db_contact_hours_weekday', 'db_contact_hours_weekend',
        'db_contact_map_embed', 'db_tech_float_value', 'db_tech_float_label',
        'db_about_stat1_num', 'db_about_stat1_label', 'db_about_stat2_num', 'db_about_stat2_label',
        'db_about_quote', 'db_about_quote_sub',
    ];

    $textarea_fields = [
        'db_hero_override_sub', 'db_about_body_override',
        'db_tech_body',
    ];

    $image_fields = [
        'db_hero_override_image', 'db_page_hero_image', 'db_about_image_override',
        'db_tech_image',
    ];

    $repeater_fields = [
        'db_services_items', 'db_tech_items',
    ];

    foreach ( $text_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
        }
    }
    foreach ( $textarea_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_textarea_field( wp_unslash( $_POST[ $field ] ) ) );
        }
    }
    foreach ( $image_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, absint( $_POST[ $field ] ) );
        }
    }

    // ── Repeater: services ──
    if ( isset( $_POST['db_services_title'], $_POST['db_services_text'] ) ) {
        $titles = array_map( 'sanitize_text_field', wp_unslash( $_POST['db_services_title'] ) );
        $texts  = array_map( 'sanitize_textarea_field', wp_unslash( $_POST['db_services_text'] ) );
        $items  = [];
        foreach ( $titles as $i => $title ) {
            if ( ! empty( $title ) ) {
                $items[] = [ 'title' => $title, 'text' => $texts[ $i ] ?? '' ];
            }
        }
        update_post_meta( $post_id, 'db_services_items', $items );
    }

    // ── Repeater: tech items ──
    if ( isset( $_POST['db_tech_item_num'], $_POST['db_tech_item_name'], $_POST['db_tech_item_desc'] ) ) {
        $nums  = array_map( 'sanitize_text_field', wp_unslash( $_POST['db_tech_item_num'] ) );
        $names = array_map( 'sanitize_text_field', wp_unslash( $_POST['db_tech_item_name'] ) );
        $descs = array_map( 'sanitize_textarea_field', wp_unslash( $_POST['db_tech_item_desc'] ) );
        $items = [];
        foreach ( $nums as $i => $num ) {
            $items[] = [ 'num' => $num, 'name' => $names[$i] ?? '', 'desc' => $descs[$i] ?? '' ];
        }
        update_post_meta( $post_id, 'db_tech_items', $items );
    }
}

/* ================================================================
   NONCE HELPER (printed once at top of each box)
================================================================ */
function db_meta_nonce( int $post_id ): void {
    wp_nonce_field( 'db_meta_save_' . $post_id, 'db_meta_nonce' );
}

/* ================================================================
   METABOX: PAGE HERO BANNER (generic pages)
================================================================ */
function db_metabox_page_hero( WP_Post $post ): void {
    db_meta_nonce( $post->ID );
    $title  = get_post_meta( $post->ID, 'db_page_hero_title', true );
    $sub    = get_post_meta( $post->ID, 'db_page_hero_sub',   true );
    $label  = get_post_meta( $post->ID, 'db_page_hero_label', true );
    $img_id = absint( get_post_meta( $post->ID, 'db_page_hero_image', true ) );
    $img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'thumbnail' ) : '';
    ?>
    <p style="color:#777;font-size:12px;margin-bottom:12px;"><?php esc_html_e( 'Shown at the top of this page. Leave blank to use the default.', 'dante-burba' ); ?></p>
    <?php db_meta_field( 'db_page_hero_label', __( 'Section Label (small, above title)', 'dante-burba' ), $label ); ?>
    <?php db_meta_field( 'db_page_hero_title', __( 'Hero Title', 'dante-burba' ), $title ); ?>
    <?php db_meta_field( 'db_page_hero_sub',   __( 'Subtitle / Subheading', 'dante-burba' ), $sub, 'textarea' ); ?>
    <div class="db-image-field">
        <label style="font-weight:600;font-size:12px;display:block;margin-bottom:4px;"><?php esc_html_e( 'Background Image', 'dante-burba' ); ?></label>
        <?php if ( $img_url ) : ?>
            <img src="<?php echo esc_url( $img_url ); ?>" style="max-width:200px;display:block;margin-bottom:8px;">
        <?php endif; ?>
        <input type="hidden" name="db_page_hero_image" id="db_page_hero_image" value="<?php echo esc_attr( $img_id ); ?>">
        <button type="button" class="button db-media-upload" data-target="db_page_hero_image" data-preview="db_page_hero_preview"><?php esc_html_e( 'Select Image', 'dante-burba' ); ?></button>
        <?php if ( $img_id ) : ?><button type="button" class="button db-media-remove" data-target="db_page_hero_image"><?php esc_html_e( 'Remove', 'dante-burba' ); ?></button><?php endif; ?>
    </div>
    <?php
}

/* ================================================================
   METABOX: HERO OVERRIDE (for pages using the homepage template)
================================================================ */
function db_metabox_hero( WP_Post $post ): void {
    db_meta_nonce( $post->ID );
    $badge    = get_post_meta( $post->ID, 'db_hero_override_badge',        true );
    $line1    = get_post_meta( $post->ID, 'db_hero_override_line1',        true );
    $line2    = get_post_meta( $post->ID, 'db_hero_override_line2',        true );
    $sub      = get_post_meta( $post->ID, 'db_hero_override_sub',          true );
    $cta1     = get_post_meta( $post->ID, 'db_hero_override_cta_primary',  true );
    $cta2     = get_post_meta( $post->ID, 'db_hero_override_cta_secondary',true );
    $img_id   = absint( get_post_meta( $post->ID, 'db_hero_override_image', true ) );
    $img_url  = $img_id ? wp_get_attachment_image_url( $img_id, 'thumbnail' ) : '';
    ?>
    <p style="color:#777;font-size:12px;margin-bottom:12px;"><?php esc_html_e( 'Overrides the Customizer hero settings for this specific page.', 'dante-burba' ); ?></p>
    <?php db_meta_field( 'db_hero_override_badge',        __( 'Badge Text',           'dante-burba' ), $badge ); ?>
    <?php db_meta_field( 'db_hero_override_line1',        __( 'Headline Line 1',      'dante-burba' ), $line1 ); ?>
    <?php db_meta_field( 'db_hero_override_line2',        __( 'Headline Line 2 (accent)', 'dante-burba' ), $line2 ); ?>
    <?php db_meta_field( 'db_hero_override_sub',          __( 'Subheading',           'dante-burba' ), $sub, 'textarea' ); ?>
    <?php db_meta_field( 'db_hero_override_cta_primary',  __( 'Primary CTA Label',    'dante-burba' ), $cta1 ); ?>
    <?php db_meta_field( 'db_hero_override_cta_secondary',__( 'Secondary CTA Label',  'dante-burba' ), $cta2 ); ?>
    <div class="db-image-field">
        <label style="font-weight:600;font-size:12px;display:block;margin-bottom:4px;"><?php esc_html_e( 'Background Image', 'dante-burba' ); ?></label>
        <?php if ( $img_url ) : ?><img src="<?php echo esc_url( $img_url ); ?>" style="max-width:200px;display:block;margin-bottom:8px;"><?php endif; ?>
        <input type="hidden" name="db_hero_override_image" id="db_hero_override_image" value="<?php echo esc_attr( $img_id ); ?>">
        <button type="button" class="button db-media-upload" data-target="db_hero_override_image"><?php esc_html_e( 'Select Image', 'dante-burba' ); ?></button>
        <?php if ( $img_id ) : ?><button type="button" class="button db-media-remove" data-target="db_hero_override_image"><?php esc_html_e( 'Remove', 'dante-burba' ); ?></button><?php endif; ?>
    </div>
    <?php
}

/* ================================================================
   METABOX: SERVICES
================================================================ */
function db_metabox_services( WP_Post $post ): void {
    $defaults = [
        [ 'title' => 'DIESEL INJECTION',  'text' => 'Complete overhaul and calibration of rotary, inline, and common rail pumps.' ],
        [ 'title' => 'TURBOCHARGERS',     'text' => 'Advanced diagnostic and repair of variable geometry (VNT) and wastegate turbos.' ],
        [ 'title' => 'ELECTRONIC SYSTEMS','text' => 'Diagnostic solutions for modern EFI, GDI, and common rail electronics.' ],
    ];
    $items = get_post_meta( $post->ID, 'db_services_items', true );
    if ( empty( $items ) || ! is_array( $items ) ) $items = $defaults;
    ?>
    <div id="db-services-repeater">
        <?php foreach ( $items as $i => $item ) : ?>
        <div class="db-repeater-row" style="border:1px solid #ddd;padding:12px;margin-bottom:12px;background:#fafafa;">
            <label style="font-weight:600;font-size:12px;display:block;margin-bottom:4px;"><?php printf( esc_html__( 'Service %d Title', 'dante-burba' ), $i + 1 ); ?></label>
            <input type="text" name="db_services_title[]" value="<?php echo esc_attr( $item['title'] ); ?>" style="width:100%;margin-bottom:8px;">
            <label style="font-weight:600;font-size:12px;display:block;margin-bottom:4px;"><?php esc_html_e( 'Description', 'dante-burba' ); ?></label>
            <textarea name="db_services_text[]" rows="3" style="width:100%;"><?php echo esc_textarea( $item['text'] ); ?></textarea>
        </div>
        <?php endforeach; ?>
    </div>
    <?php
}

/* ================================================================
   METABOX: TECHNOLOGY SECTION
================================================================ */
function db_metabox_tech( WP_Post $post ): void {
    $body       = get_post_meta( $post->ID, 'db_tech_body',         true );
    $float_val  = get_post_meta( $post->ID, 'db_tech_float_value',  true );
    $float_lbl  = get_post_meta( $post->ID, 'db_tech_float_label',  true );
    $img_id     = absint( get_post_meta( $post->ID, 'db_tech_image', true ) );
    $img_url    = $img_id ? wp_get_attachment_image_url( $img_id, 'thumbnail' ) : '';
    $items_def  = [
        [ 'num' => '01', 'name' => 'Bosch EPS 815 & 708',       'desc' => 'Industry-standard benches for high-pressure common rail testing up to 2,500 bar.' ],
        [ 'num' => '02', 'name' => 'Hartridge Sabre CRi Master', 'desc' => 'Rapid, accurate testing for all makes of solenoid and piezo injectors.' ],
        [ 'num' => '03', 'name' => 'ISO Clean Room Environment', 'desc' => 'Particle-controlled assembly area ensuring contamination-free component handling.' ],
    ];
    $items = get_post_meta( $post->ID, 'db_tech_items', true );
    if ( empty( $items ) ) $items = $items_def;
    ?>
    <?php db_meta_field( 'db_tech_body', __( 'Section Body Text', 'dante-burba' ), $body, 'textarea' ); ?>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
        <?php db_meta_field( 'db_tech_float_value', __( 'Float Card Value (e.g. ±0.3)', 'dante-burba' ), $float_val ); ?>
        <?php db_meta_field( 'db_tech_float_label', __( 'Float Card Label', 'dante-burba' ), $float_lbl ); ?>
    </div>
    <label style="font-weight:600;font-size:12px;display:block;margin-bottom:8px;"><?php esc_html_e( 'Equipment Items', 'dante-burba' ); ?></label>
    <?php foreach ( $items as $i => $item ) : ?>
    <div style="display:grid;grid-template-columns:60px 1fr 2fr;gap:8px;margin-bottom:8px;align-items:start;">
        <input type="text" name="db_tech_item_num[]"  value="<?php echo esc_attr( $item['num'] ); ?>" placeholder="01" style="width:100%;">
        <input type="text" name="db_tech_item_name[]" value="<?php echo esc_attr( $item['name'] ); ?>" placeholder="Equipment name" style="width:100%;">
        <input type="text" name="db_tech_item_desc[]" value="<?php echo esc_attr( $item['desc'] ); ?>" placeholder="Description" style="width:100%;">
    </div>
    <?php endforeach; ?>
    <div class="db-image-field" style="margin-top:12px;">
        <label style="font-weight:600;font-size:12px;display:block;margin-bottom:4px;"><?php esc_html_e( 'Technology Section Image', 'dante-burba' ); ?></label>
        <?php if ( $img_url ) : ?><img src="<?php echo esc_url( $img_url ); ?>" style="max-width:200px;display:block;margin-bottom:8px;"><?php endif; ?>
        <input type="hidden" name="db_tech_image" id="db_tech_image" value="<?php echo esc_attr( $img_id ); ?>">
        <button type="button" class="button db-media-upload" data-target="db_tech_image"><?php esc_html_e( 'Select Image', 'dante-burba' ); ?></button>
        <?php if ( $img_id ) : ?><button type="button" class="button db-media-remove" data-target="db_tech_image"><?php esc_html_e( 'Remove', 'dante-burba' ); ?></button><?php endif; ?>
    </div>
    <?php
}

/* ================================================================
   METABOX: ABOUT SECTION
================================================================ */
function db_metabox_about( WP_Post $post ): void {
    $body       = get_post_meta( $post->ID, 'db_about_body_override',   true );
    $s1n        = get_post_meta( $post->ID, 'db_about_stat1_num',       true ) ?: '70+';
    $s1l        = get_post_meta( $post->ID, 'db_about_stat1_label',     true ) ?: 'Years Experience';
    $s2n        = get_post_meta( $post->ID, 'db_about_stat2_num',       true ) ?: '120k+';
    $s2l        = get_post_meta( $post->ID, 'db_about_stat2_label',     true ) ?: 'Systems Serviced';
    $quote      = get_post_meta( $post->ID, 'db_about_quote',           true );
    $quote_sub  = get_post_meta( $post->ID, 'db_about_quote_sub',       true );
    $img_id     = absint( get_post_meta( $post->ID, 'db_about_image_override', true ) );
    $img_url    = $img_id ? wp_get_attachment_image_url( $img_id, 'thumbnail' ) : '';
    ?>
    <?php db_meta_field( 'db_about_body_override', __( 'About Body Text', 'dante-burba' ), $body, 'textarea' ); ?>
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:8px;margin-bottom:12px;">
        <?php db_meta_field( 'db_about_stat1_num',   'Stat 1 Number', $s1n ); ?>
        <?php db_meta_field( 'db_about_stat1_label', 'Stat 1 Label',  $s1l ); ?>
        <?php db_meta_field( 'db_about_stat2_num',   'Stat 2 Number', $s2n ); ?>
        <?php db_meta_field( 'db_about_stat2_label', 'Stat 2 Label',  $s2l ); ?>
    </div>
    <?php db_meta_field( 'db_about_quote',     __( 'Pull Quote',     'dante-burba' ), $quote ); ?>
    <?php db_meta_field( 'db_about_quote_sub', __( 'Quote Sub-text', 'dante-burba' ), $quote_sub ); ?>
    <div class="db-image-field" style="margin-top:12px;">
        <label style="font-weight:600;font-size:12px;display:block;margin-bottom:4px;"><?php esc_html_e( 'About Section Image', 'dante-burba' ); ?></label>
        <?php if ( $img_url ) : ?><img src="<?php echo esc_url( $img_url ); ?>" style="max-width:200px;display:block;margin-bottom:8px;"><?php endif; ?>
        <input type="hidden" name="db_about_image_override" id="db_about_image_override" value="<?php echo esc_attr( $img_id ); ?>">
        <button type="button" class="button db-media-upload" data-target="db_about_image_override"><?php esc_html_e( 'Select Image', 'dante-burba' ); ?></button>
        <?php if ( $img_id ) : ?><button type="button" class="button db-media-remove" data-target="db_about_image_override"><?php esc_html_e( 'Remove', 'dante-burba' ); ?></button><?php endif; ?>
    </div>
    <?php
}

/* ================================================================
   METABOX: CONTACT DETAILS
================================================================ */
function db_metabox_contact( WP_Post $post ): void {
    db_meta_nonce( $post->ID );
    $phone    = get_post_meta( $post->ID, 'db_contact_phone',          true );
    $email    = get_post_meta( $post->ID, 'db_contact_email',          true );
    $address  = get_post_meta( $post->ID, 'db_contact_address',        true );
    $wkdy     = get_post_meta( $post->ID, 'db_contact_hours_weekday',  true );
    $wknd     = get_post_meta( $post->ID, 'db_contact_hours_weekend',  true );
    $map      = get_post_meta( $post->ID, 'db_contact_map_embed',      true );
    ?>
    <p style="color:#777;font-size:11px;margin-bottom:8px;"><?php esc_html_e( 'Overrides the Customizer contact settings for this page.', 'dante-burba' ); ?></p>
    <?php db_meta_field( 'db_contact_phone',         'Phone',          $phone ); ?>
    <?php db_meta_field( 'db_contact_email',         'Email',          $email ); ?>
    <?php db_meta_field( 'db_contact_address',       'Address',        $address ); ?>
    <?php db_meta_field( 'db_contact_hours_weekday', 'Weekday Hours',  $wkdy ); ?>
    <?php db_meta_field( 'db_contact_hours_weekend', 'Weekend Hours',  $wknd ); ?>
    <?php db_meta_field( 'db_contact_map_embed',     'Google Maps URL',$map ); ?>
    <?php
}

/* ================================================================
   FIELD HELPER
================================================================ */
function db_meta_field( string $name, string $label, $value, string $type = 'text' ): void {
    echo '<div style="margin-bottom:12px;">';
    echo '<label for="' . esc_attr( $name ) . '" style="font-weight:600;font-size:12px;display:block;margin-bottom:4px;">' . esc_html( $label ) . '</label>';
    if ( $type === 'textarea' ) {
        echo '<textarea id="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" rows="3" style="width:100%;">' . esc_textarea( (string)$value ) . '</textarea>';
    } else {
        echo '<input type="text" id="' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( (string)$value ) . '" style="width:100%;">';
    }
    echo '</div>';
}

/* ================================================================
   ADMIN: Media upload JS for meta boxes
================================================================ */
add_action( 'admin_enqueue_scripts', 'db_admin_scripts' );
function db_admin_scripts( string $hook ): void {
    if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) return;
    wp_enqueue_media();
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.db-media-upload').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var target = btn.getAttribute('data-target');
                var frame = wp.media({ title: 'Select Image', button: { text: 'Use Image' }, multiple: false });
                frame.on('select', function () {
                    var att = frame.state().get('selection').first().toJSON();
                    document.getElementById(target).value = att.id;
                    // Show preview
                    var prev = document.querySelector('input[name="'+target+'"]').previousElementSibling;
                    if (prev && prev.tagName === 'IMG') { prev.src = att.url; } else {
                        var img = document.createElement('img');
                        img.src = att.url; img.style.cssText = 'max-width:200px;display:block;margin-bottom:8px;';
                        document.getElementById(target).insertAdjacentElement('beforebegin', img);
                    }
                });
                frame.open();
            });
        });
        document.querySelectorAll('.db-media-remove').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var target = btn.getAttribute('data-target');
                document.getElementById(target).value = '';
            });
        });
    });
    </script>
    <?php
}
