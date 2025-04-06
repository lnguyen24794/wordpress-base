jQuery(document).ready(function($) {
    $('#tour-booking-form').on('submit', function(e) {
        e.preventDefault();

        var $form = $(this);
        var $submitButton = $form.find('button[type="submit"]');
        var $responseDiv = $('.booking-response');

        // Disable submit button and show loading state
        $submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + bike_booking.submitting_text);

        // Get form data
        var formData = new FormData(this);
        formData.append('action', 'bike_theme_process_booking');
        formData.append('security', bike_booking.nonce);

        // Send Ajax request
        $.ajax({
            url: bike_booking.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Show success message
                    $responseDiv.html('<div class="alert alert-success">' + response.data.message + '</div>');
                    
                    // Reset form
                    $form[0].reset();
                    
                    // Update price calculation if exists
                    if (typeof updatePriceDisplay === 'function') {
                        updatePriceDisplay();
                    }
                } else {
                    // Show error message
                    var errorHtml = '<div class="alert alert-danger"><ul class="mb-0">';
                    if (Array.isArray(response.data)) {
                        response.data.forEach(function(error) {
                            errorHtml += '<li>' + error + '</li>';
                        });
                    } else {
                        errorHtml += '<li>' + response.data + '</li>';
                    }
                    errorHtml += '</ul></div>';
                    $responseDiv.html(errorHtml);
                }
            },
            error: function() {
                // Show error message
                $responseDiv.html('<div class="alert alert-danger">' + bike_booking.error_message + '</div>');
            },
            complete: function() {
                // Re-enable submit button
                $submitButton.prop('disabled', false).text(bike_booking.submit_text);
            }
        });
    });

    // Update price calculation when participants change
    $('#participants').on('change', function() {
        if (typeof updatePriceDisplay === 'function') {
            updatePriceDisplay();
        }
    });
}); 