<?php
/**
 * 404.php — Strona błędu 404
 */

get_header();
?>

<main id="main-content" style="padding-top: 100px; min-height: 80vh; display:flex; align-items:center;">
    <div class="container text-center">
        <div style="max-width: 560px; margin: 0 auto;">
            <span class="eyebrow" style="color: var(--color-burgundy);">404</span>

            <h1 class="display-md" style="margin: var(--space-sm) 0 var(--space-md);">
                <?php esc_html_e( 'Tej strony nie ma w naszej piwnicy', 'salon-win' ); ?>
            </h1>

            <p class="body-lg text-muted" style="margin-bottom: var(--space-lg);">
                <?php esc_html_e( 'Szukana strona nie istnieje lub została przeniesiona. Wróć na stronę główną lub odkryj naszą ofertę win.', 'salon-win' ); ?>
            </p>

            <?php get_search_form(); ?>

            <div style="display: flex; gap: var(--space-sm); justify-content: center; flex-wrap: wrap; margin-top: var(--space-lg);">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
                    <span><?php esc_html_e( 'Strona główna', 'salon-win' ); ?></span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <?php
                $shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/sklep' );
                ?>
                <a href="<?php echo esc_url( $shop_url ); ?>" class="btn btn-ghost">
                    <span><?php esc_html_e( 'Sklep z winami', 'salon-win' ); ?></span>
                </a>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>
