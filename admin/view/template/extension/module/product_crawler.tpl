<?php 
	//Start session if there is none
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
?>
<?php echo $header; ?>
<?php echo $column_left; ?>
<!-- <div class="loading" id="loading" style="display: none;">
	<div class="loading-holder">
		<div class="sk-folding-cube">
		  <div class="sk-cube1 sk-cube"></div>
		  <div class="sk-cube2 sk-cube"></div>
		  <div class="sk-cube4 sk-cube"></div>
		  <div class="sk-cube3 sk-cube"></div>
		</div>
	</div>
</div> -->
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<?php 
				if (isset($_SESSION['success'])) { ?>
					<div class="alert alert-success">
						<?= $_SESSION['success'] ?>
					</div>
		<?php	
					unset($_SESSION['success']);
				}
			?>
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<div class="col-md-7 form">
						<form class="form-inline" method="POST" action="<?= $updatePercentLink ?>">
							<div class="form-group">
								<label>Надценка (в проценти %)</label>
								<input type="number" step="0.01" name="price-percent" class="form-control">
							</div>
							<div class="form-group">
								<label>Магазин</label>
								<select class="form-control" name="store">
									<option value="0" selected="selected">--Моля изберете--</option>
									<?php 
										foreach ($stores as $store) { ?>
											<option value="<?= $store['store'] ?>"><?= $store['store'] ?></option>
								<?php	}
									?>
								</select>
							</div>

							<button class="btn btn-primary price-submit"><i class="material-icons">add</i></button>
						</form>
					</div>
					<table class="table table-bordered table-hover">
						<thead>
							<tr>
								<th>Магазин</th>
								<th>% надценка</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								foreach ($stores as $store) { ?>
									<tr>
										<td><?= $store['store'] ?></td>
										<td><?= $store['percent'] ?></td>
									</tr>
						<?php	}
							?>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<div class="col-md-7 form">
						<form method="POST" action="<?php echo $action ?>" id="crawler-form">
							<div class="form-group">
								<label>URL</label>
								<input type="text" name="site-url" class="form-control">
							</div>

							<div class="form-group">
								<label>Магазин</label>
								<select class="form-control" name="site">
									<option value="" selected>--Моля избери--</option>
									<option value="dims-92">Dims-92</option>
									<option value="sky-r">Sky-r</option>
									<option value="vip-giftshop">Vip-giftshop</option>
									<option value="art93">art93</option>
									<option value="wenger">wenger</option>
									<option value="max-pen">max-pen</option>
								</select>
							</div>

							<div class="form-group">
								<button type="submit" class="btn btn-primary">Качи</button>
								<a href="<?= $updates_link ?>" class="link updates">Промени в цени и количества</a>
							</div>
						</form>
						<div class="clearfix"></div>
					</div>
					<a href="<?= $deleteAllLink ?>" class="delete-all-link">Изтрий всички продукти от чакащата опашка</a>
				</div>
			</div>
		</div>
		<div class="row" style="margin: 0;">
			<div class="col-md-4">
				<?php 
					if (isset($_SESSION['uploaded'])) { ?>
						<div class="alert alert-success">
							<strong><?= $_SESSION['uploaded'] ?></strong>
						</div>
			<?php	
						unset($_SESSION['uploaded']);
					}
					if (isset($_SESSION['newProducts'])) { ?>
						<div class="alert alert-info">
							<strong><?= $_SESSION['newProducts'] ?></strong>
						</div>

				<?php	unset($_SESSION['newProducts']);
					}

					if (isset($_SESSION['deleted'])) { ?>
						<div class="alert alert-info">
							<strong><?= $_SESSION['deleted']; ?></strong>
						</div>
			<?php		unset($_SESSION['deleted']);
					}

					if (isset($_SESSION['updateProducts'])) { ?>
						<div class="alert alert-info">
							<strong><?= $_SESSION['updateProducts'] ?></strong>
						</div>

				<?php	unset($_SESSION['updateProducts']);
					}

					if (isset($_SESSION['error'])) { ?>
						<div class="alert alert-danger">
							<strong><?= $_SESSION['error'] ?></strong>
						</div>
			<?php		
						unset($_SESSION['error']);
					}
					?>

				<?php 
					if (isset($_SESSION['all-deleted'])) { ?>
						<div class="alert alert-success">
							<strong><?= $_SESSION['all-deleted'] ?></strong>
						</div>
			<?php		unset($_SESSION['all-deleted']);
					}

					if (isset($_SESSION['all-deleted-error'])) { ?>
						<div class="alert alert-danger">
							<strong><?= $_SESSION['all-deleted-error'] ?></strong>
						</div>
			<?php		unset($_SESSION['all-deleted-error']);
					}
				?>
			</div>
		</div>
		<div class="col-md-8 col-md-offset-2">
			<div class="row">
				<div class="col-md-3">
					<div class="product-counter all">
						<strong>Чакащи продукти: <?= $count_all->rows[0]['COUNT(id)'] ?></strong>
					</div>
					<div class="product-counter in">
						<strong>В наличност: <?= $count_in_stock->rows[0]['COUNT(id)'] ?></strong>
					</div>
					<div class="product-counter out">
						<strong>Извън наличност: <?= $count_out_of_stock->rows[0]['COUNT(id)'] ?></strong>
					</div>
				</div>

				<div class="col-md-4">
					<form method="GET" action="<?= $crawler_link ?>">
						<input type="hidden" name="route" value="<?= $_GET['route'] ?>">
						<input type="hidden" name="token" value="<?= $_GET['token'] ?>">
						<div class="form-group">
							<label>Виж продукти само от</label>
							<select name="site" class="form-control">
								<option value="" selected>--Моля избери--</option>
								<?php 
									foreach ($stores as $store) { ?>
										<option value="<?= $store['store'] ?>"><?= $store['store'] ?></option>
							<?php	}
								?>
							</select>
						</div>
						<div class="form-group">
							<a href="#" class="link">Покажи всички</a>
						</div>
						<button type="submit" class="btn btn-primary">Покажи</button>
					</form>
				</div>
				<div class="col-md-4">
					<form method="POST" action="<?= $delete_from_store ?>">
						<div class="form-group">
							<label>Изтрий само от</label>
							<select name="site" class="form-control">
								<option value="" selected>--Моля избери--</option>
								<?php 
									foreach ($stores as $store) { ?>
										<option value="<?= $store['store'] ?>"><?= $store['store'] ?></option>
							<?php	}
								?>
							</select>
						</div>
						<button type="submit" class="btn btn-primary">Изтрий</button>
					</form>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Избери</th>
							<th>Магазин</th>
							<th>Име</th>
							<th>Код</th>
							<th>Цена</th>
							<th>
								<?php 
									$link = $product_crawler_link;
									if ($sort) {
										if ($order == 'DESC') { 
											$link .= '&order=ASC&sort=product_manufacturer';
										}
										elseif ($order == 'ASC') {
											$link .= '&order=DESC&sort=product_manufacturer';
										}
									}
									else { 
										$link .= '&order=DESC&sort=product_manufacturer';
									}

									if (isset($_GET['page'])) {
										$link .= '&page=' . intval($_GET['page']);
									} 

									if (isset($_GET['site'])) {
										$link .= '&site=' . $_GET['site'];
									}
								?>
								<a href="<?= $link ?>">Производител <i class="fa fa-fw fa-sort"></i></a>
							</th>
							<th>
								<?php 
									$link = $product_crawler_link;
									if ($sort) {
										if ($order == 'DESC') { 
											$link .= '&order=ASC&sort=product_quantity';
										}
										elseif ($order == 'ASC') {
											$link .= '&order=DESC&sort=product_quantity';
										}
									}
									else { 
										$link .= '&order=DESC&sort=product_quantity';
									}

									if (isset($_GET['page'])) {
										$link .= '&page=' . intval($_GET['page']);
									}

									if (isset($_GET['site'])) {
										$link .= '&site=' . $_GET['site'];
									}
								?>
								<a href="<?= $link ?>">Наличност <i class="fa fa-fw fa-sort"></i></a>
							</th>
							<th>Качи</th>
							<th>Изтрий</th>
						</tr>
					</thead>
					<tbody id="tbody">
						<?php 
							if (isset($allProducts)) {
								foreach ($allProducts as $row) { ?>
									<tr>
										<td>
											<input type="checkbox" data-product-id="<?= $row['id'] ?>" class="product-id">
										</td>
										<td><?= $row['store'] ?></td>
										<td><?= $row['product_name'] ?></td>
										<td><?= $row['product_code'] ?></td>
										<td><?= $row['product_price'] ?></td>
										<td>
											<?php 
												if ($row['product_manufacturer']) {
													echo $row['product_manufacturer'];
												}
												else {
													echo "Няма";
												}
											?>
										</td>
										<td><?= $row['product_quantity'] ?></td>
										<td>
											<a href="<?= $upload_form ?>&crawled-id=<?= $row['id'] ?>">Качи</a>
										</td>
										<td>
											<a href="<?= $deleteUrl ?>&id=<?= $row['id'] ?>">x</a>
										</td>
									</tr>
						<?php	}
							}
						?>
					</tbody>
				</table>
			</div>

			<div class="row">
				<div class="col-md-8">
					<ul class="pagination">
						<?php 
							for ($i=1; $i <= $pages; $i++) { ?>
								<?php 
									$link = $crawler_link;
									if (isset($_GET['sort']) && isset($_GET['order'])) {
										$sort = $_GET['sort'];
										$order = $_GET['order'];

										$link .= '&sort=' . $sort . '&order=' . $order . '&page=' . $i;
									}
									else { 
										$link .= '&page=' . $i;
									}

									if (isset($_GET['site'])) {
										$link .= '&site=' . $_GET['site'];
									}
								?>
								<li><a href="<?= $link ?>"><?= $i ?></a></li>
					<?php	}
						?>
					</ul>
				</div>
				<div class="col-md-4 upload-many" style="display: none;">
					<a href="<?= $upload_form ?>&upload-many=1" class="upload-many-link">Качи всички избрани като един</a>
				</div>
			</div>

		</div>
		<div class="clearfix"></div>
	</div>
</div>
<script>
	$(document).ready(function() {
		let firstLink = $('.upload-many-link').attr('href');
		let productIds = [];

		$('.product-id:checkbox:checked').each(function(index, el) {
			$(this).parents('tr').addClass('active');
			
			productIds.push($(this).data('product-id'));
		});

		$('.product-id').change(function() {
			let productId = $(this).data('product-id');
			if (this.checked) {
				$(this).parents('tr').addClass('active');

				productIds.push(productId);
			}
			else {
				$(this).parents('tr').removeClass('active');

				let index = productIds.indexOf(productId);

				if (index > -1) {
					productIds.splice(index, 1);
				}

				if (productIds.length == 0) {
					$('.upload-many').css('display', 'none');				
				}
			}

			if (productIds.length > 0) {
				$('.upload-many').css('display', 'block');	
			}

			let newLink = firstLink + '&product-ids=' + productIds.join('-');

			$('.upload-many-link').attr('href', newLink);
		});

		if (productIds.length > 0) {
			$('.upload-many').css('display', 'block');	
		}

		let newLink = firstLink + '&product-ids=' + productIds.join('-');

		$('.upload-many-link').attr('href', newLink);
	});
</script>

<!-- <script>
	$(document).ready(function() {
		$('#crawler-form').submit(function(event) {
			$('#loading').show();
		});
	});
</script> -->
<?php echo $footer; ?>