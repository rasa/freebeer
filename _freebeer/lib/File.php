<?php

// $CVSHeader: _freebeer/lib/File.php,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

/*!
	\file File.php
	\brief File and file system related functions
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

if (phpversion() <= '5.0') {
	include_once FREEBEER_BASE . '/lib/Backport.php'; // scandir()
}

/*!
	\class fbFile
	\brief File and file system related functions

	\static
*/
class fbFile {
	/*!
		\param $path \c string
		\param $sorting_order \c int
		\param $recursive \c bool
		\return \c bool

		\static
	*/
	function scandir($path, $sorting_order = 0, $recursive = false) {
		$files = scandir($path, $sorting_order);
		if ($files === false) {
			return false;
		}
		
		$rv = array();
		$dirs = array();
		foreach ($files as $file) {
			if ($file == '.' || $file == '..') {
				continue;
			}
			if ($recursive) {
				$file = $path . DIRECTORY_SEPARATOR . $file;

				if (@is_dir($file) && @is_readable($file)) {
					$dirs[] = $file;
					continue;
				}
			}
			
			$rv[] = $file;
		}

		foreach ($dirs as $dir) {
			$files = fbFile::scandir($dir, $sorting_order, $recursive);
			$rv = array_merge($rv, $files);
		}
		
		switch ($sorting_order) {
			case 0:
				sort($rv);
				break;
			case 1:
				rsort($rv);
				break;
			case false:
			case null:
				break;
			default:
				sort($rv);
				break;
		}

		return $rv;		
	}
	
	/*!
		\param $path \c string
		\param $rights \c int
		\param $umask \c int
		\return \c bool

		\static
	*/
	function mkdirs($path, $rights = 0777, $umask = null) {
		if (!$path) {
			return false;
		}
		
		if (@is_dir($path)) {
			return true;
		}
		
		$dirname = dirname($path);
		
		if ($dirname == $path) {
			// we've reached / or c:\
			return false;
		}

		$track_errors = @ini_set('track_errors', true);
		$php_errormsg = '';

		$rv = false;
		
		do {
			if (!@is_dir($dirname)) {
				if (!fbFile::mkdirs($dirname, $rights)) {
					break;
				}
			}
			
			if (!is_null($umask)) {
				$saved_umask = @umask();
				@umask($umask);
			}
			$rv = @mkdir($path, $rights);
			if (!is_null($umask)) {
				@umask($saved_umask);
			}
			if (!$rv) {
				trigger_error(sprintf('Could not create directory \'%s\': %s', $path, $php_errormsg));
			}
		} while (false);

		if (!$track_errors) {
			@ini_set('track_errors', $track_errors);
		}
		
		return $rv;
	}

}

?>
