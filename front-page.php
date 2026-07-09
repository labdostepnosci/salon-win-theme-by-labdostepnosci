<?php
/**
 * Footer — Salon Win
 */

$phone         = get_option( 'sw_phone',         '13 445 05 90' );
$phone_url     = salon_win_phone_url( $phone );
$email_opt     = get_option( 'sw_email',         'salon-win@salon-win.pl' );
$address       = get_option( 'sw_address',       'ul. Wyspiańskiego 16' );
$postal_code   = get_option( 'sw_postal_code',   '38-200' );
$city          = get_option( 'sw_city',          'Jasło' );
$restaurant_hours = salon_win_restaurant_hours();
$hotel_day     = get_option( 'sw_hotel_day',     '14:00-11:00' );
$instagram     = get_option( 'sw_instagram',      '#' );
$facebook      = get_option( 'sw_facebook',       '#' );
$booking_url      = get_option( 'sw_booking_url', 'https://www.booking.com/index.pl.html?aid=304142&label=gen173nr-10CAEoggI46AdIM1gEaLYBiAEBmAEzuAEXyAEM2AED6AEB-AEBiAIBqAIBuALGyr7RBsACAdICJGY4YjBkYTY5LTdkYzgtNDU0Zi1hZTZkLWZjNjBiZjkzYzIxMdgCAeACAQ' );
$booking_is_ext   = str_starts_with( $booking_url, 'http' );
$shop_url      = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/sklep' );
$year          = date( 'Y' );
?>

<footer id="site-footer" role="contentinfo">
    <div class="container">

        <!-- Footer Top Grid -->
        <div class="footer-top">

            <!-- Brand Column -->
            <div class="footer-brand">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php bloginfo( 'name' ); ?>">
                    <span class="footer-logo">Salon Win</span>
                    <span class="footer-logo-sub">Jasło</span>
                </a>

                <p>
                    <?php esc_html_e( 'Kameralne apartamenty w Jaśle, ogród, sztuka, wino i spokojna przestrzeń do pobytu, warsztatów, degustacji oraz niewielkich wydarzeń.', 'salon-win' ); ?>
                </p>

                <!-- Social Links -->
                <div class="footer-socials" aria-label="<?php esc_attr_e( 'Nasze profile w mediach społecznościowych', 'salon-win' ); ?>">
                    <a href="<?php echo esc_url( $instagram ); ?>"
                       class="social-link"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="<?php esc_attr_e( 'Instagram Salon Win', 'salon-win' ); ?>">
                        <i class="fab fa-instagram" aria-hidden="true"></i>
                    </a>
                    <a href="<?php echo esc_url( $facebook ); ?>"
                       class="social-link"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="<?php esc_attr_e( 'Facebook Salon Win', 'salon-win' ); ?>">
                        <i class="fab fa-facebook-f" aria-hidden="true"></i>
                    </a>
                    <a href="https://www.tripadvisor.com"
                       class="social-link"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="<?php esc_attr_e( 'TripAdvisor Salon Win', 'salon-win' ); ?>">
                        <i class="fab fa-tripadvisor" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <!-- Navigation Column -->
            <div class="footer-col">
                <h4><?php esc_html_e( 'Nawigacja', 'salon-win' ); ?></h4>
                <ul>
                    <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Strona główna', 'salon-win' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/#rezerwacja' ) ); ?>"><?php esc_html_e( 'Zapytaj o pobyt', 'salon-win' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/#pobyt' ) ); ?>"><?php esc_html_e( 'Apartamenty i doświadczenia', 'salon-win' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/degustacje' ) ); ?>"><?php esc_html_e( 'Wydarzenia kameralne', 'salon-win' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/#okolica' ) ); ?>"><?php esc_html_e( 'Region jasielski i slow travel', 'salon-win' ); ?></a></li>
                    <li><a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Sklep z winami', 'salon-win' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/galeria' ) ); ?>"><?php esc_html_e( 'Galeria', 'salon-win' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/blog' ) ); ?>"><?php esc_html_e( 'Blog', 'salon-win' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/kontakt' ) ); ?>"><?php esc_html_e( 'Kontakt', 'salon-win' ); ?></a></li>
                </ul>
            </div>

            <!-- Shop Column -->
            <div class="footer-col">
                <h4><?php esc_html_e( 'Sklep', 'salon-win' ); ?></h4>
                <ul>
                    <?php
                    if ( function_exists( 'get_terms' ) ) :
                        $categories = get_terms( [
                            'taxonomy'   => 'product_cat',
                            'hide_empty' => true,
                            'number'     => 6,
                        ] );
                        if ( ! is_wp_error( $categories ) && $categories ) :
                            foreach ( $categories as $cat ) :
                    ?>
                    <li>
                        <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>">
                            <?php echo esc_html( $cat->name ); ?>
                        </a>
                    </li>
                    <?php
                            endforeach;
                        else :
                    ?>
                    <li><a href="<?php echo esc_url( $shop_url . '?filter=czerwone' ); ?>"><?php esc_html_e( 'Wina czerwone', 'salon-win' ); ?></a></li>
                    <li><a href="<?php echo esc_url( $shop_url . '?filter=biale' ); ?>"><?php esc_html_e( 'Wina białe', 'salon-win' ); ?></a></li>
                    <li><a href="<?php echo esc_url( $shop_url . '?filter=rozowe' ); ?>"><?php esc_html_e( 'Wina różowe', 'salon-win' ); ?></a></li>
                    <li><a href="<?php echo esc_url( $shop_url . '?filter=musujace' ); ?>"><?php esc_html_e( 'Wina musujące', 'salon-win' ); ?></a></li>
                    <li><a href="<?php echo esc_url( $shop_url . '?filter=naturalne' ); ?>"><?php esc_html_e( 'Wina naturalne', 'salon-win' ); ?></a></li>
                    <li><a href="<?php echo esc_url( home_url( '/akcesoria' ) ); ?>"><?php esc_html_e( 'Akcesoria', 'salon-win' ); ?></a></li>
                    <?php
                        endif;
                    endif;
                    ?>
                    <li>
                        <a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/moje-konto' ) ); ?>">
                            <?php esc_html_e( 'Moje konto', 'salon-win' ); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'cart' ) : home_url( '/koszyk' ) ); ?>">
                            <?php esc_html_e( 'Koszyk', 'salon-win' ); ?>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Column -->
            <div class="footer-col">
                <h4><?php esc_html_e( 'Kontakt', 'salon-win' ); ?></h4>
                <ul class="footer-contact">
                    <li>
                        <span aria-hidden="true"><i class="fas fa-map-marker-alt"></i></span>
                        <span>
                            <?php echo esc_html( $address ); ?><br>
                            <?php echo esc_html( $postal_code . ' ' . $city ); ?>
                        </span>
                    </li>
                    <li>
                        <span aria-hidden="true"><i class="fas fa-phone"></i></span>
                        <a href="<?php echo esc_url( $phone_url ); ?>">
                            <?php echo esc_html( $phone ); ?>
                        </a>
                    </li>
                    <li>
                        <span aria-hidden="true"><i class="fas fa-envelope"></i></span>
                        <a href="mailto:<?php echo esc_attr( $email_opt ); ?>">
                            <?php echo esc_html( $email_opt ); ?>
                        </a>
                    </li>
                    <li>
                        <span aria-hidden="true"><i class="fas fa-clock"></i></span>
                        <span>
                            <strong><?php esc_html_e( 'Restauracja Salon-Win', 'salon-win' ); ?></strong><br>
                            <?php foreach ( $restaurant_hours as $restaurant_day ) : ?>
                                <?php echo esc_html( $restaurant_day['day'] ); ?>:
                                <?php if ( 'tel.' === $restaurant_day['hours'] ) : ?>
                                    <a href="<?php echo esc_url( $phone_url ); ?>"><?php esc_html_e( 'tel.', 'salon-win' ); ?></a>
                                <?php else : ?>
                                    <?php echo esc_html( $restaurant_day['hours'] ); ?>
                                <?php endif; ?>
                                <br>
                            <?php endforeach; ?>
                        </span>
                    </li>
                    <li>
                        <span aria-hidden="true"><i class="fas fa-hotel"></i></span>
                        <span>
                            <strong><?php esc_html_e( 'Hotel Salon-Win', 'salon-win' ); ?></strong><br>
                            <?php esc_html_e( 'Recepcja:', 'salon-win' ); ?>
                            <a href="<?php echo esc_url( $phone_url ); ?>"><?php echo esc_html( $phone ); ?></a><br>
                            <?php esc_html_e( 'Doba hotelowa:', 'salon-win' ); ?> <?php echo esc_html( $hotel_day ); ?>
                        </span>
                    </li>
                </ul>

                <a href="<?php echo esc_url( home_url( '/#rezerwacja' ) ); ?>"
                   class="btn btn-gold mt-md"
                   style="display: inline-flex;">
                    <span><?php esc_html_e( 'Wyślij zapytanie o pobyt', 'salon-win' ); ?></span>
                </a>
                <a href="<?php echo esc_url( $booking_url ); ?>"
                   class="footer-booking-link"
                   <?php if ( $booking_is_ext ) echo 'target="_blank" rel="noopener noreferrer"'; ?>>
                    <?php esc_html_e( 'Sprawdź nocleg przez Booking.com', 'salon-win' ); ?>
                </a>
            </div>

        </div><!-- .footer-top -->

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <p>
                &copy; <?php echo esc_html( $year ); ?> Salon Win.
                <?php esc_html_e( 'Wszelkie prawa zastrzeżone.', 'salon-win' ); ?>
            </p>

            <nav class="footer-legal" aria-label="<?php esc_attr_e( 'Linki prawne', 'salon-win' ); ?>">
                <a href="<?php echo esc_url( home_url( '/polityka-prywatnosci' ) ); ?>">
                    <?php esc_html_e( 'Polityka prywatności', 'salon-win' ); ?>
                </a>
                <a href="<?php echo esc_url( home_url( '/regulamin' ) ); ?>">
                    <?php esc_html_e( 'Regulamin', 'salon-win' ); ?>
                </a>
                <a href="<?php echo esc_url( home_url( '/cookies' ) ); ?>">
                    <?php esc_html_e( 'Cookies', 'salon-win' ); ?>
                </a>
                <a href="<?php echo esc_url( home_url( '/dostawa' ) ); ?>">
                    <?php esc_html_e( 'Dostawa i zwroty', 'salon-win' ); ?>
                </a>
            </nav>

            <div class="age-badge" aria-label="<?php esc_attr_e( 'Tylko dla pełnoletnich', 'salon-win' ); ?>">
                <strong aria-hidden="true">18+</strong>
                <?php esc_html_e( 'Tylko dla pełnoletnich', 'salon-win' ); ?>
            </div>
        </div>

    </div><!-- .container -->
</footer><!-- #site-footer -->

<?php wp_footer(); ?>

</body>
</html>
