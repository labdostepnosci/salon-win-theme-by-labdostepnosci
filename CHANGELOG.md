<?php
/**
 * 404.php — Strona błędu 404
 */

get_header();
?>

<main id="main-content" class="sw-error-main">
    <div class="container text-center">
        <div class="sw-error-panel">
            <span class="eyebrow">404</span>

            <h1 class="display-md sw-page-title">
                <?php esc_html_e( 'Ta strona odpoczywa gdzie indziej', 'salon-win' ); ?>
            </h1>

            <p class="body-lg text-muted">
                <?php esc_html_e( 'Szukana strona nie istnieje lub została przeniesiona. Wróć na stronę główną albo zobacz apartamenty, wydarzenia i spokojne pobyty w Jaśle.', 'salon-win' ); ?>
            </p>

            <?php get_search_form(); ?>

            <div class="sw-empty-actions">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
                    <span><?php esc_html_e( 'Wróć na stronę główną', 'salon-win' ); ?></span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <?php
                $shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/sklep' );
                ?>
                <a href="<?php echo esc_url( $shop_url ); ?>" class="btn btn-ghost">
                    <span><?php esc_html_e( 'Zobacz wina Salon Win', 'salon-win' ); ?></span>
                </a>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
