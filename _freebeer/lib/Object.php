<?php

// $CVSHeader: _freebeer/lib/Object.php,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

/*!
	\file Object.php
	\brief 	Base persistent object
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

require_once FREEBEER_BASE . '/lib/System.php'; // tempDirectory()

/*!	
	\class fbObject
	\brief 	Base persistent object
		
	Base persistent object
*/
class fbObject {
	/*!
		Translates object to a human readable text representation of the object.

		\return \c string Human readable text representation of the object
	*/
	function toString() {
		static $indent = "\n    ";

		$rv = var_export($this, true);
		$rv = str_replace("=>\n  ",'=>', $rv);
		return "\n" . str_replace("\n", $indent, $rv);
	}

	/*!
		Translates object to HTML representation of the object.

		\return \c string HTML representation of the object
	*/
	function toHtml() {
		return nl2br(str_replace(' ', '&nbsp;', htmlspecialchars($this->toString())));
	}

	/*!
		Persists object on the file system

		\return \c string File name of serialized object, or \c null if an error occured
	*/
	function persist() {
		$file = tempnam(fbSystem::tempDirectory(), 'fbo');
		if (!$file) {
			return false;
		}
		$data = serialize($this);
		if (!$data) {
			return false;
		}
		$fp = fopen($file, 'wb');
		if (!$fp) {
			return false;
		}
		if (fwrite($fp, $data) != strlen($data)) {
			fclose($fp);
			@unlink($file);
			return false;
		}
		if (!fclose($fp)) {
			@unlink($file);
			return false;
		}
		return $file;
	}

	/*!
		Recreates object from persistent data stored on file system.

		\param $file \c string file name containing serialized object
		\param $delete_file \c bool \c true to delete $file, otherwise \c false
		\return mixed object created from unserializing data, or \c null if an error occured
	*/
	function unpersist($file, $delete_file = false) {
		if (!$file) {
			return null;
		}
		$fp = fopen($file, 'rb');
		if (!$fp) {
			return null;
		}
		$size = filesize($file);
		if ($size) {
			$data = fread($fp, $size);
		} else {
			$data = '';
			while (!feof($fp)) {
			   $data .= fgets ($fp, 4096);
			}
		}
		@fclose($fp);
		if ($delete_file) {
			@unlink($file);
		}
		return unserialize($data);
	}

} // class Mc_Object

?>
