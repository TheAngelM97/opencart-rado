<?php
class ControllerPaymentRapidoCod extends Controller {
	public function index() {
		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['continue'] = $this->url->link('checkout/success');

		if (version_compare(VERSION, '2.2', '>')) {
			return $this->load->view('payment/rapido_cod.tpl', $data);
		} else {
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/rapido_cod.tpl')) {
				return $this->load->view($this->config->get('config_template') . '/template/payment/rapido_cod.tpl', $data);
			} else {
				return $this->load->view('default/template/payment/rapido_cod.tpl', $data);
			}
		}
	}

	public function confirm() {
		if ($this->session->data['payment_method']['code'] == 'rapido_cod') {
			$this->load->model('checkout/order');

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('rapido_cod_order_status_id'));
		}
	}
}
?>