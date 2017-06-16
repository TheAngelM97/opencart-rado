<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_tovaritelnica; ?> <?php echo $tovaritelnica; ?></h3>
      </div>
      <div class="panel-body">
        <div class="tab-content">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <td class="left"><?php echo $text_event_date; ?></td>
                  <td class="left"><?php echo $text_event; ?></td>
                  <td class="left"><?php echo $text_event_place; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($tracks) { ?>
                <?php foreach ($tracks as $track) { ?>
                <tr>
                  <td class="left"><?php echo $track['event_date']; ?></td>
                  <td class="left"><?php echo $track['event']; ?></td>
                  <td class="left"><?php echo $track['event_place']; ?></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="center" colspan="3"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 