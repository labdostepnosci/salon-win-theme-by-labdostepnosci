<?php
/**
 * Customizer settings and helpers for theme images.
 *
 * @package Salon_Win
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Return a Customizer image URL or its bundled theme fallback.
 *
 * The returned URL is sanitized for storage. Callers must use esc_url() when
 * rendering it in HTML.
 *
 * @param string $key               Theme modification key.
 * @param string $fallback_filename Fallback filename from assets/images.
 * @return string
 */
function salon_win_get_image_url( $key, $fallback_filename ) {
    $key        = sanitize_key( $key );
    $custom_url = get_theme_mod( $key, '' );

    if ( is_string( $custom_url ) && '' !== trim( $custom_url ) ) {
        return esc_url_raw( $custom_url );
    }

    $fallback_filename = sanitize_file_name( wp_basename( $fallback_filename ) );
    $fallback_url      = trailingslashit( get_template_directory_uri() ) . 'assets/images/' . $fallback_filename;

    return esc_url_raw( $fallback_url );
}

/**
 * Register image controls in the WordPress Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager.
 * @return void
 */
function salon_win_customize_images( $wp_customize ) {
    if ( ! class_exists( 'WP_Customize_Image_Control' ) ) {
        return;
    }

    $wp_customize->add_section(
        'sw_images',
        array(
            'title'       => __( 'Salon Win — obrazy', 'salon-win' ),
            'description' => __( 'Wybierz obrazy z biblioteki mediów. Puste pole zachowuje plik fallbackowy z katalogu assets/images.', 'salon-win' ),
            'panel'       => 'salon_win_panel',
            'priority'    => 20,
        )
    );

    $images = array(
        'sw_image_hero_wine'        => array(
            'label'       => __( 'Tło hero — 1920×1080', 'salon-win' ),
            'description' => __( 'Rekomendowany rozmiar: 1920×1080 px.', 'salon-win' ),
        ),
        'sw_image_about_salon'      => array(
            'label'       => __( 'O salonie — zdjęcie pionowe 900×1200', 'salon-win' ),
            'description' => __( 'Rekomendowany rozmiar: 900×1200 px.', 'salon-win' ),
        ),
        'sw_image_about_wine'       => array(
            'label'       => __( 'O salonie — akcent 600×600', 'salon-win' ),
            'description' => __( 'Rekomendowany rozmiar: 600×600 px.', 'salon-win' ),
        ),
        'sw_image_gallery_1'        => array(
            'label'       => __( 'Galeria 1 — zdjęcie główne 800×1200', 'salon-win' ),
            'description' => __( 'Rekomendowany rozmiar: 800×1200 px.', 'salon-win' ),
        ),
        'sw_image_gallery_2'        => array(
            'label'       => __( 'Galeria 2 — kafelek 800×600', 'salon-win' ),
            'description' => __( 'Rekomendowany rozmiar: 800×600 px.', 'salon-win' ),
        ),
        'sw_image_gallery_3'        => array(
            'label'       => __( 'Galeria 3 — kafelek 800×600', 'salon-win' ),
            'description' => __( 'Rekomendowany rozmiar: 800×600 px.', 'salon-win' ),
        ),
        'sw_image_gallery_4'        => array(
            'label'       => __( 'Galeria 4 — kafelek 800×600', 'salon-win' ),
            'description' => __( 'Rekomendowany rozmiar: 800×600 px.', 'salon-win' ),
        ),
        'sw_image_gallery_5'        => array(
            'label'       => __( 'Galeria 5 — kafelek 800×600', 'salon-win' ),
            'description' => __( 'Rekomendowany rozmiar: 800×600 px.', 'salon-win' ),
        ),
        'sw_image_wine_red_1'       => array(
            'label'       => __( 'Wino czerwone 1 — 600×800', 'salon-win' ),
            'description' => __( 'Rekomendowany rozmiar: 600×800 px.', 'salon-win' ),
        ),
        'sw_image_wine_red_2'       => array(
            'label'       => __( 'Wino czerwone 2 — 600×800', 'salon-win' ),
            'description' => __( 'Rekomendowany rozmiar: 600×800 px.', 'salon-win' ),
        ),
        'sw_image_wine_red_3'       => array(
            'label'       => __( 'Wino czerwone 3 — 600×800', 'salon-win' ),
            'description' => __( 'Rekomendowany rozmiar: 600×800 px.', 'salon-win' ),
        ),
        'sw_image_wine_white_1'     => array(
            'label'       => __( 'Wino białe 1 — 600×800', 'salon-win' ),
            'description' => __( 'Rekomendowany rozmiar: 600×800 px.', 'salon-win' ),
        ),
        'sw_image_wine_white_2'     => array(
            'label'       => __( 'Wino białe 2 — 600×800', 'salon-win' ),
            'description' => __( 'Rekomendowany rozmiar: 600×800 px.', 'salon-win' ),
        ),
        'sw_image_wine_sparkling_1' => array(
            'label'       => __( 'Wino musujące 1 — 600×800', 'salon-win' ),
            'description' => __( 'Rekomendowany rozmiar: 600×800 px.', 'salon-win' ),
        ),
        'sw_image_wine_rose_1'      => array(
            'label'       => __( 'Wino różowe 1 — 600×800', 'salon-win' ),
            'description' => __( 'Rekomendowany rozmiar: 600×800 px.', 'salon-win' ),
        ),
        'sw_image_wine_natural_1'   => array(
            'label'       => __( 'Wino naturalne 1 — 600×800', 'salon-win' ),
            'description' => __( 'Rekomendowany rozmiar: 600×800 px.', 'salon-win' ),
        ),
        'sw_image_wine_placeholder' => array(
            'label'       => __( 'Fallback dla wina — 600×800', 'salon-win' ),
            'description' => __( 'Rekomendowany rozmiar: 600×800 px.', 'salon-win' ),
        ),
    );

    foreach ( $images as $setting_key => $image ) {
        $wp_customize->add_setting(
            $setting_key,
            array(
                'type'              => 'theme_mod',
                'default'           => '',
                'sanitize_callback' => 'esc_url_raw',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            new WP_Customize_Image_Control(
                $wp_customize,
                $setting_key,
                array(
                    'label'       => $image['label'],
                    'description' => $image['description'],
                    'section'     => 'sw_images',
                    'settings'    => $setting_key,
                )
            )
        );
    }
}

add_action( 'customize_register', 'salon_win_customize_images', 20 );
