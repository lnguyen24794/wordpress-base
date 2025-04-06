<?php
/**
 * The template for displaying bike tour archive
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Bike_Theme
 */

get_header();
?>

<main id="primary" class="site-main">
    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 p-0" style="background-image: url(<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/bikes/tour-banner.jpg);">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center pb-5">
                <h1 class="display-3 text-white mb-3 animated slideInDown"><?php esc_html_e('Our Bike Tours', 'bike-theme'); ?></h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center text-uppercase">
                        <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'bike-theme'); ?></a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page"><?php esc_html_e('Bike Tours', 'bike-theme'); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Tours Start -->
    <div class="container-xxl py-5">
            <!-- Destinations Grid Start -->
            <div class="row g-4">
                <?php
                // Get all destinations
                $destinations = get_terms(array(
                    'taxonomy' => 'destination',
                    'hide_empty' => true,
                    'parent' => 0,
                    'orderby' => 'name',
                    'order' => 'ASC'
                ));

                if (!empty($destinations) && !is_wp_error($destinations)) :
                    foreach ($destinations as $destination) :
                        // Get destination image
                        $image_id = get_term_meta($destination->term_id, 'destination_image', true);
                        $image_url = wp_get_attachment_url($image_id);
                        if (!$image_url) {
                            $image_url = get_template_directory_uri() . '/assets/images/bikes/destination-default.jpg';
                        }

                        // Get tours count
                        $tours_count = $destination->count;

                        // Get category counts for this destination
                        $category_counts = bike_theme_count_tours_by_category_in_destination($destination->term_id);
                ?>
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="destination-folder">
                        <div class="folder-header">
                            <div class="folder-icon">
                                <i class="fas fa-folder-open text-primary"></i>
                            </div>
                            <div class="folder-info">
                                <h4 class="folder-title"><?php echo esc_html($destination->name); ?></h4>
                                <span class="tour-count"><?php printf(esc_html(_n('%s Tour', '%s Tours', $tours_count, 'bike-theme')), number_format_i18n($tours_count)); ?></span>
                            </div>
                        </div>
                        <div class="folder-content">
                            <div class="folder-image">
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($destination->name); ?>" class="img-fluid rounded">
                            </div>
                            <div class="folder-categories">
                                <?php if (!empty($category_counts)) : ?>
                                    <?php foreach ($category_counts as $cat_id => $data) : ?>
                                        <div class="category-badge">
                                            <span class="badge bg-primary">
                                                <?php echo esc_html($data['category']->name); ?>
                                                <span class="count">(<?php echo esc_html($data['count']); ?>)</span>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <div class="folder-description">
                                <?php echo wp_trim_words($destination->description, 20, '...'); ?>
                            </div>
                            <div class="folder-actions">
                                <a href="<?php echo esc_url(get_term_link($destination)); ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye me-2"></i><?php esc_html_e('View Tours', 'bike-theme'); ?>
                                </a>
                                <a href="<?php echo esc_url(get_term_link($destination)); ?>#book-now" class="btn btn-sm btn-dark">
                                    <i class="fas fa-calendar-alt me-2"></i><?php esc_html_e('Book Now', 'bike-theme'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
            <!-- Destinations Grid End -->

            <style>
                .destination-folder {
                    background: #fff;
                    border-radius: 15px;
                    box-shadow: 0 0 20px rgba(0,0,0,0.1);
                    transition: all 0.3s ease;
                    overflow: hidden;
                    height: 100%;
                }

                .destination-folder:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 5px 25px rgba(0,0,0,0.15);
                }

                .folder-header {
                    display: flex;
                    align-items: center;
                    padding: 20px;
                    background: #f8f9fa;
                    border-bottom: 1px solid #eee;
                }

                .folder-icon {
                    font-size: 24px;
                    margin-right: 15px;
                }

                .folder-info {
                    flex: 1;
                }

                .folder-title {
                    margin: 0;
                    font-size: 18px;
                    font-weight: 600;
                }

                .tour-count {
                    font-size: 14px;
                    color: #6c757d;
                }

                .folder-content {
                    padding: 20px;
                }

                .folder-image {
                    margin-bottom: 15px;
                    border-radius: 10px;
                    overflow: hidden;
                }

                .folder-image img {
                    width: 100%;
                    height: 200px;
                    object-fit: cover;
                }

                .folder-categories {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 8px;
                    margin-bottom: 15px;
                }

                .category-badge .badge {
                    font-size: 12px;
                    font-weight: 500;
                    padding: 6px 12px;
                }

                .folder-description {
                    font-size: 14px;
                    color: #6c757d;
                    margin-bottom: 15px;
                    line-height: 1.5;
                }

                .folder-actions {
                    display: flex;
                    gap: 10px;
                }

                .folder-actions .btn {
                    flex: 1;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    padding: 8px 15px;
                }

                .folder-actions .btn i {
                    font-size: 14px;
                }

                @media (max-width: 768px) {
                    .folder-header {
                        padding: 15px;
                    }

                    .folder-title {
                        font-size: 16px;
                    }

                    .folder-image img {
                        height: 150px;
                    }
                }
            </style>
    </div>
    <!-- Tours End -->
</main>

<?php
get_footer();
?> 