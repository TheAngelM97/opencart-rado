<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="col-md-4">
				<?php 
					if (isset($_SESSION['success']) && isset($_SESSION['category-updated'])) { ?>
						<div class="alert alert-success">
							<strong><?= $_SESSION['success'] ?> <?= $_SESSION['category-updated']['name'] ?></strong>
						</div>
			<?php	
						unset($_SESSION['success']);
					}
				?>
				<form action="<?= $action ?>" method="POST">
					<div class="form-group">
						<input type="number" step="0.01" name="weight" placeholder="Тегло" class="form-control">
					</div>
					<div class="form-group">
						<select name="category" class="form-control">
							<option value="" selected>--Изберете категория--</option>
							<?php 
								foreach ($categories as $category) { ?>
									<option value="<?= $category['category_id'] ?>"><?= $category['name'] ?></option>
						<?php	}
							?>
						</select>
					</div>
					<button type="submit" class="btn btn-primary">Качи</button>
				</form>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>