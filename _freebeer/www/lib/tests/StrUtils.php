<?php

// $CVSHeader: _freebeer/www/lib/tests/StrUtils.php,v 1.1.1.1 2004/01/18 00:12:08 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') : 
	dirname(dirname(dirname(dirname(__FILE__)))));

$test_name = '/lib/StrUtils.js';

include_once FREEBEER_BASE . '/www/lib/tests/_header.php';

?>
<script language="JavaScript" type="text/javascript" src="<?php echo $test_name ?>"></script>
<script language="JavaScript" type="text/javascript">
<!-- // <![CDATA[

function test_escape_1() {
//	var rv = StrUtils.escape('a"b', '"', '\\');
//	assertTrue('a\\"b', rv);
}

function test_isBlank_1() {
	var rv = StrUtils.isBlank(' not blank');
	assertEquals(false, rv);
}

function test_isBlank_2() {
	var rv = StrUtils.isBlank(' ');
	assertEquals(true, rv);
}

function test_isBlank_3() {
	var rv = StrUtils.isBlank('');
	assertEquals(true, rv);
}

function test_isBlank_4() {
	var rv = StrUtils.isBlank(null);
	assertEquals(true, rv);
}

function test_isNull_1() {
	var rv = StrUtils.isNull(' not blank');
	assertEquals(false, rv);
}

function test_isNull_2() {
//	var rv = StrUtils.isNull(' ');
//	assertEquals(false, rv);
}

function test_isNull_3() {
	var rv = StrUtils.isNull('');
	assertEquals(true, rv);
}

function test_isNull_4() {
	var rv = StrUtils.isNull(null);
	assertEquals(true, rv);
}

function test_lpad_1() {
	var rv = StrUtils.lpad('abc', 4, ' ');
	assertEquals(' abc', rv);
}

function test_lpad_2() {
	var rv = StrUtils.lpad(' abc', 5, ' ');
	assertEquals('  abc', rv);
}

function test_lpad_3() {
	var rv = StrUtils.lpad('abc', 3, ' ');
	assertEquals('abc', rv);
}

function test_lpad_4() {
	var rv = StrUtils.lpad('abcd', 3, ' ');
	assertEquals('abcd', rv);
}

function test_ltrim_1() {
	var rv = StrUtils.ltrim(' bcd ');
	assertEquals('bcd ', rv);
}

function test_ltrim_2() {
	var rv = StrUtils.ltrim('abcd ');
	assertEquals('abcd ', rv);
}

function test_ltrim_3() {
	var rv = StrUtils.ltrim('===bcd=', '=');
	assertEquals('bcd=', rv);
}

function test_ltrim_4() {
	var rv = StrUtils.ltrim('abcd=', '=');
	assertEquals('abcd=', rv);
}

function test_repeat_1() {
	var rv = StrUtils.repeat(' ', 5);
	//            12345
	assertEquals('     ', rv);
}

function test_repeat_2() {
	var rv = StrUtils.repeat(' ', 0);
	//            12345
	assertEquals('', rv);
}

function test_repeat_3() {
	var rv = StrUtils.repeat(' ', -1);
	//            12345
	assertEquals('', rv);
}

function test_rpad_1() {
	var rv = StrUtils.rpad('abc', 4, ' ');
	assertEquals('abc ', rv);
}

function test_rpad_2() {
	var rv = StrUtils.rpad('abc ', 5, ' ');
	assertEquals('abc  ', rv);
}

function test_rpad_3() {
	var rv = StrUtils.rpad('abc', 3, ' ');
	assertEquals('abc', rv);
}

function test_rpad_4() {
	var rv = StrUtils.rpad('abcd', 3, ' ');
	assertEquals('abcd', rv);
}

function test_rtrim_1() {
	var rv = StrUtils.rtrim(' bcd ');
	assertEquals(' bcd', rv);
}

function test_rtrim_2() {
	var rv = StrUtils.rtrim(' abcd');
	assertEquals(' abcd', rv);
}

function test_rtrim_3() {
	var rv = StrUtils.rtrim('=bcd===', '=');
	assertEquals('=bcd', rv);
}

function test_rtrim_4() {
	var rv = StrUtils.rtrim('=abcd', '=');
	assertEquals('=abcd', rv);
}

function test_trim_1() {
	var rv = StrUtils.trim(' bcd ');
	assertEquals('bcd', rv);
}

function test_trim_2() {
	var rv = StrUtils.trim(' abcd');
	assertEquals('abcd', rv);
}

function test_trim_3() {
	var rv = StrUtils.trim('=bcd===', '=');
	assertEquals('bcd', rv);
}

function test_trim_4() {
	var rv = StrUtils.trim('=abcd', '=');
	assertEquals('abcd', rv);
}

function test_trim_5() {
	var rv = StrUtils.trim('abcd');
	assertEquals('abcd', rv);
}

function test_trim_6() {
	var rv = StrUtils.trim('abcd', '=');
	assertEquals('abcd', rv);
}

// ]]> -->
</script>
<?php

include_once FREEBEER_BASE . '/www/lib/tests/_footer.php';

?>
