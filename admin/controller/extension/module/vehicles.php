<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class ControllerExtensionModuleVehicles extends Controller
{
	private function validateForm($post)
	{
		$_SESSION['errors'] = array();
		//Check if any there are any empty values
		foreach ($post as $input_field => $input_value) {
			if (empty($input_value)) {
				$_SESSION['errors'][$input_field] = 'Това поле е задължително и то не може да бъде празно';
			}
		}

		if (count($_SESSION['errors'])) {
			return false;
		}

		return true;
	}

	public function index()
	{
		$this->load->model('extension/module/vehicle');

		$this->load->language('extension/module/vehicles');

		$title = $this->language->get('heading_title');
		$this->document->setTitle($title);
		$this->document->addStyle('view/stylesheet/extension/module/vehicles.css');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$data['title'] = $title;
		$data['add_message'] = $this->language->get('add');
		$data['none'] = $this->language->get('none');

		$data['delete_warning'] = $this->language->get('delete_warning');

		$data['form_link'] = $this->url->link('extension/module/vehicles/showForm', 'token='. $this->session->data['token'], true);

		$data['name'] = $this->language->get('name');
		$data['reg_number'] = $this->language->get('reg_number');
		$data['type'] = $this->language->get('type');
		$data['km'] = $this->language->get('kilometers');

		$data['vehicles'] = $this->model_extension_module_vehicle->getAllVehicles();

		$data['edit'] = $this->language->get('edit');
		$data['delete'] = $this->language->get('delete');

		$data['edit_link'] = $this->url->link('extension/module/vehicles/edit', 'token='. $this->session->data['token'], true);
		$data['delete_link'] = $this->url->link('extension/module/vehicles/delete', 'token='. $this->session->data['token'], true);

		$this->response->setOutput($this->load->view('extension/module/vehicles', $data));
	}

	public function showForm($data = array())
	{
		$this->load->model('extension/module/vehicle');

		$this->load->language('extension/module/vehicles');

		$this->document->setTitle($this->language->get('add'));
		$this->document->addStyle('view/stylesheet/extension/module/vehicles.css');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$data['add'] = $this->language->get('add');

		$data['name'] = $this->language->get('name');
		$data['reg_number'] = $this->language->get('reg_number');
		$data['type'] = $this->language->get('type');
		$data['km'] = $this->language->get('kilometers');
		$data['choose'] = $this->language->get('choose');

		$data['types'] = $this->model_extension_module_vehicle->getTypes();

		if (isset($this->request->get['id'])) {
			$data['vehicle_info'] = $this->model_extension_module_vehicle->getVehicle($this->request->get['id']);

			$data['form_action'] = $this->url->link('extension/module/vehicles/edit', 'token='. $this->session->data['token'] . '&id=' . $this->request->get['id'], true);
		}
		else {
			$data['form_action'] = $this->url->link('extension/module/vehicles/upload', 'token='. $this->session->data['token'], true);
		}

		$this->response->setOutput($this->load->view('extension/module/vehicle_form', $data));
	}

	public function upload()
	{
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateForm($this->request->post)) {
			$this->load->model('extension/module/vehicle');
			$this->load->language('extension/module/vehicles');

			if ($this->model_extension_module_vehicle->addVehicle($this->request->post)) {
				$_SESSION['success']['vehicle'] = $this->language->get('success_message');
			}
			else {
				$_SESSION['error']['vehicle'] = $this->language->get('error_message');
			}

			$this->response->redirect($this->url->link('extension/module/vehicles', 'token='. $this->session->data['token'], true));
		}

		$this->response->redirect($this->url->link('extension/module/vehicles/showForm', 'token='. $this->session->data['token'], true));
	}

	public function edit()
	{
		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateForm($this->request->post)) {
			$this->load->model('extension/module/vehicle');
			$this->load->language('extension/module/vehicles');

			if ($this->model_extension_module_vehicle->editVehicle($this->request->get['id'], $this->request->post)) {
				$_SESSION['success']['vehicle'] = $this->language->get('edit_success_message');
			}
			else {
				$_SESSION['error']['vehicle'] = $this->language->get('edit_error_message');
			}

			$this->response->redirect($this->url->link('extension/module/vehicles', 'token='. $this->session->data['token'], true));
		}

		$this->response->redirect($this->url->link('extension/module/vehicles/showForm', 'token='. $this->session->data['token']. '&id=' . $this->request->get['id'], true));
	}

	public function delete()
	{
		if (isset($this->request->get['id'])) {
			$this->load->model('extension/module/vehicle');

			$this->language->load('extension/module/vehicles');

			if ($this->model_extension_module_vehicle->delete($this->request->get['id'])) {
				$_SESSION['success']['vehicle'] = $this->language->get('delete_success_message');
			}
			else {
				$_SESSION['error']['vehicle'] = $this->language->get('delete_error_message');
			}
		}

		$this->response->redirect($this->url->link('extension/module/vehicles', 'token=' . $this->session->data['token'], true));
	}

	public function install()
	{
		$this->load->model('extension/module/vehicle');

		$this->load->language('extension/module/vehicles');

		$personal = $this->language->get('personal');
		$service = $this->language->get('service');

		if ($this->model_extension_module_vehicle->createTables($personal, $service)) {
			return 'ok';
		}

		return 'greshka';
	}
}
?>