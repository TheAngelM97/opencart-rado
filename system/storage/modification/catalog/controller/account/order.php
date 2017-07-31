<?php

				use Dompdf\Dompdf;
				use Dompdf\Options;
				require_once DIR_SYSTEM.'library/dompdf/autoload.inc.php';
			
class ControllerAccountOrder extends Controller {

public function return_loading() {
	if (isset($this->request->get['order_id'])) {
		$order_id = $this->request->get['order_id'];
	} else {
		$order_id = 0;
	}

	if (!$this->customer->isLogged()) {
		$this->session->data['redirect'] = $this->url->link('account/order/return_loading', 'order_id=' . $order_id, 'SSL');

		$this->response->redirect($this->url->link('account/login', '', 'SSL'));
	}

	$this->language->load('account/order');

	$this->load->model('account/econt');

	$loading_info = $this->model_account_econt->getLoading($order_id);

	if ($loading_info && (strtotime($loading_info['receiver_time']) > 0)) {
		$data = array();
		$data['system']['validate'] = 0;
		$data['system']['response_type'] = 'XML';
		$data['system']['only_calculate'] = 0;

		$data['client']['username'] = $this->config->get('econt_username');
		$data['client']['password'] = $this->config->get('econt_password');
		$data['client_software'] = 'ExtensaOpenCart2x';
		$data['loadings']['row']['returned_loading']['first_loading_num'] = $loading_info['loading_num'];
		$data['loadings']['row']['returned_loading']['first_loading_receiver_phone'] = $loading_info['receiver_person_phone'];

		$results = $this->parcelImport($data);

		if ($results) {
			if (!empty($results->result->e->error)) {
				$this->session->data['error'] = (string)$results->result->e->error;
			} elseif (isset($results->pdf) && !empty($results->pdf->blank_yes)) {
				$this->model_account_econt->updateLoadingReturn($loading_info['econt_loading_id'], array('is_returned' => TRUE, 'blank_yes' => $results->pdf->blank_yes));

				$this->response->redirect(trim($results->pdf->blank_yes));
			} else {
				$this->model_account_econt->updateLoadingReturn($loading_info['econt_loading_id'], array('is_returned' => TRUE, 'blank_yes' => ''));

				$this->session->data['success'] = $this->language->get('text_success_return_loading');
			}
		} else {
			$this->session->data['error'] = $this->language->get('error_connect');
		}
	}

	$this->response->redirect($this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL'));
}

private function prepareXML($data) {
	$xml = '';

	foreach ($data as $key => $value) {
		if ($key && $key == 'error') {
			continue;
		}

		if ($key && ($key == 'p' || $key == 'cd')) {
			$xml .= '<' . $key . ' type="' . htmlspecialchars($value['type']) . '">' . htmlspecialchars($value['value']) . '</' . $key . '>' . "\r\n";
		} else {
			if (!is_numeric($key) && $key != 'to_door' && $key != 'to_office' && $key != 'to_aps') {
				$xml .= '<' . $key . '>';
			}

			if (is_array($value)) {
				$xml .= "\r\n" . $this->prepareXML($value);
			} else {
				$xml .= htmlspecialchars($value);
			}

			if (!is_numeric($key) && $key != 'to_door' && $key != 'to_office' && $key != 'to_aps') {
				$xml .= '</' . $key . '>' . "\r\n";
			}
		}
	}

	return $xml;
}

private function serviceTool($data) {
	if (!$this->config->get('econt_test')) {
		$url = 'http://www.econt.com/e-econt/xml_service_tool.php';
	} else {
		$url = 'http://demo.econt.com/e-econt/xml_service_tool.php';
	}

	$request = '<?xml version="1.0" ?>
				<request>
					<client>
						<username>' . $this->config->get('econt_username') . '</username>
						<password>' . $this->config->get('econt_password') . '</password>
					</client>
					<client_software>ExtensaOpenCart2x</client_software>
					<request_type>' . $data['type'] . '</request_type>
					<mediator>extensa</mediator>';

	if (isset($data['xml'])) {
		$request .= $data['xml'];
	}

	$request .= '</request>';

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, array('xml' => $request));

	$response = curl_exec($ch);

	curl_close($ch);

	libxml_use_internal_errors(TRUE);
	return simplexml_load_string($response);
}

private function parcelImport($data) {
	if (!$this->config->get('econt_test')) {
		$url = 'http://www.econt.com/e-econt/xml_parcel_import2.php';
	} else {
		$url = 'http://demo.econt.com/e-econt/xml_parcel_import2.php';
	}

	$data['loadings']['row']['mediator'] = 'extensa';

	$request = '<?xml version="1.0" ?>';
	$request .= '<parcels>';
	$request .= $this->prepareXML($data);
	$request .= '</parcels>';

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, array('xml' => $request));

	$response = curl_exec($ch);

	curl_close($ch);

	libxml_use_internal_errors(TRUE);
	return simplexml_load_string($response);
}
			
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/order', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/order');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/order', $url, true)
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_empty'] = $this->language->get('text_empty');

		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_product'] = $this->language->get('column_product');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');

		$data['button_view'] = $this->language->get('button_view');
		$data['button_continue'] = $this->language->get('button_continue');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['orders'] = array();

		$this->load->model('account/order');

		$order_total = $this->model_account_order->getTotalOrders();


				$data['invoice_manager_download_invoice_customer_status'] = $this->config->get('invoice_manager_download_invoice_customer_status');
				if($this->config->get('invoice_manager_complete_status')){
					$data['invoice_manager_complete_status'] = $this->config->get('invoice_manager_complete_status');
				}else{
					$data['invoice_manager_complete_status'] = array();
				}
			
		$results = $this->model_account_order->getOrders(($page - 1) * 10, 10);

		foreach ($results as $result) {
			$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
			$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

			$data['orders'][] = array(
				'order_id'   => $result['order_id'],
'order_status_id' => $result['order_status_id'],
			'download'   => $this->url->link('account/order/pdfinvoice', '&type=pdf&order_id=' . $result['order_id'], 'SSL'),
				'name'       => $result['firstname'] . ' ' . $result['lastname'],
				'status'     => $result['status'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'products'   => ($product_total + $voucher_total),
				'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'view'       => $this->url->link('account/order/info', 'order_id=' . $result['order_id'], true),
			);
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('account/order', 'page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($order_total - 10)) ? $order_total : ((($page - 1) * 10) + 10), $order_total, ceil($order_total / 10));

		$data['continue'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/order_list', $data));
	}


				public function pdfinvoice() {
		$this->load->language('account/order');

		$data['title'] = $this->language->get('text_invoice');

		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}
		
		if ($this->request->server['HTTPS']){
			$server = HTTPS_SERVER;
		} else {
			$server = HTTP_SERVER;
		}

		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');

		$data['text_invoice'] = $this->language->get('text_invoice');
		$data['text_order_detail'] = $this->language->get('text_order_detail');
		$data['text_order_id'] = $this->language->get('text_order_id');
		$data['text_invoice_no'] = $this->language->get('text_invoice_no');
		$data['text_invoice_date'] = $this->language->get('text_invoice_date');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['text_telephone'] = $this->language->get('text_telephone');
		$data['text_fax'] = $this->language->get('text_fax');
		$data['text_email'] = $this->language->get('text_email');
		$data['text_website'] = $this->language->get('text_website');
		$data['text_payment_address'] = $this->language->get('text_payment_address');
		$data['text_shipping_address'] = $this->language->get('text_shipping_address');
		$data['text_payment_method'] = $this->language->get('text_payment_method');
		$data['text_shipping_method'] = $this->language->get('text_shipping_method');
		$data['text_comment'] = $this->language->get('text_comment');

		$data['column_product'] = $this->language->get('column_product');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_total'] = $this->language->get('column_total');

		$this->load->model('account/order');

		$this->load->model('setting/setting');
		
		$this->load->model('tool/image');
		
		$this->load->model('tool/upload');
		
		$this->load->model('catalog/product');
				
		$data['orders'] = array();

		$orders = array();

		if (isset($this->request->post['selected'])) {
			$orders = $this->request->post['selected'];
		} elseif (isset($this->request->get['order_id'])) {
			$orders[] = $this->request->get['order_id'];
		}elseif(isset($this->request->post['bulkmail'])){
		  $orders = explode(',',$this->request->post['bulkmail']);
		}
		
		
		if(isset($this->request->get['mail_status'])){
			$mail_status = $this->request->get['mail_status'];
		}else{
			$mail_status = false;
		}
		
		if(!empty($this->request->get['type'])){
		 $format_type = 'pdf';
		}else{
		 $format_type = 'html';
		}

		foreach ($orders as $order_id){
			
			$order_info = $this->model_account_order->getOrder($order_id);
			
			
			if ($order_info){
				$store_info = $this->model_setting_setting->getSetting('config', $order_info['store_id']);

				if ($store_info){
					$store_address = $store_info['config_address'];
					$store_email = $store_info['config_email'];
					$store_telephone = $store_info['config_telephone'];
					$store_fax = $store_info['config_fax'];
				} else {
					$store_address = $this->config->get('config_address');
					$store_email = $this->config->get('config_email');
					$store_telephone = $this->config->get('config_telephone');
					$store_fax = $this->config->get('config_fax');
				}
				
				if ($order_info['invoice_no']) {
					$invoice_no = $order_info['invoice_prefix'] . $order_info['invoice_no'];
				} else {
					$invoice_no = '';
				}
				
				$invoice_info = $this->model_setting_setting->getSetting('invoice_manager', $order_info['store_id']);
				
				if (!empty($invoice_info['invoice_manager_payment_address_format'])) {
					$format = $invoice_info['invoice_manager_payment_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $order_info['payment_firstname'],
					'lastname'  => $order_info['payment_lastname'],
					'company'   => $order_info['payment_company'],
					'address_1' => $order_info['payment_address_1'],
					'address_2' => $order_info['payment_address_2'],
					'city'      => $order_info['payment_city'],
					'postcode'  => $order_info['payment_postcode'],
					'zone'      => $order_info['payment_zone'],
					'zone_code' => $order_info['payment_zone_code'],
					'country'   => $order_info['payment_country']
				);

				$payment_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

				if (!empty($invoice_info['invoice_manager_shipping_address_format'])) {
					$format = $invoice_info['invoice_manager_shipping_address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = array(
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				);

				$replace = array(
					'firstname' => $order_info['shipping_firstname'],
					'lastname'  => $order_info['shipping_lastname'],
					'company'   => $order_info['shipping_company'],
					'address_1' => $order_info['shipping_address_1'],
					'address_2' => $order_info['shipping_address_2'],
					'city'      => $order_info['shipping_city'],
					'postcode'  => $order_info['shipping_postcode'],
					'zone'      => $order_info['shipping_zone'],
					'zone_code' => $order_info['shipping_zone_code'],
					'country'   => $order_info['shipping_country']
				);

				$shipping_address = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));
				
				
				//Hide/Show option
				if(!empty($invoice_info['invoice_manager_invoice_heading_status'])){
					$invoice_manager_invoice_heading_status  = $invoice_info['invoice_manager_invoice_heading_status'];
				}else{
					$invoice_manager_invoice_heading_status = false;
				}
				
				if(!empty($invoice_info['invoice_manager_orderdetails_status'])){
					$invoice_manager_orderdetails_status  = $invoice_info['invoice_manager_orderdetails_status'];
				}else{
					$invoice_manager_orderdetails_status = false;
				}
				
				$tdwidtharray=array();
				if(!empty($invoice_info['invoice_manager_shipping_address_status'])){
					$invoice_manager_shipping_address_status  = $invoice_info['invoice_manager_shipping_address_status'];
					$tdwidtharray[]=true;
				}else{
					$invoice_manager_shipping_address_status = false;
				}
				
				if(!empty($invoice_info['invoice_manager_payment_address_status'])){
					$invoice_manager_payment_address_status  = $invoice_info['invoice_manager_payment_address_status'];
					$tdwidtharray[]=true;
				}else{
					$invoice_manager_payment_address_status = false;
				}
				
				if(count($tdwidtharray > 1)){
					$tdwidth=50;
				}else{
					$tdwidth=100;
				}
				
				if(!empty($invoice_info['invoice_manager_product_image_status'])){
					$invoice_manager_product_image_status  = $invoice_info['invoice_manager_product_image_status'];
				}else{
					$invoice_manager_product_image_status = false;
				}
				
				if(!empty($invoice_info['invoice_manager_product_name_status'])){
					$invoice_manager_product_name_status  = $invoice_info['invoice_manager_product_name_status'];
				}else{
					$invoice_manager_product_name_status = false;
				}
				
				if(!empty($invoice_info['invoice_manager_product_model_status'])){
					$invoice_manager_product_model_status  = $invoice_info['invoice_manager_product_model_status'];
				}else{
					$invoice_manager_product_model_status = false;
				}
				
				if(!empty($invoice_info['invoice_manager_product_sku_status'])){
					$invoice_manager_product_sku_status  = $invoice_info['invoice_manager_product_sku_status'];
				}else{
					$invoice_manager_product_sku_status = false;
				}
				
				if(!empty($invoice_info['invoice_manager_product_qty_status'])){
					$invoice_manager_product_qty_status  = $invoice_info['invoice_manager_product_qty_status'];
				}else{
					$invoice_manager_product_qty_status = false;
				}
				
				if(!empty($invoice_info['invoice_manager_product_unit_price_status'])){
					$invoice_manager_product_unit_price_status  = $invoice_info['invoice_manager_product_unit_price_status'];
				}else{
					$invoice_manager_product_unit_price_status = false;
				}
				
				if(!empty($invoice_info['invoice_manager_product_total_status'])){
					$invoice_manager_product_total_status  = $invoice_info['invoice_manager_product_total_status'];
				}else{
					$invoice_manager_product_total_status = false;
				}
				
				if(!empty($invoice_info['invoice_manager_logo'])){
					$invoice_manager_logo  = str_replace(' ', '%20', $invoice_info['invoice_manager_logo']);
				}else{
					$invoice_manager_logo = false;
				}
				
				if(!empty($invoice_info['invoice_manager_pdf_stream'])){
					$invoice_manager_pdf_stream  = $invoice_info['invoice_manager_pdf_stream'];
				}else{
					$invoice_manager_pdf_stream = false;
				}
				
				$ifind = array(
					'{logo}',
					'{store}',
					'{address}',
					'{email}',
					'{telephone}',
					'{fax}',
					'{website}',
					'{order_date}',
					'{order}',
					'{invoice}',
					'{payment}',
					'{shipping}',
					'{payment_address}',
					'{shipping_address}',
					'{customer}',
					'{customer_email}',
					'{customer_telephone}',
				);
				$ireplace = array(
					'logo'				=> '<img class="img-responsive" src='.$server.'image/'.$invoice_manager_logo.'>',
					'store_name'        => $order_info['store_name'],
					'store_address'  	=> nl2br($store_address),
					'store_email'  		=> $store_email,
					'store_telephone'  	=> $store_telephone,
					'store_fax'  		=> $store_fax,
					'store_url'         => rtrim($order_info['store_url'], '/'),
					'date_added'        => date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'order_id'				=> $order_id,
					'invoice'			=> $invoice_no,
					'payment_method'	=> $order_info['payment_method'],
					'shipping_method'	=> $order_info['shipping_method'],
					'payment_address'	=> $payment_address,
					'shipping_address'	=> $shipping_address,
					'customer'			=> $order_info['firstname'].' '.$order_info['lastname'],
					'email'				=> $order_info['email'],
					'telephone'			=> $order_info['telephone'],
				);
				
				if(!empty($invoice_info['invoice_manager_header'. $order_info['language_id']])){
					$invoice_manager_header = $invoice_info['invoice_manager_header'. $order_info['language_id']];
				}else{
					$invoice_manager_header = '';
				}
				
				if($invoice_manager_header){
					$invoice_manager_header = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($ifind, $ireplace, $invoice_manager_header))));
				}
				
				if(!empty($invoice_info['invoice_manager_footer'. $order_info['language_id']])){
					$invoice_manager_footer = $invoice_info['invoice_manager_footer'. $order_info['language_id']];
				}else{
					$invoice_manager_footer = '';
				}
				
				if($invoice_manager_footer){
					$invoice_manager_footer = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($ifind, $ireplace, $invoice_manager_footer))));
				}
				
				if(!empty($invoice_info['invoice_manager_invoice_heading'. $order_info['language_id']])){
					$invoice_manager_invoice_heading = $invoice_info['invoice_manager_invoice_heading'. $order_info['language_id']];
				}else{
					$invoice_manager_invoice_heading = '';
				}
				
				if(!empty($invoice_info['invoice_manager_order_details_heading'. $order_info['language_id']])){
					$invoice_manager_order_details_heading = $invoice_info['invoice_manager_order_details_heading'. $order_info['language_id']];
				}else{
					$invoice_manager_order_details_heading = '';
				}
				
				if(!empty($invoice_info['invoice_manager_payment_address_heading'. $order_info['language_id']])){
					$invoice_manager_payment_address_heading = $invoice_info['invoice_manager_payment_address_heading'. $order_info['language_id']];
				}else{
					$invoice_manager_payment_address_heading = '';
				}
				
				if(!empty($invoice_info['invoice_manager_shipping_address_heading'. $order_info['language_id']])){
					$invoice_manager_shipping_address_heading = $invoice_info['invoice_manager_shipping_address_heading'. $order_info['language_id']];
				}else{
					$invoice_manager_shipping_address_heading = '';
				}
				
				if(!empty($invoice_info['invoice_manager_image_title'. $order_info['language_id']])){
					$invoice_manager_image_title = $invoice_info['invoice_manager_image_title'. $order_info['language_id']];
				}else{
					$invoice_manager_image_title = '';
				}
				
				if(!empty($invoice_info['invoice_manager_product_title'. $order_info['language_id']])){
					$invoice_manager_product_title = $invoice_info['invoice_manager_product_title'. $order_info['language_id']];
				}else{
					$invoice_manager_product_title = '';
				}
				
				if(!empty($invoice_info['invoice_manager_model_title'.$order_info['language_id']])){
					$invoice_manager_model_title = $invoice_info['invoice_manager_model_title'. $order_info['language_id']];
				}else{
					$invoice_manager_model_title = '';
				}
				
				if(!empty($invoice_info['invoice_manager_sku_title'.$order_info['language_id']])){
					$invoice_manager_sku_title = $invoice_info['invoice_manager_sku_title'.$order_info['language_id']];
				}else{
					$invoice_manager_sku_title = '';
				}
				
				if(!empty($invoice_info['invoice_manager_qty_title'.$order_info['language_id']])){
					$invoice_manager_qty_title = $invoice_info['invoice_manager_qty_title'.$order_info['language_id']];
				}else{
					$invoice_manager_qty_title = '';
				}
				
				if(!empty($invoice_info['invoice_manager_unit_title'.$order_info['language_id']])){
					$invoice_manager_unit_title = $invoice_info['invoice_manager_unit_title'.$order_info['language_id']];
				}else{
					$invoice_manager_unit_title = '';
				}
				
				if(!empty($invoice_info['invoice_manager_total_title'.$order_info['language_id']])){
					$invoice_manager_total_title = $invoice_info['invoice_manager_total_title'.$order_info['language_id']];
				}else{
					$invoice_manager_total_title = '';
				}
				
				if(!empty($invoice_info['invoice_manager_width'])){
					$invoice_manager_width = $invoice_info['invoice_manager_width'];
				}else{
					$invoice_manager_width = 50;
				}
				
				if(!empty($invoice_info['invoice_manager_height'])){
					$invoice_manager_height = $invoice_info['invoice_manager_height'];
				}else{
					$invoice_manager_height = 50;
				}
				
				if(!empty($invoice_info['invoice_manager_title_backgound'])){
					$invoice_manager_title_backgound = $invoice_info['invoice_manager_title_backgound'];
				}else{
					$invoice_manager_title_backgound = '';
				}
				
				if(!empty($invoice_info['invoice_manager_title_color'])){
					$invoice_manager_title_color = $invoice_info['invoice_manager_title_color'];
				}else{
					$invoice_manager_title_color = '';
				}
				
				if(!empty($invoice_info['invoice_manager_total_title'.$order_info['language_id']])){
					$invoice_manager_total_title = $invoice_info['invoice_manager_total_title'.$order_info['language_id']];
				}else{
					$invoice_manager_total_title = '';
				}
				
				
				if(!empty($invoice_info['invoice_manager_subject'.$order_info['language_id']])){
					$invoice_manager_subject = $invoice_info['invoice_manager_subject'.$order_info['language_id']];
				}else{
					$invoice_manager_subject = '';
				}
				
				
				if(!empty($invoice_info['invoice_manager_message'.$order_info['language_id']])){
					$invoice_manager_message = $invoice_info['invoice_manager_message'.$order_info['language_id']];
				}else{
					$invoice_manager_message = '';
				}
				
				
				$product_data = array();

				$products = $this->model_account_order->getOrderProducts($order_id);

				foreach ($products as $product){
					$option_data = array();
					$product_info = $this->model_catalog_product->getProduct($product['product_id']);
					if(isset($product_info['sku'])){
					 $sku = $product_info['sku'];
					}else{
					 $sku = '';
					}
					
					if (is_file(DIR_IMAGE . $product_info['image'])) {
						$image = $this->model_tool_image->resize($product_info['image'], $invoice_manager_width, $invoice_manager_height);
					} else {
						$image = $this->model_tool_image->resize('no_image.png', $invoice_manager_width, $invoice_manager_height);
					}

					$options = $this->model_account_order->getOrderOptions($order_id, $product['order_product_id']);

					foreach ($options as $option) {
						if ($option['type'] != 'file') {
							$value = $option['value'];
						} else {
							$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

							if ($upload_info) {
								$value = $upload_info['name'];
							} else {
								$value = '';
							}
						}

						$option_data[] = array(
							'name'  => $option['name'],
							'value' => $value
						);
					}

					$product_data[] = array(
						'name'     => $product['name'],
						'model'    => $product['model'],
						'sku'      => $sku,
						'image'	   => str_replace(' ', '%20', $image),
						'option'   => $option_data,
						'quantity' => $product['quantity'],
						'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
						'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value'])
					);
				}

				$voucher_data = array();

				$vouchers = $this->model_account_order->getOrderVouchers($order_id);

				foreach ($vouchers as $voucher) {
					$voucher_data[] = array(
						'description' => $voucher['description'],
						'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
					);
				}

				$total_data = array();

				$totals = $this->model_account_order->getOrderTotals($order_id);

				foreach ($totals as $total) {
					$total_data[] = array(
						'title' => $total['title'],
						'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value'])
					);
				}

				$data['orders'][] = array(
					'invoice_manager_invoice_heading_status'	=> $invoice_manager_invoice_heading_status,
					'invoice_manager_orderdetails_status'		=> $invoice_manager_orderdetails_status,
					'invoice_manager_shipping_address_status'	=> $invoice_manager_shipping_address_status,
					'invoice_manager_payment_address_status'	=> $invoice_manager_payment_address_status,
					'invoice_manager_product_image_status'		=> $invoice_manager_product_image_status,
					'invoice_manager_product_name_status'		=> $invoice_manager_product_name_status,
					'invoice_manager_product_model_status'		=> $invoice_manager_product_model_status,
					'invoice_manager_product_sku_status'		=> $invoice_manager_product_sku_status,
					'invoice_manager_product_qty_status'		=> $invoice_manager_product_qty_status,
					'invoice_manager_product_unit_price_status'	=> $invoice_manager_product_unit_price_status,
					'invoice_manager_product_total_status'		=> $invoice_manager_product_total_status,
					'invoice_manager_shipping_address_heading'	=> $invoice_manager_shipping_address_heading,
					'invoice_manager_payment_address_heading'	=> $invoice_manager_payment_address_heading,
					'invoice_manager_order_details_heading'		=> $invoice_manager_order_details_heading,
					'invoice_manager_invoice_heading'			=> $invoice_manager_invoice_heading,
					'invoice_manager_total_title'				=> $invoice_manager_total_title,
					'invoice_manager_unit_title'				=> $invoice_manager_unit_title,
					'invoice_manager_qty_title'					=> $invoice_manager_qty_title,
					'invoice_manager_sku_title'					=> $invoice_manager_sku_title,
					'invoice_manager_model_title'				=> $invoice_manager_model_title,
					'invoice_manager_product_title'				=> $invoice_manager_product_title,
					'invoice_manager_image_title'				=> $invoice_manager_image_title,
					'invoice_manager_title_backgound'			=> $invoice_manager_title_backgound,
					'invoice_manager_title_color'				=> $invoice_manager_title_color,
					'tdwidth'	       							=> $tdwidth,
					'order_id'	      							=> $order_id,
					'invoice_no'       							=> $invoice_no,
					'date_added'       							=> date($this->language->get('date_format_short'), strtotime($order_info['date_added'])),
					'store_name'       							=> $order_info['store_name'],
					'store_url'        							=> rtrim($order_info['store_url'], '/'),
					'store_address'    							=> nl2br($store_address),
					'store_email'     							=> $store_email,
					'store_telephone'  							=> $store_telephone,
					'store_fax'        							=> $store_fax,
					'email'            							=> $order_info['email'],
					'telephone'        							=> $order_info['telephone'],
					'shipping_address' 							=> $shipping_address,
					'shipping_method'  							=> $order_info['shipping_method'],
					'payment_address'  						 	=> $payment_address,
					'payment_method'   							=> $order_info['payment_method'],
					'product'          							=> $product_data,
					'voucher'          							=> $voucher_data,
					'total'            							=> $total_data,
					'comment'          							=> nl2br($order_info['comment']),
					'invoice_manager_header'          			=> html_entity_decode($invoice_manager_header, ENT_QUOTES, 'UTF-8'),
					'invoice_manager_footer'          			=> html_entity_decode($invoice_manager_footer, ENT_QUOTES, 'UTF-8'),
				);
			
				$filename="invoice-".$order_id;
			}
		}
		if(!$mail_status){
			if($format_type=='pdf'){
			  $html = $this->load->view('account/order_pdfinvoice', $data,true);
			  $this->pdf_create($html, $filename, $invoice_manager_pdf_stream, $orientation='portrait');
			}else{
			  $this->response->setOutput($this->load->view('account/order_pdfinvoice', $data));
			}
		}
	}
	
	public function pdf_create($html, $filename, $stream=false, $orientation="portrait",$download_status=false){
					$options = new Options();
					$options->set('isRemoteEnabled', TRUE);
					$dompdf = new Dompdf($options);
					$dompdf->loadHtml($html);
					$dompdf->setPaper("A4", $orientation);
					$dompdf->render();
					$savepath = DIR_DOWNLOAD.$filename.'.pdf';
					if($stream){
						$dompdf->stream($filename . ".pdf",array("Attachment" => false));
					} else { // save to file only, your going to load the file helper for this one
						file_put_contents($savepath, $dompdf->output());
						if($download_status){
							header("Content-Type: application/octet-stream");
							header("Content-Disposition: attachment; filename=" . urlencode($filename.'.pdf'));
							header("Content-Type: application/octet-stream");
							header("Content-Type: application/download");
							header("Content-Description: File Transfer");            
							header('Content-Length: ' . filesize($savepath));
							header('Accept-Ranges: bytes');
							readfile($savepath);
							exit;
						}else{
							return true;
						}
					}
				}
				
			
	public function info() {
		$this->load->language('account/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/order/info', 'order_id=' . $order_id, true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->model('account/order');

		$order_info = $this->model_account_order->getOrder($order_id);

		if ($order_info) {
			$this->document->setTitle($this->language->get('text_order'));

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/order', $url, true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_order'),
				'href' => $this->url->link('account/order/info', 'order_id=' . $this->request->get['order_id'] . $url, true)
			);

			$data['heading_title'] = $this->language->get('text_order');

			$data['text_order_detail'] = $this->language->get('text_order_detail');
			$data['text_invoice_no'] = $this->language->get('text_invoice_no');
			$data['text_order_id'] = $this->language->get('text_order_id');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_shipping_method'] = $this->language->get('text_shipping_method');
			$data['text_shipping_address'] = $this->language->get('text_shipping_address');
			$data['text_payment_method'] = $this->language->get('text_payment_method');
			$data['text_payment_address'] = $this->language->get('text_payment_address');
			$data['text_history'] = $this->language->get('text_history');
			$data['text_comment'] = $this->language->get('text_comment');

$data['text_loading_num'] = $this->language->get('text_loading_num');
$data['text_blank_yes'] = $this->language->get('text_blank_yes');

$data['button_return_loading'] = $this->language->get('button_return_loading');

if (isset($this->session->data['error'])) {
	$data['error_warning'] = $this->session->data['error'];

	unset($this->session->data['error']);
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
			
			$data['text_no_results'] = $this->language->get('text_no_results');

			$data['column_name'] = $this->language->get('column_name');
			$data['column_model'] = $this->language->get('column_model');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total'] = $this->language->get('column_total');
			$data['column_action'] = $this->language->get('column_action');
			$data['column_date_added'] = $this->language->get('column_date_added');
			$data['column_status'] = $this->language->get('column_status');
			$data['column_comment'] = $this->language->get('column_comment');

			$data['button_reorder'] = $this->language->get('button_reorder');
			$data['button_return'] = $this->language->get('button_return');
			$data['button_continue'] = $this->language->get('button_continue');

			if (isset($this->session->data['error'])) {
				$data['error_warning'] = $this->session->data['error'];

				unset($this->session->data['error']);
			} else {
				$data['error_warning'] = '';
			}

			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];

				unset($this->session->data['success']);
			} else {
				$data['success'] = '';
			}

			if ($order_info['invoice_no']) {
				$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			} else {
				$data['invoice_no'] = '';
			}

			$data['order_id'] = $this->request->get['order_id'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

			if ($order_info['payment_address_format']) {
				$format = $order_info['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $order_info['payment_firstname'],
				'lastname'  => $order_info['payment_lastname'],
				'company'   => $order_info['payment_company'],
				'address_1' => $order_info['payment_address_1'],
				'address_2' => $order_info['payment_address_2'],
				'city'      => $order_info['payment_city'],
				'postcode'  => $order_info['payment_postcode'],
				'zone'      => $order_info['payment_zone'],
				'zone_code' => $order_info['payment_zone_code'],
				'country'   => $order_info['payment_country']
			);

			$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$data['payment_method'] = $order_info['payment_method'];

			if ($order_info['shipping_address_format']) {
				$format = $order_info['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $order_info['shipping_firstname'],
				'lastname'  => $order_info['shipping_lastname'],
				'company'   => $order_info['shipping_company'],
				'address_1' => $order_info['shipping_address_1'],
				'address_2' => $order_info['shipping_address_2'],
				'city'      => $order_info['shipping_city'],
				'postcode'  => $order_info['shipping_postcode'],
				'zone'      => $order_info['shipping_zone'],
				'zone_code' => $order_info['shipping_zone_code'],
				'country'   => $order_info['shipping_country']
			);

			$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$data['shipping_method'] = $order_info['shipping_method'];

			$this->load->model('catalog/product');
			$this->load->model('tool/upload');

			// Products
			$data['products'] = array();

			$products = $this->model_account_order->getOrderProducts($this->request->get['order_id']);

			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value),
						'color-code' => $option['option_value_id']
					);
				}

				$product_info = $this->model_catalog_product->getProduct($product['product_id']);

				if ($product_info) {
					$reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], true);
				} else {
					$reorder = '';
				}

				$data['products'][] = array(
					'name'     => $product['name'],
					'model'    => $product['model'],
					'option'   => $option_data,
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					'reorder'  => $reorder,
					'return'   => $this->url->link('account/return/add', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], true)
				);
			}

			// Voucher
			$data['vouchers'] = array();

			$vouchers = $this->model_account_order->getOrderVouchers($this->request->get['order_id']);

			foreach ($vouchers as $voucher) {
				$data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			// Totals
			$data['totals'] = array();

			$totals = $this->model_account_order->getOrderTotals($this->request->get['order_id']);

			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
				);
			}

			$data['comment'] = nl2br($order_info['comment']);

$data['return_loading'] = $this->url->link('account/order/return_loading', 'order_id=' . $order_id, 'SSL');

$data['loading_num'] = FALSE;
$data['blank_yes'] = FALSE;

if ($this->config->get('econt_return_loading')) {
	$this->load->model('account/econt');

	$loading_info = $this->model_account_econt->getLoading($order_id);

	if ($loading_info) {
		if (!$loading_info['is_returned']) {
			if (strtotime($loading_info['receiver_time']) > 0) {
				$data['loading_num'] = $loading_info['loading_num'];
			} else {
				$econt_data = array(
					'type' => 'shipments',
					'xml'  => "<shipments full_tracking='ON'><num>" . $loading_info['loading_num'] . '</num></shipments>'
				);

				$results = $this->serviceTool($econt_data);

				if ($results) {
					if (isset($results->shipments->e->error)) {
						$this->error['warning'] = (string)$results->shipments->e->error;
					} elseif (isset($results->error)) {
						$this->error['warning'] = (string)$results->error->message;
					} elseif (isset($results->shipments->e)) {
						if (strtotime($results->shipments->e->receiver_time) > 0) {
							$data['loading_num'] = $loading_info['loading_num'];
						}

						if ($results->shipments->e->CD_send_sum && (strtotime($results->shipments->e->CD_send_time) > 0)) {
							$loading_info['trackings'] = array();
							$loading_info['next_parcels'] = array();

							$loading_info['is_imported'] = $results->shipments->e->is_imported;
							$loading_info['storage'] = $results->shipments->e->storage;
							$loading_info['receiver_person'] = $results->shipments->e->receiver_person;
							$loading_info['receiver_person_phone'] = $results->shipments->e->receiver_person_phone;
							$loading_info['receiver_courier'] = $results->shipments->e->receiver_courier;
							$loading_info['receiver_courier_phone'] = $results->shipments->e->receiver_courier_phone;
							$loading_info['receiver_time'] = $results->shipments->e->receiver_time;
							$loading_info['cd_get_sum'] = $results->shipments->e->CD_get_sum;
							$loading_info['cd_get_time'] = $results->shipments->e->CD_get_time;
							$loading_info['cd_send_sum'] = $results->shipments->e->CD_send_sum;
							$loading_info['cd_send_time'] = $results->shipments->e->CD_send_time;
							$loading_info['total_sum'] = $results->shipments->e->total_sum;
							$loading_info['currency'] = $results->shipments->e->currency;
							$loading_info['sender_ammount_due'] = $results->shipments->e->sender_ammount_due;
							$loading_info['receiver_ammount_due'] = $results->shipments->e->receiver_ammount_due;
							$loading_info['other_ammount_due'] = $results->shipments->e->other_ammount_due;
							$loading_info['delivery_attempt_count'] = $results->shipments->e->delivery_attempt_count;
							$loading_info['blank_yes'] = $results->shipments->e->blank_yes;
							$loading_info['blank_no'] = $results->shipments->e->blank_no;

							if (isset($results->shipments->e->tracking)) {
								foreach ($results->shipments->e->tracking->row as $tracking) {
									$loading_info['trackings'][] = array(
										'time'       => $tracking->time,
										'is_receipt' => $tracking->is_receipt,
										'event'      => $tracking->event,
										'name'       => $tracking->name,
										'name_en'    => $tracking->name_en
									);
								}
							}

							if (isset($results->shipments->e->next_parcels)) {
								foreach ($results->shipments->e->next_parcels->e as $next_parcel) {
									$data_next_parcel = array(
										'type' => 'shipments',
										'xml'  => "<shipments full_tracking='ON'><num>" . $next_parcel->num . '</num></shipments>'
									);

									$results_next_parcel = $this->serviceTool($data_next_parcel);

									if ($results_next_parcel) {
										if (isset($results_next_parcel->shipments->e->error)) {
											$this->error['warning'] = (string)$results_next_parcel->shipments->e->error;
										} elseif (isset($results_next_parcel->error)) {
											$this->error['warning'] = (string)$results_next_parcel->error->message;
										} elseif (isset($results_next_parcel->shipments->e)) {
											$trackings_next_parcel = array();

											if (isset($results_next_parcel->shipments->e->tracking)) {
												foreach ($results_next_parcel->shipments->e->tracking->row as $tracking) {
													$trackings_next_parcel[] = array(
														'time'       => $tracking->time,
														'is_receipt' => $tracking->is_receipt,
														'event'      => $tracking->event,
														'name'       => $tracking->name,
														'name_en'    => $tracking->name_en
													);
												}
											}

											$loading_info['next_parcels'][] = array(
												'loading_num'            => $results_next_parcel->shipments->e->loading_num,
												'is_imported'            => $results_next_parcel->shipments->e->is_imported,
												'storage'                => $results_next_parcel->shipments->e->storage,
												'receiver_person'        => $results_next_parcel->shipments->e->receiver_person,
												'receiver_person_phone'  => $results_next_parcel->shipments->e->receiver_person_phone,
												'receiver_courier'       => $results_next_parcel->shipments->e->receiver_courier,
												'receiver_courier_phone' => $results_next_parcel->shipments->e->receiver_courier_phone,
												'receiver_time'          => $results_next_parcel->shipments->e->receiver_time,
												'cd_get_sum'             => $results_next_parcel->shipments->e->CD_get_sum,
												'cd_get_time'            => $results_next_parcel->shipments->e->CD_get_time,
												'cd_send_sum'            => $results_next_parcel->shipments->e->CD_send_sum,
												'cd_send_time'           => $results_next_parcel->shipments->e->CD_send_time,
												'total_sum'              => $results_next_parcel->shipments->e->total_sum,
												'currency'               => $results_next_parcel->shipments->e->currency,
												'sender_ammount_due'     => $results_next_parcel->shipments->e->sender_ammount_due,
												'receiver_ammount_due'   => $results_next_parcel->shipments->e->receiver_ammount_due,
												'other_ammount_due'      => $results_next_parcel->shipments->e->other_ammount_due,
												'delivery_attempt_count' => $results_next_parcel->shipments->e->delivery_attempt_count,
												'blank_yes'              => $results_next_parcel->shipments->e->blank_yes,
												'blank_no'               => $results_next_parcel->shipments->e->blank_no,
												'reason'                 => $next_parcel->reason,
												'trackings'              => $trackings_next_parcel
											);
										}
									} else {
										$this->error['warning'] = $this->language->get('error_connect');
									}
								}
							}

							if (!$this->error) {
								$this->model_account_econt->updateLoading($loading_info);
							}
						}
					}
				} else {
					$this->error['warning'] = $this->language->get('error_connect');
				}
			}
		} elseif ($loading_info['returned_blank_yes']) {
			$data['blank_yes'] = $loading_info['returned_blank_yes'];
		}
	}
}
			

			// History
			$data['histories'] = array();

			$results = $this->model_account_order->getOrderHistories($this->request->get['order_id']);

			foreach ($results as $result) {
				$data['histories'][] = array(
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'status'     => $result['status'],
					'comment'    => $result['notify'] ? nl2br($result['comment']) : ''
				);
			}

			$data['continue'] = $this->url->link('account/order', '', true);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('account/order_info', $data));
		} else {
			$this->document->setTitle($this->language->get('text_order'));

			$data['heading_title'] = $this->language->get('text_order');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/order', '', true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_order'),
				'href' => $this->url->link('account/order/info', 'order_id=' . $order_id, true)
			);

			$data['continue'] = $this->url->link('account/order', '', true);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	public function reorder() {
		$this->load->language('account/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$this->load->model('account/order');

		$order_info = $this->model_account_order->getOrder($order_id);

		if ($order_info) {
			if (isset($this->request->get['order_product_id'])) {
				$order_product_id = $this->request->get['order_product_id'];
			} else {
				$order_product_id = 0;
			}

			$order_product_info = $this->model_account_order->getOrderProduct($order_id, $order_product_id);

			if ($order_product_info) {
				$this->load->model('catalog/product');

				$product_info = $this->model_catalog_product->getProduct($order_product_info['product_id']);

				if ($product_info) {
					$option_data = array();

					$order_options = $this->model_account_order->getOrderOptions($order_product_info['order_id'], $order_product_id);

					foreach ($order_options as $order_option) {
						if ($order_option['type'] == 'select' || $order_option['type'] == 'radio' || $order_option['type'] == 'image') {
							$option_data[$order_option['product_option_id']] = $order_option['product_option_value_id'];
						} elseif ($order_option['type'] == 'checkbox') {
							$option_data[$order_option['product_option_id']][] = $order_option['product_option_value_id'];
						} elseif ($order_option['type'] == 'text' || $order_option['type'] == 'textarea' || $order_option['type'] == 'date' || $order_option['type'] == 'datetime' || $order_option['type'] == 'time') {
							$option_data[$order_option['product_option_id']] = $order_option['value'];
						} elseif ($order_option['type'] == 'file') {
							$option_data[$order_option['product_option_id']] = $this->encryption->encrypt($order_option['value']);
						}
					}

					$this->cart->add($order_product_info['product_id'], $order_product_info['quantity'], $option_data);

					$this->session->data['success'] = sprintf($this->language->get('text_success'), $this->url->link('product/product', 'product_id=' . $product_info['product_id']), $product_info['name'], $this->url->link('checkout/cart'));

					unset($this->session->data['shipping_method']);
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['payment_method']);
					unset($this->session->data['payment_methods']);
				} else {
					$this->session->data['error'] = sprintf($this->language->get('error_reorder'), $order_product_info['name']);
				}
			}
		}

		$this->response->redirect($this->url->link('account/order/info', 'order_id=' . $order_id));
	}
}