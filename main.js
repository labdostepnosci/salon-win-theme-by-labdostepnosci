<?php
/**
 * woocommerce.php — Wrapper dla stron WooCommerce
 * WordPress szuka tego pliku gdy WooCommerce wyświetla swoje strony.
 */

get_header();
?>

<main id="main-content" class="sw-subpage-main sw-shop-main">
    <div class="section sw-content-section">
        <div class="container">

            <!-- WooCommerce breadcrumb -->
            <?php if ( function_exists( 'woocommerce_breadcrumb' ) ) : ?>
            <div class="sw-breadcrumb-wrap">
                <?php woocommerce_breadcrumb(); ?>
            </div>
            <?php endif; ?>

            <!-- WooCommerce content -->
            <?php woocommerce_content(); ?>

        </div>
    </div>
</main>

<?php get_footer(); ?>
