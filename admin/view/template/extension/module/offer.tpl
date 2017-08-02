<?php echo $header; ?>
<?php echo $column_left; ?>

<div id="content">
<?php 
  if (isset($custom_styles)) {
    foreach ($custom_styles as $style) { ?>
     <link rel="stylesheet" href="view/stylesheet/<?= $style ?>">
  <?php 
    } 
  }
?>
	<div class="page-header">
		<div class="container-fluid">
			<h1><?= $title ?></h1>
			<hr>
		</div>

		<div class="container-fluid">
			<div class="text-right">
				<a href="<?= $add_form_link ?>"><i class="material-icons add add-icon">add_box</i></a>
			</div>

			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Име</th>
							<th>Продукти</th>
							<th>Процент отстъпка</th>
							<th>Цена</th>
							<th>Дата на създаване</th>
							<th>Изтрий</th>
						</tr>
					</thead>
					<?php 
						if (count($offers)) { ?>
							<tbody>
								<?php 
									foreach ($offers as $id => $offer) { ?>
										<tr>
											<td><?= $offer['person_name'] ?></td>
											<td>
												<?php 
													foreach ($offer['products'] as $product) {
														echo $product['name'] . ' - ' . $product['offer_quantity'] . ' броя' . '<br>';
													}
												?>
											</td>
											<td><?= $offer['discount'] ?> <strong>(%)</strong></td>
											<td><?= number_format(round(($offer['total_price'] - ($offer['discount'] / 100) * $offer['total_price']), 2), 2) ?></td>
											<td><?= $offer['created_at'] ?></td>
											<td>
												<a href="<?= $delete_link ?>&id=<?=$id?>">
													<div class="delete-offer text-center">
														<i class="material-icons">delete</i>
													</div>
												</a>
											</td>
										</tr>			
							<?php	}
								?>
							</tbody>
				<?php	}
					?>
				</table>
			</div>
		</div>
	</div>
</div>

<?php echo $footer; ?>