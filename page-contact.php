<?php
/**
 * Contact Page Template - Multi-Channel Communication Hub
 * Template Name: Contact
 */

get_header(); ?>

<div class="contact-page">
    <!-- Hero Section with Animated Contact Methods -->
    <section class="contact-hero">
        <div class="hero-background">
            <div class="contact-methods-animation">
                <div class="method-icon" data-method="email">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="method-icon" data-method="phone">
                    <i class="fas fa-phone"></i>
                </div>
                <div class="method-icon" data-method="location">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="method-icon" data-method="social">
                    <i class="fas fa-share-alt"></i>
                </div>
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

    <!-- Contact Preference Selector -->
    <section class="contact-preference-section">
        <div class="container">
            <div class="preference-selector">
                <h2><?php esc_html_e('How would you like to get in touch?', 'saab'); ?></h2>
                <div class="preference-options">
                    <button class="preference-option active" data-preference="form">
                        <i class="fas fa-envelope"></i>
                        <span><?php esc_html_e('Send a Message', 'saab'); ?></span>
                    </button>
                    <button class="preference-option" data-preference="direct">
                        <i class="fas fa-phone"></i>
                        <span><?php esc_html_e('Direct Contact', 'saab'); ?></span>
                    </button>
                    <button class="preference-option" data-preference="visit">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?php esc_html_e('Visit Us', 'saab'); ?></span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Content Section -->
    <section class="contact-content-section">
        <div class="container">
            <div class="contact-grid">
                <!-- Smart Contact Form -->
                <div class="contact-form-section" id="contact-form-section">
                    <div class="form-header">
                        <h2><?php esc_html_e('Send us a Message', 'saab'); ?></h2>
                        <p><?php esc_html_e('We\'d love to hear from you. Fill out the form below and we\'ll get back to you as soon as possible.', 'saab'); ?></p>
                    </div>
                    
                    <form class="smart-contact-form" method="post" action="">
                        <?php wp_nonce_field('contact_form', 'contact_nonce'); ?>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact-name"><?php esc_html_e('Name', 'saab'); ?> *</label>
                                <input type="text" id="contact-name" name="contact_name" required>
                            </div>
                            <div class="form-group">
                                <label for="contact-email"><?php esc_html_e('Email', 'saab'); ?> *</label>
                                <input type="email" id="contact-email" name="contact_email" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="contact-subject"><?php esc_html_e('Subject', 'saab'); ?> *</label>
                            <select id="contact-subject" name="contact_subject" required>
                                <option value=""><?php esc_html_e('Select a topic', 'saab'); ?></option>
                                <option value="general"><?php esc_html_e('General Inquiry', 'saab'); ?></option>
                                <option value="screening"><?php esc_html_e('Screening Request', 'saab'); ?></option>
                                <option value="collaboration"><?php esc_html_e('Collaboration', 'saab'); ?></option>
                                <option value="support"><?php esc_html_e('Support & Donations', 'saab'); ?></option>
                                <option value="media"><?php esc_html_e('Media & Press', 'saab'); ?></option>
                                <option value="other"><?php esc_html_e('Other', 'saab'); ?></option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="contact-message"><?php esc_html_e('Message', 'saab'); ?> *</label>
                            <textarea id="contact-message" name="contact_message" rows="6" required></textarea>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-paper-plane"></i>
                                <?php esc_html_e('Send Message', 'saab'); ?>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Direct Contact Information -->
                <div class="contact-info-section" id="contact-info-section" style="display: none;">
                    <div class="info-header">
                        <h2><?php esc_html_e('Direct Contact Information', 'saab'); ?></h2>
                        <p><?php esc_html_e('Get in touch with us directly through these channels.', 'saab'); ?></p>
                    </div>
                    
                    <div class="contact-methods">
                        <!-- Email -->
                        <div class="contact-method">
                            <div class="method-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="method-details">
                                <h3><?php esc_html_e('Email', 'saab'); ?></h3>
                                <p><a href="mailto:amicale.jocelynesaab@gmail.com">amicale.jocelynesaab@gmail.com</a></p>
                                <span class="response-time"><?php esc_html_e('Response within 24 hours', 'saab'); ?></span>
                            </div>
                        </div>

                        <!-- Phone -->
                        <?php if (function_exists('get_field') && get_field('phone')) : ?>
                        <div class="contact-method">
                            <div class="method-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="method-details">
                                <h3><?php esc_html_e('Phone', 'saab'); ?></h3>
                                <p><a href="tel:<?php echo esc_attr(get_field('phone')); ?>"><?php echo esc_html(get_field('phone')); ?></a></p>
                                <span class="response-time"><?php esc_html_e('Available during office hours', 'saab'); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Address -->
                        <?php if (function_exists('get_field') && get_field('address')) : ?>
                        <div class="contact-method">
                            <div class="method-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="method-details">
                                <h3><?php esc_html_e('Address', 'saab'); ?></h3>
                                <p><?php echo esc_html(get_field('address')); ?></p>
                                <span class="response-time"><?php esc_html_e('By appointment only', 'saab'); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Office Hours -->
                        <?php if (function_exists('get_field') && get_field('office_hours')) : ?>
                        <div class="contact-method">
                            <div class="method-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="method-details">
                                <h3><?php esc_html_e('Office Hours', 'saab'); ?></h3>
                                <p><?php echo esc_html(get_field('office_hours')); ?></p>
                                <span class="response-time"><?php esc_html_e('Current time zone', 'saab'); ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Visit Information -->
                <div class="visit-section" id="visit-section" style="display: none;">
                    <div class="visit-header">
                        <h2><?php esc_html_e('Visit Our Location', 'saab'); ?></h2>
                        <p><?php esc_html_e('Find us and plan your visit.', 'saab'); ?></p>
                    </div>
                    
                    <?php if (function_exists('get_field') && get_field('show_map') && get_field('map_address')) : ?>
                    <div class="location-map">
                        <div id="contact-map"></div>
                        <div class="map-info">
                            <h3><?php esc_html_e('Getting Here', 'saab'); ?></h3>
                            <p><?php echo esc_html(get_field('map_address')); ?></p>
                            <a href="https://maps.google.com/?q=<?php echo urlencode(get_field('map_address')); ?>" 
                               target="_blank" class="btn-secondary">
                                <i class="fas fa-directions"></i>
                                <?php esc_html_e('Get Directions', 'saab'); ?>
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Media Integration -->
    <section class="social-section">
        <div class="container">
            <div class="social-header">
                <h2><?php esc_html_e('Connect With Us', 'saab'); ?></h2>
                <p><?php esc_html_e('Follow us on social media for updates and behind-the-scenes content.', 'saab'); ?></p>
            </div>
            
            <div class="social-links">
                <?php if (function_exists('get_field') && get_field('facebook_url')) : ?>
                    <a href="<?php echo esc_url(get_field('facebook_url')); ?>" target="_blank" class="social-link facebook">
                        <i class="fab fa-facebook-f"></i>
                        <span><?php esc_html_e('Facebook', 'saab'); ?></span>
                    </a>
                <?php endif; ?>
                
                <?php if (function_exists('get_field') && get_field('twitter_url')) : ?>
                    <a href="<?php echo esc_url(get_field('twitter_url')); ?>" target="_blank" class="social-link twitter">
                        <i class="fab fa-twitter"></i>
                        <span><?php esc_html_e('Twitter', 'saab'); ?></span>
                    </a>
                <?php endif; ?>
                
                <?php if (function_exists('get_field') && get_field('instagram_url')) : ?>
                    <a href="<?php echo esc_url(get_field('instagram_url')); ?>" target="_blank" class="social-link instagram">
                        <i class="fab fa-instagram"></i>
                        <span><?php esc_html_e('Instagram', 'saab'); ?></span>
                    </a>
                <?php endif; ?>
                
                <?php if (function_exists('get_field') && get_field('youtube_url')) : ?>
                    <a href="<?php echo esc_url(get_field('youtube_url')); ?>" target="_blank" class="social-link youtube">
                        <i class="fab fa-youtube"></i>
                        <span><?php esc_html_e('YouTube', 'saab'); ?></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <?php if (function_exists('have_rows') && have_rows('faq_items')) : ?>
    <section class="faq-section">
        <div class="container">
            <div class="faq-header">
                <h2><?php esc_html_e('Frequently Asked Questions', 'saab'); ?></h2>
                <p><?php esc_html_e('Find quick answers to common questions.', 'saab'); ?></p>
            </div>
            
            <div class="faq-list">
                <?php while (have_rows('faq_items')) : the_row(); ?>
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3><?php echo esc_html(function_exists('get_sub_field') ? get_sub_field('question') : ''); ?></h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="faq-answer">
                            <p><?php echo esc_html(function_exists('get_sub_field') ? get_sub_field('answer') : ''); ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
</div>

<?php get_footer(); ?> 