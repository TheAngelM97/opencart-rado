<?php
class ControllerSaleRapido extends Controller {
	private $error = array();
	private $rapido;

	public function __construct($registry) {
		parent::__construct($registry);
		require_once(DIR_SYSTEM . 'library/rapido.php');
		$this->rapido = new Rapido($registry);
	}

	public function index() {
		$this->load->language('sale/rapido');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/rapido');

		$this->getList();
	}

	public function create() {
		$this->load->language('sale/rapido');

		$this->document->setTitle($this->language->get('heading_title_details'));

		$this->load->model('sale/rapido');

		$url = '';

		if (isset($this->request->get['filter_tovaritelnica'])) {
			$url .= '&filter_tovaritelnica=' . $this->request->get['filter_tovaritelnica'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_date_created'])) {
			$url .= '&filter_date_created=' . $this->request->get['filter_date_created'];
		}

		if (isset($this->request->get['filter_sendoffice'])) {
			$url .= '&filter_sendoffice=' . $this->request->get['filter_sendoffice'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['order_id'])) {
			$rapido_order_info = $this->model_sale_rapido->getOrder($this->request->get['order_id']);
		}

		if (!empty($rapido_order_info)) {
			$rapido_order_data = unserialize($rapido_order_info['data']);

			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($this->request->get['order_id']);

			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm() && !$this->request->post['calculate']) {
				if (isset($this->request->post['shipping_method'])) {
					$shipping_method_info = explode('.', $this->request->post['shipping_method']);

					$this->request->post['shipping_method_id'] = $shipping_method_info[1];
					$this->request->post['shipping_method_cost'] = $this->session->data['rapido_admin'][$shipping_method_info[1]]['shipping_method_cost'];
					$this->request->post['shipping_method_title'] = $this->session->data['rapido_admin'][$shipping_method_info[1]]['shipping_method_title'];
					$this->request->post['service'] = $this->session->data['rapido_admin'][$shipping_method_info[1]]['service'];
				}

				if ((float)$this->request->post['width'] > 0 && (float)$this->request->post['length'] > 0 && (float)$this->request->post['height'] > 0) {
					$weight_volume = Rapido::getVolumeWeight($this->request->post['width'], $this->request->post['length'], $this->request->post['height']);

					if ($weight_volume > $this->request->post['weight']) {
						$this->request->post['weight'] = $weight_volume;
					}
				}

				unset($rapido_order_data['fixed_time_cb']);
				$rapido_data = array_merge($rapido_order_data, $this->request->post);

				$result = $this->rapido->create_order($rapido_data, $order_info);

				if (!$this->rapido->getError() && !$result['ERROR']) {
					$this->model_sale_rapido->editOrder($this->request->get['order_id'], array('tovaritelnica' => $result['TOVARITELNICA']));

					$sender_city_id = (isset($rapido_data['sender_city_id']) ? $rapido_data['sender_city_id'] : $rapido_data('rapido_sender_city_id'));

					if ((float)$this->config->get('rapido_free_total') && ($rapido_data['total'] >= (float)$this->config->get('rapido_free_total'))) {
						$rapido_data['shipping_method_cost'] = 0;
					} elseif ((float)$this->config->get('rapido_fixed_total_city') && $rapido_data['city_id'] == $sender_city_id ) {
						$rapido_data['shipping_method_cost'] = (float)$this->config->get('rapido_fixed_total_city');
					} elseif ((float)$this->config->get('rapido_fixed_total_intercity') && $rapido_data['city_id'] != $sender_city_id ) {
						$rapido_data['shipping_method_cost'] = (float)$this->config->get('rapido_fixed_total_intercity');
					} else {
						$rapido_data['shipping_method_cost'] = $result['TOTAL'];
					}

					$comment = $this->model_sale_rapido->updateOrderInfo($this->request->get['order_id'], $rapido_data);

					$history_data = array(
						'order_status_id' => $this->config->get('rapido_order_status_id'),
						'notify' => true,
						'comment' => $comment
					);

					// API - Add Order History
					if (!isset($this->session->data['cookie'])) {
						$this->load->model('user/api');

						$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

						if ($api_info) {
							$curl = curl_init();

							// Set SSL if required
							if (substr(HTTPS_CATALOG, 0, 5) == 'https') {
								curl_setopt($curl, CURLOPT_PORT, 443);
							}

							curl_setopt($curl, CURLOPT_HEADER, false);
							curl_setopt($curl, CURLINFO_HEADER_OUT, true);
							curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
							curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
							curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
							curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG . 'index.php?route=api/login');
							curl_setopt($curl, CURLOPT_POST, true);
							curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($api_info));

							$json = curl_exec($curl);

							if (!$json) {
								$this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
							} else {
								$response = json_decode($json, true);

								if (isset($response['cookie'])) {
									$this->session->data['cookie'] = $response['cookie'];
								}

								curl_close($curl);
							}
						}
					}

					if (isset($this->session->data['cookie'])) {
						$curl = curl_init();

						// Set SSL if required
						if (substr(HTTPS_CATALOG, 0, 5) == 'https') {
							curl_setopt($curl, CURLOPT_PORT, 443);
						}

						curl_setopt($curl, CURLOPT_HEADER, false);
						curl_setopt($curl, CURLINFO_HEADER_OUT, true);
						curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
						curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
						curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
						curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG . 'index.php?route=api/order/history&order_id=' . $this->request->get['order_id']);
						curl_setopt($curl, CURLOPT_POST, true);
						curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($history_data));
						curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . $this->session->data['cookie'] . ';');


						$response = curl_exec($curl);
					}
					// End API

					$this->session->data['success'] = sprintf($this->language->get('text_success_create'), '<a href="' . $this->url->link('sale/rapido/print_pdf', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . $url, 'SSL') . '" target="_blank">' . $result['TOVARITELNICA'] . '</a>');

					$this->response->redirect($this->url->link('sale/rapido', 'token=' . $this->session->data['token'] . $url, 'SSL'));
				} else {
					if ($this->rapido->getError()) {
						$this->error['warning'] = $this->rapido->getError();
					} else {
						$this->error['warning'] = $result['ERROR'];
					}
				}
			}

			if (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
			} else {
				$data['error_warning'] = '';
			}

			if (isset($this->error['content'])) {
				$data['error_content'] = $this->error['content'];
			} else {
				$data['error_content'] = '';
			}

			if (isset($this->error['weight'])) {
				$data['error_weight'] = $this->error['weight'];
			} else {
				$data['error_weight'] = '';
			}

			if (isset($this->error['count'])) {
				$data['error_count'] = $this->error['count'];
			} else {
				$data['error_count'] = '';
			}

			if (isset($this->error['size'])) {
				$data['error_size'] = $this->error['size'];
			} else {
				$data['error_size'] = '';
			}

			if (isset($this->error['sender_city'])) {
				$data['error_sender_city'] = $this->error['sender_city'];
			} else {
				$data['error_sender_city'] = '';
			}

			if (isset($this->error['sendtime'])) {
				$data['error_sendtime'] = $this->error['sendtime'];
			} else {
				$data['error_sendtime'] = '';
			}

			if (isset($this->error['worktime'])) {
				$data['error_worktime'] = $this->error['worktime'];
			} else {
				$data['error_worktime'] = '';
			}

			if (isset($this->error['address'])) {
				$data['error_address'] = $this->error['address'];
			} else {
				$data['error_address'] = '';
			}

			if (isset($this->error['office'])) {
				$data['error_office'] = $this->error['office'];
			} else {
				$data['error_office'] = '';
			}

			if (isset($this->error['fixed_time'])) {
				$data['error_fixed_time'] = $this->error['fixed_time'];
			} else {
				$data['error_fixed_time'] = '';
			}

			$total = 0;
			$insurance_total = 0;
			$weight = 0;
			$weight_volume = 0;

			$order_products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);

			$this->load->model('catalog/product');

			foreach ($order_products as $order_product) {
				$product_price = $this->currency->convert($this->currency->format(($order_product['total'] + $order_product['tax'] * $order_product['quantity']), $order_info['currency_code'], $order_info['currency_value'], false), $order_info['currency_code'], $this->config->get('rapido_currency'));

				//$total += $product_price;
				$insurance_total += $product_price;

				$product_info = $this->model_catalog_product->getProduct($order_product['product_id']);

				if ($product_info) { // && $product_info['shipping']) {
					$product_weight = (float)$product_info['weight'];

					if (!empty($product_weight)) {
						$weight += $this->weight->convert(($product_info['weight'] * $order_product['quantity']), $product_info['weight_class_id'], $this->config->get('rapido_weight_class_id'));
					} else {
						$weight += ($this->config->get('rapido_default_weight') * $order_product['quantity']);
					}

					$product_width = (float)$product_info['width'];
					$product_length = (float)$product_info['length'];
					$product_height = (float)$product_info['height'];

					if (!empty($product_width) && !empty($product_length) && !empty($product_height)) {
						$weight_volume += Rapido::getVolumeWeight($this->length->convert($product_info['width'], $product_info['length_class_id'], $this->config->get('rapido_length_class_id')),
							$this->length->convert($product_info['length'], $product_info['length_class_id'], $this->config->get('rapido_length_class_id')),
							$this->length->convert($product_info['height'], $product_info['length_class_id'], $this->config->get('rapido_length_class_id'))) * $order_product['quantity'];
					} else {
						$weight_volume += ($this->config->get('rapido_default_weight') * $order_product['quantity']);
					}

					//$insurance_total += $product_price;
				}
			}

			$order_total = $this->model_sale_rapido->getOrderTotals($this->request->get['order_id']);

			if (!empty($order_total['shipping']) && !empty($order_total['total'])) {
				$total = $order_total['total']['value'] - $order_total['shipping']['value'];

				if ($total > 0) {
					$total = $this->currency->convert($this->currency->format($total, $order_info['currency_code'], $order_info['currency_value'], false), $order_info['currency_code'], $this->config->get('rapido_currency'));
				} else {
					$total = 0;
				}
			}

			if (isset($this->request->post['content'])) {
				$data['content'] = $this->request->post['content'];
			} elseif (isset($rapido_order_data['content'])) {
				$data['content'] = $rapido_order_data['content'];
			} else {
				$data['content'] = $this->language->get('text_content') . ' ' . $this->request->get['order_id'];
			}

			if (isset($this->request->post['weight'])) {
				$data['weight'] = $this->request->post['weight'];
			} elseif (isset($rapido_order_data['weight'])) {
				$data['weight'] = ($weight_volume > $weight ? $weight_volume : $weight); //$rapido_order_data['weight'];
			} else {
				$data['weight'] = '';
			}

			if (isset($this->request->post['count'])) {
				$data['count'] = $this->request->post['count'];
			} elseif (isset($rapido_order_data['count'])) {
				$data['count'] = $rapido_order_data['count'];
			} else {
				$data['count'] = 1;
			}

			if (isset($this->request->post['width'])) {
				$data['width'] = $this->request->post['width'];
			} elseif (isset($rapido_order_data['width'])) {
				$data['width'] = $rapido_order_data['width'];
			} else {
				$data['width'] = '';
			}

			if (isset($this->request->post['length'])) {
				$data['length'] = $this->request->post['length'];
			} elseif (isset($rapido_order_data['length'])) {
				$data['length'] = $rapido_order_data['length'];
			} else {
				$data['length'] = '';
			}

			if (isset($this->request->post['height'])) {
				$data['height'] = $this->request->post['height'];
			} elseif (isset($rapido_order_data['height'])) {
				$data['height'] = $rapido_order_data['height'];
			} else {
				$data['height'] = '';
			}

			if (isset($this->request->post['insurance'])) {
				$data['insurance'] = $this->request->post['insurance'];
			} elseif (isset($rapido_order_data['insurance'])) {
				$data['insurance'] = $rapido_order_data['insurance'];
			} else {
				$data['insurance'] = $this->config->get('rapido_insurance');
			}

			if (isset($this->request->post['insurance_total'])) {
				$data['insurance_total'] = $this->request->post['insurance_total'];
			} elseif (isset($rapido_order_data['insurance_total'])) {
				$data['insurance_total'] = round($insurance_total, 2); //$rapido_order_data['insurance_total'];
			} else {
				$data['insurance_total'] = '';
			}

			if (isset($this->request->post['fragile'])) {
				$data['fragile'] = $this->request->post['fragile'];
			} elseif (isset($rapido_order_data['fragile'])) {
				$data['fragile'] = $rapido_order_data['fragile'];
			} else {
				$data['fragile'] = $this->config->get('rapido_fragile');
			}

			if (isset($this->request->post['shipping_method'])) {
				$shipping_method = explode('.', $this->request->post['shipping_method']);
				$data['shipping_method_id'] = $shipping_method[1];
			} elseif (isset($rapido_order_data['shipping_method_id'])) {
				$data['shipping_method_id'] = $rapido_order_data['shipping_method_id'];
			} else {
				$data['shipping_method_id'] = '';
			}

			if (isset($this->request->post['cod'])) {
				$data['cod'] = $this->request->post['cod'];
			} elseif (isset($rapido_order_data['cod'])) {
				$data['cod'] = $rapido_order_data['cod'];
			} else {
				$data['cod'] = true;
			}

			if (isset($this->request->post['total'])) {
				$data['total'] = $this->request->post['total'];
			} elseif (isset($rapido_order_data['total'])) {
				$data['total'] = round($total, 2); //$rapido_order_data['total'];
			} else {
				$data['total'] = '';
			}

			$data['payers'] = array(
				Rapido::PAYER_SENDER   => $this->language->get('text_sender'),
				Rapido::PAYER_RECEIVER => $this->language->get('text_receiver')
			);

			if (isset($this->request->post['payer'])) {
				$data['rapido_payer'] = $this->request->post['payer'];
			} else {
				$data['rapido_payer'] = $this->config->get('rapido_payer');
			}

			if (isset($this->request->post['sender_office_id'])) {
				$data['sender_office_id'] = $this->request->post['sender_office_id'];
			} elseif (isset($rapido_order_data['sender_office_id'])) {
				$data['sender_office_id'] = $rapido_order_data['sender_office_id'];
			} else {
				$data['sender_office_id'] = $this->config->get('rapido_sender_office_id');
			}

			if (isset($this->request->post['sender_office'])) {
				$data['sender_office'] = $this->request->post['sender_office'];
			} elseif (isset($rapido_order_data['sender_office'])) {
				$data['sender_office'] = $rapido_order_data['sender_office'];
			} else {
				$data['sender_office'] = $this->config->get('rapido_sender_office');
			}

			if (isset($this->request->post['sender_office_default'])) {
				$data['sender_office_default'] = $this->request->post['sender_office_default'];
			} elseif (isset($rapido_order_data['sender_office_default'])) {
				$data['sender_office_default'] = $rapido_order_data['sender_office_default'];
			} else {
				$data['sender_office_default'] = $this->config->get('rapido_sender_office_default');
			}

			if (isset($this->request->post['sender_city'])) {
				$data['sender_city'] = $this->request->post['sender_city'];
			} elseif (isset($rapido_order_data['sender_city'])) {
				$data['sender_city'] = $rapido_order_data['sender_city'];
			} else {
				$data['sender_city'] = $this->config->get('rapido_sender_city');
			}

			if (isset($this->request->post['sender_city_id'])) {
				$data['sender_city_id'] = $this->request->post['sender_city_id'];
			} elseif (isset($rapido_order_data['sender_city_id'])) {
				$data['sender_city_id'] = $rapido_order_data['sender_city_id'];
			} else {
				$data['sender_city_id'] = $this->config->get('rapido_sender_city_id');
			}

			if (isset($this->request->post['sender_postcode'])) {
				$data['sender_postcode'] = $this->request->post['sender_postcode'];
			} elseif (isset($rapido_order_data['sender_postcode'])) {
				$data['sender_postcode'] = $rapido_order_data['sender_postcode'];
			} else {
				$data['sender_postcode'] = $this->config->get('rapido_sender_postcode');
			}

			if (isset($this->request->post['sendhour'])) {
				$data['sendhour'] = $this->request->post['sendhour'];
			} elseif (isset($rapido_order_data['sendhour'])) {
				$data['sendhour'] = $rapido_order_data['sendhour'];
			} else {
				$data['sendhour'] = date('H');
			}

			if (isset($this->request->post['sendmin'])) {
				$data['sendmin'] = $this->request->post['sendmin'];
			} elseif (isset($rapido_order_data['sendmin'])) {
				$data['sendmin'] = $rapido_order_data['sendmin'];
			} else {
				$data['sendmin'] = date('i');
			}

			if (isset($this->request->post['workhour'])) {
				$data['workhour'] = $this->request->post['workhour'];
			} elseif (isset($rapido_order_data['workhour'])) {
				$data['workhour'] = $rapido_order_data['workhour'];
			} else {
				$data['workhour'] = date('H');
			}

			if (isset($this->request->post['workmin'])) {
				$data['workmin'] = $this->request->post['workmin'];
			} elseif (isset($rapido_order_data['workmin'])) {
				$data['workmin'] = $rapido_order_data['workmin'];
			} else {
				$data['workmin'] = date('i');
			}

			if (isset($this->request->post['take_office'])) {
				$data['take_office'] = $this->request->post['take_office'];
			} elseif (isset($rapido_order_data['take_office'])) {
				$data['take_office'] = $rapido_order_data['take_office'];
			} else {
				$data['take_office'] = false;
			}

			if (isset($this->request->post['country_id'])) {
				$data['country_id'] = $this->request->post['country_id'];
			} elseif (isset($rapido_order_data['country_id'])) {
				$data['country_id'] = $rapido_order_data['country_id'];
			} else {
				$data['country_id'] = 0;
			}

			if (isset($this->request->post['city'])) {
				$data['city'] = $this->request->post['city'];
			} elseif (isset($rapido_order_data['city'])) {
				$data['city'] = $rapido_order_data['city'];
			} else {
				$data['city'] = '';
			}

			if (isset($this->request->post['region'])) {
				$data['region'] = $this->request->post['region'];
			} elseif (isset($rapido_order_data['region'])) {
				$data['region'] = $rapido_order_data['region'];
			} else {
				$data['region'] = '';
			}

			if (isset($this->request->post['postcode'])) {
				$data['postcode'] = $this->request->post['postcode'];
			} elseif (isset($rapido_order_data['postcode'])) {
				$data['postcode'] = $rapido_order_data['postcode'];
			} else {
				$data['postcode'] = '';
			}

			if (isset($this->request->post['city_id'])) {
				$data['city_id'] = $this->request->post['city_id'];
			} elseif (isset($rapido_order_data['city_id'])) {
				$data['city_id'] = $rapido_order_data['city_id'];
			} else {
				$data['city_id'] = 0;
			}

			if (isset($this->request->post['quarter'])) {
				$data['quarter'] = $this->request->post['quarter'];
			} elseif (isset($rapido_order_data['quarter'])) {
				$data['quarter'] = $rapido_order_data['quarter'];
			} else {
				$data['quarter'] = '';
			}

			if (isset($this->request->post['quarter_id'])) {
				$data['quarter_id'] = $this->request->post['quarter_id'];
			} elseif (isset($rapido_order_data['quarter_id'])) {
				$data['quarter_id'] = $rapido_order_data['quarter_id'];
			} else {
				$data['quarter_id'] = 0;
			}

			if (isset($this->request->post['street'])) {
				$data['street'] = $this->request->post['street'];
			} elseif (isset($rapido_order_data['street'])) {
				$data['street'] = $rapido_order_data['street'];
			} else {
				$data['street'] = '';
			}

			if (isset($this->request->post['street_id'])) {
				$data['street_id'] = $this->request->post['street_id'];
			} elseif (isset($rapido_order_data['street_id'])) {
				$data['street_id'] = $rapido_order_data['street_id'];
			} else {
				$data['street_id'] = 0;
			}

			if (isset($this->request->post['street_no'])) {
				$data['street_no'] = $this->request->post['street_no'];
			} elseif (isset($rapido_order_data['street_no'])) {
				$data['street_no'] = $rapido_order_data['street_no'];
			} else {
				$data['street_no'] = '';
			}

			if (isset($this->request->post['block_no'])) {
				$data['block_no'] = $this->request->post['block_no'];
			} elseif (isset($rapido_order_data['block_no'])) {
				$data['block_no'] = $rapido_order_data['block_no'];
			} else {
				$data['block_no'] = '';
			}

			if (isset($this->request->post['entrance_no'])) {
				$data['entrance_no'] = $this->request->post['entrance_no'];
			} elseif (isset($rapido_order_data['entrance_no'])) {
				$data['entrance_no'] = $rapido_order_data['entrance_no'];
			} else {
				$data['entrance_no'] = '';
			}

			if (isset($this->request->post['floor_no'])) {
				$data['floor_no'] = $this->request->post['floor_no'];
			} elseif (isset($rapido_order_data['floor_no'])) {
				$data['floor_no'] = $rapido_order_data['floor_no'];
			} else {
				$data['floor_no'] = '';
			}

			if (isset($this->request->post['apartment_no'])) {
				$data['apartment_no'] = $this->request->post['apartment_no'];
			} elseif (isset($rapido_order_data['apartment_no'])) {
				$data['apartment_no'] = $rapido_order_data['apartment_no'];
			} else {
				$data['apartment_no'] = '';
			}

			if (isset($this->request->post['office_id'])) {
				$data['office_id'] = $this->request->post['office_id'];
			} elseif (isset($rapido_order_data['office_id'])) {
				$data['office_id'] = $rapido_order_data['office_id'];
			} else {
				$data['office_id'] = 0;
			}

			if (isset($this->request->post['additional_info'])) {
				$data['additional_info'] = $this->request->post['additional_info'];
			} elseif (isset($rapido_order_data['additional_info'])) {
				$data['additional_info'] = $rapido_order_data['additional_info'];
			} else {
				$data['additional_info'] = '';
			}

			if (isset($this->request->post['check_before_pay'])) {
				$data['check_before_pay'] = $this->request->post['check_before_pay'];
			} elseif ($this->config->get('rapido_check_before_pay')) {
				$data['check_before_pay'] = $this->config->get('rapido_check_before_pay');
			} else {
				$data['check_before_pay'] = 0;
			}

			if (isset($this->request->post['test_before_pay'])) {
				$data['test_before_pay'] = $this->request->post['test_before_pay'];
			} elseif ($this->config->get('rapido_test_before_pay')) {
				$data['test_before_pay'] = $this->config->get('rapido_test_before_pay');
			} else {
				$data['test_before_pay'] = 0;
			}

			if (isset($this->request->post['suboten_raznos'])) {
				$data['suboten_raznos'] = $this->request->post['suboten_raznos'];
			} elseif (isset($rapido_order_data['suboten_raznos']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$data['suboten_raznos'] = $rapido_order_data['suboten_raznos'];
			} else {
				$data['suboten_raznos'] = 0;
			}

			if (isset($this->request->post['fixed_time_cb'])) {
				$data['fixed_time_cb'] = $this->request->post['fixed_time_cb'];
			} elseif (isset($rapido_order_data['fixed_time_cb']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
				$data['fixed_time_cb'] = $rapido_order_data['fixed_time_cb'];
			} else {
				$data['fixed_time_cb'] = false;
			}

			if (isset($this->request->post['fixed_time_type'])) {
				$data['fixed_time_type'] = $this->request->post['fixed_time_type'];
			} elseif (isset($rapido_order_data['fixed_time_type'])) {
				$data['fixed_time_type'] = $rapido_order_data['fixed_time_type'];
			} else {
				$data['fixed_time_type'] = '';
			}

			if (isset($this->request->post['fixed_time_hour'])) {
				$data['fixed_time_hour'] = $this->request->post['fixed_time_hour'];
			} elseif (isset($rapido_order_data['fixed_time_hour'])) {
				$data['fixed_time_hour'] = $rapido_order_data['fixed_time_hour'];
			} else {
				$data['fixed_time_hour'] = '';
			}

			if (isset($this->request->post['fixed_time_min'])) {
				$data['fixed_time_min'] = $this->request->post['fixed_time_min'];
			} elseif (isset($rapido_order_data['fixed_time_min'])) {
				$data['fixed_time_min'] = $rapido_order_data['fixed_time_min'];
			} else {
				$data['fixed_time_min'] = '';
			}

			if (isset($this->request->post['pazar'])) {
				$data['pazar'] = (float)$this->request->post['pazar'];
			} elseif (isset($rapido_order_data['pazar'])) {
				$data['pazar'] = (float)$rapido_order_data['pazar'];
			} else {
				$data['pazar'] = '';
			}

			if (isset($this->request->post['label_printer'])) {
				$data['label_printer'] = (int)$this->request->post['label_printer'];
			} else {
				$data['label_printer'] = (int)$this->config->get('rapido_label_printer');
			}

			$data['fixed_time_types'] = array(
				'before' => $this->language->get('text_before'),
				'in'     => $this->language->get('text_in'),
				'after'  => $this->language->get('text_after')
			);

			$data['my_objects'] = $this->rapido->getMyObjects();
			$data['countries'] = $this->rapido->getCountries();

			$data['offices'] = array();

			if ($data['city_id']) {
				$data['offices'] = $this->rapido->getOfficesCity($data['city_id']);

				if ($this->rapido->getError()) {
					$data['error_office'] = $this->rapido->getError();
				}
			}

			if ($this->config->get('rapido_enable_suboten_raznos') && date('l', time()) == 'Friday') {
				$data['enable_suboten_raznos'] = true;
			} else {
				$data['enable_suboten_raznos'] = false;
			}

			if ($this->config->get('rapido_fixed_time') && $data['city_id']) {
				$data['fixed_time'] = $this->rapido->checkCityFixChas($data['city_id']);
			} else {
				$data['fixed_time'] = $this->config->get('rapido_fixed_time');
			}

			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm() && $this->request->post['calculate']) {
				$data['quote'] = $this->getQuote();

				if (isset($data['quote']['rapido_error'])) {
					$data['error_warning'] = $data['quote']['rapido_error'];

					$data['quote'] = array();
				}
			} else {
				$data['quote'] = array();
			}

			$data['heading_title'] = $this->language->get('heading_title_details');

			$data['text_calculate'] = $this->language->get('text_calculate');
			$data['text_wait'] = $this->language->get('text_wait');
			$data['text_yes'] = $this->language->get('text_yes');
			$data['text_no'] = $this->language->get('text_no');
			$data['text_to_office'] = $this->language->get('text_to_office');
			$data['text_to_door'] = $this->language->get('text_to_door');
			$data['text_select_city'] = $this->language->get('text_select_city');

			$data['entry_content'] = $this->language->get('entry_content');
			$data['entry_weight'] = $this->language->get('entry_weight');
			$data['entry_count'] = $this->language->get('entry_count');
			$data['entry_size'] = $this->language->get('entry_size');
			$data['entry_insurance'] = $this->language->get('entry_insurance');
			$data['entry_insurance_total'] = $this->language->get('entry_insurance_total');
			$data['entry_fragile'] = $this->language->get('entry_fragile');
			$data['entry_shipping_method'] = $this->language->get('entry_shipping_method');
			$data['entry_cod'] = $this->language->get('entry_cod');
			$data['entry_cod_total'] = $this->language->get('entry_cod_total');
			$data['entry_payer'] = $this->language->get('entry_payer');
			$data['entry_sender_office'] = $this->language->get('entry_sender_office');
			$data['entry_sender_city'] = $this->language->get('entry_sender_city');
			$data['entry_sender_postcode'] = $this->language->get('entry_sender_postcode');
			$data['entry_sendtime'] = $this->language->get('entry_sendtime');
			$data['entry_worktime'] = $this->language->get('entry_worktime');
			$data['entry_shipping_to'] = $this->language->get('entry_shipping_to');
			$data['entry_country'] = $this->language->get('entry_country');
			$data['entry_city'] = $this->language->get('entry_city');
			$data['entry_region'] = $this->language->get('entry_region');
			$data['entry_postcode'] = $this->language->get('entry_postcode');
			$data['entry_quarter'] = $this->language->get('entry_quarter');
			$data['entry_street'] = $this->language->get('entry_street');
			$data['entry_street_no'] = $this->language->get('entry_street_no');
			$data['entry_block_no'] = $this->language->get('entry_block_no');
			$data['entry_entrance_no'] = $this->language->get('entry_entrance_no');
			$data['entry_floor_no'] = $this->language->get('entry_floor_no');
			$data['entry_apartment_no'] = $this->language->get('entry_apartment_no');
			$data['entry_office'] = $this->language->get('entry_office');
			$data['entry_additional_info'] = $this->language->get('entry_additional_info');
			$data['entry_fixed_time'] = $this->language->get('entry_fixed_time');
			$data['entry_check_before_pay'] = $this->language->get('entry_check_before_pay');
			$data['entry_test_before_pay'] = $this->language->get('entry_test_before_pay');
			$data['entry_suboten_raznos'] = $this->language->get('entry_suboten_raznos');
			$data['entry_pazar'] = $this->language->get('entry_pazar');
			$data['entry_label_printer'] = $this->language->get('entry_label_printer');

			$data['button_create'] = $this->language->get('button_create');
			$data['button_cancel'] = $this->language->get('button_cancel');
			$data['button_calculate'] = $this->language->get('button_calculate');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			);

			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title_details'),
				'href'      => $this->url->link('sale/rapido', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			);

			$data['action'] = $this->url->link('sale/rapido/create', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'] . $url, 'SSL');
			$data['cancel'] = $this->url->link('sale/rapido', 'token=' . $this->session->data['token'] . $url, 'SSL');

			$data['token'] = $this->session->data['token'];

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('sale/rapido_form.tpl', $data));
		} else {
			$this->notFound();
		}
	}

	public function getQuote() {
		$this->load->language('shipping/rapido');

		$quote_data = array();

		$quote_data['rapido'] = array(
			'code'         => 'rapido.rapido',
			'title'        => $this->language->get('text_description'),
			'cost'         => 0.00,
			'tax_class_id' => 0,
			'text'         => $this->currency->format(0.00, $this->config->get('rapido_currency'))
		);

		$method_data = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$sender_city_id = (isset($this->request->post['sender_city_id']) ? $this->request->post['sender_city_id'] : $this->config->get('rapido_sender_city_id'));

			$free = false;
			$fixed_city = false;
			$fixed_intercity = false;
			if ((float)$this->config->get('rapido_free_total') && ((float)$this->request->post['total'] >= (float)$this->config->get('rapido_free_total'))) {
				$free = true;
			} elseif ((float)$this->config->get('rapido_fixed_total_city') && $this->request->post['city_id'] == $sender_city_id) {
				$fixed_city = true;
			} elseif ((float)$this->config->get('rapido_fixed_total_intercity') && $this->request->post['city_id'] != $sender_city_id) {
				$fixed_intercity = true;
			}

			$methods = $this->rapido->calculate($this->request->post);
			$methods_count = 0;

			if (!$this->rapido->getError()) {
				foreach ($methods as $method_id => $method) {
					if (!$method['PERROR']) {
						if ($free) {
							$method_total = 0;
						} elseif ($this->request->post['payer'] == Rapido::PAYER_SENDER) {
							$method_total = 0;
						} elseif ($fixed_city) {
							$method_total = (float)$this->config->get('rapido_fixed_total_city');
							} elseif ($fixed_intercity) {
								$method_total = (float)$this->config->get('rapido_fixed_total_intercity');
							} else {
							$method_total = $method['TOTAL'];
						}

						$this->session->data['rapido_admin'][$method_id]['shipping_method_cost'] = $method_total;
						$this->session->data['rapido_admin'][$method_id]['shipping_method_title'] = $method['label'];
						$this->session->data['rapido_admin'][$method_id]['service'] = $method['service'];

						$quote_data[$method_id] = array(
							'code'         => 'rapido.' . $method_id,
							'title'        => $this->language->get('text_description') . ' - ' . $method['label'],
							'cost'         => $method_total,
							'tax_class_id' => 0,
							'text'         => $this->currency->format($method_total, $this->config->get('rapido_currency'), 1)
						);

						$methods_count++;
					}
				}

				if ($methods_count) {
					unset($quote_data['rapido']);
					$method_data['quote'] = $quote_data;
				}
			} else {
				$method_data['rapido_error'] = $this->rapido->getError();
			}
		} else {
			$method_data['rapido_error'] = $this->language->get('error_calculate');
		}

		if (isset($method_data['rapido_error'])) {
			$method_data['quote']['rapido']['text'] = '';
		}

		return $method_data;
	}

	public function track() {
		$this->load->language('sale/rapido');

		$this->document->setTitle($this->language->get('heading_title_track'));

		$this->load->model('sale/rapido');

		$url = '';

		if (isset($this->request->get['filter_tovaritelnica'])) {
			$url .= '&filter_tovaritelnica=' . $this->request->get['filter_tovaritelnica'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_date_created'])) {
			$url .= '&filter_date_created=' . $this->request->get['filter_date_created'];
		}

		if (isset($this->request->get['filter_sendoffice'])) {
			$url .= '&filter_sendoffice=' . $this->request->get['filter_sendoffice'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['order_id'])) {
			$rapido_order_info = $this->model_sale_rapido->getOrder($this->request->get['order_id']);
		}

		if (!empty($rapido_order_info) && !empty($rapido_order_info['tovaritelnica'])) {
			if (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
			} else {
				$data['error_warning'] = '';
			}

			$tracks = $this->rapido->track_order($rapido_order_info['tovaritelnica']);

			$data['tovaritelnica'] = $rapido_order_info['tovaritelnica'];
			$data['tracks'] = array();

			foreach ($tracks as $track) {
				$data['tracks'][] = array(
					'event_date'  => date($this->language->get('date_format_short') . ' ' . $this->language->get('time_format'), strtotime($track['DATA'])),
					'event'       => $track['STATUS'],
					'event_place' => $track['PLACE']
				);
			}

			$data['heading_title'] = $this->language->get('heading_title_track');

			$data['text_tovaritelnica'] = $this->language->get('text_tovaritelnica');
			$data['text_event_date'] = $this->language->get('text_event_date');
			$data['text_event'] = $this->language->get('text_event');
			$data['text_event_place'] = $this->language->get('text_event_place');
			$data['text_no_results'] = $this->language->get('text_no_results');

			$data['button_cancel'] = $this->language->get('button_cancel');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			);

			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title_track'),
				'href'      => $this->url->link('sale/rapido', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			);

			$data['cancel'] = $this->url->link('sale/rapido', 'token=' . $this->session->data['token'] . $url, 'SSL');

			$data['token'] = $this->session->data['token'];

			if ($this->rapido->getError()) {
				$data['error_warning'] = $this->rapido->getError();
			}

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('sale/rapido_track.tpl', $data));
		} else {
			$this->notFound();
		}
	}

	public function getRazhodOrders() {
		$json = array();

		$from_date = date('Y-m-d H:i:s', strtotime('-30 days'));
		$to_date = date('Y-m-d H:i:s');

		$orders = $this->rapido->getRazhodOrders($from_date, $to_date);

		if ($orders) {
			$json['orders'] = $orders;
		}

		if ($this->rapido->getError()) {
			$json['error'] = $this->rapido->getError();
		}

		echo json_encode($json);
		exit;
	}

	public function expense_orders() {
		$this->load->language('sale/rapido');

		$url = '';

		if (isset($this->request->get['filter_tovaritelnica'])) {
			$url .= '&filter_tovaritelnica=' . $this->request->get['filter_tovaritelnica'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_date_created'])) {
			$url .= '&filter_date_created=' . $this->request->get['filter_date_created'];
		}

		if (isset($this->request->get['filter_sendoffice'])) {
			$url .= '&filter_sendoffice=' . $this->request->get['filter_sendoffice'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['heading_title'] = $this->language->get('heading_title_expense_orders');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_select'] = $this->language->get('text_select');

		$data['column_invoiceid'] = $this->language->get('column_invoiceid');
		$data['column_created'] = $this->language->get('column_created');
		$data['column_paytype'] = $this->language->get('column_paytype');
		$data['column_tcount'] = $this->language->get('column_tcount');
		$data['column_tsum'] = $this->language->get('column_tsum');
		$data['column_dds'] = $this->language->get('column_dds');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_expense_order'] = $this->language->get('entry_expense_order');

		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['button_expense_order'] = $this->language->get('button_expense_order');
		$data['button_info_expense_order'] = $this->language->get('button_info_expense_order');
		$data['button_get_invoices'] = $this->language->get('button_get_invoices');
		$data['button_print'] = $this->language->get('button_print');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title_info'),
			'href'      => $this->url->link('sale/rapido', 'token=' . $this->session->data['token'] . $url, 'SSL'),
		);

		$data['cancel'] = $this->url->link('sale/rapido', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['expense_order'] = $this->url->link('sale/rapido/expenseOrder', 'token=' . $this->session->data['token'], 'SSL');

		$data['expense_orders'] = $this->url->link('sale/rapido/expense_orders', 'token=' . $this->session->data['token'], 'SSL');

		$data['token'] = $this->session->data['token'];

		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
		} elseif (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['invoices'] = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$from_date = date('Y-m-d H:i:s', strtotime('-12 months'));
			$to_date = date('Y-m-d H:i:s');

			$invoices = $this->rapido->getInvoices($from_date, $to_date);

			if (!$this->rapido->getError()) {
				foreach ($invoices as $invoice) {
					$data['invoices'][] = array(
						'invoiceid' => $invoice['invoiceid'],
						'created'   => $invoice['created'],
						'paytype'   => $invoice['paytype'] ? $this->language->get('text_pay_bank') : $this->language->get('text_pay_cash'),
						'tcount'    => $invoice['tcount'],
						'tsum'      => $invoice['tsum'],
						'dds'       => $invoice['dds'],
						'total'     => $invoice['total'],
					);
				}

				if (!$invoices) {
					$data['error_warning'] = $this->language->get('error_empty_invoices');
				}
			} else {
				$data['error_warning'] = $this->rapido->getError();
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/rapido_expense_orders.tpl', $data));
	}

	public function tovaritelnica_info() {
		$this->load->language('sale/rapido');

		$this->document->setTitle($this->language->get('heading_title_info'));

		$this->load->model('sale/rapido');

		$url = '';

		if (isset($this->request->get['filter_tovaritelnica'])) {
			$url .= '&filter_tovaritelnica=' . $this->request->get['filter_tovaritelnica'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_date_created'])) {
			$url .= '&filter_date_created=' . $this->request->get['filter_date_created'];
		}

		if (isset($this->request->get['filter_sendoffice'])) {
			$url .= '&filter_sendoffice=' . $this->request->get['filter_sendoffice'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['order_id'])) {
			$rapido_order_info = $this->model_sale_rapido->getOrder($this->request->get['order_id']);
		}

		if (!empty($rapido_order_info) && !empty($rapido_order_info['tovaritelnica'])) {
			if (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
			} else {
				$data['error_warning'] = '';
			}

			$tovaritelnica_info = $this->rapido->getTovarInfo($rapido_order_info['tovaritelnica']);

			$data['tovaritelnica'] = (isset($tovaritelnica_info['TOVARITELNICA']) ? $tovaritelnica_info['TOVARITELNICA'] : $rapido_order_info['tovaritelnica']);
			$data['date_created'] = (isset($tovaritelnica_info['CREATED']) ? date($this->language->get('date_format_short') . ' ' . $this->language->get('time_format'), strtotime($tovaritelnica_info['CREATED'])) : '');
			$data['client_ref'] = (isset($tovaritelnica_info['CLIENT_REF1']) ? $tovaritelnica_info['CLIENT_REF1'] : '');
			$data['cod'] = (isset($tovaritelnica_info['NP']) ? $tovaritelnica_info['NP'] : '');
			$data['weight'] = (isset($tovaritelnica_info['TEGLO']) ? $tovaritelnica_info['TEGLO'] : '');
			$data['tax_service'] = (isset($tovaritelnica_info['TAX_USLUGA']) ? $tovaritelnica_info['TAX_USLUGA'] : '');
			$data['tax_fixed_time'] = (isset($tovaritelnica_info['TAX_FIXCH']) ? $tovaritelnica_info['TAX_FIXCH'] : '');
			$data['tax_return_doc'] = (isset($tovaritelnica_info['TAX_RET_D']) ? $tovaritelnica_info['TAX_RET_D'] : '');
			$data['tax_return_receipt'] = (isset($tovaritelnica_info['TAX_RET_R']) ? $tovaritelnica_info['TAX_RET_R'] : '');
			$data['tax_cod'] = (isset($tovaritelnica_info['TAX_NP']) ? $tovaritelnica_info['TAX_NP'] : '');
			$data['tax_vat'] = (isset($tovaritelnica_info['TAX_DDS']) ? $tovaritelnica_info['TAX_DDS'] : '');
			$data['tax_insurance'] = (isset($tovaritelnica_info['TAX_INS']) ? $tovaritelnica_info['TAX_INS'] : '');
			$data['total'] = (isset($tovaritelnica_info['STOINOST']) ? $tovaritelnica_info['STOINOST'] : '');
			$data['pazar'] = (isset($tovaritelnica_info['PAZAR']) ? $tovaritelnica_info['PAZAR'] : '');

			$data['heading_title'] = $this->language->get('heading_title_info');

			$data['text_tovaritelnica'] = $this->language->get('text_tovaritelnica');
			$data['text_created'] = $this->language->get('text_created');
			$data['text_client_ref'] = $this->language->get('text_client_ref');
			$data['text_cod'] = $this->language->get('text_cod');
			$data['text_weight'] = $this->language->get('text_weight');
			$data['text_tax_service'] = $this->language->get('text_tax_service');
			$data['text_tax_fixed_time'] = $this->language->get('text_tax_fixed_time');
			$data['text_tax_return_doc'] = $this->language->get('text_tax_return_doc');
			$data['text_tax_return_receipt'] = $this->language->get('text_tax_return_receipt');
			$data['text_tax_cod'] = $this->language->get('text_tax_cod');
			$data['text_tax_vat'] = $this->language->get('text_tax_vat');
			$data['text_tax_insurance'] = $this->language->get('text_tax_insurance');
			$data['text_total'] = $this->language->get('text_total');

			$data['button_cancel'] = $this->language->get('button_cancel');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			);

			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title_info'),
				'href'      => $this->url->link('sale/rapido', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			);

			$data['cancel'] = $this->url->link('sale/rapido', 'token=' . $this->session->data['token'] . $url, 'SSL');

			$data['token'] = $this->session->data['token'];

			if ($this->rapido->getError()) {
				$data['error_warning'] = $this->rapido->getError();
			}

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('sale/rapido_tovaritelnica_info.tpl', $data));
		} else {
			$this->notFound();
		}
	}

	public function cod_info() {
		$this->load->language('sale/rapido');

		$this->document->setTitle($this->language->get('heading_title_cod_info'));

		$this->load->model('sale/rapido');

		$url = '';

		if (isset($this->request->get['filter_tovaritelnica'])) {
			$url .= '&filter_tovaritelnica=' . $this->request->get['filter_tovaritelnica'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_date_created'])) {
			$url .= '&filter_date_created=' . $this->request->get['filter_date_created'];
		}

		if (isset($this->request->get['filter_sendoffice'])) {
			$url .= '&filter_sendoffice=' . $this->request->get['filter_sendoffice'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['order_id'])) {
			$rapido_order_info = $this->model_sale_rapido->getOrder($this->request->get['order_id']);
		}

		if (!empty($rapido_order_info) && !empty($rapido_order_info['tovaritelnica'])) {
			if (isset($this->error['warning'])) {
				$data['error_warning'] = $this->error['warning'];
			} else {
				$data['error_warning'] = '';
			}

			$cod_info = $this->rapido->getNPInfo($rapido_order_info['tovaritelnica']);

			$data['tovaritelnica'] = (isset($cod_info['TOVARITELNICA']) ? $cod_info['TOVARITELNICA'] : $rapido_order_info['tovaritelnica']);
			$data['total_cod'] = (isset($cod_info['SUMA']) ? $cod_info['SUMA'] : '');
			$data['receiver_city'] = (isset($cod_info['RECEIVER [CITY]']) ? $cod_info['RECEIVER [CITY]'] : '');
			$data['paid_date'] = (isset($cod_info['IZPLATENODATA']) ? date($this->language->get('date_format_short') . ' ' . $this->language->get('time_format'), strtotime($cod_info['IZPLATENODATA'])) : '');
			$data['company_id'] = (isset($cod_info['COMPANY_ID']) ? $cod_info['COMPANY_ID'] : '');
			$data['expense_order'] = (isset($cod_info['RAZHODORDER']) ? $cod_info['RAZHODORDER'] : '');
			$data['client_ref'] = (isset($cod_info['CLIENT_REF1']) ? $cod_info['CLIENT_REF1'] : '');

			$data['heading_title'] = $this->language->get('heading_title_cod_info');

			$data['text_tovaritelnica'] = $this->language->get('text_tovaritelnica');
			$data['text_total_cod'] = $this->language->get('text_total_cod');
			$data['text_receiver_city'] = $this->language->get('text_receiver_city');
			$data['text_paid_date'] = $this->language->get('text_paid_date');
			$data['text_company_id'] = $this->language->get('text_company_id');
			$data['text_expense_order'] = $this->language->get('text_expense_order');
			$data['text_client_ref'] = $this->language->get('text_client_ref');

			$data['button_cancel'] = $this->language->get('button_cancel');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			);

			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title_cod_info'),
				'href'      => $this->url->link('sale/rapido', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			);

			$data['cancel'] = $this->url->link('sale/rapido', 'token=' . $this->session->data['token'] . $url, 'SSL');

			$data['token'] = $this->session->data['token'];

			if ($this->rapido->getError()) {
				$data['error_warning'] = $this->rapido->getError();
			}

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('sale/rapido_cod_info.tpl', $data));
		} else {
			$this->notFound();
		}
	}

	public function print_pdf() {
		$this->load->language('sale/rapido');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/rapido');

		$url = '';

		if (isset($this->request->get['filter_tovaritelnica'])) {
			$url .= '&filter_tovaritelnica=' . $this->request->get['filter_tovaritelnica'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_date_created'])) {
			$url .= '&filter_date_created=' . $this->request->get['filter_date_created'];
		}

		if (isset($this->request->get['filter_sendoffice'])) {
			$url .= '&filter_sendoffice=' . $this->request->get['filter_sendoffice'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['order_id'])) {
			$rapido_order_info = $this->model_sale_rapido->getOrder($this->request->get['order_id']);
		}

		if (!empty($rapido_order_info) && !empty($rapido_order_info['tovaritelnica'])) {
			$rapido_order_data = unserialize($rapido_order_info['data']);

			if (isset($rapido_order_data['label_printer'])) {
				$label_printer = $rapido_order_data['label_printer'];
			} else {
				$label_printer = (int)$this->config->get('rapido_label_printer');
			}

			$pdf = $this->rapido->print_pdf($rapido_order_info['tovaritelnica'], $label_printer);

			if (!$this->rapido->getError() && $pdf) {
				header('Content-Type: application/pdf');
				header('Content-Disposition: attachment; filename="' . $rapido_order_info['tovaritelnica'] . '.pdf"');
				echo $pdf;
				exit;
			} else {
				$this->session->data['warning'] = $this->rapido->getError();
			}
		} else {
			$this->session->data['warning'] = $this->language->get('error_exists');
		}

		$this->response->redirect($this->url->link('sale/rapido', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function courier() {
		$this->load->language('sale/rapido');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/rapido');

		$url = '';

		if (isset($this->request->get['filter_tovaritelnica'])) {
			$url .= '&filter_tovaritelnica=' . $this->request->get['filter_tovaritelnica'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_date_created'])) {
			$url .= '&filter_date_created=' . $this->request->get['filter_date_created'];
		}

		if (isset($this->request->get['filter_sendoffice'])) {
			$url .= '&filter_sendoffice=' . $this->request->get['filter_sendoffice'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$tovaritelnicas = array();
		$count = 0;
		$weight = 0;
		$sendoffice_id = 0;
		$sendoffice_same = true;

		if (isset($this->request->get['order_id']) || isset($this->request->post['selected'])) {
			if (isset($this->request->get['order_id'])) {
				$rapido_order_info = $this->model_sale_rapido->getOrder($this->request->get['order_id']);

				if ($rapido_order_info['tovaritelnica']) {
					$tovaritelnicas[] = $rapido_order_info['tovaritelnica'];

					$rapido_order_data = unserialize($rapido_order_info['data']);
					$count = $rapido_order_data['count'];
					$weight = $rapido_order_data['weight'];
					$sendoffice_id = $rapido_order_data['sender_office_id'];
				}
			} else {
				foreach ($this->request->post['selected'] as $order_id) {
					$rapido_order_info = $this->model_sale_rapido->getOrder($order_id);

					if ($rapido_order_info['tovaritelnica']) {
						$tovaritelnicas[] = $rapido_order_info['tovaritelnica'];

						$rapido_order_data = unserialize($rapido_order_info['data']);
						$count += $rapido_order_data['count'];
						$weight += $rapido_order_data['weight'];

						if ($sendoffice_id && $rapido_order_data['sender_office_id'] && $sendoffice_id != $rapido_order_data['sender_office_id']) {
							$sendoffice_same = false;
						} else {
							$sendoffice_id = $rapido_order_data['sender_office_id'];
						}
					}
				}
			}
		}

		if ($tovaritelnicas && $count && $weight && $sendoffice_same) {
			$result = $this->rapido->requestCurier($count, $weight, $sendoffice_id);

			if (!$this->rapido->getError() && $result['SUCCESS']) {
				foreach ($tovaritelnicas as $tovaritelnica) {
					$this->model_sale_rapido->editOrderCourier($tovaritelnica, true);
				}

				$this->session->data['success'] = $this->language->get('text_success_courier');
			} else {
				if ($this->rapido->getError()) {
					$this->session->data['warning'] = $this->rapido->getError();
				} else {
					$this->session->data['warning'] = $result['ERRORMSG'];
				}
			}
		} else {
			if (!$sendoffice_same) {
				$this->session->data['warning'] = $this->language->get('error_sendoffice');
			} else {
				$this->session->data['warning'] = $this->language->get('error_no_tovaritelnicas');
			}
		}

		$this->response->redirect($this->url->link('sale/rapido', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function courierRequested() {
		$this->load->language('sale/rapido');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/rapido');

		$url = '';

		if (isset($this->request->get['filter_tovaritelnica'])) {
			$url .= '&filter_tovaritelnica=' . $this->request->get['filter_tovaritelnica'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_date_created'])) {
			$url .= '&filter_date_created=' . $this->request->get['filter_date_created'];
		}

		if (isset($this->request->get['filter_sendoffice'])) {
			$url .= '&filter_sendoffice=' . $this->request->get['filter_sendoffice'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$tovaritelnicas = array();

		if (isset($this->request->get['order_id']) || isset($this->request->post['selected'])) {
			if (isset($this->request->get['order_id'])) {
				$rapido_order_info = $this->model_sale_rapido->getOrder($this->request->get['order_id']);

				if ($rapido_order_info['tovaritelnica']) {
					$tovaritelnicas[] = $rapido_order_info['tovaritelnica'];
				}
			} else {
				foreach ($this->request->post['selected'] as $order_id) {
					$rapido_order_info = $this->model_sale_rapido->getOrder($order_id);

					if ($rapido_order_info['tovaritelnica']) {
						$tovaritelnicas[] = $rapido_order_info['tovaritelnica'];
					}
				}
			}
		}

		if ($tovaritelnicas) {
			$this->load->model('sale/order');

			$history_data = array(
				'order_status_id' => $this->config->get('rapido_order_status_courier_id'),
				'notify' => true,
				'comment' => ''
			);

			$tovaritelnicas_updated = array();

			foreach ($tovaritelnicas as $tovaritelnica) {
				$result = $this->rapido->getTovarInfo($tovaritelnica);

				if (!$this->rapido->getError() && $result) {
					if (isset($result['STATUS']) && $result['STATUS'] == Rapido::STATUS_OFFICE) {
						$order_info = $this->model_sale_order->getOrder($result['CLIENT_REF1']);

						if ($order_info && ($order_info['order_status_id'] == $this->config->get('rapido_order_status_id'))) {

							// API - Add Order History
							if (!isset($this->session->data['cookie'])) {
								$this->load->model('user/api');

								$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

								if ($api_info) {
									$curl = curl_init();

									// Set SSL if required
									if (substr(HTTPS_CATALOG, 0, 5) == 'https') {
										curl_setopt($curl, CURLOPT_PORT, 443);
									}

									curl_setopt($curl, CURLOPT_HEADER, false);
									curl_setopt($curl, CURLINFO_HEADER_OUT, true);
									curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
									curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
									curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
									curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
									curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG . 'index.php?route=api/login');
									curl_setopt($curl, CURLOPT_POST, true);
									curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($api_info));

									$json = curl_exec($curl);

									if (!$json) {
										$this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
									} else {
										$response = json_decode($json, true);

										if (isset($response['cookie'])) {
											$this->session->data['cookie'] = $response['cookie'];
										}

										curl_close($curl);
									}
								}
							}

							if (isset($this->session->data['cookie'])) {
								$curl = curl_init();

								// Set SSL if required
								if (substr(HTTPS_CATALOG, 0, 5) == 'https') {
									curl_setopt($curl, CURLOPT_PORT, 443);
								}

								curl_setopt($curl, CURLOPT_HEADER, false);
								curl_setopt($curl, CURLINFO_HEADER_OUT, true);
								curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
								curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
								curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
								curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
								curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG . 'index.php?route=api/order/history&order_id=' . $result['CLIENT_REF1']);
								curl_setopt($curl, CURLOPT_POST, true);
								curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($history_data));
								curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . $this->session->data['cookie'] . ';');


								$response = curl_exec($curl);
							}
							// End API

							$tovaritelnicas_updated[] = $result['TOVARITELNICA'];
						}
					}
				} else {
					$this->session->data['warning'] = $this->rapido->getError();
				}
			}

			if ($tovaritelnicas_updated) {
				$this->session->data['success'] = sprintf($this->language->get('text_success_requested'), implode(', ', $tovaritelnicas_updated));
			} elseif (empty($this->session->data['warning'])) {
				$this->session->data['warning'] = $this->language->get('error_courier_requested');
			}
		} else {
			$this->session->data['warning'] = $this->language->get('error_no_tovaritelnicas');
		}

		$this->response->redirect($this->url->link('sale/rapido', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	public function expenseOrder() {
		$this->load->language('sale/rapido');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('sale/rapido');

		$url = '';

		if (isset($this->request->get['filter_tovaritelnica'])) {
			$url .= '&filter_tovaritelnica=' . $this->request->get['filter_tovaritelnica'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_date_created'])) {
			$url .= '&filter_date_created=' . $this->request->get['filter_date_created'];
		}

		if (isset($this->request->get['filter_sendoffice'])) {
			$url .= '&filter_sendoffice=' . $this->request->get['filter_sendoffice'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (!empty($this->request->post['expense_order'])) {
			$results = $this->rapido->getRazhodOrderInfo($this->request->post['expense_order']);

			if (!$this->rapido->getError()) {
				$this->load->model('sale/order');

				$history_data = array(
					'order_status_id' => $this->config->get('rapido_order_status_cod_id'),
					'notify' => true,
					'comment' => ''
				);

				$tovaritelnicas_updated = array();

				foreach ($results as $result) {
					$order_info = $this->model_sale_order->getOrder($result['CLIENT_REF1']);

					if ($order_info && ($order_info['order_status_id'] != $this->config->get('rapido_order_status_cod_id'))) {

						// API - Add Order History
						if (!isset($this->session->data['cookie'])) {
							$this->load->model('user/api');

							$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

							if ($api_info) {
								$curl = curl_init();

								// Set SSL if required
								if (substr(HTTPS_CATALOG, 0, 5) == 'https') {
									curl_setopt($curl, CURLOPT_PORT, 443);
								}

								curl_setopt($curl, CURLOPT_HEADER, false);
								curl_setopt($curl, CURLINFO_HEADER_OUT, true);
								curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
								curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
								curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
								curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
								curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG . 'index.php?route=api/login');
								curl_setopt($curl, CURLOPT_POST, true);
								curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($api_info));

								$json = curl_exec($curl);

								if (!$json) {
									$this->error['warning'] = sprintf($this->language->get('error_curl'), curl_error($curl), curl_errno($curl));
								} else {
									$response = json_decode($json, true);

									if (isset($response['cookie'])) {
										$this->session->data['cookie'] = $response['cookie'];
									}

									curl_close($curl);
								}
							}
						}

						if (isset($this->session->data['cookie'])) {
							$curl = curl_init();

							// Set SSL if required
							if (substr(HTTPS_CATALOG, 0, 5) == 'https') {
								curl_setopt($curl, CURLOPT_PORT, 443);
							}

							curl_setopt($curl, CURLOPT_HEADER, false);
							curl_setopt($curl, CURLINFO_HEADER_OUT, true);
							curl_setopt($curl, CURLOPT_USERAGENT, $this->request->server['HTTP_USER_AGENT']);
							curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
							curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
							curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($curl, CURLOPT_URL, HTTPS_CATALOG . 'index.php?route=api/order/history&order_id=' . $result['CLIENT_REF1']);
							curl_setopt($curl, CURLOPT_POST, true);
							curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($history_data));
							curl_setopt($curl, CURLOPT_COOKIE, session_name() . '=' . $this->session->data['cookie'] . ';');


							$response = curl_exec($curl);
						}
						// End API

						$tovaritelnicas_updated[] = $result['TOVARITELNICA'];
					}
				}

				if ($tovaritelnicas_updated) {
					$this->session->data['success'] = sprintf($this->language->get('text_success_expence'), implode(', ', $tovaritelnicas_updated));
				} else {
					$this->session->data['warning'] = $this->language->get('error_expense_order');
				}
			} else {
				$this->session->data['warning'] = $this->rapido->getError();
			}
		} else {
			$this->session->data['warning'] = $this->language->get('error_expense_number');
		}

		$this->response->redirect($this->url->link('sale/rapido/expense_orders', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	}

	protected function getList() {
		$this->document->addScript('view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		if (isset($this->request->get['filter_tovaritelnica'])) {
			$filter_tovaritelnica = $this->request->get['filter_tovaritelnica'];
		} else {
			$filter_tovaritelnica = null;
		}

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = null;
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = null;
		}

		if (isset($this->request->get['filter_date_created'])) {
			$filter_date_created = $this->request->get['filter_date_created'];
		} else {
			$filter_date_created = null;
		}

		if (isset($this->request->get['filter_sendoffice'])) {
			$filter_sendoffice = $this->request->get['filter_sendoffice'];
		} else {
			$filter_sendoffice = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'ro.tovaritelnica';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_tovaritelnica'])) {
			$url .= '&filter_tovaritelnica=' . $this->request->get['filter_tovaritelnica'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_date_created'])) {
			$url .= '&filter_date_created=' . $this->request->get['filter_date_created'];
		}

		if (isset($this->request->get['filter_sendoffice'])) {
			$url .= '&filter_sendoffice=' . $this->request->get['filter_sendoffice'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('sale/rapido', 'token=' . $this->session->data['token'] . $url, 'SSL'),
		);

		$data['courier'] = $this->url->link('sale/rapido/courier', 'token=' . $this->session->data['token'], 'SSL');
		$data['courier_requested'] = $this->url->link('sale/rapido/courierRequested', 'token=' . $this->session->data['token'], 'SSL');
		$data['expense_orders'] = $this->url->link('sale/rapido/expense_orders', 'token=' . $this->session->data['token'], 'SSL');

		$data['rapido_orders'] = array();

		$filter_data = array(
			'filter_tovaritelnica'   => $filter_tovaritelnica,
			'filter_order_id'        => $filter_order_id,
			'filter_order_status_id' => $filter_order_status_id,
			'filter_date_created'    => $filter_date_created,
			'filter_sendoffice'      => $filter_sendoffice,
			'sort'                   => $sort,
			'order'                  => $order,
			'start'                  => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                  => $this->config->get('config_limit_admin')
		);

		$rapido_order_total = $this->model_sale_rapido->getTotalOrders($filter_data);

		$results = $this->model_sale_rapido->getOrders($filter_data);

		foreach ($results as $result) {
			$action = array();

			if ($result['courier']) {
				$action[] = array(
					'text' => $this->language->get('text_courier_sent')
				);
			} else {
				$action[] = array(
					'text' => $this->language->get('text_request_courier'),
					'href' => $this->url->link('sale/rapido/courier', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL')
				);
			}

			$action[] = array(
				'text'   => $this->language->get('text_track'),
				'href' => $this->url->link('sale/rapido/track', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL')
			);

			$action[] = array(
				'text'   => $this->language->get('text_tovaritelnica_info'),
				'href' => $this->url->link('sale/rapido/tovaritelnica_info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL')
			);

			$action[] = array(
				'text'   => $this->language->get('text_cod_info'),
				'href' => $this->url->link('sale/rapido/cod_info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL')
			);

			$data['rapido_orders'][] = array(
				'tovaritelnica'        => $result['tovaritelnica'],
				'tovaritelnica_href'   => $this->url->link('sale/rapido/print_pdf', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
				'tovaritelnica_target' => '_blank',
				'order_id'             => $result['order_id'],
				'order_href'           => $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $result['order_id'] . $url, 'SSL'),
				'status'               => $result['status'],
				'date_created'         => date($this->language->get('date_format_short') . ' ' . $this->language->get('time_format'), strtotime($result['date_created'])),
				'sendoffice'           => $result['sendoffice'],
				'selected'             => isset($this->request->post['selected']) && in_array($result['order_id'], $this->request->post['selected']),
				'action'               => $action
			);
		}

		if (isset($this->session->data['warning'])) {
			$data['error_warning'] = $this->session->data['warning'];

			unset($this->session->data['warning']);
		} elseif (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_tovaritelnica'])) {
			$url .= '&filter_tovaritelnica=' . $this->request->get['filter_tovaritelnica'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_date_created'])) {
			$url .= '&filter_date_created=' . $this->request->get['filter_date_created'];
		}

		if (isset($this->request->get['filter_sendoffice'])) {
			$url .= '&filter_sendoffice=' . $this->request->get['filter_sendoffice'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_rapido_order'] = $this->url->link('sale/rapido', 'token=' . $this->session->data['token'] . '&sort=ro.tovaritelnica' . $url, 'SSL');
		$data['sort_order'] = $this->url->link('sale/rapido', 'token=' . $this->session->data['token'] . '&sort=ro.order_id' . $url, 'SSL');
		$data['sort_status'] = $this->url->link('sale/rapido', 'token=' . $this->session->data['token'] . '&sort=status' . $url, 'SSL');
		$data['sort_date_created'] = $this->url->link('sale/rapido', 'token=' . $this->session->data['token'] . '&sort=ro.date_created' . $url, 'SSL');
		$data['sort_sendoffice'] = $this->url->link('sale/rapido', 'token=' . $this->session->data['token'] . '&sort=ro.sendoffice' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_tovaritelnica'])) {
			$url .= '&filter_tovaritelnica=' . $this->request->get['filter_tovaritelnica'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}

		if (isset($this->request->get['filter_date_created'])) {
			$url .= '&filter_date_created=' . $this->request->get['filter_date_created'];
		}

		if (isset($this->request->get['filter_sendoffice'])) {
			$url .= '&filter_sendoffice=' . $this->request->get['filter_sendoffice'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $rapido_order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('sale/rapido', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($rapido_order_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($rapido_order_total - $this->config->get('config_limit_admin'))) ? $rapido_order_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $rapido_order_total, ceil($rapido_order_total / $this->config->get('config_limit_admin')));

		$data['filter_tovaritelnica'] = $filter_tovaritelnica;
		$data['filter_order_id'] = $filter_order_id;
		$data['filter_order_status_id'] = $filter_order_status_id;
		$data['filter_date_created'] = $filter_date_created;
		$data['filter_sendoffice'] = $filter_sendoffice;

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_missing'] = $this->language->get('text_missing');
		$data['text_list'] = $this->language->get('text_list');
		$data['text_select'] = $this->language->get('text_select');

		$data['column_tovaritelnica'] = $this->language->get('column_tovaritelnica');
		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_created'] = $this->language->get('column_date_created');
		$data['column_sendoffice'] = $this->language->get('column_sendoffice');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_expense_order'] = $this->language->get('entry_expense_order');
		$data['entry_courier'] = $this->language->get('entry_courier');

		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_courier'] = $this->language->get('button_courier');
		$data['button_courier_requested'] = $this->language->get('button_courier_requested');
		$data['button_expense_orders'] = $this->language->get('button_expense_orders');

		$data['token'] = $this->session->data['token'];

		if ($this->rapido->getError()) {
			$data['error_warning'] = $this->rapido->getError();
		}

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/rapido_list.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'sale/rapido')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['content']) < 1) || (utf8_strlen($this->request->post['content']) > 200)) {
			$this->error['content'] = $this->language->get('error_content');
		}

		if ((float)$this->request->post['weight'] <= 0) {
			$this->error['weight'] = $this->language->get('error_weight');
		}

		if ((int)$this->request->post['count'] <= 0) {
			$this->error['count'] = $this->language->get('error_count');
		}

		if (((float)$this->request->post['width'] > 0 && (float)$this->request->post['length'] > 0 && (float)$this->request->post['height'] > 0) ||
			((float)$this->request->post['width'] == 0 && (float)$this->request->post['length'] == 0 && (float)$this->request->post['height'] == 0)) {
		} else {
			$this->error['size'] = $this->language->get('error_size');
		}

		if (!$this->request->post['sender_city'] || !$this->request->post['sender_city_id']) {
			$this->error['sender_city'] = $this->language->get('error_sender_city');
		}

		if (!$this->request->post['sender_office_default'] && (($this->request->post['sendhour'] . $this->request->post['sendmin']) < date('Hi'))) {
			$this->error['sendtime'] = $this->language->get('error_sendtime');
		}

		if (!$this->request->post['sender_office_default'] && (($this->request->post['workhour'] . $this->request->post['workmin']) < ($this->request->post['sendhour'] . $this->request->post['sendmin']))) {
			$this->error['worktime'] = $this->language->get('error_worktime');
		}

		if ($this->request->post['country_id'] && $this->request->post['postcode'] && $this->request->post['city'] && $this->request->post['city_id'] &&
			(!$this->request->post['take_office'] && ($this->request->post['quarter'] && $this->request->post['quarter_id'] && $this->request->post['block_no'] ||
			$this->request->post['street'] && $this->request->post['street_id'] && $this->request->post['street_no'] ||
			$this->request->post['additional_info'] || $this->request->post['country_id'] != Rapido::BULGARIA) ||
			$this->request->post['take_office'] && $this->request->post['office_id'])) {
		} else {
			if ($this->request->post['take_office']) {
				$this->error['office'] = $this->language->get('error_office');
			} else {
				$this->error['address'] = $this->language->get('error_address');
			}
		}

		if (isset($this->request->post['fixed_time_cb'])) {
			if (!$this->request->post['fixed_time_hour'] || $this->request->post['fixed_time_hour'] < 10 || $this->request->post['fixed_time_hour'] > 17 ||
				!$this->request->post['fixed_time_min'] || ($this->request->post['fixed_time_min'] != '00' && $this->request->post['fixed_time_min'] != 15 && $this->request->post['fixed_time_min'] != 30 && $this->request->post['fixed_time_min'] != 45) ||
				($this->request->post['fixed_time_hour'] == 10 && $this->request->post['fixed_time_min'] < '00') ||
				($this->request->post['fixed_time_hour'] == 10 && $this->request->post['fixed_time_min'] < 30 && $this->request->post['fixed_time_type'] == 'before') ||
				($this->request->post['fixed_time_hour'] == 17 && $this->request->post['fixed_time_min'] > 45)) {
				$this->error['fixed_time'] = $this->language->get('error_fixed_time');
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'sale/rapido')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function notFound() {
		$this->load->language('error/not_found');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_not_found'] = $this->language->get('text_not_found');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('error/not_found', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('error/not_found.tpl', $data));
	}

	public function office() {
		if (isset($this->request->get['filter_city_id'])) {
			$filter_city_id = $this->request->get['filter_city_id'];
		} else {
			$filter_city_id = '';
		}

		if ($filter_city_id) {
			$json = $this->rapido->getOfficesCity($filter_city_id);

			if ($this->rapido->getError()) {
				$json = array('error' => $this->rapido->getError());
			}
		} else {
			$this->load->language('shipping/rapido');
			$json = array('error' => $this->language->get('error_city'));
		}

		$this->response->setOutput(json_encode($json));
	}

	public function city() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('shipping/rapido');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_country_id'])) {
				$filter_country_id = $this->request->get['filter_country_id'];
			} else {
				$filter_country_id = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 10;
			}

			$filter_data = array(
				'filter_name'       => $filter_name,
				'filter_country_id' => $filter_country_id,
				'limit'             => $limit
			);

			$results = $this->model_shipping_rapido->getCities($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'id'       => $result['siteid'],
					'label'    => ($result['sitetype'] ? $result['sitetype'] . ' ' : '') . $result['name'] . ' (' . $result['oblast'] . ') [' . $result['postcode'] . ']',
					'value'    => $result['name'],
					'region'   => $result['oblast'],
					'postcode' => $result['postcode']
				);
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function street() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('shipping/rapido');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_city_id'])) {
				$filter_city_id = $this->request->get['filter_city_id'];
			} else {
				$filter_city_id = '';
			}

			if (isset($this->request->get['filter_type'])) {
				$filter_type = $this->request->get['filter_type'];
			} else {
				$filter_type = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 10;
			}

			if ($filter_city_id) {
				$filter_data = array(
					'filter_name'    => $filter_name,
					'filter_city_id' => $filter_city_id,
					'filter_type_id' => ($filter_type == 'quarter' ? Rapido::QUARTER_TYPE : Rapido::STREET_TYPE),
					'limit'          => $limit
				);

				$results = $this->model_shipping_rapido->getStreets($filter_data);

				foreach ($results as $result) {
					$json[] = array(
						'id'    => $result['streetid'],
						'label' => $result['streettype'] . ' ' . $result['streetname'],
						'value' => $result['streetname']
					);
				}
			} else {
				$this->load->language('shipping/rapido');
				$json = array('error' => $this->language->get('error_city'));
			}
		}

		$this->response->setOutput(json_encode($json));
	}

	public function fixedTime() {
		if (isset($this->request->get['filter_city_id'])) {
			$filter_city_id = $this->request->get['filter_city_id'];
		} else {
			$filter_city_id = '';
		}

		if ($filter_city_id) {
			$result = $this->rapido->checkCityFixChas($filter_city_id);
			$json = array('result' => $result);

			if ($this->rapido->getError()) {
				$json = array('error' => $this->rapido->getError());
			}
		} else {
			$this->load->language('shipping/rapido');
			$json = array('error' => $this->language->get('error_city'));
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>