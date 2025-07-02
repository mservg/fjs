<?php
/**
 * Single News Template
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<!-- News Hero -->
<div class="hero news-hero">
    <?php if (has_post_thumbnail()) : ?>
        <div class="hero-background" style="background-image: url('<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'news-featured')); ?>');"></div>
    <?php endif; ?>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="news-meta">
            <span class="news-badge">
                <?php 
                $badge = get_post_meta(get_the_ID(), '_saab_news_badge', true) ?: 'News';
                echo esc_html($badge);
                ?>
            </span>
        </div>
        <h1 class="hero-title"><?php the_title(); ?></h1>
        <div class="news-details single-meta">
            <span>
                <i class="fas fa-calendar"></i>
                <?php echo get_the_date('j F Y'); ?>
            </span>
            <span>
                <i class="fas fa-user"></i>
                <?php the_author(); ?>
            </span>
        </div>
    </div>
</div>

<div class="section section-white">
    <div class="container">
        <?php if (function_exists('saab_breadcrumbs')) saab_breadcrumbs(); ?>
        
        <div class="news-content-grid" style="display: grid; grid-template-columns: 2fr 1fr; gap: 3rem; margin-top: 2rem;">
            <!-- Main Content -->
            <div class="news-main-content">
                <article class="news-article">
                    <div class="article-content">
                        <?php the_content(); ?>
                    </div>
                    
                    <!-- Article Meta -->
                    <?php 
                    $source = get_post_meta(get_the_ID(), '_saab_news_source', true);
                    $external_url = get_post_meta(get_the_ID(), '_saab_news_external_url', true);
                    if ($source || $external_url) : ?>
                        <div class="article-meta sidebar-section">
                            <h4><?php esc_html_e('Additional Information', 'saab'); ?></h4>
                            <?php if ($source) : ?>
                                <p>
                                    <strong><?php esc_html_e('Source:', 'saab'); ?></strong> 
                                    <span><?php echo esc_html($source); ?></span>
                                </p>
                            <?php endif; ?>
                            <?php if ($external_url) : ?>
                                <p>
                                    <a href="<?php echo esc_url($external_url); ?>" target="_blank" rel="noopener" class="btn-enhanced btn-enhanced-outline btn-enhanced-sm">
                                        <i class="fas fa-external-link-alt"></i>
                                        <?php esc_html_e('Read Original Article', 'saab'); ?>
                                    </a>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </article>
            </div>
            
            <!-- Sidebar -->
            <div class="news-sidebar">
                <!-- Share Buttons -->
                <div class="share-section sidebar-section">
                    <h4><?php esc_html_e('Share This Article', 'saab'); ?></h4>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                           target="_blank" rel="noopener" class="btn-enhanced btn-enhanced-outline btn-enhanced-sm" aria-label="Share on Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                           target="_blank" rel="noopener" class="btn-enhanced btn-enhanced-outline btn-enhanced-sm" aria-label="Share on Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" 
                           target="_blank" rel="noopener" class="btn-enhanced btn-enhanced-outline btn-enhanced-sm" aria-label="Share on LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="mailto:?subject=<?php echo urlencode(get_the_title()); ?>&body=<?php echo urlencode(get_permalink()); ?>" 
                           class="btn-enhanced btn-enhanced-outline btn-enhanced-sm" aria-label="Share by Email">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Related News -->
                <div class="related-news-section sidebar-section">
                    <h4><?php esc_html_e('Related News', 'saab'); ?></h4>
                    <?php
                    $related_news = new WP_Query(array(
                        'post_type' => 'news',
                        'posts_per_page' => 3,
                        'post__not_in' => array(get_the_ID()),
                        'orderby' => 'date',
                        'order' => 'DESC',
                    ));
                    if ($related_news->have_posts()) :
                        while ($related_news->have_posts()) : $related_news->the_post();
                            ?>
                            <div class="related-news-item">
                                <h5>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h5>
                                <p>
                                    <?php echo get_the_date('j M Y'); ?>
                                </p>
                            </div>
                        <?php endwhile;
                        wp_reset_postdata();
                    else : ?>
                        <p class="no-related-news">
                            <?php esc_html_e('No related news at the moment.', 'saab'); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- News Gallery -->
<?php
$gallery = get_post_meta(get_the_ID(), '_saab_news_gallery', true);
if (!empty($gallery)) :
    $ids = explode(',', $gallery);
    if (!empty($ids)) : ?>
        <div class="section section-cream">
            <div class="container">
                <h2 class="section-title-centered">
                    <?php esc_html_e('Gallery', 'saab'); ?>
                </h2>
                <div class="news-gallery-swiper swiper-container">
                    <div class="swiper-wrapper">
                        <?php foreach ($ids as $img_id) : 
                            $img_url = wp_get_attachment_image_url($img_id, 'gallery-thumb');
                            if ($img_url) : ?>
                                <div class="swiper-slide">
                                    <img src="<?php echo esc_url($img_url); ?>" 
                                         alt="<?php esc_attr_e('News image', 'saab'); ?>" 
                                         loading="lazy" />
                                </div>
                            <?php endif;
                        endforeach; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next" tabindex="0" role="button" aria-label="<?php esc_attr_e('Next slide', 'saab'); ?>"></div>
                    <div class="swiper-button-prev" tabindex="0" role="button" aria-label="<?php esc_attr_e('Previous slide', 'saab'); ?>"></div>
                </div>
            </div>
        </div>
    <?php endif;
endif; ?>

<?php if (function_exists('saab_display_related_content')) saab_display_related_content(get_the_ID()); ?>

<?php endwhile; ?>

<?php get_footer(); ?> 