<?php

// $CVSHeader: _freebeer/www/error.php,v 1.2 2004/03/07 17:51:33 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

/// \todo flush out

defined('FREEBEER_BASE') || define('FREEBEER_BASE', getenv('FREEBEER_BASE') ? getenv('FREEBEER_BASE') :
	dirname(dirname(__FILE__)));

require_once FREEBEER_BASE . '/lib/HTTP/Status.php';

$redirect_status = isset($_SERVER['REDIRECT_STATUS']) ? $_SERVER['REDIRECT_STATUS'] : 500;

$header_status = fbHTTP_Status::getStatusName($redirect_status);

if (!$header_status) {
	$redirect_status = 500;
	$header_status = fbHTTP_Status::getStatusName($redirect_status);
}

$protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';

$title = $redirect_status . ' ' . $header_status;
header($protocol . ' ' . $title);

?>
<html>
<!-- $CVSHeader: _freebeer/www/error.php,v 1.2 2004/03/07 17:51:33 ross Exp $ -->
<head>
<title>$title</title>
</head>
<body>
<?php

echo '<pre>';

echo $title,"\n\n";

echo '$_SERVER=';
asort($_SERVER);
print_r($_SERVER);

?>
</body>
</html>
