<?php 
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}

	if (!isset($_SESSION['error'])) {
		$_SESSION['error'] = array();
	}

	if (!isset($_SESSION['success'])) {
		$_SESSION['success'] = array();
	}

	echo $header;
	echo $column_left;
?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<h1><?= $title ?></h1>
			<hr>
			<?php 
				if (array_key_exists('vehicle', $_SESSION['error'])) { ?>
					<div class="alert alert-danger">
						<strong><?= $_SESSION['error']['vehicle'] ?></strong>
					</div>
		<?php	
					unset($_SESSION['error']['vehicle']);
				}

				if (array_key_exists('vehicle', $_SESSION['success'])) { ?>
					<div class="alert alert-success">
						<strong><?= $_SESSION['success']['vehicle'] ?></strong>
					</div>
		<?php	
					unset($_SESSION['success']['vehicle']);
				}
			?>
			<div class="link-holder float-left">
				<a href="<?= $form_link ?>" class="vehicle-link"><?= $add_message ?></a>
			</div>
			<div class="clearfix" style="margin-bottom: 40px;"></div>

			<?php 
				if (count($vehicles)) { ?>
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th><?= $name ?></th>
									<th><?= $reg_number ?></th>
									<th><?= $type ?></th>
									<th><?= $km ?></th>
									<th><?= $edit ?></th>
									<th><?= $delete ?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
									foreach ($vehicles as $vehicle) { ?>
										<tr>
											<td><?= $vehicle['name'] ?></td>
											<td><?= $vehicle['reg_number'] ?></td>
											<td><?= $vehicle['type'] ?></td>
											<td><?= $vehicle['kilometers'] ?></td>
											<td>
												<a href="<?= $edit_link . '&id=' . $vehicle['id'] ?>" class="btn btn-primary" data-toggle="tooltip" title="" data-original-title="<?= $edit ?>"><i class="fa fa-pencil"></i></a>
											</td>
											<td>
												<a href="#" data-href="<?= $delete_link . '&id=' . $vehicle['id'] ?>" data-toggle="tooltip" title="" class="btn btn-danger delete-link" data-original-title="<?= $delete ?>"><i class="fa fa-minus-circle"></i></a>
											</td>
										</tr>
							<?php	}
								?>
							</tbody>
						</table>
					</div>
		<?php	}
				else { ?>
					<h2><?= $none ?></h2>
		<?php	}
			?>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('.delete-link').click(function(event) {
			event.preventDefault();

			let href = $(this).data('href');

			let c = confirm('<?= $delete_warning ?>');

			if (c) {
				window.location.href = href;
			}
		});
	});
</script>
<?php 
	echo $footer;	
?>