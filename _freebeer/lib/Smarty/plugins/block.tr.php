<?php

// $CVSHeader: _freebeer/lib/Smarty/plugins/block.tr.php,v 1.1.1.1 2004/01/18 00:12:05 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(dirname(__FILE__)))));

require_once FREEBEER_BASE . '/lib/Gettext.php';
require_once FREEBEER_BASE . '/lib/Locale.php';

function smarty_block_tr($params, $content, &$smarty) {
	global $_SERVER; // < 4.1.0
	
	static $init;
	static $default_languages = false;
	
	if (!$init) {
		$init = true;

		fbGettext::init();
		if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
			$default_languages = &fbLocale::parseAcceptLanguages($_SERVER['HTTP_ACCEPT_LANGUAGE']);
		}
	}

	$lang = false;
	extract($params);
	if ($lang) {
		$languages = &fbLocale::parseAcceptLanguages($lang);
	} else {
		$languages = $default_languages;
	}

	/// \todo only switch locales if the current locale is different!
	fbLocale::pushLocale(LC_ALL, $languages);
	$rv = gettext($content);
	fbLocale::popLocale(LC_ALL);
	return $rv;
}

?>
