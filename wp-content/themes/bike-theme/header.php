<?php
/**
 * The header for our theme
 *
 * @package Bike_Theme
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="<?php echo get_bloginfo('description'); ?>" name="description">
    <meta content="<?php echo esc_attr(bike_theme_get_option('meta_keywords', 'bikes, cycling, bicycle')); ?>" name="keywords">
    
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="container-fluid bg-white p-0">
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only"><?php esc_html_e('Loading...', 'bike-theme'); ?></span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start -->
    <div class="container-fluid bg-primary px-0">
        <div class="row gx-0">
            <div class="col-lg-3 bg-primary d-none d-lg-block">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="navbar-brand w-100 h-100 m-0 p-0 d-flex align-items-center justify-content-center">
                    <?php if (has_custom_logo()) : 
                        $custom_logo_id = get_theme_mod('custom_logo');
                        $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                    ?>
                        <img src="<?php echo esc_url($logo[0]); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="img-fluid custom-logo" style="max-height: 100px;">
                    <?php else : ?>
                        <h1 class="m-0 text-primary text-uppercase"><?php echo get_bloginfo('name'); ?></h1>
                    <?php endif; ?>
                </a>
            </div>
            <div class="col-lg-9">
                <div class="row gx-0 bg-dark text-white d-none d-lg-flex border-radius-bottom-left-15">
                    <div class="col-lg-7 px-5 text-start">
                        <div class="h-100 d-inline-flex align-items-center py-2 me-4">
                            <i class="fa fa-envelope text-primary me-2"></i>
                            <p class="mb-0"><?php echo esc_html(bike_theme_get_option('email', 'info@example.com')); ?></p>
                        </div>
                        <div class="h-100 d-inline-flex align-items-center py-2">
                            <i class="fa fa-phone-alt text-primary me-2"></i>
                            <p class="mb-0"><?php echo esc_html(bike_theme_get_option('phone', '+012 345 6789')); ?></p>
                        </div>
                    </div>
                    <div class="col-lg-5 px-5 text-end">
                        <div class="d-inline-flex align-items-center py-2">
                            <?php if (bike_theme_get_option('facebook')) : ?>
                            <a class="me-3" href="<?php echo esc_url(bike_theme_get_option('facebook')); ?>"><i class="fab fa-facebook-f"></i></a>
                            <?php endif; ?>
                            
                            <?php if (bike_theme_get_option('twitter')) : ?>
                            <a class="me-3" href="<?php echo esc_url(bike_theme_get_option('twitter')); ?>"><i class="fab fa-twitter"></i></a>
                            <?php endif; ?>
                            
                            <?php if (bike_theme_get_option('linkedin')) : ?>
                            <a class="me-3" href="<?php echo esc_url(bike_theme_get_option('linkedin')); ?>"><i class="fab fa-linkedin-in"></i></a>
                            <?php endif; ?>
                            
                            <?php if (bike_theme_get_option('instagram')) : ?>
                            <a class="me-3" href="<?php echo esc_url(bike_theme_get_option('instagram')); ?>"><i class="fab fa-instagram"></i></a>
                            <?php endif; ?>
                            
                            <?php if (bike_theme_get_option('youtube')) : ?>
                            <a class="" href="<?php echo esc_url(bike_theme_get_option('youtube')); ?>"><i class="fab fa-youtube"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <nav class="navbar navbar-expand-lg bg-primary navbar-dark p-3 p-lg-0">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="navbar-brand d-block d-lg-none">
                        <?php if (has_custom_logo()) : 
                            $custom_logo_id = get_theme_mod('custom_logo');
                            $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                        ?>
                            <img src="<?php echo esc_url($logo[0]); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="img-fluid custom-logo-mobile" style="max-height: 40px;">
                        <?php else : ?>
                            <h1 class="m-0 text-primary text-uppercase"><?php bloginfo('name'); ?></h1>
                        <?php endif; ?>
                    </a>
                    <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                        <?php
                        wp_nav_menu(array(
                            'theme_location'    => 'primary',
                            'depth'             => 2,
                            'container'         => false,
                            'menu_class'        => 'navbar-nav mr-auto py-0',
                            'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
                            'walker'            => new WP_Bootstrap_Navwalker()
                        ));
                        ?>
                        <?php $premium_button_url = bike_theme_get_option('premium_button_url', '#'); ?>
                        <?php if ($premium_button_url) : ?>
                            <a href="/booking" class="btn btn-primary rounded-0 py-4 px-md-5 d-none d-lg-block">
                                <?php echo esc_html(bike_theme_get_option('premium_button_text', __('Booking Now', 'bike-theme'))); ?>
                                <i class="fa fa-arrow-right ms-3"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar End -->
</div>

<?php wp_footer(); ?>
</body>
</html> 