<?php
/*
Plugin Name: System Admin
Description: This plugin creates a new role (sysadmin), extending the admin role by offering custom privileges that enable hiding options from other users, including administrators.
Version: 1.0
Author: Jason Mcgwier
Author URI: https://mcgwier.com
License: GPLv3 or later
*/

// Plugin information
define('JMSA_VERSION', '1.0');

// Paths
define('JMSA_PATH', dirname(__FILE__));
define('JMSA_LIBRARY_PATH', JMSA_PATH . '/lib');
define('JMSA_URL', untrailingslashit(plugins_url('', __FILE__)));

// Library
require_once JMSA_LIBRARY_PATH . '/roles.php';
require_once JMSA_LIBRARY_PATH . '/functions.php';

if (is_admin()) {
	// Admin-specific library
	require_once JMSA_LIBRARY_PATH . '/admin.php';
	require_once JMSA_LIBRARY_PATH . '/adminmenu.php';
}

// Localization
load_plugin_textdomain('systemadmin', false, dirname(plugin_basename(__FILE__)) . '/languages/');

// Add Settings Action Link
function my_plugin_settings_link($links) {
  $settings_link = '<a href="admin.php?page=systemadmin">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'my_plugin_settings_link' );
?>
