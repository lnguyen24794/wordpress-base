<?php
/**
 * The template for displaying bike archive pages
 *
 * @package Bike_Theme
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e('Bikes', 'bike-theme'); ?></h1>
            <div class="archive-description">
                <p><?php esc_html_e('Explore our collection of high-quality bikes.', 'bike-theme'); ?></p>
            </div>
        </header><!-- .page-header -->

        <?php if (have_posts()) : ?>
            <div class="bike-archive">
                <?php
                /* Start the Loop */
                while (have_posts()) :
                    the_post();
                    ?>
                    <div class="bike-card">
                        <div class="bike-card-image">
                            <?php
                            if (has_post_thumbnail()) {
                                the_post_thumbnail('medium');
                            } else {
                                echo '<img src="' . get_template_directory_uri() . '/assets/images/default-bike.jpg" alt="' . get_the_title() . '">';
                            }
                    ?>
                        </div>
                        <div class="bike-card-content">
                            <h2 class="bike-card-title"><?php the_title(); ?></h2>
                            <?php
                    // Get and display custom field for price
                    $price = get_post_meta(get_the_ID(), 'bike_price', true);
                    if (! empty($price)) {
                        echo '<div class="bike-card-price">$' . esc_html($price) . '</div>';
                    }
                    ?>
                            <div class="bike-card-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            <a href="<?php the_permalink(); ?>" class="bike-card-button"><?php esc_html_e('View Details', 'bike-theme'); ?></a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div><!-- .bike-archive -->

            <?php
            the_posts_navigation(
                array(
                    'prev_text' => esc_html__('← Older Bikes', 'bike-theme'),
                    'next_text' => esc_html__('Newer Bikes →', 'bike-theme'),
                )
            );

else :
    get_template_part('template-parts/content', 'none');
endif;
?>
    </div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();
