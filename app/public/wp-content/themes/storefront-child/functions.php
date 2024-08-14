<?php

/**
 * Enqueue the parent theme's stylesheet
 */
function storefront_child_enqueue_styles()
{
    wp_enqueue_style('storefront-parent-style', get_template_directory_uri() . '/style.css');
}
add_action('wp_enqueue_scripts', 'storefront_child_enqueue_styles');

/**
 * enqueue admin ajax
 */
function enqueue_custom_scripts() {
    wp_enqueue_script('ajax-search', get_stylesheet_directory_uri() . '/js/ajax-search.js', array('jquery'), null, true);
    wp_localize_script('ajax-search', 'ajaxobject', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');




/**
 * Register Custom Post Type 'Cities'
 */
function crete_post_type()
{
    $labels = array(
        'name'                  => _x('Cities', 'Post Type General Name', 'textdomain'),
        'singular_name'         => _x('City', 'Post Type Singular Name', 'textdomain'),
        'menu_name'             => __('Cities', 'textdomain'),
        'name_admin_bar'        => __('City', 'textdomain'),
        'add_new'               => __('Add New', 'textdomain'),
        'add_new_item'          => __('Add New City', 'textdomain'),
        'new_item'              => __('New City', 'textdomain'),
        'edit_item'             => __('Edit City', 'textdomain'),
        'view_item'             => __('View City', 'textdomain'),
        'all_items'             => __('All Cities', 'textdomain'),
        'search_items'          => __('Search Cities', 'textdomain'),
        'not_found'             => __('No cities found', 'textdomain'),
        'not_found_in_trash'    => __('No cities found in Trash', 'textdomain'),
    );

    $args = array(
        'label'                 => __('City', 'textdomain'),
        'description'           => __('Post type for cities', 'textdomain'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'thumbnail'),
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'has_archive'           => true,
        'rewrite'               => array('slug' => 'cities'),
        'capability_type'       => 'post',
    );

    register_post_type('cities', $args);
}
add_action('init', 'crete_post_type', 0);


/**
 * Register Custom Taxonomy 'Countries'
 */
function create_custom_taxonomy()
{
    $labels = array(
        'name'                       => _x('Countries', 'taxonomy general name', 'textdomain'),
        'singular_name'              => _x('Country', 'taxonomy singular name', 'textdomain'),
        'menu_name'                  => __('Countries', 'textdomain'),
        'all_items'                  => __('All Countries', 'textdomain'),
        'edit_item'                  => __('Edit Country', 'textdomain'),
        'view_item'                  => __('View Country', 'textdomain'),
        'update_item'                => __('Update Country', 'textdomain'),
        'add_new_item'               => __('Add New Country', 'textdomain'),
        'new_item_name'              => __('New Country Name', 'textdomain'),
        'search_items'               => __('Search Countries', 'textdomain'),
        'not_found'                  => __('No countries found', 'textdomain'),
    );
    $args = array(
        'hierarchical'          => true,
        'labels'                => $labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'query_var'             => true,
        'rewrite'               => array('slug' => 'country'),
    );
    register_taxonomy('countries', array('cities'), $args);
}
add_action('init', 'create_custom_taxonomy', 0);


/**
 * Add Meta Box
 */
function add_city_meta_box() {
    add_meta_box(
        'city_meta_box',
        __('City Details', 'text_domain'),
        'render_city_meta_box',
        'cities', // Post type
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_city_meta_box');

function render_city_meta_box($post) {
    // Add a nonce field for security
    wp_nonce_field('save_city_meta_box_data', 'city_meta_box_nonce');

    // Retrieve current values
    $latitude = get_post_meta($post->ID, '_latitude', true);
    $longitude = get_post_meta($post->ID, '_longitude', true);

    ?>
    <p>
        <label for="city_latitude"><?php _e('Latitude:', 'text_domain'); ?></label>
        <input type="text" id="city_latitude" name="city_latitude" value="<?php echo esc_attr($latitude); ?>" />
    </p>
    <p>
        <label for="city_longitude"><?php _e('Longitude:', 'text_domain'); ?></label>
        <input type="text" id="city_longitude" name="city_longitude" value="<?php echo esc_attr($longitude); ?>" />
    </p>
    <?php
}

/**
 * Save Meta Box Data
 */
function save_city_meta_box_data($post_id) {
    // Check if nonce is set and verify it
    if (!isset($_POST['city_meta_box_nonce'])) {
        return;
    }
    if (!wp_verify_nonce($_POST['city_meta_box_nonce'], 'save_city_meta_box_data')) {
        return;
    }

    // Check if the user has permission to edit the post
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Check if our field is set
    if (isset($_POST['city_latitude'])) {
        $latitude = sanitize_text_field($_POST['city_latitude']);
        update_post_meta($post_id, '_latitude', $latitude);
    }

    if (isset($_POST['city_longitude'])) {
        $longitude = sanitize_text_field($_POST['city_longitude']);
        update_post_meta($post_id, '_longitude', $longitude);
    }
}
add_action('save_post', 'save_city_meta_box_data');




/**
 * Register and load the widget
 */
class City_Temperature_Widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'city_temperature_widget',
            __('City Temperature Widget', 'text_domain'),
            array('description' => __('Displays the selected city and its temperature', 'text_domain'))
        );
    }

    public function widget($args, $instance) {
        $city_id = !empty($instance['city_id']) ? $instance['city_id'] : '';

        if ($city_id) {
            // Get the city post by ID
            $city = get_post($city_id);
            if ($city) {
                $latitude = get_post_meta($city_id, '_latitude', true);
                $longitude = get_post_meta($city_id, '_longitude', true);
                $temperature = $this->get_city_temperature($latitude, $longitude);

                echo $args['before_widget'];
                echo $args['before_title'] . $city->post_title . $args['after_title'];
                echo '<p>Temperature: ' . esc_html($temperature) . ' °C</p>';
                echo $args['after_widget'];
            } else {
                echo $args['before_widget'];
                echo $args['before_title'] . __('City Not Found', 'text_domain') . $args['after_title'];
                echo '<p>' . __('No data available for this city.', 'text_domain') . '</p>';
                echo $args['after_widget'];
            }
        }
    }

    public function form($instance) {
        $city_id = !empty($instance['city_id']) ? $instance['city_id'] : '';
        $cities = get_posts(array(
            'post_type' => 'cities',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        ));

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('city_id'); ?>"><?php _e('Select City:'); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('city_id'); ?>" name="<?php echo $this->get_field_name('city_id'); ?>">
                <option value=""><?php _e('Select a city'); ?></option>
                <?php foreach ($cities as $city) : ?>
                    <option value="<?php echo $city->ID; ?>" <?php selected($city_id, $city->ID); ?>>
                        <?php echo esc_html($city->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['city_id'] = (!empty($new_instance['city_id'])) ? sanitize_text_field($new_instance['city_id']) : '';
        return $instance;
    }

    private function get_city_temperature($latitude, $longitude) {
        $api_key = 'f0a816c9362d62d0090d959a1db3f8ab';
        $response = wp_remote_get("http://api.openweathermap.org/data/2.5/weather?lat={$latitude}&lon={$longitude}&units=metric&appid={$api_key}");

        if (is_wp_error($response)) {
            return 'N/A';
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);
        return isset($data['main']['temp']) ? $data['main']['temp'] : 'N/A';
    }
}

function register_city_temperature_widget() {
    register_widget('City_Temperature_Widget');
}
add_action('widgets_init', 'register_city_temperature_widget');


/**
 * display city country and temperature
 */
function display_countries_cities_table() {
    global $wpdb;

    $query = "
        SELECT 
            countries.name AS country_name,
            cities.post_title AS city_name,
            COALESCE(pm.meta_value, 'N/A') AS temperature
        FROM 
            {$wpdb->prefix}posts AS cities
        INNER JOIN 
            {$wpdb->prefix}term_relationships AS tr ON cities.ID = tr.object_id
        INNER JOIN 
            {$wpdb->prefix}term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        INNER JOIN 
            {$wpdb->prefix}terms AS countries ON tt.term_id = countries.term_id
        LEFT JOIN 
            {$wpdb->prefix}postmeta AS pm ON cities.ID = pm.post_id AND pm.meta_key = '_temperature'
        WHERE 
            cities.post_type = 'cities'
            AND tt.taxonomy = 'countries'
        ORDER BY 
            countries.name, cities.post_title
    ";

    $results = $wpdb->get_results($query);

    if ($results) {
        echo '<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Country</th>
                        <th>City</th>
                        <th>Temperature (°C)</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($results as $row) {
            echo '<tr>
                    <td>' . esc_html($row->country_name) . '</td>
                    <td>' . esc_html($row->city_name) . '</td>
                    <td>' . esc_html($row->temperature) . ' °C</td>
                </tr>';
        }

        echo '</tbody></table>';
    } else {
        echo '<p>No data found.</p>';
    }
}


add_action('wp_ajax_search_cities', 'handle_city_search');
add_action('wp_ajax_nopriv_search_cities', 'handle_city_search');
/**
 * Handles search for cities
 */
function handle_city_search() {
    global $wpdb;

    $search_query = sanitize_text_field($_POST['query']);
    $results = $wpdb->get_results($wpdb->prepare("
        SELECT 
            c.name AS country_name,
            ci.post_title AS city_name,
            COALESCE(pm.meta_value, 'N/A') AS temperature
        FROM 
            {$wpdb->prefix}posts AS ci
        INNER JOIN 
            {$wpdb->prefix}term_relationships AS tr ON ci.ID = tr.object_id
        INNER JOIN 
            {$wpdb->prefix}term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
        INNER JOIN 
            {$wpdb->prefix}terms AS c ON tt.term_id = c.term_id
        LEFT JOIN 
            {$wpdb->prefix}postmeta AS pm ON ci.ID = pm.post_id AND pm.meta_key = '_temperature'
        WHERE 
            ci.post_type = 'cities'
            AND tt.taxonomy = 'countries'
            AND ci.post_title LIKE %s
        ORDER BY 
            c.name, ci.post_title
        LIMIT 10
    ", '%' . $wpdb->esc_like($search_query) . '%'));

    if ($results) {
        echo '<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Country</th>
                        <th>City</th>
                        <th>Temperature (°C)</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($results as $row) {
            echo '<tr>
                    <td>' . esc_html($row->country_name) . '</td>
                    <td>' . esc_html($row->city_name) . '</td>
                    <td>' . esc_html($row->temperature) . ' °C</td>
                </tr>';
        }

        echo '</tbody></table>';
    } else {
        echo '<p>No results found.</p>';
    }

    wp_die();
}

// Custom action hooks
function custom_before_countries_cities_table() {
    echo '<p>Custom content or action before the table.</p>';
}
add_action('before_countries_cities_table', 'custom_before_countries_cities_table');

function custom_after_countries_cities_table() {
    echo '<p>Custom content or action after the table.</p>';
}
add_action('after_countries_cities_table', 'custom_after_countries_cities_table');

function update_city_temperature($post_id) {
    if (get_post_type($post_id) !== 'cities') {
        return;
    }


    $latitude = get_post_meta($post_id, '_latitude', true);
    $longitude = get_post_meta($post_id, '_longitude', true);



    if ($latitude && $longitude) {
        $api_key = 'f0a816c9362d62d0090d959a1db3f8ab'; 
        $response = wp_remote_get("http://api.openweathermap.org/data/2.5/weather?lat={$latitude}&lon={$longitude}&units=metric&appid={$api_key}");

        echo "<pre>";
        
        if (!is_wp_error($response)) {
            $data = json_decode(wp_remote_retrieve_body($response), true);
            $temperature = isset($data['main']['temp']) ? $data['main']['temp'] : 'N/A';
            update_post_meta($post_id, '_temperature', $temperature);
        }
    }
}

// Hook this function to save or update actions for 'cities' post type
add_action('save_post_cities', 'update_city_temperature');