<?php

// $CVSHeader: _freebeer/www/demo/hmac_login/server_mysql.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once '../_demo.php';

$title = 'fbHMAC_Login_MySQL Class (Secure Challenge/Response Login)';

require_once FREEBEER_BASE . '/lib/HMAC_Login/MySQL.php';

$html_header = html_header_demo($title, null, null, false);

require_once FREEBEER_BASE . '/lib/HTTP.php' ;

$client_url = dirname(dirname($_SERVER['PHP_SELF'])) . '/Hmac_Login.MySQL.php';

if (!isset($_REQUEST['challenge'])) {
	fbHTTP::redirect($client_url);
	exit;
}

echo $html_header;

$hmac_login = &new fbHMAC_Login_MySQL();

$hmac_login->setTimeout(10);

if (!$hmac_login->connect('localhost', 'root', '', 'hmac_login')) {
	echo $hmac_login->getLastError();
	exit;
}

// $hmac_login->_dbh->debug = true;

$rv = $hmac_login->validate(
	@$_REQUEST['challenge'],
	@$_REQUEST['response'],
	@$_REQUEST['login'],
	@$_REQUEST['password']	// only passed if JavaScript is turned off on the client
);

$msg = $hmac_login->getLastError();

echo $msg;

?>

<p>
<a href="<?php echo $client_url; ?>">Try again</a>
</p>

<p>
<a href="javascript:history.go(0);">Refresh this page</a>
</p>

<address>
$CVSHeader: _freebeer/www/demo/hmac_login/server_mysql.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>

</body>
</html>
