<div class="row">
    <div class="col-md-6 mb-4">
        <h4><?php esc_html_e('What\'s Included', 'bike-theme'); ?></h4>
        <?php if (!empty($included)) : ?>
            <div class="included-services">
                <?php echo wp_kses_post($included); ?>
            </div>
        
        <?php endif; ?>
    </div>
    <div class="col-md-6 mb-4">
        <h4><?php esc_html_e('What\'s Not Included', 'bike-theme'); ?></h4>
        <?php if (!empty($not_included)) : ?>
            <div class="not-included-services">
                <?php echo wp_kses_post($not_included); ?>
            </div>
        
        <?php endif; ?>
    </div>
</div>

<div class="price-details mt-4">
    <h4><?php esc_html_e('Price Details', 'bike-theme'); ?></h4>
    <?php if (get_post_meta(get_the_ID(), '_tour_flexible_pricing_enabled', true) === '1') :
        $pricing_data = get_post_meta(get_the_ID(), '_tour_flexible_pricing', true);
        if (!empty($pricing_data) && is_array($pricing_data)) :
            usort($pricing_data, function ($a, $b) {
                return $a['participants'] - $b['participants'];
            });
            ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th><?php esc_html_e('Number of Participants', 'bike-theme'); ?></th>
                        <th><?php esc_html_e('Price per Person', 'bike-theme'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pricing_data as $price_item) : ?>
                    <tr>
                        <td><?php printf(esc_html(_n('%d person', '%d people', $price_item['participants'], 'bike-theme')), $price_item['participants']); ?></td>
                        <td><?php echo bike_theme_format_price($price_item['price']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else : ?>
        <p><?php echo bike_theme_format_price(bike_theme_get_tour_price(get_the_ID())); ?> <?php esc_html_e('per person', 'bike-theme'); ?></p>
    <?php endif; ?>
    <?php else : ?>
        <p><?php echo bike_theme_format_price(bike_theme_get_tour_price(get_the_ID())); ?> <?php esc_html_e('per person', 'bike-theme'); ?></p>
    <?php endif; ?>
</div>