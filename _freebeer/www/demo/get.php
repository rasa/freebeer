<?php

// $CVSHeader: _freebeer/www/demo/get.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/Debug/PHP_Constants.php';

echo html_header_demo('Display PHP Information');

// change one, just to see the result
ini_set('SMTP', 'locally.changed.com');

$options = array(
	0	=> 'all',
	1	=> 'get_declared_classes',
	2	=> 'get_loaded_extensions',
	3	=> 'get_extension_funcs',
	4	=> 'get_user_constants',
	5	=> 'get_defined_constants',
	6	=> 'ini_get_all',
	7	=> 'ini_get_all (different)',
	8	=> 'dba_handlers',
	9	=> 'system constants',
	10	=> 'stream_get_wrappers',
	11	=> '$_ENV',
	12	=> '$_SERVER',
);

if (!isset($_REQUEST['option'])) {
	$_REQUEST['option'] = 0;
}

if (!preg_match('/wget/i', $_SERVER['HTTP_USER_AGENT'])) {
	foreach($options as $key => $value) {
		$url = $_SERVER['PHP_SELF'] . '?option=' . $key;
		printf("<a href=\"%s\">%s</a><br />\n", $url, $value);
	}
}

echo "<pre>\n";

echo "get_cfg_var('cfg_file_path')=";
echo get_cfg_var('cfg_file_path');
echo "\n";

echo "ini_get('cfg_file_path')=";
echo ini_get('cfg_file_path');
echo "\n";

function do_option($option) {
	switch ($option) {
		case 0:
			global $options;
			foreach ($options as $key => $value) {
				if ($key == 0) {
					continue;
				}
				do_option($key);
				echo "<hr />\n";
			}
			break;

		case 1:
			echo "get_declared_classes()=";
			$a = get_declared_classes();
			ksort($a);
			print_r($a);
			foreach ($a as $class) {
				echo "get_class_methods('$class')=";
				$b = get_class_methods($class);
				sort($b);
				print_r($b);
			}
			break;

		case 2:
			$a = get_loaded_extensions();
			echo "get_loaded_extensions()=";
			sort($a);
			print_r($a);
			break;

		case 3:
			$a = get_loaded_extensions();
			sort($a);
			foreach ($a as $ext) {
				echo "get_extension_funcs('$ext')=";
				$b = get_extension_funcs($ext);
				sort($b);
				print_r($b);
			}
			break;

		case 4:
			echo "get_user_constants()=";
			$a = get_defined_constants();
			$user_constants = array();
			foreach ($a as $key => $value) {
				if (isset($PHP_CONSTANTS[$key])) {
					continue;
				}
				$user_constants[$key] = $value;
			}
			ksort($user_constants);
			print_r($user_constants);
			break;


		case 5:
			echo "get_defined_constants()=";
			$a = get_defined_constants();
			ksort($a);
			print_r($a);
			break;

		case 6:
			echo "ini_get_all()=";
			if (function_exists('ini_get_all')) {
				$a = ini_get_all();
				ksort($a);
				print_r($a);
			} else {
				echo "not available in PHP version ", phpversion(), "\n";
			}
			break;

		case 7:
			echo "ini_get_all() (different)=";
			if (function_exists('ini_get_all')) {
				$a = ini_get_all();
				$diff = array();
				foreach ($a as $key => $value) {
					if ($value['global_value'] != $value['local_value']) {
						$diff[$key] = $value;
					}
				}
				ksort($diff);
				print_r($diff);
			} else {
				echo "not available in PHP version ", phpversion(), "\n";
			}
			break;

		case 8:
			if (function_exists('dba_handlers')) {
				echo "dba_handlers()=";
				$a = dba_handlers();
				ksort($a);
				print_r($a);
			}
			break;

		case 9:
				echo "getrandmax()=",getrandmax(),"\n";
				echo "mt_getrandmax()=",mt_getrandmax(),"\n";
				break;

		case 10:
			if (function_exists('stream_get_wrappers')) {
				echo "stream_get_wrappers()=";
				$a = stream_get_wrappers();
				ksort($a);
				print_r($a);
			}
			break;

		case 11:
			echo "\$_ENV=";
			$a = $_ENV;
			ksort($a);
			print_r($a);
			break;

		case 12:
			echo "\$_SERVER=";
			$a = $_SERVER;
			ksort($a);
			print_r($a);
			break;

		default:
			break;
	}
}

do_option($_REQUEST['option']);

?>
</pre>
<address>
$CVSHeader: _freebeer/www/demo/get.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>
</body>
</html>
