<?php
/**
 * Single Training/Workshop Template
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<!-- Hero Section -->
<div class="hero training-hero">
    <?php if (has_post_thumbnail()) : ?>
        <div class="hero-background" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'hero-background')); ?>');"></div>
    <?php endif; ?>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="workshop-meta">
            <span class="news-badge">
                <?php 
                $type = get_post_meta(get_the_ID(), '_saab_training_workshop_type', true) ?: 'Workshop';
                echo esc_html($type);
                ?>
            </span>
        </div>
        <h1 class="hero-title"><?php the_title(); ?></h1>
        <?php 
        $date = get_post_meta(get_the_ID(), '_saab_training_workshop_date', true);
        $location = get_post_meta(get_the_ID(), '_saab_training_workshop_location', true);
        if ($date || $location) : ?>
            <div class="workshop-details single-meta">
                <?php if ($date) : ?>
                    <span>
                        <i class="fas fa-calendar"></i>
                        <?php echo esc_html($date); ?>
                    </span>
                <?php endif; ?>
                <?php if ($location) : ?>
                    <span>
                        <i class="fas fa-map-marker-alt"></i>
                        <?php echo esc_html($location); ?>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="section section-white">
    <div class="container">
        <?php if (function_exists('saab_breadcrumbs')) saab_breadcrumbs(); ?>
        
        <div class="workshop-content-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem; margin-top: 2rem;">
            <!-- Main Content -->
            <div class="workshop-main-content">
                <article class="workshop-article">
                    <div class="article-content">
                        <?php the_content(); ?>
                    </div>
                </article>
            </div>
            
            <!-- Sidebar -->
            <div class="workshop-sidebar">
                <!-- Workshop Details Card -->
                <div class="workshop-details-card sidebar-section">
                    <h3><?php esc_html_e('Workshop Details', 'saab'); ?></h3>
                    <div class="details-list">
                        <?php 
                        $date = get_post_meta(get_the_ID(), '_saab_training_workshop_date', true);
                        $time = get_post_meta(get_the_ID(), '_saab_training_workshop_time', true);
                        $location = get_post_meta(get_the_ID(), '_saab_training_workshop_location', true);
                        $duration = get_post_meta(get_the_ID(), '_saab_training_workshop_duration', true);
                        $price = get_post_meta(get_the_ID(), '_saab_training_workshop_price', true);
                        $capacity = get_post_meta(get_the_ID(), '_saab_training_workshop_capacity', true);
                        $project_manager = get_post_meta(get_the_ID(), '_saab_training_workshop_project_manager', true);
                        $trainers = get_post_meta(get_the_ID(), '_saab_training_workshop_trainers', true);
                        $trainer = get_post_meta(get_the_ID(), '_saab_training_workshop_trainer', true);
                        
                        if ($date) : ?>
                            <div class="detail-item">
                                <i class="fas fa-calendar"></i>
                                <div>
                                    <strong><?php esc_html_e('Date:', 'saab'); ?></strong>
                                    <p><?php echo esc_html($date); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($time) : ?>
                            <div class="detail-item">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <strong><?php esc_html_e('Time:', 'saab'); ?></strong>
                                    <p><?php echo esc_html($time); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($location) : ?>
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <strong><?php esc_html_e('Location:', 'saab'); ?></strong>
                                    <p><?php echo esc_html($location); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($duration) : ?>
                            <div class="detail-item">
                                <i class="fas fa-hourglass-half"></i>
                                <div>
                                    <strong><?php esc_html_e('Duration:', 'saab'); ?></strong>
                                    <p><?php echo esc_html($duration); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($price) : ?>
                            <div class="detail-item">
                                <i class="fas fa-tag"></i>
                                <div>
                                    <strong><?php esc_html_e('Price:', 'saab'); ?></strong>
                                    <p><?php echo esc_html($price); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($capacity) : ?>
                            <div class="detail-item">
                                <i class="fas fa-users"></i>
                                <div>
                                    <strong><?php esc_html_e('Capacity:', 'saab'); ?></strong>
                                    <p><?php echo esc_html($capacity); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($project_manager) : ?>
                            <div class="detail-item">
                                <i class="fas fa-user-tie"></i>
                                <div>
                                    <strong><?php esc_html_e('Project Manager:', 'saab'); ?></strong>
                                    <p><?php echo esc_html($project_manager); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($trainers) : ?>
                            <div class="detail-item">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <div>
                                    <strong><?php esc_html_e('Trainers:', 'saab'); ?></strong>
                                    <p><?php echo nl2br(esc_html($trainers)); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($trainer) : ?>
                            <div class="detail-item">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <div>
                                    <strong><?php esc_html_e('Trainer:', 'saab'); ?></strong>
                                    <p><?php echo esc_html($trainer); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Registration Button -->
                <div class="registration-section sidebar-section">
                    <h4><?php esc_html_e('Interested in this workshop?', 'saab'); ?></h4>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn-enhanced btn-enhanced-primary btn-enhanced-lg">
                        <?php esc_html_e('Register Now', 'saab'); ?>
                    </a>
                </div>

                <!-- Related Workshops -->
                <div class="related-workshops-section sidebar-section">
                    <h4><?php esc_html_e('Related Workshops', 'saab'); ?></h4>
                    <?php
                    $related_workshops = new WP_Query(array(
                        'post_type' => 'training_workshop',
                        'posts_per_page' => 3,
                        'post__not_in' => array(get_the_ID()),
                        'orderby' => 'date',
                        'order' => 'DESC',
                    ));
                    if ($related_workshops->have_posts()) :
                        while ($related_workshops->have_posts()) : $related_workshops->the_post();
                            ?>
                            <div class="related-workshop-item">
                                <h5>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h5>
                                <p>
                                    <?php echo get_post_meta(get_the_ID(), '_saab_training_workshop_date', true); ?>
                                </p>
                            </div>
                        <?php endwhile;
                        wp_reset_postdata();
                    else : ?>
                        <p class="no-related-workshops">
                            <?php esc_html_e('No related workshops at the moment.', 'saab'); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Participants Section -->
        <?php $participants = get_post_meta(get_the_ID(), '_saab_training_workshop_participants', true); if ($participants) : ?>
        <div class="section section-cream" style="margin-top: 3rem; padding: 3rem; border-radius: 8px;">
            <h3 style="font-family: 'Epilogue', sans-serif; color: var(--brand-midnight); margin-bottom: 1.5rem; font-size: 1.5rem;">
                <?php esc_html_e('Target Participants', 'saab'); ?>
            </h3>
            <div style="font-family: 'Lora', serif; color: var(--brand-blue); font-size: 1.1rem; line-height: 1.7;">
                <?php echo nl2br(esc_html($participants)); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Workshop Gallery -->
<?php
$gallery = get_post_meta(get_the_ID(), '_saab_training_workshop_gallery', true);
if (!empty($gallery)) :
    $ids = explode(',', $gallery);
    if (!empty($ids)) : ?>
        <div class="section section-cream" style="padding-top: 4rem; padding-bottom: 4rem;">
            <div class="container">
                <h2 style="font-family: 'Epilogue', sans-serif; color: var(--brand-yellow); text-align: center; margin-bottom: 3rem; font-size: 2rem;">
                    <?php esc_html_e('Workshop Gallery', 'saab'); ?>
                </h2>
                <div class="workshop-gallery-swiper swiper-container" style="max-width: 1000px; margin: 0 auto;">
                    <div class="swiper-wrapper">
                        <?php foreach ($ids as $img_id) : 
                            $img_url = wp_get_attachment_image_url($img_id, 'gallery-thumb');
                            if ($img_url) : ?>
                                <div class="swiper-slide" style="text-align: center;">
                                    <img src="<?php echo esc_url($img_url); ?>" 
                                         alt="<?php esc_attr_e('Workshop image', 'saab'); ?>" 
                                         loading="lazy" 
                                         style="max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);" />
                                </div>
                            <?php endif;
                        endforeach; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next" tabindex="0" role="button" aria-label="<?php esc_attr_e('Next slide', 'saab'); ?>"></div>
                    <div class="swiper-button-prev" tabindex="0" role="button" aria-label="<?php esc_attr_e('Previous slide', 'saab'); ?>"></div>
                </div>
            </div>
        </div>
    <?php endif;
endif; ?>

<?php if (function_exists('saab_display_related_content')) saab_display_related_content(get_the_ID()); ?>

<?php endwhile; ?>

<?php get_footer(); ?> 