<?php
/**
 * Work/Œuvre Page Template
 * Template Name: Work
 *
 * NOTE: Always check for unmatched parentheses and syntax errors before deploying!
 */

get_header(); ?>

<div class="work-page">
    <!-- Hero Section -->
    <div class="work-hero section section-dark">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title"><?php the_title(); ?></h1>
                <?php if (function_exists('get_field') && get_field('subtitle')) : ?>
                    <p class="hero-subtitle"><?php echo esc_html(get_field('subtitle')); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Work Overview -->
    <div class="section section-white">
        <div class="container">
            <div class="work-overview">
                <?php the_content(); ?>
            </div>
        </div>
    </div>

    <!-- Work Categories -->
    <div class="section section-light">
        <div class="container">
            <div class="work-categories">
                <h2><?php esc_html_e('Categories of Work', 'saab'); ?></h2>
                
                <!-- Category Filters -->
                <div class="category-filters">
                    <button class="filter-btn active" data-category="all">
                        <?php esc_html_e('All Work', 'saab'); ?>
                    </button>
                    <button class="filter-btn" data-category="documentary">
                        <?php esc_html_e('Documentaries', 'saab'); ?>
                    </button>
                    <button class="filter-btn" data-category="fiction">
                        <?php esc_html_e('Fiction Films', 'saab'); ?>
                    </button>
                    <button class="filter-btn" data-category="experimental">
                        <?php esc_html_e('Experimental', 'saab'); ?>
                    </button>
                    <button class="filter-btn" data-category="television">
                        <?php esc_html_e('Television', 'saab'); ?>
                    </button>
                </div>

                <!-- Work Grid -->
                <div class="work-grid">
                    <?php
                    $films = get_posts(array(
                        'post_type' => 'film',
                        'posts_per_page' => -1,
                        'meta_query' => array(
                            array(
                                'key' => '_saab_film_director',
                                'value' => 'Jocelyne Saab',
                                'compare' => 'LIKE'
                            )
                        )
                    ));
                    
                    foreach ($films as $film) : 
                        $category = get_post_meta($film->ID, '_saab_film_category', true);
                        $year = get_post_meta($film->ID, '_saab_film_year', true);
                        $duration = get_post_meta($film->ID, '_saab_film_duration', true);
                        $country = get_post_meta($film->ID, '_saab_film_country', true);
                    ?>
                        <div class="work-item" data-category="<?php echo esc_attr($category); ?>">
                            <div class="work-image">
                                <?php if (has_post_thumbnail($film->ID)) : ?>
                                    <?php echo get_the_post_thumbnail($film->ID, 'film-poster'); ?>
                                <?php else : ?>
                                    <div class="work-placeholder">
                                        <i class="fas fa-film"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="work-overlay">
                                    <div class="work-overlay-content">
                                        <h3><?php echo esc_html($film->post_title); ?></h3>
                                        <div class="work-meta">
                                            <?php if ($year) : ?>
                                                <span class="work-year"><?php echo esc_html($year); ?></span>
                                            <?php endif; ?>
                                            <?php if ($duration) : ?>
                                                <span class="work-duration"><?php echo esc_html($duration); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <a href="<?php echo get_permalink($film->ID); ?>" class="work-link">
                                            <?php esc_html_e('View Details', 'saab'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="work-info">
                                <h3 class="work-title"><?php echo esc_html($film->post_title); ?></h3>
                                <div class="work-details">
                                    <?php if ($year) : ?>
                                        <span class="work-year"><?php echo esc_html($year); ?></span>
                                    <?php endif; ?>
                                    <?php if ($country) : ?>
                                        <span class="work-country"><?php echo esc_html($country); ?></span>
                                    <?php endif; ?>
                                    <?php if ($category) : ?>
                                        <span class="work-category"><?php echo esc_html($category); ?></span>
                                    <?php endif; ?>
                                </div>
                                <p class="work-excerpt"><?php echo wp_trim_words($film->post_excerpt, 20); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline Section -->
    <div class="section section-white">
        <div class="container">
            <div class="work-timeline">
                <h2><?php esc_html_e('Chronological Timeline', 'saab'); ?></h2>
                <div class="timeline-container">
                    <?php
                    // Sort films by year
                    usort($films, function($a, $b) {
                        $year_a = get_post_meta($a->ID, '_saab_film_year', true);
                        $year_b = get_post_meta($b->ID, '_saab_film_year', true);
                        return $year_a - $year_b;
                    });
                    
                    $current_year = '';
                    foreach ($films as $film) : 
                        $year = get_post_meta($film->ID, '_saab_film_year', true);
                        if ($year !== $current_year) :
                            if ($current_year !== '') echo '</div>'; // Close previous year group
                            $current_year = $year;
                    ?>
                        <div class="timeline-year">
                            <h3 class="year-label"><?php echo esc_html($year); ?></h3>
                    <?php endif; ?>
                    
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <h4><?php echo esc_html($film->post_title); ?></h4>
                            <p><?php echo wp_trim_words($film->post_excerpt, 15); ?></p>
                            <a href="<?php echo get_permalink($film->ID); ?>" class="timeline-link">
                                <?php esc_html_e('Learn More', 'saab'); ?>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php if ($current_year !== '') echo '</div>'; // Close last year group ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="section section-dark">
        <div class="container">
            <div class="work-statistics">
                <h2><?php esc_html_e('Work Statistics', 'saab'); ?></h2>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number"><?php echo count($films); ?></div>
                        <div class="stat-label"><?php esc_html_e('Total Films', 'saab'); ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?php 
                            $years = array();
                            foreach ($films as $film) {
                                $year = get_post_meta($film->ID, '_saab_film_year', true);
                                if ($year) {
                                    $years[] = $year;
                                }
                            }
                            echo count(array_unique($years));
                        ?></div>
                        <div class="stat-label"><?php esc_html_e('Years of Work', 'saab'); ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?php 
                            $countries = array();
                            foreach ($films as $film) {
                                $country = get_post_meta($film->ID, '_saab_film_country', true);
                                if ($country) {
                                    $countries[] = $country;
                                }
                            }
                            echo count(array_unique($countries));
                        ?></div>
                        <div class="stat-label"><?php esc_html_e('Countries', 'saab'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?> 