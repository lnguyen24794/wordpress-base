<div class="row g-4">
    <?php
        $args = array(
            'post_type' => 'bike',
            'posts_per_page' => 3,
        );

        $bikes_query = new WP_Query($args);

        if ($bikes_query->have_posts()) :
            while ($bikes_query->have_posts()) : $bikes_query->the_post();
                $bike_price = get_post_meta(get_the_ID(), 'bike_price', true);
                $bike_brand = get_post_meta(get_the_ID(), 'bike_brand', true);
                $bike_type = get_post_meta(get_the_ID(), 'bike_type', true);
                $duration = get_post_meta(get_the_ID(), 'bike_duration', true);
            ?>
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="room-item shadow rounded">
                    <div class="position-relative">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('large', array('class' => 'img-fluid')); ?>
                        <?php else : ?>
                            <img class="img-fluid" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/bikes/bike-default.jpg" alt="<?php the_title_attribute(); ?>">
                        <?php endif; ?>
                        <small class="position-absolute start-0 top-100 translate-middle-y bg-primary text-white rounded py-1 px-3 ms-4">
                            <?php echo esc_html(number_format($bike_price, 2, ',', '.')); ?> $<?php echo ($bike_price > 0) ? '/day' : ''; ?>
                        </small>
                    </div>
                    <div class="p-4 mt-2">
                        <div class="bikes-item mb-2">
                            <h5 class="mb-0"><?php the_title(); ?></h5>
                            <div class="ps-2">
                                <?php
                                $rating = get_post_meta(get_the_ID(), 'bike_rating', true);
                                $rating = $rating ? $rating : 5;
                                for ($i = 0; $i < 5; $i++) {
                                    if ($i < $rating) {
                                        echo '<small class="fa fa-star text-primary"></small>';
                                    } else {
                                        echo '<small class="fa fa-star text-secondary"></small>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <?php if ($bike_brand) : ?>
                            <small class="border-end me-3 pe-3"><i class="fa fa-tag text-primary me-2"></i><?php echo esc_html($bike_brand); ?></small>
                            <?php endif; ?>
                            <?php if ($bike_type) : ?>
                            <small><i class="fa fa-bicycle text-primary me-2"></i><?php echo esc_html($bike_type); ?></small>
                            <?php endif; ?>
                            <?php if ($duration) : ?>
                            <span><i class="fa fa-clock me-2"></i><?php echo esc_html(bike_theme_get_tour_duration(get_the_ID())); ?></span>
                            <?php endif; ?>
                        </div>
                        <p class="text-body mb-3"><?php echo wp_trim_words(get_the_content(), 20, '...'); ?></p>
                        <div class="d-flex justify-content-between">
                            <a class="btn btn-sm btn-primary rounded py-2 px-4" href="<?php the_permalink(); ?>"><?php esc_html_e('View Details', 'bike-theme'); ?></a>
                            <a class="btn btn-sm btn-dark rounded py-2 px-4" href="/booking"><?php esc_html_e('Book Now', 'bike-theme'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
    <?php
            endwhile;
wp_reset_postdata();
    ?> <?php endif; ?>
</div>
<div class="text-center mt-5">
    <a href="<?php echo esc_url(get_post_type_archive_link('bike')); ?>" class="btn btn-primary py-3 px-5"><?php esc_html_e('View All Bikes', 'bike-theme'); ?></a>
</div>