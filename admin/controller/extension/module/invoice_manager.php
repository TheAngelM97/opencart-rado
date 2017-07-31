<?php
class ControllerExtensionModuleInvoiceManager extends Controller {
	private $error = array();
	private $ssl = 'SSL';
	private $tpl = '.tpl';
	
	public function __construct($registry){
		 parent::__construct( $registry );
		 $this->ssl = (defined('VERSION') && version_compare(VERSION,'2.2.0.0','>=')) ? true : 'SSL';
		 $this->tpl = (defined('VERSION') && version_compare(VERSION,'2.2.0.0','>=')) ? false : '.tpl';
	}
	
	private function checkfield() {
		$query = $this->db->query("SHOW COLUMNS FROM `" . DB_PREFIX . "order` LIKE 'mail_status' ");
		if (!$query->num_rows) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD `mail_status` tinyint(4) NOT NULL ");
		}
	}

	public function index() {
		$this->checkfield();
		$this->load->language('extension/module/invoice_manager');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
		
		if(isset($this->request->get['store_id'])) {
			$data['store_id'] = $this->request->get['store_id'];
		}else{
			$data['store_id']	= 0;
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('invoice_manager', $this->request->post,$data['store_id']);

			$this->session->data['success'] = $this->language->get('text_success');
			if($this->request->post['stay']==1){
				$this->response->redirect($this->url->link('extension/module/invoice_manager', '&store_id='.$data['store_id'].'&token=' . $this->session->data['token'] , $this->ssl));
			}else{
				$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', $this->ssl));
			}
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_default'] = $this->language->get('text_default');

		//Entry
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_invoice_header'] = $this->language->get('entry_invoice_header');
		$data['entry_invoice_footer'] = $this->language->get('entry_invoice_footer');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_order_details'] = $this->language->get('entry_order_details');
		$data['entry_payment_address'] = $this->language->get('entry_payment_address');
		$data['entry_shipping_address'] = $this->language->get('entry_shipping_address');
		$data['entry_show_image'] = $this->language->get('entry_show_image');
		$data['entry_products'] = $this->language->get('entry_products');
		$data['entry_payment_format'] = $this->language->get('entry_payment_format');
		$data['entry_invoice_no'] = $this->language->get('entry_invoice_no');
		$data['entry_shipping_format'] = $this->language->get('entry_shipping_format');
		$data['entry_invoice_heading'] = $this->language->get('entry_invoice_heading');
		$data['entry_image_height'] = $this->language->get('entry_image_height');
		$data['entry_auto_send_invoice'] = $this->language->get('entry_auto_send_invoice');
		$data['entry_complete_status'] = $this->language->get('entry_complete_status');
		
		
		
		//Tab
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_invoice'] = $this->language->get('tab_invoice');
		$data['tab_support'] = $this->language->get('tab_support');
		$data['tab_language'] = $this->language->get('tab_language');
		$data['tab_control_panel'] = $this->language->get('tab_control_panel');
		$data['tab_mailing'] = $this->language->get('tab_mailing');
		
		//help
		$data['help_order_details'] = $this->language->get('help_order_details');
		$data['help_payment_address'] = $this->language->get('help_payment_address');
		$data['help_shipping_address'] = $this->language->get('help_shipping_address');
		$data['help_invoice_no'] = $this->language->get('help_invoice_no');
		$data['help_autoinvoice_no'] = $this->language->get('help_autoinvoice_no');
		$data['help_complete_status'] = $this->language->get('help_complete_status');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$this->load->model('setting/store');
		$data['stores'] = $this->model_setting_store->getStores();
		
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->error['warning'])) {
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
		
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/invoice_manager', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/module/invoice_manager', 'token=' . $this->session->data['token'] . '&store_id='. $data['store_id'], $this->ssl . '&type=module');
		
		$data['store_action'] =  $this->url->link('extension/module/invoice_manager','token=' . $this->session->data['token'], $this->ssl . '&type=module');

		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true);
		
		
		$store_info = $this->model_setting_setting->getSetting('invoice_manager', $data['store_id']);

		if (isset($this->request->post['invoice_manager_status'])) {
			$data['invoice_manager_status'] = $this->request->post['invoice_manager_status'];
		}else if(isset($store_info['invoice_manager_status'])){
			$data['invoice_manager_status'] = $store_info['invoice_manager_status'];
		} else {
			$data['invoice_manager_status'] = '';
		}
		
		if (isset($this->request->post['invoice_manager_complete_status'])) {
			$data['invoice_manager_complete_status'] = $this->request->post['invoice_manager_complete_status'];
		}else if(isset($store_info['invoice_manager_complete_status'])){
			$data['invoice_manager_complete_status'] = $store_info['invoice_manager_complete_status'];
		} else {
			$data['invoice_manager_complete_status'] = array();
		}
		
		if (isset($this->request->post['invoice_manager_send_button_status'])) {
			$data['invoice_manager_send_button_status'] = $this->request->post['invoice_manager_send_button_status'];
		}else if(isset($store_info['invoice_manager_send_button_status'])){
			$data['invoice_manager_send_button_status'] = $store_info['invoice_manager_send_button_status'];
		} else {
			$data['invoice_manager_send_button_status'] = '';
		}
		
		if (isset($this->request->post['invoice_manager_send_bulk_status'])) {
			$data['invoice_manager_send_bulk_status'] = $this->request->post['invoice_manager_send_bulk_status'];
		}else if(isset($store_info['invoice_manager_send_bulk_status'])){
			$data['invoice_manager_send_bulk_status'] = $store_info['invoice_manager_send_bulk_status'];
		} else {
			$data['invoice_manager_send_bulk_status'] = '';
		}
		
		if (isset($this->request->post['invoice_manager_pdf_stream'])) {
			$data['invoice_manager_pdf_stream'] = $this->request->post['invoice_manager_pdf_stream'];
		}else if(isset($store_info['invoice_manager_pdf_stream'])){
			$data['invoice_manager_pdf_stream'] = $store_info['invoice_manager_pdf_stream'];
		} else {
			$data['invoice_manager_pdf_stream'] = '';
		}
		
		if (isset($this->request->post['invoice_manager_download_invoice_customer_status'])) {
			$data['invoice_manager_download_invoice_customer_status'] = $this->request->post['invoice_manager_send_button_status'];
		}else if(isset($store_info['invoice_manager_download_invoice_customer_status'])){
			$data['invoice_manager_download_invoice_customer_status'] = $store_info['invoice_manager_download_invoice_customer_status'];
		} else {
			$data['invoice_manager_download_invoice_customer_status'] = '';
		}
		
		if (isset($this->request->post['invoice_manager_download_invoice_admin_status'])) {
			$data['invoice_manager_download_invoice_admin_status'] = $this->request->post['invoice_manager_download_invoice_admin_status'];
		}else if(isset($store_info['invoice_manager_download_invoice_admin_status'])){
			$data['invoice_manager_download_invoice_admin_status'] = $store_info['invoice_manager_download_invoice_admin_status'];
		} else {
			$data['invoice_manager_download_invoice_admin_status'] = '';
		}
		
		if (isset($this->request->post['invoice_manager_title_backgound'])) {
			$data['invoice_manager_title_backgound'] = $this->request->post['invoice_manager_title_backgound'];
		}else if(isset($store_info['invoice_manager_title_backgound'])){
			$data['invoice_manager_title_backgound'] = $store_info['invoice_manager_title_backgound'];
		} else {
			$data['invoice_manager_title_backgound'] = '';
		}
		
		if (isset($this->request->post['invoice_manager_title_color'])) {
			$data['invoice_manager_title_color'] = $this->request->post['invoice_manager_title_color'];
		}else if(isset($store_info['invoice_manager_title_color'])){
			$data['invoice_manager_title_color'] = $store_info['invoice_manager_title_color'];
		} else {
			$data['invoice_manager_title_color'] = '';
		}
		
		foreach($data['languages'] as $language) {
			if (isset($this->request->post['invoice_manager_header' . $language['language_id']])) {
				$data['invoice_manager_header' . $language['language_id']] = $this->request->post['invoice_manager_header' . $language['language_id']];
			}else if(isset($store_info['invoice_manager_header'. $language['language_id']])){
				$data['invoice_manager_header'. $language['language_id']] = $store_info['invoice_manager_header'. $language['language_id']];
			} else {
				$data['invoice_manager_header' . $language['language_id']] = '';
			}
			
			
			if (isset($this->request->post['invoice_manager_footer' . $language['language_id']])) {
				$data['invoice_manager_footer' . $language['language_id']] = $this->request->post['invoice_manager_footer' . $language['language_id']];
			}else if(isset($store_info['invoice_manager_footer'. $language['language_id']])){
				$data['invoice_manager_footer'. $language['language_id']] = $store_info['invoice_manager_footer'. $language['language_id']];
			} else {
				$data['invoice_manager_footer' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['invoice_manager_invoice_heading' . $language['language_id']])) {
				$data['invoice_manager_invoice_heading' . $language['language_id']] = $this->request->post['invoice_manager_invoice_heading' . $language['language_id']];
			}else if(isset($store_info['invoice_manager_invoice_heading'. $language['language_id']])){
				$data['invoice_manager_invoice_heading'. $language['language_id']] = $store_info['invoice_manager_invoice_heading'. $language['language_id']];
			} else {
				$data['invoice_manager_invoice_heading' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['invoice_manager_order_details_heading' . $language['language_id']])) {
				$data['invoice_manager_order_details_heading' . $language['language_id']] = $this->request->post['invoice_manager_order_details_heading' . $language['language_id']];
			}else if(isset($store_info['invoice_manager_order_details_heading'. $language['language_id']])){
				$data['invoice_manager_order_details_heading'. $language['language_id']] = $store_info['invoice_manager_order_details_heading'. $language['language_id']];
			} else {
				$data['invoice_manager_order_details_heading' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['invoice_manager_payment_address_heading' . $language['language_id']])) {
				$data['invoice_manager_payment_address_heading' . $language['language_id']] = $this->request->post['invoice_manager_payment_address_heading' . $language['language_id']];
			}else if(isset($store_info['invoice_manager_payment_address_heading'. $language['language_id']])){
				$data['invoice_manager_payment_address_heading'. $language['language_id']] = $store_info['invoice_manager_payment_address_heading'. $language['language_id']];
			} else {
				$data['invoice_manager_payment_address_heading' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['invoice_manager_shipping_address_heading' . $language['language_id']])) {
				$data['invoice_manager_shipping_address_heading' . $language['language_id']] = $this->request->post['invoice_manager_shipping_address_heading' . $language['language_id']];
			}else if(isset($store_info['invoice_manager_shipping_address_heading'. $language['language_id']])){
				$data['invoice_manager_shipping_address_heading'. $language['language_id']] = $store_info['invoice_manager_shipping_address_heading'. $language['language_id']];
			} else {
				$data['invoice_manager_shipping_address_heading' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['invoice_manager_image_title' . $language['language_id']])) {
				$data['invoice_manager_image_title' . $language['language_id']] = $this->request->post['invoice_manager_image_title' . $language['language_id']];
			}else if(isset($store_info['invoice_manager_image_title'. $language['language_id']])){
				$data['invoice_manager_image_title'. $language['language_id']] = $store_info['invoice_manager_image_title'. $language['language_id']];
			} else {
				$data['invoice_manager_image_title' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['invoice_manager_product_title' . $language['language_id']])) {
				$data['invoice_manager_product_title' . $language['language_id']] = $this->request->post['invoice_manager_product_title' . $language['language_id']];
			}else if(isset($store_info['invoice_manager_product_title'. $language['language_id']])){
				$data['invoice_manager_product_title'. $language['language_id']] = $store_info['invoice_manager_product_title'. $language['language_id']];
			} else {
				$data['invoice_manager_product_title' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['invoice_manager_model_title' . $language['language_id']])) {
				$data['invoice_manager_model_title' . $language['language_id']] = $this->request->post['invoice_manager_model_title' . $language['language_id']];
			}else if(isset($store_info['invoice_manager_model_title'. $language['language_id']])){
				$data['invoice_manager_model_title'. $language['language_id']] = $store_info['invoice_manager_model_title'. $language['language_id']];
			} else {
				$data['invoice_manager_model_title' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['invoice_manager_sku_title' . $language['language_id']])) {
				$data['invoice_manager_sku_title' . $language['language_id']] = $this->request->post['invoice_manager_sku_title' . $language['language_id']];
			}else if(isset($store_info['invoice_manager_sku_title'. $language['language_id']])){
				$data['invoice_manager_sku_title'. $language['language_id']] = $store_info['invoice_manager_sku_title'. $language['language_id']];
			} else {
				$data['invoice_manager_sku_title' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['invoice_manager_qty_title' . $language['language_id']])) {
				$data['invoice_manager_qty_title' . $language['language_id']] = $this->request->post['invoice_manager_qty_title' . $language['language_id']];
			}else if(isset($store_info['invoice_manager_qty_title'. $language['language_id']])){
				$data['invoice_manager_qty_title'. $language['language_id']] = $store_info['invoice_manager_qty_title'. $language['language_id']];
			} else {
				$data['invoice_manager_qty_title' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['invoice_manager_unit_title' . $language['language_id']])) {
				$data['invoice_manager_unit_title' . $language['language_id']] = $this->request->post['invoice_manager_unit_title' . $language['language_id']];
			}else if(isset($store_info['invoice_manager_unit_title'. $language['language_id']])){
				$data['invoice_manager_unit_title'. $language['language_id']] = $store_info['invoice_manager_unit_title'. $language['language_id']];
			} else {
				$data['invoice_manager_unit_title' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['invoice_manager_total_title' . $language['language_id']])) {
				$data['invoice_manager_total_title' . $language['language_id']] = $this->request->post['invoice_manager_total_title' . $language['language_id']];
			}else if(isset($store_info['invoice_manager_total_title'. $language['language_id']])){
				$data['invoice_manager_total_title'. $language['language_id']] = $store_info['invoice_manager_total_title'. $language['language_id']];
			} else {
				$data['invoice_manager_total_title' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['invoice_manager_subject' . $language['language_id']])) {
				$data['invoice_manager_subject' . $language['language_id']] = $this->request->post['invoice_manager_subject' . $language['language_id']];
			}else if(isset($store_info['invoice_manager_subject'. $language['language_id']])){
				$data['invoice_manager_subject'. $language['language_id']] = $store_info['invoice_manager_subject'. $language['language_id']];
			} else {
				$data['invoice_manager_subject' . $language['language_id']] = '';
			}
			
			if (isset($this->request->post['invoice_manager_message' . $language['language_id']])) {
				$data['invoice_manager_message' . $language['language_id']] = $this->request->post['invoice_manager_message' . $language['language_id']];
			}else if(isset($store_info['invoice_manager_message'. $language['language_id']])){
				$data['invoice_manager_message'. $language['language_id']] = $store_info['invoice_manager_message'. $language['language_id']];
			} else {
				$data['invoice_manager_message' . $language['language_id']] = '';
			}
		}
		
		if (isset($this->request->post['invoice_manager_width'])) {
			$data['invoice_manager_width'] = $this->request->post['invoice_manager_width'];
		}else if(isset($store_info['invoice_manager_width'])){
			$data['invoice_manager_width'] = $store_info['invoice_manager_width'];
		} else {
			$data['invoice_manager_width'] = 50;
		}
		
		if (isset($this->request->post['invoice_manager_height'])) {
			$data['invoice_manager_height'] = $this->request->post['invoice_manager_height'];
		}else if(isset($store_info['invoice_manager_height'])){
			$data['invoice_manager_height'] = $store_info['invoice_manager_height'];
		} else {
			$data['invoice_manager_height'] = 50;
		}
		
		if (isset($this->request->post['invoice_manager_invoice_heading_status'])) {
			$data['invoice_manager_invoice_heading_status'] = $this->request->post['invoice_manager_invoice_heading_status'];
		}else if(isset($store_info['invoice_manager_invoice_heading_status'])){
			$data['invoice_manager_invoice_heading_status'] = $store_info['invoice_manager_invoice_heading_status'];
		} else {
			$data['invoice_manager_invoice_heading_status'] = '';
		}
		
		if (isset($this->request->post['invoice_manager_orderdetails_status'])) {
			$data['invoice_manager_orderdetails_status'] = $this->request->post['invoice_manager_orderdetails_status'];
		}else if(isset($store_info['invoice_manager_orderdetails_status'])){
			$data['invoice_manager_orderdetails_status'] = $store_info['invoice_manager_orderdetails_status'];
		} else {
			$data['invoice_manager_orderdetails_status'] = '';
		}
		
		if (isset($this->request->post['invoice_manager_shipping_address_status'])) {
			$data['invoice_manager_shipping_address_status'] = $this->request->post['invoice_manager_shipping_address_status'];
		}else if(isset($store_info['invoice_manager_shipping_address_status'])){
			$data['invoice_manager_shipping_address_status'] = $store_info['invoice_manager_shipping_address_status'];
		} else {
			$data['invoice_manager_shipping_address_status'] = '';
		}
		
		if (isset($this->request->post['invoice_manager_payment_address_status'])) {
			$data['invoice_manager_payment_address_status'] = $this->request->post['invoice_manager_payment_address_status'];
		}else if(isset($store_info['invoice_manager_payment_address_status'])){
			$data['invoice_manager_payment_address_status'] = $store_info['invoice_manager_payment_address_status'];
		} else {
			$data['invoice_manager_payment_address_status'] = '';
		}
		
		
		if (isset($this->request->post['invoice_manager_product_image_status'])) {
			$data['invoice_manager_product_image_status'] = $this->request->post['invoice_manager_product_image_status'];
		}else if(isset($store_info['invoice_manager_product_image_status'])){
			$data['invoice_manager_product_image_status'] = $store_info['invoice_manager_product_image_status'];
		} else {
			$data['invoice_manager_product_image_status'] = '';
		}
		
		if (isset($this->request->post['invoice_manager_product_name_status'])) {
			$data['invoice_manager_product_name_status'] = $this->request->post['invoice_manager_product_name_status'];
		}else if(isset($store_info['invoice_manager_product_name_status'])){
			$data['invoice_manager_product_name_status'] = $store_info['invoice_manager_product_name_status'];
		} else {
			$data['invoice_manager_product_name_status'] = '';
		}
		
		if (isset($this->request->post['invoice_manager_product_model_status'])) {
			$data['invoice_manager_product_model_status'] = $this->request->post['invoice_manager_product_model_status'];
		}else if(isset($store_info['invoice_manager_product_model_status'])){
			$data['invoice_manager_product_model_status'] = $store_info['invoice_manager_product_model_status'];
		} else {
			$data['invoice_manager_product_model_status'] = '';
		}
		
		if (isset($this->request->post['invoice_manager_product_sku_status'])) {
			$data['invoice_manager_product_sku_status'] = $this->request->post['invoice_manager_product_sku_status'];
		}else if(isset($store_info['invoice_manager_product_sku_status'])){
			$data['invoice_manager_product_sku_status'] = $store_info['invoice_manager_product_sku_status'];
		} else {
			$data['invoice_manager_product_sku_status'] = '';
		}
		
		if (isset($this->request->post['invoice_manager_product_qty_status'])) {
			$data['invoice_manager_product_qty_status'] = $this->request->post['invoice_manager_product_qty_status'];
		}else if(isset($store_info['invoice_manager_product_qty_status'])){
			$data['invoice_manager_product_qty_status'] = $store_info['invoice_manager_product_qty_status'];
		} else {
			$data['invoice_manager_product_qty_status'] = '';
		}
		
		if (isset($this->request->post['invoice_manager_product_unit_price_status'])) {
			$data['invoice_manager_product_unit_price_status'] = $this->request->post['invoice_manager_product_unit_price_status'];
		}else if(isset($store_info['invoice_manager_product_unit_price_status'])){
			$data['invoice_manager_product_unit_price_status'] = $store_info['invoice_manager_product_unit_price_status'];
		} else {
			$data['invoice_manager_product_unit_price_status'] = '';
		}
		
		if (isset($this->request->post['invoice_manager_product_total_status'])) {
			$data['invoice_manager_product_total_status'] = $this->request->post['invoice_manager_product_total_status'];
		}else if(isset($store_info['invoice_manager_product_total_status'])){
			$data['invoice_manager_product_total_status'] = $store_info['invoice_manager_product_total_status'];
		} else {
			$data['invoice_manager_product_total_status'] = '';
		}
		
		if (isset($this->request->post['invoice_manager_payment_address_format'])) {
			$data['invoice_manager_payment_address_format'] = $this->request->post['invoice_manager_payment_address_format'];
		}else if(isset($store_info['invoice_manager_payment_address_format'])){
			$data['invoice_manager_payment_address_format'] = $store_info['invoice_manager_payment_address_format'];
		} else {
			$data['invoice_manager_payment_address_format'] = $this->language->get('text_payment_address_format');
		}
		
		if (isset($this->request->post['invoice_manager_shipping_address_format'])) {
			$data['invoice_manager_shipping_address_format'] = $this->request->post['invoice_manager_payment_address_format'];
		}else if(isset($store_info['invoice_manager_shipping_address_format'])){
			$data['invoice_manager_shipping_address_format'] = $store_info['invoice_manager_shipping_address_format'];
		} else {
			$data['invoice_manager_shipping_address_format'] = $this->language->get('text_payment_address_format');
		}
		
		if (isset($this->request->post['invoice_manager_logo'])) {
			$data['invoice_manager_logo'] = $this->request->post['invoice_manager_logo'];
		}else if(isset($store_info['invoice_manager_logo'])){
			$data['invoice_manager_logo'] = $store_info['invoice_manager_logo'];
		} else {
			$data['invoice_manager_logo'] = '';
		}
		
		$this->load->model('tool/image');
		
		if (isset($this->request->post['invoice_manager_logo']) && is_file(DIR_IMAGE . $this->request->post['config_logo'])) {
			$data['logo'] = $this->model_tool_image->resize($this->request->post['invoice_manager_logo'], 100, 100);
		} elseif (isset($store_info['invoice_manager_logo']) && is_file(DIR_IMAGE . $store_info['invoice_manager_logo'])) {
			$data['logo'] = $this->model_tool_image->resize($store_info['invoice_manager_logo'], 100, 100);
		} else {
			$data['logo'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/invoice_manager', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/invoice_manager')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}