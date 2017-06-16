<?php if ($error_warning) { ?>
<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($shipping_methods) { ?>
<p><?php echo $text_shipping_method; ?></p>
<?php foreach ($shipping_methods as $shipping_method) { ?>
<p><strong><?php echo $shipping_method['title']; ?></strong></p>
<?php if (!$shipping_method['error']) { ?>
<?php foreach ($shipping_method['quote'] as $quote) { ?>
<div class="radio">
  <label>
    <?php if ($quote['code'] == $code || !$code) { ?>
    <?php $code = $quote['code']; ?>
    <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" checked="checked" />
    <?php } else { ?>
    <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" />
    <?php } ?>
    <?php echo $quote['title']; ?> - <?php echo $quote['text']; ?></label>
</div>
<?php } ?>
<?php } else { ?>
<div class="alert alert-danger"><?php echo $shipping_method['error']; ?></div>
<?php } ?>
<?php } ?>
<?php } ?>

<div id="econt"></div>
			
<p><strong><?php echo $text_comments; ?></strong></p>
<p>
  <textarea name="comment" rows="8" class="form-control"><?php echo $comment; ?></textarea>
</p>
<div class="buttons">

<script type="text/javascript">
var econt_loaded = false;

$(document).ready(function() {
	if ($('[name="shipping_method"][value^="econt."]:checked').length) {
		econt($('[name="shipping_method"][value^="econt."]:checked').attr('value'));
	}

	$('[name="shipping_method"]').not('[value^="econt."]').click(function() {
		$('#econt').hide();
	});

	$('[name="shipping_method"][value^="econt."]').click(function() {
		econt($(this).attr('value'));
	});
});

function econt(econt_id) {
	if (econt_loaded) {
		$('#econt').show();

		if (econt_id == 'econt.econt_door') {
			$('#to_door').click();
		} else if (econt_id == 'econt.econt_office') {
			$('#to_office').click();
		} else if (econt_id == 'econt.econt_aps') {
			$('#to_aps').click();
		}
	} else {
		$('#econt').html('<div class="wait"><img src="catalog/view/theme/default/image/loading.gif" alt="" /></div>');

		$.ajax({
			url: 'index.php?route=extension/shipping/econt',
			dataType: 'json',
			success: function(data) {
				if (data) {
					if (data.redirect) {
						location = data.redirect;
					} else {
						$('#econt').html(data.html);

						if (econt_id == 'econt.econt_door') {
							$('#to_door').click();
						} else if (econt_id == 'econt.econt_office') {
							$('#to_office').click();
						} else if (econt_id == 'econt.econt_aps') {
							$('#to_aps').click();
						}

						econt_loaded = true;
					}
				}
			}
		});
	}
}
</script>
			
  <div class="pull-right">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-shipping-method" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
  </div>
</div>
