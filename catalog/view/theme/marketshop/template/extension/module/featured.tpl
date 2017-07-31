<?php if ($module['position'] == 'content_top' || $module['position'] == 'content_bottom') { ?>
<h3><?php echo $heading_title; ?></h3>
<div style="margin-bottom: 40px;">
  <?php
    $count = 0;
    foreach ($products as $product) { 
      if ($count == 0 || $count == 4) { ?>
        <div class="row mr-left">
<?php  }
    ?>
    <div class="product-thumb col-md-3">
      <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
      <div class="caption" style="text-align: center;">
        <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
        <p><?= $product['model']; ?></p>
        <?php if ($product['price']) { ?>
        <p class="price">
          <?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
          <?php if($marketshop_percentage_discount_badge == 1) { ?>
            <span class="saving">-<?php echo $product['saving']; ?>%</span>
            <?php } ?>
          <?php } ?>
        </p>
        <?php } ?>
      </div>
      <div class="button-group" style="text-align: center;">
        <button class="btn-primary" type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><span><?php echo $button_cart; ?></span></button>
<!--         <div class="add-to-links">
        <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
        </div> -->
      </div>
    </div>
  <?php 
      $count++;
      if ($count == 4) { ?>
          </div>
<?php    $count = 0;
        }
      } ?></div>
<?php } else { ?>
<h3><?php echo $heading_title; ?></h3>
<div class="owl-carousel featured_carousel">
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
          <?php if($marketshop_percentage_discount_badge == 1) { ?>
            <span class="saving">-<?php echo $product['saving']; ?>%</span>
            <?php } ?>
          <?php } ?>
        </p>
        <?php } ?>
      </div>
    </div>
  <?php } ?></div>
<?php } ?>