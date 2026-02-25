<?php
/**
 * Template Name: Full Width Page
 * Template Post Type: page
 *
 * No sidebar, full content width. Good for Elementor pages.
 *
 * @package dante-burba
 */

get_header();

$post_id    = get_the_ID();
$hero_title = get_post_meta( $post_id, 'db_page_hero_title', true ) ?: get_the_title();
$hero_sub   = get_post_meta( $post_id, 'db_page_hero_sub',   true );
$hero_label = get_post_meta( $post_id, 'db_page_hero_label', true );
$hero_img   = absint( get_post_meta( $post_id, 'db_page_hero_image', true ) );
$hero_img_url = $hero_img ? wp_get_attachment_image_url( $hero_img, 'db-hero' ) : '';
?>

<?php if ( $hero_title ) : ?>
<section class="db-page-hero" <?php if ( $hero_img_url ) echo 'style="--hero-img:url(' . esc_url($hero_img_url) . ')"'; ?>>
    <div class="db-page-hero__inner reveal active">
        <?php if ( $hero_label ) : ?>
        <p class="db-page-hero__label">// <?php echo esc_html( $hero_label ); ?></p>
        <?php endif; ?>
        <h1 class="db-page-hero__title"><?php echo esc_html( $hero_title ); ?></h1>
        <?php if ( $hero_sub ) : ?>
        <p class="db-page-hero__sub"><?php echo esc_html( $hero_sub ); ?></p>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<div style="background:var(--db-surface);min-height:60vh;padding:64px 24px 80px;">
    <div style="max-width:var(--db-container-width);margin:0 auto;">
        <?php while ( have_posts() ) : the_post(); ?>
        <article class="db-page-content">
            <?php the_content(); ?>
            <?php wp_link_pages(); ?>
        </article>
        <?php endwhile; ?>
    </div>
</div>

<?php get_footer(); ?>
