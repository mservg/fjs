<?php
/**
 * Partner Card Template Part
 * Used in: index.php, archive-partner.php
 */

$partner_website = get_post_meta(get_the_ID(), '_saab_partner_website', true);
$partnership_type = get_the_terms(get_the_ID(), 'partnership_type');
?>

<article class="partner-card card-enhanced" data-partner-id="<?php the_ID(); ?>">
    <div class="partner-image card-enhanced-image">
        <?php if (has_post_thumbnail()) : ?>
            <?php if ($partner_website) : ?>
                <a href="<?php echo esc_url($partner_website); ?>" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   aria-label="<?php echo esc_attr(sprintf(__('Visit %s website', 'saab'), get_the_title())); ?>">
                    <?php the_post_thumbnail('partner-logo', array('class' => 'partner-logo', 'loading' => 'lazy', 'alt' => get_the_title())); ?>
                </a>
            <?php else : ?>
                <?php the_post_thumbnail('partner-logo', array('class' => 'partner-logo', 'loading' => 'lazy', 'alt' => get_the_title())); ?>
            <?php endif; ?>
        <?php else : ?>
            <div class="partner-placeholder">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17 21V19C17 17.9391 16.5786 16.9217 15.8284 16.1716C15.0783 15.4214 14.0609 15 13 15H5C3.93913 15 2.92172 15.4214 2.17157 16.1716C1.42143 16.9217 1 17.9391 1 19V21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="2"/>
                    <path d="M23 21V19C22.9993 18.1137 22.7044 17.2528 22.1614 16.5523C21.6184 15.8519 20.8581 15.3516 20 15.13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16 3.13C16.8604 3.35031 17.623 3.85071 18.1676 4.55232C18.7122 5.25392 19.0078 6.11683 19.0078 7.005C19.0078 7.89317 18.7122 8.75608 18.1676 9.45768C17.623 10.1593 16.8604 10.6597 16 10.88" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
        <?php endif; ?>
        <div class="card-enhanced-overlay"></div>
    </div>
    
    <div class="card-enhanced-content">
        <h4 class="card-enhanced-title">
            <?php the_title(); ?>
        </h4>
        
        <?php if ($partnership_type && !is_wp_error($partnership_type)) : ?>
            <div class="card-enhanced-meta">
                <span class="partnership-type">
                    <i class="fas fa-handshake" aria-hidden="true"></i>
                    <?php echo esc_html($partnership_type[0]->name); ?>
                </span>
            </div>
        <?php endif; ?>
        
        <?php if ($partner_website) : ?>
            <div class="card-enhanced-actions">
                <a href="<?php echo esc_url($partner_website); ?>" 
                   class="btn-enhanced btn-enhanced-outline btn-enhanced-sm"
                   target="_blank" 
                   rel="noopener noreferrer">
                    <?php esc_html_e('Visit Website', 'saab'); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</article>