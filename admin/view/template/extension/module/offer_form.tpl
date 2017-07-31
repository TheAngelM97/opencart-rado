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
		</div>
	</div>

	<div class="container-fluid">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1><?= $data ?></h1>
			</div>
			<div class="panel-body">
				<form action="" method="POST" class="form-horizontal">
					<div class="form-group">
						<label for="name" class="col-md-2 control-label"><?= $text_name ?></label>
						<div class="col-md-10">
							<input type="text" id="name" name="name" class="form-control">
						</div>
					</div>

					<div class="form-group">
						<label for="products" class="col-md-2 control-label"><?= $text_products ?></label>
						<div class="col-md-6">
							<input type="text" id="products" name="products" class="form-control">
						</div>
						<i class="material-icons add add-product">add_box</i>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('#products').keyup(function(event) {
			let search = $(this).val()
			
			$.ajax({
				url: '<?= $product_search_link ?>' + '&token=<?=$token?>',
				type: 'POST',
				data: {search: search},
			})
			.done(function(data) {
				console.log(data)
				console.log("success")
			})
			.fail(function(err) {
				console.log(err)
				console.log("error");
			})
			.always(function() {
				console.log("complete")
			});
			
		});
	});
</script>

<?php echo $footer; ?>