<?php

// $CVSHeader: _freebeer/lib/Payment.php,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

// Copyright (c) 2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

// Copyright (c) 2001-2003, Bold New World, Inc. (http://bnw.com/) All rights reserved.  Used by permission.

/**
	\file Payment.php
	\brief Abstract class for online payment processing	
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

define('FB_PAYMENT_METHOD_TYPE_CREDIT_CARD',        1);
define('FB_PAYMENT_METHOD_TYPE_ELECTRONIC_CHECK',   2);
define('FB_PAYMENT_METHOD_TYPE_ACH_TRANSACTION',    3);
define('FB_PAYMENT_METHOD_TYPE_CASH',               4);
define('FB_PAYMENT_METHOD_TYPE_CHECK',              5);

define('FB_PAYMENT_CREDIT_CARD_TYPE_VISA',              1);
define('FB_PAYMENT_CREDIT_CARD_TYPE_MASTERCARD',        2);
define('FB_PAYMENT_CREDIT_CARD_TYPE_AMERICAN_EXPRESS',  3);
define('FB_PAYMENT_CREDIT_CARD_TYPE_DISCOVER',          4); // aka Novus
define('FB_PAYMENT_CREDIT_CARD_TYPE_DINERS_CLUB',       5);
define('FB_PAYMENT_CREDIT_CARD_TYPE_CARTE_BLANCHE',     6);
define('FB_PAYMENT_CREDIT_CARD_TYPE_JAPAN_CARD',        7);
define('FB_PAYMENT_CREDIT_CARD_TYPE_ENROUTE',           8);

define('FB_PAYMENT_TRANSACTION_TYPE_SALE',                  1);
define('FB_PAYMENT_TRANSACTION_TYPE_AUTHORIZATION',         2); // aka PRE-AUTHORIZED
define('FB_PAYMENT_TRANSACTION_TYPE_CREDIT',                3);
define('FB_PAYMENT_TRANSACTION_TYPE_POST_AUTHORIZATION',    4);
define('FB_PAYMENT_TRANSACTION_TYPE_VOID',                  5);
define('FB_PAYMENT_TRANSACTION_TYPE_INQUIRY',               6);

define('FB_PAYMENT_RESPONSE_TYPE_APPROVED',         1);
define('FB_PAYMENT_RESPONSE_TYPE_DECLINED',         2);
define('FB_PAYMENT_RESPONSE_TYPE_REFERRAL',         3);
define('FB_PAYMENT_RESPONSE_TYPE_BAD_EXPIRATION',   4);

/**
	\class fbPayment
	\brief Abstract class for online payment processing	

	fbPayment is a virtual class for processing payments through
	online credit card processors, electronic cash systems, etc.

field keys:

	type:			Transaction type:
						FB_PAYMENT_METHOD_TYPE_CREDIT_CARD:			Credit Card
						FB_PAYMENT_METHOD_TYPE_ELECTRONIC_CHECK:	Electronic Check
						FB_PAYMENT_METHOD_TYPE_ACH_TRANSACTION:		ACH Transaction
					(Not all processors support all these transaction types).
					
	card_type:		Credit card type:
						FB_PAYMENT_CREDIT_CARD_VISA:				Visa
						FB_PAYMENT_CREDIT_CARD_MASTERCARD:			Mastercard
						FB_PAYMENT_CREDIT_CARD_AMERICAN_EXPRESS:	American Express
						FB_PAYMENT_CREDIT_CARD_DISCOVER:			Discover/Novus
						FB_PAYMENT_CREDIT_CARD_DINERS_CLUB:			Diner's Club
						FB_PAYMENT_CREDIT_CARD_CARTE_BLANCHE:		Carte Blanche
						FB_PAYMENT_CREDIT_CARD_JAPAN_CARD:			Japan Card
						FB_PAYMENT_CREDIT_CARD_ENROUTE:				Enroute
					(Not all processors support all these card types).

	login:			Your login name to use for authentication to the online processor.

	password:		Your password to use for authentication to the online processor.

	action:			What to do with the transaction:
						FB_PAYMENT_TRANSACTION_TYPE_SALE:					Normal sale/authorization
						FB_PAYMENT_TRANSACTION_TYPE_AUTHORIZATION:			"Pre" authorization
						FB_PAYMENT_TRANSACTION_TYPE_CREDIT:					Credit/Refund
						FB_PAYMENT_TRANSACTION_TYPE_POST_AUTHORIZATION:		"Post" authorization
						FB_PAYMENT_TRANSACTION_TYPE_VOID:					Void
						FB_PAYMENT_TRANSACTION_TYPE_INQUIRY:				Inquiry

	card_number:	Credit card number (credit card transactions only).

	expiration:		Credit card expiration (credit card transactions only).

    cvv2:			The CVV2 code (the 3 or 4 digit number found on the back of the credit card).
					(credit card transactions only) (Not normally required).

	description:	A description of the transaction
					(used by some processors to send information to the client)
					(Not normally required).

	amount:			The amount of the transaction, most processors dont want dollar signs and the like,
					just a floating point number.

	first_name:		The customer's first name, your processor may not require this.

	last_name:		The customer's last name, your processor may not require this.

	address:		The customer's address
					(your processor may not require this unless you are requiring AVS Verification).

	city:			The customer's city
					(your processor may not require this unless you are requiring AVS Verification).

	state:			The customer's state
					(your processor may not require this unless you are requiring AVS Verification).

	zip:			The customer's zip code
					(your processor may not require this unless you are requiring AVS Verification).

	country:		Customer's country.

	phone:			Customer's phone number.

	fax:			Customer's fax number.

	email:			Customer's email address.

	invoice_number:	An invoice number, for your use and not normally required, 
					many processors require this field to be a numeric only field.
					(Not normally required).

	customer_id:	A customer identifier. (Not normally required).

	
    check_type:		'E'-Check type (electronic checks or electronic funds transfer only).
    
	account_number:	Bank account number (electronic checks or electronic funds transfer only).

	routing_code:	Bank's ABA routing code (electronic checks or electronic funds transfer only).

	bank_name:		Bank's name (electronic checks or electronic funds transfer only).

	account_name:	Bank account name (electronic checks or electronic funds transfer only).

	account_type:	Bank account type (electronic checks or electronic funds transfer only).
					C = Checking, S = Savings
					
    micr:			Check MICR code (electronic checks or electronic funds transfer only).

	check_number:	Check Number (electronic checks or electronic funds transfer only).


	customer_org:	Customer's organization type.

	customer_ssn:	Customer's Social Security # or Tax ID #.

	license_num:	Customer's driver's license number.

	license_state:	Customer's driver's license state.

	license_dob:	Customer's driver's license date of birth.


	auth_code:		Authorization code.
	

	merchant_id:	Merchant ID (Not normally required) (BofA).

	ioc_indicator:	IOC indicator (Not normally required) (BofA).

	card_owner:		The credit card's "owner". (Not normally required) (Beanstream).
	
	issue:			The issue (Not normally required) (Cardstream).
	
	partner:		The partner (Not normally required) (PayflowPro).
	
    quantity:		The quantity of the item of the order (Not normally required) (SurePay).

    sku_number:		The SKU of the item (Not normally required) (SurePay).

    tax_rate:		The tax rate of the item (Not normally required) (SurePay).

American Express

INVNUM				Merchant invoice number. (Also 
					referred to as Supplier Reference Num-
					ber). This generated reference number 
					appears on merchant's bank reconcili-
					ation statement. Acquirer decides if this 
					information will appear on the state-
					ment. PNREF numeric digits are the 
					default.
					Required: No
					Numeric 9

SHIPTOZIP			Ship to Zip code. 
					Required: No (but provides best rate when used)
					Alphanumeric 6
SWIPE				Allows Track 1 and Track 2 data to be passed to enable a card-present transaction.
					Required: No
					Alphanumeric 80

TAXAMT				Tax Amount. Do not include comma separators. 
					Use 1199.95 instead of 1,199.95.
					Required: No (but provides best rate when used).
					Currency 10

	po_number

	description

	description1

	description2

	description3

	description4
	
	
City
	Cardholder?s billing city No Alpha 20
	Comment1 User-defined field for reporting and 
	auditing purposes (VeriSign field 
	only)
	No
	Alphanumeric 128

Comment2
	User-defined field for reporting and 
	auditing purposes (Verisign field only)
	No
	Alphanumeric 128

CompanyName
	Cardholder?s company
	No
	Alphanumeric 30

Country
	Cardholder?s billing country code
	No
	Alphanumeric 3

CUSTCODE
	Customer code
	No
	Alphanumeric 30

DUTYAMT
	Duty amount
	No
	Alphanumeric 10

Email
	Cardholder?s e-mail address
	No
	Alphanumeric 64

FirstName
	Cardholder?s first name
	No 
	Alpha 15

FREIGHTAMT
	Freight amount
	No
	Currency 10

LastName
	Cardholder?s last name
	No 
	Alpha 15

Name 
	Cardholder?s name 
	No 
	Alphanumeric 15

PONUM
	Purchase Order Number
	No 
	Alphanumeric 15

ShipToCity 
	Shipping city 
	No
	Alphanumeric 30

ShipToFirstName
	First name in the shipping address
	No 
	Alphanumeric 30

ShipToLastName
	Last name in the shipping address
	No 
	Alphanumeric 30

ShipToState
	Shipping state. 
	US = 2-letter state code. Outside 
	US, use full name.
	No
	Alphanumeric 10

ShipToStreet
	Shipping street address
	No
	Alphanumeric 30

ShipToZip 
	Shipping Zip code 
	No 
	Alphanumeric 9

State
	Cardholder?s billing state code
	No
	Alphanumeric 2

Street
	Cardholder?s billing street address 
	(used for AVS and reporting)
	No
	Alphanumeric 30

TAXAMT 
	Tax amount
	No 
	Currency 10

Zip 
	Cardholder?s billing zip code (used 
	for AVS and reporting). Can be 5 to 
	9 digits; do not include spaces or 
	non-numeric characters.
	No
	Numeric 9
	
*/

class fbPayment {
	// Curl related variables
	// for when we need to subclass the Payment_BlueGateway, or Payment_EZIC classes

	/// @todo Switch all private and use getter/setter functions
	
	/**
	 * The SSL version for the transfer
	 *
	 *	@var $sslVersion string
	 *	@access public
	 */
	var $sslVersion;

	/**
	 * The filename of the SSL certificate
	 *
	 *	@var string $sslCert
	 *	@access public
	 */
	var $sslCert;

	/**
	 * The password corresponding to the certificate
	 * in the $sslCert property
	 *
	 *	@var string $sslCertPasswd
	 *	@access public
	 */
	var $sslCertPasswd;

	/**
	 * User Agent string when making an HTTP request
	 *
	 *	@var string $userAgent
	 *	@access public
	 */
	var $userAgent;

	/**
	 * Whether or not to include the header in the results
	 * of the CURL transfer
	 *
	 *	@var boolean $header
	 *	@access public
	 */
	var $header = 1;

	/**
	 * Whether or not to output debug information while executing a
	 * curl transfer
	 *
	 *	@var boolean $verbose
	 *	@access public
	 */
	var $verbose = 0;

	/**
	 * Whether or not to display a progress meter for the current transfer
	 *
	 *	@var boolean $progress
	 *	@access public
	 */
	var $progress = 0;

	/**
	 * Whether or not to suppress error messages
	 *
	 *	@var boolean $mute
	 *	@access public
	 */
	var $mute = 1;

	/**
	 * Whether or not to follow HTTP Location headers.
	 *
	 *	@var boolean $follow_location
	 *	@access public
	 */
	var $follow_location = 0;

	/**
	 * Time allowed for current transfer, in seconds.  0 means no limit
	 *
	 *	@var int $timeout
	 *	@access public
	 */
	var $timeout;

	/**
	 * Whether or not to return the results of the
	 * current transfer
	 *
	 *	@var boolean $return_transfer
	 *	@access public
	 */
	var $return_transfer = 1;

	/**
	 * The type of transfer to perform
	 *
	 *	@var string $type
	 *	@access public
	 */
	var $type;

	/**
	 * The cookies to send to the remote site
	 *
	 *	@var array $cookies
	 *	@access public
	 */
	var $cookies;

	/**
	 * The fields to send in a 'POST' request
	 *
	 *	@var array $fields
	 *	@access public
	 */
	var $post_fields;

	/**
	 * The proxy server to go through
	 *
	 *	@var string $proxy
	 *	@access public
	 */
	var $proxy;

	/**
	 * The proxy server port 
	 *
	 *	@var string $proxy_port
	 *	@access public
	 */
	var $proxy_port;

	/**
	 * The username for the Proxy server
	 *
	 *	@var string $proxyUser
	 *	@access public
	 */
	var $proxy_user;

	/**
	 * The password for the Proxy server
	 *
	 *	@var string $proxyPassword
	 *	@access public
	 */
	var $proxy_password;

	/**
		Constructor
	*/
	function fbPayment() {
	}

	/**
		 @var hash $_fields
		
		The information necessary for the transaction, this tends to vary a little depending on the processor,
		so we have chosen to use a system which defines specific fields in the frontend which get mapped 
		to the correct fields in the backend.  The currently defined fields are: TBD.
	*/
	var $_fields = array();
	
	/**
		getFields - Retrieve the hash of the submission key/value pairs.
		
		@return hash Hash of the submission key/value pairs.
	*/
	function getFields() {
		return $this->_fields;
	}
	
	/**
		addFields - Adds a hash to the hash of the submission key/value pairs.

		@param $fields
		@return void
	*/
	function addFields($fields) {
		assert('is_array($fields)');
		$this->_fields = array_merge($this->_fields, $fields);
	}
	
	/**
		setFields - Sets the hash of the submission key/value pairs.

		@param $fields
		@return void
	*/
	function setFields($fields) {
		assert('is_array($fields)');
		$this->_fields = $fields;
	}

	var $_server = false;
	
	/**
		getServer - Retrieve the processor submission server address.
		@return string
	*/
	function getServer() {
		assert('strlen($this->_server)');
		return $this->_server;
	}
	
	/**
		setServer - Set the processor submission server address (CHANGE AT YOUR OWN RISK).
		
		@param $server
		@return void
	*/
	function setServer($server) {
		assert('strlen($server)');
		$this->_server = (string) $server;
	}
	
	var $_port = 443;
	
	/**
		getPort - Get the processor submission port
		@return int 
	*/
	function getPort() {
		return $this->_port;
	}

	/**
		setPort - Set the processor submission port (CHANGE AT YOUR OWN RISK).
		@param $port int
		@return void
	*/
	function setPort($port) {
		assert('intval($server)');
		$this->_port = (int) $port;
	}
	
	/**
	*/
	var $_path = false;
	
	/**
		getPath - Get the processor submission path.
		@return string
	*/
	function getPath() {
		assert('strlen($server)');
		return $this->_path;	
	}

	/**
		setPath - Set the processor submission path (CHANGE AT YOUR OWN RISK).
		@param $path
		@return void
	*/
	function setPath($path) {
		assert('strlen($path)');
		$this->_path = (string) $path;
	}

	/**
	*/
	var $_test_mode = false;
	
	/**
		setTestMode - Most processors provide a test mode, where submitted transactions will not actually be charged or added to your batch, calling this function with a true argument will turn that mode on if the processor supports it, or generate a fatal error if the processor does not support a test mode (which is probably better than accidentally making real charges).
		@param $test_mode
		@return void
	*/
	function setTestMode($test_mode = true) {
		$this->_test_mode			= (bool) $test_mode;
		if ($test_mode) {
#			if (!$this->_test_method_type) {
#				$this->_test_method_type	= FB_PAYMENT_METHOD_TYPE_CREDIT_CARD;
#			}
			
#			if (!$this->_test_card_type) {
#				$this->_test_card_type		= FB_PAYMENT_CREDIT_CARD_TYPE_VISA;
#			}
			
#			if (!$this->_test_response_type) {
#				$this->_test_response_type	= FB_PAYMENT_RESPONSE_TYPE_APPROVED;
#			}
		}
	}

	var $_test_method_type = false;
	
	/**
		setTestMethod -
		@param $method_type
		@return void 
	*/
	function setTestMethodType($method_type) {
		static $method_types = array(
			FB_PAYMENT_METHOD_TYPE_CREDIT_CARD,
			FB_PAYMENT_METHOD_TYPE_ELECTRONIC_CHECK,
			FB_PAYMENT_METHOD_TYPE_ACH_TRANSACTION,
			FB_PAYMENT_METHOD_TYPE_PAYPAL,
		);
		assert('in_array($method_type, $method_types)');
		$this->_test_method_type = (int) $method_type;
	}

	/**
	*/
	var $_test_card_type = false;
	
	/**
		setTestCardType -
		@param $card_type
		@return void 
	*/
	function setTestCardType($card_type) {
		static $card_types = array(
			FB_PAYMENT_CREDIT_CARD_TYPE_VISA,
			FB_PAYMENT_CREDIT_CARD_TYPE_MASTERCARD,
			FB_PAYMENT_CREDIT_CARD_TYPE_AMERICAN_EXPRESS,
			FB_PAYMENT_CREDIT_CARD_TYPE_DISCOVER,
			FB_PAYMENT_CREDIT_CARD_TYPE_DINERS_CLUB,
			FB_PAYMENT_CREDIT_CARD_TYPE_CARTE_BLANCHE,
			FB_PAYMENT_CREDIT_CARD_TYPE_JAPAN_CARD,
			FB_PAYMENT_CREDIT_CARD_TYPE_ENROUTE,
		);
		assert('in_array($card_type, $card_types)');
		$this->_test_card_type = (int) $card_type;
	}

	/**
	*/
	var $_test_response_type = false;

	/**
		setTestResponseType - 
		@return void 
	*/
	function setTestResponseType($response_type) {
		static $response_types = array(
			FB_PAYMENT_RESPONSE_TYPE_APPROVED,
			FB_PAYMENT_RESPONSE_TYPE_DECLINED,
			FB_PAYMENT_RESPONSE_TYPE_REFERRAL,
			FB_PAYMENT_RESPONSE_TYPE_BAD_EXPIRATION,
		);
		assert('in_array($response_type, $response_types)');
		$this->_test_response_type = (int) $response_type;
	}

	/**
	*/
	var $_test_transaction_type;

	/**
		setTestTransactionType - 
		@return void 
	*/
	function setTestTransactionType($transaction_type = FB_PAYMENT_TRANSACTION_TYPE_SALE) {
		static $transaction_types = array(
			FB_PAYMENT_TRANSACTION_TYPE_SALE,
			FB_PAYMENT_TRANSACTION_TYPE_AUTHORIZATION,
			FB_PAYMENT_TRANSACTION_TYPE_CREDIT,
			FB_PAYMENT_TRANSACTION_TYPE_POST_AUTHORIZATION,
			FB_PAYMENT_TRANSACTION_TYPE_VOID,
			FB_PAYMENT_TRANSACTION_TYPE_INQUIRY,
		);
		assert('in_array($transaction_type, $transaction_types)');
		$this->_test_transaction_type = (int) $transaction_type;
	}

	/**
	*/
	var $_require_avs = false;
	
	/**
		setRequireAVS - Providing a true argument to this module will turn on address verification (if the processor supports it).
		@return void 
	*/
	function setRequireAVS($require_avs = true) {
		$this->_require_avs = (bool) $require_avs;
	}

	/**	
		submit - Submit the transaction to the processor for completion.
	*/
	function submit() {
		trigger_error(sprintf('You must override %s::submit().', get_class($this)), E_USER_ERROR);
	}
	
	/**
	*/
	var $_approved;
	
	/**
		isApproved - Returns true if the transaction was approved, 
					 false if was not (or undefined if it has not been submitted yet).
					 
					 If both isApproved() and isDeclined() return false, then it is some other error.
	*/
	function isApproved() {
		return $this->_approved;
	}
	
	/**
	*/
	var $_declined;
	
	/**
		isDeclined - Returns true if the transaction was declined, 
					 false if was not declined (or undefined if it has not been submitted yet).

					 If both isApproved() and isDeclined() return false, then it is some other error.

	*/
	function isDeclined() {
		return $this->_declined;
	}
	
	/**
	*/
	var $_result_code;
	
	/**
		getResultCode - Returns the precise result code that the processor returned, these are normally one letter codes that don't mean much unless you understand the protocol they speak, you probably don't need this, but it's there just in case.
	*/
	function getResultCode() {
		return $this->_result_code;
	}

	/**
	*/
	var $_result_message;
	
	/**
		getResultMessage - If the transaction has been submitted but was not accepted, this function will return the provided error message (if any) that the processor returned.
	*/
	function getResultMessage() {
		return $this->_result_message;
	}

	/**
	*/
	var $_authorization;

	/**
		getAuthorization - If the transaction has been submitted and accepted, this function will provide you with the authorization code that the processor returned.
	*/
	function getAuthorization() {
		return $this->_authorization;
	}

	/**
	*/
	var $_avs_address;

	/**
		getAVSAddress - 
	*/
	function getAVSAddress() {
		return $this->_avs_address;
	}

	/**
	*/
	var $_avs_zip;

	/**
		getAVSZip - 
	*/
	function getAVSZip() {
		return $this->_avs_zip;
	}

	/**
	*/
	var $_response;
	
	/**
		getResponse -
	*/
	function getResponse() {
		return $this->_response;
	}

	/**
	*/
	var $_transaction_id;

	/**
		getTransactionID - Return the transaction ID, if any, returned from the credit card processor.
	*/
	function getTransactionID() {
		return $this->_transaction_id;
	}

	/**
		printResults 
	*/
	function printResults() {
		printf(
"isApproved:       '%s'
isDeclined:       '%s'
getResultCode:    '%s'
getResultMessage: '%s'
getAuthorization: '%s'
getTransactionID: '%s'
getAVSAddress:    '%s'
getAVSZip:        '%s'
",
			$this->isApproved(),
			$this->isDeclined(),
			$this->getResultCode(),
			$this->getResultMessage(),
			$this->getAuthorization(),
			$this->getTransactionID(),
			$this->getAVSAddress(),
			$this->getAVSZip()
		);

		print "getResponse:\n";
		print_r($this->getResponse());
		print "\n\n";
	}

	/**
	*/
	function getCreditCardType($credit_card) {
		switch (substr($credit_card, 0, 1)) {
			case '3':
				return FB_PAYMENT_CREDIT_CARD_TYPE_AMERICAN_EXPRESS;

			case '4':
				return FB_PAYMENT_CREDIT_CARD_TYPE_VISA;

			case '5':
				return FB_PAYMENT_CREDIT_CARD_TYPE_MASTERCARD;

			case '6':
				return FB_PAYMENT_CREDIT_CARD_TYPE_DISCOVER;

			case '?': /// \todo support FB_PAYMENT_CREDIT_CARD_TYPE_DINERS_CLUB
				return FB_PAYMENT_CREDIT_CARD_TYPE_DINERS_CLUB;

			case '?': /// \todo support FB_PAYMENT_CREDIT_CARD_TYPE_CARTE_BLANCHE 
				return FB_PAYMENT_CREDIT_CARD_TYPE_CARTE_BLANCHE;

			case '?': /// \todo support FB_PAYMENT_CREDIT_CARD_TYPE_JAPAN_CARD 
				return FB_PAYMENT_CREDIT_CARD_TYPE_JAPAN_CARD;

			case '?': /// \todo support FB_PAYMENT_CREDIT_CARD_TYPE_ENROUTE
				return FB_PAYMENT_CREDIT_CARD_TYPE_ENROUTE;

			default:
				return 0;
		}
	}

/*
\todo incorporate this code into the above:

   * A description of the criteria used in this function can be found at 
   * http://www.beachnet.com/~hstiles/cardtype.html. If you have any  
   * questions or comments, please direct them to ccval@holotech.net 
   * 
   * maybe also check http://www.icverify.com/ --andrej
   * 
   * there is also detailed description at http://www.webmasterbase.com/article/728
   * 
   * @param  $Num string the card number
   * @param  $Name string the short form of the cart type (n/a, mcd, vis, amx, dsc, dsc, dnc, jcb) default is 'n/a'.
   * @return bool 
   * @static
  function ccVal($Num, $Name='n/a') { 
    //this code doesn't follow bs coding standards, but ... i don't really care now --andrej
    
    //Innocent until proven guilty 
    $GoodCard = true; 
    //Get rid of any non-digits 
    $Num = ereg_replace("[^[:digit:]]", "", $Num); 
    //Perform card-specific checks, if applicable 
    switch ($Name) { 
    case "mcd" : 
      $GoodCard = ereg("^5[1-5].{14}$", $Num); 
      break; 
    case "vis" : 
      $GoodCard = ereg("^4.{15}$|^4.{12}$", $Num); 
      break; 
    case "amx" : 
      $GoodCard = ereg("^3[47].{13}$", $Num); 
      break; 
    case "dsc" : 
      $GoodCard = ereg("^6011.{12}$", $Num); 
      break; 
    case "dnc" : 
      $GoodCard = ereg("^30[0-5].{11}$|^3[68].{12}$", $Num); 
      break; 
    case "jcb" : 
      $GoodCard = ereg("^3.{15}$|^2131|1800.{11}$", $Num); 
      break; 
    } 
    //The Luhn formula works right to left, so reverse the number. 
    $Num = strrev($Num); 
    $Total = 0; 
    for ($x=0; $x<strlen($Num); $x++) { 
      $digit = substr($Num,$x,1); 
      //If it's an odd digit, double it 
      if ($x/2 != floor($x/2)) { 
        $digit *= 2; 
        //If the result is two digits, add them 
        if (strlen($digit) == 2)  
          $digit = substr($digit,0,1) + substr($digit,1,1); 
      } 
      //Add the current digit, doubled and added if applicable, to the Total 
      $Total += $digit; 
    } 
    //If it passed (or bypassed) the card-specific check and the Total is 
    //evenly divisible by 10, it's cool! 
    if ($GoodCard && $Total % 10 == 0) return TRUE; else return FALSE; 
  }
  
*/

}

?>
