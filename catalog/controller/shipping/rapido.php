<?php
class ControllerShippingRapido extends Controller {
	private $error = array();
	private $rapido;

	public function __construct($registry) {
		parent::__construct($registry);
		require_once(DIR_SYSTEM . 'library/rapido.php');
		$this->rapido = new Rapido($registry);
	}

	public function index() {
		$this->load->language('shipping/rapido');
		$this->load->language('checkout/checkout');

		$this->load->model('shipping/rapido');

		$results = array();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if ($this->customer->isLogged() && isset($this->session->data['shipping_address']['address_id'])) {
				$this->model_shipping_rapido->addAddress($this->session->data['shipping_address']['address_id'], $this->request->post);
			}

			$this->session->data['rapido'] = $this->request->post;

			$results['submit'] = true;

			$this->response->setOutput(json_encode($results));
		}

		if ((!$this->cart->hasProducts() && (!isset($this->session->data['vouchers']) || !$this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$results['redirect'] = $this->url->link('checkout/cart');

			$this->response->setOutput(json_encode($results));
		}

		if (!$this->customer->isLogged() && !isset($this->session->data['guest'])) {
			$results['redirect'] = $this->url->link('checkout/checkout', '', 'SSL');

			$this->response->setOutput(json_encode($results));
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

		if ($this->customer->isLogged() && isset($this->session->data['shipping_address']['address_id'])) {
			$shipping_address = $this->model_shipping_rapido->getAddress($this->session->data['shipping_address']['address_id']);
		} elseif (isset($this->session->data['rapido'])) {
			$shipping_address = $this->session->data['rapido'];
		}

		if (!$this->config->get('rapido_cod_status')) {
			$data['cod'] = false;
		} elseif (isset($this->request->post['cod'])) {
			$data['cod'] = $this->request->post['cod'];
		} elseif (isset($this->session->data['rapido']['cod'])) {
			$data['cod'] = $this->session->data['rapido']['cod'];
		} else {
			$data['cod'] = true;
		}

		if (isset($this->request->post['take_office'])) {
			$data['take_office'] = $this->request->post['take_office'];
		} elseif (isset($shipping_address['take_office'])) {
			$data['take_office'] = $shipping_address['take_office'];
		} else {
			$data['take_office'] = false;
		}

		if (isset($this->request->post['country_id'])) {
			$data['country_id'] = $this->request->post['country_id'];
		} elseif (isset($shipping_address['country_id'])) {
			$data['country_id'] = $shipping_address['country_id'];
		} else {
			$data['country_id'] = 0;
		}

		if (isset($this->request->post['city'])) {
			$data['city'] = $this->request->post['city'];
		} elseif (isset($shipping_address['city'])) {
			$data['city'] = $shipping_address['city'];
		} else {
			$data['city'] = '';
		}

		if (isset($this->request->post['region'])) {
			$data['region'] = $this->request->post['region'];
		} elseif (isset($shipping_address['region'])) {
			$data['region'] = $shipping_address['region'];
		} else {
			$data['region'] = '';
		}

		if (isset($this->request->post['postcode'])) {
			$data['postcode'] = $this->request->post['postcode'];
		} elseif (isset($shipping_address['postcode'])) {
			$data['postcode'] = $shipping_address['postcode'];
		} else {
			$data['postcode'] = '';
		}

		if (isset($this->request->post['city_id'])) {
			$data['city_id'] = $this->request->post['city_id'];
		} elseif (isset($shipping_address['city_id'])) {
			$data['city_id'] = $shipping_address['city_id'];
		} else {
			$data['city_id'] = 0;
		}

		if (isset($this->request->post['quarter'])) {
			$data['quarter'] = $this->request->post['quarter'];
		} elseif (isset($shipping_address['quarter'])) {
			$data['quarter'] = $shipping_address['quarter'];
		} else {
			$data['quarter'] = '';
		}

		if (isset($this->request->post['quarter_id'])) {
			$data['quarter_id'] = $this->request->post['quarter_id'];
		} elseif (isset($shipping_address['quarter_id'])) {
			$data['quarter_id'] = $shipping_address['quarter_id'];
		} else {
			$data['quarter_id'] = 0;
		}

		if (isset($this->request->post['street'])) {
			$data['street'] = $this->request->post['street'];
		} elseif (isset($shipping_address['street'])) {
			$data['street'] = $shipping_address['street'];
		} else {
			$data['street'] = '';
		}

		if (isset($this->request->post['street_id'])) {
			$data['street_id'] = $this->request->post['street_id'];
		} elseif (isset($shipping_address['street_id'])) {
			$data['street_id'] = $shipping_address['street_id'];
		} else {
			$data['street_id'] = 0;
		}

		if (isset($this->request->post['street_no'])) {
			$data['street_no'] = $this->request->post['street_no'];
		} elseif (isset($shipping_address['street_no'])) {
			$data['street_no'] = $shipping_address['street_no'];
		} else {
			$data['street_no'] = '';
		}

		if (isset($this->request->post['block_no'])) {
			$data['block_no'] = $this->request->post['block_no'];
		} elseif (isset($shipping_address['block_no'])) {
			$data['block_no'] = $shipping_address['block_no'];
		} else {
			$data['block_no'] = '';
		}

		if (isset($this->request->post['entrance_no'])) {
			$data['entrance_no'] = $this->request->post['entrance_no'];
		} elseif (isset($shipping_address['entrance_no'])) {
			$data['entrance_no'] = $shipping_address['entrance_no'];
		} else {
			$data['entrance_no'] = '';
		}

		if (isset($this->request->post['floor_no'])) {
			$data['floor_no'] = $this->request->post['floor_no'];
		} elseif (isset($shipping_address['floor_no'])) {
			$data['floor_no'] = $shipping_address['floor_no'];
		} else {
			$data['floor_no'] = '';
		}

		if (isset($this->request->post['apartment_no'])) {
			$data['apartment_no'] = $this->request->post['apartment_no'];
		} elseif (isset($shipping_address['apartment_no'])) {
			$data['apartment_no'] = $shipping_address['apartment_no'];
		} else {
			$data['apartment_no'] = '';
		}

		if (isset($this->request->post['office_id'])) {
			$data['office_id'] = $this->request->post['office_id'];
		} elseif (isset($shipping_address['office_id'])) {
			$data['office_id'] = $shipping_address['office_id'];
		} else {
			$data['office_id'] = 0;
		}

		if (isset($this->request->post['additional_info'])) {
			$data['additional_info'] = $this->request->post['additional_info'];
		} elseif (isset($shipping_address['additional_info'])) {
			$data['additional_info'] = $shipping_address['additional_info'];
		} else {
			$data['additional_info'] = '';
		}

		if (isset($this->request->post['suboten_raznos'])) {
			$data['suboten_raznos'] = $this->request->post['suboten_raznos'];
		} elseif (isset($this->session->data['rapido']['suboten_raznos'])) {
			$data['suboten_raznos'] = $this->session->data['rapido']['suboten_raznos'];
		} else {
			$data['suboten_raznos'] = 0;
		}

		if (isset($this->request->post['fixed_time_cb'])) {
			$data['fixed_time_cb'] = $this->request->post['fixed_time_cb'];
		} elseif (isset($this->session->data['rapido']['fixed_time_cb'])) {
			$data['fixed_time_cb'] = $this->session->data['rapido']['fixed_time_cb'];
		} else {
			$data['fixed_time_cb'] = false;
		}

		if (isset($this->request->post['fixed_time_type'])) {
			$data['fixed_time_type'] = $this->request->post['fixed_time_type'];
		} elseif (isset($this->session->data['rapido']['fixed_time_type'])) {
			$data['fixed_time_type'] = $this->session->data['rapido']['fixed_time_type'];
		} else {
			$data['fixed_time_type'] = '';
		}

		if (isset($this->request->post['fixed_time_hour'])) {
			$data['fixed_time_hour'] = $this->request->post['fixed_time_hour'];
		} elseif (isset($this->session->data['rapido']['fixed_time_hour'])) {
			$data['fixed_time_hour'] = $this->session->data['rapido']['fixed_time_hour'];
		} else {
			$data['fixed_time_hour'] = '';
		}

		if (isset($this->request->post['fixed_time_min'])) {
			$data['fixed_time_min'] = $this->request->post['fixed_time_min'];
		} elseif (isset($this->session->data['rapido']['fixed_time_min'])) {
			$data['fixed_time_min'] = $this->session->data['rapido']['fixed_time_min'];
		} else {
			$data['fixed_time_min'] = '';
		}

		$data['fixed_time_types'] = array(
			'before' => $this->language->get('text_before'),
			'in'     => $this->language->get('text_in'),
			'after'  => $this->language->get('text_after')
		);

		$data['countries'] = $this->rapido->getCountries();

		$data['offices'] = array();

		if (!$data['city_id']) {
			if ($this->customer->isLogged() && isset($this->session->data['shipping_address']['address_id'])) {
				$this->load->model('account/address');

				$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address']['address_id']);
			} elseif (isset($this->session->data['guest'])) {
				$shipping_address = $this->session->data['shipping_address'];
			}

			if (!$data['country_id']) {
				$data['country_id'] = $this->rapido->getAvailableCountryById($shipping_address['country_id']);
			}

			$city = $this->model_shipping_rapido->getCityByNameAndPostcode($shipping_address['city'], $shipping_address['postcode'], $data['country_id']);

			if ($city) {
				$data['city'] = $city['name'];
				$data['city_id'] = $city['siteid'];
				$data['postcode'] = $city['postcode'];
				$data['region'] = $city['oblast'];
			}
		}

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

		$data['text_calculate'] = $this->language->get('text_calculate');
		$data['text_wait'] = $this->language->get('text_wait');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_to_office'] = $this->language->get('text_to_office');
		$data['text_to_door'] = $this->language->get('text_to_door');
		$data['text_select_city'] = $this->language->get('text_select_city');
		$data['text_select_office'] = $this->language->get('text_select_office');
		$data['text_modify'] = $this->language->get('text_modify');

		if (version_compare(VERSION, '2.2', '>')) {
			if ($this->cart->hasShipping()) {
				$data['text_checkout_payment_method'] = sprintf($this->language->get('text_checkout_payment_method'), 5);
				$data['text_checkout_confirm'] = sprintf($this->language->get('text_checkout_confirm'), 6);
			} else {
				$data['text_checkout_payment_method'] = sprintf($this->language->get('text_checkout_payment_method'), 3);
				$data['text_checkout_confirm'] = sprintf($this->language->get('text_checkout_confirm'), 4);
			}
		} else {
			$data['text_checkout_confirm'] = $this->language->get('text_checkout_confirm');
			$data['text_checkout_payment_method'] = $this->language->get('text_checkout_payment_method');
		}

		$data['entry_cod'] = $this->language->get('entry_cod');
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
		$data['entry_suboten_raznos'] = $this->language->get('entry_suboten_raznos');
		$data['entry_send_email'] = $this->language->get('entry_send_email');

		$data['button_calculate'] = $this->language->get('button_calculate');
		$data['button_calculate_loading'] = $this->language->get('button_calculate_loading');

		$data['action'] = $this->url->link('shipping/rapido', '', 'SSL');

		$data['cod_status'] = $this->config->get('rapido_cod_status');
		$data['send_email'] = $this->config->get('rapido_send_email');


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		if (version_compare(VERSION, '2.2', '>')) {
			$results['html'] = $this->load->view('shipping/rapido.tpl', $data);
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/shipping/rapido.tpl')) {
				$results['html'] = $this->load->view($this->config->get('config_template') . '/template/shipping/rapido.tpl', $data);
			} else {
				$results['html'] = $this->load->view('default/template/shipping/rapido.tpl', $data);
			}
		}

		$this->response->setOutput(json_encode($results));
	}

	protected function validate() {
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

		return !$this->error;
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
			$json = array('error' => $this->language->get('error_city'));
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>