<?php

// $CVSHeader: _freebeer/lib/Locale.php,v 1.2 2004/03/07 17:51:17 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file Locale.php
	\brief Locale related functions
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

if (phpversion() <= '4.2.0') {
	include_once FREEBEER_BASE . '/lib/Backport.php'; // array_change_key_case()
}

require_once FREEBEER_BASE . '/lib/System.php';
require_once FREEBEER_BASE . '/lib/Debug.php';
require_once FREEBEER_BASE . '/lib/ISO639.php';
require_once FREEBEER_BASE . '/lib/ISO3166.php';
require_once FREEBEER_BASE . '/lib/StrUtils.php';

if (preg_match('/^win/i', PHP_OS)) {
	include_once FREEBEER_BASE . '/lib/ISO639/Alpha3.php';
//	include_once 'fbISO639_Map.php';
}

// include_once 'fbDateTime.php'; // included below as needed

/*!
	\class fbLocale
	\brief Locale related functions

	On both Debian Linux (sid) and Windows XP Home SP1:

		echo setlocale($category, 'Italian'),"\n";
		echo setlocale($category, null),"\n";

	will return:

		Italian_Italy.1252
		English_United States.1252

	It seems, contrary to what the documentation states,
	that setlocale($category, null) does *not* return the current locale.

	Therefore, we have to cache the last return value from setlocale()
	and hope no one else ever calls setlocale() directly.

	If they do, they should call fbLocale::setLocale() when they're done,
	to let us know what the current locale should be.

	\todo look up default locale in config file, instead of guessing
	\todo add getNearestLocale() logic back
	\todo don't call setlocale() if the current locale is the same
	\todo Passing multiple locales at once if >= 4.3.0

	\static
*/
class fbLocale {
	/*!
		\return \c void
		\private
		\static
	*/
	function _init() {
		fbLocale::getDefaultLocale();
	}

	/*!
		\return \c array
		\private
		\static
	*/
	function &_locale() {
		static $_locale = null;

		if (is_null($_locale)) {
			$_locale = array(
				LC_ALL		=> false,
				LC_COLLATE	=> false,
				LC_CTYPE	=> false,
				LC_MONETARY	=> false,
				LC_NUMERIC	=> false,
				LC_TIME		=> false,
			);
		};

		return $_locale;
	}

	/*!
		\param $category \c int (LC_ALL (default), LC_COLLATE, LC_TYPE, LC_MONETARY, LC_NUMERIC, or LC_TIME)
		\param $locale \c string (default is current locale) locale string
		\return \c string
		\private
		\static
	*/
	function _setLocale($category, $locale) {
fbDebug::enter();

/// \todo TEST!
/*
		$current_locale = fbLocale::_getLocale($category);
		if ($locale && $current_locale == $locale) {
			return $locale;
		}
*/

		$rv = setlocale($category, $locale);
		if (!$rv) {
			/// \todo log setlocale() failure?
fbDebug::leave($rv);
			return false;
		}

//echo "<pre>\nlocaleconv()=";
//print_r(localeconv());

		$_locale = &fbLocale::_locale();

		$_locale[$category] = $rv;

		if ($category == LC_ALL) {
			$_locale[LC_COLLATE]	= $rv;
			$_locale[LC_CTYPE]		= $rv;
			$_locale[LC_MONETARY]	= $rv;
			$_locale[LC_NUMERIC]	= $rv;
			$_locale[LC_TIME]		= $rv;
		}

fbDebug::leave($rv);
		return $rv;
	}

	/*!
		\param $category \c int (LC_ALL (default), LC_COLLATE, LC_TYPE, LC_MONETARY, LC_NUMERIC, or LC_TIME)
		\return \c string

		\static
	*/
	function _getLocale($category = LC_ALL) {
fbDebug::enter();
		$_locale = &fbLocale::_locale();

		$rv = isset($_locale[$category]) ? $_locale[$category] : false;
fbDebug::leave($rv);
		return $rv;
	}

	/*!
		\return \c array
		\private
		\static
	*/
	function &_locale_cache() {
		static $_locale_cache = array();

		return $_locale_cache;
	}

	/*!
		Parses locale string returned by setlocale().

		Returns a hash.

		For example, _parseLocale('en_US.ISO8859-1') returns:

		array(
			'locale'		=> 'en_US.ISO8859-1',
			'language_id'	=> 'EN',
			'language'		=> 'English',
			'country_id'	=> 'US',
			'country'		=> 'United States',
			'charset'		=> 'ISO8859-1',
			'codepage'		=> '1252',
		);

		This function can parse Windows locales as well.

		For example, _parseLocale('English_United Kingdom.1252') returns:

		array(
			'locale'		=> 'en_GB.ISO8859-1',
			'language_id'	=> 'EN',
			'language'		=> 'English',
			'country_id'	=> 'GB',
			'country'		=> 'United Kingdom',
			'charset'		=> 'ISO8859-1',
			'codepage'		=> '1252',
		);

		\param $locale \c string (default is current locale) locale string
		\return \c hash
		\private
		\static
	*/
	function _parseLocale($locale = null) {
		static $_codepage_to_charset_map = array(
			'1250'	=> 'ISO8859-2',
			'1251'	=> 'ISO8859-5',
			'1252'	=> 'ISO8859-1',
			'1253'	=> 'ISO8859-1', /// \todo Determine correct charset for 1253
			'1254'	=> 'ISO8859-9',
			'1257'	=> 'ISO8859-13',
			'1252'	=> 'ISO885915',	// I can't find this anywhere yet, but I think it's right
		);

		if (is_null($locale)) {
			$locale = fbLocale::_getLocale(LC_ALL);
		}

		$iso_locale		= false;
		$language_id	= false;
		$language		= false;
		$country		= false;
		$country_id		= false;
		$charset		= false;
		$codepage		= false;

		do {
			$lang = $locale;
			if (preg_match('/LC_COLLATE=([^;]*)/i', $locale, $matches)) {
				if ($matches[1] == 'C') {
					if (preg_match('/LC_CTYPE=([^;]*)/i', $locale, $matches)) {
						$lang = $matches[1];
					}
				} else {
					$lang = $matches[1];
				}
			}

			/// \todo deal with ll-CC

			if (preg_match('/([^\._]+)[\._]*(.*)/i', $lang, $matches)) {
				$lang = $matches[1];
				$coun = $matches[2];

				if (preg_match('/([^\._]+)[\._]*(.*)/i', $coun, $matches)) {
					$coun = $matches[1];
					$charset = $matches[2];
				}
			}

if ($lang == 'C') {
		return array(
			'locale'		=> 'C',
			'language_id'	=> '',
			'language'		=> '',
			'country_id'	=> '',
			'country'		=> '',
			'charset'		=> '',
			'codepage'		=> '',
		);
}

			$language	= fbISO639::getLanguageName($lang);
			if ($language) {
				$language_id = $lang;
			} else {
				$language_id = fbISO639::getLanguageId($lang);
				if (!$language_id) {
					trigger_error(sprintf('Language not found: \'%s\'', $lang), E_USER_WARNING);
					break;
				}
				$language = $lang;
			}

			$iso_locale = fbString::strtolower($language_id);

			$country		= fbISO3166::getCountryName($coun);
			if ($country) {
				$country_id = fbString::strtoupper($coun);
			} else {
				$country_id = fbISO3166::getCountryId($coun);
				if ($coun && !$country_id) {
					trigger_error(sprintf('Country not found: \'%s\'', $coun), E_USER_WARNING);
					break;
				}
			}

			$iso_locale .= '_' . fbString::strtoupper($country_id);

			if ($charset) {
				$charset = fbString::strtoupper($charset);
				if (isset($_codepage_to_charset_map[$charset])) {
					$codepage = $charset;
					$charset = $_codepage_to_charset_map[$charset];
				} else {
					$_charset_to_codepage_map = array_flip($_codepage_to_charset_map);
					if (isset($_charset_to_codepage_map[$charset])) {
						$codepage = $_charset_to_codepage_map[$charset];
					} else {
						trigger_error(sprintf('Charset not found: \'%s\'', $charset), E_USER_NOTICE);
						break;
					}
				}

				$iso_locale .= '.' . $charset;
			}
		} while (false);

		return array(
			'locale'		=> $iso_locale,
			'language_id'	=> fbString::strtoupper($language_id),
			'language'		=> $language,
			'country_id'	=> fbString::strtoupper($country_id),
			'country'		=> $country,
			'charset'		=> fbString::strtoupper($charset),
			'codepage'		=> $codepage,
		);
	}

	/*!
		\param $category \c int (LC_ALL (default), LC_COLLATE, LC_TYPE, LC_MONETARY, LC_NUMERIC, or LC_TIME)
		\param $locale \c string (default is current locale) locale string
		\return \c string
		\static
	*/
	function getLocale($category = LC_ALL, $locale = null) {
fbDebug::enter();
		assert( '$category == LC_ALL   || $category == LC_COLLATE ||'.
				'$category == LC_CTYPE || $category == LC_MONETARY ||'.
				'$category == LC_NUMERIC  || $category == LC_TIME');

fbDebug::dump($locale, '$locale');

		if (is_null($locale)) {
			$locale = fbLocale::_getLocale($category);

fbDebug::dump($locale, '$locale');

			if (!$locale || $locale == 'C') {
				$locale = fbLocale::getDefaultLocale();
			}
fbDebug::dump($locale, '$locale');
		}

		$_locale_cache = &fbLocale::_locale_cache();

		if (!isset($_locale_cache[$category][$locale])) {
			$a = fbLocale::_parseLocale($locale);
fbDebug::dump($a, '$a');
			$iso_locale = $a['locale'];
			$_locale_cache[$category][$locale]		= $a;
			$_locale_cache[$category][$iso_locale]	= $a;
		}

fbDebug::dump($_locale_cache, '$_locale_cache');

		$rv = $_locale_cache[$category][$locale]['locale'];
fbDebug::leave($rv);
		return $rv;
	}

	/*!
		\param $category \c int (LC_ALL (default), LC_COLLATE, LC_TYPE, LC_MONETARY, LC_NUMERIC, or LC_TIME)
		\param $locale \c string (default is current locale) locale string
		\param $key \c string (locale, language_id, language, country_id, country, charset or codepage)
		\return \c string
		\private
		\static
	*/
	function _get($category = LC_ALL, $locale = null, $key = 'locale') {
		$_locale_cache = &fbLocale::_locale_cache();

		if (is_null($locale)) {
			$locale = fbLocale::getLocale($category, $locale);
		}

		return isset($_locale_cache[$category][$locale][$key])
			? $_locale_cache[$category][$locale][$key]
			: false;
	}

	/*!
		\param $category \c int (LC_ALL (default), LC_COLLATE, LC_TYPE, LC_MONETARY, LC_NUMERIC, or LC_TIME)
		\param $locale \c string (default is current locale) locale string
		\return \c string
		\static
	*/
	function getLanguageID($category = LC_ALL, $locale = null) {
		return fbLocale::_get($category, $locale, 'language_id');
	}

	/*!
		\param $category \c int (LC_ALL (default), LC_COLLATE, LC_TYPE, LC_MONETARY, LC_NUMERIC, or LC_TIME)
		\param $locale \c string (default is current locale) locale string
		\return \c string
		\static
	*/
	function getLanguageName($category = LC_ALL, $locale = null) {
		return fbLocale::_get($category, $locale, 'language');
	}

	/*!
		\param $category \c int (LC_ALL (default), LC_COLLATE, LC_TYPE, LC_MONETARY, LC_NUMERIC, or LC_TIME)
		\param $locale \c string (default is current locale) locale string
		\return \c string
		\static
	*/
	function getCountryID($category = LC_ALL, $locale = null) {
		return fbLocale::_get($category, $locale, 'country_id');
	}

	/*!
		\param $category \c int (LC_ALL (default), LC_COLLATE, LC_TYPE, LC_MONETARY, LC_NUMERIC, or LC_TIME)
		\param $locale \c string (default is current locale) locale string
		\return \c string
		\static
	*/
	function getCountryName($category = LC_ALL, $locale = null) {
		return fbLocale::_get($category, $locale, 'country');
	}

	/*!
		\param $category \c int (LC_ALL (default), LC_COLLATE, LC_TYPE, LC_MONETARY, LC_NUMERIC, or LC_TIME)
		\param $locale \c string (default is current locale) locale string
		\return \c string
		\static
	*/
	function getCharset($category = LC_ALL, $locale = null) {
		return fbLocale::_get($category, $locale, 'charset');
	}

	/*!
		\param $category \c int (LC_ALL (default), LC_COLLATE, LC_TYPE, LC_MONETARY, LC_NUMERIC, or LC_TIME)
		\param $locale \c string (default is current locale) locale string
		\return \c string
		\static
	*/
	function getCodepage($category = LC_ALL, $locale = null) {
		return fbLocale::_get($category, $locale, 'codepage');
	}

	/*!
		\return \c string
		\static
	*/
	function getDefaultLanguageID() {
		return fbLocale::_get($category, 'C', 'language_id');
	}

	/*!
		\return \c string
		\static
	*/
	function getDefaultLanguageName() {
		return fbLocale::_get($category, 'C', 'language');
	}

	/*!
		\return \c string
		\static
	*/
	function getDefaultCountryID() {
		return fbLocale::_get($category, 'C', 'country_id');
	}

	/*!
		\return \c string
		\static
	*/
	function getDefaultCountryName() {
		return fbLocale::_get($category, 'C', 'country');
	}

	/*!
		\return \c string
		\static
	*/
	function getDefaultCharset() {
		return fbLocale::_get($category, 'C', 'charset');
	}

	/*!
		\return \c string
		\static
	*/
	function getDefaultCodepage() {
		return fbLocale::_get($category, 'C', 'codepage');
	}

	/*!
		If setlocale() returns 'C', _guessLocale() determines the current locale by comparing all
		the long month names returned by strftime against a known list.

		\return \c array
		\private
		\static
	*/
	function _guessLocale() {
fbDebug::enter();
		static $language_months_map = array(
			'MN'	=> '1?????????????????????,2??????????????????????,3??????????????????????,4?????????????????????,5??????????????????????,6??????????????????????,7??????????????????????,8??????????????????????,9?????????????????????,10??????????????????????,11?????????????????????,12??????????????????????', // Mongolian (and MO/Moldavian)
			'KA'	=> '??????????,?????????,????????????,?????????,??????????,????????????,??????????,??????????,??????????????,?????????,???????????,?????????????????', // Georgian (and KK/Kazakh)
			'DE'	=> 'Januar,Februar,M??rz,April,Mai,Juni,Juli,August,September,Oktober,November,Dezember', // German
			'MS'	=> 'Januari,Februari,Mac,April,Mei,Jun,Julai,Ogos,September,Oktober,November,Disember', // Malay
			'IN'	=> 'Januari,Februari,Maret,April,Mei,Juni,Juli,Agustus,September,Oktober,Nopember,Desember', // Indonesian
			'AF'	=> 'Januarie,Februarie,Maart,April,Mei,Junie,Julie,Augustus,September,Oktober,November,Desember', // Afrikaans
			'EN'	=> 'January,February,March,April,May,June,July,August,September,October,November,December', // English
			'TR'	=> 'Ocak,??ubat,Mart,Nisan,May??s,Haziran,Temmuz,A??ustos,Eyl??l,Ekim,Kas??m,Aral??k', // Turkish
			'AZ'	=> 'Yanvar,Fevral,Mart,Aprel,May,??yun,??yul,Avgust,Sentyabr,Oktyabr,Noyabr,Dekabr', // Azerbaijani
			'ES'	=> 'enero,febrero,marzo,abril,mayo,junio,julio,agosto,septiembre,octubre,noviembre,diciembre', // Spanish
			'CA'	=> 'gener,febrer,mar??,abril,maig,juny,juliol,agost,setembre,octubre,novembre,desembre', // Catalan
			'IT'	=> 'gennaio,febbraio,marzo,aprile,maggio,giugno,luglio,agosto,settembre,ottobre,novembre,dicembre', // Italian
			'RO'	=> 'ianuarie,februarie,martie,aprilie,mai,iunie,iulie,august,septembrie,octombrie,noiembrie,decembrie', // Romanian
			'ET'	=> 'jaanuar,veebruar,m??rts,aprill,mai,juuni,juuli,august,september,oktoober,november,detsember', // Estonian
			'SQ'	=> 'janar,shkurt,mars,prill,maj,qershor,korrik,gusht,shtator,tetor,n??ntor,dhjetor', // Albanian
			'PT'	=> 'janeiro,fevereiro,mar??o,abril,maio,junho,julho,agosto,setembro,outubro,novembro,dezembro', // Portuguese
			'SL'	=> 'januar,februar,marec,april,maj,junij,julij,avgust,september,oktober,november,december', // Slovenian
			'NO'	=> 'januar,februar,mars,april,mai,juni,juli,august,september,oktober,november,desember', // Norwegian (and NN/Norwegian (Nynorsk), NB/Norwegian (Bokm??l))
// same as NO
//			'FA'	=> 'januar,februar,mars,apr??l,mai,juni,juli,august,september,oktober,november,desember', // FO/Faeroese
			'SR'	=> 'januar,februar,mart,april,maj,jun,jul,avgust,septembar,oktobar,novembar,decembar', // Serbian
			'DA'	=> 'januar,februar,marts,april,maj,juni,juli,august,september,oktober,november,december', // Danish
			'NL'	=> 'januari,februari,maart,april,mei,juni,juli,augustus,september,oktober,november,december', // Dutch
			'SV'	=> 'januari,februari,mars,april,maj,juni,juli,augusti,september,oktober,november,december', // Swedish
			'SK'	=> 'janu??r,febru??r,marec,apr??l,m??j,j??n,j??l,august,september,okt??ber,november,december', // Slovak
			'HU'	=> 'janu??r,febru??r,m??rcius,??prilis,m??jus,j??nius,j??lius,augusztus,szeptember,okt??ber,november,december', // Hungarian
			'FR'	=> 'janvier,f??vrier,mars,avril,mai,juin,juillet,ao??t,septembre,octobre,novembre,d??cembre', // French
			'LA'	=> 'janv??ris,febru??ris,marts,apr??lis,maijs,j??nijs,j??lijs,augusts,septembris,oktobris,novembris,decembris', // Latin (and LV/Latvian)
			'IS'	=> 'jan??ar,febr??ar,mars,apr??l,ma??,j??n??,j??l??,??g??st,september,okt??ber,n??vember,desember', // Icelandic
			'CS'	=> 'leden,??nor,b??ezen,duben,kv??ten,??erven,??ervenec,srpen,z??????,????jen,listopad,prosinec', // Czech
			'LT'	=> 'sausis,vasaris,kovas,balandis,gegu????,bir??elis,liepa,rugpj??tis,rugs??jis,spalis,lapkritis,gruodis', // Lithuanian
			'HR'	=> 'sije??anj,velja??a,o??ujak,travanj,svibanj,lipanj,srpanj,kolovoz,rujan,listopad,studeni,prosinac', // Croatian
			'PL'	=> 'stycze??,luty,marzec,kwiecie??,maj,czerwiec,lipiec,sierpie??,wrzesie??,pa??dziernik,listopad,grudzie??', // Polish
			'FI'	=> 'tammikuu,helmikuu,maaliskuu,huhtikuu,toukokuu,kes??kuu,hein??kuu,elokuu,syyskuu,lokakuu,marraskuu,joulukuu', // Finnish
			'BA'	=> 'urtarrila,otsaila,martxoa,apirila,maiatza,ekaina,uztaila,abuztua,iraila,urria,azaroa,abendua', // Bashkir (and EU/Basque)
			'GA'	=> 'xaneiro,febreiro,marzo,abril,maio,xu??o,xullo,agosto,setembro,outubro,novembro,decembro', // Irish (and GL/Galician)
			'UZ'	=> 'yanvar,fevral,mart,aprel,may,iyun,iyul,avgust,sentyabr,oktyabr,noyabr,dekabr', // Uzbek
			'FY'	=> '??????????????,????????????????,????????,??????????,??????,????????,????????,????????????,??????????????????,????????????????,??????????????,????????????????', // Frisian
			/// \todo TA for Tatar?
			'TA'	=> '????????????????,??????????????,????????,????????????,??????,????????,????????,????????????,????????????????,??????????????,????????????,??????????????', // Tatar
			'EL'	=> '????????????????????,??????????????????????,??????????????,????????????????,??????????,??????????????,??????????????,??????????????????,??????????????????????,??????????????????,??????????????????,????????????????????', // Greek
			'UK'	=> '????????????,??????????,????????????????,??????????????,??????????????,??????????????,????????????,??????????????,????????????????,??????????????,????????????????,??????????????', // Ukrainian
			'BE'	=> '????????????????,????????,??????????????,????????????????,??????,??????????????,????????????,??????????????,????????????????,????????????????????,????????????????,??????????????', // Byelorussian
			'RU'	=> '????????????,??????????????,????????,????????????,??????,????????,????????,????????????,????????????????,??????????????,????????????,??????????????', // Russian (and KY/Kirghiz)
			'BG'	=> '????????????,????????????????,????????,??????????,??????,??????,??????,????????????,??????????????????,????????????????,??????????????,????????????????', // Bulgarian
		);

		include_once FREEBEER_BASE . '/lib/DateTime.php';

		$locale = fbLocale::_getLocale(LC_ALL);

		$month_names = fbDateTime::getLongMonthNames($locale);
		if (!$month_names) {
			/// \todo fixme
			trigger_error('fbDateTime::getLongMonthNames() failed!', E_USER_WARNING);
			return false;
		}

		static $months_language_map;

		if (!isset($months_language_map)) {
			$t = array_flip($language_months_map);
			$lc = array_change_key_case($t);
			$months_language_map = array_merge($lc, $t);
		}

		$months = join(',', $month_names);
		if (!isset($months_language_map[$months])) {
			return false;
		}

		$language_id = $months_language_map[$months];

		$language = fbISO639::getLanguageName($language_id);

		$iso_locale = fbString::strtolower($language_id);

		/// \todo Look up country_id using fbISO639_ISO3166_Map::getCountryID()?
		/// \todo default to ISO8559-1 charset?
		/// \todo default to 1252 codepage?

		return array(
			'locale'		=> $iso_locale,
			'language_id'	=> $language_id,
			'language'		=> $language,
			'country_id'	=> '', // fbString::strtoupper($country_id),
			'country'		=> '', // $country,
			'charset'		=> '', // fbString::strtoupper($charset),
			'codepage'		=> '', // $codepage,
		);
	}

	/*!
		If setlocale() returns false or 'C', getDefaultLocale() determines the current locale by comparing all
		the long month names returned by strftime against a known list.

		\return \c string
		\static
	*/
	function getDefaultLocale() {
fbDebug::enter();

		$_locale_cache = &fbLocale::_locale_cache();

		if (isset($_locale_cache[LC_ALL]['C'])) {
fbDebug::leave($_locale_cache[LC_ALL]['C']['locale']);
			return $_locale_cache[LC_ALL]['C']['locale'];
		}

		$_locale = &fbLocale::_locale();

		if (!$_locale[LC_ALL]) {
			fbLocale::_setLocale(LC_ALL		, null);
			fbLocale::_setLocale(LC_COLLATE	, null);
			fbLocale::_setLocale(LC_CTYPE	, null);
			fbLocale::_setLocale(LC_MONETARY, null);
			fbLocale::_setLocale(LC_NUMERIC	, null);
			fbLocale::_setLocale(LC_TIME	, null);
		}

fbDebug::dump($_locale, '$_locale');

		$locale = fbLocale::_getLocale(LC_ALL);

fbDebug::dump($locale, '$locale');

		if ($locale && $locale != 'C') {
			$a = fbLocale::_parseLocale($locale);
		} else {
			$a = fbLocale::_guessLocale();
		}

fbDebug::dump($a, '$a');

		$locale = $a['locale'];

		if ($locale == 'C') {
			$a = fbLocale::_guessLocale();
		}

		$_locale_cache[LC_ALL]['C']		= $a;
		$_locale_cache[LC_ALL][$locale] = $a;

fbDebug::dump($_locale_cache, '$_locale_cache');
fbDebug::dump($locale, '$locale');
fbDebug::leave($locale);

		return $locale;
	}

	/*!
		\param $category \c int (LC_ALL, LC_COLLATE, LC_TYPE, LC_MONETARY, LC_NUMERIC, or LC_TIME)
		\param $locale \c mixed locale string or array of strings
		\return \c string
		\private
		\static
	*/
	function _setLocaleWindows($category, $locale) {
fbDebug::enter();
		static $locale_cache = array();

		if (isset($locale_cache[$locale])) {
			return fbLocale::_setLocale($category, $locale_cache[$locale]);
		}

		$language_id2 = $locale;
		if (preg_match('/^([^_]+)_(.*)$/', $locale, $matches)) {
			$language_id2 = $matches[1];
			$country_id2 = $matches[2];
		}

fbDebug::dump($language_id2, '$language_id2');

		do {
			// get the full language name for the ISO 639 2 character language code
			$language_name = fbISO639::getLanguageName($language_id2);
fbDebug::dump($language_name, '$language_name');
			if (!$language_id2) {
				// if it's not a valid language, then try the locale and return
				$rv = fbLocale::_setLocale($category, $locale);
				break;
			}

			// first, try the full language name
			$rv = fbLocale::_setLocale($category, $language_name);
			if ($rv) {
				break;
			}

			$language_id3 = fbISO639_Alpha3::getLanguageID($language_name);
fbDebug::dump($language_id3, '$language_id3');
			if (!$language_id3) {
				$rv = fbLocale::_setLocale($category, $locale);
				break;
			}

			// next, try the ISO 639 3 character language code
			$rv = fbLocale::_setLocale($category, $language_id3);
			if ($rv) {
				break;
			}

			// next, try the ISO 639 2 character language code
			$rv = fbLocale::_setLocale($category, $language_id2);
			if ($rv) {
				break;
			}

			// finally, try the original locale
			$rv = fbLocale::_setLocale($category, $locale);
		} while (false);

		$locale_cache[$locale] = $rv;

fbDebug::leave($rv);

		return $rv;
	}

	/*!
		\param $category \c int (LC_ALL, LC_COLLATE, LC_TYPE, LC_MONETARY, LC_NUMERIC, or LC_TIME)
		\param $locales \c mixed locale string or array of strings
		\return \c string
		\static
	*/
	function setLocale($category, $locales) {
fbDebug::enter();

		assert( '$category == LC_ALL   || $category == LC_COLLATE ||'.
				'$category == LC_CTYPE || $category == LC_MONETARY ||'.
				'$category == LC_NUMERIC  || $category == LC_TIME');

		static $locale_map = array();

/*
		// \todo get serialize working
		$locale_key = is_array($locales) ? serialize($locales) : $locales;

fbDebug::dump($locale_key, '$locale_key');

		if (!isset($locale_map[$locale_key])) {
			$locale_map[$locale_key] = fbLocale::getNearestLocale($locales);
fbDebug::dump($locale_map, '$locale_map');
		}

		$locale = $locale_map[$locale_key];

		if (!$locale) {
			return setlocale($category, $locales);
		}
*/

		$locale = $locales;

		// this appears to be required in order for the gettext functions to work
		fbSystem::putEnv('LANG', $locale);

		// it's been reported that this is needed to on some systems
		fbSystem::putEnv('LANGUAGE', $locale);

		if (!preg_match('/^win/i', PHP_OS)) {
			$rv = fbLocale::_setLocale($category, $locale);
		} else {
			$rv = fbLocale::_setLocaleWindows($category, $locale);
		}

		$_locale_cache = &fbLocale::_locale_cache();

		if ($rv && !isset($_locale_cache[$category][$rv])) {
			$a = fbLocale::_parseLocale($rv);
			$iso_locale = $a['locale'];
			$_locale_cache[$category][$rv]			= $a;
			$_locale_cache[$category][$iso_locale]	= $a;
		}

fbDebug::leave($rv);

		return $rv;
	}

	/*!
		\param $category \c int (LC_ALL, LC_COLLATE, LC_TYPE, LC_MONETARY, LC_NUMERIC, or LC_TIME)
		\param $locale \c string locale string (optional)
		\return \c mixed
		\private
		\static
	*/
	function _localeStack($category, $locale = null) {
fbDebug::enter();
		assert( '$category == LC_ALL   || $category == LC_COLLATE ||'.
				'$category == LC_CTYPE || $category == LC_MONETARY ||'.
				'$category == LC_NUMERIC  || $category == LC_TIME');

		static $locale_stack = array(
			LC_ALL		=> array(),
			LC_COLLATE	=> array(),
			LC_CTYPE	=> array(),
			LC_MONETARY	=> array(),
			LC_NUMERIC	=> array(),
			LC_TIME		=> array(),
		);

		if (is_null($locale)) {
			return count($locale_stack[$category])
				? array_pop($locale_stack[$category])
				: false;
		}

		array_push($locale_stack[$category], $locale);
	}

	/*!
		\param $category \c int (LC_ALL, LC_COLLATE, LC_TYPE, LC_MONETARY, LC_NUMERIC, or LC_TIME)
		\param $locale \c string (default is current locale) locale string
		\return \c void
		\static
	*/
	function pushLocale($category, $locale) {
fbDebug::enter();
		assert( '$category == LC_ALL   || $category == LC_COLLATE ||'.
				'$category == LC_CTYPE || $category == LC_MONETARY ||'.
				'$category == LC_NUMERIC  || $category == LC_TIME');

		$old_locale = fbLocale::_getLocale($category);

fbDebug::dump($old_locale, '$old_locale');

		fbLocale::_localeStack($category, $old_locale);

		$rv = fbLocale::setLocale($category, $locale);
fbDebug::leave($rv);
		return $rv;
	}

	/*!
		\param $category \c int (LC_ALL, LC_COLLATE, LC_TYPE, LC_MONETARY, LC_NUMERIC, or LC_TIME)
		\return \c void
		\static
	*/
	function popLocale($category) {
fbDebug::enter();
		$locale = fbLocale::_localeStack($category);

		$rv = $locale
			? fbLocale::_setLocale($category, $locale)
			: false;
fbDebug::leave($rv);
		return $rv;
	}

	/*!
		\param $http_accept_language \c string
		\return \c mixed
		\static
	*/
	function &parseAcceptLanguages($http_accept_language = null) {
fbDebug::enter();
		global $_SERVER; // < 4.1.0

		static $http_accept_language_map;

		if (is_null($http_accept_language)) {
			if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
				$http_accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
			}
		}

		if (!isset($http_accept_language_map[$http_accept_language])) {
			if (strpos($http_accept_language, ',') !== false) {
				$languages = explode(',', $http_accept_language);
			} else {
				$languages = array($http_accept_language);
			}

			$locales = array();
			foreach ($languages as $language) {
				$semicolon = strpos($language, ';');
				if ($semicolon !== false) {
					$language = trim(substr($language, 0, $semicolon));
				}
				$language = strtr($language, '-', '_');
				if (strlen($language) == 2) {
					$language = $language . '_' . $language;
				}

				$underscore = strpos($language, '_');
				if ($underscore !== false) {
					$language = fbString::strtolower(substr($language, 0, $underscore)) . '_' .
						fbString::strtoupper(substr($language, $underscore + 1));
				}

				$locales[] = $language;
			}

			$http_accept_language_map[$http_accept_language] = $locales;
		}

fbDebug::leave($http_accept_language_map[$http_accept_language]);

		return $http_accept_language_map[$http_accept_language];
	}

	/*!
		\todo move to fbGetText

		\param $dir \c string
		\return \c array
		\static
	*/
	function &getAvailableLocales($dir = null) {
fbDebug::enter();
		static $locales = array();

		if (is_null($dir)) {
			$dir = FREEBEER_BASE . '/lib/locale';
		}

		if (!isset($locales[$dir])) {
			$locales[$dir] = array();

			if ($dh = @opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					if (preg_match('/(.+)\.po$/i', $file, $matches)) {

						// \todo handle charset specific files such as ru_RU.KOI8-R.po
						$language = substr($matches[1], 0, 2);
						$locales[$dir][$matches[1]] = $language;
					}
				}
				closedir($dh);
			}
		}

fbDebug::leave($locales[$dir]);

		return $locales[$dir];
	}

	/*!
		\todo support multiple locales

		\param $locales \c mixed A single locale, or an array of locales
		\return \c string
		\static
	*/
	function getNearestLocales($locales) {
fbDebug::enter();
		static $locale_map;
		static $available_locales;

		if (!$available_locales) {
			$available_locales = &fbLocale::getAvailableLocales();
		}

		// \todo get serialize working
		$locale_key = is_array($locales) ? serialize($locales) : $locales;

fbDebug::dump($locale_key, '$locale_key');

		if (!isset($locale_map[$locale_key])) {
			if (is_array($locales)) {
				foreach($locales as $locale) {
					$locale_map[$locale_key] = fbLocale::getNearestLocales($locale);
					if ($locale_map[$locale_key]) {

fbDebug::dump($locale_map[$locale_key], '$locale_map[$locale_key]');

						return $locale_map[$locale_key];
					}
				}

fbDebug::dump($locale_map[$locale_key], '$locale_map[$locale_key]');

				return $locale_map[$locale_key];
			}

			if (isset($available_locales[$locale_key])) {
				$locale_map[$locale_key] = $locale_key;

fbDebug::dump($locale_map[$locale_key], '$locale_map[$locale_key]');

				return $locale_map[$locale_key];
			} else {
				$language = substr($locale_key, 0, 2);

fbDebug::dump($language, '$language');

				foreach ($available_locales as $locale => $available_language) {

fbDebug::dump($locale, '$locale');
fbDebug::dump($available_language, '$available_language');

					if (strcasecmp($language, $available_language) == 0) {
						$locale_map[$locale_key] = $locale;

fbDebug::dump($locale_map[$locale_key], '$locale_map[$locale_key]');

						return $locale_map[$locale_key];
					}
				}

				$locale_map[$locale_key] = false;

fbDebug::dump($locale_map[$locale_key], '$locale_map[$locale_key]');

			}
		}

fbDebug::dump($locale_map[$locale_key], '$locale_map[$locale_key]');

fbDebug::leave($locale_map[$locale_key]);

		return $locale_map[$locale_key];
	}

	/*!
		decimal_point Decimal point character
		thousands_sep Thousands separator

		grouping Array containing numeric groupings

		int_curr_symbol International currency symbol (i.e. USD)
		currency_symbol Local currency symbol (i.e. $)

		mon_decimal_point Monetary decimal point character
		mon_thousands_sep Monetary thousands separator
		mon_grouping Array containing monetary groupings

		positive_sign Sign for positive values
		negative_sign Sign for negative values

		int_frac_digits International fractional digits
		frac_digits Local fractional digits

		p_cs_precedes TRUE if currency_symbol precedes a positive value, FALSE if it succeeds one
		p_sep_by_space TRUE if a space separates currency_symbol from a positive value, FALSE otherwise
		n_cs_precedes TRUE if currency_symbol precedes a negative value, FALSE if it succeeds one
		n_sep_by_space TRUE if a space separates currency_symbol from a negative value, FALSE otherwise

		p_sign_posn
			0 The sign string succeeds the quantity and currency_symbol
			3 Parentheses surround the quantity and currency_symbol
			1 The sign string immediately precedes the currency_symbol
			4 The sign string precedes the quantity and currency_symbol
			2 The sign string immediately succeeds the currency_symbol
		n_sign_posn
			0 The sign string succeeds the quantity and currency_symbol
			3 Parentheses surround the quantity and currency_symbol
			1 The sign string immediately precedes the currency_symbol
			4 The sign string precedes the quantity and currency_symbol
			2 The sign string immediately succeeds the currency_symbol

		\param $number \c float
		\param $digits \c int
		\param $locale \c string (default is current locale) locale string
		\return \c string
		\static
	*/
	function numberFormat($number, $digits = null, $locale = null) {
fbDebug::enter();
		static $localeconv_cache = array();

		$get_locale = is_null($locale);
fbDebug::dump($get_locale, '$get_locale');
		if ($get_locale) {
			$locale = fbLocale::getLocale(LC_MONETARY);
fbDebug::dump($locale, '$locale');
		}

fbDebug::dump($localeconv_cache, '$localeconv_cache');

		if (!isset($localeconv_cache[$locale])) {
fbDebug::dump($locale, '$locale');
			if (!$get_locale) {
				$rv = fbLocale::pushLocale(LC_MONETARY, $locale);
fbDebug::dump($rv, '$rv');
			}

$_locale = fbLocale::getLocale(LC_MONETARY);
fbDebug::dump($_locale, '$_locale');

			$localeconv_cache[$locale] = localeconv();

fbDebug::dump($localeconv_cache[$locale], '$localeconv_cache[$locale]');

			if (!$get_locale) {
				fbLocale::popLocale(LC_MONETARY);
			}
		}

		$lc = $localeconv_cache[$locale];

fbDebug::dump($lc, '$lc');

		if (is_null($digits)) {
			$digits = $lc['int_frac_digits'];
		}

		$rv = number_format($number, $digits, $lc['mon_decimal_point'], $lc['mon_thousands_sep']);
		$n = strpos($rv, '-');
		if ($n !== false) {
			$rv = str_replace('-', $lc['negative_sign'], $rv);
		}

fbDebug::leave($rv);

		return $rv;
	}

	/// \todo implment fbLocale::moneyFormat()
	function moneyFormat($number, $digits = null, $locale = null) {
	}
}

// get default locale
fbLocale::_init();

?>
