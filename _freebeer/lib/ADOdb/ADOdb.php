<?php

// $CVSHeader: _freebeer/lib/ADOdb/ADOdb.php,v 1.2 2004/03/07 17:51:17 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file ADOdb/ADOdb.php
	\brief ADOdb database abstraction layer support functions
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
 	dirname(dirname(dirname(__FILE__))));

if (!defined('_ADODB_LAYER') && is_file(FREEBEER_BASE . '/opt/adodb/adodb.inc.php')) {
	include_once FREEBEER_BASE . '/opt/adodb/adodb.inc.php';
}

if (phpversion() <= '5.0.0') {
	include_once FREEBEER_BASE . '/lib/Backport.php'; // scandir()
}

require_once FREEBEER_BASE . '/lib/Config.php';

/*!
	\class fbADOdb
	\brief ADOdb database abstraction layer support functions

	\static
*/
class fbADOdb {
	/*!
		\return \c bool
		\static
	*/
	function isAvailable() {
		return defined('_ADODB_LAYER');
	}

	/*!
		\return \c hash
		\static
	*/
	function getDrivers() {
		$dir = ADODB_DIR . '/drivers';
		if (!is_dir($dir)) {
			return false;
		}

		$files = scandir($dir);

		if (!$files) {
			return false;
		}

		$rv = array();
		foreach($files as $file) {
			if (preg_match('/adodb-(.*)\.inc\.php/i', $file, $matches)) {
				$rv[$matches[1]] = $file;
			}
		}

		return $rv;
	}

	/*!
		\param $search_dir \c string
		\param $starting_dir \c string
		\return \c string
		\private
		\static
	*/
	function _findDir($search_dir, $starting_dir = null) {
		$path = is_null($starting_dir) ? dirname(__FILE__) : $starting_dir;

		$rv = false;

		while (true) {
			$dir = $path . '/' . $search_dir;

			if (@is_dir($dir)) {
				$rv = $path;
				break;
			}
			$new_path = dirname($path);
			if ($new_path == $path) {
				// we've reached the / (or c:\) directory
				break;
			}
			$path = $new_path;
		}

		return $rv;
	}

	/*!
		\return \c void
		\private
		\static
	*/
	function _init() {
		if (!defined('ADODB_CACHE_DIR')) {
			$cache_dir = fbADOdb::_findDir('var/cache/adodb');
			if ($cache_dir) {
				@define('ADODB_CACHE_DIR', $cache_dir);
			}
		}
		
//		fbSystem::appendIncludePath(dirname(__FILE__));
	}
}

fbADOdb::_init();

/**
 * Load the code for a specific database driver
 */
function fbADOLoadCode($dbType) {
	global $ADODB_Database;

	if (!$dbType) {
		return false;
	}
	$ADODB_Database = strtolower($dbType);
	switch ($ADODB_Database) {
		case 'maxsql':
			$ADODB_Database = 'mysqlt';
			break;
		case 'postgres':
		case 'pgsql':
			$ADODB_Database = 'postgres7';
			break;
	}

	$class_name = 'ADODB_' . $ADODB_Database;

	$file = 'adodb-' . $ADODB_Database . '.inc.php';

	// first, try the lib/freebeer/adodb directory
	$path = dirname(__FILE__) . '/' . $file;
	if (is_file($path)) {
		@include_once($path);
		if (class_exists($class_name)) {
			return;
		}
	}
	
	// next, try the default lib/adodb/drivers directory
	$path = ADODB_DIR . '/drivers/' . $file;
	if (is_file($path)) {
		@include_once($path);
		if (class_exists($class_name)) {
			return;
		}
	}

	// finally, search for the file in the include path
	@include_once($file);
	if (class_exists($class_name)) {
		return;
	}
	
	ADOConnection::outp("<p>fbADONewConnection: File not found '$file'</p>", false);
}

/**
 * Instantiate a new Connection class for a specific database driver.
 *
 * @param [db]  is the database Connection object to create. If undefined,
 * 	use the last database driver that was loaded by ADOLoadCode().
 *
 * @return the freshly created instance of the Connection class.
 */
function &fbADONewConnection($db = '') {
	global $ADODB_Database;

	defined('ADODB_ASSOC_CASE') || 
	 define('ADODB_ASSOC_CASE', 2);
	 
	$errorfn = (defined('ADODB_ERROR_HANDLER')) ? ADODB_ERROR_HANDLER : false;

	$rez = true;
	if ($db) {
		if ($ADODB_Database != $db) {
			fbADOLoadCode($db);

		}
	} else { 
		if (!empty($ADODB_Database)) {
			fbADOLoadCode($ADODB_Database);
		} else {
			 $rez = false;
		}
	}

	if (!$rez) {
		 if ($errorfn) {
			// raise an error
			$errorfn('ADONewConnection', 'ADONewConnection', -998,
					 "could not load the database driver for '$db",
					 $dbtype);
		} else {
			 ADOConnection::outp("<p>fbADONewConnection: Unable to load database driver '$db'</p>", false);
		}
		return false;
	}

	$cls = 'ADODB_'.$ADODB_Database;
	$obj = &new $cls();
	if ($errorfn) {
		$obj->raiseErrorFn = $errorfn;
	}

	$conf = &new fbConfig();
	$conf->getSection($obj);

	return $obj;
}

$GLOBALS['ADODB_NEWCONNECTION'] = 'fbADONewConnection';

if (!defined('ADODB_OUTP')) {
	include_once FREEBEER_BASE . '/lib/Debug.php';

	/*!
		\static
	*/
	function fb_adodb_outp($msg, $newline) {
		if (!(fbDebug::getLevel() & FB_DEBUG_ADODB)) {
			global $_SERVER; // < 4.1.0

			if ($newline) {
				$msg .= "<br>\n";
			}

			if (isset($_SERVER['HTTP_USER_AGENT'])) {
				echo $msg;
			} else {
				echo strip_tags($msg);
			}
			flush();
			return;
		}

		// \todo make fbDebug browser aware
		if (isset($_SERVER['HTTP_USER_AGENT'])) {
	//		if (function_exists('html_entity_decode')) {
	//		}
			$msg = wordwrap($msg, 132);
			fbDebug::log("\n<pre>\n".$msg."\n</pre>\n");
		} else {
			if ($newline) {
				$msg .= "\n";
			}
			fbDebug::log(strip_tags($msg));
		}
	}

	define('ADODB_OUTP', 'fb_adodb_outp');
}

if (!defined('ADODB_ERROR_HANDLER')) {
	if (!defined('ADODB_ERROR_HANDLER_TYPE')) {
		define('ADODB_ERROR_HANDLER_TYPE', E_USER_ERROR);
	}
	
	include_once FREEBEER_BASE . '/lib/Debug.php';

	/*!
	Default error handler.

	\param $dbms \c string	RDBMS you are connecting to
	\param $fn		\c string name of the calling function (in uppercase)
	\param $errno	\c int native error number from the database
	\param $errmsg	\c string native error msg from the database
	\param $p1		\c mixed ? $fn specific parameter - see below
	\param $p2		\c mixed ? $fn specific parameter - see below
	*/
	function fb_adodb_error_handler($dbms, $fn, $errno, $errmsg, $p1 = false, $p2 = false) {
		if (error_reporting() == 0) {
			return; // obey @ protocol
		}
		
		switch ($fn) {
			case 'EXECUTE':
				$sql = $p1;
				$inputparams = $p2;

				$s = "
<pre>
$dbms error $errno: $errmsg
sql='$sql'
";
if ($inputparams) {
	$s .= 'inputparams=' . var_export($inputparams, true);
}
$s .= "\n</pre>\n";

				break;

			case 'PCONNECT':
			case 'CONNECT':
				$s = sprintf("%s error: [%s: %s] in %s(%s, '****', '****', %s)\n", $dbms, $errno, $errmsg, $fn, $p1, $p2);
				break;

			default:
				$s = sprintf("%s error: [%s: %s] in %s(%s, %s)\n", $dbms, $errno, $errmsg, $fn, $p1, $p2);
				break;
		}

//		fbDebug::log($s);
//		print fbDebug::stackdump();
		trigger_error($s, ADODB_ERROR_HANDLER_TYPE); 

	} // function fb_adodb_error_handler($dbms, $fn, $errno, $errmsg, $p1 = false, $p2 = false)

	define('ADODB_ERROR_HANDLER', 'fb_adodb_error_handler');

} // if (!defined('ADODB_ERROR_HANDLER'))

?>
