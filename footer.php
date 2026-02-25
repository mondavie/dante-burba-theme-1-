</div><!-- #main-content -->

<!-- ======================================================
     FOOTER
====================================================== -->
<footer class="db-footer">
    <div class="db-footer__inner">
        <div class="db-footer__top">

            <!-- Logo -->
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="db-logo">
                <div class="db-logo-mark" style="background:#27272a;" onmouseenter="this.style.background='var(--db-orange)'" onmouseleave="this.style.background='#27272a'">DB</div>
                <div class="db-logo-text">
                    <span class="db-logo-name"><?php bloginfo( 'name' ); ?></span>
                    <span class="db-logo-sub" style="color:#444;"><?php echo esc_html( get_theme_mod( 'db_tagline', 'Diesel Injection Ltd.' ) ); ?></span>
                </div>
            </a>

            <!-- Social Icons -->
            <div class="db-footer__social">
                <?php
                $socials = [
                    'instagram' => db_icon( 'instagram' ),
                    'linkedin'  => db_icon( 'linkedin' ),
                    'facebook'  => db_icon( 'facebook' ),
                ];
                foreach ( $socials as $platform => $icon ) :
                    $url = get_theme_mod( "db_social_{$platform}", '#' );
                    if ( $url && '#' !== $url ) :
                ?>
                    <a href="<?php echo esc_url( $url ); ?>"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="<?php echo esc_attr( ucfirst( $platform ) ); ?>">
                        <?php echo $icon; ?>
                    </a>
                <?php
                    endif;
                endforeach;
                ?>
            </div>

        </div>

        <div class="db-footer__divider"></div>

        <p class="db-footer__copy">
            &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?>
            <?php bloginfo( 'name' ); ?>.
            <?php esc_html_e( 'All Rights Reserved.', 'dante-burba' ); ?>
            &nbsp;·&nbsp;
            <?php esc_html_e( 'Nairobi, Kenya', 'dante-burba' ); ?>
        </p>

        <?php if ( has_nav_menu( 'footer' ) ) : ?>
        <nav aria-label="<?php esc_attr_e( 'Footer', 'dante-burba' ); ?>" style="margin-top:16px;">
            <?php wp_nav_menu( [
                'theme_location' => 'footer',
                'container'      => false,
                'menu_class'     => 'db-footer-nav',
                'depth'          => 1,
            ] ); ?>
        </nav>
        <?php endif; ?>

    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
