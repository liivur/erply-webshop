<?php
/**
 * Plugin Name: Erply webshop
 * Plugin URI: http://www.newtime.ee/
 * Description: Plugin to sync products from erply.
 * Version: 1.0.0
 * Author: Newtime
 * Author URI: http://www.newtime.ee/
 * License: GPL3
 */

class ErplyWebshop {
	function __construct()
	{
		// Register admin styles and scripts
		// add_action('admin_print_styles', array($this, 'register_admin_styles'));
		// add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'));

		// Register frontend styles
		// add_action('wp_enqueue_scripts', array($this, 'register_frontend_styles'));
		// add_action('wp_enqueue_scripts', array($this, 'register_frontend_scripts'));

		// Register menu
		add_action('admin_menu', array($this, 'register_plugin_menu'));

		// Setup deactivation for cleaning up after plugin
		// register_deactivation_hook(__FILE__, array($this, 'deactivate'));
		// register_activation_hook(__FILE__, array($this, 'activate'));

		add_filter('template_include', array($this, 'ew_set_template'));
	}

	public function register_plugin_menu() {

		add_menu_page('Erply Webshop', 'Erply Webshop', 'manage_options', 'erply-webshop', array($this, 'route'), null, null);
		add_submenu_page('erply-webshop', 'Settings', 'Settings', 'manage_options', 'erply-settings', array($this, 'view_settings'));
	}

	public function route()
	{
		if (!empty($_REQUEST['action'])) {
			switch ($_REQUEST['action']) {
				case 'sync':
					$this->synchronize();
					break;
				case 'test':
					$this->test();
			}
		} else $this->view_index();
	}

	public function view_index()
	{
		echo 'index';
		?>

		<button>Sync products</button>
		<?php
	}

	public function view_settings()
	{
		echo 'settings';
	}

	public function synchronize()
	{
		echo 'syncime';
	}

	public function test()
	{
		echo 'testime';
	}

	function ew_set_template($template){
		//Check if the taxonomy is being viewed 
		if (is_tax('event-venue') && !$this->is_template($template)) {
			$template = plugin_dir_url(__FILE__ ).'templates/taxonomy-event-venue.php';
		}

		return $template;
	}

	function is_template( $template_path ){

		//Get template name
		$template = basename($template_path);

		//Check if template is taxonomy-event-venue.php
		//Check if template is taxonomy-event-venue-{term-slug}.php
		if( 1 == preg_match('/^taxonomy-event-venue((-(\S*))?).php/',$template) )
			 return true;

		return false;
	}
}

$root = new ErplyWebshop;

?>