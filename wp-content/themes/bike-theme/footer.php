<?php
/**
 * The template for displaying the footer
 *
 * @package Bike_Theme
 */

?>

    </div><!-- #page -->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-light footer mt-5 pt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4"><?php esc_html_e('About Us', 'bike-theme'); ?></h4>
                    <?php if (is_active_sidebar('footer-1')) : ?>
                        <?php dynamic_sidebar('footer-1'); ?>
                    <?php else : ?>
                        <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i><?php echo esc_html(get_option('bike_theme_address', '123 Street, New York, USA')); ?></p>
                        <p class="mb-2"><i class="fa fa-phone-alt me-3"></i><?php echo esc_html(bike_theme_get_option('phone', '+012 345 67890')); ?></p>
                        <p class="mb-2"><i class="fa fa-envelope me-3"></i><?php echo esc_html(bike_theme_get_option('email', 'info@example.com')); ?></p>
                        <div class="d-flex pt-2">
                            <?php if ($twitter = bike_theme_get_option('twitter')) : ?>
                                <a class="btn btn-outline-light btn-social" href="<?php echo esc_url($twitter); ?>"><i class="fab fa-twitter"></i></a>
                            <?php endif; ?>
                            <?php if ($facebook = bike_theme_get_option('facebook')) : ?>
                                <a class="btn btn-outline-light btn-social" href="<?php echo esc_url($facebook); ?>"><i class="fab fa-facebook-f"></i></a>
                            <?php endif; ?>
                            <?php if ($youtube = bike_theme_get_option('youtube')) : ?>
                                <a class="btn btn-outline-light btn-social" href="<?php echo esc_url($youtube); ?>"><i class="fab fa-youtube"></i></a>
                            <?php endif; ?>
                            <?php if ($linkedin = bike_theme_get_option('linkedin')) : ?>
                                <a class="btn btn-outline-light btn-social" href="<?php echo esc_url($linkedin); ?>"><i class="fab fa-linkedin-in"></i></a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4"><?php esc_html_e('Company', 'bike-theme'); ?></h4>
                    <?php if (is_active_sidebar('footer-2')) : ?>
                        <?php dynamic_sidebar('footer-2'); ?>
                    <?php else : ?>
                        <a class="btn btn-link" href=""><?php esc_html_e('About Us', 'bike-theme'); ?></a>
                        <a class="btn btn-link" href=""><?php esc_html_e('Contact Us', 'bike-theme'); ?></a>
                        <a class="btn btn-link" href=""><?php esc_html_e('Privacy Policy', 'bike-theme'); ?></a>
                        <a class="btn btn-link" href=""><?php esc_html_e('Terms & Condition', 'bike-theme'); ?></a>
                        <a class="btn btn-link" href=""><?php esc_html_e('Support', 'bike-theme'); ?></a>
                    <?php endif; ?>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4"><?php esc_html_e('Services', 'bike-theme'); ?></h4>
                    <?php if (is_active_sidebar('footer-3')) : ?>
                        <?php dynamic_sidebar('footer-3'); ?>
                    <?php else : ?>
                        <a class="btn btn-link" href=""><?php esc_html_e('Bike Repair', 'bike-theme'); ?></a>
                        <a class="btn btn-link" href=""><?php esc_html_e('Bike Rental', 'bike-theme'); ?></a>
                        <a class="btn btn-link" href=""><?php esc_html_e('Custom Builds', 'bike-theme'); ?></a>
                        <a class="btn btn-link" href=""><?php esc_html_e('Bike Accessories', 'bike-theme'); ?></a>
                        <a class="btn btn-link" href=""><?php esc_html_e('Guided Tours', 'bike-theme'); ?></a>
                    <?php endif; ?>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4"><?php esc_html_e('Newsletter', 'bike-theme'); ?></h4>
                    <?php if (is_active_sidebar('footer-4')) : ?>
                        <?php dynamic_sidebar('footer-4'); ?>
                    <?php else : ?>
                        <p><?php esc_html_e('Subscribe to our newsletter for the latest updates and offers.', 'bike-theme'); ?></p>
                        <div class="position-relative mx-auto" style="max-width: 400px;">
                            <input class="form-control border-0 w-100 py-3 ps-4 pe-5" type="text" placeholder="<?php esc_attr_e('Your email', 'bike-theme'); ?>">
                            <button type="button" class="btn btn-primary py-2 position-absolute top-0 end-0 mt-2 me-2"><?php esc_html_e('Subscribe', 'bike-theme'); ?></button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <?php echo wp_kses_post(bike_theme_get_option('copyright', '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. All Rights Reserved.')); ?>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <div class="footer-menu">
                            <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'bike-theme'); ?></a>
                            <a href="#"><?php esc_html_e('Cookies', 'bike-theme'); ?></a>
                            <a href="#"><?php esc_html_e('Help', 'bike-theme'); ?></a>
                            <a href="#"><?php esc_html_e('FAQs', 'bike-theme'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

</div><!-- /.container-xxl -->

<?php wp_footer(); ?>

</body>
</html> 