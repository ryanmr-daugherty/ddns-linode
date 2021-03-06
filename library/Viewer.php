<?php

class DynamicDNS_Viewer {

	private $datastore;

	public function __construct() {
		$this->datastore = new DynamicDNS_DataStore(); 
	}

	private function is_request_valid() {
		if ( isset($_GET['hostname']) == false ) {
			return false;
		}

		$hostname = $_GET['hostname'];

		// hostnames are no longer than 63 characters
		if ( strlen($hostname) > 64 ) {
			return false;
		}

		$config = isset( $this->datastore->config->hosts->{$hostname} );
		$data = isset( $this->datastore->data->hosts->{$hostname} );

		if ( $config && $data ) {
			return true;
		}
		return false;
	}
	
	public function render() {

		if ( !$this->is_request_valid() ) {
			include(DD_VIEWS . 'invalid-hostname-view.php');
			return false;
		}

		$type = "html";
		if ( isset($_GET['type']) && !empty($_GET['type']) ) {
			$type = $_GET['type'];
		}

		$hostname = $_GET['hostname'];
		$ip = $this->datastore->data->hosts->{$hostname}->{'ip'};
		$last_update = $this->datastore->data->hosts->{$hostname}->{'last_update'};
		$last_ping = $this->datastore->data->hosts->{$hostname}->{'last_ping'};
		$updates = $this->datastore->data->hosts->{$hostname}->{'updates'};

		switch ($type) {
			case 'plain':
				include(DD_VIEWS . 'plain-view.php');
				break;
			
			case 'json':
				include(DD_VIEWS . 'json-view.php');
				break;

			default:
				include(DD_VIEWS . 'html-view.php');
				break;
		}

	}

}