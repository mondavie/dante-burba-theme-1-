<?php
/**
 * Index — main fallback template.
 * WordPress requires this file to exist.
 *
 * @package dante-burba
 */

get_header();
?>

<div style="background:var(--db-surface);min-height:100vh;padding:128px 24px 80px;">
    <div style="max-width:1280px;margin:0 auto;">

        <h1 style="font-family:var(--db-font-display);font-size:clamp(40px,6vw,80px);color:white;line-height:1;margin-bottom:48px;">
            <?php
            if ( is_archive() ) the_archive_title();
            elseif ( is_search() ) printf( esc_html__( 'Search: %s', 'dante-burba' ), get_search_query() );
            else esc_html_e( 'Latest Posts', 'dante-burba' );
            ?>
        </h1>

        <?php if ( have_posts() ) : ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:24px;">
            <?php while ( have_posts() ) : the_post(); ?>
            <article style="background:var(--db-surface-2);border:1px solid var(--db-border);padding:32px;">
                <p style="font-family:var(--db-font-mono);font-size:9px;color:var(--db-orange);text-transform:uppercase;letter-spacing:.2em;margin-bottom:12px;">
                    <?php the_date(); ?>
                </p>
                <h2 style="font-family:var(--db-font-display);font-size:28px;color:white;margin-bottom:12px;">
                    <a href="<?php the_permalink(); ?>" style="color:inherit;">
                        <?php the_title(); ?>
                    </a>
                </h2>
                <p style="color:#666;font-size:14px;line-height:1.7;margin-bottom:24px;">
                    <?php echo wp_kses_post( get_the_excerpt() ); ?>
                </p>
                <a href="<?php the_permalink(); ?>" class="btn-primary" style="display:inline-flex;padding:12px 24px;font-size:10px;">
                    <?php esc_html_e( 'Read More', 'dante-burba' ); ?>
                </a>
            </article>
            <?php endwhile; ?>
        </div>
        <div style="margin-top:48px;"><?php db_pagination(); ?></div>
        <?php else : ?>
        <p style="color:var(--db-muted);"><?php esc_html_e( 'No content found.', 'dante-burba' ); ?></p>
        <?php endif; ?>

    </div>
</div>

<?php get_footer();
