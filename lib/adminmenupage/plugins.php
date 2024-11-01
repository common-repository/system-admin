<?php
/**
 * Admin menu page: Plugins
 */
class JMSA_AdminMenuPage_Plugins
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
		add_submenu_page('systemadmin', __('System Admin: Plugins', 'systemadmin'), __('Plugins', 'systemadmin'), 'manage_options', 'systemadmin_plugins', array(&$this, 'page'));
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
		// Helpers
		require_once JMSA_LIBRARY_PATH . '/helper/strings.php';

		// Form submitted
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// Currently hidden menu items
			$hiddenplugins = get_option('jmsa_plugins_hidden', array());

			if (!is_array($hiddenplugins)) {
				$hiddenplugins = array();
			}

			// Array with menu items and submenu items
			$plugins = get_plugins();

			// As unchecked checkboxes are not sent by the browser, we reset the hidden setting for every available menu item so they are not hidden by default
			foreach ($plugins as $index => $plugin) {
				$fullslug = JMSA_Helper_Strings::esc_attr_id($index);

				if (($key = array_search($fullslug, $hiddenplugins)) !== false) {
					unset($hiddenplugins[$key]);
				}
			}

			// Set hidden menu items
			foreach ($_POST as $index => $postdata) {
				$searchstring = 'jmsa-plugin-hidden-';
				//$searchstring_length = strlen($searchstring);

				if (substr($index, 0, strlen($searchstring)) == $searchstring) {
					$hiddenplugins[] = substr(sanitize_text_field($index), strlen($searchstring));
				}
			}

			$hiddenplugins = array_values(array_unique($hiddenplugins));

			// Save
			update_option('jmsa_plugins_hidden', $hiddenplugins);

			// Successfully updated
			$this->updated = true;
		}
	}

	/**
	 * Output the contents of the page
	 */
	public function display()
	{
		// Helpers
		require_once JMSA_LIBRARY_PATH . '/helper/strings.php';

		// Get all plugins
		$plugins = get_plugins();

		// Get current hidden plugins
		$option_hiddenplugins = get_option('jmsa_plugins_hidden', array());
		?>

		<div class="wrap">
			<h2><?php _e('System Admin: Plugins', 'systemadmin'); ?></h2>

			<?php if ($this->updated) : ?>
				<div class="updated">
					<p><?php printf(__('The hidden plugins were successfully updated.', 'systemadmin'), '<a href="" title="' . __('Refresh') . '">', '</a>'); ?></p>
				</div>
			<?php endif; ?>

			<form action="" method="post">
				<h3><?php _e('Plugin visibility', 'systemadmin'); ?></h3>
				<p><?php _e('You have the option to hide plugins from being displayed on the plugins page when a non-sysadmin is logged in. Please check the boxes for the plugins that you want to hide from the plugin overview when anybody outside the Sysadmin user group (for example, your client) is logged in.', 'systemadmin'); ?></p>

				<p>
					<a href="#jmsa-plugins-plugins" class="jmsa-list-checkall" title="<?php esc_attr_e('Check all', 'systemadmin'); ?>"><?php _e('Check all', 'systemadmin'); ?></a>
					-
					<a href="#jmsa-plugins-plugins" class="jmsa-list-uncheckall" title="<?php esc_attr_e('Uncheck all', 'systemadmin'); ?>"><?php _e('Uncheck all', 'systemadmin'); ?></a>
					-
					<a href="#jmsa-plugins-plugins" class="jmsa-list-toggleall" title="<?php esc_attr_e('Toggle all', 'systemadmin'); ?>"><?php _e('Toggle all', 'systemadmin'); ?></a>
				</p>

				<ul id="jmsa-plugins-plugins">

				<?php
				foreach ($plugins as $index => $plugin) {
					$fullslug = JMSA_Helper_Strings::esc_attr_id($index);
				?>
					<li>
						<label for="jmsa-plugin-hidden-<?php echo $fullslug; ?>">
							<input type="checkbox" name="jmsa-plugin-hidden-<?php echo $fullslug; ?>" id="jmsa-plugin-hidden-<?php echo $fullslug; ?>" <?php checked(true, in_array($fullslug, $option_hiddenplugins)); ?> />
							<span class="jmsa-plugin-title"><?php echo $plugin['Name']; ?></span>
						</label>
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

new JMSA_AdminMenuPage_Plugins();
?>
