<?php
class Rapido {
	private $error;

	private $url;
	private $loginParam;

	const SERVICE_CITY = 1;
	const SERVICE_INTERCITY = 2;

	const STREET_TYPE = 1;
	const QUARTER_TYPE = 2;

	const BULGARIA = 100;
	const ROMANIA = 642;

	const PAYER_SENDER = 0;
	const PAYER_RECEIVER = 1;

	const PRICE_LIST_PAYER = 0;
	const PRICE_LIST_SENDER = 1;
	const PRICE_LIST_RECEIVER = 2;

	const PRINT_FORMAT_A4 = 1;
	const PRINT_FORMAT_LABEL = 2;

	const STATUS_OFFICE = 3;

	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->request = $registry->get('request');
		$this->log = $registry->get('log');

		if (isset($this->request->post['rapido_test'])) {
			$test = $this->request->post['rapido_test'];
		} else {
			$test = $this->config->get('rapido_test');
		}

		if (!$test) {
			$this->url = 'https://www.rapido.bg/rsystem2/schema.wsdl';
		} else {
			$this->url = 'https://www.rapido.bg/testsystem/schema.wsdl';
		}

		if (isset($this->request->post['rapido_username'])) {
			$username = $this->request->post['rapido_username'];
		} else {
			$username = $this->config->get('rapido_username');
		}

		if (isset($this->request->post['rapido_password'])) {
			$password = $this->request->post['rapido_password'];
		} else {
			$password = $this->config->get('rapido_password');
		}

		$this->loginParam = new stdClass();
		$this->loginParam->user = $username;
		$this->loginParam->pass = $password;
	}

	public function getMyObjects() {
		$this->error = '';
		$objects = array();

		try {
			$client = new SoapClient($this->url);
			$results = $client->getMyObjects($this->loginParam);
			$default = 1;

			foreach ($results as $result) {
				$objects[$result['SOAP_OFFICE_ID']] = $result;
				$objects[$result['SOAP_OFFICE_ID']]['default'] = $default;
				$default = 0;
			}
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			$this->log->write('Rapido :: getMyObjects :: ' . $e->getMessage());
		}

		return $objects;
	}

	public function getRazhodOrders($from_date = '', $to_date = '') {
		$this->error = '';
		$orders = array();

		try {
			$client = new SoapClient($this->url);
			$results = $client->getRazhodOrders($this->loginParam, $from_date, $to_date);

			foreach ($results as $result) {
				$orders[] =  array(
					'rid'      => $result['RID'],
					'created'  => $result['CREATED'],
					'tsum'     => $result['TSUM']
				);
			}
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			$this->log->write('Rapido :: getRazhodOrders :: ' . $e->getMessage());
		}

		return $orders;
	}

	public function getSubServices($service) {
		$this->error = '';
		$subservices = array();

		try {
			$client = new SoapClient($this->url);
			$results = $client->getSubServices($this->loginParam, $service);

			foreach ($results as $result) {
				$subservices[$result['DATA']] = $result['LABEL'];
			}
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			$this->log->write('Rapido :: getSubServices :: ' . $e->getMessage());
		}

		return $subservices;
	}

	public function getOfficesCity($city_id) {
		$this->error = '';
		$offices = array();

		try {
			$client = new SoapClient($this->url);
			$results = $client->getOfficesCity($this->loginParam, $city_id);

			foreach ($results as $result) {
				$offices[] = array(
					'id'    => $result['DATA'],
					'label' => $result['LABEL']
				);
			}
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			$this->log->write('Rapido :: getOfficesCity :: ' . $e->getMessage());
		}

		return $offices;
	}

	public function getCountries($start = 0, $count = 1000) {
		$this->error = '';
		$countries = array();

		try {
			$client = new SoapClient($this->url);
			$countries = $client->getCountries($this->loginParam, $start, $count);
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			$this->log->write('Rapido :: getCountries :: ' . $e->getMessage());
		}

		return $countries;
	}

	public function getCityes($country = 100, $start = 0, $count = 1000) {
		$this->error = '';
		$cities = array();

		try {
			$client = new SoapClient($this->url);
			$cities = $client->getCityes($this->loginParam, $country, $start, $count);
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			$this->log->write('Rapido :: getCityes :: ' . $e->getMessage());
		}

		return $cities;
	}

	public function getStreets($siteid = 0, $start = 0, $count = 1000) {
		$this->error = '';
		$streets = array();

		try {
			$client = new SoapClient($this->url);
			$streets = $client->getStreets($this->loginParam, $siteid, $start, $count);
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			$this->log->write('Rapido :: getStreets :: ' . $e->getMessage());
		}

		return $streets;
	}

	public function calculate($data) {
		$this->error = '';
		$prices = array();
		$params = array();

		$sender_city_id = (isset($data['sender_city_id']) ? $data['sender_city_id'] : $this->config->get('rapido_sender_city_id'));

		if ($data['city_id'] == $sender_city_id) {
			$params['service'] = self::SERVICE_CITY;
			$subservices = array_intersect_key($this->getSubServices($params['service']), array_flip($this->config->get('rapido_subservices_city')));
		} else {
			$params['service'] = self::SERVICE_INTERCITY;
			$subservices = array_intersect_key($this->getSubServices($params['service']), array_flip($this->config->get('rapido_subservices_intercity')));
		}

		$params['fix_chas'] = (isset($data['fixed_time_cb']) ? 1 : 0);
		$params['return_receipt'] = $this->config->get('rapido_return_receipt');
		$params['return_doc'] = $this->config->get('rapido_return_doc');
		$params['nal_platej'] = ($data['cod'] ? $data['total'] : 0);
		$insurance = (isset($data['insurance']) ? $data['insurance'] : $this->config->get('rapido_insurance'));
		$params['zastrahovka'] = ($insurance ? $data['insurance_total'] : 0);
		$params['teglo'] = $data['weight'];

		if ((float)$this->config->get('rapido_free_total') && ($data['total'] >= (float)$this->config->get('rapido_free_total'))) {
			$params['ZASMETKA'] = self::PAYER_SENDER;
		} elseif ((float)$this->config->get('rapido_fixed_total_city') && $params['service'] == self::SERVICE_CITY) {
			$params['ZASMETKA'] = self::PAYER_SENDER;
		} elseif ((float)$this->config->get('rapido_fixed_total_intercity') && $params['service'] == self::SERVICE_INTERCITY) {
			$params['ZASMETKA'] = self::PAYER_SENDER;
		} else {
			$params['ZASMETKA'] = $this->config->get('rapido_payer');
		}

		$params['CENOVA_LISTA'] = $this->config->get('rapido_price_list');

		try {
			$client = new SoapClient($this->url);

			foreach ($subservices as $subservice_data => $subservice_label) {
				$params['subservice'] = $subservice_data;
				$result = $client->calculate($this->loginParam, $params);

				$prices[$subservice_data] = $result;
				$prices[$subservice_data]['label'] = $subservice_label;
				$prices[$subservice_data]['service'] = $params['service'];
			}
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			$this->log->write('Rapido :: calculate :: ' . $e->getMessage());
		}

		return $prices;
	}

	public function create_order($data, $order) {
		$this->error = '';
		$result = array();

		$params = array();
		$params['service'] = $data['service'];
		$params['subservice'] = $data['shipping_method_id'];
		$params['nal_platej'] = ($data['cod'] ? $data['total'] : 0);
		$insurance = (isset($data['insurance']) ? $data['insurance'] : $this->config->get('rapido_insurance'));
		$params['zastrahovka'] = ($insurance ? $data['insurance_total'] : 0);
		$params['CONTENT'] = $data['content'];
		$params['CHUPLIVO'] = $data['fragile'];
		$params['teglo'] = $data['weight'];
		$params['RECEIVER'] = $order['firstname'] . ' ' . $order['lastname'];
		$params['COUNTRY_B'] = $data['country_id'];
		$params['CITY_B'] = $data['city'];
		$params['SITEID_B'] = $data['city_id'];
		$params['PK_B'] = $data['postcode'];
		$params['SUBOTEN_RAZNOS'] = (int)$data['suboten_raznos'];
		$params['CHECK_BEFORE_PAY'] = (int)$data['check_before_pay'];
		$params['TEST_BEFORE_PAY'] = (int)$data['test_before_pay'];

		if (!$data['take_office']) {
			$params['STREET_B'] = ($data['street'] ? $data['street'] : $data['quarter']);
			$params['STREETB_ID'] = ($data['street_id'] ? $data['street_id'] : $data['quarter_id']);
			$params['STREETB_TYPE'] = ($data['street_id'] ? self::STREET_TYPE : self::QUARTER_TYPE);
			$params['STREET_NO_B'] = $data['street_no'];
			$params['BLOCK_B'] = $data['block_no'];
			$params['ENTRANCE_B'] = $data['entrance_no'];
			$params['FLOOR_B'] = $data['floor_no'];
			$params['APARTMENT_B'] = $data['apartment_no'];
			$params['ADDITIONAL_INFO_B'] = $data['additional_info'];
		} else {
			$params['TAKEOFFICE'] = $data['office_id'];
		}

		if (isset($data['send_email'])) {
			$params['EMAIL_B'] = $order['email'];
		}
		$params['PHONE_B'] = $order['telephone'];
		$params['fix_chas'] = (isset($data['fixed_time_cb']) ? self::getFixedTimeType($data['fixed_time_type']) . ':' . $data['fixed_time_hour'] . ':' . $data['fixed_time_min'] : '');
		$params['return_receipt'] = $this->config->get('rapido_return_receipt');
		$params['return_doc'] = $this->config->get('rapido_return_doc');
		$params['PACK_COUNT'] = $data['count'];
		$params['CLIENT_REF1'] = $order['order_id'];
		if (isset($data['pazar']) && !empty($data['pazar'])) {
			$params['PAZAR'] = (float)$data['pazar'];
		}

		if ((float)$this->config->get('rapido_free_total') && ($data['total'] >= (float)$this->config->get('rapido_free_total'))) {
			$params['ZASMETKA'] = self::PAYER_SENDER;
		} elseif ((float)$this->config->get('rapido_fixed_total_city') && $params['service'] == self::SERVICE_CITY) {
			$params['ZASMETKA'] = self::PAYER_SENDER;
			if ($params['nal_platej']) {
				$params['nal_platej'] += (float)$this->config->get('rapido_fixed_total_city');
			}
		} elseif ((float)$this->config->get('rapido_fixed_total_intercity') && $params['service'] == self::SERVICE_INTERCITY) {
			$params['ZASMETKA'] = self::PAYER_SENDER;
			if ($params['nal_platej']) {
				$params['nal_platej'] += (float)$this->config->get('rapido_fixed_total_intercity');
			}
		} else {
			$params['ZASMETKA'] = isset($data['payer']) ? $data['payer'] : $this->config->get('rapido_payer');
		}

		$params['CENOVA_LISTA'] = $this->config->get('rapido_price_list');

		$params['POST_MONEY_TRANSFER'] = (int)$this->config->get('rapido_money_transfer');

		if ($data['sender_office_id'] && !$data['sender_office_default']) {
			$params['SENDOFFICE'] = $data['sender_office_id'];
			$params['SENDHOUR'] = $data['sendhour'];
			$params['SENDMIN'] = $data['sendmin'];
			$params['WORKHOUR'] = $data['workhour'];
			$params['WORKMIN'] = $data['workmin'];
		}

		try {
			$client = new SoapClient($this->url);
			$result = $client->create_order($this->loginParam, $params);
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			$this->log->write('Rapido :: create_order :: ' . $e->getMessage());
		}

		return $result;
	}

	public function track_order($tovaritelnica) {
		$this->error = '';
		$tracks = array();

		try {
			$client = new SoapClient($this->url);
			$tracks = $client->track_order($this->loginParam, $tovaritelnica);
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			$this->log->write('Rapido :: track_order :: ' . $e->getMessage());
		}

		return $tracks;
	}

	public function getTovarInfo($tovaritelnica) {
		$this->error = '';
		$info = array();

		try {
			$client = new SoapClient($this->url);
			$results = $client->getTovarInfo($this->loginParam, $tovaritelnica);

			if ($results) {
				$info = $results[0];
			}
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			$this->log->write('Rapido :: getTovarInfo :: ' . $e->getMessage());
		}

		return $info;
	}

	public function getNPInfo($tovaritelnica) {
		$this->error = '';
		$info = array();

		try {
			$client = new SoapClient($this->url);
			$info = $client->getNPInfo($this->loginParam, $tovaritelnica);
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			$this->log->write('Rapido :: getNPInfo :: ' . $e->getMessage());
		}

		return $info;
	}

	public function setPrintSettings() {
		$this->error = '';
		$result = '';

		if (isset($this->request->post['rapido_auto_print'])) {
			$auto_print = $this->request->post['rapido_auto_print'];
		} else {
			$auto_print = $this->config->get('rapido_auto_print');
		}

		if (isset($this->request->post['rapido_label_printer'])) {
			$label_printer = $this->request->post['rapido_label_printer'];
		} else {
			$label_printer = $this->config->get('rapido_label_printer');
		}

		if (isset($this->request->post['rapido_printer'])) {
			$printer = $this->request->post['rapido_printer'];
		} else {
			$printer = $this->config->get('rapido_printer');
		}

		$settings = array(
			'auto'    => (int)$auto_print,
			'format'  => ($label_printer ? self::PRINT_FORMAT_LABEL : self::PRINT_FORMAT_A4),
			'printer' => $printer
		);

		try {
			$client = new SoapClient($this->url);
			$result = $client->setPrintSettings($this->loginParam, $settings);
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			$this->log->write('Rapido :: setPrintSettings :: ' . $e->getMessage());
		}

		return $result;
	}

	public function print_pdf($tovaritelnica, $label_printer = 0) {
		$this->error = '';
		$pdf = array();

		$format = ($label_printer ? self::PRINT_FORMAT_LABEL : self::PRINT_FORMAT_A4);

		try {
			$client = new SoapClient($this->url);
			$pdf = base64_decode($client->print_pdf($this->loginParam, $tovaritelnica, $format));
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			$this->log->write('Rapido :: print_pdf :: ' . $e->getMessage());
		}

		return $pdf;
	}

	public function requestCurier($count, $weight, $sendoffice = 0, $readiness = '') {
		$this->error = '';
		$result = array();

		if (!$readiness) {
			$readiness = $this->config->get('rapido_readiness');
		}

		if (!$sendoffice) {
			$sendoffice = $this->config->get('rapido_sender_office_id');
		}

		try {
			$client = new SoapClient($this->url);
			$result = $client->requestCurier($this->loginParam, $count, $weight, $readiness, $sendoffice);
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			$this->log->write('Rapido :: requestCurier :: ' . $e->getMessage());
		}

		return $result;
	}

	public function getRazhodOrderInfo($number) {
		$this->error = '';
		$result = array();

		try {
			$client = new SoapClient($this->url);
			$result = $client->getRazhodOrderInfo($this->loginParam, $number);
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			$this->log->write('Rapido :: getRazhodOrderInfo :: ' . $e->getMessage());
		}

		return $result;
	}

	public function checkCityFixChas($city_id) {
		$this->error = '';
		$check = false;

		try {
			$client = new SoapClient($this->url);
			$check = $client->checkCityFixChas($this->loginParam, $city_id);
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			$this->log->write('Rapido :: checkCityFixChas :: ' . $e->getMessage());
		}

		return $check;
	}

	public function getInvoiceType() {
		$this->error = '';
		$type = 0;

		try {
			$client = new SoapClient($this->url);
			$type = $client->getInvoiceType($this->loginParam);
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			$this->log->write('Rapido :: getInvoiceType :: ' . $e->getMessage());
		}

		return $type;
	}

	public function getInvoices($from_date = '', $to_date = '') {
		$this->error = '';
		$invoices = array();

		try {
			$client = new SoapClient($this->url);
			$results = $client->getInvoices($this->loginParam, $from_date, $to_date);

			foreach ($results as $result) {
				$invoices[] =  array(
					'invoiceid' => $result['INVOICEID'],
					'created'   => $result['CREATED'],
					'paytype'   => $result['PAYTYPE'],
					'tcount'    => $result['TCOUNT'],
					'tsum'      => $result['TSUM'],
					'dds'       => $result['DDS'],
					'total'     => $result['TOTAL'],
				);
			}
		} catch (Exception $e) {
			$this->error = $e->getMessage();
			$this->log->write('Rapido :: getInvoices :: ' . $e->getMessage());
		}

		return $invoices;
	}

	public function getError() {
		return $this->error;
	}

	static function transliterate($value, $language_from = 'en', $language_to = 'bg') {
		$en = array('sht', 'zh', 'ts', 'tz', 'tc', 'c', 'ch', 'sh', 'yu', 'ya', 'yo', 'a', 'b', 'v', 'g', 'd', 'e', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'a', 'y', 'y', 'e', 'Zh', 'Sht', 'Ts', 'Tz', 'Tc', 'C', 'Ch', 'Sh', 'Yu', 'Ya', 'Yo', 'A', 'B', 'V', 'G', 'D', 'E', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'A', 'Y', 'Y', 'E', 'q', 'Q');
		$bg = array('щ', 'ж', 'ц', 'ц', 'ц', 'ц', 'ч', 'ш', 'ю', 'я', 'ё', 'а', 'б', 'в', 'г', 'д', 'е', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ъ', 'ь', 'ы', 'э', 'Ж', 'Щ', 'Ц', 'Ц', 'Ц', 'Ц', 'Ч', 'Ш', 'Ю', 'Я', 'Ё', 'А', 'Б', 'В', 'Г', 'Д', 'Е', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ъ', 'Ь', 'Ы', 'Э', 'я', 'Я');

		if ($language_from != $language_to) {
			$value = str_replace(${$language_from}, ${$language_to}, $value);
		}

		return $value;
	}

	static function getFixedTimeType($type) {
		$fixed_time_types = array(
			'before' => 'ПРЕДИ',
			'in'     => 'ТОЧНО',
			'after'  => 'СЛЕД'
		);

		if (isset($fixed_time_types[$type])) {
			return $fixed_time_types[$type];
		} else {
			return '';
		}
	}

	static function getAvailableCountries() {
		return array(
			self::BULGARIA => 'БЪЛГАРИЯ',
			self::ROMANIA  => 'РУМЪНИЯ'
		);
	}

	static function getAvailableCountryById($country_id) {
		$countries = array(
			33  => self::BULGARIA,
			175 => self::ROMANIA
		);

		if (!empty($countries[$country_id])) {
			return $countries[$country_id];
		} else {
			return self::BULGARIA;
		}
	}
	// public function getAvailableCountryById($country_id) {
		// $countries = $this->getCountries();
		
		// var_dump($countries, $country_id, in_array($country_id, $countries));exit;
		// foreach ($countries as $country) {
			// var_dump($country['COUNTRYID_ISO'], $country_id);exit;
			// if (!empty($country['COUNTRYID_ISO'])) {
				// return $country['COUNTRYID_ISO'];
			// } else {
				// return self::BULGARIA;
			// }
		// }
	// }

	static function getAvailableCountryByIso($countryid_iso) {
		$countries = array(
			self::BULGARIA => array(
					'zone'       => '',
					'zone_id'    => 498, //Sofia - town
					'country'    => 'България',
					'country_id' => 33
				),
			self::ROMANIA  => array(
					'zone'       => '',
					'zone_id'    => 2688, //Bucuresti
					'country'    => 'Румъния',
					'country_id' => 175
				)
		);

		return $countries[$countryid_iso];
	}

	static function getVolumeWeight($width, $length, $height) {
		return round((((float)$width * (float)$length * (float)$height) / 6000), 3);
	}
}
?>