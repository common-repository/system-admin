<?php
/**
 * Admin menu page: PromoteSelf
 */
class JMSA_AdminMenuPage_PromoteSelf
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Actions
		add_action('admin_menu', array(&$this, 'action_admin_menu'));
	}
	
	/************************
	 * Actions
	 ***********************/
	
	/**
	 * Action: admin_menu
	 *
	 * Add menu pages
	 */
	public function action_admin_menu()
	{
		global $menu;
		
		// Add menu page
		add_menu_page(__('System Admin: Promote Self', 'systemadmin'), __('Promote to Sysadmin', 'systemadmin'), 'promote_users', 'systemadmin_promoteself', array(&$this, 'page'));
		
		// Remove menu page from visible menu
		foreach ($menu as $index => $menuitem) {
			if ($menuitem[2] == 'systemadmin_promoteself') {
				unset($menu[$index]);
			}
		}
	}
	
	/************************
	 * Menu pages
	 ***********************/
	
	/**
	 * Hanlde and display page
	 */
	public function page()
	{
		// Helpers
		require_once JMSA_LIBRARY_PATH . '/helper/roles.php';
		
		// Get logged in user
		$current_user = wp_get_current_user();
		
		// Whether the user was promoted
		$promoted = false;
		
		if ($current_user->ID && JMSA_Helper_Roles::role_exists('sysadmin') && current_user_can('promote_users')) {
			// Promote self
			$user = new WP_User($current_user->ID);
			$user->set_role('sysadmin');
			
			// Promoted successfully
			$promoted = true;
		}
	?>
		<div class="wrap">
			<h2><?php _e('System Admin: Promote Self', 'systemadmin'); ?></h2>
			
			<?php if ($promoted) : ?>
				<div class="updated">
					<p>
						<?php
						printf(__('You have been successfully promoted to &quot;Sysadmin&quot;. The System Admin plugin is now fully functional.', 'systemadmin'),
							'<a href="" title="' . __('Refresh') . '">',
							'</a>'
						);
						?>
					</p>
				</div>
			<?php endif; ?>
		</div>
	<?php
	}

}

new JMSA_AdminMenuPage_PromoteSelf();
?>