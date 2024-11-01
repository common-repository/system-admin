<?php
/**
 * Helper: Roles
 *
 * This helper adds functionality for managing user roles and capabilities, as well as
 * functionality for accessing roles and capabilities.
 */
class JMSA_Helper_Roles
{

	/**
	 * Get a list of all available roles from the WP_Roles object
	 *
	 * @return array Available roles
	 */
	public static function get_roles()
	{
		global $wp_roles;
		
		if (!is_object($wp_roles)) {
			$wp_roles = new WP_Roles();
		}
		
		return $wp_roles->roles;
	}
	
	/**
	 * Check whether a role with a specific ID exists
	 *
	 * @param string $roleid Role ID to check
	 * @return bool Whether the role exists
	 */
	public static function role_exists($roleid)
	{
		$roles = self::get_roles();
		
		foreach ($roles as $index => $role) {
			if ($index == $roleid) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Check whether any system admins exist
	 *
	 * @return bool Whether there is at least one user with the sysadmin role
	 */
	public static function sysadmin_users_exist()
	{
		$sysadminusers_query = new WP_User_Query(array('role' => 'sysadmin'));
		
		return $sysadminusers_query->get_total();
	}
	
	/**
	 * Get the highest hierarchy role for a user
	 *
	 * @param int $userid ID of the user to get the role for
	 * @return string User role name
	 */
	public static function get_user_role($userid)
	{
		$userid = intval($userid);
		
		if (!$userid) {
			return false;
		}
		
		$user = get_user_by('id', $userid);
		
		return array_shift($user->roles);
	}

}
?>