<?php

// $CVSHeader: _freebeer/lib/HTML/LockFormFields.php,v 1.2 2004/03/07 17:51:20 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file HTML/LockFormFields.php
	\brief Lock selected HTML form fields
*/

/*!
	\enum FB_LOCK_FORM_FIELDS_HASH_NAME
	hash value name, rename if desired
*/
defined('FB_LOCK_FORM_FIELDS_HASH_NAME') ||
 define('FB_LOCK_FORM_FIELDS_HASH_NAME', 'raTY7BIuw3FBPnBVGciTAg');
// 'raTY7BIuw3FBPnBVGciTAg' = base64_encode(pack('H*', md5('Ross Ronald Smith II')));

/*!
	\enum FB_LOCK_FORM_FIELDS_SECRET
	
	You should define (or replace) this value with your own series of 64 random characters
	(or 128 random hexidecimal characters).
	
	To generate 128 random hexidecimal characters in *nix/Cygwin type:
	head -c 64 </dev/urandom | od -h --width=256 | cut -c 9- | tr -d ' ' | tr -d '\n' ; echo

	Or for the truly paranoid:
	head -c 64 </dev/random | od -h --width=256 | cut -c 9- | tr -d ' ' | tr -d '\n' ; echo
	
	Or you can visit:
	
	http://www.random.org/cgi-bin/randbyte?nbytes=128&format=hex

*/

/*!
	\typedef FB_LOCK_FORM_FIELDS_SECRET
*/
defined('FB_LOCK_FORM_FIELDS_SECRET') ||
 define('FB_LOCK_FORM_FIELDS_SECRET', 'd416be1efda7dbda4f199660d7020f1c51942aba0554a0e08acb5554d7c7efd67cc6acb00e928d07e7ce2afa0ab29c07ac70561e4fc0cecc695e6d9f627dd9b6');

/*!
	\enum FB_LOCK_FORM_FIELDS_MAGIC_LIFE_ANY
	Magic life any
*/
define('FB_LOCK_FORM_FIELDS_MAGIC_LIFE_ANY',		0);

/*!
	\enum FB_LOCK_FORM_FIELDS_MAGIC_LIFE_SCRIPT
	Magic life script
*/
define('FB_LOCK_FORM_FIELDS_MAGIC_LIFE_SCRIPT',		1);

/*!
	\enum FB_LOCK_FORM_FIELDS_MAGIC_LIFE_SESSION
	Magic life session
*/
define('FB_LOCK_FORM_FIELDS_MAGIC_LIFE_SESSION',	2);

/*!
	\enum FB_LOCK_FORM_FIELDS_MAGIC_LIFE_USER
	Magic life user
*/
define('FB_LOCK_FORM_FIELDS_MAGIC_LIFE_USER',		4);

/*!
	\class fbHTML_LockFormFields
	\brief Lock selected HTML form fields

	\see http://www.zend.com/codex.php?id=626&single=1

	\static
*/
class fbHTML_LockFormFields {
	/*!
		\return \c mixed
		\static
	*/
	function getHash() {
		return isset($_REQUEST[FB_LOCK_FORM_FIELDS_HASH_NAME])
			? $_REQUEST[FB_LOCK_FORM_FIELDS_HASH_NAME]
			: '';
	}

	/*!
		\return \c mixed
		\static
	*/
	function getHashName() {
		return FB_LOCK_FORM_FIELDS_HASH_NAME;
	}

	/*!
		\return \c string
		\static
	*/
	function getHashField() {
		return sprintf('<input type="hidden" name="%s" value="%s" />',
			FB_LOCK_FORM_FIELDS_HASH_NAME, 
			isset($_REQUEST[FB_LOCK_FORM_FIELDS_HASH_NAME])
			? $_REQUEST[FB_LOCK_FORM_FIELDS_HASH_NAME]
			: ''
		);
	}

	
	/*!
		Subclasses can override this function, but should call the parent too:
		
		\code
		
		class MySecureRequest extends fbHTML_LockFormFields {
			function addMagic($magic_life = FB_LOCK_FORM_FIELDS_MAGIC_LIFE_ANY) {
				$s = parent::addMagic($magic_life);
				$s .= ...
			}
		}
		
		\endcode
		
		\param $magic_life \c int \see FB_LOCK_FORM_FIELDS_MAGIC_LIFE_ANY, etc.
		\return \c string
		\static
	*/
	function addMagic($magic_life = FB_LOCK_FORM_FIELDS_MAGIC_LIFE_ANY) {
		$s = '';
/* // for example:		
		if ($magic_life & FB_LOCK_FORM_FIELDS_MAGIC_LIFE_USER) {
			if (isset($_SESSION['auth']) && is_object($_SESSION['auth'])) {
				@$s .= $_SESSION['auth']->st_uid;
			}
		}
*/		
		return $s;
	}

	/*!
		\param $values \c hash hash of all variables, usually \c $_REQUEST
		\param $locked \c array array of keys in \c $values to protect
		\param $magic_life \c int \see FB_LOCK_FORM_FIELDS_MAGIC_LIFE_ANY, etc.
		\return \c string
		\private
		\static
	*/
	function _generateHash($values, $locked = null, $magic_life = FB_LOCK_FORM_FIELDS_MAGIC_LIFE_ANY) {
		global $_SERVER; // < 4.1.0
		
		assert('is_array($values)');
		assert('is_integer($magic_life)');

		if (!$locked) {
			$locked = array_keys($values);
		}

		assert('is_array($locked)');

		$secret = FB_LOCK_FORM_FIELDS_SECRET;

		if ($magic_life & FB_LOCK_FORM_FIELDS_MAGIC_LIFE_SCRIPT) {
			if (isset($_SERVER['PHP_SELF'])) {
				$secret .= $_SERVER['PHP_SELF'];
			} elseif (isset($_SERVER['SCRIPT_FILENAME'])) {
				$secret .= $_SERVER['SCRIPT_FILENAME'];
			}
		}
	
		if ($magic_life & FB_LOCK_FORM_FIELDS_MAGIC_LIFE_SESSION) {
			$secret .= session_id();
		}

		if (isset($this)) {
			assert('is_object($this)');
			assert('method_exists($this, "addMagic")');

			$secret .= $this->addMagic($magic_life);
		} else {
			$secret .= fbHTML_LockFormFields::addMagic($magic_life);
		}
		
		foreach($locked as $k) {
			if ($k == FB_LOCK_FORM_FIELDS_HASH_NAME) {
				 // skip if there is hash
				continue;
			}
			
			// serialize allows us to handle arrays (such as multi-selects)
			$secret .= '&' . $k . '=' . serialize($values[$k]);
		}

		return md5($secret);
	}
	
	/*!
		Generate hash of locked variables in \c $values and add it to the array

		\param $values \c hash hash of all variables, usually \c $_REQUEST, or $_POST
		\param $locked \c array array of keys in \c $values to protect
		\param $magic_life \c int \see FB_LOCK_FORM_FIELDS_MAGIC_LIFE_ANY, etc.
		\return \c hash Original \c $values array with hash added to it
		\static
	*/
	function &set(&$values, $locked = null, $magic_life = FB_LOCK_FORM_FIELDS_MAGIC_LIFE_ANY) {
		assert('is_array($values)');

		$hash = fbHTML_LockFormFields::_generateHash($values, $locked, $magic_life);

		$values[FB_LOCK_FORM_FIELDS_HASH_NAME] = $hash;

		return $values;
	}

	/*!
		Validate that the locked variables in \c $values have not been tampered with.

		\param $values \c hash hash of all variables, usually \c $_REQUEST, or $_POST
		\param $locked \c array array of keys in \c $values to protect
		\param $magic_life \c int \see FB_LOCK_FORM_FIELDS_MAGIC_LIFE_ANY, etc.
		\return bool \c true if the hash is valid, otherwise \c false
		\static
	*/
	function check(&$values, $locked = null, $magic_life = FB_LOCK_FORM_FIELDS_MAGIC_LIFE_ANY) {
		assert('is_array($values)');

		if (!isset($values[FB_LOCK_FORM_FIELDS_HASH_NAME])) {
			return false;
		}

		$hash = fbHTML_LockFormFields::_generateHash($values, $locked, $magic_life);

		return $values[FB_LOCK_FORM_FIELDS_HASH_NAME] == $hash;
	}

}

?>
