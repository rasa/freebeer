<?php

// $CVSHeader: _freebeer/lib/ISO639.php,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

/*!
	\file ISO639.php
	\brief ISO 639 language code related functions
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

if (phpversion() <= '4.2.0') {
	include_once FREEBEER_BASE . '/lib/Backport.php'; // array_change_key_case
}

/*!
	\brief ISO 639 language code related functions
	\class fbISO639
	
	\static
*/
class fbISO639 {
	/*!
		\return \c array

		\static
		\see http://www.w3.org/WAI/ER/IG/ert/iso639.htm
		\see http://www.iso.ch/iso/en/prods-services/iso3166ma/02iso-3166-code-lists/list-en1-semic.txt
	*/
	function &getIDToNameHash() {
		static $ID_TO_NAME_HASH = array(
			'AA'	=> 'Afar',
			'AB'	=> 'Abkhazian',
			'AF'	=> 'Afrikaans',
			'AM'	=> 'Amharic',
			'AR'	=> 'Arabic',
			'AS'	=> 'Assamese',
			'AY'	=> 'Aymara',
			'AZ'	=> 'Azerbaijani',
			'BA'	=> 'Bashkir',
			'BE'	=> 'Byelorussian',
			'BG'	=> 'Bulgarian',
			'BH'	=> 'Bihari',
			'BI'	=> 'Bislama',
			'BN'	=> 'Bengali',
			'BO'	=> 'Tibetan',
			'BR'	=> 'Breton',
			'CA'	=> 'Catalan',
			'CO'	=> 'Corsican',
			'CS'	=> 'Czech',
			'CY'	=> 'Welsh',
			'DA'	=> 'Danish',
			'DE'	=> 'German',
			'DZ'	=> 'Bhutani',
			'EL'	=> 'Greek',
			'EN'	=> 'English',
			'EO'	=> 'Esperanto',
			'ES'	=> 'Spanish',
			'ET'	=> 'Estonian',
			'EU'	=> 'Basque',
			'FA'	=> 'Persian',
			'FI'	=> 'Finnish',
			'FJ'	=> 'Fiji',
			'FO'	=> 'Faeroese',
			'FR'	=> 'French',
			'FY'	=> 'Frisian',
			'GA'	=> 'Irish',
			'GD'	=> 'Gaelic',
			'GL'	=> 'Galician',
			'GN'	=> 'Guarani',
			'GU'	=> 'Gujarati',
			'HA'	=> 'Hausa',
			'HI'	=> 'Hindi',
			'HR'	=> 'Croatian',
			'HU'	=> 'Hungarian',
			'HY'	=> 'Armenian',
			'IA'	=> 'Interlingua',
			'IE'	=> 'Interlingue',
			'IK'	=> 'Inupiak',
			'IN'	=> 'Indonesian',
			'IS'	=> 'Icelandic',
			'IT'	=> 'Italian',
			'IW'	=> 'Hebrew',
			'JA'	=> 'Japanese',
			'JI'	=> 'Yiddish',
			'JW'	=> 'Javanese',
			'KA'	=> 'Georgian',
			'KK'	=> 'Kazakh',
			'KL'	=> 'Greenlandic',
			'KM'	=> 'Cambodian',
			'KN'	=> 'Kannada',
			'KO'	=> 'Korean',
			'KS'	=> 'Kashmiri',
			'KU'	=> 'Kurdish',
			'KY'	=> 'Kirghiz',
			'LA'	=> 'Latin',
			'LN'	=> 'Lingala',
			'LO'	=> 'Laothian',
			'LT'	=> 'Lithuanian',
			'LV'	=> 'Latvian',
			'MG'	=> 'Malagasy',
			'MI'	=> 'Maori',
			'MK'	=> 'Macedonian',
			'ML'	=> 'Malayalam',
			'MN'	=> 'Mongolian',
			'MO'	=> 'Moldavian',
			'MR'	=> 'Marathi',
			'MS'	=> 'Malay',
			'MT'	=> 'Maltese',
			'MY'	=> 'Burmese',
			'NA'	=> 'Nauru',
			'NE'	=> 'Nepali',
			'NL'	=> 'Dutch',
			'NN'	=> 'Norwegian (Nynorsk)',
			'NO'	=> 'Norwegian',
			'OC'	=> 'Occitan',
			'OM'	=> 'Oromo',
			'OR'	=> 'Oriya',
			'PA'	=> 'Punjabi',
			'PL'	=> 'Polish',
			'PS'	=> 'Pashto',
			'PT'	=> 'Portuguese',
			'QU'	=> 'Quechua',
			'RM'	=> 'Rhaeto-Romance',
			'RN'	=> 'Kirundi',
			'RO'	=> 'Romanian',
			'RU'	=> 'Russian',
			'RW'	=> 'Kinyarwanda',
			'SA'	=> 'Sanskrit',
			'SD'	=> 'Sindhi',
			'SG'	=> 'Sangro',
			'SH'	=> 'Serbo-Croatian',
			'SI'	=> 'Singhalese',
			'SK'	=> 'Slovak',
			'SL'	=> 'Slovenian',
			'SM'	=> 'Samoan',
			'SN'	=> 'Shona',
			'SO'	=> 'Somali',
			'SQ'	=> 'Albanian',
			'SR'	=> 'Serbian',
			'SS'	=> 'Siswati',
			'ST'	=> 'Sesotho',
			'SU'	=> 'Sudanese',
			'SV'	=> 'Swedish',
			'SW'	=> 'Swahili',
			'TA'	=> 'Tamil',
			'TE'	=> 'Tegulu',
			'TG'	=> 'Tajik',
			'TH'	=> 'Thai',
			'TI'	=> 'Tigrinya',
			'TK'	=> 'Turkmen',
			'TL'	=> 'Tagalog',
			'TN'	=> 'Setswana',
			'TO'	=> 'Tonga',
			'TR'	=> 'Turkish',
			'TS'	=> 'Tsonga',
			'TT'	=> 'Tatar',
			'TW'	=> 'Twi',
			'UK'	=> 'Ukrainian',
			'UR'	=> 'Urdu',
			'UZ'	=> 'Uzbek',
			'VI'	=> 'Vietnamese',
			'VO'	=> 'Volapuk',
			'WO'	=> 'Wolof',
			'XH'	=> 'Xhosa',
			'YO'	=> 'Yoruba',
			'ZH'	=> 'Chinese',
			'ZU'	=> 'Zulu',

			// Non-ISO639 additions (from Horde)
			'NB'	=> 'Norwegian (Bokm?l)',
		);

		// make sure no dups snuck in
		assert('count($ID_TO_NAME_HASH) == 138');

		return $ID_TO_NAME_HASH;
	}

	/*!
		\return \c array
		\static
	*/
	function &getNameToIDHash() {
		static $NAME_TO_ID_HASH = null;

		if (is_null($NAME_TO_ID_HASH)) {
			$ID_TO_NAME_HASH = &fbISO639::getIDToNameHash();
			$NAME_TO_ID_HASH = array_flip($ID_TO_NAME_HASH);

			// Alternative names
			$NAME_TO_ID_HASH['Bangla']				= 'BN';
			$NAME_TO_ID_HASH['American']			= 'EN';
			$NAME_TO_ID_HASH['Scots Gaelic']		= 'GD';
			$NAME_TO_ID_HASH['Lettish']				= 'LV';
			$NAME_TO_ID_HASH['Pushto']				= 'PS';
			$NAME_TO_ID_HASH['Afan']				= 'OM';

			// Windows names
			$NAME_TO_ID_HASH['Azeri (Latin)']		= 'AZ';	// 'Azerbaijani'
			$NAME_TO_ID_HASH['Belarusian']			= 'BE';	// 'Byelorussian'
			$NAME_TO_ID_HASH['FYRO Macedonian']		= 'MK';	// 'Macedonian'
			$NAME_TO_ID_HASH['Kyrgyz']				= 'KY';	// 'Kirghiz'
			$NAME_TO_ID_HASH['Serbian (Latin)']		= 'SR';	// 'Serbian'
			$NAME_TO_ID_HASH['Uzbek (Latin)']		= 'UZ';	// 'Uzbek'
			$NAME_TO_ID_HASH['Norwegian-Nynorsk']	= 'NN';	// 'Norwegian (Nynorsk)'
		}

		return $NAME_TO_ID_HASH;
	}

	/*!
		\return \c array
		\static
	*/
	function &getIDToLocalizedNameHash() {
		static $ID_TO_LOCALIZED_NAME_HASH = null;
		
		if (is_null($ID_TO_LOCALIZED_NAME_HASH)) {
			$ID_TO_NAME_HASH = &fbISO639::getIDToNameHash();

			/*
				$a['NB']['NO']	= 'Norsk bokm&aring;l';
				$a['NN']['NO']	= 'Norsk nynorsk';
				$a['PT']['BR']	= 'Portugu&ecirc;s Brasileiro';
				$a['SK']['SK']	= 'Slovak (Sloven&#x010d;ina)';
				$a['SL']['SI']	= 'Slovenian (Sloven&#x0161;&#x010d;ina)';
				$a['ZH']['CN']	= 'Chinese (Simplified)  (&#x7b80;&#x4f53;&#x4e2d;&#x6587;)';
				$a['ZH']['TW']	= 'Chinese (Traditional) (&#x6b63;&#x9ad4;&#x4e2d;&#x6587;)';
			*/

			$a = array(
				'AR'	=> '&#x0627;&#x0644;&#x0639;&#x0631;&#x0628;&#x064a;&#x0629;',
				'BG'	=> '&#x0411;&#x044a;&#x043b;&#x0433;&#x0430;&#x0440;&#x0441;&#x043a;&#x0438;',
				'CA'	=> 'Catal&agrave;',
				'ZH'	=> '&#x7b80;&#x4f53;&#x4e2d;&#x6587;',
				'CS'	=> '&#x010c;esky',
				'DA'	=> 'Dansk',
				'DE'	=> 'Deutsch',
//				'EN'	=> 'English',
				'ES'	=> 'Espa&ntilde;ol',
				'ET'	=> 'Eesti',
				'FR'	=> 'Fran&ccedil;ais',
				'EL'	=> '&#x0395;&#x03bb;&#x03bb;&#x03b7;&#x03bd;&#x03b9;&#x03ba;&#x03ac;',
				'IS'	=> '&Iacute;slenska',
				'IT'	=> 'Italiano',
				'JA'	=> '&#x65e5;&#x672c;&#x8a9e;',
				'KO'	=> '&#xd55c;&#xad6d;&#xc5b4;',
				'LV'	=> 'Latvie&#x0161;u',
				'LT'	=> 'Lietuvi&#x0173;',
				'MK'	=> '&#x041c;&#x0430;&#x043a;&#x0435;&#x0434;&#x043e;&#x043d;&#x0441;&#x043a;&#x0438;',
				'HU'	=> 'Magyar',
//				'NL'	=> 'Nederlands',
				'NB'	=> 'Norsk bokm&aring;l',
				'NO'	=> 'Norsk',
				'NN'	=> 'Norsk nynorsk',
				'PL'	=> 'Polski',
				'PT'	=> 'Portugu&ecirc;s',
				'RO'	=> 'Romani',
				'RU'	=> '&#x0420;&#x0443;&#x0441;&#x0441;&#x043a;&#x0438;&#x0439;',
//				'SK'	=> 'Slovak',
//				'SL'	=> 'Slovenian',
				'FI'	=> 'Suomi',
				'SV'	=> 'Svenska',
				'TH'	=> '&#x0e44;&#x0e17;&#x0e22;',
				'TR'	=> 'T&uuml;rk&ccedil;e',
				'UK'	=> '&#x0423;&#x043a;&#x0440;&#x0430;&#x0457;&#x043d;&#x0441;&#x044c;&#x043a;&#x0430;',
			);

			$ID_TO_LOCALIZED_NAME_HASH = array_merge($ID_TO_NAME_HASH, $a);
		}

		// make sure no dups snuck in
//		assert('count($ID_TO_NAME_HASH) == 138');

		return $ID_TO_LOCALIZED_NAME_HASH;
	}

	/*!
		\param $name \c string
		\return \c string Language ID if found, otherwise \c false
		
		\static
	*/
	function getLanguageID($name) {
		static $NAME_TO_ID_HASH_UC = null;

		if (is_null($NAME_TO_ID_HASH_UC)) {
			$NAME_TO_ID_HASH = &fbISO639::getNameToIDHash();
			$NAME_TO_ID_HASH_UC = array_change_key_case($NAME_TO_ID_HASH, CASE_UPPER);
		}

		$name = strtoupper($name);
		return isset($NAME_TO_ID_HASH_UC[$name]) ? $NAME_TO_ID_HASH_UC[$name] : false;
	}

	/*!
		\param $id \c string
		\return \c string Language name if found, otherwise \c false
		
		\static
	*/
	function getLanguageName($id) {
		$ID_TO_NAME_HASH = &fbISO639::getIDToNameHash();
		$id = strtoupper($id);
		return isset($ID_TO_NAME_HASH[$id]) ? $ID_TO_NAME_HASH[$id] : false;
	}

	/*!
		\param $id \c string
		\return \c string Localized version of language name if found, otherwise \c false
		
		\static
	*/
	function getLocalizedLanguageName($id) {
		$ID_TO_LOCALIZED_NAME_HASH = &fbISO639::getIDToLocalizedNameHash();
		$id = strtoupper($id);
		return isset($ID_TO_LOCALIZED_NAME_HASH[$id]) ? $ID_TO_LOCALIZED_NAME_HASH[$id] : false;
	}

}

?>
