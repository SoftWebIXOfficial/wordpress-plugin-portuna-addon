<?php

namespace PortunaAddon\Api;

defined( 'ABSPATH' ) || exit;

class Rest_Api {
    protected $namespace       = 'portuna/v2/';
    protected $permitted_chars = '&0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    protected function generate_string( $input, $strength = 16 ) {
        $input_length  = strlen( $input );
        $random_string = '';

        for ( $i = 0; $i < $strength; $i++ ) {
            $random_character = $input[ mt_rand( 0, $input_length - 1 ) ];
            $random_string    .= $random_character;
        }

        return $random_string;
    }

    // $this->is_valid_domain( get_site_url() )
    private function is_valid_domain( $url ) {
        $validation = FALSE;
        $urlparts   = parse_url( filter_var( $url, FILTER_SANITIZE_URL ) );

        /*
         * Check host exist else path assign to host.
         */
        if ( ! isset( $urlparts[ 'host' ] ) ) {
            $urlparts[ 'host' ] = $urlparts[ 'path' ];
        }

        if ( $urlparts[ 'host' ] != '' ) {
            if ( ! isset( $urlparts[ 'scheme' ] ) ) {
                $urlparts[ 'scheme' ] = 'http';
            }

            if ( checkdnsrr( $urlparts[ 'host' ], 'A' )
                && in_array( $urlparts[ 'scheme' ] , array( 'http', 'https' ) )
                && ip2long( $urlparts[ 'host' ] ) === FALSE )
            {
                $urlparts[ 'host' ] = preg_replace( '/^www\./', '', $urlparts[ 'host' ] );
                $url = $urlparts[ 'scheme' ].'://' . $urlparts[ 'host' ] . "/";

                if ( filter_var( $url, FILTER_VALIDATE_URL ) !== false && @get_headers( $url ) ) {
                    $validation = TRUE;
                }
            }
        }

        return (bool) $validation;
    }

    public function rest_api() {
        add_action( 'rest_api_init', function() {
            register_rest_route( $this->namespace, '/generator', array(
                'methods'             => \WP_REST_Server::ALLMETHODS,
                'callback'            => [ $this, 'generate_key' ],
                'permission_callback' => null,
            ) );
        } );
    }

    public function generate_key( $request_data ) {
        global $wpdb;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $table_name = $wpdb->get_blog_prefix() . 'generate_key';
        $generate   = $this->generate_string( $this->permitted_chars, 28 );

        $sql_create = "CREATE TABLE {$table_name} (
            id bigint(20) unsigned NOT NULL auto_increment,
            unique_key varchar(255) NOT NULL default '',
            date_generate DATETIME,
            PRIMARY KEY (id)
        )";

        dbDelta( $sql_create ); //Create new table.

        $wpdb->insert(
            $table_name,
            [
                'unique_key'    => $generate,
                'date_generate' => date( 'Y-m-d H:i:s')
            ]
        );

        $this->successHtml( $generate );
    }

    public function successHtml( $key ) {
        $output = "<div>";
            $output .= $key;
        $output .= "</div>";

        echo sprintf( '%s', $output );
    }

    public function __construct() {
        $this->rest_api();
//         $callback = file_get_contents('php://input');
//         $callaback_object = json_decode($callback);
//         var_dump( $_SERVER );
    }
}