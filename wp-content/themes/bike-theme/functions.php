<?php

/**
 * Bike Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Bike_Theme
 */

if (! defined('BIKE_THEME_VERSION')) {
    define('BIKE_THEME_VERSION', '1.0.0');
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function bike_theme_setup()
{
    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title.
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support('post-thumbnails');

    // Register menu locations
    register_nav_menus(
        array(
            'primary' => esc_html__('Primary Menu', 'bike-theme'),
            'footer' => esc_html__('Footer Menu', 'bike-theme'),
        )
    );

    // Switch default core markup to output valid HTML5.
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        )
    );

    // Add theme support for selective refresh for widgets.
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for custom logo
    add_theme_support(
        'custom-logo',
        array(
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        )
    );

    // Register Destination Taxonomy for Bike Tours
    bike_theme_register_destination_taxonomy();
}
add_action('after_setup_theme', 'bike_theme_setup');

/**
 * Register Destination Taxonomy for Bike Tours
 */
function bike_theme_register_destination_taxonomy()
{
    $labels = array(
        'name'              => _x('Destinations', 'taxonomy general name', 'bike-theme'),
        'singular_name'     => _x('Destination', 'taxonomy singular name', 'bike-theme'),
        'search_items'      => __('Search Destinations', 'bike-theme'),
        'all_items'         => __('All Destinations', 'bike-theme'),
        'parent_item'       => __('Parent Destination', 'bike-theme'),
        'parent_item_colon' => __('Parent Destination:', 'bike-theme'),
        'edit_item'         => __('Edit Destination', 'bike-theme'),
        'update_item'       => __('Update Destination', 'bike-theme'),
        'add_new_item'      => __('Add New Destination', 'bike-theme'),
        'new_item_name'     => __('New Destination Name', 'bike-theme'),
        'menu_name'         => __('Destinations', 'bike-theme'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'destination'),
        'show_in_rest'      => true,
    );

    register_taxonomy('destination', array('bike_tour'), $args);
}
add_action('init', 'bike_theme_register_destination_taxonomy');

/**
 * Add image field to destination taxonomy
 */
function bike_theme_destination_add_image_field()
{
    ?>
    <div class="form-field">
        <label for="destination_image"><?php _e('Destination Image', 'bike-theme'); ?></label>
        <input type="hidden" id="destination_image" name="destination_image" class="custom_media_url" value="">
        <div id="destination-image-wrapper"></div>
        <p>
            <input type="button" class="button button-secondary destination_tax_media_button" id="destination_tax_media_button" name="destination_tax_media_button" value="<?php _e('Add Image', 'bike-theme'); ?>" />
            <input type="button" class="button button-secondary destination_tax_media_remove" id="destination_tax_media_remove" name="destination_tax_media_remove" value="<?php _e('Remove Image', 'bike-theme'); ?>" />
        </p>
    </div>
    <?php
}
add_action('destination_add_form_fields', 'bike_theme_destination_add_image_field', 10, 2);

/**
 * Edit image field in destination taxonomy
 */
function bike_theme_destination_edit_image_field($term)
{
    $image_id = get_term_meta($term->term_id, 'destination_image', true);
    $image_url = wp_get_attachment_url($image_id);
    ?>
    <tr class="form-field">
        <th scope="row" valign="top">
            <label for="destination_image"><?php _e('Destination Image', 'bike-theme'); ?></label>
        </th>
        <td>
            <input type="hidden" id="destination_image" name="destination_image" class="custom_media_url" value="<?php echo esc_attr($image_id); ?>">
            <div id="destination-image-wrapper">
                <?php if ($image_url) : ?>
                    <img src="<?php echo esc_url($image_url); ?>" style="max-width: 100%; height: auto; margin: 10px 0;">
                <?php endif; ?>
            </div>
            <p>
                <input type="button" class="button button-secondary destination_tax_media_button" id="destination_tax_media_button" name="destination_tax_media_button" value="<?php _e('Add Image', 'bike-theme'); ?>" />
                <input type="button" class="button button-secondary destination_tax_media_remove" id="destination_tax_media_remove" name="destination_tax_media_remove" value="<?php _e('Remove Image', 'bike-theme'); ?>" />
            </p>
        </td>
    </tr>
    <?php
}
add_action('destination_edit_form_fields', 'bike_theme_destination_edit_image_field', 10, 2);

/**
 * Save destination image
 */
function bike_theme_save_destination_image($term_id)
{
    if (isset($_POST['destination_image'])) {
        update_term_meta($term_id, 'destination_image', absint($_POST['destination_image']));
    }
}
add_action('created_destination', 'bike_theme_save_destination_image', 10, 2);
add_action('edited_destination', 'bike_theme_save_destination_image', 10, 2);

/**
 * Enqueue media uploader scripts
 */
function bike_theme_destination_media_scripts()
{
    if (!isset($_GET['taxonomy']) || $_GET['taxonomy'] != 'destination') {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_script('destination-media-uploader', get_template_directory_uri() . '/assets/js/destination-media.js', array('jquery'), BIKE_THEME_VERSION, true);
}
add_action('admin_enqueue_scripts', 'bike_theme_destination_media_scripts');

/**
 * Set the content width in pixels.
 */
function bike_theme_content_width()
{
    $GLOBALS['content_width'] = apply_filters('bike_theme_content_width', 1200);
}
add_action('after_setup_theme', 'bike_theme_content_width', 0);

/**
 * Register widget area.
 */
function bike_theme_widgets_init()
{
    register_sidebar(
        array(
            'name'          => esc_html__('Sidebar', 'bike-theme'),
            'id'            => 'sidebar-1',
            'description'   => esc_html__('Add widgets here.', 'bike-theme'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__('Footer 1', 'bike-theme'),
            'id'            => 'footer-1',
            'description'   => esc_html__('Add footer widgets here.', 'bike-theme'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__('Footer 2', 'bike-theme'),
            'id'            => 'footer-2',
            'description'   => esc_html__('Add footer widgets here.', 'bike-theme'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__('Footer 3', 'bike-theme'),
            'id'            => 'footer-3',
            'description'   => esc_html__('Add footer widgets here.', 'bike-theme'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );
}
add_action('widgets_init', 'bike_theme_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function bike_theme_scripts()
{
    // Enqueue custom fonts
    wp_enqueue_style('bike-theme-fonts', get_template_directory_uri() . '/assets/css/fonts.css', array(), '1.0.0');
    
    // Google Fonts
    wp_enqueue_style('bike-theme-google-fonts', 'https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700&display=swap', array(), null);

    // Font Awesome
    wp_enqueue_style('bike-theme-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css', array(), '5.10.0');

    // Bootstrap Icons
    wp_enqueue_style('bike-theme-bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css', array(), '1.4.1');

    // Libraries CSS
    wp_enqueue_style('bike-theme-animate', get_template_directory_uri() . '/lib/animate/animate.min.css', array(), BIKE_THEME_VERSION);
    wp_enqueue_style('bike-theme-owl-carousel', get_template_directory_uri() . '/lib/owlcarousel/assets/owl.carousel.min.css', array(), BIKE_THEME_VERSION);
    wp_enqueue_style('bike-theme-tempusdominus', get_template_directory_uri() . '/lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css', array(), BIKE_THEME_VERSION);

    // Bootstrap
    wp_enqueue_style('bike-theme-bootstrap', get_template_directory_uri() . '/assets/bootstrap/bootstrap.min.css', array(), BIKE_THEME_VERSION);

    // Theme Stylesheet
    wp_enqueue_style('bike-theme-style', get_stylesheet_uri(), array(), BIKE_THEME_VERSION);

    // Main Template Stylesheet
    wp_enqueue_style('bike-theme-main-style', get_template_directory_uri() . '/assets/css/style.css', array(), BIKE_THEME_VERSION);

    // Custom CSS
    wp_enqueue_style('bike-theme-custom', get_template_directory_uri() . '/assets/css/custom.css', array(), BIKE_THEME_VERSION);

    // Deregister core jQuery and register newer version from CDN
    wp_deregister_script('jquery');
    wp_register_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js', array(), '3.6.0', true);
    wp_enqueue_script('jquery');

    // Bootstrap JS
    wp_enqueue_script('bike-theme-bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.0.0', true);

    // Libraries JS
    wp_enqueue_script('bike-theme-wow', get_template_directory_uri() . '/lib/wow/wow.min.js', array('jquery'), BIKE_THEME_VERSION, true);
    wp_enqueue_script('bike-theme-easing', get_template_directory_uri() . '/lib/easing/easing.min.js', array('jquery'), BIKE_THEME_VERSION, true);
    wp_enqueue_script('bike-theme-waypoints', get_template_directory_uri() . '/lib/waypoints/waypoints.min.js', array('jquery'), BIKE_THEME_VERSION, true);
    wp_enqueue_script('bike-theme-counterup', get_template_directory_uri() . '/lib/counterup/counterup.min.js', array('jquery'), BIKE_THEME_VERSION, true);
    wp_enqueue_script('bike-theme-owl-carousel', get_template_directory_uri() . '/lib/owlcarousel/owl.carousel.min.js', array('jquery'), BIKE_THEME_VERSION, true);
    wp_enqueue_script('bike-theme-moment', get_template_directory_uri() . '/lib/tempusdominus/js/moment.min.js', array('jquery'), BIKE_THEME_VERSION, true);
    wp_enqueue_script('bike-theme-moment-timezone', get_template_directory_uri() . '/lib/tempusdominus/js/moment-timezone.min.js', array('jquery', 'bike-theme-moment'), BIKE_THEME_VERSION, true);
    wp_enqueue_script('bike-theme-tempusdominus', get_template_directory_uri() . '/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js', array('jquery', 'bike-theme-moment', 'bike-theme-moment-timezone'), BIKE_THEME_VERSION, true);

    // Navigation JS
    wp_enqueue_script('bike-theme-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), BIKE_THEME_VERSION, true);

    // Main JS
    wp_enqueue_script('bike-theme-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), BIKE_THEME_VERSION, true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'bike_theme_scripts');

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Register Custom Meta Boxes for Bikes
 */
function bike_theme_register_meta_boxes()
{
    add_meta_box(
        'bike_details',
        __('Bike Details', 'bike-theme'),
        'bike_theme_bike_details_callback',
        'bike',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'bike_theme_register_meta_boxes');

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function bike_theme_bike_details_callback($post)
{
    // Add a nonce field
    wp_nonce_field('bike_details_nonce_action', 'bike_details_nonce');

    // Get current values
    $price = get_post_meta($post->ID, 'bike_price', true);
    $weight = get_post_meta($post->ID, 'bike_weight', true);
    $frame = get_post_meta($post->ID, 'bike_frame', true);
    $gears = get_post_meta($post->ID, 'bike_gears', true);
    $color = get_post_meta($post->ID, 'bike_color', true);

    // Output fields
    ?>
    <p>
        <label for="bike_price"><?php esc_html_e('Price ($)', 'bike-theme'); ?></label><br>
        <input type="text" id="bike_price" name="bike_price" value="<?php echo esc_attr($price); ?>" class="widefat">
    </p>
    <p>
        <label for="bike_weight"><?php esc_html_e('Weight (kg)', 'bike-theme'); ?></label><br>
        <input type="text" id="bike_weight" name="bike_weight" value="<?php echo esc_attr($weight); ?>" class="widefat">
    </p>
    <p>
        <label for="bike_frame"><?php esc_html_e('Frame Material', 'bike-theme'); ?></label><br>
        <input type="text" id="bike_frame" name="bike_frame" value="<?php echo esc_attr($frame); ?>" class="widefat">
    </p>
    <p>
        <label for="bike_gears"><?php esc_html_e('Number of Gears', 'bike-theme'); ?></label><br>
        <input type="text" id="bike_gears" name="bike_gears" value="<?php echo esc_attr($gears); ?>" class="widefat">
    </p>
    <p>
        <label for="bike_color"><?php esc_html_e('Available Colors', 'bike-theme'); ?></label><br>
        <input type="text" id="bike_color" name="bike_color" value="<?php echo esc_attr($color); ?>" class="widefat">
    </p>
    <?php
}

/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function bike_theme_save_meta_boxes($post_id)
{
    // Check if nonce is set
    if (!isset($_POST['bike_details_nonce'])) {
        return;
    }

    // Verify that the nonce is valid
    if (!wp_verify_nonce($_POST['bike_details_nonce'], 'bike_details_nonce_action')) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions
    if (isset($_POST['post_type']) && 'bike' == $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Save each field
    if (isset($_POST['bike_price'])) {
        update_post_meta($post_id, 'bike_price', sanitize_text_field($_POST['bike_price']));
    }

    if (isset($_POST['bike_weight'])) {
        update_post_meta($post_id, 'bike_weight', sanitize_text_field($_POST['bike_weight']));
    }

    if (isset($_POST['bike_frame'])) {
        update_post_meta($post_id, 'bike_frame', sanitize_text_field($_POST['bike_frame']));
    }

    if (isset($_POST['bike_gears'])) {
        update_post_meta($post_id, 'bike_gears', sanitize_text_field($_POST['bike_gears']));
    }

    if (isset($_POST['bike_color'])) {
        update_post_meta($post_id, 'bike_color', sanitize_text_field($_POST['bike_color']));
    }
}
add_action('save_post', 'bike_theme_save_meta_boxes');

/**
 * Register Theme Options Page
 */
function bike_theme_register_options_page()
{
    add_theme_page(
        __('Bike Theme Options', 'bike-theme'),
        __('Bike Theme Options', 'bike-theme'),
        'manage_options',
        'bike-theme-options',
        'bike_theme_options_page_callback'
    );
}
add_action('admin_menu', 'bike_theme_register_options_page');

/**
 * Enqueue scripts for theme options page
 */
function bike_theme_admin_scripts($hook)
{
    if ('appearance_page_bike-theme-options' !== $hook) {
        return;
    }

    // Enqueue WordPress media scripts
    wp_enqueue_media();

    // Enqueue custom admin script
    wp_enqueue_script(
        'bike-theme-admin-js',
        get_template_directory_uri() . '/assets/js/admin.js',
        array('jquery'),
        BIKE_THEME_VERSION,
        true
    );

    // Enqueue admin styles
    wp_enqueue_style(
        'bike-theme-admin-css',
        get_template_directory_uri() . '/assets/css/admin.css',
        array(),
        BIKE_THEME_VERSION
    );
}
add_action('admin_enqueue_scripts', 'bike_theme_admin_scripts');

/**
 * Theme Options Page Callback
 */
function bike_theme_options_page_callback()
{
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // Save settings if submitted
    if (isset($_POST['bike_theme_options_submit'])) {
        // Verify nonce
        if (check_admin_referer('bike_theme_options_nonce')) {
            // Header contact info
            $email = isset($_POST['bike_theme_email']) ? sanitize_email($_POST['bike_theme_email']) : '';
            update_option('bike_theme_email', $email);

            $phone = isset($_POST['bike_theme_phone']) ? sanitize_text_field($_POST['bike_theme_phone']) : '';
            update_option('bike_theme_phone', $phone);

            // Social media links
            $facebook = isset($_POST['bike_theme_facebook']) ? esc_url_raw($_POST['bike_theme_facebook']) : '';
            update_option('bike_theme_facebook', $facebook);

            $twitter = isset($_POST['bike_theme_twitter']) ? esc_url_raw($_POST['bike_theme_twitter']) : '';
            update_option('bike_theme_twitter', $twitter);

            $instagram = isset($_POST['bike_theme_instagram']) ? esc_url_raw($_POST['bike_theme_instagram']) : '';
            update_option('bike_theme_instagram', $instagram);

            $linkedin = isset($_POST['bike_theme_linkedin']) ? esc_url_raw($_POST['bike_theme_linkedin']) : '';
            update_option('bike_theme_linkedin', $linkedin);

            $youtube = isset($_POST['bike_theme_youtube']) ? esc_url_raw($_POST['bike_theme_youtube']) : '';
            update_option('bike_theme_youtube', $youtube);

            // Footer info
            $copyright = isset($_POST['bike_theme_copyright']) ? sanitize_text_field($_POST['bike_theme_copyright']) : '';
            update_option('bike_theme_copyright', $copyright);

            // About Slides
            $about_slides = array();
            if (isset($_POST['bike_theme_about_slide']) && is_array($_POST['bike_theme_about_slide'])) {
                foreach ($_POST['bike_theme_about_slide'] as $index => $slide_data) {
                    if (!isset($slide_data['delete']) || $slide_data['delete'] != 'yes') {
                        $about_slides[] = array(
                            'image_id' => isset($slide_data['image_id']) ? absint($slide_data['image_id']) : 0,
                            'image_url' => isset($slide_data['image_url']) ? esc_url_raw($slide_data['image_url']) : '',
                            'alt' => isset($slide_data['alt']) ? sanitize_text_field($slide_data['alt']) : '',
                            'active' => isset($slide_data['active']) ? 1 : 0
                        );
                    }
                }
            }
            update_option('bike_theme_about_slides', $about_slides);

            // Carousel Slides
            // Get existing slides to compare
            $existing_slides = get_option('bike_theme_slides', array());

            // Process the submitted slides
            $slides = array();
            if (isset($_POST['bike_theme_slide']) && is_array($_POST['bike_theme_slide'])) {
                foreach ($_POST['bike_theme_slide'] as $index => $slide_data) {
                    if (!isset($slide_data['delete']) || $slide_data['delete'] != 'yes') {
                        $slides[] = array(
                            'image_id' => isset($slide_data['image_id']) ? absint($slide_data['image_id']) : 0,
                            'image_url' => isset($slide_data['image_url']) ? esc_url_raw($slide_data['image_url']) : '',
                            'subtitle' => isset($slide_data['subtitle']) ? sanitize_text_field($slide_data['subtitle']) : '',
                            'title' => isset($slide_data['title']) ? sanitize_text_field($slide_data['title']) : '',
                            'btn1_text' => isset($slide_data['btn1_text']) ? sanitize_text_field($slide_data['btn1_text']) : '',
                            'btn1_url' => isset($slide_data['btn1_url']) ? esc_url_raw($slide_data['btn1_url']) : '',
                            'btn2_text' => isset($slide_data['btn2_text']) ? sanitize_text_field($slide_data['btn2_text']) : '',
                            'btn2_url' => isset($slide_data['btn2_url']) ? esc_url_raw($slide_data['btn2_url']) : '',
                            'active' => isset($slide_data['active']) ? 1 : 0
                        );
                    }
                }
            }

            // Save the slides
            update_option('bike_theme_slides', $slides);

            echo '<div class="notice notice-success is-dismissible"><p>' . __('Settings saved successfully!', 'bike-theme') . '</p></div>';
        }
    }

    // Get current values
    $email = get_option('bike_theme_email', 'info@example.com');
    $phone = get_option('bike_theme_phone', '+012 345 6789');
    $facebook = get_option('bike_theme_facebook', '');
    $twitter = get_option('bike_theme_twitter', '');
    $instagram = get_option('bike_theme_instagram', '');
    $linkedin = get_option('bike_theme_linkedin', '');
    $youtube = get_option('bike_theme_youtube', '');
    $copyright = get_option('bike_theme_copyright', '© ' . date('Y') . ' Bike Theme. All Rights Reserved.');

    // Get all about slides
    $about_slides = get_option('bike_theme_about_slides', array());

    // If no about slides exist, create default ones
    if (empty($about_slides)) {
        $about_slides = array(
            array(
                'image_id' => 0,
                'image_url' => '',
                'alt' => __('About Us Image 1', 'bike-theme'),
                'active' => 1
            ),
            array(
                'image_id' => 0,
                'image_url' => '',
                'alt' => __('About Us Image 2', 'bike-theme'),
                'active' => 1
            )
        );
    }

    // Get all carousel slides
    $slides = get_option('bike_theme_slides', array());

    // If no slides exist, create a default one
    if (empty($slides)) {
        $slides[] = array(
            'image_id' => 0,
            'image_url' => '',
            'subtitle' => __('Tour Xe Đạp Việt Nam', 'bike-theme'),
            'title' => __('Khám Phá Việt Nam Trên Hai Bánh', 'bike-theme'),
            'btn1_text' => __('Xem Tour', 'bike-theme'),
            'btn1_url' => '',
            'btn2_text' => __('Đặt Xe Ngay', 'bike-theme'),
            'btn2_url' => '',
            'active' => 1
        );
    }

    // Display the form
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form method="post" action="">
            <?php wp_nonce_field('bike_theme_options_nonce'); ?>
            
            <h2><?php esc_html_e('Header Contact Information', 'bike-theme'); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="bike_theme_email"><?php esc_html_e('Email Address', 'bike-theme'); ?></label></th>
                    <td><input name="bike_theme_email" type="email" id="bike_theme_email" value="<?php echo esc_attr($email); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="bike_theme_phone"><?php esc_html_e('Phone Number', 'bike-theme'); ?></label></th>
                    <td><input name="bike_theme_phone" type="text" id="bike_theme_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text"></td>
                </tr>
            </table>
            
            <h2><?php esc_html_e('Social Media Links', 'bike-theme'); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="bike_theme_facebook"><?php esc_html_e('Facebook', 'bike-theme'); ?></label></th>
                    <td><input name="bike_theme_facebook" type="url" id="bike_theme_facebook" value="<?php echo esc_url($facebook); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="bike_theme_twitter"><?php esc_html_e('Twitter', 'bike-theme'); ?></label></th>
                    <td><input name="bike_theme_twitter" type="url" id="bike_theme_twitter" value="<?php echo esc_url($twitter); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="bike_theme_instagram"><?php esc_html_e('Instagram', 'bike-theme'); ?></label></th>
                    <td><input name="bike_theme_instagram" type="url" id="bike_theme_instagram" value="<?php echo esc_url($instagram); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="bike_theme_linkedin"><?php esc_html_e('LinkedIn', 'bike-theme'); ?></label></th>
                    <td><input name="bike_theme_linkedin" type="url" id="bike_theme_linkedin" value="<?php echo esc_url($linkedin); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="bike_theme_youtube"><?php esc_html_e('YouTube', 'bike-theme'); ?></label></th>
                    <td><input name="bike_theme_youtube" type="url" id="bike_theme_youtube" value="<?php echo esc_url($youtube); ?>" class="regular-text"></td>
                </tr>
            </table>
            
            <h2><?php esc_html_e('Footer Information', 'bike-theme'); ?></h2>
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="bike_theme_copyright"><?php esc_html_e('Copyright Text', 'bike-theme'); ?></label></th>
                    <td><input name="bike_theme_copyright" type="text" id="bike_theme_copyright" value="<?php echo esc_attr($copyright); ?>" class="regular-text"></td>
                </tr>
            </table>
            
            <h2><?php esc_html_e('About Section Slides', 'bike-theme'); ?></h2>
            <div id="bike-about-slides-container">
                <?php foreach ($about_slides as $index => $slide) : ?>
                <div class="bike-slide-item" data-index="<?php echo $index; ?>">
                    <h3><?php esc_html_e('About Slide', 'bike-theme'); ?> <span class="slide-number"><?php echo $index + 1; ?></span> <span class="slide-controls"><a href="#" class="slide-toggle"><?php esc_html_e('Toggle', 'bike-theme'); ?></a> | <a href="#" class="slide-remove"><?php esc_html_e('Remove', 'bike-theme'); ?></a></span></h3>
                    <div class="slide-content">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><label for="bike_theme_about_slide_<?php echo $index; ?>_active"><?php esc_html_e('Active', 'bike-theme'); ?></label></th>
                                <td>
                                    <input name="bike_theme_about_slide[<?php echo $index; ?>][active]" type="checkbox" id="bike_theme_about_slide_<?php echo $index; ?>_active" <?php checked($slide['active'], 1); ?>>
                                    <input type="hidden" name="bike_theme_about_slide[<?php echo $index; ?>][delete]" class="slide-delete-field" value="no">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="bike_theme_about_slide_<?php echo $index; ?>_image"><?php esc_html_e('Image', 'bike-theme'); ?></label></th>
                                <td>
                                    <div class="bike-media-upload">
                                        <input type="hidden" name="bike_theme_about_slide[<?php echo $index; ?>][image_id]" class="bike-media-id" value="<?php echo esc_attr($slide['image_id']); ?>">
                                        <input type="hidden" name="bike_theme_about_slide[<?php echo $index; ?>][image_url]" class="bike-media-url" value="<?php echo esc_url($slide['image_url']); ?>">
                                        <div class="bike-media-preview">
                                            <?php if (!empty($slide['image_url'])) : ?>
                                                <img src="<?php echo esc_url($slide['image_url']); ?>" alt="">
                                            <?php endif; ?>
                                        </div>
                                        <input type="button" class="button bike-media-upload-btn" value="<?php esc_attr_e('Upload Image', 'bike-theme'); ?>">
                                        <input type="button" class="button bike-media-remove-btn <?php echo empty($slide['image_url']) ? 'hidden' : ''; ?>" value="<?php esc_attr_e('Remove Image', 'bike-theme'); ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="bike_theme_about_slide_<?php echo $index; ?>_alt"><?php esc_html_e('Image Alt Text', 'bike-theme'); ?></label></th>
                                <td><input name="bike_theme_about_slide[<?php echo $index; ?>][alt]" type="text" id="bike_theme_about_slide_<?php echo $index; ?>_alt" value="<?php echo esc_attr($slide['alt']); ?>" class="regular-text"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <p>
                <button type="button" id="add-about-slide-button" class="button button-secondary"><?php esc_html_e('Add New About Slide', 'bike-theme'); ?></button>
            </p>
            
            <!-- Template for new about slides -->
            <script type="text/template" id="about-slide-template">
                <div class="bike-slide-item" data-index="{{index}}">
                    <h3><?php esc_html_e('About Slide', 'bike-theme'); ?> <span class="slide-number">{{number}}</span> <span class="slide-controls"><a href="#" class="slide-toggle"><?php esc_html_e('Toggle', 'bike-theme'); ?></a> | <a href="#" class="slide-remove"><?php esc_html_e('Remove', 'bike-theme'); ?></a></span></h3>
                    <div class="slide-content">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><label for="bike_theme_about_slide_{{index}}_active"><?php esc_html_e('Active', 'bike-theme'); ?></label></th>
                                <td>
                                    <input name="bike_theme_about_slide[{{index}}][active]" type="checkbox" id="bike_theme_about_slide_{{index}}_active" checked>
                                    <input type="hidden" name="bike_theme_about_slide[{{index}}][delete]" class="slide-delete-field" value="no">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="bike_theme_about_slide_{{index}}_image"><?php esc_html_e('Image', 'bike-theme'); ?></label></th>
                                <td>
                                    <div class="bike-media-upload">
                                        <input type="hidden" name="bike_theme_about_slide[{{index}}][image_id]" class="bike-media-id" value="">
                                        <input type="hidden" name="bike_theme_about_slide[{{index}}][image_url]" class="bike-media-url" value="">
                                        <div class="bike-media-preview"></div>
                                        <input type="button" class="button bike-media-upload-btn" value="<?php esc_attr_e('Upload Image', 'bike-theme'); ?>">
                                        <input type="button" class="button bike-media-remove-btn hidden" value="<?php esc_attr_e('Remove Image', 'bike-theme'); ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="bike_theme_about_slide_{{index}}_alt"><?php esc_html_e('Image Alt Text', 'bike-theme'); ?></label></th>
                                <td><input name="bike_theme_about_slide[{{index}}][alt]" type="text" id="bike_theme_about_slide_{{index}}_alt" value="" class="regular-text"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </script>
            
            <h2><?php esc_html_e('Hero Banner Slides', 'bike-theme'); ?></h2>
            <div id="bike-slides-container">
                <?php foreach ($slides as $index => $slide) : ?>
                <div class="bike-slide-item" data-index="<?php echo $index; ?>">
                    <h3><?php esc_html_e('Slide', 'bike-theme'); ?> <span class="slide-number"><?php echo $index + 1; ?></span> <span class="slide-controls"><a href="#" class="slide-toggle"><?php esc_html_e('Toggle', 'bike-theme'); ?></a> | <a href="#" class="slide-remove"><?php esc_html_e('Remove', 'bike-theme'); ?></a></span></h3>
                    <div class="slide-content">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><label for="bike_theme_slide_<?php echo $index; ?>_active"><?php esc_html_e('Active', 'bike-theme'); ?></label></th>
                                <td>
                                    <input name="bike_theme_slide[<?php echo $index; ?>][active]" type="checkbox" id="bike_theme_slide_<?php echo $index; ?>_active" <?php checked($slide['active'], 1); ?>>
                                    <input type="hidden" name="bike_theme_slide[<?php echo $index; ?>][delete]" class="slide-delete-field" value="no">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="bike_theme_slide_<?php echo $index; ?>_image"><?php esc_html_e('Image', 'bike-theme'); ?></label></th>
                                <td>
                                    <div class="bike-media-upload">
                                        <input type="hidden" name="bike_theme_slide[<?php echo $index; ?>][image_id]" class="bike-media-id" value="<?php echo esc_attr($slide['image_id']); ?>">
                                        <input type="hidden" name="bike_theme_slide[<?php echo $index; ?>][image_url]" class="bike-media-url" value="<?php echo esc_url($slide['image_url']); ?>">
                                        <div class="bike-media-preview">
                                            <?php if (!empty($slide['image_url'])) : ?>
                                                <img src="<?php echo esc_url($slide['image_url']); ?>" alt="">
                                            <?php endif; ?>
                                        </div>
                                        <input type="button" class="button bike-media-upload-btn" value="<?php esc_attr_e('Upload Image', 'bike-theme'); ?>">
                                        <input type="button" class="button bike-media-remove-btn <?php echo empty($slide['image_url']) ? 'hidden' : ''; ?>" value="<?php esc_attr_e('Remove Image', 'bike-theme'); ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="bike_theme_slide_<?php echo $index; ?>_subtitle"><?php esc_html_e('Subtitle', 'bike-theme'); ?></label></th>
                                <td><input name="bike_theme_slide[<?php echo $index; ?>][subtitle]" type="text" id="bike_theme_slide_<?php echo $index; ?>_subtitle" value="<?php echo esc_attr($slide['subtitle']); ?>" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="bike_theme_slide_<?php echo $index; ?>_title"><?php esc_html_e('Title', 'bike-theme'); ?></label></th>
                                <td><input name="bike_theme_slide[<?php echo $index; ?>][title]" type="text" id="bike_theme_slide_<?php echo $index; ?>_title" value="<?php echo esc_attr($slide['title']); ?>" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="bike_theme_slide_<?php echo $index; ?>_btn1_text"><?php esc_html_e('Button 1 Text', 'bike-theme'); ?></label></th>
                                <td><input name="bike_theme_slide[<?php echo $index; ?>][btn1_text]" type="text" id="bike_theme_slide_<?php echo $index; ?>_btn1_text" value="<?php echo esc_attr($slide['btn1_text']); ?>" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="bike_theme_slide_<?php echo $index; ?>_btn1_url"><?php esc_html_e('Button 1 URL', 'bike-theme'); ?></label></th>
                                <td><input name="bike_theme_slide[<?php echo $index; ?>][btn1_url]" type="url" id="bike_theme_slide_<?php echo $index; ?>_btn1_url" value="<?php echo esc_url($slide['btn1_url']); ?>" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="bike_theme_slide_<?php echo $index; ?>_btn2_text"><?php esc_html_e('Button 2 Text', 'bike-theme'); ?></label></th>
                                <td><input name="bike_theme_slide[<?php echo $index; ?>][btn2_text]" type="text" id="bike_theme_slide_<?php echo $index; ?>_btn2_text" value="<?php echo esc_attr($slide['btn2_text']); ?>" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="bike_theme_slide_<?php echo $index; ?>_btn2_url"><?php esc_html_e('Button 2 URL', 'bike-theme'); ?></label></th>
                                <td><input name="bike_theme_slide[<?php echo $index; ?>][btn2_url]" type="url" id="bike_theme_slide_<?php echo $index; ?>_btn2_url" value="<?php echo esc_url($slide['btn2_url']); ?>" class="regular-text"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <p>
                <button type="button" id="add-slide-button" class="button button-secondary"><?php esc_html_e('Add New Slide', 'bike-theme'); ?></button>
            </p>
            
            <!-- Template for new slides -->
            <script type="text/template" id="slide-template">
                <div class="bike-slide-item" data-index="{{index}}">
                    <h3><?php esc_html_e('Slide', 'bike-theme'); ?> <span class="slide-number">{{number}}</span> <span class="slide-controls"><a href="#" class="slide-toggle"><?php esc_html_e('Toggle', 'bike-theme'); ?></a> | <a href="#" class="slide-remove"><?php esc_html_e('Remove', 'bike-theme'); ?></a></span></h3>
                    <div class="slide-content">
                        <table class="form-table">
                            <tr>
                                <th scope="row"><label for="bike_theme_slide_{{index}}_active"><?php esc_html_e('Active', 'bike-theme'); ?></label></th>
                                <td>
                                    <input name="bike_theme_slide[{{index}}][active]" type="checkbox" id="bike_theme_slide_{{index}}_active" checked>
                                    <input type="hidden" name="bike_theme_slide[{{index}}][delete]" class="slide-delete-field" value="no">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="bike_theme_slide_{{index}}_image"><?php esc_html_e('Image', 'bike-theme'); ?></label></th>
                                <td>
                                    <div class="bike-media-upload">
                                        <input type="hidden" name="bike_theme_slide[{{index}}][image_id]" class="bike-media-id" value="">
                                        <input type="hidden" name="bike_theme_slide[{{index}}][image_url]" class="bike-media-url" value="">
                                        <div class="bike-media-preview"></div>
                                        <input type="button" class="button bike-media-upload-btn" value="<?php esc_attr_e('Upload Image', 'bike-theme'); ?>">
                                        <input type="button" class="button bike-media-remove-btn hidden" value="<?php esc_attr_e('Remove Image', 'bike-theme'); ?>">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="bike_theme_slide_{{index}}_subtitle"><?php esc_html_e('Subtitle', 'bike-theme'); ?></label></th>
                                <td><input name="bike_theme_slide[{{index}}][subtitle]" type="text" id="bike_theme_slide_{{index}}_subtitle" value="" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="bike_theme_slide_{{index}}_title"><?php esc_html_e('Title', 'bike-theme'); ?></label></th>
                                <td><input name="bike_theme_slide[{{index}}][title]" type="text" id="bike_theme_slide_{{index}}_title" value="" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="bike_theme_slide_{{index}}_btn1_text"><?php esc_html_e('Button 1 Text', 'bike-theme'); ?></label></th>
                                <td><input name="bike_theme_slide[{{index}}][btn1_text]" type="text" id="bike_theme_slide_{{index}}_btn1_text" value="<?php echo esc_attr__('Xem Tour', 'bike-theme'); ?>" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="bike_theme_slide_{{index}}_btn1_url"><?php esc_html_e('Button 1 URL', 'bike-theme'); ?></label></th>
                                <td><input name="bike_theme_slide[{{index}}][btn1_url]" type="url" id="bike_theme_slide_{{index}}_btn1_url" value="" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="bike_theme_slide_{{index}}_btn2_text"><?php esc_html_e('Button 2 Text', 'bike-theme'); ?></label></th>
                                <td><input name="bike_theme_slide[{{index}}][btn2_text]" type="text" id="bike_theme_slide_{{index}}_btn2_text" value="<?php echo esc_attr__('Đặt Xe Ngay', 'bike-theme'); ?>" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="bike_theme_slide_{{index}}_btn2_url"><?php esc_html_e('Button 2 URL', 'bike-theme'); ?></label></th>
                                <td><input name="bike_theme_slide[{{index}}][btn2_url]" type="url" id="bike_theme_slide_{{index}}_btn2_url" value="" class="regular-text"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </script>
            
            <?php submit_button(__('Save Settings', 'bike-theme'), 'primary', 'bike_theme_options_submit'); ?>
        </form>
    </div>
    <?php
}

/**
 * Get theme option helper function
 */
function bike_theme_get_option($option, $default = '')
{
    return get_option('bike_theme_' . $option, $default);
}

/**
 * Add custom CSS class to body
 */
function bike_theme_body_classes($classes)
{
    // Add a class for the bike single page
    if (is_singular('bike')) {
        $classes[] = 'single-bike-page';
    }

    // Add a class for the bike archive page
    if (is_post_type_archive('bike') || is_tax('bike_category') || is_tax('bike_brand')) {
        $classes[] = 'bike-archive-page';
    }

    return $classes;
}
add_filter('body_class', 'bike_theme_body_classes');

/**
 * Include the required files for Bootstrap Navigation
 */
require get_template_directory() . '/includes/class-wp-bootstrap-navwalker.php';

/**
 * Register additional widget areas
 */
function bike_theme_additional_widgets_init()
{
    register_sidebar(
        array(
            'name'          => esc_html__('Footer 4', 'bike-theme'),
            'id'            => 'footer-4',
            'description'   => esc_html__('Add footer newsletter widgets here.', 'bike-theme'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__('Home Page Widgets', 'bike-theme'),
            'id'            => 'home-widgets',
            'description'   => esc_html__('Add widgets for home page.', 'bike-theme'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        )
    );
}
add_action('widgets_init', 'bike_theme_additional_widgets_init');

/**
 * Register Testimonial metabox
 */
function bike_theme_testimonial_meta_boxes()
{
    add_meta_box(
        'testimonial_details',
        __('Testimonial Details', 'bike-theme'),
        'bike_theme_testimonial_details_callback',
        'testimonial',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'bike_theme_testimonial_meta_boxes');

/**
 * Testimonial metabox callback
 */
function bike_theme_testimonial_details_callback($post)
{
    // Add a nonce field
    wp_nonce_field('testimonial_details_nonce_action', 'testimonial_details_nonce');

    // Get current values
    $position = get_post_meta($post->ID, 'testimonial_position', true);

    // Output fields
    ?>
    <p>
        <label for="testimonial_position"><?php esc_html_e('Position/Title', 'bike-theme'); ?></label><br>
        <input type="text" id="testimonial_position" name="testimonial_position" value="<?php echo esc_attr($position); ?>" class="widefat">
        <span class="description"><?php esc_html_e('e.g. "CEO at Company" or "Happy Customer"', 'bike-theme'); ?></span>
    </p>
    <?php
}

/**
 * Save testimonial meta data
 */
function bike_theme_save_testimonial_meta($post_id)
{
    // Check if nonce is set
    if (!isset($_POST['testimonial_details_nonce'])) {
        return;
    }

    // Verify that the nonce is valid
    if (!wp_verify_nonce($_POST['testimonial_details_nonce'], 'testimonial_details_nonce_action')) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions
    if (isset($_POST['post_type']) && 'testimonial' == $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Save the position field
    if (isset($_POST['testimonial_position'])) {
        update_post_meta($post_id, 'testimonial_position', sanitize_text_field($_POST['testimonial_position']));
    }
}
add_action('save_post', 'bike_theme_save_testimonial_meta');

/**
 * Add Featured field to Bike CPT
 */
function bike_theme_bike_featured_meta_box()
{
    add_meta_box(
        'bike_featured',
        __('Featured Bike', 'bike-theme'),
        'bike_theme_bike_featured_callback',
        'bike',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'bike_theme_bike_featured_meta_box');

/**
 * Featured bike metabox callback
 */
function bike_theme_bike_featured_callback($post)
{
    // Add a nonce field
    wp_nonce_field('bike_featured_nonce_action', 'bike_featured_nonce');

    // Get current value
    $featured = get_post_meta($post->ID, '_featured', true);

    // Output field
    ?>
    <p>
        <label for="bike_featured">
            <input type="checkbox" id="bike_featured" name="bike_featured" value="yes" <?php checked($featured, 'yes'); ?>>
            <?php esc_html_e('Feature this bike on the homepage', 'bike-theme'); ?>
        </label>
    </p>
    <?php
}

/**
 * Save featured bike meta
 */
function bike_theme_save_bike_featured($post_id)
{
    // Check if nonce is set
    if (!isset($_POST['bike_featured_nonce'])) {
        return;
    }

    // Verify that the nonce is valid
    if (!wp_verify_nonce($_POST['bike_featured_nonce'], 'bike_featured_nonce_action')) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions
    if (isset($_POST['post_type']) && 'bike' == $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    // Save the featured field
    $featured = isset($_POST['bike_featured']) ? 'yes' : 'no';
    update_post_meta($post_id, '_featured', $featured);
}
add_action('save_post', 'bike_theme_save_bike_featured');

/**
 * Add additional theme options
 */
function bike_theme_additional_options($wp_customize)
{
    // Add Home Page Options Section
    $wp_customize->add_section('bike_theme_home_options', array(
        'title'    => __('Home Page Options', 'bike-theme'),
        'priority' => 130,
    ));

    // Carousel Settings
    $wp_customize->add_setting('bike_theme_carousel_show', array(
        'default'           => true,
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('bike_theme_carousel_show', array(
        'label'    => __('Show Carousel', 'bike-theme'),
        'section'  => 'bike_theme_home_options',
        'type'     => 'checkbox',
    ));

    // About Section Settings
    $wp_customize->add_setting('bike_theme_about_show', array(
        'default'           => true,
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('bike_theme_about_show', array(
        'label'    => __('Show About Section', 'bike-theme'),
        'section'  => 'bike_theme_home_options',
        'type'     => 'checkbox',
    ));

    // Featured Bikes Section Settings
    $wp_customize->add_setting('bike_theme_featured_bikes_show', array(
        'default'           => true,
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('bike_theme_featured_bikes_show', array(
        'label'    => __('Show Featured Bikes Section', 'bike-theme'),
        'section'  => 'bike_theme_home_options',
        'type'     => 'checkbox',
    ));

    // Contact Page
    $wp_customize->add_setting('bike_theme_contact_page', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('bike_theme_contact_page', array(
        'label'    => __('Contact Page', 'bike-theme'),
        'section'  => 'bike_theme_home_options',
        'type'     => 'dropdown-pages',
    ));

    // Address Setting
    $wp_customize->add_setting('bike_theme_address', array(
        'default'           => '123 Street, New York, USA',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('bike_theme_address', array(
        'label'    => __('Address', 'bike-theme'),
        'section'  => 'title_tagline',
        'type'     => 'text',
    ));

    // Opening Hours Setting
    $wp_customize->add_setting('bike_theme_hours', array(
        'default'           => 'Mon - Fri : 09.00 AM - 06.00 PM',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('bike_theme_hours', array(
        'label'    => __('Opening Hours', 'bike-theme'),
        'section'  => 'title_tagline',
        'type'     => 'text',
    ));

    // Currency Settings
    $wp_customize->add_setting('bike_theme_currency', array(
        'default'           => '$',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('bike_theme_currency', array(
        'label'    => __('Currency', 'bike-theme'),
        'section'  => 'title_tagline',
        'type'     => 'select',
        'choices'  => array(
            'VNĐ' => 'VNĐ',
            '$'   => '$',
        ),
    ));

    $wp_customize->add_setting('bike_theme_currency_position', array(
        'default'           => 'after',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('bike_theme_currency_position', array(
        'label'    => __('Currency Position', 'bike-theme'),
        'section'  => 'title_tagline',
        'type'     => 'radio',
        'choices'  => array(
            'before' => __('Before price ($100)', 'bike-theme'),
            'after'  => __('After price (100 VNĐ)', 'bike-theme'),
        ),
    ));
}
add_action('customize_register', 'bike_theme_additional_options');

/**
 * Add loading="lazy" attribute to all images in content
 */
function bike_theme_add_lazy_loading_attribute($content)
{
    // If not content or not on frontend, return
    if (!$content || is_admin()) {
        return $content;
    }

    // Add loading="lazy" to all img tags
    $content = preg_replace('/<img(.*?)>/', '<img$1 loading="lazy">', $content);

    // Don't add it twice if already exists
    $content = str_replace('loading="lazy" loading="lazy"', 'loading="lazy"', $content);

    return $content;
}
add_filter('the_content', 'bike_theme_add_lazy_loading_attribute');
add_filter('post_thumbnail_html', 'bike_theme_add_lazy_loading_attribute');
add_filter('get_avatar', 'bike_theme_add_lazy_loading_attribute');

/**
 * Add lazy-image-container class to post thumbnails
 */
function bike_theme_lazy_loading_thumbnail_class($attr)
{
    if (!isset($attr['class'])) {
        $attr['class'] = 'lazy-image-container';
    } else {
        $attr['class'] .= ' lazy-image-container';
    }

    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'bike_theme_lazy_loading_thumbnail_class');

/**
 * Include custom post types
 */
require get_template_directory() . '/inc/post-types.php';

/**
 * Include meta boxes
 */
require get_template_directory() . '/inc/meta-boxes.php';

/**
 * Get tour price based on number of participants
 *
 * @param int $tour_id         Tour ID
 * @param int $participants    Number of participants (default: 1)
 * @return int                 Price per person
 */
function bike_theme_get_tour_price($tour_id, $participants = 1)
{
    // Get flexible pricing status
    $flexible_pricing_enabled = get_post_meta($tour_id, '_tour_flexible_pricing_enabled', true);

    // If flexible pricing is not enabled, return standard price
    if (empty($flexible_pricing_enabled) || $flexible_pricing_enabled !== '1') {
        return (int) get_post_meta($tour_id, '_tour_price', true);
    }

    // Get flexible pricing data
    $pricing_data = get_post_meta($tour_id, '_tour_flexible_pricing', true);

    // If no pricing data, return standard price
    if (empty($pricing_data) || !is_array($pricing_data)) {
        return (int) get_post_meta($tour_id, '_tour_price', true);
    }

    // Sort pricing data by number of participants (ascending)
    usort($pricing_data, function ($a, $b) {
        return $a['participants'] - $b['participants'];
    });

    // Find applicable price
    $applicable_price = null;

    foreach ($pricing_data as $price_item) {
        if ($participants >= $price_item['participants']) {
            $applicable_price = $price_item['price'];
        } else {
            break; // Stop once we exceed the participant level
        }
    }

    // If no applicable price found, use the first price level
    if ($applicable_price === null && !empty($pricing_data)) {
        $applicable_price = $pricing_data[0]['price'];
    }

    // Fallback to standard price if still no price found
    if ($applicable_price === null) {
        $applicable_price = (int) get_post_meta($tour_id, '_tour_price', true);
    }

    return $applicable_price;
}

/**
 * Format price with currency symbol based on theme settings
 *
 * @param int|float $price   The price to format
 * @param bool $include_html Whether to include HTML formatting
 * @return string            Formatted price with currency
 */
function bike_theme_format_price($price, $include_html = false)
{
    // Format number with thousand separator
    $formatted_price = number_format($price, 0, ',', '.');

    // Get currency symbol and position from theme options
    $currency = get_theme_mod('bike_theme_currency', '$');
    $position = get_theme_mod('bike_theme_currency_position', 'after');

    // Format the price with the currency symbol in the correct position
    if ($position === 'before') {
        if ($include_html) {
            return '<span class="currency-symbol">' . $currency . '</span>' . $formatted_price;
        } else {
            return $currency . $formatted_price;
        }
    } else {
        if ($include_html) {
            return $formatted_price . ' <span class="currency-symbol">' . $currency . '</span>';
        } else {
            return $formatted_price . ' ' . $currency;
        }
    }
}

/**
 * Calculate total tour price based on number of participants
 *
 * @param int $tour_id         Tour ID
 * @param int $participants    Number of participants
 * @return int                 Total price for all participants
 */
function bike_theme_get_tour_total_price($tour_id, $participants = 1)
{
    $price_per_person = bike_theme_get_tour_price($tour_id, $participants);
    return $price_per_person * $participants;
}

/**
 * Process booking form submission
 */
function bike_theme_submit_booking()
{
    if (!isset($_POST['booking_nonce']) || !wp_verify_nonce($_POST['booking_nonce'], 'bike_theme_booking_nonce')) {
        wp_die(__('Security check failed. Please try again.', 'bike-theme'));
    }

    // Get and sanitize form data
    $customer_name = sanitize_text_field($_POST['name']);
    $customer_email = sanitize_email($_POST['email']);
    $customer_phone = sanitize_text_field($_POST['phone']);
    $booking_date = sanitize_text_field($_POST['date']);
    $number_of_participants = intval($_POST['participants']);
    $tour_id = isset($_POST['tour']) ? intval($_POST['tour']) : 0;
    $bike_id = isset($_POST['bike']) ? intval($_POST['bike']) : 0;
    $message = sanitize_textarea_field($_POST['message']);
    $payment_method = sanitize_text_field($_POST['payment_method']);

    // Validate required fields
    $errors = array();
    if (empty($customer_name)) $errors[] = __('Name is required', 'bike-theme');
    if (empty($customer_email)) $errors[] = __('Email is required', 'bike-theme');
    if (empty($customer_phone)) $errors[] = __('Phone is required', 'bike-theme');
    if (empty($booking_date)) $errors[] = __('Date is required', 'bike-theme');
    if ($number_of_participants < 1) $errors[] = __('Number of participants must be at least 1', 'bike-theme');
    if (!$tour_id && !$bike_id) $errors[] = __('Please select either a tour or a bike', 'bike-theme');

    if (!empty($errors)) {
        $error_message = implode('<br>', $errors);
        wp_redirect(add_query_arg(array(
            'booking' => 'error',
            'message' => urlencode($error_message)
        ), wp_get_referer()));
        exit;
    }

    // Calculate prices if tour is selected
    $price_per_person = 0;
    $total_price = 0;
    if ($tour_id > 0) {
        $price_per_person = bike_theme_get_tour_price($tour_id, $number_of_participants);
        $total_price = $price_per_person * $number_of_participants;
    }

    // Create descriptive booking title
    $booking_title = '';
    if ($tour_id > 0) {
        $booking_title = sprintf(
            __('%s - %s (%d participants) - %s', 'bike-theme'),
            get_the_title($tour_id),
            $customer_name,
            $number_of_participants,
            $booking_date
        );
    } 
    // else if ($bike_id > 0) {
    //     $booking_title = sprintf(
    //         __('%s - %s - %s', 'bike-theme'),
    //         get_the_title($bike_id),
    //         $customer_name,
    //         $booking_date
    //     );
    // }

    // Create booking post
    $booking_data = array(
        'post_title' => $booking_title,
        'post_status' => 'publish',
        'post_type' => 'bike_booking',
    );

    $booking_id = wp_insert_post($booking_data);

    if ($booking_id) {
        // Save booking meta with proper field names
        update_post_meta($booking_id, '_booking_customer_name', $customer_name);
        update_post_meta($booking_id, '_booking_customer_email', $customer_email);
        update_post_meta($booking_id, '_booking_customer_phone', $customer_phone);
        update_post_meta($booking_id, '_booking_date', $booking_date);
        update_post_meta($booking_id, '_booking_participants', $number_of_participants);
        update_post_meta($booking_id, '_booking_message', $message);
        update_post_meta($booking_id, '_booking_payment_method', $payment_method);
        update_post_meta($booking_id, '_booking_price_per_person', $price_per_person);
        update_post_meta($booking_id, '_booking_total_price', $total_price);

        if ($tour_id > 0) {
            update_post_meta($booking_id, '_booking_tour_id', $tour_id);
            update_post_meta($booking_id, '_booking_type', 'tour');
        }
        if ($bike_id > 0) {
            update_post_meta($booking_id, '_booking_bike_id', $bike_id);
            update_post_meta($booking_id, '_booking_type', 'bike');
        }

        // Set initial booking status
        wp_set_object_terms($booking_id, 'pending', 'booking_status');
        update_post_meta($booking_id, '_booking_status', 'pending');

        // Send email notification to admin
        $admin_email = get_option('admin_email');
        $site_name = get_bloginfo('name');
        $subject = sprintf(__('[%s] New Booking #%d Received', 'bike-theme'), $site_name, $booking_id);

        $message = __("A new booking has been received:\n\n", 'bike-theme');
        $message .= sprintf(__("Booking ID: #%d\n", 'bike-theme'), $booking_id);
        $message .= sprintf(__("Booking Type: %s\n", 'bike-theme'), ($tour_id > 0 ? 'Tour' : 'Bike'));
        $message .= sprintf(__("Status: %s\n\n", 'bike-theme'), __('Pending', 'bike-theme'));
        
        $message .= __("Customer Details:\n", 'bike-theme');
        $message .= sprintf(__("Name: %s\n", 'bike-theme'), $customer_name);
        $message .= sprintf(__("Email: %s\n", 'bike-theme'), $customer_email);
        $message .= sprintf(__("Phone: %s\n\n", 'bike-theme'), $customer_phone);
        
        $message .= __("Booking Details:\n", 'bike-theme');
        $message .= sprintf(__("Date: %s\n", 'bike-theme'), $booking_date);
        
        if ($tour_id > 0) {
            $message .= sprintf(__("Tour: %s\n", 'bike-theme'), get_the_title($tour_id));
            $message .= sprintf(__("Participants: %d\n", 'bike-theme'), $number_of_participants);
            $message .= sprintf(__("Price per Person: %s\n", 'bike-theme'), bike_theme_format_price($price_per_person));
            $message .= sprintf(__("Total Price: %s\n", 'bike-theme'), bike_theme_format_price($total_price));
        }
        
        if ($bike_id > 0) {
            $message .= sprintf(__("Bike: %s\n", 'bike-theme'), get_the_title($bike_id));
        }
        
        $message .= sprintf(__("Payment Method: %s\n", 'bike-theme'), $payment_method);
        
        if (!empty($message)) {
            $message .= sprintf(__("\nSpecial Requests:\n%s\n", 'bike-theme'), $message);
        }
        
        $message .= sprintf(__("\nManage this booking: %s", 'bike-theme'), admin_url('post.php?post=' . $booking_id . '&action=edit'));

        wp_mail($admin_email, $subject, $message);

        // Send confirmation email to customer
        $customer_subject = sprintf(__('Your Booking Confirmation #%d - %s', 'bike-theme'), $booking_id, $site_name);

        $customer_message = sprintf(__("Dear %s,\n\n", 'bike-theme'), $customer_name);
        $customer_message .= sprintf(__("Thank you for your booking (ID: #%d). Below are your booking details:\n\n", 'bike-theme'), $booking_id);
        
        if ($tour_id > 0) {
            $customer_message .= sprintf(__("Tour: %s\n", 'bike-theme'), get_the_title($tour_id));
            $customer_message .= sprintf(__("Date: %s\n", 'bike-theme'), $booking_date);
            $customer_message .= sprintf(__("Number of Participants: %d\n", 'bike-theme'), $number_of_participants);
            $customer_message .= sprintf(__("Price per Person: %s\n", 'bike-theme'), bike_theme_format_price($price_per_person));
            $customer_message .= sprintf(__("Total Price: %s\n", 'bike-theme'), bike_theme_format_price($total_price));
        }
        
        if ($bike_id > 0) {
            $customer_message .= sprintf(__("Bike: %s\n", 'bike-theme'), get_the_title($bike_id));
            $customer_message .= sprintf(__("Date: %s\n", 'bike-theme'), $booking_date);
        }
        
        $customer_message .= sprintf(__("Payment Method: %s\n", 'bike-theme'), $payment_method);
        
        if (!empty($message)) {
            $customer_message .= sprintf(__("\nYour Special Requests:\n%s\n", 'bike-theme'), $message);
        }
        
        $customer_message .= __("\nBooking Status: Pending\n", 'bike-theme');
        $customer_message .= __("We will review your booking and contact you shortly for confirmation.\n\n", 'bike-theme');
        $customer_message .= sprintf(__("Thank you for choosing %s!\n\n", 'bike-theme'), $site_name);
        $customer_message .= sprintf(__("Best regards,\n%s", 'bike-theme'), $site_name);

        wp_mail($customer_email, $customer_subject, $customer_message);

        // Redirect to thank you page with success message
        wp_redirect(add_query_arg(array(
            'booking' => 'success',
            'id' => $booking_id
        ), get_permalink(get_page_by_path('thank-you'))));
        exit;
    } else {
        // Redirect back with error message
        wp_redirect(add_query_arg(array(
            'booking' => 'error',
            'message' => urlencode(__('Failed to create booking. Please try again.', 'bike-theme'))
        ), wp_get_referer()));
        exit;
    }
}
add_action('admin_post_bike_theme_submit_booking', 'bike_theme_submit_booking');
add_action('admin_post_nopriv_bike_theme_submit_booking', 'bike_theme_submit_booking');

/**
 * Register booking page option
 */
function bike_theme_register_booking_page_option()
{
    register_setting('general', 'bike_theme_booking_page', 'intval');

    add_settings_field(
        'bike_theme_booking_page',
        __('Booking Page', 'bike-theme'),
        'bike_theme_booking_page_callback',
        'general',
        'default',
        array('label_for' => 'bike_theme_booking_page')
    );
}
add_action('admin_init', 'bike_theme_register_booking_page_option');

/**
 * Booking page option callback
 */
function bike_theme_booking_page_callback()
{
    $booking_page = get_option('bike_theme_booking_page');
    wp_dropdown_pages(array(
        'name' => 'bike_theme_booking_page',
        'show_option_none' => __('— Select —', 'bike-theme'),
        'option_none_value' => '0',
        'selected' => $booking_page,
    ));
    echo '<p class="description">' . __('Select the page that contains the booking form.', 'bike-theme') . '</p>';
}

/**
 * Setup initial booking statuses
 */
function bike_theme_setup_booking_statuses()
{
    // Only run once
    if (get_option('bike_theme_booking_statuses_created')) {
        return;
    }

    $statuses = array(
        'pending' => __('Pending', 'bike-theme'),
        'confirmed' => __('Confirmed', 'bike-theme'),
        'completed' => __('Completed', 'bike-theme'),
        'cancelled' => __('Cancelled', 'bike-theme'),
    );

    foreach ($statuses as $slug => $name) {
        if (!term_exists($slug, 'booking_status')) {
            wp_insert_term($name, 'booking_status', array('slug' => $slug));
        }
    }

    update_option('bike_theme_booking_statuses_created', true);
}
add_action('init', 'bike_theme_setup_booking_statuses');

/**
 * Enqueue admin scripts for tour management
 */
function bike_theme_tour_admin_scripts($hook)
{
    global $post;

    // Only enqueue on tour edit screen
    if ($hook == 'post-new.php' || $hook == 'post.php') {
        if (isset($post) && $post->post_type === 'bike_tour') {
            // Enqueue tour admin CSS
            wp_enqueue_style('bike-theme-tour-admin', get_template_directory_uri() . '/assets/css/tour-admin.css', array(), '1.0.0');

            // Enqueue media library scripts
            wp_enqueue_media();
        }
    }
}
add_action('admin_enqueue_scripts', 'bike_theme_tour_admin_scripts');

/**
 * Count tours by category within a destination
 * 
 * @param int $destination_id The destination term ID
 * @return array Array of category counts with category term objects as keys
 */
function bike_theme_count_tours_by_category_in_destination($destination_id) {
    $category_counts = array();
    
    // Get all categories
    $categories = get_terms(array(
        'taxonomy' => 'tour_category',
        'hide_empty' => false,
    ));
    
    if (!empty($categories) && !is_wp_error($categories)) {
        foreach ($categories as $category) {
            // Query posts that belong to both the destination and this category
            $args = array(
                'post_type' => 'bike_tour',
                'post_status' => 'publish',
                'posts_per_page' => -1, // Get all posts
                'tax_query' => array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => 'destination',
                        'field' => 'term_id',
                        'terms' => $destination_id,
                    ),
                    array(
                        'taxonomy' => 'tour_category',
                        'field' => 'term_id',
                        'terms' => $category->term_id,
                    ),
                ),
            );
            
            $query = new WP_Query($args);
            $count = $query->found_posts;
            
            if ($count > 0) {
                $category_counts[$category->term_id] = array(
                    'category' => $category,
                    'count' => $count
                );
            }
        }
    }
    
    return $category_counts;
}

/**
 * Display categories with counts for a destination
 * 
 * @param int $destination_id The destination term ID
 * @param string $destination_slug The destination slug
 * @param bool $show_empty Whether to show categories with zero tours
 * @return string HTML output of categories with counts
 */
function bike_theme_display_destination_categories($destination_id, $destination_slug, $show_empty = false) {
    $category_counts = bike_theme_count_tours_by_category_in_destination($destination_id);
    
    if (empty($category_counts)) {
        return '';
    }
    
    $output = '<div class="destination-categories">';
    $output .= '<ul class="list-unstyled">';
    
    foreach ($category_counts as $data) {
        $category = $data['category'];
        $count = $data['count'];
        
        $output .= '<li>';
        $output .= '<a href="/bike-tour?tour_category=' . $category->slug . '&destination=' . $destination_slug . '">';
        $output .= esc_html($category->name);
        $output .= ' <span class="badge bg-primary rounded-pill">' . $count . '</span>';
        $output .= '</a>';
        $output .= '</li>';
    }
    
    $output .= '</ul>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Enqueue booking scripts
 */
function bike_theme_enqueue_booking_scripts() {
    if (is_singular('bike_tour')) {
        wp_enqueue_script('bike-theme-booking', get_template_directory_uri() . '/assets/js/booking.js', array('jquery'), '1.0.0', true);
        wp_localize_script('bike-theme-booking', 'bike_booking', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('bike_booking_nonce'),
            'submitting_text' => __('Submitting...', 'bike-theme'),
            'submit_text' => __('Book Now', 'bike-theme'),
            'error_message' => __('An error occurred. Please try again.', 'bike-theme')
        ));
    }
}
add_action('wp_enqueue_scripts', 'bike_theme_enqueue_booking_scripts');

/**
 * Process booking Ajax request
 */
function bike_theme_process_booking() {
    // Verify nonce
    if (!isset($_POST['bike_tour_booking_nonce']) || 
        !wp_verify_nonce($_POST['bike_tour_booking_nonce'], 'bike_tour_booking')) {
        wp_send_json_error(array('message' => __('Invalid security token.', 'bike-theme')));
    }

    // Sanitize and validate form data
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $date = sanitize_text_field($_POST['date']);
    $participants = intval($_POST['participants']);
    $message = sanitize_textarea_field($_POST['message']);
    $tour_id = intval($_POST['tour_id']);

    // Validate required fields
    $errors = array();
    if (empty($name)) $errors[] = __('Name is required', 'bike-theme');
    if (empty($email)) $errors[] = __('Email is required', 'bike-theme');
    if (empty($phone)) $errors[] = __('Phone is required', 'bike-theme');
    if (empty($date)) $errors[] = __('Date is required', 'bike-theme');
    if ($participants < 1) $errors[] = __('Number of participants must be at least 1', 'bike-theme');
    if (!$tour_id) $errors[] = __('Invalid tour selected', 'bike-theme');

    if (!empty($errors)) {
        wp_send_json_error(array('message' => implode('<br>', $errors)));
    }

    // Calculate total price
    $price_per_person = bike_theme_get_tour_price($tour_id, $participants);
    $total_price = $price_per_person * $participants;

    // Create booking post
    $booking_data = array(
        'post_title'    => sprintf(__('Booking for %s - %s - %s', 'bike-theme'), get_the_title($tour_id), $name, $date),
        'post_type'     => 'bike_booking',
        'post_status'   => 'publish'
    );

    $booking_id = wp_insert_post($booking_data);

    if ($booking_id) {
        // Add booking meta data
        add_post_meta($booking_id, '_booking_tour_id', $tour_id);
        add_post_meta($booking_id, '_booking_customer_name', $name);
        add_post_meta($booking_id, '_booking_customer_email', $email);
        add_post_meta($booking_id, '_booking_customer_phone', $phone);
        add_post_meta($booking_id, '_booking_date', $date);
        add_post_meta($booking_id, '_booking_participants', $participants);
        add_post_meta($booking_id, '_booking_message', $message);
        add_post_meta($booking_id, '_booking_price_per_person', $price_per_person);
        add_post_meta($booking_id, '_booking_total_price', $total_price);
        add_post_meta($booking_id, '_booking_status', 'pending');

        // Set booking status taxonomy
        wp_set_object_terms($booking_id, 'pending', 'booking_status');

        // Send confirmation email to customer
        $to = $email;
        $subject = sprintf(__('Booking Confirmation - %s', 'bike-theme'), get_the_title($tour_id));
        $message = sprintf(
            __('Thank you for booking %s. Your booking details:

            Name: %s
            Email: %s
            Phone: %s
            Date: %s
            Participants: %d
            Total Price: %s

            We will contact you shortly to confirm your booking.

            Best regards,
            %s', 'bike-theme'),
            get_the_title($tour_id),
            $name,
            $email,
            $phone,
            $date,
            $participants,
            bike_theme_format_price($total_price),
            get_bloginfo('name')
        );
        wp_mail($to, $subject, $message);

        // Send notification email to admin
        $admin_email = get_option('admin_email');
        $admin_subject = sprintf(__('New Booking - %s', 'bike-theme'), get_the_title($tour_id));
        wp_mail($admin_email, $admin_subject, $message);

        wp_send_json_success(array(
            'message' => __('Your booking has been submitted successfully. We will contact you shortly.', 'bike-theme'),
            'booking_id' => $booking_id
        ));
    } else {
        wp_send_json_error(array('message' => __('Failed to create booking. Please try again.', 'bike-theme')));
    }
}
add_action('wp_ajax_bike_theme_process_booking', 'bike_theme_process_booking');
add_action('wp_ajax_nopriv_bike_theme_process_booking', 'bike_theme_process_booking');

/**
 * Get formatted tour duration
 * 
 * @param int $tour_id Tour post ID
 * @return string Formatted duration string
 */
function bike_theme_get_tour_duration($tour_id) {
    // Get saved display string
    $duration = get_post_meta($tour_id, '_tour_duration_display', true);
    if (!empty($duration)) {
        return $duration;
    }

    // If no display string, format based on type
    $duration_type = get_post_meta($tour_id, '_tour_duration_type', true) ?: 'days_nights';
    
    if ($duration_type === 'days_nights') {
        $days = get_post_meta($tour_id, '_tour_duration_days', true);
        $nights = get_post_meta($tour_id, '_tour_duration_nights', true);
        
        if (empty($days) && empty($nights)) {
            return '';
        }
        
        $duration = '';
        if (!empty($days)) {
            $duration = sprintf(
                _n('%d day', '%d days', $days, 'bike-theme'),
                $days
            );
        }
        
        if (!empty($nights)) {
            if (!empty($duration)) {
                $duration .= ' ';
            }
            $duration .= sprintf(
                _n('%d night', '%d nights', $nights, 'bike-theme'),
                $nights
            );
        }
        
        return $duration;
    } else {
        $hours = get_post_meta($tour_id, '_tour_duration_hours', true);
        if (empty($hours)) {
            return '';
        }
        
        return sprintf(
            _n('%g hour', '%g hours', ceil($hours), 'bike-theme'),
            $hours
        );
    }
}

/**
 * Get tour additions
 */
function bike_theme_get_tour_additions($tour_id) {
    $additions = get_post_meta($tour_id, '_tour_additions', true);
    return is_array($additions) ? $additions : array();
}

/**
 * Calculate additions total price
 */
function bike_theme_calculate_additions_price($tour_id, $selected_additions, $participants = 1) {
    $total = 0;
    $additions = bike_theme_get_tour_additions($tour_id);
    
    foreach ($additions as $addition) {
        if (in_array($addition['name'], $selected_additions)) {
            if (isset($addition['per_person']) && $addition['per_person']) {
                $total += floatval($addition['price']) * $participants;
            } else {
                $total += floatval($addition['price']);
            }
        }
    }
    
    return $total;
}

/**
 * Get total booking price including additions
 */
function bike_theme_get_booking_total_price($tour_id, $participants = 1, $selected_additions = array()) {
    $tour_price = bike_theme_get_tour_total_price($tour_id, $participants);
    $additions_price = bike_theme_calculate_additions_price($tour_id, $selected_additions, $participants);
    
    return $tour_price + $additions_price;
}
