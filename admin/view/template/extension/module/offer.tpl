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
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>

<?php echo $footer; ?>