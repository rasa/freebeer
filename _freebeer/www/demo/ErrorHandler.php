<?php

// $CVSHeader: _freebeer/www/demo/ErrorHandler.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/ErrorHandler.php';

error_reporting(2047);

fbErrorHandler::init();

echo html_header_demo('fbErrorHandler Class');

echo "<pre>";

class AClass {
	var $a_class_var = 'avar';

	function afunc() {
	}
}

function afunc($a_param, $fp_param, &$aclass_param) {
	$alocalvar = 'alocalvar';
	
	fopen('fail me', 'r');
}

$global = 'a global string';

$aclass = &new AClass();

$fp = fopen('php://stdin', 'r');

class AnotherClass {
	function test($fp_param, &$aclass_param) {
		afunc('aparam', $fp_param, $aclass_param);
	}
}

$an_object = new AnotherClass();

$an_object->test($fp, $aclass);

#AnotherClass::test($fp, $aclass);

?>
</pre>
<address>
<?php echo date('r'); ?> <br />
$CVSHeader: _freebeer/www/demo/ErrorHandler.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>

</body>
</html>
