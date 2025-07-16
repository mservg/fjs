<?php
/**
 * Jocelyne Saab Theme Functions - COMPLETE FILE
 * Following Prince Claus Fund design principles
 * Author: Karl Serag (karlserag)
 * Date: 2025-06-21 10:15:24 UTC
 * Version: 1.0.1 - Fixed meta box errors
 */

// Load SEO functions
require_once get_template_directory() . '/functions-seo.php';

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get related screenings based on film, location, and date
 */
if (!function_exists('saab_get_related_screenings')) {
function saab_get_related_screenings($post_id, $limit = 4) {
    $related_posts = array();
    
    // Get current screening data
    $current_film = get_post_meta($post_id, '_saab_screening_film', true);
    $current_location = get_post_meta($post_id, '_saab_screening_location', true);
    $current_date = get_post_meta($post_id, '_saab_screening_date', true);
    
    // Build query for related screenings
    $args = array(
        'post_type' => 'screening',
        'posts_per_page' => $limit * 2,
        'post__not_in' => array($post_id),
        'post_status' => 'publish',
        'meta_query' => array(),
    );
    
    // Add film filter if available
    if ($current_film) {
        $args['meta_query'][] = array(
            'key' => '_saab_screening_film',
            'value' => $current_film,
            'compare' => '=',
        );
    }
    
    // Add location filter if available
    if ($current_location) {
        $args['meta_query'][] = array(
            'key' => '_saab_screening_location',
            'value' => $current_location,
            'compare' => 'LIKE',
        );
    }
    
    // Add date filter if available (screenings within same month)
    if ($current_date) {
        $current_month = date('Y-m', strtotime($current_date));
        $args['meta_query'][] = array(
            'key' => '_saab_screening_date',
            'value' => $current_month,
            'compare' => 'LIKE',
        );
    }
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $related_posts[] = get_post();
        }
        wp_reset_postdata();
    }
    
    return array_slice($related_posts, 0, $limit);
}
}

/**
 * Get cross-type recommendations
 */
function saab_get_cross_type_recommendations($post_id, $current_type, $limit = 2) {
    $recommendations = array();
    
    // Get popular content from other types
    $other_types = array('film', 'training_workshop', 'news', 'screening');
    $other_types = array_diff($other_types, array($current_type));
    
    foreach ($other_types as $type) {
        $args = array(
            'post_type' => $type,
            'posts_per_page' => ceil($limit / count($other_types)),
            'post__not_in' => array($post_id),
            'post_status' => 'publish',
            'meta_key' => '_saab_featured',
            'meta_value' => '1',
            'orderby' => 'date',
            'order' => 'DESC',
        );
        
        $query = new WP_Query($args);
        
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $recommendations[] = get_post();
            }
            wp_reset_postdata();
        }
    }
    
    return array_slice($recommendations, 0, $limit);
}

// Theme setup
function saab_theme_setup() {
    // Make theme available for translation
    load_theme_textdomain('saab', get_template_directory() . '/languages');

    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Enable support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for core custom background feature
    add_theme_support('custom-background', apply_filters('saab_custom_background_args', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    )));

    // HTML5 markup support for search form, comment form, and comments
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add theme support for Post Formats
    add_theme_support('post-formats', array(
        'aside',
        'image',
        'video',
        'quote',
        'link',
        'gallery',
        'status',
        'audio',
        'chat',
    ));

    // Set up the WordPress core custom header feature
    add_theme_support('custom-header', apply_filters('saab_custom_header_args', array(
        'default-image'          => '',
        'default-text-color'     => '000000',
        'width'                  => 1920,
        'height'                 => 1080,
        'flex-height'            => true,
    )));

    // Add support for Block Styles
    add_theme_support('wp-block-styles');

    // Add support for full and wide align images
    add_theme_support('align-wide');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Enqueue editor styles
    add_editor_style('assets/css/editor-style.css');

    // Add support for responsive embedded content
    add_theme_support('responsive-embeds');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'saab'),
        'footer'  => esc_html__('Footer Menu', 'saab'),
        'social'  => esc_html__('Social Links Menu', 'saab'),
    ));

    // Add image sizes
    add_image_size('film-poster', 400, 600, true);           // For film posters
    add_image_size('film-poster-large', 800, 1200, true);    // Large film posters
    add_image_size('news-featured', 800, 450, true);         // News featured images
    add_image_size('news-thumbnail', 400, 225, true);        // News thumbnails
    add_image_size('partner-logo', 300, 150, false);         // Partner logos
    add_image_size('hero-background', 1920, 1080, true);     // Hero backgrounds
    add_image_size('gallery-thumb', 300, 300, true);         // Gallery thumbnails
    add_image_size('workshop-featured', 600, 400, true);     // Workshop featured images
    add_image_size('screening-featured', 600, 400, true);    // Screening featured images
    add_image_size('event-featured', 600, 400, true);        // Event images (legacy)
}
add_action('after_setup_theme', 'saab_theme_setup');

// Set the content width in pixels, based on the theme's design and stylesheet
function saab_content_width() {
    $GLOBALS['content_width'] = apply_filters('saab_content_width', 1200);
}
add_action('after_setup_theme', 'saab_content_width', 0);

// Enqueue scripts and styles
function saab_scripts() {
    // Main stylesheet
    wp_enqueue_style('saab-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Main CSS file
    wp_enqueue_style('saab-main-css', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0');
    
    // Google Fonts - Only Epilogue and Lora
    wp_enqueue_style('saab-fonts', 'https://fonts.googleapis.com/css2?family=Epilogue:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500&family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap', array(), null);
    
    // Font Awesome (for icons)
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css', array(), '6.0.0');
    
    // Enqueue Swiper for carousels
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css', array(), '8.0.0');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js', array(), '8.0.0', true);
    
    // Main theme scripts (depend on Swiper)
    wp_enqueue_script('saab-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery', 'swiper-js'), '1.0.0', true);
    
    // Enhanced scripts
    wp_enqueue_script('saab-enhanced', get_template_directory_uri() . '/assets/js/enhanced-main.js', array('jquery', 'swiper-js'), '1.0.0', true);
    
    // Localize script for AJAX
    wp_localize_script('saab-main', 'saabAjax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('saab_nonce'),
        'strings' => array(
            'error' => esc_html__('An error occurred. Please try again.', 'saab'),
            'loading' => esc_html__('Loading...', 'saab'),
            'load_more' => esc_html__('Load More', 'saab'),
            'no_more' => esc_html__('No more items to load.', 'saab'),
        ),
    ));
    
    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'saab_scripts');

function enqueue_saab_theme_scripts() {
  wp_enqueue_style('saab-main-css', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0.0');
  wp_enqueue_script('saab-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery', 'swiper-js'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'enqueue_saab_theme_scripts');

// Admin styles
function saab_admin_styles() {
    wp_enqueue_style('saab-admin', get_template_directory_uri() . '/assets/css/admin.css', array(), '1.0.0');
    wp_enqueue_script('media-upload');
    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'saab_admin_styles');

// Custom Post Types
function saab_register_post_types() {
    // Films
    register_post_type('film', array(
        'labels' => array(
            'name' => 'Films',
            'singular_name' => 'Film',
            'menu_name' => 'Films',
            'add_new' => 'Add New Film',
            'add_new_item' => 'Add New Film',
            'edit_item' => 'Edit Film',
            'new_item' => 'New Film',
            'view_item' => 'View Film',
            'view_items' => 'View Films',
            'search_items' => 'Search Films',
            'not_found' => 'No films found',
            'not_found_in_trash' => 'No films found in trash',
            'all_items' => 'All Films',
            'archives' => 'Film Archives',
            'attributes' => 'Film Attributes',
            'insert_into_item' => 'Insert into film',
            'uploaded_to_this_item' => 'Uploaded to this film',
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'films', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-video-alt3',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
        'show_in_rest' => true,
        'rest_base' => 'films',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
    ));

    // News
    register_post_type('news', array(
        'labels' => array(
            'name' => 'News',
            'singular_name' => 'News Article',
            'menu_name' => 'News',
            'add_new' => 'Add News',
            'add_new_item' => 'Add New News Article',
            'edit_item' => 'Edit News Article',
            'new_item' => 'New News Article',
            'view_item' => 'View News Article',
            'view_items' => 'View News',
            'search_items' => 'Search News',
            'not_found' => 'No news found',
            'not_found_in_trash' => 'No news found in trash',
            'all_items' => 'All News',
            'archives' => 'News Archives',
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'news', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-megaphone',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'author', 'comments', 'revisions'),
        'show_in_rest' => true,
        'rest_base' => 'news',
    ));

    // Trainings and Workshops (renamed from Workshops)
    register_post_type('training_workshop', array(
        'labels' => array(
            'name' => __('Trainings and Workshops', 'saab'),
            'singular_name' => __('Training/Workshop', 'saab'),
            'menu_name' => __('Trainings & Workshops', 'saab'),
            'add_new' => __('Add Training/Workshop', 'saab'),
            'add_new_item' => __('Add New Training/Workshop', 'saab'),
            'edit_item' => __('Edit Training/Workshop', 'saab'),
            'new_item' => __('New Training/Workshop', 'saab'),
            'view_item' => __('View Training/Workshop', 'saab'),
            'view_items' => __('View Trainings & Workshops', 'saab'),
            'search_items' => __('Search Trainings & Workshops', 'saab'),
            'not_found' => __('No trainings or workshops found', 'saab'),
            'not_found_in_trash' => __('No trainings or workshops found in trash', 'saab'),
            'all_items' => __('All Trainings & Workshops', 'saab'),
            'archives' => __('Trainings & Workshops Archives', 'saab'),
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'trainings-workshops', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 7,
        'menu_icon' => 'dashicons-welcome-learn-more',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
        'show_in_rest' => true,
        'rest_base' => 'trainings-workshops',
    ));

    // Publications
    register_post_type('publication', array(
        'labels' => array(
            'name' => 'Publications',
            'singular_name' => 'Publication',
            'menu_name' => 'Publications',
            'add_new' => 'Add Publication',
            'add_new_item' => 'Add New Publication',
            'edit_item' => 'Edit Publication',
            'new_item' => 'New Publication',
            'view_item' => 'View Publication',
            'view_items' => 'View Publications',
            'search_items' => 'Search Publications',
            'not_found' => 'No publications found',
            'not_found_in_trash' => 'No publications found in trash',
            'all_items' => 'All Publications',
            'archives' => 'Publication Archives',
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'publications', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 8,
        'menu_icon' => 'dashicons-book',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
        'show_in_rest' => true,
        'rest_base' => 'publications',
    ));

    // Partners
    register_post_type('partner', array(
        'labels' => array(
            'name' => 'Partners',
            'singular_name' => 'Partner',
            'menu_name' => 'Partners',
            'add_new' => 'Add Partner',
            'add_new_item' => 'Add New Partner',
            'edit_item' => 'Edit Partner',
            'new_item' => 'New Partner',
            'view_item' => 'View Partner',
            'view_items' => 'View Partners',
            'search_items' => 'Search Partners',
            'not_found' => 'No partners found',
            'not_found_in_trash' => 'No partners found in trash',
            'all_items' => 'All Partners',
            'archives' => 'Partner Archives',
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'partners', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 9,
        'menu_icon' => 'dashicons-groups',
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields', 'revisions'),
        'show_in_rest' => true,
        'rest_base' => 'partners',
    ));

    // Screenings CPT
    register_post_type('screening', array(
        'labels' => array(
            'name' => __('Screenings', 'saab'),
            'singular_name' => __('Screening', 'saab'),
            'menu_name' => __('Screenings', 'saab'),
            'add_new' => __('Add Screening', 'saab'),
            'add_new_item' => __('Add New Screening', 'saab'),
            'edit_item' => __('Edit Screening', 'saab'),
            'new_item' => __('New Screening', 'saab'),
            'view_item' => __('View Screening', 'saab'),
            'view_items' => __('View Screenings', 'saab'),
            'search_items' => __('Search Screenings', 'saab'),
            'not_found' => __('No screenings found', 'saab'),
            'not_found_in_trash' => __('No screenings found in trash', 'saab'),
            'all_items' => __('All Screenings', 'saab'),
            'archives' => __('Screenings Archives', 'saab'),
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'screenings', 'with_front' => false),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 11,
        'menu_icon' => 'dashicons-tickets-alt',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions'),
        'show_in_rest' => true,
        'rest_base' => 'screenings',
    ));
}
add_action('init', 'saab_register_post_types');

// Custom taxonomies
function saab_register_taxonomies() {
    // Film genres
    register_taxonomy('genre', 'film', array(
        'labels' => array(
            'name' => 'Genres',
            'singular_name' => 'Genre',
            'menu_name' => 'Genres',
            'all_items' => 'All Genres',
            'edit_item' => 'Edit Genre',
            'view_item' => 'View Genre',
            'update_item' => 'Update Genre',
            'add_new_item' => 'Add New Genre',
            'new_item_name' => 'New Genre Name',
            'parent_item' => 'Parent Genre',
            'parent_item_colon' => 'Parent Genre:',
            'search_items' => 'Search Genres',
            'popular_items' => 'Popular Genres',
            'not_found' => 'No genres found',
        ),
        'hierarchical' => true,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'genre', 'with_front' => false),
        'query_var' => true,
    ));

    // Film languages
    register_taxonomy('film_language', 'film', array(
        'labels' => array(
            'name' => 'Languages',
            'singular_name' => 'Language',
            'menu_name' => 'Languages',
            'all_items' => 'All Languages',
            'edit_item' => 'Edit Language',
            'view_item' => 'View Language',
            'update_item' => 'Update Language',
            'add_new_item' => 'Add New Language',
            'new_item_name' => 'New Language Name',
            'search_items' => 'Search Languages',
            'popular_items' => 'Popular Languages',
            'not_found' => 'No languages found',
        ),
        'hierarchical' => false,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'language', 'with_front' => false),
        'query_var' => true,
    ));

    // News categories
    register_taxonomy('news_category', 'news', array(
        'labels' => array(
            'name' => 'News Categories',
            'singular_name' => 'News Category',
            'menu_name' => 'Categories',
            'all_items' => 'All Categories',
            'edit_item' => 'Edit Category',
            'view_item' => 'View Category',
            'update_item' => 'Update Category',
            'add_new_item' => 'Add New Category',
            'new_item_name' => 'New Category Name',
            'parent_item' => 'Parent Category',
            'parent_item_colon' => 'Parent Category:',
            'search_items' => 'Search Categories',
            'popular_items' => 'Popular Categories',
            'not_found' => 'No categories found',
        ),
        'hierarchical' => true,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'news-category', 'with_front' => false),
        'query_var' => true,
    ));

    // Partnership types
    register_taxonomy('partnership_type', 'partner', array(
        'labels' => array(
            'name' => 'Partnership Types',
            'singular_name' => 'Partnership Type',
            'menu_name' => 'Types',
            'all_items' => 'All Types',
            'edit_item' => 'Edit Type',
            'view_item' => 'View Type',
            'update_item' => 'Update Type',
            'add_new_item' => 'Add New Type',
            'new_item_name' => 'New Type Name',
            'parent_item' => 'Parent Type',
            'parent_item_colon' => 'Parent Type:',
            'search_items' => 'Search Types',
            'popular_items' => 'Popular Types',
            'not_found' => 'No types found',
        ),
        'hierarchical' => true,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'partnership-type', 'with_front' => false),
        'query_var' => true,
    ));

    // Event types
    register_taxonomy('event_type', 'event', array(
        'labels' => array(
            'name' => 'Event Types',
            'singular_name' => 'Event Type',
            'menu_name' => 'Types',
            'all_items' => 'All Types',
            'edit_item' => 'Edit Type',
            'view_item' => 'View Type',
            'update_item' => 'Update Type',
            'add_new_item' => 'Add New Type',
            'new_item_name' => 'New Type Name',
            'search_items' => 'Search Types',
            'popular_items' => 'Popular Types',
            'not_found' => 'No types found',
        ),
        'hierarchical' => true,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_rest' => true,
        'rewrite' => array('slug' => 'event-type', 'with_front' => false),
        'query_var' => true,
    ));
}
add_action('init', 'saab_register_taxonomies');

// Custom Walker for Navigation Menu
class Saab_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    // Start the element output
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = ($depth) ? str_repeat($t, $depth) : '';
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        // Add active class for current menu item
        if (in_array('current-menu-item', $classes) || in_array('current-menu-parent', $classes)) {
            $classes[] = 'active';
        }
        
        // Add dropdown class if has children
        if (in_array('menu-item-has-children', $classes)) {
            $classes[] = 'has-dropdown';
        }
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        $output .= $indent . '<li' . $id . $class_names .'>';
        
        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';
        
        $item_output = isset($args->before) ? $args->before ?? '' : '';
        $item_output .= '<a' . $attributes . '>';
        $item_output .= (isset($args->link_before) ? $args->link_before ?? '' : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after ?? '' : '');
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after ?? '' : '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    
    // Start the sub-menu
    public function start_lvl(&$output, $depth = 0, $args = null) {
        if (!empty($args->mega_menu) && $depth === 0) {
            $output .= "\n<div class=\"menu-overlay-grid\">\n";
        } else {
            $output .= "\n<ul class=\"sub-menu\">\n";
        }
    }
    
    // End the sub-menu
    public function end_lvl(&$output, $depth = 0, $args = null) {
        if (!empty($args->mega_menu) && $depth === 0) {
            $output .= "</div>\n";
        } else {
            $output .= "</ul>\n";
        }
    }
    
    // End the element
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        if (!empty($args->mega_menu) && $depth === 0) {
            if (in_array('menu-item-has-children', (array) $item->classes)) {
                $output .= '</ul>';
            }
            $output .= '</div>';
        } else {
            $output .= '</li>';
        }
    }
}

// Helper to inject mega_menu arg for overlay menu
function saab_mega_menu_args($args) {
    if (!empty($args['menu_class']) && strpos($args['menu_class'], 'overlay-nav-menu') !== false) {
        $args['walker'] = new Saab_Walker_Nav_Menu();
        $args['mega_menu'] = true;
    }
    return $args;
}
add_filter('wp_nav_menu_args', 'saab_mega_menu_args');

// Add meta boxes for custom fields
function saab_add_meta_boxes() {
    // Film meta box
    add_meta_box(
        'film-details',
        'Film Details',
        'saab_film_meta_box',
        'film',
        'normal',
        'high'
    );
    
    // Trainings and Workshops meta box
    add_meta_box(
        'training_workshop-details',
        __('Training/Workshop Details', 'saab'),
        'saab_training_workshop_meta_box',
        'training_workshop',
        'normal',
        'high'
    );
    
    // Screening meta box
    add_meta_box(
        'screening-details',
        __('Screening Details', 'saab'),
        'saab_screening_meta_box',
        'screening',
        'normal',
        'high'
    );
    
    // Publication meta box
    add_meta_box(
        'publication-details',
        'Publication Details',
        'saab_publication_meta_box',
        'publication',
        'normal',
        'high'
    );
    
    // Partner meta box
    add_meta_box(
        'partner-details',
        'Partner Details',
        'saab_partner_meta_box',
        'partner',
        'normal',
        'high'
    );
    
    // Event meta box
    add_meta_box(
        'event-details',
        'Event Details',
        'saab_event_meta_box',
        'event',
        'normal',
        'high'
    );
    
    // News meta box
    add_meta_box(
        'news-details',
        'News Details',
        'saab_news_meta_box',
        'news',
        'normal',
        'high'
    );
    
    // Hero meta box for pages
    add_meta_box(
        'hero-settings',
        'Hero Section Settings',
        'saab_hero_meta_box',
        array('page', 'film', 'news'),
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'saab_add_meta_boxes');

// News meta box callback
function saab_news_meta_box($post) {
    wp_nonce_field('saab_news_meta_box', 'saab_news_meta_box_nonce');
    
    $featured = get_post_meta($post->ID, '_saab_news_featured', true);
    $source = get_post_meta($post->ID, '_saab_news_source', true);
    $external_url = get_post_meta($post->ID, '_saab_news_external_url', true);
    $badge = get_post_meta($post->ID, '_saab_news_badge', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="news_featured">Featured News</label></th>
            <td>
                <input type="checkbox" id="news_featured" name="news_featured" value="1" <?php checked($featured, 1); ?> />
                <label for="news_featured">Mark as featured news</label>
            </td>
        </tr>
        <tr>
            <th><label for="news_badge">News Badge</label></th>
            <td><input type="text" id="news_badge" name="news_badge" value="<?php echo esc_attr($badge); ?>" placeholder="e.g., Announcement, Press Release, Exhibition" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="news_source">News Source</label></th>
            <td><input type="text" id="news_source" name="news_source" value="<?php echo esc_attr($source); ?>" placeholder="e.g., The Guardian, BBC" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="news_external_url">External URL</label></th>
            <td><input type="url" id="news_external_url" name="news_external_url" value="<?php echo esc_attr($external_url); ?>" placeholder="https://..." class="regular-text" /></td>
        </tr>
    </table>
    <?php
}

// Film meta box callback
function saab_film_meta_box($post) {
    wp_nonce_field('saab_film_meta_box', 'saab_film_meta_box_nonce');
    
    $year = get_post_meta($post->ID, '_saab_film_year', true);
    $duration = get_post_meta($post->ID, '_saab_film_duration', true);
    $format = get_post_meta($post->ID, '_saab_film_format', true);
    $country = get_post_meta($post->ID, '_saab_film_country', true);
    $language = get_post_meta($post->ID, '_saab_film_language', true);
    $production = get_post_meta($post->ID, '_saab_film_production', true);
    $trailer_url = get_post_meta($post->ID, '_saab_film_trailer_url', true);
    $awards = get_post_meta($post->ID, '_saab_film_awards', true);
    $director = get_post_meta($post->ID, '_saab_film_director', true);
    $dop = get_post_meta($post->ID, '_saab_film_dop', true);
    $editor = get_post_meta($post->ID, '_saab_film_editor', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="film_year">Year</label></th>
            <td><input type="number" id="film_year" name="film_year" value="<?php echo esc_attr($year); ?>" min="1900" max="<?php echo date('Y'); ?>" class="small-text" /></td>
        </tr>
        <tr>
            <th><label for="film_duration">Duration</label></th>
            <td><input type="text" id="film_duration" name="film_duration" value="<?php echo esc_attr($duration); ?>" placeholder="e.g., 90 min" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="film_format">Format</label></th>
            <td>
                <select id="film_format" name="film_format" class="regular-text">
                    <option value="">Select Format</option>
                    <option value="documentary" <?php selected($format, 'documentary'); ?>>Documentary</option>
                    <option value="feature" <?php selected($format, 'feature'); ?>>Feature Film</option>
                    <option value="short" <?php selected($format, 'short'); ?>>Short Film</option>
                    <option value="experimental" <?php selected($format, 'experimental'); ?>>Experimental</option>
                    <option value="animation" <?php selected($format, 'animation'); ?>>Animation</option>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="film_country">Country</label></th>
            <td><input type="text" id="film_country" name="film_country" value="<?php echo esc_attr($country); ?>" placeholder="e.g., Lebanon, France" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="film_language">Language</label></th>
            <td><input type="text" id="film_language" name="film_language" value="<?php echo esc_attr($language); ?>" placeholder="e.g., Arabic, French" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="film_production">Production</label></th>
            <td><input type="text" id="film_production" name="film_production" value="<?php echo esc_attr($production); ?>" placeholder="Production company" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="film_trailer_url">Trailer URL</label></th>
            <td><input type="url" id="film_trailer_url" name="film_trailer_url" value="<?php echo esc_attr($trailer_url); ?>" placeholder="https://..." class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="film_awards">Awards</label></th>
            <td><textarea id="film_awards" name="film_awards" rows="3" cols="50" class="large-text"><?php echo esc_textarea($awards); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="film_director">Director</label></th>
            <td><input type="text" id="film_director" name="film_director" value="<?php echo esc_attr($director); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="film_dop">DOP (Director of Photography)</label></th>
            <td><input type="text" id="film_dop" name="film_dop" value="<?php echo esc_attr($dop); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="film_editor">Editor</label></th>
            <td><input type="text" id="film_editor" name="film_editor" value="<?php echo esc_attr($editor); ?>" class="regular-text" /></td>
        </tr>
    </table>
    <?php
}

// Trainings and Workshops meta box callback
function saab_training_workshop_meta_box($post) {
    wp_nonce_field('saab_training_workshop_meta_box', 'saab_training_workshop_meta_box_nonce');
    $date = get_post_meta($post->ID, '_saab_training_workshop_date', true);
    $time = get_post_meta($post->ID, '_saab_training_workshop_time', true);
    $location = get_post_meta($post->ID, '_saab_training_workshop_location', true);
    $duration = get_post_meta($post->ID, '_saab_training_workshop_duration', true);
    $price = get_post_meta($post->ID, '_saab_training_workshop_price', true);
    $capacity = get_post_meta($post->ID, '_saab_training_workshop_capacity', true);
    $project_manager = get_post_meta($post->ID, '_saab_training_workshop_project_manager', true);
    $trainers = get_post_meta($post->ID, '_saab_training_workshop_trainers', true);
    $trainer = get_post_meta($post->ID, '_saab_training_workshop_trainer', true);
    $gallery = get_post_meta($post->ID, '_saab_training_workshop_gallery', true);
    $participants = get_post_meta($post->ID, '_saab_training_workshop_participants', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="training_workshop_date"><?php _e('Date', 'saab'); ?></label></th>
            <td><input type="date" id="training_workshop_date" name="training_workshop_date" value="<?php echo esc_attr($date); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="training_workshop_time"><?php _e('Time', 'saab'); ?></label></th>
            <td><input type="text" id="training_workshop_time" name="training_workshop_time" value="<?php echo esc_attr($time); ?>" placeholder="e.g., 2:00 PM - 6:00 PM" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="training_workshop_location"><?php _e('Location', 'saab'); ?></label></th>
            <td><input type="text" id="training_workshop_location" name="training_workshop_location" value="<?php echo esc_attr($location); ?>" placeholder="e.g., Beirut, Lebanon" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="training_workshop_duration"><?php _e('Duration', 'saab'); ?></label></th>
            <td><input type="text" id="training_workshop_duration" name="training_workshop_duration" value="<?php echo esc_attr($duration); ?>" placeholder="e.g., 4 hours, 2 days" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="training_workshop_price"><?php _e('Price', 'saab'); ?></label></th>
            <td><input type="text" id="training_workshop_price" name="training_workshop_price" value="<?php echo esc_attr($price); ?>" placeholder="e.g., $100, Free" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="training_workshop_capacity"><?php _e('Capacity', 'saab'); ?></label></th>
            <td><input type="number" id="training_workshop_capacity" name="training_workshop_capacity" value="<?php echo esc_attr($capacity); ?>" placeholder="e.g., 20" class="small-text" /></td>
        </tr>
        <tr>
            <th><label for="training_workshop_project_manager"><?php _e('Project Manager', 'saab'); ?></label></th>
            <td><input type="text" id="training_workshop_project_manager" name="training_workshop_project_manager" value="<?php echo esc_attr($project_manager); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="training_workshop_trainers"><?php _e('Trainers (comma-separated)', 'saab'); ?></label></th>
            <td><textarea id="training_workshop_trainers" name="training_workshop_trainers" rows="2" class="large-text"><?php echo esc_textarea($trainers); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="training_workshop_trainer"><?php _e('Trainer (single)', 'saab'); ?></label></th>
            <td><input type="text" id="training_workshop_trainer" name="training_workshop_trainer" value="<?php echo esc_attr($trainer); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="training_workshop_gallery"><?php _e('Gallery Images', 'saab'); ?></label></th>
            <td>
                <input type="hidden" id="training_workshop_gallery" name="training_workshop_gallery" value="<?php echo esc_attr($gallery); ?>" />
                <button type="button" class="button" id="training_workshop_gallery_button"><?php _e('Add Images', 'saab'); ?></button>
                <div id="training_workshop_gallery_preview">
                    <?php
                    if ($gallery) {
                        $ids = explode(',', $gallery);
                        foreach ($ids as $id) {
                            $img = wp_get_attachment_image($id, 'gallery-thumb');
                            if ($img) echo $img;
                        }
                    }
                    ?>
                </div>
            </td>
        </tr>
        <tr>
            <th><label for="training_workshop_participants"><?php _e('Participants', 'saab'); ?></label></th>
            <td><textarea id="training_workshop_participants" name="training_workshop_participants" rows="2" class="large-text"><?php echo esc_textarea($participants); ?></textarea></td>
        </tr>
    </table>
    <script>
    jQuery(document).ready(function($) {
        $('#training_workshop_gallery_button').click(function(e) {
            e.preventDefault();
            var frame = wp.media({
                title: '<?php _e('Select Gallery Images', 'saab'); ?>',
                multiple: true,
                library: { type: 'image' }
            });
            frame.on('select', function() {
                var selection = frame.state().get('selection');
                var ids = [];
                var preview = '';
                selection.each(function(attachment) {
                    ids.push(attachment.id);
                    preview += '<img src="' + attachment.attributes.sizes.thumbnail.url + '" style="max-width:100px; margin:2px;" />';
                });
                $('#training_workshop_gallery').val(ids.join(','));
                $('#training_workshop_gallery_preview').html(preview);
            });
            frame.open();
        });
    });
    </script>
    <?php
}

// Screening meta box callback
function saab_screening_meta_box($post) {
    wp_nonce_field('saab_screening_meta_box', 'saab_screening_meta_box_nonce');
    $date = get_post_meta($post->ID, '_saab_screening_date', true);
    $time = get_post_meta($post->ID, '_saab_screening_time', true);
    $location = get_post_meta($post->ID, '_saab_screening_location', true);
    $venue = get_post_meta($post->ID, '_saab_screening_venue', true);
    $film = get_post_meta($post->ID, '_saab_screening_film', true);
    $ticket_url = get_post_meta($post->ID, '_saab_screening_ticket_url', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="screening_date"><?php _e('Screening Date', 'saab'); ?></label></th>
            <td><input type="date" id="screening_date" name="screening_date" value="<?php echo esc_attr($date); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="screening_time"><?php _e('Screening Time', 'saab'); ?></label></th>
            <td><input type="text" id="screening_time" name="screening_time" value="<?php echo esc_attr($time); ?>" placeholder="e.g., 7:00 PM" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="screening_location"><?php _e('Location', 'saab'); ?></label></th>
            <td><input type="text" id="screening_location" name="screening_location" value="<?php echo esc_attr($location); ?>" placeholder="City, Country" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="screening_venue"><?php _e('Venue', 'saab'); ?></label></th>
            <td><input type="text" id="screening_venue" name="screening_venue" value="<?php echo esc_attr($venue); ?>" placeholder="Venue name" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="screening_film"><?php _e('Film', 'saab'); ?></label></th>
            <td><input type="text" id="screening_film" name="screening_film" value="<?php echo esc_attr($film); ?>" placeholder="Film title" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="screening_ticket_url"><?php _e('Ticket URL', 'saab'); ?></label></th>
            <td><input type="url" id="screening_ticket_url" name="screening_ticket_url" value="<?php echo esc_attr($ticket_url); ?>" placeholder="https://..." class="regular-text" /></td>
        </tr>
    </table>
    <?php
}

// Publication meta box callback
function saab_publication_meta_box($post) {
    wp_nonce_field('saab_publication_meta_box', 'saab_publication_meta_box_nonce');
    
    $author = get_post_meta($post->ID, '_saab_publication_author', true);
    $year = get_post_meta($post->ID, '_saab_publication_year', true);
    $publisher = get_post_meta($post->ID, '_saab_publication_publisher', true);
    $isbn = get_post_meta($post->ID, '_saab_publication_isbn', true);
    $pages = get_post_meta($post->ID, '_saab_publication_pages', true);
    $language = get_post_meta($post->ID, '_saab_publication_language', true);
    $purchase_url = get_post_meta($post->ID, '_saab_publication_purchase_url', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="publication_author">Author(s)</label></th>
            <td><input type="text" id="publication_author" name="publication_author" value="<?php echo esc_attr($author); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="publication_year">Publication Year</label></th>
            <td><input type="number" id="publication_year" name="publication_year" value="<?php echo esc_attr($year); ?>" min="1900" max="<?php echo date('Y'); ?>" class="small-text" /></td>
        </tr>
        <tr>
            <th><label for="publication_publisher">Publisher</label></th>
            <td><input type="text" id="publication_publisher" name="publication_publisher" value="<?php echo esc_attr($publisher); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="publication_isbn">ISBN</label></th>
            <td><input type="text" id="publication_isbn" name="publication_isbn" value="<?php echo esc_attr($isbn); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="publication_pages">Pages</label></th>
            <td><input type="number" id="publication_pages" name="publication_pages" value="<?php echo esc_attr($pages); ?>" class="small-text" /></td>
        </tr>
        <tr>
            <th><label for="publication_language">Language</label></th>
            <td><input type="text" id="publication_language" name="publication_language" value="<?php echo esc_attr($language); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="publication_purchase_url">Purchase URL</label></th>
            <td><input type="url" id="publication_purchase_url" name="publication_purchase_url" value="<?php echo esc_attr($purchase_url); ?>" class="regular-text" /></td>
        </tr>
    </table>
    <?php
}

// Partner meta box callback
function saab_partner_meta_box($post) {
    wp_nonce_field('saab_partner_meta_box', 'saab_partner_meta_box_nonce');
    
    $website = get_post_meta($post->ID, '_saab_partner_website', true);
    $contact_email = get_post_meta($post->ID, '_saab_partner_contact_email', true);
    $contact_phone = get_post_meta($post->ID, '_saab_partner_contact_phone', true);
    $location = get_post_meta($post->ID, '_saab_partner_location', true);
    $partnership_since = get_post_meta($post->ID, '_saab_partner_since', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="partner_website">Website URL</label></th>
            <td><input type="url" id="partner_website" name="partner_website" value="<?php echo esc_attr($website); ?>" placeholder="https://example.com" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="partner_contact_email">Contact Email</label></th>
            <td><input type="email" id="partner_contact_email" name="partner_contact_email" value="<?php echo esc_attr($contact_email); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="partner_contact_phone">Contact Phone</label></th>
            <td><input type="tel" id="partner_contact_phone" name="partner_contact_phone" value="<?php echo esc_attr($contact_phone); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="partner_location">Location</label></th>
            <td><input type="text" id="partner_location" name="partner_location" value="<?php echo esc_attr($location); ?>" placeholder="City, Country" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="partner_since">Partnership Since</label></th>
            <td><input type="number" id="partner_since" name="partner_since" value="<?php echo esc_attr($partnership_since); ?>" min="1900" max="<?php echo date('Y'); ?>" class="small-text" /></td>
        </tr>
    </table>
    <?php
}

// Event meta box callback
function saab_event_meta_box($post) {
    wp_nonce_field('saab_event_meta_box', 'saab_event_meta_box_nonce');
    
    $date = get_post_meta($post->ID, '_saab_event_date', true);
    $time = get_post_meta($post->ID, '_saab_event_time', true);
    $location = get_post_meta($post->ID, '_saab_event_location', true);
    $venue = get_post_meta($post->ID, '_saab_event_venue', true);
    $ticket_url = get_post_meta($post->ID, '_saab_event_ticket_url', true);
    $price = get_post_meta($post->ID, '_saab_event_price', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="event_date">Event Date</label></th>
            <td><input type="date" id="event_date" name="event_date" value="<?php echo esc_attr($date); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="event_time">Event Time</label></th>
            <td><input type="text" id="event_time" name="event_time" value="<?php echo esc_attr($time); ?>" placeholder="e.g., 7:00 PM" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="event_location">Location</label></th>
            <td><input type="text" id="event_location" name="event_location" value="<?php echo esc_attr($location); ?>" placeholder="City, Country" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="event_venue">Venue</label></th>
            <td><input type="text" id="event_venue" name="event_venue" value="<?php echo esc_attr($venue); ?>" placeholder="Venue name" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="event_ticket_url">Ticket URL</label></th>
            <td><input type="url" id="event_ticket_url" name="event_ticket_url" value="<?php echo esc_attr($ticket_url); ?>" placeholder="https://..." class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="event_price">Ticket Price</label></th>
            <td><input type="text" id="event_price" name="event_price" value="<?php echo esc_attr($price); ?>" placeholder="e.g., $25, Free" class="regular-text" /></td>
        </tr>
    </table>
    <?php
}

// Hero meta box callback
function saab_hero_meta_box($post) {
    wp_nonce_field('saab_hero_meta_box', 'saab_hero_meta_box_nonce');
    
    $hero_video = get_post_meta($post->ID, '_saab_hero_video', true);
    $hero_image = get_post_meta($post->ID, '_saab_hero_image', true);
    $hero_title = get_post_meta($post->ID, '_saab_hero_title', true);
    $hero_subtitle = get_post_meta($post->ID, '_saab_hero_subtitle', true);
    ?>
    <table class="form-table">
        <tr>
            <th><label for="hero_video">Hero Video URL</label></th>
            <td><input type="url" id="hero_video" name="hero_video" value="<?php echo esc_attr($hero_video); ?>" placeholder="https://..." class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="hero_image">Hero Background Image</label></th>
            <td>
                <input type="hidden" id="hero_image" name="hero_image" value="<?php echo esc_attr($hero_image); ?>" />
                <button type="button" class="button" id="hero_image_button">Choose Image</button>
                <div id="hero_image_preview">
                    <?php if ($hero_image) : ?>
                        <img src="<?php echo esc_url($hero_image); ?>" style="max-width: 300px; height: auto;" />
                    <?php endif; ?>
                </div>
            </td>
        </tr>
        <tr>
            <th><label for="hero_title">Custom Hero Title</label></th>
            <td><input type="text" id="hero_title" name="hero_title" value="<?php echo esc_attr($hero_title); ?>" placeholder="Leave empty to use page title" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="hero_subtitle">Hero Subtitle</label></th>
            <td><textarea id="hero_subtitle" name="hero_subtitle" rows="3" cols="50" class="large-text" placeholder="Optional subtitle"><?php echo esc_textarea($hero_subtitle); ?></textarea></td>
        </tr>
    </table>
    
    <script>
    jQuery(document).ready(function($) {
        $('#hero_image_button').click(function(e) {
            e.preventDefault();
            var image_frame = wp.media({
                title: 'Select Hero Image',
                multiple: false,
                library: {
                    type: 'image',
                }
            });
            image_frame.on('select', function() {
                var selection = image_frame.state().get('selection');
                var attachment = selection.first().toJSON();
                $('#hero_image').val(attachment.url);
                $('#hero_image_preview').html('<img src="' + attachment.url + '" style="max-width: 300px; height: auto;" />');
            });
            image_frame.open();
        });
    });
    </script>
    <?php
}

// Save meta box data
function saab_save_meta_boxes($post_id) {
    // Check if user has permission to edit the post
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // If this is an autosave, don't do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // News meta box
    if (isset($_POST['saab_news_meta_box_nonce']) && wp_verify_nonce($_POST['saab_news_meta_box_nonce'], 'saab_news_meta_box')) {
        if (isset($_POST['news_featured'])) {
            update_post_meta($post_id, '_saab_news_featured', 1);
        } else {
            delete_post_meta($post_id, '_saab_news_featured');
        }
        if (isset($_POST['news_badge'])) {
            update_post_meta($post_id, '_saab_news_badge', sanitize_text_field($_POST['news_badge']));
        }
        if (isset($_POST['news_source'])) {
            update_post_meta($post_id, '_saab_news_source', sanitize_text_field($_POST['news_source']));
        }
        if (isset($_POST['news_external_url'])) {
            update_post_meta($post_id, '_saab_news_external_url', esc_url_raw($_POST['news_external_url']));
        }
    }

    // Film meta box
    if (isset($_POST['saab_film_meta_box_nonce']) && wp_verify_nonce($_POST['saab_film_meta_box_nonce'], 'saab_film_meta_box')) {
        $film_fields = array('film_year', 'film_duration', 'film_format', 'film_country', 'film_language', 'film_production', 'film_trailer_url', 'film_awards', 'film_director', 'film_dop', 'film_editor');
        foreach ($film_fields as $field) {
            if (isset($_POST[$field])) {
                $value = ($field === 'film_trailer_url') ? esc_url_raw($_POST[$field]) : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, '_saab_' . $field, $value);
            }
        }
    }
    
    // Trainings and Workshops meta box
    if (isset($_POST['saab_training_workshop_meta_box_nonce']) && wp_verify_nonce($_POST['saab_training_workshop_meta_box_nonce'], 'saab_training_workshop_meta_box')) {
        $fields = array('training_workshop_date', 'training_workshop_time', 'training_workshop_location', 'training_workshop_duration', 'training_workshop_price', 'training_workshop_capacity', 'training_workshop_project_manager', 'training_workshop_trainers', 'training_workshop_trainer', 'training_workshop_gallery', 'training_workshop_participants');
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = ($field === 'training_workshop_gallery') ? sanitize_textarea_field($_POST[$field]) : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, '_saab_' . $field, $value);
            }
        }
    }
    
    // Screening meta box
    if (isset($_POST['saab_screening_meta_box_nonce']) && wp_verify_nonce($_POST['saab_screening_meta_box_nonce'], 'saab_screening_meta_box')) {
        $fields = array('screening_date', 'screening_time', 'screening_location', 'screening_venue', 'screening_film', 'screening_ticket_url');
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = ($field === 'screening_ticket_url') ? esc_url_raw($_POST[$field]) : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, '_saab_' . $field, $value);
            }
        }
    }
    
    // Publication meta box
    if (isset($_POST['saab_publication_meta_box_nonce']) && wp_verify_nonce($_POST['saab_publication_meta_box_nonce'], 'saab_publication_meta_box')) {
        $publication_fields = array('publication_author', 'publication_year', 'publication_publisher', 'publication_isbn', 'publication_pages', 'publication_language', 'publication_purchase_url');
        foreach ($publication_fields as $field) {
            if (isset($_POST[$field])) {
                $value = ($field === 'publication_purchase_url') ? esc_url_raw($_POST[$field]) : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, '_saab_' . $field, $value);
            }
        }
    }
    
    // Partner meta box
    if (isset($_POST['saab_partner_meta_box_nonce']) && wp_verify_nonce($_POST['saab_partner_meta_box_nonce'], 'saab_partner_meta_box')) {
        $partner_fields = array('partner_website', 'partner_contact_email', 'partner_contact_phone', 'partner_location', 'partner_since');
        foreach ($partner_fields as $field) {
            if (isset($_POST[$field])) {
                if ($field === 'partner_website') {
                    $value = esc_url_raw($_POST[$field]);
                } elseif ($field === 'partner_contact_email') {
                    $value = sanitize_email($_POST[$field]);
                } else {
                    $value = sanitize_text_field($_POST[$field]);
                }
                update_post_meta($post_id, '_saab_' . $field, $value);
            }
        }
    }
    
    // Event meta box
    if (isset($_POST['saab_event_meta_box_nonce']) && wp_verify_nonce($_POST['saab_event_meta_box_nonce'], 'saab_event_meta_box')) {
        $event_fields = array('event_date', 'event_time', 'event_location', 'event_venue', 'event_ticket_url', 'event_price');
        foreach ($event_fields as $field) {
            if (isset($_POST[$field])) {
                $value = ($field === 'event_ticket_url') ? esc_url_raw($_POST[$field]) : sanitize_text_field($_POST[$field]);
                update_post_meta($post_id, '_saab_' . $field, $value);
            }
        }
    }
    
    // Hero meta box
    if (isset($_POST['saab_hero_meta_box_nonce']) && wp_verify_nonce($_POST['saab_hero_meta_box_nonce'], 'saab_hero_meta_box')) {
        if (isset($_POST['hero_video'])) {
            update_post_meta($post_id, '_saab_hero_video', esc_url_raw($_POST['hero_video']));
        }
        if (isset($_POST['hero_image'])) {
            update_post_meta($post_id, '_saab_hero_image', esc_url_raw($_POST['hero_image']));
        }
        if (isset($_POST['hero_title'])) {
            update_post_meta($post_id, '_saab_hero_title', sanitize_text_field($_POST['hero_title']));
        }
        if (isset($_POST['hero_subtitle'])) {
            update_post_meta($post_id, '_saab_hero_subtitle', sanitize_textarea_field($_POST['hero_subtitle']));
        }
    }
}
add_action('save_post', 'saab_save_meta_boxes');

// Widget areas
function saab_widgets_init() {
    register_sidebar(array(
        'name'          => esc_html__('Sidebar', 'saab'),
        'id'            => 'sidebar-1',
        'description'   => esc_html__('Add widgets here.', 'saab'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    // Footer widget areas
    for ($i = 1; $i <= 4; $i++) {
        register_sidebar(array(
            'name'          => sprintf(esc_html__('Footer %d', 'saab'), $i),
            'id'            => 'footer-' . $i,
            'description'   => sprintf(esc_html__('Footer widget area %d.', 'saab'), $i),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ));
    }
}
add_action('widgets_init', 'saab_widgets_init');

// Customizer
function saab_customize_register($wp_customize) {
    // Hero Section
    $wp_customize->add_section('hero_section', array(
        'title'    => __('Hero Section', 'saab'),
        'priority' => 30,
    ));

    // Hero Video URL
    $wp_customize->add_setting('hero_video_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('hero_video_url', array(
        'label'    => __('Hero Video URL', 'saab'),
        'section'  => 'hero_section',
        'type'     => 'url',
        'description' => __('URL to MP4 video file for hero background', 'saab'),
    ));

    // Hero Background Image
    $wp_customize->add_setting('hero_background_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_background_image', array(
        'label'    => __('Hero Background Image (Fallback)', 'saab'),
        'section'  => 'hero_section',
        'description' => __('Fallback image if video is not available', 'saab'),
    )));

    // Hero Title
    $wp_customize->add_setting('hero_title', array(
        'default'           => get_bloginfo('name'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('hero_title', array(
        'label'    => __('Hero Title', 'saab'),
        'section'  => 'hero_section',
        'type'     => 'text',
    ));

    // Hero Subtitle
    $wp_customize->add_setting('hero_subtitle', array(
        'default'           => get_bloginfo('description'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('hero_subtitle', array(
        'label'    => __('Hero Subtitle', 'saab'),
        'section'  => 'hero_section',
        'type'     => 'textarea',
    ));

    // Rotating Text
    $wp_customize->add_setting('hero_rotating_texts', array(
        'default'           => 'Filmmaker|Artist|Visionary',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('hero_rotating_texts', array(
        'label'       => __('Rotating Text (separated by |)', 'saab'),
    ));
}

/**
 * Enhanced AJAX handlers for advanced filtering
 */
function saab_enhanced_filter_films() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'saab_ajax_nonce')) {
        wp_die('Security check failed');
    }

    $filters = json_decode(stripslashes($_POST['filters']), true);
    
    // Build query args
    $args = array(
        'post_type' => 'film',
        'posts_per_page' => 12,
        'post_status' => 'publish',
        'meta_query' => array(),
        'tax_query' => array(),
    );

    // Apply filters
    if (!empty($filters['genre']) && $filters['genre'] !== 'all') {
        $args['tax_query'][] = array(
            'taxonomy' => 'genre',
            'field' => 'slug',
            'terms' => $filters['genre'],
        );
    }

    if (!empty($filters['language']) && $filters['language'] !== 'all') {
        $args['tax_query'][] = array(
            'taxonomy' => 'film_language',
            'field' => 'slug',
            'terms' => $filters['language'],
        );
    }

    if (!empty($filters['year']) && $filters['year'] !== 'all') {
        $args['meta_query'][] = array(
            'key' => '_saab_film_year',
            'value' => $filters['year'],
            'compare' => '=',
        );
    }

    if (!empty($filters['search'])) {
        $args['s'] = sanitize_text_field($filters['search']);
    }

    // Apply sorting
    switch ($filters['sort']) {
        case 'date-asc':
            $args['orderby'] = 'date';
            $args['order'] = 'ASC';
            break;
        case 'title-asc':
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
            break;
        case 'title-desc':
            $args['orderby'] = 'title';
            $args['order'] = 'DESC';
            break;
        case 'year-desc':
            $args['meta_key'] = '_saab_film_year';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        case 'year-asc':
            $args['meta_key'] = '_saab_film_year';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
            break;
        default:
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
    }

    $query = new WP_Query($args);
    $html = '';
    $count = $query->found_posts;

    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/film-card');
        }
        $html = ob_get_clean();
    } else {
        $html = '<div class="no-results">
            <div class="no-results-icon">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2" stroke="currentColor" stroke-width="2"/>
                    <line x1="8" y1="21" x2="16" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <line x1="12" y1="17" x2="12" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h3>' . esc_html__('No films found', 'saab') . '</h3>
            <p>' . esc_html__('Try adjusting your filters or search terms.', 'saab') . '</p>
        </div>';
    }

    wp_reset_postdata();

    wp_send_json_success(array(
        'html' => $html,
        'count' => $count,
    ));
}
add_action('wp_ajax_enhanced_filter_films', 'saab_enhanced_filter_films');
add_action('wp_ajax_nopriv_enhanced_filter_films', 'saab_enhanced_filter_films');

/**
 * Load more films for infinite scroll
 */
function saab_load_more_films() {
    if (!wp_verify_nonce($_POST['nonce'], 'saab_ajax_nonce')) {
        wp_die('Security check failed');
    }

    $page = intval($_POST['page']);
    
    $args = array(
        'post_type' => 'film',
        'posts_per_page' => 12,
        'paged' => $page,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    );

    $query = new WP_Query($args);
    $html = '';

    if ($query->have_posts()) {
        ob_start();
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/film-card');
        }
        $html = ob_get_clean();
    }

    wp_reset_postdata();

    wp_send_json_success(array(
        'html' => $html,
        'has_more' => $query->max_num_pages > $page,
    ));
}
add_action('wp_ajax_load_more_films', 'saab_load_more_films');
add_action('wp_ajax_nopriv_load_more_films', 'saab_load_more_films');

/**
 * Enhanced breadcrumbs function
 */
function saab_breadcrumbs() {
    if (is_front_page()) return;

    echo '<nav class="breadcrumbs-enhanced" aria-label="' . esc_attr__('Breadcrumb navigation', 'saab') . '">';
    echo '<ol class="breadcrumb-list">';
    
    echo '<li class="breadcrumb-item"><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'saab') . '</a></li>';
    
    if (is_category() || is_single()) {
        echo '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '">' . esc_html__('News', 'saab') . '</a></li>';
        if (is_single()) {
            echo '<li class="breadcrumb-item" aria-current="page">' . get_the_title() . '</li>';
        }
    } elseif (is_page()) {
        if ($post->post_parent) {
            $ancestors = array_reverse(get_post_ancestors($post->ID));
            foreach ($ancestors as $ancestor) {
                echo '<li class="breadcrumb-item"><a href="' . esc_url(get_permalink($ancestor)) . '">' . get_the_title($ancestor) . '</a></li>';
            }
        }
        echo '<li class="breadcrumb-item" aria-current="page">' . get_the_title() . '</li>';
    } elseif (is_archive()) {
        if (is_post_type_archive('film')) {
            echo '<li class="breadcrumb-item" aria-current="page">' . esc_html__('Films', 'saab') . '</li>';
        } elseif (is_post_type_archive('training_workshop')) {
            echo '<li class="breadcrumb-item" aria-current="page">' . esc_html__('Trainings & Workshops', 'saab') . '</li>';
        } elseif (is_post_type_archive('screening')) {
            echo '<li class="breadcrumb-item" aria-current="page">' . esc_html__('Screenings', 'saab') . '</li>';
        } elseif (is_post_type_archive('news')) {
            echo '<li class="breadcrumb-item" aria-current="page">' . esc_html__('News', 'saab') . '</li>';
        } elseif (is_post_type_archive('partner')) {
            echo '<li class="breadcrumb-item" aria-current="page">' . esc_html__('Partners', 'saab') . '</li>';
        } else {
            echo '<li class="breadcrumb-item" aria-current="page">' . get_the_archive_title() . '</li>';
        }
    } elseif (is_search()) {
        echo '<li class="breadcrumb-item" aria-current="page">' . esc_html__('Search Results', 'saab') . '</li>';
    } elseif (is_404()) {
        echo '<li class="breadcrumb-item" aria-current="page">' . esc_html__('Page Not Found', 'saab') . '</li>';
    }
    
    echo '</ol>';
    echo '</nav>';
}

/**
 * Add AJAX URL and nonce to frontend
 */
function saab_ajax_scripts() {
    wp_localize_script('saab-enhanced-main', 'saabAjax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('saab_ajax_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'saab_ajax_scripts');

/**
 * Image Optimization Features
 */

/**
 * Add WebP support and image optimization
 */
function saab_add_webp_support($mimes) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter('upload_mimes', 'saab_add_webp_support');

/**
 * Generate WebP versions of uploaded images
 */
function saab_generate_webp_versions($metadata, $attachment_id) {
    if (!function_exists('imagewebp')) {
        return $metadata;
    }

    $file_path = get_attached_file($attachment_id);
    $file_info = pathinfo($file_path);
    
    if (!in_array(strtolower($file_info['extension']), ['jpg', 'jpeg', 'png'])) {
        return $metadata;
    }

    $image = null;
    $extension = strtolower($file_info['extension']);
    
    if ($extension === 'jpg' || $extension === 'jpeg') {
        $image = imagecreatefromjpeg($file_path);
    } elseif ($extension === 'png') {
        $image = imagecreatefrompng($file_path);
    }
    
    if ($image) {
        $webp_path = $file_info['dirname'] . '/' . $file_info['filename'] . '.webp';
        imagewebp($image, $webp_path, 85);
        imagedestroy($image);
    }
    
    return $metadata;
}
add_filter('wp_generate_attachment_metadata', 'saab_generate_webp_versions', 10, 2);

/**
 * Add WebP support to WordPress image functions
 */
function saab_webp_upload_filter($file) {
    if ($file['type'] === 'image/webp') {
        $file['ext'] = 'webp';
        $file['name'] = $file['name'] . '.webp';
    }
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'saab_webp_upload_filter');

/**
 * Enhanced lazy loading for images
 */
function saab_enhanced_lazy_loading($attr, $attachment, $size) {
    if (is_admin()) {
        return $attr;
    }
    
    // Add lazy loading attributes
    $attr['loading'] = 'lazy';
    $attr['data-src'] = $attr['src'];
    $attr['class'] = isset($attr['class']) ? $attr['class'] . ' lazy-image' : 'lazy-image';
    
    // Add WebP support if available
    $webp_url = str_replace(['.jpg', '.jpeg', '.png'], '.webp', $attr['src']);
    if (file_exists(str_replace(wp_upload_dir()['baseurl'], wp_upload_dir()['basedir'], $webp_url))) {
        $attr['data-webp'] = $webp_url;
    }
    
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'saab_enhanced_lazy_loading', 10, 3);

/**
 * Add image compression and optimization
 */
function saab_optimize_image_quality($quality, $mime_type) {
    switch ($mime_type) {
        case 'image/jpeg':
            return 85; // Optimized JPEG quality
        case 'image/png':
            return 9; // Maximum PNG compression
        default:
            return $quality;
    }
}
add_filter('wp_editor_set_quality', 'saab_optimize_image_quality', 10, 2);

/**
 * Related Content System
 */

/**
 * Get related content based on various criteria
 */
function saab_get_related_content($post_id, $post_type = null, $limit = 4) {
    if (!$post_type) {
        $post_type = get_post_type($post_id);
    }
    
    $related_posts = array();
    
    // Get current post data
    $current_post = get_post($post_id);
    $current_title = $current_post->post_title;
    $current_content = $current_post->post_content;
    
    // Build query based on post type
    switch ($post_type) {
        case 'film':
            $related_posts = saab_get_related_films($post_id, $limit);
            break;
        case 'training_workshop':
            $related_posts = saab_get_related_workshops($post_id, $limit);
            break;
        case 'news':
            $related_posts = saab_get_related_news($post_id, $limit);
            break;
        case 'screening':
            $related_posts = saab_get_related_screenings($post_id, $limit);
            break;
    }
    
    // If not enough related posts, add cross-type recommendations
    if (count($related_posts) < $limit) {
        $cross_type_posts = saab_get_cross_type_recommendations($post_id, $post_type, $limit - count($related_posts));
        $related_posts = array_merge($related_posts, $cross_type_posts);
    }
    
    return array_slice($related_posts, 0, $limit);
}

/**
 * Get related films based on genre, year, and content similarity
 */
function saab_get_related_films($post_id, $limit = 4) {
    $related_posts = array();
    
    // Get current film data
    $current_genres = get_the_terms($post_id, 'genre');
    $current_languages = get_the_terms($post_id, 'film_language');
    $current_year = get_post_meta($post_id, '_saab_film_year', true);
    
    // Build query for related films
    $args = array(
        'post_type' => 'film',
        'posts_per_page' => $limit * 2, // Get more to filter
        'post__not_in' => array($post_id),
        'post_status' => 'publish',
        'meta_query' => array(),
        'tax_query' => array(),
    );
    
    // Add genre filter if available
    if ($current_genres && !is_wp_error($current_genres)) {
        $genre_ids = wp_list_pluck($current_genres, 'term_id');
        $args['tax_query'][] = array(
            'taxonomy' => 'genre',
            'field' => 'term_id',
            'terms' => $genre_ids,
            'operator' => 'IN',
        );
    }
    
    // Add language filter if available
    if ($current_languages && !is_wp_error($current_languages)) {
        $language_ids = wp_list_pluck($current_languages, 'term_id');
        $args['tax_query'][] = array(
            'taxonomy' => 'film_language',
            'field' => 'term_id',
            'terms' => $language_ids,
            'operator' => 'IN',
        );
    }
    
    // Add year filter if available
    if ($current_year) {
        $args['meta_query'][] = array(
            'key' => '_saab_film_year',
            'value' => $current_year,
            'compare' => '=',
        );
    }
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $related_posts[] = get_post();
        }
        wp_reset_postdata();
    }
    
    return array_slice($related_posts, 0, $limit);
}

/**
 * Get related workshops based on type, location, and trainers
 */
function saab_get_related_workshops($post_id, $limit = 4) {
    $related_posts = array();
    
    // Get current workshop data
    $current_type = get_post_meta($post_id, '_saab_training_workshop_type', true);
    $current_location = get_post_meta($post_id, '_saab_training_workshop_location', true);
    $current_trainers = get_post_meta($post_id, '_saab_training_workshop_trainers', true);
    
    // Build query for related workshops
    $args = array(
        'post_type' => 'training_workshop',
        'posts_per_page' => $limit * 2,
        'post__not_in' => array($post_id),
        'post_status' => 'publish',
        'meta_query' => array(),
    );
    
    // Add type filter if available
    if ($current_type) {
        $args['meta_query'][] = array(
            'key' => '_saab_training_workshop_type',
            'value' => $current_type,
            'compare' => '=',
        );
    }
    
    // Add location filter if available
    if ($current_location) {
        $args['meta_query'][] = array(
            'key' => '_saab_training_workshop_location',
            'value' => $current_location,
            'compare' => 'LIKE',
        );
    }
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $related_posts[] = get_post();
        }
        wp_reset_postdata();
    }
    
    return array_slice($related_posts, 0, $limit);
}

/**
 * Get related news based on categories and content similarity
 */
function saab_get_related_news($post_id, $limit = 4) {
    $related_posts = array();
    
    // Get current news categories
    $current_categories = get_the_terms($post_id, 'news_category');
    
    $args = array(
        'post_type' => 'news',
        'posts_per_page' => $limit,
        'post__not_in' => array($post_id),
        'post_status' => 'publish',
    );
    
    if ($current_categories && !is_wp_error($current_categories)) {
        $category_ids = wp_list_pluck($current_categories, 'term_id');
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'news_category',
                'field' => 'term_id',
                'terms' => $category_ids,
                'operator' => 'IN',
            ),
        );
    }
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $related_posts[] = get_post();
        }
        wp_reset_postdata();
    }
    
    return $related_posts;
}

/**
 * Get related screenings based on film, location, and date
 */
function saab_get_related_screenings($post_id, $limit = 4) {
    $related_posts = array();
    
    // Get current screening data
    $current_film = get_post_meta($post_id, '_saab_screening_film', true);
    $current_location = get_post_meta($post_id, '_saab_screening_location', true);
    $current_date = get_post_meta($post_id, '_saab_screening_date', true);
    
    // Build query for related screenings
    $args = array(
        'post_type' => 'screening',
        'posts_per_page' => $limit * 2,
        'post__not_in' => array($post_id),
        'post_status' => 'publish',
        'meta_query' => array(),
    );
    
    // Add film filter if available
    if ($current_film) {
        $args['meta_query'][] = array(
            'key' => '_saab_screening_film',
            'value' => $current_film,
            'compare' => '=',
        );
    }
    
    // Add location filter if available
    if ($current_location) {
        $args['meta_query'][] = array(
            'key' => '_saab_screening_location',
            'value' => $current_location,
            'compare' => 'LIKE',
        );
    }
    
    // Add date filter if available (screenings within same month)
    if ($current_date) {
        $current_month = date('Y-m', strtotime($current_date));
        $args['meta_query'][] = array(
            'key' => '_saab_screening_date',
            'value' => $current_month,
            'compare' => 'LIKE',
        );
    }
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $related_posts[] = get_post();
        }
        wp_reset_postdata();
    }
    
    return array_slice($related_posts, 0, $limit);
}

/**
 * Get cross-type recommendations
 */
// DUPLICATE FUNCTION REMOVED

/**
 * Display related content section
 */
function saab_display_related_content($post_id = null, $title = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    if (!$title) {
        $title = __('Related Content', 'saab');
    }
    
    $related_posts = saab_get_related_content($post_id);
    
    if (empty($related_posts)) {
        return;
    }
    
    echo '<section class="related-content-section">';
    echo '<div class="container">';
    echo '<h2 class="section-title-enhanced">' . esc_html($title) . '</h2>';
    echo '<div class="related-content-grid grid-enhanced">';
    
    foreach ($related_posts as $post) {
        setup_postdata($post);
        $post_type = get_post_type($post);
        
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
    
    wp_reset_postdata();
    
    echo '</div>';
    echo '</div>';
    echo '</section>';
}

/**
 * Social Sharing & Export Features
 */

/**
 * Enhanced social sharing functionality
 */
function saab_social_sharing_buttons($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post = get_post($post_id);
    $post_type = get_post_type($post_id);
    $title = get_the_title($post_id);
    $url = get_permalink($post_id);
    $excerpt = wp_trim_words(get_the_excerpt($post_id), 20, '...');
    
    // Get custom image for sharing
    $share_image = get_post_meta($post_id, '_saab_share_image', true);
    if (!$share_image && has_post_thumbnail($post_id)) {
        $share_image = get_the_post_thumbnail_url($post_id, 'large');
    }
    
    // Custom sharing text based on post type
    $share_text = '';
    switch ($post_type) {
        case 'film':
            $year = get_post_meta($post_id, '_saab_film_year', true);
            $share_text = sprintf(__('Check out this film by Jocelyne Saab: %s', 'saab'), $title);
            if ($year) {
                $share_text .= ' (' . $year . ')';
            }
            break;
        case 'training_workshop':
            $share_text = sprintf(__('Join this workshop: %s', 'saab'), $title);
            break;
        case 'news':
            $share_text = sprintf(__('Read this news: %s', 'saab'), $title);
            break;
        default:
            $share_text = $title;
    }
    
    echo '<div class="social-sharing">';
    echo '<h4 class="social-sharing-title">' . esc_html__('Share this', 'saab') . '</h4>';
    echo '<div class="social-sharing-buttons">';
    
    // Facebook
    echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url) . '" 
              class="social-share-btn social-share-facebook" 
              target="_blank" 
              rel="noopener noreferrer"
              aria-label="' . esc_attr__('Share on Facebook', 'saab') . '">
              <i class="fab fa-facebook-f" aria-hidden="true"></i>
              <span>' . esc_html__('Facebook', 'saab') . '</span>
          </a>';
    
    // Twitter
    echo '<a href="https://twitter.com/intent/tweet?text=' . urlencode($share_text) . '&url=' . urlencode($url) . '" 
              class="social-share-btn social-share-twitter" 
              target="_blank" 
              rel="noopener noreferrer"
              aria-label="' . esc_attr__('Share on Twitter', 'saab') . '">
              <i class="fab fa-twitter" aria-hidden="true"></i>
              <span>' . esc_html__('Twitter', 'saab') . '</span>
          </a>';
    
    // LinkedIn
    echo '<a href="https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($url) . '" 
              class="social-share-btn social-share-linkedin" 
              target="_blank" 
              rel="noopener noreferrer"
              aria-label="' . esc_attr__('Share on LinkedIn', 'saab') . '">
              <i class="fab fa-linkedin-in" aria-hidden="true"></i>
              <span>' . esc_html__('LinkedIn', 'saab') . '</span>
          </a>';
    
    // Email
    echo '<a href="mailto:?subject=' . urlencode($title) . '&body=' . urlencode($share_text . ' ' . $url) . '" 
              class="social-share-btn social-share-email" 
              aria-label="' . esc_attr__('Share via Email', 'saab') . '">
              <i class="fas fa-envelope" aria-hidden="true"></i>
              <span>' . esc_html__('Email', 'saab') . '</span>
          </a>';
    
    // Copy Link
    echo '<button class="social-share-btn social-share-copy" 
                  data-url="' . esc_attr($url) . '"
                  aria-label="' . esc_attr__('Copy link', 'saab') . '">
              <i class="fas fa-link" aria-hidden="true"></i>
              <span>' . esc_html__('Copy Link', 'saab') . '</span>
          </button>';
    
    echo '</div>';
    echo '</div>';
}

/**
 * PDF Export functionality
 */
function saab_pdf_export_button($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post_type = get_post_type($post_id);
    
    // Only show for films and workshops
    if (!in_array($post_type, array('film', 'training_workshop'))) {
        return;
    }
    
    $export_url = add_query_arg(array(
        'action' => 'saab_export_pdf',
        'post_id' => $post_id,
        'nonce' => wp_create_nonce('saab_pdf_export'),
    ), admin_url('admin-ajax.php'));
    
    echo '<div class="pdf-export">';
    echo '<a href="' . esc_url($export_url) . '" 
              class="btn-enhanced btn-enhanced-outline btn-enhanced-sm pdf-export-btn" 
              target="_blank"
              aria-label="' . esc_attr__('Export as PDF', 'saab') . '">
              <i class="fas fa-file-pdf" aria-hidden="true"></i>
              <span>' . esc_html__('Export PDF', 'saab') . '</span>
          </a>';
    echo '</div>';
}

/**
 * AJAX handler for PDF export
 */
function saab_handle_pdf_export() {
    if (!wp_verify_nonce($_GET['nonce'], 'saab_pdf_export')) {
        wp_die('Security check failed');
    }
    
    $post_id = intval($_GET['post_id']);
    $post = get_post($post_id);
    
    if (!$post) {
        wp_die('Post not found');
    }
    
    $post_type = get_post_type($post_id);
    
    // Generate PDF content
    $pdf_content = saab_generate_pdf_content($post_id, $post_type);
    
    // Set headers for PDF download
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . sanitize_file_name($post->post_title) . '.pdf"');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // For now, we'll output HTML that can be converted to PDF
    // In a production environment, you'd use a library like TCPDF or mPDF
    echo '<!DOCTYPE html>';
    echo '<html><head>';
    echo '<meta charset="UTF-8">';
    echo '<title>' . esc_html($post->post_title) . '</title>';
    echo '<style>';
    echo 'body { font-family: Arial, sans-serif; margin: 40px; }';
    echo 'h1 { color: #2C3E50; border-bottom: 2px solid #F4D03F; padding-bottom: 10px; }';
    echo 'h2 { color: #34495E; margin-top: 30px; }';
    echo '.meta { background: #F8F9FA; padding: 15px; border-radius: 5px; margin: 20px 0; }';
    echo '.meta-item { margin: 10px 0; }';
    echo '.content { line-height: 1.6; }';
    echo '.footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #DDD; font-size: 12px; color: #666; }';
    echo '</style>';
    echo '</head><body>';
    
    echo $pdf_content;
    
    echo '<div class="footer">';
    echo '<p>' . esc_html__('Generated from', 'saab') . ' ' . get_bloginfo('name') . ' - ' . date('Y-m-d H:i:s') . '</p>';
    echo '</div>';
    
    echo '</body></html>';
    
    exit;
}
add_action('wp_ajax_saab_export_pdf', 'saab_handle_pdf_export');
add_action('wp_ajax_nopriv_saab_export_pdf', 'saab_handle_pdf_export');

/**
 * Generate PDF content based on post type
 */
function saab_generate_pdf_content($post_id, $post_type) {
    $post = get_post($post_id);
    $content = '';
    
    switch ($post_type) {
        case 'film':
            $content = saab_generate_film_pdf($post_id);
            break;
        case 'training_workshop':
            $content = saab_generate_workshop_pdf($post_id);
            break;
        default:
            $content = saab_generate_default_pdf($post_id);
    }
    
    return $content;
}

/**
 * Generate film PDF content
 */
function saab_generate_film_pdf($post_id) {
    $post = get_post($post_id);
    $year = get_post_meta($post_id, '_saab_film_year', true);
    $duration = get_post_meta($post_id, '_saab_film_duration', true);
    $genres = get_the_terms($post_id, 'genre');
    $languages = get_the_terms($post_id, 'film_language');
    
    $content = '<h1>' . esc_html($post->post_title) . '</h1>';
    
    $content .= '<div class="meta">';
    if ($year) {
        $content .= '<div class="meta-item"><strong>' . esc_html__('Year:', 'saab') . '</strong> ' . esc_html($year) . '</div>';
    }
    if ($duration) {
        $content .= '<div class="meta-item"><strong>' . esc_html__('Duration:', 'saab') . '</strong> ' . esc_html($duration) . '</div>';
    }
    if ($genres && !is_wp_error($genres)) {
        $genre_names = wp_list_pluck($genres, 'name');
        $content .= '<div class="meta-item"><strong>' . esc_html__('Genre:', 'saab') . '</strong> ' . esc_html(implode(', ', $genre_names)) . '</div>';
    }
    if ($languages && !is_wp_error($languages)) {
        $language_names = wp_list_pluck($languages, 'name');
        $content .= '<div class="meta-item"><strong>' . esc_html__('Language:', 'saab') . '</strong> ' . esc_html(implode(', ', $language_names)) . '</div>';
    }
    $content .= '</div>';
    
    $content .= '<div class="content">';
    $content .= wpautop($post->post_content);
    $content .= '</div>';
    
    return $content;
}

/**
 * Generate workshop PDF content
 */
function saab_generate_workshop_pdf($post_id) {
    $post = get_post($post_id);
    $date = get_post_meta($post_id, '_saab_training_workshop_date', true);
    $location = get_post_meta($post_id, '_saab_training_workshop_location', true);
    $duration = get_post_meta($post_id, '_saab_training_workshop_duration', true);
    $trainers = get_post_meta($post_id, '_saab_training_workshop_trainers', true);
    $type = get_post_meta($post_id, '_saab_training_workshop_type', true);
    
    $content = '<h1>' . esc_html($post->post_title) . '</h1>';
    
    $content .= '<div class="meta">';
    if ($date) {
        $formatted_date = date_i18n(get_option('date_format'), strtotime($date));
        $content .= '<div class="meta-item"><strong>' . esc_html__('Date:', 'saab') . '</strong> ' . esc_html($formatted_date) . '</div>';
    }
    if ($location) {
        $content .= '<div class="meta-item"><strong>' . esc_html__('Location:', 'saab') . '</strong> ' . esc_html($location) . '</div>';
    }
    if ($duration) {
        $content .= '<div class="meta-item"><strong>' . esc_html__('Duration:', 'saab') . '</strong> ' . esc_html($duration) . '</div>';
    }
    if ($trainers) {
        $content .= '<div class="meta-item"><strong>' . esc_html__('Trainers:', 'saab') . '</strong> ' . esc_html($trainers) . '</div>';
    }
    if ($type) {
        $content .= '<div class="meta-item"><strong>' . esc_html__('Type:', 'saab') . '</strong> ' . esc_html($type) . '</div>';
    }
    $content .= '</div>';
    
    $content .= '<div class="content">';
    $content .= wpautop($post->post_content);
    $content .= '</div>';
    
    return $content;
}

/**
 * Generate default PDF content
 */
function saab_generate_default_pdf($post_id) {
    $post = get_post($post_id);
    
    $content = '<h1>' . esc_html($post->post_title) . '</h1>';
    $content .= '<div class="meta">';
    $content .= '<div class="meta-item"><strong>' . esc_html__('Date:', 'saab') . '</strong> ' . get_the_date('', $post_id) . '</div>';
    $content .= '</div>';
    $content .= '<div class="content">';
    $content .= wpautop($post->post_content);
    $content .= '</div>';
    
    return $content;
}

/**
 * Multi-language Support
 */

/**
 * Add RTL support and language detection
 */
function saab_add_rtl_support() {
    // Check if current locale is RTL
    $locale = get_locale();
    $rtl_languages = array('ar', 'he', 'fa', 'ur', 'ps', 'sd', 'yi');
    
    if (in_array($locale, $rtl_languages)) {
        add_filter('body_class', function($classes) {
            $classes[] = 'rtl';
            return $classes;
        });
    }
}
add_action('init', 'saab_add_rtl_support');

/**
 * Load theme text domain for translations
 */
function saab_load_textdomain() {
    load_theme_textdomain('saab', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'saab_load_textdomain');

/**
 * Add language switcher functionality
 */
function saab_language_switcher() {
    $current_language = get_locale();
    $available_languages = array(
        'en_US' => array('name' => 'English', 'flag' => '????'),
        'ar' => array('name' => '???????', 'flag' => '????'),
        'fr_FR' => array('name' => 'Fran�ais', 'flag' => '????'),
        'es_ES' => array('name' => 'Espa�ol', 'flag' => '????'),
        'de_DE' => array('name' => 'Deutsch', 'flag' => '????'),
    );
    
    echo '<div class="language-switcher">';
    echo '<button class="language-toggle" aria-expanded="false">';
    echo '<span class="current-language">';
    if (isset($available_languages[$current_language])) {
        echo $available_languages[$current_language]['flag'] . ' ' . $available_languages[$current_language]['name'];
    } else {
        echo '?? ' . __('Language', 'saab');
    }
    echo '</span>';
    echo '<i class="fas fa-chevron-down" aria-hidden="true"></i>';
    echo '</button>';
    
    echo '<ul class="language-dropdown">';
    foreach ($available_languages as $code => $language) {
        $is_current = ($code === $current_language);
        $url = add_query_arg('lang', $code, remove_query_arg('lang'));
        
        echo '<li class="language-option' . ($is_current ? ' current' : '') . '">';
        echo '<a href="' . esc_url($url) . '" class="language-link">';
        echo '<span class="language-flag">' . $language['flag'] . '</span>';
        echo '<span class="language-name">' . esc_html($language['name']) . '</span>';
        if ($is_current) {
            echo '<i class="fas fa-check" aria-hidden="true"></i>';
        }
        echo '</a>';
        echo '</li>';
    }
    echo '</ul>';
    echo '</div>';
}

/**
 * Handle language switching
 */
function saab_handle_language_switch() {
    if (isset($_GET['lang'])) {
        $new_language = sanitize_text_field($_GET['lang']);
        $available_languages = array('en_US', 'ar', 'fr_FR', 'es_ES', 'de_DE');
        
        if (in_array($new_language, $available_languages)) {
            // Set language cookie
            setcookie('saab_language', $new_language, time() + (86400 * 30), '/');
            
            // Redirect to remove the lang parameter
            wp_redirect(remove_query_arg('lang'));
            exit;
        }
    }
}
add_action('init', 'saab_handle_language_switch');

/**
 * Get current language
 */
function saab_get_current_language() {
    if (isset($_COOKIE['saab_language'])) {
        return sanitize_text_field($_COOKIE['saab_language']);
    }
    return get_locale();
}

/**
 * Add RTL styles
 */
function saab_add_rtl_styles() {
    if (is_rtl()) {
        wp_enqueue_style('saab-rtl', get_template_directory_uri() . '/assets/css/rtl.css', array(), '1.0.0');
    }
}
add_action('wp_enqueue_scripts', 'saab_add_rtl_styles');

/**
 * Translate content based on current language
 */
function saab_translate_content($content, $translations = array()) {
    $current_language = saab_get_current_language();
    
    if (isset($translations[$current_language])) {
        return $translations[$current_language];
    }
    
    return $content;
}

/**
 * Get translated post meta
 */
function saab_get_translated_meta($post_id, $meta_key, $translations = array()) {
    $value = get_post_meta($post_id, $meta_key, true);
    
    if (!empty($translations)) {
        $current_language = saab_get_current_language();
        if (isset($translations[$current_language])) {
            $translated_key = $meta_key . '_' . $current_language;
            $translated_value = get_post_meta($post_id, $translated_key, true);
            if ($translated_value) {
                return $translated_value;
            }
        }
    }
    
    return $value;
}

/**
 * Add language meta to head
 */
function saab_add_language_meta() {
    $current_language = saab_get_current_language();
    echo '<meta name="language" content="' . esc_attr($current_language) . '">';
    
    if (is_rtl()) {
        echo '<meta name="text-direction" content="rtl">';
    }
}
add_action('wp_head', 'saab_add_language_meta');

/**
 * Custom Video Player Features
 */

/**
 * Add video player functionality
 */
function saab_video_player($video_url, $poster_image = '', $autoplay = false, $controls = true) {
    $video_id = '';
    $video_type = '';
    
    // Detect video type and extract ID
    if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
        $video_type = 'youtube';
        $video_id = saab_extract_youtube_id($video_url);
    } elseif (strpos($video_url, 'vimeo.com') !== false) {
        $video_type = 'vimeo';
        $video_id = saab_extract_vimeo_id($video_url);
    } else {
        $video_type = 'direct';
    }
    
    $player_id = 'video-player-' . uniqid();
    
    echo '<div class="video-player-container" data-video-type="' . esc_attr($video_type) . '">';
    
    if ($video_type === 'youtube') {
        echo saab_youtube_player($video_id, $poster_image, $autoplay, $controls, $player_id);
    } elseif ($video_type === 'vimeo') {
        echo saab_vimeo_player($video_id, $poster_image, $autoplay, $controls, $player_id);
    } else {
        echo saab_direct_video_player($video_url, $poster_image, $autoplay, $controls, $player_id);
    }
    
    echo '</div>';
}

/**
 * Extract YouTube video ID
 */
function saab_extract_youtube_id($url) {
    $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
    if (preg_match($pattern, $url, $matches)) {
        return $matches[1];
    }
    return '';
}

/**
 * Extract Vimeo video ID
 */
function saab_extract_vimeo_id($url) {
    $pattern = '/vimeo\.com\/([0-9]+)/i';
    if (preg_match($pattern, $url, $matches)) {
        return $matches[1];
    }
    return '';
}

/**
 * YouTube player
 */
function saab_youtube_player($video_id, $poster_image, $autoplay, $controls, $player_id) {
    $autoplay_param = $autoplay ? '1' : '0';
    $controls_param = $controls ? '1' : '0';
    
    $output = '<div class="video-player youtube-player" id="' . esc_attr($player_id) . '">';
    
    if ($poster_image) {
        $output .= '<div class="video-poster" style="background-image: url(\'' . esc_url($poster_image) . '\');">';
        $output .= '<button class="video-play-btn" aria-label="' . esc_attr__('Play video', 'saab') . '">';
        $output .= '<i class="fas fa-play" aria-hidden="true"></i>';
        $output .= '</button>';
        $output .= '</div>';
    }
    
    $output .= '<iframe class="video-iframe" 
                        src="https://www.youtube.com/embed/' . esc_attr($video_id) . '?autoplay=' . $autoplay_param . '&controls=' . $controls_param . '&rel=0&modestbranding=1" 
                        frameborder="0" 
                        allowfullscreen 
                        allow="autoplay; encrypted-media">
                </iframe>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Vimeo player
 */
function saab_vimeo_player($video_id, $poster_image, $autoplay, $controls, $player_id) {
    $autoplay_param = $autoplay ? '1' : '0';
    $controls_param = $controls ? '1' : '0';
    
    $output = '<div class="video-player vimeo-player" id="' . esc_attr($player_id) . '">';
    
    if ($poster_image) {
        $output .= '<div class="video-poster" style="background-image: url(\'' . esc_url($poster_image) . '\');">';
        $output .= '<button class="video-play-btn" aria-label="' . esc_attr__('Play video', 'saab') . '">';
        $output .= '<i class="fas fa-play" aria-hidden="true"></i>';
        $output .= '</button>';
        $output .= '</div>';
    }
    
    $output .= '<iframe class="video-iframe" 
                        src="https://player.vimeo.com/video/' . esc_attr($video_id) . '?autoplay=' . $autoplay_param . '&controls=' . $controls_param . '&title=0&byline=0&portrait=0" 
                        frameborder="0" 
                        allowfullscreen>
                </iframe>';
    $output .= '</div>';
    
    return $output;
}

/**
 * Direct video player
 */
function saab_direct_video_player($video_url, $poster_image, $autoplay, $controls, $player_id) {
    $autoplay_attr = $autoplay ? 'autoplay' : '';
    $controls_attr = $controls ? 'controls' : '';
    
    $output = '<div class="video-player direct-player" id="' . esc_attr($player_id) . '">';
    $output .= '<video class="video-element" ' . $autoplay_attr . ' ' . $controls_attr . ' preload="metadata">';
    $output .= '<source src="' . esc_url($video_url) . '" type="video/mp4">';
    $output .= esc_html__('Your browser does not support the video tag.', 'saab');
    $output .= '</video>';
    
    if ($poster_image) {
        $output .= '<div class="video-poster" style="background-image: url(\'' . esc_url($poster_image) . '\');">';
        $output .= '<button class="video-play-btn" aria-label="' . esc_attr__('Play video', 'saab') . '">';
        $output .= '<i class="fas fa-play" aria-hidden="true"></i>';
        $output .= '</button>';
        $output .= '</div>';
    }
    
    $output .= '</div>';
    
    return $output;
}

/**
 * Get video URL from post meta
 */
function saab_get_video_url($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $video_url = get_post_meta($post_id, '_saab_video_url', true);
    
    if (!$video_url) {
        // Check for trailer URL
        $video_url = get_post_meta($post_id, '_saab_trailer_url', true);
    }
    
    return $video_url;
}

/**
 * Display video player for films
 */
function saab_display_film_video($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $video_url = saab_get_video_url($post_id);
    
    if (!$video_url) {
        return;
    }
    
    $poster_image = '';
    if (has_post_thumbnail($post_id)) {
        $poster_image = get_the_post_thumbnail_url($post_id, 'large');
    }
    
    echo '<div class="film-video-section">';
    echo '<h3 class="video-section-title">' . esc_html__('Watch Trailer', 'saab') . '</h3>';
    saab_video_player($video_url, $poster_image, false, true);
    echo '</div>';
}

/**
 * Advanced Image Gallery Features
 */

/**
 * Display advanced image gallery
 */
function saab_advanced_gallery($images, $gallery_id = '', $layout = 'masonry', $filters = array()) {
    if (empty($images) || !is_array($images)) {
        return;
    }
    
    $gallery_id = $gallery_id ?: 'gallery-' . uniqid();
    $layout_class = 'gallery-' . $layout;
    
    echo '<div class="advanced-gallery" id="' . esc_attr($gallery_id) . '" data-layout="' . esc_attr($layout) . '">';
    
    // Gallery filters
    if (!empty($filters)) {
        echo '<div class="gallery-filters">';
        echo '<button class="filter-btn active" data-filter="all">' . esc_html__('All', 'saab') . '</button>';
        foreach ($filters as $filter) {
            echo '<button class="filter-btn" data-filter="' . esc_attr($filter['slug']) . '">' . esc_html($filter['name']) . '</button>';
        }
        echo '</div>';
    }
    
    // Gallery grid
    echo '<div class="gallery-grid ' . esc_attr($layout_class) . '">';
    
    foreach ($images as $image) {
        $image_id = is_array($image) ? $image['id'] : $image;
        $image_url = wp_get_attachment_image_url($image_id, 'large');
        $image_full = wp_get_attachment_image_url($image_id, 'full');
        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
        $image_title = get_the_title($image_id);
        $image_caption = wp_get_attachment_caption($image_id);
        
        // Get image categories for filtering
        $image_categories = wp_get_object_terms($image_id, 'category', array('fields' => 'slugs'));
        $filter_classes = !empty($image_categories) ? ' ' . implode(' ', $image_categories) : '';
        
        echo '<div class="gallery-item' . esc_attr($filter_classes) . '" data-category="' . esc_attr(implode(' ', $image_categories)) . '">';
        echo '<div class="gallery-item-inner">';
        echo '<img src="' . esc_url($image_url) . '" 
                   alt="' . esc_attr($image_alt) . '" 
                   title="' . esc_attr($image_title) . '"
                   data-full="' . esc_url($image_full) . '"
                   loading="lazy"
                   class="gallery-image">';
        
        echo '<div class="gallery-overlay">';
        echo '<div class="gallery-overlay-content">';
        if ($image_title) {
            echo '<h4 class="gallery-title">' . esc_html($image_title) . '</h4>';
        }
        if ($image_caption) {
            echo '<p class="gallery-caption">' . esc_html($image_caption) . '</p>';
        }
        echo '<button class="gallery-zoom" aria-label="' . esc_attr__('View full size', 'saab') . '">';
        echo '<i class="fas fa-search-plus" aria-hidden="true"></i>';
        echo '</button>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    
    echo '</div>';
    
    // Lightbox container
    echo '<div class="gallery-lightbox" id="lightbox-' . esc_attr($gallery_id) . '">';
    echo '<div class="lightbox-overlay"></div>';
    echo '<div class="lightbox-content">';
    echo '<button class="lightbox-close" aria-label="' . esc_attr__('Close lightbox', 'saab') . '">';
    echo '<i class="fas fa-times" aria-hidden="true"></i>';
    echo '</button>';
    echo '<button class="lightbox-prev" aria-label="' . esc_attr__('Previous image', 'saab') . '">';
    echo '<i class="fas fa-chevron-left" aria-hidden="true"></i>';
    echo '</button>';
    echo '<button class="lightbox-next" aria-label="' . esc_attr__('Next image', 'saab') . '">';
    echo '<i class="fas fa-chevron-right" aria-hidden="true"></i>';
    echo '</button>';
    echo '<div class="lightbox-image-container">';
    echo '<img src="" alt="" class="lightbox-image">';
    echo '</div>';
    echo '<div class="lightbox-info">';
    echo '<h3 class="lightbox-title"></h3>';
    echo '<p class="lightbox-caption"></p>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    
    echo '</div>';
}

/**
 * Display film stills gallery
 */
function saab_film_stills_gallery($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $film_stills = get_post_meta($post_id, '_saab_film_stills', true);
    
    if (empty($film_stills) || !is_array($film_stills)) {
        return;
    }
    
    echo '<div class="film-stills-gallery">';
    echo '<h3 class="gallery-section-title">' . esc_html__('Film Stills', 'saab') . '</h3>';
    
    // Create filters for different types of stills
    $filters = array(
        array('slug' => 'scene', 'name' => __('Scenes', 'saab')),
        array('slug' => 'character', 'name' => __('Characters', 'saab')),
        array('slug' => 'location', 'name' => __('Locations', 'saab')),
        array('slug' => 'behind-scenes', 'name' => __('Behind the Scenes', 'saab'))
    );
    
    saab_advanced_gallery($film_stills, 'film-stills-' . $post_id, 'masonry', $filters);
    echo '</div>';
}

/**
 * Display workshop gallery
 */
function saab_workshop_gallery($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $workshop_images = get_post_meta($post_id, '_saab_workshop_images', true);
    
    if (empty($workshop_images) || !is_array($workshop_images)) {
        return;
    }
    
    echo '<div class="workshop-gallery">';
    echo '<h3 class="gallery-section-title">' . esc_html__('Workshop Gallery', 'saab') . '</h3>';
    
    // Create filters for workshop images
    $filters = array(
        array('slug' => 'session', 'name' => __('Sessions', 'saab')),
        array('slug' => 'participants', 'name' => __('Participants', 'saab')),
        array('slug' => 'results', 'name' => __('Results', 'saab')),
        array('slug' => 'venue', 'name' => __('Venue', 'saab'))
    );
    
    saab_advanced_gallery($workshop_images, 'workshop-' . $post_id, 'grid', $filters);
    echo '</div>';
}

/**
 * Get gallery images with metadata
 */
function saab_get_gallery_images($image_ids) {
    if (empty($image_ids) || !is_array($image_ids)) {
        return array();
    }
    
    $images = array();
    foreach ($image_ids as $image_id) {
        $image_data = array(
            'id' => $image_id,
            'url' => wp_get_attachment_image_url($image_id, 'large'),
            'full' => wp_get_attachment_image_url($image_id, 'full'),
            'alt' => get_post_meta($image_id, '_wp_attachment_image_alt', true),
            'title' => get_the_title($image_id),
            'caption' => wp_get_attachment_caption($image_id),
            'description' => get_post_field('post_content', $image_id)
        );
        $images[] = $image_data;
    }
    
    return $images;
}

/**
 * Maps Integration Features
 */

/**
 * Display location map
 */
function saab_display_location_map($address, $title = '', $zoom = 15, $height = '400px') {
    if (empty($address)) {
        return;
    }
    
    $map_id = 'map-' . uniqid();
    $encoded_address = urlencode($address);
    
    echo '<div class="location-map-container">';
    if ($title) {
        echo '<h3 class="map-title">' . esc_html($title) . '</h3>';
    }
    echo '<div class="location-map" id="' . esc_attr($map_id) . '" style="height: ' . esc_attr($height) . ';" data-address="' . esc_attr($address) . '" data-zoom="' . esc_attr($zoom) . '">';
    echo '<div class="map-loading">';
    echo '<i class="fas fa-map-marker-alt" aria-hidden="true"></i>';
    echo '<p>' . esc_html__('Loading map...', 'saab') . '</p>';
    echo '</div>';
    echo '</div>';
    echo '<div class="map-info">';
    echo '<div class="map-address">';
    echo '<i class="fas fa-map-marker-alt" aria-hidden="true"></i>';
    echo '<span>' . esc_html($address) . '</span>';
    echo '</div>';
    echo '<button class="map-directions-btn" onclick="openDirections(\'' . esc_js($address) . '\')">';
    echo '<i class="fas fa-directions" aria-hidden="true"></i>';
    echo esc_html__('Get Directions', 'saab');
    echo '</button>';
    echo '</div>';
    echo '</div>';
}

/**
 * Display workshop location map
 */
function saab_workshop_location_map($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $location = get_post_meta($post_id, '_saab_workshop_location', true);
    $venue = get_post_meta($post_id, '_saab_workshop_venue', true);
    
    if (empty($location)) {
        return;
    }
    
    $title = $venue ? sprintf(__('Workshop Location: %s', 'saab'), $venue) : __('Workshop Location', 'saab');
    saab_display_location_map($location, $title, 15, '350px');
}

/**
 * Display screening location map
 */
function saab_screening_location_map($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $location = get_post_meta($post_id, '_saab_screening_location', true);
    $venue = get_post_meta($post_id, '_saab_screening_venue', true);
    
    if (empty($location)) {
        return;
    }
    
    $title = $venue ? sprintf(__('Screening Location: %s', 'saab'), $venue) : __('Screening Location', 'saab');
    saab_display_location_map($location, $title, 15, '350px');
}

/**
 * Display multiple locations map
 */
function saab_display_multiple_locations($locations, $title = '') {
    if (empty($locations) || !is_array($locations)) {
        return;
    }
    
    $map_id = 'multi-map-' . uniqid();
    
    echo '<div class="multiple-locations-map">';
    if ($title) {
        echo '<h3 class="map-title">' . esc_html($title) . '</h3>';
    }
    echo '<div class="locations-map" id="' . esc_attr($map_id) . '" style="height: 500px;" data-locations="' . esc_attr(json_encode($locations)) . '">';
    echo '<div class="map-loading">';
    echo '<i class="fas fa-map-marker-alt" aria-hidden="true"></i>';
    echo '<p>' . esc_html__('Loading locations...', 'saab') . '</p>';
    echo '</div>';
    echo '</div>';
    echo '<div class="locations-list">';
    foreach ($locations as $index => $location) {
        echo '<div class="location-item" data-index="' . esc_attr($index) . '">';
        echo '<div class="location-marker">' . esc_html($index + 1) . '</div>';
        echo '<div class="location-details">';
        if (!empty($location['title'])) {
            echo '<h4 class="location-title">' . esc_html($location['title']) . '</h4>';
        }
        echo '<p class="location-address">' . esc_html($location['address']) . '</p>';
        if (!empty($location['description'])) {
            echo '<p class="location-description">' . esc_html($location['description']) . '</p>';
        }
        echo '</div>';
        echo '</div>';
    }
    echo '</div>';
    echo '</div>';
}

/**
 * Display upcoming screenings map
 */
function saab_upcoming_screenings_map() {
    $screenings = get_posts(array(
        'post_type' => 'screening',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_saab_screening_date',
                'value' => date('Y-m-d'),
                'compare' => '>=',
                'type' => 'DATE'
            )
        )
    ));
    
    if (empty($screenings)) {
        return;
    }
    
    $locations = array();
    foreach ($screenings as $screening) {
        $location = get_post_meta($screening->ID, '_saab_screening_location', true);
        $venue = get_post_meta($screening->ID, '_saab_screening_venue', true);
        $date = get_post_meta($screening->ID, '_saab_screening_date', true);
        
        if ($location) {
            $locations[] = array(
                'title' => $screening->post_title,
                'address' => $location,
                'description' => $venue . ' - ' . $date,
                'url' => get_permalink($screening->ID)
            );
        }
    }
    
    if (!empty($locations)) {
        saab_display_multiple_locations($locations, __('Upcoming Screenings', 'saab'));
    }
}

/**
 * Display workshop locations map
 */
function saab_workshop_locations_map() {
    $workshops = get_posts(array(
        'post_type' => 'training_workshop',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => '_saab_workshop_date',
                'value' => date('Y-m-d'),
                'compare' => '>=',
                'type' => 'DATE'
            )
        )
    ));
    
    if (empty($workshops)) {
        return;
    }
    
    $locations = array();
    foreach ($workshops as $workshop) {
        $location = get_post_meta($workshop->ID, '_saab_workshop_location', true);
        $venue = get_post_meta($workshop->ID, '_saab_workshop_venue', true);
        $date = get_post_meta($workshop->ID, '_saab_workshop_date', true);
        
        if ($location) {
            $locations[] = array(
                'title' => $workshop->post_title,
                'address' => $location,
                'description' => $venue . ' - ' . $date,
                'url' => get_permalink($workshop->ID)
            );
        }
    }
    
    if (!empty($locations)) {
        saab_display_multiple_locations($locations, __('Workshop Locations', 'saab'));
    }
}

/**
 * Get location coordinates (placeholder for Google Maps API)
 */
function saab_get_location_coordinates($address) {
    // This would typically use Google Maps Geocoding API
    // For now, return placeholder coordinates
    return array(
        'lat' => 33.8935, // Beirut coordinates as default
        'lng' => 35.5018
    );
}

/**
 * Event Calendar Features
 */

/**
 * Display event calendar
 */
function saab_event_calendar($view = 'month', $post_types = array('screening', 'training_workshop')) {
    $calendar_id = 'calendar-' . uniqid();
    
    echo '<div class="event-calendar-container">';
    echo '<div class="calendar-header">';
    echo '<h3 class="calendar-title">' . esc_html__('Event Calendar', 'saab') . '</h3>';
    echo '<div class="calendar-controls">';
    echo '<button class="calendar-view-btn active" data-view="month">' . esc_html__('Month', 'saab') . '</button>';
    echo '<button class="calendar-view-btn" data-view="week">' . esc_html__('Week', 'saab') . '</button>';
    echo '<button class="calendar-view-btn" data-view="list">' . esc_html__('List', 'saab') . '</button>';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="calendar-filters">';
    echo '<label class="filter-label">' . esc_html__('Filter by:', 'saab') . '</label>';
    echo '<div class="filter-options">';
    echo '<label class="filter-checkbox">';
    echo '<input type="checkbox" value="screening" checked> ';
    echo '<span>' . esc_html__('Screenings', 'saab') . '</span>';
    echo '</label>';
    echo '<label class="filter-checkbox">';
    echo '<input type="checkbox" value="training_workshop" checked> ';
    echo '<span>' . esc_html__('Workshops', 'saab') . '</span>';
    echo '</label>';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="calendar-wrapper">';
    echo '<div class="calendar-navigation">';
    echo '<button class="calendar-nav-btn" data-action="prev">';
    echo '<i class="fas fa-chevron-left" aria-hidden="true"></i>';
    echo '</button>';
    echo '<h4 class="calendar-current-date">' . esc_html(date('F Y')) . '</h4>';
    echo '<button class="calendar-nav-btn" data-action="next">';
    echo '<i class="fas fa-chevron-right" aria-hidden="true"></i>';
    echo '</button>';
    echo '</div>';
    
    echo '<div class="calendar-view" id="' . esc_attr($calendar_id) . '" data-view="' . esc_attr($view) . '">';
    echo '<div class="calendar-loading">';
    echo '<i class="fas fa-calendar-alt" aria-hidden="true"></i>';
    echo '<p>' . esc_html__('Loading calendar...', 'saab') . '</p>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    
    echo '</div>';
}

/**
 * Get events for calendar
 */
function saab_get_calendar_events($start_date, $end_date, $post_types = array('screening', 'training_workshop')) {
    $events = array();
    
    foreach ($post_types as $post_type) {
        $posts = get_posts(array(
            'post_type' => $post_type,
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => $post_type === 'screening' ? '_saab_screening_date' : '_saab_workshop_date',
                    'value' => array($start_date, $end_date),
                    'compare' => 'BETWEEN',
                    'type' => 'DATE'
                )
            )
        ));
        
        foreach ($posts as $post) {
            $date_key = $post_type === 'screening' ? '_saab_screening_date' : '_saab_workshop_date';
            $time_key = $post_type === 'screening' ? '_saab_screening_time' : '_saab_workshop_time';
            $location_key = $post_type === 'screening' ? '_saab_screening_location' : '_saab_workshop_location';
            
            $date = get_post_meta($post->ID, $date_key, true);
            $time = get_post_meta($post->ID, $time_key, true);
            $location = get_post_meta($post->ID, $location_key, true);
            
            $events[] = array(
                'id' => $post->ID,
                'title' => $post->post_title,
                'date' => $date,
                'time' => $time,
                'location' => $location,
                'type' => $post_type,
                'url' => get_permalink($post->ID),
                'excerpt' => wp_trim_words($post->post_excerpt, 20),
                'color' => $post_type === 'screening' ? '#EF4444' : '#3B82F6'
            );
        }
    }
    
    return $events;
}

/**
 * Display monthly calendar view
 */
function saab_monthly_calendar_view($year, $month, $events) {
    $first_day = mktime(0, 0, 0, $month, 1, $year);
    $days_in_month = date('t', $first_day);
    $first_day_of_week = date('w', $first_day);
    $month_name = date('F', $first_day);
    
    echo '<div class="calendar-month-view">';
    echo '<div class="calendar-weekdays">';
    $weekdays = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
    foreach ($weekdays as $day) {
        echo '<div class="weekday">' . esc_html__($day, 'saab') . '</div>';
    }
    echo '</div>';
    
    echo '<div class="calendar-days">';
    
    // Empty cells for days before the first day of the month
    for ($i = 0; $i < $first_day_of_week; $i++) {
        echo '<div class="calendar-day empty"></div>';
    }
    
    // Days of the month
    for ($day = 1; $day <= $days_in_month; $day++) {
        $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
        $day_events = array_filter($events, function($event) use ($date) {
            return $event['date'] === $date;
        });
        
        $is_today = $date === date('Y-m-d');
        $day_class = 'calendar-day';
        if ($is_today) $day_class .= ' today';
        if (!empty($day_events)) $day_class .= ' has-events';
        
        echo '<div class="' . esc_attr($day_class) . '" data-date="' . esc_attr($date) . '">';
        echo '<div class="day-number">' . esc_html($day) . '</div>';
        
        if (!empty($day_events)) {
            echo '<div class="day-events">';
            foreach (array_slice($day_events, 0, 3) as $event) {
                echo '<div class="day-event" style="background-color: ' . esc_attr($event['color']) . ';" data-event-id="' . esc_attr($event['id']) . '">';
                echo '<span class="event-title">' . esc_html($event['title']) . '</span>';
                if ($event['time']) {
                    echo '<span class="event-time">' . esc_html($event['time']) . '</span>';
                }
                echo '</div>';
            }
            if (count($day_events) > 3) {
                echo '<div class="more-events">+' . (count($day_events) - 3) . '</div>';
            }
            echo '</div>';
        }
        
        echo '</div>';
    }
    
    echo '</div>';
    echo '</div>';
}

/**
 * Display weekly calendar view
 */
function saab_weekly_calendar_view($start_date, $events) {
    $week_start = strtotime($start_date);
    $week_end = strtotime('+6 days', $week_start);
    
    echo '<div class="calendar-week-view">';
    echo '<div class="week-header">';
    echo '<div class="week-day-header">' . esc_html__('Time', 'saab') . '</div>';
    
    for ($i = 0; $i < 7; $i++) {
        $day_date = date('Y-m-d', strtotime("+$i days", $week_start));
        $day_name = date('D', strtotime($day_date));
        $day_number = date('j', strtotime($day_date));
        $is_today = $day_date === date('Y-m-d');
        
        $day_class = 'week-day-header';
        if ($is_today) $day_class .= ' today';
        
        echo '<div class="' . esc_attr($day_class) . '" data-date="' . esc_attr($day_date) . '">';
        echo '<div class="day-name">' . esc_html__($day_name, 'saab') . '</div>';
        echo '<div class="day-number">' . esc_html($day_number) . '</div>';
        echo '</div>';
    }
    echo '</div>';
    
    echo '<div class="week-timeline">';
    // Generate time slots (9 AM to 9 PM)
    for ($hour = 9; $hour <= 21; $hour++) {
        echo '<div class="time-slot">';
        echo '<div class="time-label">' . esc_html(sprintf('%02d:00', $hour)) . '</div>';
        
        for ($day = 0; $day < 7; $day++) {
            $day_date = date('Y-m-d', strtotime("+$day days", $week_start));
            $day_events = array_filter($events, function($event) use ($day_date, $hour) {
                if ($event['date'] !== $day_date) return false;
                if (!$event['time']) return false;
                
                $event_hour = (int) substr($event['time'], 0, 2);
                return $event_hour === $hour;
            });
            
            $cell_class = 'time-cell';
            if (!empty($day_events)) $cell_class .= ' has-event';
            
            echo '<div class="' . esc_attr($cell_class) . '">';
            foreach ($day_events as $event) {
                echo '<div class="timeline-event" style="background-color: ' . esc_attr($event['color']) . ';" data-event-id="' . esc_attr($event['id']) . '">';
                echo '<div class="event-title">' . esc_html($event['title']) . '</div>';
                echo '<div class="event-time">' . esc_html($event['time']) . '</div>';
                echo '</div>';
            }
            echo '</div>';
        }
        echo '</div>';
    }
    echo '</div>';
    echo '</div>';
}

/**
 * Display list calendar view
 */
function saab_list_calendar_view($events) {
    // Sort events by date
    usort($events, function($a, $b) {
        return strtotime($a['date']) - strtotime($b['date']);
    });
    
    echo '<div class="calendar-list-view">';
    
    $current_date = '';
    foreach ($events as $event) {
        $event_date = date('Y-m-d', strtotime($event['date']));
        
        if ($event_date !== $current_date) {
            if ($current_date !== '') {
                echo '</div>'; // Close previous day group
            }
            
            $current_date = $event_date;
            $date_display = date('l, F j, Y', strtotime($event_date));
            $is_today = $event_date === date('Y-m-d');
            
            $day_class = 'calendar-day-group';
            if ($is_today) $day_class .= ' today';
            
            echo '<div class="' . esc_attr($day_class) . '">';
            echo '<h4 class="day-header">' . esc_html($date_display) . '</h4>';
        }
        
        echo '<div class="list-event" data-event-id="' . esc_attr($event['id']) . '">';
        echo '<div class="event-time-badge" style="background-color: ' . esc_attr($event['color']) . ';">';
        echo '<span class="event-time">' . esc_html($event['time'] ?: 'TBD') . '</span>';
        echo '</div>';
        echo '<div class="event-details">';
        echo '<h5 class="event-title">' . esc_html($event['title']) . '</h5>';
        if ($event['location']) {
            echo '<p class="event-location"><i class="fas fa-map-marker-alt"></i> ' . esc_html($event['location']) . '</p>';
        }
        if ($event['excerpt']) {
            echo '<p class="event-excerpt">' . esc_html($event['excerpt']) . '</p>';
        }
        echo '<a href="' . esc_url($event['url']) . '" class="event-link">' . esc_html__('View Details', 'saab') . '</a>';
        echo '</div>';
        echo '</div>';
    }
    
    if ($current_date !== '') {
        echo '</div>'; // Close last day group
    }
    
    echo '</div>';
}

/**
 * Display upcoming events widget
 */
function saab_upcoming_events_widget($limit = 5) {
    $events = saab_get_calendar_events(date('Y-m-d'), date('Y-m-d', strtotime('+30 days')));
    
    if (empty($events)) {
        return;
    }
    
    // Sort by date and limit
    usort($events, function($a, $b) {
        return strtotime($a['date']) - strtotime($b['date']);
    });
    
    $events = array_slice($events, 0, $limit);
    
    echo '<div class="upcoming-events-widget">';
    echo '<h3 class="widget-title">' . esc_html__('Upcoming Events', 'saab') . '</h3>';
    echo '<div class="events-list">';
    
    foreach ($events as $event) {
        echo '<div class="event-item" data-event-id="' . esc_attr($event['id']) . '">';
        echo '<div class="event-date">';
        echo '<span class="day">' . esc_html(date('j', strtotime($event['date']))) . '</span>';
        echo '<span class="month">' . esc_html(date('M', strtotime($event['date']))) . '</span>';
        echo '</div>';
        echo '<div class="event-info">';
        echo '<h4 class="event-title">' . esc_html($event['title']) . '</h4>';
        if ($event['time']) {
            echo '<p class="event-time"><i class="fas fa-clock"></i> ' . esc_html($event['time']) . '</p>';
        }
        if ($event['location']) {
            echo '<p class="event-location"><i class="fas fa-map-marker-alt"></i> ' . esc_html($event['location']) . '</p>';
        }
        echo '</div>';
        echo '</div>';
    }
    
    echo '</div>';
    echo '<a href="#" class="view-all-events">' . esc_html__('View All Events', 'saab') . '</a>';
    echo '</div>';
}

// Flush rewrite rules on theme activation
function saab_flush_rewrite_rules() {
    saab_register_post_types();
    saab_register_taxonomies();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'saab_flush_rewrite_rules');

// Manual flush rewrite rules function
function saab_manual_flush_rewrite_rules() {
    if (isset($_GET['flush_rewrite_rules']) && current_user_can('manage_options')) {
        saab_register_post_types();
        saab_register_taxonomies();
        flush_rewrite_rules();
        wp_die('Rewrite rules flushed successfully! You can now close this window.');
    }
}
add_action('init', 'saab_manual_flush_rewrite_rules');

// Force flush rewrite rules on theme activation and deactivation
function saab_force_flush_rewrite_rules() {
    saab_register_post_types();
    saab_register_taxonomies();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'saab_force_flush_rewrite_rules');
add_action('after_setup_theme', 'saab_force_flush_rewrite_rules');

// Add admin notice for manual flush
function saab_admin_notice_rewrite_rules() {
    if (current_user_can('manage_options') && isset($_GET['page']) && $_GET['page'] === 'flush-rewrite-rules') {
        echo '<div class="notice notice-success is-dismissible"><p>Rewrite rules have been flushed successfully. Your custom post types should now be accessible.</p></div>';
    }
}
add_action('admin_notices', 'saab_admin_notice_rewrite_rules');

// Add admin menu for manual flush
function saab_add_admin_menu() {
    add_management_page(
        'Flush Rewrite Rules',
        'Flush Rewrite Rules',
        'manage_options',
        'flush-rewrite-rules',
        'saab_flush_rewrite_rules_page'
    );
}
add_action('admin_menu', 'saab_add_admin_menu');

// Admin page for flushing rewrite rules
function saab_flush_rewrite_rules_page() {
    if (isset($_POST['flush_rewrite_rules']) && wp_verify_nonce($_POST['_wpnonce'], 'flush_rewrite_rules')) {
        saab_register_post_types();
        saab_register_taxonomies();
        flush_rewrite_rules();
        echo '<div class="notice notice-success"><p>Rewrite rules have been flushed successfully!</p></div>';
    }
    
    echo '<div class="wrap">';
    echo '<h1>Flush Rewrite Rules</h1>';
    echo '<p>If you\'re having issues accessing custom post type pages (like Films, News, etc.), click the button below to flush the rewrite rules.</p>';
    echo '<form method="post">';
    wp_nonce_field('flush_rewrite_rules');
    echo '<input type="submit" name="flush_rewrite_rules" class="button button-primary" value="Flush Rewrite Rules">';
    echo '</form>';
    echo '</div>';
}

// Enqueue Leaflet for the homepage map
function saab_enqueue_leaflet() {
    if (is_front_page()) {
        wp_enqueue_style('leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css', array(), '1.9.4');
        wp_enqueue_script('leaflet', 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js', array(), '1.9.4', true);
        wp_enqueue_script('saab-leaflet-map', get_template_directory_uri() . '/assets/js/leaflet-map.js', array('leaflet'), '1.0.0', true);
        // Pass screening locations to JS
        $screenings = new WP_Query([
            'post_type' => 'screening',
            'posts_per_page' => -1,
            'meta_query' => [
                ['key' => '_saab_screening_location_lat', 'compare' => 'EXISTS'],
                ['key' => '_saab_screening_location_lng', 'compare' => 'EXISTS'],
            ]
        ]);
        $locations = [];
        if ($screenings->have_posts()) :
            while ($screenings->have_posts()) : $screenings->the_post();
                $lat = get_post_meta(get_the_ID(), '_saab_screening_location_lat', true);
                $lng = get_post_meta(get_the_ID(), '_saab_screening_location_lng', true);
                if ($lat && $lng) {
                    $locations[] = [
                        'title' => get_the_title(),
                        'permalink' => get_permalink(),
                        'lat' => $lat,
                        'lng' => $lng,
                    ];
                }
            endwhile;
        endif;
        wp_reset_postdata();
        wp_localize_script('saab-leaflet-map', 'saabScreeningLocations', $locations);
        wp_localize_script('saab-leaflet-map', 'saabThemeData', array('url' => get_template_directory_uri()));
    }
}
add_action('wp_enqueue_scripts', 'saab_enqueue_leaflet');

// TEMPORARY: Force flush rewrite rules to fix CPT issues
function saab_temp_flush_rewrite_rules() {
    // Only run once per session
    if (!get_transient('saab_rewrite_flushed')) {
        flush_rewrite_rules();
        set_transient('saab_rewrite_flushed', true, HOUR_IN_SECONDS);
    }
}
add_action('init', 'saab_temp_flush_rewrite_rules', 999);

// Fallback menu for header navigation
function saab_fallback_menu() {
    echo '<ul class="nav-menu">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'saab') . '</a></li>';
    echo '<li><a href="' . esc_url(get_post_type_archive_link('film')) . '">' . esc_html__('Films', 'saab') . '</a></li>';
    echo '<li><a href="' . esc_url(get_post_type_archive_link('news')) . '">' . esc_html__('News', 'saab') . '</a></li>';
    echo '<li><a href="' . esc_url(get_post_type_archive_link('training_workshop')) . '">' . esc_html__('Workshops', 'saab') . '</a></li>';
    echo '<li><a href="' . esc_url(get_post_type_archive_link('publication')) . '">' . esc_html__('Publications', 'saab') . '</a></li>';
    echo '</ul>';
}


