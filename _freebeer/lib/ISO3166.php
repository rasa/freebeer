<?php

// $CVSHeader: _freebeer/lib/ISO3166.php,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

/*!
	\file ISO3166.php
	\brief ISO3166 country code related functions
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

if (phpversion() <= '4.2.0') {
	include_once FREEBEER_BASE . '/lib/Backport.php'; // array_change_key_case
}

/*!
	\class fbISO3166
	\brief ISO3166 country code related functions
	
	\static
*/
class fbISO3166 {
	/*!
		\return \c array

		\static
		\see http://www.w3.org/WAI/ER/IG/ert/iso639.htm
		\see http://www.iso.ch/iso/en/prods-services/iso3166ma/02iso-3166-code-lists/list-en1-semic.txt
	*/
	function &getIDToNameHash() {
		static $ID_TO_NAME_HASH = array(
			'AF'	=> 'Afghanistan',
			'AL'	=> 'Albania',
			'DZ'	=> 'Algeria',
			'AS'	=> 'American Samoa',
			'AD'	=> 'Andorra',
			'AO'	=> 'Angola',
			'AI'	=> 'Anguilla',
			'AQ'	=> 'Antarctica',
			'AG'	=> 'Antigua and Barbuda',
			'AR'	=> 'Argentina',
			'AM'	=> 'Armenia',
			'AW'	=> 'Aruba',
			'AU'	=> 'Australia',
			'AT'	=> 'Austria',
			'AZ'	=> 'Azerbaijan',
			'BS'	=> 'Bahamas',
			'BH'	=> 'Bahrain',
			'BD'	=> 'Bangladesh',
			'BB'	=> 'Barbados',
			'BY'	=> 'Belarus',
			'BE'	=> 'Belgium',
			'BZ'	=> 'Belize',
			'BJ'	=> 'Benin',
			'BM'	=> 'Bermuda',
			'BT'	=> 'Bhutan',
			'BO'	=> 'Bolivia',
			'BA'	=> 'Bosnia and Herzegovina',
			'BW'	=> 'Botswana',
			'BV'	=> 'Bouvet Island',
			'BR'	=> 'Brazil',
			'IO'	=> 'British Indian Ocean Territory',
			'BN'	=> 'Brunei Darussalam',
			'BG'	=> 'Bulgaria',
			'BF'	=> 'Burkina Faso',
			'BI'	=> 'Burundi',
			'KH'	=> 'Cambodia',
			'CM'	=> 'Cameroon',
			'CA'	=> 'Canada',
			'CV'	=> 'Cape Verde',
			'KY'	=> 'Cayman Islands',
			'CF'	=> 'Central African Republic',
			'TD'	=> 'Chad',
			'CL'	=> 'Chile',
			'CN'	=> 'China',
			'CX'	=> 'Christmas Island',
			'CC'	=> 'Cocos (Keeling) Islands',
			'CO'	=> 'Colombia',
			'KM'	=> 'Comoros',
			'CG'	=> 'Congo',
			'CD'	=> 'Congo, The Democratic Republic of the',
			'CK'	=> 'Cook Islands',
			'CR'	=> 'Costa Rica',
			'CI'	=> 'Cote D\'Ivoire',
			'HR'	=> 'Croatia',
			'CU'	=> 'Cuba',
			'CY'	=> 'Cyprus',
			'CZ'	=> 'Czech Republic',
			'DK'	=> 'Denmark',
			'DJ'	=> 'Djibouti',
			'DM'	=> 'Dominica',
			'DO'	=> 'Dominican Republic',
			'EC'	=> 'Ecuador',
			'EG'	=> 'Egypt',
			'SV'	=> 'El Salvador',
			'GQ'	=> 'Equatorial Guinea',
			'ER'	=> 'Eritrea',
			'EE'	=> 'Estonia',
			'ET'	=> 'Ethiopia',
			'FK'	=> 'Falkland Islands (Malvinas)',
			'FO'	=> 'Faroe Islands',
			'FJ'	=> 'Fiji',
			'FI'	=> 'Finland',
			'FR'	=> 'France',
			'GF'	=> 'French Guiana',
			'PF'	=> 'French Polynesia',
			'TF'	=> 'French Southern Territories',
			'GA'	=> 'Gabon',
			'GM'	=> 'Gambia',
			'GE'	=> 'Georgia',
			'DE'	=> 'Germany',
			'GH'	=> 'Ghana',
			'GI'	=> 'Gibraltar',
			'GR'	=> 'Greece',
			'GL'	=> 'Greenland',
			'GD'	=> 'Grenada',
			'GP'	=> 'Guadeloupe',
			'GU'	=> 'Guam',
			'GT'	=> 'Guatemala',
			'GN'	=> 'Guinea',
			'GW'	=> 'Guinea-Bissau',
			'GY'	=> 'Guyana',
			'HT'	=> 'Haiti',
			'HM'	=> 'Heard Island and McDonald Islands',
			'VA'	=> 'Holy See (Vatican City State)',
			'HN'	=> 'Honduras',
			'HK'	=> 'Hong Kong',
			'HU'	=> 'Hungary',
			'IS'	=> 'Iceland',
			'IN'	=> 'India',
			'ID'	=> 'Indonesia',
			'IR'	=> 'Iran, Islamic Republic of',
			'IQ'	=> 'Iraq',
			'IE'	=> 'Ireland',
			'IL'	=> 'Israel',
			'IT'	=> 'Italy',
			'JM'	=> 'Jamaica',
			'JP'	=> 'Japan',
			'JO'	=> 'Jordan',
			'KZ'	=> 'Kazakhstan',
			'KE'	=> 'Kenya',
			'KI'	=> 'Kiribati',
			'KP'	=> 'Korea, Democratic People\'s Republic of',
			'KR'	=> 'Korea, Republic of',
			'KW'	=> 'Kuwait',
			'KG'	=> 'Kyrgyzstan',
			'LA'	=> 'Lao People\'s Democratic Republic',
			'LV'	=> 'Latvia',
			'LB'	=> 'Lebanon',
			'LS'	=> 'Lesotho',
			'LR'	=> 'Liberia',
			'LY'	=> 'Libyan Arab Jamahiriya',
			'LI'	=> 'Liechtenstein',
			'LT'	=> 'Lithuania',
			'LU'	=> 'Luxembourg',
			'MO'	=> 'Macao',
			'MK'	=> 'Macedonia, the Former Yugoslav Republic of',
			'MG'	=> 'Madagascar',
			'MW'	=> 'Malawi',
			'MY'	=> 'Malaysia',
			'MV'	=> 'Maldives',
			'ML'	=> 'Mali',
			'MT'	=> 'Malta',
			'MH'	=> 'Marshall Islands',
			'MQ'	=> 'Martinique',
			'MR'	=> 'Mauritania',
			'MU'	=> 'Mauritius',
			'YT'	=> 'Mayotte',
			'MX'	=> 'Mexico',
			'FM'	=> 'Micronesia, Federated States of',
			'MD'	=> 'Moldova, Republic of',
			'MC'	=> 'Monaco',
			'MN'	=> 'Mongolia',
			'MS'	=> 'Montserrat',
			'MA'	=> 'Morocco',
			'MZ'	=> 'Mozambique',
			'MM'	=> 'Myanmar',
			'NA'	=> 'Namibia',
			'NR'	=> 'Nauru',
			'NP'	=> 'Nepal',
			'NL'	=> 'Netherlands',
			'AN'	=> 'Netherlands Antilles',
			'NC'	=> 'New Caledonia',
			'NZ'	=> 'New Zealand',
			'NI'	=> 'Nicaragua',
			'NE'	=> 'Niger',
			'NG'	=> 'Nigeria',
			'NU'	=> 'Niue',
			'NF'	=> 'Norfolk Island',
			'MP'	=> 'Northern Mariana Islands',
			'NO'	=> 'Norway',
			'OM'	=> 'Oman',
			'PK'	=> 'Pakistan',
			'PW'	=> 'Palau',
			'PS'	=> 'Palestinian Territory, Occupied',
			'PA'	=> 'Panama',
			'PG'	=> 'Papua New Guinea',
			'PY'	=> 'Paraguay',
			'PE'	=> 'Peru',
			'PH'	=> 'Philippines',
			'PN'	=> 'Pitcairn',
			'PL'	=> 'Poland',
			'PT'	=> 'Portugal',
			'PR'	=> 'Puerto Rico',
			'QA'	=> 'Qatar',
			'RE'	=> 'Reunion',
			'RO'	=> 'Romania',
			'RU'	=> 'Russian Federation',
			'RW'	=> 'Rwanda',
			'SH'	=> 'Saint Helena',
			'KN'	=> 'Saint Kitts and Nevis',
			'LC'	=> 'Saint Lucia',
			'PM'	=> 'Saint Pierre and Miquelon',
			'VC'	=> 'Saint Vincent and the Grenadines',
			'WS'	=> 'Samoa',
			'SM'	=> 'San Marino',
			'ST'	=> 'Sao Tome and Principe',
			'SA'	=> 'Saudi Arabia',
			'SN'	=> 'Senegal',
			'CS'	=> 'Serbia and Montenegro',
			'SC'	=> 'Seychelles',
			'SL'	=> 'Sierra Leone',
			'SG'	=> 'Singapore',
			'SK'	=> 'Slovakia',
			'SI'	=> 'Slovenia',
			'SB'	=> 'Solomon Islands',
			'SO'	=> 'Somalia',
			'ZA'	=> 'South Africa',
			'GS'	=> 'South Georgia and the South Sandwich Islands',
			'ES'	=> 'Spain',
			'LK'	=> 'Sri Lanka',
			'SD'	=> 'Sudan',
			'SR'	=> 'Suriname',
			'SJ'	=> 'Svalbard and Jan Mayen',
			'SZ'	=> 'Swaziland',
			'SE'	=> 'Sweden',
			'CH'	=> 'Switzerland',
			'SY'	=> 'Syrian Arab Republic',
			'TW'	=> 'Taiwan, Province of China',
			'TJ'	=> 'Tajikistan',
			'TZ'	=> 'Tanzania, United Republic of',
			'TH'	=> 'Thailand',
			'TL'	=> 'Timor-Leste',
			'TG'	=> 'Togo',
			'TK'	=> 'Tokelau',
			'TO'	=> 'Tonga',
			'TT'	=> 'Trinidad and Tobago',
			'TN'	=> 'Tunisia',
			'TR'	=> 'Turkey',
			'TM'	=> 'Turkmenistan',
			'TC'	=> 'Turks and Caicos Islands',
			'TV'	=> 'Tuvalu',
			'UG'	=> 'Uganda',
			'UA'	=> 'Ukraine',
			'AE'	=> 'United Arab Emirates',
			'GB'	=> 'United Kingdom',
			'US'	=> 'United States',
			'UM'	=> 'United States Minor Outlying Islands',
			'UY'	=> 'Uruguay',
			'UZ'	=> 'Uzbekistan',
			'VU'	=> 'Vanuatu',
			'VE'	=> 'Venezuela',
			'VN'	=> 'Viet Nam',
			'VG'	=> 'Virgin Islands, British',
			'VI'	=> 'Virgin Islands, U.S.',
			'WF'	=> 'Wallis and Futuna',
			'EH'	=> 'Western Sahara',
			'YE'	=> 'Yemen',
			'ZM'	=> 'Zambia',
			'ZW'	=> 'Zimbabwe',
		);

		// make sure no dups snuck in
		assert('count($ID_TO_NAME_HASH) == 239');

		return $ID_TO_NAME_HASH;
	}

	/*!
		\return \c array
		\static
	*/
	function &getNameToIDHash() {
		static $NAME_TO_ID_HASH = null;

		if (is_null($NAME_TO_ID_HASH)) {
			$ID_TO_NAME_HASH = &fbISO3166::getIDToNameHash();
			$NAME_TO_ID_HASH = array_flip($ID_TO_NAME_HASH);
		}

		// Windows names 
		$NAME_TO_ID_HASH['Faeroe Islands']			= 'FO'; // 'Faroe Islands'
		$NAME_TO_ID_HASH['Tatarstan']				= 'TA'; /// \todo verify TA is country code for Tatarstan
		$NAME_TO_ID_HASH['Principality of Monaco']	= 'MC'; // 'Monaco'
		$NAME_TO_ID_HASH['Former Yugoslav Republic of Macedonia'] = 'MK'; // 'Macedonia, the Former Yugoslav Republic of'
		$NAME_TO_ID_HASH['Russia']					= 'RU'; // 'Russian Federation'
		$NAME_TO_ID_HASH['Serbia']					= 'CS'; // 'Serbia and Montenegro'

		return $NAME_TO_ID_HASH;
	}

	/*!
		\param $name \c string
		\return \c string
		
		\static
	*/
	function getCountryID($name) {
		static $NAME_TO_ID_HASH_UC = null;

		if (is_null($NAME_TO_ID_HASH_UC)) {
			$NAME_TO_ID_HASH = &fbISO3166::getNameToIDHash();
			$NAME_TO_ID_HASH_UC = array_change_key_case($NAME_TO_ID_HASH, CASE_UPPER);
		}

		$name = strtoupper($name);
		return isset($NAME_TO_ID_HASH_UC[$name]) ? $NAME_TO_ID_HASH_UC[$name] : false;
	}

	/*!
		\param $id \c string
		\return \c string
		
		\static
	*/
	function getCountryName($id) {
		$ID_TO_NAME_HASH = &fbISO3166::getIDToNameHash();
		$id = strtoupper($id);
		return isset($ID_TO_NAME_HASH[$id]) ? $ID_TO_NAME_HASH[$id] : false;
	}

}

?>
