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