<?php if ($module['position'] == 'content_top' || $module['position'] == 'content_bottom') { ?>
<h3><?php echo $heading_title; ?></h3>
<div class="owl-carousel special_carousel">
  <?php foreach ($products as $product) { ?>
    <div class="product-thumb clearfix">
      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
      <div class="caption">
        <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
        <?php if ($product['price']) { ?>
        <p class="price">
          <?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
          <?php if($marketshop_percentage_discount_badge == 1) { ?><span class="saving">-<?php echo $product['saving']; ?>%</span><?php } ?>
          <?php } ?>
        </p>
        <?php } ?>
      </div>
      <div class="button-group">
        <button class="btn-primary" type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><span><?php echo $button_cart; ?></span></button>
        <div class="add-to-links">
        <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
        </div>
      </div>
    </div>
  <?php } ?>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#content .owl-carousel.special_carousel").owlCarousel({
		itemsCustom : [[320, 1],[600, 2],[768, 3],[992, <?php echo $marketshop_specials_slider_product_per_row; ?>],[1199, <?php echo $marketshop_specials_slider_product_per_row; ?>]],											   
		lazyLoad : true,
		navigation : true,
		navigationText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
		scrollPerPage : true
    }); 
		});
</script>
<?php } else { ?>
<h3><?php echo $heading_title; ?></h3>
<div class="owl-carousel special_carousel">
  <?php foreach ($products as $product) { ?>
    <div class="product-thumb clearfix">
      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
      <div class="caption">
        <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
        <?php if ($product['price']) { ?>
        <p class="price">
          <?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
          <?php if($marketshop_percentage_discount_badge == 1) { ?><span class="saving">-<?php echo $product['saving']; ?>%</span><?php } ?>
          <?php } ?>
        </p>
        <?php } ?>
      </div>
    </div>
  <?php } ?>
</div>
<?php } ?>