<?php

// $CVSHeader: _freebeer/www/lib/tests/_example.php,v 1.1.1.1 2004/01/18 00:12:08 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

defined('FREEBEER_BASE') || 
 define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') : dirname(dirname(dirname(dirname(__FILE__)))));

$test_name = '/lib/pajhome.org.uk/md5.js';

include_once FREEBEER_BASE . '/www/lib/tests/_header.php';

?>
<script type="text/javascript" language="JavaScript" src="<?php echo $test_name ?>"></script>
<script language="JavaScript" type="text/javascript">
<!-- // <![CDATA[

function test_hex_hmac_md5_1() {
	var key		= ''; // '0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b0b';
	var data	= "Hi There";
	var digest	= '9294727a3638bb1c13f48ef8158bfc9d';

	for (var i = 0; i < 16; ++i) {
		key += String.fromCharCode(0x0b);
	}

	var rv = hex_hmac_md5(key, data);

	assertEquals(digest, rv);
}

// ]]> -->
</script>
<?php

include_once FREEBEER_BASE . '/www/lib/tests/_footer.php';

?>
