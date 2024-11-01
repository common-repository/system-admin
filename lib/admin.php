<?php
/**
 */
class JMSA_Admin
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Actions
		add_action('admin_init', array(&$this, 'action_admin_init'));
		add_action('admin_enqueue_scripts', array(&$this, 'action_admin_enqueue_scripts'));
		add_action('admin_notices', array(&$this, 'action_admin_notices'));

		// Filters
		add_filter('all_plugins', array(&$this, 'filter_all_plugins'));
		add_filter('user_has_cap', array(&$this, 'filter_user_has_cap'), 999, 3);
		add_filter('editable_roles', array(&$this, 'filter_editable_roles'), 999);
	}

	/************************
	 * Actions
	 ***********************/

	/**
	 * Action: admin_init
	 */
	public function action_admin_init()
	{
		$this->handle_settings();
	}

	/**
	 * Action: admin_enqueue_scripts
	 */
	public function action_admin_enqueue_scripts()
	{
		// Styles
		wp_register_style('jmsa_admin', JMSA_URL . '/public/css/admin.css');
		wp_enqueue_style('jmsa_admin');

		// Scripts
		wp_register_script('jmsa-admin', JMSA_URL . '/public/js/admin.js', array('jquery'));
		wp_enqueue_script('jquery');
		wp_enqueue_script('jmsa-admin');
	}

	/**
	 * Action: admin_notices
	 *
	 * Handle the notices that should be displayed on all admin pages
	 */
	public function action_admin_notices()
	{
		if (isset($_GET['page']) && $_GET['page'] == 'systemadmin_promoteself') {
			return;
		}

		// Helpers
		require_once JMSA_LIBRARY_PATH . '/helper/roles.php';
		require_once JMSA_LIBRARY_PATH . '/helper/strings.php';

		if (current_user_can('promote_users') && !JMSA_Helper_Roles::sysadmin_users_exist()) {
			if (empty($sysadminusers)) {
			?>
				<div class="error">
					<p>
						<?php
						printf(__('No users have been assigned the &quot;Sysadmin&quot; role yet. Please go to the %s page and assign the Sysadmin role to a user, or %spromote yourself%s to &quot;Sysadmin&quot;.', 'systemadmin'),
						'<a href="' . get_admin_url(NULL, 'users.php') . '" title="' . esc_attr__('Users') . '">' . esc_html__('Users') . '</a>',
						'<a href="' . get_admin_url(NULL, 'admin.php?page=systemadmin_promoteself') . '" title="' . esc_attr__('Promote') . '">',
						'</a>'
						);
						?>
					</p>
				</div>
			<?php
			}
		}
	}

	/************************
	 * Filters
	 ***********************/

	/**
	 * Filter: all_plugins
	 */
	public function filter_all_plugins(array $plugins)
	{
		// Helpers
		require_once JMSA_LIBRARY_PATH . '/helper/roles.php';
		require_once JMSA_LIBRARY_PATH . '/helper/strings.php';

		// Check if the current user can view sysadmin-only plugins
		if (current_user_can('view_sysadmin_plugins') || !JMSA_Helper_Roles::sysadmin_users_exist()) {
			return $plugins;
		}

		// Get hidden plugins
		$hiddenplugins = get_option('jmsa_plugins_hidden', array());

		foreach ($plugins as $index => $plugin) {
			$fullslug = JMSA_Helper_Strings::esc_attr_id($index);

			if (in_array($fullslug, $hiddenplugins)) {
				unset($plugins[$index]);
			}
		}

		return $plugins;
	}

	/**
	 * Filter: editable_roles
	 */
	public function filter_editable_roles($roles)
	{
		$options = get_option('jmsa_options');

		if ( !empty( $options['prevent_sysadminuser_edit'] ) && isset( $roles['sysadmin'] ) ) {
			$user = wp_get_current_user();

			if (is_object($user) && !empty($user->ID)) {
				if (JMSA_Helper_Roles::get_user_role($user->ID) != 'sysadmin') {
					unset($roles['sysadmin']);
				}
			}
		}

		return $roles;
	}


	/**
	 * Filter: user_has_cap
	 */
	public function filter_user_has_cap($allcaps, $caps, $args)
	{
		$options = get_option('jmsa_options');

		if ( empty( $options['prevent_sysadminuser_edit'] ) ) {
			return $allcaps;
		}

		// Check role
		$user = wp_get_current_user();

		if (!is_object($user) || empty($user->ID) || JMSA_Helper_Roles::get_user_role($user->ID) == 'sysadmin') {
			return $allcaps;
		}

		// Make sure the requested capability and the user ID are set
		if ( empty( $args[0] ) || !in_array($args[0], array('edit_user', 'delete_user')) || empty( $args[1] ) || empty( $args[2] ) ) {
			return $allcaps;
		}

		$access = JMSA_Helper_Roles::get_user_role($args[2]) == 'sysadmin' ? false : true;

		foreach ($caps as $index => $cap) {
			$allcaps[$cap] = $access;
		}

		return $allcaps;
	}

	/************************
	 * Main functionality
	 ***********************/

	/**
	 * Handle WordPress settings API functionality
	 */
	public function handle_settings()
	{
		register_setting('jmsa_options', 'jmsa_options');
	}

}

new JMSA_Admin();
?>
