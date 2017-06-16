<?php echo $header; ?>

			<?php if(!empty($hd_ct23)||($hd_ct13)) { ?>
			<div class="container">
            <div class="row">
            <div class="col-sm-8"><?php echo $hd_ct23; ?></div>
			<div class="col-sm-4 pull-right flip"><?php echo $hd_ct13; ?></div>
            </div>
            </div>
			<?php } ?>
            
<div class="container">
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?><?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>