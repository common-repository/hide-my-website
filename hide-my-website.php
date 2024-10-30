<?php
/*
Plugin Name: Hide My Website
Description: Hides your website behind a password prompt and manages various security features.
Version: 1.3
Author: Pranav MT
Tags: hide my site, password protection,hide my website,hide
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Include additional files

//all includes
require_once plugin_dir_path(__FILE__) . 'main.php';
//plugin settings
require_once plugin_dir_path(__FILE__) . 'admin/hmw-admin-settings.php';

require_once plugin_dir_path(__FILE__) . 'admin/hmw-settings-links.php';
//plugin core 
require_once plugin_dir_path(__FILE__) . 'core/main-core.php';


// Activation, deactivation, and uninstall hooks
register_activation_hook(__FILE__, 'hmwp_disable_crawlers_load');

register_deactivation_hook(__FILE__, 'hmwp_revert_robots_txt');

