<?php if ($error_warning) { ?>
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php } ?>
<table class="table table-bordered table-hover">
  <thead>
    <tr>
      <td class="text-left"><?php echo $text_event_date; ?></td>
      <td class="text-left"><?php echo $text_event; ?></td>
      <td class="text-left"><?php echo $text_event_place; ?></td>
    </tr>
  </thead>
  <tbody>
    <?php if ($tracks) { ?>
    <?php foreach ($tracks as $track) { ?>
    <tr>
      <td class="text-left"><?php echo $track['event_date']; ?></td>
      <td class="text-left"><?php echo $track['event']; ?></td>
      <td class="text-left"><?php echo $track['event_place']; ?></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>