<?php
/**
 * Main template file - Homepage
 */

get_header(); ?>

<!-- Hero Section -->
<?php get_template_part('template-parts/hero'); ?>

<!-- Featured Films Section -->
<section class="section-enhanced section-white" id="featured-films">
    <div class="container">
        <div class="section-header-enhanced">
            <h2 class="section-title-enhanced"><?php esc_html_e('Featured Films', 'saab'); ?></h2>
            <p class="section-subtitle-enhanced"><?php esc_html_e('Discover the most significant works from Jocelyne Saab\'s cinematic journey', 'saab'); ?></p>
        </div>

        <div class="films-carousel swiper-container">
            <div class="swiper-wrapper">
                <?php
                $featured_films = new WP_Query(array(
                    'post_type' => 'film',
                    'posts_per_page' => 6,
                    'meta_key' => '_saab_featured',
                    'meta_value' => '1',
                    'orderby' => 'date',
                    'order' => 'DESC',
                ));

                if ($featured_films->have_posts()) :
                    while ($featured_films->have_posts()) : $featured_films->the_post();
                        echo '<div class="swiper-slide">';
                        get_template_part('template-parts/film-card');
                        echo '</div>';
                    endwhile;
                    wp_reset_postdata();
                else :
                    // Fallback to latest films if no featured films
                    $latest_films = new WP_Query(array(
                        'post_type' => 'film',
                        'posts_per_page' => 6,
                        'orderby' => 'date',
                        'order' => 'DESC',
                    ));
                    
                    if ($latest_films->have_posts()) :
                        while ($latest_films->have_posts()) : $latest_films->the_post();
                            echo '<div class="swiper-slide">';
                            get_template_part('template-parts/film-card');
                            echo '</div>';
                        endwhile;
                        wp_reset_postdata();
                    endif;
                endif;
                ?>
            </div>
            
            <!-- Navigation -->
            <div class="swiper-button-next" tabindex="0" role="button" aria-label="<?php esc_attr_e('Next film', 'saab'); ?>"></div>
            <div class="swiper-button-prev" tabindex="0" role="button" aria-label="<?php esc_attr_e('Previous film', 'saab'); ?>"></div>
            
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        </div>

        <div class="section-actions">
            <a href="<?php echo esc_url(get_post_type_archive_link('film')); ?>" class="btn-enhanced btn-enhanced-primary btn-enhanced-lg">
                <?php esc_html_e('View All Films', 'saab'); ?>
            </a>
        </div>
    </div>
</section>

<!-- Featured Workshops Section -->
<section class="section-enhanced section-cream">
    <div class="container">
        <div class="section-header-enhanced">
            <h2 class="section-title-enhanced"><?php esc_html_e('Featured Workshops', 'saab'); ?></h2>
            <p class="section-subtitle-enhanced"><?php esc_html_e('Explore our latest educational programs and workshops', 'saab'); ?></p>
        </div>
        
        <div class="workshops-carousel swiper-container">
            <div class="swiper-wrapper">
                <?php
                $featured_workshops = new WP_Query(array(
                    'post_type' => 'training_workshop',
                    'posts_per_page' => 4,
                    'orderby' => 'date',
                    'order' => 'DESC',
                ));
                if ($featured_workshops->have_posts()) :
                    while ($featured_workshops->have_posts()) : $featured_workshops->the_post();
                        echo '<div class="swiper-slide">';
                        get_template_part('template-parts/workshop-card');
                        echo '</div>';
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
            
            <!-- Navigation -->
            <div class="swiper-button-next" tabindex="0" role="button" aria-label="<?php esc_attr_e('Next workshop', 'saab'); ?>"></div>
            <div class="swiper-button-prev" tabindex="0" role="button" aria-label="<?php esc_attr_e('Previous workshop', 'saab'); ?>"></div>
            
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        </div>
        
        <div class="section-actions">
            <a href="<?php echo esc_url(get_post_type_archive_link('training_workshop')); ?>" class="btn-enhanced btn-enhanced-primary btn-enhanced-lg">
                <?php esc_html_e('View All Trainings & Workshops', 'saab'); ?>
            </a>
        </div>
    </div>
</section>

<!-- Latest News Section -->
<section class="section-enhanced section-white">
    <div class="container">
        <div class="section-header-enhanced">
            <h2 class="section-title-enhanced"><?php esc_html_e('Latest News', 'saab'); ?></h2>
            <p class="section-subtitle-enhanced"><?php esc_html_e('Stay updated with exhibitions, screenings, and announcements', 'saab'); ?></p>
        </div>

        <div class="news-grid grid-enhanced">
            <?php
            $latest_news = new WP_Query(array(
                'post_type' => 'news',
                'posts_per_page' => 3,
                'orderby' => 'date',
                'order' => 'DESC',
            ));

            if ($latest_news->have_posts()) :
                while ($latest_news->have_posts()) : $latest_news->the_post();
                    get_template_part('template-parts/news-card');
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>

        <div class="section-actions">
            <a href="<?php echo esc_url(get_post_type_archive_link('news')); ?>" class="btn-enhanced btn-enhanced-outline btn-enhanced-lg">
                <?php esc_html_e('View All News', 'saab'); ?>
            </a>
        </div>
    </div>
</section>

<!-- Partners Section -->
<section class="section-enhanced section-cream">
    <div class="container">
        <div class="section-header-enhanced">
            <h2 class="section-title-enhanced"><?php esc_html_e('Partners & Collaborators', 'saab'); ?></h2>
            <p class="section-subtitle-enhanced"><?php esc_html_e('Organizations and institutions that support our mission', 'saab'); ?></p>
        </div>

        <div class="partner-grid grid-enhanced">
            <?php
            $partners = new WP_Query(array(
                'post_type' => 'partner',
                'posts_per_page' => 8,
                'orderby' => 'menu_order',
                'order' => 'ASC',
            ));

            if ($partners->have_posts()) :
                while ($partners->have_posts()) : $partners->the_post();
                    get_template_part('template-parts/partner-card');
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>