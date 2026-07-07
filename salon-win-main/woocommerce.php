<?php
/**
 * woocommerce.php — Wrapper dla stron WooCommerce
 * WordPress szuka tego pliku gdy WooCommerce wyświetla swoje strony.
 */

get_header();
?>

<main id="main-content" style="padding-top: 100px; min-height: 70vh;">
    <div class="section">
        <div class="container">

            <!-- WooCommerce breadcrumb -->
            <?php if ( function_exists( 'woocommerce_breadcrumb' ) ) : ?>
            <div class="mb-md" style="padding-bottom: var(--space-sm); border-bottom: 1px solid rgba(26,10,0,0.08);">
                <?php woocommerce_breadcrumb(); ?>
            </div>
            <?php endif; ?>

            <!-- WooCommerce content -->
            <?php woocommerce_content(); ?>

        </div>
    </div>
</main>

<?php get_footer(); ?>
