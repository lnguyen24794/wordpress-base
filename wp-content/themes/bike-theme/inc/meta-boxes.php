<?php
/**
 * Register meta boxes for custom post types
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Tour meta boxes
 */
function bike_theme_add_tour_meta_boxes()
{
    add_meta_box(
        'tour_details',
        __('Tour Details', 'bike-theme'),
        'bike_theme_tour_details_callback',
        'bike_tour',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'bike_theme_add_tour_meta_boxes');

/**
 * Tour details meta box callback
 */
function bike_theme_tour_details_callback($post)
{
    wp_nonce_field('bike_theme_tour_details', 'bike_theme_tour_details_nonce');

    $duration_days = get_post_meta($post->ID, '_tour_duration_days', true);
    $duration_nights = get_post_meta($post->ID, '_tour_duration_nights', true);
    $duration_hours = get_post_meta($post->ID, '_tour_duration_hours', true);
    $duration_type = get_post_meta($post->ID, '_tour_duration_type', true) ?: 'days_nights';
    $distance = get_post_meta($post->ID, '_tour_distance', true);
    $price = get_post_meta($post->ID, '_tour_price', true);
    $max_participants = get_post_meta($post->ID, '_tour_max_participants', true);
    $difficulty = get_post_meta($post->ID, '_tour_difficulty', true);
    $start_location = get_post_meta($post->ID, '_tour_start_location', true);
    $end_location = get_post_meta($post->ID, '_tour_end_location', true);
    $schedule = get_post_meta($post->ID, '_tour_schedule', true);
    $included = get_post_meta($post->ID, '_tour_included', true);
    $not_included = get_post_meta($post->ID, '_tour_not_included', true);
    
    // Get detailed itinerary
    $itinerary_data = get_post_meta($post->ID, '_tour_itinerary_data', true);
    if (!is_array($itinerary_data)) {
        $itinerary_data = array();
    }
    
    // Get cancellation policy
    $cancellation_policy = get_post_meta($post->ID, '_tour_cancellation_policy', true);
    
    // Get booking terms
    $booking_terms = get_post_meta($post->ID, '_tour_booking_terms', true);
    
    // Get contact information
    $contact_info = get_post_meta($post->ID, '_tour_contact_info', true);
    
    // Get gallery IDs
    $gallery_ids = get_post_meta($post->ID, '_tour_gallery', true);
    
    // Get video URL
    $video_url = get_post_meta($post->ID, '_tour_video_url', true);
    
    // Get flexible pricing
    $flexible_pricing_enabled = get_post_meta($post->ID, '_tour_flexible_pricing_enabled', true);
    $flexible_pricing = get_post_meta($post->ID, '_tour_flexible_pricing', true);
    if (!is_array($flexible_pricing)) {
        $flexible_pricing = array();
    }
    ?>
    <div class="tour-meta-box">
        <h3><?php esc_html_e('Basic Information', 'bike-theme'); ?></h3>
        
        <!-- Duration Section -->
        <div class="duration-section">
            <p>
                <label><?php esc_html_e('Duration Type', 'bike-theme'); ?></label>
                <select id="tour_duration_type" name="tour_duration_type" class="widefat">
                    <option value="days_nights" <?php selected($duration_type, 'days_nights'); ?>><?php esc_html_e('Days & Nights', 'bike-theme'); ?></option>
                    <option value="hours" <?php selected($duration_type, 'hours'); ?>><?php esc_html_e('Hours', 'bike-theme'); ?></option>
                </select>
            </p>
            
            <div id="days_nights_fields" class="duration-fields" <?php echo $duration_type === 'hours' ? 'style="display:none;"' : ''; ?>>
                <div class="duration-flex">
                    <p class="duration-field">
                        <label for="tour_duration_days"><?php esc_html_e('Days', 'bike-theme'); ?></label>
                        <input type="number" id="tour_duration_days" name="tour_duration_days" 
                               value="<?php echo esc_attr($duration_days); ?>" class="widefat" min="0">
                    </p>
                    <p class="duration-field">
                        <label for="tour_duration_nights"><?php esc_html_e('Nights', 'bike-theme'); ?></label>
                        <input type="number" id="tour_duration_nights" name="tour_duration_nights" 
                               value="<?php echo esc_attr($duration_nights); ?>" class="widefat" min="0">
                    </p>
                </div>
            </div>
            
            <div id="hours_fields" class="duration-fields" <?php echo $duration_type === 'days_nights' ? 'style="display:none;"' : ''; ?>>
                <p>
                    <label for="tour_duration_hours"><?php esc_html_e('Hours', 'bike-theme'); ?></label>
                    <input type="number" id="tour_duration_hours" name="tour_duration_hours" 
                           value="<?php echo esc_attr($duration_hours); ?>" class="widefat" min="0" step="0.5">
                </p>
            </div>
        </div>

        <style>
            .duration-flex {
                display: flex;
                gap: 20px;
            }
            .duration-field {
                flex: 1;
            }
            .duration-section {
                margin-bottom: 20px;
                padding: 15px;
                background: #f9f9f9;
                border: 1px solid #e5e5e5;
                border-radius: 4px;
            }
        </style>

        <script>
        jQuery(document).ready(function($) {
            $('#tour_duration_type').on('change', function() {
                var type = $(this).val();
                if (type === 'days_nights') {
                    $('#days_nights_fields').show();
                    $('#hours_fields').hide();
                } else {
                    $('#days_nights_fields').hide();
                    $('#hours_fields').show();
                }
            });
        });
        </script>

        <p>
            <label for="tour_distance"><?php esc_html_e('Distance (km)', 'bike-theme'); ?></label>
            <input type="number" id="tour_distance" name="tour_distance" value="<?php echo esc_attr($distance); ?>" class="widefat">
        </p>
        
        <!-- Pricing section -->
        <div class="tour-pricing-section">
            <p>
                <label><input type="checkbox" id="tour_flexible_pricing_enabled" name="tour_flexible_pricing_enabled" value="1" <?php checked($flexible_pricing_enabled, '1'); ?>> <?php esc_html_e('Enable flexible pricing based on number of participants', 'bike-theme'); ?></label>
            </p>
            
            <div id="standard-pricing" <?php echo $flexible_pricing_enabled ? 'style="display:none;"' : ''; ?>>
                <p>
                    <label for="tour_price"><?php esc_html_e('Standard Price ($ per person)', 'bike-theme'); ?></label>
                    <input type="number" id="tour_price" name="tour_price" value="<?php echo esc_attr($price); ?>" class="widefat">
                </p>
            </div>
            
            <div id="flexible-pricing" <?php echo !$flexible_pricing_enabled ? 'style="display:none;"' : ''; ?>>
                <h4><?php esc_html_e('Flexible Pricing', 'bike-theme'); ?></h4>
                <p><?php esc_html_e('Set different prices based on the number of participants. The price is per person.', 'bike-theme'); ?></p>
                
                <div class="flexible-pricing-container">
                    <div class="flexible-pricing-row">
                        <div class="flexible-pricing-header">
                            <div class="flexible-pricing-cell"><?php esc_html_e('Participants', 'bike-theme'); ?></div>
                            <div class="flexible-pricing-cell"><?php esc_html_e('Price per person ($)', 'bike-theme'); ?></div>
                            <div class="flexible-pricing-cell"></div>
                        </div>
                    </div>
                    
                    <div id="flexible-pricing-rows">
                        <?php 
                        if (!empty($flexible_pricing)) {
                            foreach ($flexible_pricing as $key => $price_item) {
                                $participants = isset($price_item['participants']) ? $price_item['participants'] : '';
                                $price_value = isset($price_item['price']) ? $price_item['price'] : '';
                                ?>
                                <div class="flexible-pricing-row">
                                    <div class="flexible-pricing-cell">
                                        <input type="number" name="tour_flexible_pricing[<?php echo $key; ?>][participants]" value="<?php echo esc_attr($participants); ?>" min="1" placeholder="<?php esc_attr_e('e.g. 1', 'bike-theme'); ?>">
                                    </div>
                                    <div class="flexible-pricing-cell">
                                        <input type="number" name="tour_flexible_pricing[<?php echo $key; ?>][price]" value="<?php echo esc_attr($price_value); ?>" min="0" placeholder="<?php esc_attr_e('e.g. 1000000', 'bike-theme'); ?>">
                                    </div>
                                    <div class="flexible-pricing-cell">
                                        <button type="button" class="button remove-price-row"><?php esc_html_e('Remove', 'bike-theme'); ?></button>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            // Default rows for new tours
                            for ($i = 1; $i <= 3; $i++) {
                                ?>
                                <div class="flexible-pricing-row">
                                    <div class="flexible-pricing-cell">
                                        <input type="number" name="tour_flexible_pricing[<?php echo $i; ?>][participants]" value="<?php echo $i; ?>" min="1" placeholder="<?php esc_attr_e('e.g. 1', 'bike-theme'); ?>">
                                    </div>
                                    <div class="flexible-pricing-cell">
                                        <input type="number" name="tour_flexible_pricing[<?php echo $i; ?>][price]" value="" min="0" placeholder="<?php esc_attr_e('e.g. 1000000', 'bike-theme'); ?>">
                                    </div>
                                    <div class="flexible-pricing-cell">
                                        <button type="button" class="button remove-price-row"><?php esc_html_e('Remove', 'bike-theme'); ?></button>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    
                    <div class="flexible-pricing-row">
                        <div class="flexible-pricing-cell">
                            <button type="button" class="button button-secondary add-price-row"><?php esc_html_e('Add Price Level', 'bike-theme'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <p>
            <label for="tour_max_participants"><?php esc_html_e('Maximum Participants', 'bike-theme'); ?></label>
            <input type="number" id="tour_max_participants" name="tour_max_participants" value="<?php echo esc_attr($max_participants); ?>" class="widefat">
        </p>
        <p>
            <label for="tour_difficulty"><?php esc_html_e('Difficulty Level', 'bike-theme'); ?></label>
            <select id="tour_difficulty" name="tour_difficulty" class="widefat">
                <option value="easy" <?php selected($difficulty, 'easy'); ?>><?php esc_html_e('Easy', 'bike-theme'); ?></option>
                <option value="moderate" <?php selected($difficulty, 'moderate'); ?>><?php esc_html_e('Moderate', 'bike-theme'); ?></option>
                <option value="difficult" <?php selected($difficulty, 'difficult'); ?>><?php esc_html_e('Difficult', 'bike-theme'); ?></option>
            </select>
        </p>
        <p>
            <label for="tour_start_location"><?php esc_html_e('Start Location', 'bike-theme'); ?></label>
            <input type="text" id="tour_start_location" name="tour_start_location" value="<?php echo esc_attr($start_location); ?>" class="widefat">
        </p>
        <p>
            <label for="tour_end_location"><?php esc_html_e('End Location', 'bike-theme'); ?></label>
            <input type="text" id="tour_end_location" name="tour_end_location" value="<?php echo esc_attr($end_location); ?>" class="widefat">
        </p>
        
        <!-- Itinerary Section -->
        <div class="tour-itinerary-section">
            <h3><?php esc_html_e('Tour Itinerary', 'bike-theme'); ?></h3>
            
            <div id="itinerary-days-container">
                <?php
                if (!empty($itinerary_data)) {
                    foreach ($itinerary_data as $day_index => $day) {
                        ?>
                        <div class="itinerary-day" data-day="<?php echo esc_attr($day_index); ?>">
                            <div class="day-header">
                                <h4><?php printf(esc_html__('Day %d', 'bike-theme'), $day_index + 1); ?></h4>
                                <button type="button" class="button remove-day"><?php esc_html_e('Remove Day', 'bike-theme'); ?></button>
                            </div>
                            
                            <div class="day-content">
                                <p>
                                    <label><?php esc_html_e('Day Title', 'bike-theme'); ?></label>
                                    <input type="text" name="tour_itinerary[<?php echo $day_index; ?>][title]" 
                                           value="<?php echo esc_attr($day['title']); ?>" class="widefat">
                                </p>
                                
                                <p>
                                    <label><?php esc_html_e('Description', 'bike-theme'); ?></label>
                                    <textarea name="tour_itinerary[<?php echo $day_index; ?>][description]" 
                                              class="widefat" rows="4"><?php echo esc_textarea($day['description']); ?></textarea>
                                </p>
                                
                                <div class="day-details">
                                    <div class="detail-column">
                                        <h5><?php esc_html_e('Accommodation', 'bike-theme'); ?></h5>
                                        <input type="text" name="tour_itinerary[<?php echo $day_index; ?>][accommodation]" 
                                               value="<?php echo esc_attr($day['accommodation']); ?>" class="widefat">
                                    </div>
                                    
                                    <div class="detail-column">
                                        <h5><?php esc_html_e('Meals', 'bike-theme'); ?></h5>
                                        <div class="meals-checkboxes">
                                            <?php
                                            $meals = array('breakfast', 'lunch', 'dinner');
                                            foreach ($meals as $meal) {
                                                $checked = isset($day['meals'][$meal]) ? $day['meals'][$meal] : false;
                                                ?>
                                                <label>
                                                    <input type="checkbox" 
                                                           name="tour_itinerary[<?php echo $day_index; ?>][meals][<?php echo $meal; ?>]" 
                                                           value="1" 
                                                           <?php checked($checked, true); ?>>
                                                    <?php echo esc_html(ucfirst($meal)); ?>
                                                </label>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    
                                    <div class="detail-column">
                                        <h5><?php esc_html_e('Distance', 'bike-theme'); ?></h5>
                                        <input type="number" name="tour_itinerary[<?php echo $day_index; ?>][distance]" 
                                               value="<?php echo esc_attr($day['distance']); ?>" class="widefat" step="0.1">
                                        <span class="unit">km</span>
                                    </div>
                                </div>
                                
                                <!-- Additional Details -->
                                <div class="additional-details">
                                    <h5><?php esc_html_e('Additional Details', 'bike-theme'); ?></h5>
                                    <div class="details-container" data-day="<?php echo esc_attr($day_index); ?>">
                                        <?php
                                        if (!empty($day['additional_details'])) {
                                            foreach ($day['additional_details'] as $detail_index => $detail) {
                                                ?>
                                                <div class="detail-item">
                                                    <input type="text" 
                                                           name="tour_itinerary[<?php echo $day_index; ?>][additional_details][<?php echo $detail_index; ?>]" 
                                                           value="<?php echo esc_attr($detail); ?>" 
                                                           class="widefat">
                                                    <button type="button" class="button remove-detail"><?php esc_html_e('Remove', 'bike-theme'); ?></button>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                    <button type="button" class="button add-detail" data-day="<?php echo esc_attr($day_index); ?>">
                                        <?php esc_html_e('Add Detail', 'bike-theme'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            
            <p>
                <button type="button" class="button button-primary" id="add-itinerary-day">
                    <?php esc_html_e('Add New Day', 'bike-theme'); ?>
                </button>
            </p>
        </div>

        <style>
            .tour-itinerary-section {
                margin: 20px 0;
            }
            .itinerary-day {
                background: #fff;
                border: 1px solid #ddd;
                border-radius: 4px;
                margin-bottom: 20px;
                padding: 15px;
            }
            .day-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
            }
            .day-header h4 {
                margin: 0;
            }
            .day-content {
                padding: 10px;
            }
            .day-details {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
                margin: 15px 0;
            }
            .detail-column {
                background: #f9f9f9;
                padding: 10px;
                border-radius: 4px;
            }
            .detail-column h5 {
                margin: 0 0 10px 0;
            }
            .meals-checkboxes {
                display: flex;
                flex-direction: column;
                gap: 5px;
            }
            .additional-details {
                margin-top: 20px;
            }
            .detail-item {
                display: flex;
                gap: 10px;
                margin-bottom: 10px;
            }
            .detail-item input {
                flex: 1;
            }
            .unit {
                margin-left: 5px;
                color: #666;
            }
        </style>

        <script>
        jQuery(document).ready(function($) {
            // Add new day
            $('#add-itinerary-day').on('click', function() {
                var dayCount = $('.itinerary-day').length;
                var template = `
                    <div class="itinerary-day" data-day="${dayCount}">
                        <div class="day-header">
                            <h4><?php esc_html_e('Day', 'bike-theme'); ?> ${dayCount + 1}</h4>
                            <button type="button" class="button remove-day"><?php esc_html_e('Remove Day', 'bike-theme'); ?></button>
                        </div>
                        
                        <div class="day-content">
                            <p>
                                <label><?php esc_html_e('Day Title', 'bike-theme'); ?></label>
                                <input type="text" name="tour_itinerary[${dayCount}][title]" class="widefat">
                            </p>
                            
                            <p>
                                <label><?php esc_html_e('Description', 'bike-theme'); ?></label>
                                <textarea name="tour_itinerary[${dayCount}][description]" class="widefat" rows="4"></textarea>
                            </p>
                            
                            <div class="day-details">
                                <div class="detail-column">
                                    <h5><?php esc_html_e('Accommodation', 'bike-theme'); ?></h5>
                                    <input type="text" name="tour_itinerary[${dayCount}][accommodation]" class="widefat">
                                </div>
                                
                                <div class="detail-column">
                                    <h5><?php esc_html_e('Meals', 'bike-theme'); ?></h5>
                                    <div class="meals-checkboxes">
                                        <?php
                                        $meals = array('breakfast', 'lunch', 'dinner');
                                        foreach ($meals as $meal) {
                                            ?>
                                            <label>
                                                <input type="checkbox" name="tour_itinerary[${dayCount}][meals][<?php echo $meal; ?>]" value="1">
                                                <?php echo esc_html(ucfirst($meal)); ?>
                                            </label>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                
                                <div class="detail-column">
                                    <h5><?php esc_html_e('Distance', 'bike-theme'); ?></h5>
                                    <input type="number" name="tour_itinerary[${dayCount}][distance]" class="widefat" step="0.1">
                                    <span class="unit">km</span>
                                </div>
                            </div>
                            
                            <div class="additional-details">
                                <h5><?php esc_html_e('Additional Details', 'bike-theme'); ?></h5>
                                <div class="details-container" data-day="${dayCount}"></div>
                                <button type="button" class="button add-detail" data-day="${dayCount}">
                                    <?php esc_html_e('Add Detail', 'bike-theme'); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                $('#itinerary-days-container').append(template);
            });

            // Remove day
            $(document).on('click', '.remove-day', function() {
                if (confirm('<?php esc_html_e('Are you sure you want to remove this day?', 'bike-theme'); ?>')) {
                    $(this).closest('.itinerary-day').remove();
                    reindexDays();
                }
            });

            // Add detail
            $(document).on('click', '.add-detail', function() {
                var day = $(this).data('day');
                var detailsContainer = $(this).siblings('.details-container');
                var detailCount = detailsContainer.children().length;
                
                var template = `
                    <div class="detail-item">
                        <input type="text" name="tour_itinerary[${day}][additional_details][${detailCount}]" class="widefat">
                        <button type="button" class="button remove-detail"><?php esc_html_e('Remove', 'bike-theme'); ?></button>
                    </div>
                `;
                
                detailsContainer.append(template);
            });

            // Remove detail
            $(document).on('click', '.remove-detail', function() {
                $(this).closest('.detail-item').remove();
            });

            // Reindex days after removal
            function reindexDays() {
                $('.itinerary-day').each(function(index) {
                    var day = $(this);
                    day.attr('data-day', index);
                    day.find('h4').text('<?php esc_html_e('Day', 'bike-theme'); ?> ' + (index + 1));
                    
                    // Update all input names
                    day.find('input, textarea').each(function() {
                        var name = $(this).attr('name');
                        if (name) {
                            name = name.replace(/tour_itinerary\[\d+\]/, 'tour_itinerary[' + index + ']');
                            $(this).attr('name', name);
                        }
                    });
                });
            }
        });
        </script>

        <h3><?php esc_html_e('Services', 'bike-theme'); ?></h3>
        <p>
            <label for="tour_included"><?php esc_html_e('What\'s Included', 'bike-theme'); ?></label>
            <?php 
            wp_editor($included, 'tour_included', array(
                'textarea_name' => 'tour_included',
                'media_buttons' => true,
                'textarea_rows' => 5,
                'editor_class' => 'widefat',
                'teeny' => true
            )); 
            ?>
        </p>
        <p>
            <label for="tour_not_included"><?php esc_html_e('What\'s Not Included', 'bike-theme'); ?></label>
            <?php 
            wp_editor($not_included, 'tour_not_included', array(
                'textarea_name' => 'tour_not_included',
                'media_buttons' => true,
                'textarea_rows' => 5,
                'editor_class' => 'widefat',
                'teeny' => true
            )); 
            ?>
        </p>
        
        <h3><?php esc_html_e('Booking & Cancellation', 'bike-theme'); ?></h3>
        <p>
            <label for="tour_booking_terms"><?php esc_html_e('Booking Terms', 'bike-theme'); ?></label>
            <?php 
            wp_editor($booking_terms, 'tour_booking_terms', array(
                'textarea_name' => 'tour_booking_terms',
                'media_buttons' => true,
                'textarea_rows' => 5,
                'editor_class' => 'widefat',
                'teeny' => true
            )); 
            ?>
        </p>
        <p>
            <label for="tour_cancellation_policy"><?php esc_html_e('Cancellation Policy', 'bike-theme'); ?></label>
            <?php 
            wp_editor($cancellation_policy, 'tour_cancellation_policy', array(
                'textarea_name' => 'tour_cancellation_policy',
                'media_buttons' => true,
                'textarea_rows' => 5,
                'editor_class' => 'widefat',
                'teeny' => true
            )); 
            ?>
        </p>
        
        <h3><?php esc_html_e('Media Gallery', 'bike-theme'); ?></h3>
        <div class="tour-gallery-container">
            <input type="hidden" id="tour_gallery" name="tour_gallery" value="<?php echo esc_attr($gallery_ids); ?>">
            <div id="tour_gallery_preview" class="tour-gallery-preview">
                <?php
                if (!empty($gallery_ids)) {
                    $gallery_ids_array = explode(',', $gallery_ids);
                    foreach ($gallery_ids_array as $image_id) {
                        if ($image_id) {
                            $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                            if ($image_url) {
                                echo '<div class="gallery-image-item" data-id="' . esc_attr($image_id) . '">';
                                echo '<img src="' . esc_url($image_url) . '" alt="">';
                                echo '<button type="button" class="remove-gallery-image dashicons dashicons-no-alt"></button>';
                                echo '</div>';
                            }
                        }
                    }
                }
                ?>
            </div>
            <p>
                <button type="button" class="button add-gallery-images"><?php esc_html_e('Add Gallery Images', 'bike-theme'); ?></button>
            </p>
        </div>
        
        <p>
            <label for="tour_video_url"><?php esc_html_e('Video URL (YouTube or Vimeo)', 'bike-theme'); ?></label>
            <input type="url" id="tour_video_url" name="tour_video_url" value="<?php echo esc_attr($video_url); ?>" class="widefat" placeholder="https://www.youtube.com/watch?v=...">
        </p>
        
        <h3><?php esc_html_e('Contact Information', 'bike-theme'); ?></h3>
        <p>
            <label for="tour_contact_info"><?php esc_html_e('Contact Information for Booking', 'bike-theme'); ?></label>
            <?php 
            wp_editor($contact_info, 'tour_contact_info', array(
                'textarea_name' => 'tour_contact_info',
                'media_buttons' => true,
                'textarea_rows' => 5,
                'editor_class' => 'widefat',
                'teeny' => true
            )); 
            ?>
        </p>

        <h3><?php esc_html_e('Tour Additions', 'bike-theme'); ?></h3>
        <div class="tour-additions-section">
            <?php
            $additions = get_post_meta($post->ID, '_tour_additions', true);
            if (!is_array($additions)) {
                $additions = array();
            }
            ?>
            <div class="additions-container">
                <div class="additions-header">
                    <div class="addition-cell"><?php esc_html_e('Name', 'bike-theme'); ?></div>
                    <div class="addition-cell"><?php esc_html_e('Description', 'bike-theme'); ?></div>
                    <div class="addition-cell"><?php esc_html_e('Price', 'bike-theme'); ?></div>
                    <div class="addition-cell"><?php esc_html_e('Per Person', 'bike-theme'); ?></div>
                    <div class="addition-cell"></div>
                </div>
                <div id="additions-rows">
                    <?php
                    if (!empty($additions)) {
                        foreach ($additions as $key => $addition) {
                            ?>
                            <div class="addition-row">
                                <div class="addition-cell">
                                    <input type="text" name="tour_additions[<?php echo $key; ?>][name]" 
                                           value="<?php echo esc_attr($addition['name']); ?>" 
                                           class="widefat" placeholder="<?php esc_attr_e('e.g. Bike Rental', 'bike-theme'); ?>">
                                </div>
                                <div class="addition-cell">
                                    <input type="text" name="tour_additions[<?php echo $key; ?>][description]" 
                                           value="<?php echo esc_attr($addition['description']); ?>" 
                                           class="widefat" placeholder="<?php esc_attr_e('e.g. High-quality mountain bike', 'bike-theme'); ?>">
                                </div>
                                <div class="addition-cell">
                                    <input type="number" name="tour_additions[<?php echo $key; ?>][price]" 
                                           value="<?php echo esc_attr($addition['price']); ?>" 
                                           class="widefat" min="0" step="0.01" placeholder="0.00">
                                </div>
                                <div class="addition-cell">
                                    <input type="checkbox" name="tour_additions[<?php echo $key; ?>][per_person]" 
                                           value="1" <?php checked(isset($addition['per_person']) && $addition['per_person'], true); ?>>
                                </div>
                                <div class="addition-cell">
                                    <button type="button" class="button remove-addition"><?php esc_html_e('Remove', 'bike-theme'); ?></button>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <div class="addition-row">
                    <div class="addition-cell">
                        <button type="button" class="button button-secondary add-addition"><?php esc_html_e('Add Extra Service', 'bike-theme'); ?></button>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .flexible-pricing-container {
                border: 1px solid #ddd;
                padding: 15px;
                margin-bottom: 20px;
                background: #f9f9f9;
                border-radius: 4px;
            }
            .flexible-pricing-row {
                display: flex;
                margin-bottom: 10px;
                align-items: center;
            }
            .flexible-pricing-header {
                display: flex;
                width: 100%;
                font-weight: bold;
                margin-bottom: 5px;
            }
            .flexible-pricing-cell {
                flex: 1;
                padding: 0 10px;
            }
            .flexible-pricing-cell:last-child {
                flex: 0.5;
            }
            .flexible-pricing-cell input {
                width: 100%;
            }
            .tour-gallery-preview {
                display: flex;
                flex-wrap: wrap;
                margin: 10px 0;
                gap: 10px;
            }
            .gallery-image-item {
                position: relative;
                width: 100px;
                height: 100px;
                border: 1px solid #ddd;
                border-radius: 4px;
                overflow: hidden;
            }
            .gallery-image-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .remove-gallery-image {
                position: absolute;
                top: 0;
                right: 0;
                background: rgba(0,0,0,0.5);
                color: #fff;
                border: none;
                cursor: pointer;
                padding: 2px;
                line-height: 1;
            }
            .remove-gallery-image:hover {
                background: rgba(0,0,0,0.8);
            }
            .tour-meta-box h3 {
                margin: 1.5em 0 0.5em;
                padding-bottom: 0.5em;
                border-bottom: 1px solid #ddd;
            }
            .tour-additions-section {
                border: 1px solid #ddd;
                padding: 15px;
                margin: 15px 0;
                background: #f9f9f9;
                border-radius: 4px;
            }
            .additions-container {
                margin-top: 10px;
            }
            .additions-header {
                display: flex;
                background: #f1f1f1;
                padding: 8px;
                font-weight: bold;
                border-radius: 4px 4px 0 0;
            }
            .addition-row {
                display: flex;
                align-items: center;
                padding: 8px;
                border-bottom: 1px solid #eee;
            }
            .addition-row:last-child {
                border-bottom: none;
            }
            .addition-cell {
                padding: 0 8px;
            }
            .addition-cell:nth-child(1) { /* Name */
                flex: 2;
            }
            .addition-cell:nth-child(2) { /* Description */
                flex: 3;
            }
            .addition-cell:nth-child(3) { /* Price */
                flex: 1;
            }
            .addition-cell:nth-child(4) { /* Per Person */
                flex: 0.5;
                text-align: center;
            }
            .addition-cell:nth-child(5) { /* Actions */
                flex: 0.5;
            }
            .addition-cell input[type="checkbox"] {
                margin: 0;
            }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            // Toggle pricing display
            $('#tour_flexible_pricing_enabled').change(function() {
                if ($(this).is(':checked')) {
                    $('#standard-pricing').hide();
                    $('#flexible-pricing').show();
                } else {
                    $('#standard-pricing').show();
                    $('#flexible-pricing').hide();
                }
            });
            
            // Add new price row
            $('.add-price-row').click(function() {
                var rowCount = $('#flexible-pricing-rows .flexible-pricing-row').length;
                var newRow = '<div class="flexible-pricing-row">' +
                    '<div class="flexible-pricing-cell">' +
                    '<input type="number" name="tour_flexible_pricing[' + (rowCount + 1) + '][participants]" value="" min="1" placeholder="<?php esc_attr_e('e.g. 1', 'bike-theme'); ?>">' +
                    '</div>' +
                    '<div class="flexible-pricing-cell">' +
                    '<input type="number" name="tour_flexible_pricing[' + (rowCount + 1) + '][price]" value="" min="0" placeholder="<?php esc_attr_e('e.g. 1000000', 'bike-theme'); ?>">' +
                    '</div>' +
                    '<div class="flexible-pricing-cell">' +
                    '<button type="button" class="button remove-price-row"><?php esc_html_e('Remove', 'bike-theme'); ?></button>' +
                    '</div>' +
                    '</div>';
                $('#flexible-pricing-rows').append(newRow);
            });
            
            // Remove price row
            $(document).on('click', '.remove-price-row', function() {
                $(this).closest('.flexible-pricing-row').remove();
                // Re-index the rows for proper saving
                reindexRows();
            });
            
            function reindexRows() {
                $('#flexible-pricing-rows .flexible-pricing-row').each(function(index) {
                    $(this).find('input').each(function() {
                        var name = $(this).attr('name');
                        name = name.replace(/\[\d+\]/, '[' + (index + 1) + ']');
                        $(this).attr('name', name);
                    });
                });
            }
            
            // Gallery image management
            var gallery_frame;
            
            $('.add-gallery-images').on('click', function(e) {
                e.preventDefault();
                
                // If the frame already exists, open it
                if (gallery_frame) {
                    gallery_frame.open();
                    return;
                }
                
                // Create the media frame
                gallery_frame = wp.media({
                    title: '<?php esc_html_e('Select or Upload Tour Gallery Images', 'bike-theme'); ?>',
                    button: {
                        text: '<?php esc_html_e('Add to Gallery', 'bike-theme'); ?>'
                    },
                    multiple: true
                });
                
                // When an image is selected, run a callback
                gallery_frame.on('select', function() {
                    var selection = gallery_frame.state().get('selection');
                    var ids = [];
                    var currentIds = $('#tour_gallery').val() ? $('#tour_gallery').val().split(',') : [];
                    
                    // Add existing IDs to the array
                    if (currentIds.length > 0) {
                        for (var i = 0; i < currentIds.length; i++) {
                            if (currentIds[i]) {
                                ids.push(currentIds[i]);
                            }
                        }
                    }
                    
                    // Add new IDs to the array
                    selection.forEach(function(attachment) {
                        var attachmentId = attachment.id;
                        if (ids.indexOf(attachmentId.toString()) === -1) {
                            ids.push(attachmentId);
                            
                            // Add image preview
                            var image = attachment.attributes.sizes.thumbnail ? attachment.attributes.sizes.thumbnail.url : attachment.attributes.url;
                            $('#tour_gallery_preview').append(
                                '<div class="gallery-image-item" data-id="' + attachmentId + '">' +
                                '<img src="' + image + '" alt="">' +
                                '<button type="button" class="remove-gallery-image dashicons dashicons-no-alt"></button>' +
                                '</div>'
                            );
                        }
                    });
                    
                    // Update the input value
                    $('#tour_gallery').val(ids.join(','));
                });
                
                // Open the frame
                gallery_frame.open();
            });
            
            // Remove gallery image
            $(document).on('click', '.remove-gallery-image', function() {
                var imageItem = $(this).closest('.gallery-image-item');
                var imageId = imageItem.data('id');
                var currentIds = $('#tour_gallery').val().split(',');
                var newIds = [];
                
                // Filter out the removed ID
                for (var i = 0; i < currentIds.length; i++) {
                    if (currentIds[i] != imageId) {
                        newIds.push(currentIds[i]);
                    }
                }
                
                // Update the input value
                $('#tour_gallery').val(newIds.join(','));
                
                // Remove the image preview
                imageItem.remove();
            });

            // Add new addition row
            $('.add-addition').click(function() {
                var rowCount = $('#additions-rows .addition-row').length;
                var newRow = '<div class="addition-row">' +
                    '<div class="addition-cell">' +
                    '<input type="text" name="tour_additions[' + rowCount + '][name]" class="widefat" placeholder="<?php esc_attr_e('e.g. Bike Rental', 'bike-theme'); ?>">' +
                    '</div>' +
                    '<div class="addition-cell">' +
                    '<input type="text" name="tour_additions[' + rowCount + '][description]" class="widefat" placeholder="<?php esc_attr_e('e.g. High-quality mountain bike', 'bike-theme'); ?>">' +
                    '</div>' +
                    '<div class="addition-cell">' +
                    '<input type="number" name="tour_additions[' + rowCount + '][price]" class="widefat" min="0" step="0.01" placeholder="0.00">' +
                    '</div>' +
                    '<div class="addition-cell">' +
                    '<input type="checkbox" name="tour_additions[' + rowCount + '][per_person]" value="1">' +
                    '</div>' +
                    '<div class="addition-cell">' +
                    '<button type="button" class="button remove-addition"><?php esc_html_e('Remove', 'bike-theme'); ?></button>' +
                    '</div>' +
                    '</div>';
                $('#additions-rows').append(newRow);
            });
            
            // Remove addition row
            $(document).on('click', '.remove-addition', function() {
                $(this).closest('.addition-row').remove();
                reindexAdditions();
            });
            
            function reindexAdditions() {
                $('#additions-rows .addition-row').each(function(index) {
                    $(this).find('input').each(function() {
                        var name = $(this).attr('name');
                        name = name.replace(/\[\d+\]/, '[' + index + ']');
                        $(this).attr('name', name);
                    });
                });
            }
        });
        </script>
    </div>
    <?php
}

/**
 * Save Tour meta box data
 */
function bike_theme_save_tour_meta_box_data($post_id)
{
    if (!isset($_POST['bike_theme_tour_details_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['bike_theme_tour_details_nonce'], 'bike_theme_tour_details')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save duration type
    $duration_type = sanitize_text_field($_POST['tour_duration_type']);
    update_post_meta($post_id, '_tour_duration_type', $duration_type);

    // Save duration values based on type
    if ($duration_type === 'days_nights') {
        $days = absint($_POST['tour_duration_days']);
        $nights = absint($_POST['tour_duration_nights']);
        update_post_meta($post_id, '_tour_duration_days', $days);
        update_post_meta($post_id, '_tour_duration_nights', $nights);
        // Format duration string for display
        $duration_string = sprintf(
            _n('%d day', '%d days', $days, 'bike-theme'),
            $days
        );
        if ($nights > 0) {
            $duration_string .= ' ' . sprintf(
                _n('%d night', '%d nights', $nights, 'bike-theme'),
                $nights
            );
        }
    } else {
        $hours = floatval($_POST['tour_duration_hours']);
        update_post_meta($post_id, '_tour_duration_hours', $hours);
        // Format duration string for display
        $duration_string = sprintf(
            _n('%g hour', '%g hours', ceil($hours), 'bike-theme'),
            $hours
        );
    }
    
    // Save formatted duration string for easy display
    update_post_meta($post_id, '_tour_duration_display', $duration_string);

    // Save itinerary data
    if (isset($_POST['tour_itinerary']) && is_array($_POST['tour_itinerary'])) {
        $itinerary_data = array();
        
        foreach ($_POST['tour_itinerary'] as $day_index => $day) {
            $itinerary_data[$day_index] = array(
                'title' => sanitize_text_field($day['title']),
                'description' => wp_kses_post($day['description']),
                'accommodation' => sanitize_text_field($day['accommodation']),
                'distance' => floatval($day['distance']),
                'meals' => array(
                    'breakfast' => isset($day['meals']['breakfast']),
                    'lunch' => isset($day['meals']['lunch']),
                    'dinner' => isset($day['meals']['dinner'])
                )
            );
            
            // Save additional details
            if (isset($day['additional_details']) && is_array($day['additional_details'])) {
                $itinerary_data[$day_index]['additional_details'] = array_map('sanitize_text_field', $day['additional_details']);
            }
        }
        
        update_post_meta($post_id, '_tour_itinerary_data', $itinerary_data);
    }

    $fields = array(
        'tour_distance',
        'tour_price',
        'tour_max_participants',
        'tour_difficulty',
        'tour_start_location',
        'tour_end_location',
        'tour_schedule',
        'tour_included',
        'tour_not_included',
        'tour_booking_terms',
        'tour_cancellation_policy',
        'tour_contact_info',
        'tour_gallery',
        'tour_video_url'
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            if (in_array($field, array('tour_itinerary_data', 'tour_included', 'tour_not_included', 'tour_booking_terms', 'tour_cancellation_policy', 'tour_contact_info'))) {
                // For WYSIWYG fields, save without strict sanitization
                update_post_meta($post_id, '_' . $field, wp_kses_post($_POST[$field]));
            } else {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }
    }
    
    // Save flexible pricing option
    $flexible_pricing_enabled = isset($_POST['tour_flexible_pricing_enabled']) ? '1' : '0';
    update_post_meta($post_id, '_tour_flexible_pricing_enabled', $flexible_pricing_enabled);
    
    // Save flexible pricing data
    if (isset($_POST['tour_flexible_pricing']) && is_array($_POST['tour_flexible_pricing'])) {
        $pricing_data = array();
        foreach ($_POST['tour_flexible_pricing'] as $key => $price_item) {
            if (!empty($price_item['participants']) && !empty($price_item['price'])) {
                $pricing_data[] = array(
                    'participants' => absint($price_item['participants']),
                    'price' => absint($price_item['price'])
                );
            }
        }
        
        // Sort by number of participants
        usort($pricing_data, function($a, $b) {
            return $a['participants'] - $b['participants'];
        });
        
        update_post_meta($post_id, '_tour_flexible_pricing', $pricing_data);
    } else {
        delete_post_meta($post_id, '_tour_flexible_pricing');
    }

    // Save tour additions
    if (isset($_POST['tour_additions']) && is_array($_POST['tour_additions'])) {
        $additions_data = array();
        foreach ($_POST['tour_additions'] as $addition) {
            if (!empty($addition['name']) && isset($addition['price'])) {
                $additions_data[] = array(
                    'name' => sanitize_text_field($addition['name']),
                    'description' => sanitize_text_field($addition['description']),
                    'price' => floatval($addition['price']),
                    'per_person' => isset($addition['per_person']) ? true : false
                );
            }
        }
        update_post_meta($post_id, '_tour_additions', $additions_data);
    } else {
        delete_post_meta($post_id, '_tour_additions');
    }
}
add_action('save_post_bike_tour', 'bike_theme_save_tour_meta_box_data');

/**
 * Add Booking meta boxes
 */
function bike_theme_add_booking_meta_boxes()
{
    add_meta_box(
        'booking_details',
        __('Booking Details', 'bike-theme'),
        'bike_theme_booking_details_callback',
        'bike_booking',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'bike_theme_add_booking_meta_boxes');

/**
 * Booking details meta box callback
 */
function bike_theme_booking_details_callback($post)
{
    wp_nonce_field('bike_theme_booking_details', 'bike_theme_booking_details_nonce');

    $customer_name = get_post_meta($post->ID, '_booking_customer_name', true);
    $customer_email = get_post_meta($post->ID, '_booking_customer_email', true);
    $customer_phone = get_post_meta($post->ID, '_booking_customer_phone', true);
    $booking_date = get_post_meta($post->ID, '_booking_date', true);
    $number_of_participants = get_post_meta($post->ID, '_booking_participants', true);
    $special_requests = get_post_meta($post->ID, '_booking_message', true);
    $tour_id = get_post_meta($post->ID, '_booking_tour_id', true);
    $price_per_person = get_post_meta($post->ID, '_booking_price_per_person', true);
    $total_price = get_post_meta($post->ID, '_booking_total_price', true);
    $booking_status = get_post_meta($post->ID, '_booking_status', true);
    ?>
    <div class="booking-meta-box">
        <div class="booking-section customer-info">
            <h3><?php esc_html_e('Customer Information', 'bike-theme'); ?></h3>
            <p>
                <label for="booking_customer_name"><?php esc_html_e('Customer Name', 'bike-theme'); ?></label>
                <input type="text" id="booking_customer_name" name="booking_customer_name" value="<?php echo esc_attr($customer_name); ?>" class="widefat">
            </p>
            <p>
                <label for="booking_customer_email"><?php esc_html_e('Customer Email', 'bike-theme'); ?></label>
                <input type="email" id="booking_customer_email" name="booking_customer_email" value="<?php echo esc_attr($customer_email); ?>" class="widefat">
            </p>
            <p>
                <label for="booking_customer_phone"><?php esc_html_e('Customer Phone', 'bike-theme'); ?></label>
                <input type="tel" id="booking_customer_phone" name="booking_customer_phone" value="<?php echo esc_attr($customer_phone); ?>" class="widefat">
            </p>
        </div>

        <div class="booking-section tour-info">
            <h3><?php esc_html_e('Tour Information', 'bike-theme'); ?></h3>
            <p>
                <label for="booking_tour_id"><?php esc_html_e('Tour', 'bike-theme'); ?></label>
                <select id="booking_tour_id" name="booking_tour_id" class="widefat">
                    <option value=""><?php esc_html_e('Select a tour', 'bike-theme'); ?></option>
                    <?php
                    $tours = get_posts(array(
                        'post_type' => 'bike_tour',
                        'posts_per_page' => -1,
                        'orderby' => 'title',
                        'order' => 'ASC'
                    ));
                    foreach ($tours as $tour) {
                        echo '<option value="' . esc_attr($tour->ID) . '"' . selected($tour_id, $tour->ID, false) . '>' . esc_html($tour->post_title) . '</option>';
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="booking_date"><?php esc_html_e('Booking Date', 'bike-theme'); ?></label>
                <input type="date" id="booking_date" name="booking_date" value="<?php echo esc_attr($booking_date); ?>" class="widefat">
            </p>
            <p>
                <label for="booking_participants"><?php esc_html_e('Number of Participants', 'bike-theme'); ?></label>
                <input type="number" id="booking_participants" name="booking_participants" value="<?php echo esc_attr($number_of_participants); ?>" min="1" class="widefat">
            </p>
        </div>

        <div class="booking-section pricing-info">
            <h3><?php esc_html_e('Pricing Information', 'bike-theme'); ?></h3>
            <p>
                <label for="booking_price_per_person"><?php esc_html_e('Price per Person', 'bike-theme'); ?></label>
                <input type="number" id="booking_price_per_person" name="booking_price_per_person" value="<?php echo esc_attr($price_per_person); ?>" class="widefat" readonly>
            </p>
            <p>
                <label for="booking_total_price"><?php esc_html_e('Total Price', 'bike-theme'); ?></label>
                <input type="number" id="booking_total_price" name="booking_total_price" value="<?php echo esc_attr($total_price); ?>" class="widefat" readonly>
            </p>

            <!-- Additions Section -->
            <div class="booking-additions">
                <h4><?php esc_html_e('Selected Additions', 'bike-theme'); ?></h4>
                <?php
                $selected_additions = get_post_meta($post->ID, '_booking_additions', true);
                if (!empty($selected_additions)) {
                    echo '<div class="additions-list">';
                    $additions_total = 0;
                    foreach ($selected_additions as $addition) {
                        $price = floatval($addition['price']);
                        $per_person = isset($addition['per_person']) && $addition['per_person'];
                        $total = $per_person ? $price * $number_of_participants : $price;
                        $additions_total += $total;
                        ?>
                        <div class="addition-item">
                            <div class="addition-details">
                                <strong><?php echo esc_html($addition['name']); ?></strong>
                                <span class="addition-price">
                                    <?php 
                                    echo esc_html(number_format($price, 0, ',', '.') . ' VND');
                                    if ($per_person) {
                                        echo ' ' . esc_html__('per person', 'bike-theme');
                                    }
                                    ?>
                                </span>
                            </div>
                            <div class="addition-total">
                                <?php echo esc_html(number_format($total, 0, ',', '.') . ' VND'); ?>
                            </div>
                        </div>
                        <?php
                    }
                    echo '<div class="additions-total">';
                    echo '<strong>' . esc_html__('Additions Total:', 'bike-theme') . '</strong> ';
                    echo '<span>' . esc_html(number_format($additions_total, 0, ',', '.') . ' VND') . '</span>';
                    echo '</div>';
                    echo '</div>';
                } else {
                    echo '<p class="no-additions">' . esc_html__('No additions selected', 'bike-theme') . '</p>';
                }
                ?>
            </div>

            <!-- Grand Total -->
            <div class="grand-total">
                <label><?php esc_html_e('Grand Total', 'bike-theme'); ?></label>
                <div class="total-amount">
                    <?php
                    $tour_total = floatval($total_price);
                    $additions_total = !empty($additions_total) ? $additions_total : 0;
                    $grand_total = $tour_total + $additions_total;
                    echo esc_html(number_format($grand_total, 0, ',', '.') . ' VND');
                    ?>
                </div>
            </div>
        </div>

        <div class="booking-section status-info">
            <h3><?php esc_html_e('Booking Status', 'bike-theme'); ?></h3>
            <p>
                <label for="booking_status"><?php esc_html_e('Status', 'bike-theme'); ?></label>
                <select id="booking_status" name="booking_status" class="widefat">
                    <option value="pending" <?php selected($booking_status, 'pending'); ?>><?php esc_html_e('Pending', 'bike-theme'); ?></option>
                    <option value="confirmed" <?php selected($booking_status, 'confirmed'); ?>><?php esc_html_e('Confirmed', 'bike-theme'); ?></option>
                    <option value="completed" <?php selected($booking_status, 'completed'); ?>><?php esc_html_e('Completed', 'bike-theme'); ?></option>
                    <option value="cancelled" <?php selected($booking_status, 'cancelled'); ?>><?php esc_html_e('Cancelled', 'bike-theme'); ?></option>
                </select>
            </p>
        </div>

        <div class="booking-section special-requests">
            <h3><?php esc_html_e('Special Requests', 'bike-theme'); ?></h3>
            <p>
                <label for="booking_message"><?php esc_html_e('Special Requests', 'bike-theme'); ?></label>
                <textarea id="booking_message" name="booking_message" rows="5" class="widefat"><?php echo esc_textarea($special_requests); ?></textarea>
            </p>
        </div>
    </div>

    <style>
        .booking-meta-box {
            padding: 12px;
        }
        .booking-section {
            margin-bottom: 24px;
            padding: 16px;
            background: #f9f9f9;
            border: 1px solid #e5e5e5;
            border-radius: 4px;
        }
        .booking-section h3 {
            margin-top: 0;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e5e5;
        }
        .booking-section p {
            margin-bottom: 16px;
        }
        .booking-section label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .booking-section input[readonly] {
            background-color: #f0f0f1;
        }
        .booking-additions {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e5e5;
        }
        .booking-additions h4 {
            margin: 0 0 15px 0;
            color: #23282d;
        }
        .additions-list {
            background: #fff;
            border: 1px solid #e5e5e5;
            border-radius: 4px;
            padding: 10px;
        }
        .addition-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px;
            border-bottom: 1px solid #f0f0f0;
        }
        .addition-item:last-child {
            border-bottom: none;
        }
        .addition-details {
            flex: 1;
        }
        .addition-price {
            color: #666;
            margin-left: 10px;
            font-size: 0.9em;
        }
        .addition-total {
            font-weight: 500;
            color: #23282d;
        }
        .additions-total {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 2px solid #f0f0f0;
            text-align: right;
            font-size: 1.1em;
        }
        .no-additions {
            color: #666;
            font-style: italic;
            margin: 0;
        }
        .grand-total {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border: 2px solid #007cba;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .grand-total label {
            font-size: 1.2em;
            font-weight: 600;
            color: #23282d;
            margin: 0;
        }
        .total-amount {
            font-size: 1.2em;
            font-weight: 600;
            color: #007cba;
        }
    </style>

    <script>
    jQuery(document).ready(function($) {
        // Function to update price calculations
        function updatePriceCalculations() {
            var tourId = $('#booking_tour_id').val();
            var participants = parseInt($('#booking_participants').val()) || 1;

            if (tourId) {
                // Get tour price data via Ajax
                $.post(ajaxurl, {
                    action: 'get_tour_price',
                    tour_id: tourId,
                    participants: participants,
                    nonce: '<?php echo wp_create_nonce('get_tour_price_nonce'); ?>'
                }, function(response) {
                    if (response.success) {
                        $('#booking_price_per_person').val(response.data.price_per_person);
                        $('#booking_total_price').val(response.data.total_price);
                    }
                });
            }
        }

        // Update prices when tour or participants change
        $('#booking_tour_id, #booking_participants').on('change', updatePriceCalculations);

        // Initial price calculation
        updatePriceCalculations();
    });
    </script>
    <?php
}

/**
 * Save Booking meta box data
 */
function bike_theme_save_booking_meta_box_data($post_id)
{
    if (!isset($_POST['bike_theme_booking_details_nonce'])) {
        return;
    }

    if (!wp_verify_nonce($_POST['bike_theme_booking_details_nonce'], 'bike_theme_booking_details')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = array(
        'booking_customer_name',
        'booking_customer_email',
        'booking_customer_phone',
        'booking_date',
        'booking_participants',
        'booking_tour_id',
        'booking_price_per_person',
        'booking_total_price',
        'booking_status',
        'booking_message'
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $value = sanitize_text_field($_POST[$field]);
            update_post_meta($post_id, '_' . $field, $value);
        }
    }

    // Handle status change notifications
    $old_status = get_post_meta($post_id, '_booking_status', true);
    $new_status = sanitize_text_field($_POST['booking_status']);

    if ($old_status !== $new_status) {
        // Send email notifications about status change
        $customer_email = get_post_meta($post_id, '_booking_customer_email', true);
        $tour_id = get_post_meta($post_id, '_booking_tour_id', true);
        
        $subject = sprintf(__('Booking Status Update - %s', 'bike-theme'), get_the_title($tour_id));
        $message = sprintf(
            __('Your booking for %s has been updated to: %s', 'bike-theme'),
            get_the_title($tour_id),
            ucfirst($new_status)
        );
        
        wp_mail($customer_email, $subject, $message);
    }
}
add_action('save_post_bike_booking', 'bike_theme_save_booking_meta_box_data');

/**
 * Ajax handler for getting tour price
 */
function bike_theme_get_tour_price_ajax() {
    check_ajax_referer('get_tour_price_nonce', 'nonce');

    $tour_id = isset($_POST['tour_id']) ? intval($_POST['tour_id']) : 0;
    $participants = isset($_POST['participants']) ? intval($_POST['participants']) : 1;

    if (!$tour_id) {
        wp_send_json_error('Invalid tour ID');
    }

    $price_per_person = bike_theme_get_tour_price($tour_id, $participants);
    $total_price = $price_per_person * $participants;

    wp_send_json_success(array(
        'price_per_person' => $price_per_person,
        'total_price' => $total_price
    ));
}
add_action('wp_ajax_get_tour_price', 'bike_theme_get_tour_price_ajax');
