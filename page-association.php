<?php
/**
 * Association/About Page Template - Living Organization Portrait
 * Template Name: Association
 */

get_header(); ?>

<div class="association-page">
    <!-- Hero Section with Team Mosaic -->
    <section class="association-hero">
        <div class="hero-background">
            <div class="team-mosaic">
                <?php if (function_exists('have_rows') && have_rows('team_members')) : ?>
                    <?php $count = 0; while (have_rows('team_members')) : the_row(); 
                        $image = function_exists('get_sub_field') ? get_sub_field('photo') : '';
                        $name = function_exists('get_sub_field') ? get_sub_field('name') : '';
                        if ($count < 6 && $image) : ?>
                            <div class="mosaic-item" data-name="<?php echo esc_attr($name); ?>">
                                <img src="<?php echo esc_url($image['url']); ?>" 
                                     alt="<?php echo esc_attr($image['alt']); ?>" 
                                     class="mosaic-image">
                            </div>
                        <?php endif; $count++; endwhile; ?>
                <?php endif; ?>
            </div>
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-content">
            <div class="container">
                <h1 class="hero-title"><?php the_title(); ?></h1>
                <?php if (function_exists('get_field') && get_field('subtitle')) : ?>
                    <p class="hero-subtitle"><?php echo esc_html(get_field('subtitle')); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Cards -->
    <section class="mission-vision-section">
        <div class="container">
            <div class="mission-vision-grid">
                <div class="mission-card">
                    <div class="card-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h2><?php esc_html_e('Our Mission', 'saab'); ?></h2>
                    <?php if (function_exists('get_field') && get_field('mission')) : ?>
                        <p><?php echo esc_html(get_field('mission')); ?></p>
                    <?php endif; ?>
                    <div class="card-details">
                        <button class="details-toggle" aria-expanded="false">
                            <?php esc_html_e('Learn More', 'saab'); ?>
                        </button>
                        <div class="details-content">
                            <?php if (function_exists('get_field') && get_field('mission_details')) : ?>
                                <?php echo wp_kses_post(get_field('mission_details')); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="vision-card">
                    <div class="card-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h2><?php esc_html_e('Our Vision', 'saab'); ?></h2>
                    <?php if (function_exists('get_field') && get_field('vision')) : ?>
                        <p><?php echo esc_html(get_field('vision')); ?></p>
                    <?php endif; ?>
                    <div class="card-details">
                        <button class="details-toggle" aria-expanded="false">
                            <?php esc_html_e('Learn More', 'saab'); ?>
                        </button>
                        <div class="details-content">
                            <?php if (function_exists('get_field') && get_field('vision_details')) : ?>
                                <?php echo wp_kses_post(get_field('vision_details')); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Content Section -->
    <section class="about-content-section">
        <div class="container">
            <div class="about-grid">
                <div class="about-text">
                    <h2><?php esc_html_e('About the Association', 'saab'); ?></h2>
                    <?php the_content(); ?>
                </div>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="about-image">
                        <?php the_post_thumbnail('large', array('class' => 'about-img')); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Interactive Team Section -->
    <?php if (function_exists('have_rows') && have_rows('team_members')) : ?>
    <section class="team-section">
        <div class="container">
            <div class="section-header">
                <h2><?php esc_html_e('Our Team', 'saab'); ?></h2>
                <p><?php esc_html_e('Meet the people behind the Jocelyne Saab Association', 'saab'); ?></p>
            </div>
            
            <div class="team-filters">
                <button class="team-filter active" data-filter="all"><?php esc_html_e('All', 'saab'); ?></button>
                <button class="team-filter" data-filter="leadership"><?php esc_html_e('Leadership', 'saab'); ?></button>
                <button class="team-filter" data-filter="curatorial"><?php esc_html_e('Curatorial', 'saab'); ?></button>
                <button class="team-filter" data-filter="technical"><?php esc_html_e('Technical', 'saab'); ?></button>
            </div>
            
            <div class="team-grid">
                <?php while (have_rows('team_members')) : the_row(); 
                    $image = function_exists('get_sub_field') ? get_sub_field('photo') : '';
                    $name = function_exists('get_sub_field') ? get_sub_field('name') : '';
                    $role = function_exists('get_sub_field') ? get_sub_field('role') : '';
                    $bio = function_exists('get_sub_field') ? get_sub_field('bio') : '';
                    $email = function_exists('get_sub_field') ? get_sub_field('email') : '';
                    $category = function_exists('get_sub_field') ? get_sub_field('category') : 'general';
                ?>
                    <div class="team-member" data-category="<?php echo esc_attr($category); ?>">
                        <div class="member-image">
                            <?php if ($image) : ?>
                                <img src="<?php echo esc_url($image['url']); ?>" 
                                     alt="<?php echo esc_attr($image['alt']); ?>" 
                                     class="member-photo">
                            <?php else : ?>
                                <div class="member-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="member-info">
                            <h3><?php echo esc_html($name); ?></h3>
                            <p class="member-role"><?php echo esc_html($role); ?></p>
                            <p class="member-bio"><?php echo esc_html($bio); ?></p>
                            
                            <?php if ($email) : ?>
                                <a href="mailto:<?php echo esc_attr($email); ?>" 
                                   class="member-email">
                                    <i class="fas fa-envelope"></i>
                                    <?php echo esc_html($email); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Values Section -->
    <?php if (function_exists('have_rows') && have_rows('values')) : ?>
    <section class="values-section">
        <div class="container">
            <div class="section-header">
                <h2><?php esc_html_e('Our Values', 'saab'); ?></h2>
                <p><?php esc_html_e('The principles that guide our work and mission', 'saab'); ?></p>
            </div>
            
            <div class="values-grid">
                <?php while (have_rows('values')) : the_row(); ?>
                    <div class="value-item">
                        <div class="value-icon">
                            <i class="<?php echo esc_attr(function_exists('get_sub_field') ? get_sub_field('icon') : ''); ?>"></i>
                        </div>
                        <h3><?php echo esc_html(function_exists('get_sub_field') ? get_sub_field('title') : ''); ?></h3>
                        <p><?php echo esc_html(function_exists('get_sub_field') ? get_sub_field('description') : ''); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- History Timeline -->
    <?php if (function_exists('have_rows') && have_rows('history_timeline')) : ?>
    <section class="history-section">
        <div class="container">
            <div class="section-header">
                <h2><?php esc_html_e('Our History', 'saab'); ?></h2>
                <p><?php esc_html_e('Key milestones in the association\'s journey', 'saab'); ?></p>
            </div>
            
            <div class="history-timeline">
                <?php while (have_rows('history_timeline')) : the_row(); ?>
                    <div class="history-item">
                        <div class="history-date">
                            <?php echo esc_html(function_exists('get_sub_field') ? get_sub_field('date') : ''); ?>
                        </div>
                        <div class="history-content">
                            <h3><?php echo esc_html(function_exists('get_sub_field') ? get_sub_field('title') : ''); ?></h3>
                            <p><?php echo esc_html(function_exists('get_sub_field') ? get_sub_field('description') : ''); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Partners & Collaborations -->
    <?php if (function_exists('have_rows') && have_rows('partners')) : ?>
    <section class="partners-section">
        <div class="container">
            <div class="section-header">
                <h2><?php esc_html_e('Partners & Collaborations', 'saab'); ?></h2>
                <p><?php esc_html_e('Organizations and institutions that support our mission', 'saab'); ?></p>
            </div>
            
            <div class="partners-showcase">
                <?php while (have_rows('partners')) : the_row(); ?>
                    <div class="partner-item">
                        <?php 
                        $logo = function_exists('get_sub_field') ? get_sub_field('logo') : '';
                        if ($logo) : ?>
                            <div class="partner-logo">
                                <img src="<?php echo esc_url($logo['url']); ?>" 
                                     alt="<?php echo esc_attr($logo['alt']); ?>" 
                                     class="partner-img">
                            </div>
                        <?php endif; ?>
                        
                        <div class="partner-info">
                            <h3><?php echo esc_html(function_exists('get_sub_field') ? get_sub_field('name') : ''); ?></h3>
                            <p><?php echo esc_html(function_exists('get_sub_field') ? get_sub_field('description') : ''); ?></p>
                            
                            <?php if (function_exists('get_sub_field') && get_sub_field('website')) : ?>
                                <a href="<?php echo esc_url(get_sub_field('website')); ?>" 
                                   target="_blank" class="partner-link">
                                    <?php esc_html_e('Visit Website', 'saab'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2><?php esc_html_e('Get Involved', 'saab'); ?></h2>
                <p><?php esc_html_e('Join us in preserving and promoting the legacy of Jocelyne Saab.', 'saab'); ?></p>
                <div class="cta-buttons">
                    <a href="<?php echo get_permalink(get_page_by_path('contact')); ?>" class="btn-primary">
                        <?php esc_html_e('Contact Us', 'saab'); ?>
                    </a>
                    <a href="<?php echo get_permalink(get_page_by_path('support')); ?>" class="btn-secondary">
                        <?php esc_html_e('Support Us', 'saab'); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?> 