<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit16862581ea4482efdc7f8079dbd54cfc
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PortunaAddon\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PortunaAddon\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'PortunaAddon\\Base' => __DIR__ . '/../..' . '/includes/base.php',
        'PortunaAddon\\Controls\\Elementor_Area' => __DIR__ . '/../..' . '/includes/controls/elementor-area.php',
        'PortunaAddon\\Core\\Api' => __DIR__ . '/../..' . '/includes/core/api.php',
        'PortunaAddon\\Core\\Cpt_Apissss' => __DIR__ . '/../..' . '/includes/core/elementor-cpt/cpt-api.php',
        'PortunaAddon\\Core\\Elementor\\Init' => __DIR__ . '/../..' . '/includes/core/elementor-cpt/init.php',
        'PortunaAddon\\Core\\Register_Cpt' => __DIR__ . '/../..' . '/includes/core/elementor-cpt/cpt-register.php',
        'PortunaAddon\\Helpers\\ControlsManager' => __DIR__ . '/../..' . '/includes/helpers/controls-manager.php',
        'PortunaAddon\\Helpers\\Utils' => __DIR__ . '/../..' . '/includes/helpers/utils.php',
        'PortunaAddon\\Helpers\\WidgetsManager' => __DIR__ . '/../..' . '/includes/helpers/widgets-manager.php',
        'PortunaAddon\\ScriptsManager' => __DIR__ . '/../..' . '/includes/scripts-manager.php',
        'PortunaAddon\\Traits\\Singleton' => __DIR__ . '/../..' . '/includes/traits/singleton.php',
        'PortunaAddon\\Widgets\\Edit\\AdvancedHeading' => __DIR__ . '/../..' . '/includes/widgets/heading/edit.php',
        'PortunaAddon\\Widgets\\Edit\\BannerSlider' => __DIR__ . '/../..' . '/includes/widgets/banner-slider/edit.php',
        'PortunaAddon\\Widgets\\Portuna_Widget_Base' => __DIR__ . '/../..' . '/includes/portuna-widget-base.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit16862581ea4482efdc7f8079dbd54cfc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit16862581ea4482efdc7f8079dbd54cfc::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit16862581ea4482efdc7f8079dbd54cfc::$classMap;

        }, null, ClassLoader::class);
    }
}
