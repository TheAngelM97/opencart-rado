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
		?>
			</div>
			<div class="col-md-8 col-md-offset-2 text-center">
				<?php 
					if (count($updateProducts)) { ?>
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>Име</th>
									<th>Сегашна цена</th>
									<th>Нова цена</th>
									<th>Сегашна наличност</th>
									<th>Нова наличност</th>
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
									<td><?= number_format(round($product['product_price'], 2), 2) ?></td>
									<td><?= number_format(round($product['new_price'], 2), 2) ?></td>
									<td>
										<?php 
											if ($product['quantity']) { 
												echo "Да";
											}
											else {
												echo "Не";
											}
										?>
									</td>
									<td><?= $product['new_quantity'] ?></td>
									<td>
										<?php 
											if (isset($product['name'])) { 
												echo $product['name'];
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