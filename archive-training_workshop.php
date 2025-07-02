<?php
/**
 * Archive template for Training Workshops
 */

get_header(); ?>

<div class="hero archive-hero">
    <div class="hero-background" style="background-image: url('<?php echo esc_url(get_template_directory_uri() . '/assets/images/workshop-hero.jpg'); ?>');"></div>
    <div class="hero-content">
        <h1 class="hero-title"><?php esc_html_e('Trainings & Workshops', 'saab'); ?></h1>
        <p class="hero-subtitle"><?php esc_html_e('Educational programs and professional development opportunities', 'saab'); ?></p>
    </div>
</div>

<div class="section section-white">
    <div class="container">
        <?php saab_breadcrumbs(); ?>
        
        <!-- Workshop Filters -->
        <div class="workshop-filters">
            <h3 class="section-title-centered" style="color: var(--brand-yellow);">
                <?php esc_html_e('Filter by Type', 'saab'); ?>
            </h3>
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">
                    <?php esc_html_e('All', 'saab'); ?>
                </button>
                <button class="filter-btn" data-filter="training">
                    <?php esc_html_e('Training', 'saab'); ?>
                </button>
                <button class="filter-btn" data-filter="workshop">
                    <?php esc_html_e('Workshop', 'saab'); ?>
                </button>
            </div>
        </div>

        <!-- Workshops Grid -->
        <div class="workshops-grid" id="workshops-grid">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php get_template_part('template-parts/workshop-card'); ?>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="no-workshops">
                    <p><?php esc_html_e('No workshops found.', 'saab'); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if (function_exists('wp_pagenavi')) : ?>
            <?php wp_pagenavi(); ?>
        <?php else : ?>
            <div class="pagination-wrapper">
                <?php
                echo paginate_links(array(
                    'prev_text' => '&laquo; ' . __('Previous', 'saab'),
                    'next_text' => __('Next', 'saab') . ' &raquo;',
                    'type' => 'list',
                ));
                ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Upcoming Workshops Section -->
<div class="section section-cream">
    <div class="container">
        <h2 class="section-title-centered">
            <?php esc_html_e('Upcoming Workshops', 'saab'); ?>
        </h2>
        <div class="upcoming-workshops">
            <?php
            $upcoming_workshops = new WP_Query(array(
                'post_type' => 'training_workshop',
                'posts_per_page' => 3,
                'meta_query' => array(
                    array(
                        'key' => '_saab_training_workshop_date',
                        'value' => date('Y-m-d'),
                        'compare' => '>=',
                        'type' => 'DATE'
                    )
                ),
                'orderby' => 'meta_value',
                'meta_key' => '_saab_training_workshop_date',
                'order' => 'ASC'
            ));
            
            if ($upcoming_workshops->have_posts()) :
                while ($upcoming_workshops->have_posts()) : $upcoming_workshops->the_post();
                    $date = get_post_meta(get_the_ID(), '_saab_training_workshop_date', true);
                    $location = get_post_meta(get_the_ID(), '_saab_training_workshop_location', true);
                    ?>
                    <div class="upcoming-workshop-card">
                        <h3>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        <?php if ($date) : ?>
                            <p class="workshop-date">
                                <?php echo esc_html($date); ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($location) : ?>
                            <p class="workshop-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo esc_html($location); ?>
                            </p>
                        <?php endif; ?>
                        <a href="<?php the_permalink(); ?>" class="btn-enhanced btn-enhanced-outline btn-enhanced-sm">
                            <?php esc_html_e('Learn More', 'saab'); ?>
                        </a>
                    </div>
                <?php endwhile;
                wp_reset_postdata();
            else : ?>
                <div class="no-upcoming-workshops">
                    <p><?php esc_html_e('No upcoming workshops at the moment.', 'saab'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const workshopCards = document.querySelectorAll('.workshop-card');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Filter workshops
            workshopCards.forEach(card => {
                const type = card.getAttribute('data-type') || 'all';
                if (filter === 'all' || type === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});
</script>

<?php get_footer(); ?> 