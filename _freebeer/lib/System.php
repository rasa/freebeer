<?php

// $CVSHeader: _freebeer/lib/System.php,v 1.2 2004/03/07 17:51:17 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file System.php
	\brief Operation system and PHP related functions	
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

if (phpversion() <= '4.1.0') {
	include_once FREEBEER_BASE . '/lib/Backport.php'; // $_SERVER
}

/*!
	\class fbSystem
	\brief Operation system and PHP related functions	

	\static
*/
class fbSystem {
	/*!
		\return \c bool
		\static
	*/
	function isCLI() {
		static $_is_cli = null;
		
		if (is_null($_is_cli)) {
			global $_SERVER; // < 4.1.0

			$_is_cli = isset($_SERVER['REMOTE_ADDR']) ? !$_SERVER['REMOTE_ADDR'] : true;
		}
		
		return $_is_cli;
	}

	/*!
		\return \c void
		\private
		\static
	*/
	function _initCLI() {
		global $_SERVER; // < 4.1.0
		
		if (ini_get('max_execution_time') > 0) {
			@set_time_limit(0);
		}
        @ob_implicit_flush(true);
        @ini_set('html_errors', false);

        $_SERVER['HTTP_HOST']			= '127.0.0.1';
        $_SERVER['SERVER_NAME']			= '127.0.0.1';
        $_SERVER['SERVER_PORT']			= '';
        $_SERVER['REMOTE_ADDR']			= '';
        $_SERVER['PHP_SELF']			= isset($_SERVER['argv']) ? $_SERVER['argv'] : '';
		$_SERVER['SCRIPT_FILENAME']		= '';
		$_SERVER['REQUEST_URI']			= '';
	}

	/*!
		\return \c void
		\private
		\static
	*/
	function _init() {
		global $_SERVER; // < 4.1.0

		if (fbSystem::isCLI()) {
			fbSystem::_initCLI();
			return;
		}

		// IIS doesn't provide $_SERVER['SCRIPT_FILENAME']
		if (!isset($_SERVER['SCRIPT_FILENAME'])) {
			if (isset($_SERVER['SCRIPT_NAME']) && $_SERVER['DOCUMENT_ROOT']) {
				$_SERVER['SCRIPT_FILENAME'] = $_SERVER['DOCUMENT_ROOT'] . $_SERVER['SCRIPT_NAME'];
			} else {
				$_SERVER['SCRIPT_FILENAME'] = '';
			}
		}

		// IIS doesn't provide $_SERVER['REQUEST_URI']
		if (!isset($_SERVER['REQUEST_URI']) && isset($_SERVER['PHP_SELF'])) {
			$_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];

			if (isset($_SERVER['PATH_INFO'])) {
				$_SERVER['REQUEST_URI'] .= $_SERVER['PATH_INFO'];
			}

			if (isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING']) {
				$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
			}
		}
		if (!isset($_SERVER['REQUEST_URI'])) {
			$_SERVER['REQUEST_URI'] = '';
		}

		$dir = trim(ini_get('upload_tmp_dir'));
		if (!$dir || !@is_dir($dir) || !is_writable($dir)) {
			@ini_set('upload_tmp_dir', fbSystem::tempDirectory());
		}

		$dir = trim(ini_get('session.save_path'));
		if (!$dir || !@is_dir($dir) || !is_writable($dir)) {
			@ini_set('session.save_path', fbSystem::tempDirectory());
		}
	}

	/*!
		\param $info \c int
		
		\return \c string
		\static
	*/
	function getPhpinfo($info = INFO_ALL) {
		static $phpinfo = array();

		if (!isset($phpinfo[$info])) {
			ob_start();
			phpinfo($info);
			$phpinfo[$info] = ob_get_contents();
			ob_end_clean();
		}
		return $phpinfo[$info];
	}

	/*!
		\param $var \c string
		\param $case_sensitive \c bool
		
		\return \c string
		\static
	*/
	function getServerVar($var, $case_sensitive = false) {
		global $_SERVER; // < 4.1.0

		if (isset($_SERVER[$var])) {
			return $_SERVER[$var];
		}

		if (!$case_sensitive) {
			foreach($_SERVER as $key => $value) {
				if (strcasecmp($var, $key) == 0) {
					return $value;
				}
			}
		}
		
		return false;
	}

	/*!
		\param $var \c string
		\param $case_sensitive \c bool

		\return \c string
		\static
	*/
	function getEnvVar($var, $case_sensitive = false) {
		global $_ENV; // < 4.1.0

		if (isset($_ENV[$var])) {
			return $_ENV[$var];
		}

		if (!$case_sensitive) {
			foreach($_ENV as $key => $value) {
				if (strcasecmp($var, $key) == 0) {
					return $value;
				}
			}
		}
		
		if (!getenv($var)) {
			return false;
		}
		
		if ($case_sensitive) {
			$uvar = strtoupper($var);
			if (($var != $uvar) && (getenv($var) == getenv($uvar))) {
				return false;
			}
		}
		
		return getenv($var);
	}

	/*!
		\return \c string
		\static
	*/
	function platform() {
		static $platform;

		if (!$platform) {
			if (preg_match('/^win/i', PHP_OS)) {
				$platform = 'windows';
			} elseif (preg_match('/os\/2/i', PHP_OS)) {
				$platform = 'os/2';
			} elseif (preg_match('/netware/i', PHP_OS)) {
				$platform = 'netware';
			} else {
				$platform = 'unix';
			}
		}

		return $platform;
	}

	/*!
		\return \c bool
		\static
	*/
	function isApache() {
		global $_SERVER; // < 4.1.0

		return isset($_SERVER['SERVER_SOFTWARE']) ? strpos($_SERVER['SERVER_SOFTWARE'], 'Apache') !== false : false;
	}

	/*!
		\return \c string
		\static
	*/
	function directorySeparator() {
		while (!defined('DIRECTORY_SEPARATOR')) {
			if (preg_match('/^(win|os\/2)/i', PHP_OS)) {
				define('DIRECTORY_SEPARATOR', '\\');
				break;
			}

			define('DIRECTORY_SEPARATOR', '/');
		}
		
		return DIRECTORY_SEPARATOR;
	}

	/*!
		\return \c string
		\static
	*/
	function lineSeparator() {
		static $line_separator;

		while (!$line_separator) {
			if (defined('LINE_SEPARATOR')) {
				$line_separator = constant('LINE_SEPARATOR');
				break;
			}
				
			if (preg_match('/^(win|os\/2)/i', PHP_OS)) {
				$line_separator = "\r\n";
				break;
			}

/* \todo verify pre OSX mac OS name
			if (preg_match('/^(mac)/i', PHP_OS)) {
				$line_separator = "\r";
				break;
			}
*/

			$line_separator = "\n";
		}

		return $line_separator;
	}

	/*!
		\return \c string
		\static
	*/
	function pathSeparator() {
		while (!defined('PATH_SEPARATOR')) {
			if (preg_match('/^(win|os\/2)/i', PHP_OS)) {
				define('PATH_SEPARATOR', ';');
				break;
			}
				
			define('PATH_SEPARATOR', ':');
		}

		return PATH_SEPARATOR;
	}

	/*!
		Return the hostname the system is running on

		\return \c string
		\static
	*/
	function hostname() {

		static $hostname = null;

		if (is_null($hostname)) {
			global $_SERVER; // < 4.1.0

			if (function_exists('posix_uname')) {
				$hash = posix_uname();

				$hostname = @$hash['nodename'];
			} elseif (isset($_SERVER['HOSTNAME'])) {
				$hostname = $_SERVER['HOSTNAME'];
			} elseif (fbSystem::getEnvVar('COMPUTERNAME')) {
				$hostname = fbSystem::getEnvVar('COMPUTERNAME');
			} else {
				$hostname = '';
			}
		}

		return $hostname;
	}

	/*!
		\return \c string
		\static
	*/
	function username() {
		static $username = null;

		if (is_null($username)) {
			global $_SERVER; // < 4.1.0

			if (function_exists('posix_getpwuid')) {
				$hash = posix_getpwuid(posix_getuid());
				$username = @$hash['name'];
			} elseif (isset($_SERVER['USER'])) {
				$username = $_SERVER['USER'];
			} elseif (isset($_SERVER['LOGNAME'])) {
				$username = $_SERVER['LOGNAME'];
			} elseif (fbSystem::getEnvVar('USERNAME')) {
				$username = fbSystem::getEnvVar('USERNAME');
//			} elseif (fbSystem::getEnvVar('USERPROFILE')) {
//				$username = basename(fbSystem::getEnvVar('USERPROFILE'));
			} else {
				$username = '';
			}
		}

		return $username;
	}

	/*!
		\return \c string
		\static
	*/
	function tempDirectory() {
		static $dir = null;

		while (is_null($dir)) {
			$dirs = array();

			$dirs[] = trim(fbSystem::getEnvVar('PHPTMP'));

			if (preg_match('/^(win|os\/2)/i', PHP_OS)) {
				$dirs[] = trim(fbSystem::getEnvVar('TEMP'));
				$dirs[] = trim(fbSystem::getEnvVar('TMP'));
			} else {
				$dirs[] = trim(fbSystem::getEnvVar('TMPDIR'));
			}

			$dirs[] = trim(ini_get('session.save_path'));
			$dirs[] = trim(ini_get('upload_tmp_dir'));

			if (preg_match('/^(win|os\/2)/i', PHP_OS)) {
				$dirs[] = trim(fbSystem::getEnvVar('SystemRoot')) . '/TEMP';	// C:\WINDOWS/TEMP
				$dirs[] = trim(fbSystem::getEnvVar('SystemDrive')) . '/TEMP';	// C:\TEMP
				$dirs[] = trim(fbSystem::getEnvVar('USERPROFILE')) . '/Local Settings/TEMP';
				$dirs[] = trim(fbSystem::getEnvVar('SystemRoot'));	// C:\WINDOWS
				$dirs[] = trim(fbSystem::getEnvVar('windir'));		// C:\WINDOWS (Windows 95/98)
			} else {
				$dirs[] = '/tmp';
				$dirs[] = '/var/tmp';
			}
			
			// find a writable directory			
			foreach ($dirs as $dir) {
				if ($dir && @is_dir($dir) && is_writable($dir)) {
					break 2;
				}
			}

			// create a writable directory				
			foreach ($dirs as $dir) {
				if (!$dir || !is_writable($dir)) {
					continue;
				}
				@mkdir($dir, 0770);
				if (@is_dir($dir)) {
					break 2;
				}
			}

			$dir = false;
			break;
		}

		return $dir;
	}

	/*!
		\param $last_error \c string
		\return \c string
		\static
	*/
	function _lastError($last_error = null) {
		static $_last_error;

		if (!is_null($last_error)) {
			$_last_error = $last_error;
		}

		return $_last_error;
	}

	/*!
		\return \c string
		\static
	*/
	function getLastError() {
		return fbSystem::_lastError();
	}

	/*!
		\param $last_error \c string
		\return \c void
		\static
	*/
	function setLastError($last_error) {
		fbSystem::_lastError($last_error);
	}

	/*!
		\return \c string
		\static
	*/
	function extensionSuffix() {
		while (!defined('PHP_SHLIB_SUFFIX')) {
			if (preg_match('/^(win|os\/2)/i', PHP_OS)) {
				define('PHP_SHLIB_SUFFIX', 'dll');
				break;
			}
			if (preg_match('/netware/i', PHP_OS)) {
				define('PHP_SHLIB_SUFFIX', 'nlm');
				break;
			}
			if (preg_match('/hp-?ux/i', PHP_OS)) {
				define('PHP_SHLIB_SUFFIX', 'sl');
				break;
			}
			if (preg_match('/aix/i', PHP_OS)) {
				define('PHP_SHLIB_SUFFIX', 'a');
				break;
			}
			if (preg_match('/osx/i', PHP_OS)) {
				define('PHP_SHLIB_SUFFIX', 'bundle');
				break;
			}
			define('PHP_SHLIB_SUFFIX', 'so');
		}
		
		return PHP_SHLIB_SUFFIX;
	}

	/*!
		\param $name \c string
		\return \c bool
		\static
	*/
	function loadExtension($name) {
		if (fbSystem::isExtensionLoaded($name)) {
			return true;
		}

		if (!ini_get('enable_dl') || ini_get('safe_mode')) {
			return false;
		}

		$ext = $name;

		if (!preg_match('/\./', $ext)) {
			$ext .= '.' . fbSystem::extensionSuffix();
		}

		$track_errors = @ini_set('track_errors', true);
		$php_errormsg = '';

		@dl('php_' . $ext) || @dl($ext);

		fbSystem::_lastError($php_errormsg);
		$track_errors || @ini_set('track_errors', $track_errors);

		return fbSystem::isExtensionLoaded($name, true);
	}

	/*!
		\param $name \c string
		\param $retest \c bool
		\return \c bool
		\static
	*/
	function isExtensionLoaded($name, $retest = false) {
		static $extensions;

		if ($retest || !isset($extensions[$name])) {
			$extensions[$name] = extension_loaded($name);
		}

		return $extensions[$name];
	}

	/*!
		Append $path to the include path

		\param $path \c string
		\return \c string
		\static
	*/
	function appendIncludePath($path) {
		static $path_separator;

		$include_path = ini_get('include_path');

		if ($include_path) {
			if (!$path_separator) {
				$path_separator = fbSystem::pathSeparator();
			}

			$paths = explode($path_separator, $include_path);
			foreach ($paths as $p) {
				if ($p == $path) {
					return $include_path;
				}
			}
			$include_path .= $path_separator . $path;
		} else {
			$include_path = $path;
		}

		$rv = ini_set('include_path', $include_path);
		assert('$include_path == ini_get("include_path")');
		return $rv;
	}

	/*!
		Prepend $path to the include path

		\param $path \c string
		\return \c string
		\static
	*/
	function prependIncludePath($path) {
		static $path_separator;

		$include_path = ini_get('include_path');

		if ($include_path) {
			if (!$path_separator) {
				$path_separator = fbSystem::pathSeparator();
			}

			$paths = explode($path_separator, $include_path);
			foreach ($paths as $p) {
				if ($p == $path) {
					return $include_path;
				}
			}
			$include_path = $path . $path_separator . $include_path;
		} else {
			$include_path = $path;
		}

		$rv = ini_set('include_path', $include_path);
		assert('$include_path == ini_get("include_path")');
		return $rv;
	}

	/*!
		\param $file \c string
		\param $search_paths \c array
		\return \c bool
		\static
	*/
	function includeFile($file, $search_paths = array()) {
		$track_errors = @ini_set('track_errors', true);
		$php_errormsg = '';

		@include_once $file;

		@ini_set('track_errors', $track_errors);

		if (!$php_errormsg) {
			return true;
		}

		$pathinfo = pathinfo($file);

		if ($pathinfo['dirname']) {
			trigger_error($php_errormsg, E_USER_WARNING);

			return false;
		}

		if (!$search_paths) {
			return false;
		}

		if (!is_array($search_paths)) {
			$search_paths = array($search_paths);
		}

		$platform = fbSystem::platform();

		if (isset($search_paths[$platform])) {
			$search_path = $search_paths[$platform];
		} else {
			$search_path = $search_paths;
		}

		foreach ($search_path as $dir) {
			$path = $dir . fbSystem::directorySeparator(). $file;

			if (is_file($path)) {
				break;
			}
		}

		if (!is_file($path)) {
			trigger_error(sprintf("Can't find '%s' in '%s' or '%s'",
				$file, join(fbSystem::pathSeparator(), $search_path), ini_get('include_path')), E_USER_WARNING);
		}

		//		fbSystem::appendIncludePath($dir);
		include_once $path;

		return true;
	}

	/*!
		\param $var \c string Environment variable name
		\param $value \c string Environment variable value
		\param $case_sensitive \c bool
		\return \c bool \c true if successful, otherwise \c false
		\static
	*/
	function putEnv($var, $value, $case_sensitive = false) {
		static $warned = array();
		
		$rv = fbSystem::getEnvVar($var, $case_sensitive);
		if ($value == $rv) {
			return $rv;
		}

		if (isset($warned[$var])) {
			return false;
		}

		$track_errors = @ini_set('track_errors', true);
		$php_errormsg = '';
		@putenv($var . '=' . $value);
		if ($php_errormsg) {
			$warned[$var] = true;
			$rv = false;
			$php_ini = get_cfg_var('cfg_file_path') ? get_cfg_var('cfg_file_path') : 'php.ini';

			$safe_mode_allowed_env_vars = ini_get('safe_mode_allowed_env_vars');
			if ($safe_mode_allowed_env_vars) {
				$safe_mode_allowed_env_vars .= ',';
			}
			$safe_mode_allowed_env_vars .= $var;

			$msg = sprintf("%s\n" . 
				"To correct, add (or edit) one of the following in the [PHP] section of %s:\n" .
				"  safe_mode = Off\n" .
				"  safe_mode_allowed_env_vars = \n" .
				"  safe_mode_allowed_env_vars = %s\n", $php_errormsg, $php_ini, $safe_mode_allowed_env_vars);
			trigger_error($msg, E_USER_NOTICE);
		}
		if (!$track_errors) {
			@ini_set('track_errors', $track_errors);
		}
		
		return getenv($var);
	}
	
}

// initialize missing vars, make sure temporary directory exists 
fbSystem::_init();

/*
	\todo move to doc/ directory
Some server supplied enviroment variables are not defined in the current CGI/1.1 specification.
Only the following variables are defined there; everything else should be treated as 'vendor extensions':
 AUTH_TYPE,
 CONTENT_LENGTH,
 CONTENT_TYPE,
 GATEWAY_INTERFACE,
 PATH_INFO,
 PATH_TRANSLATED,
 QUERY_STRING,
 REMOTE_ADDR,
 REMOTE_HOST,
 REMOTE_IDENT,
 REMOTE_USER,
 REQUEST_METHOD,
 SCRIPT_NAME,
 SERVER_NAME,
 SERVER_PORT,
 SERVER_PROTOCOL and
 SERVER_SOFTWARE
*/

?>
