<?php
class ControllerPaymentRapidoCod extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/rapido_cod');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('rapido_cod', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			if (version_compare(VERSION, '2.3', '>')) {
				$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)); 
			} else {
				$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_edit'] = $this->language->get('text_edit');

		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/rapido_cod', 'token=' . $this->session->data['token'], 'SSL'),
		);

		$data['action'] = $this->url->link('payment/rapido_cod', 'token=' . $this->session->data['token'], 'SSL');

		if (version_compare(VERSION, '2.3', '>')) {
			$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true);
		} else {
			$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		}

		if (isset($this->request->post['rapido_cod_order_status_id'])) {
			$data['rapido_cod_order_status_id'] = $this->request->post['rapido_cod_order_status_id'];
		} else {
			$data['rapido_cod_order_status_id'] = $this->config->get('rapido_cod_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['rapido_cod_geo_zone_id'])) {
			$data['rapido_cod_geo_zone_id'] = $this->request->post['rapido_cod_geo_zone_id'];
		} else {
			$data['rapido_cod_geo_zone_id'] = $this->config->get('rapido_cod_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['rapido_cod_status'])) {
			$data['rapido_cod_status'] = $this->request->post['rapido_cod_status'];
		} else {
			$data['rapido_cod_status'] = $this->config->get('rapido_cod_status');
		}

		if (isset($this->request->post['rapido_cod_sort_order'])) {
			$data['rapido_cod_sort_order'] = $this->request->post['rapido_cod_sort_order'];
		} else {
			$data['rapido_cod_sort_order'] = $this->config->get('rapido_cod_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/rapido_cod.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/rapido_cod')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
?>