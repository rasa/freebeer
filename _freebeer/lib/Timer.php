<?php

// $CVSHeader: _freebeer/lib/Timer.php,v 1.2 2004/03/07 17:51:17 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

// Copyright (c) 2001-2003, Bold New World, Inc. (http://bnw.com/) All rights reserved.  Used by permission.

/*!
	\file Timer.php
	\brief Microsecond resolution timer (stopwatch) class
*/

/*!
	\class fbTimer
	\brief Microsecond resolution timer (stopwatch) class
*/
class fbTimer {

	/*!
		Last time the time was started
	*/
	var $_start	= false;

	/*!
		Elapsed time so far
	*/
	var $_elapsed	= 0;

	/*!
		Is timer running?
	*/
	var $_running	= false;

	/*!
		\todo rewrite to store seconds and microseconds as separate fields
		so we don't lose precision?
		\return \c double
		\private
		\static
	*/
  	function _now() {
  		list($microsecs, $secs) = explode(' ', microtime());
		return (double) $secs + (double) $microsecs;
	}

	/*!
		\return \c double
	*/
	function elapsed() {
		if ($this->_running) {
			return $this->_elapsed + $this->_now() - $this->_start;
		}

		return $this->_elapsed;
	}

	/*!
		\return \c bool
	*/
	function isRunning() {
		return $this->_running;
	}

	/*!
		\return \c void
	*/
	function reset() {
		$this->_elapsed = 0;
		$this->_start = $this->_now();
	}

	/*!
		\return \c double
	*/
	function start() {
		if (!$this->_running) {
			$this->_start = $this->_now();
			$this->_running = true;
			return $this->_elapsed;
		}

		return $this->_elapsed + $this->_now() - $this->_start;
	}

	/*!
		\return \c double
	*/
	function stop() {
		if ($this->_running) {
			$this->_elapsed += $this->_now() - $this->_start;
		}
		$this->_running = false;

		return $this->_elapsed;
	}

	/*!
		\return \c string
	*/
	function toString() {
		return $this->sprintf('%d:%02d:%010.7f', $this->elapsed());
	}

	/*!
		\param $format \c string
		\param $secs \c double
		\return \c string The time, formatted as hhh:mm:ss.nnnnnnn
		\static
	*/
	function sprintf($format, $secs) {
		$hours		= intval($secs / 3600);
		$remainder	= $secs - $hours * 3600;
		$minutes	= intval($remainder / 60);
		$seconds	= $remainder - $minutes * 60;
		return sprintf($format, $hours, $minutes, $seconds);
	}

	/*!
		\return \c void
		\static
	*/
	function usleep($micro_seconds) {
		// usleep doesn't appear to sleep on Windows
		if (!preg_match('/^win/i', PHP_OS)) {
			if (function_exists('usleep')) {
				usleep($micro_seconds);
				return;
			}
		}
		$t = gettimeofday();
		extract($t);
		$usec += $micro_seconds;
		if ($usec > 1000000) {
			$sec += intval($usec / 1000000);
			$usec %= 1000000;
		}

		do {
			$t = gettimeofday();
		} while ($t['sec'] <= $sec && $t['usec'] <= $usec);
	}
}

?>
