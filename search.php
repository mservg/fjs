<?php
/**
 * Enhanced Global Search Template
 * Searches across all content types: films, workshops, news, screenings
 */

get_header(); ?>

<div class="hero-enhanced search-hero">
    <div class="hero-background" style="background-image: url('<?php echo esc_url(get_template_directory_uri() . '/assets/images/search-hero.jpg'); ?>');"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="container">
            <h1 class="hero-title"><?php esc_html_e('Search Results', 'saab'); ?></h1>
            <p class="hero-subtitle">
                <?php 
                $search_query = get_search_query();
                if ($search_query) {
                    printf(esc_html__('Search results for: "%s"', 'saab'), esc_html($search_query));
                } else {
                    esc_html_e('Find films, workshops, news, and more', 'saab');
                }
                ?>
            </p>
            
            <!-- Enhanced Search Form -->
            <div class="search-form-enhanced">
                <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                    <div class="search-input-group">
                        <input type="search" 
                               name="s" 
                               value="<?php echo esc_attr(get_search_query()); ?>" 
                               placeholder="<?php esc_attr_e('Search films, workshops, news...', 'saab'); ?>"
                               class="search-input-enhanced">
                        <button type="submit" class="search-btn-enhanced">
                            <i class="fas fa-search" aria-hidden="true"></i>
                        </button>
                    </div>
                    
                    <!-- Content Type Filters -->
                    <div class="search-filters">
                        <label class="search-filter-label"><?php esc_html_e('Search in:', 'saab'); ?></label>
                        <div class="search-filter-options">
                            <label class="search-filter-option">
                                <input type="checkbox" name="post_type[]" value="film" <?php checked(in_array('film', (array) $_GET['post_type'])); ?>>
                                <span><?php esc_html_e('Films', 'saab'); ?></span>
                            </label>
                            <label class="search-filter-option">
                                <input type="checkbox" name="post_type[]" value="training_workshop" <?php checked(in_array('training_workshop', (array) $_GET['post_type'])); ?>>
                                <span><?php esc_html_e('Workshops', 'saab'); ?></span>
                            </label>
                            <label class="search-filter-option">
                                <input type="checkbox" name="post_type[]" value="news" <?php checked(in_array('news', (array) $_GET['post_type'])); ?>>
                                <span><?php esc_html_e('News', 'saab'); ?></span>
                            </label>
                            <label class="search-filter-option">
                                <input type="checkbox" name="post_type[]" value="screening" <?php checked(in_array('screening', (array) $_GET['post_type'])); ?>>
                                <span><?php esc_html_e('Screenings', 'saab'); ?></span>
                            </label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<section class="section-enhanced section-white">
    <div class="container">
        <?php if (function_exists('saab_breadcrumbs')) saab_breadcrumbs(); ?>
        
        <!-- Search Results -->
        <div class="search-results">
            <?php
            $search_query = get_search_query();
            $post_types = isset($_GET['post_type']) ? (array) $_GET['post_type'] : array('film', 'training_workshop', 'news', 'screening');
            
            if ($search_query) {
                // Build search query
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                
                $args = array(
                    's' => $search_query,
                    'post_type' => $post_types,
                    'posts_per_page' => 12,
                    'paged' => $paged,
                    'post_status' => 'publish',
                    'orderby' => 'relevance',
                    'meta_query' => array(
                        'relation' => 'OR',
                        array(
                            'key' => '_saab_film_year',
                            'value' => $search_query,
                            'compare' => 'LIKE',
                        ),
                        array(
                            'key' => '_saab_training_workshop_location',
                            'value' => $search_query,
                            'compare' => 'LIKE',
                        ),
                        array(
                            'key' => '_saab_training_workshop_trainers',
                            'value' => $search_query,
                            'compare' => 'LIKE',
                        ),
                    ),
                );
                
                $search_query_obj = new WP_Query($args);
                
                if ($search_query_obj->have_posts()) {
                    // Group results by post type
                    $results_by_type = array();
                    
                    while ($search_query_obj->have_posts()) {
                        $search_query_obj->the_post();
                        $post_type = get_post_type();
                        if (!isset($results_by_type[$post_type])) {
                            $results_by_type[$post_type] = array();
                        }
                        $results_by_type[$post_type][] = get_post();
                    }
                    wp_reset_postdata();
                    
                    // Display results grouped by type
                    foreach ($results_by_type as $post_type => $posts) {
                        $type_labels = array(
                            'film' => __('Films', 'saab'),
                            'training_workshop' => __('Workshops', 'saab'),
                            'news' => __('News', 'saab'),
                            'screening' => __('Screenings', 'saab'),
                        );
                        
                        echo '<div class="search-results-section">';
                        echo '<h2 class="search-results-title">' . esc_html($type_labels[$post_type]) . ' (' . count($posts) . ')</h2>';
                        echo '<div class="search-results-grid grid-enhanced">';
                        
                        foreach ($posts as $post) {
                            setup_postdata($post);
                            
                            switch ($post_type) {
                                case 'film':
                                    get_template_part('template-parts/film-card');
                                    break;
                                case 'training_workshop':
                                    get_template_part('template-parts/workshop-card');
                                    break;
                                case 'news':
                                    get_template_part('template-parts/news-card');
                                    break;
                                case 'screening':
                                    get_template_part('template-parts/screening-card');
                                    break;
                            }
                        }
                        
                        echo '</div>';
                        echo '</div>';
                    }
                    
                    // Pagination
                    if ($search_query_obj->max_num_pages > 1) {
                        echo '<div class="pagination-enhanced">';
                        echo paginate_links(array(
                            'total' => $search_query_obj->max_num_pages,
                            'current' => $paged,
                            'prev_text' => '<i class="fas fa-chevron-left"></i> ' . __('Previous', 'saab'),
                            'next_text' => __('Next', 'saab') . ' <i class="fas fa-chevron-right"></i>',
                            'type' => 'list',
                        ));
                        echo '</div>';
                    }
                    
                } else {
                    // No results
                    echo '<div class="no-results">';
                    echo '<div class="no-results-icon">';
                    echo '<svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">';
                    echo '<circle cx="11" cy="11" r="8" stroke="currentColor" stroke-width="2"/>';
                    echo '<path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>';
                    echo '</svg>';
                    echo '</div>';
                    echo '<h3>' . esc_html__('No results found', 'saab') . '</h3>';
                    echo '<p>' . sprintf(esc_html__('Sorry, no results were found for "%s". Try different keywords or browse our content.', 'saab'), esc_html($search_query)) . '</p>';
                    
                    // Suggest popular content
                    echo '<div class="search-suggestions">';
                    echo '<h4>' . esc_html__('Popular Content', 'saab') . '</h4>';
                    echo '<div class="suggestions-grid">';
                    
                    $popular_posts = new WP_Query(array(
                        'post_type' => array('film', 'training_workshop', 'news'),
                        'posts_per_page' => 6,
                        'meta_key' => '_saab_featured',
                        'meta_value' => '1',
                        'orderby' => 'date',
                        'order' => 'DESC',
                    ));
                    
                    if ($popular_posts->have_posts()) {
                        while ($popular_posts->have_posts()) {
                            $popular_posts->the_post();
                            $post_type = get_post_type();
                            
                            echo '<div class="suggestion-item">';
                            echo '<a href="' . esc_url(get_permalink()) . '">';
                            if (has_post_thumbnail()) {
                                the_post_thumbnail('thumbnail');
                            }
                            echo '<h5>' . get_the_title() . '</h5>';
                            echo '<span class="suggestion-type">' . esc_html(ucfirst($post_type)) . '</span>';
                            echo '</a>';
                            echo '</div>';
                        }
                        wp_reset_postdata();
                    }
                    
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                // No search query
                echo '<div class="search-intro">';
                echo '<h2>' . esc_html__('Search Our Content', 'saab') . '</h2>';
                echo '<p>' . esc_html__('Use the search form above to find films, workshops, news, and screenings.', 'saab') . '</p>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</section>

<?php get_footer(); ?> 