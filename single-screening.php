<?php
/**
 * Single Screening Template
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<!-- Screening Hero -->
<div class="hero screening-hero">
    <?php if (has_post_thumbnail()) : ?>
        <div class="hero-background" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'hero-background')); ?>');"></div>
    <?php endif; ?>
    <div class="hero-content">
        <h1><?php the_title(); ?></h1>
        <?php 
        $screening_date = get_post_meta(get_the_ID(), '_saab_screening_date', true);
        $screening_time = get_post_meta(get_the_ID(), '_saab_screening_time', true);
        $screening_location = get_post_meta(get_the_ID(), '_saab_screening_location', true);
        
        if ($screening_date || $screening_time || $screening_location) : ?>
            <p class="hero-subtitle">
                <?php 
                $subtitle_parts = array();
                if ($screening_date) {
                    $subtitle_parts[] = date_i18n(get_option('date_format'), strtotime($screening_date));
                }
                if ($screening_time) {
                    $subtitle_parts[] = $screening_time;
                }
                if ($screening_location) {
                    $subtitle_parts[] = $screening_location;
                }
                echo esc_html(implode(' • ', $subtitle_parts));
                ?>
            </p>
        <?php endif; ?>
    </div>
</div>

<div class="section section-white">
    <div class="container">
        <?php if (function_exists('saab_breadcrumbs')) saab_breadcrumbs(); ?>
        
        <div class="grid grid-12" style="gap: var(--space-60);">
            <!-- Main Content -->
            <div style="grid-column: 1 / span 8;">
                
                <!-- Screening Details -->
                <div class="screening-details" style="margin-bottom: var(--space-50);">
                    <h2><?php esc_html_e('Screening Details', 'saab'); ?></h2>
                    
                    <div class="details-grid">
                        <?php if ($screening_date) : ?>
                            <div class="detail-item">
                                <i class="fas fa-calendar" aria-hidden="true"></i>
                                <div>
                                    <strong><?php esc_html_e('Date:', 'saab'); ?></strong>
                                    <time datetime="<?php echo esc_attr($screening_date); ?>">
                                        <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($screening_date))); ?>
                                    </time>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($screening_time) : ?>
                            <div class="detail-item">
                                <i class="fas fa-clock" aria-hidden="true"></i>
                                <div>
                                    <strong><?php esc_html_e('Time:', 'saab'); ?></strong>
                                    <span><?php echo esc_html($screening_time); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($screening_location) : ?>
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                                <div>
                                    <strong><?php esc_html_e('Location:', 'saab'); ?></strong>
                                    <span><?php echo esc_html($screening_location); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php 
                        $screening_film = get_post_meta(get_the_ID(), '_saab_screening_film', true);
                        if ($screening_film) : ?>
                            <div class="detail-item">
                                <i class="fas fa-video" aria-hidden="true"></i>
                                <div>
                                    <strong><?php esc_html_e('Film:', 'saab'); ?></strong>
                                    <span><?php echo esc_html($screening_film); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php 
                        $screening_type = get_post_meta(get_the_ID(), '_saab_screening_type', true);
                        if ($screening_type) : ?>
                            <div class="detail-item">
                                <i class="fas fa-tag" aria-hidden="true"></i>
                                <div>
                                    <strong><?php esc_html_e('Type:', 'saab'); ?></strong>
                                    <span><?php echo esc_html($screening_type); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php 
                        $screening_duration = get_post_meta(get_the_ID(), '_saab_screening_duration', true);
                        if ($screening_duration) : ?>
                            <div class="detail-item">
                                <i class="fas fa-hourglass-half" aria-hidden="true"></i>
                                <div>
                                    <strong><?php esc_html_e('Duration:', 'saab'); ?></strong>
                                    <span><?php echo esc_html($screening_duration); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Screening Description -->
                <div class="screening-description" style="margin-bottom: var(--space-50);">
                    <h3><?php esc_html_e('About This Screening', 'saab'); ?></h3>
                    <div class="description-content">
                        <?php the_content(); ?>
                    </div>
                </div>

                <!-- Film Information -->
                <?php if ($screening_film) : ?>
                    <div class="film-information" style="margin-bottom: var(--space-50);">
                        <h3><?php esc_html_e('Film Information', 'saab'); ?></h3>
                        <div class="film-details">
                            <?php 
                            // Try to find the film post
                            $film_query = new WP_Query(array(
                                'post_type' => 'film',
                                'title' => $screening_film,
                                'posts_per_page' => 1
                            ));
                            
                            if ($film_query->have_posts()) :
                                $film_query->the_post();
                                $film_synopsis = get_post_meta(get_the_ID(), '_saab_film_synopsis', true);
                                $film_year = get_post_meta(get_the_ID(), '_saab_film_year', true);
                                $film_duration = get_post_meta(get_the_ID(), '_saab_film_duration', true);
                                ?>
                                
                                <div class="film-card">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="film-poster">
                                            <?php the_post_thumbnail('film-poster'); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="film-info">
                                        <h4><?php the_title(); ?></h4>
                                        <?php if ($film_year || $film_duration) : ?>
                                            <p class="film-meta">
                                                <?php if ($film_year) echo esc_html($film_year); ?>
                                                <?php if ($film_year && $film_duration) echo ' • '; ?>
                                                <?php if ($film_duration) echo esc_html($film_duration); ?>
                                            </p>
                                        <?php endif; ?>
                                        
                                        <?php if ($film_synopsis) : ?>
                                            <div class="film-synopsis">
                                                <?php echo wp_kses_post(wp_trim_words($film_synopsis, 50, '...')); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <a href="<?php the_permalink(); ?>" class="btn btn-small">
                                            <?php esc_html_e('View Film Details', 'saab'); ?>
                                        </a>
                                    </div>
                                </div>
                                
                                <?php
                                wp_reset_postdata();
                            else : ?>
                                <p><?php echo esc_html(sprintf(__('Film: %s', 'saab'), $screening_film)); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Additional Information -->
                <?php 
                $additional_info = get_post_meta(get_the_ID(), '_saab_screening_additional_info', true);
                if ($additional_info) : ?>
                    <div class="additional-info" style="margin-bottom: var(--space-50);">
                        <h3><?php esc_html_e('Additional Information', 'saab'); ?></h3>
                        <div class="info-content">
                            <?php echo wp_kses_post($additional_info); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div style="grid-column: 9 / span 4;">
                <div class="screening-sidebar">
                    
                    <!-- Tickets -->
                    <?php 
                    $screening_tickets = get_post_meta(get_the_ID(), '_saab_screening_tickets', true);
                    $is_upcoming = $screening_date && strtotime($screening_date) >= strtotime(date('Y-m-d'));
                    
                    if ($is_upcoming && $screening_tickets) : ?>
                        <div class="sidebar-section">
                            <h4><?php esc_html_e('Get Tickets', 'saab'); ?></h4>
                            <a href="<?php echo esc_url($screening_tickets); ?>" 
                               class="btn btn-large" 
                               target="_blank" 
                               rel="noopener noreferrer">
                                <?php esc_html_e('Purchase Tickets', 'saab'); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <!-- Contact Information -->
                    <?php 
                    $contact_info = get_post_meta(get_the_ID(), '_saab_screening_contact', true);
                    if ($contact_info) : ?>
                        <div class="sidebar-section">
                            <h4><?php esc_html_e('Contact Information', 'saab'); ?></h4>
                            <div class="contact-info">
                                <?php echo wp_kses_post($contact_info); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Related Screenings -->
                    <div class="sidebar-section">
                        <h4><?php esc_html_e('Other Screenings', 'saab'); ?></h4>
                        <?php
                        $related_screenings = new WP_Query(array(
                            'post_type' => 'screening',
                            'posts_per_page' => 3,
                            'post__not_in' => array(get_the_ID()),
                            'meta_key' => '_saab_screening_date',
                            'orderby' => 'meta_value',
                            'order' => 'ASC',
                            'meta_query' => array(
                                array(
                                    'key' => '_saab_screening_date',
                                    'value' => date('Y-m-d'),
                                    'compare' => '>=',
                                    'type' => 'DATE'
                                )
                            )
                        ));

                        if ($related_screenings->have_posts()) : ?>
                            <div class="related-screenings">
                                <?php while ($related_screenings->have_posts()) : $related_screenings->the_post(); ?>
                                    <div class="related-screening">
                                        <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                        <?php 
                                        $rel_date = get_post_meta(get_the_ID(), '_saab_screening_date', true);
                                        if ($rel_date) : ?>
                                            <time datetime="<?php echo esc_attr($rel_date); ?>">
                                                <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($rel_date))); ?>
                                            </time>
                                        <?php endif; ?>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else : ?>
                            <p><?php esc_html_e('No other upcoming screenings at this time.', 'saab'); ?></p>
                        <?php endif;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Screening Gallery above related content -->
<div class="section section-white" style="padding-top: 2rem; padding-bottom: 2rem;">
    <div class="container">
        <h2 class="section-title-enhanced" style="text-align:center; color: var(--brand-yellow); font-family: 'Epilogue', sans-serif; font-weight: 700; margin-bottom: 2rem;">
            <?php esc_html_e('Gallery', 'saab'); ?>
        </h2>
        <?php
        $gallery = get_post_meta(get_the_ID(), '_saab_screening_gallery', true);
        if (!empty($gallery)) :
            $ids = explode(',', $gallery);
            if (!empty($ids)) : ?>
                <div class="screening-gallery-swiper swiper-container">
                    <div class="swiper-wrapper">
                        <?php foreach ($ids as $img_id) : 
                            $img_url = wp_get_attachment_image_url($img_id, 'gallery-thumb');
                            if ($img_url) : ?>
                                <div class="swiper-slide">
                                    <img src="<?php echo esc_url($img_url); ?>" alt="<?php esc_attr_e('Screening image', 'saab'); ?>" loading="lazy" />
                                </div>
                            <?php endif;
                        endforeach; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next" tabindex="0" role="button" aria-label="<?php esc_attr_e('Next slide', 'saab'); ?>"></div>
                    <div class="swiper-button-prev" tabindex="0" role="button" aria-label="<?php esc_attr_e('Previous slide', 'saab'); ?>"></div>
                </div>
            <?php endif;
        endif; ?>
    </div>
</div>

<?php if (function_exists('saab_display_related_content')) saab_display_related_content(get_the_ID()); ?>

<?php endwhile; ?>

<?php get_footer(); ?> 