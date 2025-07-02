<?php
/**
 * Default Page Template
 * Used for all pages that don't have a specific template
 */

get_header(); ?>

<div class="default-page">
    <!-- Hero Section -->
    <section class="page-hero">
        <div class="hero-background">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('full', array('class' => 'hero-image')); ?>
            <?php endif; ?>
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

    <!-- Main Content Section -->
    <section class="page-content-section">
        <div class="container">
            <div class="content-grid">
                <!-- Main Content -->
                <div class="main-content">
                    <article class="page-content">
                        <?php while (have_posts()) : the_post(); ?>
                            <div class="content-text">
                                <?php the_content(); ?>
                            </div>
                            
                            <!-- Page Navigation -->
                            <?php
                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'saab'),
                                'after'  => '</div>',
                            ));
                            ?>
                        <?php endwhile; ?>
                    </article>
                </div>
                
                <!-- Sidebar Content -->
                <div class="sidebar-content">
                    <!-- Quick Links -->
                    <?php if (function_exists('get_field') && get_field('quick_links')) : ?>
                        <div class="quick-links-card">
                            <h3><?php esc_html_e('Quick Links', 'saab'); ?></h3>
                            <div class="links-list">
                                <?php foreach (get_field('quick_links') as $link) : ?>
                                    <a href="<?php echo esc_url($link['url']); ?>" class="quick-link">
                                        <i class="fas fa-arrow-right"></i>
                                        <span><?php echo esc_html($link['text']); ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Related Pages -->
                    <?php
                    $related_pages = get_posts(array(
                        'post_type' => 'page',
                        'posts_per_page' => 3,
                        'post__not_in' => array(get_the_ID()),
                        'meta_query' => array(
                            array(
                                'key' => '_wp_page_template',
                                'value' => array('page-biography.php', 'page-association.php', 'page-contact.php', 'page-work.php'),
                                'compare' => 'IN'
                            )
                        )
                    ));
                    
                    if (!empty($related_pages)) : ?>
                        <div class="related-pages-card">
                            <h3><?php esc_html_e('Related Pages', 'saab'); ?></h3>
                            <div class="related-pages-list">
                                <?php foreach ($related_pages as $related_page) : ?>
                                    <a href="<?php echo get_permalink($related_page->ID); ?>" class="related-page-link">
                                        <div class="page-info">
                                            <h4><?php echo esc_html($related_page->post_title); ?></h4>
                                            <p><?php echo wp_trim_words($related_page->post_excerpt, 10); ?></p>
                                        </div>
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Contact CTA -->
                    <div class="contact-cta-card">
                        <h3><?php esc_html_e('Get in Touch', 'saab'); ?></h3>
                        <p><?php esc_html_e('Have questions or want to learn more? We\'d love to hear from you.', 'saab'); ?></p>
                        <a href="<?php echo get_permalink(get_page_by_path('contact')); ?>" class="btn-primary">
                            <i class="fas fa-envelope"></i>
                            <?php esc_html_e('Contact Us', 'saab'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Content Sections -->
    <?php if (function_exists('have_rows') && have_rows('content_sections')) : ?>
        <?php while (have_rows('content_sections')) : the_row(); ?>
            <section class="additional-content-section">
                <div class="container">
                    <div class="section-header">
                        <h2><?php echo esc_html(get_sub_field('title')); ?></h2>
                        <?php if (get_sub_field('subtitle')) : ?>
                            <p><?php echo esc_html(get_sub_field('subtitle')); ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="section-content">
                        <?php if (get_sub_field('content_type') === 'text') : ?>
                            <div class="text-content">
                                <?php echo wp_kses_post(get_sub_field('text_content')); ?>
                            </div>
                        <?php elseif (get_sub_field('content_type') === 'image') : ?>
                            <div class="image-content">
                                <?php 
                                $image = get_sub_field('image');
                                if ($image) : ?>
                                    <img src="<?php echo esc_url($image['url']); ?>" 
                                         alt="<?php echo esc_attr($image['alt']); ?>" 
                                         class="content-image">
                                <?php endif; ?>
                            </div>
                        <?php elseif (get_sub_field('content_type') === 'gallery') : ?>
                            <div class="gallery-content">
                                <?php 
                                $gallery = get_sub_field('gallery');
                                if ($gallery) : ?>
                                    <div class="gallery-grid">
                                        <?php foreach ($gallery as $image) : ?>
                                            <div class="gallery-item">
                                                <img src="<?php echo esc_url($image['url']); ?>" 
                                                     alt="<?php echo esc_attr($image['alt']); ?>" 
                                                     class="gallery-image">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<?php get_footer(); ?> 