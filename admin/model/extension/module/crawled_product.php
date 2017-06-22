<?php 

class ModelExtensionModuleCrawledProduct extends Model
{
	public function getAllProducts($start = null, $limit = null)
	{
		if ($start !== null && $limit != null) {
			$sql = 'SELECT * FROM oc_crawled_products ORDER BY id DESC LIMIT ' . $start . ', ' . $limit . '';
		}
		else {
			$sql = 'SELECT * FROM oc_crawled_products';
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getUpdateProduct($product_id)
	{
		$sql = 'SELECT * FROM crawled_updates WHERE update_product_id = ' . $product_id;
		$query = $this->db->query($sql);
		return $query->row;
	}

	public function getUpdateProducts($start = null, $limit = null)
	{
		if ($start !== null && $limit != null) {
			$sql = 'SELECT *, '. DB_PREFIX .'product_description.name AS product_name, '.DB_PREFIX.'product.price AS product_price, '.DB_PREFIX.'product.quantity AS product_quantity FROM crawled_updates INNER JOIN '.DB_PREFIX.'product_description ON crawled_updates.update_product_id = '.DB_PREFIX.'product_description.product_id INNER JOIN '.DB_PREFIX.'product ON crawled_updates.update_product_id = '.DB_PREFIX.'product.product_id INNER JOIN '. DB_PREFIX .'product_option_value ON '. DB_PREFIX .'product_option_value.product_option_value_id = crawled_updates.product_option_value_id INNER JOIN '. DB_PREFIX .'option_value_description ON '. DB_PREFIX .'product_option_value.option_value_id = '. DB_PREFIX .'option_value_description.option_value_id ORDER BY crawled_updates.update_product_id DESC LIMIT ' . $start . ', ' . $limit . '';
		}
		else {
			$sql = 'SELECT * FROM crawled_updates INNER JOIN '.DB_PREFIX.'product_description ON crawled_updates.update_product_id = '.DB_PREFIX.'product_description.product_id INNER JOIN '.DB_PREFIX.'product ON crawled_updates.update_product_id = '.DB_PREFIX.'product.product_id ORDER BY crawled_updates.update_product_id DESC';
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getProduct($id)
	{
		$sql = 'SELECT * FROM oc_crawled_products WHERE id = ' . intval($id);
		$query = $this->db->query($sql);
		return $query->row;
	}

	public function getProductByCode($code, $store)
	{
		$sql = 'SELECT * FROM oc_crawled_products WHERE product_code = "'.trim($code).'" AND store = "'.$this->db->escape(trim($store)).'"';
		$query = $this->db->query($sql);
		return $query->row;
	}

	public function getProductByAdminCode($code, $store)
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'product WHERE admin_code = "'.trim($code).'"';
		$query = $this->db->query($sql);
		return $query->row;
	}

	public function upload($name, $code, $price, $quantity, $store, $manufacturer = '')
	{
		$sql = 'INSERT INTO oc_crawled_products (product_name, product_code, product_price, product_quantity, product_manufacturer, store) VALUES ("'.$this->db->escape(trim($name)).'", "'.$this->db->escape(trim($code)).'", '.$this->db->escape(trim($price)).', "'.$this->db->escape(trim($quantity)).'", "'.$this->db->escape(trim($manufacturer)).'", "'.$this->db->escape(trim($store)).'")';
		if ($this->db->query($sql)) {
			return true;
		}

		return false;
	}

	public function uploadInUpdates($product_id, $price, $quantity, $product_option_value_id = NULL)
	{
		$sql = 'INSERT INTO crawled_updates (update_product_id, new_price, new_quantity, product_option_value_id) VALUES ('.$this->db->escape(trim($product_id)).', '.$this->db->escape(trim($price)).', "'.$this->db->escape(trim($quantity)).'", '. $this->db->escape($product_option_value_id) .')';
		
		return $this->db->query($sql);
	}

	public function checkForUpdates($product_id)
	{
		$sql = 'SELECT * FROM crawled_updates WHERE update_product_id = ' . $this->db->escape(trim($product_id));
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getAllUpdates()
	{
		$sql = 'SELECT * FROM crawled_updates';
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function updateUpdates($product_id, $price, $quantity)
	{
		$sql = 'UPDATE crawled_updates SET new_price = ' . $this->db->escape(trim($price)) . ', new_quantity = "'.$this->db->escape(trim($quantity)).'"';
		if ($this->db->query($sql)) {
			return true;
		}
		return false;
	}

	public function updateWaiting($code, $price, $quantity, $store)
	{
		$sql = 'UPDATE oc_crawled_products SET price = "'.$this->db->escape($price).'", quantity = "'.$this->db->escape($name).'" WHERE product_code = "'.$this->db->escape(trim($name)).'" AND store = "'.$this->db->escape(trim($store)).'"';

		if ($this->db->query($sql)) {
			return true;
		}
		
		return false;
	}

	public function getPrice($code, $store)
	{
		$sql = 'SELECT product_price FROM oc_crawled_products WHERE product_code = "'.$this->db->escape(trim($code)).'" AND store = "'.$this->db->escape(trim($store)).'"';
		$query = $this->db->query($sql);
		return $query->row['product_price'];
	}

	public function getQuantity($code, $store)
	{
		$sql = 'SELECT * FROM oc_crawled_products WHERE product_code = "'.$this->db->escape(trim($code)).'" AND store = "'.$this->db->escape(trim($store)).'"';
		$query = $this->db->query($sql);
		return $query->row['product_quantity'];
	}

	public function getOptionValueInfo($product_option_value_id)
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'product_option_value WHERE product_option_value_id = ' . $this->db->escape($product_option_value_id);
		$query = $this->db->query($sql);
		return $query->row;
	}

	public function delete($id)
	{
		$sql = 'DELETE FROM oc_crawled_products WHERE id = ' . $id;

		if ($this->db->query($sql)) {
			return true;
		}

		return false;
	}

	public function deleteUpdate($id)
	{
		$sql = 'DELETE FROM crawled_updates WHERE update_id = ' . $this->db->escape(trim($id));

		if ($this->db->query($sql)) {
			return true;
		}

		return false;
	}

	public function deleteAll()
	{
		$sql = 'DELETE FROM oc_crawled_products';

		return $this->db->query($sql);
	}
}
?>