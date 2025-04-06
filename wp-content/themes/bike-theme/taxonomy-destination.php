<?php
/**
 * The template for displaying destination taxonomy archives
 *
 * @package Bike_Theme
 */

get_header();

// Get current destination term
$term = get_queried_object();

// Get destination image
$image_id = get_term_meta($term->term_id, 'destination_image', true);
$image_url = wp_get_attachment_url($image_id);
if (!$image_url) {
    $image_url = get_template_directory_uri() . '/assets/images/bikes/destination-default.jpg';
}

// Get category counts for this destination
$category_counts = bike_theme_count_tours_by_category_in_destination($term->term_id);
?>

<main id="primary" class="site-main">
    <!-- Destination Header Banner -->
    <div class="container-fluid page-header mb-2 py-5" style="background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?php echo esc_url($image_url); ?>') center center no-repeat; background-size: cover;">
        <div class="container py-5">
            <h1 class="display-3 text-white mb-3 animated slideInDown"><?php echo esc_html($term->name); ?></h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb text-uppercase">
                    <li class="breadcrumb-item"><a class="text-white" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'bike-theme'); ?></a></li>
                    <li class="breadcrumb-item"><a class="text-white" href="<?php echo esc_url(get_post_type_archive_link('bike_tour')); ?>"><?php esc_html_e('Tours', 'bike-theme'); ?></a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page"><?php echo esc_html($term->name); ?></li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Destination Header Banner End -->

    <!-- Destination Information -->
    <div class="container-xxl py-5">
        <div class="row g-5">
            <div class="col-lg-8">

                <!-- Tours List Start -->
                <div class="row g-4">
                    <?php
                    // Set up custom query with filters
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    $args = array(
                        'post_type' => 'bike_tour',
                        'posts_per_page' => 9,
                        'paged' => $paged,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'destination',
                                'field' => 'term_id',
                                'terms' => $term->term_id,
                            ),
                        ),
                    );

                    // Add meta query if filters are active
                    $meta_query = array();

                    // Duration filter
                    if (isset($_GET['duration']) && !empty($_GET['duration'])) {
                        switch ($_GET['duration']) {
                            case '1-3':
                                $meta_query[] = array(
                                    'key' => '_tour_duration',
                                    'value' => array(1, 3),
                                    'type' => 'numeric',
                                    'compare' => 'BETWEEN'
                                );
                                break;
                            case '4-7':
                                $meta_query[] = array(
                                    'key' => '_tour_duration',
                                    'value' => array(4, 7),
                                    'type' => 'numeric',
                                    'compare' => 'BETWEEN'
                                );
                                break;
                            case '8+':
                                $meta_query[] = array(
                                    'key' => '_tour_duration',
                                    'value' => 8,
                                    'type' => 'numeric',
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

                    if (!empty($meta_query)) {
                        $args['meta_query'] = $meta_query;
                    }

                    $tour_query = new WP_Query($args);

                    if ($tour_query->have_posts()) :
                        while ($tour_query->have_posts()) : $tour_query->the_post();
                            $duration = get_post_meta(get_the_ID(), '_tour_duration', true);
                            $distance = get_post_meta(get_the_ID(), '_tour_distance', true);
                            $difficulty = get_post_meta(get_the_ID(), '_tour_difficulty', true);
                            $price = bike_theme_get_tour_price(get_the_ID());
                            $flexible_pricing = get_post_meta(get_the_ID(), '_tour_flexible_pricing_enabled', true) === '1';
                    ?>
                            <div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
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
                                                    <span><i class="fa fa-clock me-2"></i><?php echo esc_html($duration); ?> <?php esc_html_e('Days', 'bike-theme'); ?></span>
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
                                            <a class="btn btn-sm btn-dark rounded py-2 px-4" href="<?php echo esc_url(get_permalink(get_option('bike_theme_booking_page'))); ?>?tour=<?php the_ID(); ?>"><?php esc_html_e('Book Now', 'bike-theme'); ?></a>
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
                <!-- Tours List End -->

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
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow mb-4">
                    <div class="card-body">
                        <h4 class="mb-3"><?php esc_html_e('Tour Available', 'bike-theme'); ?></h4>
                        <div class="destination-category-list">
                            <?php echo bike_theme_display_destination_categories($term->term_id, $term->slug); ?>
                        </div>
                    </div>
                </div>
                
                <div class="card border-0 shadow mb-4">
                    <div class="card-body">
                        <h4 class="mb-3"><?php esc_html_e('Need Assistance?', 'bike-theme'); ?></h4>
                        <p><?php esc_html_e('Contact our tour experts for personalized tour recommendations or special requirements.', 'bike-theme'); ?></p>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fa fa-phone-alt text-primary me-2"></i>
                            <p class="mb-0"><?php echo bike_theme_get_option('phone', '+012 345 6789'); ?></p>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fa fa-envelope-open text-primary me-2"></i>
                            <p class="mb-0"><?php echo bike_theme_get_option('email', 'info@example.com'); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <h4 class="mb-3"><?php esc_html_e('Other Destinations', 'bike-theme'); ?></h4>
                        <div class="destination-category-list">
                            <div class="destination-categories">
                            <ul class="list-unstyled">
                            <?php
                            // Get other destinations
                            $other_destinations = get_terms(array(
                                'taxonomy' => 'destination',
                                'hide_empty' => false,
                                'exclude' => array($term->term_id),
                                'number' => 6
                            ));
                            
                            if (!empty($other_destinations) && !is_wp_error($other_destinations)) :
                                foreach ($other_destinations as $other_destination) :
                                    $other_image_id = get_term_meta($other_destination->term_id, 'destination_image', true);
                                    $other_image_url = wp_get_attachment_url($other_image_id);
                                    if (!$other_image_url) {
                                        $other_image_url = get_template_directory_uri() . '/assets/images/bikes/destination-default.jpg';
                                    }
                            ?>
                            
                                <li>
                                    <a href="/destination/<?php echo esc_attr($other_destination->slug); ?>">
                                        <?php echo esc_html($other_destination->name); ?> <span class="badge bg-primary rounded-pill"><?php echo esc_html($other_destination->count); ?></span>
                                    </a>
                                </li>
                            
                            <?php
                            endforeach;
                            endif;
                            ?>
                            </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Destination Information End -->
</main>

<?php
get_footer();
?> 