<?php 
if (session_status() == PHP_SESSION_NONE) {
   session_start();
}

class ControllerExtensionModuleCategoryWeight extends Controller
{
	private $data = array();
	private $db;

	private function setDB()
	{
		$dsn = 'mysql:host='.DB_HOSTNAME.';dbname='.DB_DATABASE.';charset=utf8';
		$username = DB_USERNAME;
		$password = DB_PASSWORD;

		$this->db = new PDO($dsn, $username, $password);
	}

	private function setData()
	{
		$this->setDB();

		$this->load->language('extension/module/category_weight');

		$this->document->setTitle($this->language->get('heading_title'));

		//links
		$this->data['header'] = $this->load->controller('common/header');
		$this->data['column_left'] = $this->load->controller('common/column_left');
		$this->data['footer'] = $this->load->controller('common/footer');
		$this->data['action'] = $this->url->link('extension/module/category_weight/setWeight', 'token=' . $this->session->data['token'], true);

		$sql = 'SELECT * FROM ' . DB_PREFIX . 'category INNER JOIN ' . DB_PREFIX . 'category_description ON ' . DB_PREFIX . 'category.category_id = ' . DB_PREFIX . 'category_description.category_id';
		$query = $this->db->prepare($sql);
		$query->execute([]);

		$this->data['categories'] = $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function install()
	{

	}

	public function index()
	{
		$this->setData();

		$this->response->setOutput($this->load->view('extension/module/category_weight', $this->data));
	}

	public function setWeight()
	{
		$this->setDB();

		$this->load->language('extension/module/category_weight');

		if (isset($_POST['category']) && isset($_POST['weight'])) {
			$weight = $_POST['weight'];
			$category_id = intval($_POST['category']);

			//Update weight in DB
			$sql = 'SELECT * FROM ' . DB_PREFIX . 'product_to_category WHERE category_id = ?';
			$query = $this->db->prepare($sql);
			$query->execute([$category_id]);

			while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
				$sqlUpdate = 'UPDATE ' . DB_PREFIX . 'product SET weight = ? WHERE product_id = ?'; 
				$queryUpdate = $this->db->prepare($sqlUpdate);
				$queryUpdate->execute([$weight, $row['product_id']]);
			}

			//Get name of category
			$sql = 'SELECT * FROM ' . DB_PREFIX . 'category_description WHERE category_id = ?';
			$query = $this->db->prepare($sql);
			$query->execute([$category_id]);

			$_SESSION['category-updated'] = $query->fetch(PDO::FETCH_ASSOC);
			$_SESSION['success'] = $this->language->get('success-message');
		}

		$this->response->redirect($this->url->link('extension/module/category_weight', 'token=' . $this->session->data['token'], true));
	}
}