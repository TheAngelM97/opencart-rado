<?php 

class ModelExtensionModuleCrawledUpdate extends Model
{
    private $table = 'crawled_updates';

    public function getAll()
    {
        $sql = 'SELECT * FROM ' . $this->table;
        $query = $this->db->query($sql);
        return $query->rows;
    }

    public function update($update)
    {
        $product_id = $update['update_product_id'];
        $new_price = $update['new_price'];
        $product_option_value_id = $update['product_option_value_id'];

        if ($product_option_value_id) {
            $sql = 'SELECT *, '.DB_PREFIX.'product.price AS product_price FROM ' . DB_PREFIX . 'product_option_value INNER JOIN ' . DB_PREFIX . 'product ON '.DB_PREFIX.'product_option_value.product_id = ' .DB_PREFIX . 'product.product_id WHERE '.DB_PREFIX.'product_option_value.product_option_value_id = ' . $product_option_value_id;
            $query = $this->db->query($sql);
            $product = $query->row;

            if ($new_price >= $product['product_price']) {
                $finalPrice = $new_price - $product['product_price'];
                $sql = 'UPDATE ' . DB_PREFIX . 'product_option_value SET price_prefix = \'+\', price = ' . $finalPrice . ' WHERE product_option_value_id = ' . $product_option_value_id;
            }
            else {
                $finalPrice = $product['product_price'] - $new_price;
                $sql = 'UPDATE ' . DB_PREFIX . 'product_option_value SET price_prefix = \'-\', price = ' . $finalPrice . '" WHERE product_option_value_id = ' . $product_option_value_id;
            }
        }
        else {
            $sql = 'UPDATE ' . DB_PREFIX . 'product SET price = ' . $new_price . ' WHERE product_id = ' . $product_id;
        }

        return $this->db->query($sql);
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM crawled_updates WHERE update_id = ' . $this->db->escape(trim($id));

        return $this->db->query($sql);
    }
}
?>