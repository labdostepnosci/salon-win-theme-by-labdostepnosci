<?php
/**
 * index.php — WordPress fallback template
 * Służy jako template dla wszystkich typów stron bez dedykowanego szablonu.
 */

get_header();
?>

<main id="main-content" class="section" style="min-height: 60vh; padding-top: 120px;">
    <div class="container">

        <?php if ( have_posts() ) : ?>

            <div class="section-header" style="margin-bottom: var(--space-lg);">
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

            <div class="grid-3" style="margin-bottom: var(--space-xl);">
                <?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'wine-card' ); ?> style="background: var(--color-white); border: 1px solid rgba(26,10,0,0.08);">
                    <?php if ( has_post_thumbnail() ) : ?>
                    <a href="<?php the_permalink(); ?>" class="wine-card-image" tabindex="-1">
                        <?php the_post_thumbnail( 'sw-gallery', [ 'loading' => 'lazy', 'alt' => get_the_title() ] ); ?>
                    </a>
                    <?php endif; ?>
                    <div class="wine-card-body">
                        <span class="wine-region"><?php echo get_the_date(); ?></span>
                        <h2 class="wine-name" style="color: var(--color-ink);">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <p class="body-sm text-muted" style="margin-top: 0.5rem;"><?php the_excerpt(); ?></p>
                        <a href="<?php the_permalink(); ?>" class="btn btn-ghost" style="margin-top: var(--space-sm); padding: 0.6rem 1.25rem;">
                            <span><?php esc_html_e( 'Czytaj więcej', 'salon-win' ); ?></span>
                        </a>
                    </div>
                </article>
                <?php endwhile; ?>
            </div>

            <div style="display: flex; justify-content: center;">
                <?php the_posts_pagination( [
                    'prev_text' => '<i class="fas fa-arrow-left"></i>',
                    'next_text' => '<i class="fas fa-arrow-right"></i>',
                    'class'     => 'sw-pagination',
                ] ); ?>
            </div>

        <?php else : ?>

            <div class="text-center" style="padding: var(--space-xl) 0;">
                <span class="eyebrow"><?php esc_html_e( '404', 'salon-win' ); ?></span>
                <h1 class="display-md" style="margin: var(--space-sm) 0;">
                    <?php esc_html_e( 'Strona nie istnieje', 'salon-win' ); ?>
                </h1>
                <p class="body-lg text-muted" style="max-width: 40ch; margin: 0 auto var(--space-lg);">
                    <?php esc_html_e( 'Nie znaleźliśmy szukanej strony. Wróć na stronę główną lub przeglądaj naszą ofertę.', 'salon-win' ); ?>
                </p>
                <div style="display: flex; gap: var(--space-sm); justify-content: center; flex-wrap: wrap;">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
                        <span><?php esc_html_e( 'Strona główna', 'salon-win' ); ?></span>
                    </a>
                    <a href="<?php echo esc_url( home_url( '/wina' ) ); ?>" class="btn btn-ghost">
                        <span><?php esc_html_e( 'Przeglądaj wina', 'salon-win' ); ?></span>
                    </a>
                </div>
            </div>

        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
