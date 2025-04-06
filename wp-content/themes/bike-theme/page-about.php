<?php
/**
 * Template Name: About Page
 *
 * @package Bike_Theme
 */

get_header();
?>

<main id="primary" class="site-main">
    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 p-0" style="background-image: url(<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/bikes/about-banner.jpg);">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center pb-5">
                <h1 class="display-3 text-white mb-3 animated slideInDown"><?php esc_html_e('About Us', 'bike-theme'); ?></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center text-uppercase">
                        <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'bike-theme'); ?></a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page"><?php esc_html_e('About Us', 'bike-theme'); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- About Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <h6 class="section-title text-start text-primary text-uppercase"><?php esc_html_e('About Us', 'bike-theme'); ?></h6>
                    <h1 class="mb-4"><?php echo wp_kses_post(__('Welcome to <span class="text-primary">BeeBikeHub</span>', 'bike-theme')); ?></h1>
                    <p class="mb-4"><?php esc_html_e('Welcome to BeeBikeHub – your starting point for unforgettable adventures exploring Da Nang on two wheels! We\'re a passionate team dedicated to bringing you unique travel experiences, blending the freedom of cycling with the stunning beauty of Da Nang, Vietnam\'s vibrant coastal city.', 'bike-theme'); ?></p>
                    <p class="mb-4"><?php esc_html_e('At BeeBikeHub, we go beyond just offering high-quality bike rentals and carefully crafted bike tours. We want you to feel the pulse of local life – from sunlit coastal roads and iconic bridges to peaceful villages and mouthwatering street food. Our mission is to turn every ride into a cherished memory, with well-maintained bikes, diverse routes, and a friendly team of guides who know this land inside and out.', 'bike-theme'); ?></p>
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
                    <a class="btn btn-primary py-3 px-5 mt-2" href="<?php echo esc_url(get_post_type_archive_link('bike_tour')); ?>"><?php esc_html_e('Explore Our Tours', 'bike-theme'); ?></a>
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

    <!-- Team Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title text-center text-primary text-uppercase"><?php esc_html_e('Our Reviews', 'bike-theme'); ?></h6>
                <h1 class="mb-5"><?php esc_html_e('What Our Customers Say', 'bike-theme'); ?></h1>
            </div>
            <?php echo do_shortcode('[trustindex no-registration=google]'); ?>
        </div>
    </div>
    <!-- Team End -->

    <!-- Call to Action Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="bg-light rounded p-5 wow fadeInUp" data-wow-delay="0.1s">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6">
                        <div class="row g-4">
                            <div class="col-12">
                                <h1 class="mb-3"><?php esc_html_e('Ready to Experience Da Nang on Two Wheels?', 'bike-theme'); ?></h1>
                                <p class="mb-4"><?php esc_html_e('Born from a love for cycling and a desire to share Da Nang\'s charm in the most eco-friendly way, BeeBikeHub is here for everyone – whether you\'re a seasoned rider or just looking to pedal at your own pace. We\'re proud to be your companion on the journey, helping you discover Da Nang in a way that\'s uniquely yours.', 'bike-theme'); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="d-flex align-items-center justify-content-center">
                            <a class="btn btn-primary py-3 px-4 me-3" href="<?php echo esc_url(get_post_type_archive_link('bike_tour')); ?>"><?php esc_html_e('Book a Tour', 'bike-theme'); ?></a>
                            <a class="btn btn-dark py-3 px-4" href="<?php echo esc_url(home_url('/bike-rentals')); ?>"><?php esc_html_e('Rent a Bike', 'bike-theme'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Call to Action End -->
</main><!-- #main -->

<?php
get_footer();
?> 