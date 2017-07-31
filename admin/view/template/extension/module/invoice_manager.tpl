<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
		<button onclick="$('.stay').val(1);" type="submit" form="form-invoice_manager" data-toggle="tooltip" title="<?php echo $button_save; ?> & stay" class="btn btn-success1"><i class="fa fa-save"></i> <?php echo $button_save; ?> & stay </button>
        <button type="submit" form="form-invoice_manager" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success1"><i class="fa fa-save"></i> <?php echo $button_save; ?> </button>
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
	
	<?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
		<div class="pull-right">
			<b>Stores : </b><select onchange="location = this.options[this.selectedIndex].value;" name="store_id">
			<option value="<?php echo $store_action.'&store_id=0'; ?>"><?php echo $text_default; ?></option>
			<?php foreach($stores as $store){ 
				if($store['store_id']==$store_id){
					 $select = 'selected=selected';
				}else{
					 $select = '';
				}
			?>
			<option <?php echo $select; ?> value="<?php echo $store_action .'&store_id='. $store['store_id']; ?>"><?php echo $store['name']; ?></option>
			<?php } ?>
			</select>
		</div>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-invoice_manager" class="form-horizontal">
		  <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><i class="fa fa-cog"></i> <?php echo $tab_general; ?></a></li>
            <li class="dropdown"><a href="#" data-toggle="dropdown"><i class="fa fa-eye"></i> <?php echo $tab_invoice; ?> Layout <span class="caret"></span></a> 
				<ul class="dropdown-menu">
					<li><a href="#tab-invoice" data-toggle="tab"><i class="fa fa-eye"></i> Default Layout</a></li>
					<li><a href="#tab-custom" data-toggle="tab"><i class="fa fa-eye"></i> Custom Layout</a></li>
				</ul>
			</li>
            <li class="dropdown"><a href="#" data-toggle="dropdown"><i class="fa fa-language"></i> <?php echo $tab_language; ?> <span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><a href="#tab-language" data-toggle="tab"><i class="fa fa-language"></i> <?php echo $tab_general; ?></a></li>
					<li><a href="#tab-mailing" data-toggle="tab"><i class="fa fa-language"></i> <?php echo $tab_mailing; ?></a></li>
				</ul>
			</li>
           <li><a href="#tab-support" data-toggle="tab"><i class="fa fa-external-link" aria-hidden="true"></i> <?php echo $tab_support; ?></a></li>
          </ul>
		  <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
				<div class="col-sm-5">
				  <select name="invoice_manager_status" id="input-status" class="form-control">
					<?php if ($invoice_manager_status) { ?>
					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					<option value="0"><?php echo $text_disabled; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_enabled; ?></option>
					<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
					<?php } ?>
				  </select>
				  <input type="hidden" name="stay" class="stay" value="0"/>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-enable_send_button"><span  data-toggle="tooltip" title="<?php echo $help_autoinvoice_no; ?>"><?php echo $entry_auto_send_invoice; ?></span></label>
				<div class="col-sm-10">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-success btn-sm <?php echo ($invoice_manager_send_button_status ? 'active' : ''); ?>" >	
							<input type="radio" <?php echo ($invoice_manager_send_button_status ? 'checked=checked' : ''); ?>  name="invoice_manager_send_button_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
						</label>
						<label class="btn btn-success btn-sm <?php echo (!$invoice_manager_send_button_status ? 'active' : ''); ?>">
							<input type="radio" <?php echo (!$invoice_manager_send_button_status ? 'checked=checked' : ''); ?>  name="invoice_manager_send_button_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
						</label>
					</div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-enable_send_button"><span  data-toggle="tooltip" title="Enable Send Invoice in Bulk">Enable Send Invoice in Bulk</span></label>
				<div class="col-sm-10">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-success btn-sm <?php echo ($invoice_manager_send_bulk_status ? 'active' : ''); ?>" >	
							<input type="radio" <?php echo ($invoice_manager_send_bulk_status ? 'checked=checked' : ''); ?>  name="invoice_manager_send_bulk_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
						</label>
						<label class="btn btn-success btn-sm <?php echo (!$invoice_manager_send_bulk_status ? 'active' : ''); ?>">
							<input type="radio" <?php echo (!$invoice_manager_send_bulk_status ? 'checked=checked' : ''); ?>  name="invoice_manager_send_bulk_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
						</label>
					</div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-enable_send_button"><span  data-toggle="tooltip" title="If you want to pdf open a new tab then Choose yes Either no">PDF Stream</span></label>
				<div class="col-sm-10">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-success btn-sm <?php echo ($invoice_manager_pdf_stream ? 'active' : ''); ?>" >	
							<input type="radio" <?php echo ($invoice_manager_pdf_stream ? 'checked=checked' : ''); ?>  name="invoice_manager_pdf_stream"  value="1" autocomplete="off"><?php echo $text_yes; ?>
						</label>
						<label class="btn btn-success btn-sm <?php echo (!$invoice_manager_pdf_stream ? 'active' : ''); ?>">
							<input type="radio" <?php echo (!$invoice_manager_pdf_stream ? 'checked=checked' : ''); ?>  name="invoice_manager_pdf_stream"  value="0" autocomplete="off"><?php echo $text_no; ?>
						</label>
					</div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-enable_download_invoice_admin"><span  data-toggle="tooltip" title="allow Invoice Download Button in the Order List.">Enable Download Button to admin</span></label>
				<div class="col-sm-10">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-success btn-sm <?php echo ($invoice_manager_download_invoice_admin_status ? 'active' : ''); ?>" >	
							<input type="radio" <?php echo ($invoice_manager_download_invoice_admin_status ? 'checked=checked' : ''); ?>  name="invoice_manager_download_invoice_admin_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
						</label>
						<label class="btn btn-success btn-sm <?php echo (!$invoice_manager_download_invoice_admin_status ? 'active' : ''); ?>">
							<input type="radio" <?php echo (!$invoice_manager_download_invoice_admin_status ? 'checked=checked' : ''); ?>  name="invoice_manager_download_invoice_admin_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
						</label>
					</div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-enable_download_invoice_customer"><span  data-toggle="tooltip" title="allow Invoice Download Button to the customer.">Enable Download Button to customer</span></label>
				<div class="col-sm-10">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-success btn-sm <?php echo ($invoice_manager_download_invoice_customer_status ? 'active' : ''); ?>" >	
							<input type="radio" <?php echo ($invoice_manager_download_invoice_customer_status ? 'checked=checked' : ''); ?>  name="invoice_manager_download_invoice_customer_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
						</label>
						<label class="btn btn-success btn-sm <?php echo (!$invoice_manager_download_invoice_customer_status ? 'active' : ''); ?>">
							<input type="radio" <?php echo (!$invoice_manager_download_invoice_customer_status ? 'checked=checked' : ''); ?>  name="invoice_manager_download_invoice_customer_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
						</label>
					</div>
				</div>
			  </div>
			  <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-complete-status"><span data-toggle="tooltip" title="<?php echo $help_complete_status; ?>"><?php echo $entry_complete_status; ?></span></label>
                  <div class="col-sm-10">
                    <div class="well well-sm" style="height: 150px; overflow: auto;">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <div class="checkbox">
                        <label>
                          <?php if (in_array($order_status['order_status_id'], $invoice_manager_complete_status)) { ?>
                          <input type="checkbox" name="invoice_manager_complete_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
                          <?php echo $order_status['name']; ?>
                          <?php } else { ?>
                          <input type="checkbox" name="invoice_manager_complete_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
                          <?php echo $order_status['name']; ?>
                          <?php } ?>
                        </label>
                      </div>
                      <?php } ?>
                    </div>
                  </div>
              </div>
			</div>
			<div class="tab-pane" id="tab-invoice">
			  <div class="form-group">
                <label class="col-sm-2 control-label"><?php echo $entry_image; ?></label>
                <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $logo; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="invoice_manager_logo" value="<?php echo $invoice_manager_logo; ?>" id="input-image" />
                </div>
              </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-payment_address_format"><span  data-toggle="tooltip" title="Change Payment Address format"><?php echo $entry_payment_format; ?></span></label>
				<div class="col-sm-5">
				   <textarea style="width:492px; height:145px;" name="invoice_manager_payment_address_format" class="form-control"><?php echo $invoice_manager_payment_address_format; ?></textarea>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-shipping_address_format"><span  data-toggle="tooltip" title="Change Shipping Address format"><?php echo $entry_shipping_format; ?></span></label>
				<div class="col-sm-5">
				   <textarea style="width:492px; height:145px;" name="invoice_manager_shipping_address_format" class="form-control"><?php echo $invoice_manager_shipping_address_format; ?></textarea>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-invoice_no"><span  data-toggle="tooltip" title="<?php echo $help_invoice_no; ?>"><?php echo $entry_invoice_no; ?></span></label>
				<div class="col-sm-10">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-success btn-sm <?php echo ($invoice_manager_invoice_heading_status ? 'active' : ''); ?>" >	
							<input type="radio" <?php echo ($invoice_manager_invoice_heading_status ? 'checked=checked' : ''); ?>  name="invoice_manager_invoice_heading_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
						</label>
						<label class="btn btn-success btn-sm <?php echo (!$invoice_manager_invoice_heading_status ? 'active' : ''); ?>">
							<input type="radio" <?php echo (!$invoice_manager_invoice_heading_status ? 'checked=checked' : ''); ?>  name="invoice_manager_invoice_heading_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
						</label>
					</div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-order-details"><span  data-toggle="tooltip" title="<?php echo $help_order_details; ?> "><?php echo $entry_order_details; ?></span></label>
				<div class="col-sm-10">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-success btn-sm <?php echo ($invoice_manager_orderdetails_status ? 'active' : ''); ?>" >	
							<input type="radio" <?php echo ($invoice_manager_orderdetails_status ? 'checked=checked' : ''); ?>  name="invoice_manager_orderdetails_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
						</label>
						<label class="btn btn-success btn-sm <?php echo (!$invoice_manager_orderdetails_status ? 'active' : ''); ?>">
							<input type="radio" <?php echo (!$invoice_manager_orderdetails_status ? 'checked=checked' : ''); ?>  name="invoice_manager_orderdetails_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
						</label>
					</div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-payment-address"><span  data-toggle="tooltip" title="<?php echo $help_payment_address; ?> "><?php echo $entry_payment_address; ?></span></label>
				<div class="col-sm-10">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-success btn-sm <?php echo ($invoice_manager_payment_address_status ? 'active' : ''); ?>" >	
							<input type="radio" <?php echo ($invoice_manager_payment_address_status ? 'checked=checked' : ''); ?>  name="invoice_manager_payment_address_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
						</label>
						<label class="btn btn-success btn-sm <?php echo (!$invoice_manager_payment_address_status ? 'active' : ''); ?>">
							<input type="radio" <?php echo (!$invoice_manager_payment_address_status ? 'checked=checked' : ''); ?>  name="invoice_manager_payment_address_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
						</label>
					</div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-shipping-address"><span  data-toggle="tooltip" title="<?php echo $help_shipping_address; ?> "><?php echo $entry_shipping_address; ?></span></label>
				<div class="col-sm-10">
					<div class="btn-group" data-toggle="buttons">
						<label class="btn btn-success btn-sm <?php echo ($invoice_manager_shipping_address_status ? 'active' : ''); ?>" >	
							<input type="radio" <?php echo ($invoice_manager_shipping_address_status ? 'checked=checked' : ''); ?>  name="invoice_manager_shipping_address_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
						</label>
						<label class="btn btn-success btn-sm <?php echo (!$invoice_manager_shipping_address_status ? 'active' : ''); ?>">
							<input type="radio" <?php echo (!$invoice_manager_shipping_address_status ? 'checked=checked' : ''); ?>  name="invoice_manager_shipping_address_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
						</label>
					</div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-image_height"><?php echo $entry_image_height; ?></label>
				<div class="col-sm-10">
					<div class="col-sm-3">
						<input type="text" name="invoice_manager_width" class="form-control" value="<?php echo $invoice_manager_width; ?>"/> 
				    </div>
					<div class="col-sm-3">
					  <input type="text" name="invoice_manager_height" class="form-control" value="<?php echo $invoice_manager_height; ?>"/>
				    </div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-title-background-color">Title Background Color</label>
				<div class="col-sm-10">
					<div class="col-sm-3">
						<input type="text" name="invoice_manager_title_backgound" class="form-control color" value="<?php echo $invoice_manager_title_backgound; ?>"/> 
				    </div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-title-color">Title Color</label>
				<div class="col-sm-10">
					<div class="col-sm-3">
						<input type="text" name="invoice_manager_title_color" class="form-control color" value="<?php echo $invoice_manager_title_color; ?>"/> 
				    </div>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-12">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th class="text-left"><?php echo $entry_show_image; ?></th>
								<th class="text-left">Product Name</th>
								<th class="text-left">Model</th>
								<th class="text-left">Sku</th>
								<th class="text-left">Quantity</th>
								<th class="text-left">Unit Price</th>
								<th class="text-left">Total</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="text-left">
									<div class="col-sm-10">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm <?php echo ($invoice_manager_product_image_status ? 'active' : ''); ?>" >	
												<input type="radio" <?php echo ($invoice_manager_product_image_status ? 'checked=checked' : ''); ?>  name="invoice_manager_product_image_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											<label class="btn btn-success btn-sm <?php echo (!$invoice_manager_product_image_status ? 'active' : ''); ?>">
												<input type="radio" <?php echo (!$invoice_manager_product_image_status ? 'checked=checked' : ''); ?>  name="invoice_manager_product_image_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</td>
								<td class="text-left">
									<div class="col-sm-10">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm <?php echo ($invoice_manager_product_name_status ? 'active' : ''); ?>" >	
												<input type="radio" <?php echo ($invoice_manager_product_name_status ? 'checked=checked' : ''); ?>  name="invoice_manager_product_name_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											<label class="btn btn-success btn-sm <?php echo (!$invoice_manager_product_name_status ? 'active' : ''); ?>">
												<input type="radio" <?php echo (!$invoice_manager_product_name_status ? 'checked=checked' : ''); ?>  name="invoice_manager_product_name_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</td>
								<td class="text-left">
									<div class="col-sm-10">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm <?php echo ($invoice_manager_product_model_status ? 'active' : ''); ?>" >	
												<input type="radio" <?php echo ($invoice_manager_product_model_status ? 'checked=checked' : ''); ?>  name="invoice_manager_product_model_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											<label class="btn btn-success btn-sm <?php echo (!$invoice_manager_product_model_status ? 'active' : ''); ?>">
												<input type="radio" <?php echo (!$invoice_manager_product_model_status ? 'checked=checked' : ''); ?>  name="invoice_manager_product_model_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</td>
								<td class="text-left">
									<div class="col-sm-10">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm <?php echo ($invoice_manager_product_sku_status ? 'active' : ''); ?>" >	
												<input type="radio" <?php echo ($invoice_manager_product_sku_status ? 'checked=checked' : ''); ?>  name="invoice_manager_product_sku_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											<label class="btn btn-success btn-sm <?php echo (!$invoice_manager_product_sku_status ? 'active' : ''); ?>">
												<input type="radio" <?php echo (!$invoice_manager_product_sku_status ? 'checked=checked' : ''); ?>  name="invoice_manager_product_sku_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</td>
								<td class="text-left">
									<div class="col-sm-10">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm <?php echo ($invoice_manager_product_qty_status ? 'active' : ''); ?>" >	
												<input type="radio" <?php echo ($invoice_manager_product_qty_status ? 'checked=checked' : ''); ?>  name="invoice_manager_product_qty_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											<label class="btn btn-success btn-sm <?php echo (!$invoice_manager_product_qty_status ? 'active' : ''); ?>">
												<input type="radio" <?php echo (!$invoice_manager_product_qty_status ? 'checked=checked' : ''); ?>  name="invoice_manager_product_qty_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</td>
								<td class="text-left">
									<div class="col-sm-10">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm <?php echo ($invoice_manager_product_unit_price_status ? 'active' : ''); ?>" >	
												<input type="radio" <?php echo ($invoice_manager_product_unit_price_status ? 'checked=checked' : ''); ?>  name="invoice_manager_product_unit_price_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											<label class="btn btn-success btn-sm <?php echo (!$invoice_manager_product_unit_price_status ? 'active' : ''); ?>">
												<input type="radio" <?php echo (!$invoice_manager_product_unit_price_status ? 'checked=checked' : ''); ?>  name="invoice_manager_product_unit_price_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</td>
								<td class="text-left">
									<div class="col-sm-10">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-success btn-sm <?php echo ($invoice_manager_product_total_status ? 'active' : ''); ?>" >	
												<input type="radio" <?php echo ($invoice_manager_product_total_status ? 'checked=checked' : ''); ?>  name="invoice_manager_product_total_status"  value="1" autocomplete="off"><?php echo $text_yes; ?>
											</label>
											<label class="btn btn-success btn-sm <?php echo (!$invoice_manager_product_total_status ? 'active' : ''); ?>">
												<input type="radio" <?php echo (!$invoice_manager_product_total_status ? 'checked=checked' : ''); ?>  name="invoice_manager_product_total_status"  value="0" autocomplete="off"><?php echo $text_no; ?>
											</label>
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			  </div>
			</div>
			<div class="tab-pane" id="tab-custom">
				<ul class="nav nav-tabs" id="language">
					<?php foreach ($languages as $language) { ?>
					<li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
					<?php } ?>
				</ul>
			    <div class="tab-content">
					<?php foreach ($languages as $language) { ?>
					<div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
						<div class="col-sm-9">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-invoice"><?php echo $entry_invoice_header; ?></label>
								<div class="col-sm-10">
								  <textarea name="invoice_manager_header<?php echo $language['language_id']; ?>" class="summernote"><?php echo isset(${'invoice_manager_header' . $language['language_id']}) ? ${'invoice_manager_header' . $language['language_id']} : ''; ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-invoice-footer"><?php echo $entry_invoice_footer; ?></label>
								<div class="col-sm-10">
								  <textarea name="invoice_manager_footer<?php echo $language['language_id']; ?>" class="summernote"><?php echo isset(${'invoice_manager_footer' . $language['language_id']}) ? ${'invoice_manager_footer' . $language['language_id']} : ''; ?></textarea>
								</div>
							</div>
						</div>
						<div style="padding:0px;" class="col-sm-3">
							<h3>Store Information</h3>
							{logo} = Store Logo</br/>
							{store} = Store Name</br/>
							{address} = Store address</br/>
							{email} = Store Email</br/>
							{telephone} = Store Telephone</br/>
							{website} = Store website link</br/>
							</br/>
							<h3>Customer Information</h3>
							{customer} = Name<br/>
							{customer_email} =  Email<br/>
							{customer_telephone} = Telephone<br/>
							</br/>
							<h3>Order Information</h3>
							{order} = Order Number</br/>
							{invoice} = Invoice Number</br/>
							{order_date} = Order Date</br/>
							{payment} = Payment Method</br/>
							{shipping} = Shipping Method</br/>
							{payment_address} = Payment Address</br/>
							{shipping_address} = Shipping Address</br/>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
			<div class="tab-pane" id="tab-mailing">
				<ul class="nav nav-tabs" id="languagemail">
					<?php foreach ($languages as $language) { ?>
					<li><a href="#languagemail<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
					<?php } ?>
				</ul>
				<div class="tab-content">
					<?php foreach ($languages as $language){ ?>
					<div class="tab-pane" id="languagemail<?php echo $language['language_id']; ?>">
						<div class="col-sm-9">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-subject">Subject</label>
								<div class="col-sm-10">
								  <input type="text" class="form-control" name="invoice_manager_subject<?php echo $language['language_id']; ?>" value="<?php echo isset(${'invoice_manager_subject' . $language['language_id']}) ? ${'invoice_manager_subject' . $language['language_id']} : ''; ?>"/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-invoice-message">Message</label>
								<div class="col-sm-10">
								  <textarea name="invoice_manager_message<?php echo $language['language_id']; ?>" class="summernote"><?php echo isset(${'invoice_manager_message' . $language['language_id']}) ? ${'invoice_manager_message' . $language['language_id']} : ''; ?></textarea>
								</div>
							</div>
						</div>
						<div style="padding:0px;" class="col-sm-3">
							<h3>Store Information</h3>
							{logo} = Store Logo<br/>
							{store} = Store Name<br/>
							{address} = Store address<br/>
							{email} = Store Email<br/>
							{telephone} = Store Telephone<br/>
							{website} = Store website link<br/>
							</br/>
							<h3>Customer Information</h3>
							{customer} = Name<br/>
							{customer_email} =  Email<br/>
							{customer_telephone} = Telephone<br/>
							</br/>
							<h3>Order Information</h3>
							{order} = Order Number<br/>
							{invoice} = Invoice Number<br/>
							{order_date} = Order Date<br/>
							{payment} = Payment Method<br/>
							{shipping} = Shipping Method<br/>
							</br/>
							<h3>Invoice Attachment</h3>
							{invoice_pdf}
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
			<div class="tab-pane" id="tab-language">
				<ul class="nav nav-tabs" id="languagetext">
					<?php foreach ($languages as $language) { ?>
					<li><a href="#languagetext<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
					<?php } ?>
				</ul>
			    <div class="tab-content">
					<?php foreach ($languages as $language) { ?>
					<div class="tab-pane" id="languagetext<?php echo $language['language_id']; ?>">
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-invoice"><?php echo $entry_invoice_heading; ?></label>
							<div class="col-sm-10">
							  <input type="text" class="form-control" name="invoice_manager_invoice_heading<?php echo $language['language_id']; ?>" value="<?php echo isset(${'invoice_manager_invoice_heading' . $language['language_id']}) ? ${'invoice_manager_invoice_heading' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-invoice"><?php echo $entry_order_details; ?> Heading</label>
							<div class="col-sm-10">
							  <input type="text" class="form-control" name="invoice_manager_order_details_heading<?php echo $language['language_id']; ?>" value="<?php echo isset(${'invoice_manager_order_details_heading' . $language['language_id']}) ? ${'invoice_manager_order_details_heading' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-invoice"><?php echo $entry_payment_address; ?> Heading</label>
							<div class="col-sm-10">
							  <input type="text" class="form-control" name="invoice_manager_payment_address_heading<?php echo $language['language_id']; ?>" value="<?php echo isset(${'invoice_manager_payment_address_heading' . $language['language_id']}) ? ${'invoice_manager_payment_address_heading' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-invoice"><?php echo $entry_shipping_address; ?> Heading</label>
							<div class="col-sm-10">
							  <input type="text" class="form-control" name="invoice_manager_shipping_address_heading<?php echo $language['language_id']; ?>" value="<?php echo isset(${'invoice_manager_shipping_address_heading' . $language['language_id']}) ? ${'invoice_manager_shipping_address_heading' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-image_title">Image Title</label>
							<div class="col-sm-10">
							  <input type="text" class="form-control" name="invoice_manager_image_title<?php echo $language['language_id']; ?>" value="<?php echo isset(${'invoice_manager_image_title' . $language['language_id']}) ? ${'invoice_manager_image_title' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-image_title">Product Title</label>
							<div class="col-sm-10">
							  <input type="text" class="form-control" name="invoice_manager_product_title<?php echo $language['language_id']; ?>" value="<?php echo isset(${'invoice_manager_product_title' . $language['language_id']}) ? ${'invoice_manager_product_title' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-image_title">Model Title</label>
							<div class="col-sm-10">
							  <input type="text" class="form-control" name="invoice_manager_model_title<?php echo $language['language_id']; ?>" value="<?php echo isset(${'invoice_manager_model_title' . $language['language_id']}) ? ${'invoice_manager_model_title' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-image_title">Sku Title</label>
							<div class="col-sm-10">
							  <input type="text" class="form-control" name="invoice_manager_sku_title<?php echo $language['language_id']; ?>" value="<?php echo isset(${'invoice_manager_sku_title' . $language['language_id']}) ? ${'invoice_manager_sku_title' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-image_title">Quantity Title</label>
							<div class="col-sm-10">
							  <input type="text" class="form-control" name="invoice_manager_qty_title<?php echo $language['language_id']; ?>" value="<?php echo isset(${'invoice_manager_qty_title' . $language['language_id']}) ? ${'invoice_manager_qty_title' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-image_title">Unit Price Title</label>
							<div class="col-sm-10">
							  <input type="text" class="form-control" name="invoice_manager_unit_title<?php echo $language['language_id']; ?>" value="<?php echo isset(${'invoice_manager_unit_title' . $language['language_id']}) ? ${'invoice_manager_unit_title' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-image_title">Total Title</label>
							<div class="col-sm-10">
							  <input type="text" class="form-control" name="invoice_manager_total_title<?php echo $language['language_id']; ?>" value="<?php echo isset(${'invoice_manager_total_title' . $language['language_id']}) ? ${'invoice_manager_total_title' . $language['language_id']} : ''; ?>"/>
							</div>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
			<div class="tab-pane" id="tab-support">
			  <p class="text-center">For Support and Query Feel Free to contact:<br /><strong>extensionsbazaar@gmail.com</strong></p>
			</div>
		  </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script> 
<script type="text/javascript"><!--
$('#language a:first').tab('show');
$('#languagetext a:first').tab('show');
$('#languagemail a:first').tab('show');
//--></script>
<link href="view/javascript/colorpicker/css/bootstrap-colorpicker.css" rel="stylesheet">
<script src="view/javascript/colorpicker/js/colorpicker-color.js"></script>
<script src="view/javascript/colorpicker/js/colorpicker.js"></script>
<script src="view/javascript/colorpicker/js/docs.js"></script>
<script>
$(function(){
 $('.color').colorpicker();
});
</script>
<style>
.btn-success1{
    background-color:#8fbb6c;
    border-color:#7aae50;
    color:#fff;
}
.dropdown-menu > li > a {
	padding: 6px 20px;
}
.nav-tabs .dropdown-menu{
	min-width: 267px;
}

.form-horizontal .control-label {
	padding-top: 5px;
}

.btn-success {
	background-color: #fff;
	color: #000;
	border-color: #ddd;
}
.btn-success1{
    background-color:#8fbb6c;
    border-color:#7aae50;
    color:#fff;
}
.btn-success1:hover{
    color:#fff;
}
</style>
<?php echo $footer; ?>