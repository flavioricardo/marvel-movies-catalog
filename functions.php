<?php
/**
 * Marvel Movies Catalog functions and definitions
 */

 // Add Tailwind CSS via CDN
function add_tailwindcss() {
    // Load Tailwind before configuration script
    wp_enqueue_script('tailwindcss', 'https://cdn.tailwindcss.com', array(), null, false);
    error_log('Tailwind CSS loaded via CDN');

    // Add configuration after Tailwind loads
    wp_add_inline_script('tailwindcss', '
        console.log("Tailwind configuration initialized");
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    aspectRatio: {
                        "2/3": "2 / 3",
                    },
                },
            },
            plugins: [
                function({ addUtilities }) {
                    addUtilities({
                        ".line-clamp-2": {
                            display: "-webkit-box",
                            "-webkit-line-clamp": "2",
                            "-webkit-box-orient": "vertical",
                            overflow: "hidden",
                        },
                    });
                },
            ],
        };
    ');
}

add_action( 'wp_enqueue_scripts', 'add_tailwindcss' );

// Add Theme Manager
function add_theme_manager() {
    wp_enqueue_script('theme-manager', get_template_directory_uri() . '/scripts/themeManager.js', array(), null, false);
    
    wp_add_inline_script('theme-manager', '
        document.addEventListener("DOMContentLoaded", function() {
            new ThemeManager();
        });
    ', 'after');
}

add_action('wp_enqueue_scripts', 'add_theme_manager');

// Register and enqueue theme assets
function register_movie_scripts() {
    if (!is_singular('movies')) {
        return;
    }

    $version = time();
    
    // Register scripts
    wp_register_script(
        'movie-state-manager',
        get_template_directory_uri() . '/scripts/movieStateManager.js',
        array(),
        $version,
        false
    );

    wp_register_script(
        'movie-ui',
        get_template_directory_uri() . '/scripts/movieUI.js',
        array('movie-state-manager'),
        $version,
        false
    );

    // Enqueue scripts
    wp_enqueue_script('movie-state-manager');
    wp_enqueue_script('movie-ui');

    // Add movie data
    wp_localize_script('movie-state-manager', 'movieData', array(
        'movieId' => get_the_ID(),
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'debug' => true
    ));

    // Add initialization script
    wp_add_inline_script('movie-ui', '
        document.addEventListener("DOMContentLoaded", function() {
            console.log("Starting movie scripts...");
            try {
                const stateManager = new MovieStateManager(movieData.movieId);
                console.log("StateManager created:", stateManager);
                
                const movieUI = new MovieUI(stateManager);
                console.log("MovieUI created:", movieUI);
            } catch (error) {
                console.error("Initialization error:", error);
            }
        });
    ', 'after');
}

add_action('wp_enqueue_scripts', 'register_movie_scripts');

// Register Custom Post Type for Movies
function marvel_movies_register_post_type() {
    $labels = array(
        'name'               => _x( 'Movies', 'post type general name', 'marvel-movies-catalog' ),
        'singular_name'      => _x( 'Movie', 'post type singular name', 'marvel-movies-catalog' ),
        'menu_name'          => _x( 'Movies', 'admin menu', 'marvel-movies-catalog' ),
        'name_admin_bar'     => _x( 'Movie', 'add new on admin bar', 'marvel-movies-catalog' ),
        'add_new'            => _x( 'Add New', 'movie', 'marvel-movies-catalog' ),
        'add_new_item'       => __( 'Add New Movie', 'marvel-movies-catalog' ),
        'new_item'           => __( 'New Movie', 'marvel-movies-catalog' ),
        'edit_item'          => __( 'Edit Movie', 'marvel-movies-catalog' ),
        'view_item'          => __( 'View Movie', 'marvel-movies-catalog' ),
        'all_items'          => __( 'All Movies', 'marvel-movies-catalog' ),
        'search_items'       => __( 'Search Movies', 'marvel-movies-catalog' ),
        'parent_item_colon'  => __( 'Parent Movies:', 'marvel-movies-catalog' ),
        'not_found'          => __( 'No movies found.', 'marvel-movies-catalog' ),
        'not_found_in_trash' => __( 'No movies found in Trash.', 'marvel-movies-catalog' )
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __( 'Marvel Movies', 'marvel-movies-catalog' ),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'movies' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'menu_icon'          => 'dashicons-video-alt2',
        'show_in_rest'       => true,
    );

    register_post_type( 'movies', $args );
}

add_action( 'init', 'marvel_movies_register_post_type' );

/**
 * Configure ACF fields for movie data
 */
function marvel_movies_acf_fields() {
    if( function_exists('acf_add_local_field_group') ) {
        acf_add_local_field_group(array(
            'key' => 'group_marvel_movie_details',
            'title' => 'Movie Details',
            'fields' => array(
                array(
                    'key' => 'field_year',
                    'label' => 'Year',
                    'name' => 'year',
                    'type' => 'number',
                    'instructions' => 'Enter the release year of the movie',
                    'required' => 1,
                    'min' => 1900,
                    'max' => 2100,
                ),
                array(
                    'key' => 'field_imdb_rating',
                    'label' => 'IMDB Rating',
                    'name' => 'imdb_rating',
                    'type' => 'number',
                    'instructions' => 'Enter the IMDB rating (0-10)',
                    'required' => 1,
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.1,
                ),
                array(
                    'key' => 'field_synopsis',
                    'label' => 'Synopsis',
                    'name' => 'synopsis',
                    'type' => 'textarea',
                    'instructions' => 'Enter the full movie synopsis',
                    'required' => 1,
                ),
                array(
                    'key' => 'field_cast',
                    'label' => 'Cast',
                    'name' => 'cast',
                    'type' => 'textarea',
                    'instructions' => 'Enter the main cast members',
                    'required' => 1,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'movies',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
        ));
    }
}

add_action('acf/init', 'marvel_movies_acf_fields');

/**
 * Create REST API endpoint for random movie
 * Endpoint: wp-json/marvel-movies/v1/random
 */
function marvel_movies_register_rest_routes() {
    register_rest_route('marvel-movies/v1', '/random', array(
        'methods' => 'GET',
        'callback' => 'marvel_movies_get_random',
        'permission_callback' => '__return_true'
    ));
}

add_action('rest_api_init', 'marvel_movies_register_rest_routes');

/**
 * Get random movie data
 */
function marvel_movies_get_random() {
    $args = array(
        'post_type' => 'movies',
        'posts_per_page' => 1,
        'orderby' => 'rand'
    );
    
    $random_movie = get_posts($args);
    
    if (empty($random_movie)) {
        return new WP_Error('no_movies', 'No movies found', array('status' => 404));
    }
    
    $movie = $random_movie[0];
    $movie_id = $movie->ID;
    
    $response = array(
        'id' => $movie_id,
        'title' => get_the_title($movie_id),
        'year' => get_field('year', $movie_id),
        'imdb_rating' => get_field('imdb_rating', $movie_id),
        'synopsis' => get_field('synopsis', $movie_id),
        'cast' => get_field('cast', $movie_id),
        'featured_image' => get_the_post_thumbnail_url($movie_id, 'full'),
        'permalink' => get_permalink($movie_id)
    );
    
    return $response;
}

/**
 * Theme setup function
 * Adds necessary theme support features
 */
function marvel_movies_theme_setup() {
    // Add featured image support
    add_theme_support('post-thumbnails');
    
    // Add title tag support
    add_theme_support('title-tag');
}

add_action('after_setup_theme', 'marvel_movies_theme_setup');
