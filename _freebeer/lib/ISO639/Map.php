<?php

// $CVSHeader: _freebeer/lib/ISO639/Map.php,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

/*!
	\file ISO639/Map.php
	\brief ISO639 (language code) two letter code to three letter code map.
*/

/*!
	\class fbISO639_Map
	\brief ISO639 (language code) two letter code to three letter code map.
	
	\static
*/
class fbISO639_Map {
	/*!
		\see http://www.w3.org/WAI/ER/IG/ert/iso639.htm

		\return \c array
		\static
	*/
	function &getID3ToID2Hash() {
		static $ID3_TO_ID2_HASH = array(
			'AAR'	=> 'AA',	// Afar
			'ABK'	=> 'AB',	// Abkhazian
			'AFR'	=> 'AF',	// Afrikaans
			'AMH'	=> 'AM',	// Amharic
			'ARA'	=> 'AR',	// Arabic
			'ASM'	=> 'AS',	// Assamese
			'AYM'	=> 'AY',	// Aymara
			'AZE'	=> 'AZ',	// Azerbaijani
			'BAK'	=> 'BA',	// Bashkir
			'BEL'	=> 'BE',	// Byelorussian
			'BEN'	=> 'BN',	// Bengali
			'BIH'	=> 'BH',	// Bihari
			'BIS'	=> 'BI',	// Bislama
//			'BRE'	=> 'BE',	// Breton		// BE is Byelorussian
			'BUL'	=> 'BG',	// Bulgarian
			'CAT'	=> 'CA',	// Catalan
			'COS'	=> 'CO',	// Corsican
			'DAN'	=> 'DA',	// Danish
			'DZO'	=> 'DZ',	// Dzongkha
			'ENG'	=> 'EN',	// English
			'EPO'	=> 'EO',	// Esperanto
			'EST'	=> 'ET',	// Estonian
			'FAO'	=> 'FO',	// Faroese
			'FIJ'	=> 'FJ',	// Fijian
			'FIN'	=> 'FI',	// Finnish
			'FRY'	=> 'FY',	// Frisian
			'GLG'	=> 'GL',	// Gallegan
			'GRN'	=> 'GN',	// Guarani
			'GUJ'	=> 'GU',	// Gujarati
			'HAU'	=> 'HA',	// Hausa
			'HEB'	=> 'HE',	// Hebrew
			'HIN'	=> 'HI',	// Hindi
			'HUN'	=> 'HU',	// Hungarian
			'IKU'	=> 'IU',	// Inuktitut
			'INA'	=> 'IA',	// Interlingua (International Auxiliary language Association)
			'IND'	=> 'ID',	// Indonesian
			'IPK'	=> 'IK',	// Inupiak
			'ITA'	=> 'IT',	// Italian
			'JPN'	=> 'JA',	// Japanese
			'KAL'	=> 'KL',	// Greenlandic
			'KAN'	=> 'KN',	// Kannada
			'KAS'	=> 'KS',	// Kashmiri
			'KAZ'	=> 'KK',	// Kazakh
			'KHM'	=> 'KM',	// Khmer
			'KIN'	=> 'RW',	// Kinyarwanda
			'KIR'	=> 'KY',	// Kirghiz
			'KOR'	=> 'KO',	// Korean
			'KUR'	=> 'KU',	// Kurdish
			'LAO'	=> 'LO',	// Lao
			'LAT'	=> 'LA',	// Latin
			'LAV'	=> 'LV',	// Latvian
			'LIN'	=> 'LN',	// Lingala
			'LIT'	=> 'LT',	// Lithuanian
			'MAR'	=> 'MR',	// Marathi
			'MLG'	=> 'MG',	// Malagasy
			'MLT'	=> 'ML',	// Maltese
			'MOL'	=> 'MO',	// Moldavian
			'MON'	=> 'MN',	// Mongolian
			'NAU'	=> 'NA',	// Nauru
			'NEP'	=> 'NE',	// Nepali
			'NOR'	=> 'NO',	// Norwegian
			'OCI'	=> 'OC',	// Langue d\'Oc (post 1500)
			'ORI'	=> 'OR',	// Oriya
			'ORM'	=> 'OM',	// Oromo
			'PAN'	=> 'PA',	// Panjabi
			'POL'	=> 'PL',	// Polish
			'POR'	=> 'PT',	// Portuguese
			'PUS'	=> 'PS',	// Pushto
			'QUE'	=> 'QU',	// Quechua
			'ROH'	=> 'RM',	// Rhaeto-Romance
			'RUN'	=> 'RN',	// Rundi
			'RUS'	=> 'RU',	// Russian
			'SAG'	=> 'SG',	// Sango
			'SAN'	=> 'SA',	// Sanskrit
			'SCR'	=> 'SH',	// Serbo-Croatian
			'SIN'	=> 'SI',	// Singhalese
			'SLV'	=> 'SL',	// Slovenian
			'SMO'	=> 'SM',	// Samoan
			'SNA'	=> 'SN',	// Shona
			'SND'	=> 'SD',	// Sindhi
			'SOM'	=> 'SO',	// Somali
			'SOT'	=> 'ST',	// Sotho, Southern
			'SSW'	=> 'SS',	// Siswant
			'SUN'	=> 'SU',	// Sudanese
			'SWA'	=> 'SW',	// Swahili
			'TAM'	=> 'TA',	// Tamil
			'TAT'	=> 'TT',	// Tatar
			'TEL'	=> 'TE',	// Telugu
			'TGK'	=> 'TG',	// Tajik
			'TGL'	=> 'TL',	// Tagalog
			'THA'	=> 'TH',	// Thai
			'TIR'	=> 'TI',	// Tigrinya
			'TOG'	=> 'TO',	// Tonga (Nyasa)
			'TSN'	=> 'TN',	// Tswana
			'TSO'	=> 'TS',	// Tsonga
			'TUK'	=> 'TK',	// Turkmen
			'TUR'	=> 'TR',	// Turkish
			'TWI'	=> 'TW',	// Twi
			'UIG'	=> 'UG',	// Uighur
			'UKR'	=> 'UK',	// Ukrainian
			'URD'	=> 'UR',	// Urdu
			'UZB'	=> 'UZ',	// Uzbek
			'VIE'	=> 'VI',	// Vietnamese
			'VOL'	=> 'VO',	// Volap?k
			'WOL'	=> 'WO',	// Wolof
			'XHO'	=> 'XH',	// Xhosa
			'YID'	=> 'YI',	// Yiddish
			'YOR'	=> 'YO',	// Yoruba
			'ZHA'	=> 'ZA',	// Zhuang
			'ZUL'	=> 'ZU',	// Zulu
			'CES'	=> 'CS',	// Czech (also cze)
			'BAQ'	=> 'EU',	// Basque (also eus)
			'FRA'	=> 'FR',	// French (also fre)
			'GAE'	=> 'GD',	// Gaelic (Scots) (also gdh)
			'DEU'	=> 'DE',	// German (also ger)
			'ELL'	=> 'EL',	// Greek, Modern (1453-) (also gre)
			'ARM'	=> 'HY',	// Armenian (also hye)
			'GAI'	=> 'GA',	// Irish (also iri)
			'ICE'	=> 'IS',	// Icelandic (also isl)
			'GEO'	=> 'KA',	// Georgian (also kat)
			'MAC'	=> 'MK',	// Macedonian (also mak)
			'MAO'	=> 'MI',	// Maori (also mri)
			'MAY'	=> 'MS',	// Malay (also msa)
			'BUR'	=> 'MY',	// Burmese (also mya)
			'DUT'	=> 'NL',	// Dutch (also nla)
			'FAS'	=> 'FA',	// Persian (also per)
			'RON'	=> 'RO',	// Romanian (also rum)
			'SLK'	=> 'SK',	// Slovak (also slo)
			'ESL'	=> 'ES',	// Spanish (also spa)
			'ALB'	=> 'SQ',	// Albanian (also sqi)
			'SVE'	=> 'SV',	// Swedish (also swe)
			'BOD'	=> 'BO',	// Tibetan (also tib)
			'CYM'	=> 'CY',	// Welsh (also wel)
			'CHI'	=> 'ZH',	// Chinese (also zho)
			'JAV'	=> 'JV',	// Javanese (also jaw/jw)
			'CZE'	=> 'CS',	// Czech (also ces)
			'EUS'	=> 'EU',	// Basque (also baq)
			'FRE'	=> 'FR',	// French (also fra)
			'GDH'	=> 'GD',	// Gaelic (Scots) (also gae)
			'GER'	=> 'DE',	// German (also deu)
			'GRE'	=> 'EL',	// Greek, Modern (1453-) (also ell)
			'HYE'	=> 'HY',	// Armenian (also arm)
			'IRI'	=> 'GA',	// Irish (also gai)
			'ISL'	=> 'IS',	// Icelandic (also ice)
			'KAT'	=> 'KA',	// Georgian (also geo)
			'MAK'	=> 'MK',	// Macedonian (also mac)
			'MRI'	=> 'MI',	// Maori (also mao)
			'MSA'	=> 'MS',	// Malay (also may)
			'MYA'	=> 'MY',	// Burmese (also bur)
			'NLA'	=> 'NL',	// Dutch (also dut)
			'PER'	=> 'FA',	// Persian (also fas)
			'RUM'	=> 'RO',	// Romanian (also ron)
			'SLO'	=> 'SK',	// Slovak (also slk)
			'SPA'	=> 'ES',	// Spanish (also esl)
			'SQI'	=> 'SQ',	// Albanian (also alb)
			'SWE'	=> 'SV',	// Swedish (also sve)
			'TIB'	=> 'BO',	// Tibetan (also bod)
			'WEL'	=> 'CY',	// Welsh (also cym)
			'ZHO'	=> 'ZH',	// Chinese (also chi)
			'JAW'	=> 'JV',	// Javanese (also jav/jw)

			// Non-ISO 639 additions
			'NON'	=> 'NN',	// Norse, Old => Norwegian (Nynorsk) (also no/nb)
			'NNO'	=> 'NN',	// Norwegian (Nynorsk)

		//	'JAV'	=> 'JW',	// Javanese (also jaw/jv)
		//	'JAW'	=> 'JW',	// Javanese (also jav/jv)
		);

		// make sure no dups snuck in
		assert('count($ID3_TO_ID2_HASH) == (159 + 2)');

		return $ID3_TO_ID2_HASH;
	}

	/*!
		\return \c array
		\static
	*/
	function &getID2ToID3Hash() {
		static $ID2_TO_ID3_HASH = null;

		if (is_null($ID2_TO_ID3_HASH)) {
			$ID3_TO_ID2_HASH = &fbISO639_Map::getID3ToID2Hash();
			$ID2_TO_ID3_HASH = array_flip($ID3_TO_ID2_HASH);

			$ID2_TO_ID3_HASH['CS']	= 'CES'; // Czech (also cze)
			$ID2_TO_ID3_HASH['EU']	= 'BAQ'; // Basque (also eus)
			$ID2_TO_ID3_HASH['FR']	= 'FRA'; // French (also fre)
			$ID2_TO_ID3_HASH['GD']	= 'GAE'; // Gaelic (Scots) (also gdh)
			$ID2_TO_ID3_HASH['DE']	= 'DEU'; // German (also ger)
			$ID2_TO_ID3_HASH['EL']	= 'ELL'; // Greek, Modern (1453-) (also gre)
			$ID2_TO_ID3_HASH['HY']	= 'ARM'; // Armenian (also hye)
			$ID2_TO_ID3_HASH['GA']	= 'GAI'; // Irish (also iri)
			$ID2_TO_ID3_HASH['IS']	= 'ICE'; // Icelandic (also isl)
			$ID2_TO_ID3_HASH['KA']	= 'GEO'; // Georgian (also kat)
			$ID2_TO_ID3_HASH['MK']	= 'MAC'; // Macedonian (also mak)
			$ID2_TO_ID3_HASH['MI']	= 'MAO'; // Maori (also mri)
			$ID2_TO_ID3_HASH['MS']	= 'MAY'; // Malay (also msa)
			$ID2_TO_ID3_HASH['MY']	= 'BUR'; // Burmese (also mya)
			$ID2_TO_ID3_HASH['NL']	= 'DUT'; // Dutch (also nla)
			$ID2_TO_ID3_HASH['FA']	= 'FAS'; // Persian (also per)
			$ID2_TO_ID3_HASH['RO']	= 'RON'; // Romanian (also rum)
			$ID2_TO_ID3_HASH['SK']	= 'SLK'; // Slovak (also slo)
			$ID2_TO_ID3_HASH['ES']	= 'ESL'; // Spanish (also spa)
			$ID2_TO_ID3_HASH['SQ']	= 'ALB'; // Albanian (also sqi)
			$ID2_TO_ID3_HASH['SV']	= 'SVE'; // Swedish (also swe)
			$ID2_TO_ID3_HASH['BO']	= 'BOD'; // Tibetan (also tib)
			$ID2_TO_ID3_HASH['CY']	= 'CYM'; // Welsh (also wel)
			$ID2_TO_ID3_HASH['ZH']	= 'CHI'; // Chinese (also zho)
			$ID2_TO_ID3_HASH['JV']	= 'JAV'; // Javanese (also jaw/jw)

			// Non-ISO 639 additions
			$ID2_TO_ID3_HASH['NB']	= 'NOR'; // Norwegian (also no/nn)
			$ID2_TO_ID3_HASH['NN']	= 'NOR'; // Norwegian (Nynorsk) (also no/nb)
//			$ID2_TO_ID3_HASH['NN']	= 'NON'; // Norwegian (Nynorsk) (also no/nb)
		}

		return $ID2_TO_ID3_HASH;
	}

	/*!
		\static
	*/
	function getID2($id3) {
		$ID3_TO_ID2_HASH = &fbISO639_Map::getID3ToID2Hash();
		$id3 = strtoupper($id3);
		return isset($ID3_TO_ID2_HASH[$id3]) ? $ID3_TO_ID2_HASH[$id3] : false;
	}

	/*!
		\static
	*/
	function getID3($id2) {
		$ID2_TO_ID3_HASH = &fbISO639_Map::getID2ToID3Hash();
		$id2 = strtoupper($id2);
		return isset($ID2_TO_ID3_HASH[$id2]) ? $ID2_TO_ID3_HASH[$id2] : false;
	}

}

?>
