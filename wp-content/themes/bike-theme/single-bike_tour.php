<?php
/**
 * The template for displaying single tour
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Bike_Theme
 */

get_header();

// Enqueue the tour single CSS
wp_enqueue_style('bike-theme-tour-single', get_template_directory_uri() . '/assets/css/tour-single.css', array(), '1.0.0');

// Get tour meta data
$duration = get_post_meta(get_the_ID(), '_tour_duration', true);
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
                <p class="text-white">
                    From about <?php if (get_post_meta(get_the_ID(), '_tour_flexible_pricing_enabled', true) === '1') :
                        $pricing_data = get_post_meta(get_the_ID(), '_tour_flexible_pricing', true);
                        if (!empty($pricing_data) && is_array($pricing_data)) :
                            usort($pricing_data, function ($a, $b) {
                                return $a['participants'] - $b['participants'];
                            });
                            ?>
                    <span id="tour-price-display" class="text-primary"><?php echo bike_theme_format_price(bike_theme_get_tour_price(get_the_ID())); ?></span> <span><?php esc_html_e('per person', 'bike-theme'); ?></span>
                    <?php else : ?>
                    <span><?php echo bike_theme_format_price(bike_theme_get_tour_price(get_the_ID())); ?></span> <span><?php esc_html_e('per person', 'bike-theme'); ?></span>
                    <?php endif; ?>
                    <?php else : ?>
                    <span><?php echo bike_theme_format_price(bike_theme_get_tour_price(get_the_ID())); ?></span> <span><?php esc_html_e('per person', 'bike-theme'); ?></span>
                    <?php endif; ?>
                </p>
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
                                <h5 class="mb-1"><?php esc_html_e('Route', 'bike-theme'); ?></h5>
                                <span><?php echo esc_html($start_location); ?> - <?php echo esc_html($end_location); ?></span>
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
                                <span><?php echo esc_html($duration); ?> <?php esc_html_e('days', 'bike-theme'); ?></span>
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
                    <div class="col">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 btn-square bg-primary rounded-circle">
                                <i class="fa fa-users text-white"></i>
                            </div>
                            <div class="ms-3 tour-basic-info-title">
                                <h5 class="mb-1"><?php esc_html_e('Group Size', 'bike-theme'); ?></h5>
                                <span><?php esc_html_e('Maximum', 'bike-theme'); ?> <?php echo esc_html($max_participants); ?> <?php esc_html_e('participants', 'bike-theme'); ?></span>
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
                                <div class="tour-overview">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                            
                            <!-- Itinerary Tab -->
                            <div class="tab-pane fade <?php echo $active_tab === 'itinerary' ? 'show active' : ''; ?>" id="itinerary" role="tabpanel" aria-labelledby="itinerary-tab">
                                <div class="tour-itinerary">
                                    <?php if (!empty($itinerary)) : ?>
                                        <?php echo wp_kses_post($itinerary); ?>
                                    <?php else : ?>
                                        <div class="alert alert-info">
                                        <?php esc_html_e('Detailed itinerary information is not available at this moment. Please contact us for more details.', 'bike-theme'); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Price & Services Tab -->
                            <div class="tab-pane fade <?php echo $active_tab === 'inclusions' ? 'show active' : ''; ?>" id="inclusions" role="tabpanel" aria-labelledby="inclusions-tab">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <h4><?php esc_html_e('What\'s Included', 'bike-theme'); ?></h4>
                                        <?php if (!empty($included)) : ?>
                                            <div class="included-services">
                                                <?php echo wp_kses_post($included); ?>
                                            </div>
                                        <?php else : ?>
                                            <p><?php esc_html_e('Please contact us for details about what is included in this tour.', 'bike-theme'); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <h4><?php esc_html_e('What\'s Not Included', 'bike-theme'); ?></h4>
                                        <?php if (!empty($not_included)) : ?>
                                            <div class="not-included-services">
                                                <?php echo wp_kses_post($not_included); ?>
                                            </div>
                                        <?php else : ?>
                                            <p><?php esc_html_e('Please contact us for details about what is not included in this tour.', 'bike-theme'); ?></p>
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
                            </div>
                            
                            <!-- Booking & Cancellation Tab -->
                            <div class="tab-pane fade <?php echo $active_tab === 'booking' ? 'show active' : ''; ?>" id="booking" role="tabpanel" aria-labelledby="booking-tab">
                                <div class="booking-cancellation">
                                    <div class="mb-4">
                                        <h4><?php esc_html_e('Booking Terms', 'bike-theme'); ?></h4>
                                        <?php if (!empty($booking_terms)) : ?>
                                            <?php echo wp_kses_post($booking_terms); ?>
                                        <?php else : ?>
                                            <p><?php esc_html_e('Please contact us for detailed information about booking terms and conditions.', 'bike-theme'); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="mt-4">
                                        <h4><?php esc_html_e('Cancellation Policy', 'bike-theme'); ?></h4>
                                        <?php if (!empty($cancellation_policy)) : ?>
                                            <?php echo wp_kses_post($cancellation_policy); ?>
                                        <?php else : ?>
                                            <p><?php esc_html_e('Please contact us for detailed information about our cancellation policy.', 'bike-theme'); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Gallery Tab -->
                            <div class="tab-pane fade <?php echo $active_tab === 'gallery' ? 'show active' : ''; ?>" id="gallery" role="tabpanel" aria-labelledby="gallery-tab">
                                <div class="tour-media">
                                    <?php if (!empty($gallery_ids)) :
                                        $gallery_ids_array = explode(',', $gallery_ids);
                                        ?>
                                        <div class="row g-3 gallery-container">
                                            <?php foreach ($gallery_ids_array as $image_id) :
                                                if (!empty($image_id)) :
                                                    $full_image_url = wp_get_attachment_image_url($image_id, 'full');
                                                    $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                                                    if ($full_image_url) :
                                                        ?>
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="gallery-item">
                                                        <a href="<?php echo esc_url($full_image_url); ?>" class="gallery-lightbox">
                                                            <?php echo wp_get_attachment_image($image_id, 'large', false, array('class' => 'img-fluid rounded')); ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php
                                                    endif;
                                                endif;
                                            endforeach;
                                        ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($video_url)) : ?>
                                        <div class="tour-video mt-4">
                                            <h4><?php esc_html_e('Tour Video', 'bike-theme'); ?></h4>
                                            <div class="ratio ratio-16x9 mt-3">
                                                <?php
                                            // Extract video ID and platform
                                            $video_embed_url = '';
                                        if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
                                            // YouTube
                                            preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video_url, $matches);
                                            if (!empty($matches[1])) {
                                                $video_embed_url = 'https://www.youtube.com/embed/' . $matches[1];
                                            }
                                        } elseif (strpos($video_url, 'vimeo.com') !== false) {
                                            // Vimeo
                                            preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]*)\/videos\/|album\/(?:\d+)\/video\/|video\/|)(\d+)(?:[a-zA-Z0-9_-]+)?/', $video_url, $matches);
                                            if (!empty($matches[1])) {
                                                $video_embed_url = 'https://player.vimeo.com/video/' . $matches[1];
                                            }
                                        }

if (!empty($video_embed_url)) :
    ?>
                                                <iframe src="<?php echo esc_url($video_embed_url); ?>" title="<?php echo esc_attr(get_the_title()); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                <?php else : ?>
                                                <div class="alert alert-info">
                                                    <?php esc_html_e('The video URL is not valid. Please provide a valid YouTube or Vimeo URL.', 'bike-theme'); ?>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (empty($gallery_ids) && empty($video_url)) : ?>
                                        <div class="alert alert-info">
                                            <?php esc_html_e('No gallery images or videos are available for this tour.', 'bike-theme'); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Reviews Tab -->
                            <div class="tab-pane fade <?php echo $active_tab === 'reviews' ? 'show active' : ''; ?>" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                <div class="tour-reviews">
                                    <?php if (comments_open() || get_comments_number()) : ?>
                                        <?php comments_template(); ?>
                                    <?php else : ?>
                                        <div class="alert alert-info">
                                            <?php esc_html_e('No reviews yet. Be the first to review this tour!', 'bike-theme'); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Contact Tab -->
                            <div class="tab-pane fade <?php echo $active_tab === 'contact' ? 'show active' : ''; ?>" id="contact" role="tabpanel" aria-labelledby="contact-tab">
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
                        <form action="<?php echo esc_url(get_permalink(get_option('bike_theme_booking_page'))); ?>" method="get">
                            <div class="row g-3">
                                <input type="hidden" name="tour" value="<?php echo get_the_ID(); ?>">
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
                                        <input type="date" class="form-control" id="date" name="date" required>
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
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="<?php esc_attr_e('Special Request', 'bike-theme'); ?>" id="message" name="message" style="height: 100px"></textarea>
                                        <label for="message"><?php esc_html_e('Special Request', 'bike-theme'); ?></label>
                                    </div>
                                </div>
                                <?php if (get_post_meta(get_the_ID(), '_tour_flexible_pricing_enabled', true) === '1') : ?>
                                <div class="col-12 mt-1">
                                    <div class="pricing-summary p-3 bg-white rounded border">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><?php esc_html_e('Price per person:', 'bike-theme'); ?></span>
                                            <span id="price-per-person"><?php echo bike_theme_format_price(bike_theme_get_tour_price(get_the_ID())); ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><?php esc_html_e('Participants:', 'bike-theme'); ?></span>
                                            <span id="participant-count">1</span>
                                        </div>
                                        <div class="d-flex justify-content-between fw-bold">
                                            <span><?php esc_html_e('Total:', 'bike-theme'); ?></span>
                                            <span id="total-price"><?php echo bike_theme_format_price(bike_theme_get_tour_total_price(get_the_ID(), 1)); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                              
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

<?php
get_footer();
?> 