<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
	<div class="container-fluid">
	  <div class="pull-right">
		<button type="submit" onclick="$('#form-rapido-shipping :input').removeAttr('disabled'); $('#form-rapido-shipping').submit();" form="form-rapido-shipping" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
		<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
	<?php if ($info_electronic_invoice_type) { ?>
	<div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <?php echo $info_electronic_invoice_type; ?>
	  <button type="button" class="close" data-dismiss="alert">&times;</button>
	</div>
	<?php } ?>
	<div class="panel panel-default">
	  <div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
	  </div>
	  <div class="panel-body">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-rapido-shipping" class="form-horizontal">
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="rapido_test"><?php echo $entry_test; ?></label>
			<div class="col-sm-10">
			  <select name="rapido_test" id="rapido_test" class="form-control">
				<?php if ($rapido_test) { ?>
				<option value="1" selected="selected"><?php echo $text_yes; ?></option>
				<option value="0"><?php echo $text_no; ?></option>
				<?php } else { ?>
				<option value="1"><?php echo $text_yes; ?></option>
				<option value="0" selected="selected"><?php echo $text_no; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group required">
			<label class="col-sm-2 control-label" for="rapido_username"><?php echo $entry_username; ?></label>
			<div class="col-sm-10">
			  <input type="text" name="rapido_username" value="<?php echo $rapido_username; ?>" placeholder="<?php echo $entry_username; ?>" id="rapido_username" class="form-control" />
			  <?php if ($error_username) { ?>
			  <div class="text-danger"><?php echo $error_username; ?></div>
			  <?php } ?>
			</div>
		  </div>
		  <div class="form-group required">
			<label class="col-sm-2 control-label" for="rapido_password"><?php echo $entry_password; ?></label>
			<div class="col-sm-10">
			  <input type="password" name="rapido_password" value="<?php echo $rapido_password; ?>" placeholder="<?php echo $entry_password; ?>" id="rapido_password" class="form-control" />
			  <?php if ($error_password) { ?>
			  <div class="text-danger"><?php echo $error_password; ?></div>
			  <?php } ?>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="rapido_nomenclature_count"><span data-toggle="tooltip" title="<?php echo $help_nomenclature; ?>"><?php echo $entry_nomenclature; ?></span></label>
			<label class="col-sm-2 control-label" for="rapido_nomenclature_count"><?php echo $entry_nomenclature_count; ?></label>
			<div class="col-sm-1">
			  <input type="text" id="rapido_nomenclature_count" name="rapido_nomenclature_count" value="<?php echo $rapido_nomenclature_count; ?>" size="4" class="form-control" />
			</div>
			<div class="col-sm-4">
			  <a id="rapido_nomenclature" onclick="getNomenclature('', 0, '');" class="btn btn-primary"><?php echo $text_nomenclature; ?></a>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="rapido_nomenclature_count"><span data-toggle="tooltip" title="<?php echo $help_subservices; ?>"><?php echo $entry_subservices; ?></span></label>
			<div class="col-sm-4">
			  <a id="rapido_subservices_button" onclick="getSubservices();" class="btn btn-primary"><?php echo $text_subservices; ?></a>
			</div>
		  </div>
		  <div <?php if (count($my_objects) <= 1 && !$error_sender_city) { ?> style="display: none;"<?php } ?> class="form-group">
			 <label class="col-sm-2 control-label" for="rapido_sender_office_id"><?php echo $entry_sender_office; ?></label>
			 <div class="col-sm-10">
			   <select name="rapido_sender_office_id" id="rapido_sender_office_id" class="form-control" onchange="fillCity();">
				 <?php foreach ($my_objects as $my_object_id => $my_object) { ?>
				 <?php if ($my_object_id == $rapido_sender_office_id) { ?>
				 <option value="<?php echo $my_object_id; ?>" selected="selected"><?php echo $my_object['OFFICENAME']; ?></option>
				 <?php } else { ?>
				 <option value="<?php echo $my_object_id; ?>"><?php echo $my_object['OFFICENAME']; ?></option>
				 <?php } ?>
				 <?php } ?>
			  </select>
			 </div>
			 <input type="hidden" id="rapido_sender_office" name="rapido_sender_office" value="<?php echo $rapido_sender_office; ?>" />
			 <input type="hidden" id="rapido_sender_office_default" name="rapido_sender_office_default" value="<?php echo $rapido_sender_office_default; ?>" /></td>
		  </div>
		  <div <?php if (count($my_objects) <= 1 && !$error_sender_city) { ?> style="display: none;"<?php } ?> class="form-group required">
			<label class="col-sm-2 control-label" for="rapido_sender_city"><?php echo $entry_sender_city; ?></label>
			<div class="col-sm-2">
			  <input type="text" id="rapido_sender_city" name="rapido_sender_city" value="<?php echo $rapido_sender_city; ?>" disabled="disabled" class="form-control" />
			  <input type="hidden" id="rapido_sender_city_id" name="rapido_sender_city_id" value="<?php echo $rapido_sender_city_id; ?>" />
			  <?php if ($error_sender_city) { ?>
			    <div class="text-danger"><?php echo $error_sender_city; ?></div>
			  <?php } ?>
			</div>
			<label class="col-sm-2 control-label" for="rapido_sender_postcode"><?php echo $entry_sender_postcode; ?></label>
			<div class="col-sm-2">
			  <input type="text" id="rapido_sender_postcode" name="rapido_sender_postcode" value="<?php echo $rapido_sender_postcode; ?>" disabled="disabled" size="3" class="form-control" />
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="rapido_fixed_time"><?php echo $entry_fixed_time; ?></label>
			<div class="col-sm-10">
			  <select name="rapido_fixed_time" id="rapido_fixed_time" class="form-control">
				<?php if ($rapido_fixed_time) { ?>
				<option value="1" selected="selected"><?php echo $text_yes; ?></option>
				<option value="0"><?php echo $text_no; ?></option>
				<?php } else { ?>
				<option value="1"><?php echo $text_yes; ?></option>
				<option value="0" selected="selected"><?php echo $text_no; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="rapido_return_receipt"><?php echo $entry_return_receipt; ?></label>
			<div class="col-sm-10">
			  <select name="rapido_return_receipt" id="rapido_return_receipt" class="form-control">
				<?php if ($rapido_return_receipt) { ?>
				<option value="1" selected="selected"><?php echo $text_yes; ?></option>
				<option value="0"><?php echo $text_no; ?></option>
				<?php } else { ?>
				<option value="1"><?php echo $text_yes; ?></option>
				<option value="0" selected="selected"><?php echo $text_no; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="rapido_return_doc"><?php echo $entry_return_doc; ?></label>
			<div class="col-sm-10">
			  <select name="rapido_return_doc" id="rapido_return_doc" class="form-control">
				<?php if ($rapido_return_doc) { ?>
				<option value="1" selected="selected"><?php echo $text_yes; ?></option>
				<option value="0"><?php echo $text_no; ?></option>
				<?php } else { ?>
				<option value="1"><?php echo $text_yes; ?></option>
				<option value="0" selected="selected"><?php echo $text_no; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="rapido_insurance"><?php echo $entry_insurance; ?></label>
			<div class="col-sm-10">
			  <select name="rapido_insurance" id="rapido_insurance" class="form-control">
				<?php if ($rapido_insurance) { ?>
				<option value="1" selected="selected"><?php echo $text_yes; ?></option>
				<option value="0"><?php echo $text_no; ?></option>
				<?php } else { ?>
				<option value="1"><?php echo $text_yes; ?></option>
				<option value="0" selected="selected"><?php echo $text_no; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="rapido_fragile"><?php echo $entry_fragile; ?></label>
			<div class="col-sm-10">
			  <select name="rapido_fragile" id="rapido_fragile" class="form-control">
				<?php if ($rapido_fragile) { ?>
				<option value="1" selected="selected"><?php echo $text_yes; ?></option>
				<option value="0"><?php echo $text_no; ?></option>
				<?php } else { ?>
				<option value="1"><?php echo $text_yes; ?></option>
				<option value="0" selected="selected"><?php echo $text_no; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group required">
			<label class="col-sm-2 control-label"><?php echo $entry_subservices_city; ?></label>
			<div class="col-sm-10">
			  <div id="rapido_subservices_city" class="well well-sm" style="height: 150px; overflow: auto;">
				<?php foreach ($subservices_city as $subservice_id => $subservice_name) { ?>
				<div class="checkbox">
				  <label>
					<?php if (in_array($subservice_id, $rapido_subservices_city)) { ?>
					<input type="checkbox" name="rapido_subservices_city[]" value="<?php echo $subservice_id; ?>" checked="checked" />
					<?php echo $subservice_name; ?>
					<?php } else { ?>
					<input type="checkbox" name="rapido_subservices_city[]" value="<?php echo $subservice_id; ?>" />
					<?php echo $subservice_name; ?>
					<?php } ?>
				  </label>
				</div>
				<?php } ?>
			  </div>
			  <a onclick="$(this).parent().find(':checkbox').prop('checked', true);" style="cursor: pointer;"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);" style="cursor: pointer;"><?php echo $text_unselect_all; ?></a>
			  <?php if ($error_subservices_city) { ?>
			  <div class="text-danger"><?php echo $error_subservices_city; ?></div>
			  <?php } ?>
			</div>
		  </div>
		  <div class="form-group required">
			<label class="col-sm-2 control-label"><?php echo $entry_subservices_intercity; ?></label>
			<div class="col-sm-10">
			  <div id="rapido_subservices_intercity" class="well well-sm" style="height: 150px; overflow: auto;">
				<?php foreach ($subservices_intercity as $subservice_id => $subservice_name) { ?>
				<div class="checkbox">
				  <label>
					<?php if (in_array($subservice_id, $rapido_subservices_intercity)) { ?>
					<input type="checkbox" name="rapido_subservices_intercity[]" value="<?php echo $subservice_id; ?>" checked="checked" />
					<?php echo $subservice_name; ?>
					<?php } else { ?>
					<input type="checkbox" name="rapido_subservices_intercity[]" value="<?php echo $subservice_id; ?>" />
					<?php echo $subservice_name; ?>
					<?php } ?>
				  </label>
				</div>
				<?php } ?>
			  </div>
			  <a onclick="$(this).parent().find(':checkbox').prop('checked', true);" style="cursor: pointer;"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);" style="cursor: pointer;"><?php echo $text_unselect_all; ?></a>
			  <?php if ($error_subservices_intercity) { ?>
			  <div class="text-danger"><?php echo $error_subservices_intercity; ?></div>
			  <?php } ?>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="rapido_auto_print"><?php echo $entry_auto_print; ?></label>
			<div class="col-sm-10">
			  <select name="rapido_auto_print" id="rapido_auto_print" class="form-control">
				<?php if ($rapido_auto_print) { ?>
				<option value="1" selected="selected"><?php echo $text_yes; ?></option>
				<option value="0"><?php echo $text_no; ?></option>
				<?php } else { ?>
				<option value="1"><?php echo $text_yes; ?></option>
				<option value="0" selected="selected"><?php echo $text_no; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div id="rapidocarrier_printer_row" class="form-group" <?php if (!$rapido_auto_print) { ?> style="display: none;" <?php } ?>>
			<label class="col-sm-2 control-label" for="rapido_printer"><span data-toggle="tooltip" title="<?php echo $help_printer; ?>"><?php echo $entry_printer; ?></span></label>
			<div class="col-sm-10">
			  <input type="text" name="rapido_printer" value="<?php echo $rapido_printer; ?>" placeholder="<?php echo $entry_printer; ?>" id="rapido_printer" class="form-control" />
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="rapido_label_printer"><?php echo $entry_label_printer; ?></label>
			<div class="col-sm-10">
			  <select name="rapido_label_printer" id="rapido_label_printer" class="form-control">
				<?php if ($rapido_label_printer) { ?>
				<option value="1" selected="selected"><?php echo $text_yes; ?></option>
				<option value="0"><?php echo $text_no; ?></option>
				<?php } else { ?>
				<option value="1"><?php echo $text_yes; ?></option>
				<option value="0" selected="selected"><?php echo $text_no; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="rapido_send_email"><?php echo $entry_send_email; ?></label>
			<div class="col-sm-10">
			  <select name="rapido_send_email" id="rapido_send_email" class="form-control">
				<?php if ($rapido_send_email) { ?>
				<option value="1" selected="selected"><?php echo $text_yes; ?></option>
				<option value="0"><?php echo $text_no; ?></option>
				<?php } else { ?>
				<option value="1"><?php echo $text_yes; ?></option>
				<option value="0" selected="selected"><?php echo $text_no; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="rapido_readiness"><span data-toggle="tooltip" title="<?php echo $help_readiness; ?>"><?php echo $entry_readiness; ?></span></label>
			<div class="col-sm-10">
			  <input type="text" name="rapido_readiness" value="<?php echo $rapido_readiness; ?>" placeholder="<?php echo $entry_readiness; ?>" id="rapido_readiness" class="form-control" />
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="rapido_default_weight"><?php echo $entry_default_weight; ?></label>
			<div class="col-sm-10">
			  <input type="text" name="rapido_default_weight" value="<?php echo $rapido_default_weight; ?>" placeholder="<?php echo $entry_default_weight; ?>" id="rapido_default_weight" class="form-control" />
			  <?php if ($error_default_weight) { ?>
			  <div class="text-danger"><?php echo $error_default_weight; ?></div>
			  <?php } ?>
			</div>
		  </div>
		  <div class="form-group">
			 <label class="col-sm-2 control-label" for="rapido_payer"><?php echo $entry_payer; ?></label>
			 <div class="col-sm-10">
			   <select name="rapido_payer" id="rapido_payer" class="form-control">
				 <?php foreach ($payers as $payer_id => $payer) { ?>
				 <?php if ($payer_id == $rapido_payer) { ?>
				 <option value="<?php echo $payer_id; ?>" selected="selected"><?php echo $payer; ?></option>
				 <?php } else { ?>
				 <option value="<?php echo $payer_id; ?>"><?php echo $payer; ?></option>
				 <?php } ?>
				 <?php } ?>
			  </select>
			 </div>
		  </div>
		  <div class="form-group">
			 <label class="col-sm-2 control-label" for="rapido_price_list"><?php echo $entry_price_list; ?></label>
			 <div class="col-sm-10">
			   <select name="rapido_price_list" id="rapido_price_list" class="form-control">
				 <?php foreach ($price_lists as $price_list_id => $price_list) { ?>
				 <?php if ($price_list_id == $rapido_price_list) { ?>
				 <option value="<?php echo $price_list_id; ?>" selected="selected"><?php echo $price_list; ?></option>
				 <?php } else { ?>
				 <option value="<?php echo $price_list_id; ?>"><?php echo $price_list; ?></option>
				 <?php } ?>
				 <?php } ?>
			  </select>
			 </div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="rapido_free_total"><?php echo $entry_free_total; ?></label>
			<div class="col-sm-10">
			  <input type="text" name="rapido_free_total" value="<?php echo $rapido_free_total; ?>" placeholder="<?php echo $entry_free_total; ?>" id="rapido_free_total" class="form-control" />
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="rapido_fixed_total_city"><?php echo $entry_fixed_total_city; ?></label>
			<div class="col-sm-10">
			  <input type="text" name="rapido_fixed_total_city" value="<?php echo $rapido_fixed_total_city; ?>" placeholder="<?php echo $entry_fixed_total_city; ?>" id="rapido_fixed_total_city" class="form-control" />
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="rapido_fixed_total_intercity"><?php echo $entry_fixed_total_intercity; ?></label>
			<div class="col-sm-10">
			  <input type="text" name="rapido_fixed_total_intercity" value="<?php echo $rapido_fixed_total_intercity; ?>" placeholder="<?php echo $entry_fixed_total_intercity; ?>" id="rapido_fixed_total_intercity" class="form-control" />
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="rapido_enable_suboten_raznos"><span data-toggle="tooltip" title="<?php echo $help_enable_suboten_raznos; ?>"><?php echo $entry_enable_suboten_raznos; ?></span></label>
			<div class="col-sm-10">
			  <select name="rapido_enable_suboten_raznos" id="rapido_enable_suboten_raznos" class="form-control">
				<?php if ($rapido_enable_suboten_raznos) { ?>
				<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
				<option value="0"><?php echo $text_disabled; ?></option>
				<?php } else { ?>
				<option value="1"><?php echo $text_enabled; ?></option>
				<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="rapido_check_before_pay"><span data-toggle="tooltip" title="<?php echo $help_check_before_pay; ?>"><?php echo $entry_check_before_pay; ?></span></label>
			<div class="col-sm-10">
			  <select name="rapido_check_before_pay" id="rapido_check_before_pay" class="form-control">
				<?php if ($rapido_check_before_pay) { ?>
				<option value="1" selected="selected"><?php echo $text_yes; ?></option>
				<option value="0"><?php echo $text_no; ?></option>
				<?php } else { ?>
				<option value="1"><?php echo $text_yes; ?></option>
				<option value="0" selected="selected"><?php echo $text_no; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="rapido_test_before_pay"><span data-toggle="tooltip" title="<?php echo $help_test_before_pay; ?>"><?php echo $entry_test_before_pay; ?></span></label>
			<div class="col-sm-10">
			  <select name="rapido_test_before_pay" id="rapido_test_before_pay" class="form-control">
				<?php if ($rapido_test_before_pay) { ?>
				<option value="1" selected="selected"><?php echo $text_yes; ?></option>
				<option value="0"><?php echo $text_no; ?></option>
				<?php } else { ?>
				<option value="1"><?php echo $text_yes; ?></option>
				<option value="0" selected="selected"><?php echo $text_no; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="rapido_money_transfer"><span data-toggle="tooltip" title="<?php echo $help_money_transfer; ?>"><?php echo $entry_money_transfer; ?></span></label>
			<div class="col-sm-10">
			  <select name="rapido_money_transfer" id="rapido_money_transfer" class="form-control">
				<?php if ($rapido_money_transfer) { ?>
				<option value="1" selected="selected"><?php echo $text_yes; ?></option>
				<option value="0"><?php echo $text_no; ?></option>
				<?php } else { ?>
				<option value="1"><?php echo $text_yes; ?></option>
				<option value="0" selected="selected"><?php echo $text_no; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			 <label class="col-sm-2 control-label" for="rapido_currency"><?php echo $entry_currency; ?></label>
			 <div class="col-sm-10">
			   <select name="rapido_currency" id="rapido_currency" class="form-control">
				 <?php foreach ($currencies as $currency) { ?>
				 <?php if ($currency['code'] == $rapido_currency) { ?>
				 <option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
				 <?php } else { ?>
				 <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
				 <?php } ?>
				 <?php } ?>
			  </select>
			 </div>
		  </div>
		  <div class="form-group">
			 <label class="col-sm-2 control-label" for="rapido_weight_class_id"><?php echo $entry_weight_class; ?></label>
			 <div class="col-sm-10">
			   <select name="rapido_weight_class_id" id="rapido_weight_class_id" class="form-control">
				 <?php foreach ($weight_classes as $weight_class) { ?>
				 <?php if ($weight_class['weight_class_id'] == $rapido_weight_class_id) { ?>
				 <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
				 <?php } else { ?>
				 <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
				 <?php } ?>
				 <?php } ?>
			  </select>
			 </div>
		  </div>
		  <div class="form-group">
			 <label class="col-sm-2 control-label" for="rapido_length_class_id"><?php echo $entry_length_class; ?></label>
			 <div class="col-sm-10">
			   <select name="rapido_length_class_id" id="rapido_length_class_id" class="form-control">
				 <?php foreach ($length_classes as $length_class) { ?>
				 <?php if ($length_class['length_class_id'] == $rapido_length_class_id) { ?>
				 <option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
				 <?php } else { ?>
				 <option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
				 <?php } ?>
				 <?php } ?>
			  </select>
			 </div>
		  </div>
		  <div class="form-group">
			 <label class="col-sm-2 control-label" for="rapido_order_status_id"><?php echo $entry_order_status; ?></label>
			 <div class="col-sm-10">
			   <select name="rapido_order_status_id" id="rapido_order_status_id" class="form-control">
				 <?php foreach ($order_statuses as $order_status) { ?>
				 <?php if ($order_status['order_status_id'] == $rapido_order_status_id) { ?>
				 <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
				 <?php } else { ?>
				 <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
				 <?php } ?>
				 <?php } ?>
			  </select>
			 </div>
		  </div>
		  <div class="form-group">
			 <label class="col-sm-2 control-label" for="rapido_order_status_courier_id"><?php echo $entry_order_status_courier; ?></label>
			 <div class="col-sm-10">
			   <select name="rapido_order_status_courier_id" id="rapido_order_status_courier_id" class="form-control">
				 <?php foreach ($order_statuses as $order_status) { ?>
				 <?php if ($order_status['order_status_id'] == $rapido_order_status_courier_id) { ?>
				 <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
				 <?php } else { ?>
				 <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
				 <?php } ?>
				 <?php } ?>
			  </select>
			 </div>
		  </div>
		  <div class="form-group">
			 <label class="col-sm-2 control-label" for="rapido_order_status_cod_id"><?php echo $entry_order_status_cod; ?></label>
			 <div class="col-sm-10">
			   <select name="rapido_order_status_cod_id" id="rapido_order_status_cod_id" class="form-control">
				 <?php foreach ($order_statuses as $order_status) { ?>
				 <?php if ($order_status['order_status_id'] == $rapido_order_status_cod_id) { ?>
				 <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
				 <?php } else { ?>
				 <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
				 <?php } ?>
				 <?php } ?>
			  </select>
			 </div>
		  </div>
		  <div class="form-group">
			 <label class="col-sm-2 control-label" for="rapido_geo_zone_id"><?php echo $entry_geo_zone; ?></label>
			 <div class="col-sm-10">
			   <select name="rapido_geo_zone_id" id="rapido_geo_zone_id" class="form-control">
				 <option value="0"><?php echo $text_all_zones; ?></option>
				 <?php foreach ($geo_zones as $geo_zone) { ?>
				 <?php if ($geo_zone['geo_zone_id'] == $rapido_geo_zone_id) { ?>
				 <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
				 <?php } else { ?>
				 <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
				 <?php } ?>
				 <?php } ?>
			  </select>
			 </div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="rapido_status"><?php echo $entry_status; ?></label>
			<div class="col-sm-10">
			  <select name="rapido_status" id="rapido_status" class="form-control">
				<?php if ($rapido_status) { ?>
				<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
				<option value="0"><?php echo $text_disabled; ?></option>
				<?php } else { ?>
				<option value="1"><?php echo $text_enabled; ?></option>
				<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				<?php } ?>
			  </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="rapido_sort_order"><?php echo $entry_sort_order; ?></label>
			<div class="col-sm-10">
			  <input type="text" name="rapido_sort_order" value="<?php echo $rapido_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="rapido_sort_order" class="form-control" />
			</div>
		  </div>
		</form>
	  </div>
	</div>
  </div>
</div>

<script type="text/javascript"><!--
function getNomenclature(stage, offset, countryid_iso) {
	$('#rapido_nomenclature_count').after('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');

	$.ajax({
		url: 'index.php?route=shipping/rapido/nomenclature&token=<?php echo $token; ?>',
		type: 'POST',
		data: 'rapido_username=' + encodeURIComponent($('#rapido_username').val()) + '&rapido_password=' + encodeURIComponent($('#rapido_password').val()) + '&rapido_test=' + encodeURIComponent($('#rapido_test').val()) + '&stage=' + stage + '&offset=' + offset + '&count=' + encodeURIComponent($('#rapido_nomenclature_count').val()) + '&countryid_iso=' + countryid_iso,
		dataType: 'json',
		success: function(data) {
			if (data) {
				if (data.error) {
					$('.text-success, .text-danger, .attention').remove();
					$('#rapido_nomenclature').after('<div class="text-danger" style="display: none;">' + data.error + '</div>');
					$('.text-danger').fadeIn('slow');
				} else if (data.info) {
					$('.text-success, .text-danger, .attention').remove();
					$('#rapido_nomenclature').after('<div class="text-success" style="display: none;">' + data.info + '</div>');
					$('.text-success').fadeIn('slow');

					if (data.stage) {
						getNomenclature(data.stage, data.offset, data.countryid_iso);
					}
				}
			}
		},
		error: function(request) {
			$('.text-success, .text-danger, .attention').remove();
			$('#rapido_nomenclature').after('<div class="text-danger" style="display: none;"><?php echo $error_general; ?></div>');
			$('.text-danger').fadeIn('slow');
		}
	});
}

var my_objects = <?php echo json_encode($my_objects); ?>;

function fillCity() {
	index = $('#rapido_sender_office_id').val();

	if (my_objects[index]) {
		$('#rapido_sender_office').val(my_objects[index]['OFFICENAME']);
		$('#rapido_sender_city').val(my_objects[index]['CITY']);
		$('#rapido_sender_city_id').val(my_objects[index]['SITEID']);
		$('#rapido_sender_postcode').val(my_objects[index]['POSTCODE']);
		$('#rapido_sender_office_default').val(my_objects[index]['default']);
	}
}

$('#rapido_sender_office_id').trigger('change');

function getSubservices() {
	$('#rapido_subservices_button').after('<div class="attention"><img src="view/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');

	$.ajax({
		url: 'index.php?route=shipping/rapido/getSubservices&token=<?php echo $token; ?>',
		type: 'POST',
		data: 'rapido_username=' + encodeURIComponent($('#rapido_username').val()) + '&rapido_password=' + encodeURIComponent($('#rapido_password').val()) + '&rapido_test=' + encodeURIComponent($('#rapido_test').val()),
		dataType: 'json',
		success: function(data) {
			if (data) {
				if (data.error) {
					$('.text-success, .text-danger, .attention').remove();
					$('#rapido_subservices_button').after('<div class="text-danger" style="display: none;">' + data.error + '</div>');
					$('.text-danger').fadeIn('slow');
				} else {

					// Offices
					html = '';
					$.each(data.my_objects,function(key, value) {
						html += '<option value="' + key + '">' + value['OFFICENAME'] + '</option>';
					});
					$('#rapido_sender_office_id').html(html);

					my_objects = data.my_objects;
					fillCity();

					$('#rapido_sender_office_id').parent().parent().show();
					$('#rapido_sender_city').parent().parent().show();

					//Subservices City
					html = '';
					$.each(data.subservices_city,function(subservice_id, subservice_name) {
						html += '<div class="checkbox">';
						html += '  <label>';
						html += '    <input type="checkbox" name="rapido_subservices_city[]" value="' + subservice_id + '" />' + subservice_name;
						html += '  </label>';
						html += '</div>';
					});
					$('#rapido_subservices_city').html(html);

					//Subservices City
					html = '';
					$.each(data.subservices_intercity,function(subservice_id, subservice_name) {
						html += '<div class="checkbox">';
						html += '  <label>';
						html += '    <input type="checkbox" name="rapido_subservices_intercity[]" value="' + subservice_id + '" />' + subservice_name;
						html += '  </label>';
						html += '</div>';
					});
					$('#rapido_subservices_intercity').html(html);

					$('.text-success, .text-danger, .attention').remove();
					$('#rapido_subservices_button').after('<div class="text-success" style="display: none;"><?php echo $text_subservices_success; ?></div>');
					$('.text-success').fadeIn('slow');

				}
			}
		},
		error: function(request) {
			$('.text-success, .text-danger, .attention').remove();
			$('#rapido_subservices_button').after('<div class="text-danger" style="display: none;"><?php echo $error_general; ?></div>');
			$('.text-danger').fadeIn('slow');
		}
	});
}

$('#rapido_auto_print').change(function () {
	if ($(this).val() > 0) {
		$('#rapidocarrier_printer_row').show();
	} else {
		$('#rapidocarrier_printer_row').hide();
	}
});

$('#rapido_auto_print').trigger('change');

//--></script>
<?php echo $footer; ?>