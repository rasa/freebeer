<?php

// $CVSHeader: _freebeer/lib/HMAC_Login.php,v 1.2 2004/03/07 17:51:17 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file HMAC_Login.php
	\brief Secure login via HMAC authentication
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

require_once FREEBEER_BASE . '/lib/Random.php';

/*!
	 \typedef FB_HMAC_LOGIN_MAX_ATTEMPTS
	Maximum number of attempts to generate a unique challenge before giving up.
	Default is 5 attempts.
*/
defined('FB_HMAC_LOGIN_MAX_ATTEMPTS') ||
 define('FB_HMAC_LOGIN_MAX_ATTEMPTS', 5);

/*!
	\typedef FB_HMAC_LOGIN_TIMEOUT_SECONDS
	Maximum number of seconds to allow the user to login by.
	Default is 15 minutes.
*/
defined('FB_HMAC_LOGIN_TIMEOUT_SECONDS') ||
 define('FB_HMAC_LOGIN_TIMEOUT_SECONDS', 15 * 60);

/*!
	\typedef FB_HMAC_LOGIN_PURGE_PERCENT
	Percent of login records to purge whenever we generate a duplicate challenge.
	Default is 0 percent.
*/
defined('FB_HMAC_LOGIN_PURGE_PERCENT') ||
 define('FB_HMAC_LOGIN_PURGE_PERCENT', 0);

/*!
	\enum FB_HMAC_LOGIN_ERROR_OK
	Safe login with correct password.
*/
define('FB_HMAC_LOGIN_ERROR_OK',			 		 0);

/*!
	\enum FB_HMAC_LOGIN_ERROR_NOT_CONNECTED
	Not connected.
*/
define('FB_HMAC_LOGIN_ERROR_NOT_CONNECTED',			-1);

/*!
	\enum FB_HMAC_LOGIN_ERROR_LOGIN_EXPIRED
	Login has expired (timeout exceeded).
*/
define('FB_HMAC_LOGIN_ERROR_LOGIN_EXPIRED',			-2);

/*!
	\enum FB_HMAC_LOGIN_ERROR_NO_CHALLENGE
	No challenge (we we're not able to generate a challenge for some reason).
*/
define('FB_HMAC_LOGIN_ERROR_NO_CHALLENGE',			-3);

/*!
	\enum FB_HMAC_LOGIN_ERROR_INVALID_CHALLENGE
	Challenge is bad or has already been used.
*/
define('FB_HMAC_LOGIN_ERROR_INVALID_CHALLENGE',		-4);

/*!
	\enum FB_HMAC_LOGIN_ERROR_BAD_CHALLENGE
	Challenge is bad.
*/
define('FB_HMAC_LOGIN_ERROR_BAD_CHALLENGE', 		-5);

/*!
	\enum FB_HMAC_LOGIN_ERROR_BAD_PASSWORD
	Safe login with incorrect password.
*/
define('FB_HMAC_LOGIN_ERROR_BAD_PASSWORD',			-6);

/*!
	\enum FB_HMAC_LOGIN_ERROR_UNSAFE_PASSWORD
	Unsafe login with correct password.
*/
define('FB_HMAC_LOGIN_ERROR_UNSAFE_PASSWORD',		-7);

/*!
	\enum FB_HMAC_LOGIN_ERROR_UNSAFE_BAD_PASSWORD
	Unsafe login with incorrect password.
*/
define('FB_HMAC_LOGIN_ERROR_UNSAFE_BAD_PASSWORD',	-8);

/*!
	\enum FB_HMAC_LOGIN_ERROR_INVALID_LOGIN
	Invalid login
*/
define('FB_HMAC_LOGIN_ERROR_INVALID_LOGIN',			-9);

/*!
	\enum FB_HMAC_LOGIN_ERROR_INVALID_PASSWORD
	Invalid login/password.
*/
define('FB_HMAC_LOGIN_ERROR_INVALID_PASSWORD',		-10);

/*!
	\class fbHMAC_Login
	\brief Secure login via HMAC authentication

	\todo add support for sha1

	\abstract
*/
class fbHMAC_Login {
	/*!
		\c resource Database handle.
		\private
	*/
	var $_dbh;

	/*!
		\c int Last error number.
		\private
	*/
	var $_last_errno;

	/*!
		\c string Last error message.
		\private
	*/
	var $_last_error;

	/*!
		\c int Number of seconds to login before timing out,
		default is 15 minutes.
		\private
	*/
	var $_timeout = FB_HMAC_LOGIN_TIMEOUT_SECONDS;

	/*!
		\c int Maximum number of attempts to generate a challenge before giving.
		up, default is 5
		\private
	*/
	var $_max_attempts = FB_HMAC_LOGIN_MAX_ATTEMPTS;

	/*!
		\c string name for SQL table containing challenges,
		default is 'challenges'.
		\private
	*/
	var $_challenge_table = 'challenges';

	/*!
		\c string name for SQL table containing login/password,
		default is 'logins'.
		\private
	*/
	var $_login_table = 'logins';

	/*!
		\c string name for SQL field containing login name in logins
		table, default is 'login'.
		\private
	*/
	var $_login_field = 'login';

	/*!
		\c string name for SQL field containing password in logins
		table, default is 'password'.
		\private
	*/
	var $_password_field = 'password';

	/*!
		Set the last error/errno to the last database error.

		\private
	*/
	function _setDbError() {
		assert(false);
	}

	/*!
		Set the last error/errno to \c $errno.

		\private
	*/
	function _setError($errno) {
		static $errors;

		if (!isset($errors)) {
			$errors = array(
				FB_HMAC_LOGIN_ERROR_OK					=> 'Safe login with correct password',
				FB_HMAC_LOGIN_ERROR_NOT_CONNECTED		=> 'Not connected',
				FB_HMAC_LOGIN_ERROR_LOGIN_EXPIRED		=> 'Login has expired (timeout exceeded)',
				FB_HMAC_LOGIN_ERROR_NO_CHALLENGE		=> 'No challenge',
				FB_HMAC_LOGIN_ERROR_INVALID_CHALLENGE	=> 'Challenge is bad or has already been used',
				FB_HMAC_LOGIN_ERROR_BAD_CHALLENGE		=> 'Challenge is bad',
				FB_HMAC_LOGIN_ERROR_BAD_PASSWORD		=> 'Safe login with incorrect password',
				FB_HMAC_LOGIN_ERROR_UNSAFE_PASSWORD		=> 'Unsafe login with correct password',
				FB_HMAC_LOGIN_ERROR_UNSAFE_BAD_PASSWORD	=> 'Unsafe login with incorrect password',
				FB_HMAC_LOGIN_ERROR_INVALID_LOGIN		=> 'Invalid login',
				FB_HMAC_LOGIN_ERROR_INVALID_PASSWORD	=> 'Invalid login/password',
			);
		}

		assert('isset($errors[$errno])');
		$this->_last_errno = $errno;
		$this->_last_error = $errors[$errno];
	}

	/*!
		Get last error number, or 0 if no error has yet occured.

		\return \c int The last error number.
	*/
	function getLastErrno() {
		return $this->_last_errno;
	}

	/*!
		Get last error message, or '' of no error has yet occured.

		\return \c string The last error message.
	*/
	function getLastError() {
		return $this->_last_error;
	}

	/*!
		Set the maximum attempts to generate a new challenge to
		\c $max_attempts.

		\param $max_attempts \c int the the maximum attempts to generate a new
		challenge.
		\return \c void
	*/
	function setMaxAttempts($max_attempts) {
		$this->_max_attempts = $max_attempts;
	}

	/*!
		Set the name for SQL table containing challenges,
		default is 'challenges'

		\param $challenge_table \c string Name for SQL table containing
		challenges.

		\return \c void
	*/
	function setChallengeTable($challenge_table) {
		$this->_challenge_table = $challenge_table;
	}

	/*!
		Set name for SQL table containing login/password,
		default is 'logins'.

		\param $login_table \c string name for SQL table containing
		login/password.

		\return \c void
	*/
	function setLoginTable($login_table) {
		$this->_login_table = $login_table;
	}

	/*!
		Set name for SQL field containing login name in login
		table, default is 'login'.

		\param $login_field \c string name for SQL field containing login name
		in login table.

		\return \c void
	*/
	function setLoginField($login_field) {
		$this->_login_field = $login_field;
	}

	/*!
		Set name for SQL field containing password in login
		table, default is 'password'.

		\param $password_field \c string name for SQL field containing password
		in login table.

		\return \c void
	*/
	function setPasswordField($password_field) {
		$this->_password_field = $password_field;
	}

	/*!
		Set the number of seconds to login before timing out, default is 15 minutes

		\param $seconds \c int The number of seconds to login before timing out
		\private
	*/
	function setTimeout($seconds) {
		$this->_timeout = (int) $seconds;
	}

	/*!
		Connect to the database.

		\param $host \c string
		\param $user \c string
		\param $password \c string
		\param $database \c string
		\param $driver \c string

		\return \c true if successful, otherwise \c false.
	*/
	function connect($host = '', $user = '', $password = '', $database = '', $driver = '') {
		assert(false);
		return false;
	}

	/*!
		Disconnect from the database.

		Can be safely called if we're already disconnected.

		\return \c true if successful, otherwise \c false.

	*/
	function close() {
		assert(false);
		return false;
	}

	/*!
		Get the next random challenge.

		\return \c string A 22 character challenge, or \c false if unsuccessful.
	*/
	function getChallenge() {
		assert(false);
		return false;
	}

	/*!
		Get the next random challenge.

		\return \c string A 22 character challenge, or \c false if unsuccessful.
	*/
	function _getChallenge($max_id, $attempts) {
		$random = &fbRandom::getInstance();

		// sha1 needs 80
		$entropy = $random->nextBytes(64) .
			$random->getEntropy() .
			sprintf('%20s', $max_id) .
			sprintf('%03d', $attempts);

		// convert 32 byte hex string to 22 byte base 64 string
		$challenge = base64_encode(pack('H*', md5($entropy)));

		// discard superfluous trailing '==' chars
		$challenge	= substr($challenge, 0, 22);

		return $challenge;
	}

	/*!
		Validate the \c $response.

		Will work if JavaScript is turned off on the client,
		but the password we be sent as clear text.

		\param $challenge \c string 22 character challenge generated via
		getChallenge().

		\param $response \c string 32 character response generated on the
		client.
		\param $login \c string Login name entered by user.
		\param $password \c string Password entered by user.
		\return \c bool \c true if successful, otherwise \c false.
	*/
	function validate($challenge, $response, $login, $password) {
		assert(false);
		return false;
	}

	/*!
		Get the password associated with the login \c $login.

		\param $login \c string Login name to retrieve password for.
		\return \c bool \c true if successful, otherwise \c false.
	*/
	function getPassword($login) {
		assert(false);
		return false;
	}

	/*!
		Delete unused records that were never used and have expired
		in the challenges table.

		These records were created when the user displayed the
		login page, but the user never subsequently logged in, leaving
		an used record in the challenges table.

		\param $days \c int The number of days old a record has to be
		in order to be deleted.  If 0, or unspecified, all records older
		than the timeout (default is 15 minutes) will be deleted.

		\return \c bool \c true if successful, otherwise \c false.
	*/
	function deleteUnused($days = null) {
		assert(false);
		return false;
	}

	/*!
		Delete used records in the challenges table.

		\param $days \c int The number of days old a record has to be
		in order to be deleted.  If 0, or unspecified, all records older
		than the timeout (default is 15 minutes) will be deleted.
		\return \c bool \c true if successful, otherwise \c false.
	*/
	function deleteUsed($days) {
		assert(false);
		return false;
	}

	/*!
		Delete a percentage of the oldest records in the challenges table.

		Deletes the oldest records first.

		\param $percent \c int The percentage of records to delete,
		0 for none, 100 for all.
		\return \c bool \c true if successful, otherwise \c false.
	*/
	function deletePercentage($percent) {
		assert(false);
		return false;
	}

	/*!
		\todo Implement checkAddress() ?

		\param $check_address \c string
		\return \c bool
	*/
	function checkAddress($check_address) {
	}

	/*!
		\todo Implement checkReferer() ?

		\param $check_referer \c string
		\return \c bool
	*/
	function checkReferer($check_referer) {
	}

	/*!
		\todo Implement checkUserAgent() ?

		\param $check_user_agent \c string
		\return \c bool
	*/
	function checkUserAgent($check_user_agent) {
	}

}

?>
