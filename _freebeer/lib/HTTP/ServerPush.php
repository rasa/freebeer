<?php

// $CVSHeader: _freebeer/lib/HTTP/ServerPush.php,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

/*!
	\file HTTP/ServerPush.php
	\brief Push content from the web server to the client via push or refresh
*/

/*!
	\enum FB_HTTP_SERVER_PUSH
	Push content via Netscape's multipart/x-mixed-replace method
*/
define('FB_HTTP_SERVER_PUSH',			1);

/*!
	\enum FB_HTTP_SERVER_PUSH_REFRESH
	Push content via HTTP Meta Refresh method
*/
define('FB_HTTP_SERVER_PUSH_REFRESH',	2);

/*
	From http://wp.netscape.com/assist/net_sites/pushpull.html

#!/bin/sh
echo "HTTP/1.0 200"
echo "Content-type: multipart/x-mixed-replace;boundary=---ThisRandomString---"
echo ""
echo "---ThisRandomString---"
while true
do
echo "Content-type: text/html"
echo ""
echo "<h2>Processes on this machine updated every 5 seconds</h2>"
echo "time: "
date
echo "<p>"
echo "<plaintext>"
ps -el
echo "---ThisRandomString---"
sleep 5
done
*/
			
/*!
	\class fbHTTP_ServerPush
	\brief Push content from the web server to the client via push or refresh

	\static
*/
class fbHTTP_ServerPush {
	/*!
		\param $page_function \c string
		\param $seconds \c int
		\param $url \c string
		\param $loops \c int
		\static
	*/
	function _push_server($page_function, $seconds = 0, $url = null, $loops = null) {
		global $_SERVER; // < 4.1.0

		$boundry = md5(microtime());
		header('Content-type: multipart/x-mixed-replace;boundary=' . $boundry);
		echo $boundry,"\n";

		$loop = 0;
		while ($loop < $loops) {
			++$loop;
			$rv = $page_function(FB_HTTP_SERVER_PUSH, $seconds, $url, $loop);
			if ($rv) {
				echo $rv;
			}
			echo $boundry,"\n";
			flush();
			if (!$rv) {
				break;
			}
			sleep($seconds);
		}
		return true;
	}

	/*!
		\param $page_function \c string
		\param $seconds \c int
		\param $url \c string
		\param $loops \c int
		\static
	*/
	function _push_refresh($page_function, $seconds = 0, $url = null, $loops = null) {
		global $_SERVER; // < 4.1.0
		
		if ($url == null) {
			$url = $_SERVER['SCRIPT_NAME'];
		}

		header('Connection: close');
		header('Cache-Control: no-cache');
		header('Pragma: no-cache');
		header('Expires: 0');
	#	header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time() - 8640000) . ' GMT');
		header('Refresh: ' . $seconds . '; URL=' . $url);

		echo $page_function(FB_HTTP_SERVER_PUSH_REFRESH, $seconds, $url, 1);

		flush();
		
		return true;
	}

	/*!
		\param $page_function \c string
		\param $seconds \c int
		\param $url \c string
		\param $loops \c int
		\static
	*/
	function push($page_function, $seconds = 0, $url = null, $loops = null) {
		global $_SERVER; // < 4.1.0

		$ua = $_SERVER['HTTP_USER_AGENT'];

		if (preg_match('/(Gecko|Netscape)/i', $ua)) {
			$boundry = md5(microtime());
			header('Content-type: multipart/x-mixed-replace;boundary=' . $boundry);
			echo $boundry,"\n";

			$loop = 0;
			while ($loop < $loops) {
				++$loop;
				$rv = $page_function(FB_HTTP_SERVER_PUSH, $seconds, $url, $loop);
				if ($rv) {
					echo "Content-type: text/html\n\n";
					echo $rv;
				}
				echo $boundry,"\n";
				flush();
				if (!$rv) {
					break;
				}
				sleep($seconds);
			}
		} else {
			if ($url == null) {
				$url = $_SERVER['SCRIPT_NAME'];
			}

			header('Connection: close');
			header('Cache-Control: no-cache');
			header('Pragma: no-cache');
			header('Expires: 0');
		#	header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time() - 8640000) . ' GMT');
			header('Refresh: ' . $seconds . '; URL=' . $url);

			echo $page_function(FB_HTTP_SERVER_PUSH_REFRESH, $seconds, $url, 1);

			flush();
		}
		
		return true;
	}
}

?>
