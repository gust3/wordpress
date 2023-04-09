<?php

class OrionPersonalTaskManagerClassWidget extends WP_Widget
{
    public $wpdb;
    public $table_name;
    public $user_id;
    public $result;
    public $cachegroup = "optm_widget";

    public function __construct() {
        global $wpdb;

        $this->user_id = get_current_user_id();
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . "optm_tasks";

        // actual widget processes
        parent::__construct(
            'OPTC_widget',
            __( 'OPTC Widget' , 'OPTC'),
            array( 'description' => __( 'OPTC' , 'OPTC') )
        );
    }

    public function form( $instance ) {
        // outputs the options form in the admin
    }

    public function update( $new_instance, $old_instance ) {
        // processes widget options to be saved
    }

    public function insertTask($post){
        //вставляем задачу
        //очищаем ввод пользователя от возможных гадостей
        $name= $this->wpdb->escape(sanitize_text_field($post['name']));
        $description = $this->wpdb->escape(sanitize_textarea_field($post['description']));

        //делаем вставку
        $this->wpdb->insert( $this->table_name, array( 'date' => time(), 'name' => $name, 'description' => $description, 'user_id' =>  $this->user_id, 'state' => 0) );
        $this->flushCache();
        //возвращаем внешний вид виджета
        return $this->view('index', ['data' => $this->selectDataForUser(), 'user_id' => $this->user_id, 'ajax' => 1]);
    }

    public function checkRights($id){
        //проверяем может ли пользователь править задачу
        $result = $this->wpdb->get_row( "SELECT * FROM " . $this->table_name . ' WHERE id = ' .  $id);
        //чтобы избежать второго запроса к базе - сохраняем запрос после проверки
        $this->result = $result;

        if ($result){
            if ($result->user_id == $this->user_id){
                return true;
            }
        } else {
            return false;
        }
    }

    public function setStateOptm($id, $state){
        //функция меняет состояние задачи
        $id = $this->wpdb->escape(absint($id));
        $state = $this->wpdb->escape(absint($state));

        if ($this->checkRights($id)){
            $this->wpdb->update( $this->table_name, array( 'state' => $state ), array( 'id' => $id ));
        }
        $this->flushCache();
        return $this->view('index', ['data' => $this->selectDataForUser(), 'user_id' => $this->user_id, 'ajax' => 1]);
    }
    public function getForm($id){
        $id = $this->wpdb->escape(absint($id));
        if ($this->checkRights($id)){
            if ($this->result){
                return $this->view('update', ['task' => $this->result]);
            }
        }

    }
    public function saveForm($post){
        $id = $this->wpdb->escape(absint($post['id']));
        $name= $this->wpdb->escape(sanitize_text_field($post['name']));
        $description = $this->wpdb->escape(sanitize_textarea_field($post['description']));

        if ($this->checkRights($id)){
            if ($this->result){
                $this->wpdb->update( $this->table_name, array( 'name' => $name, 'description' => $description ), array( 'id' => $id  ));
            }
        }
        $this->flushCache();
        return $this->view('index', ['data' => $this->selectDataForUser(), 'user_id' => $this->user_id, 'ajax' => 1]);
    }

    public function deleteTaskOptm($post){
        $id = $this->wpdb->escape(absint($post['id']));
        //проверяем права - может ли пользователь удалить
        if ($this->checkRights($id)){
            $this->wpdb->delete( $this->table_name, [ 'id' => $id ] );
        }
        $this->flushCache();
        //возвращаем внешний вид виджета
        return $this->view('index', ['data' => $this->selectDataForUser(), 'user_id' => $this->user_id, 'ajax' => 1]);
    }

    /**
     * @return mixed
     */

    public function flushCache(){
        set_transient('user_' . $this->user_id . '_' . $this->cachegroup, false, 12 * HOUR_IN_SECONDS);
    }

    public function selectDataForUser(){
        //пробуем получить реквест к базе данных из кеша
        $request = get_transient( 'user_' . $this->user_id . '_' . $this->cachegroup);

        if (!$request){
            $request = $this->wpdb->get_results( "SELECT * FROM " . $this->table_name . ' WHERE user_id = ' .  $this->user_id. ' ORDER BY date DESC');
            set_transient('user_' . $this->user_id . '_' . $this->cachegroup, $request, 12 * HOUR_IN_SECONDS);
        }
        return $request;
    }

    public function widget( $args, $instance ) {
        $this->view('index', ['data' => $this->selectDataForUser(), 'user_id' => $this->user_id, 'ajax' => 0]);
    }

    public function view( $name, array $args = array() ) {
        foreach ( $args AS $key => $val ) {
            $$key = $val;
        }
        $file = OPTM__PLUGIN_DIR . 'views/'. $name . '.php';
        include( $file );
    }
    public function saveInsert($name){
        return isset($_POST[$name]) ? $_POST[$name] : '';
    }


}

function OPTM_register_widgets() {
   register_widget( 'OrionPersonalTaskManagerClassWidget' );
}
add_action( 'widgets_init', 'OPTM_register_widgets' );
