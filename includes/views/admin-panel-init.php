<?php

defined( 'ABSPATH' ) || die();

$front_page = [
    'settings' => [
        'front'    => [ $this, 'admin_frontend_settings' ]
    ],
    'pro' => [
        'front'    => [ $this, 'admin_frontend_pro' ]
    ],
];

$front_page = apply_filters( 'get_front_page', $front_page );

?>

<div class="portuna-addon-admin">
    <div class="portuna-addon-admin--wrapper">
        <?php
            $j = 1;

            foreach( $front_page as $key => $data ) :
                if ( empty( $data[ 'front' ] ) || ! is_callable( $data[ 'front' ] ) ) :
                    continue;
                endif;

                $key = esc_attr( strtolower( $key ) );
        ?>
            <div class="portuna-addon-admin--content-menu--front portuna-addon-admin--content-menu--<?php echo esc_attr( $key ); ?><?php echo $j == 1 ? ' nav-is-active' : null ?>" data-tab="<?php echo esc_attr( $key ); ?>">
                <?php call_user_func( $data[ 'front' ], $key ); ?>
            </div>
        <?php
            ++$j;

            endforeach;
        ?>
    </div>
</div>