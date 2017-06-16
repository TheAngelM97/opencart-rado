<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
	<div class="container-fluid">
	  <div class="pull-right">
		<a onclick="$('#form-rapido :input').removeAttr('disabled'); $('#form-rapido').submit();" class="btn btn-primary"><?php echo $button_create; ?></a>
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
	<div class="panel panel-default">
	 <div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
	 </div>
	 <div class="panel-body">
	  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-rapido" class="form-horizontal">
		<div class="form-group required">
			<label class="col-sm-3 control-label" for="rapido_content"><?php echo $entry_content; ?></label>
			<div class="col-sm-9">
			  <input class="form-control" type="text" id="rapido_content" name="content" value="<?php echo $content; ?>" />
			  <?php if ($error_content) { ?>
			  <span class="text-danger"><?php echo $error_content; ?></span>
			  <?php } ?>
			</div>
		</div>
		<div class="form-group required">
			<label class="col-sm-3 control-label" for="rapido_weight"><?php echo $entry_weight; ?></label>
			<div class="col-sm-9">
			  <input class="form-control" type="text" id="rapido_weight" name="weight" value="<?php echo $weight; ?>" />
			  <?php if ($error_weight) { ?>
			  <span class="text-danger"><?php echo $error_weight; ?></span>
			  <?php } ?>
			</div>
		</div>
		<div class="form-group required">
			<label class="col-sm-3 control-label" for="rapido_count"><?php echo $entry_count; ?></label>
			<div class="col-sm-9">
			  <input class="form-control" type="text" id="rapido_count" name="count" value="<?php echo $count; ?>" />
			  <?php if ($error_count) { ?>
			  <span class="text-danger"><?php echo $error_count; ?></span>
			  <?php } ?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label" for="rapido_width"><?php echo $entry_size; ?></label>
			<div class="col-sm-1">
			  <input class="form-control" type="text" id="rapido_width" name="width" value="<?php echo $width; ?>" />
			  <?php if ($error_size) { ?>
			  <span class="text-danger"><?php echo $error_size; ?></span>
			  <?php } ?>
			</div>
			<label class="col-sm-1 control-label">x</label>
			<div class="col-sm-1">
			  <input class="form-control" type="text" name="length" value="<?php echo $length; ?>" />
			</div>
			<label class="col-sm-1 control-label">x</label>
			<div class="col-sm-1">
			  <input class="form-control" type="text" name="height" value="<?php echo $height; ?>" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label" for="rapido_insurance"><?php echo $entry_insurance; ?></label>
			<div class="col-sm-9">
			  <select class="form-control" id="rapido_insurance" name="insurance" onchange="$('#rapido_insurance_total').parent().parent().toggle();">
				<?php if ($insurance) { ?>
				<option value="1" selected="selected"><?php echo $text_yes; ?></option>
				<option value="0"><?php echo $text_no; ?></option>
				<?php } else { ?>
				<option value="1"><?php echo $text_yes; ?></option>
				<option value="0" selected="selected"><?php echo $text_no; ?></option>
				<?php } ?>
			  </select>
			</div>
		</div>
		<div class="form-group" <?php if (!$insurance) { ?> style="display: none;"<?php } ?> >
			<label class="col-sm-3 control-label" for="rapido_insurance_total"><?php echo $entry_insurance_total; ?></label>
			<div class="col-sm-9">
			  <input class="form-control" type="text" id="rapido_insurance_total" name="insurance_total" value="<?php echo $insurance_total; ?>" />
			</div>
		</div>
		<div class="form-group" >
			<label class="col-sm-3 control-label" for="rapido_fragile"><?php echo $entry_fragile; ?></label>
			<div class="col-sm-9">
			  <select class="form-control" id="rapido_fragile" name="fragile">
				<?php if ($fragile) { ?>
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
			<label class="col-sm-3 control-label"><?php echo $entry_cod; ?></label>
			<div class="col-sm-9">
			  <label class="radio-inline" style="display: inline; width: auto; float: none;">
				<input type="radio" id="rapido_cod_yes" name="cod" value="1" <?php if ($cod) { ?> checked="checked"<?php } ?> onclick="$(this).parent().parent().parent().next().show();" />
				<?php echo $text_yes; ?>
			  </label>
			  <label class="radio-inline" style="display: inline; width: auto; float: none;">
				<input type="radio" id="rapido_cod_no" name="cod" value="0" <?php if (!$cod) { ?> checked="checked"<?php } ?> onclick="$(this).parent().parent().parent().next().hide();" />
				<?php echo $text_no; ?>
			  </label>   
			</div>
		</div>
		<div class="form-group" <?php if (!$cod) { ?> style="display: none;"<?php } ?> >
			<label class="col-sm-3 control-label" for="rapido_total"><?php echo $entry_cod_total; ?></label>
			<div class="col-sm-9">
			  <input class="form-control" type="text" id="rapido_total" name="total" value="<?php echo $total; ?>" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label" for="rapido_payer"><?php echo $entry_payer; ?></label>
			<div class="col-sm-9">
			  <select class="form-control" id="rapido_payer" name="payer">
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
		<div class="form-group" <?php if (count($my_objects) <= 1 && !$error_sender_city) { ?> style="display: none;"<?php } ?>>
			<label class="col-sm-3 control-label" for="rapido_sender_office_id"><?php echo $entry_sender_office; ?></label>
			<div class="col-sm-9">
			  <select class="form-control" id="rapido_sender_office_id" name="sender_office_id" onchange="fillCity();">
				<?php foreach ($my_objects as $my_object_id => $my_object) { ?>
				<?php if ($my_object_id == $sender_office_id) { ?>
				<option value="<?php echo $my_object_id; ?>" selected="selected"><?php echo $my_object['OFFICENAME']; ?></option>
				<?php } else { ?>
				<option value="<?php echo $my_object_id; ?>"><?php echo $my_object['OFFICENAME']; ?></option>
				<?php } ?>
				<?php } ?>
			  </select>
			  <input type="hidden" id="rapido_sender_office" name="sender_office" value="<?php echo $sender_office; ?>" />
			  <input type="hidden" id="rapido_sender_office_default" name="sender_office_default" value="<?php echo $sender_office_default; ?>" />
			</div>
		</div>
		<div class="form-group required" <?php if (count($my_objects) <= 1 && !$error_sender_city) { ?> style="display: none;"<?php } ?>>
			<label class="col-sm-3 control-label" for="rapido_sender_city"><?php echo $entry_sender_city; ?></label>
			<div class="col-sm-7">
			  <input class="form-control" type="text" id="rapido_sender_city" name="sender_city" value="<?php echo $sender_city; ?>" />
			</div>
			<input type="hidden" id="rapido_sender_city_id" name="sender_city_id" value="<?php echo $sender_city_id; ?>" />
			<label class="col-sm-1 control-label" for="rapido_sender_postcode"><?php echo $entry_sender_postcode; ?></label>
			<div class="col-sm-1">
			  <input class="form-control" type="text" id="rapido_sender_postcode" name="sender_postcode" value="<?php echo $sender_postcode; ?>" disabled="disabled" />
			</div>
		</div>
		<div class="form-group" <?php if (count($my_objects) <= 1 || $sender_office_default) { ?> style="display: none;"<?php } ?>>
			<label class="col-sm-3 control-label" for="rapido_sendhour"><?php echo $entry_sendtime; ?></label>
			<div class="col-sm-2">
			  <select id="rapido_sendhour" name="sendhour" class="form-control">
				<?php for ($i = 0; $i <= 23; $i++) { ?>
				<?php $hour = str_pad($i, 2, '0', STR_PAD_LEFT); ?>
				<?php if ($hour == $sendhour) { ?>
				<option value="<?php echo $hour; ?>" selected="selected"><?php echo $hour; ?></option>
				<?php } else { ?>
				<option value="<?php echo $hour; ?>"><?php echo $hour; ?></option>
				<?php } ?>
				<?php } ?>
			  </select>
			</div>
			<div class="col-sm-2">
			  <select id="rapido_sendmin" name="sendmin" class="form-control">
				<?php for ($i = 0; $i <= 59; $i++) { ?>
				<?php $mins = str_pad($i, 2, '0', STR_PAD_LEFT); ?>
				<?php if ($mins == $sendmin) { ?>
				<option value="<?php echo $mins; ?>" selected="selected"><?php echo $mins; ?></option>
				<?php } else { ?>
				<option value="<?php echo $mins; ?>"><?php echo $mins; ?></option>
				<?php } ?>
				<?php } ?>
			  </select>
			  <?php if ($error_sendtime) { ?>
			  <span class="text-danger"><?php echo $error_sendtime; ?></span>
			  <?php } ?>
			</div>
		</div>
		<div class="form-group" <?php if (count($my_objects) <= 1 || $sender_office_default) { ?> style="display: none;"<?php } ?>>
			<label class="col-sm-3 control-label" for="rapido_workhour"><?php echo $entry_worktime; ?></label>
			<div class="col-sm-2">
			  <select id="rapido_workhour" name="workhour" class="form-control">
				<?php for ($i = 0; $i <= 23; $i++) { ?>
				<?php $hour = str_pad($i, 2, '0', STR_PAD_LEFT); ?>
				<?php if ($hour == $workhour) { ?>
				<option value="<?php echo $hour; ?>" selected="selected"><?php echo $hour; ?></option>
				<?php } else { ?>
				<option value="<?php echo $hour; ?>"><?php echo $hour; ?></option>
				<?php } ?>
				<?php } ?>
			  </select>
			</div>
			<div class="col-sm-2">
			  <select id="rapido_workmin" name="workmin" class="form-control">
				<?php for ($i = 0; $i <= 59; $i++) { ?>
				<?php $mins = str_pad($i, 2, '0', STR_PAD_LEFT); ?>
				<?php if ($mins == $workmin) { ?>
				<option value="<?php echo $mins; ?>" selected="selected"><?php echo $mins; ?></option>
				<?php } else { ?>
				<option value="<?php echo $mins; ?>"><?php echo $mins; ?></option>
				<?php } ?>
				<?php } ?>
			  </select>
			  <?php if ($error_worktime) { ?>
			  <span class="text-danger"><?php echo $error_worktime; ?></span>
			  <?php } ?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label"><?php echo $entry_shipping_to; ?></label>
			<div class="col-sm-9">
			  <label class="radio-inline" style="display: inline; width: auto; float: none;">
				<input type="radio" id="rapido_shipping_to_door" name="take_office" value="0"  <?php if (!$take_office) { ?> checked="checked"<?php } ?> onclick="$('#rapido_region_container,#rapido_quarter_container,#rapido_street_container,#rapido_block_no_container,#rapido_additional_info_container').show(); $('#rapido_office_container').hide();" />
				<?php echo $text_to_door; ?>
			  </label>
			  <label class="radio-inline" style="display: inline; width: auto; float: none;">
				<input type="radio" id="rapido_shipping_take_office" name="take_office" value="1" <?php if ($take_office) { ?> checked="checked"<?php } ?> onclick="$('#rapido_region_container,#rapido_quarter_container,#rapido_street_container,#rapido_block_no_container,#rapido_additional_info_container').hide(); $('#rapido_office_container').show();" />
				<?php echo $text_to_office; ?>
			  </label>   
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label" for="rapido_country_id"><?php echo $entry_country; ?></label>
			<div class="col-sm-9">
			  <select class="form-control" id="rapido_country_id" name="country_id" onchange="rapidoClearAddress();">
				<?php foreach ($countries as $country) { ?>
				<?php if ($country['COUNTRYID_ISO'] == $country_id) { ?>
				<option value="<?php echo $country['COUNTRYID_ISO']; ?>" selected="selected"><?php echo $country['COUNTRYNAME']; ?></option>
				<?php } else { ?>
				<option value="<?php echo $country['COUNTRYID_ISO']; ?>"><?php echo $country['COUNTRYNAME']; ?></option>
				<?php } ?>
				<?php } ?>
			  </select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label" for="rapido_city"><?php echo $entry_city; ?></label>
			<div class="col-sm-7">
			  <input class="form-control" type="text" id="rapido_city" name="city" value="<?php echo $city; ?>" />
			</div>
			<input type="hidden" id="rapido_city_id" name="city_id" value="<?php echo $city_id; ?>" />
			<label class="col-sm-1 control-label" for="rapido_postcode"><?php echo $entry_postcode; ?></label>
			<div class="col-sm-1">
			  <input class="form-control" type="text" id="rapido_postcode" name="postcode" value="<?php echo $postcode; ?>" disabled="disabled" />
			</div>
		</div>
		<div class="form-group" id="rapido_region_container" <?php if ($take_office) { ?> style="display: none;"<?php } ?> >
			<label class="col-sm-3 control-label" for="rapido_region"><?php echo $entry_region; ?></label>
			<div class="col-sm-9">
			  <input class="form-control" type="text" id="rapido_region" name="region" value="<?php echo $region; ?>" disabled="disabled" />
			</div>
		</div>
		<div class="form-group" id="rapido_quarter_container" <?php if ($take_office) { ?> style="display: none;"<?php } ?> >
			<label class="col-sm-3 control-label" for="rapido_quarter"><?php echo $entry_quarter; ?></label>
			<div class="col-sm-9">
			  <input class="form-control" type="text" id="rapido_quarter" name="quarter" value="<?php echo $quarter; ?>" />
			  <input type="hidden" id="rapido_quarter_id" name="quarter_id" value="<?php echo $quarter_id; ?>" />
			</div>
		</div>
		<div class="form-group" id="rapido_street_container" <?php if ($take_office) { ?> style="display: none;"<?php } ?> >
			<label class="col-sm-3 control-label" for="rapido_street"><?php echo $entry_street; ?></label>
			<div class="col-sm-7">
			  <input class="form-control" type="text" id="rapido_street" name="street" value="<?php echo $street; ?>" />
			  <input type="hidden" id="rapido_street_id" name="street_id" value="<?php echo $street_id; ?>" />
			  <?php if ($error_address) { ?>
				<span class="text-danger"><?php echo $error_address; ?></span>
			  <?php } ?>
			</div>
			<label class="col-sm-1 control-label" for="rapido_street_no"><?php echo $entry_street_no; ?></label>
			<div class="col-sm-1">
			  <input class="form-control" type="text" id="rapido_street_no" name="street_no" value="<?php echo $street_no; ?>" />
			</div>
		</div>
		<div class="form-group" id="rapido_block_no_container" <?php if ($take_office) { ?> style="display: none;"<?php } ?> >
			<label class="col-sm-3 control-label" for="rapido_block_no"><?php echo $entry_block_no; ?></label>
			<div class="col-sm-3">
			  <input class="form-control" type="text" id="rapido_block_no" name="block_no" value="<?php echo $block_no; ?>" />
			</div>
			<label class="col-sm-1 control-label" for="rapido_entrance_no"><?php echo $entry_entrance_no; ?></label>
			<div class="col-sm-1">
			  <input class="form-control" type="text" id="rapido_entrance_no" name="entrance_no" value="<?php echo $entrance_no; ?>" />
			</div>
			<label class="col-sm-1 control-label" for="rapido_floor_no"><?php echo $entry_floor_no; ?></label>
			<div class="col-sm-1">
			  <input class="form-control" type="text" id="rapido_floor_no" name="floor_no" value="<?php echo $floor_no; ?>" />
			</div>
			<label class="col-sm-1 control-label" for="rapido_apartment_no"><?php echo $entry_apartment_no; ?></label>
			<div class="col-sm-1">
			  <input class="form-control" type="text" id="rapido_apartment_no" name="apartment_no" value="<?php echo $apartment_no; ?>" />
			</div>
		</div>
		<div class="form-group" id="rapido_additional_info_container" <?php if ($take_office) { ?> style="display: none;"<?php } ?> >
			<label class="col-sm-3 control-label" for="rapido_additional_info"><?php echo $entry_additional_info; ?></label>
			<div class="col-sm-9">
			  <input class="form-control" type="text" id="rapido_additional_info" name="additional_info" value="<?php echo $additional_info; ?>" />
			</div>
		</div>
		<div class="form-group" id="rapido_office_container" <?php if (!$take_office) { ?> style="display: none;"<?php } ?> >
			<label class="col-sm-3 control-label" for="rapido_office_id"><?php echo $entry_office; ?></label>
			<div class="col-sm-9">
			  <select class="form-control" id="rapido_office_id" name="office_id">
				<?php if (!$offices) { ?>
					<option value="0" selected="selected"><?php echo $text_select_city; ?></option>
				<?php } ?>
				<?php foreach ($offices as $office) { ?>
				<?php if ($office['id'] == $office_id) { ?>
				<option value="<?php echo $office['id']; ?>" selected="selected"><?php echo $office['label']; ?></option>
				<?php } else { ?>
				<option value="<?php echo $office['id']; ?>"><?php echo $office['label']; ?></option>
				<?php } ?>
				<?php } ?>
			  </select>
			  <?php if ($error_office) { ?>
			    <br />&nbsp;&nbsp;&nbsp;<span class="text-danger"><?php echo $error_office; ?></span>
			  <?php } ?>
			</div>
		</div>
		<div class="form-group" id="rapido_fixed_time" <?php if (!$fixed_time) { ?> style="display: none;"<?php } ?> >
			<label class="col-sm-3 control-label">
			  <input type="checkbox" id="rapido_fixed_time_cb" name="fixed_time_cb" value="1" <?php if ($fixed_time_cb) { ?> checked="checked"<?php } ?> onclick="rapidoCheckFixedTime();" />
			  <?php echo $entry_fixed_time; ?>
			</label>
			<div class="col-sm-2">
			  <select id="rapido_fixed_time_type" name="fixed_time_type" class="form-control" <?php if (!$fixed_time_cb) { ?> disabled="disabled"<?php } ?> onchange="rapidoSetFixedTime();">
				<?php foreach ($fixed_time_types as $fixed_time_type_id => $fixed_time_type_label) { ?>
				<?php if ($fixed_time_type_id == $fixed_time_type || !$fixed_time_type) { ?>
				<?php $fixed_time_type = $fixed_time_type_id; ?>
				<option value="<?php echo $fixed_time_type_id; ?>" selected="selected"><?php echo $fixed_time_type_label; ?></option>
				<?php } else { ?>
				<option value="<?php echo $fixed_time_type_id; ?>"><?php echo $fixed_time_type_label; ?></option>
				<?php } ?>
				<?php } ?>
			  </select>
			  <?php if ($error_fixed_time) { ?>
			    <br />&nbsp;&nbsp;&nbsp;<span class="text-danger"><?php echo $error_fixed_time; ?></span>
			  <?php } ?>
			</div>
			<div class="col-sm-1">
			  <select id="rapido_fixed_time_hour" name="fixed_time_hour" class="form-control" <?php if (!$fixed_time_cb) { ?> disabled="disabled"<?php } ?> onchange="rapidoSetFixedTime();">
				<?php for ($i = 10; $i <= 17; $i++) { ?>
				<?php if ($i == $fixed_time_hour || !$fixed_time_hour) { ?>
				<?php $fixed_time_hour = $i; ?>
				<option value="<?php echo $i; ?>" selected="selected"><?php echo $i; ?></option>
				<?php } else { ?>
				<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php } ?>
				<?php } ?>
			  </select>
			</div>
			<div class="col-sm-1">
			  <select id="rapido_fixed_time_min" name="fixed_time_min" class="form-control" <?php if (!$fixed_time_cb) { ?> disabled="disabled"<?php } ?>>
				<?php $min_fixed_time_mins = ($fixed_time_hour == 10 && $fixed_time_type == 'before' ? 30 : 0); ?>
				<?php for ($i = $min_fixed_time_mins; $i <= 59; $i+=15) { ?>
				<?php $mins = str_pad($i, 2, '0', STR_PAD_LEFT); ?>
				<?php if ($mins == $fixed_time_min) { ?>
				<option value="<?php echo $mins; ?>" selected="selected"><?php echo $mins; ?></option>
				<?php } else { ?>
				<option value="<?php echo $mins; ?>"><?php echo $mins; ?></option>
				<?php } ?>
				<?php } ?>
			  </select>
			</div>
		</div>
		<div class="form-group" >
			<label class="col-sm-3 control-label" for="rapido_check_before_pay"><?php echo $entry_check_before_pay; ?></label>
			<div class="col-sm-9">
			  <select class="form-control" id="rapido_check_before_pay" name="check_before_pay">
				<?php if ($check_before_pay) { ?>
				<option value="1" selected="selected"><?php echo $text_yes; ?></option>
				<option value="0"><?php echo $text_no; ?></option>
				<?php } else { ?>
				<option value="1"><?php echo $text_yes; ?></option>
				<option value="0" selected="selected"><?php echo $text_no; ?></option>
				<?php } ?>
			  </select>
			</div>
		</div>
		<div class="form-group" >
			<label class="col-sm-3 control-label" for="rapido_test_before_pay"><?php echo $entry_test_before_pay; ?></label>
			<div class="col-sm-9">
			  <select class="form-control" id="rapido_test_before_pay" name="test_before_pay">
				<?php if ($test_before_pay) { ?>
				<option value="1" selected="selected"><?php echo $text_yes; ?></option>
				<option value="0"><?php echo $text_no; ?></option>
				<?php } else { ?>
				<option value="1"><?php echo $text_yes; ?></option>
				<option value="0" selected="selected"><?php echo $text_no; ?></option>
				<?php } ?>
			  </select>
			</div>
		</div>
		<div class="form-group" <?php if (!$enable_suboten_raznos) { ?>style="display: none;"<?php } ?> >
			<label class="col-sm-3 control-label" for="rapido_suboten_raznos"><?php echo $entry_suboten_raznos; ?></label>
			<div class="col-sm-9">
			  <select class="form-control" id="rapido_suboten_raznos" name="suboten_raznos">
				<?php if ($suboten_raznos) { ?>
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
			<label class="col-sm-3 control-label" for="rapido_label_printer"><?php echo $entry_label_printer; ?></label>
			<div class="col-sm-9">
				<select class="form-control" id="rapido_label_printer" name="label_printer">
					<?php if ($label_printer) { ?>
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
			<label class="col-sm-3 control-label" for="rapido_pazar"><?php echo $entry_pazar; ?></label>
			<div class="col-sm-9">
			  <input class="form-control" type="text" id="rapido_pazar" name="pazar" value="<?php echo $pazar; ?>" />
			</div>
		</div>
		<?php if (!empty($quote)) { ?>
		<div class="form-group">
			<label class="col-sm-3 control-label"><?php echo $entry_shipping_method; ?></label>
			<div class="col-sm-9">
			  <?php foreach ($quote['quote'] as $shipping_method_key_id => $shipping_method) { ?>
			  <div class="radio">
				<label>
				  <input type="radio" id="<?php echo $shipping_method['code']; ?>" name="shipping_method" value="<?php echo $shipping_method['code']; ?>" <?php if ($shipping_method_key_id == $shipping_method_id) { ?> checked="checked"<?php } ?> />
				  <?php echo $shipping_method['title']; ?>&nbsp;<?php echo $shipping_method['text']; ?>
				</label>
			  </div>    
			  <?php } ?>
			</div>
		</div>
		<?php } ?>
		<div class="form-group">
			<b><label class="col-sm-3 control-label"><?php echo $text_calculate; ?></label></b>
			<div class="col-sm-9">
			  <input type="hidden" id="calculate" name="calculate" value="0" />
			  <a onclick="$('#calculate').val('1'); $('#form-rapido :input').removeAttr('disabled'); $('#form-rapido').submit();" class="btn btn-primary"><?php echo $button_calculate; ?></a>
			</div>
		</div>
	  </form>
	 </div>
	</div>
  </div>
</div>


<script type="text/javascript"><!--
var my_objects = <?php echo json_encode($my_objects); ?>;

function fillCity() {
	index = $('#rapido_sender_office_id').val();
	if (my_objects[index]) {
		$('#rapido_sender_office').val(my_objects[index]['OFFICENAME']);
		$('#rapido_sender_city').val(my_objects[index]['CITY']);
		$('#rapido_sender_city_id').val(my_objects[index]['SITEID']);
		$('#rapido_sender_postcode').val(my_objects[index]['POSTCODE']);
		$('#rapido_sender_office_default').val(my_objects[index]['default']);

		if (my_objects[index]['default']) {
			$('#rapido_sendhour').parent().parent().hide();
			$('#rapido_workhour').parent().parent().hide();
		} else {
			$('#rapido_sendhour').parent().parent().show();
			$('#rapido_workhour').parent().parent().show();
		}
	}
}

$('#rapido_sender_office_id').trigger('change');

function rapidoCheckFixedTime() {
	if ($('#rapido_fixed_time_cb:checked').length) {
		$('#rapido_fixed_time_type').removeAttr('disabled');
		$('#rapido_fixed_time_hour').removeAttr('disabled');
		$('#rapido_fixed_time_min').removeAttr('disabled');
	} else {
		$('#rapido_fixed_time_type').prop('disabled', 'disabled');
		$('#rapido_fixed_time_hour').prop('disabled', 'disabled');
		$('#rapido_fixed_time_min').prop('disabled', 'disabled');
	}
}

function rapidoSetFixedTime() {
	if ($('#rapido_fixed_time_hour').val() == 10 && $('#rapido_fixed_time_type').val() == 'before') {
		min_fixed_time_mins = 30;
	} else {
		min_fixed_time_mins = 0;
	}

	html = '';

	for (i = min_fixed_time_mins; i <= 59; i+=15) {
		iStr = i.toString();

		if (iStr.length < 2) {
			fixed_time_min = '0' + i;
		} else {
			fixed_time_min = i;
		}

		html += '<option value="' + fixed_time_min + '">' + fixed_time_min + '</option>';
	}

	$('#rapido_fixed_time_min').html(html);
}

function rapidoClearAddress() {
	$('#rapido_city').val('');
	$('#rapido_city_id').val('');
	$('#rapido_region').val('');
	$('#rapido_postcode').val('');
	$('#rapido_office_id').html('<option value="0"><?php echo $text_select_city; ?></option>');
	$('#rapido_quarter').val('');
	$('#rapido_quarter_id').val('');
	$('#rapido_street').val('');
	$('#rapido_street_id').val('');
	$('#rapido_street_no').val('');
	$('#rapido_block_no').val('');
	$('#rapido_entrance_no').val('');
	$('#rapido_floor_no').val('');
	$('#rapido_apartment_no').val('');
	$('#rapido_additional_info').val('');

	/*
	if ($('#rapido_country_id').val() != '<?php echo Rapido::BULGARIA; ?>') {
		$('#rapido_quarter, #rapido_street').autocomplete('option', 'disabled', true);
	} else {
		$('#rapido_quarter, #rapido_street').autocomplete('option', 'disabled', false);
	}
	*/
}

var rapido_city = '<?php echo $city; ?>';
var rapido_quarter = '<?php echo $quarter; ?>';
var rapido_street = '<?php echo $street; ?>';

$(document).ready(function() {
	$('#rapido_city').autocomplete({
		'source': function(request, response) {
			if (request) {
				$.ajax({
					url: 'index.php?route=sale/rapido/city&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request) + '&filter_country_id=' + encodeURIComponent($('#rapido_country_id').val()),
					dataType: 'json',
					success: function(json) {
						if ($('#rapido_city').is(":focus")) {
							response($.map(json, function(item) {
								return {
									label:        item['label'],
									value:        item['value'],
									id:           item['id'],
									postcode:     item['postcode'],
									region:       item['region'],
								}
							}));
						}
					}
				});
			}
		},
		'select': function(item) {
			if (item) {
				rapido_city = item.value;
				$('#rapido_city').val(item.value);
				$('#rapido_city_id').val(item.id);
				$('#rapido_region').val(item.region);
				$('#rapido_postcode').val(item.postcode);
				$('#rapido_quarter').val('');
				$('#rapido_quarter_id').val('');
				$('#rapido_street').val('');
				$('#rapido_street_id').val('');
				$('#rapido_street_no').val('');
				$('#rapido_block_no').val('');
				$('#rapido_entrance_no').val('');
				$('#rapido_floor_no').val('');
				$('#rapido_apartment_no').val('');
				$('#rapido_additional_info').val('');
				$('#rapido_office_id').html('<option value="0"><?php echo $text_wait; ?></option>');

				$.ajax({
					url: 'index.php?route=sale/rapido/office&token=<?php echo $token; ?>&filter_city_id=' + encodeURIComponent(item.id),
					dataType: 'json',
					success: function(json) {
						if (json.error) {
							alert(json.error);
						} else {
							html = '';

							if (json.length) {
								for (i = 0; i < json.length; i++) {
									html += '<option value="' + json[i]['id'] + '">' + json[i]['label'] + '</option>';
								}
							} else {
								html += '<option value="0"><?php echo $text_select_city; ?></option>';
							}

							$('#rapido_office_id').html(html);
						}
					}
				});

				$.ajax({
					url: 'index.php?route=sale/rapido/fixedTime&token=<?php echo $token; ?>&filter_city_id=' + encodeURIComponent(item.id),
					dataType: 'json',
					success: function(json) {
						if (json.error) {
							alert(json.error);
						} else {
							if (json.result == 1) {
								$('#rapido_fixed_time').show();
							} else if (json.result == 0) {
								$('#rapido_fixed_time_cb').prop('checked', false);
								$('#rapido_fixed_time').hide();
							}
						}
					}
				});
			}
		},
		'change': function(item) {
			if(!item) {
				$('#rapido_city').val('');
				$('#rapido_city_id').val('');
				$('#rapido_region').val('');
				$('#rapido_postcode').val('');
				$('#rapido_office_id').html('<option value="0"><?php echo $text_select_city; ?></option>');
			}

			$('#rapido_quarter').val('');
			$('#rapido_quarter_id').val('');
			$('#rapido_street').val('');
			$('#rapido_street_id').val('');
			$('#rapido_street_no').val('');
			$('#rapido_block_no').val('');
			$('#rapido_entrance_no').val('');
			$('#rapido_floor_no').val('');
			$('#rapido_apartment_no').val('');
			$('#rapido_additional_info').val('');
		}
	});

	$('#rapido_city').blur(function() {
		if ($(this).val() != rapido_city) {
			$(this).val('');
			$('#rapido_city_id').val('');
			$('#rapido_region').val('');
			$('#rapido_postcode').val('');
			$('#rapido_office_id').html('<option value="0"><?php echo $text_select_city; ?></option>');
			$('#rapido_quarter').val('');
			$('#rapido_quarter_id').val('');
			$('#rapido_street').val('');
			$('#rapido_street_id').val('');
			$('#rapido_street_no').val('');
			$('#rapido_block_no').val('');
			$('#rapido_entrance_no').val('');
			$('#rapido_floor_no').val('');
			$('#rapido_apartment_no').val('');
			$('#rapido_additional_info').val('');
		}
	});

	$('#rapido_quarter').autocomplete({
		'source': function(request, response) {
			if (request) {
				$.ajax({
					url: 'index.php?route=sale/rapido/street&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request) + '&filter_city_id=' + encodeURIComponent($('#rapido_city_id').val()) + '&filter_type=quarter',
					dataType: 'json',
					success: function(json) {
						if (json.error) {
							$('#rapido_quarter').val('');
							$('#rapido_quarter_id').val('');
							alert(json.error);
						} else {
							if ($('#rapido_quarter').is(":focus")) {
								response($.map(json, function(item) {
									return {
										label:        item['label'],
										value:        item['value'],
										id:           item['id'],
									}
								}));
							}
						}
					}
				});
			}
		},
		'select': function(item) {
			if (item) {
				rapido_quarter = item.value;
				$('#rapido_quarter').val(item.value);
				$('#rapido_quarter_id').val(item.id);
			}
		},
		'change': function(item) {
			if ($('#rapido_country_id').val() == '<?php echo Rapido::BULGARIA; ?>') {
				if(!item) {
					$('#rapido_quarter').val('');
					$('#rapido_quarter_id').val('');
				}
			}
		}
	});

	$('#rapido_quarter').blur(function() {
		if ($('#rapido_country_id').val() == '<?php echo Rapido::BULGARIA; ?>') {
			if ($(this).val() != rapido_quarter) {
				$('#rapido_quarter').val('');
				$('#rapido_quarter_id').val('');
			}
		}
	});

	$('#rapido_street').autocomplete({
		'source': function(request, response) {
			if (request) {
				$.ajax({
					url: 'index.php?route=sale/rapido/street&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request) + '&filter_city_id=' + encodeURIComponent($('#rapido_city_id').val()) + '&filter_type=street',
					dataType: 'json',
					success: function(json) {
						if (json.error) {
							$('#rapido_street').val('');
							$('#rapido_street_id').val('');
							alert(json.error);
						} else {
							if ($('#rapido_street').is(":focus")) {
								response($.map(json, function(item) {
									return {
										label:        item['label'],
										value:        item['value'],
										id:           item['id'],
									}
								}));
							}
						}
					}
				});
			}
		},
		'select': function(item) {
			if (item) {
				rapido_street = item.value;
				$('#rapido_street').val(item.value);
				$('#rapido_street_id').val(item.id);
			}
		},
		'change': function(item) {
			if ($('#rapido_country_id').val() == '<?php echo Rapido::BULGARIA; ?>') {
				if(!item) {
					$('#rapido_street').val('');
					$('#rapido_street_id').val('');
				}
			}
		}
	});

	$('#rapido_street').blur(function() {
		if ($('#rapido_country_id').val() == '<?php echo Rapido::BULGARIA; ?>') {
			if ($(this).val() != rapido_street) {
				$('#rapido_street').val('');
				$('#rapido_street_id').val('');
			}
		}
	});
});
//--></script>
<?php echo $footer; ?>