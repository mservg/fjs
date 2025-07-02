<?php
/**
 * Template part for displaying a film card
 *
 * @package Saab
 */

// Get film meta data
$film_year = get_post_meta(get_the_ID(), '_saab_film_year', true);
$film_country = get_post_meta(get_the_ID(), '_saab_film_country', true);
$film_duration = get_post_meta(get_the_ID(), '_saab_film_duration', true);
$film_genre = get_post_meta(get_the_ID(), '_saab_film_genre', true);
$film_director = get_post_meta(get_the_ID(), '_saab_film_director', true);
?>

<article class="film-card awards-style-card" id="film-<?php the_ID(); ?>">
    <div class="awards-card-image">
        <a href="<?php the_permalink(); ?>" aria-label="<?php printf(esc_attr__('Read more about %s', 'saab'), get_the_title()); ?>">
            <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('large', [
                    'loading' => 'lazy',
                    'alt' => get_the_title(),
                    'class' => 'film-poster-img'
                ]); ?>
            <?php else : ?>
                <div class="film-poster-placeholder" aria-hidden="true">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 4H20V16H4V4Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M4 20H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 9L15 12L9 15V9Z" fill="currentColor"/>
                    </svg>
                    <span><?php echo esc_html(get_the_title()); ?></span>
                </div>
            <?php endif; ?>
        </a>
        <?php if ($film_year) : ?>
            <div class="film-year-badge" aria-label="<?php printf(esc_attr__('Released in %s', 'saab'), $film_year); ?>">
                <?php echo esc_html($film_year); ?>
            </div>
        <?php endif; ?>
    </div>
    <div class="awards-card-content">
        <h3 class="awards-card-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        <div class="awards-card-excerpt">
            <?php echo wp_trim_words(get_the_excerpt(), 18); ?>
        </div>
    </div>
</article>