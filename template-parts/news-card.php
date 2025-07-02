<?php
/**
 * News Card Template Part
 * Used in: index.php, archive-news.php
 */

$news_categories = get_the_terms(get_the_ID(), 'news_category');
?>

<article class="news-card card-enhanced" data-news-id="<?php the_ID(); ?>">
    <div class="news-image card-enhanced-image">
        <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('news-featured', array('loading' => 'lazy', 'alt' => get_the_title())); ?>
            </a>
        <?php else : ?>
            <div class="news-placeholder">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <polyline points="14,2 14,8 20,8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <line x1="16" y1="13" x2="8" y2="13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <line x1="16" y1="17" x2="8" y2="17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span><?php echo esc_html(get_the_title()); ?></span>
            </div>
        <?php endif; ?>
        <div class="card-enhanced-overlay"></div>
    </div>
    
    <div class="card-enhanced-content">
        <div class="card-enhanced-meta">
            <span class="news-date">
                <i class="fas fa-calendar" aria-hidden="true"></i>
                <time datetime="<?php echo get_the_date('c'); ?>">
                    <?php echo get_the_date(); ?>
                </time>
            </span>
            <?php if ($news_categories && !is_wp_error($news_categories)) : ?>
                <span class="news-category">
                    <i class="fas fa-tag" aria-hidden="true"></i>
                    <?php echo esc_html($news_categories[0]->name); ?>
                </span>
            <?php endif; ?>
        </div>
        
        <h3 class="card-enhanced-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        
        <div class="card-enhanced-excerpt">
            <?php echo wp_trim_words(get_the_excerpt(), 25, '...'); ?>
        </div>
        
        <div class="card-enhanced-actions">
            <a href="<?php the_permalink(); ?>" class="btn-enhanced btn-enhanced-outline btn-enhanced-sm">
                <?php esc_html_e('Read More', 'saab'); ?>
            </a>
        </div>
    </div>
</article>