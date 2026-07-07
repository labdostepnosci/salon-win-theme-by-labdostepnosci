<?php
/**
 * page.php — Strony statyczne WordPress
 */

get_header();
?>

<main id="main-content" style="padding-top: 100px; min-height: 70vh;">
    <?php while ( have_posts() ) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="section">
            <div class="container--narrow">
                <h1 class="display-md" style="margin-bottom: var(--space-md);"><?php the_title(); ?></h1>
                <div class="entry-content" style="line-height:1.85;">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    </article>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
