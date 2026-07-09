<?php
/**
 * single.php — Widok pojedynczego wpisu
 */

get_header();
?>

<main id="main-content" class="sw-subpage-main">
    <?php while ( have_posts() ) : the_post(); ?>

    <!-- Hero image if exists -->
    <?php if ( has_post_thumbnail() ) : ?>
    <div class="sw-post-hero">
        <?php the_post_thumbnail( 'sw-hero', [ 'class' => 'sw-post-hero-image', 'loading' => 'eager' ] ); ?>
        <div class="sw-post-hero-overlay" aria-hidden="true"></div>
    </div>
    <?php endif; ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class( 'sw-singular-view sw-post-view' ); ?>>
        <div class="container--narrow">

            <!-- Meta -->
            <div class="sw-post-meta">
                <?php the_category( ' &middot; ' ); ?>
                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="body-sm text-muted">
                    <?php echo esc_html( get_the_date() ); ?>
                </time>
            </div>

            <h1 class="display-md sw-page-title"><?php the_title(); ?></h1>

            <?php if ( get_the_excerpt() ) : ?>
            <p class="body-lg sw-post-excerpt">
                <?php the_excerpt(); ?>
            </p>
            <?php endif; ?>

            <div class="entry-content sw-entry-content">
                <?php the_content(); ?>
            </div>

            <!-- Tags -->
            <?php the_tags( '<div class="sw-post-tags">', '', '</div>' ); ?>

            <!-- Navigation -->
            <nav class="sw-post-nav" aria-label="<?php esc_attr_e( 'Nawigacja wpisów', 'salon-win' ); ?>">
                <?php previous_post_link( '<div class="sw-post-nav-item sw-post-nav-prev">%link</div>', '<i class="fas fa-arrow-left" aria-hidden="true"></i> %title' ); ?>
                <?php next_post_link(     '<div class="sw-post-nav-item sw-post-nav-next">%link</div>', '%title <i class="fas fa-arrow-right" aria-hidden="true"></i>' ); ?>
            </nav>

        </div>
    </article>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
