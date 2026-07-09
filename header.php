<?php
/**
 * Salon Win — functions.php
 * Elegancki motyw WordPress dla winnicy i salonu winiarskiego.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$salon_win_updater = get_template_directory() . '/inc/github-theme-updater.php';

if ( file_exists( $salon_win_updater ) ) {
    require_once $salon_win_updater;
}

$salon_win_customizer = get_template_directory() . '/inc/customizer.php';

if ( file_exists( $salon_win_customizer ) ) {
    require_once $salon_win_customizer;
}

/* ===================================================
   1. THEME SETUP
=================================================== */
function salon_win_setup() {
    load_theme_textdomain( 'salon-win', get_template_directory() . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'editor-styles' );
    add_editor_style( 'style.css' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    add_theme_support( 'woocommerce', [
        'thumbnail_image_width' => 600,
        'single_image_width'    => 900,
    ] );

    add_image_size( 'sw-hero',    1920, 1080, true );
    add_image_size( 'sw-wine',     600,  800, true );
    add_image_size( 'sw-gallery',  800,  600, true );
    add_image_size( 'sw-thumb',    400,  400, true );

    register_nav_menus( [
        'primary'  => __( 'Menu Główne',   'salon-win' ),
        'footer'   => __( 'Menu Stopka',   'salon-win' ),
        'legal'    => __( 'Menu Prawne',   'salon-win' ),
    ] );
}
add_action( 'after_setup_theme', 'salon_win_setup' );

/* ===================================================
   2. CONTENT WIDTH
=================================================== */
function salon_win_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'salon_win_content_width', 1360 );
}
add_action( 'after_setup_theme', 'salon_win_content_width', 0 );

/* ===================================================
   3. ENQUEUE STYLES & SCRIPTS
=================================================== */
function salon_win_enqueue() {
    $ver = wp_get_theme()->get( 'Version' );

    // Google Fonts
    wp_enqueue_style(
        'salon-win-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garant:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500&family=Inter:wght@400;500;600;700&display=swap',
        [],
        null
    );

    // Font Awesome
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
        [],
        '6.5.1'
    );

    // Main stylesheet
    wp_enqueue_style( 'salon-win-style', get_stylesheet_uri(), [ 'salon-win-fonts' ], $ver );

    // Main JS
    wp_enqueue_script(
        'salon-win-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        $ver,
        true
    );

    // Localize JS data
    wp_localize_script( 'salon-win-main', 'salonWin', [
        'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
        'nonce'     => wp_create_nonce( 'salon_win_nonce' ),
        'siteUrl'   => get_site_url(),
        'cartCount' => ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0,
    ] );

    // WooCommerce
    if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() ) ) {
        wp_enqueue_script( 'wc-add-to-cart-variation' );
    }

    // Comment reply
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'salon_win_enqueue' );

/* ===================================================
   3a. FRONTEND BLOCK NORMALIZATION
=================================================== */
function salon_win_normalize_content_heading_levels( $parsed_block ) {
    if ( is_admin() || ! is_singular() ) {
        return $parsed_block;
    }

    if (
        isset( $parsed_block['blockName'] ) &&
        'core/heading' === $parsed_block['blockName'] &&
        isset( $parsed_block['attrs']['level'] ) &&
        1 === (int) $parsed_block['attrs']['level']
    ) {
        $parsed_block['attrs']['level'] = 2;
    }

    return $parsed_block;
}
add_filter( 'render_block_data', 'salon_win_normalize_content_heading_levels' );

/* ===================================================
   4. REGISTER WIDGET AREAS
=================================================== */
function salon_win_widgets() {
    $sidebars = [
        [
            'name'          => __( 'Sidebar Główny', 'salon-win' ),
            'id'            => 'sidebar-main',
            'description'   => __( 'Główna kolumna boczna', 'salon-win' ),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h3 class="widget-title eyebrow">',
            'after_title'   => '</h3>',
        ],
        [
            'name'          => __( 'Stopka — Kolumna 1', 'salon-win' ),
            'id'            => 'footer-col-1',
            'before_widget' => '<div class="footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="footer-widget-title">',
            'after_title'   => '</h4>',
        ],
        [
            'name'          => __( 'Stopka — Kolumna 2', 'salon-win' ),
            'id'            => 'footer-col-2',
            'before_widget' => '<div class="footer-widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="footer-widget-title">',
            'after_title'   => '</h4>',
        ],
    ];

    foreach ( $sidebars as $sb ) {
        register_sidebar( $sb );
    }
}
add_action( 'widgets_init', 'salon_win_widgets' );

/* ===================================================
   5. CUSTOM POST TYPE — DEGUSTACJE / EVENTY
=================================================== */
function salon_win_register_cpts() {
    // Wydarzenia / degustacje
    register_post_type( 'degustacja', [
        'labels' => [
            'name'          => __( 'Wydarzenia',      'salon-win' ),
            'singular_name' => __( 'Wydarzenie',      'salon-win' ),
            'add_new_item'  => __( 'Dodaj Wydarzenie','salon-win' ),
            'edit_item'     => __( 'Edytuj Wydarzenie','salon-win' ),
            'menu_name'     => __( 'Wydarzenia',      'salon-win' ),
        ],
        'public'        => true,
        'menu_icon'     => 'dashicons-calendar-alt',
        'supports'      => [ 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt' ],
        'has_archive'   => true,
        'rewrite'       => [ 'slug' => 'degustacje' ],
        'show_in_rest'  => true,
    ] );

    // Wina (jeśli nie używamy WooCommerce)
    register_post_type( 'wino', [
        'labels' => [
            'name'          => __( 'Wina',     'salon-win' ),
            'singular_name' => __( 'Wino',     'salon-win' ),
            'add_new_item'  => __( 'Dodaj Wino','salon-win' ),
            'edit_item'     => __( 'Edytuj Wino','salon-win' ),
            'menu_name'     => __( 'Wina',     'salon-win' ),
        ],
        'public'        => true,
        'menu_icon'     => 'dashicons-star-filled',
        'supports'      => [ 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt' ],
        'has_archive'   => true,
        'rewrite'       => [ 'slug' => 'wina' ],
        'show_in_rest'  => true,
    ] );

    // Opinie
    register_post_type( 'opinia', [
        'labels' => [
            'name'          => __( 'Opinie',     'salon-win' ),
            'singular_name' => __( 'Opinia',     'salon-win' ),
            'add_new_item'  => __( 'Dodaj Opinię','salon-win' ),
            'menu_name'     => __( 'Opinie',     'salon-win' ),
        ],
        'public'        => false,
        'show_ui'       => true,
        'menu_icon'     => 'dashicons-format-quote',
        'supports'      => [ 'title', 'editor', 'thumbnail', 'custom-fields' ],
        'show_in_rest'  => true,
    ] );
}
add_action( 'init', 'salon_win_register_cpts' );

/* ===================================================
   6. CUSTOM TAXONOMIES
=================================================== */
function salon_win_register_taxonomies() {
    // Regiony win
    register_taxonomy( 'region_wina', [ 'wino', 'product' ], [
        'labels' => [
            'name'          => __( 'Regiony',     'salon-win' ),
            'singular_name' => __( 'Region',      'salon-win' ),
            'menu_name'     => __( 'Regiony Win', 'salon-win' ),
        ],
        'hierarchical' => true,
        'rewrite'      => [ 'slug' => 'region' ],
        'show_in_rest' => true,
        'public'       => true,
    ] );

    // Szczep wina
    register_taxonomy( 'szczep', [ 'wino', 'product' ], [
        'labels' => [
            'name'          => __( 'Szczepy',  'salon-win' ),
            'singular_name' => __( 'Szczep',   'salon-win' ),
            'menu_name'     => __( 'Szczepy',  'salon-win' ),
        ],
        'hierarchical' => false,
        'rewrite'      => [ 'slug' => 'szczep' ],
        'show_in_rest' => true,
        'public'       => true,
    ] );
}
add_action( 'init', 'salon_win_register_taxonomies' );

/* ===================================================
   7. CUSTOM META BOXES
=================================================== */
function salon_win_meta_boxes() {
    add_meta_box( 'sw-degustacja-details', __( 'Szczegóły Wydarzenia', 'salon-win' ),
        'salon_win_degustacja_meta_cb', 'degustacja', 'normal', 'high' );

    add_meta_box( 'sw-wine-details', __( 'Dane Wina', 'salon-win' ),
        'salon_win_wine_meta_cb', 'wino', 'normal', 'high' );

    add_meta_box( 'sw-review-details', __( 'Szczegóły Opinii', 'salon-win' ),
        'salon_win_review_meta_cb', 'opinia', 'normal', 'high' );

    add_meta_box( 'sw-booking-details', __( 'Szczegóły Rezerwacji', 'salon-win' ),
        'salon_win_rezerwacja_meta_cb', 'rezerwacja', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'salon_win_meta_boxes' );

function salon_win_degustacja_meta_cb( $post ) {
    wp_nonce_field( 'sw_degustacja_nonce', 'sw_degustacja_nonce_field' );
    $fields = [
        'sw_date'           => [ 'label' => 'Data wydarzenia', 'type' => 'date' ],
        'sw_time'           => [ 'label' => 'Godzina',          'type' => 'time' ],
        'sw_price'          => [ 'label' => 'Cena (PLN)',        'type' => 'number' ],
        'sw_spots'          => [ 'label' => 'Liczba miejsc',     'type' => 'number' ],
        'sw_spots_left'     => [ 'label' => 'Pozostałe miejsca', 'type' => 'number' ],
        'sw_booking_url'    => [ 'label' => 'Link do rezerwacji','type' => 'url' ],
        'sw_location'       => [ 'label' => 'Lokalizacja',       'type' => 'text' ],
        'sw_type'           => [ 'label' => 'Typ wydarzenia',    'type' => 'text' ],
    ];
    salon_win_render_meta_fields( $post, $fields );
}

function salon_win_wine_meta_cb( $post ) {
    wp_nonce_field( 'sw_wine_nonce', 'sw_wine_nonce_field' );
    $fields = [
        'sw_wine_vintage'   => [ 'label' => 'Rocznik',          'type' => 'number' ],
        'sw_wine_region'    => [ 'label' => 'Region',            'type' => 'text' ],
        'sw_wine_grape'     => [ 'label' => 'Szczep',            'type' => 'text' ],
        'sw_wine_price'     => [ 'label' => 'Cena (PLN)',        'type' => 'number' ],
        'sw_wine_alcohol'   => [ 'label' => 'Alkohol (%)',       'type' => 'text' ],
        'sw_wine_taste'     => [ 'label' => 'Profil smakowy',    'type' => 'text' ],
        'sw_wine_badge'     => [ 'label' => 'Etykieta (new/bestseller)', 'type' => 'text' ],
        'sw_wine_in_stock'  => [ 'label' => 'Dostępne',         'type' => 'checkbox' ],
    ];
    salon_win_render_meta_fields( $post, $fields );
}

function salon_win_review_meta_cb( $post ) {
    wp_nonce_field( 'sw_review_nonce', 'sw_review_nonce_field' );
    $fields = [
        'sw_reviewer_name'   => [ 'label' => 'Imię i nazwisko',  'type' => 'text' ],
        'sw_reviewer_source' => [ 'label' => 'Źródło (Google/TripAdvisor)', 'type' => 'text' ],
        'sw_review_rating'   => [ 'label' => 'Ocena (1-5)',      'type' => 'number' ],
        'sw_review_date'     => [ 'label' => 'Data',             'type' => 'date' ],
        'sw_review_verified' => [ 'label' => 'Zweryfikowana',    'type' => 'checkbox' ],
    ];
    salon_win_render_meta_fields( $post, $fields );
}

function salon_win_rezerwacja_meta_cb( $post ) {
    wp_nonce_field( 'sw_booking_admin_nonce', 'sw_booking_admin_nonce_field' );

    $fields = [
        'name'    => __( 'Imię i nazwisko', 'salon-win' ),
        'email'   => __( 'E-mail', 'salon-win' ),
        'phone'   => __( 'Telefon', 'salon-win' ),
        'date'    => __( 'Data', 'salon-win' ),
        'time'    => __( 'Godzina', 'salon-win' ),
        'guests'  => __( 'Liczba osób', 'salon-win' ),
        'type'    => __( 'Rodzaj rezerwacji', 'salon-win' ),
        'message' => __( 'Wiadomość', 'salon-win' ),
    ];

    $status = get_post_meta( $post->ID, 'sw_booking_status', true ) ?: 'nowa';

    echo '<table class="form-table">';
    foreach ( $fields as $key => $label ) {
        $value = get_post_meta( $post->ID, 'sw_booking_' . $key, true );
        echo '<tr><th scope="row">' . esc_html( $label ) . '</th><td>';
        if ( 'message' === $key ) {
            echo '<textarea class="large-text" rows="4" readonly>' . esc_textarea( $value ) . '</textarea>';
        } else {
            echo '<input type="text" class="regular-text" value="' . esc_attr( $value ) . '" readonly>';
        }
        echo '</td></tr>';
    }

    echo '<tr><th scope="row"><label for="sw_booking_status">' . esc_html__( 'Status', 'salon-win' ) . '</label></th><td>';
    echo '<select id="sw_booking_status" name="sw_booking_status">';
    foreach ( [ 'nowa', 'w toku', 'potwierdzona', 'odrzucona' ] as $option ) {
        echo '<option value="' . esc_attr( $option ) . '"' . selected( $status, $option, false ) . '>' . esc_html( ucfirst( $option ) ) . '</option>';
    }
    echo '</select>';
    echo '</td></tr>';
    echo '</table>';
}

function salon_win_render_meta_fields( $post, $fields ) {
    echo '<table class="form-table">';
    foreach ( $fields as $key => $field ) {
        $value = get_post_meta( $post->ID, $key, true );
        echo '<tr><th scope="row"><label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] ) . '</label></th><td>';
        if ( $field['type'] === 'checkbox' ) {
            echo '<input type="checkbox" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="1"' . checked( $value, '1', false ) . '>';
        } else {
            echo '<input type="' . esc_attr( $field['type'] ) . '" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="regular-text">';
        }
        echo '</td></tr>';
    }
    echo '</table>';
}

function salon_win_save_meta( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $all_fields = [
        'sw_date', 'sw_time', 'sw_price', 'sw_spots', 'sw_spots_left',
        'sw_booking_url', 'sw_location', 'sw_type',
        'sw_booking_status',
        'sw_wine_vintage', 'sw_wine_region', 'sw_wine_grape', 'sw_wine_price',
        'sw_wine_alcohol', 'sw_wine_taste', 'sw_wine_badge', 'sw_wine_in_stock',
        'sw_reviewer_name', 'sw_reviewer_source', 'sw_review_rating',
        'sw_review_date', 'sw_review_verified',
    ];

    foreach ( $all_fields as $field ) {
        if ( isset( $_POST[ $field ] ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[ $field ] ) );
        } else {
            // For checkboxes, save '0' if unchecked
            if ( in_array( $field, [ 'sw_wine_in_stock', 'sw_review_verified' ] ) ) {
                update_post_meta( $post_id, $field, '0' );
            }
        }
    }
}
add_action( 'save_post', 'salon_win_save_meta' );

/* ===================================================
   8. AJAX — BOOKING FORM
=================================================== */
function salon_win_handle_booking() {
    check_ajax_referer( 'salon_win_nonce', 'nonce' );

    $data = [
        'name'    => sanitize_text_field( $_POST['name']    ?? '' ),
        'email'   => sanitize_email(      $_POST['email']   ?? '' ),
        'phone'   => sanitize_text_field( $_POST['phone']   ?? '' ),
        'date'    => sanitize_text_field( $_POST['date']    ?? '' ),
        'time'    => sanitize_text_field( $_POST['time']    ?? '' ),
        'guests'  => absint(              $_POST['guests']  ?? 1  ),
        'type'    => sanitize_text_field( $_POST['type']    ?? '' ),
        'message' => sanitize_textarea_field( $_POST['message'] ?? '' ),
    ];

    if ( empty( $data['name'] ) || empty( $data['email'] ) || empty( $data['date'] ) ) {
        wp_send_json_error( [ 'message' => __( 'Uzupełnij wymagane pola, abyśmy mogli odpowiedzieć na zapytanie.', 'salon-win' ) ] );
    }

    if ( ! is_email( $data['email'] ) ) {
        wp_send_json_error( [ 'message' => __( 'Podaj poprawny adres e-mail.', 'salon-win' ) ] );
    }

    // Save reservation as post
    $post_id = wp_insert_post( [
        'post_title'  => sprintf( '%s — %s %s', $data['name'], $data['date'], $data['time'] ),
        'post_type'   => 'rezerwacja',
        'post_status' => 'publish',
    ] );

    if ( is_wp_error( $post_id ) || ! $post_id ) {
        wp_send_json_error( [ 'message' => __( 'Nie udało się zapisać zapytania. Spróbuj ponownie lub zadzwoń do nas.', 'salon-win' ) ] );
    }

    foreach ( $data as $key => $value ) {
        update_post_meta( $post_id, 'sw_booking_' . $key, $value );
    }
    update_post_meta( $post_id, 'sw_booking_status', 'nowa' );

    // Send confirmation email
    $admin_email = get_option( 'admin_email' );
    $subject = sprintf( __( 'Nowa rezerwacja: %s', 'salon-win' ), $data['name'] );
    $message = sprintf(
        "Nowe zapytanie rezerwacyjne:\n\nImię: %s\nE-mail: %s\nTelefon: %s\nData: %s %s\nGości: %d\nRodzaj: %s\nWiadomość: %s\n\nPanel WordPress: %s",
        $data['name'], $data['email'], $data['phone'],
        $data['date'], $data['time'], $data['guests'],
        $data['type'], $data['message'], admin_url( 'post.php?post=' . $post_id . '&action=edit' )
    );

    wp_mail( $admin_email, $subject, $message );

    // Confirmation to guest
    $guest_subject = __( 'Otrzymaliśmy Twoje zapytanie - Salon Win', 'salon-win' );
    $guest_message = sprintf(
        "Dzień dobry %s,\n\nDziękujemy za zapytanie rezerwacyjne w Salon Win.\n\nData: %s, godzina: %s\nLiczba gości: %d\nRodzaj: %s\n\nSprawdzimy dostępność i wrócimy z potwierdzeniem albo propozycją najlepszego wariantu pobytu.\n\nSalon Win\nhttps://salon-win.pl",
        $data['name'], $data['date'], $data['time'], $data['guests'], $data['type']
    );
    wp_mail( $data['email'], $guest_subject, $guest_message );

    wp_send_json_success( [ 'message' => __( 'Dziękujemy za zapytanie. Otrzymasz e-mail, a zespół Salon Win wróci z informacją o dostępności.', 'salon-win' ) ] );
}
add_action( 'wp_ajax_salon_win_booking',        'salon_win_handle_booking' );
add_action( 'wp_ajax_nopriv_salon_win_booking', 'salon_win_handle_booking' );

/* ===================================================
   9. AJAX — NEWSLETTER
=================================================== */
function salon_win_handle_newsletter() {
    check_ajax_referer( 'salon_win_nonce', 'nonce' );

    $email = sanitize_email( $_POST['email'] ?? '' );

    if ( ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => __( 'Podaj poprawny adres e-mail.', 'salon-win' ) ] );
    }

    // Store in options or forward to Mailchimp / newsletter plugin
    $subscribers = get_option( 'sw_newsletter_subscribers', [] );

    if ( in_array( $email, $subscribers ) ) {
        wp_send_json_error( [ 'message' => __( 'Ten adres jest już zapisany.', 'salon-win' ) ] );
    }

    $subscribers[] = $email;
    update_option( 'sw_newsletter_subscribers', $subscribers );

    wp_send_json_success( [ 'message' => __( 'Dziękujemy za zapis. Będziemy wysyłać spokojne wiadomości o pobytach, warsztatach i wydarzeniach Salon Win.', 'salon-win' ) ] );
}
add_action( 'wp_ajax_salon_win_newsletter',        'salon_win_handle_newsletter' );
add_action( 'wp_ajax_nopriv_salon_win_newsletter', 'salon_win_handle_newsletter' );

/* ===================================================
   10. WOOCOMMERCE TWEAKS
=================================================== */
// Remove default WooCommerce styles (we use our own)
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

// Custom loop product columns
add_filter( 'loop_shop_columns', function() { return 4; } );

// Items per page
add_filter( 'loop_shop_per_page', function() { return 12; } );

// Breadcrumb separator
add_filter( 'woocommerce_breadcrumb_defaults', function( $args ) {
    $args['delimiter'] = '&nbsp;&mdash;&nbsp;';
    return $args;
} );

// Cart fragments for AJAX cart update
add_filter( 'woocommerce_add_to_cart_fragments', function( $fragments ) {
    if ( ! WC()->cart ) {
        return $fragments;
    }
    ob_start();
    $count = WC()->cart->get_cart_contents_count();
    echo '<span class="cart-count">' . esc_html( $count ) . '</span>';
    $fragments['.cart-count'] = ob_get_clean();
    return $fragments;
} );

/* ===================================================
   11. REZERWACJA CPT (wewnętrzny)
=================================================== */
function salon_win_register_rezerwacja() {
    register_post_type( 'rezerwacja', [
        'labels' => [
            'name'          => __( 'Rezerwacje',  'salon-win' ),
            'singular_name' => __( 'Rezerwacja',  'salon-win' ),
            'menu_name'     => __( 'Rezerwacje',  'salon-win' ),
        ],
        'public'   => false,
        'show_ui'  => true,
        'menu_icon'=> 'dashicons-calendar-alt',
        'supports' => [ 'title', 'custom-fields' ],
    ] );
}
add_action( 'init', 'salon_win_register_rezerwacja' );

add_filter( 'manage_rezerwacja_posts_columns', function( $columns ) {
    return [
        'cb'      => $columns['cb'] ?? '',
        'title'   => __( 'Rezerwacja', 'salon-win' ),
        'type'    => __( 'Rodzaj', 'salon-win' ),
        'date_in' => __( 'Termin', 'salon-win' ),
        'guests'  => __( 'Osoby', 'salon-win' ),
        'status'  => __( 'Status', 'salon-win' ),
        'date'    => __( 'Wpłynęło', 'salon-win' ),
    ];
} );

add_action( 'manage_rezerwacja_posts_custom_column', function( $column, $post_id ) {
    if ( 'type' === $column ) {
        echo esc_html( get_post_meta( $post_id, 'sw_booking_type', true ) ?: '—' );
    }

    if ( 'date_in' === $column ) {
        $date = get_post_meta( $post_id, 'sw_booking_date', true );
        $time = get_post_meta( $post_id, 'sw_booking_time', true );
        echo esc_html( trim( $date . ' ' . $time ) ?: '—' );
    }

    if ( 'guests' === $column ) {
        echo esc_html( get_post_meta( $post_id, 'sw_booking_guests', true ) ?: '—' );
    }

    if ( 'status' === $column ) {
        echo esc_html( ucfirst( get_post_meta( $post_id, 'sw_booking_status', true ) ?: 'nowa' ) );
    }
}, 10, 2 );

/* ===================================================
   12. EXCERPT LENGTH
=================================================== */
add_filter( 'excerpt_length', function() { return 25; } );
add_filter( 'excerpt_more',   function() { return '&hellip;'; } );

/* ===================================================
   13. BODY CLASS
=================================================== */
add_filter( 'body_class', function( $classes ) {
    if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
        $classes[] = 'is-shop';
    }
    return $classes;
} );

/* ===================================================
   14. DISABLE COMMENTS (optional — remove if needed)
=================================================== */
// Uncomment to disable comments site-wide:
// add_filter( 'comments_open', '__return_false', 20, 2 );

/* ===================================================
   15. SECURITY HEADERS
=================================================== */
add_action( 'send_headers', function() {
    header( 'X-Content-Type-Options: nosniff' );
    header( 'X-Frame-Options: SAMEORIGIN' );
    header( 'Referrer-Policy: strict-origin-when-cross-origin' );
} );

/* ===================================================
   16. SCHEMA.ORG — LOCAL BUSINESS
=================================================== */
function salon_win_schema_markup() {
    if ( ! is_front_page() ) return;
    $phone = get_option( 'sw_phone', '13 445 05 90' );
    $schema = [
        '@context'    => 'https://schema.org',
        '@type'       => 'BarOrPub',
        'name'        => 'Salon Win',
        'url'         => 'https://salon-win.pl',
        'telephone'   => salon_win_normalize_phone( $phone ),
        'email'       => get_option( 'sw_email', 'salon-win@salon-win.pl' ),
        'address'     => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => get_option( 'sw_address',     'ul. Wyspiańskiego 16' ),
            'postalCode'      => get_option( 'sw_postal_code', '38-200' ),
            'addressLocality' => get_option( 'sw_city',        'Jasło' ),
            'addressCountry'  => 'PL',
        ],
        'openingHours' => [
            'We-Th 13:00-21:00',
            'Fr-Sa 13:00-22:00',
            'Su 13:00-20:00',
        ],
        'servesCuisine' => 'Wine Bar',
        'priceRange'   => 'PLN',
    ];
    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
}
add_action( 'wp_head', 'salon_win_schema_markup' );

/* ===================================================
   17. HELPER FUNCTIONS
=================================================== */

/**
 * Normalize a Polish phone number to international format.
 */
function salon_win_normalize_phone( $phone ) {
    $digits = preg_replace( '/\D+/', '', (string) $phone );

    if ( str_starts_with( $digits, '48' ) ) {
        return '+' . $digits;
    }

    return '+48' . $digits;
}

/**
 * Return a callable telephone URI.
 */
function salon_win_phone_url( $phone ) {
    return 'tel:' . salon_win_normalize_phone( $phone );
}

/**
 * Return the restaurant opening schedule.
 */
function salon_win_restaurant_hours() {
    return [
        [ 'day' => __( 'Poniedziałek', 'salon-win' ), 'hours' => get_option( 'sw_hours_monday', 'tel.' ) ],
        [ 'day' => __( 'Wtorek',       'salon-win' ), 'hours' => get_option( 'sw_hours_tuesday', 'tel.' ) ],
        [ 'day' => __( 'Środa',        'salon-win' ), 'hours' => get_option( 'sw_hours_wednesday', '13:00–21:00' ) ],
        [ 'day' => __( 'Czwartek',     'salon-win' ), 'hours' => get_option( 'sw_hours_thursday', '13:00–21:00' ) ],
        [ 'day' => __( 'Piątek',       'salon-win' ), 'hours' => get_option( 'sw_hours_friday', '13:00–22:00' ) ],
        [ 'day' => __( 'Sobota',       'salon-win' ), 'hours' => get_option( 'sw_hours_saturday', '13:00–22:00' ) ],
        [ 'day' => __( 'Niedziela',    'salon-win' ), 'hours' => get_option( 'sw_hours_sunday', '13:00–20:00' ) ],
    ];
}

/**
 * Render star rating HTML
 */
function salon_win_stars( $rating = 5 ) {
    $rating = min( 5, max( 0, intval( $rating ) ) );
    $html = '<span class="review-stars">';
    for ( $i = 1; $i <= 5; $i++ ) {
        $class = $i <= $rating ? 'fas fa-star' : 'far fa-star';
        $html .= '<span><i class="' . $class . '"></i></span>';
    }
    $html .= '</span>';
    return $html;
}

/**
 * Get upcoming events
 */
function salon_win_get_events( $limit = 5 ) {
    return get_posts( [
        'post_type'      => 'degustacja',
        'posts_per_page' => $limit,
        'meta_key'       => 'sw_date',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
        'meta_query'     => [ [
            'key'     => 'sw_date',
            'value'   => current_time( 'Y-m-d' ),
            'compare' => '>=',
            'type'    => 'DATE',
        ] ],
    ] );
}

/**
 * Get reviews
 */
function salon_win_get_reviews( $limit = 10 ) {
    return get_posts( [
        'post_type'      => 'opinia',
        'posts_per_page' => $limit,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ] );
}

/**
 * Format Polish price
 */
function salon_win_price( $price ) {
    return number_format( floatval( $price ), 2, ',', ' ' ) . ' zł';
}

/* ===================================================
   18. THEME OPTIONS (Customizer)
=================================================== */
add_action( 'customize_register', function( $wp_customize ) {
    // Panel: Salon Win
    $wp_customize->add_panel( 'salon_win_panel', [
        'title'    => __( 'Salon Win', 'salon-win' ),
        'priority' => 10,
    ] );

    // Section: Kontakt
    $wp_customize->add_section( 'sw_contact', [
        'title' => __( 'Dane Kontaktowe', 'salon-win' ),
        'panel' => 'salon_win_panel',
    ] );

    $contact_settings = [
        'sw_phone'          => [ 'label' => 'Telefon',             'default' => '13 445 05 90' ],
        'sw_email'          => [ 'label' => 'E-mail',              'default' => 'salon-win@salon-win.pl' ],
        'sw_address'        => [ 'label' => 'Adres',               'default' => 'ul. Wyspiańskiego 16' ],
        'sw_postal_code'    => [ 'label' => 'Kod pocztowy',        'default' => '38-200' ],
        'sw_city'           => [ 'label' => 'Miasto',              'default' => 'Jasło' ],
        'sw_hours_monday'   => [ 'label' => 'Restauracja – pon.',  'default' => 'tel.' ],
        'sw_hours_tuesday'  => [ 'label' => 'Restauracja – wt.',   'default' => 'tel.' ],
        'sw_hours_wednesday'=> [ 'label' => 'Restauracja – śr.',   'default' => '13:00–21:00' ],
        'sw_hours_thursday' => [ 'label' => 'Restauracja – czw.',  'default' => '13:00–21:00' ],
        'sw_hours_friday'   => [ 'label' => 'Restauracja – pt.',   'default' => '13:00–22:00' ],
        'sw_hours_saturday' => [ 'label' => 'Restauracja – sob.',  'default' => '13:00–22:00' ],
        'sw_hours_sunday'   => [ 'label' => 'Restauracja – niedz.','default' => '13:00–20:00' ],
        'sw_hotel_day'      => [ 'label' => 'Doba hotelowa',       'default' => '14:00–11:00' ],
        'sw_booking_url' => [ 'label' => 'URL Rezerwacji (Booking.com lub własny)', 'default' => 'https://www.booking.com/index.pl.html?aid=304142&label=gen173nr-10CAEoggI46AdIM1gEaLYBiAEBmAEzuAEXyAEM2AED6AEB-AEBiAIBqAIBuALGyr7RBsACAdICJGY4YjBkYTY5LTdkYzgtNDU0Zi1hZTZkLWZjNjBiZjkzYzIxMdgCAeACAQ' ],
        'sw_instagram'   => [ 'label' => 'Instagram URL', 'default' => '#' ],
        'sw_facebook'    => [ 'label' => 'Facebook URL',  'default' => '#' ],
        'sw_maps_embed'  => [ 'label' => 'Embed URL Map', 'default' => '' ],
    ];

    foreach ( $contact_settings as $key => $args ) {
        $wp_customize->add_setting( $key, [ 'default' => $args['default'], 'sanitize_callback' => 'sanitize_text_field' ] );
        $wp_customize->add_control( $key, [
            'label'   => __( $args['label'], 'salon-win' ),
            'section' => 'sw_contact',
            'type'    => 'text',
        ] );
    }
} );
