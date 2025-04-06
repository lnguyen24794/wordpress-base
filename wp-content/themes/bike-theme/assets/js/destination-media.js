jQuery(document).ready(function($) {
    // Handle image upload
    $('.destination_tax_media_button').click(function(e) {
        e.preventDefault();
        var button = $(this);
        var custom_uploader = wp.media({
            title: 'Choose Destination Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        }).on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#destination_image').val(attachment.id);
            $('#destination-image-wrapper').html('<img src="' + attachment.url + '" style="max-width:100%; height:auto; margin:10px 0;">');
        }).open();
    });

    // Handle image removal
    $('.destination_tax_media_remove').click(function(e) {
        e.preventDefault();
        $('#destination_image').val('');
        $('#destination-image-wrapper').html('');
    });
}); 