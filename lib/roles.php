<?php
/**
 * Roles class
 */
class JMSA_Roles
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->handle_roles();

		// Filters
		add_filter( 'user_has_cap', array( &$this, 'filter_user_has_cap' ), 10, 3 );
	}

	/************************
	 * Main functionality
	 ***********************/

	/**
	 * Add the system admin role and capabilities if it doesn't exist yet
	 */
	public function handle_roles()
	{
		// Helpers
		require_once JMSA_LIBRARY_PATH . '/helper/roles.php';

		// Only try to add the role if it doesn't exist already
		if ( !JMSA_Helper_Roles::role_exists( 'sysadmin' ) ) {
			$roles = JMSA_Helper_Roles::get_roles();

			// Get all available capabilities
			$capabilities = array();

			foreach ( $roles as $index => $role ) {
				$capabilities = array_merge($capabilities, $role['capabilities']);
			}

			// Enable all capabilities
			$capabilities = array_fill_keys( array_keys( $capabilities ), true );

			// Add the user role
			$role_sysadmin = add_role( 'sysadmin', 'Sysadmin', $capabilities );
			$role_sysadmin->add_cap( 'view_sysadmin_menu_items', true );
			$role_sysadmin->add_cap( 'view_sysadmin_plugins', true );
			$role_sysadmin->add_cap( 'sysadmin_updates', true );
			$role_sysadmin->add_cap( 'view_sysadmin_content', true );
			$role_sysadmin->add_cap( 'administrator', true );
		}
		else if ( JMSA_Helper_Roles::role_exists( 'administrator' ) ) {
			$role_admin = get_role( 'administrator' );
			$role_sysadmin = get_role( 'sysadmin' );

			foreach ( $role_admin->capabilities as $index => $cap ) {
				if ( $cap && !isset( $role_sysadmin->capabilities[ $index ] ) ) {
					$role_sysadmin->add_cap( $index, true );
				}
			}
		}
	}

	/************************
	 * Filters
	 ***********************/

	/**
	 * Filter: user_has_cap
	 */
	public function filter_user_has_cap($capabilities, $cap, $name)
	{
		if ( empty( $capabilities['sysadmin_updates'] ) ) {
			if ( !empty( $capabilities['update_core'] ) || !empty( $capabilities['update_plugins'] ) || !empty( $capabilities['update_themes'] ) ) {
				$options['general'] = get_option('jmsa_options');
			}

			if ( !empty( $options['general']['disable_core_update'] ) && !empty( $capabilities['update_core'] ) ) {
				$capabilities['update_core'] = false;
			}

			if ( !empty( $options['general']['disable_plugin_update'] ) && !empty( $capabilities['update_plugins'] ) ) {
				$capabilities['update_plugins'] = false;
			}

			if ( !empty( $options['general']['disable_theme_update'] ) && !empty( $capabilities['update_themes'] ) ) {
				$capabilities['update_themes'] = false;
			}
		}

		return $capabilities;
	}

}

new JMSA_Roles();
?>
