<?php

// $CVSHeader: _freebeer/www/demo/Hmac_Login.ADOdb.php,v 1.3 2004/03/08 04:29:18 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

// \todo fix wget's continuously reporting "End of file while parsing headers."
if (preg_match('/wget/i', $_SERVER['HTTP_USER_AGENT'])) {
	echo "<html><body></body></html>";
	exit(0);
}

require_once './_demo.php';

$title = 'fbHMAC_Login_ADOdb Class (Secure Challenge/Response Login)';

require_once FREEBEER_BASE . '/lib/ErrorHandler.php';
require_once FREEBEER_BASE . '/lib/HMAC_Login/ADOdb.php';

fbErrorHandler::init();

echo html_header_demo($title,  null, array('../opt/pajhome.org.uk/md5.js', '../lib/StrUtils.js'));

$hmac_login = &new fbHMAC_Login_ADOdb();

/// \todo add as fields on from with a connect button
if (!$hmac_login->connect('localhost', 'root', '', 'hmac_login', 'mysql')) {
	echo $hmac_login->getLastError();
	exit;
}

// $hmac_login->_dbh->debug = true;

$challenge = $hmac_login->getChallenge();

if (!$challenge) {
	echo $hmac_login->getLastError();
	exit;
}

$hchallenge = htmlspecialchars($challenge);

?>
<script type="text/javascript" language="JavaScript" src="../opt/pajhome.org.uk/md5.js"></script>
<script type="text/javascript" language="JavaScript" src="../lib/StrUtils.js"></script>
<script type="text/javascript" language="JavaScript">
<!-- // <![CDATA[
function form_onsubmit(f) {
	f['login'].value	= StrUtils.trim(f['login'].value);
	f['response'].value = hex_hmac_md5(f['challenge'].value, f['password'].value);
	f['password'].value = '';
	return true;
}
// ]]> -->
</script>
<noscript>
To view this page, you will need to enable JavaScript.
</noscript>
<form method="post" action="<?php echo dirname($_SERVER['PHP_SELF']); ?>/hmac_login/server_adodb.php" onsubmit="form_onsubmit(this);">
Enter login:
<input type="text" name="login" value="login" />
<br />
The correct login is 'login' without the quotes.
<br />
Enter password:
<input type="text" name="password" value="password" />
<br />
The correct password is 'password' without the quotes.
<br />
Please log in within 10 seconds.
<br />
<input type="submit" value="<?php echo 'Login' ?>" />
<input type="hidden" name="challenge" value="<?php echo $hchallenge; ?>" />
<input type="hidden" name="response" value="" />
</form>
<?php echo strftime('%c'); ?>
<p>
See
<br />
<a target='_blank' href='http://pajhome.org.uk/crypt/md5/chaplogin.html'>Paj's Home: Cryptography: JavaScript MD5: CHAP Login</a>
</p>

<address>
$CVSHeader: _freebeer/www/demo/Hmac_Login.ADOdb.php,v 1.3 2004/03/08 04:29:18 ross Exp $
</address>

</body>
</html>
