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

		private $store = null;

		//Pagination
		private $start;
		private $limit = 30;

		private $sky_r = 'http://www.sky-r.com';
		private $sky_r_manifacturers = array();
		private $sky_r_pages = array();

		private $max_pen_option;
		private $max_pen_pages = array();

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

			//clean link
			$this->data['product_crawler_link'] = $this->url->link('extension/module/product_crawler', 'token=' . $this->session->data['token']);

			//updates link
			$this->data['updates_link'] = $this->url->link('extension/module/product_crawler/showUpdates', 'token=' . $this->session->data['token']);
			
			//pagination
			if (isset($this->request->get['page'])) {
				$page = intval($this->request->get['page']);
			}
			else {
				$page = 1;
			}

			$this->data['sort'] = null;
			if (isset($this->request->get['sort'])) {
				$this->data['sort'] = $this->request->get['sort'];
			}
			$this->data['order'] = 'DESC';
			if (isset($this->request->get['order'])) {
				$this->data['order'] = $this->request->get['order'];
			}

			if (isset($this->request->get['site'])) {
				$this->store = $this->request->get['site'];
			}

			$this->start = $this->limit * ($page - 1);

			//get all pages
			$this->load->model('extension/module/crawled_product');

			//uploaded codes
			$this->load->model('extension/module/uploaded_code');

			$allProducts = $this->model_extension_module_crawled_product->getAllProducts($this->store);

			$this->data['pages'] = ceil(count($allProducts) / $this->limit);

			//All products
			$this->data['allProducts'] = $this->model_extension_module_crawled_product->getAllProducts($this->store, $this->start, $this->limit, $this->data['sort'], $this->data['order']);

			//pagination link
			$this->data['crawler_link'] = $this->url->link('extension/module/product_crawler', 'token=' . $this->session->data['token'], true);

			//Products waiting for update
			$this->data['updateProducts'] = $this->model_extension_module_crawled_product->getUpdateProducts($this->start, $this->limit);

			//Edit product link
			$this->data['editLink'] = $this->url->link('catalog/product/edit', 'token=' . $this->session->data['token'], true);

			//Delete update link
			$this->data['deleteUpdateLink'] = $this->url->link('extension/module/product_crawler/deleteUpdate', 'token=' . $this->session->data['token'], true);

			//Delete all link
			$this->data['deleteAllLink'] = $this->url->link('extension/module/product_crawler/deleteAll', 'token=' . $this->session->data['token'], true);

			//All stores
			$this->data['stores'] = $this->model_extension_module_crawled_product->getStores();

			//Update percent link
			$this->data['updatePercentLink'] = $this->url->link('extension/module/product_crawler/updatePercent', 'token=' . $this->session->data['token'], true);

			//count products
			$this->data['count_all'] = $this->model_extension_module_crawled_product->countProducts();

			//instock count
			$this->data['count_in_stock'] = $this->model_extension_module_crawled_product->countInStock();

			//out of stock count
			$this->data['count_out_of_stock'] = $this->model_extension_module_crawled_product->countOutOfStock();

			//delete from store
			$this->data['delete_from_store'] = $this->url->link('extension/module/product_crawler/deleteFromStore', 'token=' . $this->session->data['token'], true);

			//All
			$this->data['all'] = $this->model_extension_module_uploaded_code->getAll();
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

		private function crawlMaxPenProductsPage($finalProducts) {
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

					//Color
					$color = $innerNode->filter('.info div:nth-child(3)')->text();
					$color = str_replace('Цвят: ', '', $color);
					
					$product['code'] .= '_' . $color;

					$product['name'] = str_replace('-', '_', $product['name']);
					$product['code'] = str_replace('-', '_', $product['code']);

					//Quantity
					$quantity = $innerNode->filter('.price div')->first()->text();

					preg_match_all('!\d+!', $quantity, $matches);

					$product['quantity'] = trim($matches[0][0]);

					$product['manufacturer'] = '';

					$product['store'] = 'max-pen';

					$this->products[] = $product;
				});
			}
		}

		private function setCurlParametersMaxPen($option, $i = "") {
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			$params = array(
				"__EVENTTARGET"=>"lnkPgr",
				"__EVENTARGUMENT"=>$i,
				"ddlstthis->sorting"=>"1",
				"chkOnlyAvailable"=>"on",
			    "ddlstGroups"=> $option,
			    "ddlstColor" => "0",
			    "txtSearchProduct" => "",
			);
			curl_setopt($ch,CURLOPT_URL,"http://max-pen.no-ip.info/Default.aspx");
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($params));
			$result = curl_exec($ch);
			return $result;
		}

		public function upload()
		{		
			$this->load->model('extension/module/crawled_product');

			// if (count($this->model_extension_module_crawled_product->getAllProducts()) || count($this->model_extension_module_crawled_product->getAllUpdates())) {
			// 	$_SESSION['error'] = 'Качете или изтрийте всички чакащи продукти за да пуснете нов паяк';
			// 	$this->response->redirect($this->url->link('extension/module/product_crawler', 'token=' . $this->session->data['token'], true));
			// }

			//try {
				if (isset($_POST['site-url']) && isset($_POST['site'])) {
				//Dims crawler
				if ($_POST['site'] == 'dims-92') {
					//Upload a blank cookie.txt to the same directory as this file with a CHMOD/Permission to 777
					function login($url,$data){
					    $fp = fopen("cookie.txt", "w");
					    fclose($fp);
					    $login = curl_init();
					    curl_setopt($login, CURLOPT_COOKIEJAR, "cookie.txt");
					    curl_setopt($login, CURLOPT_COOKIEFILE, "cookie.txt");
					    curl_setopt($login, CURLOPT_TIMEOUT, 40000);
					    curl_setopt($login, CURLOPT_RETURNTRANSFER, TRUE);
					    curl_setopt($login, CURLOPT_URL, $url);
					    curl_setopt($login, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
					    curl_setopt($login, CURLOPT_FOLLOWLOCATION, TRUE);
					    curl_setopt($login, CURLOPT_POST, TRUE);
					    curl_setopt($login, CURLOPT_POSTFIELDS, $data);
					    ob_start();
					    return curl_exec ($login);
					    ob_end_clean();
					    curl_close ($login);
					    unset($login);    
					} 

					function grab_page($site){
					    $ch = curl_init();
					    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
					    curl_setopt($ch, CURLOPT_TIMEOUT, 40);
					    curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
					    curl_setopt($ch, CURLOPT_URL, $site);
					    ob_start();
					    return curl_exec ($ch);
					    ob_end_clean();
					    curl_close ($ch);
					}

					login("http://dims-92.com/AnonymousLogInPage", "SubmitControlId=Auto_CAuthenticate_LogIn_LogIn_Standart&ParameterInfo=41757468656e7469636174653a&FC_CEShop_SearchControl_SearchInput=&FC_CAuthenticate_LogIn_UsernameInput=972&FC_CAuthenticate_LogIn_PasswordInput=bggift");

					$html = grab_page("http://dims-92.com/AnonymousProductCatalogPage");
					$crawler = new Crawler($html);

					//Cycle through pages
					$pages = $crawler->filter('table[width="170px"]');

					$pages->each(function (Crawler $node, $i) {
						$link = $node->filter('a')->first();
						
						$link = $link->attr('href');

						if (strpos($link, 'dims-92.com')) {
							//Single page crawler
							$singlePage = new Crawler(grab_page($link));
							//$html = file_get_contents("http://weband.bg/ecommerce/dev/tempSites/dims.html");
							//$singlePage = new Crawler($html);

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
									$product['quantity'] = 0;
								}
								else {
									$product['quantity'] = 1000;
								}

								$product['store'] = 'dims92';

								$this->products[] = $product;
							});
						}
					});
				}

				else if ($_POST['site'] == 'sky-r') {
					$html = file_get_contents('http://www.sky-r.com/view/home.htm');

					$crawler = new Crawler($html);

					$categories = $crawler->filter('#nav li.btn a:first-child');

					$categories->each(function (Crawler $linkNode, $i) {
						$link = $this->sky_r . $linkNode->attr('href');

						if (str_replace('  ', ' ', mb_strtolower(trim($linkNode->text()), 'UTF-8')) != 'изчерпани артикули') {
							$categoryPage = new Crawler(file_get_contents($link));

							$manifacturerLinks = $categoryPage->filter('#manifacturerList .manifacturer a:first-child');

							if ($manifacturerLinks->count()) {
								$manifacturerLinks->each(function (Crawler $manifacturerLinkNode, $j) {
									$manifacturerPageLink = $this->sky_r . $manifacturerLinkNode->attr('href');

									$this->sky_r_pages = array();

									$manifacturerText = explode('/', $manifacturerLinkNode->attr('href'));
									$manifacturerText = explode('.', end($manifacturerText));
									if (!in_array(trim($manifacturerText[0]), $this->sky_r_manifacturers)) {
										$this->sky_r_manifacturers[] = trim($manifacturerText[0]);
									}

									$manifacturerPage = new Crawler(file_get_contents(str_replace(' ', '%20', $manifacturerPageLink)));

									$productPages = $manifacturerPage->filter('#productPaging a');

									if ($productPages->count()) {
										$productPages->each(function (Crawler $pageNode, $k) {
											if (strlen($pageNode->attr('href')) && trim($pageNode->attr('href'))) {
												if (!in_array(trim($pageNode->attr('href')), $this->sky_r_pages) && trim($pageNode->attr('href') != '#')) {
													$this->sky_r_pages[] = trim($pageNode->attr('href'));
												}
											}
										});
									}

									$productInfo = $manifacturerPage->filter("#productList div.product");

									$productInfo->each(function (Crawler $node, $i) {
										$product = array();

										//Names
										$product['name'] = $node->filter('.descr .pTitle')->first()->text();

										//Prices
										//Determine whether you have to be logged or not for the price
										$lock = $node->filter('.descr .price a');
										$lock_2 = $node->filter('.descr .promoprice a');

										$price = $node->filter('.descr .price')->first();
										if (!$price->count()) {
											$price = $node->filter('.descr .promoprice')->first()->text();
											preg_match_all('!\d+!', $price, $matches);
											$price = floatval($matches[0][0] . '.' . $matches[0][1]);
										}
										else {
											$price = $price->text();
											$price = str_replace('Цена: ', '', $price);
											$price = str_replace(' лв', '', $price);
											$price = str_replace(' РА', '', $price);
											$price = str_replace(',', '', $price);
										}
										
										$product['price'] = $price;

										//Codes
										$productCodeArray = explode(' - ', $node->filter('.descr .pCatNumber')->first()->text());
										$code = $productCodeArray[0];
										if (in_array(trim($code), $this->sky_r_manifacturers)) {
											$product['code'] = $node->filter('.descr .pTitle')->first()->text();
											$product['manufacturer'] = trim($code);
										}
										else {
											if (strlen(trim($code))) {
												$product['code'] = trim($code);
												$product['manufacturer'] = trim($productCodeArray[1]);
											}
											else {
												$product['code'] = $node->filter('.descr .pTitle')->first()->text();
												$product['manufacturer'] = '';
											}
										}

										//Quantity
										$quantity = $node->filter('a #text')->first();

										if ($quantity->count()) {
											$product['quantity'] = '0';
										}
										else {
											$product['quantity'] = '1000';
										}

										$product['store'] = 'sky-r';

										if (!$lock->count() && !$lock_2->count()) {
											$this->products[] = $product;
										}
									});

									if (count($this->sky_r_pages)) {
										foreach ($this->sky_r_pages as $productPage) {
											$pageProducts = new Crawler(file_get_contents(str_replace(' ', '%20', $this->sky_r . $productPage)));

											$productInfo = $pageProducts->filter("#productList div.product");

											$productInfo->each(function (Crawler $node, $i) {
												$product = array();

												//Names
												$product['name'] = $node->filter('.descr .pTitle')->first()->text();

												//Prices
												//Determine whether you have to be logged or not for the price
												$lock = $node->filter('.descr .price a');
												$lock_2 = $node->filter('.descr .promoprice a');

												$price = $node->filter('.descr .price')->first();
												if (!$price->count()) {
													$price = $node->filter('.descr .promoprice')->first()->text();
													preg_match_all('!\d+!', $price, $matches);
													$price = floatval($matches[0][0] . '.' . $matches[0][1]);
												}
												else {
													$price = $price->text();
													$price = str_replace('Цена: ', '', $price);
													$price = str_replace(' лв', '', $price);
													$price = str_replace(' РА', '', $price);
													$price = str_replace(',', '', $price);
												}
												
												$product['price'] = $price;

												//Codes
												$productCodeArray = explode(' - ', $node->filter('.descr .pCatNumber')->first()->text());
												$code = $productCodeArray[0];
												if (in_array(trim($code), $this->sky_r_manifacturers)) {
													$product['code'] = $node->filter('.descr .pTitle')->first()->text();
													$product['manufacturer'] = trim($code);
												}
												else {
													if (strlen(trim($code))) {
														$product['code'] = trim($code);
														$product['manufacturer'] = trim($productCodeArray[1]);
													}
													else {
														$product['code'] = $node->filter('.descr .pTitle')->first()->text();
														$product['manufacturer'] = '';
													}
												}

												//Quantity
												$quantity = $node->filter('a #text')->first();

												if ($quantity->count()) {
													$product['quantity'] = '0';
												}
												else {
													$product['quantity'] = '1000';
												}

												$product['store'] = 'sky-r';

												if (!$lock->count() && !$lock_2->count()) {
													$this->products[] = $product;
												}
											});
										}
									}
								});
							}
							else {
								$productPages = $categoryPage->filter('#productPaging a');

								if ($productPages->count()) {
									$productPages->each(function (Crawler $pageNode, $k) {
										if (strlen($pageNode->attr('href')) && trim($pageNode->attr('href'))) {
											if (!in_array(trim($pageNode->attr('href')), $this->sky_r_pages) && trim($pageNode->attr('href') != '#')) {
												$this->sky_r_pages[] = trim($pageNode->attr('href'));
											}
										}
									});
								}

								$productInfo = $categoryPage->filter("#productList div.product");

								$productInfo->each(function (Crawler $node, $i) {
									$product = array();

									//Names
									$product['name'] = $node->filter('.descr .pTitle')->first()->text();

									//Prices
									//Determine whether you have to be logged or not for the price
									$lock = $node->filter('.descr .price a');
									$lock_2 = $node->filter('.descr .promoprice a');

									$price = $node->filter('.descr .price')->first();
									if (!$price->count()) {
										$price = $node->filter('.descr .promoprice')->first()->text();
										preg_match_all('!\d+!', $price, $matches);
										$price = floatval($matches[0][0] . '.' . $matches[0][1]);
									}
									else {
										$price = $price->text();
										$price = str_replace('Цена: ', '', $price);
										$price = str_replace(' лв', '', $price);
										$price = str_replace(' РА', '', $price);
										$price = str_replace(',', '', $price);
									}
									
									$product['price'] = $price;

									//Codes
									$productCodeArray = explode(' - ', $node->filter('.descr .pCatNumber')->first()->text());
									$code = $productCodeArray[0];
									if (in_array(trim($code), $this->sky_r_manifacturers)) {
										$product['code'] = $node->filter('.descr .pTitle')->first()->text();
										$product['manufacturer'] = trim($code);
									}
									else {
										if (strlen(trim($code))) {
											$product['code'] = trim($code);
											$product['manufacturer'] = trim($productCodeArray[1]);
										}
										else {
											$product['code'] = $node->filter('.descr .pTitle')->first()->text();
											$product['manufacturer'] = '';
										}
									}

									//Quantity
									$quantity = $node->filter('a #text')->first();

									if ($quantity->count()) {
										$product['quantity'] = '0';
									}
									else {
										$product['quantity'] = '1000';
									}

									$product['store'] = 'sky-r';

									if (!$lock->count() && !$lock_2->count()) {
										$this->products[] = $product;
									}
								});

								if (count($this->sky_r_pages)) {
									foreach ($this->sky_r_pages as $productPage) {
										$pageProducts = new Crawler(file_get_contents(str_replace(' ', '%20', $this->sky_r . $productPage)));

										$productInfo = $pageProducts->filter("#productList div.product");

										$productInfo->each(function (Crawler $node, $i) {
											$product = array();

											//Names
											$product['name'] = $node->filter('.descr .pTitle')->first()->text();

											//Prices
											//Determine whether you have to be logged or not for the price
											$lock = $node->filter('.descr .price a');
											$lock_2 = $node->filter('.descr .promoprice a');

											$price = $node->filter('.descr .price')->first();
											if (!$price->count()) {
												$price = $node->filter('.descr .promoprice')->first()->text();
												preg_match_all('!\d+!', $price, $matches);
												$price = floatval($matches[0][0] . '.' . $matches[0][1]);
											}
											else {
												$price = $price->text();
												$price = str_replace('Цена: ', '', $price);
												$price = str_replace(' лв', '', $price);
												$price = str_replace(' РА', '', $price);
												$price = str_replace(',', '', $price);
											}
											
											$product['price'] = $price;

											//Codes
											$productCodeArray = explode(' - ', $node->filter('.descr .pCatNumber')->first()->text());
											$code = $productCodeArray[0];
											if (in_array(trim($code), $this->sky_r_manifacturers)) {
												$product['code'] = $node->filter('.descr .pTitle')->first()->text();
												$product['manufacturer'] = trim($code);
											}
											else {
												if (strlen(trim($code))) {
													$product['code'] = trim($code);
													$product['manufacturer'] = trim($productCodeArray[1]);
												}
												else {
													$product['code'] = $node->filter('.descr .pTitle')->first()->text();
													$product['manufacturer'] = '';
												}
											}

											//Quantity
											$quantity = $node->filter('a #text')->first();

											if ($quantity->count()) {
												$product['quantity'] = '0';
											}
											else {
												$product['quantity'] = '1000';
											}

											$product['store'] = 'sky-r';

											if (!$lock->count() && !$lock_2->count()) {
												$this->products[] = $product;
											}
										});
									}
								}
							}
						}
					});
				}

				else if ($_POST['site'] == 'vip-giftshop') {
					$html = file_get_contents($_POST['site-url']);

					$crawler = new Crawler($html);

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
					$html = file_get_contents($_POST['site-url']);

					$crawler = new Crawler($html);

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
					$html = file_get_contents($_POST['site-url']);

					$crawler = new Crawler($html);

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
					$html = file_get_contents('http://max-pen.no-ip.info/Default.aspx');

					$crawler = new Crawler($html);

					$productInfo = $crawler->filter('.grupi select option');

					$productInfo->each(function (Crawler $node, $i) {
						$product = array();

						$this->max_pen_pages = array();

						$this->option = $node->attr('value');
						
						//CURL
						if ($this->option != 0) {
							$result = $this->setCurlParametersMaxPen($this->option);

							$finalProducts = new Crawler($result);

							$this->crawlMaxPenProductsPage($finalProducts);

							//Pages
							$pages = new Crawler($result);
							$pages = $pages->filter('div.pages ul li a');

							if ($pages->count()) {
								$pages->each(function (Crawler $pageNode, $j) {
									if (intval($pageNode->text())) {
										if (strpos($pageNode->text(), '-') !== false) {
											$this->max_pen_pages[] = $pageNode->text();
										}
									}
								});

								if (count($this->max_pen_pages)) {
									$max_page = max($this->max_pen_pages);
									$max_page = explode(' - ', $max_page);
									$max_page = max($max_page);

									for ($i=2; $i <= $max_page; $i++) { 
										$result_2 = $this->setCurlParametersMaxPen($this->option, $i);

										$finalProducts_2 = new Crawler($result_2);

										$this->crawlMaxPenProductsPage($finalProducts_2);
									}
								}
								else {
									$pages->each(function (Crawler $innerPageNode, $l) {
										if (intval($innerPageNode->text())) {
											$result_3 = $this->setCurlParametersMaxPen($this->option, trim($innerPageNode->text()));

											$finalProducts_3 = new Crawler($result_3);

											$this->crawlMaxPenProductsPage($finalProducts_3);
										}
									});
								}
							}
						}
					});
				}

				$this->load->model('extension/module/uploaded_code');
				$this->load->model('catalog/product');

				//No new products for now 
				$countNewProducts = 0; 

				$countProductsForUpdates = 0;

				$updated_prices = 0;

				foreach ($this->products as $product) { 
					//Check if product code already exists in database

					//Update the price
					$percent = $this->model_extension_module_crawled_product->getStorePercent($product['store']);
					$product['price'] += ($percent['percent'] / 100) * $product['price']; 

					//Check if code is uploaded
					$queryCheckUploaded = $this->model_extension_module_uploaded_code->getByCode($product['code'], $product['store']);

					//Check if exists in the temp table
					$queryCheckUploadedCrawled = $this->model_extension_module_crawled_product->getProductByCode($product['code'], $product['store']);

					if (count($queryCheckUploadedCrawled)) {
						continue;
					}

					//If it not exists insert it
					if (!count($queryCheckUploaded)) {
						if (mb_strtolower($product['quantity'], 'UTF-8') == 'да') {
							$product['quantity'] = 1000;
						}
						elseif(mb_strtolower($product['quantity'], 'UTF-8') == 'не') {
							$product['quantity'] = 0;
						}

						$this->model_extension_module_crawled_product->upload($product['name'], $product['code'], $product['price'], $product['quantity'], $product['store'], $product['manufacturer']);

						$countNewProducts++;
					}
					//If product exists
					else {
						$this->load->model('catalog/product');

						//update
						$uploaded_product = $this->model_catalog_product->getProduct($queryCheckUploaded['product_id']);

						$price = $uploaded_product['price'];

						$quantity = $uploaded_product['quantity'];

						//Find product id by store and code
						$product_id = $uploaded_product['product_id'];

						//Checks if it's in color connection
						$colorConnection = $this->model_extension_module_uploaded_code->colorConnection($product['code']);

						if (count($colorConnection) > 0) {
							//Get product that is connected
							$connectedProduct = $this->model_extension_module_uploaded_code->getProductByCodeAndStore($product['code'], $product['store']);

							$currentColorQuantity = $this->model_extension_module_uploaded_code->getColorQuantity($connectedProduct['product_option_value_id']);
							$currentColorQuantity = $currentColorQuantity['quantity'];

							$color_quantity = $product['quantity'];

							//Get the price of the color plus the pride of the product
							//Get the product option info (containing the price)
							$colorInfo = $this->model_extension_module_uploaded_code->getColorPrice($connectedProduct['product_option_value_id']);
							$price = $colorInfo['price'];

							if ($colorInfo['price_prefix'] == '+') {
								$price += $uploaded_product['price'];
							}
							else {
								$price -= $uploaded_product['price'];
							}

							//Check if price is different
							if ($product['price'] != $price) {
								$this->model_extension_module_crawled_product->uploadInUpdates($uploaded_product['product_id'], $product['price'],$colorConnection['product_option_value_id']);
								$updated_prices++;
							}

							//Update color quantity
							$this->model_extension_module_uploaded_code->updateColorQuantity($connectedProduct['product_option_value_id'], $color_quantity);
						}
						else {
							//update price
							if (strval(floatval($product['price'])) != strval(floatval($price))) {
								$this->model_extension_module_crawled_product->uploadInUpdates($uploaded_product['product_id'], $product['price']);
								$updated_prices++;
							}	

							//update quantity
							$quantityToUpdateWith = $product['quantity'];

							$this->model_catalog_product->updateQuantity($product_id, $quantityToUpdateWith);
						}
					}
				}

				//Reverse search for products

				//Get all uploaded codes
				$allUploadedCodes = $this->model_extension_module_uploaded_code->getByStore($this->products[0]['store']);

				$uploadedToUpdate = array();

				$uploaded = true;

				foreach ($allUploadedCodes as $uploadedCode) {
					$found = false;
					//Check if it exists in the currently crawled products
					foreach ($this->products as $crawledProduct) {
						if ($uploadedCode['admin_code'] == $crawledProduct['code']) {
							$found = true;
							break;
						}
					}

					if (!$found) {
						$uploadedToUpdate[] = $uploadedCode;
					}
				}

				//Get all temp codes
				$allTempCodes = $this->model_extension_module_crawled_product->getByStore($this->products[0]['store']);

				$tempToUpdate = array();

				foreach ($allTempCodes as $tempCode) {
					$found = false;
					//Check if it exists in the currently crawled products
					foreach ($this->products as $crawledProduct) {
						if ($tempCode['product_code'] == $crawledProduct['code']) {
							$found = true;
							break;
						}
					}

					if (!$found) {
						$tempToUpdate[] = $tempCode;
					}
				}

				//if it both not exist in the currently crawled products

				foreach ($uploadedToUpdate as $productToUpdate) {
					//update quantity to 0
					$this->model_extension_module_uploaded_code->updateQuantity($productToUpdate['product_id'], 0);
				}

				foreach ($tempToUpdate as $productToUpdate) {
					//update quantity to 0
					$this->model_extension_module_crawled_product->updateQuantity($productToUpdate['id'], 0);
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
					if ($updated_prices == 1) {
						$updated_prices_message = 'Бяха намерени промени в цените на ' . $updated_prices . ' продукт';
					}
					else {
						$updated_prices_message = 'Бяха намерени промени в цените на ' . $updated_prices . ' продукта';
					}
					$_SESSION['updateProducts'] = $updated_prices_message;
				}

				$_SESSION['newProducts'] = $productsMessage;
				
				if ($countNewProducts > 0) {
					$_SESSION['uploaded'] = 'Продукти от ' . htmlspecialchars($_POST['site']) . ' бяха качени успешно';
				}
			}

			$this->response->redirect($this->url->link('extension/module/product_crawler', 'token=' . $this->session->data['token'], true));
		}

		public function showUpdates()
		{
			$this->setData();

			$this->data['updateAllLink'] = $this->url->link('extension/module/product_crawler/updateAll', 'token=' . $this->session->data['token'], true);

			$this->response->setOutput($this->load->view('extension/module/crawler_updates', $this->data));
		}

		public function updateAll()
		{	
			$this->setData();

			$this->load->model('extension/module/crawled_update');

			$updates = $this->model_extension_module_crawled_update->getAll();

			foreach ($updates as $update) {
				$this->model_extension_module_crawled_update->update($update);
				$this->model_extension_module_crawled_update->delete($update['update_id']);
			}

			if (count($updates) == 1) {
				$_SESSION['success'] = 'Беше качена 1 промяна';
			}
			else {
				$_SESSION['success'] = 'Бяха качени ' . count($updates) . ' промени';
			}

			$this->response->redirect($this->url->link('extension/module/product_crawler/showUpdates', 'token=' . $this->session->data['token'], true));
		}

		public function updatePercent()
		{
			if (isset($this->request->post) && $this->request->server['REQUEST_METHOD'] == 'POST') {
				$price_percent = $this->request->post['price-percent'];
				$store = $this->request->post['store'];

				$this->load->model('extension/module/crawled_product');

				$this->model_extension_module_crawled_product->updatePricePercent($store, $price_percent);

				$_SESSION['success'] = 'Процента за надценка на ' . $store . ' беше променен успешно';
			}

			$this->response->redirect($this->url->link('extension/module/product_crawler', 'token=' . $this->session->data['token'], true));
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
					$_SESSION['deleted'] = 'Изникна проблем с изтриването на продукта';
				}
			}

			$this->response->redirect($this->url->link('extension/module/product_crawler', 'token=' . $this->session->data['token'], true));
		}

		public function deleteFromStore()
		{
			$this->load->model('extension/module/crawled_product');

			if (isset($this->request->post) && $this->request->server['REQUEST_METHOD'] == 'POST') {
				$site = $this->request->post['site'];

				//Delete from site with model
				$this->model_extension_module_crawled_product->deleteFromStore($site);

				$_SESSION['success'] = 'Продуктите от ' . htmlentities($site) . ' бяха изтрити';
			}

			//redirect
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

			$this->response->redirect($this->url->link('extension/module/product_crawler/showUpdates', 'token=' . $this->session->data['token'], true));
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