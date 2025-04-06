<?php
/**
 * The template for displaying single tour
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Bike_Theme
 */

get_header();

// Process booking form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bike_tour_booking'])) {
    // Verify nonce
    if (!isset($_POST['bike_tour_booking_nonce']) || 
        !wp_verify_nonce($_POST['bike_tour_booking_nonce'], 'bike_tour_booking')) {
        wp_die(__('Invalid nonce specified', 'bike-theme'));
    }

    // Sanitize and validate form data
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $date = sanitize_text_field($_POST['date']);
    $participants = intval($_POST['participants']);
    $message = sanitize_textarea_field($_POST['message']);
    $tour_id = get_the_ID();

    // Validate required fields
    $errors = array();
    if (empty($name)) $errors[] = __('Name is required', 'bike-theme');
    if (empty($email)) $errors[] = __('Email is required', 'bike-theme');
    if (empty($phone)) $errors[] = __('Phone is required', 'bike-theme');
    if (empty($date)) $errors[] = __('Date is required', 'bike-theme');
    if ($participants < 1) $errors[] = __('Number of participants must be at least 1', 'bike-theme');

    // If no errors, create booking
    if (empty($errors)) {
        // Calculate total price
        $price_per_person = bike_theme_get_tour_price($tour_id, $participants);
        $total_price = $price_per_person * $participants;

        // Create booking post
        $booking_data = array(
            'post_title'    => sprintf(__('Booking for %s - %s', 'bike-theme'), get_the_title($tour_id), $name),
            'post_type'     => 'bike_booking',
            'post_status'   => 'publish'
        );

        $booking_id = wp_insert_post($booking_data);

        if ($booking_id) {
            // Add booking meta data with correct field names
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

            // Set success message
            $booking_success = true;
        }
    }
}

// Enqueue the tour single CSS
wp_enqueue_style('bike-theme-tour-single', get_template_directory_uri() . '/assets/css/tour-single.css', array(), '1.0.0');

// Get tour meta data
$duration = bike_theme_get_tour_duration(get_the_ID());
$distance = get_post_meta(get_the_ID(), '_tour_distance', true);
$price = get_post_meta(get_the_ID(), '_tour_price', true);
$max_participants = get_post_meta(get_the_ID(), '_tour_max_participants', true);
$difficulty = get_post_meta(get_the_ID(), '_tour_difficulty', true);
$start_location = get_post_meta(get_the_ID(), '_tour_start_location', true);
$end_location = get_post_meta(get_the_ID(), '_tour_end_location', true);
$schedule = get_post_meta(get_the_ID(), '_tour_schedule', true);
$included = get_post_meta(get_the_ID(), '_tour_included', true);
$not_included = get_post_meta(get_the_ID(), '_tour_not_included', true);
$gallery_ids = get_post_meta(get_the_ID(), '_tour_gallery', true);

// Format difficulty text and class
$difficulty_text = '';
$difficulty_class = '';
switch ($difficulty) {
    case 'easy':
        $difficulty_text = __('Easy', 'bike-theme');
        $difficulty_class = 'text-success';
        break;
    case 'moderate':
        $difficulty_text = __('Moderate', 'bike-theme');
        $difficulty_class = 'text-warning';
        break;
    case 'difficult':
        $difficulty_text = __('Difficult', 'bike-theme');
        $difficulty_class = 'text-danger';
        break;
}

// Tab active state
$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'overview';
?>

<main id="primary" class="site-main">
    <!-- Page Header Start -->
    <div class="container-fluid page-header tour-header mb-5 p-0" style="background-image: url(<?php echo has_post_thumbnail() ? esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full')) : esc_url(get_template_directory_uri() . '/assets/images/bikes/tour-banner.jpg'); ?>);">
        <div class="container-fluid page-header-inner tour-header py-5 relative">
            <div class="container text-center pb-5">
                <h1 class="text-white mb-3 animated slideInDown"><?php the_title(); ?></h1>
            </div>
            <!-- Tour Basic Info Start -->
           <div class="tour-basic-info absolute">
                <div class="row mb-5 bg-light shadow rounded p-4 align-items-center wrapper">
                
                    <div class="col">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 btn-square bg-primary rounded-circle">
                                <i class="fa fa-map-marker-alt text-white"></i>
                            </div>
                            <div class="ms-3 tour-basic-info-title">
                                <h5 class="mb-1"><?php esc_html_e('Price', 'bike-theme'); ?></h5>
                                <span><?php echo bike_theme_format_price(bike_theme_get_tour_price(get_the_ID())); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 btn-square bg-primary rounded-circle">
                                <i class="fa fa-clock text-white"></i>
                            </div>
                            <div class="ms-3 tour-basic-info-title">
                                <h5 class="mb-1"><?php esc_html_e('Duration', 'bike-theme'); ?></h5>
                                <span><?php echo esc_html($duration); ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 btn-square bg-primary rounded-circle">
                                <i class="fa fa-route text-white"></i>
                            </div>
                            <div class="ms-3 tour-basic-info-title">
                                <h5 class="mb-1"><?php esc_html_e('Distance', 'bike-theme'); ?></h5>
                                <span><?php echo esc_html($distance); ?> km</span>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 btn-square bg-primary rounded-circle">
                                <i class="fa fa-mountain text-white"></i>
                            </div>
                            <div class="ms-3 tour-basic-info-title">
                                <h5 class="mb-1"><?php esc_html_e('Difficulty', 'bike-theme'); ?></h5>
                                <span class="<?php echo esc_attr($difficulty_class); ?>"><?php echo esc_html($difficulty_text); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
           </div>
        <!-- Tour  Basic Info End -->
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Tour Detail Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5">
                <!-- Tour Description -->
                <div class="col-lg-8">
                    <!-- Tour Tabs Start -->
                    <div class="mb-5">
                        <ul class="nav nav-tabs mb-4 d-flex justify-content-center tour-tabs" id="tourTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $active_tab === 'overview' ? 'active' : ''; ?>" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab" aria-controls="overview" aria-selected="<?php echo $active_tab === 'overview' ? 'true' : 'false'; ?>">
                                    <?php esc_html_e('Overview', 'bike-theme'); ?>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $active_tab === 'itinerary' ? 'active' : ''; ?>" id="itinerary-tab" data-bs-toggle="tab" data-bs-target="#itinerary" type="button" role="tab" aria-controls="itinerary" aria-selected="<?php echo $active_tab === 'itinerary' ? 'true' : 'false'; ?>">
                                    <?php esc_html_e('Itinerary', 'bike-theme'); ?>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $active_tab === 'inclusions' ? 'active' : ''; ?>" id="inclusions-tab" data-bs-toggle="tab" data-bs-target="#inclusions" type="button" role="tab" aria-controls="inclusions" aria-selected="<?php echo $active_tab === 'inclusions' ? 'true' : 'false'; ?>">
                                    <?php esc_html_e('Price & Services', 'bike-theme'); ?>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $active_tab === 'booking' ? 'active' : ''; ?>" id="booking-tab" data-bs-toggle="tab" data-bs-target="#booking" type="button" role="tab" aria-controls="booking" aria-selected="<?php echo $active_tab === 'booking' ? 'true' : 'false'; ?>">
                                    <?php esc_html_e('Booking & Cancellation', 'bike-theme'); ?>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $active_tab === 'gallery' ? 'active' : ''; ?>" id="gallery-tab" data-bs-toggle="tab" data-bs-target="#gallery" type="button" role="tab" aria-controls="gallery" aria-selected="<?php echo $active_tab === 'gallery' ? 'true' : 'false'; ?>">
                                    <?php esc_html_e('Gallery', 'bike-theme'); ?>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $active_tab === 'reviews' ? 'active' : ''; ?>" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab" aria-controls="reviews" aria-selected="<?php echo $active_tab === 'reviews' ? 'true' : 'false'; ?>">
                                    <?php esc_html_e('Reviews', 'bike-theme'); ?>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $active_tab === 'contact' ? 'active' : ''; ?>" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="<?php echo $active_tab === 'contact' ? 'true' : 'false'; ?>">
                                    <?php esc_html_e('Contact', 'bike-theme'); ?>
                                </button>
                            </li>
                        </ul>
                        
                        <div class="tab-content p-3" style="border: none;" id="tourTabContent">
                            <!-- Overview Tab -->
                            <div class="tab-pane fade <?php echo $active_tab === 'overview' ? 'show active' : ''; ?>" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                                <?php include(get_template_directory() . '/template-parts/bike-tours/overview.php'); ?>
                                <!-- Tour Itinerary Start -->
                                <?php include(get_template_directory() . '/template-parts/bike-tours/itinerary.php'); ?>
                                <!-- Tour Itinerary End -->
                                <!-- Price & Services Tab -->
                                <?php include(get_template_directory() . '/template-parts/bike-tours/price-and-services.php'); ?>
                                <!-- Price & Services Tab End -->
                                <!-- Booking & Cancellation Tab -->
                                <?php include(get_template_directory() . '/template-parts/bike-tours/booking-cancellation.php'); ?>
                                <!-- Booking & Cancellation Tab End -->
                                <!-- Gallery Tab -->
                                <?php include(get_template_directory() . '/template-parts/bike-tours/gallery.php'); ?>
                                <!-- Gallery Tab End -->    
                                <!-- Reviews Tab -->
                                <?php include(get_template_directory() . '/template-parts/bike-tours/review.php'); ?>
                                <!-- Reviews Tab End -->
                                <!-- Contact Tab -->
                                <?php include(get_template_directory() . '/template-parts/bike-tours/contact.php'); ?>
                                <!-- Contact Tab End -->
                            </div>
                            
                            <!-- Itinerary Tab -->
                            <div class="tab-pane fade <?php echo $active_tab === 'itinerary' ? 'show active' : ''; ?>" id="itinerary" role="tabpanel" aria-labelledby="itinerary-tab">
                                <?php include(get_template_directory() . '/template-parts/bike-tours/itinerary.php'); ?>
                            </div>
                            
                            <!-- Price & Services Tab -->
                            <div class="tab-pane fade <?php echo $active_tab === 'inclusions' ? 'show active' : ''; ?>" id="inclusions" role="tabpanel" aria-labelledby="inclusions-tab">
                                <?php include(get_template_directory() . '/template-parts/bike-tours/price-and-services.php'); ?>
                            </div>
                            
                            <!-- Booking & Cancellation Tab -->
                            <div class="tab-pane fade <?php echo $active_tab === 'booking' ? 'show active' : ''; ?>" id="booking" role="tabpanel" aria-labelledby="booking-tab">
                                <?php include(get_template_directory() . '/template-parts/bike-tours/booking-cancellation.php'); ?>
                            </div>
                            
                            <!-- Gallery Tab -->
                            <div class="tab-pane fade <?php echo $active_tab === 'gallery' ? 'show active' : ''; ?>" id="gallery" role="tabpanel" aria-labelledby="gallery-tab">
                                <?php include(get_template_directory() . '/template-parts/bike-tours/gallery.php'); ?>
                            </div>
                            
                            <!-- Reviews Tab -->
                            <div class="tab-pane fade <?php echo $active_tab === 'reviews' ? 'show active' : ''; ?>" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                <?php include(get_template_directory() . '/template-parts/bike-tours/review.php'); ?>
                            </div>
                            
                            <!-- Contact Tab -->
                            <div class="tab-pane fade <?php echo $active_tab === 'contact' ? 'show active' : ''; ?>" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                <?php include(get_template_directory() . '/template-parts/bike-tours/contact.php'); ?>
                            </div>

                            <!-- In the pricing tab -->
                            <div class="tab-pane fade" id="pricing" role="tabpanel">
                                <div class="tour-pricing-details">
                                    <h3><?php esc_html_e('Tour Price', 'bike-theme'); ?></h3>
                                    <?php
                                    $flexible_pricing_enabled = get_post_meta(get_the_ID(), '_tour_flexible_pricing_enabled', true);
                                    $flexible_pricing = get_post_meta(get_the_ID(), '_tour_flexible_pricing', true);
                                    $standard_price = get_post_meta(get_the_ID(), '_tour_price', true);

                                    if ($flexible_pricing_enabled && !empty($flexible_pricing)) {
                                        ?>
                                        <div class="flexible-pricing-table">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th><?php esc_html_e('Number of Participants', 'bike-theme'); ?></th>
                                                        <th><?php esc_html_e('Price per Person', 'bike-theme'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($flexible_pricing as $price_level) { ?>
                                                        <tr>
                                                            <td><?php echo esc_html($price_level['participants']); ?>+</td>
                                                            <td><?php echo esc_html(number_format($price_level['price'], 0, '.', ',')); ?> VND</td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php
                                    } else {
                                        ?>
                                        <div class="standard-price">
                                            <p class="price-amount"><?php echo esc_html(number_format($standard_price, 0, '.', ',')); ?> VND</p>
                                            <p class="price-note"><?php esc_html_e('per person', 'bike-theme'); ?></p>
                                        </div>
                                        <?php
                                    }
                                    ?>

                                    <!-- Tour Additions Section -->
                                    <?php
                                    $additions = get_post_meta(get_the_ID(), '_tour_additions', true);
                                    if (!empty($additions)) {
                                        ?>
                                        <div class="tour-additions mt-4">
                                            <h3><?php esc_html_e('Optional Extras', 'bike-theme'); ?></h3>
                                            <div class="additions-table">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th><?php esc_html_e('Service', 'bike-theme'); ?></th>
                                                            <th><?php esc_html_e('Description', 'bike-theme'); ?></th>
                                                            <th><?php esc_html_e('Price', 'bike-theme'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($additions as $addition) { ?>
                                                            <tr>
                                                                <td><?php echo esc_html($addition['name']); ?></td>
                                                                <td><?php echo esc_html($addition['description']); ?></td>
                                                                <td>
                                                                    <?php
                                                                    echo esc_html(number_format($addition['price'], 0, '.', ',')); ?> VND
                                                                    <?php
                                                                    if (isset($addition['per_person']) && $addition['per_person']) {
                                                                        echo ' <span class="per-person-note">' . esc_html__('per person', 'bike-theme') . '</span>';
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <style>
                                        .tour-additions {
                                            background: #f9f9f9;
                                            padding: 20px;
                                            border-radius: 8px;
                                            margin-top: 30px;
                                        }

                                        .tour-additions h3 {
                                            margin-bottom: 20px;
                                            color: #333;
                                            font-size: 1.5rem;
                                        }

                                        .additions-table table {
                                            width: 100%;
                                            border-collapse: collapse;
                                            background: #fff;
                                            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                                        }

                                        .additions-table th,
                                        .additions-table td {
                                            padding: 12px 15px;
                                            text-align: left;
                                            border-bottom: 1px solid #eee;
                                        }

                                        .additions-table th {
                                            background: #f5f5f5;
                                            font-weight: 600;
                                            color: #333;
                                        }

                                        .additions-table tr:last-child td {
                                            border-bottom: none;
                                        }

                                        .per-person-note {
                                            font-size: 0.9em;
                                            color: #666;
                                            font-style: italic;
                                        }

                                        @media (max-width: 768px) {
                                            .additions-table {
                                                overflow-x: auto;
                                                -webkit-overflow-scrolling: touch;
                                            }
                                            
                                            .additions-table table {
                                                min-width: 500px;
                                            }
                                        }
                                        </style>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Tour Tabs End -->

                    <!-- Tour Schedule Start -->
                    <?php if (!empty($schedule)) : ?>
                    <div class="mb-5">
                        <h3 class="mb-4"><?php esc_html_e('Bike Tour Schedule', 'bike-theme'); ?></h3>
                        <div class="border rounded p-4">
                            <?php echo wpautop(wp_kses_post($schedule)); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <!-- Tour Schedule End -->
                </div>
                <!-- Tour Description End -->

                <!-- Booking Form Start -->
                <div class="col-lg-4">
                    <div class="bg-light rounded p-4 mb-5 wow fadeInUp tour-booking-form" data-wow-delay="0.1s">
                        <h4 class="mb-2 text-center"><?php esc_html_e('Book This Tour', 'bike-theme'); ?></h4>
                        <div class="booking-response"></div>
                        <form id="tour-booking-form" method="post">
                            <?php wp_nonce_field('bike_tour_booking', 'bike_tour_booking_nonce'); ?>
                            <input type="hidden" name="action" value="bike_theme_process_booking">
                            <input type="hidden" name="tour_id" value="<?php echo get_the_ID(); ?>">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="<?php esc_attr_e('Your Name', 'bike-theme'); ?>" required>
                                        <label for="name"><?php esc_html_e('Your Name', 'bike-theme'); ?></label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="<?php esc_attr_e('Your Email', 'bike-theme'); ?>" required>
                                        <label for="email"><?php esc_html_e('Your Email', 'bike-theme'); ?></label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="<?php esc_attr_e('Your Phone', 'bike-theme'); ?>" required>
                                        <label for="phone"><?php esc_html_e('Your Phone', 'bike-theme'); ?></label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="date" name="date" required min="<?php echo date('Y-m-d'); ?>">
                                        <label for="date"><?php esc_html_e('Preferred Date', 'bike-theme'); ?></label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <select class="form-select" id="participants" name="participants">
                                            <?php for ($i = 1; $i <= $max_participants; $i++) : ?>
                                                <option value="<?php echo esc_attr($i); ?>"><?php echo esc_html($i); ?></option>
                                            <?php endfor; ?>
                                        </select>
                                        <label for="participants"><?php esc_html_e('Number of Participants', 'bike-theme'); ?></label>
                                    </div>
                                </div>
                                <?php
                                $additions = bike_theme_get_tour_additions(get_the_ID());
                                if (!empty($additions)) :
                                ?>
                                <div class="col-12">
                                    <h5 class="mb-3"><?php esc_html_e('Optional Extras', 'bike-theme'); ?></h5>
                                    <div class="additions-options">
                                        <?php foreach ($additions as $addition) : ?>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input addition-checkbox" type="checkbox" 
                                                   name="additions[]" value="<?php echo esc_attr($addition['name']); ?>" 
                                                   id="addition_<?php echo esc_attr(sanitize_title($addition['name'])); ?>"
                                                   data-price="<?php echo esc_attr($addition['price']); ?>"
                                                   data-per-person="<?php echo esc_attr(isset($addition['per_person']) && $addition['per_person'] ? '1' : '0'); ?>">
                                            <label class="form-check-label" for="addition_<?php echo esc_attr(sanitize_title($addition['name'])); ?>">
                                                <?php echo esc_html($addition['name']); ?> 
                                                (<?php echo esc_html(number_format($addition['price'], 0, '.', ',')); ?> VND
                                                <?php if (isset($addition['per_person']) && $addition['per_person']) echo esc_html__('per person', 'bike-theme'); ?>)
                                                <?php if (!empty($addition['description'])) : ?>
                                                    <small class="text-muted d-block"><?php echo esc_html($addition['description']); ?></small>
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <div class="price-summary bg-white p-3 rounded border">
                                        <h5 class="mb-3"><?php esc_html_e('Price Summary', 'bike-theme'); ?></h5>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><?php esc_html_e('Tour price per person:', 'bike-theme'); ?></span>
                                            <span id="tour-price-per-person"><?php echo esc_html(number_format(bike_theme_get_tour_price(get_the_ID()), 0, '.', ',')); ?> VND</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><?php esc_html_e('Number of participants:', 'bike-theme'); ?></span>
                                            <span id="participant-count">1</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><?php esc_html_e('Tour subtotal:', 'bike-theme'); ?></span>
                                            <span id="tour-subtotal"><?php echo esc_html(number_format(bike_theme_get_tour_total_price(get_the_ID(), 1), 0, '.', ',')); ?> VND</span>
                                        </div>
                                        <div id="additions-summary" style="display: none;">
                                            <div class="additions-list my-2"></div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span><?php esc_html_e('Additions subtotal:', 'bike-theme'); ?></span>
                                                <span id="additions-subtotal">0 VND</span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between fw-bold pt-2 border-top">
                                            <span><?php esc_html_e('Total:', 'bike-theme'); ?></span>
                                            <span id="total-price"><?php echo esc_html(number_format(bike_theme_get_tour_total_price(get_the_ID(), 1), 0, '.', ',')); ?> VND</span>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="<?php esc_attr_e('Special Request', 'bike-theme'); ?>" id="message" name="message" style="height: 100px"></textarea>
                                        <label for="message"><?php esc_html_e('Special Request', 'bike-theme'); ?></label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary w-100" type="submit"><?php esc_html_e('Book Now', 'bike-theme'); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Booking Form End -->
            </div>
        </div>
    </div>
    <!-- Tour Detail End -->

</main><!-- #main -->

<?php
// Add JavaScript for dynamic price calculation
if (get_post_meta(get_the_ID(), '_tour_flexible_pricing_enabled', true) === '1') :
    ?>
<script>
jQuery(document).ready(function($) {
    // Tour pricing data
    var pricingData = <?php
        $pricing_data = get_post_meta(get_the_ID(), '_tour_flexible_pricing', true);
    if (empty($pricing_data) || !is_array($pricing_data)) {
        $pricing_data = array(
            array('participants' => 1, 'price' => get_post_meta(get_the_ID(), '_tour_price', true))
        );
    }
    echo json_encode($pricing_data);
    ?>;
    
    // Currency settings
    var currency = '<?php echo esc_js(get_theme_mod('bike_theme_currency', '$')); ?>';
    var currencyPosition = '<?php echo esc_js(get_theme_mod('bike_theme_currency_position', 'after')); ?>';
    
    // Sort pricing data by number of participants (ascending)
    pricingData.sort(function(a, b) {
        return a.participants - b.participants;
    });
    
    // Get price for a specific number of participants
    function getPriceForParticipants(participants) {
        var applicablePrice = null;
        
        for (var i = 0; i < pricingData.length; i++) {
            if (participants >= pricingData[i].participants) {
                applicablePrice = pricingData[i].price;
            } else {
                break;
            }
        }
        
        // If no applicable price found, use the first price level
        if (applicablePrice === null && pricingData.length > 0) {
            applicablePrice = pricingData[0].price;
        }
        
        return applicablePrice || <?php echo (int)get_post_meta(get_the_ID(), '_tour_price', true); ?>;
    }
    
    // Format number with thousand separator and currency
    function formatNumber(number) {
        var formattedNumber = number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        
        if (currencyPosition === 'before') {
            return currency + formattedNumber;
        } else {
            return formattedNumber + ' ' + currency;
        }
    }
    
    // Update price display
    function updatePriceDisplay() {
        var participants = parseInt($('#participants').val(), 10);
        var pricePerPerson = getPriceForParticipants(participants);
        var totalPrice = pricePerPerson * participants;
        
        $('#price-per-person').text(formatNumber(pricePerPerson));
        $('#participant-count').text(participants);
        $('#total-price').text(formatNumber(totalPrice));
        $('#tour-price-display').text(formatNumber(pricePerPerson));
    }
    
    // Update price when number of participants changes
    $('#participants').change(function() {
        updatePriceDisplay();
    });
    
    // Initial price update
    updatePriceDisplay();
});
</script>
<?php endif; ?>

<!-- Gallery Lightbox and Tab Styles/Scripts -->
<style>
.gallery-item {
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
    border-radius: 4px;
}

.gallery-item img {
    transition: transform 0.3s ease;
}

.gallery-item:hover img {
    transform: scale(1.05);
}

.gallery-lightbox {
    display: block;
    width: 100%;
    height: 100%;
    position: relative;
}

.gallery-lightbox::after {
    content: "\f00e";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 24px;
    color: #fff;
    background-color: rgba(0, 0, 0, 0.5);
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.gallery-item:hover .gallery-lightbox::after {
    opacity: 1;
}

/* Tab Nav Styles */
.nav-tabs .nav-link {
    color: #6c757d;
    font-weight: 500;
    border-radius: 0;
    padding: 12px 20px;
    border: 1px solid transparent;
    border-bottom: none;
}

.nav-tabs .nav-link.active {
    color: #0078ff;
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
}

.nav-tabs .nav-link:hover {
    color: #0078ff;
    border-color: transparent;
}

.tab-content {
    border: 1px solid #dee2e6;
    border-top: none;
    padding: 25px;
    background-color: #fff;
}

.tour-itinerary h4 {
    margin-top: 20px;
    margin-bottom: 10px;
    color: #0078ff;
}

.price-list {
    list-style: none;
    padding-left: 0;
}

.price-list li {
    margin-bottom: 5px;
    padding: 5px 0;
    border-bottom: 1px dotted #dee2e6;
}

/* Review Form Styles */
.comment-respond {
    margin-top: 30px;
}

.comment-form-rating {
    margin-bottom: 20px;
}

.comment-form-rating label {
    display: block;
    margin-bottom: 10px;
}

.comment-form-rating .stars {
    display: inline-block;
}

.comment-form-rating .stars a {
    color: #ffc107;
    font-size: 24px;
    margin-right: 5px;
}

.tour-itinerary {
    max-width: 1200px;
    margin: 0 auto;
}

.itinerary-day-container {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 30px;
    overflow: hidden;
    display: flex;
    flex-wrap: wrap;
}

.itinerary-day-content {
    flex: 1;
    min-width: 300px;
    padding: 25px;
}

.day-title {
    color: #333;
    font-size: 1.5rem;
    margin-bottom: 15px;
    border-bottom: 2px solid #f8f9fa;
    padding-bottom: 10px;
}

.day-description {
    color: #666;
    line-height: 1.6;
}

.itinerary-day-details {
    background: #f8f9fa;
    padding: 25px;
    min-width: 280px;
    width: 300px;
    border-left: 1px solid #eee;
}

.detail-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 20px;
}

.detail-item:last-child {
    margin-bottom: 0;
}

.detail-item i {
    font-size: 1.2rem;
    color: var(--primary-color);
    margin-right: 15px;
    margin-top: 3px;
}

.detail-content h5 {
    margin: 0 0 5px;
    color: #333;
    font-size: 1.11rem;
}

.detail-content p {
    margin: 0;
    color: #666;
}

.meals-included {
    color: #666;
}

.additional-details ul {
    margin: 0;
    padding-left: 0;
    list-style: none;
}

.additional-details li {
    position: relative;
    padding-left: 15px;
    margin-bottom: 5px;
    color: #666;
}

.additional-details li:before {
    content: "•";
    position: absolute;
    left: 0;
    color: var(--primary-color);;
}

@media (max-width: 768px) {
    .itinerary-day-container {
        flex-direction: column;
    }
    
    .itinerary-day-details {
        width: 100%;
        border-left: none;
        border-top: 1px solid #eee;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Initialize lightbox for gallery images
    $('.gallery-lightbox').on('click', function(e) {
        e.preventDefault();
        
        var imageUrl = $(this).attr('href');
        var imageTitle = $(this).find('img').attr('alt') || '';
        
        // Create lightbox elements
        var lightbox = $('<div class="lightbox-overlay"></div>');
        var lightboxContent = $('<div class="lightbox-content"></div>');
        var lightboxClose = $('<button class="lightbox-close">&times;</button>');
        var lightboxImage = $('<img src="' + imageUrl + '" alt="' + imageTitle + '">');
        var lightboxCaption = '';
        
        if (imageTitle) {
            lightboxCaption = $('<div class="lightbox-caption">' + imageTitle + '</div>');
        }
        
        // Append elements
        lightboxContent.append(lightboxClose, lightboxImage, lightboxCaption);
        lightbox.append(lightboxContent);
        $('body').append(lightbox);
        
        // Add styles
        $('<style>')
            .prop('type', 'text/css')
            .html('\
                .lightbox-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 9999; display: flex; align-items: center; justify-content: center; }\
                .lightbox-content { position: relative; max-width: 90%; max-height: 90%; }\
                .lightbox-content img { max-width: 100%; max-height: 90vh; display: block; }\
                .lightbox-close { position: absolute; top: -40px; right: 0; color: #fff; background: transparent; border: none; font-size: 30px; cursor: pointer; }\
                .lightbox-caption { position: absolute; bottom: -30px; left: 0; color: #fff; padding: 5px; }\
            ')
            .appendTo('head');
        
        // Close on button click or overlay click
        lightboxClose.add(lightbox).on('click', function() {
            lightbox.remove();
        });
        
        // Prevent closing when clicking on the image
        lightboxContent.on('click', function(e) {
            e.stopPropagation();
        });
        
        // Close on ESC key
        $(document).on('keydown.lightbox', function(e) {
            if (e.keyCode === 27) { // ESC key
                lightbox.remove();
                $(document).off('keydown.lightbox');
            }
        });
    });
    
    // Handle tab navigation from URL
    var hash = window.location.hash;
    if (hash) {
        var tab = hash.replace('#', '');
        // Check if this is a valid tab
        var $tab = $('#' + tab + '-tab');
        if ($tab.length) {
            $tab.tab('show');
        }
    }
    
    // Update URL when tab changes
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        var id = $(e.target).attr('aria-controls');
        window.location.hash = id;
    });
});
</script>

<script>
jQuery(document).ready(function($) {
    // Handle booking form submission
    $('#tour-booking-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $submitButton = $form.find('button[type="submit"]');
        var $response = $('.booking-response');
        
        // Disable submit button and show loading state
        $submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <?php esc_html_e('Processing...', 'bike-theme'); ?>');
        
        // Clear previous messages
        $response.empty();
        
        // Get form data
        var formData = new FormData(this);
        
        // Add price information if flexible pricing is enabled
        if ($('#price-per-person').length) {
            formData.append('price_per_person', $('#price-per-person').text().replace(/[^0-9]/g, ''));
            formData.append('total_price', $('#total-price').text().replace(/[^0-9]/g, ''));
        }
        
        // Send Ajax request
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Show success message
                    $response.html('<div class="alert alert-success">' + response.data.message + '</div>');
                    
                    // Reset form
                    $form[0].reset();
                    
                    // Reset price display if flexible pricing is enabled
                    if ($('#price-per-person').length) {
                        updatePriceDisplay();
                    }
                } else {
                    // Show error message
                    $response.html('<div class="alert alert-danger">' + response.data.message + '</div>');
                }
            },
            error: function() {
                // Show error message
                $response.html('<div class="alert alert-danger"><?php esc_html_e('An error occurred. Please try again.', 'bike-theme'); ?></div>');
            },
            complete: function() {
                // Re-enable submit button
                $submitButton.prop('disabled', false).html('<?php esc_html_e('Book Now', 'bike-theme'); ?>');
            }
        });
    });
});
</script>

<script>
jQuery(document).ready(function($) {
    function formatNumber(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    function updatePriceSummary() {
        var participants = parseInt($('#participants').val());
        var tourPricePerPerson = <?php echo bike_theme_get_tour_price(get_the_ID()); ?>;
        var tourSubtotal = tourPricePerPerson * participants;
        var additionsTotal = 0;
        var additionsList = [];

        // Calculate additions total
        $('.addition-checkbox:checked').each(function() {
            var price = parseFloat($(this).data('price'));
            var perPerson = $(this).data('per-person') === 1;
            var additionTotal = perPerson ? price * participants : price;
            additionsTotal += additionTotal;
            
            additionsList.push(
                '<div class="d-flex justify-content-between mb-1">' +
                '<small>' + $(this).next('label').text().split('(')[0].trim() + '</small>' +
                '<small>' + formatNumber(additionTotal) + ' VND</small>' +
                '</div>'
            );
        });

        // Update display
        $('#tour-price-per-person').text(formatNumber(tourPricePerPerson) + ' VND');
        $('#participant-count').text(participants);
        $('#tour-subtotal').text(formatNumber(tourSubtotal) + ' VND');
        
        if (additionsList.length > 0) {
            $('#additions-summary').show();
            $('.additions-list').html(additionsList.join(''));
            $('#additions-subtotal').text(formatNumber(additionsTotal) + ' VND');
        } else {
            $('#additions-summary').hide();
        }

        $('#total-price').text(formatNumber(tourSubtotal + additionsTotal) + ' VND');
    }

    // Update price when participants change or additions are selected
    $('#participants').change(updatePriceSummary);
    $('.addition-checkbox').change(updatePriceSummary);

    // Initial price update
    updatePriceSummary();
});
</script>

<style>
.additions-options {
    max-height: 200px;
    overflow-y: auto;
    padding-right: 10px;
}

.additions-options::-webkit-scrollbar {
    width: 6px;
}

.additions-options::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.additions-options::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.additions-options::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.price-summary {
    background: #f8f9fa;
}

.additions-list {
    padding: 10px;
    background: #fff;
    border-radius: 4px;
    margin: 10px 0;
}
</style>

<?php
get_footer();
?> 