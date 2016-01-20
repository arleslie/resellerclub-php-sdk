<?php

namespace arleslie\ResellerClub\APIs;

class Domains {
	use \arleslie\ResellerClub\Helper;

	public function available($slds, $tlds = ['com', 'org', 'net'])
	{
		return $this->get('available', [
			'domain-name' => $slds,
			'tlds' => $tlds
		]);
	}
}