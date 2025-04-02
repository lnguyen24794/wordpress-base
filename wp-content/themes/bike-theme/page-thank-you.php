<?php
/**
 * Template Name: Thank You Page
 *
 * @package Bike_Theme
 */

get_header();
?>

<main id="primary" class="site-main">

    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 p-0" style="background-image: url(<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/bikes/booking-header.jpg);">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center pb-5">
                <h1 class="display-3 text-white mb-3 animated slideInDown"><?php esc_html_e('Thank You', 'bike-theme'); ?></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center text-uppercase">
                        <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'bike-theme'); ?></a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page"><?php esc_html_e('Thank You', 'bike-theme'); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Thank You Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title text-center text-primary text-uppercase"><?php esc_html_e('Success', 'bike-theme'); ?></h6>
                <h1 class="mb-5"><?php esc_html_e('Your Booking Has Been Received', 'bike-theme'); ?></h1>
                
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="bg-light rounded p-5 mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="flex-shrink-0 btn-square bg-primary rounded-circle">
                                    <i class="fa fa-check text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-1"><?php esc_html_e('Booking Confirmed', 'bike-theme'); ?></h5>
                                </div>
                            </div>
                            <p class="mb-4"><?php esc_html_e('Thank you for booking with us! We have received your booking request and will contact you shortly for confirmation.', 'bike-theme'); ?></p>
                            <p class="mb-4"><?php esc_html_e('A confirmation email has been sent to your email address with your booking details.', 'bike-theme'); ?></p>
                            
                            <h5 class="mb-3"><?php esc_html_e('What Happens Next?', 'bike-theme'); ?></h5>
                            <ul class="mb-4">
                                <li><?php esc_html_e('Our team will review your booking details.', 'bike-theme'); ?></li>
                                <li><?php esc_html_e('We will contact you within 24 hours to confirm availability.', 'bike-theme'); ?></li>
                                <li><?php esc_html_e('Payment instructions will be provided if not already completed.', 'bike-theme'); ?></li>
                                <li><?php esc_html_e('You will receive a final confirmation once everything is set.', 'bike-theme'); ?></li>
                            </ul>
                            
                            <div class="text-center">
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary py-3 px-5"><?php esc_html_e('Return to Home', 'bike-theme'); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h4 class="mb-4"><?php esc_html_e('Need assistance?', 'bike-theme'); ?></h4>
                <p class="mb-4"><?php esc_html_e('If you have any questions, please contact us:', 'bike-theme'); ?></p>
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0 btn-square bg-primary rounded-circle">
                                <i class="fa fa-phone-alt text-white"></i>
                            </div>
                            <div class="ms-3">
                                <p class="mb-0"><?php echo esc_html(get_theme_mod('bike_theme_phone', '+84 345 67890')); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0 btn-square bg-primary rounded-circle">
                                <i class="fa fa-envelope text-white"></i>
                            </div>
                            <div class="ms-3">
                                <p class="mb-0"><?php echo esc_html(get_theme_mod('bike_theme_email', 'info@vietcycle.com')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Thank You End -->

</main><!-- #main -->

<?php
get_footer();
?> 