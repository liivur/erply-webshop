<?php
class EWSync
{
	function __construct() {
		
	}

	public function syncCategories()
	{
		# code...
	}

	public function syncProducts()
	{
		if (isset($_POST['products'])) {
			foreach ($_POST['products'] as $product) {

			}
		}	
	}

	public function saveProduct() 
	{
		$args = array(
			
		);
		wp_insert_post();
	}
}