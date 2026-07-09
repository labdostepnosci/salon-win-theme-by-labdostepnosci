<?php
/**
 * page.php — Strony statyczne WordPress
 */

get_header();
?>

<main id="main-content" class="sw-subpage-main">
    <?php while ( have_posts() ) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class( 'sw-singular-view sw-page-view' ); ?>>
        <header class="sw-page-hero">
            <div class="container--narrow">
                <span class="eyebrow"><?php esc_html_e( 'Salon Win', 'salon-win' ); ?></span>
                <h1 class="display-md sw-page-title"><?php the_title(); ?></h1>
            </div>
        </header>

        <?php if ( has_post_thumbnail() ) : ?>
        <div class="container sw-featured-wrap">
            <?php the_post_thumbnail( 'sw-hero', [ 'class' => 'sw-featured-image', 'loading' => 'eager' ] ); ?>
        </div>
        <?php endif; ?>

        <div class="sw-content-section">
            <div class="container--narrow">
                <div class="entry-content sw-entry-content">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    </article>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
