<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="rapido_form" class="form-horizontal">
  <div class="content">
	<div>
	  <div class="form-group" <?php if (!$cod_status) { ?> style="display: none;"<?php } ?>>
		<label class="col-sm-2 control-label"><?php echo $entry_cod; ?></label>
		<div class="col-sm-8">
		  <label class="radio-inline">
			<input type="radio" id="rapido_cod_yes" name="cod" value="1" <?php if ($cod) { ?> checked="checked"<?php } ?> />
			<?php echo $text_yes; ?>
		  </label>
		  <label class="radio-inline">
			<input type="radio" id="rapido_cod_no" name="cod" value="0" <?php if (!$cod && !is_null($cod)) { ?> checked="checked"<?php } ?> />
			<?php echo $text_no; ?>
		  </label>
		</div>
	  </div>
	  <div class="form-group">
		<label class="col-sm-2 control-label"><?php echo $entry_shipping_to; ?></label>
		<div class="col-sm-8">
		  <label class="radio-inline">
			<input type="radio" id="rapido_shipping_to_door" name="take_office" value="0" <?php if (!$take_office) { ?> checked="checked"<?php } ?> onclick="$('#rapido_region_container,#rapido_quarter_container,#rapido_street_container,#rapido_block_no_container,#rapido_additional_info_container').show(); $('#rapido_office_container').hide();" />
			<?php echo $text_to_door; ?>
		  </label>
		  <label class="radio-inline">
			<input type="radio" id="rapido_shipping_take_office" name="take_office" value="1" <?php if ($take_office) { ?> checked="checked"<?php } ?> onclick="$('#rapido_region_container,#rapido_quarter_container,#rapido_street_container,#rapido_block_no_container,#rapido_additional_info_container').hide(); $('#rapido_office_container').show();" />
			<?php echo $text_to_office; ?>
		  </label>
		</div>
	  </div>
	  <div class="form-group">
		<label class="col-sm-2 control-label" for="rapido_country_id"><?php echo $entry_country; ?></label>
		<div class="col-sm-8">
		  <select id="rapido_country_id" name="country_id"  onchange="rapidoClearAddress();" class="form-control">
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
		<label class="col-sm-2 control-label" for="rapido_city"><?php echo $entry_city; ?></label>
		<div class="col-sm-5">
		  <input type="text" id="rapido_city" name="city" value="<?php echo $city; ?>" class="form-control" />
		  <input type="hidden" id="rapido_city_id" name="city_id" value="<?php echo $city_id; ?>" />
		</div>
		<label class="col-sm-1 control-label" for="rapido_postcode"><?php echo $entry_postcode; ?></label>
		<div class="col-sm-2">
		  <input type="text" id="rapido_postcode" name="postcode" value="<?php echo $postcode; ?>" disabled="disabled" class="form-control" />
		</div>
	  </div>
	  <div class="form-group" id="rapido_region_container" <?php if ($take_office) { ?> style="display: none;"<?php } ?>>
		<label class="col-sm-2 control-label" for="rapido_region"><?php echo $entry_region; ?></label>
		<div class="col-sm-8">
		  <input type="text" id="rapido_region" name="region" value="<?php echo $region; ?>" disabled="disabled" class="form-control" />
		</div>
	  </div>
	  <div class="form-group" id="rapido_quarter_container" <?php if ($take_office) { ?> style="display: none;"<?php } ?>>
		<label class="col-sm-2 control-label" for="rapido_quarter"><?php echo $entry_quarter; ?></label>
		<div class="col-sm-8">
		  <input type="text" id="rapido_quarter" name="quarter" value="<?php echo $quarter; ?>" class="form-control" />
		  <input type="hidden" id="rapido_quarter_id" name="quarter_id" value="<?php echo $quarter_id; ?>" />
		</div>
	  </div>
	  <div class="form-group" id="rapido_street_container" <?php if ($take_office) { ?> style="display: none;"<?php } ?>>
		<label class="col-sm-2 control-label" for="rapido_street"><?php echo $entry_street; ?></label>
		<div class="col-sm-5">
		  <input type="text" id="rapido_street" name="street" value="<?php echo $street; ?>" class="form-control" />
		  <input type="hidden" id="rapido_street_id" name="street_id" value="<?php echo $street_id; ?>" />
		</div>
		<label class="col-sm-1 control-label" for="rapido_street_no"><?php echo $entry_street_no; ?></label>
		<div class="col-sm-2">
		  <input type="text" id="rapido_street_no" name="street_no" value="<?php echo $street_no; ?>" class="form-control" />
		</div>
	  </div>
	  <div class="form-group" id="rapido_block_no_container" <?php if ($take_office) { ?> style="display: none;"<?php } ?>>
		<label class="col-sm-2 control-label" for="rapido_block_no"><?php echo $entry_block_no; ?></label>
		<div class="col-sm-2">
		  <input type="text" id="rapido_block_no" name="block_no" value="<?php echo $block_no; ?>" class="form-control" />
		</div>
		<label class="col-sm-1 control-label" for="rapido_entrance_no"><?php echo $entry_entrance_no; ?></label>
		<div class="col-sm-1">
		  <input type="text" id="rapido_entrance_no" name="entrance_no" value="<?php echo $entrance_no; ?>" class="form-control" />
		</div>
		<label class="col-sm-1 control-label" for="rapido_floor_no"><?php echo $entry_floor_no; ?></label>
		<div class="col-sm-1">
		  <input type="text" id="rapido_floor_no" name="floor_no" value="<?php echo $floor_no; ?>" class="form-control" />
		</div>
		<label class="col-sm-1 control-label" for="rapido_apartment_no"><?php echo $entry_apartment_no; ?></label>
		<div class="col-sm-1">
		  <input type="text" id="rapido_apartment_no" name="apartment_no" value="<?php echo $apartment_no; ?>" class="form-control" />
		</div>
	  </div>
	  <?php if ($error_address) { ?>
		<div class="form-group">
		  <div class="col-sm-2"></div>
		  <div class="col-sm-3">
			<span class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_address; ?></span>
		  </div>
	  </div>
	  <?php } ?>
	  <div class="form-group" id="rapido_additional_info_container" <?php if ($take_office) { ?> style="display: none;"<?php } ?>>
		<label class="col-sm-2 control-label" for="rapido_additional_info"><?php echo $entry_additional_info; ?></label>
		<div class="col-sm-8">
		  <input type="text" id="rapido_additional_info" name="additional_info" value="<?php echo $additional_info; ?>" class="form-control" />
		</div>
	  </div>
	  <div class="form-group" id="rapido_office_container" <?php if (!$take_office) { ?> style="display: none;"<?php } ?>>
		<label class="col-sm-2 control-label" for="rapido_office_id"><?php echo $entry_office; ?></label>
		<div class="col-sm-8">
		  <select id="rapido_office_id" name="office_id" class="form-control">
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
			<br />
			<span class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_office; ?></span>
		  <?php } ?>
		</div>
	  </div>
	  <div class="form-group" id="rapido_enable_suboten_raznos" <?php if (!$enable_suboten_raznos) { ?> style="display: none;"<?php } ?>>
		<label class="col-sm-2 control-label">
			<?php echo $entry_suboten_raznos; ?>
		</label>
		<div class="col-sm-8">
			<input type="checkbox" id="rapido_suboten_raznos" name="suboten_raznos" value="1" <?php if ($suboten_raznos) { ?> checked="checked"<?php } ?> />
		</div>
	  </div>
	  <div class="form-group" id="rapido_fixed_time" <?php if (!$fixed_time) { ?> style="display: none;"<?php } ?>>
		<label class="col-sm-2 control-label">
			<input type="checkbox" id="rapido_fixed_time_cb" name="fixed_time_cb" value="1" <?php if ($fixed_time_cb) { ?> checked="checked"<?php } ?>  onclick="rapidoCheckFixedTime();" />
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
		</div>
		<div class="col-sm-1">
		  <select id="rapido_fixed_time_hour" name="fixed_time_hour" class="form-control" <?php if (!$fixed_time_cb) { ?> disabled="disabled"<?php } ?> onchange="rapidoSetFixedTime();>
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
			<?php if ($error_fixed_time) { ?>
			<br />
			<span class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_fixed_time; ?></span>
		  <?php } ?>
	  </div>
	  <?php if($send_email) { ?>
		<div class="form-group" id="rapido_send_email">
		  <label class="col-sm-2 control-label"><?php echo $entry_send_email; ?></label>
		  <div class="col-sm-8">
			  <input type="checkbox" id="rapido_send_email" name="send_email" value="1" checked="checked" />
		  </div>
		</div>
	  <?php } ?>
	</div>
  </div>
  <div class="content">
	<div>
	  <div class="form-group">
		<label class="col-sm-4 control-label"><strong><?php echo $text_calculate; ?></strong></label>
		<div class="col-sm-8 control-label">
		  <div class="pull-right">
			<input type="button" class="btn btn-primary" data-loading-text="<?php echo $button_calculate_loading; ?>" id="button-rapido-calculate" onclick="rapidoSubmit(false);" value="<?php echo $button_calculate; ?>">
		  </div>
		</div>
	  </div>
	</div>
  </div>
</form>

<script type="text/javascript"><!--
$('#button-shipping-method').off();
$('#button-shipping-method').on('click', function() {
	if ($('[name="shipping_method"][value^="rapido."]:checked').length) {
		rapidoSubmit(true);
	} else {
		rapidoShipping(true);
	}
	return false;
});

function rapidoSubmit(next) {
	$('.wait').remove();
	$('#rapido_form').prepend('<div class="wait"><img src="catalog/view/theme/default/image/loading.gif" alt="" /></div>');
	rapido_disabled = $('#rapido_form :input :disabled');
	$('#rapido_form :input').removeAttr('disabled');

	$.ajax({
		url: 'index.php?route=shipping/rapido',
		type: 'POST',
		data: $('#rapido_form').serialize(),
		dataType: 'json',
		complete: function() {
			rapido_disabled.prop('disabled', true);
		},
		success: function(json) {
			if (json) {
				if (json.redirect) {
					location = json.redirect;
				} else if (json.submit) {
					rapidoShipping(next);
				} else {
					$('#rapido_container').html(json.html);
				}
			}
		}
	});
}

function rapidoShipping(next) {
	$.ajax({
		url: 'index.php?route=checkout/shipping_method/save',
		type: 'post',
		data: $('#collapse-shipping-method input[type=\'radio\']:checked, #collapse-shipping-method textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-rapido-calculate').button('loading');
			$('#button-shipping-method').button('loading');
		},
		complete: function() {
			$('#button-rapido-method').button('reset');
			$('#button-shipping-method').button('reset');
		},
		success: function(json) {
			$('.wait').remove();
			$('.alert, .text-danger').remove();

			if (json['redirect']) {
				location = json['redirect'];
			}

			if (json['error']) {
				if (json['error']['warning']) {
					$('#collapse-shipping-method .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '</div>');

					$('.warning').fadeIn('slow');
				}
			} else {
				$('#rapido_form').prepend('<div class="wait"><img src="catalog/view/theme/default/image/loading.gif" alt="" /></div>');

				$.ajax({
					url: 'index.php?route=checkout/shipping_method',
					dataType: 'html',
					success: function(html) {
						$('.wait').remove();
						$('#collapse-shipping-method .panel-body').html(html);

						if (next) {
							$.ajax({
								url: 'index.php?route=checkout/payment_method',
								dataType: 'html',
								success: function(html) {
									$('#collapse-payment-method .panel-body').html(html);

									$('#collapse-payment-method').parent().find('.panel-heading .panel-title').html('<a href="#collapse-payment-method" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle"><?php echo $text_checkout_payment_method; ?> <i class="fa fa-caret-down"></i></a>');
									
									$('a[href=\'#collapse-payment-method\']').trigger('click');
									
									$('#collapse-checkout-confirm').parent().find('.panel-heading .panel-title').html('<?php echo $text_checkout_confirm; ?>');
								}
							});
						}
					}
				});
			}
		}
	});
}

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
					url: 'index.php?route=shipping/rapido/city&filter_name=' + encodeURIComponent(request) + '&filter_country_id=' + encodeURIComponent($('#rapido_country_id').val()),
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
					url: 'index.php?route=shipping/rapido/office&filter_city_id=' + encodeURIComponent(item.id),
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
					url: 'index.php?route=shipping/rapido/fixedTime&filter_city_id=' + encodeURIComponent(item.id),
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
					url: 'index.php?route=shipping/rapido/street&filter_name=' + encodeURIComponent(request) + '&filter_city_id=' + encodeURIComponent($('#rapido_city_id').val()) + '&filter_type=quarter',
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
					url: 'index.php?route=shipping/rapido/street&filter_name=' + encodeURIComponent(request) + '&filter_city_id=' + encodeURIComponent($('#rapido_city_id').val()) + '&filter_type=street',
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

	// autocomplete css workaround
	$('#rapido_container .dropdown-menu').css('position', 'absolute');
});
//--></script>