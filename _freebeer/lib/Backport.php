<?php

// $CVSHeader: _freebeer/lib/Backport.php,v 1.2 2004/03/07 17:51:16 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file Backport.php
	\brief Backwards compatibility functions and definitions

*/

if (defined('_fbBackport_loaded')) {
	return;
}
define('_fbBackport_loaded', 1);

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

// CLI version doesn't define these
defined('STDIN')	|| define('STDIN',  fopen('php://stdin', 'r'));
defined('STDOUT')	|| define('STDOUT', fopen('php://stdout', 'w'));
defined('STDERR')	|| define('STDERR', fopen('php://stderr', 'w'));
register_shutdown_function(
	create_function('' , 'fclose(STDIN); fclose(STDOUT); fclose(STDERR); return true;' )
);

// 5.0.0b3
defined('E_STRICT') || define('E_STRICT', 2048);

// Creates an array by using one array for keys and another for its values (PHP 5 CVS only)
// array array_combine ( array keys, array values )

if (!function_exists('array_combine')) {
	function array_combine(&$keys, &$values) {
 		if (count($keys) !== count($values) || count($keys) === 0 || count($values) === 0) {
 			return false;
 		}

 		$keys = array_values($keys);
 		$values = array_values($values);
 		$rv = array();
 		for ($i = 0; $i < count($values); ++$i) {
 			$rv[$keys[$i]] = &$values[$i];
 		}
 		return $rv;
 	}
}

// Computes the difference of arrays with additional index check which is performed by a user supplied callback function (PHP 5 CVS only)
// array_diff_uassoc()

/// \todo implement array_diff_uassoc() function

// Computes the difference of arrays by using a callback function for data comparison (PHP 5 CVS only)
// array_udiff()

/// \todo implement array_udiff() function

// Computes the difference of arrays with additional index check. (PHP 5 CVS only)
// array_udiff_assoc()

/// \todo implement array_udiff_assoc() function

// Computes the difference of arrays with additional index check. (PHP 5 CVS only)
// array_udiff_uassoc()

/// \todo implement array_udiff_uassoc() function

// Apply a user function recursively to every member of an array (PHP 5 CVS only)
// array_walk_recursive()

/// \todo implement array_walk_recursive() function

// Raise an arbitrary precision number to another, reduced by a specified modulus. (PHP 5 CVS only)
// string bcpowmod ( string x, string y, string modulus [, int scale] )

/// \todo test bcpowmod() emulation function
// if (!function_exists('bcpowmod') && function_exists('bcmod')) {
// 	function bcpowmod($x, $y, $modulus) {
// 		return bcmod(bcpow($x, $y), $modulus);
// 	}
// }

// Prints a backtrace (PHP 5 CVS only)
// void debug_print_backtrace ( )

if (!function_exists('debug_print_backtrace') && function_exists('debug_backtrace')) {
	function debug_print_backtrace() {
		$bt = debug_backtrace();
		/// \todo Flush out debug_print_backtrace()
		if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']) {
			print "\n<pre>\n";
		}
		print_r($bt);
		if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']) {
			print "\n</pre>\n";
		}
	}
}

// Return a formatted string (PHP 4 >= 4.1.0)
// string vsprintf ( string format, array args )

if (!function_exists('vsprintf')) {
	function vsprintf() {
		$args = func_get_args();
		$format = array_shift($args);
		$code = '';
		for ($i = 0; $i < count($args); ++$i) {
			if ($code) {
				$code .= ',';
			}
			$code .= '$args[' . $i . ']';
		}
		return eval('return sprintf($format, ' . $code . ');');
 	}
}

// Write a formatted string to a stream (PHP 5 CVS only)
// int fprintf ( resource handle, string format [, mixed args] )

if (!function_exists('fprintf')) {
	function fprintf() {
	   $args = func_get_args();
	   $fp = array_shift($args);
	   $format = array_shift($args);
//echo "format=$format\n";
//echo "args=";
//print_r($args);
	   return fwrite($fp, vsprintf($format, $args));
	}
}

// Write a string to a file (PHP 5 CVS only)
// int file_put_contents ( string filename, string data [, int flags [, resource context]])

// flags can take FILE_USE_INCLUDE_PATH and/or FILE_APPEND,
// however the FILE_USE_INCLUDE_PATH option should be used with caution.

defined('FILE_USE_INCLUDE_PATH')	|| define('FILE_USE_INCLUDE_PATH',		1);
defined('FILE_IGNORE_NEW_LINES')	|| define('FILE_IGNORE_NEW_LINES',		2);
defined('FILE_SKIP_EMPTY_LINES')	|| define('FILE_SKIP_EMPTY_LINES',		4);
defined('FILE_APPEND')				|| define('FILE_APPEND',				8);
defined('FILE_NO_DEFAULT_CONTEXT')	|| define('FILE_NO_DEFAULT_CONTEXT',	16);

if (!function_exists('file_put_contents')) {
	function file_put_contents($filename, $data, $flags = 0, $resource = null) {
		if ($flags) {
			/// \todo implement $flags option for file_put_contents() function
			trigger_error('file_put_contents() function doesn\'t currently support the $flags parameter', E_USER_WARNING);
		}
		if ($resource) {
			/// \todo implement $resource option for file_put_contents() function
			trigger_error('file_put_contents() function doesn\'t currently support the $resource parameter', E_USER_WARNING);
		}
		$fp = fopen($filename, 'wb');
		if (!$fp) {
			return false;
		}
		$rv = fwrite($fp, $data);
		fclose($fp);
		return $rv;
	}
}

// Generate URL-encoded query string (PHP 5 CVS only)
// string http_build_query ( array formdata [, string numeric_prefix])

/// \todo test http_build_query() emulation function

// see http://www.php.net/manual/en/function.http-build-query.php#38808

if (!function_exists('http_build_query')) {
	function http_build_query($formdata, $numeric_prefix = '') {
		return _http_build_query($formdata, $numeric_prefix);
	}
	function _http_build_query($formdata, $numeric_prefix = '', $key_prefix='') {
		if ($numeric_prefix != '' && !is_numeric($numeric_prefix)) {
			$prefix = $numeric_prefix;
		} else {
			$prefix = '';
		}
		if (!is_array($formdata)) {
			return '';
		}
		$str = '';
		foreach($formdata as $key => $val) {
			if (is_numeric($key)) {
				$key = $prefix.$key;
			}
			if ($str != '') {
				$str .= '&';
			}
			if ($key_prefix != '') {
				$mykey = $key_prefix ."[$key]";
			} else {
				$mykey = &$key;
			}
			if (is_array($val)) {
				$str .= _http_build_query($val, '', $mykey);
			} else {
				$str .= $mykey . '=' . urlencode($val);
			}
		}
		return $str;
	}
}

// idate() - Format a local time/date as integer 

/// \todo implement idate() function

// proc_nice --  Change the priority of the current process  (PHP 5 CVS only)
// bool proc_nice ( int priority)

if (!function_exists('proc_nice')) {
	function proc_nice($priority) {
		if (preg_match('/^win/i', PHP_OS)) {
		 	return false;
		}
		$cmd = sprintf('renice +%d %d', $priority, getmypid());
		exec($cmd, $dummy, $rv);
		return $rv == 0;
	}

	//You also need a shutdown function if you don't want to leave your http deamons with a modified priority
	function _proc_nice_shutdown_function() {
		// Restore priority
		proc_nice(0);
	}

	register_shutdown_function('_proc_nice_shutdown_function');
}

// Convert a string to an array (PHP 5 CVS only)
// array str_split ( string string [, int split_length])

/// \todo implement str_split() function

// Search a string for any of a set of characters (PHP 5 CVS only)
// strpbrk()

/// \todo implement strpbrk() function

// Binary safe optionally case insensitive comparison of 2 strings from an offset, up to length characters (PHP 5 CVS only)
// int substr_compare ( string main_str, string str, int offset [, int length [, bool case_sensitivity]])

/// \todo implement substr_compare() function

// Delay for a number of seconds and nano seconds (PHP 5 CVS only)
// time_nanosleep()

/// \todo implement time_nanosleep() function

// Tells whether the filename is executable (PHP 3, PHP 4 ) 
// bool is_executable ( string filename )
// is_executable() became available with Windows in PHP version 5.0.0. 

if (!function_exists('is_executable')) {
	function is_executable($filename) {
		return is_file($filename) && preg_match('/\.(com|exe)$/i', $filename);
	}
}

// List files and directories inside the specified path (PHP 5 CVS only)
// array scandir ( string directory [, int sorting_order] )

if (!function_exists('scandir')) {
	function scandir($directory, $sorting_order = 0) {
		$files = array();
		$fh = opendir($directory);
		if (!$fh) {
			return false;
		}
		while (($filename = readdir($fh)) !== false) {
			array_push($files, $filename);
		}
		closedir($fh);
		if ($sorting_order) {
			rsort($files);
		} else {
			sort($files);
		}
		return $files;
	}
}

// Find position of first occurrence of a case-insensitive string (PHP 5 CVS only)
// int stripos ( string haystack, string needle [, int offset] )

if (!function_exists('stripos')) {
	function stripos($haystack, $needle, $offset = 0) {
		return strpos(strtoupper($haystack), strtoupper($needle), $offset);
	}
}

// Find position of last occurrence of a case-insensitive string in a string (PHP 5 CVS only)
// int strripos ( string haystack, string needle )

if (!function_exists('strripos')) {
	function strripos($haystack, $needle) {
		return strrpos(strtoupper($haystack), strtoupper($needle));
	}
}

// Case-insensitive version of str_replace. (PHP 5 CVS only)
// mixed str_ireplace ( mixed search, mixed replace, mixed subject [, int &count] )

if (!function_exists('str_ireplace')) {
	function str_ireplace($search, $replace, $subject, $count = null) {
		if (!is_null($count)) {
			trigger_error('str_ireplace() does not currently support a $count parameter');
			return false;
		}

		if (!is_array($search)) {
			$search = array($search);
		}

		if (!is_array($replace)) {
			if (!is_array($search)) {
				$replace = array($replace);
			} else {
				// this will duplicate the string into an array the size of $search
				$c = count($search);
				$rString = $replace;
				unset($replace);
				for ($i = 0; $i < $c; ++$i) {
					$replace[$i] = $rString;
				}
			}
		}
		foreach($search as $fKey => $fItem) {
			$between = explode(strtolower($fItem), strtolower($subject));
			$pos = 0;
			foreach($between as $bKey => $bItem) {
				$between[$bKey] = substr($subject, $pos, strlen($bItem));
				$pos += strlen($bItem) + strlen($fItem);
			}
			$subject = implode($replace[$fKey], $between);
		}
		return($subject);
	}
}

// Convert a string to an array (PHP 5 CVS only)
// array str_split ( string string [, int split_length] )

if (!function_exists('str_split')) {
	function str_split($string, $split_length = 1) {
		$split_length = (int) $split_length;
		if (!$split_length) {
			return false;
		}

		$rv = array();
		$offset = 0;
		$len = strlen($string);
		while ($offset < $len) {
			$rv[] = substr($string, $offset, $split_length);
			$offset += $split_length;
		}

		return $rv;
	}
}

// Delay execution in microseconds (PHP 3, PHP 4 ) 
// void usleep ( int micro_seconds )
// wasn't available on Windows until ?

if (!function_exists('usleep')) {
	defined('FB_USLEEP_PORT') || define('FB_USLEEP_PORT', 31238);
	function usleep($micro_seconds) {
		@fsockopen('tcp://localhost', FB_USLEEP_PORT, $errno, $errstr, $micro_seconds);
	}
}

// Computes the difference of arrays with additional index check (PHP 4 CVS only)
// array array_diff_assoc ( array array1, array array2 [, array ...] )

if (!function_exists('array_diff_assoc')) {
	function array_diff_assoc($array, $array2) {
		if (func_num_args() > 2) {
			$args = func_get_args();
			array_shift($args);
			array_shift($args);
			foreach ($args as $arg) {
				$array2 = array_merge($array2, $arg);
			}
		}
		foreach ($array as $k => $v) {
			foreach ($array2 as $k2 => $v2) {
				if ($k == $k2 && $v == $v2) {
					unset($array[$k]);
				}
			}
		}
		return $array;
	}
}

// Computes the intersection of arrays with additional index check (PHP 4 CVS only)
// array array_intersect_assoc ( array array1, array array2 [, array ...] )

// if (!function_exists('array_intersect_assoc')) {
// 	function array_intersect_assoc() {
// 		trigger_error('Function not implemented: array_intersect_assoc()');
/// \todo Implement function array_intersect_assoc()
// 	}
// }

// Reads entire file into a string (PHP 4 CVS only)
// string file_get_contents ( string filename [, int use_include_path] )

if (!function_exists('file_get_contents')) {
	function file_get_contents($filename, $use_include_path = false) {
		if ($use_include_path) {
			trigger_error('file_get_contents($filename, true) is not currently supported', E_USER_WARNING);
		}
		$fp = fopen($filename, 'rb');
		if (!$fp) {
			return false;
		}
		$size = filesize($filename);
		if ($size) {
			$rv = fread($fp, $size);
		} else {
			$rv = '';
			while (!feof($fp)) {
			   $rv .= fread ($fp, 4096);
			}
		}
		fclose($fp);
		clearstatcache();
		return $rv;
	}
}

// Match filename against a pattern (PHP 4 CVS only)
// array fnmatch ( string pattern, string string [, int flags] )

// if (!function_exists('fnmatch')) {
// 	function fnmatch($pattern, $string, $flags = null) {
// 		trigger_error('Function not implemented: fnmatch()');
/// \todo Implement function fnmatch()
// 	}
// }

// Find pathnames matching a pattern (PHP 4 CVS only)
// array glob ( string pattern [, int flags] )

// if (!function_exists('glob')) {
// 	function glob($pattern, $flags = null) {
// 		trigger_error('Function not implemented: glob()');
/// \todo Implement function glob()
// 	}
// }

// Convert all HTML entities to their applicable characters (PHP 4 CVS only)
// string html_entity_decode ( string string [, int quote_style [, string charset]] )

// if (!function_exists('html_entity_decode')) {
// 	function html_entity_decode($string, $quote_style = null, $charset = null) {
// 		trigger_error('Function not implemented: html_entity_decode()');
/// \todo Implement function html_entity_decode()
// 	}
// }

// Compares two "PHP-standardized" version number strings (PHP 4 >= 4.1.0)
// int version_compare ( string version1, string version2 [, string operator ] )

if (!function_exists('version_compare')) {
	function version_compare($ver1, $ver2) {
		// \q why won't this work in all cases?
		return strcmp($ver1, $ver2);
// 		$v1 = (int) str_replace('.', '', $ver1);
// 		$v2 = (int) str_replace('.', '', $ver2);
// 		if ($v1 > $v2) {
// 			return 1;
// 		}
// 		if ($v1 < $v2) {
// 			return -1;
// 		}
// 		return 0;
	}
}

if (version_compare(phpversion(), '4.3.0') >= 0) {
	return;
}

// PATH_SEPARATOR appeared in 4.3.0
while (!defined('PATH_SEPARATOR')) {
	if (preg_match('/^(win|os\/2)/i', PHP_OS)) {
		define('PATH_SEPARATOR', ';');
		break;
	}

	define('PATH_SEPARATOR', ':');
}

// PHP_SHLIB_SUFFIX appeared in 4.3.0
while (!defined('PHP_SHLIB_SUFFIX')) {
	if (preg_match('/^(win|os\/2)/i', PHP_OS)) {
		define('PHP_SHLIB_SUFFIX', 'dll');
		break;
	}
	if (preg_match('/netware/i', PHP_OS)) {
		define('PHP_SHLIB_SUFFIX', 'nlm');
		break;
	}
	if (preg_match('/hpux/i', PHP_OS)) {
		define('PHP_SHLIB_SUFFIX', 'sl');
		break;
	}
	if (preg_match('/darwin/i', PHP_OS)) {
		define('PHP_SHLIB_SUFFIX', 'dylib');
		break;
	}

	define('PHP_SHLIB_SUFFIX', 'so');
}

// these constants appeared in 4.3.0
defined('UPLOAD_ERR_OK')		|| define('UPLOAD_ERR_OK', 0);
defined('UPLOAD_ERR_INI_SIZE')	|| define('UPLOAD_ERR_INI_SIZE', 1);
defined('UPLOAD_ERR_FORM_SIZE')	|| define('UPLOAD_ERR_FORM_SIZE', 2);
defined('UPLOAD_ERR_PARTIAL')	|| define('UPLOAD_ERR_PARTIAL', 3);
defined('UPLOAD_ERR_NO_FILE')	|| define('UPLOAD_ERR_NO_FILE', 4);

// Calculate the sha1 hash of a string (PHP 4 >= 4.3.0)
// string sha1 ( string str [, bool raw_output] )

if (!function_exists('sha1')) {
	include_once FREEBEER_BASE . '/lib/Backport/SHA1.php';
}

// Calculate the sha1 hash of a file (PHP 4 >= 4.3.0)
// string sha1_file ( string filename [, bool raw_output] )

if (!function_exists('sha1_file')) {
	function sha1_file($file) {
		$rv = file_get_contents($file);
		return $rv ? sha1($rv) : false;
	}
}

// Generates a backtrace (PHP 4 >= 4.3.0)
// array debug_backtrace ( void)

// if (!function_exists('debug_backtrace')) {
// 	function debug_backtrace() {
// 		trigger_error('Function not implemented: debug_backtrace()');
/// \todo Implement debug_backtrace() using apd functions if available
// 	}
// }

// Retrieve information about the currently installed GD library (PHP 4 >= 4.3.0)
// array gd_info ( void)

// if (!function_exists('gd_info')) {
// 	function gd_info() {
// 		trigger_error('Function not implemented: gd_info()');
/// \todo implement gd_info() via phpinfo()'s output
// 	}
// }

// Gets the current include_path configuration option (PHP 4 >= 4.3.0) 
// string get_include_path ( )

if (!function_exists('get_include_path')) {
	function get_include_path() {
		return ini_get('include_path');
	}
}

// Gets options from the command line argument list () (PHP 4 >= 4.3.0)
// string getopt ( string options )

// if (!function_exists('getopt')) {
// 	function getopt($options) {
// 		trigger_error('Function not implemented: getopt()');
/// \todo implement getopt()
// 	}
// }

// Formats a number as a currency string (PHP 4 >= 4.3.0) 
// string money_format ( string format, float number )

// if (!function_exists('money_format')) {
// 	function money_format($format, $number) {
// 		trigger_error('Function not implemented: money_format()');
/// \todo implement money_format()
// 	}
// }

// Restores the value of the include_path configuration option (PHP 4 >= 4.3.0) 
// void restore_include_path ( )

if (!function_exists('restore_include_path')) {
	function restore_include_path() {
		return ini_restore('include_path');
	}
}

// Sets the include_path configuration option (PHP 4 >= 4.3.0) 
// string set_include_path ( string new_include_path )

if (!function_exists('set_include_path')) {
	function set_include_path($new_include_path) {
		return ini_set('include_path', $new_include_path);
	}
}

// Return information about words used in a string ()  (PHP 4 >= 4.3.0)
// mixed str_word_count ( string string [, int format] )

// if (!function_exists('str_word_count')) {
// 	function str_word_count($string, $format = null) {
// 		trigger_error('Function not implemented: str_word_count()');
/// \todo implement str_word_count()
// 	}
// }

if (version_compare(phpversion(), '4.2.0') >= 0) {
	return;
}

// Returns an array with all string keys lowercased or uppercased (PHP 4 >= 4.2.0)
// array array_change_key_case ( array input [, int case] )

defined('CASE_LOWER')	|| define('CASE_LOWER', 0);
defined('CASE_UPPER')	|| define('CASE_UPPER', 1);

if (!function_exists('array_change_key_case')) {
	function array_change_key_case($array, $case = CASE_LOWER) {
		if (is_array($array)) {
			$rv = array();
			switch ($case) {
				case CASE_UPPER:
					foreach($array as $key => $value) {
						$rv[strtoupper($key)] = $value;
					}
					break;

				default:
					foreach($array as $key => $value) {
						$rv[strtolower($key)] = $value;
					}
			}
			return $rv;
		} else {
			return $array;
		}
	}
}

// Split an array into chunks (PHP 4 >= 4.2.0)
// array array_chunk ( array input, int size [, bool preserve_keys] )

if (!function_exists('array_chunk')) {
	function array_chunk($array, $size, $preserve_keys = false) {
		$r = array();
		$ak = array_keys($array);
		$i = 0;
		$sc = 0;
		for ($x = 0; $x < count($ak); ++$x) {
			if ($i == $size) {
				$i = 0;
				++$sc;
			}
			$k = ($preserve_keys) ? $ak[$x] : $i;
			$r[$sc][$k] = $array[$ak[$x]];
			++$i;
		}
		return $r;
	}
}

// Fill an array with values (PHP 4 >= 4.2.0)
// array array_fill ( int start_index, int num, mixed value )

if (!function_exists('array_fill')) {
	function array_fill($start_index, $num, $value) {
		$rv = array();
		$end = $start_index + $num;
		for ($i = $start_index; $i < $end; ++$i) {
			$rv[$i] = $value;
		}
		return $rv;
	}
}

// Get float value of a variable (PHP 4 >= 4.2.0)
// float floatval ( mixed var )

if (!function_exists('floatval')) {
	function floatval($var) {
		$var = trim($var);

		$f = '';
		$e = false;
		$dot = false;
		$minus = false;
		$plus = false;
		$l = strlen($var);

		for ($i = 0; $i < $l; ++$i) {
			$c = substr($var, $i, 1);
			if ($c == '+') {
				if ($plus) {
					break;
				}
				$plus = true;
			}
			if ($c == '-') {
				if ($minus) {
					break;
				}
				$minus = true;
			}
			if ($c == '.') {
				if ($dot) {
					break;
				}
				$dot = true;
			}
			if (strpos('eE', $c) !== false) {
				if ($e) {
					break;
				}
				$e = true;
				$minus = false;
				$plus = false;
			}
			if (strpos('+-0123456789.eE', $c) === false) {
				break;
			}

			$f .= $c;
		}

// Be aware if you use is_numeric() or is_float() after using set_locale(LC_ALL,'lang') or set_locale(LC_NUMERIC,'lang')
//		return (float) (is_numeric($f) ? $f : 0);

		return (float) $f;
	}
}

// Returns the floating point remainder (modulo) of the division of the arguments (PHP 4 >= 4.2.0)
// float fmod ( float x, float y )

if (!function_exists('fmod')) {
	function fmod($x, $y) {
       $i = floor($x/$y);
       // r = x - i * y
       return $x - $i*$y;
 	}
}

// Gets all configuration options (PHP 4 >= 4.2.0)
// array ini_get_all ( [string extension] )

// impossible to emulate

// Convert a pathname and a project identifier to a System V IPC key (PHP 4 >= 4.2.0)
// int ftok ( string pathname, string proj )

if (!function_exists('is_a')) {
	function is_a($object, $classname) {
		return ((strtolower($classname) == get_class($object)) ||
			(is_subclass_of($object, $classname)));
	}
}

// (PHP 4 >= 4.2.0) 
// bool is_finite ( float val )

if (!function_exists('is_finite')) {
	function is_finite($val) {
		return (abs($val) != abs(log(0)));
	}
}

// (PHP 4 >= 4.2.0) 
// bool is_infinite ( float val )

if (!function_exists('is_infinite')) {
	function is_infinite($val) {
		return (abs($val) == abs(log(0)));
	}
}

// Returns TRUE if val is 'not a number', like the result of acos(1.01). (PHP 4 >= 4.2.0)
// bool is_nan ( float val )

if (!function_exists('is_nan')) {
	function is_nan($val) {
		return ($val === acos(1.01));
	}
}

// Calculates the md5 hash of a given filename (PHP 4 >= 4.2.0)
// string md5_file ( string filename )

if (!function_exists('md5_file')) {
	function md5_file($file) {
		$rv = file_get_contents($file);
		return $rv ? md5($rv) : false;
	}
}

// Perform the rot13 transform on a string (PHP 4 >= 4.2.0)
// string str_rot13 ( string str )

if (!function_exists('str_rot13')) {
	function str_rot13($str) {
		static $from = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		static $to   = 'nopqrstuvwxyzabcdefghijklmNOPQRSTUVWXYZABCDEFGHIJKLM';

		return strtr($str, $from, $to);
	}
}

// Split given source in tokens (PHP 4 >= 4.2.0)
// array token_get_all ( string source )

// if (!function_exists('token_get_all')) {
// 	function token_get_all($string) {
// 		trigger_error('Function not implemented: token_get_all()');
/// \todo implement token_get_all()
// 	}
// }

// Get the name of a given token (PHP 4 >= 4.2.0)
// string token_name ( int type )

// if (!function_exists('token_name')) {
// 	function token_name($string) {
// 		trigger_error('Function not implemented: token_name()');
/// \todo implement token_name()
// 	}
// }

// Outputs or returns a string representation of a variable (PHP 4 >= 4.2.0)
// mixed var_export ( mixed expression [, bool return] )

if (!function_exists('var_export')) {
	function var_export($expression, $return) {
		if ($return) {
			ob_start();
		}
		print_r($expression);
		if ($return) {
			$data = ob_get_contents();
			ob_end_clean();
			return $data;
		}
	}
}

if (version_compare(phpversion(), '4.1.0') >= 0) {
	return;
}

// NOTE: In versions of PHP prior to 4.1.0,
// you will need to add
//   global $_SERVER;
// inside a function to access this additional variables.
//
// For example,
//
// function myfunc() {
// 	global $_SERVER;
//
// 	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
// 	...
// }

if (!isset($GLOBALS['_COOKIE']) && isset($GLOBALS['HTTP_COOKIE_VARS'])) {
	$GLOBALS['_COOKIE'] = &$GLOBALS['HTTP_COOKIE_VARS'];
}

if (!isset($GLOBALS['_ENV']) && isset($GLOBALS['HTTP_ENV_VARS'])) {
	$GLOBALS['_ENV'] = &$GLOBALS['HTTP_ENV_VARS'];
}

if (!isset($GLOBALS['_FILES']) && isset($GLOBALS['HTTP_POST_FILES'])) {
	$GLOBALS['_FILES'] = &$GLOBALS['HTTP_POST_FILES'];
}

if (!isset($GLOBALS['_GET']) && isset($GLOBALS['HTTP_GET_VARS'])) {
	$GLOBALS['_GET'] = &$GLOBALS['HTTP_GET_VARS'];
}

if (!isset($GLOBALS['_POST']) && isset($GLOBALS['HTTP_POST_VARS'])) {
	$GLOBALS['_POST'] = &$GLOBALS['HTTP_POST_VARS'];
}

if (!isset($GLOBALS['_SERVER']) && isset($GLOBALS['HTTP_SERVER_VARS'])) {
	$GLOBALS['_SERVER'] = &$GLOBALS['HTTP_SERVER_VARS'];
}

if (!isset($GLOBALS['_SESSION']) && isset($GLOBALS['HTTP_SESSION_VARS'])) {
	$GLOBALS['_SESSION'] = &$GLOBALS['HTTP_SESSION_VARS'];
}

if (!isset($GLOBALS['_REQUEST'])) {
	/// \todo use gpc_order order to merge?
	$GLOBALS['_REQUEST'] = array();

	if (isset($GLOBALS['HTTP_GET_VARS'])) {
		$GLOBALS['_REQUEST'] = array_merge($GLOBALS['_REQUEST'], $GLOBALS['HTTP_GET_VARS']);
	}
	if (isset($GLOBALS['HTTP_POST_VARS'])) {
		$GLOBALS['_REQUEST'] = array_merge($GLOBALS['_REQUEST'], $GLOBALS['HTTP_POST_VARS']);
	}
	if (isset($GLOBALS['HTTP_COOKIE_VARS'])) {
		$GLOBALS['_REQUEST'] = array_merge($GLOBALS['_REQUEST'], $GLOBALS['HTTP_COOKIE_VARS']);
	}
}

// Checks if the given key or index exists in the array (PHP 4 >= 4.1.0)
// bool array_key_exists ( mixed key, array search )

if (!function_exists('array_key_exists')) {
	// name of this function is key_exists() in PHP version 4.0.6.
	if (function_exists('key_exists')) {
		function array_key_exists($key, &$array) {
			return key_exists($key, $array);
		}
	} else {
		function array_key_exists($key, &$array) {
			return in_array($key, array_keys($array));
		}
	}
}

// Returns exp(number) - 1, computed in a way that is accurate even when the value of number is close to zero (PHP 4 >= 4.1.0) 
// float expm1 ( float number )

if (!function_exists('expm1')) {
	function expm1($number) {
	    return ($number > -1.0e-6 && $number < 1.0e-6) ? ($number + $number * $number / 2.0) : (exp($number) - 1.0);
	}
}

// Returns sqrt( num1*num1 + num2*num2) (PHP 4 >= 4.1.0) 
// float hypot ( float num1, float num2 )

if (!function_exists('hypot')) {
	function hypot($num1, $num2) {
	    return sqrt($num1 * $num1 + $num2 * $num2);
	}
}

// Import GET/POST/Cookie variables into the global scope (PHP 4 >= 4.1.0)
// bool import_request_variables ( string types [, string prefix] )

// if (!function_exists('import_request_variables')) {
// 	function import_request_variables($types, $prefix = null) {
// 		trigger_error('Function not implemented: import_request_variables()');
/// \todo implement import_request_variables()
// 	}
// }

// Returns log(1 + number), computed in a way that accurate even when the val ue of number is close to zero (PHP 4 >= 4.1.0) 
// float log1p ( float number )

if (!function_exists('log1p')) {
	function log1p($number) {
		return ($x > -1.0e-8 && $x < 1.0e-8) ? ($x - $x * $x / 2.0) : log(1.0 + $x);
	}
}


// Output a formatted string (PHP 4 >= 4.1.0)
// void vprintf ( string format, array args )

if (!function_exists('vprintf')) {
	function vprintf() {
		$args = func_get_args();
		$format = array_shift($args);
		$code = '';
		for ($i = 0; $i < count($args); ++$i) {
			if ($code) {
				$code .= ',';
			}
			$code .= '$args[' . $i . ']';
		}
		eval('printf($format, ' . $code . ');');
 	}
}

if (version_compare(phpversion(), '4.0.6') >= 0) {
	return;
}

// DIRECTORY_SEPARATOR appeared in 4.0.6
while (!defined('DIRECTORY_SEPARATOR')) {
	if (preg_match('/^(win|os\/2)/i', PHP_OS)) {
		define('DIRECTORY_SEPARATOR', '\\');
		break;
	}

	define('DIRECTORY_SEPARATOR', '/');
}

// Filters elements of an array using a callback function (PHP 4 >= 4.0.6)
// array array_filter ( array input [, callback function] )

// if (!function_exists('array_filter')) {
// 	function array_filter($input, $callback) {
// 		trigger_error('Function not implemented: array_filter()');
/// \todo implement array_filter()
// 	}
// }

// Applies the callback to the elements of the given arrays (PHP 4 >= 4.0.6)
// array array_map ( callback function, array arr1 [, array arr2...] )

// if (!function_exists('array_map')) {
// 	function array_map($callback, $arr1) {
// 		trigger_error('Function not implemented: array_map()');
/// \todo implement array_map()
// 	}
// }

// Find out whether the argument is a valid callable construct (PHP 4 >= 4.0.6)
// bool is_callable ( mixed var [, bool syntax_only [, string callable_name]] )

// if (!function_exists('is_callable')) {
// 	function is_callable($var, $syntax_only = false, $callable_name = null) {
// 		trigger_error('Function not implemented: is_callable()');
/// \todo implement is_callable()
// 	}
// }

if (version_compare(phpversion(), '4.0.5') >= 0) {
	return;
}

// Iteratively reduce the array to a single value using a callback function (PHP 4 >= 4.0.5)
// mixed array_reduce ( array input, callback function [, int initial] )

// if (!function_exists('array_reduce')) {
// 	function array_reduce($input, $callback, $initial = null) {
// 		trigger_error('Function not implemented: array_reduce()');
/// \todo implement array_reduce()
// 	}
// }

// Searches the array for a given value and returns the corresponding key if successful (PHP 4 >= 4.0.5)
// mixed array_search ( mixed needle, array haystack [, bool strict] )

if (!function_exists('array_search')) {
	function array_search($needle, $haystack) {
		foreach ($haystack as $key => $value) {
			if ($value == $needle) {
				return $key;
			}
		}
		return false;
	}
}

// Finds whether a variable is a scalar (PHP 4 >= 4.0.5)
// bool is_scalar ( mixed var )

if (!function_exists('is_scalar')) {
	function is_scalar($n) {
		return (is_numeric($n) || is_string($n));
	}
}

if (version_compare(phpversion(), '4.0.4') >= 0) {
	return;
}

// Calculate the sum of values in an array. (PHP 4 >= 4.0.4)
//mixed array_sum ( array array )

if (!function_exists('array_sum')) {
	function array_sum($a) {
		$rv = 0;
		foreach ($a as $key => $val) {
			if (is_numeric($val)) {
				$rv += $val;
			}
		}
		return $rv;
	}
}

// Call a user function given with an array of parameters (PHP 4 >= 4.0.4)
// mixed call_user_func_array ( callback function [, array param_arr] )

if (!function_exists('call_user_func_array')) {
	function call_user_func_array($function, $param_arr) {
		$funcCall = $function . '(';
		// Build function call string by adding each parameter
		for ($i = 0; $i < sizeof($param_arr); ++$i) {
			eval("\$funcCall .= '\$param_arr[$i],';");
		}
		// Strip off last comma ','
		$funcCall = substr($funcCall, 0, -1) . ')';
		eval("\$rv = $funcCall;");
		return $rv;
	}
}

// Returns the value of a constant (PHP 4 >= 4.0.4)
// mixed constant ( string name )

if (!function_exists('constant')) {
	/// \todo test constant() function
	function constant($c) {
		return @eval('@return ' . $c);
	}
}

// Finds whether a variable is NULL (PHP 4 >= 4.0.4)
// bool is_null ( mixed var )

if (!function_exists('is_null')) {
	function is_null($n) {
		return $n === null;
	}
}

if (version_compare(phpversion(), '4.0.3') >= 0) {
	return;
}

// escape a string to be used as a shell argument (PHP 4 >= 4.0.3)
// string escapeshellarg ( string arg )

// Tells whether the file was uploaded via HTTP POST (PHP 3>= 3.0.17, PHP 4 >= 4.0.3)
// bool is_uploaded_file ( string filename )

// Returns information about a file path (PHP 4 >= 4.0.3)
// array pathinfo ( string path )

if (version_compare(phpversion(), '4.0.2') >= 0) {
	return;
}

// Returns information about the operating system PHP was built on (PHP 4 >= 4.0.2)
// string php_uname ( )

if (!function_exists('php_uname')) {
	function php_uname($s) {
		static $uname;

		if (!isset($uname)) {
			if (function_exists('posix_uname')) {
				$uname = posix_uname();
			} else {
				$uname = '';
			}
		}

		return $uname;
	}
}

// Binary safe case-insensitive string comparison of the first n characters (PHP 4 >= 4.0.2)
// int strncasecmp ( string str1, string str2, int len )

if (!function_exists('strncasecmp')) {
	function strncasecmp($str1, $str2, $len) {
		return strcasecmp(substr($str1, 0, $len), substr($str2, 0, $len));
	}
}

if (version_compare(phpversion(), '4.0.1pl2') >= 0) {
	return;
}

// require_once() was added in PHP 4.0.1pl2
// include_once() was added in PHP 4.0.1pl2

if (version_compare(phpversion(), '4.0.1') >= 0) {
	return;
}

// Computes the difference of arrays (PHP 4 >= 4.0.1)
// array array_diff ( array array1, array array2 [, array ...] )

// Computes the intersection of arrays (PHP 4 >= 4.0.1)
// array array_intersect ( array array1, array array2 [, array ...] )

// Merge two or more arrays recursively (PHP 4 >= 4.0.1)
// array array_merge_recursive ( array array1, array array2 [, array ...] )

// Removes duplicate values from an array (PHP 4 >= 4.0.1)
// array array_unique ( array array )

if (!function_exists('array_unique')) {
	function array_unique(&$thearray) {
		sort($thearray);
		reset($thearray);
		$newarray = array();
		$i = 0;
		$element = current($thearray);
		for ($n = 0; $n < sizeof($thearray); ++$n) {
			if (next($thearray) != $element) {
				$newarray[$i] = $element;
				$element = current($thearray);
				++$i;
			}
		}
		$thearray = $newarray;
	}
}

// Create an anonymous (lambda-style) function (PHP 4 >= 4.0.1)
// string create_function ( string args, string code )

// Flushes the output to a file (PHP 4 >= 4.0.1)
// int fflush ( int fp )

// Parses input from a file according to a format (PHP 4 >= 4.0.1)
// mixed fscanf ( int handle, string format [, string var1] )

// Pad a string to a certain length with another string (PHP 4 >= 4.0.1)
// string str_pad ( string input, int pad_length [, string pad_string [, int pad_type]] )

?>
