<?php echo $header; ?>
<?php echo $column_left; ?>

<div id="content">
	<?php 
	  if (isset($custom_styles)) {
	    foreach ($custom_styles as $style) { ?>
	     <link rel="stylesheet" href="view/stylesheet/<?= $style ?>">
	  <?php 
	    } 
	  }
	?>

	<div class="page-header">
		<div class="container-fluid">
			<h1><?= $title ?></h1>
		</div>
	</div>

	<div class="container-fluid">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1><?= $data ?></h1>
			</div>
			<div class="panel-body">
				<form action="<?= $store_offer_link ?>" method="POST" class="form-horizontal">
					<div class="form-group">
						<label for="name" class="col-md-2 control-label"><?= $text_name ?></label>
						<div class="col-md-10">
							<input type="text" id="name" name="name" class="form-control">
						</div>
					</div>

					<div class="form-group">
						<label for="products" class="col-md-2 control-label"><?= $text_products ?></label>
						<div class="col-md-6">
							<input type="text" id="products" name="products" class="form-control products">
							<div class="suggestions" style="display: none;">
								<ul>
								</ul>
							</div>
							<div class="products-holder">
							</div>
						</div>
						<i class="material-icons add add-product">add_box</i>
					</div>

					<button type="submit" class="btn btn-primary">Добави</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$(document).click(function(event) { 
		    if(!$(event.target).closest('.suggestions').length) {
		        if($('.suggestions').is(":visible")) {
		            $('.suggestions').slideUp('fast');
		        }
		    }        
		});

		$('#products').keyup(function(event) {
			let search = $(this).val()
			
			$.ajax({
				url: '<?= $product_search_link ?>' + '&token=<?=$token?>',
				type: 'POST',
				data: {search: search},
			})
			.done(function(data) {
				data = JSON.parse(data)

				//Empty the suggestions div
				$('.suggestions > ul').empty()

				if ((data.products).length > 0) {
					//Put products in suggestions div
					for (product of data.products) {
						let li = $('<li>')

						li.addClass('product-suggestion')

						li.data('product-id', product.product_id)

						li.text(product.model + ' - ' + product.name)

						$('.suggestions > ul').append(li)
					}
				}
				else {
					let li = $('<li>')

					li.text('Не бяха намерени продукти')

					$('.suggestions > ul').append(li)
				}
				//Show suggestions div
				$('.suggestions').slideDown('fast')
			})
			.fail(function(err) {
				console.log(err)
				console.log("error");
			})	
		});

		$('body').on('click', '.product-suggestion', function() {
			let productText = $(this).text()
			let id = $(this).data('product-id')

			$('#products').val(productText)

			$('.add-product').data('product-id', id)
		});

		$('.add-product').click(function(event) {
			event.preventDefault()

			let id = $(this).data('product-id')

			$.ajax({
				url: '<?= $get_product_link ?>' + '&token=<?= $token ?>',
				type: 'POST',
				data: {id: id},
			})
			.done(function(data) {
				data = JSON.parse(data)

				//Show a div with the product info
				let div = $('<div>')
				div.addClass('product animated fadeIn')

				//image holer
				let imageHolder = $('<div>')
				imageHolder.addClass('image-holder')

				let img = $('<img>')
				img.attr({
					src: '<?=DIR_IMAGE?>' + data.product.image,
					class: 'product-image img-responsive',
					alt: data.name
				});

				imageHolder.append(img)

				div.append(imageHolder)

				//product info holder
				let divInfo = $('<div>')
				divInfo.addClass('product-info-holder')

				let p = $('<p>')
				p.text(data.product.name)

				divInfo.append(p)

				div.append(divInfo)

				//Quantity
				let quantityHolder = $('<div>')
				quantityHolder.addClass('quantity-holder')

				let label = $('<label>')
				label.attr('for', 'quantity-' + data.product.product_id)

				label.text('<?= $text_quantity ?>')

				let input = $('<input>')
				input.attr({
					type: 'number',
					class: 'form-control quantity-input',
					name: 'quantity['+data.product.product_id+']',
					id: 'quantity-' + data.product.product_id,
					min: '0'
				})

				input.data('product-price', data.product.price)

				quantityHolder.append(label)
				quantityHolder.append(input)

				let hiddenInput = $('<input>')
				hiddenInput.attr({
					name: 'products[]',
					type: 'hidden',
					value: data.product.product_id
				})

				div.append(hiddenInput)

				div.append(quantityHolder)

				let clearfix = $('<div>')
				clearfix.addClass('clearfix')

				div.append(clearfix)

				$('#products').val('')

				$('.products-holder').append(div)
			})
			.fail(function() {
				console.log("error");
			})	
		});

		$('body').on('keyup', '.product-quantity', function() {
			let price = $(this).data('product-price')
			console.log(price)
		});
	});
</script>

<?php echo $footer; ?>