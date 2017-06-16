<?php
class ModelShippingRapido extends Model {
	public function addStreet($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "rapido_street SET siteid = '" . (int)trim($data['SITEID']) . "', streetid = '" . (int)trim($data['STREETID']) . "', streettype = '" . $this->db->escape(trim($data['STREETTYPE'])) . "', streettype2 = '" . (int)trim($data['STREETTYPE2']) . "', streetname = '" . $this->db->escape(trim($data['STREETNAME'])) . "'");
	}

	public function deleteStreets() {
		$this->db->query("TRUNCATE TABLE " . DB_PREFIX . "rapido_street");
	}

	public function getStreets($data) {
		$sql = "SELECT * FROM " . DB_PREFIX . "rapido_street s";

		$implode = array();

		if ($data['filter_name']) {
			$implode[] = "(LCASE(s.streetname) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%' OR LCASE(s.streetname) LIKE '%" . $this->db->escape(utf8_strtolower(Rapido::transliterate($data['filter_name']))) . "%')";
		}

		if ($data['filter_city_id']) {
			$implode[] = "s.siteid = '" . (int)$data['filter_city_id'] . "'";
		}

		if ($data['filter_type_id']) {
			$implode[] = "s.streettype2 = '" . (int)$data['filter_type_id'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " ORDER BY s.streetname";

		$sql .= " LIMIT " . (int)$data['limit'];

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function addCity($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "rapido_city SET siteid = '" . (int)trim($data['SITEID']) . "', name = '" . $this->db->escape(trim($data['NAME'])) . "', oblast = '" . $this->db->escape(trim($data['OBLAST'])) . "', obshtina = '" . $this->db->escape(trim($data['OBSHTINA'])) . "', sitetype = '" . $this->db->escape(trim($data['SITETYPE'])) . "', postcode = '" . $this->db->escape(trim($data['POSTCODE'])) . "', eknm = '" . $this->db->escape(trim($data['EKNM'])) . "', countryid_iso = '" . (int)trim($data['COUNTRYID_ISO']) . "'");
	}

	public function deleteCities() {
		$this->db->query("TRUNCATE TABLE " . DB_PREFIX . "rapido_city");
	}

	public function getCities($data) {
		$sql = "SELECT * FROM " . DB_PREFIX . "rapido_city c";

		$implode = array();

		if ($data['filter_name']) {
			$implode[] = "(LCASE(c.name) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%' OR LCASE(c.name) LIKE '%" . $this->db->escape(utf8_strtolower(Rapido::transliterate($data['filter_name']))) . "%')";
		}

		if ($data['filter_country_id']) {
			$implode[] = "c.countryid_iso = '" . (int)$data['filter_country_id'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sql .= " ORDER BY c.name";

		$sql .= " LIMIT " . (int)$data['limit'];

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function addCountry($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "rapido_country SET countryid_iso = '" . (int)trim($data['COUNTRYID_ISO']) . "', countryname = '" . $this->db->escape(trim($data['COUNTRYNAME'])) . "'");
	}

	public function deleteCountries() {
		$this->db->query("TRUNCATE TABLE " . DB_PREFIX . "rapido_country");
	}

	public function createTables() {
		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "rapido_address` (
		  `address_id` INT(11) NOT NULL,
		  `customer_id` INT(11) NOT NULL,
		  `postcode` VARCHAR(10) NOT NULL DEFAULT '',
		  `country_id` INT(11) NOT NULL DEFAULT '100' COMMENT '100 - България',
		  `city` VARCHAR(255) NOT NULL DEFAULT '',
		  `city_id` INT(11) NOT NULL DEFAULT '0',
		  `region` VARCHAR(255) NOT NULL DEFAULT '',
		  `take_office` TINYINT(1) NOT NULL DEFAULT '0',
		  `office_id` VARCHAR(10) NOT NULL DEFAULT '',
		  `quarter` VARCHAR(255) NOT NULL DEFAULT '',
		  `quarter_id` INT(11) NOT NULL DEFAULT '0',
		  `street` VARCHAR(255) NOT NULL DEFAULT '',
		  `street_id` INT(11) NOT NULL DEFAULT '0',
		  `street_no` VARCHAR(255) NOT NULL DEFAULT '',
		  `block_no` VARCHAR(255) NOT NULL DEFAULT '',
		  `entrance_no` VARCHAR(255) NOT NULL DEFAULT '',
		  `floor_no` VARCHAR(255) NOT NULL DEFAULT '',
		  `apartment_no` VARCHAR(255) NOT NULL DEFAULT '',
		  `additional_info` VARCHAR(255) NOT NULL DEFAULT '',
		  KEY `address_id` (`address_id`),
		  KEY `customer_id` (`customer_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "rapido_city` (
		  `siteid` INT(11) NOT NULL DEFAULT '0',
		  `postcode` VARCHAR(10) NOT NULL DEFAULT '',
		  `eknm` VARCHAR(10) NOT NULL DEFAULT '',
		  `sitetype` VARCHAR(5) NOT NULL DEFAULT '',
		  `name` VARCHAR(255) NOT NULL DEFAULT '',
		  `oblast` VARCHAR(255) NOT NULL DEFAULT '',
		  `obshtina` VARCHAR(255) NOT NULL DEFAULT '',
		  `countryid_iso` INT(11) NOT NULL DEFAULT '100' COMMENT '100 - България',
		  KEY `siteid` (`siteid`),
		  KEY `name` (`name`),
		  KEY `postcode` (`postcode`),
		  KEY `countryid_iso` (`countryid_iso`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "rapido_country` (
		  `countryid_iso` INT(11) NOT NULL DEFAULT '0' COMMENT '100 - България',
		  `countryname` VARCHAR(255) NOT NULL DEFAULT '',
		  KEY `countryid_iso` (`countryid_iso`),
		  KEY `countryname` (`countryname`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "rapido_order` (
		  `rapido_order_id` INT(11) NOT NULL AUTO_INCREMENT,
		  `order_id` INT(11) NOT NULL DEFAULT '0',
		  `tovaritelnica` VARCHAR(15) NOT NULL,
		  `data` TEXT NOT NULL,
		  `date_created` DATETIME NOT NULL,
		  `sendoffice` VARCHAR(255) NOT NULL DEFAULT '',
		  `courier` TINYINT(1) NOT NULL DEFAULT '0',
		  PRIMARY KEY (`rapido_order_id`),
		  KEY `order_id` (`order_id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8");

		$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "rapido_street` (
		  `siteid` INT(11) NOT NULL DEFAULT '0',
		  `streetid` INT(11) NOT NULL DEFAULT '0',
		  `streettype` VARCHAR(5) NOT NULL DEFAULT '',
		  `streettype2` TINYINT(1) NOT NULL DEFAULT '0',
		  `streetname` VARCHAR(255) NOT NULL DEFAULT '',
		  KEY `siteid` (`siteid`),
		  KEY `streetid` (`streetid`),
		  KEY `streettype2` (`streettype2`),
		  KEY `streetname` (`streetname`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8");
	}

	public function deleteTables() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "rapido_address`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "rapido_city`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "rapido_country`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "rapido_order`");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "rapido_street`");
	}
}
?>