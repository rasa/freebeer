<?php

// $CVSHeader: _freebeer/lib/ISO639/CharsetMap.php,v 1.2 2004/03/07 17:51:22 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/*!
	\file ISO639/CharsetMap.php
	\brief ISO 639 language code to character set map
*/		

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(__FILE__))));

if (phpversion() <= '4.2.0') {
	include_once FREEBEER_BASE . '/lib/Backport.php'; // array_change_key_case
}

/*!
	\class fbISO639_CharsetMap
	\brief ISO 639 language code to character set map

	From http://www.w3.org/International/O-charset-lang.html :

Afrikaans		(af)	ISO-8859-1	windows-1252  
Albanian		(sq)	ISO-8859-1	windows-1252  
Arabic			(ar)	ISO-8859-6  
Basque			(eu)	ISO-8859-1	windows-1252  
Bulgarian		(bg)	ISO-8859-5  
Byelorussian	(be)	ISO-8859-5  
Catalan			(ca)	ISO-8859-1	windows-1252  
Croatian		(hr)	ISO-8859-2	windows-1250 
Czech			(cs)	ISO-8859-2  
Danish			(da)	ISO-8859-1	windows-1252  
Dutch			(nl)	ISO-8859-1	windows-1252  
English			(en)	ISO-8859-1	windows-1252  
Esperanto		(eo)	ISO-8859-3*  
Estonian		(et)	ISO-8859-15  
Faroese			(fo)	ISO-8859-1	windows-1252  
Finnish			(fi)	ISO-8859-1	windows-1252  
French			(fr)	ISO-8859-1	windows-1252  
Galician		(gl)	ISO-8859-1	windows-1252  
German			(de)	ISO-8859-1	windows-1252  
Greek			(el)	ISO-8859-7  
Hebrew			(iw)	ISO-8859-8  
Hungarian		(hu)	ISO-8859-2  
Icelandic		(is)	ISO-8859-1	windows-1252  
Inuit			(Eskimo)	ISO-8859-10*
Irish			(ga)	ISO-8859-1	windows-1252  
Italian			(it)	ISO-8859-1	windows-1252  
Japanese		(ja)	shift_jis	ISO-2022-jp	euc-jp  
Korean			(kr)	euc-kr 
Lapp   				ISO-8859-10* ** 
Latvian			(lv)	ISO-8859-13	windows-1257  
Lithuanian		(lt)	ISO-8859-13	windows-1257  
Macedonian		(mk)	ISO-8859-5	windows-1251 
Maltese			(mt)	ISO-8859-3*  
Norwegian		(no)	ISO-8859-1	windows-1252  
Polish			(pl)	ISO-8859-2  
Portuguese		(pt)	ISO-8859-1	windows-1252  
Romanian		(ro)	ISO-8859-2  
Russian			(ru)	koi8-r	ISO-8859-5  
Scottish		(gd)	ISO-8859-1	windows-1252  
Serbian			(sr)	cyrillic  windows-1251	ISO-8859-5***  
Serbian			(sr)	latin  ISO-8859-2	windows-1250  
Slovak			(sk)	ISO-8859-2  
Slovenian		(sl)	ISO-8859-2	windows-1250 
Spanish			(es)	ISO-8859-1	windows-1252  
Swedish			(sv)	ISO-8859-1	windows-1252  
Turkish			(tr)	ISO-8859-9	windows-1254  
Ukrainian		(uk)	ISO-8859-5   

* = scarce support in browsers
** = Lapp doesn't have a 2-letter code, a three letter code (lap) is proposed in NISO Z39.53.
*** = Serbian can be written in Latin (most commonly used) and Cyrillic (mostly windows-1251)

Note that UTF-8 can be used for all languages and is the recommended charset on the Internet. Support for it is rapidly increasing.

For Hebrew in HTML, ISO-8859-8 is the same as ISO-8859-8-i ('implicit directionality'). This is unlike e-mail, where they are different.

For more 2-letter language codes, see ISO 639.

	\static
*/
class fbISO639_CharsetMap {
	/*!
		\return \c array
		\static
	*/
	function &getIDToCharsetHash() {
		static $ID_TO_CHARSET_HASH = array(
			'AF'	=> 'ISO-8859-1',	// Afrikaans	windows-1252
			'AR'	=> 'ISO-8859-6',	// Arabic						windows-1256
			'BE'	=> 'ISO-8859-5',	// Byelorussian		
			'BG'	=> 'ISO-8859-5',	// Bulgarian					windows-1251
			'CA'	=> 'ISO-8859-1',	// Catalan		windows-1252
			'CS'	=> 'ISO-8859-2',	// Czech		
			'DA'	=> 'ISO-8859-1',	// Danish		windows-1252
			'DE'	=> 'ISO-8859-1',	// German		windows-1252
			'EL'	=> 'ISO-8859-7',	// Greek		
			'EN'	=> 'ISO-8859-1',	// English		windows-1252
			'EO'	=> 'ISO-8859-3',	// Esperanto		
			'ES'	=> 'ISO-8859-1',	// Spanish		windows-1252
			'ET'	=> 'ISO-8859-15',	// Estonian						ISO-8859-13
			'EU'	=> 'ISO-8859-1',	// Basque		windows-1252
			'FI'	=> 'ISO-8859-1',	// Finnish		windows-1252
			'FO'	=> 'ISO-8859-1',	// Faroese		windows-1252
			'FR'	=> 'ISO-8859-1',	// French		windows-1252
			'GA'	=> 'ISO-8859-1',	// Irish		windows-1252
			'GD'	=> 'ISO-8859-1',	// Scottish		windows-1252
			'GL'	=> 'ISO-8859-1',	// Galician		windows-1252
			'HR'	=> 'ISO-8859-2',	// Croatian		windows-1250
			'HU'	=> 'ISO-8859-2',	// Hungarian		
			'IS'	=> 'ISO-8859-1',	// Icelandic	windows-1252
			'IT'	=> 'ISO-8859-1',	// Italian		windows-1252
			'IW'	=> 'ISO-8859-8',	// Hebrew		
			'JA'	=> 'SHIFT_JIS',		// Japanese		ISO-2022-jp	euc-jp
			'KR'	=> 'EUC-KR',		// Korean		
			'LT'	=> 'ISO-8859-13',	// Lithuanian	windows-1257
			'LV'	=> 'ISO-8859-13',	// Latvian		windows-1257	windows-1257
			'MK'	=> 'ISO-8859-5',	// Macedonian	windows-1251
			'MT'	=> 'ISO-8859-3',	// Maltese		
			'NL'	=> 'ISO-8859-1',	// Dutch		windows-1252
			'NO'	=> 'ISO-8859-1',	// Norwegian	windows-1252
			'PL'	=> 'ISO-8859-2',	// Polish		
			'PT'	=> 'ISO-8859-1',	// Portuguese	windows-1252
			'RO'	=> 'ISO-8859-2',	// Romanian		
			'RU'	=> 'KOI8-R',		// Russian		ISO-8859-5		windows-1251
			'SK'	=> 'ISO-8859-2',	// Slovak		
			'SL'	=> 'ISO-8859-2',	// Slovenian	windows-1250
			'SQ'	=> 'ISO-8859-1',	// Albanian		windows-1252
			'SR'	=> 'ISO-8859-2',	// Serbian (latin)		windows-1250
//			'SR'	=> 'windows-1251',	// Serbian (cyrillic)	ISO-8859-5
			'SV'	=> 'ISO-8859-1',	// Swedish		windows-1252
			'TH'	=> 'TIS-620',		// Thai
			'TR'	=> 'ISO-8859-9',	// Turkish		windows-1254
			'UK'	=> 'ISO-8859-5',	// Ukrainian					KOI8-U
			'ZH'	=> 'GB2312',		// Chinese
//			'ZH_CN'	=> 'GB2312',		// Chinese (China) (Simplified)
			'ZH_TW'	=> 'BIG5',			// Chinese (Taiwan) (Traditional)

			// Inuit	(Eskimo)	ISO-8859-10*
			// Lapp   	ISO-8859-10* ** 
		);

		return $ID_TO_CHARSET_HASH;
	}

	/*!
		\return \c string charset if found, otherwise false
		\static
	*/
	function getCharset($language_id, $country_id = null) {
		$ID_TO_CHARSET_HASH = &fbISO639_CharsetMap::getIDToCharsetHash();
		$language_id = strtoupper($language_id);
		if (!is_null($country_id)) {
			$country_id = strtoupper($country_id);
			$id = $language_id . '_' . $country_id;
			if (isset($ID_TO_CHARSET_HASH[$id])) {
				return $ID_TO_CHARSET_HASH[$id];
			}
		}
		return isset($ID_TO_CHARSET_HASH[$language_id]) ? $ID_TO_CHARSET_HASH[$language_id] : false;
	}

}

?>
