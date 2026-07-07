<?php
/**
 * single.php — Widok pojedynczego wpisu
 */

get_header();
?>

<main id="main-content" style="padding-top: 100px;">
    <?php while ( have_posts() ) : the_post(); ?>

    <!-- Hero image if exists -->
    <?php if ( has_post_thumbnail() ) : ?>
    <div style="width:100%; max-height:520px; overflow:hidden; position:relative;">
        <?php the_post_thumbnail( 'sw-hero', [ 'style' => 'width:100%;height:520px;object-fit:cover;', 'loading' => 'eager' ] ); ?>
        <div style="position:absolute;inset:0;background:linear-gradient(to bottom,transparent 50%,var(--color-cream) 100%);"></div>
    </div>
    <?php endif; ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> style="padding: var(--space-lg) 0 var(--space-xl);">
        <div class="container--narrow">

            <!-- Meta -->
            <div class="flex-between" style="margin-bottom: var(--space-md); flex-wrap:wrap; gap: 0.5rem;">
                <?php the_category( ' &middot; ' ); ?>
                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="body-sm text-muted">
                    <?php echo esc_html( get_the_date() ); ?>
                </time>
            </div>

            <h1 class="display-md" style="margin-bottom: var(--space-md);"><?php the_title(); ?></h1>

            <?php if ( get_the_excerpt() ) : ?>
            <p class="body-lg text-muted" style="margin-bottom: var(--space-md); border-left: 3px solid var(--color-gold); padding-left: var(--space-sm);">
                <?php the_excerpt(); ?>
            </p>
            <?php endif; ?>

            <div class="entry-content" style="line-height:1.85; color: var(--color-ink);">
                <?php the_content(); ?>
            </div>

            <!-- Tags -->
            <?php the_tags( '<div class="mt-lg" style="display:flex;flex-wrap:wrap;gap:0.5rem;">', '', '</div>' ); ?>

            <!-- Navigation -->
            <nav style="display:grid; grid-template-columns:1fr 1fr; gap:var(--space-sm); margin-top:var(--space-xl); border-top:1px solid rgba(26,10,0,0.1); padding-top:var(--space-md);">
                <?php previous_post_link( '<div class="btn btn-ghost">%link</div>', '<i class="fas fa-arrow-left" style="margin-right:0.5rem;"></i> %title' ); ?>
                <?php next_post_link(     '<div class="btn btn-ghost" style="text-align:right;">%link</div>', '%title <i class="fas fa-arrow-right" style="margin-left:0.5rem;"></i>' ); ?>
            </nav>

        </div>
    </article>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
