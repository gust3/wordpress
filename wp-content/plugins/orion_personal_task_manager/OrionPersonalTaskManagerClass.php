<?php

class OrionPersonalTaskManagerClass
{
    public static function pluginActivation(){
        //обработка события активации плагина
        self::createDataBaseTableIfNedeed();
    }
    public static function pluginDeactivation(){
        //обработка события деактивации плагина
    }
    public static function createDataBaseTableIfNedeed(){
        //создание таблицы базы данных
        global $wpdb;
        $table_name = $wpdb->prefix . "optm_tasks";
        if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name){
            //создаем таблицу для хранения если есть необходимость, если есть уже до этого не создаем
            $sql = "CREATE TABLE " . $table_name . " (
                  id mediumint(9) NOT NULL AUTO_INCREMENT,
                  date bigint(11) DEFAULT '0' NOT NULL,
                  name tinytext NOT NULL,
                  description text NOT NULL,
                  state mediumint(9) NOT NULL,
                  user_id bigint(11) NOT NULL,
                  UNIQUE KEY id (id)
            );";
            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);
        }
    }
}