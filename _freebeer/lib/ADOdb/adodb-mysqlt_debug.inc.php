<?php

// $CVSHeader: _freebeer/lib/ADOdb/adodb-mysqlt_debug.inc.php,v 1.2 2004/03/07 17:51:17 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

// Copyright (c) 2001-2003, Bold New World, Inc. (http://bnw.com/) All rights reserved.  Used by permission.

/*!
	\file ADOdb/adodb-mysqlt_debug.inc.php
	\brief ADOdb mysqlt debug class
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(__FILE__))));

require_once FREEBEER_BASE . '/lib/Timer.php';

require_once FREEBEER_BASE . '/lib/ADOdb/ADOdb_Debug.php';

require_once ADODB_DIR . '/drivers/adodb-mysqlt.inc.php';

/*!
	\class ADODB_mysqlt_debug
	\brief ADOdb mysqlt debug class

	\static
*/
class ADODB_mysqlt_debug extends ADODB_mysqlt {
	/*!
	*/
	var $databaseType = 'mysqlt_debug';

	/*!
	*/
	var $_timer;
	
	/*!
		\todo move to ADOdb_Debug
	*/
	function _start() {
		if (ADODB_debug::time_sql()) {
			$this->_timer = &new fbTimer();
			$this->_timer->start();
		}
	}

	/*!
		\todo move to ADOdb_Debug
	*/
	function _dumpSql($cmd, $sql, $inputarr) {
		if (fbDebug::_level() & FB_DEBUG_SQL) {
			fbDebug::_log(fbDebug::tt($cmd . "()\n"));
			fbDebug::dump($sql, 'sql');
			if ($inputarr)
				fbDebug::dump($inputarr, 'params');
		}
	}

	/*!
		\todo move to ADOdb_Debug
	*/
	function _explain($sql, $inputarr, $arg3) {
		if (ADODB_debug::explain()) {
			if (preg_match("/^\s*select\s+/i", $sql)) {
				$rs2 = &parent::Execute("explain " . $sql, $inputarr, $arg3);
				if ($rs2) {
					fbDebug::_log(fbDebug::tt("Explanation:\n"));
					ADODB_debug::rsdumpNoSql($rs2);
					$rs2->close();
				}				
			}
		}
	}

	/*!
		\todo move to ADOdb_Debug
	*/
	function _end() {
		if (ADODB_debug::time_sql()) {
			$this->_timer->stop();
		}
	}

	/*!
		\todo move to ADOdb_Debug
	*/
	function _error($cmd, $sql, $inputarr) {
		fbDebug::_log(fbDebug::tt($cmd . "()\n"));
		fbDebug::dump($sql, 'sql');
		if ($inputarr)
			fbDebug::dump($inputarr, 'params');
		trigger_error("Error #" . $this->ErrorNo() . ': ' . $this->ErrorMsg());
	}
	
	/*!
		\todo move to ADOdb_Debug
	*/
	function _elapsed() {
		if (ADODB_debug::time_sql()) {
			$this->_timer->stop();
			fbDebug::log(fbDebug::pre(sprintf("Elapsed time: %s\n", $this->_timer->toString())));
		}
	}

/*
	function &CacheExecute($sql, $inputarr = false, $arg3 = false) {
		$this->_start();
		$rs = &parent::CacheExecute($sql, $inputarr, $arg3);
		if (!$rs) {
			// \todo use adodb-error.inc.php values instead
			// key violations should not be fatal errors
			if ($this->ErrorNo() == 1062) {
				return $rs;
			}
			$this->_error("CacheExecute", $sql, $inputarr);
		}
		$this->_end();
		$this->_dumpSql("CacheExecute", $sql, $inputarr);
		$this->_explain($sql, $inputarr);
		$this->_elapsed();
		ADODB_debug::rsdumpNoSql($rs);
		fbDebug::log(fbDebug::hr());
		return $rs;
	}
*/

	/*!
		\param $sql \c string SQL command to execute
		\param $inputarr \c array optional array of substitution parameters
		\param $arg3 \c mixed unused and reserved
		\return \c mixed recordset if successful, otherwise \c false
	*/
	function &Execute($sql, $inputarr = false, $arg3 = false) {
		$this->_start();
		
		$rs = &parent::Execute($sql, $inputarr, $arg3);
		if (!$rs) {
			// \todo use adodb-error.inc.php values instead
			// key violations should not be fatal errors
			if ($this->ErrorNo() == 1062) {
				return $rs;
			}
			$this->_error("Execute", $sql, $inputarr);
		}
		$affected_rows = $this->Affected_Rows();
		$this->_end();
		$this->_dumpSql("Execute", $sql, $inputarr);
		$this->_explain($sql, $inputarr, $arg3);
		$this->_elapsed();
		ADODB_debug::rsdumpNoSql($rs);

		if ($rs && fbDebug::_level() & FB_DEBUG_SQL) {
			if (preg_match('/^\s*insert\s+|^\s*delete\s+|^\s*update\s+/i', $sql)) {
				fbDebug::dump($affected_rows, 'Affected_Rows');
			}
		}

//		fbDebug::log(fbDebug::hr());
		return $rs;
	}

/*	
	function &SelectLimit($sql,$nrows=-1,$offset=-1, $inputarr=false,$arg3=false,$secs2cache=0) {
		$this->_start();
		$rs = &parent::SelectLimit($sql, $nrows, $offset, $inputarr, $arg3, $secs2cache);
		if (!$rs) {
			# key violations should not be fatal errors
			if ($this->ErrorNo() == 1062) {
				return $rs;
			}
			$this->_error("SelectLimit", $sql, $inputarr);
		}
		$this->_end();
		$this->_dumpSql("SelectLimit", $sql, $inputarr);
		$this->_explain($sql, $inputarr);
		$this->_elapsed();
		ADODB_debug::rsdumpNoSql($rs);
		fbDebug::log(fbDebug::hr());
		return $rs;
	}
*/

	/*!
		\param $table
		\param $field_hash
		\param $auto_quote
		\return \c bool \c true if successful, otherwise \c false
	*/
	function Insert($table, $field_hash, $auto_quote = false) {
		assert('$table');
		assert('is_array($field_hash)');
		
		if (!count($field_hash)) {
			return false;
		}

        $fields = '';
        $values = '';
		foreach ($field_hash as $field => $value) {
			if ($auto_quote && is_string($value)
#				&& (substr($value, 0, 1) != "'" || substr($value, -1) != "'")
				) {
				$value = parent::qstr($value);
			} elseif ($value === null) {
				$value = 'NULL';
			}

			if ($fields) {
				$fields .= ",\n";
				$values .= ",\n";
			}
			$fields .= $field;
			$values .= $value;

			if ($this->debug) {
				$values .= " /* $field */";
			}

        }

		assert('$fields');
		assert('$values');
		
        $sql = "INSERT $table (\n$fields\n) VALUES (\n$values\n)";
#print "<pre>\n";
#print $sql;
#fbDebug::dump($sql, '$sql');
#return true;
		$rs = &$this->Execute($sql);
		return (boolean) $rs;

	} # Insert

	/*!
		\todo move to base class, and subclass in mysql driver
		
		$field_hash = array(
			array(
				'field1' => 'row1_value',
				'field2' => 'row2_value',
				...
			),
			array(
				'field1' => 'row1_value',
				'field2' => 'row2_value',
				...
			),
		 )	
		
		\todo add support for:
		$field_hash = array(
			'field1' => array('row1_value', 'row2_value', ...)
		 	'field2' => array('row1_value', 'row2_value', ...)
		 )	
		 
		\param $table
		\param $rows
		\param $auto_quote
		\return \c bool \c true if successful, otherwise \c false
	*/
	function MultiInsert($table, $rows, $auto_quote = false) {
		assert('$table');
		assert('is_array($field_hash)');
		
		if (!count($rows)) {
			return false;
		}

		$sql_fields = '';
		reset($rows);
		$row = value($rows);
		foreach ($row as $field => $value) {
			if ($sql_fields) {
				$sql_fields .= ",\n";
			}
			$sql_fields .= "\t" . $field;
		}

		$sql_values = '';
		foreach ($rows as $row) {
	        $sql_row = '';
	        assert('count($row)');
			foreach ($row as $field => $value) {
				if ($auto_quote && is_string($value)
	#				&& (substr($value, 0, 1) != "'" || substr($value, -1) != "'")
					) {
					$value = parent::qstr($value);
				} elseif ($value === null) {
					$value = 'NULL';
				}

				if ($sql_row) {
					$sql_row .= ",\n";
				}
				
				$sql_row .= "\t" . $value;
			}
			if ($sql_values) {
				$sql_values .= ",\n";
			}
			$sql_values = '(' . $sql_row . ")\n";
		}

        $sql = "INSERT $table ($sql_fields) VALUES $sql_values";
#print "<pre>\n";
#print $sql;
#fbDebug::dump($sql, '$sql');
#return true;
		$rs = &$this->Execute($sql);
		return (boolean) $rs;
	} # MultiInsert

	/*!
		\param $table
		\param $field_hash
		\param $key_fields
		\param $auto_quote
		\return \c bool \c true if successful, otherwise \c false
	*/
	function Update($table, $field_hash, $key_fields, $auto_quote = false) {
		assert('$table');
		assert('is_array($field_hash)');
		assert('$key_fields');
		
		if (!count($field_hash)) {
			return false;
		}

		if (!is_array($key_fields)) {
			$key_fields = array($key_fields);
		}
		
        $set = '';
		foreach ($field_hash as $field => $value) {
			if (in_array($field, $key_fields)) {
				continue;
			}

			if ($auto_quote && is_string($value)
#				&& (substr($value, 0, 1) != "'" || substr($value, -1) != "'")
				) {
				$value = parent::qstr($value);
			} elseif ($value === null) {
				$value = 'NULL';
			}

			if ($set) {
				$set .= ',';
			}
        	$set .= sprintf("\n\t%-25s\t= %s", $field, $value);
        }

        $where = '';
		foreach ($key_fields as $field) {
			if ($where)
				$where .= ' AND';

        	assert('isset($field_hash[$field])');
			$value = $field_hash[$field];
			if ($auto_quote && is_string($value)
#				&& (substr($value, 0, 1) != "'" || substr($value, -1) != "'")
				) {
				$value = parent::qstr($value);
			} elseif ($value === null) {
				$value = 'NULL';
			}

        	$where .= sprintf("\n\t%-25s\t= %s", $field, $value);
        }

		assert('$set');
		assert('$where');
		
        $sql = "UPDATE $table SET $set\nWHERE $where";
#print "<pre>\n";
#print $sql;
#fbDebug::dump($sql, '$sql');
#return true;
		$rs = &$this->Execute($sql);
		return (boolean) $rs;
	} # update()

	var $_fetch_mode = array();
	
	/*!
		\param $mode
		\return void
	*/
	function pushFetchMode($mode = ADODB_FETCH_ASSOC) {
		global $ADODB_FETCH_MODE;

		array_push($this->_fetch_mode, $ADODB_FETCH_MODE);
		$ADODB_FETCH_MODE = $mode;
	}

	/*!
		\return void
	*/
	function popFetchMode() {
		global $ADODB_FETCH_MODE;
		
		if (count($this->_fetch_mode)) {
			$ADODB_FETCH_MODE = array_pop($this->_fetch_mode);
		}
	}

	/*!
		\param $sql
		\param $inputarr
		\param $force_array
		\param $first2cols
		\return \c bool \c true if successful, otherwise \c false
	*/
	function GetAssoc($sql, $inputarr = false, $force_array = false, $first2cols = false) {
		$rs = &$this->Execute($sql, $inputarr);
		if (!$rs) {
			return false;
		}
		$rv = $rs->GetAssoc($force_array, $first2cols);
		$rs->close();
		return $rv;
	}

}

class ADORecordSet_mysqlt_debug extends ADORecordSet_mysqlt {
	var $databaseType = 'mysqlt_debug';
	
	function ADORecordSet_mysqlt_debug($queryID, $mode = false) {
		return $this->ADORecordSet_mysqlt($queryID, $mode);
	}
}

?>
