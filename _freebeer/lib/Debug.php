<?php

// $CVSHeader: _freebeer/lib/Debug.php,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

// Copyright (c) 2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

// Copyright (c) 2001-2003, Bold New World, Inc. (http://bnw.com/) All rights reserved.  Used by permission.

/*!
	\file Debug.php
	\brief Debugging support class
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

require_once FREEBEER_BASE . '/lib/System.php';

/*!
	\todo redo this whole class using PEAR::Log, then add tracing details using debug_backtrace()
	\todo rename to fbLog
	\todo add fbLog::dump($var) => $var (type) = 'string'
	\todo add fbLog::enter($text) =>  Entering class::function($param1, $param2): $text
	\todo add fbLog::exit($retval) => Leaving  class::function(): returning $retval
	
	
	\todo review PEAR::Log class to see if it fits our needs
	
	\todo define debug API per changes described below
	
	\todo separate database related functions to subclass fbDebug_DB in Debug/DB.php

	\todo move adodb specific debug code to ADOdb/ADOdb.php
	
	\todo get JavaScript output working

	\todo clean up HTML/text logic

		have code automatically sense if inside browser or not	

	\todo add 
	
		Date
		Time
		Filename
		Line
		Function
		Class

	as options to log message

		FB_DEBUG_LOG_DATETIME
		FB_DEBUG_LOG_TIME
		FB_DEBUG_LOG_FILE
		FB_DEBUG_LOG_FUNCTION
		FB_DEBUG_LOG_CLASS
	
	\todo allow debug (and error_handler) log output to one or more destinations:

		file://example.log
		mailto:example@example.com
		javascript:
		syslog
		stderr
		stdout
		/dev/null

	\todo utilize syslog logging levels?
	
		LOG_EMERG system is unusable 
		LOG_ALERT action must be taken immediately 
		LOG_CRIT critical conditions 
		LOG_ERR error conditions 
		LOG_WARNING warning conditions 
		LOG_NOTICE normal, but significant, condition 
		LOG_INFO informational message 
		LOG_DEBUG debug-level message 

(these can be imported via import_syslog_variables())

	then add

	fbDebug::log(LOG_DEBUG, 'message');
	
	-or-
	
	fbDebug::debug('message');
	fbDebug::info('message');
		
Usage:

\code

if (fbDebug::isUser('ross')) {
	error_reporting(2047);
	fbDebug::setSite('harlan');
	fbDebug::setLevel(FB_DEBUG_HTML | FB_DEBUG_SQL_ALL);
}

....

fbDebug::dump($var, 'varname');

if (fbDebug::debugging()) {
	...
}

\endcode

*/

/*!
	\enum FB_DEBUG_OFF
	Turn off debugging, fbDebug::debugging() will return \c false.
	This is the default if not overwritten by fbDebug::setLevel()
*/
define('FB_DEBUG_OFF',         	0x0001);                   

/*!	
	\enum FB_DEBUG_NONE
	Backwards compatibility
*/
define('FB_DEBUG_NONE',         FB_DEBUG_OFF);			   

/*!
	\enum FB_DEBUG_LOG
	Send debugging to ~/tmp/mercury.log or /tmp/$USER.log
*/
define('FB_DEBUG_LOG',          0x0002);                   

/*!
	\enum FB_DEBUG_HTML
	Send debugging to browser in HTML
*/
define('FB_DEBUG_HTML',         0x0004);                   

/*!
	\enum FB_DEBUG_TEXT
	Send debugging to stdout in plain text
*/
define('FB_DEBUG_TEXT',         0x0008);                   

/*!
	\enum FB_DEBUG_JAVASCRIPT
	Send debugging to javascript window
*/
define('FB_DEBUG_JAVASCRIPT',   0x0010);                   

/*!
	\enum FB_DEBUG_SQL
	Display SQL code
*/
define('FB_DEBUG_SQL',          0x0020);                   

/*!
	\enum FB_DEBUG_SELECT
	Display 1st 1000 rows of SELECT statements
*/
define('FB_DEBUG_SELECT',       0x0040);                   

/*!
	\enum FB_DEBUG_EXPLAIN
	EXPLAIN SQL SELECT statements
*/
define('FB_DEBUG_EXPLAIN',      0x0080 | FB_DEBUG_SQL);    

/*!
	\enum FB_DEBUG_TIME_SQL
	Display elapsed time for SQL queries
*/
define('FB_DEBUG_TIME_SQL',     0x0100 | FB_DEBUG_SQL);    

/*!
	\enum FB_DEBUG_SQL_ALL
	Display all of the above
*/
define('FB_DEBUG_SQL_ALL',      FB_DEBUG_SQL | FB_DEBUG_SELECT | FB_DEBUG_EXPLAIN | FB_DEBUG_TIME_SQL);	

/*!
	\enum FB_DEBUG_EMAIL
	Send all emails to $USER@bnw.com
*/
define('FB_DEBUG_EMAIL',        0x0200);                   

/*!
	\enum FB_DEBUG_SMARTY
	Turn on smarty debugging
*/
define('FB_DEBUG_SMARTY',       0x0400);                   

/*!
	\enum FB_DEBUG_TRACE
	Turn on trace statements
*/
define('FB_DEBUG_TRACE',     	0x0800);                   

/*!
	\enum FB_DEBUG_NO_STACKDUMP
	Turn off stack dumps
*/
define('FB_DEBUG_NO_STACKDUMP', 0x1000);                   

/*!
	\enum FB_DEBUG_NO_ASSERTS
	Turn off assert statements
*/
define('FB_DEBUG_NO_ASSERTS',   0x2000);                   

/*!
	\enum FB_DEBUG_ADODB
	Turn on ADOdb debugging
*/
define('FB_DEBUG_ADODB',		0x4000);					

/*!
	\enum FB_DEBUG_DEPRECATED
	Display depreciateds to the screen
*/
define('FB_DEBUG_DEPRECATED',	0x8000);					

/*!
	\enum FB_DEBUG_DEFAULT
	The default debug level if not set to something else
*/
define('FB_DEBUG_DEFAULT',		FB_DEBUG_OFF | FB_DEBUG_LOG);

/*!
	\enum FB_DEBUG_ALL
	Debug everything (except javascript, which isn't fully debugged) (not recommended) 
*/
define('FB_DEBUG_ALL',          0xffff & ~FB_DEBUG_NO_STACKDUMP & ~FB_DEBUG_NO_ASSERTS & ~FB_DEBUG_JAVASCRIPT);	

/*!
	\class fbDebug
	\brief Debugging support class

	\static
*/
class fbDebug {
	// all functions are class functions
	
	/*!
		Constructor
		\static
	*/
	function fbDebug() {
		if (func_num_args()) {
			fbDebug::_level(func_get_arg(0));
		}
	}

	/*!
		\private
		\static
	*/
	function _level() {
		static $level = FB_DEBUG_DEFAULT;

		if (func_num_args()) {
			$level = func_get_arg(0);
//fbDebug::dump($level, '_level($level)');

/*		
			if ($level & FB_DEBUG_ADODB) {
				if (!defined('ADODB_OUTP')) {
					define('ADODB_OUTP', '__adodb_outp');
				}
			}
*/
		}

		return $level;
	}

	/*!
		\static
	*/
	// or two functions, a la java?
	function getLevel() {
//fbDebug::dump(fbDebug::_level(), 'getLevel()=');
		return fbDebug::_level();
	}

	/*!
		\static
	*/
	function setLevel($level) {
//fbDebug::dump($level, 'setLevel($level)');
		return fbDebug::_level($level);
	}

	/*!
		\static
	*/
	function reset() {
		return fbDebug::_level(FB_DEBUG_DEFAULT);
	}

	/*!
		\brief addLevel - Add debug flag to existing debug flags

		\param $level int debug flag to logical OR (|) with existing debug level
	*/
	function addLevel($level) {
//fbDebug::dump($level, 'addLevel($level)');
		return fbDebug::_level(fbDebug::_level() | $level);
	}

	/*!
		\static
	*/
	function is_set($flag) {
		return (fbDebug::_level() & $flag) == $flag;
	}
	
	/*!
		\static
	*/
	function debugging() {
		return (boolean) fbDebug::_level() & FB_DEBUG_OFF;
	}


	/*!
		\private
		\static
	*/
	function _https() {
		static $https = true;

		if (func_num_args() > 0) {
			$rv = $https;
			$https = func_get_arg(0);
			return $rv;
		}

		return $https;
	}

	/*!
		\static
	*/
	function getHttps() {
		return fbDebug::_https();
	}

	/*!
		\static
	*/
	function setHttps($https) {
		return fbDebug::_https($https);
	}


	/*******************************************************
	Debug logging functions
	********************************************************/
	
	/*!
		\static
	*/
	function hr() {
		if (!fbDebug::debugging()) {
			return '';
		}

		if (fbDebug::_level() & (FB_DEBUG_HTML | FB_DEBUG_JAVASCRIPT)) {
			return "\n<hr />\n";
		}
		
		if (fbDebug::_level() & (FB_DEBUG_TEXT | FB_DEBUG_LOG)) {
			return "\n" . str_repeat('-', 79) . "\n";
		}
	}

	/*!
		\static
	*/
	function br() {
		if (!fbDebug::debugging()) {
			return '';
		}

		if (fbDebug::_level() & (FB_DEBUG_HTML | FB_DEBUG_JAVASCRIPT)) {
			return "\n<br />\n";
		}
		if (fbDebug::_level() & (FB_DEBUG_TEXT | FB_DEBUG_LOG)) {
			return "\n";
		}
	}
	
	/*!
		\static
	*/
	function pre($text) {
		if (!fbDebug::debugging()) {
			return '';
		}

		if (fbDebug::_level() & (FB_DEBUG_HTML | FB_DEBUG_JAVASCRIPT)) {
			$text = "\n<pre>\n" . htmlspecialchars($text) . "</pre>\n";
		}

		return $text;
	}
	
	/*!
		\static
	*/
	function tt($text) {
		if (!fbDebug::debugging()) {
			return '';
		}

		if (fbDebug::_level() & (FB_DEBUG_HTML | FB_DEBUG_JAVASCRIPT)) {
			$text = "\n<tt>\n" . htmlspecialchars($text) . "</tt>\n";
		}

		return $text;
	}

	/*!
		\private
		\static
	*/
	function _ksort(&$array) {
		if (!is_array($array)) {
			return;
		}
		ksort($array);
		foreach(array_keys($array) as $k) {
			if(gettype($array[$k])=="array") {
				fbDebug::_ksort($array[$k]);
			}
		}
	}

	/*!
		\private
		\static
	*/
	function _dump($v, $text = null) {
		if (!fbDebug::debugging()) {
			return '';
		}

		fbDebug::_ksort($v);

		$s = '';
		if (fbDebug::_level() & (FB_DEBUG_HTML | FB_DEBUG_JAVASCRIPT)) {
			$s = "\n<pre>\n";
			if ($text) {
				$s .= htmlspecialchars($text) . '=';
			}
			
			if (function_exists('var_export')) {
				$s .= htmlspecialchars(var_export($v, true)) . "</pre>\n";
			} else {
				var_dump($v);
				print "</pre>\n";
			}
		} elseif (fbDebug::_level() & (FB_DEBUG_TEXT | FB_DEBUG_LOG)) {
			if ($text) {
				$s .= $text . '=';
			}
			if (function_exists('var_export')) {
				$s .= var_export($v, true) . "\n";
			} else {
				var_dump($v);
				print "\n";
			}
		}
		return $s;
	}

	/*!
		\static
	*/
	function stackdump($stack_frames_to_discard = 1) {
		if (!fbDebug::debugging()) {
			return '';
		}

		if ((fbDebug::getLevel() & FB_DEBUG_NO_STACKDUMP) == FB_DEBUG_NO_STACKDUMP) {
			return '';
		}

		if (function_exists('debug_backtrace')) {
			$callstack = debug_backtrace();

//print_r($callstack);
//return '';
			// remove the call to create a new Callstack
			while ($stack_frames_to_discard-- > 0) {
				array_shift($callstack);
			}

			$s = "Call Stack:\n";
			foreach ($callstack as $call) {
				$args		= array();
				$class		= '';
				$file		= '';
				$function	= '';
				$line		= 0;
				$type		= '';

				extract($call);
				
				if ($function == 'fb_assert_handler') {
					continue;
				}
				$frame	= $class . $type . $function . '()';
				$s .= sprintf("\t%-30s: %s\t(%s)\n", basename($file) . '('.$line.')', $frame, $file);
			}
			$s .= "\n";

		} elseif (function_exists('apd_callstack')) {
			$callstack = apd_callstack();

			// remove the call to create a new Callstack
			while ($stack_frames_to_discard-- > 0) {
				array_shift($callstack);
			}

			$s = "\n<tt>\nCall Stack:\n</tt>\n";
			foreach ($callstack as $call) {
				$file = $call[1];
				$line = $call[2];
				$frame	= $call[0] . '(' . implode(', ', $call[3]) . ')';
				$s .= sprintf("\n<pre>\t%-30s: %s\t(%s)</pre>\n", 
					basename($file) . '('.$line.')', $frame, $file);
			}
			$s .= "\n<br />\n";

		} else {
			return '';
		}
		
		fbDebug::_log($s);
		return $s;
	}

	/*!
		\static
	*/
	function deprecated($msg) {
		$msg = 'DEPRECIATED: ' . $msg;
//		fbDebug::log($msg);
		if ((fbDebug::getLevel() & FB_DEBUG_DEPRECATED) == FB_DEBUG_DEPRECATED) {
			echo fbDebug::pre($msg);
			fbDebug::stackdump();
		}
	}
	
	/*!
		\static
	*/
	function dump($v, $text = null) {
		if (!fbDebug::debugging()) {
			return '';
		}

/*
		if ((fbDebug::getLevel() & FB_DEBUG_NO_STACKDUMP) != FB_DEBUG_NO_STACKDUMP) {
			if (function_exists('apd_callstack')) {
				$callstack = apd_callstack();
				$call = array_shift($callstack);
				$trace = sprintf('%s(%d): ', basename($call[1]) , $call[2]);
				if (is_object($v)) {
					$trace .= '(' . get_class($v) . ') ';
				}
				$text = $trace . $text;
			}
		}
*/		
		$s = fbDebug::_dump($v, $text);
		fbDebug::_log($s);
		return $s;
	}

	/*!
		\private
		\static
	*/
	function _dump_globals($ignore_vars = '', $no_uc_vars = false) {
		if (!fbDebug::debugging()) {
			return '';
		}

		$g = $GLOBALS;

		static $skip_vars = '^argc$|^argv$|^ADODB_Database$|^ADODB_vers$|^conf$|^conn$|^sectioninfo$|^sessname$';
//		if ($ignore_vars) {
//			$match = '/' . $ignore_vars . '|' . $skip_vars . '/';
//		} else {
			$match = '/' . $skip_vars . '/';
//		}
		
		$rv = fbDebug::hr();
		$rv .= fbDebug::pre(sprintf("\n%d GLOBALS (less $match): \n", count($g)));
		
		ksort ($g);
		reset ($g);
		while (list ($key, $val) = each ($g)) {
			if (preg_match($match, $key)) {
				continue;
			}
			if ($no_uc_vars) {
				if (strtoupper($key) == $key) {
					continue;
				}
			}
			$rv .= fbDebug::_dump($val, $key);
		} 
		$rv .= "\n";
		$rv .= fbDebug::hr();
		return $rv;
	}

	/*!
		\static
	*/
	function dump_globals($ignore_vars = '', $no_uc_vars = false) {
		if (!fbDebug::debugging()) {
			return '';
		}
			
		$s = fbDebug::_dump_globals($ignore_vars, $no_uc_vars);
		fbDebug::_log($s);
		return $s;
	}


	/*!
		\private
		\static
	*/
	function _error_log($s) {
		static $file;

		if (!$file) {
			$file = fbSystem::tempDirectory() . '/phpdebug.log';
			if (!is_file($file)) {
				@error_log('', 3, $file);
				$old_umask = @umask(0);
				@chmod($file, 0666);
				@umask($old_umask);
			}
		}
		@error_log($s, 3, $file);
	}

	/*!
		\private
		\static
	*/
	function _javascript($s) {
		static $init;
		
		if (!$init) {
			$init = true;
			$title = "Debug: " . $_SERVER['REQUEST_URI'];

			print <<<EOD

<script language='javascript'>
var _console = null;
function _d(msg) {
	if ((_console == null) || (_console.closed)) {
		_console = window.open('','fb_debug');
		_console.opener = self;
		_console.document.open("text/html");
	}

	_console.document.write(msg);
}
</script>
EOD;
			$html = <<<EOD
<html>
<head>
<title>$title</title>
</head>
<body>
EOD;
			$data = addcslashes($html, "\0..\37");
			print <<<EOD

<script language='javascript'>
_d("$data");
</script>
EOD;
		}

		$data = addcslashes($s, "\0..\37");
		print <<<EOD

<script language='javascript'>
_d("$data");
</script>
EOD;

	}
	
	/*!
		\private
		\static
	*/
	function _log($s) {
		if (!fbDebug::debugging()) {
			return '';
		}

		if (fbDebug::_level() & FB_DEBUG_JAVASCRIPT) {
			fbDebug::_javascript($s);
		}

		if (fbDebug::_level() & (FB_DEBUG_HTML | FB_DEBUG_TEXT)) {
			print $s;
			print "\n";
		}

		if (fbDebug::_level() & FB_DEBUG_LOG) {
			fbDebug::_error_log($s);
		}

		return $s;
	}

	/*!
		\static
	*/
	function log($s) {
		if (!fbDebug::debugging()) {
			return '';
		}

		return fbDebug::_log($s);
	}
		

	/*!
		\static
	*/
	function trace($text = false, $stack_frames_to_discard = 0) {
		if (!fbDebug::debugging()) {
			return '';
		}

		if ((fbDebug::getLevel() & FB_DEBUG_TRACE) != FB_DEBUG_TRACE) {
			return '';
		}
		
//		fbDebug::stackdump();

		$s = '';
		
		if (function_exists('debug_backtrace')) {
			$callstack = debug_backtrace();

//print_r($callstack);
//exit;

			// remove the call to create a new Callstack
			while ($stack_frames_to_discard-- > 0) {
				array_shift($callstack);
			}

			$s = '';
			foreach ($callstack as $call) {
				$args		= array();
				$class		= '';
				$file		= '';
				$function	= '';
				$line		= 0;
				$type		= '';

				extract($call);
				
				if ($function == 'fb_assert_handler') {
					continue;
				}
				$s = sprintf("%-30s: %s (%s)\n", basename($file) . '('.$line.')', $text, $file);
				break;
			}
		} elseif (function_exists('apd_callstack')) {
			$callstack = apd_callstack();

			// remove the call to create a new Callstack
			while ($stack_frames_to_discard-- > 0) {
				array_shift($callstack);
			}

			$s = "\n<tt>\nCall Stack:\n</tt>\n";
			foreach ($callstack as $call) {
				$file = $call[1];
				$line = $call[2];
				$frame	= $call[0] . '(' . implode(', ', $call[3]) . ')';
				$s .= sprintf("\n<pre>\t%-30s: %s\t(%s)</pre>\n", 
					basename($file) . '('.$line.')', $frame, $file);
			}
			$s .= "\n<br />\n";

		} else {
			return '';
		}
	
/*		
		if (function_exists('apd_callstack')) {
			$callstack = apd_callstack();

			$call = array_shift($callstack);

			$s .= sprintf('%s(%d) [', basename($call[1]) , $call[2]);
			$call = array_shift($callstack);
			$s .= $call[0] . '(';
			if (is_array($call[3]))
				$s .= implode(', ', $call[3]);
			$s .= ')';
		}
*/
		$s = fbDebug::pre($s);
		$s = fbDebug::log($s);

		return $s;
	}

	/*!
		\param $text \c string
		\param $enter_or_leave \c string
		\param $stack_frames_to_discard \c int
		\return \c string
		\static
*/
	function _enter_or_leave($text = null, $enter_or_leave = 'Entering', $stack_frames_to_discard = 2) {
		if (!fbDebug::debugging()) {
			return '';
		}

		if ((fbDebug::getLevel() & FB_DEBUG_TRACE) != FB_DEBUG_TRACE) {
			return '';
		}

		if (function_exists('debug_backtrace')) {
			$callstack = debug_backtrace();

//print_r($callstack);
//exit;
			// remove the call to create a new Callstack
			while ($stack_frames_to_discard-- > 0) {
				array_shift($callstack);
			}

			$s = '';
			foreach ($callstack as $call) {
				$args		= array();
				$class		= '';
				$file		= '';
				$function	= '';
				$line		= 0;
				$type		= '';

				extract($call);
				
				if ($function == 'fb_assert_handler') {
					continue;
				}

				$frame	= $class . $type . $function . '()';

				$s = sprintf("%-30s: %s %s: %s (%s)\n", basename($file) . '('.$line.')', $enter_or_leave, $frame, $text, $file);
				break;
			}

			fbDebug::log($s);
		}
		
		return $s;
	}

	/*!
		\param $text \c string
		\return \c string
		\static
	*/
	function enter($text = null) {
		if (!fbDebug::debugging()) {
			return '';
		}

		if ((fbDebug::getLevel() & FB_DEBUG_TRACE) != FB_DEBUG_TRACE) {
			return '';
		}

		return fbDebug::_enter_or_leave($text, 'Entering');
	}
	
	/*!
		\param $text \c string
		\return \c string
		\static
	*/
	function leave($text = null) {
		if (!fbDebug::debugging()) {
			return '';
		}

		if ((fbDebug::getLevel() & FB_DEBUG_TRACE) != FB_DEBUG_TRACE) {
			return '';
		}

		return fbDebug::_enter_or_leave($text, 'Leaving');
	}

	/*!
		\param $file \c string
		\param $line \c int
		\param $code \c int
		\return \c void
		\static
	*/
	function assertHandler($file, $line, $code) {
		if ((fbDebug::getLevel() & FB_DEBUG_NO_ASSERTS) == FB_DEBUG_NO_ASSERTS) {
			return;
		}

		fbDebug::log(fbDebug::pre(sprintf("%-30s: Assertion Failed: '%s'\t(%s)\n",
			basename($file) . '(' . $line . ')', $code, $file)));
		fbDebug::stackdump(2);
	} 

	/*!
		\static
	*/
	function init() {
		assert_options(ASSERT_ACTIVE,		1); 
		assert_options(ASSERT_BAIL,			0);
		assert_options(ASSERT_CALLBACK,		array('fbDebug', 'assertHandler')); 
		assert_options(ASSERT_QUIET_EVAL,	1); 
		assert_options(ASSERT_WARNING,		0); 
	}

} // class fbDebug

fbDebug::init();

?>
