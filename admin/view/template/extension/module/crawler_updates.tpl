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
			<a href="<?= $crawler_link ?>" class="link spider">Към паяците</a>
			<div class="clearfix"></div>
			<div class="col-md-4">
		<?php
				if (isset($_SESSION['deleted'])) { ?>
					<div class="alert alert-info">
						<strong><?= $_SESSION['deleted']; ?></strong>
					</div>
		<?php		unset($_SESSION['deleted']);
				}
				if (isset($_SESSION['success'])) { ?>
					<div class="alert alert-success">
						<strong><?= $_SESSION['success'] ?></strong>
					</div>
		<?php	
					unset($_SESSION['success']);
				}
		?>
			</div>
			<div class="col-md-8 col-md-offset-2" style="position: relative;">
				<?php 
					if (count($updateProducts)) { ?>
					<div class="col-md-4 col-md-offset-8" style="margin-bottom: 40px;">
						<a href="<?= $updateAllLink ?>" class="link update-all">Изпълни всички</a>
						<div class="clearfix"></div>
					</div>
					<div class="clearfix"></div>
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>Име</th>
									<th>Сегашна цена</th>
									<th>Нова цена</th>
									<th>Цвят</th>
									<th>Промени</th>
									<th>Изтрий</th>
								</tr>
							</thead>
							<tbody>
				<?php
						foreach ($updateProducts as $product) { ?>
								<tr>
									<td><?= $product['product_name'] ?></td>
									<td>
										<?php 
											if ($product['product_price'] < $product['price']) {
												echo number_format(round($product['product_price'] + $product['price'], 2), 2);
											}
											else {
												echo number_format(round($product['product_price'] - $product['price'], 2), 2);
											}
										?>
									</td>
									<td><?= number_format(round($product['new_price'], 2), 2) ?></td>
									<td>
										<?php 
											if (isset($product['name'])) { 
												echo $product['name'];
											}
											else {
												echo "Няма";
											}
										?>
									</td>
									<td>
										<?php 
											if (isset($product['name'])) { ?>
												<a href="<?= $editLink . '&product_id=' . $product['product_id'] . '&crawler_change=1&product_option_value_id=' . $product['product_option_value_id'] ?>">Промени</a>
									<?php	}
											else { ?>
												<a href="<?= $editLink . '&product_id=' . $product['product_id'] . '&crawler_change=1' ?>">Промени</a>
									<?php	}
										?>
									</td>
									<td>
										<a href="<?= $deleteUpdateLink . '&id=' . $product['update_id'] ?>">x</a>
									</td>
								</tr>
				<?php	}
				?>			</tbody>
						</table>
					</div>
			<?php	}

					else { ?>
						<h1 class="text-center">Няма продукти чакащи за промени</h1>
			<?php	}
				?>
			</div>
		</div>
	</div>	
</div>
<?php echo $footer; ?>