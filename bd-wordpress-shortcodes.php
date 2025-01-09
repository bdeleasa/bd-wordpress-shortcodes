<?php
/**
 * BD WordPress Shortcodes
 *
 * @package     BD_Wordpress_Shortcodes
 * @author      Brianna Deleasa
 * @copyright   2024 Brianna Deleasa
 * @license     GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name: BD WordPress Shortcodes
 * Plugin URI:  https://example.com/plugin-name
 * Description: A few shortcodes to make life easier in the future, like outputting the current year in the copyright.
 * Version:     2.2.0
 * Author:      Brianna Deleasa
 * Author URI:  https://example.com
 * Text Domain: bd-wordpress-shortcodes
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */


namespace BD_Wordpress_Shortcodes;


add_shortcode( 'date', __NAMESPACE__ . '\shortcode_date' );
/**
 * Shortcode to return the current date in the given format.
 *
 * Usage:
 *
 * [date format="m/d/Y"]
 * [date format="Y-m-d"]
 * [date format="F j, Y"]
 *
 * @since 1.0.0
 *
 * @param array $atts
 * @param string|null $content
 * @return string
 */
function shortcode_date(array $atts, string $content = null ): string
{
    $format = ! empty( $atts['format'] ) ? $atts['format'] : 'm/d/Y';
    return date( $format );
}


add_shortcode( 'site-name', __NAMESPACE__ . '\shortcode_site_name' );
/**
 * Shortcode to return the site name.
 *
 * Usage: [site-name]
 *
 * @since 1.0.0
 *
 * @param array $atts
 * @param string|null $content
 * @return string
 */
function shortcode_site_name(array $atts, string $content = null): string
{
    return get_bloginfo('name');
}


add_shortcode( 'admin_email', __NAMESPACE__ . '\shortcode_admin_email' );
add_shortcode( 'admin-email', __NAMESPACE__ . '\shortcode_admin_email' );
/**
 * Shortcode to return the admin email.
 *
 * Usage: [admin-email]
 *
 * @since 1.0.0
 *
 * @param array $atts
 * @param string|null $content
 * @return mixed|null
 */
function shortcode_admin_email(array $atts, string $content = null ): mixed
{
    $email = get_bloginfo( 'admin_email' );
    return apply_filters( 'bd_wordpress_shortcode/admin_email', $email );
}


add_shortcode( 'archives', __NAMESPACE__ . '\archives_shortcode' );
/**
 * Shortcode to return the archives.
 *
 * Usage: [archives]
 *
 * @since 1.0.0
 *
 * @param array $atts
 * @param string|null $content
 * @return string
 */
function archives_shortcode( array $atts, string $content = null ): string
{
    return wp_get_archives('type=monthly');
}


add_shortcode( 'menu', __NAMESPACE__ . '\shortcode_menu' );
/**
 * Shortcode that outputs a <ul> menu.
 *
 * Example Usages:
 *
 * [menu name="primary"]
 * [menu id="123"]
 * [menu menu="primary" container="nav"]
 * [menu menu="primary" container="nav" menu_class="menu"]
 *
 * @see https://developer.wordpress.org/reference/functions/wp_nav_menu/
 *
 * @since 1.0.0
 *
 * @param $atts array
 * @param $content string|null
 * @return string
 */
function shortcode_menu(array $atts, string $content = null): string
{
    $atts = shortcode_atts(
        array(
            'name'              => null,
            'menu'              => null,
            'id'                => null,
            'menu_id'           => null,
            'menu_class'        => null,
            'container'         => false,
            'walker'            => '',
            'echo'              => false,
            'fallback_cb'       => false
        )
    , $atts);

    // Allow use of either 'menu' or 'name' parameter when specifying the menu to pull
    if ( isset($atts['name']) ) {
        $atts['menu'] = $atts['name'];
    }

    // Allow the use of either 'id' or 'menu_id' parameter when specifying the menu ID
    if ( isset($atts['id']) ) {
        $atts['menu_id'] = $atts['id'];
    }

    return wp_nav_menu( $atts );
}


add_shortcode( 'logo', __NAMESPACE__ . '\shortcode_logo' );
/**
 * Shortcode that outputs the site logo.
 *
 * Usage: [logo]
 *
 * @since 1.0.0
 *
 * @param $atts array
 * @param $content string|null
 * @return string
 */
function shortcode_logo(array $atts, string $content = null): string
{
    $atts = shortcode_atts(
        array(
            'size'              => 'full',
            'class'             => '',
            'alt'               => '',
            'echo'              => false
        )
    , $atts);

    // Get customizer logo option
    $logo = get_theme_mod( 'custom_logo' );
    $logo_id = is_array( $logo ) ? $logo['ID'] : $logo;
    $logo_id = apply_filters( 'bd_wordpress_shortcode/logo/id', $logo_id ?? false );

    if ( $logo_id ) {
        $logo_html = wp_get_attachment_image( $logo_id, $atts['size'], false, array(
            'class' => $atts['class'],
            'alt'   => $atts['alt']
        ) );
    }
    else {
        $logo_html = get_option( 'blogname' );
    }

    $logo_html = '<div class="site-logo">' . $logo_html . '</div>';
    $logo_html = apply_filters( 'bd_wordpress_shortcode/logo', $logo_html );

    if ( $atts['echo'] === true ) {
        echo $logo_html;
    }

    return $logo_html;
}


add_shortcode( 'title', __NAMESPACE__ .'\shortcode_title' );
/**
 * Outputs the title of the current post.
 *
 * Usage: [title]
 *
 * @since 1.0.0
 *
 * @param array $atts
 * @param string|null $content
 * @return string|null
 */
function shortcode_title(array $atts, string $content = null ): string|null
{
    return get_the_title();
}


add_shortcode( 'archive-title', __NAMESPACE__ .'\shortcode_archive_title' );
/**
 * Outputs the title of the current post.
 *
 * Usage: [archive-title]
 *
 * @since 2.2.0
 *
 * @param array $atts
 * @param string|null $content
 * @return string|null
 */
function shortcode_archive_title(array $atts, string $content = null ): string|null
{
    return get_the_archive_title();
}