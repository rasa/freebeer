<?php

// $CVSHeader: _freebeer/lib/HTTPS.php,v 1.2 2004/03/07 17:51:17 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file HTTPS.php
	\brief Rewrite HTML page with https:// or http:// links
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

require_once FREEBEER_BASE . '/lib/Config.php';
require_once FREEBEER_BASE . '/lib/HTTP.php';	// redirect()
require_once FREEBEER_BASE . '/lib/System.php';	// $_SERVER vars

/*!
	\class fbHTTPS
	\brief Rewrite HTML page with https:// or http:// links

	Singleton
*/
class fbHTTPS {
	var $_convert_input_src = false;
	var $_enabled		= true;
	var $_http_host		= '';
	var $_http_port		= 80;
	var $_http_path		= '';
	var $_https_host	= '';
	var $_https_port	= 443;
	var $_https_path 	= '';

	/*!
		Constructor
	*/
	function fbHTTPS() {
		$conf = &fbConfig::getInstance();
		$conf->getSection($this);

		if (!$this->_http_host) {
			$this->_http_host = getenv('HTTP_HOST');
		}

		if (!$this->_https_host) {
			$this->_https_host = getenv('HTTP_HOST');
		}
	}

	/*!
		\return \c array
		\static
	*/
	function &getInstance() {
		static $instance = null;

        if (is_null($instance)) {
            $instance = &new fbHTTPS();
        }

		return $instance;
	}

	/*!
		\return \c bool
	*/
	function getConvertInputSrc() {
		return $this->_convert_input_src;
	}

	/*!
		\param $convert_input_src \c bool
		\return \c void
	*/
	function setConvertInputSrc($convert_input_src) {
		$this->_convert_input_src = (bool) $convert_input_src;
	}

	/*!
		\return \c bool
	*/
	function getEnabled() {
		return $this->_enabled;
	}

	/*!
		\param $enabled \c bool
		\return \c void
	*/
	function setEnabled($enabled) {
		$this->_enabled = (bool) $enabled;
	}

	/*!
		\return \c string
	*/
	function getHttpsHost() {
		return $this->_https_host;
	}

	/*!
		\param $https_host \c string
		\return \c void
	*/
	function setHttpsHost($https_host) {
		$this->_https_host = (string) $https_host;
	}

	/*!
		\return \c int
	*/
	function getHttpsPort() {
		return $this->_https_port;
	}

	/*!
		\param $https_port \c int
		\return \c void
	*/
	function setHttpsPort($https_port) {
		$this->_https_port = (int) $https_port;
	}

	/*!
		\return \c string
	*/
	function getHttpsPath() {
		return $this->_https_path;
	}

	/*!
		\param $https_path \c string
		\return \c void
	*/
	function setHttpsPath($https_path) {
		$this->_https_path = (string) $https_path;
	}

	/*!
		\return \c string
	*/
	function getHttpHost() {
		return $this->_http_host;
	}

	/*!
		\param $http_host \c string
		\return \c void
	*/
	function setHttpHost($http_host) {
		$this->_http_host = (string) $http_host;
	}

	/*!
		\return \c int
	*/
	function getHttpPort() {
		return $this->_http_port;
	}

	/*!
		\param $http_port \c int
		\return \c void
	*/
	function setHttpPort($http_port) {
		$this->_http_port = (int) $http_port;
	}

	/*!
		\return \c string
	*/
	function getHttpPath() {
		return $this->_http_path;
	}

	/*!
		\param $http_path \c string
		\return \c void
	*/
	function setHttpPath($http_path) {
		$this->_http_path = (string) $http_path;
	}

	/*!
		\param $url \c string
		\param $scheme \c string
		\param $host \c string
		\param $port \c int
		\param $path \c string
		\return \c string
		\private
		\static
	*/
	function _makeUrl($url = false, $scheme = false, $host = false, $port = false, $path = false) {
		global $_SERVER; // < 4.1.0

		if (!$url) {
			$url = $_SERVER['PHP_SELF'];
		}

		$purl = parse_url($url);

		if ($scheme) {
			$rv = $scheme;
		} elseif ($purl['scheme']) {
			$rv = $purl['scheme'];
		} else {
			$rv = 'http';
		}

		$rv .= '://' . ($host ? $host : $purl['host']);

		$rv .= $port ? (':' . $port) : '';

		if ($path) {
			$rv .= $path;
		}

		if (substr($purl['path'], 0, 1) != '/') {
			$rv .= '/';
		}

		$rv .= $purl['path'];

		if (isset($purl['query']) && $purl['query']) {
			$rv .= '?' . $purl['query'];
		}

		if (isset($purl['fragment']) && $purl['fragment']) {
			$rv .= '#' . $purl['fragment'];
		}

		return $rv;
	}

	/*!
		\param $url \c string
		\param $host \c string
		\param $port \c int
		\param $path \c string
		\return \c string
		\static
	*/
	function httpsUrl($url, $host = false, $port = false, $path = false) {
		if (!$host) {
			$host = $this->_https_host;
		}

		if (!$port) {
			$p = $this->_https_port;
			if ($p <> 443) {	// 443 = SSL/TLS port
				$port = $p;
			}
		}

		if (!$path) {
			$path = $this->_https_path;
		}

		return $this->_makeUrl($url, 'https', $host, $port, $path);
	}

	/*!
		\param $url \c string
		\param $host \c string
		\param $port \c int
		\param $path \c string
		\return \c string
	*/
	function httpUrl($url, $host = false, $port = false, $path = false) {
		if (!$host) {
			$host = $this->_http_host;
		}

		if (!$port) {
			$p = $this->_http_port;
			if ($p <> 80) {	// 80 = HTTP port
				$port = $p;
			}
		}

		if (!$path) {
			$path = $this->_http_path;
		}

		return $this->_makeUrl($url, 'http', $host, $port, $path);
	}

	/*!
		\param $url \c string
		\return \c mixed
	*/
	function httpsMe($url = false) {
		if (!$this->_enabled) {
			return true;
		}

		if (!$this->isHttps()) {
			fbHTTP::redirect($this->httpsUrl($url));
			exit();
		}

		return false;
	}

	/*!
		\param $url \c string
		\return \c mixed
	*/
	function httpMe($url = false) {
		if (!$this->_enabled) {
			return true;
		}

		if ($this->isHttps()) {
			fbHTTP::redirect($this->httpUrl($url));
			exit();
		}

		return false;
	}

	/*!
		\return \c bool
		\static
	*/
	function isHttp() {
		global $_SERVER; // < 4.1.0

		return (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on');
	}

	/*!
		\return \c bool
		\static
	*/
	function isHttps() {
		global $_SERVER; // < 4.1.0

		return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on');
	}

	/*!
		\param $convert_input_src \c bool
		\return \c void
	*/
	function httpLinkify($convert_input_src = false) {
		if ($convert_input_src || $this->getConvertInputSrc()) {
			$func = '_httpLinkifyCallbackInput';
		} else {
			$func = '_httpLinkifyCallback';
		}

		ob_start(array(&$this, $func));
	}

	/*!
		\param $url \c string
		\param $quote \c string
		\return \c string
		\private
	*/
	function _httpLinkify($quote, $url) {
		// why are "s coming over as \" ?
		if ($quote == '\"') {
			$quote = '"';
		}

		// Don't linkify absolute URLs
		$purl = parse_url($url);
		if (isset($purl['scheme']) && $purl['scheme']) {
			return $quote . $url;
		}

		if ($quote != '"' && $quote != "'") {
			$quote = '"';
			$endquote = '"';
		} else {
			$endquote = '';
		}

		return $quote . $this->httpUrl($url) . $endquote;
	}

	/*!
		\param $buffer \c string
		\return \c string
		\private
		\static
	*/
	function _httpLinkifyCallback($buffer) {
		static $from = array(
			   '~<\s*a\s+href\s*=\s*([\'"]?)([^\'"\s>]+)~ie',
			'~<\s*area\s+href\s*=\s*([\'"]?)([^\'"\s>]+)~ie',
			'~<\s*frame\s+src\s*=\s*([\'"]?)([^\'"\s>]+)~ie',
		);
		static $to = array(
			   "'<a href=' . fbHTTPS::_httpLinkify('\\1', '\\2')",
			"'<area href=' . fbHTTPS::_httpLinkify('\\1', '\\2')",
			"'<frame src=' . fbHTTPS::_httpLinkify('\\1', '\\2')",
		);

		return preg_replace($from, $to, $buffer);
	}

	/*!
		\param $buffer \c string
		\return \c string
		\private
		\static
	*/
	function _httpLinkifyCallbackInput($buffer) {
//		if (!function_exists('domxml_open_mem')) {
			static $from = array(
				   '~<\s*a\s+href\s*=\s*([\'"]?)([^\'"\s>]+)~ie',
				'~<\s*area\s+href\s*=\s*([\'"]?)([^\'"\s>]+)~ie',
				'~<\s*frame\s+src\s*=\s*([\'"]?)([^\'"\s>]+)~ie',
				'~<\s*input\s+src\s*=\s*([\'"]?)([^\'"\s>]+)~ie',
			);
			static $to = array(
				   "'<a href=' . fbHTTPS::_httpLinkify('\\1', '\\2')",
				"'<area href=' . fbHTTPS::_httpLinkify('\\1', '\\2')",
				"'<frame src=' . fbHTTPS::_httpLinkify('\\1', '\\2')",
				"'<input src=' . fbHTTPS::_httpLinkify('\\1', '\\2')",
			);

			return preg_replace($from, $to, $buffer);
/*	// \todo wip
		}

		if (!$dom = domxml_open_mem($buffer)) {
			return $buffer;
		}
		return htmlentities($dom->dump_mem(true));
*/
	}

}

?>
