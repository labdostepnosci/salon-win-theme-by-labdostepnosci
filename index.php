<?php
/**
 * Custom nav walker — outputs bare <a> links.
 * Defined here (top of file) so it exists before wp_nav_menu() uses it below.
 */
if ( ! class_exists( 'Salon_Win_Nav_Walker' ) ) {
    class Salon_Win_Nav_Walker extends Walker_Nav_Menu {
        public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
            $item    = $data_object;
            $classes = empty( $item->classes ) ? [] : (array) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;
            $class_names = implode( ' ', array_filter( $classes ) );
            $url     = ! empty( $item->url ) ? $item->url : '#';
            $title   = apply_filters( 'the_title', $item->title, $item->ID );
            $target  = ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
            $rel     = ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
            $active  = in_array( 'current-menu-item', $classes ) ? ' aria-current="page"' : '';
            $output .= '<a href="' . esc_url( $url ) . '"' . $target . $rel . $active . '>' . esc_html( $title ) . '</a>';
        }

        public function end_el( &$output, $data_object, $depth = 0, $args = null ) {}
        public function start_lvl( &$output, $depth = 0, $args = null ) {}
        public function end_lvl( &$output, $depth = 0, $args = null ) {}
    }
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="#1A0A00">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Mobile Navigation Overlay -->
<nav class="mobile-nav" id="mobile-nav" role="navigation" aria-label="<?php esc_attr_e( 'Menu mobilne', 'salon-win' ); ?>">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Strona główna', 'salon-win' ); ?></a>
    <a href="<?php echo esc_url( home_url( '/#pobyt' ) ); ?>"><?php esc_html_e( 'Apartamenty', 'salon-win' ); ?></a>
    <a href="<?php echo esc_url( home_url( '/degustacje' ) ); ?>"><?php esc_html_e( 'Wydarzenia', 'salon-win' ); ?></a>
    <a href="<?php echo esc_url( home_url( '/#okolica' ) ); ?>"><?php esc_html_e( 'Region i slow', 'salon-win' ); ?></a>
    <a href="<?php echo esc_url( home_url( '/#galeria' ) ); ?>"><?php esc_html_e( 'Sztuka', 'salon-win' ); ?></a>
    <a href="<?php echo esc_url( home_url( '/#kontakt' ) ); ?>"><?php esc_html_e( 'Kontakt', 'salon-win' ); ?></a>

    <a href="<?php echo esc_url( home_url( '/#rezerwacja' ) ); ?>" class="text-gold"><?php esc_html_e( 'Zapytaj o pobyt', 'salon-win' ); ?></a>
</nav>

<!-- Site Header -->
<header id="site-header" role="banner">
    <div class="container">
        <div class="header-inner">

            <!-- Logo -->
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" aria-label="<?php bloginfo( 'name' ); ?>">
                <span class="logo-text">Salon Win</span>
                <span class="logo-sub">Jasło</span>
            </a>

            <!-- Primary Navigation -->
            <nav class="main-nav" role="navigation" aria-label="<?php esc_attr_e( 'Menu Główne', 'salon-win' ); ?>">
                <?php
                wp_nav_menu( [
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => '',
                    'fallback_cb'    => 'salon_win_fallback_menu',
                    'items_wrap'     => '%3$s',
                    'walker'         => new Salon_Win_Nav_Walker(),
                ] );
                ?>
            </nav>

            <!-- Header Actions -->
            <div class="header-actions">
                <?php if ( function_exists( 'WC' ) && WC()->cart ) : ?>
                    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header-cart" aria-label="<?php esc_attr_e( 'Koszyk', 'salon-win' ); ?>">
                        <i class="fas fa-shopping-bag" aria-hidden="true"></i>
                        <span class="cart-count" aria-live="polite">
                            <?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
                        </span>
                    </a>
                <?php endif; ?>

                <a href="<?php echo esc_url( home_url( '/#rezerwacja' ) ); ?>"
                   class="btn btn-gold">
                    <span><?php esc_html_e( 'Zapytaj o pobyt', 'salon-win' ); ?></span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>

                <!-- Hamburger -->
                <button class="hamburger" id="hamburger" aria-label="<?php esc_attr_e( 'Otwórz menu', 'salon-win' ); ?>" aria-expanded="false" aria-controls="mobile-nav">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>

        </div><!-- .header-inner -->
    </div><!-- .container -->
</header><!-- #site-header -->

<?php
/**
 * Fallback menu when no menu is assigned in WordPress.
 */
function salon_win_fallback_menu() {
    ?>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Strona główna', 'salon-win' ); ?></a>
    <a href="<?php echo esc_url( home_url( '/#pobyt' ) ); ?>"><?php esc_html_e( 'Apartamenty', 'salon-win' ); ?></a>
    <a href="<?php echo esc_url( home_url( '/degustacje' ) ); ?>"><?php esc_html_e( 'Wydarzenia', 'salon-win' ); ?></a>
    <a href="<?php echo esc_url( home_url( '/#okolica' ) ); ?>"><?php esc_html_e( 'Region i slow', 'salon-win' ); ?></a>
    <a href="<?php echo esc_url( home_url( '/#galeria' ) ); ?>"><?php esc_html_e( 'Sztuka', 'salon-win' ); ?></a>
    <a href="<?php echo esc_url( home_url( '/#kontakt' ) ); ?>"><?php esc_html_e( 'Kontakt', 'salon-win' ); ?></a>
    <?php
}

// (Salon_Win_Nav_Walker moved to top of file, defined before first use)
