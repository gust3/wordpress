<?php
function orion_add_scripts() {
    wp_enqueue_style(
        'bootstrap-css',
        get_template_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css',
        false,
        wp_get_theme()->get( 'Version' ),
        'all');
    wp_enqueue_script(
        'bootstrap-js',
        get_template_directory_uri() . '/assets/bootstrap/js/bootstrap.min.js',
        false,
        wp_get_theme()->get( 'Version' ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'orion_add_scripts' );
function orion_add_sidebars() {
    register_sidebar( array(
        'name'          => __( 'Main sidebar', 'theme_name' ),
        'id'            => 'main-area',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '',
        'after_title'   => '',
    ) );
}
add_action( 'widgets_init', 'orion_add_sidebars' );
