<?php 
	class ModelExtensionModuleOffer extends Model
	{	
		private $table = 'offer';

		public function searchProduct($search)
		{
			$sql = 'SELECT * FROM ' . DB_PREFIX . 'product_description INNER JOIN ' . DB_PREFIX . 'product ON ' . DB_PREFIX . 'product_description.product_id = ' . DB_PREFIX . 'product.product_id WHERE ' . DB_PREFIX . 'product_description.name LIKE "%'.$this->db->escape($search).'%" OR ' . DB_PREFIX . 'product.model LIKE "%'.$this->db->escape($search).'%"';
			$query = $this->db->query($sql);
			return $query->rows;
		}
	}
?>