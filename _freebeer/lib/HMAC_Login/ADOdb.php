<?php

// $CVSHeader: _freebeer/lib/HMAC_Login/ADOdb.php,v 1.2 2004/03/07 17:51:20 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file HMAC_Login/ADOdb.php
	\brief Secure login using ADOdb database class
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(__FILE__))));

require_once FREEBEER_BASE . '/lib/HTTP.php';	// getRemoteAddress();
require_once FREEBEER_BASE . '/lib/Mhash.php';

require_once FREEBEER_BASE . '/lib/HMAC_Login.php';
require_once FREEBEER_BASE . '/lib/ADOdb/ADOdb.php';

/*
if (!defined('_ADODB_LAYER')) {
	include_once FREEBEER_BASE . '/opt/adodb/adodb.inc.php';
}

require_once ADODB_DIR . '/adodb-error.inc.php';
*/

/*!
	\class fbHMAC_Login_ADOdb

	\brief Secure login using ADOdb database class
*/
class fbHMAC_Login_ADOdb extends fbHMAC_Login {
	/*!
		Set the last error/errno to the last database error.

		\private
	*/
	function _setDbError() {
		assert('$this->_dbh');

		$this->_last_errno = $this->_dbh->ErrorNo();
		$this->_last_error = $this->_dbh->ErrorMsg();
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
		$this->_dbh = &ADONewConnection($driver);
		if (!$this->_dbh) {
			return false;
		}

		$rv = $this->_dbh->PConnect($host, $user, $password, $database);

		if (!$rv) {
			$this->_dbh = 0;
		}

		return $rv;
	}

	/*!
		Disconnect from the database.

		Can be safely called if we're already disconnected.

		\return \c true if successful, otherwise \c false.

	*/
	function close() {
		if (!$this->_dbh) {
			return false;
		}

		$this->_dbh->Close();
		$this->_dbh = null;

		return true;
	}

	/*!
		Get the next random challenge.

		\return \c string A 22 character challenge, or \c false if unsuccessful.
	*/
	function getChallenge() {
		global $_SERVER; // < 4.1.0

		$this->_last_errno = FB_HMAC_LOGIN_ERROR_OK;
		$this->_last_error = '';

		if (!$this->_dbh && !$this->connect()) {
			$this->_setError(FB_HMAC_LOGIN_ERROR_NOT_CONNECTED);
			return false;
		}

		$dbh = $this->_dbh;

		$user_agent		= isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
		$remote_addr	= fbHTTP::getRemoteAddress();
		$referer		= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

		$attempts = $this->_max_attempts;

		while ($attempts--) {
			$sql = "
				SELECT
					MAX(id) AS id
				FROM
					{$this->_challenge_table}
			";
			$rs	= $dbh->Execute($sql);
			if (!$rs) {
				$this->_setDbError();
				return false;
			}

			if ($rs->RecordCount()) {
				$row = $rs->FetchRow();
				$max_id = $row[0];
			} else {
				$max_id = 1;
			}

			$challenge	= $this->_getChallenge($max_id, $attempts);

			$sql = "
				INSERT INTO
					{$this->_challenge_table}
				(
					challenge,
					used,
					ip_address,
					user_agent,
					referer,
					created,
					modified
				) VALUES (
					?,
					?,
					?,
					?,
					?,
					$dbh->sysTimeStamp,
					$dbh->sysTimeStamp
				)
			";

			$values = array(
					$challenge,
					'N',
					$remote_addr,
					$user_agent,
					$referer,
			);
			$rs = $dbh->Execute($sql, $values);
			if (!$rs) {
				if ($dbh->ErrorNo() == DB_ERROR_ALREADY_EXISTS) {	// duplicate key
					// \todo log this key violation,
					// so admin can purge some records at some point
					continue;
				}

				$this->_setDbError();
				return false;
			}

			if (!$dbh->Affected_Rows()) {
				continue;
			}

			return $challenge;
		}

		$this->_setError(FB_HMAC_LOGIN_ERROR_NO_CHALLENGE); // No challenge

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
		$this->_last_errno = FB_HMAC_LOGIN_ERROR_OK;
		$this->_last_error = '';

		if (!$this->_dbh && !$this->connect()) {
			$this->_setError(FB_HMAC_LOGIN_ERROR_NOT_CONNECTED);
			return false;
		}

		$dbh = $this->_dbh;

		$login			= trim(rtrim($login));
		$login_password = $this->getPassword($login);

		if ($login_password === false) {
			if ($this->_last_errno == FB_HMAC_LOGIN_ERROR_OK) {
				$this->_setError(FB_HMAC_LOGIN_ERROR_INVALID_PASSWORD);
			}
			return false;
		}

		$sql = "
			UPDATE
				{$this->_challenge_table}
			SET
				used		= ?,
				login		= ?,
				modified	= $dbh->sysTimeStamp
			WHERE
				/*! BINARY */ challenge = ? AND
				used		= ?
		";
		$values = array(
			'Y',
			$login,
			$challenge,
			'N',
		);
		$rs = $dbh->Execute($sql, $values);
		if (!$rs) {
			$this->_setDbError();
			return false;
		}

		$affected_rows = $dbh->Affected_Rows();

		if (!$affected_rows) {
			// Challenge is bad or has already been used
			$this->_setError(FB_HMAC_LOGIN_ERROR_INVALID_CHALLENGE);
			return false;
		}

		if ($this->_timeout > 0) {
			$sql = "
				SELECT
					modified - created
				FROM
					{$this->_challenge_table}
				WHERE
					/*! BINARY */ challenge = ?
			";
			$values = array(
				$challenge,
			);
			$rs = $dbh->Execute($sql, $values);
			if (!$rs) {
				$this->_setDbError();
				return false;
			}

			if (!$rs->RecordCount()) {
				// Challenge is bad
 				$this->_setError(FB_HMAC_LOGIN_ERROR_BAD_CHALLENGE);
				return false;
			}

			$row = $rs->FetchRow();
			$seconds = $row[0];
			if ($seconds > $this->_timeout) {
 				// Login has expired (timeout exceeded)
 				$this->_setError(FB_HMAC_LOGIN_ERROR_LOGIN_EXPIRED);
				return false;
			}
		}

		if ($response) {
			$calculated_response = bin2hex(mhash(MHASH_MD5, $login_password, $challenge));
//			$calculated_response /* :) */ = fbMhash::mhashhex(MHASH_MD5, $login_password, $challenge);
			if ($response == $calculated_response) {
 				// Safe login with correct password
				$this->_setError(FB_HMAC_LOGIN_ERROR_OK);
				return true;
			}

 			// Safe login with incorrect password
			$this->_setError(FB_HMAC_LOGIN_ERROR_BAD_PASSWORD);
			return false;
		}

		if ($password == $login_password) {
 			// Unsafe login with correct password
			$this->_setError(FB_HMAC_LOGIN_ERROR_UNSAFE_PASSWORD);
			return true;
		}

 		// Unsafe login with incorrect password
		$this->_setError(FB_HMAC_LOGIN_ERROR_UNSAFE_BAD_PASSWORD);

		return false;
	}

	/*!
		Get the password associated with the login \c $login.

		\param $login \c string Login name to retrieve password for.
		\return \c bool \c true if successful, otherwise \c false.
	*/
	function getPassword($login) {
		$this->_last_errno = FB_HMAC_LOGIN_ERROR_OK;
		$this->_last_error = '';

		if (!$this->_dbh && !$this->connect()) {
			$this->_setError(FB_HMAC_LOGIN_ERROR_NOT_CONNECTED);
			return false;
		}

		$dbh = $this->_dbh;

		$sql = "
			SELECT
				{$this->_password_field}
			FROM
				{$this->_login_table}
			WHERE
				/*! BINARY */ {$this->_login_field} = ?
		";
		$values = array(
			$login,
		);
		$rs = $dbh->Execute($sql, $values);
		if (!$rs) {
			$this->_setDbError();
			return false;
		}

		if (!$rs->RecordCount()) {
			$this->_setError(FB_HMAC_LOGIN_ERROR_INVALID_LOGIN);
			return false;
		}

		$row = $rs->FetchRow();
		$password = $row[0];
		return $password;
	}

	/*
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
		$this->_last_errno = FB_HMAC_LOGIN_ERROR_OK;
		$this->_last_error = '';

		if (!$this->_dbh && !$this->connect()) {
			$this->_setError(FB_HMAC_LOGIN_ERROR_NOT_CONNECTED);
			return false;
		}

		$dbh = $this->_dbh;

		if ($days == null) {
			$seconds = $this->_timeout;
		} else {
			$seconds = $days * 86400;
		}

		// Delete all unused records that have expired
		$sql = "
			DELETE /*! LOW_PRIORITY */ FROM
				{$this->_challenge_table}
			WHERE
				used							= ? AND
				$dbh->sysTimeStamp - created	>= ?
		";
		$values = array(
			'N',
			$seconds,
		);
		$rs = $dbh->Execute($sql, $values);
		if (!$rs) {
			$this->_setDbError();
			return false;
		}

		return true;
	}

	/*
		Delete used records in the challenges table.

		\param $days \c int The number of days old a record has to be
		in order to be deleted.  If 0, or unspecified, all records older
		than the timeout (default is 15 minutes) will be deleted.

		\return \c bool \c true if successful, otherwise \c false.
	*/
	function deleteUsed($days) {
		$this->_last_errno = FB_HMAC_LOGIN_ERROR_OK;
		$this->_last_error = '';

		if ($days == 0) {
			// You can't delete all records as there might be
			// unused records still active in the table
			return false;
		}

		if (!$this->_dbh && !$this->connect()) {
			$this->_setError(FB_HMAC_LOGIN_ERROR_NOT_CONNECTED);
			return false;
		}

		$dbh = $this->_dbh;

		$seconds = $days * 86400;

		// Delete all unused records that have expired
		$sql = "
			DELETE /*! LOW_PRIORITY */ FROM
				{$this->_challenge_table}
			WHERE
				$dbh->sysTimeStamp - created >= ?
		";
		$values = array(
			$seconds,
		);
		$rs = $dbh->Execute($sql, $values);
		if (!$rs) {
			$this->_setDbError();
			return false;
		}

		return true;
	}

	/*!
		Delete a percentage of the oldest records in the challenges table.

		Deletes the oldest records first.

		\param $percent \c int The percentage of records to delete,
		0 for none, 100 for all.

		\return \c bool \c true if successful, otherwise \c false.
	*/
	function deletePercentage($percent) {
		$this->_last_errno = FB_HMAC_LOGIN_ERROR_OK;
		$this->_last_error = '';

		if ($percent <= 0) {
			return true;
		}

		if (!$this->_dbh && !$this->connect()) {
			$this->_setError(FB_HMAC_LOGIN_ERROR_NOT_CONNECTED);
			return false;
		}

		$dbh = $this->_dbh;

		$sql = "
			SELECT
				COUNT(*)
			FROM
				{$this->_challenge_table}
		";
		$rs = $dbh->Execute($sql);
		if (!$rs) {
			$this->_setDbError();
			return false;
		}

		$row = $rs->FetchRow();
		$records = $row[0];

		$records = (int) $records * ($percent / 100);

		if ($records <= 0) {
			return true;
		}

		$sql = "
			SELECT
				MAX(modified) AS modified
			FROM
				{$this->_challenge_table}
			WHERE
				used = ?
			ORDER BY
				modified
		";
		$values = array(
			'N',
		);
		$rs = $dbh->SelectLimit($sql, $records, -1, $values);
		if (!$rs) {
			$this->_setDbError();
			return false;
		}

		$row = $rs->FetchRow();
		$max_modified = $row[0];

		$sql = "
			DELETE /*! LOW_PRIORITY */ FROM
				{$this->_challenge_table}
			WHERE
				used		= ? AND
				modified	<= ?
		";
		$values = array(
			'N',
			$max_modified,
		);
		$rs = $dbh->Execute($sql, $values);
		if (!$rs) {
			$this->_setDbError();
			return false;
		}

		return true;
	}

}

?>
