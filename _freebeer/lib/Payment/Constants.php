<?php

// $CVSHeader: _freebeer/lib/Payment/Constants.php,v 1.2 2004/03/07 17:51:22 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

// Copyright (c) 2001-2003, Bold New World, Inc. (http://bnw.com/) All rights reserved.  Used by permission.

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(__FILE__))));

/*!

fbPayment_Constants

\author		Ross Smith
\ingroup	commerce

*/

require_once FREEBEER_BASE . '/lib/Payment.php';

defined('FB_PAYMENT_METHOD_TYPE_CASH') ||
 define('FB_PAYMENT_METHOD_TYPE_CASH',				4);

defined('FB_PAYMENT_METHOD_TYPE_CHECK') ||
 define('FB_PAYMENT_METHOD_TYPE_CHECK',				5);

# pseudo payment method type!
defined('FB_PAYMENT_METHOD_TYPE_ON_ACCOUNT') ||
 define('FB_PAYMENT_METHOD_TYPE_ON_ACCOUNT',		6);

define('FB_PAYMENT_STATUS_TYPE_AWAITING_AUTHORIZATION', 1);
define('FB_PAYMENT_STATUS_TYPE_AWAITING_APPROVAL',		2);
define('FB_PAYMENT_STATUS_TYPE_APPROVED',				3);
define('FB_PAYMENT_STATUS_TYPE_DECLINED',				4);

/*!
	\static
*/
class fbPayment_Constants {
	/*!
		\static
	*/
	function creditCardTypes($i = false) {
		static $PAYMENT_CREDIT_CARD_TYPES = array(
			FB_PAYMENT_CREDIT_CARD_TYPE_VISA			=> 'Visa',
			FB_PAYMENT_CREDIT_CARD_TYPE_MASTERCARD		=> 'Mastercard',
			FB_PAYMENT_CREDIT_CARD_TYPE_AMERICAN_EXPRESS=> 'American Express',
			FB_PAYMENT_CREDIT_CARD_TYPE_DISCOVER		=> 'Discover',
			FB_PAYMENT_CREDIT_CARD_TYPE_DINERS_CLUB		=> 'Diner\'s Club',
			FB_PAYMENT_CREDIT_CARD_TYPE_CARTE_BLANCHE	=> 'Carte Blanche',
			FB_PAYMENT_CREDIT_CARD_TYPE_JAPAN_CARD		=> 'Japan Card',
			FB_PAYMENT_CREDIT_CARD_TYPE_ENROUTE			=> 'Enroute',
		);

		if ($i !== false) {
			assert('isset($PAYMENT_CREDIT_CARD_TYPES[$i])');
		}
			
		return $i !== false ? $PAYMENT_CREDIT_CARD_TYPES[$i] : $PAYMENT_CREDIT_CARD_TYPES;
	}
	
	/*!
		\static
	*/
	function methodTypes($i = false) {
		static $PAYMENT_METHOD_TYPES = array(
			FB_PAYMENT_METHOD_TYPE_CREDIT_CARD		=> 'Credit Card',
			FB_PAYMENT_METHOD_TYPE_ELECTRONIC_CHECK	=> 'Electronic Check',
			FB_PAYMENT_METHOD_TYPE_ACH_TRANSACTION	=> 'ACH Transaction',
			FB_PAYMENT_METHOD_TYPE_CASH				=> 'Cash',
			FB_PAYMENT_METHOD_TYPE_CHECK			=> 'Check',
			FB_PAYMENT_METHOD_TYPE_ON_ACCOUNT		=> 'Account',
		);

		if ($i !== false) {
			assert('isset($PAYMENT_METHOD_TYPES[$i])');
		}
		
		return $i !== false ? $PAYMENT_METHOD_TYPES[$i] : $PAYMENT_METHOD_TYPES;
	}

	/*!
		\static
	*/
	function responseTypes($i = false) {
		static $PAYMENT_RESPONSE_TYPES = array(
			FB_PAYMENT_RESPONSE_TYPE_APPROVED			=> 'Approved',
			FB_PAYMENT_RESPONSE_TYPE_DECLINED			=> 'Declined',
			FB_PAYMENT_RESPONSE_TYPE_REFERRAL			=> 'Referral',
			FB_PAYMENT_RESPONSE_TYPE_BAD_EXPIRATION		=> 'Bad Expiration',
		);

		if ($i !== false) {
			assert('isset($PAYMENT_RESPONSE_TYPES[$i])');
		}

		return $i !== false ? $PAYMENT_RESPONSE_TYPES[$i] : $PAYMENT_RESPONSE_TYPES;
	}

	/*!
		\static
	*/
	function statusTypes($i = false) {
		static $PAYMENT_STATUS_TYPES = array(
			FB_PAYMENT_STATUS_TYPE_AWAITING_AUTHORIZATION	=> 'Awaiting Authorization',
			FB_PAYMENT_STATUS_TYPE_AWAITING_APPROVAL		=> 'Awaiting Approval',
			FB_PAYMENT_STATUS_TYPE_APPROVED					=> 'Approved',
			FB_PAYMENT_STATUS_TYPE_DECLINED					=> 'Declined',
		);

		if ($i !== false) {
			assert('isset($PAYMENT_STATUS_TYPES[$i])');
		}

		return $i !== false ? $PAYMENT_STATUS_TYPES[$i] : $PAYMENT_STATUS_TYPES;
	}

	/*!
		\static
	*/
	function transactionTypes($i = false) {
		static $PAYMENT_TRANSACTION_TYPES = array(
			FB_PAYMENT_TRANSACTION_TYPE_SALE					=> 'Sale',
			FB_PAYMENT_TRANSACTION_TYPE_AUTHORIZATION			=> 'Authorization',
			FB_PAYMENT_TRANSACTION_TYPE_CREDIT					=> 'Credit',
			FB_PAYMENT_TRANSACTION_TYPE_POST_AUTHORIZATION		=> 'Post Authorization',
			FB_PAYMENT_TRANSACTION_TYPE_VOID					=> 'Void',
			FB_PAYMENT_TRANSACTION_TYPE_INQUIRY					=> 'Inquiry',
		);

		if ($i !== false) {
			assert('isset($PAYMENT_TRANSACTION_TYPES[$i])');
		}

		return $i !== false ? $PAYMENT_TRANSACTION_TYPES[$i] : $PAYMENT_TRANSACTION_TYPES;
	}

} # class fbPayment_Constants

?>
