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
}
add_action('after_setup_theme', 'bike_theme_setup');

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
 * Custom Bike post type
 */
function bike_theme_register_post_types()
{
    // Register Bike Custom Post Type
    $labels = array(
        'name'                  => _x('Bikes', 'Post Type General Name', 'bike-theme'),
        'singular_name'         => _x('Bike', 'Post Type Singular Name', 'bike-theme'),
        'menu_name'             => __('Bikes', 'bike-theme'),
        'name_admin_bar'        => __('Bike', 'bike-theme'),
        'archives'              => __('Bike Archives', 'bike-theme'),
        'attributes'            => __('Bike Attributes', 'bike-theme'),
        'all_items'             => __('All Bikes', 'bike-theme'),
        'add_new_item'          => __('Add New Bike', 'bike-theme'),
        'add_new'               => __('Add New', 'bike-theme'),
        'new_item'              => __('New Bike', 'bike-theme'),
        'edit_item'             => __('Edit Bike', 'bike-theme'),
        'update_item'           => __('Update Bike', 'bike-theme'),
        'view_item'             => __('View Bike', 'bike-theme'),
        'view_items'            => __('View Bikes', 'bike-theme'),
        'search_items'          => __('Search Bike', 'bike-theme'),
    );
    $args = array(
        'label'                 => __('Bike', 'bike-theme'),
        'description'           => __('Bike Products', 'bike-theme'),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-bicycle',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );
    register_post_type('bike', $args);

    // Register Bike Category Taxonomy
    $labels = array(
        'name'                       => _x('Bike Categories', 'Taxonomy General Name', 'bike-theme'),
        'singular_name'              => _x('Bike Category', 'Taxonomy Singular Name', 'bike-theme'),
        'menu_name'                  => __('Bike Categories', 'bike-theme'),
        'all_items'                  => __('All Bike Categories', 'bike-theme'),
        'parent_item'                => __('Parent Bike Category', 'bike-theme'),
        'parent_item_colon'          => __('Parent Bike Category:', 'bike-theme'),
        'new_item_name'              => __('New Bike Category Name', 'bike-theme'),
        'add_new_item'               => __('Add New Bike Category', 'bike-theme'),
        'edit_item'                  => __('Edit Bike Category', 'bike-theme'),
        'update_item'                => __('Update Bike Category', 'bike-theme'),
        'view_item'                  => __('View Bike Category', 'bike-theme'),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
    );
    register_taxonomy('bike_category', array( 'bike' ), $args);

    // Register Bike Brand Taxonomy
    $labels = array(
        'name'                       => _x('Bike Brands', 'Taxonomy General Name', 'bike-theme'),
        'singular_name'              => _x('Bike Brand', 'Taxonomy Singular Name', 'bike-theme'),
        'menu_name'                  => __('Bike Brands', 'bike-theme'),
        'all_items'                  => __('All Bike Brands', 'bike-theme'),
        'parent_item'                => __('Parent Bike Brand', 'bike-theme'),
        'parent_item_colon'          => __('Parent Bike Brand:', 'bike-theme'),
        'new_item_name'              => __('New Bike Brand Name', 'bike-theme'),
        'add_new_item'               => __('Add New Bike Brand', 'bike-theme'),
        'edit_item'                  => __('Edit Bike Brand', 'bike-theme'),
        'update_item'                => __('Update Bike Brand', 'bike-theme'),
        'view_item'                  => __('View Bike Brand', 'bike-theme'),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'show_in_rest'               => true,
    );
    register_taxonomy('bike_brand', array( 'bike' ), $args);
}
add_action('init', 'bike_theme_register_post_types');

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
function bike_theme_admin_scripts($hook) {
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
function bike_theme_additional_widgets_init() {
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
 * Register Testimonial custom post type
 */
function bike_theme_testimonial_post_type() {
    $labels = array(
        'name'                  => _x('Testimonials', 'Post Type General Name', 'bike-theme'),
        'singular_name'         => _x('Testimonial', 'Post Type Singular Name', 'bike-theme'),
        'menu_name'             => __('Testimonials', 'bike-theme'),
        'name_admin_bar'        => __('Testimonial', 'bike-theme'),
        'archives'              => __('Testimonial Archives', 'bike-theme'),
        'attributes'            => __('Testimonial Attributes', 'bike-theme'),
        'all_items'             => __('All Testimonials', 'bike-theme'),
        'add_new_item'          => __('Add New Testimonial', 'bike-theme'),
        'add_new'               => __('Add New', 'bike-theme'),
        'new_item'              => __('New Testimonial', 'bike-theme'),
        'edit_item'             => __('Edit Testimonial', 'bike-theme'),
        'update_item'           => __('Update Testimonial', 'bike-theme'),
        'view_item'             => __('View Testimonial', 'bike-theme'),
        'view_items'            => __('View Testimonials', 'bike-theme'),
        'search_items'          => __('Search Testimonial', 'bike-theme'),
    );
    $args = array(
        'label'                 => __('Testimonial', 'bike-theme'),
        'description'           => __('Customer Testimonials', 'bike-theme'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail'),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-format-quote',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );
    register_post_type('testimonial', $args);
}
add_action('init', 'bike_theme_testimonial_post_type');

/**
 * Register Testimonial metabox
 */
function bike_theme_testimonial_meta_boxes() {
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
function bike_theme_testimonial_details_callback($post) {
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
function bike_theme_save_testimonial_meta($post_id) {
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
function bike_theme_bike_featured_meta_box() {
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
function bike_theme_bike_featured_callback($post) {
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
function bike_theme_save_bike_featured($post_id) {
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
function bike_theme_additional_options($wp_customize) {
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

    // Testimonials Section Settings
    $wp_customize->add_setting('bike_theme_testimonials_show', array(
        'default'           => true,
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('bike_theme_testimonials_show', array(
        'label'    => __('Show Testimonials Section', 'bike-theme'),
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
}
add_action('customize_register', 'bike_theme_additional_options');

/**
 * Add loading="lazy" attribute to all images in content
 */
function bike_theme_add_lazy_loading_attribute($content) {
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
function bike_theme_lazy_loading_thumbnail_class($attr) {
    if (!isset($attr['class'])) {
        $attr['class'] = 'lazy-image-container';
    } else {
        $attr['class'] .= ' lazy-image-container';
    }
    
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'bike_theme_lazy_loading_thumbnail_class');
