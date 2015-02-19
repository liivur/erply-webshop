<?php get_header(); 
include EW_PATH.'/views/cart.php';
?>
    <main class="content js-content" role="main">
		<div class="wrap">
			<div class="products row">
				<?php include EW_PATH.'/views/product-grid.php'; ?>
			</div>
		</div>
    </main>
<?php get_footer(); ?>