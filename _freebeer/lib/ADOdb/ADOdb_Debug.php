<?php

// $CVSHeader: _freebeer/lib/ADOdb/ADOdb_Debug.php,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

// Copyright (c) 2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

// Copyright (c) 2001-2003, Bold New World, Inc. (http://bnw.com/) All rights reserved.  Used by permission.

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(__FILE__))));

require_once FREEBEER_BASE . '/lib/ADOdb.php';
require_once FREEBEER_BASE . '/lib/Debug.php';

// is this still used?
//require_once ADODB_DIR . '/tohtml.inc.php';

class ADODB_debug extends fbDebug {
	/*!
		\static
	*/
	function explain() {
		return ((fbDebug::_level() & FB_DEBUG_EXPLAIN) == FB_DEBUG_EXPLAIN);
	}

	/*!
		\static
	*/
	function time_sql() {
		return ((fbDebug::_level() & FB_DEBUG_TIME_SQL) == FB_DEBUG_TIME_SQL);
	}

	/*!
		\private
		\static
	*/
	function _rs2text(&$rs) {
		if (!fbDebug::debugging()) {
			return '';
		}

		if (!$rs)
			trigger_error('Invalid RecordSet handle');
		$ncols = $rs->FieldCount();
//		$s = "<table border='1'>\n";
//		$s .= "\t<tr>\n";
		$s = '';
		$typearr = array();
//		$s .= "\t\t<th align=right>#</th>\n";
		$s = "#\t";
		for ($i = 0; $i < $ncols; $i++) {	
			$field = $rs->FetchField($i);
			$fieldtype = $rs->MetaType($field->type,$field->max_length);
			$typearr[$i] = $fieldtype;
			$name = $field->name ? $field->name : '&nbsp;';
//			$s .= "\t\t<th align=";
			switch($fieldtype) {
				case 'T':
				case 'D':
				case 'I':
				case 'N':
//					$s .= 'right';
				break;
				default:
//					$s .= 'left';
				break;
			}
//			$s .= "><tt>" . htmlspecialchars($name) . "</tt></th>\n";
			if ($i)
				$s .= "\t";
			$s .= $name;
		}
		$s .= "\n";
//		$s .= "\t</tr>\n";

		$rows = 0;
		$numoffset = isset($rs->fields[0]);
		while (!$rs->EOF) {
//			$s .= "\t<tr valign=top>\n";

//			$s .= "\t\t<td align=right>" . ++$rows . "</td>\n";
			$s .= ++$rows . "\t";

			for ($i = 0, $v=($numoffset) ? $rs->fields[0] : reset($rs->fields); 
				$i < $ncols; 
				$i++, $v = ($numoffset) ? @$rs->fields[$i] : next($rs->fields)) {

//				$s .= "\t\t<td";

				switch($typearr[$i]) {
					case 'T':
						$s .= $rs->UserTimeStamp($v,'D d, M Y, h:i:s');
//						$s .= '>' . $rs->UserTimeStamp($v,'D d, M Y, h:i:s');
						break;
					case 'D':
						$s .= $rs->UserDate($v,"D d, M Y");
//						$s .= '>' . $rs->UserDate($v,"D d, M Y");
						break;
					case 'I':
					case 'N':
						$s .= stripslashes((trim($v)));
//						$s .= ' align=right>' . stripslashes((trim($v)));
						break;
					default:
//						if ($htmlspecialchars)
//							$v = htmlspecialchars($v);
						$s .= str_replace("\n",'\n',stripslashes((trim($v))));
//						$s .= '>'. str_replace("\n",'<br />',stripslashes((trim($v))));
				}
				$s .= "\t";
//				$s .= "&nbsp;</td>\n";
			} // for
			$s .= "\n";
//			$s .= "\t</tr>\n";

			if ($rows >= ADODB_debug::_maxRows()) {
				$s .= sprintf("Truncated at %s rows (%d rows remaining)\n", ADODB_debug::_maxRows(), $rs->RecordCount() - ADODB_debug::_maxRows());
				break;
			}

			$rs->MoveNext();
		} // while

		$s .= "</table>\n";
		if ($rows)
			$rs->MoveFirst();
		return $s;
	}

	/*!
		\static
	*/
	function rs2text($rs) {
		if (!fbDebug::debugging()) {
			return '';
		}

		$s = ADODB_debug::_rs2text($rs);

		fbDebug::_log($s);
		return $s;
	}

	/*!
		\private
		\static
	*/
	function _rs2html(&$rs) {
		if (!fbDebug::debugging()) {
			return '';
		}

		if (!$rs)
			trigger_error('Invalid RecordSet handle');
		$ncols = $rs->FieldCount();
		$s = "<table border='1'>\n";
		$s .= "\t<tr>\n";
		$typearr = array();
		$s .= "\t\t<th align=right>#</th>\n";
		for ($i = 0; $i < $ncols; $i++) {	
			$field = $rs->FetchField($i);
			$fieldtype = $rs->MetaType($field->type,$field->max_length);
			$typearr[$i] = $fieldtype;
			$name = $field->name ? $field->name : '&nbsp;';
			$s .= "\t\t<th align=";
			switch($fieldtype) {
				case 'T':
				case 'D':
				case 'I':
				case 'N':
					$s .= 'right';
				break;
				default:
					$s .= 'left';
				break;
			}
			$s .= "><tt>" . htmlspecialchars($name) . "</tt></th>\n";
		}
		$s .= "\t</tr>\n";

		$rows = 0;
		$numoffset = isset($rs->fields[0]);
		while (!$rs->EOF) {
			$s .= "\t<tr valign=top>\n";

			$s .= "\t\t<td align=right>" . ++$rows . "</td>\n";

			for ($i = 0, $v=($numoffset) ? $rs->fields[0] : reset($rs->fields); 
				$i < $ncols; 
				$i++, $v = ($numoffset) ? @$rs->fields[$i] : next($rs->fields)) {

				$s .= "\t\t<td";

				switch($typearr[$i]) {
					case 'T':
						$s .= '>' . $rs->UserTimeStamp($v,'D d, M Y, h:i:s');
						break;
					case 'D':
						$s .= '>' . $rs->UserDate($v,"D d, M Y");
						break;
					case 'I':
					case 'N':
						$s .= ' align=right>' . stripslashes((trim($v)));
						break;
					default:
//						if ($htmlspecialchars)
							$v = htmlspecialchars($v);
						$s .= '>'. str_replace("\n",'<br />',stripslashes((trim($v))));
				}
				$s .= "&nbsp;</td>\n";
			} // for
			$s .= "\t</tr>\n";

			if ($rows >= ADODB_debug::_maxRows()) {
				$s .= sprintf("Truncated at %s rows (%d rows remaining)\n", ADODB_debug::_maxRows(), $rs->RecordCount() - ADODB_debug::_maxRows());
				break;
			}

			$rs->MoveNext();
		} // while

		$s .= "</table>\n";
		if ($rows)
			$rs->MoveFirst();
		return $s;
	}

	/*!
		\static
	*/
	function rs2html($rs) {
		if (!fbDebug::debugging()) {
			return '';
		}

		$s = ADODB_debug::_rs2html($rs);

		fbDebug::_log($s);
		return $s;
	}

	/*!
		\private
		\static
	*/
	function _maxRows() {
		static $maxRows = 1000;

		if (func_num_args() > 0) {
			$rv = $maxRows;
			$maxRows = func_get_arg(0);
			return $rv;
		}

		return $maxRows;
	}

	/*!
		\static
	*/
	function getMaxRows() {
		return ADODB_debug::_maxRows();
	}

	/*!
		\static
	*/
	function setMaxRows($maxRows) {
		return ADODB_debug::_maxRows($maxRows);
	}

	/*!
		\private
		\static
	*/
	function _rsdump(&$rs) {
		if (!fbDebug::debugging()) {
			return '';
		}

		$s = '';
		if (fbDebug::_level() & FB_DEBUG_SQL == FB_DEBUG_SQL) {
// adodb-mercury.inc.php handles this now		
//			$s .= fbDebug::_dump($rs->sql, 'sql');
		}
		if ((fbDebug::_level() & FB_DEBUG_SELECT) == FB_DEBUG_SELECT) {

			if (fbDebug::_level() & FB_DEBUG_JAVASCRIPT)
				$s .= ADODB_debug::_rs2html($rs);

			if (fbDebug::_level() & (FB_DEBUG_HTML | FB_DEBUG_JAVASCRIPT)) {
				$s .= ADODB_debug::_rs2html($rs);
			} elseif (fbDebug::_level() & (FB_DEBUG_TEXT | FB_DEBUG_LOG)) {
				$s .= ADODB_debug::_rs2text($rs);
			}
		}
		return $s;
	}

	/*!
		\private
		\static
	*/
	function _rsdumpNoSql(&$rs) {
		if (!fbDebug::debugging()) {
			return '';
		}

		$s = '';
		if ((fbDebug::_level() & FB_DEBUG_SELECT) == FB_DEBUG_SELECT) {
			if (fbDebug::_level() & (FB_DEBUG_HTML | FB_DEBUG_JAVASCRIPT)) {
				$s .= ADODB_debug::_rs2html($rs);
			} elseif (fbDebug::_level() & (FB_DEBUG_TEXT | FB_DEBUG_LOG)) {
				$s .= ADODB_debug::_rs2text($rs);
			}
		}

		return $s;
	}

	/*!
		\static
	*/
	function rsdump(&$rs) {
		if (!fbDebug::debugging()) {
			return '';
		}

		$s = '';
		if (fbDebug::_level() & FB_DEBUG_SQL == FB_DEBUG_SQL) {
			$s = fbDebug::_dump($rs->sql, 'sql');
			fbDebug::_log($s);
		}
		if ((fbDebug::_level() & FB_DEBUG_SELECT) == FB_DEBUG_SELECT) {
			if (fbDebug::_level() & (FB_DEBUG_HTML | FB_DEBUG_JAVASCRIPT)) {
				ADODB_debug::rs2html($rs);
			} elseif (fbDebug::_level() & FB_DEBUG_TEXT) {
				ADODB_debug::rs2text($rs);
			} elseif (fbDebug::_level() & FB_DEBUG_LOG) {
				fbDebug::_error_log(fbDebug::_rs2text($rs));
			}
		}

		return $s;
	}

	/*!
		\static
	*/
	function rsdumpNoSql(&$rs) {
		if (!fbDebug::debugging()) {
			return '';
		}

		$s = '';
		if ((fbDebug::_level() & FB_DEBUG_SELECT) == FB_DEBUG_SELECT) {
			if (fbDebug::_level() & (FB_DEBUG_HTML | FB_DEBUG_JAVASCRIPT)) {
				ADODB_debug::rs2html($rs);
			} elseif (fbDebug::_level() & FB_DEBUG_TEXT) {
				ADODB_debug::rs2text($rs);
			} elseif (fbDebug::_level() & FB_DEBUG_LOG) {
				fbDebug::_error_log(ADODB_debug::_rs2text($rs));
			}
		}

		return $s;
	}

	/*!
		\private
		\static
	*/
	function _sqldump($sql, $fields = false) {
//		global $conn;

/// \todo fixme
//		$conn = $dbTiki;
#require_once MERCURY_LIB . '/common/fbDb.php';
#$conn				= fbDb::conn();

		if (!fbDebug::debugging()) {
			return '';
		}

		$s = '';
		$rs = $conn->execute($sql, $fields);
		if (!$rs) {
			$s .= fbDebug::pre('ErrorMsg="'.$conn->errormsg().'"');
			$s .= fbDebug::_dump($sql, 'sql');
			if ($fields) {
				$s .= fbDebug::_dump($fields, 'fields');
			}
			return $s;
		}
		$s .= ADODB_debug::_rsdump($rs);

		return $s;
	}

	/*!
		\static
	*/
	function sqldump($sql, $fields = false) {
		if (!fbDebug::debugging()) {
			return '';
		}

		$s = ADODB_debug::_sqldump($sql, $fields);
		fbDebug::_log($s);

		return $s;
	}	
}

?>
