<?php
/**
 * Admin menu page: Options
 */
class JMSA_AdminMenuPage_Options
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Actions
		add_action('admin_menu', array(&$this, 'action_admin_menu'));
		add_action('admin_init', array(&$this, 'action_admin_init'));
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
		add_menu_page(__('System Admin Settings', 'systemadmin'), __('System Admin', 'systemadmin'), 'manage_options', 'systemadmin', array(&$this, 'page'));
	}

	/**
	 * Action: admin_init
	 */
	public function action_admin_init()
	{
		// Settings sections
		add_settings_section('jmsa_users', __('Users', 'systemadmin'), array(&$this, 'section_users'), 'systemadmin');
		add_settings_section('jmsa_updates', __('Updates', 'systemadmin'), array(&$this, 'section_updates'), 'systemadmin');
		add_settings_section('jmsa_adminbar', __('Admin bar', 'systemadmin'), array(&$this, 'section_adminbar'), 'systemadmin');

		// Settings fields
		add_settings_field('jmsa_prevent_sysadminuser_edit', __('Actively prevent system admin editing', 'systemadmin'), array(&$this, 'field_prevent_sysadminuser_edit'), 'systemadmin', 'jmsa_users');
		add_settings_field('jmsa_disable_core_update', __('Disable core update', 'systemadmin'), array(&$this, 'field_disable_core_update'), 'systemadmin', 'jmsa_updates');
		add_settings_field('jmsa_disable_plugin_update', __('Disable plugin update', 'systemadmin'), array(&$this, 'field_disable_plugin_update'), 'systemadmin', 'jmsa_updates');
		add_settings_field('jmsa_disable_theme_update', __('Disable theme update', 'systemadmin'), array(&$this, 'field_disable_theme_update'), 'systemadmin', 'jmsa_updates');
		add_settings_field('jmsa_disable_adminbar_frontend', __('Disable admin bar', 'systemadmin'), array(&$this, 'field_disable_adminbar_frontend'), 'systemadmin', 'jmsa_adminbar');
	}

	/************************
	 * Settings sections
	 ***********************/

	public function section_users() {}
	public function section_updates() {}
	public function section_adminbar() {}

	/************************
	 * Settings fields
	 ***********************/

	/**
	 * Field: Actively prevent system admin editing
	 */
	public function field_prevent_sysadminuser_edit()
	{
		$options = get_option('jmsa_options');
	?>
		<input type="checkbox" name="jmsa_options[prevent_sysadminuser_edit]" value="1" <?php checked( !empty( $options['prevent_sysadminuser_edit'] ) ); ?> />
		<span class="description">
			<?php _e('By checking this box, you can prevent anyone that does not have the sysadmin role from creating, editing or deleting users with the sysadmin role.', 'systemadmin'); ?>
			<br/>
			<?php _e('<strong>Plugin/theme sysadmins only</strong>: To change these permissions per role, please leave this box unchecked and use the <code>editable_roles</code> filter.', 'systemadmin'); ?>
		</span>
	<?php
	}

	/**
	 * Field: Disable core update
	 */
	public function field_disable_core_update()
	{
		$options = get_option('jmsa_options');
	?>
		<input type="checkbox" name="jmsa_options[disable_core_update]" value="1" <?php checked( !empty( $options['disable_core_update'] ) ); ?> />
	<?php
	}

	/**
	 * Field: Disable plugin update
	 */
	public function field_disable_plugin_update()
	{
		$options = get_option('jmsa_options');
	?>
		<input type="checkbox" name="jmsa_options[disable_plugin_update]" value="1" <?php checked( !empty( $options['disable_plugin_update'] ) ); ?> />
	<?php
	}

	/**
	 * Field: Disable theme update
	 */
	public function field_disable_theme_update()
	{
		$options = get_option('jmsa_options');
	?>
		<input type="checkbox" name="jmsa_options[disable_theme_update]" value="1" <?php checked( !empty( $options['disable_theme_update'] ) ); ?> />
	<?php
	}

	/**
	 * Field: Disable admin bar
	 */
	public function field_disable_adminbar_frontend()
	{
		$options = get_option('jmsa_options');
	?>
		<input type="checkbox" name="jmsa_options[disable_adminbar_frontend]" value="1" <?php checked( !empty( $options['disable_adminbar_frontend'] ) ); ?> />
	<?php
	}

	/************************
	 * Main functionality
	 ***********************/

	/**
	 * Handle and display page
	 */
	public function page()
	{
	?>
		<div class="wrap">
			<h2><?php _e('System Admin: Settings', 'systemadmin'); ?></h2>

			<p><?php _e('Disable general options for any user that doesn&#39;t have sysadmin access.', 'systemadmin'); ?></p>

			<form action="options.php" method="post">
				<?php settings_fields('jmsa_options'); ?>
				<?php do_settings_sections('systemadmin'); ?>
				<?php submit_button(); ?>
			</form>
		</div>
	<?php
	}

}

new JMSA_AdminMenuPage_Options();
?>
