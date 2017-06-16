<?php
class ModelSaleRapido extends Model {
	public function getOrders($data = array()) {
		$sql = "SELECT ro.*, o.order_status_id, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS status FROM " . DB_PREFIX . "rapido_order ro INNER JOIN `" . DB_PREFIX . "order` o ON o.order_id = ro.order_id WHERE ro.tovaritelnica > 0";

		if (!empty($data['filter_tovaritelnica'])) {
			$sql .= " AND ro.tovaritelnica = '" . $this->db->escape($data['filter_tovaritelnica']) . "'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND ro.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_created'])) {
			$sql .= " AND DATE(ro.date_created) = DATE('" . $this->db->escape($data['filter_date_created']) . "')";
		}

		if (!empty($data['filter_sendoffice'])) {
			$sql .= " AND ro.sendoffice = '" . $this->db->escape($data['filter_sendoffice']) . "'";
		}

		$sort_data = array(
			'ro.tovaritelnica',
			'ro.order_id',
			'status',
			'ro.date_created',
			'ro.sendoffice'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY ro.tovaritelnica";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalOrders($data = array()) {
		$sql = "SELECT COUNT(ro.rapido_order_id) AS total FROM " . DB_PREFIX . "rapido_order ro INNER JOIN `" . DB_PREFIX . "order` o ON o.order_id = ro.order_id WHERE ro.tovaritelnica > 0";

		if (!empty($data['filter_tovaritelnica'])) {
			$sql .= " AND ro.tovaritelnica = '" . $this->db->escape($data['filter_tovaritelnica']) . "'";
		}

		if (!empty($data['filter_order_id'])) {
			$sql .= " AND ro.order_id = '" . (int)$data['filter_order_id'] . "'";
		}

		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}

		if (!empty($data['filter_date_created'])) {
			$sql .= " AND DATE(ro.date_created) = DATE('" . $this->db->escape($data['filter_date_created']) . "')";
		}

		if (!empty($data['filter_sendoffice'])) {
			$sql .= " AND ro.sendoffice = '" . $this->db->escape($data['filter_sendoffice']) . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getOrder($order_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "rapido_order WHERE order_id = '" . (int)$order_id . "'");

		return $query->row;
	}

	public function getOrderTotals($order_id) {
		$order_total = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_total WHERE order_id = '" . (int)$order_id . "'");

		foreach ($query->rows as $row) {
			$order_total[$row['code']] = $row;
		}

		return $order_total;
	}

	public function updateOrderInfo($order_id, $data = array()) {
		$comment = '';

		$order_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id = '" . (int)$order_id . "'");

		if ($order_query->num_rows) {
			$order_total = $this->getOrderTotals($order_id);

			if (!empty($order_total['shipping']) && !empty($order_total['total'])) {
				$language_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$order_query->row['language_id'] . "'");
				$language = new Language($language_query->row['directory']);
				$language->load('default.php');
				$language->load('sale/rapido');

				$rapido_receiver_address = array();

				if (!$data['take_office']) {
					if ($data['quarter']) {
						$rapido_receiver_address[] = $data['quarter'];
					}

					if ($data['street']) {
						$rapido_receiver_address[] = $data['street'];
					}

					if ($data['street_no']) {
						$rapido_receiver_address[] = $language->get('entry_street_no') . ' ' . $data['street_no'];
					}

					if ($data['block_no']) {
						$rapido_receiver_address[] = $language->get('entry_block_no') . ' ' . $data['block_no'];
					}

					if ($data['entrance_no']) {
						$rapido_receiver_address[] = $language->get('entry_entrance_no') . ' ' . $data['entrance_no'];
					}

					if ($data['floor_no']) {
						$rapido_receiver_address[] = $language->get('entry_floor_no') . ' ' . $data['floor_no'];
					}

					if ($data['apartment_no']) {
						$rapido_receiver_address[] = $language->get('entry_apartment_no') . ' ' . $data['apartment_no'];
					}
				}

				if ($data['additional_info']) {
					$rapido_receiver_address[] = $language->get('entry_additional_info') . ' ' . $data['additional_info'];
				}

				$old_shipping_value = $order_total['shipping']['value'];
				$shipping_value = $this->currency->convert($data['shipping_method_cost'], $this->config->get('rapido_currency'), $this->config->get('config_currency'));
				$shipping_text = $this->currency->format($shipping_value, $order_query->row['currency_code'], $order_query->row['currency_value']);

				$this->db->query("UPDATE " . DB_PREFIX . "order_total SET title = '" . (isset($data['shipping_method_title']) ? ($language->get('text_description') . ' - ' . $data['shipping_method_title']) : $order_total['shipping']['title']) . "', value = '" . (float) $shipping_value . "' WHERE order_total_id = '" . (int) $order_total['shipping']['order_total_id'] . "'");

				$comment .= (isset($data['shipping_method_title']) ? ($language->get('text_description') . ' - ' . $data['shipping_method_title']) : $order_total['shipping']['title']) . ' ' . $shipping_text;

				$total_value = $order_total['total']['value'] - $old_shipping_value + $shipping_value;
				$total_text = $this->currency->format($total_value, $order_query->row['currency_code'], $order_query->row['currency_value']);

				$this->db->query("UPDATE " . DB_PREFIX . "order_total SET value = '" . (float) $total_value . "' WHERE order_total_id = '" . (int) $order_total['total']['order_total_id'] . "'");

				$this->db->query("UPDATE `" . DB_PREFIX . "order` SET total = '" . (float)$total_value . "', shipping_address_1 = '" . $this->db->escape(implode(', ', $rapido_receiver_address)) . "', shipping_city = '" . $this->db->escape($data['city']) . "', shipping_postcode = '" . $this->db->escape($data['postcode']) . "', shipping_code = '" . (isset($data['shipping_method']) ? $data['shipping_method'] : $order_query->row['shipping_code']) . "', shipping_method = '" . (isset($data['shipping_method_title']) ? ($language->get('text_description') . ' - ' . $data['shipping_method_title']) : $order_query->row['shipping_method']) . "' WHERE order_id = '" . (int)$order_id . "'");

				$comment .= "\n" . $order_total['total']['title'] . ' ' . $total_text;
			}

			$this->db->query("UPDATE " . DB_PREFIX . "rapido_order SET data = '" . $this->db->escape(serialize($data)) . "', sendoffice = '" . $this->db->escape($data['sender_office']) . "' WHERE order_id = '" . (int)$order_id . "'");
		}

		return $comment;
	}

	public function editOrder($order_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "rapido_order SET tovaritelnica = '" . $this->db->escape($data['tovaritelnica']) . "', date_created = NOW() WHERE order_id  = '" . (int)$order_id . "'");
	}

	public function editOrderCourier($tovaritelnica, $courier) {
		$this->db->query("UPDATE " . DB_PREFIX . "rapido_order SET courier = '" . (int)$courier . "' WHERE tovaritelnica  = '" . $this->db->escape($tovaritelnica) . "'");
	}
}
?>