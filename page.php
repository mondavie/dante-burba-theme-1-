<?php
/**
 * Generic Page Template
 *
 * @package dante-burba
 */

get_header();
?>

<div style="background:var(--db-surface); min-height:100vh; padding:128px 24px 80px;">
    <div style="max-width:900px; margin:0 auto;">

        <?php while ( have_posts() ) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <header style="margin-bottom:48px;">
                    <?php if ( has_post_thumbnail() ) : ?>
                    <div style="height:400px;overflow:hidden;margin-bottom:40px;">
                        <?php the_post_thumbnail( 'db-hero', [ 'style' => 'width:100%;height:100%;object-fit:cover;filter:grayscale(.4) brightness(.7);' ] ); ?>
                    </div>
                    <?php endif; ?>
                    <h1 style="font-family:var(--db-font-display);font-size:clamp(40px,6vw,80px);color:white;line-height:1;margin-bottom:16px;">
                        <?php the_title(); ?>
                    </h1>
                    <div style="height:2px;background:linear-gradient(to right,var(--db-orange),transparent);"></div>
                </header>

                <div class="db-page-content" style="color:#aaa;font-size:16px;line-height:1.8;">
                    <?php the_content(); ?>
                    <?php
                    wp_link_pages( [
                        'before' => '<div style="margin-top:32px;">' . __( 'Pages:', 'dante-burba' ),
                        'after'  => '</div>',
                    ] );
                    ?>
                </div>

            </article>

        <?php endwhile; ?>

    </div>
</div>

<?php get_footer();
