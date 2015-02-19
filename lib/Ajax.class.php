<?php
class EWAjax 
{
	private static $instance;

	private function __construct()
    {
    	add_action('wp_ajax_nopriv_ew_test', array($this, 'ew_test'));
    	add_action('wp_ajax_nopriv_ew_get', array($this, 'ew_get'));
    	add_action('wp_ajax_nopriv_ew_post', array($this, 'ew_post'));
    	add_action('wp_ajax_nopriv_ew_put', array($this, 'ew_put'));
    	add_action('wp_ajax_nopriv_ew_delete', array($this, 'ew_delete'));
    	add_action('wp_ajax_ew_test', array($this, 'ew_test'));
    }

    public static function get_instance()
    {
        if (null == self::$instance)
            self::$instance = new self;
        return self::$instance;
    }

    public function ew_test() 
    {
    	// $this->validate();
    	// add_post_meta(1, 'erply_id', 16);
    	// delete_post_meta(1, 'erply_id');
    	echo get_post_meta(1, 'erply_id', true);

    	$options = get_option('ew_connection_settings');
    	echo $options['token'];
    	die();
    }

    public function ew_get() 
    {
    	$this->validate();
    	if (isset($_POST['id'])) {
    		$post = $this->get_ew_post($_POST['id']);
    		if ($post) {
    			echo json_encode($post);
    		} else {
    			http_response_code(404);
    		}
    	} else {
    		http_response_code(500);
    	}
    	die();
    }

    public function ew_post() 
    {
    	$this->validate();
    	if (isset($_POST['data'])) {
	    	$data = $_POST['data'];
	    	$data['post_type'] = 'product';
	    	$data['post_status'] = 'publish';
	    	$id = wp_insert_post($data);
	    	if ($id) {
	    		if (isset($data['ecommerce_id'])) {
	    			add_post_meta( $id, 'ecommerce_id', $data['ecommerce_id'], true ) || update_post_meta( $id, 'ecommerce_id', $data['ecommerce_id'] );
	    		}
	    		if (isset($data['categories'])) {
	    			wp_set_post_terms($id, $data['categories'], 'product-category');
	    		}
	    		$post = $this->get_ew_post($id);
	    		echo json_encode($post);
	    	} else {
	    		http_response_code(500);
	    	}
    	} else {
    		http_response_code(500);
    	}
    	die();
    }

    public function ew_put() 
    {
    	$this->validate();
    	if (isset($_POST['data'])) {
    		$data = $_POST['data'];
    		$id = wp_update_post($data);
    		if ($id) {
    			$post = $this->get_ew_post($id);
	    		echo json_encode($post);
    		} else {
	    		http_response_code(500);
	    	}
    	} else {
    		http_response_code(500);
    	}
    	die();
    }

    public function ew_delete() 
    {
    	$this->validate();
    	if (isset($_POST['id'])) {
    		if (wp_delete_post($_POST['id'])) {
    			die(0);
    		} else {
    			http_response_code(404);
    		}
    	} else {
    		http_response_code(500);
    	}
    	die(0);
    }

    public function get_ew_post($id) {
    	$post = get_post($id, ARRAY_A);
    	if ($post) {
    		$post['ecommerce_id'] = get_post_meta($id, 'ecommerce_id', true);
    		$categories = wp_get_post_terms($id, 'product-category');
    		$post['categories'] = array();
    		foreach ($categories as $category) {
    			$post['categories'][] = $category->term_id;
    		}
    		return $post;
    	}
    	return false;
    }

    public function validate() {
    	if (isset($_SERVER['HTTP_X_API_TOKEN'])) {
	    	$token = $_SERVER['HTTP_X_API_TOKEN'];
	    	$options = get_option('ew_connection_settings');
	    	if ($options['token'] == $token) {
	    		return;
	    	}
    	}
    	http_response_code(403);
    	die();
    }
}