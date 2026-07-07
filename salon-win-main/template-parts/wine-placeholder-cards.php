<?php
/**
 * template-parts/wine-placeholder-cards.php
 * Demo kart win gdy brak produktów WooCommerce lub wpisów CPT.
 */

$placeholder_wines = [
    [
        'region'   => 'Burgundia, Francja',
        'name'     => 'Gevrey-Chambertin Premier Cru',
        'vintage'  => '2019',
        'price'    => '580,00 zł',
        'badge'    => 'bestseller',
        'image_key'=> 'sw_image_wine_red_1',
        'img'      => 'wine-red-1.jpg',
        'type'     => 'czerwone',
    ],
    [
        'region'   => 'Toskania, Włochy',
        'name'     => 'Brunello di Montalcino DOCG',
        'vintage'  => '2018',
        'price'    => '420,00 zł',
        'badge'    => '',
        'image_key'=> 'sw_image_wine_red_2',
        'img'      => 'wine-red-2.jpg',
        'type'     => 'czerwone',
    ],
    [
        'region'   => 'Alzacja, Francja',
        'name'     => 'Riesling Grand Cru Schlossberg',
        'vintage'  => '2021',
        'price'    => '210,00 zł',
        'badge'    => 'new',
        'image_key'=> 'sw_image_wine_white_1',
        'img'      => 'wine-white-1.jpg',
        'type'     => 'biale',
    ],
    [
        'region'   => 'Priorat, Hiszpania',
        'name'     => 'Clos Mogador',
        'vintage'  => '2020',
        'price'    => '340,00 zł',
        'badge'    => '',
        'image_key'=> 'sw_image_wine_red_3',
        'img'      => 'wine-red-3.jpg',
        'type'     => 'czerwone',
    ],
    [
        'region'   => 'Champagne, Francja',
        'name'     => 'Krug Grande Cuvée Brut',
        'vintage'  => 'NV',
        'price'    => '980,00 zł',
        'badge'    => 'bestseller',
        'image_key'=> 'sw_image_wine_sparkling_1',
        'img'      => 'wine-sparkling-1.jpg',
        'type'     => 'musujace',
    ],
    [
        'region'   => 'Dolina Rodanu, Francja',
        'name'     => 'Château Rayas Blanc',
        'vintage'  => '2020',
        'price'    => '760,00 zł',
        'badge'    => '',
        'image_key'=> 'sw_image_wine_white_2',
        'img'      => 'wine-white-2.jpg',
        'type'     => 'biale',
    ],
    [
        'region'   => 'Langwedocja, Francja',
        'name'     => 'Le Soula Rouge',
        'vintage'  => '2019',
        'price'    => '185,00 zł',
        'badge'    => 'new',
        'image_key'=> 'sw_image_wine_natural_1',
        'img'      => 'wine-natural-1.jpg',
        'type'     => 'naturalne',
    ],
    [
        'region'   => 'Prowansja, Francja',
        'name'     => 'Château d\'Esclans Rock Angel',
        'vintage'  => '2022',
        'price'    => '155,00 zł',
        'badge'    => '',
        'image_key'=> 'sw_image_wine_rose_1',
        'img'      => 'wine-rose-1.jpg',
        'type'     => 'rozowe',
    ],
];

$shop_url  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/sklep' );
?>

<div class="wine-grid" id="wine-grid">
    <?php foreach ( $placeholder_wines as $i => $wine ) : ?>
    <article class="wine-card fade-up" data-type="<?php echo esc_attr( $wine['type'] ); ?>" style="transition-delay: <?php echo esc_attr( ( $i % 4 ) * 0.07 ); ?>s;">
        <a href="<?php echo esc_url( $shop_url ); ?>" aria-label="<?php echo esc_attr( $wine['name'] ); ?>">
            <div class="wine-card-image" style="background: linear-gradient(135deg, #2c1810 0%, #1a0a00 100%);">
                <img src="<?php echo esc_url( salon_win_get_image_url( $wine['image_key'], $wine['img'] ) ); ?>"
                     alt="<?php echo esc_attr( $wine['name'] . ' — ' . $wine['region'] ); ?>"
                     loading="lazy"
                     onerror="this.style.display='none'">
                <?php if ( $wine['badge'] ) : ?>
                    <span class="wine-badge <?php echo esc_attr( $wine['badge'] ); ?>">
                        <?php echo esc_html( $wine['badge'] === 'new' ? __( 'Nowość', 'salon-win' ) : __( 'Bestseller', 'salon-win' ) ); ?>
                    </span>
                <?php endif; ?>
            </div>
            <div class="wine-card-body">
                <span class="wine-region"><?php echo esc_html( $wine['region'] ); ?></span>
                <h3 class="wine-name"><?php echo esc_html( $wine['name'] ); ?></h3>
                <span class="wine-vintage"><?php echo esc_html( $wine['vintage'] ); ?></span>
                <div class="wine-footer">
                    <span class="wine-price">
                        <?php echo esc_html( $wine['price'] ); ?>
                    </span>
                    <button class="btn-add-cart" aria-label="<?php esc_attr_e( 'Dodaj do koszyka', 'salon-win' ); ?>">
                        <i class="fas fa-plus" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </a>
    </article>
    <?php endforeach; ?>
</div>
