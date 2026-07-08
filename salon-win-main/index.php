<?php
/**
 * index.php — WordPress fallback template
 * Służy jako template dla wszystkich typów stron bez dedykowanego szablonu.
 */

get_header();
?>

<main id="main-content" class="sw-archive-main section">
    <div class="container">

        <?php if ( have_posts() ) : ?>

            <div class="section-header sw-archive-header">
                <span class="eyebrow">
                    <?php
                    if ( is_category() )      single_cat_title( '', true );
                    elseif ( is_tag() )        single_tag_title( '', true );
                    elseif ( is_author() )     the_author();
                    elseif ( is_date() )       echo get_the_date( 'F Y' );
                    elseif ( is_search() )     printf( esc_html__( 'Wyniki dla: %s', 'salon-win' ), get_search_query() );
                    else                       esc_html_e( 'Salon Win', 'salon-win' );
                    ?>
                </span>
                <h1 class="display-md" style="margin-top: 0.5rem;">
                    <?php
                    if ( is_home() )           esc_html_e( 'Blog', 'salon-win' );
                    elseif ( is_archive() )    the_archive_title( '', '' );
                    elseif ( is_search() )     esc_html_e( 'Wyniki wyszukiwania', 'salon-win' );
                    else                       single_post_title();
                    ?>
                </h1>
            </div>

            <div class="sw-post-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'wine-card sw-archive-card' ); ?>>
                    <?php if ( has_post_thumbnail() ) : ?>
                    <?php
                    $thumbnail_id  = get_post_thumbnail_id();
                    $thumbnail_alt = trim( (string) get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ) );
                    ?>
                    <a href="<?php the_permalink(); ?>" class="wine-card-image sw-archive-image-link" aria-label="<?php echo esc_attr( sprintf( __( 'Przejdź do wpisu: %s', 'salon-win' ), get_the_title() ) ); ?>">
                        <?php
                        the_post_thumbnail(
                            'sw-gallery',
                            [
                                'loading' => 'lazy',
                                'alt'     => $thumbnail_alt ?: get_the_title(),
                            ]
                        );
                        ?>
                    </a>
                    <?php else : ?>
                    <div class="wine-card-image sw-archive-image-fallback" aria-hidden="true">
                        <span><?php esc_html_e( 'Salon Win', 'salon-win' ); ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="wine-card-body">
                        <time class="wine-region" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                            <?php echo esc_html( get_the_date() ); ?>
                        </time>
                        <h2 class="wine-name sw-archive-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <p class="body-sm text-muted sw-archive-excerpt">
                            <?php echo wp_kses_post( wp_trim_words( get_the_excerpt(), 36, '&hellip;' ) ); ?>
                        </p>
                        <a href="<?php the_permalink(); ?>" class="btn btn-ghost sw-archive-link" aria-label="<?php echo esc_attr( sprintf( __( 'Przeczytaj cały wpis: %s', 'salon-win' ), get_the_title() ) ); ?>">
                            <span><?php esc_html_e( 'Przeczytaj cały wpis', 'salon-win' ); ?></span>
                        </a>
                    </div>
                </article>
                <?php endwhile; ?>
            </div>

            <div class="sw-pagination-wrap">
                <?php the_posts_pagination( [
                    'prev_text' => '<i class="fas fa-arrow-left"></i>',
                    'next_text' => '<i class="fas fa-arrow-right"></i>',
                    'class'     => 'sw-pagination',
                ] ); ?>
            </div>

        <?php else : ?>

            <div class="text-center sw-empty-state">
                <span class="eyebrow"><?php esc_html_e( '404', 'salon-win' ); ?></span>
                <h1 class="display-md sw-page-title">
                    <?php esc_html_e( 'Strona nie istnieje', 'salon-win' ); ?>
                </h1>
                <p class="body-lg text-muted">
                    <?php esc_html_e( 'Nie znaleźliśmy szukanej strony. Wróć na stronę główną albo zobacz apartamenty, wydarzenia i spokojne pobyty w Salon Win.', 'salon-win' ); ?>
                </p>
                <div class="sw-empty-actions">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
                        <span><?php esc_html_e( 'Wróć na stronę główną', 'salon-win' ); ?></span>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/wina' ) ); ?>" class="btn btn-ghost">
                        <span><?php esc_html_e( 'Zobacz wina Salon Win', 'salon-win' ); ?></span>
                    </a>
                </div>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
