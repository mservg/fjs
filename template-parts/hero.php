<?php
/**
 * Template part for displaying the homepage hero section
 *
 * @package Saab
 */

// Get hero background options from customizer
$hero_bg_type = get_theme_mod('hero_bg_type', 'video');
$hero_bg_image = get_theme_mod('hero_bg_image');
$hero_bg_video = get_theme_mod('hero_bg_video', 'https://jocelynesaab.org/wp-content/uploads/2025/04/header_beyrouth_ma_ville_2_1.mp4');
$hero_cta_text = get_theme_mod('hero_cta_text', __('Explore Our Work', 'saab'));
$hero_cta_url = get_theme_mod('hero_cta_url', '#featured-films');

// Fallback to featured image from a page called "Home" if no customizer settings
if (!$hero_bg_image) {
    $home_page_query = new WP_Query(array(
        'post_type' => 'page',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        's' => 'Home',
        'exact' => true
    ));
    
    if ($home_page_query->have_posts()) {
        $home_page_query->the_post();
        if (has_post_thumbnail()) {
            $hero_bg_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
        }
        wp_reset_postdata();
    }
}
?>

<section class="hero-section" id="hero" role="banner">
    <div class="hero-background">
        <?php if ($hero_bg_type === 'video' && $hero_bg_video) : ?>
            <div class="hero-video-container">
                <video class="hero-video" autoplay muted loop playsinline preload="metadata" aria-hidden="true">
                    <source src="<?php echo esc_url($hero_bg_video); ?>" type="video/mp4">
                    Your browser does not support the video tag.
                    <?php if ($hero_bg_image) : ?>
                        <img src="<?php echo esc_url($hero_bg_image); ?>" alt="Hero background">
                    <?php endif; ?>
                </video>
            </div>
        <?php elseif ($hero_bg_image) : ?>
            <div class="hero-image-container">
                <img class="hero-image" 
                     src="<?php echo esc_url($hero_bg_image); ?>" 
                     alt="Hero background"
                     loading="eager">
            </div>
        <?php else : ?>
            <div class="hero-fallback"></div>
        <?php endif; ?>
        
        <div class="hero-overlay" aria-hidden="true"></div>
    </div>
    
    <div class="hero-content">
        <div class="container">
            <div class="hero-text">
                <h1 class="hero-title"><?php bloginfo('name'); ?></h1>
                <div class="hero-subtitle"><?php bloginfo('description'); ?></div>
                <?php if ($hero_cta_text && $hero_cta_url) : ?>
                    <div class="hero-cta">
                        <a href="<?php echo esc_url($hero_cta_url); ?>" class="btn btn-outline">
                            <?php echo esc_html($hero_cta_text); ?>
                            <span class="sr-only" style="position:absolute;left:-9999px;">Learn more about our work</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Scroll indicator -->
    <div class="hero-scroll-indicator" aria-hidden="true">
        <button class="scroll-down-btn" onclick="document.getElementById('featured-films').scrollIntoView({behavior: 'smooth'})" aria-label="<?php esc_attr_e('Scroll down to content', 'saab'); ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>
</section>