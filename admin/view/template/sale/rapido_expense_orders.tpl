<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form" formaction="<?php echo $expense_orders; ?>" data-toggle="tooltip" title="<?php echo $button_get_invoices; ?>" class="btn btn-primary"><i class="fa fa-list"></i> <?php echo $button_get_invoices; ?></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
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
		    <form method="post" enctype="multipart/form-data" id="form">
				<div class="col-sm-2" id="razhod_orders_input_container">
				  <div class="form-group">
					<label class="control-label" for="input-expense-order"><?php echo $entry_expense_order; ?></label>
					<input type="text" name="expense_order" value="" placeholder="<?php echo $entry_expense_order; ?>" id="input-expense-order" class="form-control" />
				  </div>
				</div>
				<div class="col-sm-2" id="razhod_orders_select_container" style="display: none;">
				  <div class="form-group">
					<label class="control-label" for="input-expense-order"><?php echo $entry_expense_order; ?></label>
					<select name="orders" class="form-control" id="razhod_orders_select">
						<option value=""><?php echo $text_select; ?></option>
					</select>
				  </div>
				</div>
				<div class="col-sm-2" id="block_razhod_orders" >
				  <div class="form-group">
					<label class="control-label">&nbsp;</label> <br />
					<button id="get_razhod_orders" type="button" data-toggle="tooltip" title="<?php echo $button_info_expense_order; ?>" class="btn btn-primary"><?php echo $button_info_expense_order; ?></button>
				  </div>
				</div>
				<div class="col-sm-2">
				  <div class="form-group">
					<label class="control-label">&nbsp;</label> <br />
					<button id="button-expense-order" type="submit" form="form" formaction="<?php echo $expense_order; ?>" data-toggle="tooltip" title="<?php echo $button_expense_order; ?>" class="btn btn-primary"><?php echo $button_expense_order; ?></button>
				  </div>
				</div>
			</form>
		  </div>
		</div>
	  </div>
		<?php if ($invoices) { ?>
		  <div class="table-responsive">
			<table class="table table-bordered table-hover">
			  <thead>
				<tr>
				  <td class="text-left"><?php echo $column_invoiceid; ?></td>
				  <td class="text-left"><?php echo $column_created; ?></td>
				  <td class="text-left"><?php echo $column_paytype; ?></td>
				  <td class="text-left"><?php echo $column_tcount; ?></td>
				  <td class="text-left"><?php echo $column_tsum; ?></td>
				  <td class="text-left"><?php echo $column_dds; ?></td>
				  <td class="text-left"><?php echo $column_total; ?></td>
				  <?php /*
				  <td class="text-right"><?php echo $column_action; ?></td>
				  */ ?>
				</tr>
			  </thead>
			  <tbody>
				<?php foreach ($invoices as $invoice) { ?>
				<tr>
				  <td class="text-left"><?php echo $invoice['invoiceid']; ?></td>
				  <td class="text-left"><?php echo $invoice['created']; ?></td>
				  <td class="text-left"><?php echo $invoice['paytype']; ?></td>
				  <td class="text-left"><?php echo $invoice['tcount']; ?></td>
				  <td class="text-left"><?php echo $invoice['tsum']; ?></td>
				  <td class="text-left"><?php echo $invoice['dds']; ?></td>
				  <td class="text-left"><?php echo $invoice['total']; ?></td>
				  <?php /*
				  <td class="text-right"><a href="" data-toggle="tooltip" title="<?php echo $button_print; ?>" class="btn btn-primary"><i class="fa fa-print"></i></a></td>
				  */ ?>
				</tr>
				<?php } ?>
			  </tbody>
			</table>
		  </div>
		<?php } ?>
	</div>
  </div>
</div>


<script type="text/javascript"><!--
	$('#get_razhod_orders').on('click', function() {
		if ($('.rapido_error').length != 0) {
			$('.rapido_error').remove();
		}

		$.ajax({
			url: 'index.php?route=sale/rapido/getRazhodOrders&token=<?php echo $token; ?>',
			type: 'post',
			dataType: 'json',
			beforeSend: function() {
				$('#get_razhod_orders').button('loading');
			},
			complete: function() {
				$('#get_razhod_orders').button('reset');
			},
			success: function(json) {
				if (json.orders) {
					html = '';

					html += '<option value=""><?php echo $text_select; ?></option>';
					for (i = 0; i < json.orders.length; i++) {
						html += '<option value="' + json.orders[i]['rid'] + '">' + json.orders[i]['rid'] + '</option>';
					}

					console.log
					$('#razhod_orders_select').html(html);
					$('#razhod_orders_select_container').show();
					$('#razhod_orders_input_container').hide();
				}
			}
		});	
	});

	$('#razhod_orders_select').on('change', function() {
			$('#input-expense-order').val($(this).val());
			$('#button-expense-order').click();
	});
//--></script>
<?php echo $footer; ?>