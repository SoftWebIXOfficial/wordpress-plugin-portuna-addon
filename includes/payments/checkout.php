<?php

/**
 * The Payments Checkout.
*/

namespace PortunaAddon\Payments;

class Checkout {
    protected $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    protected function generate_string( $input, $strength = 16 ) {
        $input_length  = strlen( $input );
        $random_string = '';

        for ( $i = 0; $i < $strength; $i++ ) {
            $random_character = $input[ mt_rand( 0, $input_length - 1 ) ];
            $random_string   .= $random_character;
        }

        return $random_string;
    }

    public function register_scripts() {
        wp_enqueue_script(
            'portuna-checkout-payments',
            plugins_url( 'portuna-addon/assets/' ) . 'js/checkout-payments.min.js',
            [],
            '1.0.0'
        );

        wp_localize_script(
            'portuna-checkout-payments',
            'portunaCheckoutPayments',
            array(
                'callback' => $_POST,
            )
        );
    }

    public function generate_key( $key ) {
        global $wpdb;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $table_name = $wpdb->get_blog_prefix() . 'generate_key';

        $sql_create = "CREATE TABLE {$table_name} (
            id bigint(20) unsigned NOT NULL auto_increment,
            unique_key varchar(200) NOT NULL default '',
            date_generate DATETIME,
            PRIMARY KEY (id)
        )";

        dbDelta( $sql_create ); //Create new table.

        $wpdb->insert(
            $table_name,
            [
                'unique_key'    => $key,
                'date_generate' => date( 'Y-m-d H:i:s')
            ]
        );
    }

    private function send_email( $data, $key ) {
        $status        = false;
        $output        = '';
        $to_address    = sanitize_email( $data[ 'sender_email' ] );
        $subject       = __( 'Purchase Confirmation and Instructions', 'portuna-addon' );
        $message       = '<html><body>';
            $message      .= '<div marginwidth="0" marginheight="0" style="padding: 15px 0; background-color: rgba(0,0,0,0.03);">';
                $message      .= '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
                    $message      .= '<tbody>';
                        $message      .= '<tr>';
                            $message      .= '<td align="center" valign="top">';
                                $message      .= '<table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #fff; box-shadow: 0px 0px 25px 0px rgba(0,0,0,0.05); text-align: center; padding: 0; border-radius: 5px;">';
                                    $message      .= '<tbody>';
                                        $message      .= '<tr>';
                                            $message      .= '<td align="center" valign="top">';
                                                $message      .= '<table border="0" cellpadding="0" cellspacing="0" style="padding: 25px 30px;">';
                                                    $message      .= '<tbody>';
                                                        $message      .= '<tr>';
                                                            $message      .= '<td align="center" valign="top">';
                                                                $message      .= '<h1 style="font-family: Roboto, RobotoDraft, Helvetica, Arial, sans-serif; font-size: 34px; font-weight: 400; line-height: 1.2; color: #000000;">' . __( 'Purchase Receipt', 'portuna-addon' ) . '</h1>';
                                                            $message      .= '</td>';
                                                        $message      .= '</tr>';
                                                        $message      .= '<tr>';
                                                            $message      .= '<td align="center" valign="top">';
                                                                $message      .= '<div style="color: #333333; font-size: 14px;">';
                                                                    $message      .= '<p style="color: #000000"><b>' . __( 'Dear User,', 'portuna-addon' ) . '</b></p>';
                                                                    $message      .= '<p style="color: #000000">' . __( 'Thank you for purchasing the extended version of the plugin Portuna Addon. To get started with the plugin you need to copy and enter this key in the plugin settings, here is your API key:', 'portuna-addon' ) . '</p>';
                                                                    $message      .= '<p><pre style="background: #434343; color: #ffffff; font-size: 18px; border-radius: 4px; padding: 12px 20px 12px 22px;">' . $key . '</pre></p>';
                                                                    $message      .= '<p style="color: #000000; margin-top: 30px; display: block;"><b>' . __( 'What\'s Next?', 'portuna-addon' ) . '</b></p>';
                                                                    $message      .= '<ul style="list-style: decimal; color: #000000; text-align: left;">';
                                                                        $message      .= '<li>' . sprintf( __( '%s. After purchasing, go to your admin dashboard in WordPress.', 'portuna-addon' ), '<b>' . __( 'Login to your account WordPress', 'portuna-addon' ) . '</b>' ) . '</li>';
                                                                        $message      .= '<li>' . sprintf( __( '%s. Go to %s, and insert your key in the input "License key" and submitted.', 'portuna-addon' ), '<b>' . __( 'Activate the license key', 'portuna-addon' ) . '</b>', '<b>' . __( 'Portuna > Pro', 'portuna-addon' ) . '</b>' ) . '</li>';
                                                                        $message      .= '<li>' . sprintf( __( '%s. Now you can use the plugin for all 100 percent.', 'portuna-addon' ), '<b>' . __( 'Successfully activated', 'portuna-addon' ) . '</b>', '<b>' . __( 'Portuna > Pro', 'portuna-addon' ) . '</b>' ) . '</li>';
                                                                    $message      .= '</ul>';
                                                                    $message      .= '<p style="color: #000000;">' . sprintf( __( 'We hope you enjoy using Portuna Addon Pro, %s.', 'portuna-addon' ), '<b>' . __( 'The SoftWebIX Team', 'portuna-addon' ) . '</b>' ) . '</p>';
                                                                    $message      .= '<p style="color: #000000; margin-bottom: 25px;">' . __( 'P.S. Don\'t hesitate to reply directly to this email if you have any issues with Portuna Addon or any questions - we would be happy to help you.', 'portuna-addon' ) . '</p>';
                                                                    $message      .= '<hr style="opacity: .2;">';
                                                                    $message      .= '<ul style="list-style: none; padding: 0; margin-top: 25px; color: #000000; text-align: left;">';
                                                                        $message      .= '<li>' . sprintf( __( '%s: $12.00', 'portuna-addon' ), '<b>' . __( 'Total', 'portuna-addon' ) . '</b>' ) . '</li>';
                                                                        $message      .= '<li style="margin-top: 5px;">' . sprintf( __( '%s: %s', 'portuna-addon' ), '<b>' . __( 'Date of Purchase', 'portuna-addon' ) . '</b>', date( 'F j, Y' ) ) . '</li>';
                                                                    $message      .= '</ul>';
                                                                $message      .= '</div>';
                                                            $message      .= '</td>';
                                                        $message      .= '</tr>';
                                                    $message      .= '</tbody>';
                                                $message      .= '</table>';
                                            $message      .= '</td>';
                                        $message      .= '</tr>';
                                    $message      .= '</tbody>';
                                $message      .= '</table>';
                            $message      .= '</td>';
                        $message      .= '</tr>';
                    $message      .= '</tbody>';
                $message      .= '</table>';
            $message      .= '</div>';
        $message      .= '</body></html>';
        $headers       = sprintf( 'From: %s <%s>' . "\r\n", 'Portuna Addon', 'stockwebdev@gmail.com' );
        $headers      .= sprintf( 'Reply-To: %s <%s>' . "\r\n", 'stockwebdev@gmail.com', 'stockwebdev@gmail.com' );
        $headers      .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";

        $send_email    = mail(
            $to_address,
            $subject,
            $message,
            $headers
        );

        $output .= '<p>';
            if ( ! empty( $send_email ) ) {
                $status = true;
                $output .= __( 'Thanks for your feedback! We will acknowledge and make it better in the future.', 'portuna-addon' );
            } else {
                $output .= __( 'Oops! It seems there was a problem sending the e-mail. Please try again!', 'portuna-addon' );
            }
        $output .= '</p>';

        $response = [
            'send_status' => $status,
            'message'     => $output,
        ];

        //wp_send_json( $response );

        echo $output;
    }

    public function __construct() {
        if ( isset( $_SERVER[ 'REDIRECT_URL' ] ) ) {
            $current_url =  ( is_ssl() ? 'https' : 'http' ) . '://' . $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REDIRECT_URL' ];

            if ( $current_url === home_url() . '/success-payments/' ) {
                add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ], 5 );

                if ( ! empty( $_POST ) ) {
                    $generate   = $this->generate_string( $this->permitted_chars, 28 );

                    $this->generate_key( $generate );
                    $this->send_email( $_POST, $generate );
                }
            }
        }
    }
}

new Checkout();