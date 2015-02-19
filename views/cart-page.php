<?php get_header(); 
include EW_PATH.'/views/cart.php';
$connectionSettings = get_option( 'ew_connection_settings' );
?>
    <main class="content js-content" role="main">
		<div class="wrap">
			<h1>Ostukorv</h1>
			<div id="cart-list" class="row"></div>
			
		</div>
    </main>
    <script id="cart-list-element" type="text/html">
        <h2>Tooted</h2>
        <small>Hinnad on kindlasti õiged</small>
        <hr>
        {{#cartProducts}}
          <div class="col-xs-12 text-center">
              <div class="row">
                <div class="col-xs-4">
                <h3> {{product.title}} </h3>
                </div>
                <div class="col-xs-4">
                  <p> kogus: {{amount}}</p>
                </div>
                <div class="col-xs-2">
                  Hind:33€
                </div>
                <div class="col-xs-2">
                  <button class="btn btn-danger">Kustuta</button>
                </div>
              </div>
              <hr>
          </div>
        {{/cartProducts}}
        <div class="col-xs-12 text-right">
          <button class="btn btn-success"  data-toggle="modal" data-target="#cart-form">Edasi</button>
        </div>
  </script>
  <script id="cart-list-element-empty" type="text/html">
      <div class="col-xs-12">
          <p>Ostukorv on tühi! <br /> Mine osta endale midagi</p>
       </div>
  </script>
  
  <script id="invoice" type="text/html">
      <iframe src="{{ invoiceLink }}" height="600" width="100%"></iframe>
  </script>

  <div class="modal fade" id="cart-form">
		<div class="modal-dialog modal-lg">
			<form id="cart-order" action="<?php echo $connectionSettings['api_url'].'/site/done'; ?>">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h2 class="modal-title" id="myModalLabel">Esita tellimus</h2>
					</div>
					<div class="modal-body" id="cart-form-body">
						<div class="form-horizontal">
							<div class="form-group">
								<label for="name" class="col-sm-2 control-label">Nimi</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" name="name" id="name" placeholder="Nimi">
								</div>
							</div>
							<div class="form-group">
								<label for="email" class="col-sm-2 control-label">Email</label>
								<div class="col-sm-10">
									<input type="email" class="form-control" name="email" id="email" placeholder="Email">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<div class="checkbox">
									    <label>
									    	<input type="checkbox">Olen lugenud ja nõustun <a href="http://newtime.ee" target="_blank">tingimustega</a> 
									    </label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="name" class="col-sm-2 control-label">Tarneviis</label>
								<div class="col-sm-10">
									<select class="form-control">
									    <option>SmartPost + 3€</option>
									    <option>Tulen ise järgi</option>
									</select>
								</div>
							</div>
						</div>
						<hr>
						<div class="text-right">
							<p>Summa: 36€</p>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Loobu</button>
						<button type="submit" class="btn btn-primary">Kinnita tellimus</button>
					</div>
				</div>
			</form>
		</div>
	</div>
<?php get_footer(); ?>