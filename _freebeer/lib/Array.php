<?php

// $CVSHeader: _freebeer/lib/Array.php,v 1.2 2004/03/07 17:51:16 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

// Copyright (c) 2001-2003, Bold New World, Inc. (http://bnw.com/) All rights reserved.  Used by permission.

/*!
	\file ./Array.php
	\brief Array (and hash) related functions
*/

/*!
	\class fbArray
	\brief Array (and hash) related functions

	\static
*/
class fbArray {
	/*!
		Get a key from a multi-dimensional array
		
		You have a form with the following input field:

		<input type='submit' name='submit[key1][key2][key3]' value='Default Shipping' />

		If clicked, this will return the following:

		$_REQUEST['submit'] = array('key1' => array('key2' => array('key3' => 'Default Shipping')));

		To return the value 'key1', use:

			fbArray::getNestedKey($_REQUEST['submit']);

		To return the value 'key2', use:

			fbArray::getNestedKey($_REQUEST['submit'], 'key1');
			or
			fbArray::getNestedKey($_REQUEST['submit'], array('key1'));

		To return the value 'key3', use:

			fbArray::getNestedKey($_REQUEST['submit'], array('key1', 'key2'));

		To return the value 'Default Shipping', use:

			fbArray::getNestedKeyValue($_REQUEST['submit'], array('key1', 'key2', 'key3'));

		\param $a \c array
		\param $array_of_hash_keys array (optional) \c array
		\return \c mixed array element if found, otherwise \c false
		\static
	*/
	function getNestedKey($a, $array_of_hash_keys = false) {
		if (!isset($a)) {
			return false;
		}

		if (!is_array($a)) {
			return false;
		}

		if (!is_array($array_of_hash_keys)) {
			$array_of_hash_keys = array($array_of_hash_keys);
		}

		$key = array_shift($array_of_hash_keys);

		if ($key) {
			if (count($array_of_hash_keys)) {
				if (!isset($a[$key])) {
					return false;
				}

				return fbArray::getNestedKey($a[$key], $array_of_hash_keys);
			}

			if (!isset($a[$key])) {
				return false;
			}

			$b = $a[$key];

			if (!is_array($b)) {
				return false;
			}
		} else {
			$b = $a;
		}

		reset($b);
		list($akey, $value) = each($b);
		return $akey;
	}

	/*!
		Get a value from a multi-dimensional array

		\param $a \c array
		\param $array_of_hash_keys array (optional) \c array
		\return \c mixed array element if found, otherwise \c false
		\static
	*/
	function getNestedKeyValue($a, $array_of_hash_keys = false) {
		if (!isset($a)) {
			return false;
		}

		if (!is_array($a)) {
			return $a;
		}

		if (!is_array($array_of_hash_keys)) {
			$array_of_hash_keys = array($array_of_hash_keys);
		}

		$key = array_shift($array_of_hash_keys);

		if (count($array_of_hash_keys)) {
			if (!isset($a[$key])) {
				return false;
			}

			return fbArray::getNestedKeyValue($a[$key], $array_of_hash_keys);
		}

		if (!isset($a[$key])) {
			return false;
		}

		return $a[$key];
	}

	/*!
		Add an hash element to the beginning of the hash

		\see http://us2.php.net/manual/en/function.array-unshift.php#14358

		\param $arr \c hash
		\param $key \c mixed Key of array element to insert
		\param $val \c mixed Value of array element to insert
		\return \c int Count of number of element in array after insertion
		\static
	*/
	function unshiftAssoc(&$arr, $key, $val) {
		$arr = array_reverse($arr, true);
		$arr[$key] = $val;
		$arr = array_reverse($arr, true);
		return count($arr);
	}

}

?>
