<?php
/**
 * Archive template for Screenings
 * Implements upcoming and past screenings with year grouping
 */

get_header(); ?>

<div class="hero archive-hero" style="height: 60vh;">
    <div class="hero-background" style="background-image: url('<?php echo esc_url(get_template_directory_uri() . '/assets/images/screenings-hero.jpg'); ?>');"></div>
    <div class="hero-content">
        <h1><?php esc_html_e('Screenings', 'saab'); ?></h1>
        <p class="hero-subtitle"><?php esc_html_e('Upcoming and past film screenings', 'saab'); ?></p>
    </div>
</div>

<div class="section section-white">
    <div class="container">
        <?php if (function_exists('saab_breadcrumbs')) saab_breadcrumbs(); ?>
        
        <!-- Screening Navigation Tabs -->
        <div class="screening-tabs" role="tablist" aria-label="<?php esc_attr_e('Screening categories', 'saab'); ?>">
            <button class="tab-button active" 
                    id="upcoming-tab" 
                    role="tab" 
                    aria-selected="true" 
                    aria-controls="upcoming-screenings">
                <?php esc_html_e('Upcoming Screenings', 'saab'); ?>
            </button>
            <button class="tab-button" 
                    id="past-tab" 
                    role="tab" 
                    aria-selected="false" 
                    aria-controls="past-screenings">
                <?php esc_html_e('Past Screenings', 'saab'); ?>
            </button>
        </div>

        <!-- Upcoming Screenings -->
        <div id="upcoming-screenings" 
             class="screening-section active" 
             role="tabpanel" 
             aria-labelledby="upcoming-tab">
            
            <?php
            $upcoming_screenings = new WP_Query(array(
                'post_type' => 'screening',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => '_saab_screening_date',
                        'value' => date('Y-m-d'),
                        'compare' => '>=',
                        'type' => 'DATE'
                    )
                ),
                'meta_key' => '_saab_screening_date',
                'orderby' => 'meta_value',
                'order' => 'ASC'
            ));

            if ($upcoming_screenings->have_posts()) : ?>
                <div class="screenings-grid">
                    <?php while ($upcoming_screenings->have_posts()) : $upcoming_screenings->the_post(); ?>
                        <?php get_template_part('template-parts/screening-card'); ?>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <div class="no-screenings">
                    <p><?php esc_html_e('No upcoming screenings scheduled at this time.', 'saab'); ?></p>
                </div>
            <?php endif;
            wp_reset_postdata();
            ?>
        </div>

        <!-- Past Screenings -->
        <div id="past-screenings" 
             class="screening-section" 
             role="tabpanel" 
             aria-labelledby="past-tab" 
             aria-hidden="true">
            
            <?php
            $past_screenings = new WP_Query(array(
                'post_type' => 'screening',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => '_saab_screening_date',
                        'value' => date('Y-m-d'),
                        'compare' => '<',
                        'type' => 'DATE'
                    )
                ),
                'meta_key' => '_saab_screening_date',
                'orderby' => 'meta_value',
                'order' => 'DESC'
            ));

            if ($past_screenings->have_posts()) :
                $current_year = '';
                $screenings_by_year = array();
                
                // Group screenings by year
                while ($past_screenings->have_posts()) : $past_screenings->the_post();
                    $screening_date = get_post_meta(get_the_ID(), '_saab_screening_date', true);
                    $year = $screening_date ? date('Y', strtotime($screening_date)) : 'Unknown';
                    
                    if (!isset($screenings_by_year[$year])) {
                        $screenings_by_year[$year] = array();
                    }
                    $screenings_by_year[$year][] = get_the_ID();
                endwhile;
                wp_reset_postdata();

                // Display screenings grouped by year
                foreach ($screenings_by_year as $year => $screening_ids) : ?>
                    <div class="year-group">
                        <h3 class="year-heading"><?php echo esc_html($year); ?></h3>
                        <div class="screenings-grid">
                            <?php foreach ($screening_ids as $screening_id) :
                                $post = get_post($screening_id);
                                setup_postdata($post);
                                get_template_part('template-parts/screening-card');
                            endforeach; ?>
                        </div>
                    </div>
                <?php endforeach;
                wp_reset_postdata();
            else : ?>
                <div class="no-screenings">
                    <p><?php esc_html_e('No past screenings found.', 'saab'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?> 