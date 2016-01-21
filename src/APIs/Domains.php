<?php

namespace arleslie\ResellerClub\APIs;

class Domains {
	use \arleslie\ResellerClub\Helper;

	protected $api = 'domains';

	public function available($slds, $tlds = ['com', 'org', 'net'])
	{
		return $this->get('available', [
			'domain-name' => $slds,
			'tlds' => $tlds
		]);
	}

	public function idnAvailable($slds, $tld, $languageCode)
	{
		return $this->get('idn-available', [
			'domain-name' => $slds,
			'tld' => $tld,
			'idnLanguageCode' => $languageCode
		]);
	}

	public function premiumAvailable($keyword, $tlds, $results = 10, $priceHigh = 999999999, $priceLow = 0)
	{
		return $this->get(
			'available',
			[
				'key-word' => $keyword,
				'tlds' => $tlds,
				'no-of-results' => $results,
				'price-high' => $priceHigh,
				'price-low' => $priceLow
			],
			'premium/'
		);
	}

	public function ukAvailable($domain, $name, $company, $email, $address1, $city, $state, $zipcode, $country, $address2 = '', $address3 = '', $phonecc = '', $phone = '')
	{
		return $this->get(
			'available',
			[
				'domain-name' => $domain,
				'name' => $name,
				'company' => $company,
				'email' => $email,
				'address-line-1' => $address1,
				'city' => $city,
				'state' => $state,
				'zipcode' => $zipcode,
				'country' => $country,
				'address-line-2' => $address2,
				'address-line-3' => $address3,
				'phone-cc' => $phonecc,
				'phone' => $phone
			],
			'uk/'
		);
	}

	public function suggestNames($keyword, $tld = '', $exactMatch = false)
	{
		return $this->get(
			'suggest-names',
			[
				'keyword' => $keyword,
				'tld' => $tld,
				'exact-match' => $exactMatch
			],
			'v5/'
		);
	}

	public function register($domain, $years, $ns, $customer, $reg, $admin, $tech, $billing, $invoice, $purchasePrivacy = false, $protectPrivacy = false, $additional = [])
	{
		// For idn codes, tm-claim, and pre registrations.
		$attr = []; $i = 0;
		foreach ($additional as $key => $value) {
			$i++;

			$attr[] = [
				"attr-name{$i}" => $key,
				"attr-value{$i}" => $value
			];
		}

		return $this->get(
			'register',
			[
				'domain-name' => $domain,
				'years' => $years,
				'ns' => $ns,
				'customer-id' => $customer,
				'reg-contact-id' => $reg,
				'admin-contact-id' => $admin,
				'tech-contact-id' => $tech,
				'billing-contact-id' => $billing,
				'invoice-option' => $invoice, // Options: NoInvoice, PayInvoice, KeepInvoice
				'purchase-privacy' => $purchasePrivacy,
				'protect-privacy' => $protectPrivacy,
			] + $attr
		);
	}

	public function transfer($domain, $customer, $reg, $admin, $tech, $billing, $invoice, $code, $ns = [], $purchasePrivacy = false, $protectPrivacy = false, $additional = [])
	{
		// For premium transfers and .asia tld
		$attr = []; $i = 0;
		foreach ($additional as $key => $value) {
			$i++;

			$attr[] = [
				"attr-name{$i}" => $key,
				"attr-value{$i}" => $value
			];
		}

		return $this->get(
			'register',
			[
				'domain-name' => $domain,
				'auth-code' => $code,
				'ns' => $ns,
				'customer-id' => $customer,
				'reg-contact-id' => $reg,
				'admin-contact-id' => $admin,
				'tech-contact-id' => $tech,
				'billing-contact-id' => $billing,
				'invoice-option' => $invoice, // Options: NoInvoice, PayInvoice, KeepInvoice
				'purchase-privacy' => $purchasePrivacy,
				'protect-privacy' => $protectPrivacy,
			] + $attr
		);
	}

	public function submitAuthCode($orderId, $code)
	{
		return $this->get(
			'submit-auth-code',
			[
				'order-id' => $orderId,
				'auth-code' => $code
			],
			'transfers/'
		);
	}

	public function validateTransfer($domain)
	{
		return $this->get('validate-transfer', [
			'domain-name' => $domain
		]);
	}

	public function renew($orderId, $years, $exp, $purchasePrivacy, $invoice)
	{
		return $this->get('renew', [
			'order-id' => $orderId,
			'years' => $years,
			'exp-date' => strtotime($exp),
			'purchase-privacy' => $purchasePrivacy,
			'invoice-option' => $invoice // Options: NoInvoice, PayInvoice, KeepInvoice, OnlyAdd
		]);
	}

	public function search(
		$records = 10,
		$page = 0, // this might need to be 1 but API docs are lacking that information
		$order = [],
		$orderIds = [],
		$resellers = [],
		$customers = [],
		$showChild = false,
		$productKeys = [],
		$statuses = [],
		$domain = '',
		$privacy = '',
		$createdStart = '',
		$createdEnd = '',
		$expireStart = '',
		$expireEnd = ''
	) {
		$dates = [];
		if (!empty($createdStart)) {
			$dates['creation-date-start'] = strtotime($createdStart);
		}

		if (!empty($createdEnd)) {
			$dates['creation-date-end'] = strtotime($createdEnd);
		}

		if (!empty($expireStart)) {
			$dates['expiry-date-start'] = strtotime($expireStart);
		}

		if (!empty($expireEnd)) {
			$dates['expiry-date-end'] = strtotime($expireEnd);
		}

		return $this->get(
			'search',
			[
				'no-of-results' => $records,
				'page-no' => $page,
				'order-by' => $order,
				'order-id' => $orderIds,
				'reseller-id' => $resellers,
				'customer-id' => $customers,
				'show-child-orders' => $showChild,
				'product-key' => $productKeys,
				'status' => $statues, // InActive, Active, Suspended, Pending Delete Restorable, Deleted, Archived, Pending Verification, Failed Verification
				'domain-name' => $domain,
				'privacy-enabled' => $privacy // true, false, na
			] + $dates
		);
	}

	public function getDefaultNameservers($customerId)
	{
		return $this->get('customer-default-ns', ['customer-id' => $customerId]);
	}

	public function getOrderId($domain)
	{
		return $this->get('orderid', ['domain-name' => $domain]);
	}

	public function getDetailsByOrderId($orderId, $options = ['All'])
	{
		return $this->get('details', [
			'order-id' => $orderId,
			'options' => $options // All, OrderDetails, ContactIds, RegistrantContactDetails, AdminContactDetails, TechContactDetails, BillingContactDetails, NsDetails, DoaminStatus, DNSSECDetails, StatusDetails
		]);
	}

	public function getDetailsByDomain($doamin, $options = ['All'])
	{
		return $this->get('details-by-name', [
			'domain-name' => $doamin,
			'options' => $options // All, OrderDetails, ContactIds, RegistrantContactDetails, AdminContactDetails, TechContactDetails, BillingContactDetails, NsDetails, DoaminStatus, DNSSECDetails, StatusDetails
		]);
	}

	public function modifyNameServers($orderId, $ns)
	{
		return $this->get('modify-ns', ['order-id' => $orderId, 'ns' => $ns]);
	}

	public function addChildNameServer($orderId, $cns, $ip)
	{
		return $this->get('add-cns', [
			'order-id' => $orderId,
			'cns' => $ns,
			'ip' => $ip
		]);
	}

	public function renameChildNameServer($orderId, $oldcns, $newcns)
	{
		return $this->get('modify-cns-name', [
			'order-id' => $orderId,
			'old-cns' => $oldcns,
			'new-cns' => $newcns
		]);
	}

	public function modifyChildNameServer($orderId, $cns, $oldIP, $newIP)
	{
		return $this->get('modify-cns-ip', [
			'order-id' => $orderId,
			'cns' => $cns,
			'old-ip' => $oldIP,
			'new-ip' => $newIP
		]);
	}
}