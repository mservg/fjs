<?php
/**
 * Workshop Card Template Part
 * Displays individual workshop information in a card format
 */

$workshop_date = get_post_meta(get_the_ID(), '_saab_training_workshop_date', true);
$workshop_location = get_post_meta(get_the_ID(), '_saab_training_workshop_location', true);
$workshop_duration = get_post_meta(get_the_ID(), '_saab_training_workshop_duration', true);
$workshop_trainers = get_post_meta(get_the_ID(), '_saab_training_workshop_trainers', true);
$workshop_type = get_post_meta(get_the_ID(), '_saab_training_workshop_type', true);
$workshop_registration = get_post_meta(get_the_ID(), '_saab_training_workshop_registration', true);

$is_upcoming = $workshop_date && strtotime($workshop_date) >= strtotime(date('Y-m-d'));
$formatted_date = $workshop_date ? date_i18n(get_option('date_format'), strtotime($workshop_date)) : '';
?>

<article class="workshop-card card-enhanced <?php echo $is_upcoming ? 'upcoming' : 'past'; ?>" id="workshop-<?php echo get_the_ID(); ?>">
    
    <!-- Workshop Image -->
    <div class="workshop-image card-enhanced-image">
        <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('workshop-featured', array('alt' => get_the_title())); ?>
            </a>
        <?php else : ?>
            <div class="workshop-placeholder">
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
        
        <!-- Workshop Status Badge -->
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

    <!-- Workshop Content -->
    <div class="card-enhanced-content">
        <h3 class="card-enhanced-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <!-- Workshop Meta -->
        <div class="card-enhanced-meta">
            <?php if ($formatted_date) : ?>
                <span class="workshop-date">
                    <i class="fas fa-calendar" aria-hidden="true"></i>
                    <time datetime="<?php echo esc_attr($workshop_date); ?>">
                        <?php echo esc_html($formatted_date); ?>
                    </time>
                </span>
            <?php endif; ?>

            <?php if ($workshop_location) : ?>
                <span class="workshop-location">
                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                    <?php echo esc_html($workshop_location); ?>
                </span>
            <?php endif; ?>

            <?php if ($workshop_duration) : ?>
                <span class="workshop-duration">
                    <i class="fas fa-clock" aria-hidden="true"></i>
                    <?php echo esc_html($workshop_duration); ?>
                </span>
            <?php endif; ?>

            <?php if ($workshop_trainers) : ?>
                <span class="workshop-trainers">
                    <i class="fas fa-user-tie" aria-hidden="true"></i>
                    <?php echo esc_html($workshop_trainers); ?>
                </span>
            <?php endif; ?>

            <?php if ($workshop_type) : ?>
                <span class="workshop-type">
                    <i class="fas fa-tag" aria-hidden="true"></i>
                    <?php echo esc_html($workshop_type); ?>
                </span>
            <?php endif; ?>
        </div>

        <!-- Workshop Excerpt -->
        <div class="card-enhanced-excerpt">
            <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
        </div>

        <!-- Workshop Actions -->
        <div class="card-enhanced-actions">
            <a href="<?php the_permalink(); ?>" class="btn-enhanced btn-enhanced-outline btn-enhanced-sm">
                <?php esc_html_e('View Details', 'saab'); ?>
            </a>
            
            <?php if ($is_upcoming && $workshop_registration) : ?>
                <a href="<?php echo esc_url($workshop_registration); ?>" 
                   class="btn-enhanced btn-enhanced-primary btn-enhanced-sm" 
                   target="_blank" 
                   rel="noopener noreferrer">
                    <?php esc_html_e('Register Now', 'saab'); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</article> 