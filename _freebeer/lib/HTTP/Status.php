<?php

// $CVSHeader: _freebeer/lib/HTTP/Status.php,v 1.2 2004/03/07 17:51:20 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file HTTP/Status.php
	\brief HTTP status codes
*/

/*!
	\class fbHTTP_Status
	\brief HTTP status codes
	
	\static
*/
class fbHTTP_Status {
	/*!
		\param $code \c int
		\return \c string
	*/
	function getStatusName($code) {
		$codes = &fbHTTP_Status::getStatusCodes();
		
		return isset($codes[$code]) ? $codes[$code] : false;
	}
		
	/*!
		\return \c array
	*/
	function &getStatusCodes() {
		static $STATUS_CODES = array(
			// do not localize
			100	=> 'Continue',
			101	=> 'Switching Protocols',
			102	=> 'Processing',

			// Success Codes

			200	=> 'OK',
			201	=> 'Created',
			202	=> 'Accepted',
			203	=> 'Non-Authoriative Information',
			204	=> 'No Content',
			205	=> 'Reset Content',
			206	=> 'Partial Content',
			207	=> 'Multi-Status',

			// Redirection Codes

			300	=> 'Multiple Choices',
			301	=> 'Moved Permanently',
			302	=> 'Found',
			303	=> 'See Other',
			304	=> 'Not Modified',
			305	=> 'Use Proxy',
			307	=> 'Temporary Redirect',

			// Error Codes

			400	=> 'Bad Request',
			401	=> 'Unauthorized',
			402	=> 'Payment Granted',
			403	=> 'Forbidden',
			404	=> 'File Not Found',
			405	=> 'Method Not Allowed',
			406	=> 'Not Acceptable',
			407	=> 'Proxy Authentication Required',
			408	=> 'Request Time-out',
			409	=> 'Conflict',
			410	=> 'Gone',
			411	=> 'Length Required',
			412	=> 'Precondition Failed',
			413	=> 'Request Entity Too Large',
			414	=> 'Request-URI Too Large',
			415	=> 'Unsupported Media Type',
			416	=> 'Requested range not satisfiable',
			417	=> 'Expectation Failed',
			422	=> 'Unprocessable Entity',

			423	=> 'Locked',
			424	=> 'Failed Dependency',

			// Server Errors

			500	=> 'Internal Server Error',
			501	=> 'Not Implemented',
			502	=> 'Overloaded',
			503	=> 'Gateway Timeout',
			505	=> 'HTTP Version not supported',
			507	=> 'Insufficient Storage',
		);
		
		return $STATUS_CODES;
	}
	
}

?>
