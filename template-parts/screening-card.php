<?php
/**
 * Screening Card Template Part
 * Displays individual screening information in a card format
 */

$screening_date = get_post_meta(get_the_ID(), '_saab_screening_date', true);
$screening_time = get_post_meta(get_the_ID(), '_saab_screening_time', true);
$screening_location = get_post_meta(get_the_ID(), '_saab_screening_location', true);
$screening_film = get_post_meta(get_the_ID(), '_saab_screening_film', true);
$screening_type = get_post_meta(get_the_ID(), '_saab_screening_type', true);
$screening_tickets = get_post_meta(get_the_ID(), '_saab_screening_tickets', true);

$is_upcoming = $screening_date && strtotime($screening_date) >= strtotime(date('Y-m-d'));
$formatted_date = $screening_date ? date_i18n(get_option('date_format'), strtotime($screening_date)) : '';
?>

<article class="screening-card card-enhanced <?php echo $is_upcoming ? 'upcoming' : 'past'; ?>" id="screening-<?php echo get_the_ID(); ?>">
    
    <!-- Screening Image -->
    <div class="screening-image card-enhanced-image">
        <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('screening-featured', array('alt' => get_the_title())); ?>
            </a>
        <?php else : ?>
            <div class="screening-placeholder">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2" stroke="currentColor" stroke-width="2"/>
                    <line x1="8" y1="21" x2="16" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <line x1="12" y1="17" x2="12" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        <?php endif; ?>
        
        <div class="card-enhanced-overlay"></div>
        
        <!-- Screening Status Badge -->
        <?php if ($is_upcoming) : ?>
            <div class="badge badge-upcoming">
                <?php esc_html_e('Upcoming', 'saab'); ?>
            </div>
        <?php else : ?>
            <div class="badge badge-past">
                <?php esc_html_e('Past', 'saab'); ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Screening Content -->
    <div class="card-enhanced-content">
        <h3 class="card-enhanced-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <!-- Screening Meta -->
        <div class="card-enhanced-meta">
            <?php if ($formatted_date) : ?>
                <span class="screening-date">
                    <i class="fas fa-calendar" aria-hidden="true"></i>
                    <time datetime="<?php echo esc_attr($screening_date); ?>">
                        <?php echo esc_html($formatted_date); ?>
                    </time>
                </span>
            <?php endif; ?>

            <?php if ($screening_time) : ?>
                <span class="screening-time">
                    <i class="fas fa-clock" aria-hidden="true"></i>
                    <?php echo esc_html($screening_time); ?>
                </span>
            <?php endif; ?>

            <?php if ($screening_location) : ?>
                <span class="screening-location">
                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                    <?php echo esc_html($screening_location); ?>
                </span>
            <?php endif; ?>

            <?php if ($screening_film) : ?>
                <span class="screening-film">
                    <i class="fas fa-video" aria-hidden="true"></i>
                    <?php echo esc_html($screening_film); ?>
                </span>
            <?php endif; ?>

            <?php if ($screening_type) : ?>
                <span class="screening-type">
                    <i class="fas fa-tag" aria-hidden="true"></i>
                    <?php echo esc_html($screening_type); ?>
                </span>
            <?php endif; ?>
        </div>

        <!-- Screening Excerpt -->
        <div class="card-enhanced-excerpt">
            <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
        </div>

        <!-- Screening Actions -->
        <div class="card-enhanced-actions">
            <a href="<?php the_permalink(); ?>" class="btn-enhanced btn-enhanced-outline btn-enhanced-sm">
                <?php esc_html_e('View Details', 'saab'); ?>
            </a>
            
            <?php if ($is_upcoming && $screening_tickets) : ?>
                <a href="<?php echo esc_url($screening_tickets); ?>" 
                   class="btn-enhanced btn-enhanced-primary btn-enhanced-sm" 
                   target="_blank" 
                   rel="noopener noreferrer">
                    <?php esc_html_e('Get Tickets', 'saab'); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</article> 