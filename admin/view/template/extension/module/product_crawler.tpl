<?php 
	//Start session if there is none
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
?>
<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<div class="col-md-7 form">
						<form method="POST" action="<?php echo $action ?>">
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
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Избери</th>
							<th>Магазин</th>
							<th>Име</th>
							<th>Цена</th>
							<th>Наличност</th>
							<th>Качи</th>
							<th>Изтрий</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							if (isset($allProducts)) {
								foreach ($allProducts as $row) { ?>
									<tr>
										<td>
											<input type="checkbox" data-product-id="<?= $row['id'] ?>" class="product-id">
										</td>
										<td><?= $row['store'] ?></td>
										<td><?= $row['product_name'] ?></td>
										<td><?= $row['product_price'] ?></td>
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
								<li><a href="<?= $crawler_link . '&page=' . $i ?>"><?= $i ?></a></li>
					<?php	}
						?>
					</ul>
				</div>
				<div class="col-md-4 upload-many" style="display: none;">
					<a href="<?= $upload_form ?>&upload-many=1" class="upload-many-link">Качи всички избрани</a>
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
<?php echo $footer; ?>