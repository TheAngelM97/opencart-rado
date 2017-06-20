<?php 
	/**
	* 
	*/
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}
	require_once 'vendor/autoload.php';

	use Symfony\Component\DomCrawler\Crawler;

	class ControllerExtensionModuleProductCrawler extends Controller
	{
		private $error = array();
		private $data = array();
		private $db;

		private $products = array();

		private function setData() 
		{
			//links
			$this->data['header'] = $this->load->controller('common/header');
			$this->data['column_left'] = $this->load->controller('common/column_left');
			$this->data['footer'] = $this->load->controller('common/footer');
			$this->data['action'] = $this->url->link('extension/module/product_crawler/upload', 'token=' . $this->session->data['token'], true);
			$this->data['deleteUrl'] = $this->url->link('extension/module/product_crawler/delete', 'token=' . $this->session->data['token'], true);

			//Link to upload form
			$this->data['upload_form'] = $this->url->link('catalog/product/add', 'token=' . $this->session->data['token'], true);

			//pagination link
			$this->data['crawler_link'] = $this->url->link('extension/module/product_crawler', 'token=' . $this->session->data['token']);

			//updates link
			$this->data['updates_link'] = $this->url->link('extension/module/product_crawler/showUpdates', 'token=' . $this->session->data['token']);
			
			//pagination
			if (isset($this->request->get['page'])) {
				$page = intval($this->request->get['page']);
			}
			else {
				$page = 1;
			}

			$itemsPerPage = 30;

			$start = $itemsPerPage * ($page - 1);

			//get all pages
			$this->load->model('extension/module/crawled_product');

			$allProducts = $this->model_extension_module_crawled_product->getAllProducts();

			$this->data['pages'] = ceil(count($allProducts) / $itemsPerPage);

			//All products
			$this->data['allProducts'] = $this->model_extension_module_crawled_product->getAllProducts($start, $itemsPerPage);

			//Products waiting for update
			$this->data['updateProducts'] = $this->model_extension_module_crawled_product->getUpdateProducts($start, $itemsPerPage);

			//Edit product link
			$this->data['editLink'] = $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'], true);

			//Delete update link
			$this->data['deleteUpdateLink'] = $this->url->link('extension/module/product_crawler/deleteUpdate', 'token=' . $this->session->data['token'], true);

			//Delete all link
			$this->data['deleteAllLink'] = $this->url->link('extension/module/product_crawler/deleteAll', 'token=' . $this->session->data['token'], true);
		}

		public function index()
		{
			$this->setData();

			$this->response->setOutput($this->load->view('extension/module/product_crawler', $this->data));
		}

		public function install()
		{
			$db = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

			$sql = 'CREATE TABLE IF NOT EXISTS `oc_crawled_products` (
				id int NOT NULL AUTO_INCREMENT,
			    product_name varchar(250),
			    product_code varchar(250),
			    product_price decimal(11,2),
			    product_quantity varchar(250),
			    product_manufacturer varchar(250),
			    store varchar(250),
			    PRIMARY KEY (id)
			)';

			mysqli_query($db, $sql);
		}

		public function upload()
		{
			$this->load->model('extension/module/crawled_product');

			if (count($this->model_extension_module_crawled_product->getAllProducts())) {
				$_SESSION['error'] = 'Качете или изтрийте всички чакащи продукти за да пуснете нов паяк';
				$this->response->redirect($this->url->link('extension/module/product_crawler', 'token=' . $this->session->data['token'], true));
			}

			if (isset($_POST['site-url']) && isset($_POST['site'])) {
				$html = file_get_contents($_POST['site-url']);

				$crawler = new Crawler($html);

				//Dims crawler
				if ($_POST['site'] == 'dims-92') {
					//$html = file_get_contents('C:\Users\123\Desktop\dims.html');
					//$crawler = new Crawler($html);

					$html = file_get_contents('http://dims-92.com/AnonymousProductCatalogPage');
					$crawler = new Crawler($html);

					//Cycle through pages
					$pages = $crawler->filter('table[width="170px"]');

					$pages->each(function (Crawler $node, $i) {
						$link = $node->filter('a')->first();
						
						$link = $link->attr('href');

						if (strpos($link, 'dims-92.com')) {
							//Single page crawler
							$singlePage = new Crawler(file_get_contents($link));

							$productInfo = $singlePage->filter('table[width=140]');

							$productInfo->each(function (Crawler $node, $i) {
								$product = array();

								//Names
								$product['name'] = $node->filter('td .productName')->first()->text();

								//Prices
								$productTR = $node->filter('tr');
								if ($productTR->count() == 7 || $productTR->count() == 6) {
									$price = $node->filter('tr:nth-child(4)')->first()->text();
								}
								else {
									$price = $node->filter('tr:nth-child(5)')->first()->text();
								}
								$price = str_replace('Цена: ', '', $price);
								$price = str_replace(' лв.', '', $price);
								$product['price'] = $price;

								//Codes
								$productCode = $node->filter('tr:nth-child(3)')->first()->text();
								$product['code'] = str_replace('Код: ', '', $productCode);
								$product['manufacturer'] = '';

								//Quantity
								$quantity = $node->filter('tr:nth-last-child(2)')->first()->text();

								if (strpos($quantity, 'В наличност:') !== false) {
									$product['quantity'] = 'Не';
								}
								else {
									$product['quantity'] = 'Да';
								}

								$product['store'] = 'dims92';

								$this->products[] = $product;
							});
						}
					});
				}

				else if ($_POST['site'] == 'sky-r') {
					$productInfo = $crawler->filter("#productList div.product");

					$productInfo->each(function (Crawler $node, $i) {
						$product = array();

						//Names
						$product['name'] = $node->filter('.descr .pTitle')->first()->text();

						//Prices
						$price = $node->filter('.descr .price')->first()->text();
						$price = str_replace('Цена: ', '', $price);
						$price = str_replace(' лв', '', $price);
						$product['price'] = $price;

						//Codes
						$productCodeArray = explode(' - ', $node->filter('.descr .pCatNumber')->first()->text());
						$product['code'] = $productCodeArray[0];
						$product['manufacturer'] = $productCodeArray[1];

						//Quantity
						$quantity = $node->filter('a #text')->first();

						if ($quantity->count()) {
							$product['quantity'] = 'Не';
						}
						else {
							$product['quantity'] = 'Да';
						}

						$product['store'] == 'sky-r';

						$this->products[] = $product;
					});
				}

				else if ($_POST['site'] == 'vip-giftshop') {
					$productInfo = $crawler->filter('.item');

					$productInfo->each(function (Crawler $node, $i) {
						$product = array();

						//Names
						$product['name'] = $node->filter('.product-name')->first()->text();

						//Prices
						$price = $node->filter('.price-box .price')->first()->text();
						$price = str_replace('лв.', '', $price);
						$price = str_replace(',', '.', $price);
						$product['price'] = floatval($price);

						//Codes
						$code = $node->filter('.sku')->first()->text();
						$code = str_replace('Art. No', '', $code);
						$product['code'] = trim($code);

						//Quantities
						$quantity = $node->filter('.actions button')->first();

						if ($quantity->count()) {
							$product['quantity'] = 'Да';
						}
						else {
							$product['quantity'] = 'Не';
						}

						$product['manufacturer'] = '';

						$product['store'] = 'vip-giftshop';

						$this->products[] = $product;
					});
				}

				else if ($_POST['site'] == 'art93') {
					$productInfo = $crawler->filter('.col-lg-3');

					$productInfo->each(function (Crawler $node, $i) {
						$product = array();

						//Names
						$product['name'] = $node->filter('h2')->first()->text();

						//Prices
						$price = $node->filter('.bottom h3')->first()->text();
						$product['price'] = str_replace('лв.', '', $price);

						//Codes
						$code = $node->filter('.bottom p')->first()->text();

						if (strpos($code, 'Артикулен') !== false) {
							$code = str_replace('Артикулен', '', $code);
							$code = substr($code, 9);
						}
						
						$product['code'] = $code;

						//Quantities
						$quantity = $node->filter('div[position=absolute]')->first();

						if ($quantity->count()) {
							$product['quantity'] = 'Не';
						}
						else {
							$product['quantity'] = 'Да';
						}

						$product['manufacturer'] = '';

						$product['store'] = 'art93';

						$this->products[] = $product;
					});
				}

				else if ($_POST['site'] == 'wenger') {
					$productInfo = $crawler->filter('.ty-column4');

					$productInfo->each(function (Crawler $node, $i) {
						$product = array();

						//Names
						$product['name'] = $node->filter('.ty-grid-list__item-name .product-title')->first()->text();

						//Prices
						$price = str_replace(',', '.', $node->filter('.ty-price-num')->first()->text());
						$product['price'] = str_replace(' ', '', $price);

						//Codes
						$productPageLink = $node->selectLink('Научи повече');
						$productPageLink = $productPageLink->link();
						$productPageLink = $productPageLink->getUri();

						$productPage = new Crawler(file_get_contents($productPageLink)); 

						$code = $productPage->filter('.ty-control-group__item')->first()->text();
						
						$product['code'] = $code;

						//Quantities
						$quantity = $productPage->filter('#qty_in_stock_')->first()->text();

						if ($quantity == 'В наличност') {
							$product['quantity'] = 'Да';
						}
						else {
							$product['quantity'] = 'Не';
						}

						$product['manufacturer'] = '';

						$product['store'] = 'wenger';

						$this->products[] = $product;
					});
				}

				else if ($_POST['site'] == 'max-pen') {
					$productInfo = $crawler->filter('.grupi select option');

					$productInfo->each(function (Crawler $node, $i) {
						$product = array();

						$option = $node->attr('value');
						
						//CURL
						if ($option != 0) {
							$ch = curl_init();
							curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
							$params = array(
								"__EVENTTARGET"=>"",
								"__EVENTARGUMENT"=>"",
								"ddlstSorting"=>"1",
								"chkOnlyAvailable"=>"on",
							    "ddlstGroups"=> $option,
							    "ddlstColor" => "0",
							    "txtSearchProduct" => "",
							);
							curl_setopt($ch,CURLOPT_URL,"http://max-pen.no-ip.info/Default.aspx");
							curl_setopt($ch,CURLOPT_POST,true);
							curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($params));
							$result = curl_exec($ch);

							$finalProducts = new Crawler($result);

							if ($finalProducts->filter('.item')->count()) {
								$finalProducts = $finalProducts->filter('.item');

								$finalProducts->each(function (Crawler $innerNode, $k) {
									//Names
									$product['name'] = $innerNode->filter('.info div:first-child strong')->text();

									//Prices
									$price = str_replace(' $', '', $innerNode->filter('.price .num')->first()->text());
									$product['price'] = round($price, 2);

									//Codes
									$product['code'] = $innerNode->filter('.info div:first-child strong')->text();

									//Quantity
									$quantity = $innerNode->filter('.price div')->first()->text();

									preg_match_all('!\d+!', $quantity, $matches);

									if (trim($matches[0][0]) > 0) {
										$product['quantity'] = 'Да';
									}
									else {
										$product['quantity'] = 'Не';
									}

									$product['manufacturer'] = '';

									$product['store'] = 'max-pen';

									$this->products[] = $product;
								});
							}
						}
					});
				}

				$this->load->model('extension/module/uploaded_code');
				$this->load->model('catalog/product');

				//No new products for now 
				$countNewProducts = 0; 

				$countProductsForUpdates = 0;

				foreach ($this->products as $product) { 
					//Check if product code already exists in database

					//Check if code is uploaded
					$queryCheckUploaded = $this->model_extension_module_uploaded_code->getByCode($product['code'], $product['store']);

					//If it not exists insert it
					if (!count($queryCheckUploaded)) {
						$this->model_extension_module_crawled_product->upload($product['name'], $product['code'], $product['price'], $product['quantity'], $product['store'], $product['manufacturer']);

						$countNewProducts++;
					}
					//If product exists
					else {
						$updated_prices = 0;

						$this->load->model('catalog/product');

						//update
						$uploaded_product = $this->model_catalog_product->getProduct($queryCheckUploaded['product_id']);

						$price = $uploaded_product['price'];

						if ($uploaded_product['quantity'] > 0) {
							$quantity = 'Да';
						}
						else {
							$quantity = 'Не';
						}

						//Find product id by store and code
						$uploaded_code = $this->model_extension_module_uploaded_code->getByCode($product['code'], $product['store']);
						$product_id = $uploaded_code['product_id'];

						//update price
						$this->model_catalog_product->updatePrice($product_id, $product['price']);

						$updated_prices++;

						//Checks if it's in color connection
						$colorConnection = $this->model_extension_module_uploaded_code->colorConnection($product['code']);

						if (count($colorConnection) > 0) {
							//Get product that is connected
							$connectedProduct = $this->model_extension_module_uploaded_code->getProductByCodeAndStore($product['code'], $product['store']);

							$currentColorQuantity = $this->model_extension_module_uploaded_code->getColorQuantity($connectedProduct['product_option_value_id']);
							$currentColorQuantity = $currentColorQuantity['quantity'];

							if ($product['quantity'] == 'Да') {
								$color_quantity = 1000;
							}
							else {
								$color_quantity = 0;
							}

							//Update color quantity
							$this->model_extension_module_uploaded_code->updateColorQuantity($connectedProduct['product_option_value_id'], $color_quantity);
						}
						else {
							//update quantity
							if ($product['quantity'] == 'Да') {
								$quantityToUpdateWith = 1000;
							}
							else {
								$quantityToUpdateWith = 0;
							}

							$this->model_catalog_product->updateQuantity($product_id, $quantityToUpdateWith);
						}
					}
				}

				if ($countNewProducts > 1) {
					$productsMessage = 'Бяха намерени ' . $countNewProducts . ' нови продукта';
				}
				elseif ($countNewProducts == 1) {
					$productsMessage = 'Беше намерен 1 нов продукт';
				}
				else {
					$productsMessage = 'Не бяха намерени нови проудкти';
				}

				//Updated products
				if ($updated_prices > 0) {
					$updated_prices_message = 'Бяха променени цените на ' . $updated_prices . ' продукта';
				}

				$_SESSION['newProducts'] = $productsMessage;
				$_SESSION['updateProducts'] = $updated_prices_message;
				if ($countNewProducts > 0) {
					$_SESSION['uploaded'] = 'Продукти от ' . htmlspecialchars($_POST['site']) . ' бяха качени успешно';
				}
			}

			$this->response->redirect($this->url->link('extension/module/product_crawler', 'token=' . $this->session->data['token'], true));
		}

		public function showUpdates()
		{
			$this->setData();

			$this->response->setOutput($this->load->view('extension/module/crawler_updates', $this->data));
		}

		public function delete()
		{
			$this->load->model('extension/module/crawled_product');

			if (isset($_GET['id'])) {
				$id = intval($_GET['id']);
				
				if ($this->model_extension_module_crawled_product->delete($id)) {
					$_SESSION['deleted'] = 'Продуктът беше изтрит';
				}
				else {
					$_SESSION['deleted'] = 'Изинка проблем с изтриването на продукта';
				}
			}

			$this->response->redirect($this->url->link('extension/module/product_crawler', 'token=' . $this->session->data['token'], true));
		}

		public function deleteUpdate()
		{
			$this->load->model('extension/module/crawled_product');

			if (isset($_GET['id'])) {
				$id = intval($_GET['id']);
				
				if ($this->model_extension_module_crawled_product->deleteUpdate($id)) {
					$_SESSION['deleted'] = 'Промяната беше изтрита';
				}
				else {
					$_SESSION['deleted'] = 'Изинка проблем с изтриването на промяната';
				}
			}

			$this->response->redirect($this->url->link('extension/module/crawler_updates', 'token=' . $this->session->data['token'], true));
		}

		public function deleteAll()
		{
			$this->load->model('extension/module/crawled_product');

			if ($this->model_extension_module_crawled_product->deleteAll()) {
				$_SESSION['all-deleted'] = 'Продуктите бяха изтрити';
			}
			else {
				$_SESSION['all-deleted-error'] = 'Възникна проблем при изтриването на проудктите';
			}

			$this->response->redirect($this->url->link('extension/module/product_crawler', 'token=' . $this->session->data['token'], true));
		}
	}
?>