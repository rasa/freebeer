<?php

// $CVSHeader: _freebeer/tests/Test_Timer.php,v 1.2 2004/03/07 17:51:26 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/Timer.php';

class _Test_Timer extends fbTestCase {

	var $_buggy_sprintf = null;
	
	function _Test_Timer($name) {
        parent::__construct($name);
	}

	function setUp() {
	}

	function tearDown() {
	}

	function _usleep($micro_seconds) {
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

	function test_elapsed_1() {
		$o = &new fbTimer();
		$o->start();
		fbTimer::usleep(1000);
		$rv = $o->elapsed();
		$this->assertTrue($rv > .001 && $rv < .1);
	}

	function test_isrunning_1() {
		$o = &new fbTimer();
		$this->assertTrue(!$o->isRunning());
		$o->start();
		$this->assertTrue($o->isRunning());
		$o->stop();
		$this->assertTrue(!$o->isRunning());
		$o->start();
		$this->assertTrue($o->isRunning());
		$o->reset();
		$this->assertTrue($o->isRunning());
		$o->stop();
		$this->assertTrue(!$o->isRunning());
	}

	function test_reset_1() {
		$o = &new fbTimer();
		$o->start();
		fbTimer::usleep(1000);
		$o->reset();
		$this->assertTrue($o->elapsed() < 0.1);
		$this->assertTrue($o->isRunning());
	}

	function test_start_1() {
		$o = &new fbTimer();
		$this->assertTrue(!$o->isRunning());
		$o->start();
		$this->assertTrue($o->isRunning());
	}

	function test_stop_1() {
		$o = &new fbTimer();
		$this->assertTrue(!$o->isRunning());
		$o->start();
		$this->assertTrue($o->isRunning());
		$o->stop();
		$this->assertTrue(!$o->isRunning());
	}

	function test_tostring_1() {
		$o = &new fbTimer();
		$expected = '0:00:00.0000000';
		$this->assertEquals($expected, $o->toString());
		$o->start();
		fbTimer::usleep(1000);
		$this->assertTrue($expected !== $o->toString());
	}

	function test_sprintf_2() {
		$format = '%d:%02d:%06.3f';
		$expected = '0:00:00.000';
		$this->assertEquals($expected, fbTimer::sprintf($format, 0));
	}

	function test_sprintf_1() {
		$format = '%d:%02d:%06.3f';
		$expected = '1:01:01.001';
		$this->assertEquals($expected, fbTimer::sprintf($format, 3661.001));
	}

	function test_usleep_1() {
  		list($start_ms, $start_s) = explode(' ', microtime());
		fbTimer::usleep(1000);
  		list($end_ms, $end_s) = explode(' ', microtime());
		$rv = ($end_s - $start_s) + ($end_ms - $start_ms);
		$this->assertTrue($rv >= 0.001, "rv='$rv'");
	}

}

?>
