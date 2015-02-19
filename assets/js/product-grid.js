jQuery(document).ready(function ($) {
	$.ajax({
		url:ewAjaxObject.api_url+"/api/products/wordpress",
		type:"GET",
		success: function(data){
			console.log(data);
			$('.products').mustache('product-element',data);
		}
	});
});