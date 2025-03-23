<?php
/**
 * Template Name: Contact Page
 *
 * @package Bike_Theme
 */

get_header();
?>

<main id="primary" class="site-main">

    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 p-0" style="background-image: url(<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/carousel-1.jpg);">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center pb-5">
                <h1 class="display-3 text-white mb-3 animated slideInDown"><?php the_title(); ?></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center text-uppercase">
                        <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'bike-theme'); ?></a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page"><?php the_title(); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Contact Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title text-center text-primary text-uppercase"><?php esc_html_e('Contact Us', 'bike-theme'); ?></h6>
                <h1 class="mb-5"><?php echo wp_kses_post(sprintf(__('<span class="text-primary text-uppercase">%s</span> %s', 'bike-theme'), __('Contact', 'bike-theme'), __('For Any Query', 'bike-theme'))); ?></h1>
            </div>
            <div class="row g-4">
                <div class="col-12">
                    <div class="row gy-4">
                        <div class="col-md-4">
                            <h6 class="section-title text-start text-primary text-uppercase"><?php esc_html_e('Sales', 'bike-theme'); ?></h6>
                            <p><i class="fa fa-envelope-open text-primary me-2"></i><?php echo esc_html(bike_theme_get_option('email', 'sales@example.com')); ?></p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="section-title text-start text-primary text-uppercase"><?php esc_html_e('Service', 'bike-theme'); ?></h6>
                            <p><i class="fa fa-envelope-open text-primary me-2"></i><?php echo esc_html(bike_theme_get_option('service_email', 'service@example.com')); ?></p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="section-title text-start text-primary text-uppercase"><?php esc_html_e('General', 'bike-theme'); ?></h6>
                            <p><i class="fa fa-envelope-open text-primary me-2"></i><?php echo esc_html(bike_theme_get_option('general_email', 'info@example.com')); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 wow fadeIn" data-wow-delay="0.1s">
                    <iframe class="position-relative rounded w-100 h-100"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3001156.4288297426!2d-78.01371936852176!3d42.72876761954724!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4ccc4bf0f123a5a9%3A0xddcfc6c1de189567!2sNew%20York%2C%20USA!5e0!3m2!1sen!2sbd!4v1603794290143!5m2!1sen!2sbd"
                        frameborder="0" style="min-height: 350px; border:0;" allowfullscreen="" aria-hidden="false"
                        tabindex="0"></iframe>
                </div>
                <div class="col-md-6">
                    <div class="wow fadeInUp" data-wow-delay="0.2s">
                        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                            <?php the_content(); ?>
                        <?php endwhile; endif; ?>
                        <form id="contact-form" action="" method="post">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="<?php esc_attr_e('Your Name', 'bike-theme'); ?>">
                                        <label for="name"><?php esc_html_e('Your Name', 'bike-theme'); ?></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="<?php esc_attr_e('Your Email', 'bike-theme'); ?>">
                                        <label for="email"><?php esc_html_e('Your Email', 'bike-theme'); ?></label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="subject" name="subject" placeholder="<?php esc_attr_e('Subject', 'bike-theme'); ?>">
                                        <label for="subject"><?php esc_html_e('Subject', 'bike-theme'); ?></label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="<?php esc_attr_e('Leave a message here', 'bike-theme'); ?>" id="message" name="message" style="height: 150px"></textarea>
                                        <label for="message"><?php esc_html_e('Message', 'bike-theme'); ?></label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary w-100 py-3" type="submit"><?php esc_html_e('Send Message', 'bike-theme'); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->

</main><!-- #main -->

<?php
get_footer();
?> 