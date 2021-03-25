<?php

namespace PortunaAddon\Helpers;

defined( 'ABSPATH' ) || exit;

class Ajax {
    private $options;

    public function __construct() {
        add_action( 'wp_ajax_portuna_addon_ajax', [$this, 'elementskit_admin_action'] );
        $this->options = Options::instance();
    }

    public function elementskit_admin_action() {

        if( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( isset( $_POST[ 'user_data' ] ) ) {
            $this->options->save_option( 'user_data', empty( $_POST[ 'user_data' ] ) ? [] : $_POST[ 'user_data' ] );
        }

        //do_action('elementskit/admin/after_save');

        wp_die(); // this is required to terminate immediately and return a proper response
    }

    public function return_json($data){
        if(is_array($data) || is_object($data)){
            return  json_encode($data);
        }else{
            return $data;
        }
    }

}