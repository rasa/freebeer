<?php

// $CVSHeader: _freebeer/tests/GeoIP/Free/Test_GeoIP_Free_Ascii.php,v 1.3 2004/03/07 17:51:28 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once FREEBEER_BASE . '/tests/_init.php';

require_once FREEBEER_BASE . '/tests/GeoIP/Test_GeoIP_Free.php';

require_once FREEBEER_BASE . '/lib/GeoIP/Free/Ascii.php';

class _Test_GeoIP_Free_Ascii extends _Test_GeoIP_Free {

    function _Test_GeoIP_Free_Ascii($name) {
        parent::__construct($name);
    }

	function test_getCountryIdByIP() {
		foreach($this->ips as $ip => $expected) {
			$rv = fbGeoIP_Free_Ascii::getCountryIdByIP($ip);
			$this->assertEquals($expected, $rv, "ip='$ip'");
		}
	}

	function test_getCountryIdByHostName() {
		foreach($this->hosts as $host => $expected) {
			$rv = fbGeoIP_Free_Ascii::getCountryIdByHostName($host);
			$this->assertEquals($expected, $rv, "host='$host'");
		}
	}

	function test_getCountryNameByIP() {
		foreach($this->ips as $ip => $expected) {
			$rv = fbGeoIP_Free_Ascii::getCountryNameByIP($ip);
			$this->assertTrue($rv, "ip='$ip'");
//			$this->assertEquals($expected, $rv, "ip='$ip'");
		}
	}

	function test_getCountryNameByHostName() {
		foreach($this->hosts as $host => $expected) {
			$rv = fbGeoIP_Free_Ascii::getCountryNameByHostName($host);
			$this->assertTrue($rv, "host='$host'");
//			$this->assertEquals($expected, $rv, "host='$host'");
		}
	}

}

# make PHPUnit_GUI_SetupDecorator() happy
class _GeoIP_Free_Test_GeoIP_Free_Ascii extends _Test_GeoIP_Free_Ascii {
}

?>
