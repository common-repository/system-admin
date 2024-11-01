<?php
require_once JMSA_LIBRARY_PATH . '/adminmenupage/options.php';
require_once JMSA_LIBRARY_PATH . '/adminmenupage/plugins.php';
require_once JMSA_LIBRARY_PATH . '/adminmenupage/adminmenu.php';
require_once JMSA_LIBRARY_PATH . '/adminmenupage/promoteself.php';

class JMSA_AdminMenu
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Actions
		add_action('admin_menu', array(&$this, 'action_admin_menu'), 9999, 1);
	}
	
	/************************
	 * Actions
	 ***********************/
	
	/**
	 * Action: admin_menu
	 */
	public function action_admin_menu()
	{
		global $menu, $submenu;
		
		// Save original menu and submenu
		$this->menu_original = $menu;
		$this->submenu_original = $submenu;
		
		$this->handle_hidden_menuitems();
	}
	
	/************************
	 * Main functionality
	 ***********************/
	
	/**
	 * Handle the visibility of menu items based on the user role
	 */
	public function handle_hidden_menuitems()
	{
		global $menu, $submenu;
		
		// Helpers
		require_once JMSA_LIBRARY_PATH . '/helper/roles.php';
		require_once JMSA_LIBRARY_PATH . '/helper/strings.php';
		
		
		// Check if the current user can view sysadmin-only menu items
		if (current_user_can('view_sysadmin_menu_items')) {
			return;
		}
		
		// Get hidden menu items
		$hiddenmenuitems = get_option('jmsa_menuitems_hidden', array());
		$hiddenmenuitems[] = 'systemadmin';
		
		// Menu items
		foreach ($this->menu_original as $index => $menuitem) {
			$fullslug = JMSA_Helper_Strings::esc_attr_id($menuitem[2]);
			
			if (in_array($fullslug, $hiddenmenuitems)) {
				unset($menu[$index]);
			}
		}
		
		// Submenu items
		foreach ($this->submenu_original as $index => $submenuitems) {
			foreach ($submenuitems as $index2 => $submenuitem) {
				$fullslug = JMSA_Helper_Strings::esc_attr_id($index . '-' . $submenuitem[2]);
				
				if (in_array($fullslug, $hiddenmenuitems) || $index == 'systemadmin') {
					unset($submenu[$index][$index2]);
				}
			}
		}
	}

}

$JMSA_AdminMenu = new JMSA_AdminMenu();
?>