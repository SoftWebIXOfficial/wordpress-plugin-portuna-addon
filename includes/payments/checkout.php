<?php

/**
 * The Payments Checkout.
*/

namespace PortunaAddon\Payments;

class Checkout {
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

    private function send_email( $data ) {
        $status        = false;
        $output        = '';
        $to_address    = sanitize_email( $data[ 'sender_email' ] );
        $subject       = __( 'Website Contact', 'portuna-addon' );
        $message       = '<html><body>';
        $message      .= '<h2>' . __( 'Test email send', 'portuna-addon' ) . '</h2>';
        $message      .= '</body></html>';
        $headers[]     = 'Content-type: text/html; charset=UTF-8';
        $header_string = implode( '\r\n', $headers );

        $send_email    = wp_mail(
            $to_address,
            $subject,
            $message,
            $header_string
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
                    $this->send_email( $_POST );
                }
            }
        }
    }
}

//new Checkout();