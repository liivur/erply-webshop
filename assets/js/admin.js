jQuery(document).ready(function ($) {
	$(function() {
		$('.test-it').on('click', function() {
			$.ajax({
				url: 'http://localhost/wptest/wp-admin/admin-ajax.php',
				type: $(this).data('method'),
				data: {'action': 'ew_test'},
				success: function(data) {
					console.log(data);
				}
			});
		});

		$('#get-stuff').on('click', function() {
			$.ajax({
		        url: ewAjaxObject.api_url+"/api/products/wordpress",
		        type:"GET",
		        success: function(data){
			        console.log(data);
		        }
		    });
		});
	});
});