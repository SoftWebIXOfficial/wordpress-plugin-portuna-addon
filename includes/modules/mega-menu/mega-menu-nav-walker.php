<?php
/**
 * The Mega Menu Functionality.
*/

namespace PortunaAddon\Modules;

use Walker_Nav_Menu;

defined( 'ABSPATH' ) || exit;

class Mega_Menu_Nav_Walker extends Walker_Nav_Menu {
    private $settings      = [];
    private $item_settings = null;
    private $mega_menu_is  = false;

    public function __construct( $settings, $mega_menu_is = false ) {
        $this->settings     = $settings;
        $this->mega_menu_is = $mega_menu_is;
    }

    /**
     * Starts the list before the elements are added.
     *
     * @see Walker_Nav_Menu::start_lvl()
     *
     * @since 3.0.0
     *
     * @param string   $output Used to append additional content (passed by reference).
     * @param int      $depth  Depth of page. Used for padding.
     * @param stdClass $args   Not used.
     */
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        if ( 'mega' === $this->get_item_type() ) {
            return;
        }

        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }

        $indent = str_repeat( $t, $depth );

        $classes     = [ 'portuna-addon-menu-items', 'portuna-addon-sub-menu' ];
        $class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        $output .= "{$n}{$indent}<ul $class_names>{$n}";
    }

    /**
     * Ends the list of after the elements are added.
     *
     * @since 3.0.0
     *
     * @see Walker::end_lvl()
     *
     * @param string   $output Passed by reference. Used to append additional content.
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        if ( 'mega' === $this->get_item_type() ) {
            return;
        }

        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }

        $indent = str_repeat( $t, $depth );

        $output .= "$indent</ul>{$n}";
    }

    /**
     * Starts the element output.
     *
     * @since 3.0.0
     * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
     *
     * @see Walker::start_el()
     *
     * @param string   $output Passed by reference. Used to append additional content.
     * @param WP_Post  $item   Menu item data object.
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     * @param int      $id     Current item ID.
     */
    public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
        $this->item_settings = null;
        $this->set_item_type( $item->ID, $depth );

        $item_settings = $this->get_settings( $item->ID );

        if ( 'mega' === $this->get_item_type() && 0 < $depth ) {
            return;
        }

        if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }

        $indent   = ( $depth ) ? str_repeat( $t, $depth ) : '';

        $settings = $this->get_settings( $item->ID );
        $classes  = empty( $item->classes ) ? array() : (array) $item->classes;

        if ( 'mega' === $this->get_item_type() ) {
            $classes[] = 'mega-menu-item';
        } else {
            $classes[] = 'simple-menu-item';
        }

        if ( $this->is_mega_enabled( $item->ID ) ) {
            $classes[] = 'menu-item-has-children';
        }

        // Add an active class for ancestor items
        if ( in_array( 'current-menu-ancestor', $classes ) || in_array( 'current-page-ancestor', $classes ) ) {
            $classes[] = 'current-menu-item';
        }

        /**
         * Filters the arguments for a single nav menu item.
         *
         * @since 4.4.0
         *
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param WP_Post  $item  Menu item data object.
         * @param int      $depth Depth of menu item. Used for padding.
         */
        $args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

        /**
         * Filters the CSS class(es) applied to a menu item's list item element.
         *
         * @since 3.0.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array    $classes The CSS classes that are applied to the menu item's `<li>` element.
         * @param WP_Post  $item    The current menu item.
         * @param stdClass $args    An object of wp_nav_menu() arguments.
         * @param int      $depth   Depth of menu item. Used for padding.
         */
        $classes = array_filter( $classes );

        array_walk( $classes, [ $this, 'modify_menu_item_classes' ] );

        $classes[] = 'menu-item-' . $item->ID;

        if ( 0 < $depth ) {
            $classes[] = 'sub-menu-item';
        }

        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', $classes, $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        /**
         * Filters the ID applied to a menu item's list item element.
         *
         * @since 3.0.1
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
         * @param WP_Post  $item    The current menu item.
         * @param stdClass $args    An object of wp_nav_menu() arguments.
         * @param int      $depth   Depth of menu item. Used for padding.
         */
        $id = apply_filters( 'nav_menu_item_id', 'portuna-addon-menu-item-'. $item->ID, $item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names .'>';

        $link_classes     = [];

        $atts             = [];
        $atts[ 'title' ]  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts[ 'target' ] = ! empty( $item->target )     ? $item->target     : '';
        $atts[ 'rel' ]    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts[ 'href' ]   = ! empty( $item->url )        ? $item->url        : '';

        $link_classes[]   = ( 0 === $depth ) ? 'first-level-link'  : 'nested-level-link';

        if ( $this->mega_menu_is && isset( $settings[ 'mega_menu_checkbox' ] ) && $settings[ 'mega_menu_checkbox' ] == true ) {
            $link_classes[] = 'mega-menu-is-enabled';
        }

        $atts[ 'class' ] = implode( ' ', $link_classes );

        /**
         * Filters the HTML attributes applied to a menu item's anchor element.
         *
         * @since 3.6.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array $atts {
         *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
         *
         *     @type string $title  Title attribute.
         *     @type string $target Target attribute.
         *     @type string $rel    The rel attribute.
         *     @type string $href   The href attribute.
         * }
         * @param WP_Post  $item  The current menu item.
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param int      $depth Depth of menu item. Used for padding.
         */
        $atts       = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        $attributes = '';

        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        /** This filter is documented in wp-includes/post-template.php */
        $title = apply_filters( 'the_title', $item->title, $item->ID );

        /**
         * Filters a menu item's title.
         *
         * @since 4.4.0
         *
         * @param string   $title The menu item's title.
         * @param WP_Post  $item  The current menu item.
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param int      $depth Depth of menu item. Used for padding.
         */
        $nav_title    = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

        $render_icon  = $this->renderIcon( $settings );
        $render_badge = $this->renderBadge( $settings );
        $render_arrow = $this->renderArrow( $item, $settings );

        $item_output = $args->before;
            $item_output .= '<div class="portuna-addon-menu__wrapper-content">';
                $item_output .= $args->link_before;
                    $item_output .= '<div class="portuna-addon-menu__wrapper-content-link">';
                        $item_output .= $render_icon;
                        $item_output .= '<a ' . $attributes . ' class="">' . $nav_title . '</a>';
                    $item_output .= '</div>';
                    $item_output .= $render_badge;
                    $item_output .= $render_arrow;
                $item_output .= $args->link_after;
            $item_output .= '</div>';
        $item_output .= $args->after;

        $is_elementor = ( isset( $_GET[ 'elementor-preview' ] ) ) ? true : false;

        $mega_item    = get_post_meta( $item->ID, 'portuna-addon-menu-item', true );
        $mega_item    = isset( $mega_item[ 'mega_menu_id' ] ) ? $mega_item[ 'mega_menu_id' ] : '';
        $mega_item_id = isset( $mega_item->ID ) ? $mega_item->ID : '';

        if ( $this->mega_menu_is && $this->is_mega_enabled( $item->ID ) && ! $is_elementor ) {
            $content       = '';

            if ( class_exists( 'Elementor\Plugin' ) ) {
                $elementor = \Elementor\Plugin::instance();
                $content   = $elementor->frontend->get_builder_content_for_display( $mega_item_id );
            }

            $content      = do_shortcode( $content );

            if ( $mega_item_id != '' ) {
                $item_output .= sprintf( '<ul class="portuna-addon-sub-mega-menu" data-template-id="%s">%s</ul>', $mega_item_id, $content );
            } else {
                $item_output .= sprintf( '<div class="portuna-addon-sub-mega-menu portuna-addon-sub-menu-content">%s</div>', esc_html__( 'Not Content Find', 'portuna-addon' ) );
            }

            // Fixed displaying mega and sub menu together.
            $this->set_item_type( $item->ID, $depth );
        }

        /**
         * Filters a menu item's starting output.
         *
         * The menu item's starting output only includes `$args->before`, the opening `<a>`,
         * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
         * no filter for modifying the opening and closing `<li>` for a menu item.
         *
         * @since 3.0.0
         *
         * @param string   $item_output The menu item's starting HTML output.
         * @param WP_Post  $item        Menu item data object.
         * @param int      $depth       Depth of menu item. Used for padding.
         * @param stdClass $args        An object of wp_nav_menu() arguments.
         */
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    /**
     * Ends the element output, if needed.
     *
     * @since 3.0.0
     *
     * @see Walker::end_el()
     *
     * @param string   $output Passed by reference. Used to append additional content.
     * @param WP_Post  $item   Page data object. Not used.
     * @param int      $depth  Depth of page. Not Used.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function end_el( &$output, $item, $depth = 0, $args = [] ) {
        if ( 'mega' === $this->get_item_type() && 0 < $depth ) {
            return;
        }

        $item_output = "</li>";
        $item_output = apply_filters( 'jet-menu/main-walker/end-el', $item_output, $item, $this, $depth, $args );

        $output .= $item_output;
    }

    /**
     * Modify menu item classes list
     *
     * @param  string &$item
     * @return void
     */
    public function modify_menu_item_classes( &$item, $index ) {
        if ( 0 === $index && 'menu-item' !== $item ) {
            return;
        }

        $item = 'portuna-addon-' . $item;
    }

    /**
     * Store in WP Cache processed item type
     *
     * @param integer $item_id Current menu Item ID
     * @param integer $depth   Current menu Item depth
     */
    public function set_item_type( $item_id = 0, $depth = 0 ) {
        if ( 0 < $depth ) {
            return;
        }

        $item_type = 'simple';

        if ( $this->is_mega_enabled( $item_id ) ) {
            $item_type = 'mega';
        }

        wp_cache_set( 'item-type', $item_type, 'portuna-addon' );
    }

    /**
     * Returns current item (for top level items) or parent item (for subs) type.
     * @return [type] [description]
     */
    public function get_item_type() {
        return wp_cache_get( 'item-type', 'portuna-addon' );
    }

    /**
     * Check if mega menu enabled for passed item
     *
     * @param  int  $item_id Item ID
     * @return boolean
     */
    public function is_mega_enabled( $item_id = 0 ) {
        $item_settings = $this->get_settings( $item_id );

        return ( $this->mega_menu_is && isset( $item_settings[ 'mega_menu_checkbox' ] ) && true == $item_settings[ 'mega_menu_checkbox' ] );
    }

   /**
    * Get item settings
    *
    * @param  integer $item_id Item ID
    * @return array
    */
   	public function get_settings( $item_id = 0 ) {
   		if ( null === $this->item_settings ) {
   			$this->item_settings = get_post_meta( $item_id, 'portuna-addon-menu-item', true );
   		}

   		return $this->item_settings;
   	}

   	public function renderIcon( $settings ) {
   	    $content = '';

   	    if ( isset( $settings[ 'menu_type_icon' ] ) && $settings[ 'menu_type_icon' ] === 'icon_none' ) {
   	        $content = null;
   	    }

   	    if ( isset( $settings[ 'menu_type_icon' ] ) && $settings[ 'menu_type_icon' ] === 'icon_lib' ) {
   	        if ( ! empty( $settings[ 'menu_icon' ] ) ) {
   	            $style   = $settings[ 'menu_icon_color' ] != 'style="color: ' . esc_attr( $settings[ 'menu_icon_color' ] ) . ';"' ? : null;

                $content = '<span class="" ' . $style . '><i class="' . esc_attr( $settings[ 'menu_icon' ] ) . '"></i></span>';
   	        }
   	    }

   	    if ( isset( $settings[ 'menu_type_icon' ] ) && $settings[ 'menu_type_icon' ] === 'icon_custom' ) {
   	        if ( ! empty( $settings[ 'menu_icon_custom' ] ) ) {
                $style   = $settings[ 'menu_icon_color' ] != 'style="color: ' . esc_attr( $settings[ 'menu_icon_color' ] ) . ';"' ? : null;

   	            $content = '<span class="" ' . $style . '>' . $this->customSvgToHtml( $settings[ 'menu_icon_custom' ] ) . '</span>';
   	        }
   	    }

        return $content;
   	}

   	public function renderBadge( $settings ) {
   	    $content    = '';
   	    $text_color = '';
   	    $bg_color   = '';

   	    if ( isset( $settings[ 'menu_badge_color' ] ) && $settings[ 'menu_badge_color' ] != '' ) {
            $text_color = 'color: ' . esc_attr( $settings[ 'menu_badge_color' ] ) . ';';
   	    }

   	    if ( isset( $settings[ 'menu_badge_bgcolor' ] ) && $settings[ 'menu_badge_bgcolor' ] != '' ) {
            $bg_color = 'background-color: ' . esc_attr( $settings[ 'menu_badge_bgcolor' ] ) . ';';
        }

        if ( ! empty( $settings[ 'menu_badge_text' ] ) ) {
            $style   = sprintf( 'style="%1$s %2$s"', $text_color, $bg_color );
            $content = '<span class="portuna-addon-menu-badge" ' . $style . '>' . esc_html( $settings[ 'menu_badge_text' ] ) . '</span>';
        }

        return $content;
   	}

   	public function renderArrow( $item, $settings ) {
   	    if ( ! $this->mega_menu_is ) {
   	        return;
   	    }

   	    $content      = '';
   	    $has_children = in_array( 'menu-item-has-children', $item->classes );
        $mega_menu    = $this->mega_menu_is && $this->is_mega_enabled( $item->ID );

        if ( $has_children || $mega_menu ) {
   	        $content = '<button class="portuna-addon-menu-dropdown"><i class="fa fa-angle-down"></i></button>';
        }

        return $content;
   	}

    // Custom SVG converter.
    public function customSvgToHtml( $id = '' ) {
        if ( empty( $id ) ) {
            return;
        }

        $thumbnail_id = get_post_thumbnail_id( $id );
        $get_url      = wp_get_attachment_url( $thumbnail_id );

        if ( ! $get_url ) {
            return;
        }

        return $this->getImgUrl( $get_url, [ 'class' => 'portuna-addon-custom-icon' ] );
    }

    private function getImgUrl( $url = null, $attr = [] ) {
        $url = esc_url( $url );

        if ( empty( $url ) ) {
            return;
        }

        $extends = pathinfo( $url, PATHINFO_EXTENSION );
        $attr    = array_merge( [ 'alt' => '' ], $attr );

        if ( 'svg' !== $extends ) {
            return sprintf( '<img src="%1$s"%2$s>', $url, $this->getAttr( $attr ) );
        }

        $base_url = site_url( '/' );
        $svg_path = str_replace( $base_url, ABSPATH, $url );
        $key_numb = md5( $svg_path );
        $svg      = get_transient( $key_numb );

        if ( ! $svg ) {
            $svg = file_get_contents( $svg_path );

            return sprintf( '<img src="%1$s"%2$s>', $url, $this->getAttr( $attr ) );
        }

        set_transient( $key_numb, $svg, DAY_IN_SECONDS );

        unset( $attr[ 'alt' ] );

        return sprintf( '<div%2$s>%1$s</div>', $svg, $this->getAttr( $attr ) );
    }

    private function getAttr( $attr = [] ) {
        if ( empty( $attr ) || ! is_array( $attr ) ) {
            return;
        }

        $content      = '';

        foreach ( $attr as $key => $value ) {
            $content .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
        }

        return $content;
    }

}