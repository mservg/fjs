<?php
/**
 * Archive template for Partners
 */

get_header(); ?>

<div class="hero archive-hero" style="height: 60vh;">
    <div class="hero-background" style="background-image: url('<?php echo esc_url(get_template_directory_uri() . '/assets/images/partners-hero.jpg'); ?>');"></div>
    <div class="hero-content">
        <h1><?php esc_html_e('Partners', 'saab'); ?></h1>
        <p class="hero-subtitle"><?php esc_html_e('Our valued partners and collaborators', 'saab'); ?></p>
    </div>
</div>

<div class="section section-white">
    <div class="container">
        <div class="section-header" style="text-align: center; margin-bottom: var(--space-60);">
            <h2 style="font-family: 'Epilogue', sans-serif; color: var(--brand-yellow); font-weight: 400;"><?php esc_html_e('Our Partners', 'saab'); ?></h2>
            <p style="color: var(--brand-blue); font-family: 'Lora', serif; font-size: 1.1rem;">
                <?php esc_html_e('Discover the organizations and institutions that support our mission.', 'saab'); ?>
            </p>
        </div>
        <div class="partners-grid">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/partner-card'); ?>
                <?php endwhile; ?>
            <?php else : ?>
                <p><?php esc_html_e('No partners found.', 'saab'); ?></p>
            <?php endif; ?>
        </div>
        <?php if (function_exists('wp_pagenavi')) : ?>
            <?php wp_pagenavi(); ?>
        <?php else : ?>
            <?php the_posts_navigation(); ?>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?> 