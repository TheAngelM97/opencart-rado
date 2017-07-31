<!DOCTYPE html>
<html>
<head>
<link type="text/css" href="catalog/view/theme/default/stylesheet/invoice.css" rel="stylesheet" media="all" />
</head>
<body>
<div class="container">
  <?php foreach ($orders as $key => $order){ ?>
  <div style="<?php  if(!empty($key)){ echo "page-break-after:always;"; } ?>">
   <?php echo $order['invoice_manager_header']; ?>
	<?php if($order['invoice_manager_invoice_heading_status']){ ?>
    <h1><?php echo ($order['invoice_manager_invoice_heading'] ? $order['invoice_manager_invoice_heading'] : $text_invoice); ?> #<?php echo $order['order_id']; ?></h1>
	<?php } ?>
	<?php if($order['invoice_manager_orderdetails_status']){ ?>
	<table class="table table-bordered">
      <thead>
        <tr>
          <td style="background:<?php echo $order['invoice_manager_title_backgound']; ?>; color:<?php echo $order['invoice_manager_title_color']; ?>;" colspan="2"><?php echo ($order['invoice_manager_order_details_heading'] ? $order['invoice_manager_order_details_heading'] : $text_order_detail); ?></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="width: <?php echo $tdwidth; ?>%;"><address>
            <strong><?php echo $order['store_name']; ?></strong><br />
            <?php echo $order['store_address']; ?>
            </address>
            <b><?php echo $text_telephone; ?></b> <?php echo $order['store_telephone']; ?><br />
            <?php if ($order['store_fax']) { ?>
            <b><?php echo $text_fax; ?></b> <?php echo $order['store_fax']; ?><br />
            <?php } ?>
            <b><?php echo $text_email; ?></b> <?php echo $order['store_email']; ?><br />
            <b><?php echo $text_website; ?></b> <a href="<?php echo $order['store_url']; ?>"><?php echo $order['store_url']; ?></a></td>
          <td style="width: <?php echo $tdwidth; ?>%;"><b><?php echo $text_date_added; ?></b> <?php echo $order['date_added']; ?><br />
            <?php if ($order['invoice_no']) { ?>
            <b><?php echo $text_invoice_no; ?></b> <?php echo $order['invoice_no']; ?><br />
            <?php } ?>
            <b><?php echo $text_order_id; ?></b> <?php echo $order['order_id']; ?><br />
            <b><?php echo $text_payment_method; ?></b> <?php echo $order['payment_method']; ?><br />
            <?php if ($order['shipping_method']) { ?>
            <b><?php echo $text_shipping_method; ?></b> <?php echo $order['shipping_method']; ?><br />
            <?php } ?></td>
        </tr>
      </tbody>
    </table>
	<?php } ?>
	<?php if($order['invoice_manager_payment_address_status'] || $order['invoice_manager_shipping_address_status']){ ?>
    <table class="table table-bordered">
      <thead>
        <tr>
		  <?php if($order['invoice_manager_payment_address_status']){ ?>
          <td style="background:<?php echo $order['invoice_manager_title_backgound']; ?>; color:<?php echo $order['invoice_manager_title_color']; ?>;" style="width: 50%;"><b><?php echo ($order['invoice_manager_payment_address_heading'] ? $order['invoice_manager_payment_address_heading'] : $text_payment_address); ?></b></td>
		  <?php } ?>
		   <?php if($order['invoice_manager_shipping_address_status']){ ?>
          <td style="background:<?php echo $order['invoice_manager_title_backgound']; ?>; color:<?php echo $order['invoice_manager_title_color']; ?>;" style="width: 50%;"><b><?php echo ($order['invoice_manager_shipping_address_heading'] ? $order['invoice_manager_shipping_address_heading'] : $text_shipping_address); ?></b></td>
		  <?php } ?>
        </tr>
      </thead>
      <tbody>
        <tr>
		 <?php if($order['invoice_manager_payment_address_status']){ ?>
          <td ><address>
            <?php echo $order['payment_address']; ?>
            </address></td>
		  <?php } ?>
		  <?php if($order['invoice_manager_shipping_address_status']){ ?>
          <td ><address>
            <?php echo $order['shipping_address']; ?>
            </address></td>
		  <?php } ?>
        </tr>
      </tbody>
    </table>
	<?php } ?>
	<?php $colspan=0; ?>
    <table class="table table-bordered">
      <thead>
        <tr>
		  <?php if($order['invoice_manager_product_image_status']){ ?>
          <td style="background:<?php echo $order['invoice_manager_title_backgound']; ?>; color:<?php echo $order['invoice_manager_title_color']; ?>;" class="text-center"><b><?php echo ($order['invoice_manager_image_title'] ? $order['invoice_manager_image_title'] : 'Image'); ?></b></td>
		  <?php $colspan +=1; } ?>
		  <?php if($order['invoice_manager_product_name_status']){ ?>
		  <td style="background:<?php echo $order['invoice_manager_title_backgound']; ?>; color:<?php echo $order['invoice_manager_title_color']; ?>;"><b><?php echo ($order['invoice_manager_product_title'] ? $order['invoice_manager_product_title'] : $column_product); ?></b></td>
		  <?php $colspan +=1; } ?>
		  <?php if($order['invoice_manager_product_sku_status']){ ?>
          <td style="background:<?php echo $order['invoice_manager_title_backgound']; ?>; color:<?php echo $order['invoice_manager_title_color']; ?>;"><b><?php echo ($order['invoice_manager_sku_title'] ? $order['invoice_manager_sku_title'] : 'Sku'); ?></b></td>
		  <?php $colspan +=1; } ?>
		  <?php if($order['invoice_manager_product_model_status']){ ?>
          <td style="background:<?php echo $order['invoice_manager_title_backgound']; ?>; color:<?php echo $order['invoice_manager_title_color']; ?>;"><b><?php echo ($order['invoice_manager_model_title'] ? $order['invoice_manager_model_title'] : $column_model); ?></b></td>
		  <?php $colspan +=1; } ?>
		  <?php if($order['invoice_manager_product_qty_status']){ ?>
          <td style="background:<?php echo $order['invoice_manager_title_backgound']; ?>; color:<?php echo $order['invoice_manager_title_color']; ?>;" class="text-right"><b><?php echo ($order['invoice_manager_qty_title'] ? $order['invoice_manager_qty_title'] : $column_quantity); ?></b></td>
		  <?php $colspan +=1; } ?>
		  <?php if($order['invoice_manager_product_unit_price_status']){ ?>
          <td style="background:<?php echo $order['invoice_manager_title_backgound']; ?>; color:<?php echo $order['invoice_manager_title_color']; ?>;" class="text-right"><b><?php echo ($order['invoice_manager_unit_title'] ? $order['invoice_manager_unit_title'] : $column_price); ?></b></td>
		  <?php $colspan +=1; } ?>
		  <?php if($order['invoice_manager_product_total_status']){ ?>
          <td style="background:<?php echo $order['invoice_manager_title_backgound']; ?>; color:<?php echo $order['invoice_manager_title_color']; ?>;" class="text-right"><b><?php echo ($order['invoice_manager_total_title'] ? $order['invoice_manager_total_title'] : $column_total); ?></b></td>
		  <?php $colspan +=1; } ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($order['product'] as $product) { ?>
        <tr>
		  <?php if($order['invoice_manager_product_image_status']){ ?>
		  <td class="text-center"><?php if($product['image']){ ?>
				<img src="<?php echo $product['image']; ?>"/>
			<?php } ?>
		  </td>
		  <?php } ?>
		  <?php if($order['invoice_manager_product_name_status']){ ?>
          <td><?php echo $product['name']; ?>
            <?php foreach ($product['option'] as $option) { ?>
            <br />
            &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
            <?php } ?></td>
		  <?php } ?>
		  <?php if($order['invoice_manager_product_sku_status']){ ?>
          <td><?php echo $product['sku']; ?></td>
		  <?php } ?>
		  <?php if($order['invoice_manager_product_model_status']){ ?>
          <td><?php echo $product['model']; ?></td>
		  <?php } ?>
		  <?php if($order['invoice_manager_product_qty_status']){ ?>
          <td class="text-right"><?php echo $product['quantity']; ?></td>
		  <?php } ?>
		  <?php if($order['invoice_manager_product_unit_price_status']){ ?>
          <td class="text-right"><?php echo $product['price']; ?></td>
		  <?php } ?>
		  <?php if($order['invoice_manager_product_total_status']){ ?>
          <td class="text-right"><?php echo $product['total']; ?></td>
		  <?php } ?>
        </tr>
        <?php } ?>
        <?php foreach ($order['voucher'] as $voucher) { ?>
        <tr>
          <td><?php echo $voucher['description']; ?></td>
          <td></td>
          <td class="text-right">1</td>
          <td class="text-right"><?php echo $voucher['amount']; ?></td>
          <td class="text-right"><?php echo $voucher['amount']; ?></td>
        </tr>
        <?php } ?>
        <?php foreach ($order['total'] as $total) { ?>
        <tr>
          <td class="text-right" colspan="<?php echo $colspan-1; ?>"><b><?php echo $total['title']; ?></b></td>
          <td class="text-right"><?php echo $total['text']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    <?php if ($order['comment']) { ?>
    <table class="table table-bordered">
      <thead>
        <tr>
          <td><b><?php echo $text_comment; ?></b></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $order['comment']; ?></td>
        </tr>
      </tbody>
    </table>
    <?php } ?>
	<div style="bottom:20px;"><?php echo $order['invoice_manager_footer']; ?></div>
  </div>
  <?php } ?>
</div>
</body>
</html>