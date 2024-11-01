<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
	die();
}

// Change the role of system admins to administrator
$users = get_users(array(
	'role' => 'sysadmin'
));

foreach ($users as $index => $user) {
	wp_update_user(array(
		'ID' => $user->ID,
		'role' => 'administrator'
	));
}

// Remove the sysadmin role
remove_role('sysadmin');

// Remove options
delete_option('jmsa_plugins_hidden');
delete_option('jmsa_menuitems_hidden');
delete_option('jmsa_options');
?>