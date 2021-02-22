<?php

namespace PortunaAddon\Core;

defined( 'ABSPATH' ) || exit;

class Api {
    protected $namespace   = 'portuna/v1/';

    public $prefix  = '';
    public $route   = '';
    public $request = null;

    public function config() { }

    public function rest_api() {
        add_action( 'rest_api_init', function() {
            register_rest_route( untrailingslashit( $this->namespace . $this->prefix ), '/(?P<action>\w+)/' . ltrim( $this->route, '/' ), [
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'rest_api_action' ],
                    'permission_callback' => '__return_true',
                ]
            );
        } );
    }

    public function rest_api_action( $request ) {
        $this->request = $request;
        $action_class  = strtolower( $this->request->get_method() ) . '_' . sanitize_key( $this->request[ 'action' ] );

        if ( method_exists( $this, $action_class ) ) {
            return $this->{$action_class}();
        }
    }

    public function __construct() {
        $this->config();
        $this->rest_api();
    }
}