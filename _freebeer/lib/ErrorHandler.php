<?php

// $CVSHeader: _freebeer/lib/ErrorHandler.php,v 1.2 2004/03/07 17:51:17 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file ErrorHandler.php
	\brief Error handler class
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

require_once FREEBEER_BASE . '/lib/System.php'; // isCLI

if (phpversion() <= '5.0.0b3') {
	include_once FREEBEER_BASE . '/lib/Backport.php'; // var_export(), E_STRICT
}

/*!
	\todo direct errors to STDERR in CLI mode
	\todo optionally email bug report
	\todo number and store each error as a separate file in var/log/errors/errornnnn.php or warningnnnn.php
	\todo also store 1 line for each error/warning in var/log/errorlog.php
	\todo add LOG_SEPARATE, so notices go in phpnotice.log, warnings to phpwarnings.log, errors to phperrors.log

	Usage:

		fbErrorHandler::init();

	or:
	
		set_error_handler(array('fbErrorHandler', 'errorHandler'));

	or:
	
		$error_handler = &new fbErrorHandler();
		set_error_handler(array($error_handler, 'errorHandler'));

	or:

		function fbErrorHandler($code, $error, $file, $line, $context) {
			return fbErrorHandler::errorHandler($code, $error, $file, $line, $context);
		}

		set_error_handler('fbErrorHandler');
	
*/

/*!
	\enum FB_ERROR_HANDLER_IGNORE
	Ignore the error
*/ 
define('FB_ERROR_HANDLER_IGNORE',	0x0001);

/*!
	\enum FB_ERROR_HANDLER_LOG
	Log errors to an error log
	Default is /tmp/phperror.log (or %TEMP%/phperror.log)
*/
define('FB_ERROR_HANDLER_LOG',		0x0002);

/*!
	\enum FB_ERROR_HANDLER_STDOUT
	Log errors to standard output
*/
define('FB_ERROR_HANDLER_STDOUT',	0x0004);

/*!
	\enum FB_ERROR_HANDLER_STDERR
	Log errors to standard error
*/
define('FB_ERROR_HANDLER_STDERR',	0x0008);

/*!
	\enum FB_ERROR_HANDLER_EMAIL
	Email errors to email address
*/
define('FB_ERROR_HANDLER_EMAIL',	0x0010);

/*!
	\enum FB_ERROR_HANDLER_FAIL
	All errors cause the program to exit
*/
define('FB_ERROR_HANDLER_FAIL',		0x0020);

/*!
	\enum FB_ERROR_HANDLER_TERSE
	Report minimul error information
*/
define('FB_ERROR_HANDLER_TERSE',	0x0040);

/*!
	\enum FB_ERROR_HANDLER_NORMAL
	Report normal error information
*/
define('FB_ERROR_HANDLER_NORMAL',	0x0080);

/*!
	\enum FB_ERROR_HANDLER_VERBOSE
	Report verbose error information
*/
define('FB_ERROR_HANDLER_VERBOSE',	0x0100);

/*!
	\enum FB_ERROR_HANDLER_SHOW_HIDDEN_ERRORS
*/
define('FB_ERROR_HANDLER_SHOW_HIDDEN_ERRORS',	0x0200);

/*!
	\class fbErrorHandler ErrorHandler.php
	\brief Error handler class
	
	\static
*/
class fbErrorHandler {
	/*!
		\private
		\static
	*/
	function &_action() {
		static $_action = null;
		
		if (is_null($_action)) {
			/// \todo output errors: debugging on/web: STDOUT, debugging on/cli: STDERR, debugging off: LOG
			$_action = array(
				E_ERROR				=> FB_ERROR_HANDLER_LOG | FB_ERROR_HANDLER_STDOUT | FB_ERROR_HANDLER_VERBOSE | FB_ERROR_HANDLER_FAIL,
				E_WARNING			=> FB_ERROR_HANDLER_LOG | FB_ERROR_HANDLER_STDOUT | FB_ERROR_HANDLER_TERSE,
				E_PARSE				=> FB_ERROR_HANDLER_LOG | FB_ERROR_HANDLER_STDOUT | FB_ERROR_HANDLER_VERBOSE | FB_ERROR_HANDLER_FAIL,
				E_NOTICE			=> FB_ERROR_HANDLER_LOG | FB_ERROR_HANDLER_STDOUT | FB_ERROR_HANDLER_TERSE,
				E_CORE_ERROR		=> FB_ERROR_HANDLER_LOG | FB_ERROR_HANDLER_STDOUT | FB_ERROR_HANDLER_VERBOSE | FB_ERROR_HANDLER_FAIL,
				E_CORE_WARNING		=> FB_ERROR_HANDLER_LOG | FB_ERROR_HANDLER_STDOUT | FB_ERROR_HANDLER_TERSE,
				E_COMPILE_ERROR		=> FB_ERROR_HANDLER_LOG | FB_ERROR_HANDLER_STDOUT | FB_ERROR_HANDLER_VERBOSE | FB_ERROR_HANDLER_FAIL,
				E_COMPILE_WARNING	=> FB_ERROR_HANDLER_LOG | FB_ERROR_HANDLER_STDOUT | FB_ERROR_HANDLER_TERSE,
				E_USER_ERROR		=> FB_ERROR_HANDLER_LOG | FB_ERROR_HANDLER_STDOUT | FB_ERROR_HANDLER_VERBOSE | FB_ERROR_HANDLER_FAIL,
				E_USER_WARNING		=> FB_ERROR_HANDLER_LOG | FB_ERROR_HANDLER_STDOUT | FB_ERROR_HANDLER_TERSE,
				E_USER_NOTICE		=> FB_ERROR_HANDLER_LOG | FB_ERROR_HANDLER_STDOUT | FB_ERROR_HANDLER_TERSE,
				E_STRICT			=> FB_ERROR_HANDLER_LOG | FB_ERROR_HANDLER_IGNORE,
			);
		}
		
		return $_action;
	}
	
	/*!
		\static
	*/
	function getAction($error_level) {
		static $_action = null;
		
		if (is_null($_action)) {
			$_action = &	fbErrorHandler::_action();
		}
		
		return isset($_action[$error_level]) ? $_action[$error_level] : false;
	}
	
	/*!
		\static
	*/
	function setAction($error_level, $action) {
		static $_action = null;
		
		if (is_null($_action)) {
			$_action = &	fbErrorHandler::_action();
		}
		
		$rv = $_action[$error_level];
		
		$_action[$error_level] = $action;

		return $rv;
	}
	

	/*!
		\private
		\static
	*/
	function _email_address($email_address = null) {
		static $_email_address = null;
		
		if (!is_null($email_address)) {
			$_email_address = (string) $email_address;
		}
		
		return $_email_address;
	}
	
	/*!
		\static
	*/
	function getEmailAddress() {
		return fbErrorHandler::_email_address();
	}
	
	/*!
		\static
	*/
	function setEmailAddress($email_address) {
		fbErrorHandler::_email_address($email_address);
	}
	
	/*!
		\private
		\static
	*/
	function _recursiveSort(&$a) {
		if (is_array($a)) {
			ksort($a);
			foreach (array_keys($a) as $k) {
				if (gettype($a[$k]) == 'array') {
					fbErrorHandler::_recursiveSort($a[$k]);
				}
			}
		}
	}

	/*!
		\static
	*/
	function errorHandler($code, $error, $file, $line, $context) {
		global $_SERVER; // < 4.1.0

		static $error_type = array(
			// do not localize
			E_ERROR				=> 'error',
			E_WARNING			=> 'warning',
			E_PARSE				=> 'parse error',
			E_NOTICE			=> 'notice',
			E_CORE_ERROR		=> 'core error',
			E_CORE_WARNING		=> 'core warning',
			E_COMPILE_ERROR		=> 'compile error',
			E_COMPILE_WARNING	=> 'compile warning',
			E_USER_ERROR		=> 'user error',
			E_USER_WARNING		=> 'user warning',
			E_USER_NOTICE		=> 'user notice',
			E_STRICT			=> 'strict',
		);

/*
		static $error_prefixes = array(
			// do not localize
			'Error',
			'Warning',
			'Parse error',
			'Notice',
			'Core error',
			'Core warning',
			'Compile error',
			'Compile warning',
			'User error',
			'User warning',
			'User notice',
			'Strict',
		);

		$ignorable_error = true;
		foreach ($error_prefixes as $error_prefix) {
			if (preg_match("/$error_prefix:/i", $error)) {
				$ignorable_error = false;
				break;
			}
		}
		
		if ($ignorable_error) {
//echo "ignoring error '$error'\n<br/>\n";
			return;
		}
*/		

		$action = fbErrorHandler::getAction($code);

		if (error_reporting() == 0) {
			// @ errors
			if (!($action & FB_ERROR_HANDLER_SHOW_HIDDEN_ERRORS)) {
				return;
			}
		}
		
		$type = isset($error_type[$code]) ? $error_type[$code] : 'error ' . $code;

		$errmsg = sprintf('%s(%s): %s (%s)', basename($file), $line, ucfirst($type) . ': ' . $error, $file);

		$date = date('D M d H:i:s Y');
		// \q should we localize?
		// $date = strftime('%a %b %d %H:%M:%S %Y');
		// $date .= ' ' . date('O');
		$logmsg = sprintf("[php %s] [%s] %s\n", $date, $type, $errmsg);

		// \todo use better CLI/Web determination
		$cli = fbSystem::isCLI();
		
		if ($action & FB_ERROR_HANDLER_LOG) {
			if (!$cli) {
				// write to web server's error log
				@fwrite(STDERR, $logmsg);
			} else {
				/// \todo write to separate log file?
			}
		}

		if ($action & FB_ERROR_HANDLER_IGNORE) {
			return;
		}
 
		if (!ini_get('display_errors')) {
			// \todo display default error page
echo $logmsg;
echo "\n\todo Display default error page\n";
			exit;
		}

		if (!$cli) {
			$s = "\n<pre>\n";
		}

		$s .= $errmsg;
		$s .= "\n";

		if ($action & (FB_ERROR_HANDLER_NORMAL | FB_ERROR_HANDLER_VERBOSE)) {

			// \todo if isset($_SERVER['REMOTE_ADDR'])
			//	echo "\n</pre>\n";

			/// \todo print stack trace

			if (function_exists('debug_backtrace')) {
				$stack = debug_backtrace();

			$s .= !$cli ? '<hr />' : "\n";
			$s .= "Call Stack:";
			$s .= !$cli ? '<hr />' : "\n";

			/// \todo
			/*
						[class] => anotherclass
						[type] => ::

			*/

			#print_r($stack);
			#exit;
				array_shift($stack);	// fbErrorHandler frame
			//		array_shift($stack);	// original error frame

				foreach ($stack as $frame) {
					// \todo change extract() to hash
					extract($frame);

					$a = array();
					foreach ($args as $key => $value) {
						if (is_array($value)) {
							$a[] = 'Array[' . sizeof($value) . ']';
							continue;
						}

						if (is_bool($value)) {
							$a[] = $value ? 'true' : 'false';
							continue;
						}

						if (is_null($value)) {
							$a[] = 'null';
							continue;
						}

						if (is_object($value)) {
							$a[] = get_class($value) . ' object';
							continue;
						}

						if (is_resource($value)) {
							$a[] = get_resource_type($value) . ' resource';
							continue;
						}

						if (is_string($value)) {
							if (!$cli) {
								$a[] = "'" . htmlspecialchars($value) . "'";
							} else {
								$a[] = "'$value'";
							}
							continue;
						}

						$a[] = $value;
					}

					$frame	= $function . '(' . implode(', ', $a) . ')';
					if (isset($class)) {
						$frame = $class . $type . $frame;
					}
					if (!$cli) {
						$file = htmlspecialchars($file);
					}

					$fileline = $file . '('.$line.')';
					$baseline = basename($file) . '('.$line.')';

					if (!$cli) {
						$root_dir = FREEBEER_BASE;

						// \todo move to config file
						$cvsurl = 'http://cvs.netebb.com/horde/chora/annotate.php/_freebeer';

						$path = str_replace($root_dir, '', $file);

						$url = sprintf("%s%s#%d", $cvsurl, $path, $line);

			// \todo clean this up
			// turn open into function call:
			// return fbOpenWindow($url);


						$js = "open('$url', '', 'screenX=640,screenY=480,resizeable=yes,left=0,top=0,width=640,height=480,scrollbars=yes,status=yes,toolbar=yes,location=yes,menubar=yes,maximized=yes'); return false;";
						$url = '#';

						$link = sprintf('<a href="%s" onclick="%s">%s</a>', $url, $js, $fileline);
						$baselink = sprintf('<a href="%s" onclick="%s">%s</a>', $url, $js, $baseline);
						$spaces = str_repeat(' ', 30 - strlen($baseline));

						$s .= sprintf("\t%s: %s %s\t(%s)\n", $baselink, $spaces, $frame, $link, $file);
					} else {
						$s .= sprintf("\t%s: %s\t(%s)\n", $baseline, $spaces, $frame, $file);
					}

		/*
					foreach ($args as $key => $value) {
						if (is_object($value)) {
							echo sprintf("param %d:\n", $key + 1);
							print_r($value);
							continue;
						}

						if (is_resource($value)) {
							echo sprintf("param %d:\n", $key + 1);
							echo get_resource_type($value), ' resource ';
							switch (get_resource_type($value)) {
								case 'stream':
									print_r(stream_get_meta_data($value));
									break;
							}
							continue;
						}
					}
		*/

				} // foreach ($stack as $frame)

			} // if (function_exists('debug_backtrace'))

			$s .= !$cli ? '<hr />' : "\n";
			$s .= "Context:";
			$s .= !$cli ? '<hr />' : "\n";

			fbErrorHandler::_recursiveSort($context);
			$s .= var_export($context, true);

		}

		if ($action & FB_ERROR_HANDLER_VERBOSE) {

			$s .= !$cli ? '<hr />' : "\n";
			$s .= "Globals:";
			$s .= !$cli ? '<hr />' : "\n";

			$uvars = array(
				'HTTP_COOKIE_VARS',
				'HTTP_ENV_VARS',
				'HTTP_GET_VARS',
				'HTTP_POST_FILES',
				'HTTP_POST_VARS',
				'HTTP_SERVER_VARS',
				'HTTP_SESSION_VARS',
			);

			$_vars = array(
				'_REQUEST',
				'_SESSION',
				'_COOKIE',
				'_GET',
				'_POST',
				'_SERVER',
				'_ENV',
				'_FILES',
			);

			$g = array();

			foreach ($GLOBALS as $key => $value) {
				if (in_array($key, $uvars)) {
					continue;
				}
				if (in_array($key, $_vars)) {
					continue;
				}

				$g[$key] = $GLOBALS[$key];
			}

			fbErrorHandler::_recursiveSort($g);
			$s .= var_export($g, true);
			unset($g);

			$s .= !$cli ? '<hr />' : "\n";
			$s .= "Super Globals:";
			$s .= !$cli ? '<hr />' : "\n";

			$_g = array();

			foreach ($_vars as $key) {
				if (isset($GLOBALS[$key]) && count($GLOBALS[$key])) {
					$_g[$key] = $GLOBALS[$key];
					fbErrorHandler::_recursiveSort($_g[$key]);
				}
			}

			$s .= var_export($_g, true);

			// PHP 4.3.1 defaults on Windows
			static $default_ini_values = array(
				'SMTP'=> 'localhost',
				'allow_call_time_pass_reference'=> 1,
				'allow_url_fopen'=> 1,
				'always_populate_raw_post_data'=> 0,
				'arg_separator.input'=> '&',
				'arg_separator.output'=> '&',
				'asp_tags'=> 0,
				'assert.active'=> 1,
				'assert.bail'=> 0,
				'assert.callback'=> '',
				'assert.quiet_eval'=> 0,
				'assert.warning'=> 1,
				'auto_append_file'=> '',
				'auto_detect_line_endings'=> 0,
				'auto_prepend_file'=> '',
				'browscap'=> '',
				'child_terminate'=> 0,
				'com.allow_dcom'=> 0,
				'com.autoregister_casesensitive'=> 1,
				'com.autoregister_typelib'=> 0,
				'com.autoregister_verbose'=> 0,
				'com.typelib_file'=> '',
				'default_charset'=> '',
				'default_mimetype'=> 'text/html',
				'default_socket_timeout'=> 60,
				'define_syslog_variables'=> 0,
				'disable_functions'=> '',
				'display_errors'=> 1,
				'display_startup_errors'=> 0,
				'doc_root'=> '',
				'docref_ext'=> '',
				'docref_root'=> 'http://www.php.net/',
				'enable_dl'=> 1,
				'engine'=> 1,
				'error_append_string'=> '',
				'error_log'=> '',
				'error_prepend_string'=> '',
				'error_reporting'=> '',
				'expose_php'=> 1,
				'extension_dir'=> 'c:\\php4',
				'file_uploads'=> 1,
				'gpc_order'=> 'GPC',
				'highlight.bg'=> '#FFFFFF',
				'highlight.comment'=> '#FF8000',
				'highlight.default'=> '#0000BB',
				'highlight.html'=> '#000000',
				'highlight.keyword'=> '#007700',
				'highlight.string'=> '#DD0000',
				'html_errors'=> 1,
				'ignore_repeated_errors'=> 0,
				'ignore_repeated_source'=> 0,
				'ignore_user_abort'=> 0,
				'implicit_flush'=> 0,
				'include_path'=> '.;c:\\php4\\pear',
				'last_modified'=> 0,
				'log_errors'=> 0,
				'log_errors_max_len'=> 1024,
				'magic_quotes_gpc'=> 1,
				'magic_quotes_runtime'=> 0,
				'magic_quotes_sybase'=> 0,
				'max_execution_time'=> 30,
				'max_input_time'=> -1,
				'mysql.allow_persistent'=> 1,
				'mysql.connect_timeout'=> -1,
				'mysql.default_host'=> '',
				'mysql.default_password'=> '',
				'mysql.default_port'=> '',
				'mysql.default_socket'=> '',
				'mysql.default_user'=> '',
				'mysql.max_links'=> -1,
				'mysql.max_persistent'=> -1,
				'mysql.trace_mode'=> 0,
				'odbc.allow_persistent'=> 1,
				'odbc.check_persistent'=> 1,
				'odbc.default_db'=> '',
				'odbc.default_pw'=> '',
				'odbc.default_user'=> '',
				'odbc.defaultbinmode'=> 1,
				'odbc.defaultlrl'=> 4096,
				'odbc.max_links'=> -1,
				'odbc.max_persistent'=> -1,
				'open_basedir'=> '',
				'output_buffering'=> 0,
				'output_handler'=> '',
				'post_max_size'=> '8M',
				'precision'=> 14,
				'register_argc_argv'=> 1,
				'register_globals'=> 0,
				'report_memleaks'=> 1,
				'safe_mode'=> 0,
				'safe_mode_allowed_env_vars'=> 'PHP_',
				'safe_mode_exec_dir'=> 1,
				'safe_mode_gid'=> 0,
				'safe_mode_include_dir'=> '',
				'safe_mode_protected_env_vars'=> 'LD_LIBRARY_PATH',
				'sendmail_from'=> '',
				'sendmail_path'=> '',
				'session.auto_start'=> 0,
				'session.bug_compat_42'=> 1,
				'session.bug_compat_warn'=> 1,
				'session.cache_expire'=> 180,
				'session.cache_limiter'=> 'nocache',
				'session.cookie_domain'=> '',
				'session.cookie_lifetime'=> 0,
				'session.cookie_path'=> '/',
				'session.cookie_secure'=> '',
				'session.entropy_file'=> '',
				'session.entropy_length'=> 0,
				'session.gc_dividend'=> 100,
				'session.gc_maxlifetime'=> 1440,
				'session.gc_probability'=> 1,
				'session.name'=> 'PHPSESSID',
				'session.referer_check'=> '',
				'session.save_handler'=> 'files',
				'session.save_path'=> '/tmp',
				'session.serialize_handler'=> 'php',
				'session.use_cookies'=> 1,
				'session.use_only_cookies'=> 0,
				'session.use_trans_sid'=> 0,
				'short_open_tag'=> 1,
				'smtp_port'=> 25,
				'sql.safe_mode'=> 0,
				'track_errors'=> 0,
				'unserialize_callback_func'=> '',
				'upload_max_filesize'=> '2M',
				'upload_tmp_dir'=> '',
				'url_rewriter.tags'=> 'a=href,area=href,frame=src,form=,fieldset=',
				'user_agent'=> '',
				'user_dir'=> '',
				'variables_order'=> '',
				'xbithack'=> 0,
				'xmlrpc_error_number'=> 0,
				'xmlrpc_errors'=> 0,
				'y2k_compliance'=> 1,
				'zlib.output_compression'=> 0,
				'zlib.output_compression_level'=> -1,
				'zlib.output_handler'=> '',
			);

			if (function_exists('ini_get_all')) {
				$a = ini_get_all();
				$same = array();
				$diff = array();
				$diff_default = array();
				foreach ($a as $key => $value) {
					unset($value['access']);
					if ($value['global_value'] === $value['local_value']) {
						if (isset($default_ini_values[$key]) &&
							$value['local_value'] != $default_ini_values[$key]) {
							$value['global_value'] = $default_ini_values[$key];
							$diff_default[$key] = $value;
						} else {
							$same[$key] = $value['global_value'];
						}
					} else {
						$diff[$key] = $value;
					}
				}
				ksort($same);
				ksort($diff);

				$s .= !$cli ? '<hr />' : "\n";
				$php_ini = get_cfg_var('cfg_file_path') ? get_cfg_var('cfg_file_path') : 'php.ini';
				$s .= "Configuration options (modified from current $php_ini values):";
				$s .= !$cli ? '<hr />' : "\n";
				$s .= var_export($diff, true);

				$s .= !$cli ? '<hr />' : "\n";
				$s .= "Configuration options (current $php_ini values that are different than default values):";
				$s .= !$cli ? '<hr />' : "\n";
				$s .= var_export($diff_default, true);

	/*	// why bother
				echo !$cli ? '<hr />' : "\n";
				echo "php.ini options (unmodified):";
				echo !$cli ? '<hr />' : "\n";
				print_r($same);
	*/
			}

			$type = strtr($type, ' ', '_');

			if (!$cli) {
				$s .= "\n</pre>\n";
			}
		}

		if ($action & FB_ERROR_HANDLER_STDOUT) {
			echo $s;
		}

		if ($action & FB_ERROR_HANDLER_STDERR) {
			@fwrite(STDERR, $s);
		}

		if ($action & FB_ERROR_HANDLER_EMAIL) {
			/// \todo add support for FB_ERROR_HANDLER_EMAIL option
		}

		if ($action == FB_ERROR_HANDLER_FAIL) {
			if (!$cli) {
				echo "</body>\n</html>\n";
			}
			exit(1);
		}
	}

	/*!
		\static
	*/
	function init() {
		return set_error_handler(array('fbErrorHandler', 'errorHandler'));
	}
	
}

?>
