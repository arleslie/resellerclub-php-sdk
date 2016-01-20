<?php

namespace arleslie\ResellerClub;

use guzzlehttp\client as Guzzle;

class ResellerClub {
	const API_URL = 'https://httpapi.com/api/';
	const API_TEST_URL = 'https://test.httpapi.com/api/';

	private $guzzle;
	private $apis = [];

	public function __construct($userid, $apikey, $testmode = false)
	{
		$this->guzzle = new Guzzle([
			'base_uri' => $testmode ? self::API_TEST_URL :  self::API_URL,
			'defaults' => [
				'query' => [
					'auth-userid' => $userid,
					'api-key' => $apikey
				]
			]
		]);
	}

	private function _getAPI($api)
	{
		if (empty($this->apis[$api])) {
			$class = 'APIs\\' . $api;
			$this->apis[$api] = new $class($this->guzzle);
		}

		return $this->apis[$api];
	}

	public function domains()
	{
		return $this->_getAPI('Domains');
	}
}