<?php

// $CVSHeader: _freebeer/www/demo/Payment_PayflowPro.php,v 1.2 2004/03/07 17:51:33 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.


require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/Debug.php';
//require_once FREEBEER_BASE . '/lib/Random.php';
require_once FREEBEER_BASE . '/lib/Payment/PayflowPro.php';

fbDebug::setLevel(FB_DEBUG_TEXT);

echo html_header_demo('fbPayment_PayflowPro Class');

echo "<pre>";

$fields = array(
//	'login' 		=> 'harlanestate',
//	'partner'		=> 'completecampaigns',
//	'password'		=> 'oakv1lle',

	'login' 		=> 'rosssmith2',
	'partner'		=> 'VeriSign',
	'password'		=> 'rosssmith22',

#	'card_number'	=> '',
#	'amount'		=> '',
	'CVV2'			=> '223',
	'city'			=> 'SANTA MONICA',
	'description'	=> 'Product Description',
	'invoice_number'=> '12345',
	'company'		=> 'N/A',
	'country'		=> 'US',
	'email'			=> 'payflowprospam@netebb.com',
	'expiration'	=> '1205',
	'first_name'	=> 'Ross',
	'last_name'		=> 'Smith',
#	'name'			=> 'Ross Smith',
	'state'			=> 'CA',
	'address'		=> '832 EUCLID ST 107',
	'zip'			=> '90403-1735',
);
$p =& new fbPayment_PayflowPro();
$p->setTestMode(true);
$p->setFields($fields);
$p->setTestCardType(FB_PAYMENT_CREDIT_CARD_TYPE_VISA);
$p->setTestResponseType(FB_PAYMENT_RESPONSE_TYPE_APPROVED);
$p->setTestTransactionType(FB_PAYMENT_TRANSACTION_TYPE_SALE);
$p->submit();
$p->printResults();

?>
