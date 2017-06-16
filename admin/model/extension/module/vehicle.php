<?php 

class ModelExtensionModuleVehicle extends Model
{
    private $types_table = 'vehicle_types';
    private $vehicles_table = 'vehicles';

	public function createTables($personal, $service)
	{
		$sql_vehicles = 'CREATE TABLE IF NOT EXISTS ' .$this->vehicles_table. ' (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(250) NOT NULL,
            `reg-number` varchar(50) NOT NULL,
            `type` int(11) NOT NULL,
            `kilometers` double NOT NULL,
            `created_at` datetime NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';

        $sql_types = 'CREATE TABLE IF NOT EXISTS ' .$this->types_table. ' (
            `type_id` int(11) NOT NULL AUTO_INCREMENT,
            `type` varchar(250) NOT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1';

        $sql_insert_types = 'INSERT INTO ' .$this->types_table. ' (type) VALUES ("'.$personal.'"), ("'.$service.'")';

        if ($this->db->query($sql_vehicles) && $this->db->query($sql_types)) {
            if ($this->db->query($sql_insert_types)) {
                return true;
            }
            return false;
        }

        return false;
	}

    public function getAllVehicles()
    {
        $sql = 'SELECT * FROM ' . $this->vehicles_table . ' INNER JOIN ' . $this->types_table . ' ON ' . $this->vehicles_table . '.type = ' . $this->types_table . '.type_id';

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getVehicle($id)
    {
        $sql = 'SELECT * FROM ' . $this->vehicles_table . ' WHERE id = ' . $id;

        $query = $this->db->query($sql);

        return $query->row;
    }

    public function getTypes()
    {
        $sql = 'SELECT * FROM ' . $this->types_table;

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function addVehicle($post)
    {
        $name = $post['name'];
        $reg_number = $post['reg-number'];
        $type = intval($post['type']);
        $kilometers = $post['kilometers'];

        $sql = 'INSERT INTO ' . $this->vehicles_table . ' (name, reg_number, type, kilometers, created_at) VALUES ("'.$this->db->escape($name).'", "'.$this->db->escape($reg_number).'", '.$this->db->escape($type).', "'.$this->db->escape($kilometers).'", NOW())';

        if ($this->db->query($sql)) {
            return true;
        }

        return false;
    }

    public function editVehicle($id, $post)
    {
        $name = $post['name'];
        $reg_number = $post['reg-number'];
        $type = intval($post['type']);
        $kilometers = $post['kilometers'];

        $sql = 'UPDATE ' . $this->vehicles_table . ' SET name="'.$this->db->escape($name).'", reg_number="'.$this->db->escape($reg_number).'", type='.$this->db->escape($type).', kilometers="'.$this->db->escape($kilometers).'", created_at=NOW() WHERE id=' . $id;

        if ($this->db->query($sql)) {
            return true;
        }

        return false;
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM ' . $this->vehicles_table . ' WHERE id = ' . $id;

        if ($this->db->query($sql)) {
            return true;
        }

        return false;
    }
}
?>