<?php

/**
 * WP Bootstrap Navwalker
 *
 * @package WP-Bootstrap-Navwalker
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class WP_Bootstrap_Navwalker
 *
 * A custom WordPress nav walker class to implement the Bootstrap 5 navigation style.
 */
class WP_Bootstrap_Navwalker extends Walker_Nav_Menu
{
    /**
     * Starts the element output.
     *
     * @param string   $output Used to append additional content (passed by reference).
     * @param WP_Post  $item   Menu item data object.
     * @param int      $depth  Depth of menu item.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     * @param int      $id     Current item ID.
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
            $t = '';
            $n = '';
        } else {
            $t = "\t";
            $n = "\n";
        }
        $indent = ($depth) ? str_repeat($t, $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;

        // Initialize some holder variables to store specially handled item
        // wrappers and icons.
        $linkmod_classes = array();
        $icon_classes    = array();

        // Get an updated $classes array without linkmod or icon classes.
        $classes = self::separate_linkmods_and_icons_from_classes($classes, $linkmod_classes, $icon_classes);

        // Join any icon classes plucked from $classes into a string.
        $icon_class_string = join(' ', $icon_classes);

        /**
         * Filters the arguments for a single nav menu item.
         *
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param WP_Post  $item  Menu item data object.
         * @param int      $depth Depth of menu item.
         */
        $args = apply_filters('nav_menu_item_args', $args, $item, $depth);

        // Add .dropdown or .active classes where they are needed.
        if (isset($args->has_children) && $args->has_children) {
            $classes[] = 'dropdown';
        }
        if (in_array('current-menu-item', $classes, true) || in_array('current-menu-parent', $classes, true)) {
            $classes[] = 'active';
        }

        // Add some additional default classes to the item.
        $classes[] = 'menu-item-' . $item->ID;
        $classes[] = 'nav-item';

        // Allow filtering the classes.
        $classes = apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth);

        // Form a string of classes in format: class="class_names".
        $class_names = join(' ', $classes);
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        /**
         * Filters the ID applied to a menu item's list item element.
         *
         * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
         * @param WP_Post $item   Menu item data object.
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         */
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        // Initialize array for holding the $atts for the link item.
        $atts = array();

        // Set title from item to the $atts array.
        if (empty($item->attr_title)) {
            $atts['title'] = !empty($item->title) ? strip_tags($item->title) : '';
        } else {
            $atts['title'] = $item->attr_title;
        }

        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';

        // If the item has children, add atts to the link.
        if (isset($args->has_children) && $args->has_children && 0 === $depth && $args->depth > 1) {
            $atts['href']          = '#';
            $atts['data-bs-toggle'] = 'dropdown';
            $atts['aria-expanded'] = 'false';
            $atts['class']         = 'nav-link dropdown-toggle';
        } else {
            // For items in dropdowns use .dropdown-item instead of .nav-link.
            if ($depth > 0) {
                $atts['class'] = 'dropdown-item';
            } else {
                $atts['href'] = !empty($item->url) ? $item->url : '#';
                $atts['class'] = 'nav-link';
            }
        }

        // Update atts of this item based on any custom linkmod classes.
        $atts = self::update_atts_for_linkmod_type($atts, $linkmod_classes);

        // Allow filtering of the $atts array before using it.
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        // Build a string of html containing all the atts for the item.
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value      = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        // Initialize empty variable for the item output.
        $item_output = isset($args->before) ? $args->before : '';

        // Wrap the item with appropriate elements if it's a linkmod or icon.
        $item_output .= self::linkmod_element_open($linkmod_classes, $icon_classes, $attributes);

        // Add icons before or after the text
        if (!empty($icon_class_string)) {
            // Add icons before or after text depending on position
            if (false !== strpos($icon_class_string, 'fa-after')) {
                $item_output .= sprintf('%1$s <i class="%2$s" aria-hidden="true"></i>', $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after, str_replace('fa-after', '', $icon_class_string));
            } else {
                $item_output .= sprintf('<i class="%1$s" aria-hidden="true"></i> %2$s', $icon_class_string, $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after);
            }
        } else {
            // With no icon, just output the item text
            $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        }

        $item_output .= self::linkmod_element_close($linkmod_classes);
        $item_output .= isset($args->after) ? $args->after : '';

        // Filter the output of the menu item.
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    /**
     * Traverse elements to create list from elements.
     *
     * Display one element if the element doesn't have any children otherwise,
     * display the element and its children.
     *
     * @param object $element           Data object.
     * @param array  $children_elements List of elements to continue traversing.
     * @param int    $max_depth         Max depth to traverse.
     * @param int    $depth             Depth of current element.
     * @param array  $args              An array of arguments.
     * @param string $output            Used to append additional content (passed by reference).
     */
    public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output)
    {
        if (!$element) {
            return;
        }
        $id_field = $this->db_fields['id'];
        // Display this element.
        if (is_object($args[0])) {
            $args[0]->has_children = !empty($children_elements[$element->$id_field]);
        }
        parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }

    /**
     * Menu Fallback.
     * If this function is assigned to the wp_nav_menu's fallback_cb variable
     * and a menu has not been assigned to the theme location in the WordPress
     * menu manager the function will display nothing to a non-logged in user,
     * and will add a link to the WordPress menu manager if logged in as an admin.
     *
     * @param array $args passed from the wp_nav_menu function.
     * @return string|void String when echo is false.
     */
    public static function fallback($args)
    {
        if (current_user_can('edit_theme_options')) {
            // Get Arguments.
            $container       = $args['container'];
            $container_id    = $args['container_id'];
            $container_class = $args['container_class'];
            $menu_class      = $args['menu_class'];
            $menu_id         = $args['menu_id'];

            // Initialize var to store fallback html.
            $fallback_output = '';

            if ($container) {
                $fallback_output .= '<' . esc_attr($container);
                if ($container_id) {
                    $fallback_output .= ' id="' . esc_attr($container_id) . '"';
                }
                if ($container_class) {
                    $fallback_output .= ' class="' . esc_attr($container_class) . '"';
                }
                $fallback_output .= '>';
            }
            $fallback_output .= '<ul';
            if ($menu_id) {
                $fallback_output .= ' id="' . esc_attr($menu_id) . '"';
            }
            if ($menu_class) {
                $fallback_output .= ' class="' . esc_attr($menu_class) . '"';
            }
            $fallback_output .= '>';
            $fallback_output .= '<li class="nav-item"><a href="' . esc_url(admin_url('nav-menus.php')) . '" class="nav-link" title="' . esc_attr__('Add a menu', 'bike-theme') . '">' . esc_html__('Add a menu', 'bike-theme') . '</a></li>';
            $fallback_output .= '</ul>';
            if ($container) {
                $fallback_output .= '</' . esc_attr($container) . '>';
            }

            // If $args has 'echo' key and it's true echo, otherwise return.
            if (array_key_exists('echo', $args) && $args['echo']) {
                echo $fallback_output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            } else {
                return $fallback_output;
            }
        }
    }

    /**
     * Find any custom linkmod or icon classes and store in their holder
     * arrays then remove them from the main classes array.
     *
     * @param array  $classes      The classes array.
     * @param array  $linkmod_classes An array to hold linkmod classes.
     * @param array  $icon_classes    An array to hold icon classes.
     * @return array  $classes A maybe modified array of classnames.
     */
    private static function separate_linkmods_and_icons_from_classes($classes, &$linkmod_classes, &$icon_classes)
    {
        // Loop through $classes array to find linkmod or icon classes.
        foreach ($classes as $key => $class) {
            // If any special classes are found, store the class in it's holder array and and unset the item from $classes.
            if (preg_match('/^fa-(\S*)?|^fa(s|r|l|b)?(\s?)?$/i', $class)) {
                // Font Awesome.
                $icon_classes[] = $class;
                unset($classes[$key]);
            } elseif (preg_match('/^glyphicon-(\S*)?|^glyphicon(\s?)$/i', $class)) {
                // Glyphicons.
                $icon_classes[] = $class;
                unset($classes[$key]);
            } elseif (preg_match('/^(icon-)?(\s?)bi(\s?)?bi-(\S*)?$/i', $class)) {
                // Bootstrap Icons.
                $icon_classes[] = $class;
                unset($classes[$key]);
            }
        }

        return $classes;
    }

    /**
     * Update the attributes of a menu item depending on the limkmod classes.
     *
     * @param array $atts        Array of atts to update.
     * @param array $linkmod_classes An array of classes that can change the $atts array.
     * @return array Maybe updated array of attributes.
     */
    private static function update_atts_for_linkmod_type($atts, $linkmod_classes)
    {
        if (!empty($linkmod_classes)) {
            foreach ($linkmod_classes as $link_class) {
                if (!empty($link_class)) {
                    // Update $atts with a space and the extra classname.
                    if (empty($atts['class'])) {
                        $atts['class'] = $link_class;
                    } else {
                        $atts['class'] .= ' ' . $link_class;
                    }
                }
            }
        }
        return $atts;
    }

    /**
     * Return the correct opening element and attributes for a linkmod.
     *
     * @param array  $linkmod_classes A string containing a linkmod class.
     * @param array  $icon_classes    A string containing an icon class.
     * @param string $attributes      A string of attributes to add to the element.
     *
     * @return string The opening tag for the navigation item.
     */
    private static function linkmod_element_open($linkmod_classes, $icon_classes, $attributes = '')
    {
        $item_output = '';

        // Always add <a> for links with icons
        if (!empty($icon_classes)) {
            $item_output = '<a' . $attributes . '>';
        } else {
            // Check for linkmod type and create output accordingly
            if (in_array('dropdown-header', $linkmod_classes, true)) {
                $item_output = '<h6 class="dropdown-header"';
                if (!empty($attributes)) {
                    $item_output .= $attributes;
                }
                $item_output .= '>';
            } elseif (in_array('dropdown-divider', $linkmod_classes, true)) {
                // This is a divider.
                $item_output = '<div class="dropdown-divider"';
                if (!empty($attributes)) {
                    $item_output .= $attributes;
                }
                $item_output .= '>';
            } elseif (in_array('dropdown-item-text', $linkmod_classes, true)) {
                // This is text with no link.
                $item_output = '<span class="dropdown-item-text"';
                if (!empty($attributes)) {
                    $item_output .= $attributes;
                }
                $item_output .= '>';
            } else {
                // It's a regular link with no special classes
                $item_output = '<a' . $attributes . '>';
            }
        }

        return $item_output;
    }

    /**
     * Return the correct closing element for a linkmod.
     *
     * @param array $linkmod_classes A string containing a linkmod class.
     *
     * @return string The closing tag for the navigation item.
     */
    private static function linkmod_element_close($linkmod_classes)
    {
        // Check for linkmod type and create output accordingly
        if (in_array('dropdown-header', $linkmod_classes, true)) {
            // This is a Header.
            $item_output = '</h6>';
        } elseif (in_array('dropdown-divider', $linkmod_classes, true)) {
            // This is a divider.
            $item_output = '</div>';
        } elseif (in_array('dropdown-item-text', $linkmod_classes, true)) {
            // This is text with no link.
            $item_output = '</span>';
        } else {
            // It's a regular link with no special classes
            $item_output = '</a>';
        }

        return $item_output;
    }
}
