<?php

// $CVSHeader: _freebeer/www/lib/tests/MathUtils.php,v 1.1 2004/03/08 04:29:18 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') : 
	dirname(dirname(dirname(dirname(__FILE__)))));

$test_name = '/lib/MathUtils.js';

include_once FREEBEER_BASE . '/www/lib/tests/_header.php';

?>
<script language="JavaScript" type="text/javascript" src="<?php echo $test_name ?>"></script>
<script language="JavaScript" type="text/javascript">
<!-- // <![CDATA[

function test_mathutils_round() {
	var data = 123.456;
	var expected = 123.46;
	var rv = MathUtils.round(data);

	assertEquals(expected, rv);
}

function test_mathutils_round_0() {
	var data = 123.456;
	var expected = 123;
	var rv = MathUtils.round(data, 0);

	assertEquals(expected, rv);
}

function test_mathutils_round_2() {
	var data = 123.456;
	var expected = 123.46;
	var rv = MathUtils.round(data, 2);

	assertEquals(expected, rv);
}

function test_mathutils_round__1() {
	var data = 123.456;
	var expected = 120;
	var rv = MathUtils.round(data, -1);

	assertEquals(expected, rv);
}

function test_mathutils_round_4() {
	var data = 123.456;
	var expected = 123.456;
	var rv = MathUtils.round(data, 4);

	assertEquals(expected, rv);
}

// ]]> -->
</script>
<?php

include_once FREEBEER_BASE . '/www/lib/tests/_footer.php';

?>
