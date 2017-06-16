<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
	<div class="container-fluid">
	  <?php if ($courier) { ?>
	  <div class="pull-right">
		<button type="submit" form="form" formaction="<?php echo $courier; ?>" data-toggle="tooltip" title="<?php echo $button_courier; ?>" class="btn btn-primary"><i class="fa fa-truck"></i> <?php echo $button_courier; ?></button>
		<button type="submit" form="form" formaction="<?php echo $courier_requested; ?>" data-toggle="tooltip" title="<?php echo $button_courier; ?>" class="btn btn-primary"><i class="fa fa-truck"></i> <?php echo $button_courier_requested; ?></button>
		<a href="<?php echo $expense_orders; ?>" data-toggle="tooltip" title="<?php echo $button_expense_orders; ?>" class="btn btn-primary"><i class="fa fa-file-text-o"></i> <?php echo $button_expense_orders; ?></a>
	  </div>
	  <?php } ?>
	  <h1><?php echo $heading_title; ?></h1>
	  <ul class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
		<?php } ?>
	  </ul>
	</div>
  </div>
  <div class="container-fluid">
	<?php if ($error_warning) { ?>
	<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
	  <button type="button" class="close" data-dismiss="alert">&times;</button>
	</div>
	<?php } ?>
	<?php if ($success) { ?>
	<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
	  <button type="button" class="close" data-dismiss="alert">&times;</button>
	</div>
	<?php } ?>
	<div class="panel panel-default">
	  <div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
	  </div>
	  <div class="panel-body">
		<div class="well">
		  <div class="row">
			<div class="col-sm-3">
			  <div class="form-group">
				<label class="control-label" for="input-tovaritelnica"><?php echo $column_tovaritelnica; ?></label>
				<input type="text" name="filter_tovaritelnica" value="<?php echo $filter_tovaritelnica; ?>" placeholder="<?php echo $column_tovaritelnica; ?>" id="input-tovaritelnica" class="form-control" />
			  </div>
			</div>
			<div class="col-sm-2">
			  <div class="form-group">
				<label class="control-label" for="input-order-id"><?php echo $column_order_id; ?></label>
				<input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $column_order_id; ?>" id="input-order-id" class="form-control" />
			  </div>
			</div>
			<div class="col-sm-2">
			  <div class="form-group">
				<label class="control-label" for="input-order-status"><?php echo $column_status; ?></label>
				<select name="filter_order_status_id" id="input-order-status" class="form-control">
				  <option value="*"></option>
				  <?php if ($filter_order_status_id == '0') { ?>
				  <option value="0" selected="selected"><?php echo $text_missing; ?></option>
				  <?php } else { ?>
				  <option value="0"><?php echo $text_missing; ?></option>
				  <?php } ?>
				  <?php foreach ($order_statuses as $order_status) { ?>
				  <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
				  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
				  <?php } else { ?>
				  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
				  <?php } ?>
				  <?php } ?>
				</select>
			  </div>
			</div>
			<div class="col-sm-2">
			  <div class="form-group">
				<label class="control-label" for="input-date-created"><?php echo $column_date_created; ?></label>
				<div class="input-group date">
				  <input type="text" name="filter_date_created" value="<?php echo $filter_date_created; ?>" placeholder="<?php echo $column_date_created; ?>" data-date-format="YYYY-MM-DD" id="input-date-created" class="form-control" />
				  <span class="input-group-btn">
				  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
				  </span></div>
			  </div>
			</div>
			<div class="col-sm-3">
			  <div class="form-group">
				<label class="control-label" for="input-sendoffice"><?php echo $column_sendoffice; ?></label>
				<input type="text" name="filter_sendoffice" value="<?php echo $filter_sendoffice; ?>" placeholder="<?php echo $column_sendoffice; ?>" id="input-sendoffice" class="form-control" />
			  </div>
			  <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
			</div>
		  </div>
		</div>
		<form method="post" enctype="multipart/form-data" id="form">
		  <div class="table-responsive">
			<table class="table table-bordered table-hover">
			  <thead>
				<tr>
				  <td  style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
				  <td class="text-right"><?php if ($sort == 'ro.tovaritelnica') { ?>
					<a href="<?php echo $sort_rapido_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_tovaritelnica; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_rapido_order; ?>"><?php echo $column_tovaritelnica; ?></a>
					<?php } ?></td>
				  <td class="text-right"><?php if ($sort == 'ro.order_id') { ?>
					<a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_order; ?>"><?php echo $column_order_id; ?></a>
					<?php } ?></td>
				  <td class="text-left"><?php if ($sort == 'status') { ?>
					<a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
					<?php } ?></td>
				  <td class="text-left"><?php if ($sort == 'ro.date_created') { ?>
					<a href="<?php echo $sort_date_created; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_created; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_date_created; ?>"><?php echo $column_date_created; ?></a>
					<?php } ?></td>
				  <td class="text-left"><?php if ($sort == 'ro.sendoffice') { ?>
					<a href="<?php echo $sort_sendoffice; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sendoffice; ?></a>
					<?php } else { ?>
					<a href="<?php echo $sort_sendoffice; ?>"><?php echo $column_sendoffice; ?></a>
					<?php } ?></td>
				  <td class="text-right"><?php echo $column_action; ?></td>
				</tr>
			  </thead>
			  <tbody>
				<?php if ($rapido_orders) { ?>
				<?php foreach ($rapido_orders as $order) { ?>
				<tr>
				  <td class="text-center"><?php if (in_array($order['order_id'], $selected)) { ?>
					<input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
					<?php } else { ?>
					<input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
					<?php } ?></td>
				  <td class="text-right"><a href="<?php echo $order['tovaritelnica_href']; ?>" <?php if (!empty($order['tovaritelnica_target'])) { ?> target="<?php echo $order['tovaritelnica_target']; ?>"<?php } ?>><?php echo $order['tovaritelnica']; ?></a></td>
				  <td class="text-right"><a href="<?php echo $order['order_href']; ?>"><?php echo $order['order_id']; ?></a></td>
				  <td class="text-left"><?php echo $order['status']; ?></td>
				  <td class="text-left"><?php echo $order['date_created']; ?></td>
				  <td class="text-left"><?php echo $order['sendoffice']; ?></td>
				  <td class="text-right"><?php foreach ($order['action'] as $action) { ?>
					[ <?php if (!empty($action['href'])) { ?><a href="<?php echo $action['href']; ?>" <?php if (!empty($action['target'])) { ?> target="<?php echo $action['target']; ?>"<?php } ?>><?php echo $action['text']; ?></a><?php } else { ?><?php echo $action['text']; ?><?php } ?> ]
					<?php } ?></td>
				</tr>
				<?php } ?>
				<?php } else { ?>
				<tr>
				  <td  class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
				</tr>
				<?php } ?>
			  </tbody>
			</table>
		  </div>
		</form>
		<div class="row">
		  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
		  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
		</div>
	  </div>
	</div>
  </div>
</div>

<script type="text/javascript"><!--
$('#button-filter').on('click', function() {

	url = 'index.php?route=sale/rapido&token=<?php echo $token; ?>';

	var filter_tovaritelnica = $('input[name=\'filter_tovaritelnica\']').val();

	if (filter_tovaritelnica) {
		url += '&filter_tovaritelnica=' + encodeURIComponent(filter_tovaritelnica);
	}

	var filter_order_id = $('input[name=\'filter_order_id\']').val();

	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}

	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').val();

	if (filter_order_status_id != '*') {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}

	var filter_date_created = $('input[name=\'filter_date_created\']').val();

	if (filter_date_created) {
		url += '&filter_date_created=' + encodeURIComponent(filter_date_created);
	}

	var filter_sendoffice = $('input[name=\'filter_sendoffice\']').val();

	if (filter_sendoffice) {
		url += '&filter_sendoffice=' + encodeURIComponent(filter_sendoffice);
	}

	location = url;
});
//--></script>

<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
<script type="text/javascript"><!--
	$('#get_razhod_orders').on('click', function() {
		if ($('.rapido_error').length != 0) {
			$('.rapido_error').remove();
		}

		if ($('#form_razhod_orders').length != 0) {
			$('#form_razhod_orders').remove();
		}

		$.ajax({
			url: 'index.php?route=sale/rapido/getRazhodOrders&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			success: function(json) {
				if (json.orders) {
					html = '   <div class="col-sm-2" id="form_razhod_orders">';
					html += '    <div class="form-group">';
					html += '      <label class="control-label">&nbsp;</label> <br />';
					html += '      <select name="orders" class="form-control" onchange="submitRazhodOrder(this);">';
					html += '         <option value=""><?php echo $text_select; ?></option>';
					for (i = 0; i < json.orders.length; i++) {
					html += '         <option value="' + json.orders[i]['rid'] + '"><strong>ИД:</strong> ' + json.orders[i]['rid'] + '</option>';
					}
					html += '      </select>';
					html += '    </div>';
					html += '  </div>';

					$('#block_razhod_orders').after(html);
				}
			}
		});	
	});

	function submitRazhodOrder(rid) {
		if (rid.value) {
			$('#input-expense-order').val(rid.value);
			$('#button-expense-order').click();
		}
	}
//--></script>
<?php echo $footer; ?>