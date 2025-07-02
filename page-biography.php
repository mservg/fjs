<?php
/**
 * Biography Page Template - Interactive Chronological Journey
 * Template Name: Biography
 */

get_header(); ?>

<div class="biography-page">
    <!-- Hero Section with Parallax Portrait -->
    <section class="biography-hero">
        <div class="hero-background">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('full', array('class' => 'hero-portrait')); ?>
            <?php endif; ?>
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-content">
            <div class="container">
                <h1 class="hero-title"><?php the_title(); ?></h1>
                <?php if (function_exists('get_field') && get_field('subtitle')) : ?>
                    <p class="hero-subtitle"><?php echo esc_html(get_field('subtitle')); ?></p>
                <?php endif; ?>
                <div class="hero-scroll-indicator">
                    <span><?php esc_html_e('Scroll to explore', 'saab'); ?></span>
                    <i class="fas fa-chevron-down"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Interactive Timeline Section -->
    <section class="timeline-section">
        <div class="container">
            <div class="timeline-header">
                <h2><?php esc_html_e('A Life in Film', 'saab'); ?></h2>
                <p><?php esc_html_e('Explore the journey of Jocelyne Saab through interactive moments in time', 'saab'); ?></p>
            </div>
            
            <div class="timeline-navigation">
                <div class="timeline-filters">
                    <button class="timeline-filter active" data-filter="all"><?php esc_html_e('All Years', 'saab'); ?></button>
                    <button class="timeline-filter" data-filter="1948-1970"><?php esc_html_e('1948-1970', 'saab'); ?></button>
                    <button class="timeline-filter" data-filter="1971-1990"><?php esc_html_e('1971-1990', 'saab'); ?></button>
                    <button class="timeline-filter" data-filter="1991-2019"><?php esc_html_e('1991-2019', 'saab'); ?></button>
                </div>
            </div>

            <div class="timeline-container">
                <?php if (function_exists('have_rows') && have_rows('key_dates')) : ?>
                    <?php while (have_rows('key_dates')) : the_row(); 
                        $date = function_exists('get_sub_field') ? get_sub_field('date') : '';
                        $title = function_exists('get_sub_field') ? get_sub_field('title') : '';
                        $description = function_exists('get_sub_field') ? get_sub_field('description') : '';
                        $image = function_exists('get_sub_field') ? get_sub_field('image') : '';
                        $video_url = function_exists('get_sub_field') ? get_sub_field('video_url') : '';
                        $audio_url = function_exists('get_sub_field') ? get_sub_field('audio_url') : '';
                        $year = date('Y', strtotime($date));
                    ?>
                        <div class="timeline-item" data-year="<?php echo esc_attr($year); ?>">
                            <div class="timeline-marker">
                                <div class="marker-dot"></div>
                                <div class="marker-line"></div>
                            </div>
                            
                            <div class="timeline-content">
                                <div class="timeline-date"><?php echo esc_html($date); ?></div>
                                <div class="timeline-card">
                                    <h3><?php echo esc_html($title); ?></h3>
                                    <p><?php echo esc_html($description); ?></p>
                                    
                                    <?php if ($image) : ?>
                                        <div class="timeline-media">
                                            <img src="<?php echo esc_url($image['url']); ?>" 
                                                 alt="<?php echo esc_attr($image['alt']); ?>" 
                                                 class="timeline-image">
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($video_url) : ?>
                                        <div class="timeline-media">
                                            <div class="video-container">
                                                <iframe src="<?php echo esc_url($video_url); ?>" 
                                                        frameborder="0" 
                                                        allowfullscreen 
                                                        loading="lazy">
                                                </iframe>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($audio_url) : ?>
                                        <div class="timeline-media">
                                            <audio controls preload="none">
                                                <source src="<?php echo esc_url($audio_url); ?>" type="audio/mpeg">
                                                <?php esc_html_e('Your browser does not support the audio element.', 'saab'); ?>
                                            </audio>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Biography Content Section -->
    <section class="biography-content-section">
        <div class="container">
            <div class="content-grid">
                <div class="main-content">
                    <div class="biography-text">
                        <?php the_content(); ?>
                    </div>
                </div>
                
                <div class="sidebar-content">
                    <!-- Quick Facts -->
                    <?php if (function_exists('get_field') && get_field('quick_facts')) : ?>
                        <div class="quick-facts-card">
                            <h3><?php esc_html_e('Quick Facts', 'saab'); ?></h3>
                            <div class="facts-list">
                                <?php foreach (function_exists('get_field') ? get_field('quick_facts') : array() as $fact) : ?>
                                    <div class="fact-item">
                                        <strong><?php echo esc_html($fact['label']); ?>:</strong>
                                        <span><?php echo esc_html($fact['value']); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Awards & Recognition -->
                    <?php if (function_exists('have_rows') && have_rows('awards')) : ?>
                        <div class="awards-card">
                            <h3><?php esc_html_e('Awards & Recognition', 'saab'); ?></h3>
                            <div class="awards-list">
                                <?php while (have_rows('awards')) : the_row(); ?>
                                    <div class="award-item">
                                        <div class="award-year"><?php echo esc_html(function_exists('get_sub_field') ? get_sub_field('year') : ''); ?></div>
                                        <div class="award-details">
                                            <h4><?php echo esc_html(function_exists('get_sub_field') ? get_sub_field('award_name') : ''); ?></h4>
                                            <p><?php echo esc_html(function_exists('get_sub_field') ? get_sub_field('category') : ''); ?></p>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Filmography Preview -->
    <?php if (function_exists('get_field') && get_field('show_filmography')) : ?>
        <section class="filmography-section">
            <div class="container">
                <div class="section-header">
                    <h2><?php esc_html_e('Selected Filmography', 'saab'); ?></h2>
                    <p><?php esc_html_e('A glimpse into Jocelyne Saab\'s cinematic legacy', 'saab'); ?></p>
                </div>
                
                <div class="films-showcase">
                    <?php
                    $films = get_posts(array(
                        'post_type' => 'film',
                        'posts_per_page' => 6,
                        'meta_query' => array(
                            array(
                                'key' => '_saab_film_director',
                                'value' => 'Jocelyne Saab',
                                'compare' => 'LIKE'
                            )
                        )
                    ));
                    
                    foreach ($films as $film) : ?>
                        <div class="film-showcase-item">
                            <?php if (has_post_thumbnail($film->ID)) : ?>
                                <div class="film-image">
                                    <?php echo get_the_post_thumbnail($film->ID, 'film-thumb'); ?>
                                </div>
                            <?php endif; ?>
                            <div class="film-info">
                                <h3><?php echo esc_html($film->post_title); ?></h3>
                                <?php 
                                $year = get_post_meta($film->ID, '_saab_film_year', true);
                                if ($year) : ?>
                                    <p class="film-year"><?php echo esc_html($year); ?></p>
                                <?php endif; ?>
                                <a href="<?php echo get_permalink($film->ID); ?>" class="film-link">
                                    <?php esc_html_e('View Film', 'saab'); ?>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="section-actions">
                    <a href="<?php echo get_post_type_archive_link('film'); ?>" class="btn-primary">
                        <?php esc_html_e('View All Films', 'saab'); ?>
                    </a>
                </div>
            </div>
        </section>
    <?php endif; ?>
</div>

<?php get_footer(); ?> 