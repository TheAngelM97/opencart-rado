<?php 

class ModelExtensionModuleUploadedCode extends Model
{
    private $table = 'uploaded_codes';
		
    public function getAll()
    {
        $sql = 'SELECT * FROM ' . $this->table;
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getByStore($store)
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE store = "' . $this->db->escape($store) . '"';
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function getByCode($uploaded_code, $store)
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE admin_code = "' . $this->db->escape($uploaded_code) . '" AND store = "'. $this->db->escape($store) .'"';
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function add($product_id, $admin_code, $store)
    {
        $sql = 'INSERT INTO ' . $this->table . ' (product_id, admin_code, store) VALUES ('. $this->db->escape($product_id) .', "'. $this->db->escape($admin_code) .'", "'. $this->db->escape($store) .'")';
        return $this->db->query($sql);
    }

    public function colorConnection($admin_code)
    {
        $sql = 'SELECT * FROM color_connection WHERE product_code = "'. $this->db->escape($admin_code) .'"';
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function getProductByCodeAndStore($code, $store)
    {
        $sql = 'SELECT * FROM color_connection INNER JOIN ' . DB_PREFIX . 'product_option_value ON color_connection.product_option_value_id = ' . DB_PREFIX . 'product_option_value.product_option_value_id INNER JOIN '. DB_PREFIX .'product ON '. DB_PREFIX .'product_option_value.product_id = '. DB_PREFIX .'product.product_id WHERE color_connection.product_code = "'. $this->db->escape($code) .'" AND ' . DB_PREFIX . 'product.admin_store = "'. $this->db->escape($store) .'"';
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function updateQuantity($product_id, $quantity)
    {
        $sql = 'UPDATE ' . DB_PREFIX . 'product SET quantity = ' . $this->db->escape($quantity) . ' WHERE product_id = ' . $product_id;
        return $this->db->query($sql);
    }

    public function updateColorQuantity($product_option_value_id, $color_quantity)
    {
        $sql = 'UPDATE ' . DB_PREFIX . 'product_option_value SET quantity = ' . $this->db->escape($color_quantity) . ' WHERE product_option_value_id = ' . $this->db->escape($product_option_value_id);

        return $this->db->query($sql);
    }

    public function getColorQuantity($product_option_value_id)
    {
        $sql = 'SELECT quantity FROM ' . DB_PREFIX . 'product_option_value WHERE product_option_value_id = ' . $this->db->escape($product_option_value_id);
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function getColorPrice($product_option_value_id)
    {
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'product_option_value WHERE product_option_value_id = ' . $this->db->escape($product_option_value_id);
        $query = $this->db->query($sql);
        return $query->row;
    }
}
?>