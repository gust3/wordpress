<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://cwteam.ru/
 *
 * @package WordPress
 * @subpackage Oriontheme
 * @since Oriontheme 1.0
 */
if ( !is_user_logged_in() ) {
    wp_redirect( get_home_url() . '/wp-login.php?redirect_to=' . get_home_url() );
}
get_header(); ?>

    <h1 class="text-center h3">Менеджер задач</h1>
    <?php get_sidebar( 'main-area' ); ?>

<?php
get_footer();
