<?php

// $CVSHeader: _freebeer/www/demo/ob_gzhandler.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once './_demo.php';

$title = 'ob_gzhandler() tester';

//require_once FREEBEER_BASE . '/lib/Https.php';
//require_once FREEBEER_BASE . '/lib/RandomBest.php';

//$rng = &fbRandom_Best::bestNonBlockingRNG();
#$data = $rng->nextBase64String(10000);
$data = str_repeat("0", 80) . "\n";

require_once FREEBEER_BASE . '/lib/Timer.php';
$timer = new fbTimer();
$timer->start();

/*
if (!empty($_REQUEST['http'])) {
	fbHTTPS::httpMe();
} elseif (!empty($_REQUEST['https'])) {
	fbHTTPS::httpsMe();
	fbHTTPS::httpLinkify();
} elseif (!empty($_REQUEST['https_input'])) {
	fbHTTPS::httpsMe();
	fbHTTPS::httpLinkify(true);
}
*/

function dumpElapsedCallback($buffer) {
	global $timer;
	$search = '~(</body\s*>)~i';
	$replace = sprintf("Elapsed time: %.6f seconds\n\\1", $timer->elapsed());
	return preg_replace($search, $replace, $buffer);
}

ob_start('ob_gzhandler'); 
#ob_start('dumpElapsedCallback');

#session_start();

#if (isset($_SERVER['HTTP_REFERER']) && !isset($_SESSION['fbHTTPS']['HTTP_REFERER'])) {
#	$_SESSION['fbHTTPS']['HTTP_REFERER'] = $_SERVER['HTTP_REFERER'];
#}

#$path = isset($_SESSION['fbHTTPS']['HTTP_REFERER']) ? $_SESSION['fbHTTPS']['HTTP_REFERER'] : '';
$path = '';

echo html_header_demo($title);

//echo fbHTTPS::isHttps() ? 'Secure (HTTPS)' : 'Not secure (HTTP)';
echo '<br />';

//$rng = &fbRandom_Best::bestNonBlockingRNG();

for ($i = 0; $i < 10000; ++$i) {
	echo "<!--\n";
	echo $data;
	echo "\n-->\n";
}

?>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<input type='submit' name='https' value='https' />
&nbsp;Switch to https://.
<br />
<input type='submit' name='https_input' value='https' />
&nbsp;Switch to https:// and convert &lt;input src="..." /&gt;.
<br />
<input type='submit' name='http'  value='http' />
&nbsp;Switch to http://.
</form>

<br />
<address>
$CVSHeader: _freebeer/www/demo/ob_gzhandler.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>

<?php
#	ob_end_flush();
#	ob_flush();
#while (@ob_end_flush()); 
#ob_start('ob_gzhandler'); 
	echo 'Elapsed time: ',$timer->toString();
?>

</body>
</html>
