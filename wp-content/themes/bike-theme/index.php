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
                        if (empty($slide['active'])) continue;
                        
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
                        <h1 class="mb-4"><?php echo wp_kses_post(__('Welcome to <span class="text-primary text-uppercase">VietCycle Tours</span>', 'bike-theme')); ?></h1>
                        <p class="mb-4"><?php esc_html_e('We provide high-quality bicycle tours and rentals throughout Vietnam. With over 10 years of experience, we are proud to be pioneers in eco-friendly cycling tourism, exploring Vietnamese culture and nature.', 'bike-theme'); ?></p>
                        <div class="row g-3 pb-4">
                            <div class="col-sm-4 wow fadeIn" data-wow-delay="0.1s">
                                <div class="border rounded p-1">
                                    <div class="border rounded text-center p-4">
                                        <i class="fa fa-bicycle fa-2x text-primary mb-2"></i>
                                        <h2 class="mb-1" data-toggle="counter-up">150</h2>
                                        <p class="mb-0"><?php esc_html_e('Bicycles', 'bike-theme'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 wow fadeIn" data-wow-delay="0.3s">
                                <div class="border rounded p-1">
                                    <div class="border rounded text-center p-4">
                                        <i class="fa fa-map-marked-alt fa-2x text-primary mb-2"></i>
                                        <h2 class="mb-1" data-toggle="counter-up">50</h2>
                                        <p class="mb-0"><?php esc_html_e('Tours', 'bike-theme'); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 wow fadeIn" data-wow-delay="0.5s">
                                <div class="border rounded p-1">
                                    <div class="border rounded text-center p-4">
                                        <i class="fa fa-users fa-2x text-primary mb-2"></i>
                                        <h2 class="mb-1" data-toggle="counter-up">1500</h2>
                                        <p class="mb-0"><?php esc_html_e('Customers', 'bike-theme'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a class="btn btn-primary py-3 px-5 mt-2" href="<?php echo esc_url(get_page_link(get_page_by_title('About Us')->ID)); ?>"><?php esc_html_e('Learn More', 'bike-theme'); ?></a>
                    </div>
                    <div class="col-lg-6">
                        <div class="row g-3">
                            <div class="col-6 text-end">
                                <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.1s" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/bikes/about-1.jpg" style="margin-top: 25%;">
                            </div>
                            <div class="col-6 text-start">
                                <img class="img-fluid rounded w-100 wow zoomIn" data-wow-delay="0.3s" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/bikes/about-2.jpg">
                            </div>
                            <div class="col-6 text-end">
                                <img class="img-fluid rounded w-50 wow zoomIn" data-wow-delay="0.5s" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/bikes/about-3.jpg">
                            </div>
                            <div class="col-6 text-start">
                                <img class="img-fluid rounded w-75 wow zoomIn" data-wow-delay="0.7s" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/bikes/about-4.jpg">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->

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

        <!-- Featured Bikes Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="section-title text-center text-primary text-uppercase"><?php esc_html_e('Our Bikes', 'bike-theme'); ?></h6>
                    <h1 class="mb-5"><?php echo wp_kses_post(__('Explore Our <span class="text-primary text-uppercase">Bikes</span>', 'bike-theme')); ?></h1>
                </div>
                <div class="row g-4">
                    <?php
                    $args = array(
                        'post_type' => 'bike',
                        'posts_per_page' => 3,
                        'meta_query' => array(
                            array(
                                'key' => 'bike_featured',
                                'value' => '1',
                                'compare' => '='
                            )
                        )
                    );
                    
                    $bikes_query = new WP_Query($args);
                    
                    if ($bikes_query->have_posts()) :
                        while ($bikes_query->have_posts()) : $bikes_query->the_post();
                            $bike_price = get_post_meta(get_the_ID(), 'bike_price', true);
                            $bike_brand = get_post_meta(get_the_ID(), 'bike_brand', true);
                            $bike_type = get_post_meta(get_the_ID(), 'bike_type', true);
                    ?>
                            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                                <div class="room-item shadow rounded overflow-hidden">
                                    <div class="position-relative">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('large', array('class' => 'img-fluid')); ?>
                                        <?php else : ?>
                                            <img class="img-fluid" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/bikes/bike-default.jpg" alt="<?php the_title_attribute(); ?>">
                                        <?php endif; ?>
                                        <small class="position-absolute start-0 top-100 translate-middle-y bg-primary text-white rounded py-1 px-3 ms-4">
                                            <?php echo esc_html(number_format($bike_price, 0, ',', '.')); ?> VNĐ<?php echo ($bike_price > 0) ? '/day' : ''; ?>
                                        </small>
                                    </div>
                                    <div class="p-4 mt-2">
                                        <div class="d-flex justify-content-between mb-3">
                                            <h5 class="mb-0"><?php the_title(); ?></h5>
                                            <div class="ps-2">
                                                <?php
                                                $rating = get_post_meta(get_the_ID(), 'bike_rating', true);
                                                $rating = $rating ? $rating : 5;
                                                for ($i = 0; $i < 5; $i++) {
                                                    if ($i < $rating) {
                                                        echo '<small class="fa fa-star text-primary"></small>';
                                                    } else {
                                                        echo '<small class="fa fa-star text-secondary"></small>';
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="d-flex mb-3">
                                            <?php if ($bike_brand) : ?>
                                            <small class="border-end me-3 pe-3"><i class="fa fa-tag text-primary me-2"></i><?php echo esc_html($bike_brand); ?></small>
                                            <?php endif; ?>
                                            <?php if ($bike_type) : ?>
                                            <small><i class="fa fa-bicycle text-primary me-2"></i><?php echo esc_html($bike_type); ?></small>
                                            <?php endif; ?>
                                        </div>
                                        <p class="text-body mb-3"><?php echo wp_trim_words(get_the_content(), 20, '...'); ?></p>
                                        <div class="d-flex justify-content-between">
                                            <a class="btn btn-sm btn-primary rounded py-2 px-4" href="<?php the_permalink(); ?>"><?php esc_html_e('View Details', 'bike-theme'); ?></a>
                                            <a class="btn btn-sm btn-dark rounded py-2 px-4" href="<?php echo esc_url(get_permalink(get_option('bike_theme_booking_page'))); ?>?bike=<?php echo get_the_ID(); ?>"><?php esc_html_e('Book Now', 'bike-theme'); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                    ?>
                        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="room-item shadow rounded overflow-hidden">
                                <div class="position-relative">
                                    <img class="img-fluid" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/bikes/bike-1.jpg" alt="<?php esc_attr_e('Road Bike', 'bike-theme'); ?>">
                                    <small class="position-absolute start-0 top-100 translate-middle-y bg-primary text-white rounded py-1 px-3 ms-4">300.000 VNĐ/day</small>
                                </div>
                                <div class="p-4 mt-2">
                                    <div class="d-flex justify-content-between mb-3">
                                        <h5 class="mb-0"><?php esc_html_e('Specialized Tarmac SL7', 'bike-theme'); ?></h5>
                                        <div class="ps-2">
                                            <small class="fa fa-star text-primary"></small>
                                            <small class="fa fa-star text-primary"></small>
                                            <small class="fa fa-star text-primary"></small>
                                            <small class="fa fa-star text-primary"></small>
                                            <small class="fa fa-star text-primary"></small>
                                        </div>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <small class="border-end me-3 pe-3"><i class="fa fa-tag text-primary me-2"></i>Specialized</small>
                                        <small><i class="fa fa-bicycle text-primary me-2"></i><?php esc_html_e('Road Bike', 'bike-theme'); ?></small>
                                    </div>
                                    <p class="text-body mb-3"><?php esc_html_e('Premium road bike with lightweight carbon frame, excellent speed, and modern aerodynamic design.', 'bike-theme'); ?></p>
                                    <div class="d-flex justify-content-between">
                                        <a class="btn btn-sm btn-primary rounded py-2 px-4" href="#"><?php esc_html_e('View Details', 'bike-theme'); ?></a>
                                        <a class="btn btn-sm btn-dark rounded py-2 px-4" href="#"><?php esc_html_e('Book Now', 'bike-theme'); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                            <div class="room-item shadow rounded overflow-hidden">
                                <div class="position-relative">
                                    <img class="img-fluid" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/bikes/bike-2.jpg" alt="<?php esc_attr_e('Mountain Bike', 'bike-theme'); ?>">
                                    <small class="position-absolute start-0 top-100 translate-middle-y bg-primary text-white rounded py-1 px-3 ms-4">250.000 VNĐ/day</small>
                                </div>
                                <div class="p-4 mt-2">
                                    <div class="d-flex justify-content-between mb-3">
                                        <h5 class="mb-0"><?php esc_html_e('Trek Fuel EX 8', 'bike-theme'); ?></h5>
                                        <div class="ps-2">
                                            <small class="fa fa-star text-primary"></small>
                                            <small class="fa fa-star text-primary"></small>
                                            <small class="fa fa-star text-primary"></small>
                                            <small class="fa fa-star text-primary"></small>
                                            <small class="fa fa-star text-primary"></small>
                                        </div>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <small class="border-end me-3 pe-3"><i class="fa fa-tag text-primary me-2"></i>Trek</small>
                                        <small><i class="fa fa-bicycle text-primary me-2"></i><?php esc_html_e('Mountain Bike', 'bike-theme'); ?></small>
                                    </div>
                                    <p class="text-body mb-3"><?php esc_html_e('Full suspension mountain bike, perfect for technical trails with excellent shock absorption.', 'bike-theme'); ?></p>
                                    <div class="d-flex justify-content-between">
                                        <a class="btn btn-sm btn-primary rounded py-2 px-4" href="#"><?php esc_html_e('View Details', 'bike-theme'); ?></a>
                                        <a class="btn btn-sm btn-dark rounded py-2 px-4" href="#"><?php esc_html_e('Book Now', 'bike-theme'); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.6s">
                            <div class="room-item shadow rounded overflow-hidden">
                                <div class="position-relative">
                                    <img class="img-fluid" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/bikes/bike-3.jpg" alt="<?php esc_attr_e('City Bike', 'bike-theme'); ?>">
                                    <small class="position-absolute start-0 top-100 translate-middle-y bg-primary text-white rounded py-1 px-3 ms-4">180.000 VNĐ/day</small>
                                </div>
                                <div class="p-4 mt-2">
                                    <div class="d-flex justify-content-between mb-3">
                                        <h5 class="mb-0"><?php esc_html_e('Giant Escape 2', 'bike-theme'); ?></h5>
                                        <div class="ps-2">
                                            <small class="fa fa-star text-primary"></small>
                                            <small class="fa fa-star text-primary"></small>
                                            <small class="fa fa-star text-primary"></small>
                                            <small class="fa fa-star text-primary"></small>
                                            <small class="fa fa-star text-secondary"></small>
                                        </div>
                                    </div>
                                    <div class="d-flex mb-3">
                                        <small class="border-end me-3 pe-3"><i class="fa fa-tag text-primary me-2"></i>Giant</small>
                                        <small><i class="fa fa-bicycle text-primary me-2"></i><?php esc_html_e('City Bike', 'bike-theme'); ?></small>
                                    </div>
                                    <p class="text-body mb-3"><?php esc_html_e('Comfortable city bike, easy to control, ideal for daily commuting and exploring the city.', 'bike-theme'); ?></p>
                                    <div class="d-flex justify-content-between">
                                        <a class="btn btn-sm btn-primary rounded py-2 px-4" href="#"><?php esc_html_e('View Details', 'bike-theme'); ?></a>
                                        <a class="btn btn-sm btn-dark rounded py-2 px-4" href="#"><?php esc_html_e('Book Now', 'bike-theme'); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="text-center mt-5">
                    <a href="<?php echo esc_url(get_post_type_archive_link('bike')); ?>" class="btn btn-primary py-3 px-5"><?php esc_html_e('View All Bikes', 'bike-theme'); ?></a>
                </div>
            </div>
        </div>
        <!-- Featured Bikes End -->

        <!-- Testimonial Start -->
        
        <!-- Testimonial End -->

        <!-- Tour Banner Start -->
        <div class="container-xxl py-5 px-0 wow zoomIn" data-wow-delay="0.1s">
            <div class="row g-0">
                <div class="col-md-6 bg-dark d-flex align-items-center">
                    <div class="p-5">
                        <h6 class="section-title text-start text-white text-uppercase mb-3"><?php esc_html_e('Featured Tour', 'bike-theme'); ?></h6>
                        <h1 class="text-white mb-4"><?php esc_html_e('Sapa - Mai Chau Cycling Tour 5 Days', 'bike-theme'); ?></h1>
                        <p class="text-white mb-4"><?php esc_html_e('A 5-day journey through the most beautiful mountain passes in Northern Vietnam. Explore villages, rice terraces, and the unique culture of ethnic minorities.', 'bike-theme'); ?></p>
                        <div class="row g-1 mb-4">
                            <div class="col-4">
                                <div class="bg-primary p-3">
                                    <h1 class="text-white mb-0">5</h1>
                                    <p class="text-white mb-0"><?php esc_html_e('Days', 'bike-theme'); ?></p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-primary p-3">
                                    <h1 class="text-white mb-0">4</h1>
                                    <p class="text-white mb-0"><?php esc_html_e('Nights', 'bike-theme'); ?></p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-primary p-3">
                                    <h1 class="text-white mb-0">160</h1>
                                    <p class="text-white mb-0">km</p>
                                </div>
                            </div>
                        </div>
                        <a href="<?php echo esc_url(home_url('/tour/sapa-maichau')); ?>" class="btn btn-primary py-md-3 px-md-5 me-3"><?php esc_html_e('View Details', 'bike-theme'); ?></a>
                        <a href="<?php echo esc_url(get_permalink(get_option('bike_theme_booking_page'))); ?>" class="btn btn-light py-md-3 px-md-5"><?php esc_html_e('Book Now', 'bike-theme'); ?></a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="video position-relative">
                        <img class="img-fluid w-100" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/bikes/tour-banner.jpg" alt="<?php esc_attr_e('Sapa Bike Tour', 'bike-theme'); ?>">
                        <button type="button" class="btn-play position-absolute top-50 start-50 translate-middle" data-bs-toggle="modal" data-bs-target="#videoModal">
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tour Banner End -->

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
