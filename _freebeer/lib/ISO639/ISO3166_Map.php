<?php

// $CVSHeader: _freebeer/lib/ISO639/ISO3166_Map.php,v 1.2 2004/03/07 17:51:22 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file ISO639/ISO3166_Map.php
	\brief ISO 639 (language code) to ISO 3166 (country code) map
*/

/*!
	\class fbISO639_ISO3166_Map
	\brief ISO 639 (language code) to ISO 3166 (country code) map

	\static
*/
class fbISO639_ISO3166_Map {
	/*!
		See
		http://www.w3.org/WAI/ER/IG/ert/iso639.htm
		http://www.iso.ch/iso/en/prods-services/iso3166ma/02iso-3166-code-lists/list-en1-semic.txt

		\return \c array
		\static
	*/
	function &getLanguageIDToCountryIDHash() {
		static $LANGUAGE_ID_TO_COUNTRY_ID_HASH = array(
			'AA'	=> '',			//					=> 'Afar'
			'AB'	=> '',			//					=> 'Abkhazian'
			'AF'	=> 'ZA',		// 'Afrikaans'		=> 'South Africa'
			'AM'	=> '',			//					=> 'Amharic'
			'AR'	=> '',			//					=> 'Arabic',	// What is the country with the largest 
																		// Arabic speaking population?
																		// horde uses SY: Syrian Arab Republic
			'AS'	=> '',			//					=> 'Assamese'
			'AY'	=> '',			//					=> 'Aymara'
			'AZ'	=> 'AZ',		// 'Azerbaijani'	=> 'Azerbaijan'
			'BA'	=> '',			//					=> 'Bashkir'
			'BE'	=> 'BY',		// 'Byelorussian'	=> 'Belarus'
			'BG'	=> 'BG',		// 'Bulgarian'		=> 'Bulgaria'
			'BH'	=> 'IN',		// 'Bihari'			=> 'India'
			'BI'	=> '',			//					=> 'Bislama'
			'BN'	=> 'IN',		// 'Bengali'		=> 'India'
			'BO'	=> '',			//					=> 'Tibetan',	// China
			'BR'	=> '',			//					=> 'Breton'
			'CA'	=> 'ES',		// 'Catalan'		=> 'Spain'
			'CO'	=> '',			//					=> 'Corsican'
			'CS'	=> 'CZ',		// 'Czech'			=> 'Czech Republic'
			'CY'	=> '',			//					=> 'Welsh'
			'DA'	=> 'DK',		// 'Danish'			=> 'Denmark'
			'DE'	=> 'DE',		// 'German'			=> 'Germany'
			'DZ'	=> 'BT',		// 'Bhutani'		=> 'Bhutan'
			'EL'	=> 'GR',		// 'Greek'			=> 'Greece'
			'EN'	=> 'US',		// 'English'		=> 'United States'
			'EO'	=> '',			//					=> 'Esperanto'
			'ES'	=> 'ES',		// 'Spanish'		=> 'Spain'
			'ET'	=> 'EE',		// 'Estonian'		=> 'Estonia'
			'EU'	=> '',			//					=> 'Basque'
			'FA'	=> '',			//					=> 'Persian'
			'FI'	=> 'FI',		// 'Finnish'		=> 'Finland'
			'FJ'	=> 'FJ',		// 'Fiji'			=> 'Fiji'
			'FO'	=> 'FO',		// 'Faeroese'		=> 'Faroe Islands'
			'FR'	=> 'FR',		// 'French'			=> 'France'
			'FY'	=> 'MK',		// 'Frisian'		=> 'Macedonia, the Former Yugoslav Republic of'
			'GA'	=> 'IE',		// 'Irish'			=> 'Ireland'
			'GD'	=> '',			//					=> 'Gaelic',	// Ireland or Scotland
			'GL'	=> '',			//					=> 'Galician'
			'GN'	=> 'PY',		// 'Guarani'		=> 'Paraguay'
			'GU'	=> 'IN',		// 'Gujarati'		=> 'India'
			'HA'	=> '',			//					=> 'Hausa'
			'HI'	=> 'IN',		// 'Hindi'			=> 'India'
			'HR'	=> 'HR',		// 'Croatian'		=> 'Croatia'
			'HU'	=> 'HU',		// 'Hungarian'		=> 'Hungary'
			'HY'	=> 'AM',		// 'Armenian'		=> 'Armenia'
			'IA'	=> '',			//					=> 'Interlingua'
			'IE'	=> '',			//					=> 'Interlingue'
			'IK'	=> '',			//					=> 'Inupiak'
			'IN'	=> 'ID',		// 'Indonesian'		=> 'Indonesia'
			'IS'	=> 'IS',		// 'Icelandic'		=> 'Iceland'
			'IT'	=> 'IT',		// 'Italian'		=> 'Italy'
			'IW'	=> 'IL',		// 'Hebrew'			=> 'Israel'
			'JA'	=> 'JP',		// 'Japanese'		=> 'Japan'
			'JI'	=> 'IL',		// 'Yiddish'		=> 'Israel'
			'JW'	=> 'ID',		// 'Javanese'		=> 'Indonesia'
			'KA'	=> 'GE',		// 'Georgian'		=> 'Georgia'
			'KK'	=> 'KZ',		// 'Kazakh'			=> 'Kazakhstan'
			'KL'	=> 'GL',		// 'Greenlandic'	=> 'Greenland'
			'KM'	=> 'KH',		// 'Cambodian'		=> 'Cambodia'
			'KN'	=> 'IN',		// 'Kannada'		=> 'India'
			'KO'	=> 'KR',		// 'Korean'			=> 'Korea, Republic of'
			'KS'	=> 'IN',		// 'Kashmiri'		=> 'India'
			'KU'	=> '',			//					=> 'Kurdish'
			'KY'	=> 'KG',		// 'Kirghiz'		=> 'Kyrgyzstan'
			'LA'	=> '',			//					=> 'Latin'
			'LN'	=> '',			//					=> 'Lingala'
			'LO'	=> '',			//					=> 'Laothian'
			'LT'	=> 'LT',		// 'Lithuanian'		=> 'Lithuania'
			'LV'	=> 'LV',		// 'Latvian'		=> 'Latvia'
			'MG'	=> 'MG',		// 'Malagasy'		=> 'Madagascar'
			'MI'	=> 'NZ',		// 'Maori'			=> 'New Zealand'
			'MK'	=> 'MK',		// 'Macedonian'		=> 'Macedonia'
			'ML'	=> 'IN',		// 'Malayalam'		=> 'India'
			'MN'	=> 'MN',		// 'Mongolian'		=> 'Mongolia'
			'MO'	=> 'MD',		// 'Moldavian'		=> 'Moldova, Republic of'
			'MR'	=> 'IN',		// 'Marathi'		=> 'India'
			'MS'	=> 'MY',		// 'Malay'			=> 'Malaysia'
			'MT'	=> 'MT',		// 'Maltese'		=> 'Malta'
			'MY'	=> 'MM',		// 'Burmese'		=> 'Myanmar'
			'NA'	=> 'NR',		// 'Nauru'			=> 'Nauru'
			'NE'	=> 'NP',		// 'Nepali'			=> 'Nepal'
			'NL'	=> 'NL',		// 'Dutch'			=> 'Netherlands'
			'NN'	=> 'NO',		// 'Norwegian (Nynorsk)'	=> 'Norway'
			'NO'	=> 'NO',		// 'Norwegian'		=> 'Norway'
			'OC'	=> '',			//					=> 'Occitan'
			'OM'	=> '',			//					=> 'Oromo'
			'OR'	=> 'IN',		// 'Oriya'			=> 'India'
			'PA'	=> 'PK',		// 'Punjabi'		=> 'Pakistan'
			'PL'	=> 'PL',		// 'Polish'			=> 'Poland'
			'PS'	=> 'AF',		// 'Pashto'			=> 'Afghanistan'
			'PT'	=> 'PT',		// 'Portuguese'		=> 'Portugual'
			'QU'	=> '',			//					=> 'Quechua'
			'RM'	=> '',			//					=> 'Rhaeto-Romance'
			'RN'	=> '',			//					=> 'Kirundi'
			'RO'	=> 'RO',		// 'Romanian'		=> 'Romania'
			'RU'	=> 'RU',		// 'Russian'		=> 'Russian Federation'
			'RW'	=> '',			//					=> 'Kinyarwanda'
			'SA'	=> 'IN',		// 'Sanskrit'		=> 'India'
			'SD'	=> '',			//					=> 'Sindhi',	// 'Sindhi'			=> 'Pakistan' ????
			'SG'	=> '',			//					=> 'Sangro'
			'SH'	=> '',			//					=> 'Serbo-Croatian'
			'SI'	=> 'SN',		// 'Singhalese'		=> 'Senegal'
			'SK'	=> 'SK',		// 'Slovak'			=> 'Slovakia'
			'SL'	=> 'SI',		// 'Slovenian'		=> 'Slovenia'
			'SM'	=> 'WS',		// 'Samoan'			=> 'Samoa'
			'SN'	=> '',			//					=> 'Shona'
			'SO'	=> 'SO',		// 'Somali'			=> 'Somalia'
			'SQ'	=> 'AL',		// 'Albanian'		=> 'Albania'
			'SR'	=> 'CS',		// 'Serbian' 		=> 'Serbia and Montenegro'
			'SS'	=> '',			//					=> 'Siswati'
			'ST'	=> '',			//					=> 'Sesotho'
			'SU'	=> 'SD',		// 'Sudanese'		=> 'Sudan'
			'SV'	=> 'SE',		// 'Swedish'		=> 'Sweden'
			'SW'	=> 'SZ',		// 'Swahili'		=> 'Swaziland'
			'TA'	=> 'IN',		// 'Tamil'			=> 'India'
			'TE'	=> 'IN',		// 'Tegulu'			=> 'India'
			'TG'	=> 'TJ',		// 'Tajik'			=> 'Tajikistan'
			'TH'	=> 'TH',		// 'Thai'			=> 'Thailand'
			'TI'	=> '',			//					=> 'Tigrinya'
			'TK'	=> 'TM',		// 'Turkmen'		=> 'Turkmenistan'
			'TL'	=> 'PH',		// 'Tagalog'		=> 'Philippines'
			'TN'	=> '',			//					=> 'Setswana'
			'TO'	=> 'TO',		// 'Tonga'			=> 'Tonga'
			'TR'	=> 'TR',		// 'Turkish'		=> 'Turkey'
			'TS'	=> '',			//					=> 'Tsonga'
			'TT'	=> '',			//					=> 'Tatar'
			'TW'	=> '',			//					=> 'Twi'
			'UK'	=> 'UA',		// 'Ukrainian'		=> 'Ukraine'
			'UR'	=> 'IN',		// 'Urdu'			=> 'India'
			'UZ'	=> 'UZ',		// 'Uzbek'			=> 'Uzbekistan'
			'VI'	=> 'VN',		// 'Vietnamese'		=> 'Viet Nam'
			'VO'	=> '',			//					=> 'Volapuk'
			'WO'	=> '',			//					=> 'Wolof'
			'XH'	=> '',			//					=> 'Xhosa'
			'YO'	=> '',			//					=> 'Yoruba'
			'ZH'	=> 'CN',		// 'Chinese'		=> 'China'
			'ZU'	=> '',			//					=> 'Zulu'

			// Non-ISO639 additions
			'NB'	=> 'NO',		// 'Norwegian'		=> 'Norway'
		);

//		// make sure no dups snuck in
		assert('count($LANGUAGE_ID_TO_COUNTRY_ID_HASH) == 138');

		return $LANGUAGE_ID_TO_COUNTRY_ID_HASH;
	}

	/*!
		\static
	*/
	function getCountryID($language_id) {
		$LANGUAGE_ID_TO_COUNTRY_ID_HASH = &fbISO639_ISO3166_Map::getLanguageIDToCountryIDHash();

		$language_id = strtoupper($language_id);
		return isset($LANGUAGE_ID_TO_COUNTRY_ID_HASH[$language_id])
			? $LANGUAGE_ID_TO_COUNTRY_ID_HASH[$language_id]
			: false;
	}

}

?>
