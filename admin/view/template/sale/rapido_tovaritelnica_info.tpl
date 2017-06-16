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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <div class="tab-content">
            <table class="table table-bordered">
              <tr>
                <td><?php echo $text_tovaritelnica; ?></td>
                <td><?php echo $tovaritelnica; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_created; ?></td>
                <td><?php echo $date_created; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_client_ref; ?></td>
                <td><?php echo $client_ref; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_cod; ?></td>
                <td><?php echo $cod; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_weight; ?></td>
                <td><?php echo $weight; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_tax_service; ?></td>
                <td><?php echo $tax_service; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_tax_fixed_time; ?></td>
                <td><?php echo $tax_fixed_time; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_tax_return_doc; ?></td>
                <td><?php echo $tax_return_doc; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_tax_return_receipt; ?></td>
                <td><?php echo $tax_return_receipt; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_tax_cod; ?></td>
                <td><?php echo $tax_cod; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_tax_vat; ?></td>
                <td><?php echo $tax_vat; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_tax_insurance; ?></td>
                <td><?php echo $tax_insurance; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_total; ?></td>
                <td><?php echo $total; ?></td>
              </tr>
            </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 