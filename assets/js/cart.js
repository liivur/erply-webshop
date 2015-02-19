jQuery(document).ready(function ($) {
	$.ajax({
		url:ewAjaxObject.api_url+"/api/cart/2",
		type:"GET",
		success: function(data){
			console.log(data);
			$('#cart-list').mustache('cart-list-element',data, {method: 'html'});
		},
		error: function(data){
			$('#cart-list').mustache('cart-list-element-empty',data);
		}
	});
	$('body').on('submit','#cart-order',function(e){
        e.preventDefault();
        var parent =$(this);
        $.ajax({
            url: parent.attr('action'),
            type: "POST",
            data: parent.serialize(),
            success: function(data){
                $('#cart-form-body').mustache('invoice',data, {method: "html"});
            },
            error: function(data){
                console.log('oops, kaardil polnud piisavalt raha');
            }
        });
	});
});