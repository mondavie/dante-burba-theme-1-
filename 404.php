<?php
/**
 * 404 Not Found Template
 *
 * @package dante-burba
 */

get_header();
?>

<div class="db-404">
    <div>
        <span class="db-404__num" aria-hidden="true">404</span>
        <h1 class="db-404__title"><?php esc_html_e( 'PAGE NOT FOUND', 'dante-burba' ); ?></h1>
        <p class="db-404__sub"><?php esc_html_e( 'The page you are looking for has been moved or does not exist.', 'dante-burba' ); ?></p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary">
            <?php esc_html_e( 'Return Home', 'dante-burba' ); ?>
            <?php echo db_icon( 'arrow-right' ); ?>
        </a>
    </div>
</div>

<?php get_footer();
