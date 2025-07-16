<?php
/**
 * Front Page Template - Custom Homepage
 */

get_header(); ?>

<!-- Hero Section -->
<?php get_template_part('template-parts/hero'); ?>

<!-- Screenings Map Section -->
<section id="screenings-map" class="section-map">
  <div class="container">
    <h2 class="section-title-centered"><?php esc_html_e('Screenings Around the World', 'saab'); ?></h2>
    <div id="leaflet-map"></div>
  </div>
</section>

<!-- News Carousel Section -->
<section class="section-news-carousel" id="latest-news-updates">
  <div class="container">
    <h2 class="section-title-centered"><?php esc_html_e('Latest news and updates', 'saab'); ?></h2>
    <div class="news-carousel-wrapper">
      <button class="carousel-arrow carousel-arrow-left" aria-label="Previous News"></button>
      <div class="news-carousel" id="news-carousel">
        <?php
        $news_query = new WP_Query([
          'post_type' => 'news',
          'posts_per_page' => 8,
          'orderby' => 'date',
          'order' => 'DESC',
        ]);
        if ($news_query->have_posts()) :
          while ($news_query->have_posts()) : $news_query->the_post();
            $date = get_the_date('j F Y');
            $badge = get_post_meta(get_the_ID(), '_saab_news_badge', true) ?: 'Announcement';
            ?>
            <div class="news-card">
              <div class="news-card-image">
                <?php if (has_post_thumbnail()) : ?>
                  <img src="<?php the_post_thumbnail_url('news-featured'); ?>" alt="<?php the_title_attribute(); ?>" />
                <?php endif; ?>
                <span class="news-badge"><?php echo esc_html($badge); ?> ● <?php echo esc_html($date); ?></span>
              </div>
              <div class="news-card-content">
                <h3 class="news-card-title"><?php the_title(); ?></h3>
                <div class="news-card-excerpt"><?php echo wp_trim_words(get_the_excerpt(), 18); ?></div>
              </div>
              <a href="<?php the_permalink(); ?>" class="news-card-link" aria-label="<?php the_title_attribute(); ?>"></a>
            </div>
          <?php endwhile; wp_reset_postdata();
        endif; ?>
      </div>
      <button class="carousel-arrow carousel-arrow-right" aria-label="Next News"></button>
    </div>
  </div>
</section>

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