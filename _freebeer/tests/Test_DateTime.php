<?php

// $CVSHeader: _freebeer/tests/Test_DateTime.php,v 1.2 2004/03/07 17:51:26 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/lib/DateTime.php';
require_once FREEBEER_BASE . '/lib/System.php';	// putEnv()

class _Test_DateTime extends fbTestCase {

	function _Test_DateTime($name) {
        parent::__construct($name);
	}

	function setUp() {
		$this->locale = setlocale(LC_TIME, 0);

		$rv = setlocale(LC_TIME, 'en');
		$rv || setlocale(LC_TIME, 'en_US');
		$rv || setlocale(LC_TIME, 'English');
		$rv || setlocale(LC_TIME, 'English.United States');

		assert('$rv');
	
		$this->formats = array(
			'%a'	=> 'Sun',
			'%A'	=> 'Sunday',
			'%b'	=> 'May',
			'%B'	=> 'May',
			'%c'	=> 'Sun 06 May 2007 01:03:04 AM GMT',
			'%C'	=> '20',
			'%d'	=> '06',
			'%D'	=> '05/06/07',
			'%e'	=> ' 6',
			'%g'	=> '07',
			'%G'	=> '2007',
			'%h'	=> 'May',
			'%H'	=> '01',
			'%I'	=> '01',
			'%j'	=> '126',
			'%m'	=> '05',
			'%M'	=> '03',
			'%n'	=> "\n",
			'%p'	=> 'AM',
			'%r'	=> '01:03:04 AM',
			'%R'	=> '01:03',
			'%S'	=> '04',
			'%t'	=> "\t",
			'%T'	=> '01:03:04',
			'%u'	=> '7',
			'%U'	=> '18',
			'%V'	=> '18',
			'%W'	=> '18',
			'%w'	=> '0',
			'%x'	=> '05/06/2007',
			'%X'	=> '01:03:04 AM',
			'%y'	=> '07',
			'%Y'	=> '2007',
			'%Z'	=> 'GMT',
			'%%'	=> '%',
		);

		/// \todo determine what is causing these numerous return values in windows
		
		$this->alt_formats[] = array(
			'%c'	=> '05/06/07 01:03:04',
			'%R'	=> '01:03:04',
			'%x'	=> '05/06/07',
			'%X'	=> '01:03:04',
		);
		
		$this->alt_formats[] = array(
			'%c'	=> '5/6/2007 1:03:04 AM',
			'%R'	=> '1:03:04 AM',
			'%x'	=> '5/6/2007',
			'%X'	=> '1:03:04 AM',
		);
	}

	function tearDown() {
		$rv = setlocale(LC_TIME, $this->locale);
		assert('$rv');
	}

	function test_strftime_1() {
		$tz = fbSystem::putEnv('TZ', 'GMT');
		$date = mktime(1, 3, 4, 5, 6, 2007);
		foreach($this->formats as $fmt => $expected) {
			$rv = fbDateTime::strftime($fmt, $date);
			if ($rv != $expected) {
				foreach($this->alt_formats as $alt_format) {
					if (isset($alt_format[$fmt]) && $alt_format[$fmt] == $rv) {
						$expected = $rv;
						break;
					}
				}
			}
			$this->assertEquals($expected, $rv, "fmt='$fmt'");
		}
		fbSystem::putEnv('TZ', $tz);
	}

	/*
			 December 2002 / January 2003 
		ISOWk  M   Tu  W   Thu F   Sa  Su 
		----- ---------------------------- 
		51     16  17  18  19  20  21  22 
		52     23  24  25  26  27  28  29 
		1      30  31   1   2   3   4   5 
		2       6   7   8   9  10  11  12 
		3      13  14  15  16  17  18  19

		// Outputs: 12/28/2002 - %V,%G,%Y = 52,2002,2002 
		print "12/28/2002 - %V,%G,%Y = " . strftime("%V,%G,%Y",strtotime("12/28/2002")) . "\n"; 

		// Outputs: 12/30/2002 - %V,%G,%Y = 1,2003,2002 
		print "12/30/2002 - %V,%G,%Y = " . strftime("%V,%G,%Y",strtotime("12/30/2002")) . "\n"; 

		// Outputs: 1/3/2003 - %V,%G,%Y = 1,2003,2003 
		print "1/3/2003 - %V,%G,%Y = " . strftime("%V,%G,%Y",strtotime("1/3/2003")) . "\n"; 

		// Outputs: 1/10/2003 - %V,%G,%Y = 2,2003,2003 
		print "1/10/2003 - %V,%G,%Y = " . strftime("%V,%G,%Y",strtotime("1/10/2003")) . "\n"; 

			 December 2004 / January 2005 
		ISOWk  M   Tu  W   Thu F   Sa  Su 
		----- ---------------------------- 
		51     13  14  15  16  17  18  19 
		52     20  21  22  23  24  25  26 
		53     27  28  29  30  31   1   2 
		1       3   4   5   6   7   8   9 
		2      10  11  12  13  14  15  16

		// Outputs: 12/23/2004 - %V,%G,%Y = 52,2004,2004 
		print "12/23/2004 - %V,%G,%Y = " . strftime("%V,%G,%Y",strtotime("12/23/2004")) . "\n"; 

		// Outputs: 12/31/2004 - %V,%G,%Y = 53,2004,2004 
		print "12/31/2004 - %V,%G,%Y = " . strftime("%V,%G,%Y",strtotime("12/31/2004")) . "\n"; 

		// Outputs: 1/2/2005 - %V,%G,%Y = 53,2004,2005 
		print "1/2/2005 - %V,%G,%Y = " . strftime("%V,%G,%Y",strtotime("1/2/2005")) . "\n"; 

		// Outputs: 1/3/2005 - %V,%G,%Y = 1,2005,2005 
		print "1/3/2005 - %V,%G,%Y = " . strftime("%V,%G,%Y",strtotime("1/3/2005")) . "\n"; 

	*/

	function test_strftime_20021228_VGY() {
		$date = '12/28/2002';
		$fmt = '%V,%G,%Y';
		$expected = '52,2002,2002';
		$rv = fbDateTime::strftime($fmt, strtotime($date));
		$fmt = str_replace($fmt, '%', '%%');
		$this->assertEquals($expected, $rv, "fmt='$fmt', date='$date'");
	}
	
	function test_strftime_20021230_VGY() {
		$date = '12/30/2002';
		$fmt = '%V,%G,%Y';
		$expected = '01,2003,2002';
		if (fbSystem::platform() == 'windows') {
			$expected = '1,2003,2002';
		}
		$rv = fbDateTime::strftime($fmt, strtotime($date));
		$fmt = str_replace($fmt, '%', '%%');
		$this->assertEquals($expected, $rv, "fmt='$fmt', date='$date'");
	}
	
	function test_strftime_20030103_VGY() {
		$date = '01/03/2003';
		$fmt = '%V,%G,%Y';
		$expected = '01,2003,2003';
		if (fbSystem::platform() == 'windows') {
			$expected = '1,2003,2003';
		}
		$rv = fbDateTime::strftime($fmt, strtotime($date));
		$fmt = str_replace($fmt, '%', '%%');
		$this->assertEquals($expected, $rv, "fmt='$fmt', date='$date'");
	}
	
	function test_strftime_20030110_VGY() {
		$date = '01/10/2003';
		$fmt = '%V,%G,%Y';
		$expected = '02,2003,2003';
		if (fbSystem::platform() == 'windows') {
			$expected = '2,2003,2003';
		}
		$rv = fbDateTime::strftime($fmt, strtotime($date));
		$fmt = str_replace($fmt, '%', '%%');
		$this->assertEquals($expected, $rv, "fmt='$fmt', date='$date'");
	}
	
	function test_strftime_20041223_VGY() {
		$fmt = '%V,%G,%Y';
		$date = '12/23/2004';
		$expected = '52,2004,2004';
		$rv = fbDateTime::strftime($fmt, strtotime($date));
		$fmt = str_replace($fmt, '%', '%%');
		$this->assertEquals($expected, $rv, "fmt='$fmt', date='$date'");
	}

	function test_strftime_20041231_VGY() {
		$fmt = '%V,%G,%Y';
		$date = '12/31/2004';
		$expected = '53,2004,2004';
		$rv = fbDateTime::strftime($fmt, strtotime($date));
		$fmt = str_replace($fmt, '%', '%%');
		$this->assertEquals($expected, $rv, "fmt='$fmt', date='$date'");
	}

	function test_strftime_20050102_VGY() {
		$fmt = '%V,%G,%Y';
		$date = '01/02/2005';
		$expected = '53,2004,2005';
		$rv = fbDateTime::strftime($fmt, strtotime($date));
		$fmt = str_replace($fmt, '%', '%%');
		$this->assertEquals($expected, $rv, "fmt='$fmt', date='$date'");
	}

	function test_strftime_20050103_VGY() {
		$fmt = '%V,%G,%Y';
		$date = '01/03/2005';
		$expected = '01,2005,2005';
		if (fbSystem::platform() == 'windows') {
			$expected = '1,2005,2005';
		}
		$rv = fbDateTime::strftime($fmt, strtotime($date));
		$fmt = str_replace($fmt, '%', '%%');
		$this->assertEquals($expected, $rv, "fmt='$fmt', date='$date'");
	}

	function test_getlongdate_1() {
		$time = strtotime('01/01/2003');
		$rv = fbDateTime::getlongdate($time);
		$expected = 'January 01 2003';
		$this->assertEquals($expected, $rv);
	}

	function test_getshortdate_1() {
		$time = strtotime('01/01/2003');
		$rv = fbDateTime::getshortdate($time);
		$expected = 'Jan 01 03';
		$this->assertEquals($expected, $rv);
	}

	function test_getdateorder_1() {
		$rv = fbDateTime::getdateorder();
		$rv = join(',', $rv);
		$expected = 'm,d,y';
		$this->assertEquals($expected, $rv);
	}

	function test_getlongmonthnames_1() {
		$rv = fbDateTime::getlongmonthnames();
		$rv = join(',', $rv);
		$expected = 'January,February,March,April,May,June,July,August,September,October,November,December';
		$this->assertEquals($expected, $rv);
	}

	function test_getshortmonthnames_1() {
		$rv = fbDateTime::getshortmonthnames();
		$rv = join(',', $rv);
		$expected = 'Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec';
		$this->assertEquals($expected, $rv);
	}

	function test_getlongweekdaynames_1() {
		$rv = fbDateTime::getlongweekdaynames();
		$rv = join(',', $rv);
		$expected = 'Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday';
		$this->assertEquals($expected, $rv);
	}

	function test_getshortweekdaynames_1() {
		$rv = fbDateTime::getshortweekdaynames();
		$rv = join(',', $rv);
		$expected = 'Sun,Mon,Tue,Wed,Thu,Fri,Sat';
		$this->assertEquals($expected, $rv);
	}

}

?>
