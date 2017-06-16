<?php
class ControllerShippingRapido extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('shipping/rapido');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		require_once(DIR_SYSTEM . 'library/rapido.php');
		$rapido = new Rapido($this->registry);

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('rapido', $this->request->post);

			$result = $rapido->setPrintSettings();

			if (!$rapido->getError() && $result) {
				$this->session->data['success'] = $this->language->get('text_success');
			} else {
				if ($rapido->getError()) {
					$this->session->data['error'] = $rapido->getError();
				} else {
					$this->session->data['error'] = $this->language->get('error_print_settings');
				}
			}

			if (version_compare(VERSION, '2.3', '>')) {
				$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true));
			} else {
				$this->response->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['username'])) {
			$data['error_username'] = $this->error['username'];
		} else {
			$data['error_username'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['sender_city'])) {
			$data['error_sender_city'] = $this->error['sender_city'];
		} else {
			$data['error_sender_city'] = '';
		}

		if (isset($this->error['subservices_city'])) {
			$data['error_subservices_city'] = $this->error['subservices_city'];
		} else {
			$data['error_subservices_city'] = '';
		}

		if (isset($this->error['subservices_intercity'])) {
			$data['error_subservices_intercity'] = $this->error['subservices_intercity'];
		} else {
			$data['error_subservices_intercity'] = '';
		}

		if (isset($this->error['default_weight'])) {
			$data['error_default_weight'] = $this->error['default_weight'];
		} else {
			$data['error_default_weight'] = '';
		}

		$data['info_electronic_invoice_type'] = '';
		if (!$this->config->get('rapido_electronic_invoice_type')) {
			$type = $rapido->getInvoiceType();

			if ($type == 1) {
				$rapido_settings = $this->model_setting_setting->getSetting('rapido');

				$rapido_settings['rapido_electronic_invoice_type'] = $type;
				$this->model_setting_setting->editSetting('rapido', $rapido_settings);
			} else {
				$data['info_electronic_invoice_type'] = $this->language->get('error_info_electronic_invoice_type');
			}
		}

		if (isset($this->request->post['rapido_test'])) {
			$data['rapido_test'] = $this->request->post['rapido_test'];
		} else {
			$data['rapido_test'] = $this->config->get('rapido_test');
		}

		if (isset($this->request->post['rapido_username'])) {
			$data['rapido_username'] = $this->request->post['rapido_username'];
		} else {
			$data['rapido_username'] = htmlspecialchars_decode($this->config->get('rapido_username'));
		}

		if (isset($this->request->post['rapido_password'])) {
			$data['rapido_password'] = $this->request->post['rapido_password'];
		} else {
			$data['rapido_password'] = htmlspecialchars_decode($this->config->get('rapido_password'));
		}

		if (isset($this->request->post['rapido_nomenclature_count'])) {
			$data['rapido_nomenclature_count'] = $this->request->post['rapido_nomenclature_count'];
		} elseif ($this->config->get('rapido_nomenclature_count')) {
			$data['rapido_nomenclature_count'] = $this->config->get('rapido_nomenclature_count');
		} else {
			$data['rapido_nomenclature_count'] = 3000;
		}

		if (isset($this->request->post['rapido_sender_office_id'])) {
			$data['rapido_sender_office_id'] = $this->request->post['rapido_sender_office_id'];
		} else {
			$data['rapido_sender_office_id'] = $this->config->get('rapido_sender_office_id');
		}

		if (isset($this->request->post['rapido_sender_office'])) {
			$data['rapido_sender_office'] = $this->request->post['rapido_sender_office'];
		} else {
			$data['rapido_sender_office'] = $this->config->get('rapido_sender_office');
		}

		if (isset($this->request->post['rapido_sender_office_default'])) {
			$data['rapido_sender_office_default'] = $this->request->post['rapido_sender_office_default'];
		} else {
			$data['rapido_sender_office_default'] = $this->config->get('rapido_sender_office_default');
		}

		if (isset($this->request->post['rapido_sender_city'])) {
			$data['rapido_sender_city'] = $this->request->post['rapido_sender_city'];
		} else {
			$data['rapido_sender_city'] = $this->config->get('rapido_sender_city');
		}

		if (isset($this->request->post['rapido_sender_city_id'])) {
			$data['rapido_sender_city_id'] = $this->request->post['rapido_sender_city_id'];
		} else {
			$data['rapido_sender_city_id'] = $this->config->get('rapido_sender_city_id');
		}

		if (isset($this->request->post['rapido_sender_postcode'])) {
			$data['rapido_sender_postcode'] = $this->request->post['rapido_sender_postcode'];
		} else {
			$data['rapido_sender_postcode'] = $this->config->get('rapido_sender_postcode');
		}

		if (isset($this->request->post['rapido_fixed_time'])) {
			$data['rapido_fixed_time'] = $this->request->post['rapido_fixed_time'];
		} else {
			$data['rapido_fixed_time'] = $this->config->get('rapido_fixed_time');
		}

		if (isset($this->request->post['rapido_return_receipt'])) {
			$data['rapido_return_receipt'] = $this->request->post['rapido_return_receipt'];
		} else {
			$data['rapido_return_receipt'] = $this->config->get('rapido_return_receipt');
		}

		if (isset($this->request->post['rapido_return_doc'])) {
			$data['rapido_return_doc'] = $this->request->post['rapido_return_doc'];
		} else {
			$data['rapido_return_doc'] = $this->config->get('rapido_return_doc');
		}

		if (isset($this->request->post['rapido_insurance'])) {
			$data['rapido_insurance'] = $this->request->post['rapido_insurance'];
		} else {
			$data['rapido_insurance'] = $this->config->get('rapido_insurance');
		}

		if (isset($this->request->post['rapido_fragile'])) {
			$data['rapido_fragile'] = $this->request->post['rapido_fragile'];
		} else {
			$data['rapido_fragile'] = $this->config->get('rapido_fragile');
		}

		if (isset($this->request->post['rapido_subservices_city'])) {
			$data['rapido_subservices_city'] = $this->request->post['rapido_subservices_city'];
		} elseif ($this->config->get('rapido_subservices_city') && $this->request->server['REQUEST_METHOD'] != 'POST') {
			$data['rapido_subservices_city'] = $this->config->get('rapido_subservices_city');
		} else {
			$data['rapido_subservices_city'] = array();
		}

		if (isset($this->request->post['rapido_subservices_intercity'])) {
			$data['rapido_subservices_intercity'] = $this->request->post['rapido_subservices_intercity'];
		} elseif ($this->config->get('rapido_subservices_intercity') && $this->request->server['REQUEST_METHOD'] != 'POST') {
			$data['rapido_subservices_intercity'] = $this->config->get('rapido_subservices_intercity');
		} else {
			$data['rapido_subservices_intercity'] = array();
		}

		if (isset($this->request->post['rapido_auto_print'])) {
			$data['rapido_auto_print'] = $this->request->post['rapido_auto_print'];
		} else {
			$data['rapido_auto_print'] = $this->config->get('rapido_auto_print');
		}

		if (isset($this->request->post['rapido_label_printer'])) {
			$data['rapido_label_printer'] = $this->request->post['rapido_label_printer'];
		} else {
			$data['rapido_label_printer'] = $this->config->get('rapido_label_printer');
		}

		if (isset($this->request->post['rapido_send_email'])) {
			$data['rapido_send_email'] = $this->request->post['rapido_send_email'];
		} elseif ($this->config->get('rapido_send_email') !== false) {
			$data['rapido_send_email'] = $this->config->get('rapido_send_email');
		} else {
			$data['rapido_send_email'] = 1;
		}

		if (isset($this->request->post['rapido_printer'])) {
			$data['rapido_printer'] = $this->request->post['rapido_printer'];
		} else {
			$data['rapido_printer'] = $this->config->get('rapido_printer');
		}

		if (isset($this->request->post['rapido_readiness'])) {
			$data['rapido_readiness'] = $this->request->post['rapido_readiness'];
		} else {
			$data['rapido_readiness'] = $this->config->get('rapido_readiness');
		}

		if (isset($this->request->post['rapido_default_weight'])) {
			$data['rapido_default_weight'] = $this->request->post['rapido_default_weight'];
		} else {
			$data['rapido_default_weight'] = $this->config->get('rapido_default_weight');
		}

		if (isset($this->request->post['rapido_payer'])) {
			$data['rapido_payer'] = $this->request->post['rapido_payer'];
		} elseif (!is_null($this->config->get('rapido_payer')) && $this->request->server['REQUEST_METHOD'] != 'POST') {
			$data['rapido_payer'] = $this->config->get('rapido_payer');
		} else {
			$data['rapido_payer'] = Rapido::PAYER_RECEIVER;
		}

		if (isset($this->request->post['rapido_price_list'])) {
			$data['rapido_price_list'] = $this->request->post['rapido_price_list'];
		} elseif (!is_null($this->config->get('rapido_price_list')) && $this->request->server['REQUEST_METHOD'] != 'POST') {
			$data['rapido_price_list'] = $this->config->get('rapido_price_list');
		} else {
			$data['rapido_price_list'] = Rapido::PRICE_LIST_SENDER;
		}

		if (isset($this->request->post['rapido_free_total'])) {
			$data['rapido_free_total'] = $this->request->post['rapido_free_total'];
		} else {
			$data['rapido_free_total'] = $this->config->get('rapido_free_total');
		}

		if (isset($this->request->post['rapido_fixed_total_city'])) {
			$data['rapido_fixed_total_city'] = $this->request->post['rapido_fixed_total_city'];
		} else {
			$data['rapido_fixed_total_city'] = $this->config->get('rapido_fixed_total_city');
		}

		if (isset($this->request->post['rapido_fixed_total_intercity'])) {
			$data['rapido_fixed_total_intercity'] = $this->request->post['rapido_fixed_total_intercity'];
		} else {
			$data['rapido_fixed_total_intercity'] = $this->config->get('rapido_fixed_total_intercity');
		}

		if (isset($this->request->post['rapido_enable_suboten_raznos'])) {
			$data['rapido_enable_suboten_raznos'] = $this->request->post['rapido_enable_suboten_raznos'];
		} else {
			$data['rapido_enable_suboten_raznos'] = $this->config->get('rapido_enable_suboten_raznos');
		}

		if (isset($this->request->post['rapido_check_before_pay'])) {
			$data['rapido_check_before_pay'] = $this->request->post['rapido_check_before_pay'];
		} else {
			$data['rapido_check_before_pay'] = $this->config->get('rapido_check_before_pay');
		}

		if (isset($this->request->post['rapido_test_before_pay'])) {
			$data['rapido_test_before_pay'] = $this->request->post['rapido_test_before_pay'];
		} else {
			$data['rapido_test_before_pay'] = $this->config->get('rapido_test_before_pay');
		}

		if (isset($this->request->post['rapido_money_transfer'])) {
			$data['rapido_money_transfer'] = $this->request->post['rapido_money_transfer'];
		} else {
			$data['rapido_money_transfer'] = $this->config->get('rapido_money_transfer');
		}

		if (isset($this->request->post['rapido_currency'])) {
			$data['rapido_currency'] = $this->request->post['rapido_currency'];
		} else {
			$data['rapido_currency'] = $this->config->get('rapido_currency');
		}

		if (isset($this->request->post['rapido_weight_class_id'])) {
			$data['rapido_weight_class_id'] = $this->request->post['rapido_weight_class_id'];
		} else {
			$data['rapido_weight_class_id'] = $this->config->get('rapido_weight_class_id');
		}

		if (isset($this->request->post['rapido_length_class_id'])) {
			$data['rapido_length_class_id'] = $this->request->post['rapido_length_class_id'];
		} else {
			$data['rapido_length_class_id'] = $this->config->get('rapido_length_class_id');
		}

		if (isset($this->request->post['rapido_order_status_id'])) {
			$data['rapido_order_status_id'] = $this->request->post['rapido_order_status_id'];
		} else {
			$data['rapido_order_status_id'] = $this->config->get('rapido_order_status_id');
		}

		if (isset($this->request->post['rapido_order_status_courier_id'])) {
			$data['rapido_order_status_courier_id'] = $this->request->post['rapido_order_status_courier_id'];
		} else {
			$data['rapido_order_status_courier_id'] = $this->config->get('rapido_order_status_courier_id');
		}

		if (isset($this->request->post['rapido_order_status_cod_id'])) {
			$data['rapido_order_status_cod_id'] = $this->request->post['rapido_order_status_cod_id'];
		} else {
			$data['rapido_order_status_cod_id'] = $this->config->get('rapido_order_status_cod_id');
		}

		if (isset($this->request->post['rapido_geo_zone_id'])) {
			$data['rapido_geo_zone_id'] = $this->request->post['rapido_geo_zone_id'];
		} else {
			$data['rapido_geo_zone_id'] = $this->config->get('rapido_geo_zone_id');
		}

		if (isset($this->request->post['rapido_status'])) {
			$data['rapido_status'] = $this->request->post['rapido_status'];
		} else {
			$data['rapido_status'] = $this->config->get('rapido_status');
		}

		if (isset($this->request->post['rapido_sort_order'])) {
			$data['rapido_sort_order'] = $this->request->post['rapido_sort_order'];
		} else {
			$data['rapido_sort_order'] = $this->config->get('rapido_sort_order');
		}

		$this->load->model('localisation/currency');

		$data['currencies'] = $this->model_localisation_currency->getCurrencies();

		$this->load->model('localisation/weight_class');

		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		$this->load->model('localisation/length_class');

		$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$data['payers'] = array(
			Rapido::PAYER_SENDER   => $this->language->get('text_sender'),
			Rapido::PAYER_RECEIVER => $this->language->get('text_receiver')
		);

		$data['price_lists'] = array(
			Rapido::PRICE_LIST_PAYER    => $this->language->get('text_payer'),
			Rapido::PRICE_LIST_SENDER   => $this->language->get('text_sender'),
			Rapido::PRICE_LIST_RECEIVER => $this->language->get('text_receiver')
		);

		$data['my_objects'] = $rapido->getMyObjects();
		$data['subservices_city'] = $rapido->getSubServices(Rapido::SERVICE_CITY);
		$data['subservices_intercity'] = $rapido->getSubServices(Rapido::SERVICE_INTERCITY);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_select_all'] = $this->language->get('text_select_all');
		$data['text_unselect_all'] = $this->language->get('text_unselect_all');
		$data['text_nomenclature'] = $this->language->get('text_nomenclature');
		$data['text_wait'] = $this->language->get('text_wait');
		$data['text_subservices'] = $this->language->get('text_subservices');
		$data['text_subservices_success'] = $this->language->get('text_subservices_success');

		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_nomenclature'] = $this->language->get('entry_nomenclature');
		$data['entry_nomenclature_count'] = $this->language->get('entry_nomenclature_count');
		$data['entry_sender_office'] = $this->language->get('entry_sender_office');
		$data['entry_sender_city'] = $this->language->get('entry_sender_city');
		$data['entry_sender_postcode'] = $this->language->get('entry_sender_postcode');
		$data['entry_fixed_time'] = $this->language->get('entry_fixed_time');
		$data['entry_return_receipt'] = $this->language->get('entry_return_receipt');
		$data['entry_return_doc'] = $this->language->get('entry_return_doc');
		$data['entry_insurance'] = $this->language->get('entry_insurance');
		$data['entry_fragile'] = $this->language->get('entry_fragile');
		$data['entry_subservices_city'] = $this->language->get('entry_subservices_city');
		$data['entry_subservices_intercity'] = $this->language->get('entry_subservices_intercity');
		$data['entry_auto_print'] = $this->language->get('entry_auto_print');
		$data['entry_label_printer'] = $this->language->get('entry_label_printer');
		$data['entry_send_email'] = $this->language->get('entry_send_email');
		$data['entry_printer'] = $this->language->get('entry_printer');
		$data['entry_readiness'] = $this->language->get('entry_readiness');
		$data['entry_default_weight'] = $this->language->get('entry_default_weight');
		$data['entry_payer'] = $this->language->get('entry_payer');
		$data['entry_price_list'] = $this->language->get('entry_price_list');
		$data['entry_free_total'] = $this->language->get('entry_free_total');
		$data['entry_fixed_total_city'] = $this->language->get('entry_fixed_total_city');
		$data['entry_fixed_total_intercity'] = $this->language->get('entry_fixed_total_intercity');
		$data['entry_enable_suboten_raznos'] = $this->language->get('entry_enable_suboten_raznos');
		$data['entry_check_before_pay'] = $this->language->get('entry_check_before_pay');
		$data['entry_test_before_pay'] = $this->language->get('entry_test_before_pay');
		$data['entry_money_transfer'] = $this->language->get('entry_money_transfer');
		$data['entry_currency'] = $this->language->get('entry_currency');
		$data['entry_weight_class'] = $this->language->get('entry_weight_class');
		$data['entry_length_class'] = $this->language->get('entry_length_class');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_order_status_courier'] = $this->language->get('entry_order_status_courier');
		$data['entry_order_status_cod'] = $this->language->get('entry_order_status_cod');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_subservices'] = $this->language->get('entry_subservices');

		$data['help_nomenclature'] = $this->language->get('help_nomenclature');
		$data['help_printer'] = $this->language->get('help_printer');
		$data['help_readiness'] = $this->language->get('help_readiness');
		$data['help_subservices'] = $this->language->get('help_subservices');
		$data['help_enable_suboten_raznos'] = $this->language->get('help_enable_suboten_raznos');
		$data['help_check_before_pay'] = $this->language->get('help_check_before_pay');
		$data['help_test_before_pay'] = $this->language->get('help_test_before_pay');
		$data['help_money_transfer'] = $this->language->get('help_money_transfer');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['error_general'] = $this->language->get('error_general');

		$data['token'] = $this->session->data['token'];

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_shipping'),
			'href'      => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('shipping/rapido', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['action'] = $this->url->link('shipping/rapido', 'token=' . $this->session->data['token'], 'SSL');

		if (version_compare(VERSION, '2.3', '>')) {
			$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=shipping', true);
		} else {
			$data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');
		}

		if ($rapido->getError()) {
			$this->error['warning'] = $rapido->getError();
		}


		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('shipping/rapido.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'shipping/rapido')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['rapido_username']) {
			$this->error['username'] = $this->language->get('error_username');
		}

		if (!$this->request->post['rapido_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if (!$this->request->post['rapido_sender_city'] || !$this->request->post['rapido_sender_city_id']) {
			$this->error['sender_city'] = $this->language->get('error_sender_city');
		}

		if (!isset($this->request->post['rapido_subservices_city'])) {
			$this->error['subservices_city'] = $this->language->get('error_subservices_city');
		}

		if (!isset($this->request->post['rapido_subservices_intercity'])) {
			$this->error['subservices_intercity'] = $this->language->get('error_subservices_intercity');
		}

		if (!$this->request->post['rapido_default_weight']) {
			$this->error['default_weight'] = $this->language->get('error_default_weight');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	public function install() {
		$this->load->model('setting/setting');

		$shipping_data = array(
			'shipping_estimator'  => 0,
			'shipping_status'     => 1,
			'shipping_sort_order' => $this->config->get('shipping_sort_order')
		);

		$this->model_setting_setting->editSetting('shipping', $shipping_data);

		$cod_data = array(
			'cod_status' => 0
		);

		$this->model_setting_setting->editSetting('cod', $cod_data);

		$this->load->model('shipping/rapido');

		$this->model_shipping_rapido->createTables();

		@mail('support@extensadev.com', 'Rapido Shipping Module installed (OpenCart ' . VERSION . ')', HTTP_CATALOG . ' - ' . $this->config->get('config_name') . "\r\n" . 'version - ' . VERSION . "\r\n" . 'IP - ' . $this->request->server['REMOTE_ADDR'], 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/plain; charset=UTF-8' . "\r\n" . 'From: ' . $this->config->get('config_owner') . ' <' . $this->config->get('config_email') . '>' . "\r\n");
	}

	public function uninstall() {
		$this->load->model('shipping/rapido');

		$this->model_shipping_rapido->deleteTables();
	}

	public function nomenclature() {
		@ini_set('memory_limit', '512M');
		@ini_set('max_execution_time', 3600);

		$this->load->language('shipping/rapido');

		$this->load->model('shipping/rapido');

		if (!empty($this->request->post['stage'])) {
			$stage = $this->request->post['stage'];
		} else {
			$stage = 'countries';
		}

		if (!empty($this->request->post['offset'])) {
			$offset = $this->request->post['offset'];
		} else {
			$offset = 0;
		}

		if (!empty($this->request->post['count'])) {
			$count = $this->request->post['count'];
		} else {
			$count = 1000;
		}

		if (!empty($this->request->post['countryid_iso'])) {
			$countryid_iso = (int)$this->request->post['countryid_iso'];
		} else {
			$countryid_iso = '';
		}

		$json = array();

		require_once(DIR_SYSTEM . 'library/rapido.php');
		$rapido = new Rapido($this->registry);

		$current_country_index = '';
		$rapido_countries = $rapido->getCountries();
		if (!empty($rapido_countries)) {
			foreach ($rapido_countries as $index => $country) {
				if (empty($countryid_iso)) {
					$countryid_iso = (int)trim($country['COUNTRYID_ISO']);
					$current_country_index = $index;
					break;
				} elseif ((int)trim($country['COUNTRYID_ISO']) == $countryid_iso) {
					$current_country_index = $index;
					break;
				}
			}
		}

		$results = array();

		if ($stage == 'countries') {
			$results = $rapido->getCountries($offset, $count);
		} elseif ($stage == 'cities' && $countryid_iso) {
			$results = $rapido->getCityes($countryid_iso, $offset, $count);
		} elseif ($stage == 'streets') {
			$results = $rapido->getStreets(0, $offset, $count);
		}

		$results_count = count($results);

		if ($results_count) {
			if ($offset == 0) {
				if ($stage == 'countries') {
					$this->model_shipping_rapido->deleteCountries();
				} elseif ($stage == 'cities' && is_int($current_country_index) && !$current_country_index) {
					$this->model_shipping_rapido->deleteCities();
				} elseif ($stage == 'streets') {
					$this->model_shipping_rapido->deleteStreets();
				}
			}

			foreach ($results as $result) {
				if ($stage == 'countries') {
					$this->model_shipping_rapido->addCountry($result);
				} elseif ($stage == 'cities') {
					if ((int)trim($result['COUNTRYID_ISO']) == $countryid_iso) {
						$this->model_shipping_rapido->addCity($result);
					}
				} elseif ($stage == 'streets') {
					$this->model_shipping_rapido->addStreet($result);
				}
			}
		}

		if ($stage == 'countries') {
			$json['info'] = sprintf($this->language->get('text_nomenclature_countries'), $offset, $offset + $results_count);
		} elseif ($stage == 'cities') {
			$json['info'] = sprintf($this->language->get('text_nomenclature_cities'), $offset, $offset + $results_count) . (isset($rapido_countries[$current_country_index]) ? ' - ' . $rapido_countries[$current_country_index]['COUNTRYNAME'] : '');
		} elseif ($stage == 'streets') {
			$json['info'] = sprintf($this->language->get('text_nomenclature_streets'), $offset, $offset + $results_count);
		}

		if ($results_count < $count) {
			if ($stage == 'countries') {
				$json['stage'] = 'cities';
			} elseif ($stage == 'cities') {
				if (isset($rapido_countries[$current_country_index+1])) {
					$json['stage'] = 'cities';
					$countryid_iso = $rapido_countries[$current_country_index+1]['COUNTRYID_ISO'];
				} else {
					$json['stage'] = 'streets';
				}
			} elseif ($stage == 'streets') {
				$json['info'] = $this->language->get('text_nomenclature_success');
			}

			$json['offset'] = 0;
		} else {
			$json['stage'] = $stage;
			$json['offset'] = $offset + $count;
		}

		$json['countryid_iso'] = $countryid_iso;

		if ($rapido->getError()) {
			$json['error'] = $rapido->getError();
		}

		$this->response->setOutput(json_encode($json));
	}

	public function getSubservices() {
		$json = array();

		require_once(DIR_SYSTEM . 'library/rapido.php');
		$rapido = new Rapido($this->registry);

		$json['my_objects'] = $rapido->getMyObjects();
		$json['subservices_city'] = $rapido->getSubServices(Rapido::SERVICE_CITY);
		$json['subservices_intercity'] = $rapido->getSubServices(Rapido::SERVICE_INTERCITY);

		if ($rapido->getError()) {
			$json['error'] = $rapido->getError();
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>