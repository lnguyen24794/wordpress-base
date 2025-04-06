<?php
/**
 * The template for displaying bike tour archive
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bike_Theme
 */

get_header();
?>

<main id="primary" class="site-main">
    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 p-0" style="background-image: url(<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/bikes/tour-banner.jpg);">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center pb-5">
                <h1 class="display-3 text-white mb-3 animated slideInDown"><?php esc_html_e('Our Bike Tours', 'bike-theme'); ?></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center text-uppercase">
                        <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'bike-theme'); ?></a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page"><?php esc_html_e('Bike Tours', 'bike-theme'); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Tours Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title text-center text-primary text-uppercase"><?php esc_html_e('Explore Vietnam', 'bike-theme'); ?></h6>
                <h1 class="mb-5"><?php echo wp_kses_post(__('Discover Our <span class="text-primary text-uppercase">Cycling Tours</span>', 'bike-theme')); ?></h1>
            </div>

            <!-- Tour Filter Start -->
            <div class="mb-5 wow fadeInUp" data-wow-delay="0.1s">
                <form action="/bike-tour" method="get" class="tour-filter">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <div class="form-floating">
                                <select class="form-select" id="filter-difficulty" name="difficulty">
                                    <option value=""><?php esc_html_e('Any Difficulty', 'bike-theme'); ?></option>
                                    <option value="easy" <?php selected(isset($_GET['difficulty']) && $_GET['difficulty'] == 'easy'); ?>><?php esc_html_e('Easy', 'bike-theme'); ?></option>
                                    <option value="moderate" <?php selected(isset($_GET['difficulty']) && $_GET['difficulty'] == 'moderate'); ?>><?php esc_html_e('Moderate', 'bike-theme'); ?></option>
                                    <option value="difficult" <?php selected(isset($_GET['difficulty']) && $_GET['difficulty'] == 'difficult'); ?>><?php esc_html_e('Difficult', 'bike-theme'); ?></option>
                                </select>
                                <label for="filter-difficulty"><?php esc_html_e('Difficulty', 'bike-theme'); ?></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <select class="form-select" id="filter-duration" name="duration">
                                    <option value=""><?php esc_html_e('Any Duration', 'bike-theme'); ?></option>
                                    <option value="1-3" <?php selected(isset($_GET['duration']) && $_GET['duration'] == '1-3'); ?>><?php esc_html_e('1-3 Days', 'bike-theme'); ?></option>
                                    <option value="4-7" <?php selected(isset($_GET['duration']) && $_GET['duration'] == '4-7'); ?>><?php esc_html_e('4-7 Days', 'bike-theme'); ?></option>
                                    <option value="8+" <?php selected(isset($_GET['duration']) && $_GET['duration'] == '8+'); ?>><?php esc_html_e('8+ Days', 'bike-theme'); ?></option>
                                </select>
                                <label for="filter-duration"><?php esc_html_e('Duration', 'bike-theme'); ?></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <?php
                                $categories = get_terms(array(
                                    'taxonomy' => 'tour_category',
                                    'hide_empty' => false,
                                ));
?>
                                <select class="form-select" id="filter-category" name="tour_category">
                                    <option value=""><?php esc_html_e('Any Category', 'bike-theme'); ?></option>
                                    <?php foreach ($categories as $category) : ?>
                                        <option value="<?php echo esc_attr($category->slug); ?>" <?php selected(isset($_GET['tour_category']) && $_GET['tour_category'] == $category->slug); ?>><?php echo esc_html($category->name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="filter-category"><?php esc_html_e('Category', 'bike-theme'); ?></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <?php
                                $destinations = get_terms(array(
                                    'taxonomy' => 'destination',
                                    'hide_empty' => false,
                                    'parent' => 0, // Get only top-level destinations
                                    'orderby' => 'count',
                                    'order' => 'DESC'
                                ));
                                ?>
                                <select class="form-select" id="filter-destination" name="destination">
                                    <option value=""><?php esc_html_e('Any Destination', 'bike-theme'); ?></option>
                                    <?php foreach ($destinations as $destination) : ?>
                                        <option value="<?php echo esc_attr($destination->slug); ?>" <?php selected(isset($_GET['destination']) && $_GET['destination'] == $destination->slug); ?>><?php echo esc_html($destination->name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="filter-destination"><?php esc_html_e('Destination', 'bike-theme'); ?></label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary w-100 py-3" type="submit"><?php esc_html_e('Filter Bike Tours', 'bike-theme'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Tour Filter End -->

            <div class="row g-4 justify-content-start">
                <?php
                // Set up custom query with filters
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                $args = array(
                    'post_type' => 'bike_tour',
                    'posts_per_page' => 9,
                    'paged' => $paged,
                );

                // Initialize tax_query array
                $tax_query = array();

                // Add meta query if filters are active
                $meta_query = array();

                // Duration filter
                if (isset($_GET['duration']) && !empty($_GET['duration'])) {
                    switch ($_GET['duration']) {
                        case '1-3':
                            $meta_query[] = array(
                                'key' => '_tour_duration_days',
                                'value' => array(1, 3),
                                'type' => 'NUMERIC',
                                'compare' => 'BETWEEN'
                            );
                            break;
                        case '4-7':
                            $meta_query[] = array(
                                'key' => '_tour_duration_days',
                                'value' => array(4, 7),
                                'type' => 'NUMERIC',
                                'compare' => 'BETWEEN'
                            );
                            break;
                        case '8+':
                            $meta_query[] = array(
                                'key' => '_tour_duration_days',
                                'value' => 8,
                                'type' => 'NUMERIC',
                                'compare' => '>='
                            );
                            break;
                    }
                }

                // Difficulty filter
                if (isset($_GET['difficulty']) && !empty($_GET['difficulty'])) {
                    $meta_query[] = array(
                        'key' => '_tour_difficulty',
                        'value' => sanitize_text_field($_GET['difficulty']),
                        'compare' => '='
                    );
                }

                // Category filter
                if (isset($_GET['tour_category']) && !empty($_GET['tour_category'])) {
                    $tax_query[] = array(
                        'taxonomy' => 'tour_category',
                        'field' => 'slug',
                        'terms' => sanitize_text_field($_GET['tour_category']),
                    );
                }

                // Destination filter
                if (isset($_GET['destination']) && !empty($_GET['destination'])) {
                    $tax_query[] = array(
                        'taxonomy' => 'destination',
                        'field' => 'slug',
                        'terms' => sanitize_text_field($_GET['destination']),
                    );
                }

                // Add tax_query to args if we have any taxonomies to filter
                if (!empty($tax_query)) {
                    if (count($tax_query) > 1) {
                        $tax_query['relation'] = 'AND';
                    }
                    $args['tax_query'] = $tax_query;
                }

                // Add meta_query to args if we have any meta fields to filter
                if (!empty($meta_query)) {
                    if (count($meta_query) > 1) {
                        $meta_query['relation'] = 'AND';
                    }
                    $args['meta_query'] = $meta_query;
                }

                $tour_query = new WP_Query($args);

            if ($tour_query->have_posts()) :
                while ($tour_query->have_posts()) : $tour_query->the_post();
                    $duration = bike_theme_get_tour_duration(get_the_ID());
                    $distance = get_post_meta(get_the_ID(), '_tour_distance', true);
                    $difficulty = get_post_meta(get_the_ID(), '_tour_difficulty', true);
                    $price = bike_theme_get_tour_price(get_the_ID());
                    $flexible_pricing = get_post_meta(get_the_ID(), '_tour_flexible_pricing_enabled', true) === '1';
                ?>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="tour-item shadow rounded">
                        <div class="position-relative">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('large', array('class' => 'img-fluid')); ?>
                                </a>
                            <?php else : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <img class="img-fluid" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/placeholder-tour.jpg" alt="<?php the_title_attribute(); ?>">
                                </a>
                            <?php endif; ?>
                            <div class="tour-overlay p-4">
                                <span class="tour-price"><?php echo bike_theme_format_price($price); ?>
                                    <?php if ($flexible_pricing) : ?>
                                        <small><?php esc_html_e('from', 'bike-theme'); ?></small>
                                    <?php endif; ?>
                                </span>
                                <div class="tour-meta">
                                    <?php if ($difficulty) : ?>
                                    <span><i class="fa fa-chart-line me-2"></i><?php echo esc_html($difficulty); ?></span>
                                    <?php endif; ?>
                                    <?php if ($duration) : ?>
                                    <span><i class="fa fa-clock me-2"></i><?php echo esc_html($duration); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 mt-2">
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="mb-0"><a href="<?php the_permalink(); ?>" class="text-dark"><?php the_title(); ?></a></h5>
                            </div>
                            <div class="d-flex mb-3">
                                <?php if ($distance) : ?>
                                <small class="border-end me-3 pe-3"><i class="fa fa-road text-primary me-2"></i><?php echo esc_html($distance); ?></small>
                                <?php endif; ?>
                                <?php 
                                $categories = get_the_terms(get_the_ID(), 'tour_category');
                                if ($categories && !is_wp_error($categories)) : 
                                    $category = reset($categories); // Get first category
                                ?>
                                <small><i class="fa fa-tag text-primary me-2"></i><?php echo esc_html($category->name); ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a class="btn btn-sm btn-primary rounded py-2 px-4" href="<?php the_permalink(); ?>"><?php esc_html_e('View Details', 'bike-theme'); ?></a>
                                <a class="btn btn-sm btn-dark rounded py-2 px-4" href="<?php the_permalink(); ?>#book-now"><?php esc_html_e('Book Bike Tour', 'bike-theme'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <div class="col-12 text-center">
                    <h3><?php esc_html_e('No bike tours found.', 'bike-theme'); ?></h3>
                    <p><?php esc_html_e('Please try different filter options or check back later.', 'bike-theme'); ?></p>
                </div>
                <?php
            endif;
            ?>
            </div>

            <!-- Pagination -->
            <div class="row mt-5">
                <div class="col-12">
                    <nav aria-label="Page navigation">
                        <?php
        $big = 999999999; // Need an unlikely integer
echo paginate_links(array(
    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
    'format' => '?paged=%#%',
    'current' => max(1, get_query_var('paged')),
    'total' => $tour_query->max_num_pages,
    'prev_text' => '<i class="fa fa-angle-left"></i>',
    'next_text' => '<i class="fa fa-angle-right"></i>',
    'type' => 'list',
    'end_size' => 3,
    'mid_size' => 3
));
?>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Tours End -->

</main><!-- #main -->

<?php
get_footer();
?> 