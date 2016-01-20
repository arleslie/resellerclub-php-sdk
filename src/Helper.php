<?php

namespace arleslie\ResellerClub;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Message\Response;

class Helper
{
	protected $guzzle;

	public function __construct(Guzzle $guzzle)
	{
		$this->guzzle = $guzzle;
	}

	protected function get($method, $args)
	{
		return $this->parse(
			$this->client->get($method.'.json?'.http_build_query($args))
		);
	}

	protected function post($method, $args)
	{
		return $this->parse(
			$this->client->post($method.'.json', $args)
		);
	}

	protected function parse(Response $response)
	{
		return json_encode((string) $response->getBody(), true);
	}
}