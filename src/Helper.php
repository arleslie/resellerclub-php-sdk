<?php

namespace arleslie\ResellerClub;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Psr7\Response;

trait Helper
{
	protected $guzzle;
	private $creds = [];

	public function __construct(Guzzle $guzzle, array $creds)
	{
		$this->creds = $creds;
		$this->guzzle = $guzzle;
	}

	protected function get($method, $args = [], $prefix = '')
	{
		return $this->parse(
			$this->guzzle->get(
				$this->api .'/'. $prefix . $method.'.json?'.str_replace(['%5B0','%5D'], '', http_build_query(array_merge($args, $this->creds)))
			)
		);
	}

	protected function getXML($method, $args = [], $prefix = '')
	{
		return $this->parse(
			$this->guzzle->get(
				$this->api .'/'. $prefix . $method.'.xml?'.str_replace(['%5B0','%5D'], '', http_build_query(array_merge($args, $this->creds)))
			),
			'xml'
		);
	}

	protected function post($method, $args = [], $prefix = '')
	{
		return $this->parse(
			$this->guzzle->post($prefix . $method.'.json', $args)
		);
	}

	protected function parse(Response $response, $type = 'json')
	{
		switch ($type) {
			case 'json':
				return json_decode((string) $response->getBody(), true);
			case 'xml':
				return simplexml_load_file((string) $response->getBody());
			default:
				throw new Exception("Invalid repsonse type");
		}
	}
}