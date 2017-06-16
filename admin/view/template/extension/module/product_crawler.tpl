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
				<div class="col-md-3 col-md-offset-2">
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
			</div>
		</div>
		<div class="col-md-8 col-md-offset-2">
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
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

			<ul class="pagination">
				<?php 
					for ($i=1; $i <= $pages; $i++) { ?>
						<li><a href="<?= $crawler_link . '&page=' . $i ?>"><?= $i ?></a></li>
			<?php	}
				?>
			</ul>

		</div>
		<div class="clearfix"></div>
	</div>
</div>
<?php echo $footer; ?>