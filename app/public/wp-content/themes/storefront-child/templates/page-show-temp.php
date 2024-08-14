<?php
/* Template Name: Countries and Cities Table */

get_header(); ?>

<div id="content">
    <?php
    // Action hook before the table
    do_action('before_countries_cities_table');
    ?>

    <h2><?php _e('Countries and Cities Table', 'text_domain'); ?></h2>

    <!-- Search Form -->
    <form id="search-form" action="" method="get">
        <label for="search-input"><?php _e('Search Cities:', 'text_domain'); ?></label>
        <input type="text" id="search-input" name="query" placeholder="<?php _e('Enter city name...', 'text_domain'); ?>" />
        <input type="submit" value="<?php _e('Search', 'text_domain'); ?>" />
    </form>

    <!-- Table Container -->
    <div id="countries-cities-table">
        <?php display_countries_cities_table(); ?>
    </div>

    <?php
    // Action hook after the table
    do_action('after_countries_cities_table');
    ?>
</div>

<?php get_footer(); ?>