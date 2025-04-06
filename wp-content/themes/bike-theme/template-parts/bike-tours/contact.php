<div class="tour-contact">
    <?php if (!empty($contact_info)) : ?>
        <?php echo wp_kses_post($contact_info); ?>
    <?php else : ?>
        <div class="contact-details">
            <h4><?php esc_html_e('Contact Information', 'bike-theme'); ?></h4>
            <p><?php esc_html_e('For questions about this tour or to make a reservation, please contact us:', 'bike-theme'); ?></p>
            
            <div class="mt-4">
                <p><i class="fa fa-map-marker-alt text-primary me-2"></i> <?php echo esc_html(get_theme_mod('bike_theme_address', '123 Street, Hoan Kiem, Hanoi, Vietnam')); ?></p>
                <p><i class="fa fa-phone-alt text-primary me-2"></i> <?php echo esc_html(get_theme_mod('bike_theme_phone', '+84 345 67890')); ?></p>
                <p><i class="fa fa-envelope text-primary me-2"></i> <?php echo esc_html(get_theme_mod('bike_theme_email', 'info@vietcycle.com')); ?></p>
            </div>
        </div>
    <?php endif; ?>
    
    <div class="mt-4">
        <a href="#book-now" class="btn btn-primary py-3 px-5"><?php esc_html_e('Book Now', 'bike-theme'); ?></a>
    </div>
</div>