<?php

// $CVSHeader: _freebeer/lib/Payment/AuthorizeNet.php,v 1.2 2004/03/07 17:51:22 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

// Copyright (c) 2001-2003, Bold New World, Inc. (http://bnw.com/) All rights reserved.  Used by permission.

/*!
	\file Payment/AuthorizeNet.php
	\brief online payment processing via AuthorizeNet	
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(__FILE__))));

require_once FREEBEER_BASE . '/lib/System.php';	// putenv
require_once FREEBEER_BASE . '/lib/Payment.php';
// require_once FREEBEER_BASE . '/lib/Payment/Constants.php';

/// \todo Read FB_PAYMENT_AUTHORIZE_NET_SERVER from /etc/freebeer.ini or php.ini
defined('FB_PAYMENT_AUTHORIZE_NET_SERVER') ||
 define('FB_PAYMENT_AUTHORIZE_NET_SERVER', 			'payflow.verisign.com');

/// \todo Read FB_PAYMENT_AUTHORIZE_NET_TEST_SERVER from /etc/freebeer.ini or php.ini
defined('FB_PAYMENT_AUTHORIZE_NET_TEST_SERVER') ||
 define('FB_PAYMENT_AUTHORIZE_NET_TEST_SERVER',		'test-payflow.verisign.com');

define('FB_PAYMENT_AUTHORIZE_NET_NO_RESULT', -999);

/*!
	\class fbPayment_AuthorizeNet
	\brief online payment processing via AuthorizeNet	
*/
class fbPayment_AuthorizeNet extends fbPayment {
	/*!
		\return \c array
	*/
	function resultMessages() {
		static $result_messages = array(
			FB_PAYMENT_AUTHORIZE_NET_NO_RESULT	=> 'No RESULT',
		);
		return $result_messages;
	}

	/*! 
		Constructor
	*/
	function fbPayment_AuthorizeNet() {
		if (!fbSystem::loadExtension('curl')) {
			trigger_error('Payment::AuthorizeNet requires the \'curl\' extension', E_USER_ERROR);
			return;
		}
	
		$this->_server = FB_PAYMENT_AUTHORIZE_NET_SERVER;
		$this->_port = 443;
	}

	/*!
		Most processors provide a test mode, where submitted transactions will not actually be charged or added to your batch, calling this function with a true argument will turn that mode on if the processor supports it, or generate a fatal error if the processor does not support a test mode (which is probably better than accidentally making real charges).
	*/
	function setTestMode($test_mode = true) {
		parent::setTestMode($test_mode);

		$this->_server = $test_mode
			? FB_PAYMENT_AUTHORIZE_NET_TEST_SERVER
			: FB_PAYMENT_AUTHORIZE_NET_SERVER;
	}

	/*!	
		Submit the transaction to the processor for completion.
	*/
	function submit() {
		static $parameter_map = array(
			'ACCT'			=> 'card_number',
			'AMT'			=> 'amount',
			'AUTHCODE'		=> 'auth_code',
			'CITY'			=> 'city',
			'COMMENT1'		=> 'description',
			'COMMENT2'		=> 'invoice_number',
			'COMPANYNAME'	=> 'company',
			'COUNTRY'		=> 'country',
			'EMAIL'			=> 'email',
			'EXPDATE'		=> 'expiration',
			'FIRSTNAME'		=> 'first_name',
			'LASTNAME'		=> 'last_name',
			'NAME'			=> 'name',
			//'ORIGID'		=> 'original_id',
			'PARTNER'		=> 'partner',
			'PONUM'			=> 'po_number',
			'DESC'			=> 'description',
			'DESC1'			=> 'description1',
			'DESC2'			=> 'description2',
			'DESC3'			=> 'description3',
			'DESC4'			=> 'description4',
			'PWD'			=> 'password',
			'STATE'			=> 'state',
			'STREET'		=> 'address',	//	careful!
//			'TENDER'		=> 'type',
//			'TRXTYPE'		=> 'action',
			'USER'			=> 'login',
			'VENDOR'		=> 'login',
			'ZIP'			=> 'zip',
			'MICR'			=> 'micr',
			'CHKNUM'		=> 'check_number',
//	license_num:	Customer's driver's license number.
//	license_state:	Customer's driver's license state.
		);

		static $required_fields = array(
			'AMT',		// amount
			'USER', 	// login
			'VENDOR', 	// login
			'PARTNER', 	// partner
			'PWD', 		// password
			'TRXTYPE',	// action
			'TENDER',	// type
		/// \todo use $required_fields_cc instead		
			'ACCT', 	// card_number
			'EXPDATE',	// expiration
		);

		/// \todo implement:
		static $required_fields_cc = array(
			'ACCT', 	// card_number
			'EXPDATE',	// expiration
		);
	
		static $required_fields_echeck = array(
			'CHKNUM',	// check_number
			'MICR',		// micr
			'NAME',		// name
			'DL',		// license_state + license_num
			'CITY',		// city
			'EMAIL',	// email
			'STATE',	// state
			'STREET',	// address
			'ZIP',		// zip
		);

		static $required_fields_ach = array(
			'ABA',		// routing_code
			'ACCT',		// account_number
			'ACCTTYPE',	// account_type
			'NAME',		// name
		);
					
		$fields = $this->_fields;

//fbDebug::dump($fields, 	'$fields');

		if ($this->_test_mode) {
			if ($this->_test_card_type) {
				switch ($this->_test_card_type) {
					case FB_PAYMENT_CREDIT_CARD_TYPE_VISA:
						switch ($this->_test_response_type) {
							case FB_PAYMENT_RESPONSE_TYPE_APPROVED:
								$fields['card_number'] = '4111111111111111';
								break;
							default:
								$fields['card_number'] = '4242424242424242'; // always declines
								break;
						}
						break;
						
					case FB_PAYMENT_CREDIT_CARD_TYPE_MASTERCARD:
						$fields['card_number'] = '5105105105105100';
						//$fields['card_number'] = '5555555555551111'; // Invalid account number
						break;
						
					case FB_PAYMENT_CREDIT_CARD_TYPE_AMERICAN_EXPRESS:
						$fields['card_number'] = '378282246310005';
						break;
						
					case FB_PAYMENT_CREDIT_CARD_TYPE_DISCOVER:
						$fields['card_number'] = '6011111111111117';
						break;
					
					default:
						trigger_error(sprintf("Invalid card type: '%s'", $this->_test_card_type));
						return false;
				}
			}

			if (!isset($fields['expiration']) || !$fields['expiration']) {
				$fields['expiration'] = strftime('%m%y', time());
			}

			if ($this->_test_response_type) {
				switch ($this->_test_response_type) {
					case FB_PAYMENT_RESPONSE_TYPE_APPROVED:
						$fields['amount'] = 1.00;
						break;
					case FB_PAYMENT_RESPONSE_TYPE_DECLINED:
						$fields['amount'] = 1000.01;
						break;
					case FB_PAYMENT_RESPONSE_TYPE_REFERRAL:
						$fields['amount'] = 100.01;
						break;
					case FB_PAYMENT_RESPONSE_TYPE_BAD_EXPIRATION:
						$fields['expiration'] = '9999';
						break;
					default:
						trigger_error(sprintf("Invalid response type: '%s'", $this->_test_response_type));
						return false;
				}
			}
			
			if ($this->_test_transaction_type && (!isset($this->_fields['action']) ||
				!$this->_fields['action'])) {
				$this->_fields['action'] = $this->_test_transaction_type;
			}

		} // if ($this->_test_mode)

		if (isset($fields['expiration']) && is_int($fields['expiration'])) {
			// assume numeric expiration's are a UNIX timestamp
			// convert UNIX timestamp to MMYY
			$fields['expiration'] = strftime('%m%y', $fields['expiration']);
		}

		$parameter_flipped = array_flip($parameter_map);
		
		$parameters = array();
		$fields = $fields;
		
		foreach($fields as $key => $value) {
			if (array_key_exists($key, $parameter_flipped)) {
				$key = $parameter_flipped[$key];
			}
			
			$parameters[$key] = $value;
		}

		foreach($parameter_map as $key => $value) {
			if (array_key_exists($value, $fields)) {
				$parameters[$key] = $fields[$value];
			}
		}

		if (isset($fields['type']) && $fields['type']) {
			switch ($fields['type']) {
				case FB_PAYMENT_METHOD_TYPE_CREDIT_CARD:
					$parameters['TENDER'] = 'C';
					break;
					
				case FB_PAYMENT_METHOD_TYPE_ELECTRONIC_CHECK:
					$parameters['TENDER'] = 'K';
					break;
					
				case FB_PAYMENT_METHOD_TYPE_ACH_TRANSACTION:
					$parameters['TENDER'] = 'A';
					break;
					
				default:
					trigger_error(sprintf("Unknown payment method type: '%s'", $fields['type']));
					return false;
			}
		}

		if (isset($fields['action']) && $fields['action']) {
			switch ($fields['action']) {
				case FB_PAYMENT_TRANSACTION_TYPE_SALE:
					$parameters['TRXTYPE'] = 'S';
					break;
					
				case FB_PAYMENT_TRANSACTION_TYPE_AUTHORIZATION:
					$parameters['TRXTYPE'] = 'A';
					break;
					
				case FB_PAYMENT_TRANSACTION_TYPE_CREDIT:
					$parameters['TRXTYPE'] = 'C';
					break;
					
				case FB_PAYMENT_TRANSACTION_TYPE_POST_AUTHORIZATION:
					$parameters['TRXTYPE'] = 'D'; // Delayed Capture
					break;
					
				case FB_PAYMENT_TRANSACTION_TYPE_POST_VOID:
					$parameters['TRXTYPE'] = 'V';
					break;
					
				case FB_PAYMENT_TRANSACTION_TYPE_INQUIRY:
					$parameters['TRXTYPE'] = 'I';
					break;
					
				default:
					trigger_error(sprintf("Unknown payment method type: '%s'", $fields['type']));
					return false;
			}
		}

		unset($parameters['type']);
		unset($parameters['action']);

		if (!isset($parameters['PARTNER']) || !$parameters['PARTNER']) {
			$parameters['PARTNER'] = 'Verisign';
		}

		if (!isset($parameters['TRXTYPE']) || !$parameters['TRXTYPE']) {
			$parameters['TRXTYPE'] = 'S';	// Sale
		}
		
		if (!isset($parameters['TENDER']) || !$parameters['TENDER']) {
			$parameters['TENDER'] = 'C';	// Credit card
		}

		// check for all required fields
		$errors = '';
		foreach($required_fields as $required_field) {
			if (!isset($parameters[$required_field]) || !$parameters[$required_field]) {
				$errors .= sprintf("Required field '%s' ('%s') not found in field list.\n", 
					$parameter_map[$required_field],
					$required_field);
			}
		}
		if ($errors) {

//fbDebug::dump($fields, 	'$fields');
//fbDebug::dump($parameters, '$parameters');

			trigger_error($errors);
			return false;
		}

		$parameters['AMT'] = sprintf("%.2f", floatval($parameters['AMT']));

//fbDebug::dump($parameters, '$parameters');
		
		if ($this->proxy_password	and strlen($this->proxy_password)) {
			$response = pfpro_process($parameters, $this->_server, $this->_port, $this->timeout, $this->proxy, $this->proxy_port, $this->proxy_user, $this->proxy_password);
		} elseif ($this->proxy_user	and strlen($this->proxy_user)) {
			$response = pfpro_process($parameters, $this->_server, $this->_port, $this->timeout, $this->proxy, $this->proxy_port, $this->proxy_user);
		} elseif ($this->proxy_port	and strlen($this->proxy_port)) {
			$response = pfpro_process($parameters, $this->_server, $this->_port, $this->timeout, $this->proxy, $this->proxy_port);
		} elseif ($this->proxy		and strlen($this->proxy)) {
			$response = pfpro_process($parameters, $this->_server, $this->_port, $this->timeout, $this->proxy);
		} elseif ($this->timeout		and strlen($this->timeout)) {
			$response = pfpro_process($parameters, $this->_server, $this->_port, $this->timeout);
		} elseif ($this->_port		and strlen($this->_port)) {
			$response = pfpro_process($parameters, $this->_server, $this->_port);
		} elseif ($this->_server		and strlen($this->_server)) {
			$response = pfpro_process($parameters, $this->_server);
		} else {
			$response = pfpro_process($parameters);
		}

//fbDebug::dump($response, 	'$response');

		$this->_response = $response;
		
		if (!$response) {
			trigger_error('No response received from credit card payment processor!', E_USER_WARNING);
			return false;
		}

		if (!is_array($response)) {
			trigger_error(sprintf('Invalid response received from credit card payment processor: \'%s\'', $response), E_USER_WARNING);
			return false;
		}

		$this->_transaction_id	= isset($response['PNREF']) ? $response['PNREF'] : '';

		$this->_result_code		= isset($response['RESULT'])
			? intval($response['RESULT'])
			: FB_PAYMENT_AUTHORIZE_NET_NO_RESULT;

		$this->_approved = $this->_result_code == 0;
		$this->_declined = $this->_result_code == 12; // 'Declined. Check the credit card number and transaction information to make sure they were entered correctly. If this does not resolve the problem, have the customer call the credit card issuer to resolve.',

		if (isset($response['RESPMSG'])) {
			$this->_result_message	= $response['RESPMSG'];
		} else {
			$result_messages = $this->result_messages();

			$this->_result_message	= 
				isset($result_messages[$this->_result_code]) 
				? $result_messages[$this->_result_code]
				: sprintf('Unknown error %d', $this->_result_code);
		}

		$this->_authorization	= isset($response['AUTHCODE'])
			? $response['AUTHCODE']
			: '';

		$this->_avs_address	= isset($response['AVSADDR'])
			? $response['AVSADDR']
			: '';

		$this->_avs_zip	= isset($response['AVSZIP'])
			? $response['AVSZIP']
			: '';

		return true;
	}

}

?>
