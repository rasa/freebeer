<?php

// $CVSHeader: _freebeer/www/demo/index.php,v 1.2 2004/03/07 17:51:33 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once './_demo.php';

echo html_header_demo();

$www_dir = dirname($_SERVER['SCRIPT_NAME']);

$dh = opendir(dirname(__FILE__)); // /todo fixme
$files = array();
while ($file = readdir($dh)) {
	if (!preg_match('/\.php$/', $file) ||
		preg_match('/^index\.php$/', $file) ||
		preg_match('/^_.*\.php$/', $file)) {
		continue;
	}
	$files[] = $file;
}
closedir($dh);

sort($files);

$body_text = '';

foreach ($files as $file) {
	$body_text .= sprintf("<a href=\"%s\">%s</a><br />\n", $www_dir . '/' . $file, $file);
}

echo $body_text;

?>
<address>
$CVSHeader: _freebeer/www/demo/index.php,v 1.2 2004/03/07 17:51:33 ross Exp $
</address>
</body>
</html>
