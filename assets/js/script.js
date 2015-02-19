if ( window.XDomainRequest ) {
	jQuery.ajaxTransport(function( s ) {
		if ( s.crossDomain && s.async ) {
			if ( s.timeout ) {
				s.xdrTimeout = s.timeout;
				delete s.timeout;
			}
			var xdr;
			return {
				send: function( _, complete ) {
					function callback( status, statusText, responses, responseHeaders ) {
						xdr.onload = xdr.onerror = xdr.ontimeout = jQuery.noop;
						xdr = undefined;
						complete( status, statusText, responses, responseHeaders );
					}
					xdr = new XDomainRequest();
					xdr.onload = function() {
						callback( 200, "OK", { text: xdr.responseText }, "Content-Type: " + xdr.contentType );
					};
					xdr.onerror = function() {
						callback( 404, "Not Found" );
					};
					xdr.onprogress = jQuery.noop;
					xdr.ontimeout = function() {
						callback( 0, "timeout" );
					};
					xdr.timeout = s.xdrTimeout || Number.MAX_VALUE;
					xdr.open( s.type, s.url );
					xdr.send( ( s.hasContent && s.data ) || null );
				},
				abort: function() {
					if ( xdr ) {
						xdr.onerror = jQuery.noop;
						xdr.abort();
					}
				}
			};
		}
	});
}
jQuery(document).ready(function ($) {
    $.support.cors = true
    $.ajaxSetup({
        xhrFields: {
            withCredentials: true
        }
    });
    $.Mustache.addFromDom();
    ewObject.updateCart();
    $('body').on('submit','.ecommerce-cart-form', function(e){
        e.preventDefault();
        var parent =$(this);
        $.ajax({
            url: parent.attr('action'),
            type: "POST",
            data: parent.serialize(),
            success: function(data){
               ewObject.updateCart();
            },
            error: function(data){
                console.log('osta elu');
            }
        });
    });
});

var ewObject = {
	updateCart: function() {
		jQuery.ajax({
		    url: ewAjaxObject.api_url+"/api/cart/2",
		    type:"GET",
		    success: function(data){
		        jQuery('#cart').mustache('cart-element',data, { method: 'html' });
		    },
		    error: function(data){
		        if(data.status==404){
		            jQuery('#cart').mustache('cart-empty-element',{},{ method: 'html' });
		        }
		    }
		});
	},
	productGrid: function() {
		jQuery.ajax({
			url: ewAjaxObject.api_url+"/api/products/wordpress",
			type:"GET",
			success: function(data){
				console.log(data);
				jQuery('.products').mustache('product-element',data);
			}
		});
	}
}