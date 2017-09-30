<?php
/*
Plugin Name: Notification Bar
Plugin URI: https://orangeblossommedia.com/
Description: Creates a notification bar on your website
Version: 1.0
Author: davidjlaietta
Author URI: https://orangeblossommedia.com/
License: GPLv3.0+
Text Domain: notification-bar
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2017 Orange Blossom Media, LLC.
*/

/**
 * Creates a link to the settings page under the WordPress Settings in the dashboard
 */
add_action( 'admin_menu', 'snb_general_settings_page' );
function snb_general_settings_page() {

    add_submenu_page(
        'options-general.php',
        __( 'Notifications Bar', 'notification-bar' ),
        __( 'Notifications', 'notification-bar' ),
        'manage_options',
        'notification_bar',
        'snb_render_settings_page'
    );

}

/**
 * Creates the settings page
 */
function snb_render_settings_page() {
    ?>
    <!-- Create a header in the default WordPress 'wrap' container -->
    <div class="wrap">

        <h2><?php _e( 'Notification Bar Settings', 'notification-bar' ); ?></h2>

        <form method="post" action="options.php">

            <?php
	        // Get settings for the plugin to display in the form
            settings_fields( 'snb_general_settings' );
            do_settings_sections( 'snb_general_settings' );

            // Form submit button
            submit_button();
            ?>

        </form>

    </div><!-- /.wrap -->
<?php
}

/**
 * Displays the header of the general settings
 */
function general_settings_callback() {
	_e( 'Notification Settings', 'notification-bar' );
}

/**
 * Text Input Callbacks
 */
function text_input_callback( $text_input ) {

    // Get arguments from setting
    $option_group = $text_input['option_group'];
    $option_id = $text_input['option_id'];
    $option_name = "{$option_group}[{$option_id}]";

    // Get existing option from database
    $options = get_option( $option_group );
    $option_value = isset( $options[$option_id] ) ? $options[$option_id] : "";

    // Render the output
    echo "<input type='text' size='50' id='{$option_id}' name='{$option_name}' value='{$option_value}' />";

}

/**
 * Checkbox Input Callbacks
 */
function radio_input_callback( $radio_input ) {

    // Get arguments from setting
    $option_group = $radio_input['option_group'];
    $option_id = $radio_input['option_id'];
    $radio_options = $radio_input['radio_options'];
    $option_name = "{$option_group}[{$option_id}]";

    // Get existing option from database
    $options = get_option( $option_group );
    $option_value = isset( $options[$option_id] ) ? $options[$option_id] : "";

    // Render the output
    $input = '';
    foreach ( $radio_options as $radio_option_id => $radio_option_value) {
	    $input .= "<input type='radio' id='{$radio_option_id}' name='{$option_name}' value='{$radio_option_id}' " . checked( $radio_option_id, $option_value, false ) . " />";
	    $input .= "<label for='{$radio_option_id}'>{$radio_option_value}</label><br />";
	}

    echo $input;

}

/**
 * Displays the notification bar on the frontend of the site
 */
add_action( 'wp_footer', 'snb_display_notification_bar' );
function snb_display_notification_bar() {

    ?>
	<div class="snb-notification-bar <?php echo get_theme_mod( 'display_location' ); ?>" style="background-color: <?php echo get_theme_mod( 'notification_bar_color' ); ?>;">
        <div class="snb-notification-text" style="color: <?php echo get_theme_mod( 'notification_text_color' ); ?>;"><?php echo get_theme_mod( 'notification_text' ); ?></div>
    </div>
    <?php

}

/**
 * Loads plugin scripts and styles
 */
add_action( 'wp_enqueue_scripts', 'snb_scripts' );
function snb_scripts() {

	wp_enqueue_style( 'snb-notification-bar',  plugin_dir_url( __FILE__ ) . 'notification-bar.css', array(), '1.0.0' );

}

/**
 * Adds body class to notification bar pages
 */
add_filter( 'body_class', 'snb_body_class' , 20 );
function snb_body_class( $classes ) {

    if ( get_theme_mod( 'display_location' ) === 'display_top' ||  get_theme_mod( 'display_location' )=== 'display_bottom' ) {

	    $classes[] = 'notification-bar';

	}

    return $classes;

}


/**
 * Adds customizer settings for notification bar
 */
add_action( 'customize_register', 'snb_customize_register' );
function snb_customize_register( WP_Customize_Manager $wp_customize ) {

    $wp_customize->add_section( 'skillshare_notification_bar', array(
        'title' => __( 'Notification Bar', 'notification-bar' ),
    ) );

    $wp_customize->add_setting( 'display_location', array(
        'capability' => 'edit_theme_options',
        'default' => 'display_none'
    ) );
    $wp_customize->add_control( 'display_location', array(
        'type' => 'radio',
        'section' => 'skillshare_notification_bar', // Add a default or your own section
        'label' => __( 'Display Location' ),
        'description' => __( 'Choose where the notification bar is displayed' ),
        'choices' => array(
            'display_none' => __( 'Do not display notification bar', 'notification-bar' ),
            'display_top' => __( 'Display notification bar on the top of the site', 'notification-bar' ),
            'display_bottom' => __( 'Display notification bar on the bottom of the site', 'notification-bar' )
        ),
    ) );

    $wp_customize->add_setting( 'notification_text', array(
        'capability' => 'edit_theme_options',
        'default' => ''
    ) );
    $wp_customize->add_control( 'notification_text', array(
        'type' => 'textarea',
        'section' => 'skillshare_notification_bar',
        'label' => __( 'Notification Text' ),
        'description' => __( 'This is the text of the notification.' ),
    ) );

    $wp_customize->add_setting( 'notification_bar_color', array(
        'default'           => '#000000',
        'sanitize_callback' => 'sanitize_hex_color',
        'capability'        => 'edit_theme_options',

    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'notification_bar_color', array(
        'label'    => __('Notification Bar Color', 'notification-bar'),
        'section'  => 'skillshare_notification_bar',
        'settings' => 'notification_bar_color',
    ) ) );

    $wp_customize->add_setting( 'notification_text_color', array(
        'default'           => '#FFFFFF',
        'sanitize_callback' => 'sanitize_hex_color',
        'capability'        => 'edit_theme_options',

    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'notification_text_color', array(
        'label'    => __('Notification Text Color', 'notification-bar'),
        'section'  => 'skillshare_notification_bar',
        'settings' => 'notification_text_color',
    ) ) );

}