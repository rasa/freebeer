<?php

// $CVSHeader: _freebeer/www/lib/tests/Base64.php,v 1.1.1.1 2004/01/18 00:12:08 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') : 
	dirname(dirname(dirname(dirname(__FILE__)))));

$test_name = '/lib/Base64.js';

include_once FREEBEER_BASE . '/www/lib/tests/_header.php';

?>
<script language="JavaScript" type="text/javascript" src="<?php echo $test_name ?>"></script>
<script language="JavaScript" type="text/javascript">
<!-- // <![CDATA[

function test_base64_encode_1() {
	var data = 'abc';
	var expected = 'YWJj';
	var rv = Base64.encode(data);

	assertEquals(expected, rv);
}

function test_base64_decode_1() {
	var data = 'YWJj';
	var expected = 'abc';
	var rv = Base64.decode(data);
// j = 4
	assertEquals(expected, rv);
}

function test_base64_encode_2() {
	var data = 'abcd';
	var expected = 'YWJjZA==';
	var rv = Base64.encode(data);

	assertEquals(expected, rv);
}

function test_base64_decode_2() {
	var data = 'YWJjZA==';
	var expected = 'abcd';
	var rv = Base64.decode(data);
// j = 2
//	rv = '<?php echo base64_decode('YWJjZA=='); ?>';
//	rv = 'length=' + rv.length + ' ' + rv;
	assertEquals(expected, rv);
}

function test_base64_encode_3() {
	var data = 'abcde';
	var expected = 'YWJjZGU=';
	var rv = Base64.encode(data);

	assertEquals(expected, rv);
}

function test_base64_decode_3() {
	var data = 'YWJjZGU=';
	var expected = 'abcde';
	var rv = Base64.decode(data);
// j = 3
//	rv = '<?php echo base64_decode('YWJjZGU='); ?>';

	assertEquals(expected, rv);
}

// ]]> -->
</script>
<?php

include_once FREEBEER_BASE . '/www/lib/tests/_footer.php';

?>
