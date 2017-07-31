<?php 
class ControllerExtensionModuleOffer extends Controller
{
	private $data = array('custom_styles' => ['module/offer.css']);

	public function setData()
	{
		//main
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');

		//links
		$this->data['add_form_link'] = $this->url->link('extension/module/offer/add', 'token=' . $this->session->data['token'], true);
		//product search
		$this->data['token'] = $this->session->data['token'];
		$this->data['product_search_link'] = $this->url->link('extension/module/offer/searchProduct');
	}

	public function index() {
		$this->setData();

		$this->load->language('extension/module/offer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->data['title'] = $this->language->get('heading_title');
		$this->response->setOutput($this->load->view('extension/module/offer', $this->data));
	}

	public function install() {

	}

	public function add()
	{
		$this->setData();

		$this->load->language('extension/module/offer');

		$this->data['title'] = $this->language->get('text_add_form_title');
		$this->data['data'] = $this->language->get('text_data');
		$this->data['text_name'] = $this->language->get('text_name');
		$this->data['text_products'] = $this->language->get('text_products');

		$this->response->setOutput($this->load->view('extension/module/offer_form', $this->data));
	}

	public function searchProduct()
	{
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post)) {
			echo json_encode(array('success'));
		}
		else {
			echo json_encode(array('error'));
		}
	}
}
?>