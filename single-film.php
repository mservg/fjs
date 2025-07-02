<?php
/**
 * Single Film Template
 */

get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<!-- Film Hero (Archive Style) -->
<div class="film-hero-archive-style">
    <div class="film-hero-bg">
        <?php if (has_post_thumbnail()) : ?>
            <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full')); ?>" alt="<?php the_title_attribute(); ?>" class="film-hero-bg-image" />
        <?php endif; ?>
        <div class="film-hero-overlay"></div>
    </div>
    <div class="film-hero-content">
        <div class="container">
            <div class="film-hero-text">
                <h1 class="film-hero-title"><?php the_title(); ?></h1>
                <?php $subtitle = get_post_meta(get_the_ID(), '_saab_film_subtitle', true); ?>
                <?php if ($subtitle) : ?>
                    <p class="film-hero-subtitle"><?php echo esc_html($subtitle); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Film Meta (below hero, or keep in sidebar) -->
<div class="section section-white">
    <div class="container">
        <?php if (function_exists('saab_breadcrumbs')) saab_breadcrumbs(); ?>
        <div class="grid grid-12" style="gap: 2rem;">
            <!-- Main Content -->
            <div style="grid-column: 1 / span 8;">
                <!-- Film Video -->
                <?php 
                $video_url = function_exists('saab_get_video_url') ? saab_get_video_url(get_the_ID()) : '';
                if ($video_url) : ?>
                    <?php if (function_exists('saab_display_film_video')) saab_display_film_video(get_the_ID()); ?>
                <?php endif; ?>

                <!-- Synopsis -->
                <?php 
                $synopsis = get_post_meta(get_the_ID(), '_saab_film_synopsis', true);
                if ($synopsis) : ?>
                    <div class="film-synopsis">
                        <h3 class="section-title-centered">
                            <?php esc_html_e('Synopsis', 'saab'); ?>
                        </h3>
                        <div class="synopsis-content">
                            <?php echo wp_kses_post($synopsis); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Main Content -->
                <div class="film-content">
                    <?php the_content(); ?>
                </div>

                <!-- Credits -->
                <?php 
                $credits = get_post_meta(get_the_ID(), '_saab_film_credits', true);
                if ($credits && is_array($credits)) : ?>
                    <div class="film-credits">
                        <h3 class="section-title-centered">
                            <?php esc_html_e('Credits', 'saab'); ?>
                        </h3>
                        <div class="credits-list">
                            <?php foreach ($credits as $credit) : ?>
                                <div class="credit-item">
                                    <strong><?php echo esc_html($credit['role']); ?>:</strong>
                                    <span><?php echo esc_html($credit['name']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="film-sidebar">
                <!-- Film Details -->
                <div class="sidebar-section">
                    <h4><?php esc_html_e('Film Details', 'saab'); ?></h4>
                    <div class="film-meta">
                        <?php $year = get_post_meta(get_the_ID(), '_saab_film_year', true); ?>
                        <?php if ($year) : ?>
                            <div class="meta-item">
                                <strong><?php esc_html_e('Year:', 'saab'); ?></strong>
                                <span><?php echo esc_html($year); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php $duration = get_post_meta(get_the_ID(), '_saab_film_duration', true); ?>
                        <?php if ($duration) : ?>
                            <div class="meta-item">
                                <strong><?php esc_html_e('Duration:', 'saab'); ?></strong>
                                <span><?php echo esc_html($duration); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php $format = get_post_meta(get_the_ID(), '_saab_film_format', true); ?>
                        <?php if ($format) : ?>
                            <div class="meta-item">
                                <strong><?php esc_html_e('Format:', 'saab'); ?></strong>
                                <span><?php echo esc_html($format); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php $director = get_post_meta(get_the_ID(), '_saab_film_director', true); ?>
                        <?php $dop = get_post_meta(get_the_ID(), '_saab_film_dop', true); ?>
                        <?php $editor = get_post_meta(get_the_ID(), '_saab_film_editor', true); ?>
                        <?php if ($director) : ?>
                            <div class="meta-item">
                                <strong><?php esc_html_e('Director:', 'saab'); ?></strong>
                                <span><?php echo esc_html($director); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($dop) : ?>
                            <div class="meta-item">
                                <strong><?php esc_html_e('DOP:', 'saab'); ?></strong>
                                <span><?php echo esc_html($dop); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($editor) : ?>
                            <div class="meta-item">
                                <strong><?php esc_html_e('Editor:', 'saab'); ?></strong>
                                <span><?php echo esc_html($editor); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Genres -->
                <?php 
                $genres = get_the_terms(get_the_ID(), 'genre');
                if ($genres && !is_wp_error($genres)) : ?>
                    <div class="sidebar-section">
                        <h4><?php esc_html_e('Genres', 'saab'); ?></h4>
                        <ul class="term-list">
                            <?php foreach ($genres as $genre) : ?>
                                <li>
                                    <a href="<?php echo esc_url(get_term_link($genre)); ?>">
                                        <?php echo esc_html($genre->name); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <!-- Languages -->
                <?php 
                $languages = get_the_terms(get_the_ID(), 'film_language');
                if ($languages && !is_wp_error($languages)) : ?>
                    <div class="sidebar-section">
                        <h4><?php esc_html_e('Languages', 'saab'); ?></h4>
                        <ul class="term-list">
                            <?php foreach ($languages as $language) : ?>
                                <li>
                                    <a href="<?php echo esc_url(get_term_link($language)); ?>">
                                        <?php echo esc_html($language->name); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Film Stills Gallery at the end of the page -->
<div class="section section-white" style="padding-top: 2rem; padding-bottom: 2rem;">
    <div class="container">
        <h2 class="section-title-enhanced" style="text-align:center; color: var(--brand-yellow); font-family: 'Epilogue', sans-serif; font-weight: 700; margin-bottom: 2rem;">
            <?php esc_html_e('Gallery', 'saab'); ?>
        </h2>
        <?php if (function_exists('saab_film_stills_gallery')) saab_film_stills_gallery(get_the_ID()); ?>
    </div>
</div>

<?php endwhile; ?>

<!-- Add CSS for the new hero style -->
<style>
.film-hero-archive-style {
    position: relative;
    min-height: 50vh;
    display: flex;
    align-items: center;
    background: var(--brand-dark);
    overflow: hidden;
}
.film-hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}
.film-hero-bg-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.film-hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(17, 24, 39, 0.8) 0%, rgba(30, 58, 138, 0.6) 100%);
    z-index: 2;
}
.film-hero-content {
    position: relative;
    z-index: 3;
    width: 100%;
    color: var(--brand-white);
}
.film-hero-text {
    max-width: 800px;
    text-align: center;
    margin: 0 auto;
}
.film-hero-title {
    font-size: clamp(2.5rem, 6vw, 4rem);
    color: var(--brand-white);
    margin-bottom: var(--spacing-sm);
}
.film-hero-subtitle {
    font-size: clamp(1.125rem, 3vw, 1.5rem);
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.5;
}
@media (max-width: 767px) {
    .film-hero-archive-style {
        min-height: 40vh;
    }
    .film-hero-text {
        padding: 0 1rem;
    }
}
</style>

<?php get_footer(); ?>