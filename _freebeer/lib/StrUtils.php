<?php

// $CVSHeader: _freebeer/lib/System.php,v 1.2 2004/03/07 17:51:17 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file StrUtils.php
	\brief String related functions	
*/

// defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
//	dirname(dirname(__FILE__)));

/*!
	\class fbString
	\brief String related functions	

	\static
*/
class fbString {
	/*!
		\param $s \c string String to convert to upper case
		\return \c string Upper cased string 
		\static
	*/
	function strtoupper($s) {
		static $lc = 'abcdefghijklmnopqrstuvwxyz';
		static $uc = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

		$l = strlen($s);
		for ($i = 0; $i < $l; ++$i) {
			$j = strpos($lc, $s{$i});
			if ($j !== false) {
				$s{$i} = $uc{$j};
			}
		}
		return $s; 
	}

	/*!
		\param $s \c string String to convert to upper case
		\return \c string Lower cased string
		\static
	*/
	function strtolower($s) {
		static $lc = 'abcdefghijklmnopqrstuvwxyz';
		static $uc = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

		$l = strlen($s);
		for ($i = 0; $i < $l; ++$i) {
			$j = strpos($uc, $s{$i});
			if ($j !== false) {
				$s{$i} = $lc{$j};
			}
		}
		return $s; 
	}
}

