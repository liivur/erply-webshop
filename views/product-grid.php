<?php
	$options = get_option('ew_connection_settings');
?>
<script id="product-element" type="text/html">
    <div class="col-xs-4 text-center newtime-product">
        <h2>{{title}}</h2>
        <img alt="newtime rulz" src="<?php echo EW_URL.'/assets/images/nt.png'; ?>">
        <p>59.99€</p>
        {{# productExports }}
         <p>
          <a href="{{url}}" class="btn btn-info">Vaata lähemalt</a>
         </p>
         <form action="<?php echo $options['api_url']; ?>/api/cartProduct" class="ecommerce-cart-form">
            <button type="submit" class="btn btn-success ecommerce-cart-add">Lisa korvi</button>
            <input type="hidden" name="product_id" value="{{product_id}}">
            <input type="hidden" name="amount" value="1">
          </form>
        {{/ productExports }}
         <hr>
    </div>
</script>