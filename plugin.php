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

require_once(plugin_dir_path(__FILE__).'/loader.php');

class ErplyWebshop {
	function __construct()
	{
		// Register admin styles and scripts
		// add_action('admin_print_styles', array($this, 'register_admin_styles'));
		add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'));

		// Register frontend styles
		// add_action('wp_enqueue_scripts', array($this, 'register_frontend_styles'));
		// add_action('wp_enqueue_scripts', array($this, 'register_frontend_scripts'));
		add_action('wp_enqueue_scripts', array($this, 'register_frontend_scripts'));

		// Register menu
		add_action('admin_menu', array($this, 'register_plugin_menu'));

		add_action( 'init', array($this, 'init'));
		add_action( 'plugins_loaded', array($this, 'load_first'));
		add_filter('the_content', array($this, 'manage_content'));

		// Setup deactivation for cleaning up after plugin
		register_deactivation_hook(__FILE__, array($this, 'deactivate'));
		register_activation_hook(__FILE__, array($this, 'activate'));

		add_filter('template_include', array($this, 'ew_set_template'));
	}

	public function activate()
    {
    	//check for user rights
        if (!current_user_can('activate_plugins')) return;
   
        //security check
        $plugin = isset($_REQUEST['plugin']) ? $_REQUEST['plugin'] : '';
        check_admin_referer("activate-plugin_{$plugin}");
        
        //add pages
        $this->populate_pages();
		
		// Display activation notice and congiguration button
		$notices= get_option('deferred_admin_notices', array());
		$notices[]= array('Inventory.com plugin is activated. Some new pages were created for cart, checkout, terms of service etc. Also some new widgets were added for customer login, cart, product categories etc. You can set these up later. <strong>Right now the next step is to <a href="admin.php?page=erply-cart-settings"> configure your Inventory.com account here</a></strong>.','notice');
		update_option('deferred_admin_notices', $notices);
		
    }

	public function load_first()
    {
        EWAjax::get_instance();
    }

	public function init()
	{
		$this->settings = new EWSettings();
		//register taxonomy and custom post type
        $this->register_product_category();
        $this->register_product_post_type();
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
		// var_dump(get_post(17, ARRAY_A));
		// var_dump(get_post_custom(17));
		// var_dump(get_post_meta(17));
		// var_dump(wp_get_post_terms(17, 'product-category'));
		var_dump(get_option('ew_pages_settings'));
		echo 'index';
		echo 'ajax-url: '.admin_url( 'admin-ajax.php' );
		?>
		<button class="test-it" data-method="POST">post värki</button>
		<button class="test-it" data-method="GET">get värki</button>
		<button class="test-it" data-method="PUT">put värki</button>
		<button class="test-it" data-method="DELETE">delete värki</button>
		<button id="get-stuff">Sync products</button>
		<?php
	}

	public function synchronize()
	{
		echo 'syncime';
	}

	public function test()
	{
		echo 'testime';
	}

	/*
    ======================================================================================
        Replace content
    ======================================================================================
    */

    public function manage_content($content)
    {
        if (is_main_query() && !is_admin()) {
        	$pagesSettings = (array)get_option( 'ew_pages_settings' );

            // // Cart page
            if(is_page($pagesSettings['cartPage'])) {
            	wp_enqueue_script('ew_cart', EW_URL . '/assets/js/cart.js', array('ew_main_script'));
                include EW_PATH.'views/cart-page.php';
            }
        }

        return $content;
    }

	function ew_set_template($template){
		//Check if the taxonomy is being viewed 
		if (is_tax('product-category') && !$this->is_template($template, 'product-category')) {
			$template = EW_PATH.'templates/taxonomy-product-category.php';
		} elseif (is_singular('product') && !$this->is_template($template, 'single-product')) {
			$template = EW_PATH.'templates/single-product.php';
		} elseif (is_post_type_archive('product') && !$this->is_template($template, 'archive-product')) {
			$template = EW_PATH.'templates/archive-product.php';
		}

		return $template;
	}

	function is_template( $template_path, $slug = 'single-product'){

		//Get template name
		$template = basename($template_path);

		$check = '/^'.$slug.'((-(\S*))?).php/';
		if( 1 == preg_match($check, $template) )
			 return true;

		return false;
	}

	/*
    ======================================================================================
        Register Product PostType & Category
    ======================================================================================
    */

    public function register_product_post_type()
    {    	
        register_post_type(
            'product',
            array(
                'labels' => array(
                    'name' => 'Products',
                    'singular_name' => 'Product'
                ),
                'public' => true,
                'show_ui' => true,
                'hierarchical' => false,
                'show_in_nav_menus' => true,
                'has_archive' => 'all_products',
                'supports' => array(
                    'title',
                    'editor', // (content)
                    'author',
                    'thumbnail', // (featured image, current theme must also support post-thumbnails)
                    'excerpt',
                    'trackbacks',
                    'custom-fields',
                    'comments', // (also will see comment count balloon on edit screen)
                    'page-attributes', // (menu order, hierarchical must be true to show Parent option)
                    'post-formats' // add post formats, see Post Formats
                ),
                'menu_position' => 23
            )
        );

        
    }

	public function register_product_category(){
        $args = array(
            'hierarchical' => true,
            'labels' => array(
                'name' => 'Product categories'
            ),
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'product-category'),
            // 'rewrite' => array('slug' => pll__('Products')),
            'has_archive' => true,
        );
        register_taxonomy('product-category', array('product'), $args);
        
    }

    /*
    ======================================================================================
        Populate Pages
    ======================================================================================
    */

    public function populate_pages()
    {
        $this->create_page( 'Cart', 'cartPage');
    }

    private function create_page( $name, $setting )
    {
    	$pagesSettings = (array)get_option( 'ew_pages_settings' );
        $pageID = isset($pagesSettings[$setting]) ? $pagesSettings[$setting] : null;
        $pageCheck = !empty($pageID) ? get_page($pageID) : null;
        if(empty($pageID) || empty($pageCheck)) {
            $my_post = array(
              'post_title'    => $name,
              'post_type' => 'page',
              'post_status'   => 'publish',
              'comment_status' => 'closed'
            );
            
            $post = wp_insert_post( $my_post, true );
            if($post instanceof WP_Error) {
                var_dump($post);
            } else {
                $pagesSettings[$setting] = $post;
                update_option( 'ew_pages_settings', $pagesSettings);
            }
        }
    }

    /*
    ======================================================================================
        Enqueue Stylesheets & Scripts
    ======================================================================================
    */

    public function register_admin_scripts()
    {
    	$options = get_option('ew_connection_settings');
        $ajaxObject = array(
            'ajax_url' 	=> admin_url( 'admin-ajax.php' ), 
            'nonce' 	=> wp_create_nonce('ec-nonce'),
            'api_url'	=> $options['api_url']
        );
        wp_enqueue_script('jquery');
        wp_enqueue_script('ew_admin_script', EW_URL . '/assets/js/admin.js', 'jquery');
        wp_localize_script( 'ew_admin_script', 'ewAjaxObject', $ajaxObject);
    }

    public function register_frontend_scripts()
    {
    	$options = get_option('ew_connection_settings');
        $ajaxObject = array(
            'ajax_url'  => admin_url( 'admin-ajax.php' ), 
            'nonce'     => wp_create_nonce('ec-nonce'),
            'api_url'	=> $options['api_url']
        );
        wp_enqueue_script('jquery');
        wp_enqueue_script('mustache', EW_URL . '/vendor/js/mustache.min.js', 'jquery');
        wp_enqueue_script('jquery_mustache', EW_URL . '/vendor/js/jquery.mustache.js', 'mustache');
        wp_enqueue_script('bs_modal', EW_URL . '/vendor/js/modal.js', 'jquery');
        wp_enqueue_script('ew_main_script', EW_URL . '/assets/js/script.js', array('jquery_mustache', 'bs_modal'));
        wp_localize_script( 'ew_main_script', 'ewAjaxObject', $ajaxObject);
        if (is_tax('product-category') || is_post_type_archive('product')) {
        	wp_enqueue_script('ew_grid', EW_URL . '/assets/js/product-grid.js', array('ew_main_script'));
        	// wp_localize_script( 'ew_grid', 'ewAjaxObject', $ajaxObject);
        }
        wp_register_style('bootstrap', EW_URL.'/vendor/css/bootstrap.css');
        wp_register_style('ew_ecommerce', EW_URL.'/assets/css/ecommerce.css', array('bootstrap'));
        wp_enqueue_style('bootstrap');
        wp_enqueue_style('ew_ecommerce');
    }


    /*
    ======================================================================================
        Register Menu
    ======================================================================================
    */

	public function register_plugin_menu() {

		add_menu_page('Erply Webshop', 'Erply Webshop', 'manage_options', 'erply-webshop', array($this, 'route'), null, null);
	}
}
$root = new ErplyWebshop;