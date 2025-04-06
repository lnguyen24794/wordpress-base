<div class="tour-itinerary">
    <?php
    $itinerary_data = get_post_meta($post->ID, '_tour_itinerary_data', true);
    if (!empty($itinerary_data) && is_array($itinerary_data)) :
        foreach ($itinerary_data as $day_index => $day) :
        ?>
        <div class="itinerary-day-container">
            <div class="itinerary-day-content">
                <h3 class="day-title">
                    <?php 
                    printf(
                        esc_html__('Day %d: %s', 'bike-theme'),
                        $day_index + 1,
                        esc_html($day['title'])
                    ); 
                    ?>
                </h3>
                <div class="day-description">
                    <?php echo wp_kses_post($day['description']); ?>
                </div>
            </div>
            
            <div class="itinerary-day-details">
                <?php if (!empty($day['accommodation'])) : ?>
                    <div class="detail-item">
                        <i class="fas fa-hotel"></i>
                        <div class="detail-content">
                            <h5><?php esc_html_e('Accommodation', 'bike-theme'); ?></h5>
                            <p><?php echo esc_html($day['accommodation']); ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($day['meals'])) : ?>
                    <div class="detail-item">
                        <i class="fas fa-utensils"></i>
                        <div class="detail-content">
                            <h5><?php esc_html_e('Meals', 'bike-theme'); ?></h5>
                            <div class="meals-included">
                                <?php
                                $meals_included = array();
                                foreach ($day['meals'] as $meal => $included) {
                                    if ($included) {
                                        $meals_included[] = ucfirst($meal);
                                    }
                                }
                                echo esc_html(implode(', ', $meals_included));
                                ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($day['distance'])) : ?>
                    <div class="detail-item">
                        <i class="fas fa-road"></i>
                        <div class="detail-content">
                            <h5><?php esc_html_e('Distance', 'bike-theme'); ?></h5>
                            <p><?php printf(esc_html__('%g km', 'bike-theme'), $day['distance']); ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($day['additional_details'])) : ?>
                    <div class="detail-item additional-details">
                        <i class="fas fa-info-circle"></i>
                        <div class="detail-content">
                            <h5><?php esc_html_e('Additional Information', 'bike-theme'); ?></h5>
                            <ul>
                                <?php foreach ($day['additional_details'] as $detail) : ?>
                                    <li><?php echo esc_html($detail); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    endforeach;
    else :
    ?>
    <div class="alert alert-info">
        <?php esc_html_e('Detailed itinerary information is not available at this moment. Please contact us for more details.', 'bike-theme'); ?>
    </div>
<?php endif; ?>
</div>