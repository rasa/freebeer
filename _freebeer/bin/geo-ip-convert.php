#!/usr/bin/php
<?php

// $CVSHeader: _freebeer/bin/geo-ip-convert.php,v 1.1.1.1 2004/01/18 00:12:03 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

error_reporting(2047);
@ini_set('html_errors', false);
@ini_set('track_errors', true);
@ob_implicit_flush(true);
@set_time_limit(0);

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

/*

As http://www.faqs.org/rfcs/rfc1918.html states:

   The Internet Assigned Numbers Authority (IANA) has reserved the
   following three blocks of the IP address space for private internets:

     10.0.0.0        -   10.255.255.255  (10/8 prefix)
     172.16.0.0      -   172.31.255.255  (172.16/12 prefix)
     192.168.0.0     -   192.168.255.255 (192.168/16 prefix)

we replace

172.016.000.000	172.016.000.255	TW
172.016.001.000	172.016.255.255	NL
172.017.000.000	172.017.000.255	TW
172.017.001.000	172.017.249.255	NL
172.017.250.000	172.017.250.255	SG
172.017.251.000	172.027.255.255	NL
172.028.000.000	172.028.000.255	TW
172.028.001.000	172.031.255.255	NL

with

172.016.000.000	172.031.255.255 I0

I have no idea why Geo IP doesn't list these as I0.

*/

require_once FREEBEER_BASE . '/lib/Backport.php'; // fprintf/STDERR

class fbGeoIP_Free_Utilities {
	function &ipToTuples($ip) {
			$ips = ip2long($ip);

			$rv[] = $ips >> 24;
			if ($rv[0] < 0) {
				$rv[0] += 256;
			}
			$rv[] = ($ips >> 16) & 0xff;
			$rv[] = ($ips >> 8)  & 0xff;
			$rv[] = $ips & 0xff;

//			assert('$ip == join(".", $rv)');
			return $rv;
	}
	
	function convertFile($input_file, $output_file, $format = null) {
		if (!$format) {
			$format = "%03d.%03d.%03d.%03d\t%03d.%03d.%03d.%03d\t%2s\n";
		}

		$argv0 = basename(__FILE__);
		
		$trace_errors = @ini_set('trace_errors', true);
		$php_errormsg = '';

		$fp = fopen($input_file, 'r');

		if (!$fp) {
			die(sprintf("Can't open '%s': %s", $input_file, $php_errormsg));
		}

		$fp2 = fopen($output_file, 'wb');

		if (!$fp2) {
			die(sprintf("Can't create '%s': %s", $output_file, $php_errormsg));
		}

		$done172 = false;

		$line = 0;
		while (!feof($fp)) {
			++$line;
			$s = fgets($fp, 1024);
			$matches = preg_split('/\s+/', $s);
			if (!$matches) {
//				fprintf(STDERR, "%s: error 1: line %d: no spaces in '%s'\n", $argv0, $line, $s);
//				exit(1);
				continue;
			}
			if (count($matches) < 3) {
//				fprintf(STDERR, "%s: error 2: line %d: not enough spaces in '%s'\n", $argv0, $line, $s);
//				exit(2);
				continue;
			}
			$cc = $matches[0];
			$cc = substr($cc, 0, 2);

			$ip = $matches[1];
			$ip2 = $matches[2];

			$ips = fbGeoIP_Free_Utilities::ipToTuples($ip);
			$ipe = fbGeoIP_Free_Utilities::ipToTuples($ip2);

			if ($ips[0] == 172 && $ips[1] >= 16 && $ipe[0] == 172 && $ipe[1] <= 31) {
				if ($done172) {
					continue;
				}

				$done172 = true;
				
				// 172.16.0.0 to 172.31.255.255
				$ips[0] = 172;
				$ips[1] = 16;
				$ips[2] = 0;
				$ips[3] = 0;
				$ipe[0] = 172;
				$ipe[1] = 31;
				$ipe[2] = 255;
				$ipe[3] = 255;
				$cc = 'I0';
			}

			if ($ipe[0] == 0 && $ipe[1] == 0 && $ipe[2] == 0 && $ipe[3] == 0) {
				// convert final 0.0.0.0 to 255.255.255.254
				$ipe[0] = 255;
				$ipe[1] = 255;
				$ipe[2] = 255;
				$ipe[3] = 254;
			}

			$rv = fprintf($fp2, $format, $ips[0], $ips[1], $ips[2], $ips[3], $ipe[0], $ipe[1], $ipe[2], $ipe[3], $cc);
			if (!$rv) {
				die(sprintf("Can't write to '%s': %s", $output_file, $php_errormsg));
			}
//			fwrite($fp2, sprintf($format, $ips[0], $ips[1], $ips[2], $ips[3], $ipe[0], $ipe[1], $ipe[2], $ipe[3], $cc));

		} // while (!feof($fp))

		$rv = fprintf($fp2, $format, 255, 255, 255, 255, 255, 255, 255, 255, '--');
		if (!$rv) {
			die(sprintf("Can't write to '%s': %s", $output_file, $php_errormsg));
		}

//		fwrite($fp2, sprintf($format, 255, 255, 255, 255, 255, 255, 255, 255, '--'));

		fclose($fp);
		$rv = fclose($fp2);
		if (!$rv) {
			die(sprintf("Can't close '%s': %s", $output_file, $php_errormsg));
		}
		
		@ini_set('trace_errors', $trace_errors);
		return true;
	}
}

if (!isset($argc) && isset($HTTP_SERVER_VARS['argc'])) {
	$argc = $HTTP_SERVER_VARS['argc'];
	$argv = $HTTP_SERVER_VARS['argv'];
}

if (!isset($argc)) {
	$php_ini = get_cfg_var('cfg_file_path') ? get_cfg_var('cfg_file_path') : 'php.ini';
	die(sprintf("%s: Use command line PHP, or set register_argc_argv=on in the [PHP] section of %s", basename(__FILE__), $php_ini));
}

if ($argc < 3) {
	die(sprintf("Usage: %s input.txt output.txt [format]", basename(__FILE__)));
}

if (!isset($argv[3])) {
	$argv[3] = null;
}

fbGeoIP_Free_Utilities::convertFile($argv[1], $argv[2], $argv[3]);

exit(0);
