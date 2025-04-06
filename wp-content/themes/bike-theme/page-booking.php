<?php
/**
 * Template Name: Booking Page
 *
 * @package Bike_Theme
 */

get_header();

// Get the selected tour or bike from URL, if any
$tour_id = isset($_GET['tour']) ? intval($_GET['tour']) : 0;
$bike_id = isset($_GET['bike']) ? intval($_GET['bike']) : 0;

// Get form data if passed via URL
$name = isset($_GET['name']) ? sanitize_text_field($_GET['name']) : '';
$email = isset($_GET['email']) ? sanitize_email($_GET['email']) : '';
$phone = isset($_GET['phone']) ? sanitize_text_field($_GET['phone']) : '';
$date = isset($_GET['date']) ? sanitize_text_field($_GET['date']) : '';
$participants = isset($_GET['participants']) ? intval($_GET['participants']) : 1;
$message = isset($_GET['message']) ? sanitize_textarea_field($_GET['message']) : '';
?>

<main id="primary" class="site-main">

    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 p-0" style="background-image: url(<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/bikes/booking-header.jpg);">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center pb-5">
                <h1 class="display-3 text-white mb-3 animated slideInDown"><?php esc_html_e('Book Your Adventure', 'bike-theme'); ?></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center text-uppercase">
                        <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'bike-theme'); ?></a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page"><?php esc_html_e('Booking', 'bike-theme'); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Booking Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title text-center text-primary text-uppercase"><?php esc_html_e('Booking', 'bike-theme'); ?></h6>
                <h1 class="mb-5"><?php echo wp_kses_post(__('Book A <span class="text-primary text-uppercase">Tour or Bike</span>', 'bike-theme')); ?></h1>
            </div>
            <div class="row g-5">
                <div class="col-lg-6">
                    <div class="wow fadeInUp" data-wow-delay="0.2s">
                        <form id="booking-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                            <input type="hidden" name="action" value="bike_theme_submit_booking">
                            <?php wp_nonce_field('bike_theme_booking_nonce', 'booking_nonce'); ?>   
                            <!-- Booking Form Start -->
                            <div class="row g-3">
                                <!-- Name start -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="name" name="name" value="<?php echo esc_attr($name); ?>" placeholder="<?php esc_attr_e('Your Name', 'bike-theme'); ?>" required>
                                        <label for="name"><?php esc_html_e('Your Name', 'bike-theme'); ?></label>
                                    </div>
                                </div>
                                <!-- Name end -->
                                <!-- Email start -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo esc_attr($email); ?>" placeholder="<?php esc_attr_e('Your Email', 'bike-theme'); ?>" required>
                                        <label for="email"><?php esc_html_e('Your Email', 'bike-theme'); ?></label>
                                    </div>
                                </div>
                                <!-- Email end -->
                                <!-- Phone start -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo esc_attr($phone); ?>" placeholder="<?php esc_attr_e('Your Phone', 'bike-theme'); ?>" required>
                                        <label for="phone"><?php esc_html_e('Your Phone', 'bike-theme'); ?></label>
                                    </div>
                                </div>
                                <!-- Phone end -->
                                <!-- Booking Date start -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="date" class="form-control" id="date" name="date" value="<?php echo esc_attr($date); ?>" required>
                                        <label for="date"><?php esc_html_e('Booking Date', 'bike-theme'); ?></label>
                                    </div>
                                </div>
                                <!-- Booking Date end -->
                                <!-- Tour start -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="tour" name="tour">
                                            <option value=""><?php esc_html_e('Select a Tour (Optional)', 'bike-theme'); ?></option>
                                            <?php
                                            $tours = get_posts(array(
                                                'post_type' => 'bike_tour',
                                                'posts_per_page' => -1,
                                                'orderby' => 'title',
                                                'order' => 'ASC'
                                            ));
                                            foreach ($tours as $tour) {
                                                $selected = ($tour_id == $tour->ID) ? 'selected="selected"' : '';
                                                echo '<option value="' . esc_attr($tour->ID) . '" ' . $selected . '>' . esc_html($tour->post_title) . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <label for="tour"><?php esc_html_e('Tour', 'bike-theme'); ?></label>
                                    </div>
                                </div>
                                <!-- Tour end -->
                                <!-- Participants start -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="participants" name="participants">
                                            <?php for ($i = 1; $i <= 10; $i++) : ?>
                                                <option value="<?php echo esc_attr($i); ?>" <?php selected($participants, $i); ?>><?php echo esc_html($i); ?></option>
                                            <?php endfor; ?>
                                        </select>
                                        <label for="participants"><?php esc_html_e('Number of Participants', 'bike-theme'); ?></label>
                                    </div>
                                </div>
                                <!-- After the participants field -->
                                <!-- Tour Additions Container -->
                                <div id="tour-additions-container" class="col-12" style="display: none;">
                                    <div class="form-group">
                                        <h5 class="mb-3"><?php esc_html_e('Optional Extras', 'bike-theme'); ?></h5>
                                        <div id="additions-options" class="additions-options">
                                            <!-- Will be populated via JavaScript -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Price Summary Container -->
                                <div id="price-summary-container" class="col-12 mt-3" style="display: none;">
                                    <div class="price-summary bg-white p-3 rounded border">
                                        <h5 class="mb-3"><?php esc_html_e('Price Summary', 'bike-theme'); ?></h5>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><?php esc_html_e('Tour price per person:', 'bike-theme'); ?></span>
                                            <span id="tour-price-per-person">0 VND</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><?php esc_html_e('Number of participants:', 'bike-theme'); ?></span>
                                            <span id="participant-count">1</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span><?php esc_html_e('Tour subtotal:', 'bike-theme'); ?></span>
                                            <span id="tour-subtotal">0 VND</span>
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
                                            <span id="total-price">0 VND</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Participants end -->
                                <!-- Payment Method start -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="payment_method" name="payment_method">
                                            <option value="cash"><?php esc_html_e('Cash on Arrival', 'bike-theme'); ?></option>
                                            <option value="bank_transfer"><?php esc_html_e('Bank Transfer', 'bike-theme'); ?></option>
                                            <option value="paypal"><?php esc_html_e('PayPal', 'bike-theme'); ?></option>
                                        </select>
                                        <label for="payment_method"><?php esc_html_e('Payment Method', 'bike-theme'); ?></label>
                                    </div>
                                </div>
                                <!-- Payment Method end -->
                                <!-- Special Request start -->
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" placeholder="<?php esc_attr_e('Special Request', 'bike-theme'); ?>" id="message" name="message" style="height: 100px"><?php echo esc_textarea($message); ?></textarea>
                                        <label for="message"><?php esc_html_e('Special Request', 'bike-theme'); ?></label>
                                    </div>
                                </div>
                                <!-- Special Request end -->
                                <!-- Terms and Conditions start -->
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            <?php esc_html_e('I agree to the terms and conditions', 'bike-theme'); ?>
                                        </label>
                                    </div>
                                </div>
                                <!-- Terms and Conditions end -->
                                <!-- Book Now start -->
                                <div class="col-12">
                                    <button class="btn btn-primary w-100 py-3" type="submit"><?php esc_html_e('Book Now', 'bike-theme'); ?></button>
                                </div>
                                <!-- Book Now end -->
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="bg-light rounded p-5 wow zoomIn" data-wow-delay="0.9s">
                                <h4 class="mb-4"><?php esc_html_e('Why Book With Us?', 'bike-theme'); ?></h4>
                                <div class="d-flex mb-3">
                                    <span class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 40px; height: 40px;">
                                        <i class="fa fa-check"></i>
                                    </span>
                                    <div class="ms-3">
                                        <h6 class="text-primary mb-1"><?php esc_html_e('Expert Guides', 'bike-theme'); ?></h6>
                                        <p class="mb-0"><?php esc_html_e('Our professional guides know the best routes and share local knowledge', 'bike-theme'); ?></p>
                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <span class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 40px; height: 40px;">
                                        <i class="fa fa-check"></i>
                                    </span>
                                    <div class="ms-3">
                                        <h6 class="text-primary mb-1"><?php esc_html_e('Quality Equipment', 'bike-theme'); ?></h6>
                                        <p class="mb-0"><?php esc_html_e('We provide well-maintained bicycles and safety gear for all ages', 'bike-theme'); ?></p>
                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <span class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 40px; height: 40px;">
                                        <i class="fa fa-check"></i>
                                    </span>
                                    <div class="ms-3">
                                        <h6 class="text-primary mb-1"><?php esc_html_e('Flexible Options', 'bike-theme'); ?></h6>
                                        <p class="mb-0"><?php esc_html_e('Customize your tour experience to match your preferences', 'bike-theme'); ?></p>
                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <span class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle" style="width: 40px; height: 40px;">
                                        <i class="fa fa-check"></i>
                                    </span>
                                    <div class="ms-3">
                                        <h6 class="text-primary mb-1"><?php esc_html_e('24/7 Support', 'bike-theme'); ?></h6>
                                        <p class="mb-0"><?php esc_html_e('Contact us anytime for assistance before, during, or after your tour', 'bike-theme'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Booking End -->

    <!-- Booking Process Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                <h6 class="section-title text-center text-primary text-uppercase"><?php esc_html_e('How to Book', 'bike-theme'); ?></h6>
                <h1 class="mb-5"><?php esc_html_e('Easy Booking Process', 'bike-theme'); ?></h1>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="booking-process-item rounded pt-3">
                        <div class="p-4 text-center">
                            <i class="fa fa-3x fa-search text-primary mb-4"></i>
                            <h5><?php esc_html_e('1. Choose Tour or Bike', 'bike-theme'); ?></h5>
                            <p><?php esc_html_e('Browse our tours and bikes and select what suits your needs.', 'bike-theme'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                    <div class="booking-process-item rounded pt-3">
                        <div class="p-4 text-center">
                            <i class="fa fa-3x fa-edit text-primary mb-4"></i>
                            <h5><?php esc_html_e('2. Fill Booking Form', 'bike-theme'); ?></h5>
                            <p><?php esc_html_e('Complete the booking form with your details and preferences.', 'bike-theme'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                    <div class="booking-process-item rounded pt-3">
                        <div class="p-4 text-center">
                            <i class="fa fa-3x fa-credit-card text-primary mb-4"></i>
                            <h5><?php esc_html_e('3. Confirm and Pay', 'bike-theme'); ?></h5>
                            <p><?php esc_html_e('We\'ll contact you for confirmation and payment arrangements.', 'bike-theme'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.7s">
                    <div class="booking-process-item rounded pt-3">
                        <div class="p-4 text-center">
                            <i class="fa fa-3x fa-bicycle text-primary mb-4"></i>
                            <h5><?php esc_html_e('4. Enjoy Your Trip', 'bike-theme'); ?></h5>
                            <p><?php esc_html_e('Get ready for an amazing cycling experience in Vietnam!', 'bike-theme'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Booking Process End -->

</main><!-- #main -->

<script>
jQuery(document).ready(function($) {
    // Get tour pricing data for JavaScript
    var tourPricingData = {};
    
    <?php
    // Get pricing data for all tours
    $all_tours = get_posts(array(
        'post_type' => 'bike_tour',
        'posts_per_page' => -1,
        'fields' => 'ids'
    ));

foreach ($all_tours as $tour_id) {
    $flexible_pricing_enabled = get_post_meta($tour_id, '_tour_flexible_pricing_enabled', true) === '1';
    $pricing_data = array();

    if ($flexible_pricing_enabled) {
        $pricing_data = get_post_meta($tour_id, '_tour_flexible_pricing', true);
        if (empty($pricing_data) || !is_array($pricing_data)) {
            $pricing_data = array(
                array('participants' => 1, 'price' => get_post_meta($tour_id, '_tour_price', true))
            );
        }
    } else {
        $pricing_data = array(
            array('participants' => 1, 'price' => get_post_meta($tour_id, '_tour_price', true))
        );
    }

    echo 'tourPricingData[' . $tour_id . '] = ' . json_encode($pricing_data) . ';';
}
?>
    
    // Format number with thousand separator
    function formatNumber(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    // Get price per person based on participants
    function getPricePerPerson(tourId, participants) {
        if (!tourId || !tourPricingData[tourId]) {
            return 0;
        }
        
        var pricingData = tourPricingData[tourId];
        var applicablePrice = null;
        
        // Sort pricing data by number of participants (ascending)
        pricingData.sort(function(a, b) {
            return a.participants - b.participants;
        });
        
        // Find applicable price
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
        
        return applicablePrice || 0;
    }
    
    // Update price display
    function updatePriceDisplay() {
        var tourId = $('#tour').val();
        var participants = parseInt($('#participants').val(), 10);
        
        if (!tourId) {
            $('.price-summary-container').hide();
            return;
        }
        
        var pricePerPerson = getPricePerPerson(tourId, participants);
        var totalPrice = pricePerPerson * participants;
        
        $('#price-per-person').text(formatNumber(pricePerPerson) + ' $');
        $('#participant-count').text(participants);
        $('#total-price').text(formatNumber(totalPrice) + ' $');
        
        $('.price-summary-container').show();
    }
    
    // Initial update
    updatePriceDisplay();
    
    // Update when selections change
    $('#tour, #participants').change(function() {
        updatePriceDisplay();
    });

    // Get tour additions data for JavaScript
    var tourAdditionsData = {};
    
    <?php
    // Get additions data for all tours
    $all_tours = get_posts(array(
        'post_type' => 'bike_tour',
        'posts_per_page' => -1,
        'fields' => 'ids'
    ));

    foreach ($all_tours as $tour_id) {
        $additions = bike_theme_get_tour_additions($tour_id);
        if (!empty($additions)) {
            echo 'tourAdditionsData[' . $tour_id . '] = ' . json_encode($additions) . ';';
        }
    }
    ?>
    
    function updateAdditionsOptions() {
        var tourId = $('#tour').val();
        var $container = $('#tour-additions-container');
        var $options = $('#additions-options');
        
        $options.empty();
        
        if (tourId && tourAdditionsData[tourId]) {
            var additions = tourAdditionsData[tourId];
            additions.forEach(function(addition, index) {
                var html = '<div class="form-check mb-3">' +
                    '<input class="form-check-input addition-checkbox" type="checkbox" ' +
                    'name="additions[]" value="' + addition.name + '" ' +
                    'id="addition_' + index + '" ' +
                    'data-price="' + addition.price + '" ' +
                    'data-per-person="' + (addition.per_person ? '1' : '0') + '">' +
                    '<label class="form-check-label" for="addition_' + index + '">' +
                    '<strong>' + addition.name + '</strong> - ' + formatNumber(addition.price) + ' VND' +
                    (addition.per_person ? ' per person' : '');
                
                if (addition.description) {
                    html += '<small>' + addition.description + '</small>';
                }
                
                html += '</label></div>';
                
                $options.append(html);
            });
            
            $container.slideDown();
            $('#price-summary-container').slideDown();
        } else {
            $container.slideUp();
            $('#price-summary-container').slideUp();
        }
    }
    
    function updatePriceSummary() {
        var tourId = $('#tour').val();
        if (!tourId) return;
        
        var participants = parseInt($('#participants').val());
        var tourPricePerPerson = getPricePerPerson(tourId, participants);
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
                '<small>' + $(this).next('label').find('strong').text() + '</small>' +
                '<small>' + formatNumber(additionTotal) + ' VND</small>' +
                '</div>'
            );
        });
        
        // Update display with animation
        $('#tour-price-per-person').fadeOut(200, function() {
            $(this).text(formatNumber(tourPricePerPerson) + ' VND').fadeIn(200);
        });
        $('#participant-count').fadeOut(200, function() {
            $(this).text(participants).fadeIn(200);
        });
        $('#tour-subtotal').fadeOut(200, function() {
            $(this).text(formatNumber(tourSubtotal) + ' VND').fadeIn(200);
        });
        
        if (additionsList.length > 0) {
            $('.additions-list').html(additionsList.join(''));
            $('#additions-subtotal').text(formatNumber(additionsTotal) + ' VND');
            $('#additions-summary').slideDown();
        } else {
            $('#additions-summary').slideUp();
        }
        
        $('#total-price').fadeOut(200, function() {
            $(this).text(formatNumber(tourSubtotal + additionsTotal) + ' VND').fadeIn(200);
        });
    }
    
    // Update when tour selection changes
    $('#tour').change(function() {
        updateAdditionsOptions();
        updatePriceSummary();
    });
    
    // Update when participants change or additions are selected
    $('#participants').change(updatePriceSummary);
    $(document).on('change', '.addition-checkbox', updatePriceSummary);
    
    // Initial update if tour is pre-selected
    if ($('#tour').val()) {
        updateAdditionsOptions();
        updatePriceSummary();
    }
});
</script>

<style>
.additions-options {
    max-height: 200px;
    overflow-y: auto;
    padding-right: 10px;
    margin-bottom: 20px;
    background: #fff;
    border-radius: 8px;
    padding: 15px;
    border: 1px solid #dee2e6;
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

.form-check-label {
    display: block;
    margin-bottom: 5px;
}

.form-check-label small {
    display: block;
    color: #6c757d;
    font-size: 0.875em;
    margin-top: 2px;
}

.price-summary h5 {
    color: #333;
    font-size: 1.1rem;
    margin-bottom: 15px;
}

.price-summary .d-flex {
    padding: 5px 0;
}

.price-summary .border-top {
    margin-top: 10px;
    padding-top: 15px;
}

#additions-summary {
    background: #f8f9fa;
    border-radius: 4px;
    padding: 10px;
    margin: 10px 0;
}

.additions-list small {
    color: #495057;
}
</style>

<?php
get_footer();
?> 