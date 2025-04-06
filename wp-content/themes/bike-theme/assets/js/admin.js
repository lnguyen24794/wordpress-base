/**
 * Admin JS for Bike Theme
 * Handles media uploads and slide management
 */
jQuery(document).ready(function($) {
    // Media uploader
    var mediaUploader;
    
    // Handle the media upload
    $('.bike-media-upload-btn').on('click', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var uploadContainer = button.closest('.bike-media-upload');
        var idInput = uploadContainer.find('.bike-media-id');
        var urlInput = uploadContainer.find('.bike-media-url');
        var preview = uploadContainer.find('.bike-media-preview');
        var removeButton = uploadContainer.find('.bike-media-remove-btn');
        
        // If the media uploader already exists, open it
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        
        // Create the media uploader
        mediaUploader = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });
        
        // When an image is selected
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            
            // Update the ID input
            idInput.val(attachment.id);
            
            // Update the URL input
            urlInput.val(attachment.url);
            
            // Update the preview
            preview.html('<img src="' + attachment.url + '" alt="">');
            
            // Show the remove button
            removeButton.removeClass('hidden');
        });
        
        // Open the uploader
        mediaUploader.open();
    });
    
    // Handle removing the image
    $('.bike-media-remove-btn').on('click', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var uploadContainer = button.closest('.bike-media-upload');
        var idInput = uploadContainer.find('.bike-media-id');
        var urlInput = uploadContainer.find('.bike-media-url');
        var preview = uploadContainer.find('.bike-media-preview');
        
        // Clear the inputs
        idInput.val('');
        urlInput.val('');
        
        // Clear the preview
        preview.html('');
        
        // Hide the remove button
        button.addClass('hidden');
    });
    
    // Counter for new slides
    var heroSlideCounter = $('#bike-slides-container .bike-slide-item').length;
    var aboutSlideCounter = $('#bike-about-slides-container .bike-slide-item').length;
    
    // Add new hero slide
    $('#add-slide-button').on('click', function(e) {
        e.preventDefault();
        
        // Get the template
        var template = $('#slide-template').html();
        
        // Replace the placeholders
        template = template
            .replace(/{{index}}/g, heroSlideCounter)
            .replace(/{{number}}/g, heroSlideCounter + 1);
        
        // Add the new slide
        $('#bike-slides-container').append(template);
        
        // Initialize the media uploader for the new slide
        initializeMediaUploader($('#bike-slides-container .bike-slide-item').last());
        
        // Increment the counter
        heroSlideCounter++;
    });

    // Add new about slide
    $('#add-about-slide-button').on('click', function(e) {
        e.preventDefault();
        
        // Get the template
        var template = $('#about-slide-template').html();
        
        // Replace the placeholders
        template = template
            .replace(/{{index}}/g, aboutSlideCounter)
            .replace(/{{number}}/g, aboutSlideCounter + 1);
        
        // Add the new slide
        $('#bike-about-slides-container').append(template);
        
        // Initialize the media uploader for the new slide
        initializeMediaUploader($('#bike-about-slides-container .bike-slide-item').last());
        
        // Increment the counter
        aboutSlideCounter++;
    });
    
    // Toggle slide content
    $('.bike-slide-item').on('click', '.slide-toggle', function(e) {
        e.preventDefault();
        $(this).closest('.bike-slide-item').find('.slide-content').slideToggle();
    });
    
    // Mark slide for removal
    $('.bike-slide-item').on('click', '.slide-remove', function(e) {
        e.preventDefault();
        
        if (confirm('Are you sure you want to remove this slide?')) {
            var slideItem = $(this).closest('.bike-slide-item');
            slideItem.find('.slide-delete-field').val('yes');
            slideItem.slideUp();
        }
    });
    
    // Initialize media uploader for existing slides
    function initializeMediaUploader(container) {
        container.find('.bike-media-upload-btn').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var uploadContainer = button.closest('.bike-media-upload');
            var idInput = uploadContainer.find('.bike-media-id');
            var urlInput = uploadContainer.find('.bike-media-url');
            var preview = uploadContainer.find('.bike-media-preview');
            var removeButton = uploadContainer.find('.bike-media-remove-btn');
            
            // Create new media uploader instance
            var slideMediaUploader = wp.media({
                title: 'Choose Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false
            });
            
            // When an image is selected
            slideMediaUploader.on('select', function() {
                var attachment = slideMediaUploader.state().get('selection').first().toJSON();
                
                // Update the ID input
                idInput.val(attachment.id);
                
                // Update the URL input
                urlInput.val(attachment.url);
                
                // Update the preview
                preview.html('<img src="' + attachment.url + '" alt="">');
                
                // Show the remove button
                removeButton.removeClass('hidden');
            });
            
            // Open the uploader
            slideMediaUploader.open();
        });
        
        container.find('.bike-media-remove-btn').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var uploadContainer = button.closest('.bike-media-upload');
            var idInput = uploadContainer.find('.bike-media-id');
            var urlInput = uploadContainer.find('.bike-media-url');
            var preview = uploadContainer.find('.bike-media-preview');
            
            // Clear the inputs
            idInput.val('');
            urlInput.val('');
            
            // Clear the preview
            preview.html('');
            
            // Hide the remove button
            button.addClass('hidden');
        });
    }
    
    // Initialize all slides
    $('.bike-slide-item').each(function() {
        initializeMediaUploader($(this));
    });
    
    // Optional: Add sortable functionality for slides
    if ($.fn.sortable) {
        $('#bike-slides-container, #bike-about-slides-container').sortable({
            handle: 'h3',
            update: function(event, ui) {
                // Update slide numbers after sorting
                $(this).find('.bike-slide-item').each(function(index) {
                    $(this).find('.slide-number').text(index + 1);
                });
            }
        });
    }
}); 