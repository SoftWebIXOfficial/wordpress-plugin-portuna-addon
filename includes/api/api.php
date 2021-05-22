<?php

namespace PortunaAddon\Api;

defined( 'ABSPATH' ) || exit;

class Rest_Api {

    protected $namespace = 'api/v2';

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
                $url                = $urlparts[ 'scheme' ] . '://' . $urlparts[ 'host' ] . '/';

                if ( filter_var( $url, FILTER_VALIDATE_URL ) !== false && @get_headers( $url ) ) {
                    $validation = TRUE;
                }
            }
        }

        return (bool) $validation;
    }

    public function rest_api() {
        add_action( 'rest_api_init', function() {
            register_rest_route( $this->namespace, '/get', array(
                'methods'             => \WP_REST_Server::READABLE,
                'callback'            => [ $this, 'get_data' ],
                'permission_callback' => '__return_true',
            ) );
        } );
    }

    public function get_data( $request_data ) {
        global $wpdb;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $output            = '';
        $parameters        = $request_data->get_params();
        $user_key          = sanitize_text_field( $parameters[ 'access_key' ] ); // ex: http://localhost/InprogressProject/portuna/wp-json/api/v2/get?access_key=GF1v2cP9wPNo9xD5kNt3fvSMXFxT
        $table_name        = $wpdb->get_blog_prefix() . 'generate_key';

        $sql_results_query = "SELECT id, unique_key FROM {$table_name}
                                                   WHERE unique_key = '{$user_key}'";
        $get_results       = $wpdb->get_results( $sql_results_query );

        if ( ! empty( $get_results ) ) {
            $data     = $get_results;
            $new_data = [];

            foreach ( $data as $key => $value ) {
                $user_data = [
                    'data' => [
                        'id'          => intval( $data[ $key ]->id ),
                        'license_key' => $data[ $key ]->unique_key,
                        'status'      => true
                    ]
                ];

                $new_data = $user_data;
            }

            $new_json = json_encode( $new_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );

            $output       .= $new_json;
        } else {
            $output       .= __( 'Sorry, this content isn\'t available right now.', 'portuna-addon' );
        }

        echo $output;
    }

    public function __construct() {
        $this->rest_api();
    }
}