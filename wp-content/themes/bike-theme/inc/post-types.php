<?php

/**
 * Register custom post types
 */

if (!defined('ABSPATH')) {
    exit;
}

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
        'menu_icon'             => 'dashicons-sos',
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
 * Register Tour post type
 */
function bike_theme_register_tour_post_type()
{
    $labels = array(
        'name'               => __('Bike Tours', 'bike-theme'),
        'singular_name'      => __('Bike Tour', 'bike-theme'),
        'menu_name'          => __('Bike Tours', 'bike-theme'),
        'add_new'            => __('Add New', 'bike-theme'),
        'add_new_item'       => __('Add New Bike Tour', 'bike-theme'),
        'edit_item'          => __('Edit Bike Tour', 'bike-theme'),
        'new_item'           => __('New Bike Tour', 'bike-theme'),
        'view_item'          => __('View Bike Tour', 'bike-theme'),
        'search_items'       => __('Search Bike Tours', 'bike-theme'),
        'not_found'          => __('No bike tours found', 'bike-theme'),
        'not_found_in_trash' => __('No bike tours found in Trash', 'bike-theme'),
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'has_archive'         => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'bike-tour'),
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-location-alt',
        'supports'            => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest'        => true,
    );

    register_post_type('bike_tour', $args);

    // Register Tour Category taxonomy
    $tax_labels = array(
        'name'              => __('Bike Tour Categories', 'bike-theme'),
        'singular_name'     => __('Bike Tour Category', 'bike-theme'),
        'search_items'      => __('Search Bike Tour Categories', 'bike-theme'),
        'all_items'         => __('All Bike Tour Categories', 'bike-theme'),
        'parent_item'       => __('Parent Bike Tour Category', 'bike-theme'),
        'parent_item_colon' => __('Parent Bike Tour Category:', 'bike-theme'),
        'edit_item'         => __('Edit Bike Tour Category', 'bike-theme'),
        'update_item'       => __('Update Bike Tour Category', 'bike-theme'),
        'add_new_item'      => __('Add New Bike Tour Category', 'bike-theme'),
        'new_item_name'     => __('New Bike Tour Category Name', 'bike-theme'),
        'menu_name'         => __('Categories', 'bike-theme'),
    );

    $tax_args = array(
        'hierarchical'      => true,
        'labels'            => $tax_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'bike-tour-category'),
        'show_in_rest'      => true,
    );

    register_taxonomy('tour_category', array('bike_tour'), $tax_args);

    // Đăng ký taxonomy Destination cho Bike Tour
    register_taxonomy('tour_destination', 'bike_tour', array(
        'labels' => array(
            'name' => __('Destinations', 'bike-theme'),
            'singular_name' => __('Destination', 'bike-theme'),
            'search_items' => __('Search Destinations', 'bike-theme'),
            'all_items' => __('All Destinations', 'bike-theme'),
            'parent_item' => __('Parent Destination', 'bike-theme'),
            'parent_item_colon' => __('Parent Destination:', 'bike-theme'),
            'edit_item' => __('Edit Destination', 'bike-theme'),
            'update_item' => __('Update Destination', 'bike-theme'),
            'add_new_item' => __('Add New Destination', 'bike-theme'),
            'new_item_name' => __('New Destination Name', 'bike-theme'),
            'menu_name' => __('Destinations', 'bike-theme'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'destination'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'bike_theme_register_tour_post_type');

/**
 * Register Booking post type
 */
function bike_theme_register_booking_post_type()
{
    $labels = array(
        'name'               => __('Bookings', 'bike-theme'),
        'singular_name'      => __('Booking', 'bike-theme'),
        'menu_name'          => __('Bookings', 'bike-theme'),
        'add_new'            => __('Add New', 'bike-theme'),
        'add_new_item'       => __('Add New Booking', 'bike-theme'),
        'edit_item'          => __('Edit Booking', 'bike-theme'),
        'new_item'           => __('New Booking', 'bike-theme'),
        'view_item'          => __('View Booking', 'bike-theme'),
        'search_items'       => __('Search Bookings', 'bike-theme'),
        'not_found'          => __('No bookings found', 'bike-theme'),
        'not_found_in_trash' => __('No bookings found in Trash', 'bike-theme'),
    );

    $args = array(
        'labels'              => $labels,
        'public'              => false,
        'has_archive'         => false,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'booking'),
        'capability_type'     => 'post',
        'hierarchical'        => false,
        'menu_position'       => 6,
        'menu_icon'           => 'dashicons-calendar-alt',
        'supports'            => array('title'),
        'show_in_rest'        => true,
    );

    register_post_type('bike_booking', $args);

    // Register Booking Status taxonomy
    $status_labels = array(
        'name'              => __('Booking Status', 'bike-theme'),
        'singular_name'     => __('Status', 'bike-theme'),
        'search_items'      => __('Search Statuses', 'bike-theme'),
        'all_items'         => __('All Statuses', 'bike-theme'),
        'edit_item'         => __('Edit Status', 'bike-theme'),
        'update_item'       => __('Update Status', 'bike-theme'),
        'add_new_item'      => __('Add New Status', 'bike-theme'),
        'new_item_name'     => __('New Status Name', 'bike-theme'),
        'menu_name'         => __('Status', 'bike-theme'),
    );

    $status_args = array(
        'hierarchical'      => true,
        'labels'            => $status_labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'booking-status'),
        'show_in_rest'      => true,
    );

    register_taxonomy('booking_status', array('bike_booking'), $status_args);
}
add_action('init', 'bike_theme_register_booking_post_type');
