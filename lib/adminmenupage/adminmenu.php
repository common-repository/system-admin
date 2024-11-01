<?php
/**
 * Admin menu page: Admin Menu
 */
class JMSA_AdminMenuPage_AdminMenu
{

	/**
	 * Whether the plugins were updated
	 */
	public $updated = false;

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
		add_submenu_page('systemadmin', __('System Admin: Admin Menu', 'systemadmin'), __('Admin Menu', 'systemadmin'), 'manage_options', 'systemadmin_adminmenu', array(&$this, 'page'));
	}

	/************************
	 * Main functionality
	 ***********************/

	/**
	 * Hanlde and display page
	 */
	public function page()
	{
		$this->handle();
		$this->display();
	}

	/**
	 * Handle business logic for this page
	 */
	public function handle()
	{
		global $JMSA_AdminMenu;

		// Helpers
		require_once JMSA_LIBRARY_PATH . '/helper/strings.php';
		require_once JMSA_LIBRARY_PATH . '/helper/adminmenu.php';

		// Form submitted
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// Currently hidden menu items
			$hiddenmenuitems = get_option('jmsa_menuitems_hidden', array());

			if (!is_array($hiddenmenuitems)) {
				$hiddenmenuitems = array();
			}

			// Array with menu items and submenu items
			//$menuitems = JMSA_Helper_AdminMenu::merge_menuitems($JMSA_AdminMenu->menu_original, $JMSA_AdminMenu->submenu_original);

			$JMSA_Helper_AdminMenu = new JMSA_Helper_AdminMenu();
			$menuitems = $JMSA_Helper_AdminMenu->merge_menuitems($JMSA_AdminMenu->menu_original, $JMSA_AdminMenu->submenu_original);

			// As unchecked checkboxes are not sent by the browser, we reset the hidden setting for every available menu item so they are not hidden by default
			foreach ($menuitems as $index => $menuitem) {
				$fullslug = JMSA_Helper_Strings::esc_attr_id($menuitem->slug);

				if (($key = array_search($fullslug, $hiddenmenuitems)) !== false) {
					unset($hiddenmenuitems[$key]);
				}

				foreach ($menuitem->submenuitems as $index => $submenuitem) {
					$fullslug = JMSA_Helper_Strings::esc_attr_id($menuitem->slug . '-' . $submenuitem->slug);

					if (($key = array_search($fullslug, $hiddenmenuitems)) !== false) {
						unset($hiddenmenuitems[$key]);
					}
				}
			}

			// Set hidden menu items
			foreach ($_POST as $index => $postdata) {
				$searchstring = 'jmsa-menuitem-hidden-';
				//$searchstring_length = strlen($searchstring);

				if (substr($index, 0, strlen($searchstring)) == $searchstring) {
					$hiddenmenuitems[] = substr(sanitize_text_field($index), strlen($searchstring));
				}
			}

			$hiddenmenuitems = array_values(array_unique($hiddenmenuitems));

			// Save
			update_option('jmsa_menuitems_hidden', $hiddenmenuitems);

			// Successfully updated
			$this->updated = true;
		}
	}

	/**
	 * Output the contents of the page
	 */
	public function display()
	{
		global $JMSA_AdminMenu;

		// Helpers
		require_once JMSA_LIBRARY_PATH . '/helper/strings.php';
		require_once JMSA_LIBRARY_PATH . '/helper/adminmenu.php';

		// Get all menu items
		//$menuitems = JMSA_Helper_AdminMenu::merge_menuitems($JMSA_AdminMenu->menu_original, $JMSA_AdminMenu->submenu_original);

		$JMSA_Helper_AdminMenu = new JMSA_Helper_AdminMenu();
		$menuitems = $JMSA_Helper_AdminMenu->merge_menuitems($JMSA_AdminMenu->menu_original, $JMSA_AdminMenu->submenu_original);

		// Get current hidden menu items
		$option_hiddenmenuitems = get_option('jmsa_menuitems_hidden', array());
		?>

		<div class="wrap">
			<h2><?php _e('System Admin: Admin Menu', 'systemadmin'); ?></h2>

			<?php if ($this->updated) : ?>
				<div class="updated">
					<p><?php printf(__('The hidden menu items were successfully updated.', 'systemadmin'), '<a href="" title="' . __('Refresh') . '">', '</a>'); ?></p>
				</div>
			<?php endif; ?>

			<form action="" method="post">
				<h3><?php _e('Menu item visibility', 'systemadmin'); ?></h3>
				<p><?php _e('You have the option to hide menu items from being displayed when a non-sysadmin is logged in. Please check the boxes for the pages that you want to hide when anybody outside the Sysadmin user group (for example, your client) is logged in.', 'systemadmin'); ?></p>

				<p>
					<a href="#jmsa-adminmenu-menuitems" class="jmsa-list-checkall" title="<?php esc_attr_e('Check all', 'systemadmin'); ?>"><?php _e('Check all', 'systemadmin'); ?></a>
					-
					<a href="#jmsa-adminmenu-menuitems" class="jmsa-list-uncheckall" title="<?php esc_attr_e('Uncheck all', 'systemadmin'); ?>"><?php _e('Uncheck all', 'systemadmin'); ?></a>
					-
					<a href="#jmsa-adminmenu-menuitems" class="jmsa-list-toggleall" title="<?php esc_attr_e('Toggle all', 'systemadmin'); ?>"><?php _e('Toggle all', 'systemadmin'); ?></a>
				</p>

				<ul id="jmsa-adminmenu-menuitems">

				<?php
				foreach ($menuitems as $index => $menuitem) {
					$fullslug = JMSA_Helper_Strings::esc_attr_id($menuitem->slug);
				?>
					<li>
						<label for="jmsa-menuitem-hidden-<?php echo $fullslug; ?>">
							<input type="checkbox" name="jmsa-menuitem-hidden-<?php echo $fullslug; ?>" id="jmsa-menuitem-hidden-<?php echo $fullslug; ?>" <?php checked(true, in_array($fullslug, $option_hiddenmenuitems)); ?> />
							<span class="jmsa-menuitem-title"><?php echo $menuitem->title; ?></span>
						</label>

						<?php
						if (!empty($menuitem->submenuitems)) {
						?>
							<ul>
								<?php
								foreach ($menuitem->submenuitems as $index => $submenuitem) {
									$fullslug = JMSA_Helper_Strings::esc_attr_id($menuitem->slug . '-' . $submenuitem->slug);
								?>
									<li>
										<label for="jmsa-menuitem-hidden-<?php echo $fullslug; ?>">
											<input type="checkbox" name="jmsa-menuitem-hidden-<?php echo $fullslug; ?>" id="jmsa-menuitem-hidden-<?php echo $fullslug; ?>" value="1" <?php checked(true, in_array($fullslug, $option_hiddenmenuitems)); ?> />
											<span class="jmsa-menuitem-title"><?php echo $submenuitem->title; ?></span>
										</label>
									</li>
								<?php
								}
								?>
							</ul>
						<?php
						}
						?>
					</li>
				<?php
				}
				?>

				</ul>

				<?php submit_button(); ?>
			</form>
		</div>

		<?php
	}

}

new JMSA_AdminMenuPage_AdminMenu();
?>
