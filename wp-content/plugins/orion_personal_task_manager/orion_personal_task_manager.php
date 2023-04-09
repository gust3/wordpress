<?php
/**
 * @package Orion_personal_task_manager
 */

/*
Plugin Name: Orion personal task manager
Plugin URI: https://cwteam.ru/
Description: Test code plugin
Version: 1.01
Requires at least: 5.0
Requires PHP: 5.2
Author: Gust
Author URI: https://cwteam.ru/gust/
License: GPLv2 or later
*/


define( 'OPTM_VERSION', '1.01' );
define( 'PLUGIN_NAME', 'orion_personal_task_manager' );
define( 'OPTM__MINIMUM_WP_VERSION', '5.0' );
define( 'OPTM__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

//подключаем класс плагина
require_once( OPTM__PLUGIN_DIR . 'OrionPersonalTaskManagerClass.php' );

//навешиваем события активации и деактцивации на методы класса
register_activation_hook( __FILE__, array( 'OrionPersonalTaskManagerClass', 'pluginActivation' ) );
register_deactivation_hook( __FILE__, array( 'OrionPersonalTaskManagerClass', 'pluginDeactivation' ) );

//подключаем класс виджета
require_once( OPTM__PLUGIN_DIR . 'OrionPersonalTaskManagerClassWidget.php' );

if( !is_admin() ) {

//регистрируем скрипт
    function optm_add_scripts()
    {
        wp_enqueue_script(
            'optm-js',
            '/wp-content/plugins/' . PLUGIN_NAME . '/assets/js/optm.js',
            false,
            wp_get_theme()->get('Version'),
            true
        );
    }

    add_action('wp_enqueue_scripts', 'optm_add_scripts');
//регистрируем аякс добавление

}

if( wp_doing_ajax() ) {
    //подключаем только при аякс запросе
    add_action('wp_ajax_optm_insert_new_task', 'optm_insert_new_task');
    add_action('wp_ajax_nopriv_optm_insert_new_task', 'optm_insert_new_task');
    function optm_insert_new_task(){
        $object = new OrionPersonalTaskManagerClassWidget;
        echo $object->insertTask($_POST);
        wp_die();
    }

    add_action('wp_ajax_optm_delete_task', 'optm_delete_task');
    add_action('wp_ajax_nopriv_optm_delete_task', 'optm_delete_task');
    //аякс удаление
    function optm_delete_task(){
        $object = new OrionPersonalTaskManagerClassWidget;
        echo $object->deleteTaskOptm($_POST);
        wp_die();
    }
    //изменение статуса
    add_action('wp_ajax_optm_state_to_task', 'optm_state_to_task');
    add_action('wp_ajax_nopriv_optm_state_to_task', 'optm_state_to_task');
    //аякс статутс 0
    function optm_state_to_task(){
        $object = new OrionPersonalTaskManagerClassWidget;
        $id = $object->saveInsert('id');
        $state = $object->saveInsert('state');
        echo $object->setStateOptm($id, $state);
        wp_die();
    }
    //вызов формы для правки задачи
    add_action('wp_ajax_optm_update_form', 'optm_update_form');
    add_action('wp_ajax_nopriv_optm_update_form', 'optm_update_form');
    //аякс статутс 0
    function optm_update_form(){
        $object = new OrionPersonalTaskManagerClassWidget;
        $id = $object->saveInsert('id');
        echo $object->getForm($id);
        wp_die();
    }

    //изменение формы
    add_action('wp_ajax_optm_update_new_task', 'optm_update_new_task');
    add_action('wp_ajax_nopriv_optm_update_new_task', 'optm_update_new_task');
    //аякс статутс 0
    function optm_update_new_task(){
        $object = new OrionPersonalTaskManagerClassWidget;
        echo $object->saveForm($_POST);
        wp_die();
    }

}