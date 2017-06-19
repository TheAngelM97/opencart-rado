<?php 

class ModelExtensionModuleUploadedCode extends Model
{
    private $table = 'uploaded_codes';
		
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
}
?>