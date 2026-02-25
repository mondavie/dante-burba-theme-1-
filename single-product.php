<?php
/**
 * The Template for displaying all single products
 * Overrides the default WooCommerce single product layout.
 *
 * @package dante-burba
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>

<div class="db-shop-wrap">
    <div class="db-shop-inner">

        <!-- Back Navigation -->
        <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" 
           style="display:inline-flex; align-items:center; gap:8px; color:var(--db-muted); font-weight:600; text-transform:uppercase; letter-spacing:0.15em; font-size:11px; margin-bottom:40px; transition:color 0.2s;" 
           onmouseover="this.style.color='var(--db-orange)'" 
           onmouseout="this.style.color='var(--db-muted)'">
            <?php echo db_icon( 'arrow-left' ); ?> <?php esc_html_e( 'Back to Catalog', 'dante-burba' ); ?>
        </a>

        <?php while ( have_posts() ) : the_post(); global $product; ?>
            
            <?php do_action( 'woocommerce_before_single_product' ); ?>

            <!-- Main Product Container -->
            <div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'db-single-product', $product ); ?> 
                 style="background:white; border:1px solid #E5E7EB; box-shadow:0 20px 60px rgba(0,0,0,0.05); display:grid; grid-template-columns:1fr; overflow:hidden;">
                
                <style>
                    @media (min-width: 1024px) {
                        .db-single-product { grid-template-columns: 1fr 1fr !important; }
                    }
                    /* WooCommerce specific quantity/button layout adjustments */
                    .db-single-cart form.cart { display: flex; gap: 16px; align-items: center; flex-wrap: wrap; }
                    .db-single-cart div.quantity { display: flex; align-items: center; border: 1px solid #E5E7EB; height: 48px; background: #f4f4f5; }
                    .db-single-cart input.qty { width: 56px; text-align: center; border: none; background: transparent; font-family: var(--db-font-mono); font-size: 14px; font-weight: 700; outline: none; }
                </style>

                <!-- Product Image Area -->
                <div class="db-single-img-wrap" style="background:#F5F5F5; padding:40px; position:relative; display:flex; align-items:center; justify-content:center; min-height:400px;">
                    <?php 
                    $image_id  = $product->get_image_id();
                    $image_url = wp_get_attachment_image_url( $image_id, 'full' );
                    ?>
                    <img src="<?php echo esc_url( $image_url ? $image_url : wc_placeholder_img_src() ); ?>" 
                         alt="<?php echo esc_attr( $product->get_name() ); ?>" 
                         style="max-width:100%; max-height:450px; object-fit:contain;">
                    
                    <!-- Category Badge -->
                    <?php
                    $cat_terms = get_the_terms( $product->get_id(), 'product_cat' );
                    $badge_label = ( $cat_terms && ! is_wp_error( $cat_terms ) ) ? $cat_terms[0]->name : __( 'Part', 'dante-burba' );
                    ?>
                    <div class="db-single-badge" style="position:absolute; top:24px; left:24px; background:var(--db-orange); color:white; font-family:var(--db-font-mono); font-size:10px; text-transform:uppercase; letter-spacing:0.2em; padding:6px 16px;">
                        <?php echo esc_html( $badge_label ); ?>
                    </div>
                    
                    <!-- Corner Accents -->
                    <div style="position:absolute; top:24px; right:24px; width:32px; height:32px; border-top:1px solid var(--db-orange); border-right:1px solid var(--db-orange);"></div>
                    <div style="position:absolute; bottom:24px; left:24px; width:32px; height:32px; border-bottom:1px solid var(--db-orange); border-left:1px solid var(--db-orange);"></div>
                </div>

                <!-- Product Details Area -->
                <div class="db-single-details" style="padding:48px; display:flex; flex-direction:column;">
                    
                    <h1 class="db-single-title" style="font-family:var(--db-font-display); font-size:clamp(36px, 5vw, 56px); color:#111; line-height:1.1; margin-bottom:16px;">
                        <?php the_title(); ?>
                    </h1>
                    
                    <div class="db-single-price" style="font-family:var(--db-font-display); font-size:40px; color:#111; margin-bottom:40px;">
                        <?php echo wp_kses_post( $product->get_price_html() ); ?>
                    </div>
                    
                    <!-- Native WooCommerce Add to Cart Form -->
                    <!-- This automatically hooks into your style.css buttons -->
                    <div class="db-single-cart" style="margin-bottom:56px;">
                        <?php woocommerce_template_single_add_to_cart(); ?>
                    </div>

                    <!-- Custom Tab Links -->
                    <div class="db-ext-tabs" style="display:flex; gap:32px; border-bottom:1px solid #E5E7EB; margin-bottom:32px; overflow-x:auto; -ms-overflow-style:none; scrollbar-width:none;">
                        <button class="db-ext-tab active" onclick="dbSwitchTab('desc')" style="padding-bottom:16px; font-family:var(--db-font-mono); font-size:10px; font-weight:500; text-transform:uppercase; letter-spacing:0.2em; color:var(--db-orange); border-bottom:2px solid var(--db-orange); transition:all 0.2s; white-space:nowrap;">
                            <?php esc_html_e( 'Description', 'dante-burba' ); ?>
                        </button>
                        
                        <?php if ( $product->has_attributes() ) : ?>
                            <button class="db-ext-tab" onclick="dbSwitchTab('specs')" style="padding-bottom:16px; font-family:var(--db-font-mono); font-size:10px; font-weight:500; text-transform:uppercase; letter-spacing:0.2em; color:#888; border-bottom:2px solid transparent; transition:all 0.2s; white-space:nowrap;">
                                <?php esc_html_e( 'Specifications', 'dante-burba' ); ?>
                            </button>
                        <?php endif; ?>
                        
                        <!-- Custom Compatibility Meta Support -->
                        <?php $compat = get_post_meta( get_the_ID(), 'compatibility', true ); if( $compat ): ?>
                            <button class="db-ext-tab" onclick="dbSwitchTab('compat')" style="padding-bottom:16px; font-family:var(--db-font-mono); font-size:10px; font-weight:500; text-transform:uppercase; letter-spacing:0.2em; color:#888; border-bottom:2px solid transparent; transition:all 0.2s; white-space:nowrap;">
                                <?php esc_html_e( 'Compatibility', 'dante-burba' ); ?>
                            </button>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Tab Content: Description -->
                    <div id="db-tab-desc" class="db-ext-content" style="color:#555; font-size:15px; line-height:1.7;">
                        <?php the_content(); ?>
                    </div>

                    <!-- Tab Content: Specifications -->
                    <?php if ( $product->has_attributes() ) : ?>
                        <div id="db-tab-specs" class="db-ext-content" style="display:none;">
                            <ul style="list-style:none; padding:0; margin:0;">
                                <?php 
                                foreach ( $product->get_attributes() as $attribute ) : 
                                    $name = wc_attribute_label( $attribute->get_name() );
                                    $value = '';
                                    
                                    if ( $attribute->is_taxonomy() ) {
                                        $terms = wp_get_post_terms( $product->get_id(), $attribute->get_name(), 'all' );
                                        $values = wp_list_pluck( $terms, 'name' );
                                        $value = implode( ', ', $values );
                                    } else {
                                        $value = $attribute->get_options()[0];
                                    }
                                ?>
                                <li style="display:flex; justify-content:space-between; padding:16px 0; border-bottom:1px solid #F5F5F5;">
                                    <span style="font-family:var(--db-font-mono); font-size:10px; text-transform:uppercase; letter-spacing:0.15em; color:#111; font-weight:600;">
                                        <?php echo esc_html( $name ); ?>
                                    </span>
                                    <span style="color:#666; font-size:14px; text-align:right; max-width:60%;">
                                        <?php echo esc_html( $value ); ?>
                                    </span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Tab Content: Compatibility -->
                    <?php if( $compat ): ?>
                        <div id="db-tab-compat" class="db-ext-content" style="display:none; color:#555; font-size:15px; line-height:1.7;">
                            <?php echo wpautop( esc_html( $compat ) ); ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <?php do_action( 'woocommerce_after_single_product' ); ?>

        <?php endwhile; ?>
    </div>
</div>

<script>
    /**
     * Handles switching between Product Data tabs.
     */
    function dbSwitchTab(tabId) {
        // Reset all tabs
        document.querySelectorAll('.db-ext-tab').forEach(t => {
            t.style.color = '#888';
            t.style.borderColor = 'transparent';
            t.classList.remove('active');
        });
        
        // Hide all content
        document.querySelectorAll('.db-ext-content').forEach(c => {
            c.style.display = 'none';
        });
        
        // Activate selected tab
        const activeTab = document.querySelector(`.db-ext-tab[onclick="dbSwitchTab('${tabId}')"]`);
        if (activeTab) {
            activeTab.style.color = 'var(--db-orange)';
            activeTab.style.borderColor = 'var(--db-orange)';
            activeTab.classList.add('active');
        }
        
        // Show selected content
        const content = document.getElementById(`db-tab-${tabId}`);
        if (content) {
            content.style.display = 'block';
        }
    }
</script>

<?php

get_footer( 'shop' );
