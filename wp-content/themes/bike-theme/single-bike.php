<?php
/**
 * The template for displaying single bike posts
 *
 * @package Bike_Theme
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header><!-- .entry-header -->

                <div class="bike-single">
                    <div class="bike-single-image">
                        <?php
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('large');
                        } else {
                            echo '<img src="' . get_template_directory_uri() . '/assets/images/default-bike.jpg" alt="' . get_the_title() . '">';
                        }
            ?>
                    </div>

                    <div class="bike-single-content">
                        <?php
            // Get and display custom field for price
            $price = get_post_meta(get_the_ID(), 'bike_price', true);
            if (! empty($price)) {
                echo '<div class="bike-single-price">$' . esc_html($price) . '</div>';
            }
            ?>

                        <div class="bike-single-description">
                            <?php the_content(); ?>
                        </div>

                        <div class="bike-single-meta">
                            <?php
                // Display bike categories
                $bike_categories = get_the_terms(get_the_ID(), 'bike_category');
            if ($bike_categories && ! is_wp_error($bike_categories)) {
                echo '<div class="bike-single-meta-item">';
                echo '<span class="bike-single-meta-label">' . esc_html__('Categories:', 'bike-theme') . '</span> ';
                $bike_category_names = array();
                foreach ($bike_categories as $bike_category) {
                    $bike_category_names[] = '<a href="' . esc_url(get_term_link($bike_category)) . '">' . esc_html($bike_category->name) . '</a>';
                }
                echo implode(', ', $bike_category_names);
                echo '</div>';
            }

            // Display additional specs if they exist
            $specs = array(
                'bike_weight' => __('Weight:', 'bike-theme'),
                'bike_frame' => __('Frame:', 'bike-theme'),
                'bike_gears' => __('Gears:', 'bike-theme'),
                'bike_color' => __('Colors:', 'bike-theme'),
            );

            foreach ($specs as $meta_key => $label) {
                $value = get_post_meta(get_the_ID(), $meta_key, true);
                if (! empty($value)) {
                    echo '<div class="bike-single-meta-item">';
                    echo '<span class="bike-single-meta-label">' . esc_html($label) . '</span> ';
                    echo esc_html($value);
                    echo '</div>';
                }
            }
            ?>
                        </div>
                    </div>
                </div>

                <footer class="entry-footer">
                    <?php bike_theme_entry_footer(); ?>
                </footer><!-- .entry-footer -->
            </article><!-- #post-<?php the_ID(); ?> -->

            <?php
            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) :
                comments_template();
            endif;
            ?>

            <div class="post-navigation">
                <?php
                the_post_navigation(
                    array(
                            'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'bike-theme') . '</span> <span class="nav-title">%title</span>',
                            'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'bike-theme') . '</span> <span class="nav-title">%title</span>',
                        )
                );
            ?>
            </div>
        <?php endwhile; // End of the loop.?>
    </div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();
