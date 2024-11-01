<?php
/**
 * Helper: Admin Menu
 *
 * This helper adds functionality for the menu in the admin panel
 */
class JMSA_Helper_AdminMenu
{

	/**
	 * Merge the menu items of a main and submenu to form one multi-level array of all menu items
	 *
	 * This method is mainly used to combine the WordPress global menu variables $menu and $submenu.
	 * As the method is intended for the use described above, the parameter arrays should have the same
	 * structure as those variables.
	 *
	 * @since 0.1
	 *
	 * @param array $menu Main menu items
	 * @param array $submenu Submenu items
	 */
	public function merge_menuitems($menu, $submenu)
	{
		// Multi-level array for the menu items
		$menuitems = array();
		
		// Number of main menu items
		$num_menuitems = 0;
		
		// Loop through every main menu item
		foreach ($menu as $index => $menuitem) {
			if (!$menuitem[0]) {
				continue;
			}
			
			// Add main menu item
			$menuitems[$num_menuitems] = (object) array(
				'title' => $menuitem[0],
				'id' => isset($menuitem[5]) ? $menuitem[5] : '',
				'slug' => $menuitem['2'],
				'submenuitems' => array(),
				'raw' => $menuitem
			);
			
			// Loop through each submenu item of this main menu item
			if (isset($submenu[$menuitem[2]])) {
				foreach ($submenu[$menuitem[2]] as $index => $submenuitem) {
					if (!$submenuitem[0]) {
						continue;
					}
					
					// Add submenu item
					$menuitems[$num_menuitems]->submenuitems[] = (object) array(
						'title' => $submenuitem[0],
						'slug' => $submenuitem['2'],
						'raw' => $submenuitem
					);
				}
			}
			
			$num_menuitems++;
		}
		
		return $menuitems;
	}

}
?>