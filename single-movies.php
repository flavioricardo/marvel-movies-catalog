<?php get_header(); ?>

<div class="container mx-auto px-4 py-8">
    <?php if (have_posts()) : while (have_posts()) : the_post();
        $year = get_field('year');
        $imdb_rating = get_field('imdb_rating');
        $synopsis = get_field('synopsis');
        $cast = get_field('cast');
    ?>
        <div class="max-w-7xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="md:flex">
                <div class="md:w-1/3">
                    <?php 
                    if (has_post_thumbnail()) {
                        the_post_thumbnail('large', array(
                            'class' => 'w-full h-full object-cover'
                    ));
                    } else {
                        echo '<div class="h-full min-h-[400px] bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 dark:text-gray-400">
                                <span class="text-lg">No Image</span>
                            </div>';
                    }
                    ?>
                </div>
                
                <div class="md:w-2/3 p-6">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4"><?php the_title(); ?></h1>
                    
                    <div class="flex items-center space-x-6 mb-6">
                        <p class="text-gray-600 dark:text-gray-300">
                            <span class="font-semibold">Year:</span> 
                            <span class="ml-2"><?php echo esc_html($year); ?></span>
                        </p>
                        <p class="flex items-center text-gray-600 dark:text-gray-300">
                            <span class="font-semibold">IMDB:</span>
                            <span class="ml-2 flex items-center">
                                <span class="text-yellow-500 mr-1">⭐</span>
                                <?php echo number_format((float)$imdb_rating, 1); ?>
                            </span>
                        </p>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Synopsis</h2>
                            <div class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                <?php echo nl2br(esc_html($synopsis)); ?>
                            </div>
                        </div>
                        
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Cast</h2>
                            <div class="text-gray-600 dark:text-gray-300 leading-relaxed">
                                <?php echo nl2br(esc_html($cast)); ?>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <div class="flex items-center justify-center space-x-4 mb-4">
                            <button id="watchingBtn" class="flex items-center px-4 py-2 rounded-md border dark:border-gray-600 transition-colors duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Watching
                            </button>
                            
                            <button id="watchedBtn" class="flex items-center px-4 py-2 rounded-md border dark:border-gray-600 transition-colors duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Watched
                            </button>
                        </div>

                        <div class="flex items-center justify-center space-x-4">
                            <button id="likedBtn" class="flex items-center px-4 py-2 rounded-md border transition-colors duration-200 hover:bg-gray-100">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                </svg>
                                Like
                            </button>
                            
                            <button id="dislikedBtn" class="flex items-center px-4 py-2 rounded-md border transition-colors duration-200 hover:bg-gray-100">
                                <svg class="w-5 h-5 mr-2 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                </svg>
                                Dislike
                            </button>
                        </div>
                        
                        <div class="mt-8 text-center">
                            <a href="<?php echo esc_url(home_url()); ?>" 
                               class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                ← Back to all movies
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; endif; ?>
</div>

<?php get_footer(); ?>
