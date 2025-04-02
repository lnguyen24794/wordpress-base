<?php
/**
 * Template part for displaying tour content
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('tour-item shadow rounded overflow-hidden'); ?>>
    <div class="position-relative">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('large', array('class' => 'img-fluid')); ?>
        <?php else : ?>
            <img class="img-fluid" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/tours/tour-default.jpg" alt="<?php the_title_attribute(); ?>">
        <?php endif; ?>
        <small class="position-absolute start-0 top-100 translate-middle-y bg-primary text-white rounded py-1 px-3 ms-4">
            <?php echo esc_html(number_format(get_post_meta(get_the_ID(), '_tour_price', true), 2, ',', '.')); ?> $
        </small>
    </div>
    <div class="p-4 mt-2">
        <div class="d-flex justify-content-between mb-3">
            <h5 class="mb-0"><?php the_title(); ?></h5>
            <div class="ps-2">
                <?php
                $difficulty = get_post_meta(get_the_ID(), '_tour_difficulty', true);
$difficulty_class = '';
switch ($difficulty) {
    case 'easy':
        $difficulty_class = 'text-success';
        break;
    case 'moderate':
        $difficulty_class = 'text-warning';
        break;
    case 'difficult':
        $difficulty_class = 'text-danger';
        break;
}
?>
                <small class="<?php echo esc_attr($difficulty_class); ?>">
                    <?php echo esc_html(ucfirst($difficulty)); ?>
                </small>
            </div>
        </div>
        <div class="d-flex mb-3">
            <small class="border-end me-3 pe-3">
                <i class="fa fa-clock text-primary me-2"></i>
                <?php echo esc_html(get_post_meta(get_the_ID(), '_tour_duration', true)); ?> <?php esc_html_e('days', 'bike-theme'); ?>
            </small>
            <small class="border-end me-3 pe-3">
                <i class="fa fa-route text-primary me-2"></i>
                <?php echo esc_html(get_post_meta(get_the_ID(), '_tour_distance', true)); ?> km
            </small>
            <small>
                <i class="fa fa-users text-primary me-2"></i>
                <?php echo esc_html(get_post_meta(get_the_ID(), '_tour_max_participants', true)); ?> <?php esc_html_e('max', 'bike-theme'); ?>
            </small>
        </div>
        <p class="text-body mb-3"><?php echo wp_trim_words(get_the_content(), 20, '...'); ?></p>
        <div class="d-flex justify-content-between">
            <a class="btn btn-sm btn-primary rounded py-2 px-4" href="<?php the_permalink(); ?>"><?php esc_html_e('View Details', 'bike-theme'); ?></a>
            <a class="btn btn-sm btn-dark rounded py-2 px-4" href="<?php echo esc_url(get_permalink(get_option('bike_theme_booking_page'))); ?>?tour=<?php echo get_the_ID(); ?>"><?php esc_html_e('Book Now', 'bike-theme'); ?></a>
        </div>
    </div>
</article> 