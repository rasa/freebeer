<?php

// $CVSHeader: _freebeer/lib/Gettext.php,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

/*!
	\file Gettext.php
	\brief gettext extension emulation class
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

require_once FREEBEER_BASE . '/lib/Debug.php';
require_once FREEBEER_BASE . '/lib/Locale.php';

/*!
	\class fbGettext
	\brief gettext extension emulation class
	
	\todo rewrite to use getInstance() function
	
	\static
*/
class fbGettext {
	/*!
		Initialize the gettext() system for domain \c $domain.

		\param $domain \c string Domain name
		\param $dir \c string Location of the /local directory containing the .po files
		\return \c void
		\static
	*/
	function init($domain = 'freebeer', $dir = null) {
		if (is_null($dir)) {
			$dir = FREEBEER_BASE . '/po';
		}

		bindtextdomain($domain, $dir);
		textdomain($domain);
	}

	/*!
		Get the translation table for domain \c $domain

		\param $domain \c string Domain name
		\return \c array The translation table for domain \c $domain
		\private
		\static
	*/
	function _domain($domain = null) {
fbDebug::enter();
		static $_domain = '';

		if (!is_null($domain)) {
			$_domain = $domain;
			fbGettext::_loadData();
		}

		return $_domain;
	}

	/*!
		Get the static text domain array

		\return \c array Reference to static text domain array
		\private
		\static
	*/
	function &_text_domain() {
fbDebug::enter();
		static $_text_domain = array();

		return $_text_domain;
	}

	/*!
		Get the static text domain array

		\return \c array Reference to static text domain array
		\private
		\static
	*/
	function &_translation_map() {
fbDebug::enter();
		static $_translation_map = array();

		return $_translation_map;
	}

	/*!
		Load the translation table for the domain

		\return \c array The translation table for domain \c $domain, or false

		\private
		\static
	*/
	function _loadData() {
fbDebug::enter();
		$text_domain	= &fbGettext::_text_domain();
		$domain			= fbGettext::_domain();

		static $original_locale = null;

fbDebug::dump($text_domain, 'text_domain');
fbDebug::dump($domain, 'domain');

		// \todo fixme
		$dir = isset($text_domain[$domain])
			? $text_domain[$domain] . '/'
			: FREEBEER_BASE . '/lib/locale/';

		$locale = fbLocale::getLocale();
fbDebug::dump($locale, 'locale');
		assert('strlen($locale)');

		if ($original_locale === null) {
			$original_locale = $locale;
		}

		$translation_map = &fbGettext::_translation_map();

		foreach ($translation_map as $key => $value) {
			unset($translation_map[$key]);
		}

		if ($locale == $original_locale) {
			return true;
		}

		if (!@is_dir($dir)) {
			trigger_error(sprintf("Directory '%s' not found", $dir), E_USER_WARNING);
			return false;
		}

		$file = $dir . $locale . '.po';

fbDebug::dump($file, 'file');

		while (!is_file($file)) {
			if (strpos($file, '_') === false) {
				$file = $dir . $locale . '_' . strtoupper($locale) . '.po';
				if (is_file($file) && is_readable($file)) {
					break;
				}
			}

			$nearest_locales = fbLocale::getNearestLocales($locale);
			
			if (!is_array($nearest_locales)) {
				$nearest_locales = array($nearest_locales);
			}
			
			foreach ($nearest_locales as $nearest_locale) {
				//trigger_error(sprintf('File \'%s\' not found', $file), E_USER_NOTICE);

				$file = $dir . $nearest_locale . '.po';

				if (is_file($file) && is_readable($file)) {
					break 2;
				}

				if (strpos($file, '_') === false) {
					$file = $dir . $nearest_locale . '_' . strtoupper($nearest_locale) . '.po';
					if (is_file($file) && is_readable($file)) {
						break 2;
					}
				}
			}
			
			return false;
		}

		$f = file($file);

		if (!$f) {
			trigger_error(sprintf('File \'%s\' is not readable', $file), E_USER_WARNING);
			return false;
		}

		foreach($f as $l) {
			if (preg_match('|msgid\s+["\'](.*)["\']|U', $l, $matches)) {
				$msgid = $matches[1];
				continue;
			}
			if (preg_match('|msgstr\s+["\'](.*)["\']|U', $l, $matches)) {
				if ($msgid) {
					$translation_map[$msgid] = $matches[1];
					$msgid = '';
				}
				continue;
			}
		}

fbDebug::dump($translation_map, 'translation_map');

		return true;
	}

	/*!
		\static
	*/
	function bindtextdomain($domain, $directory) {
fbDebug::enter();
		$text_domain = &fbGettext::_text_domain();

		$text_domain[$domain] = $directory;
		return $directory;
	}

	/*!
		\static
	*/
	function textdomain($domain = null) {
fbDebug::enter();
		return fbGettext::_domain($domain);
	}

	/*!
		\static
	*/
	function gettext($message) {
fbDebug::enter();

		static $current_locale = null;

		$locale = fbLocale::getLocale();
		if ($locale != $current_locale) {
			fbGettext::_loadData();
			$current_locale = $locale;
		}

		$translation_map = &fbGettext::_translation_map();

fbDebug::dump($translation_map, 'translation_map');

		if (isset($translation_map[$message])) {
			return $translation_map[$message];
		}
		return $message;
	}

	/*!
		\todo Implement
		\static
	*/
	function bind_textdomain_codeset($domain, $codeset) {
		// \todo implement fbGettext::bind_textdomain_codeset()
		trigger_error('Function not implemented: \'bind_textdomain_codeset\'', E_USER_WARNING);
	}

	/*!
		\todo Implement
		\static
	*/
	function dcgettext($domain, $message, $category) {
		// \todo implement fbGettext::dcgettext();
		trigger_error('Function not implemented: \'dcgettext\'', E_USER_WARNING);
	}

	/*!
		\todo Implement
		\static
	*/
	function dcngettext($domain, $msgid1, $msgid2, $n, $category) {
		// \todo implement fbGettext::dcngettext();
		trigger_error('Function not implemented: \'dcngettext\'', E_USER_WARNING);
	}

	/*!
		\todo Implement
		\static
	*/
	function dgettext($domain, $message) {
		// \todo implement fbGettext::dgettext();
		trigger_error('Function not implemented: \'dgettext\'', E_USER_WARNING);
	}

	/*!
		\todo Implement
		\static
	*/
	function dngettext($domain, $msgid1, $msgid2, $n) {
		// \todo implement fbGettext::dngettext();
		trigger_error('Function not implemented: \'dngettext\'', E_USER_WARNING);
	}

	/*!
		\todo Implement
		\static
	*/
	function ngettext($msgid1, $msgid2, $n) {
		// \todo implement fbGettext::ngettext();
		trigger_error('Function not implemented: \'ngettext\'', E_USER_WARNING);
	}

}

if (extension_loaded('gettext')) {
	return 1;
}

# Specify the character encoding in which the messages from the DOMAIN message catalog will be returned (PHP 4 >= 4.2.0)
# string bind_textdomain_codeset ( string domain, string codeset )

if (!function_exists('bind_textdomain_codeset')) {
	function bind_textdomain_codeset($domain, $codeset) {
		return fbGettext::bind_textdomain_codeset($domain, $codeset);
	}
}

# Sets the path for a domain (PHP 3>= 3.0.7, PHP 4 )
# string bindtextdomain ( string domain, string directory )

if (!function_exists('bindtextdomain')) {
	function bindtextdomain($domain, $directory) {
		return fbGettext::bindtextdomain($domain, $directory);
	}
}

# Overrides the domain for a single lookup (PHP 3>= 3.0.7, PHP 4 )
# string dcgettext ( string domain, string message, int category )

if (!function_exists('dcgettext')) {
	function dcgettext($domain, $message, $category) {
		return fbGettext::dcgettext($domain, $message, $category);
	}
}

# Plural version of dcgettext (PHP 4 >= 4.2.0)
# string dcngettext ( string domain, string msgid1, string msgid2, int n, int category )

if (!function_exists('dcngettext')) {
	function dcngettext($domain, $msgid1, $msgid2, $n, $category) {
		return fbGettext::dcngettext($domain, $msgid1, $msgid2, $n, $category);
	}
}

# Override the current domain (PHP 3>= 3.0.7, PHP 4 )
# string dgettext ( string domain, string message )

if (!function_exists('dgettext')) {
	function dgettext($domain, $message) {
		return fbGettext::dgettext($domain, $message);
	}
}

# Plural version of dgettext (PHP 4 >= 4.2.0)
# string dngettext ( string domain, string msgid1, string msgid2, int n )

if (!function_exists('dngettext')) {
	function dngettext($domain, $msgid1, $msgid2, $n) {
		return fbGettext::dngettext($domain, $msgid1, $msgid2, $n);
	}
}

# Lookup a message in the current domain (PHP 3>= 3.0.7, PHP 4 )
# string gettext ( string message )

if (!function_exists('gettext')) {
	function gettext($message) {
fbDebug::enter();
		return fbGettext::gettext($message);
	}
}

if (!function_exists('_')) {
	function _($message) {
fbDebug::enter();
		return fbGettext::gettext($message);
	}
}

# Plural version of gettext (PHP 4 >= 4.2.0)
# string ngettext ( string msgid1, string msgid2, int n )

if (!function_exists('ngettext')) {
	function ngettext($msgid1, $msgid2, $n) {
		return fbGettext::ngettext($msgid1, $msgid2, $n);
	}
}

# Sets the default domain (PHP 3>= 3.0.7, PHP 4 )
# string textdomain ( string text_domain )

if (!function_exists('textdomain')) {
	function textdomain($text_domain) {
		return fbGettext::textdomain($text_domain);
	}
}

// doxygen does like this
// return 1;

?>
