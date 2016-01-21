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

	protected function get($method, $args)
	{
		return $this->parse(
			$this->guzzle->get(
				$this->api .'/'. $method.'.json?'.str_replace(['%5B0','%5D'], '', http_build_query(array_merge($args, $this->creds)))
			)
		);
	}

	protected function post($method, $args)
	{
		return $this->parse(
			$this->guzzle->post($method.'.json', $args)
		);
	}

	protected function parse(Response $response)
	{
		return json_decode((string) $response->getBody(), true);
	}
}