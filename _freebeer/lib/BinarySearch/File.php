<?php

// $CVSHeader: _freebeer/lib/BinarySearch/File.php,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

// Copyright (c) 2002-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

/*!
	\file BinarySearch/File.php
	\brief Perform a binary search on a file
*/

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(dirname(__FILE__))));

require_once FREEBEER_BASE . '/lib/BinarySearch.php';

/*!
	\class fbBinarySearch_File
	\brief Perform a binary search on a file

	The file must already be sorted
	
	\todo implement $_load_all parameter
*/
class fbBinarySearch_File {
	/*!
		Constructor
		
		\param $file \c string
		\param $load_all \c bool
		\param $compare_function \c string
		\param $record_length \c int
	*/
	function fbBinarySearch_File($file = null, $load_all = null, $compare_function = null, $record_length = null) {
		if (!is_null($file)) {
			$this->setFile($file);
		}

		if (!is_null($load_all)) {
			$this->setLoadAll($load_all);
		}

		if (!is_null($compare_function)) {
			$this->setCompareFunction($compare_function);
		}

		if (!is_null($record_length)) {
			$this->setReadLength($record_length);
		}
	}

	/*!
		\private
	*/
	var $_cache = false;

	/*!
		\return \c bool
	*/
	function getCache() {
		return $this->_cache;
	}

	/*!
		\param $cache \c bool
		\return \c void
	*/
	function setCache($cache) {
		$this->_cache = (bool) $cache;
	}

	/*!
		\private
	*/
	var $_compare_function	= null;

	/*!
		\private
	*/
	var $_compare_object	= null;

	/*!
		\private
	*/
	var $_compare_method	= null;

	/*!
		\return \c string
	*/
	function getCompareFunction() {
		return $this->_compare_function;
	}

	/*!
		\param $compare_function \c string
		\return \c void
	*/
	function setCompareFunction($compare_function) {
		$this->_compare_function	= $compare_function;
		if (is_array($compare_function)) {
			reset($compare_function);
			$this->_compare_object		= key($compare_function);
			$this->_compare_method		= value($compare_function);
		} else {
			$this->_compare_object		= null;
			$this->_compare_method		= null;
		}
	}

	/*!
		\private
	*/
	var $_file = false;

	/*!
		\return \c string
	*/
	function getFile() {
		return $this->_file;
	}

	/*!
			\param $file \c string
			\return \c void
	
	*/
	function setFile($file) {
		$this->_file = $file;
		$this->_file_size = false;
	}

	/*!
		\private
	*/
	var $_file_size = false;

	/*!
		\return \c int
	*/
	function getFileSize() {
		return $this->_file_size;
	}

	/*!
		\private
	*/
	var $_load_all = false;

	/*!
		\return \c bool
	*/
	function getLoadAll() {
		return $this->_load_all;
	}

	/*!
		\param $load_all \c bool
		\return \c void
	*/
	function setLoadAll($load_all) {
		$this->_load_all = (bool) $load_all;
	}

	/*!
		\private
	*/
	var $_record_number = false;

	/*!
		\return \c int
	*/
	function getRecordNumber() {
		return $this->_record_number;
	}

	/*!
		\private
	*/
	var $_read_length = 4096;

	/*!
		\return \c int
	*/
	function getReadLength() {
		return $this->_read_length;
	}

	/*!
		\param $read_length \c int
	*/
	function setReadLength($read_length) {
		$this->_read_length = (int) $read_length;
	}

	/*!
		\private
	*/
	var $_record_length = null;

	/*!
		\return \c int
	*/
	function getRecordLength() {
		return $this->_record_length;
	}

	/*!
		\param $record_length \c int
		\return \c void
	*/
	function setRecordLength($record_length) {
		$this->_record_length = (int) $record_length;
	}

	/*!
		\param $search_value \c mixed
		\return \c bool
	*/
	function search($search_value) {
		static $result_cache = array();
		static $record_cache = array();

		$len = strlen($search_value);
		if (!$len) {
			return false;
		}

		if (isset($result_cache[$search_value])) {
			return $result_cache[$search_value];
		}
		
		$this->_record_number	= false;

		$file				= $this->_file;
		$compare_function	= $this->_compare_function;
		$compare_object		= $this->_compare_object;
		$compare_method		= $this->_compare_method;

		if (is_null($compare_function)) {
			$compare_function = 'strncmp';
		}

		$track_errors = @ini_set('track_errors', true);
		$php_errormsg = '';

		$fp = fopen($file, 'rb');

		if (!$fp) {
			trigger_error(sprintf('Can\'t open \'%s\': %s', $file, $php_errormsg));
			@ini_set('track_errors', $track_errors);
			return false;
		}

		$rv = false;

//echo "search_value='$search_value'\n";

		do {
			if (!$this->_file_size) {
				$this->_file_size = filesize($file);
			}

			$file_size = $this->_file_size;

			if ($file_size === false) {
				trigger_error(sprintf('Can\'t determine size of \'%s\': %s', $file, $php_errormsg));
				break;
			}

			if (!$this->_record_length) {
				fseek($fp, 0);
				$line = fgets($fp, 1024);
				if ($line === false) {
					trigger_error(sprintf('Can\'t determine record length for \'%s\': %s', $file, $php_errormsg));
					break;
				}

				if (!$line) {
					// php 4.1.2/Win32 bug
					$line = fread($fp, 1024);
					$i = strpos($line, "\n");
					if ($i > 0) {
						$line = substr($line, 0, $i + 1);
					}
				}

				$this->_record_length = strlen($line);

//echo "line='$line'\n";

				if ($this->_record_length == 0) {
					trigger_error(sprintf('No records found in \'%s\'', $file));
					break;
				}

				if (fseek($fp, 0)) {
					trigger_error(sprintf('Can\'t seek on \'%s\': %s', $file, $php_errormsg));
					break;
				}
			}

			$rec_len = $this->_record_length;

//echo "rec_len='$rec_len'\n";

			$high = (int) ($file_size / $rec_len);

//echo "high='$high'\n";

			if ($high == 0) {
				trigger_error(sprintf('No records found in \'%s\'', $file));
				break;
			}

			if ($high <> ($file_size / $rec_len)) {
				trigger_error(sprintf('Invalid record length specified for \'%s\': %d', $file, $rec_len));
				break;			
			}

			$mid = 0;
			$low = 0;
			$found = false;
//@printf("%3d: low=%5s high=%5s mid=%5s found=%s\n", __LINE__, $low, $high, $mid, $found);
			$lastfound = false;
			$lastmid = false;
			$t = 0;
			while ($high >= $low) {
				$mid = ($high + $low) >> 1;

				if ($this->_cache && isset($record_cache[$mid])) {
					$found = $record_cache[$mid];
				} else {
					if (fseek($fp, $mid * $rec_len)) {
						trigger_error(sprintf('Seek error on \'%s\': %s', $file, $php_errormsg));
						break 2;
					}
					if (feof($fp)) {
						trigger_error(sprintf('Seek error on \'%s\': %s', $file, $php_errormsg));
						break 2;
					}

					if ($t > 0) {
						$lastfound	= $found;
						$lastmid	= $mid;
					}
					$found = fread($fp, $rec_len);
//@printf("%3d: low=%5s high=%5s mid=%5s found=%s\n", __LINE__, $low, $high, $mid, $found);

					if (strlen($found) != $rec_len) {
//printf("strlen(found)=%s found=%s rec_len=%s\n", strlen($found), $found, $rec_len);
						trigger_error(sprintf('Read error reading \'%s\': %s', $file, $php_errormsg));
						break 2;
					}

					if ($this->_cache) {
						$record_cache[$mid] = $found;
					}
				}
				if (!is_null($compare_object)) {
					$t = @$compare_object->$compare_method($search_value, $found, $len);
				} else {
					$t = @$compare_function($search_value, $found, $len);
				}
				if ($t < 0) {
					$high = $mid - 1;
					continue;
				} elseif ($t > 0) {
					$low = $mid + 1;
					continue;
				}

				break;
			}
//@printf("%3d: low=%5s high=%5s mid=%5s found=%s\n", __LINE__, $low, $high, $mid, $found);
//print "t=$t lastmid=$lastmid lastfound=$lastfound, found=$found\n";

			if ($t < 0) {
				$this->_record_number	= $lastmid;
				$rv = $lastfound;
			} else {
				$this->_record_number	= $mid;
				$rv = $found;
			}
		} while (false);

		if ($this->_cache && $rv) {
			$result_cache[$search_value] = $rv;
		}

		@fclose($fp);
		@ini_set('track_errors', $track_errors);

		return $rv;
	}

}

?>
