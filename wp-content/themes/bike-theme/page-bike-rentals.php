<?php
/**
 * Template Name: Bike Rentals Page
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
                <h1 class="display-3 text-white mb-3 animated slideInDown"><?php esc_html_e('Our Bike Rentals', 'bike-theme'); ?></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center text-uppercase">
                        <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'bike-theme'); ?></a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page"><?php esc_html_e('Bike Rentals', 'bike-theme'); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Tours Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    </div>
 <!-- Featured Bikes Start -->
 <div class="container-xxl py-5">
    <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title text-center text-primary text-uppercase"><?php esc_html_e('Our Bikes', 'bike-theme'); ?></h6>
            <h1 class="mb-5"><?php echo wp_kses_post(__('Explore Our <span class="text-primary text-uppercase">Bikes</span>', 'bike-theme')); ?></h1>
        </div>
        <?php include(get_template_directory() . '/template-parts/bikes.php'); ?>
    </div>
</main><!-- #main -->

<?php
get_footer();
?> 