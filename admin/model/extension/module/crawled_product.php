<?php 

class ModelExtensionModuleCrawledProduct extends Model
{
	public function getAllProducts($store = null, $start = null, $limit = null, $sort = null, $order = 'DESC')
	{
		$sql = 'SELECT * FROM oc_crawled_products';

		if ($store !== null) {
			$sql .= ' WHERE store = "'.$this->db->escape(trim($store)).'"';
		}

		if ($start !== null && $limit != null) {
			if ($sort !== null) {
				$sql .= ' ORDER BY '.$sort.' '.$order.' LIMIT ' . $start . ', ' . $limit;
			}
			else {
				$sql .= ' ORDER BY id DESC LIMIT ' . $start . ', ' . $limit;
			}
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getByStore($store)
	{
		$sql = 'SELECT * FROM oc_crawled_products WHERE store = "' . $this->db->escape($store) . '"';
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function countProducts()
	{
		$sql = 'SELECT COUNT(id) FROM oc_crawled_products';
		return $this->db->query($sql);
	}

	public function countInStock()
	{
		$sql = 'SELECT COUNT(id) FROM oc_crawled_products WHERE product_quantity > 0';
		return $this->db->query($sql);
	}
	public function countOutOfStock()
	{
		$sql = 'SELECT COUNT(id) FROM oc_crawled_products WHERE product_quantity = 0';
		return $this->db->query($sql);
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
			$sql = 'SELECT *, '. DB_PREFIX .'product_description.name AS product_name, '.DB_PREFIX.'product.price AS product_price, '.DB_PREFIX.'product.quantity AS product_quantity, '.DB_PREFIX.'product.product_id AS product_id FROM crawled_updates INNER JOIN '.DB_PREFIX.'product_description ON crawled_updates.update_product_id = '.DB_PREFIX.'product_description.product_id INNER JOIN '.DB_PREFIX.'product ON crawled_updates.update_product_id = '.DB_PREFIX.'product.product_id LEFT JOIN '. DB_PREFIX .'product_option_value ON '. DB_PREFIX .'product_option_value.product_option_value_id = crawled_updates.product_option_value_id LEFT JOIN '. DB_PREFIX .'option_value_description ON '. DB_PREFIX .'product_option_value.option_value_id = '. DB_PREFIX .'option_value_description.option_value_id ORDER BY crawled_updates.update_product_id DESC LIMIT ' . $start . ', ' . $limit . '';
		}
		else {
			$sql = 'SELECT *, '. DB_PREFIX .'product_description.name AS product_name, '.DB_PREFIX.'product.price AS product_price, '.DB_PREFIX.'product.quantity AS product_quantity, '.DB_PREFIX.'product.product_id AS product_id FROM crawled_updates INNER JOIN '.DB_PREFIX.'product_description ON crawled_updates.update_product_id = '.DB_PREFIX.'product_description.product_id INNER JOIN '.DB_PREFIX.'product ON crawled_updates.update_product_id = '.DB_PREFIX.'product.product_id LEFT JOIN '. DB_PREFIX .'product_option_value ON '. DB_PREFIX .'product_option_value.product_option_value_id = crawled_updates.product_option_value_id LEFT JOIN '. DB_PREFIX .'option_value_description ON '. DB_PREFIX .'product_option_value.option_value_id = '. DB_PREFIX .'option_value_description.option_value_id ORDER BY crawled_updates.update_product_id DESC';
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
		$sql = 'SELECT * FROM oc_crawled_products WHERE product_code = "'.$this->db->escape(trim($code)).'" AND store = "'.$this->db->escape(trim($store)).'"';
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

	public function uploadInUpdates($product_id, $price, $product_option_value_id = '0')
	{
		$sql = 'INSERT INTO crawled_updates (update_product_id, new_price, product_option_value_id) VALUES ('.$this->db->escape(trim($product_id)).', '.$this->db->escape(trim($price)).', '. $this->db->escape($product_option_value_id) .')';
		
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

	public function updateQuantity($id, $quantity)
	{
		$sql = 'UPDATE oc_crawled_products SET product_quantity = ' . $this->db->escape(trim($quantity)) . ' WHERE id = ' . $id;
		return $this->db->query($sql);
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

	public function getStores()
	{
		$sql = 'SELECT * FROM crawler_stores';
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getStorePercent($store)
	{
		$sql = 'SELECT percent FROM crawler_stores WHERE store = "' . $this->db->escape(trim($store)) . '"';
		$query = $this->db->query($sql);
		return $query->row;
	}

	public function updatePricePercent($store, $price_percent)
	{
		$sql = 'UPDATE crawler_stores SET percent = ' . $this->db->escape(trim($price_percent)) . ' WHERE store = "'.$this->db->escape(trim($store)).'"';
		return $this->db->query($sql);
	}

	public function delete($id)
	{
		$sql = 'DELETE FROM oc_crawled_products WHERE id = ' . $id;

		return $this->db->query($sql);
	}

	public function deleteFromStore($store)
	{
		$sql = 'DELETE FROM oc_crawled_products WHERE store = "' .$this->db->escape($store) . '"';
		return $this->db->query($sql);
	}

	public function deleteUpdate($id)
	{
		$sql = 'DELETE FROM crawled_updates WHERE update_id = ' . $this->db->escape(trim($id));

		return $this->db->query($sql);
	}

	public function deleteAll()
	{
		$sql = 'DELETE FROM oc_crawled_products';

		return $this->db->query($sql);
	}
}
?>