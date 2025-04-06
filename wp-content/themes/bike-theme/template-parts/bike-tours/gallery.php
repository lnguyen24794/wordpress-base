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