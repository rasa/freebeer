<?php

// $CVSHeader: _freebeer/lib/ISO639/Alpha3.php,v 1.2 2004/03/07 17:51:22 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file ISO639/Alpha3.php
	\brief ISO 639 Three character language codes
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(__FILE__))));

if (phpversion() <= '4.2.0') {
	include_once FREEBEER_BASE . '/lib/Backport.php'; // array_change_key_case, array_key_exists
}

/*!
	\class fbISO639_Alpha3
	\brief ISO 639 Three character language codes
	
	\static
*/
class fbISO639_Alpha3 {
	/*!
		See
		http://www.w3.org/WAI/ER/IG/ert/iso639.htm

		Oops! Some duplicates:

			'INE'	=> 'Indo-European (Other)',
		//  'INE'	=> 'Interlingue',
			'MAK'	=> 'Macedonian',	// mk (also mac)
		//  'MAK'	=> 'Makasar',
			'SSW'	=> 'Siswant',	// ss
		//  'SSW'	=> 'Swazi',

		\return \c array
		\static
	*/
	function &getIDToNameHash() {
		static $ID3_TO_NAME_HASH = array(
			'ACE'	=> 'Achinese',
			'ACH'	=> 'Acoli',
			'ADA'	=> 'Adangme',
			'AFA'	=> 'Afro-Asiatic (Other)',
			'AFH'	=> 'Afrihili',
			'AKA'	=> 'Akan',
			'AKK'	=> 'Akkadian',
			'ALE'	=> 'Aleut',
			'ALG'	=> 'Algonquian languages',
			'ANG'	=> 'English, Old (ca. 450-1100)',
			'APA'	=> 'Apache languages',
			'ARC'	=> 'Aramaic',
			'ARN'	=> 'Araucanian',
			'ARP'	=> 'Arapaho',
			'ART'	=> 'Artificial (Other)',
			'ARW'	=> 'Arawak',
			'ATH'	=> 'Athapascan languages',
			'AVA'	=> 'Avaric',
			'AVE'	=> 'Avestan',
			'AWA'	=> 'Awadhi',
			'BAD'	=> 'Banda',
			'BAI'	=> 'Bamileke languages',
			'BAL'	=> 'Baluchi',
			'BAM'	=> 'Bambara',
			'BAN'	=> 'Balinese',
			'BAS'	=> 'Basa',
			'BAT'	=> 'Baltic (Other)',
			'BEJ'	=> 'Beja',
			'BEM'	=> 'Bemba',
			'BER'	=> 'Berber (Other)',
			'BHO'	=> 'Bhojpuri',
			'BIK'	=> 'Bikol',
			'BIN'	=> 'Bini',
			'BLA'	=> 'Siksika',
			'BNT'	=> 'Bantu (Other)',
			'BRA'	=> 'Braj',
			'BUA'	=> 'Buriat',
			'BUG'	=> 'Buginese',
			'CAD'	=> 'Caddo',
			'CAI'	=> 'Central American Indian (Other)',
			'CAR'	=> 'Carib',
			'CAU'	=> 'Caucasian (Other)',
			'CEB'	=> 'Cebuano',
			'CEL'	=> 'Celtic (Other)',
			'CHA'	=> 'Chamorro',
			'CHB'	=> 'Chibcha',
			'CHE'	=> 'Chechen',
			'CHG'	=> 'Chagatai',
			'CHM'	=> 'Mari',
			'CHN'	=> 'Chinook jargon',
			'CHO'	=> 'Choctaw',
			'CHR'	=> 'Cherokee',
			'CHU'	=> 'Church Slavic',
			'CHV'	=> 'Chuvash',
			'CHY'	=> 'Cheyenne',
			'COP'	=> 'Coptic',
			'COR'	=> 'Cornish',
			'CPE'	=> 'Creoles and Pidgins, English-based (Other)',
			'CPF'	=> 'Creoles and Pidgins, French-based (Other)',
			'CPP'	=> 'Creoles and Pidgins, Portuguese-based (Other)',
			'CRE'	=> 'Cree',
			'CRP'	=> 'Creoles and Pidgins (Other)',
			'CUS'	=> 'Cushitic (Other)',
			'DAK'	=> 'Dakota',
			'DEL'	=> 'Delaware',
			'DIN'	=> 'Dinka',
			'DIV'	=> 'Divehi',
			'DOI'	=> 'Dogri',
			'DRA'	=> 'Dravidian (Other)',
			'DUA'	=> 'Duala',
			'DUM'	=> 'Dutch, Middle (ca. 1050-1350)',
			'DYU'	=> 'Dyula',
			'EFI'	=> 'Efik',
			'EGY'	=> 'Egyptian (Ancient)',
			'EKA'	=> 'Ekajuk',
			'ELX'	=> 'Elamite',
			'ENM'	=> 'English, Middle (ca. 1100-1500)',
			'ESK'	=> 'Eskimo (Other)',
			'EWE'	=> 'Ewe',
			'EWO'	=> 'Ewondo',
			'FAN'	=> 'Fang',
			'FAT'	=> 'Fanti',
			'FIU'	=> 'Finno-Ugrian (Other)',
			'FON'	=> 'Fon',
			'FRM'	=> 'French, Middle (ca. 1400-1600)',
			'FRO'	=> 'French, Old (842- ca. 1400)',
			'FUL'	=> 'Fulah',
			'GAY'	=> 'Gayo',
			'GEM'	=> 'Germanic (Other)',
			'GEZ'	=> 'Geez',
			'GIL'	=> 'Gilbertese',
			'GMH'	=> 'German, Middle High (ca. 1050-1500)',
			'GOH'	=> 'German, Old High (ca. 750-1050)',
			'GON'	=> 'Gondi',
			'GOT'	=> 'Gothic',
			'GRB'	=> 'Grebo',
			'GRC'	=> 'Greek, Ancient (to 1453)',
			'HAI'	=> 'Haida',
			'HAW'	=> 'Hawaiian',
			'HER'	=> 'Herero',
			'HIL'	=> 'Hiligaynon',
			'HIM'	=> 'Himachali',
			'HMO'	=> 'Hiri Motu',
			'HUP'	=> 'Hupa',
			'IBA'	=> 'Iban',
			'IBO'	=> 'Igbo',
			'IJO'	=> 'Ijo',
			'ILO'	=> 'Iloko',
			'INC'	=> 'Indic (Other)',
			'INE'	=> 'Indo-European (Other)',
			// 'INE'	=> 'Interlingue',
			'IRA'	=> 'Iranian (Other)',
			'IRO'	=> 'Iroquoian languages',
			'JPR'	=> 'Judeo-Persian',
			'JRB'	=> 'Judeo-Arabic',
			'KAA'	=> 'Kara-Kalpak',
			'KAB'	=> 'Kabyle',
			'KAC'	=> 'Kachin',
			'KAM'	=> 'Kamba',
			'KAR'	=> 'Karen',
			'KAU'	=> 'Kanuri',
			'KAW'	=> 'Kawi',
			'KHA'	=> 'Khasi',
			'KHI'	=> 'Khoisan (Other)',
			'KHO'	=> 'Khotanese',
			'KIK'	=> 'Kikuyu',
			'KOK'	=> 'Konkani',
			'KOM'	=> 'Komi',
			'KON'	=> 'Kongo',
			'KPE'	=> 'Kpelle',
			'KRO'	=> 'Kru',
			'KRU'	=> 'Kurukh',
			'KUA'	=> 'Kuanyama',
			'KUM'	=> 'Kumyk',
			'KUS'	=> 'Kusaie',
			'KUT'	=> 'Kutenai',
			'LAD'	=> 'Ladino',
			'LAH'	=> 'Lahnda',
			'LAM'	=> 'Lamba',
			'LEZ'	=> 'Lezghian',
			'LOL'	=> 'Mongo',
			'LOZ'	=> 'Lozi',
			'LTZ'	=> 'Letzeburgesch',
			'LUB'	=> 'Luba-Katanga',
			'LUG'	=> 'Ganda',
			'LUI'	=> 'Luiseno',
			'LUN'	=> 'Lunda',
			'LUO'	=> 'Luo (Kenya and Tanzania)',
			'MAD'	=> 'Madurese',
			'MAG'	=> 'Magahi',
			'MAH'	=> 'Marshall',
			'MAI'	=> 'Maithili',
			// 'MAK'	=> 'Makasar',
			'MAL'	=> 'Malayalam',
			'MAN'	=> 'Mandingo',
			'MAP'	=> 'Austronesian (Other)',
			'MAS'	=> 'Masai',
			'MAX'	=> 'Manx',
			'MEN'	=> 'Mende',
			'MGA'	=> 'Irish, Middle (900 - 1200)',
			'MIC'	=> 'Micmac',
			'MIN'	=> 'Minangkabau',
			'MIS'	=> 'Miscellaneous (Other)',
			'MKH'	=> 'Mon-Kmer (Other)',
			'MNI'	=> 'Manipuri',
			'MNO'	=> 'Manobo languages',
			'MOH'	=> 'Mohawk',
			'MOS'	=> 'Mossi',
			'MUL'	=> 'Multiple languages',
			'MUN'	=> 'Munda languages',
			'MUS'	=> 'Creek',
			'MWR'	=> 'Marwari',
			'MYN'	=> 'Mayan languages',
			'NAH'	=> 'Aztec',
			'NAI'	=> 'North American Indian (Other)',
			'NAV'	=> 'Navajo',
			'NBL'	=> 'Ndebele, South',
			'NDE'	=> 'Ndebele, North',
			'NDO'	=> 'Ndongo',
			'NEW'	=> 'Newari',
			'NIC'	=> 'Niger-Kordofanian (Other)',
			'NIU'	=> 'Niuean',
			'NNO'	=> 'Norwegian (Nynorsk)',
			'NON'	=> 'Norse, Old',
			'NSO'	=> 'Sotho, Northern',
			'NUB'	=> 'Nubian languages',
			'NYA'	=> 'Nyanja',
			'NYM'	=> 'Nyamwezi',
			'NYN'	=> 'Nyankole',
			'NYO'	=> 'Nyoro',
			'NZI'	=> 'Nzima',
			'OJI'	=> 'Ojibwa',
			'OSA'	=> 'Osage',
			'OSS'	=> 'Ossetic',
			'OTA'	=> 'Turkish, Ottoman (1500 - 1928)',
			'OTO'	=> 'Otomian languages',
			'PAA'	=> 'Papuan-Australian (Other)',
			'PAG'	=> 'Pangasinan',
			'PAL'	=> 'Pahlavi',
			'PAM'	=> 'Pampanga',
			'PAP'	=> 'Papiamento',
			'PAU'	=> 'Palauan',
			'PEO'	=> 'Persian, Old (ca 600 - 400 B.C.)',
			'PHN'	=> 'Phoenician',
			'PLI'	=> 'Pali',
			'PON'	=> 'Ponape',
			'PRA'	=> 'Prakrit languages',
			'PRO'	=> 'Provencal, Old (to 1500)',
			'RAJ'	=> 'Rajasthani',
			'RAR'	=> 'Rarotongan',
			'ROA'	=> 'Romance (Other)',
			'ROM'	=> 'Romany',
			'SAD'	=> 'Sandawe',
			'SAH'	=> 'Yakut',
			'SAI'	=> 'South American Indian (Other)',
			'SAL'	=> 'Salishan languages',
			'SAM'	=> 'Samaritan Aramaic',
			'SCO'	=> 'Scots',
			'SEL'	=> 'Selkup',
			'SEM'	=> 'Semitic (Other)',
			'SGA'	=> 'Irish, Old (to 900)',
			'SHN'	=> 'Shan',
			'SID'	=> 'Sidamo',
			'SIO'	=> 'Siouan languages',
			'SIT'	=> 'Sino-Tibetan (Other)',
			'SLA'	=> 'Slavic (Other)',
			'SMI'	=> 'Sami languages',
			'SOG'	=> 'Sogdian',
			'SON'	=> 'Songhai',
			'SRD'	=> 'Sardinian',
			'SRR'	=> 'Serer',
			'SSA'	=> 'Nilo-Saharan (Other)',
			// 'SSW'	=> 'Swazi',
			'SUK'	=> 'Sukuma',
			'SUS'	=> 'Susu',
			'SUX'	=> 'Sumerian',
			'SYR'	=> 'Syriac',
			'TAH'	=> 'Tahitian',
			'TEM'	=> 'Timne',
			'TER'	=> 'Tereno',
			'TIG'	=> 'Tigre',
			'TIV'	=> 'Tivi',
			'TLI'	=> 'Tlingit',
			'TMH'	=> 'Tamashek',
			'TON'	=> 'Tonga (Tonga Islands)',
			'TRU'	=> 'Truk',
			'TSI'	=> 'Tsimshian',
			'TUM'	=> 'Tumbuka',
			'TUT'	=> 'Altaic (Other)',
			'TYV'	=> 'Tuvinian',
			'UGA'	=> 'Ugaritic',
			'UMB'	=> 'Umbundu',
			'UND'	=> 'Undetermined',
			'VAI'	=> 'Vai',
			'VEN'	=> 'Venda',
			'VOT'	=> 'Votic',
			'WAK'	=> 'Wakashan languages',
			'WAL'	=> 'Walamo',
			'WAR'	=> 'Waray',
			'WAS'	=> 'Washo',
			'WEN'	=> 'Sorbian languages',
			'YAO'	=> 'Yao',
			'YAP'	=> 'Yap',
			'ZAP'	=> 'Zapotec',
			'ZEN'	=> 'Zenaga',
			'ZUN'	=> 'Zuni',
			'AAR'	=> 'Afar',	// aa
			'ABK'	=> 'Abkhazian',	// ab
			'AFR'	=> 'Afrikaans',	// af
			'AMH'	=> 'Amharic',	// am
			'ARA'	=> 'Arabic',	// ar
			'ASM'	=> 'Assamese',	// as
			'AYM'	=> 'Aymara',	// ay
			'AZE'	=> 'Azerbaijani',	// az
			'BAK'	=> 'Bashkir',	// ba
			'BEL'	=> 'Byelorussia',	// be 	be is also bre/Breton
			'BEN'	=> 'Bengali',	// bn
			'BIH'	=> 'Bihari',	// bh
			'BIS'	=> 'Bislama',	// bi
			'BRE'	=> 'Breton',	// be	be is also bel/Byelorussian
			'BUL'	=> 'Bulgarian',	// bg
			'CAT'	=> 'Catalan',	// ca
			'COS'	=> 'Corsican',	// co
			'DAN'	=> 'Danish',	// da
			'DZO'	=> 'Dzongkha',	// dz
			'ENG'	=> 'English',	// en
			'EPO'	=> 'Esperanto',	// eo
			'EST'	=> 'Estonian',	// et
			'FAO'	=> 'Faroese',	// fo
			'FIJ'	=> 'Fijian',	// fj
			'FIN'	=> 'Finnish',	// fi
			'FRY'	=> 'Frisian',	// fy
			'GLG'	=> 'Gallegan',	// gl
			'GRN'	=> 'Guarani',	// gn
			'GUJ'	=> 'Gujarati',	// gu
			'HAU'	=> 'Hausa',	// ha
			'HEB'	=> 'Hebrew',	// he
			'HIN'	=> 'Hindi',	// hi
			'HUN'	=> 'Hungarian',	// hu
			'IKU'	=> 'Inuktitut',	// iu
			'INA'	=> 'Interlingua (International Auxiliary language Association)',	// ia
			'IND'	=> 'Indonesian',	// id
			'IPK'	=> 'Inupiak',	// ik
			'ITA'	=> 'Italian',	// it
			'JPN'	=> 'Japanese',	// ja
			'KAL'	=> 'Greenlandic',	// kl
			'KAN'	=> 'Kannada',	// kn
			'KAS'	=> 'Kashmiri',	// ks
			'KAZ'	=> 'Kazakh',	// kk
			'KHM'	=> 'Khmer',	// km
			'KIN'	=> 'Kinyarwanda',	// rw
			'KIR'	=> 'Kirghiz',	// ky
			'KOR'	=> 'Korean',	// ko
			'KUR'	=> 'Kurdish',	// ku
			'LAO'	=> 'Lao',	// lo
			'LAT'	=> 'Latin',	// la
			'LAV'	=> 'Latvian',	// lv
			'LIN'	=> 'Lingala',	// ln
			'LIT'	=> 'Lithuanian',	// lt
			'MAR'	=> 'Marathi',	// mr
			'MLG'	=> 'Malagasy',	// mg
			'MLT'	=> 'Maltese',	// ml
			'MOL'	=> 'Moldavian',	// mo
			'MON'	=> 'Mongolian',	// mn
			'NAU'	=> 'Nauru',	// na
			'NEP'	=> 'Nepali',	// ne
			'NOR'	=> 'Norwegian',	// no
			'OCI'	=> 'Langue d\'Oc (post 1500)',	// oc
			'ORI'	=> 'Oriya',	// or
			'ORM'	=> 'Oromo',	// om
			'PAN'	=> 'Panjabi',	// pa
			'POL'	=> 'Polish',	// pl
			'POR'	=> 'Portuguese',	// pt
			'PUS'	=> 'Pushto',	// ps
			'QUE'	=> 'Quechua',	// qu
			'ROH'	=> 'Rhaeto-Romance',	// rm
			'RUN'	=> 'Rundi',	// rn
			'RUS'	=> 'Russian',	// ru
			'SAG'	=> 'Sango',	// sg
			'SAN'	=> 'Sanskrit',	// sa
			'SCR'	=> 'Serbo-Croatian',	// sh
			'SIN'	=> 'Singhalese',	// si
			'SLV'	=> 'Slovenian',	// sl
			'SMO'	=> 'Samoan',	// sm
			'SNA'	=> 'Shona',	// sn
			'SND'	=> 'Sindhi',	// sd
			'SOM'	=> 'Somali',	// so
			'SOT'	=> 'Sotho, Southern',	// st
			'SSW'	=> 'Siswant',	// ss
			'SUN'	=> 'Sudanese',	// su
			'SWA'	=> 'Swahili',	// sw
			'TAM'	=> 'Tamil',	// ta
			'TAT'	=> 'Tatar',	// tt
			'TEL'	=> 'Telugu',	// te
			'TGK'	=> 'Tajik',	// tg
			'TGL'	=> 'Tagalog',	// tl
			'THA'	=> 'Thai',	// th
			'TIR'	=> 'Tigrinya',	// ti
			'TOG'	=> 'Tonga (Nyasa)',	// to
			'TSN'	=> 'Tswana',	// tn
			'TSO'	=> 'Tsonga',	// ts
			'TUK'	=> 'Turkmen',	// tk
			'TUR'	=> 'Turkish',	// tr
			'TWI'	=> 'Twi',	// tw
			'UIG'	=> 'Uighur',	// ug
			'UKR'	=> 'Ukrainian',	// uk
			'URD'	=> 'Urdu',	// ur
			'UZB'	=> 'Uzbek',	// uz
			'VIE'	=> 'Vietnamese',	// vi
			'VOL'	=> 'Volap?k',	// vo
			'WOL'	=> 'Wolof',	// wo
			'XHO'	=> 'Xhosa',	// xh
			'YID'	=> 'Yiddish',	// yi
			'YOR'	=> 'Yoruba',	// yo
			'ZHA'	=> 'Zhuang',	// za
			'ZUL'	=> 'Zulu',	// zu

			'CES'	=> 'Czech',	// cs (also cze)
			'BAQ'	=> 'Basque',	// eu (also eus)
			'FRA'	=> 'French',	// fr (also fre)
			'GAE'	=> 'Gaelic (Scots)',	// gd (also gdh)
			'DEU'	=> 'German',	// de (also ger)
			'ELL'	=> 'Greek, Modern (1453-)',	// el (also gre)
			'ARM'	=> 'Armenian',	// hy (also hye)
			'GAI'	=> 'Irish',	// ga (also iri)
			'ICE'	=> 'Icelandic',	// is (also isl)
			'GEO'	=> 'Georgian',	// ka (also kat)
			'MAC'	=> 'Macedonian',	// mk (also mak)
			'MAO'	=> 'Maori',	// mi (also mri)
			'MAY'	=> 'Malay',	// ms (also msa)
			'BUR'	=> 'Burmese',	// my (also mya)
			'DUT'	=> 'Dutch',	// nl (also nla)
			'FAS'	=> 'Persian',	// fa (also per)
			'RON'	=> 'Romanian',	// ro (also rum)
			'SLK'	=> 'Slovak',	// sk (also slo)
			'ESL'	=> 'Spanish',	// es (also spa)
			'ALB'	=> 'Albanian',	// sq (also sqi)
			'SVE'	=> 'Swedish',	// sv (also swe)
			'BOD'	=> 'Tibetan',	// bo (also tib)
			'CYM'	=> 'Welsh',	// cy (also wel)
			'CHI'	=> 'Chinese',	// zh (also zho)
			'JAV'	=> 'Javanese',	// jv (also jaw/jw)

			'CZE'	=> 'Czech',	// cs (also ces)
			'EUS'	=> 'Basque',	// eu (also baq)
			'FRE'	=> 'French',	// fr (also fra)
			'GDH'	=> 'Gaelic (Scots)',	// gd (also gae)
			'GER'	=> 'German',	// de (also deu)
			'GRE'	=> 'Greek, Modern (1453-)',	// el (also ell)
			'HYE'	=> 'Armenian',	// hy (also arm)
			'IRI'	=> 'Irish',	// ga (also gai)
			'ISL'	=> 'Icelandic',	// is (also ice)
			'KAT'	=> 'Georgian',	// ka (also geo)
			'MAK'	=> 'Macedonian',	// mk (also mac)
			'MRI'	=> 'Maori',	// mi (also mao)
			'MSA'	=> 'Malay',	// ms (also may)
			'MYA'	=> 'Burmese',	// my (also bur)
			'NLA'	=> 'Dutch',	// nl (also dut)
			'PER'	=> 'Persian',	// fa (also fas)
			'RUM'	=> 'Romanian',	// ro (also ron)
			'SLO'	=> 'Slovak',	// sk (also slk)
			'SPA'	=> 'Spanish',	// es (also esl)
			'SQI'	=> 'Albanian',	// sq (also alb)
			'SWE'	=> 'Swedish',	// sv (also sve)
			'TIB'	=> 'Tibetan',	// bo (also bod)
			'WEL'	=> 'Welsh',	// cy (also cym)
			'ZHO'	=> 'Chinese',	// zh (also chi)
			'JAW'	=> 'Javanese',	// jv (also jav/jw)
		);

		// make sure no dups snuck in
		assert('count($ID3_TO_NAME_HASH) == 423');

		return $ID3_TO_NAME_HASH;
	}

	/*!
		\return \c array
		\static
	*/
	function &getNameToIDHash() {
		static $NAME_TO_ID3_HASH = null;

		if (is_null($NAME_TO_ID3_HASH)) {
			$ID3_TO_NAME_HASH = &fbISO639_Alpha3::getIDToNameHash();
			$NAME_TO_ID3_HASH = array_flip($ID3_TO_NAME_HASH);

			$NAME_TO_ID3_HASH['Czech']			= 'CES';	// cs (also cze)
			$NAME_TO_ID3_HASH['Basque']			= 'BAQ';	// eu (also eus)
			$NAME_TO_ID3_HASH['French']			= 'FRA';	// fr (also fre)
			$NAME_TO_ID3_HASH['Gaelic (Scots)'] = 'GAE';	// gd (also gdh)
			$NAME_TO_ID3_HASH['German']			= 'DEU';	// de (also ger)
			$NAME_TO_ID3_HASH['Greek, Modern (1453-)'] = 'ELL';	// el (also gre)
			$NAME_TO_ID3_HASH['Armenian']		= 'ARM';	// hy (also hye)
			$NAME_TO_ID3_HASH['Irish']			= 'GAI';	// ga (also iri)
			$NAME_TO_ID3_HASH['Icelandic']		= 'ICE';	// is (also isl)
			$NAME_TO_ID3_HASH['Georgian']		= 'GEO';	// ka (also kat)
			$NAME_TO_ID3_HASH['Macedonian']		= 'MAC';	// mk (also mak)
			$NAME_TO_ID3_HASH['Maori']			= 'MAO';	// mi (also mri)
			$NAME_TO_ID3_HASH['Malay']			= 'MAY';	// ms (also msa)
			$NAME_TO_ID3_HASH['Burmese']		= 'BUR';	// my (also mya)
			$NAME_TO_ID3_HASH['Dutch']			= 'DUT';	// nl (also nla)
			$NAME_TO_ID3_HASH['Persian']		= 'FAS';	// fa (also per)
			$NAME_TO_ID3_HASH['Romanian']		= 'RON';	// ro (also rum)
			$NAME_TO_ID3_HASH['Slovak']			= 'SLK';	// sk (also slo)
			$NAME_TO_ID3_HASH['Spanish']		= 'ESL';	// es (also spa)
			$NAME_TO_ID3_HASH['Albanian']		= 'ALB';	// sq (also sqi)
			$NAME_TO_ID3_HASH['Swedish']		= 'SVE';	// sv (also swe)
			$NAME_TO_ID3_HASH['Tibetan']		= 'BOD';	// bo (also tib)
			$NAME_TO_ID3_HASH['Welsh']			= 'CYM';	// cy (also wel)
			$NAME_TO_ID3_HASH['Chinese']		= 'CHI';	// zh (also zho)
			$NAME_TO_ID3_HASH['Javanese']		= 'JAV';	// jv (also jaw/jw)

			$full_name_fixes = array(
				'TUT'	=> 'Altaic',	// (Other)',
				'APA'	=> 'Apache',	// languages',
				'MAP'	=> 'Austronesian',	// (Other)',
				'BAT'	=> 'Baltic',	// (Other)',
				'BNT'	=> 'Bantu',	// (Other)',
				'BER'	=> 'Berber',	// (Other)',
				'CAU'	=> 'Caucasian',	// (Other)',
				'CEL'	=> 'Celtic',	// (Other)',
				'CAI'	=> 'Central',	// American Indian (Other)',
				'CHN'	=> 'Chinook',	// jargon',
				// 'CHU'	=> 'Church Slavic',
				//'CRP'	=> 'Creoles and Pidgins (Other)',
				//'CPE'	=> 'Creoles and Pidgins, English-based (Other)',
				//'CPF'	=> 'Creoles and Pidgins, French-based (Other)',
				//'CPP'	=> 'Creoles and Pidgins, Portuguese-based (Other)',
				'CUS'	=> 'Cushitic',	// (Other)',
				'DRA'	=> 'Dravidian',	// (Other)',
				//'DUM'	=> 'Dutch, Middle (ca. 1050-1350)',
				// 'EGY'	=> 'Egyptian (Ancient)',
				// 'ENM'	=> 'English, Middle (ca. 1100-1500)',
				// 'ANG'	=> 'English, Old (ca. 450-1100)',
				'ESK'	=> 'Eskimo',	// (Other)',
				'FIU'	=> 'Finno-Ugrian',	// (Other)',
				// 'FRM'	=> 'French, Middle (ca. 1400-1600)',
				// 'FRO'	=> 'French, Old (842- ca. 1400)',
				//'GDH'	=> 'Gaelic (Scots)',	// gd (also gae)
				'GAE'	=> 'Gaelic',	// (Scots)',	// gd (also gdh)
				//'GMH'	=> 'German, Middle High (ca. 1050-1500)',
				//'GOH'	=> 'German, Old High (ca. 750-1050)',
				'GEM'	=> 'Germanic',	// (Other)',
				//'GRC'	=> 'Greek, Ancient (to 1453)',
				//'GRE'	=> 'Greek, Modern (1453-)',	// el (also ell)
				'ELL'	=> 'Greek',	// , Modern (1453-)',	// el (also gre)
				'INC'	=> 'Indic',	// (Other)',
				'INE'	=> 'Indo-European',	// (Other)',
				//'INA'	=> 'Interlingua (International Auxiliary language Association)',	// ia
				'IRA'	=> 'Iranian',	// (Other)',
				// 'MGA'	=> 'Irish, Middle (900 - 1200)',
				// 'SGA'	=> 'Irish, Old (to 900)',
				'IRO'	=> 'Iroquoian',	// languages',
				'KHI'	=> 'Khoisan',	// (Other)',
				'OCI'	=> 'Langue d\'Oc',	// (post 1500)',	// oc
				'LUO'	=> 'Luo',	// (Kenya and Tanzania)',
				'MNO'	=> 'Manobo',	// languages',
				'MYN'	=> 'Mayan',	// languages',
				'MIS'	=> 'Miscellaneous',	// (Other)',
				'MKH'	=> 'Mon-Kmer',	// (Other)',
				// 'MUL'	=> 'Multiple languages',
				'MUN'	=> 'Munda',	// languages',
				// 'NDE'	=> 'Ndebele, North',
				// 'NBL'	=> 'Ndebele, South',
				'NIC'	=> 'Niger-Kordofanian',	// (Other)',
				'SSA'	=> 'Nilo-Saharan',	// (Other)',
				'NON'	=> 'Norse',	// , Old',
				'NAI'	=> 'North American Indian', //, (Other)',
				// 'NNO'	=> 'Norwegian (Nynorsk)',
				'NUB'	=> 'Nubian',	// languages',
				'OTO'	=> 'Otomian',	// languages',
				'PAA'	=> 'Papuan-Australian',	// (Other)',
				// 'PEO'	=> 'Persian, Old (ca 600 - 400 B.C.)',
				'PRA'	=> 'Prakrit',	// languages',
				'PRO'	=> 'Provencal',	// , Old (to 1500)',
				'ROA'	=> 'Romance',	// (Other)',
				'SAL'	=> 'Salishan',	// languages',
				'SAM'	=> 'Samaritan',	// Aramaic',
				'SMI'	=> 'Sami',	// languages',
				'SEM'	=> 'Semitic',	// (Other)',
				'SIT'	=> 'Sino-Tibetan',	// (Other)',
				'SIO'	=> 'Siouan',	// languages',
				'SLA'	=> 'Slavic',	// (Other)',
				'WEN'	=> 'Sorbian',	// languages',
				// 'NSO'	=> 'Sotho, Northern',
				// 'SOT'	=> 'Sotho, Southern',	// st
				'SAI'	=> 'South American Indian',	// (Other)',
				// 'TOG'	=> 'Tonga (Nyasa)',	// to
				// 'TON'	=> 'Tonga (Tonga Islands)',
				// 'OTA'	=> 'Turkish, Ottoman (1500 - 1928)',
				'WAK'	=> 'Wakashan',	// languages',
			);

			foreach($full_name_fixes as $id3 => $name) {
				if (!array_key_exists($name, $NAME_TO_ID3_HASH)) {
					$NAME_TO_ID3_HASH[$name] = $id3;
				}
			}

		}

		return $NAME_TO_ID3_HASH;
	}

	/*!
		\param $name \c string
		\return \c string
		
		\static
	*/
	function getLanguageID($name) {
		static $NAME_TO_ID3_HASH_UC = null;

		if (is_null($NAME_TO_ID3_HASH_UC)) {
			$NAME_TO_ID3_HASH = &fbISO639_Alpha3::getNameToIDHash();
			$NAME_TO_ID3_HASH_UC = array_change_key_case($NAME_TO_ID3_HASH, CASE_UPPER);
		}

		$name = strtoupper($name);
		return isset($NAME_TO_ID3_HASH_UC[$name]) ? $NAME_TO_ID3_HASH_UC[$name] : false;
	}

	/*!
		\param $id3 \c string
		\return \c string

		\static
	*/
	function getLanguageName($id3) {
		$ID3_TO_NAME_HASH = &fbISO639_Alpha3::getIDToNameHash();
		$id3 = strtoupper($id3);
		return isset($ID3_TO_NAME_HASH[$id3]) ? $ID3_TO_NAME_HASH[$id3] : false;
	}

}

?>
