<?php get_header(); ?>

<div class="container mx-auto px-4 py-8">
    <form method="get" class="mb-8 flex justify-end">
        <select name="orderby" onchange="this.form.submit()" class="px-4 py-2 border rounded-lg shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
            <option value="title" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'title'); ?>>Sort by Title</option>
            <option value="year" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'year'); ?>>Sort by Year</option>
            <option value="rating" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'rating'); ?>>Sort by IMDB Rating</option>
        </select>
    </form>
    
    <?php
    $orderby = isset($_GET['orderby']) ? $_GET['orderby'] : 'title';
    
    $args = array(
        'post_type' => 'movies',
        'posts_per_page' => -1,
    );
    
    switch($orderby) {
        case 'year':
            $args['meta_key'] = 'year';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        case 'rating':
            $args['meta_key'] = 'imdb_rating';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        default:
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
    }
    
    $movies_query = new WP_Query($args);
    
    if ($movies_query->have_posts()) :
        ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <?php
        while ($movies_query->have_posts()) : $movies_query->the_post();
            $year = get_field('year');
            $imdb_rating = get_field('imdb_rating');
            $trailer = get_field('trailer');
            ?>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300">
                <a href="<?php the_permalink(); ?>" class="block">
                    <div class="relative pb-[150%]">
                        <?php 
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('medium', array(
                                'class' => 'absolute inset-0 w-full h-full object-cover rounded-t-lg'
                            ));
                        } else {
                            echo '<div class="absolute inset-0 bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400 rounded-t-lg">
                                    <span class="text-sm">No Image</span>
                                </div>';
                        }
                        ?>
                    </div>
                    <div class="relative p-4">
                        <div class="flex justify-between">
                            <h2 class="text-lg font-semibold mb-2 line-clamp-2 min-h-[3.5rem] text-gray-900 dark:text-white"><?php the_title(); ?></h2>
                            <?php if ($trailer) : ?>
                                <div class="relative group">
                                    <div class="cursor-default">🎬</div>
                                    <div class="absolute hidden group-hover:block bg-black text-white text-xs rounded py-1 px-2 right-0 -top-8 min-w-max">
                                        Trailer available!
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex justify-between items-center text-sm text-gray-600 dark:text-gray-300">
                            <p>Year: <?php echo esc_html($year); ?></p>
                            <p class="flex items-center">
                                <span class="text-yellow-500 mr-1">⭐</span>
                                <?php echo number_format((float)$imdb_rating, 1); ?>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            <?php
        endwhile;
        ?>
        </div>
        <?php
        wp_reset_postdata();
    else :
        echo '<p class="text-center text-gray-600 dark:text-gray-300">No movies found!</p>';
    endif;
    ?>
</div>

<?php get_footer(); ?>
