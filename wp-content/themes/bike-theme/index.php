<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * Template Name: Home Page
 *
 * @package Bike_Theme
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php
    if (is_front_page()) :
        // Display homepage content
        ?>

        <!-- Hero Banner Start -->
        <div class="container-fluid p-0 mb-5">
            <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    // Get all slides from options
                    $slides = get_option('bike_theme_slides', array());

                    // If no slides found, create a default one
                    if (empty($slides)) {
                        $slides[] = array(
                            'image_id' => 0,
                            'image_url' => get_template_directory_uri() . '/assets/images/bikes/hero-1.jpg',
                            'subtitle' => __('Vietnam Cycling Tours', 'bike-theme'),
                            'title' => __('Discover Vietnam on Two Wheels', 'bike-theme'),
                            'btn1_text' => __('View Tours', 'bike-theme'),
                            'btn1_url' => get_post_type_archive_link('bike_tour'),
                            'btn2_text' => __('Book Now', 'bike-theme'),
                            'btn2_url' => get_permalink(get_option('bike_theme_booking_page')),
                            'active' => 1
                        );
                    }

                    $active_slides = 0;
                    $found_active = false;

                    foreach ($slides as $index => $slide) :
                        // Skip inactive slides
                        if (empty($slide['active'])) {
                            continue;
                        }

                        $active_slides++;

                        // Set image URL (use default if empty)
                        $image_url = !empty($slide['image_url']) ? $slide['image_url'] : get_template_directory_uri() . '/assets/images/bikes/hero-' . ($index % 3 + 1) . '.jpg';

                        // Get text values with defaults
                        $subtitle = !empty($slide['subtitle']) ? $slide['subtitle'] : '';
                        $title = !empty($slide['title']) ? $slide['title'] : '';
                        $btn1_text = !empty($slide['btn1_text']) ? $slide['btn1_text'] : '';
                        $btn1_url = !empty($slide['btn1_url']) ? $slide['btn1_url'] : '';
                        $btn2_text = !empty($slide['btn2_text']) ? $slide['btn2_text'] : '';
                        $btn2_url = !empty($slide['btn2_url']) ? $slide['btn2_url'] : '';

                        // Set first active slide as active
                        $is_active = false;
                        if (!$found_active) {
                            $is_active = true;
                            $found_active = true;
                        }
                        ?>
                                <div class="carousel-item <?php echo $is_active ? 'active' : ''; ?>">
                                    <img class="w-100" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>">
                                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                                        <div class="p-3" style="max-width: 700px;">
                                            <?php if (!empty($subtitle)) : ?>
                                            <h6 class="section-title text-white text-uppercase mb-3 animated slideInDown"><?php echo esc_html($subtitle); ?></h6>
                                            <?php endif; ?>
                                            <?php if (!empty($title)) : ?>
                                            <h1 class="display-3 text-white mb-4 animated slideInDown"><?php echo esc_html($title); ?></h1>
                                            <?php endif; ?>
                                            <?php if (!empty($btn1_text)) : ?>
                                            <a href="<?php echo esc_url($btn1_url); ?>" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft"><?php echo esc_html($btn1_text); ?></a>
                                            <?php endif; ?>
                                            <?php if (!empty($btn2_text)) : ?>
                                            <a href="<?php echo esc_url($btn2_url); ?>" class="btn btn-light py-md-3 px-md-5 animated slideInRight"><?php echo esc_html($btn2_text); ?></a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                    endforeach;

                     // If no active slides were found, display a default slide
                    if ($active_slides == 0) :
                    ?>
                    <div class="carousel-item active">
                        <img class="w-100" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/bikes/hero-1.jpg" alt="<?php esc_attr_e('Cycling Tour in Vietnam', 'bike-theme'); ?>">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <div class="p-3" style="max-width: 700px;">
                                <h6 class="section-title text-white text-uppercase mb-3 animated slideInDown"><?php esc_html_e('Vietnam Cycling Tours', 'bike-theme'); ?></h6>
                                <h1 class="display-3 text-white mb-4 animated slideInDown"><?php esc_html_e('Discover Vietnam on Two Wheels', 'bike-theme'); ?></h1>
                                <a href="<?php echo esc_url(get_post_type_archive_link('bike_tour')); ?>" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft"><?php esc_html_e('View Tours', 'bike-theme'); ?></a>
                                <a href="<?php echo esc_url(get_permalink(get_option('bike_theme_booking_page'))); ?>" class="btn btn-light py-md-3 px-md-5 animated slideInRight"><?php esc_html_e('Book Now', 'bike-theme'); ?></a>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <?php
                // Only show controls if we have more than one active slide
                if ($active_slides > 1) :
                    ?>
                <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden"><?php esc_html_e('Previous', 'bike-theme'); ?></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden"><?php esc_html_e('Next', 'bike-theme'); ?></span>
                </button>
                <?php endif; ?>
            </div>
        </div>
        <!-- Hero Banner End -->

        <!-- About Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6">
                        <h6 class="section-title text-start text-primary text-uppercase"><?php esc_html_e('About Us', 'bike-theme'); ?></h6>
                        <h1 class="mb-4"><?php echo wp_kses_post(__('Welcome to <span class="text-primary ">BeeBikeHub</span>', 'bike-theme')); ?></h1>
                        <p class="mb-4"><?php esc_html_e('We provide high-quality bicycle tours and rentals throughout Vietnam. With over 10 years of experience, we are proud to be pioneers in eco-friendly cycling tourism, exploring Vietnamese culture and nature.', 'bike-theme'); ?></p>
                        <div class="row g-3 pb-4">
                            <div class="col-sm-4 wow fadeIn" data-wow-delay="0.1s">
                                <div class="border rounded p-1">
                                    <div class="border rounded text-center p-4">
                                        <i class="fa fa-bicycle fa-2x text-primary mb-2"></i>
                                        <h2 class="mb-1" data-toggle="counter-up">20</h2>
                                        <p class="mb-0"><?php esc_html_e('Bicycles', 'bike-theme'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 wow fadeIn" data-wow-delay="0.3s">
                                <div class="border rounded p-1">
                                    <div class="border rounded text-center p-4">
                                        <i class="fa fa-map-marked-alt fa-2x text-primary mb-2"></i>
                                        <h2 class="mb-1" data-toggle="counter-up"><?php echo wp_count_posts('bike_tour')->publish; ?></h2>
                                        <p class="mb-0"><?php esc_html_e('Tours', 'bike-theme'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 wow fadeIn" data-wow-delay="0.5s">
                                <div class="border rounded p-1">
                                    <div class="border rounded text-center p-4">
                                        <i class="fa fa-users fa-2x text-primary mb-2"></i>
                                        <h2 class="mb-1" data-toggle="counter-up">100</h2>
                                        <p class="mb-0"><?php esc_html_e('Customers', 'bike-theme'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a class="btn btn-primary py-3 px-5 mt-2" href="<?php echo esc_url(get_page_link(get_page_by_title('About Us')->ID)); ?>"><?php esc_html_e('Learn More', 'bike-theme'); ?></a>
                    </div>
                    <div class="col-lg-6">
                        <div class="row g-3">
                            <?php
                            // Get all about slides from options
                            $about_slides = get_option('bike_theme_about_slides', array());

                            // If no slides found, create default ones
                            if (empty($about_slides)) {
                                $about_slides = array(
                                    array(
                                        'image_id' => 0,
                                        'image_url' => get_template_directory_uri() . '/assets/images/bikes/about-1.jpg',
                                        'active' => 1
                                    ),
                                    array(
                                        'image_id' => 0,
                                        'image_url' => get_template_directory_uri() . '/assets/images/bikes/about-2.jpg',
                                        'active' => 1
                                    ),
                                    array(
                                        'image_id' => 0,
                                        'image_url' => get_template_directory_uri() . '/assets/images/bikes/about-3.jpg',
                                        'active' => 1
                                    ),
                                    array(
                                        'image_id' => 0,
                                        'image_url' => get_template_directory_uri() . '/assets/images/bikes/about-4.jpg',
                                        'active' => 1
                                    )
                                );
                            }

                            $active_slides = 0;
                            $positions = array(
                                array('text-end', 'w-75', '25%', '0.1s'),
                                array('text-start', 'w-100', '0', '0.3s'),
                                array('text-end', 'w-50', '0', '0.5s'),
                                array('text-start', 'w-75', '0', '0.7s')
                            );

                            foreach ($about_slides as $index => $slide) :
                                // Skip inactive slides
                                if (empty($slide['active']) || $active_slides >= 4) {
                                    continue;
                                }

                                // Set image URL (use default if empty)
                                $image_url = !empty($slide['image_url']) ? $slide['image_url'] : get_template_directory_uri() . '/assets/images/bikes/about-' . ($index + 1) . '.jpg';
                                
                                // Get position settings
                                $position = $positions[$active_slides];
                            ?>
                                <div class="col-6 <?php echo $position[0]; ?>">
                                    <img class="img-fluid rounded <?php echo $position[1]; ?> wow zoomIn" 
                                        data-wow-delay="<?php echo $position[3]; ?>" 
                                        src="<?php echo esc_url($image_url); ?>" 
                                        <?php if ($position[2] !== '0') : ?>
                                        style="margin-top: <?php echo $position[2]; ?>"
                                        <?php endif; ?>>
                                </div>
                            <?php
                                $active_slides++;
                            endforeach;

                            // If no active slides were found, display defaults
                            if ($active_slides == 0) :
                                foreach ($positions as $index => $position) :
                                    $default_image = get_template_directory_uri() . '/assets/images/bikes/about-' . ($index + 1) . '.jpg';
                                ?>
                                    <div class="col-6 <?php echo $position[0]; ?>">
                                        <img class="img-fluid rounded <?php echo $position[1]; ?> wow zoomIn" 
                                            data-wow-delay="<?php echo $position[3]; ?>" 
                                            src="<?php echo esc_url($default_image); ?>"
                                            <?php if ($position[2] !== '0') : ?>
                                            style="margin-top: <?php echo $position[2]; ?>"
                                            <?php endif; ?>>
                                    </div>
                                <?php
                                endforeach;
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->

        <!-- Destinations Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="section-title text-center text-primary text-uppercase"><?php esc_html_e('Explore Destinations', 'bike-theme'); ?></h6>
                    <h1 class="mb-5"><?php esc_html_e('Popular Cycling Destinations', 'bike-theme'); ?></h1>
                </div>
                <div class="row g-4">
                    <?php
                    // Get all destinations
                    $destinations = get_terms(array(
                        'taxonomy' => 'destination',
                        'hide_empty' => true,
                        'parent' => 0, // Get only top-level destinations
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'number' => 6 // Limit to 6 destinations
                    ));

                    if (!empty($destinations) && !is_wp_error($destinations)) :
                        foreach ($destinations as $destination) :
                            // Get destination image
                                        $image_id = get_term_meta($destination->term_id, 'destination_image', true);
                                        $image_url = wp_get_attachment_url($image_id);
                                        if (!$image_url) {
                                            $image_url = get_template_directory_uri() . '/assets/images/bikes/destination-default.jpg';
                                        }

                    // Get tours count
                    $tours_count = $destination->count;
                    ?>
                    <!-- Tour Banner Start -->
                    <div class="container-xxl py-0 px-0 wow zoomIn" data-wow-delay="0.1s">
                        <div class="row g-0">
                            <div class="col-md-6 bg-dark d-flex align-items-center">
                                <div class="p-5">
                                    <h6 class="section-title text-start text-white text-uppercase mb-3"><?php esc_html_e('Featured Destination', 'bike-theme'); ?></h6>
                                    <h1 class="text-white mb-4"><?php echo esc_html($destination->name); ?></h1>
                                    <p class="text-white mb-4"><?php echo wp_trim_words($destination->description, 20, '...'); ?></p>
                                    <div class="row g-1 mb-4">
                                        <?php 
                                        // Get category counts for this destination
                                        $category_counts = bike_theme_count_tours_by_category_in_destination($destination->term_id);
                                        
                                        if (!empty($category_counts)) {
                                            $count = 0;
                                            foreach ($category_counts as $cat_id => $data) {
                                                if ($count >= 4) break; // Display maximum 4 categories
                                                $category = $data['category'];
                                                $cat_count = $data['count'];
                                                ?>
                                                <div class="col-6 mb-2">
                                                    <div class="bg-primary p-3">
                                                        <h2 class="text-white mb-0"><?php echo esc_html($cat_count); ?></h2>
                                                        <p class="text-white mb-0"><?php echo esc_html($category->name); ?></p>
                                                    </div>
                                                </div>
                                                <?php
                                                $count++;
                                            }
                                        }
                                        ?>
                                    </div>
                                    <a href="<?php echo esc_url(get_term_link($destination)); ?>" class="btn btn-primary py-md-3 px-md-5 me-3"><?php esc_html_e('View Details', 'bike-theme'); ?></a>
                                    <a href="/booking" class="btn btn-light py-md-3 px-md-5"><?php esc_html_e('Book Now', 'bike-theme'); ?></a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="video position-relative">
                                    <img class="img-fluid w-100 h-100" src="<?php echo esc_url($image_url); ?>" alt="<?php echo $destination->name; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Tour Banner End -->
                    <?php
                    endforeach;
                    endif;
                    ?>
                </div>
                <div class="text-center mt-5">
                    <a href="<?php echo esc_url(get_post_type_archive_link('bike_tour')); ?>" class="btn btn-primary py-3 px-5"><?php esc_html_e('View All Destinations', 'bike-theme'); ?></a>
                </div>
            </div>
        </div>
        <!-- Destinations End -->

         <!-- Featured Bikes Start -->
         <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="section-title text-center text-primary text-uppercase"><?php esc_html_e('Our Bikes', 'bike-theme'); ?></h6>
                    <h1 class="mb-5"><?php echo wp_kses_post(__('Explore Our <span class="text-primary text-uppercase">Bikes</span>', 'bike-theme')); ?></h1>
                </div>
                <?php include(get_template_directory() . '/template-parts/bikes.php'); ?>
            </div>
        </div>
        <!-- Featured Bikes End -->

        <!-- Service Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="section-title text-center text-primary text-uppercase"><?php esc_html_e('Our Services', 'bike-theme'); ?></h6>
                    <h1 class="mb-5"><?php echo wp_kses_post(__('Explore Our <span class="text-primary text-uppercase">Services</span>', 'bike-theme')); ?></h1>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <a class="service-item rounded" href="<?php echo esc_url(home_url('/services/city-tours')); ?>">
                            <div class="service-icon bg-transparent border rounded p-1">
                                <div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                                    <i class="fa fa-city fa-2x text-primary"></i>
                                </div>
                            </div>
                            <h5 class="mb-3"><?php esc_html_e('City Tours', 'bike-theme'); ?></h5>
                            <p class="text-body mb-0"><?php esc_html_e('Explore the city by bike with specially designed tours that allow you to fully enjoy local scenery and culture.', 'bike-theme'); ?></p>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                        <a class="service-item rounded" href="<?php echo esc_url(home_url('/services/mountain-biking')); ?>">
                            <div class="service-icon bg-transparent border rounded p-1">
                                <div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                                    <i class="fa fa-mountain fa-2x text-primary"></i>
                                </div>
                            </div>
                            <h5 class="mb-3"><?php esc_html_e('Mountain Biking', 'bike-theme'); ?></h5>
                            <p class="text-body mb-0"><?php esc_html_e('Experience challenging trails with high-quality mountain bikes, suitable for all rider skill levels.', 'bike-theme'); ?></p>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                        <a class="service-item rounded" href="<?php echo esc_url(home_url('/services/countryside-tours')); ?>">
                            <div class="service-icon bg-transparent border rounded p-1">
                                <div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                                    <i class="fa fa-tree fa-2x text-primary"></i>
                                </div>
                            </div>
                            <h5 class="mb-3"><?php esc_html_e('Countryside Tours', 'bike-theme'); ?></h5>
                            <p class="text-body mb-0"><?php esc_html_e('Discover the beauty of Vietnam\'s countryside through peaceful village roads and meet local residents.', 'bike-theme'); ?></p>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.4s">
                        <a class="service-item rounded" href="<?php echo esc_url(home_url('/services/bike-rental')); ?>">
                            <div class="service-icon bg-transparent border rounded p-1">
                                <div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                                    <i class="fa fa-bicycle fa-2x text-primary"></i>
                                </div>
                            </div>
                            <h5 class="mb-3"><?php esc_html_e('Bike Rentals', 'bike-theme'); ?></h5>
                            <p class="text-body mb-0"><?php esc_html_e('High-quality bicycles for rent, from road bikes to mountain bikes, racing bikes, and electric bikes.', 'bike-theme'); ?></p>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                        <a class="service-item rounded" href="<?php echo esc_url(home_url('/services/multi-day-tours')); ?>">
                            <div class="service-icon bg-transparent border rounded p-1">
                                <div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                                    <i class="fa fa-route fa-2x text-primary"></i>
                                </div>
                            </div>
                            <h5 class="mb-3"><?php esc_html_e('Multi-Day Tours', 'bike-theme'); ?></h5>
                            <p class="text-body mb-0"><?php esc_html_e('Long-day expeditions across beautiful landscapes with comfortable accommodations at each stop.', 'bike-theme'); ?></p>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.6s">
                        <a class="service-item rounded" href="<?php echo esc_url(home_url('/services/customized-tours')); ?>">
                            <div class="service-icon bg-transparent border rounded p-1">
                                <div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                                    <i class="fa fa-map-marked-alt fa-2x text-primary"></i>
                                </div>
                            </div>
                            <h5 class="mb-3"><?php esc_html_e('Customized Tours', 'bike-theme'); ?></h5>
                            <p class="text-body mb-0"><?php esc_html_e('Create your own journey with support from our experts, tailored to your preferences and skill level.', 'bike-theme'); ?></p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Service End -->

        <!-- Testimonial Start -->
        
        <!-- Testimonial End -->

        

        <!-- Video Modal Start -->
        <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"><?php esc_html_e('Sapa - Mai Chau Cycling Tour', 'bike-theme'); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php esc_attr_e('Close', 'bike-theme'); ?>"></button>
                    </div>
                    <div class="modal-body">
                        <!-- 16:9 aspect ratio -->
                        <div class="ratio ratio-16x9">
                            <iframe class="embed-responsive-item" src="" id="video" allowfullscreen allowscriptaccess="always"
                                allow="autoplay"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Video Modal End -->

    <?php
    else :
        // Display standard content for blog pages
        if (have_posts()) :
            /* Start the Loop */
            while (have_posts()) :
                the_post();
                get_template_part('template-parts/content', get_post_type());
            endwhile;

            the_posts_navigation();
        else :
            get_template_part('template-parts/content', 'none');
        endif;
    endif;
?>

</main><!-- #main -->

<?php
get_footer();
?>

