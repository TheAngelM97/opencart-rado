<?php
class ModelShippingRapido extends Model {
	function getQuote($address) {
		$this->language->load('shipping/rapido');

		if (isset($address['validate'])) {
			$status = true;
		} else {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('rapido_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

			if (!$this->config->get('rapido_geo_zone_id')) {
				$status = true;
			} elseif ($query->num_rows) {
				$status = true;
			} else {
				$status = false;
			}
		}

		$method_data = array();

		if ($status) {
			$quote_data = array();

			$quote_data['rapido'] = array(
				'code'         => 'rapido.rapido',
				'title'        => $this->language->get('text_description'),
				'cost'         => 0.00,
				'tax_class_id' => 0,
				'text'         => $this->currency->format(0.00, $this->config->get('rapido_currency'))
			);

			$method_data = array(
				'code'       => 'rapido',
				'title'      => $this->language->get('text_title'),
				'quote'      => $quote_data,
				'sort_order' => $this->config->get('rapido_sort_order'),
				'error'      => false
			);

			if (isset($this->session->data['rapido'])) {
				require_once(DIR_SYSTEM . 'library/rapido.php');
				$rapido = new Rapido($this->registry);

				//$total = $this->cart->getTotal();

				$this->load->model('extension/extension');

				$total_data = array();
				$total = 0;
				$taxes = $this->cart->getTaxes();
				$sort_order = array();

				$results = $this->model_extension_extension->getExtensions('total');

				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
				}

				array_multisort($sort_order, SORT_ASC, $results);

				if (version_compare(VERSION, '2.3', '>')) {
					$totals = array(
						'totals' => array(),
						'taxes'  => &$taxes,
						'total'  => &$total
					);
					foreach ($results as $result) {
						if ($this->config->get($result['code'] . '_status')) {
							$this->load->model('extension/total/' . $result['code']);

							if ($result['code'] != 'shipping') {
								$this->{'model_extension_total_' . $result['code']}->getTotal($totals);
							}
						}
					}
				} elseif(version_compare(VERSION, '2.2', '>')) {
					$totals = array(
						'totals' => array(),
						'taxes'  => &$taxes,
						'total'  => &$total
					);
					foreach ($results as $result) {
						if ($this->config->get($result['code'] . '_status')) {
							$this->load->model('total/' . $result['code']);

							if ($result['code'] != 'shipping') {
								$this->{'model_total_' . $result['code']}->getTotal($totals);
							}
						}
					}
				} else {
					foreach ($results as $result) {
						if ($this->config->get($result['code'] . '_status')) {
							$this->load->model('total/' . $result['code']);

							if ($result['code'] != 'shipping') {
								$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
							}
						}
					}
				}

				$insurance_total = $this->cart->getTotal(); //$total;

				if (version_compare(VERSION, '2.2', '>')) {
					$total = $this->currency->format($totals['total'], $this->config->get('rapido_currency'), '', false);
				} else {
					$total = $this->currency->format($total, $this->config->get('rapido_currency'), '', false);
				}

				$weight = 0;
				$weight_volume = 0;

				foreach ($this->cart->getProducts() as $product) {
					//if ($product['shipping']) {
						$product_weight = (float)$product['weight'];

						if (!empty($product_weight)) {
							$weight += $this->weight->convert($product['weight'], $product['weight_class_id'], $this->config->get('rapido_weight_class_id'));
						} else {
							$weight += ($this->config->get('rapido_default_weight') * $product['quantity']);
						}

						$product_width = (float)$product['width'];
						$product_length = (float)$product['length'];
						$product_height = (float)$product['height'];

						if (!empty($product_width) && !empty($product_length) && !empty($product_height)) {
							$weight_volume += Rapido::getVolumeWeight($this->length->convert($product['width'], $product['length_class_id'], $this->config->get('rapido_length_class_id')),
								$this->length->convert($product['length'], $product['length_class_id'], $this->config->get('rapido_length_class_id')),
								$this->length->convert($product['height'], $product['length_class_id'], $this->config->get('rapido_length_class_id'))) * $product['quantity'];
						} else {
							$weight_volume += ($this->config->get('rapido_default_weight') * $product['quantity']);
						}
					//} else {
						//$insurance_total -= $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'];
					//}
				}

				$this->session->data['rapido']['total'] = $total;
				$this->session->data['rapido']['insurance_total'] = $this->currency->format($insurance_total, $this->config->get('rapido_currency'), '', false);
				$this->session->data['rapido']['weight'] = ($weight_volume > $weight ? $weight_volume : $weight); //$weight;
				$this->session->data['rapido']['count'] = 1; //$this->cart->countProducts();

				if (!$this->config->get('rapido_cod_status')) {
					$this->session->data['rapido']['cod'] = false;
				}

				$sender_city_id = (isset($this->session->data['rapido']['sender_city_id']) ? $this->session->data['rapido']['sender_city_id'] : $this->config->get('rapido_sender_city_id'));

				$free = false;
				$fixed_city = false;
				$fixed_intercity = false;
				if ((float)$this->config->get('rapido_free_total') && ($total >= (float)$this->config->get('rapido_free_total'))) {
					$free = true;
				} elseif ((float)$this->config->get('rapido_fixed_total_city') && $this->session->data['rapido']['city_id'] == $sender_city_id) {
					$fixed_city = true;
				} elseif ((float)$this->config->get('rapido_fixed_total_intercity') && $this->session->data['rapido']['city_id'] != $sender_city_id) {
					$fixed_intercity = true;
				}

				$methods = $rapido->calculate($this->session->data['rapido']);
				$methods_count = 0;

				if (!$rapido->getError()) {
					foreach ($methods as $method_id => $method) {
						if (!$method['PERROR']) {
							$method_title = $this->language->get('text_description') . ' - ' . $method['label'];

							if ($free) {
								$method_total = 0;
							} elseif ($this->config->get('rapido_payer') == Rapido::PAYER_SENDER) {
								$method_total = 0;
							} elseif ($fixed_city) {
								$method_total = (float)$this->config->get('rapido_fixed_total_city');
							} elseif ($fixed_intercity) {
								$method_total = (float)$this->config->get('rapido_fixed_total_intercity');
							} else {
								$method_total = $method['TOTAL'];

								if ((float)$method['fix_chas'] || (float)$method['nal_platej']) {
									$method_title .= ' (';

									if ((float)$method['fix_chas']) {
										$method_title .= $this->language->get('text_tax_fixed_time') . ' ' . $this->currency->format($this->currency->convert((float)$method['fix_chas'], $this->config->get('rapido_currency'), $this->config->get('config_currency')));
									}

									if ((float)$method['nal_platej']) {
										if (version_compare(VERSION, '2.2', '>')) {
											$method_title .= ((float)$method['fix_chas'] ? ', ' : '') . $this->language->get('text_tax_cod') . ' ' . $this->currency->format($this->currency->convert((float)$method['nal_platej'], $this->config->get('rapido_currency'), $this->config->get('config_currency')), $this->session->data['currency']);
										} else {
											$method_title .= ((float)$method['fix_chas'] ? ', ' : '') . $this->language->get('text_tax_cod') . ' ' . $this->currency->format($this->currency->convert((float)$method['nal_platej'], $this->config->get('rapido_currency'), $this->config->get('config_currency')));
										}
									}

									$method_title .= ')';
								}
							}

							$method_value = $this->currency->convert((float)$method_total, $this->config->get('rapido_currency'), $this->config->get('config_currency'));

							if (version_compare(VERSION, '2.2', '>')) {
								$method_total = $this->currency->format($method_total, $this->session->data['currency']);
							} else {
								$method_total = $this->currency->format($method_total);
							}

							$quote_data[$method_id] = array(
								'code'         => 'rapido.' . $method_id,
								'title'        => $method_title,
								'cost'         => $method_value,
								'tax_class_id' => 0,
								'text'         => $method_total
							);

							$methods_count++;
						}
					}

					if ($methods_count) {
						unset($quote_data['rapido']);
						$method_data['quote'] = $quote_data;

						$this->session->data['rapido']['service'] = $method['service'];
					}
				} else {
					$method_data['rapido_error'] = $rapido->getError();
				}
			} else {
				$method_data['rapido_error'] = $this->language->get('error_calculate');
			}
		}

		if (isset($method_data['rapido_error'])) {
			$method_data['quote']['rapido']['text'] = '';
		}

		return $method_data;
	}

	public function getCities($data) {
		$sql = "SELECT * FROM " . DB_PREFIX . "rapido_city c";

		$implode = array();

		if ($data['filter_name']) {
			$implode[] = "(LCASE(c.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%' OR LCASE(c.name) LIKE '%" . $this->db->escape(utf8_strtolower(Rapido::transliterate($data['filter_name']))) . "%')";
		}

		if ($data['filter_country_id']) {
			$implode[] = "c.countryid_iso = '" . (int)$data['filter_country_id'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " ORDER BY c.name";

		$sql .= " LIMIT " . (int)$data['limit'];

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getCityByNameAndPostcode($name, $postcode, $country_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "rapido_city c WHERE (LCASE(TRIM(c.name)) = '" . $this->db->escape(utf8_strtolower(trim($name))) . "' OR LCASE(TRIM(c.name)) = '" . $this->db->escape(utf8_strtolower(trim(Rapido::transliterate($name)))) . "') AND TRIM(c.postcode) = '" . $this->db->escape(trim($postcode)) . "' AND countryid_iso = '" . (int)$country_id . "'");

		return $query->row;
	}

	public function getStreets($data) {
		$sql = "SELECT * FROM " . DB_PREFIX . "rapido_street s";

		$implode = array();

		if ($data['filter_name']) {
			$implode[] = "(LCASE(s.streetname) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%' OR LCASE(s.streetname) LIKE '%" . $this->db->escape(utf8_strtolower(Rapido::transliterate($data['filter_name']))) . "%')";
		}

		if ($data['filter_city_id']) {
			$implode[] = "s.siteid = '" . (int)$data['filter_city_id'] . "'";
		}

		if ($data['filter_type_id']) {
			$implode[] = "s.streettype2 = '" . (int)$data['filter_type_id'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " ORDER BY s.streetname";

		$sql .= " LIMIT " . (int)$data['limit'];

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function addAddress($address_id, $data) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "rapido_address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "rapido_address SET address_id = '" . (int)$address_id . "', customer_id = '" . (int)$this->customer->getId() . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . $this->db->escape($data['country_id']) . "', city = '" . $this->db->escape($data['city']) . "', city_id = '" . (int)$data['city_id'] . "', region = '" . $this->db->escape($data['region']) . "', take_office = '" . (int)$data['take_office'] . "', office_id = '" . $this->db->escape($data['office_id']) . "', quarter = '" . $this->db->escape($data['quarter']) . "', quarter_id = '" . (int)$data['quarter_id'] . "', street = '" . $this->db->escape($data['street']) . "', street_id = '" . (int)$data['street_id'] . "', street_no = '" . $this->db->escape($data['street_no']) . "', block_no = '" . $this->db->escape($data['block_no']) . "', entrance_no = '" . $this->db->escape($data['entrance_no']) . "', floor_no = '" . $this->db->escape($data['floor_no']) . "', apartment_no = '" . $this->db->escape($data['apartment_no']) . "', additional_info = '" . $this->db->escape($data['additional_info']) . "'");
	}

	public function getAddress($address_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "rapido_address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

		return $query->row;
	}

	public function addOrder($order_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "rapido_order SET order_id = '" . (int)$order_id . "', data = '" . $this->db->escape(serialize($data)) . "'");
	}

	public function getOrder($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "rapido_order WHERE order_id = '" . (int)$order_id . "'");

		return $query->row;
	}
}
?>