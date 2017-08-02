<?php 
	class ModelExtensionModuleOffer extends Model
	{	
		private $table = 'offers';

		public function getAll()
		{
			$sql = 'SELECT * FROM ' . $this->table;
			$query = $this->db->query($sql);

			$offers = array();

			foreach ($query->rows as $row) {
				$offers[$row['id']]['person_name'] = $row['person_name'];
				$offers[$row['id']]['total_price'] = $row['total_price'];
				$offers[$row['id']]['discount'] = $row['discount'];
				$offers[$row['id']]['created_at'] = $row['created_at'];

				$products = unserialize($row['products']);

				//Get all products ingo by their id
				foreach ($products as $product_id => $product) {
					$sql = 'SELECT * FROM ' . DB_PREFIX . 'product INNER JOIN ' . DB_PREFIX . 'product_description ON ' . DB_PREFIX . 'product.product_id = ' . DB_PREFIX . 'product_description.product_id WHERE ' . DB_PREFIX . 'product.product_id = '. $product_id;

					$productQuery = $this->db->query($sql);

					if ($productQuery->row) {
						$finalProduct = $productQuery->row;
						$finalProduct['offer_quantity'] = $product['quantity'];
						$offers[$row['id']]['products'][] = $finalProduct;
					}
				}
			}

			return $offers;
		}

		public function add(string $person_name, array $products, $total_price, int $discount)
		{
			$sql = 'INSERT INTO ' . $this->table . ' (person_name, products, total_price, discount, created_at) VALUES ("'.$this->db->escape($person_name).'", "'.$this->db->escape(serialize($products)).'", '.$this->db->escape($total_price).', '.$discount.', NOW())';

			return $this->db->query($sql);
		}

		public function searchProduct($search)
		{
			$sql = 'SELECT * FROM ' . DB_PREFIX . 'product_description INNER JOIN ' . DB_PREFIX . 'product ON ' . DB_PREFIX . 'product_description.product_id = ' . DB_PREFIX . 'product.product_id WHERE ' . DB_PREFIX . 'product_description.name LIKE "%'.$this->db->escape($search).'%" OR ' . DB_PREFIX . 'product.model LIKE "%'.$this->db->escape($search).'%"';
			$query = $this->db->query($sql);
			return $query->rows;
		}

		public function getProduct($id)
		{
			$sql = 'SELECT *, '.DB_PREFIX.'product_description.product_id AS description_id FROM ' . DB_PREFIX . 'product INNER JOIN ' . DB_PREFIX . 'product_description ON ' . DB_PREFIX . 'product.product_id = ' . DB_PREFIX . 'product_description.product_id WHERE ' . DB_PREFIX . 'product.product_id = ' . $this->db->escape($id);

			$query = $this->db->query($sql);
			return $query->row;
		}

		public function delete(int $id)
		{
			$sql = 'DELETE FROM ' . $this->table . ' WHERE id = ' . $this->db->escape($id);
			return $this->db->query($sql);
		}
	}
?>