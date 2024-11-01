<?php
/**
 * Handle plugin updates
 */
function jmsa_handle_update()
{
	$db_version = get_option('jmsa_version');
	
	if (!$db_version || version_compare($db_version, '0.4.1') < 0) {
		$role = get_role('sysadmin');
		$role->add_cap('administrator', true);
		
		$sysadminusers_query = new WP_User_Query(array('role' => 'sysadmin'));
		
		foreach ($sysadminusers_query->results as $index => $user) {
			clean_user_cache($user);
		}
	}
	
	if ($db_version != JMSA_VERSION) {
		update_option('jmsa_version', JMSA_VERSION);
	}
}

// Actions
add_action('init', 'jmsa_handle_update');

if (!function_exists('jmsa_maybe_disable_adminbar_frontend')) {
	/**
	 * Disable the admin bar if that option is set
	 */
	function jmsa_maybe_disable_adminbar_frontend()
	{
		$options = get_option('jmsa_options');
		
		if ( !current_user_can( 'view_sysadmin_content' ) && !empty( $options['disable_adminbar_frontend'] ) ) {
			show_admin_bar(false);
		}
	}
}

// Actions
add_action('plugins_loaded', 'jmsa_maybe_disable_adminbar_frontend');
?>