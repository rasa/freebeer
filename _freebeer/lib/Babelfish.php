<?php

// $CVSHeader: _freebeer/lib/Babelfish.php,v 1.2 2004/03/07 17:51:16 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file Babelfish.php
	\brief Babelfish (Alta Vista's translation utility) related functions 	
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

if (phpversion() <= '4.1.0') {
	include_once FREEBEER_BASE . '/lib/Backport.php';	// $_SERVER
}

/*!
	\class fbBabelfish
	\brief Babelfish (Alta Vista's translation utility) related functions 	

	\see http://babelfish.altavista.com/babelfish/

	\static
*/
class fbBabelfish {
	/*!
		Return the host name of the server

		\return \c string host name of the server
		\static
	*/
	function host() {
		global $_SERVER; // < 4.1.0

		static $host = null;
		
		while (is_null($host)) {
			if (isset($_SERVER['HTTP_HOST'])) {
				// HTTP_HOST already includes a ':port' if it is used
				$host = $_SERVER['HTTP_HOST'];
				break;
			}

			if (!isset($_SERVER['SERVER_NAME'])) {
				$host = false;
				break;
			}

			$host = $_SERVER['SERVER_NAME'];

			if (!isset($_SERVER['SERVER_PORT'])) {
				break;
			}

			if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) {
				if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 443) {
					$host .= ':' . $_SERVER['SERVER_PORT'];
				}
			} else {
				if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80) {
					$host .= ':' . $_SERVER['SERVER_PORT'];
				}
			}
			break;
		}
		
		return $host;
	}
	
	/*!
		Return babelfish URL to translate \c $lang_from to \c $lang_to

		\param $lang_from \c string Two character language code to translate from
		\param $lang_to \c string Two character language code to translate from
		\return \c string babelfish URL to translate \c $lang_from to \c $lang_to

		\static
	*/
	function url($lang_from, $lang_to) {
		global $_SERVER; // < 4.1.0
		
		static $url_map = array(
			'en'	=> 'english',
			'fr'	=> 'french',
			'de'	=> 'german',
			'it'	=> 'italian',
			'es'	=> 'spanish',
			'pt'	=> 'portugese',
		);

		$lang_from = strtolower($lang_from);
		$lang_to = strtolower($lang_to);
		
		if (!isset($url_map[$lang_from])) {
			return '';
		}
		
		$url = 'http://jump.altavista.com/translate_' . $url_map[$lang_from] . '.go' .
			'?http://babelfish.altavista.com/babelfish/tr?doit=done' .
			'&amp;lp=' . $lang_from . '_' . $lang_to .
			'&amp;urltext=http';
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
			$url .= 's';
		}
		$url .= '://' . fbBabelfish::host() . $_SERVER['REQUEST_URI'] .
			(strpos('?', $_SERVER['REQUEST_URI']) ? '&amp;' : '?') .
			'babelfish=' . $lang_from . '_' . $lang_to;

		return $url;
	}

	/*!
		Return HTML of babelfish links

		\param $lang_from \c string Two character language code to translate from
		\return \c array array of HTML of babelfish links
		\static
	*/
	function links($lang_from = 'en') {
		global $_SERVER; // < 4.1.0

		static $fishes = array(
			'en' => array(	# English
				'de' => '&Uuml;bersetzen&nbsp;Sie&nbsp;diese&nbsp;Seite&nbsp;in&nbsp;Deutschen',
				'es' => 'Traduzca&nbsp;esta&nbsp;paginaci&oacute;n&nbsp;a&nbsp;espa&ntilde;ol',
				'fr' => 'Traduisez&nbsp;cette&nbsp;page&nbsp;en&nbsp;fran&ccedil;ais',
				'it' => 'Tradurre&nbsp;questa&nbsp;pagina&nbsp;in&nbsp;italiano',
				'pt' => 'Traduza&nbsp;esta&nbsp;p&aacute;gina&nbsp;em&nbsp;portugu&ecirc;ses',
				'zh' => '&#x7ffb;&#x8bd1;&#x8fd9;&#x9875;&#x6210;&#x6c49;&#x8bed;&nbsp;(CN)',
				'ja' => '&#x65e5;&#x672c;&#x8a9e;&#x306b;&#x3053;&#x306e;&#x30da;&#x30fc;&#x30b8;&#x3092;&#x7ffb;&#x8a33;&#x3057;&#x306a;&#x3055;&#x3044;&nbsp;(Nihongo)',
				'ko' => '&#xd55c;&#xad6d;&#xc778;&#xc73c;&#xb85c;&nbsp;&#xc774;&nbsp;&#xd398;&#xc774;&#xc9c0;&#xb97c;&nbsp;&#xbc88;&#xc5ed;&#xd558;&#xc2ed;&#xc2dc;&#xc694;&nbsp;(Hangul)',
			),
			'fr' => array(	# French
				'de' => '&Uuml;bersetzen&nbsp;Sie&nbsp;diese&nbsp;Seite&nbsp;in&nbsp;Deutschen',
				'en' => 'Translate&nbsp;this&nbsp;page&nbsp;into&nbsp;English',
			),
			'de' => array(	# German
				'en' => 'Translate&nbsp;this&nbsp;page&nbsp;into&nbsp;English',
				'fr' => 'Traduisez&nbsp;cette&nbsp;page&nbsp;en&nbsp;fran&ccedil;ais',
			),
			'it' => array(	# Italian
				'en' => 'Translate&nbsp;this&nbsp;page&nbsp;into&nbsp;English',
			),
			'es' => array(	# Spanish
				'en' => 'Translate&nbsp;this&nbsp;page&nbsp;into&nbsp;English',
			),
			'pt' => array(	# Portugese
				'en' => 'Translate&nbsp;this&nbsp;page&nbsp;into&nbsp;English',
			),
			'ru' => array(	# Russian
				'en' => 'Translate&nbsp;this&nbsp;page&nbsp;into&nbsp;English',
			),
		);

		// \todo Use phpsniff or PEAR's Net_UserAgent_Detect to detect the browser type
		// as Netscape 4.x and possibly others displays '&#xabcd;' literally
//		if (preg_match('/(mozilla\/4)/i', $_SERVER['HTTP_USER_AGENT'])) {
//			$fishes['en']['zh'] = 'Translate&nbsp;this&nbsp;page&nbsp;into&nbsp;Chinese&nbsp;(CN)';
//			$fishes['en']['ja'] = 'Translate&nbsp;this&nbsp;page&nbsp;into&nbsp;Japenese&nbsp;(Nihongo)';
//			$fishes['en']['ko'] = 'Translate&nbsp;this&nbsp;page&nbsp;into&nbsp;Korean&nbsp;(Hangul)';
//		}

		// If we have already translated this page (babelfish=en_fr), then don't display the strings again
		if (!isset($fishes[$lang_from]) || isset($_GET['babelfish'])) {
			return array();
		}

		$a = array();
		foreach ($fishes[$lang_from] as $lang_to => $msg) {
			$a[] = array('target' => $lang_to,
                         'href'   => fbBabelfish::url($lang_from, $lang_to),
                         'msg'    => $msg);
		}

		return $a;
	}

	/*!
		Return JavaScript code to display babelfish logo

		\param $lang \c string Two character language code of source language
		\return \c string JavaScript code to display babelfish logo
		\static
	*/
	function logo($lang = 'en') {
		static $js = "\n<script language=\"JavaScript1.2\" src=\"http://www.altavista.com/r?%str\"></script>\n";

		static $map = array(
			'en'	=> 'en',
			'de'	=> 'de',
			'fr'	=> 'fr',
			'it'	=> 'it',
			'es'	=> 'esp',
			'pt'	=> 'port',
		);

		$lang = strtolower($lang);

		return isset($map[$lang]) ? sprintf($js, $map[$lang]) : '';
	}

}

?>
