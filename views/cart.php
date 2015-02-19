<?php
$pagesSettings = get_option( 'ew_pages_settings' );
?>
<div class="wrap">
  <div class="row">
    <div class="col-sm-4 col-sm-offset-8 text-right" id="cart">
    </div>
  </div>
</div>
<script id="cart-element" type="text/html">
  <a href="<?php echo get_permalink($pagesSettings['cartPage']); ?>">
    <h3>Ostukorv</h3>
    <p>Tooteid: {{cartProducts.length}}</p>
    <p>Hind: 100€</p>
  </a>
</script>
<script id="cart-empty-element" type="text/html">
    <h3>Ostukorv</h3>
    <p>Tooteid: 0</p>
    <p>Hind: 0</p>
</script>