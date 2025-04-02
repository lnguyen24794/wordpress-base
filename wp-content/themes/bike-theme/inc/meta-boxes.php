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

    $duration = get_post_meta($post->ID, '_tour_duration', true);
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
    $itinerary = get_post_meta($post->ID, '_tour_itinerary', true);
    
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
        <p>
            <label for="tour_duration"><?php esc_html_e('Duration (days)', 'bike-theme'); ?></label>
            <input type="number" id="tour_duration" name="tour_duration" value="<?php echo esc_attr($duration); ?>" class="widefat">
        </p>
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
        
        <h3><?php esc_html_e('Detailed Itinerary', 'bike-theme'); ?></h3>
        <p>
            <label for="tour_itinerary"><?php esc_html_e('Detailed Day by Day Itinerary', 'bike-theme'); ?></label>
            <?php 
            wp_editor($itinerary, 'tour_itinerary', array(
                'textarea_name' => 'tour_itinerary',
                'media_buttons' => true,
                'textarea_rows' => 10,
                'editor_class' => 'widefat',
                'teeny' => false
            )); 
            ?>
        </p>
        
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
    });
    </script>
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

    $fields = array(
        'tour_duration',
        'tour_distance',
        'tour_price',
        'tour_max_participants',
        'tour_difficulty',
        'tour_start_location',
        'tour_end_location',
        'tour_schedule',
        'tour_included',
        'tour_not_included',
        'tour_itinerary',
        'tour_booking_terms',
        'tour_cancellation_policy',
        'tour_contact_info',
        'tour_gallery',
        'tour_video_url'
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            if (in_array($field, array('tour_itinerary', 'tour_included', 'tour_not_included', 'tour_booking_terms', 'tour_cancellation_policy', 'tour_contact_info'))) {
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
    $number_of_participants = get_post_meta($post->ID, '_booking_number_of_participants', true);
    $special_requests = get_post_meta($post->ID, '_booking_special_requests', true);
    $tour_id = get_post_meta($post->ID, '_booking_tour_id', true);
    $bike_id = get_post_meta($post->ID, '_booking_bike_id', true);
    ?>
    <div class="booking-meta-box">
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
        <p>
            <label for="booking_date"><?php esc_html_e('Booking Date', 'bike-theme'); ?></label>
            <input type="date" id="booking_date" name="booking_date" value="<?php echo esc_attr($booking_date); ?>" class="widefat">
        </p>
        <p>
            <label for="booking_number_of_participants"><?php esc_html_e('Number of Participants', 'bike-theme'); ?></label>
            <input type="number" id="booking_number_of_participants" name="booking_number_of_participants" value="<?php echo esc_attr($number_of_participants); ?>" class="widefat">
        </p>
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
            <label for="booking_bike_id"><?php esc_html_e('Bike', 'bike-theme'); ?></label>
            <select id="booking_bike_id" name="booking_bike_id" class="widefat">
                <option value=""><?php esc_html_e('Select a bike', 'bike-theme'); ?></option>
                <?php
    $bikes = get_posts(array(
        'post_type' => 'bike',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    ));
    foreach ($bikes as $bike) {
        echo '<option value="' . esc_attr($bike->ID) . '"' . selected($bike_id, $bike->ID, false) . '>' . esc_html($bike->post_title) . '</option>';
    }
    ?>
            </select>
        </p>
        <p>
            <label for="booking_special_requests"><?php esc_html_e('Special Requests', 'bike-theme'); ?></label>
            <textarea id="booking_special_requests" name="booking_special_requests" rows="5" class="widefat"><?php echo esc_textarea($special_requests); ?></textarea>
        </p>
    </div>
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
        'booking_number_of_participants',
        'booking_tour_id',
        'booking_bike_id',
        'booking_special_requests'
    );

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post_bike_booking', 'bike_theme_save_booking_meta_box_data');
