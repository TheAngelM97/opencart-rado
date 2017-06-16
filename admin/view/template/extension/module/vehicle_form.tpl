<?php 
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}

	if (!isset($_SESSION['errors'])) {
		$_SESSION['errors'] = array();
	}

	echo $header;
	echo $column_left;
?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<h1><?= $add ?></h1>
			<hr>
			<div class="row">
				<form action="<?= $form_action ?>" method="POST">
					<div class="form-group">
							<div class="col-md-3">
								<label><?= $name ?></label>
								<input type="text" name="name" class="form-control" <?php if (isset($vehicle_info['name'])) { echo 'value=' . $vehicle_info['name']; } ?>>

								<?php 
									if (array_key_exists('name', $_SESSION['errors'])) { ?>
										<div class="alert alert-danger">
											<strong><?= $_SESSION['errors']['name'] ?></strong>
										</div>
							<?php	
										unset($_SESSION['errors']['name']);
									}
								?>
							</div>
							<div class="col-md-3">
								<label><?= $reg_number ?></label>
								<input type="text" name="reg-number" class="form-control" <?php if (isset($vehicle_info['reg_number'])) { echo 'value=' . $vehicle_info['reg_number']; } ?>>

								<?php 
									if (array_key_exists('reg-number', $_SESSION['errors'])) { ?>
										<div class="alert alert-danger">
											<strong><?= $_SESSION['errors']['reg-number'] ?></strong>
										</div>
							<?php	
										unset($_SESSION['errors']['reg-number']);
									}
								?>
							</div>
							<div class="col-md-3">
								<label><?= $type ?></label>
								<select class="form-control" name="type">
									<?php 
										if (!isset($vehicle_info['type'])) { ?>
											<option value="" selected><?= $choose ?></option>
								<?php	}
									?>
									<?php 
										if (!isset($vehicle_info['type'])) {
											foreach ($types as $type) { ?>
												<option value="<?= $type['type_id'] ?>"><?= $type['type'] ?></option>
								<?php		}
										}
										else {
											foreach ($types as $type) { 
												if ($type['type_id'] == $vehicle_info['type']) { ?>
													<option value="<?= $type['type_id'] ?>" selected><?= $type['type'] ?></option>
										<?php	
												}
												else { ?>
													<option value="<?= $type['type_id'] ?>"><?= $type['type'] ?></option>
										<?php	}
											?>
												
								<?php		}
										}
									?>
								</select>
								<?php 
									if (array_key_exists('type', $_SESSION['errors'])) { ?>
										<div class="alert alert-danger">
											<strong><?= $_SESSION['errors']['type'] ?></strong>
										</div>
							<?php	
										unset($_SESSION['errors']['type']);
									}
								?>
							</div>
							<div class="col-md-3">
								<label><?= $km ?></label>
								<input type="number" name="kilometers" class="form-control"" <?php if (isset($vehicle_info['kilometers'])) { echo 'value=' . $vehicle_info['kilometers']; } ?>>

								<?php 
									if (array_key_exists('kilometers', $_SESSION['errors'])) { ?>
										<div class="alert alert-danger">
											<strong><?= $_SESSION['errors']['kilometers'] ?></strong>
										</div>
							<?php	
										unset($_SESSION['errors']['kilometers']);
									}
								?>
							</div>
					</div>
					<div class="clearfix"></div>
					<div class="form-group">
						<?php 
							if (isset($vehicle_info)) { ?>
								<button class="btn btn-primary" type="submit">Редактирай</button>
					<?php	}
							else { ?>
								<button class="btn btn-primary" type="submit">Добави</button>
					<?php	}
						?>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php 
	echo $footer;
?>