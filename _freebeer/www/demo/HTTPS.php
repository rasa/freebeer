<?php

// $CVSHeader: _freebeer/www/demo/HTTPS.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/HTTPS.php';

$title = 'fbHTTPS Class (Switch to https:// but leave links as http://)';

$https = &fbHTTPS::getInstance();

//$https->setHttpHost('freebeer2.netebb.com');
//$https->setHttpPort(18080);
//$https->setHttpPath('');

//$https->setHttpsHost('freebeer3.netebb.com');
//$https->setHttpsPort(18443);
//$https->setHttpsPath('');

if (!empty($_REQUEST['http'])) {
	$https->httpMe();
} elseif (!empty($_REQUEST['https'])) {
	$https->httpsMe();
	$https->httpLinkify();
} elseif (!empty($_REQUEST['https_input'])) {
	$https->httpsMe();
	$https->httpLinkify(true);
}

session_start();

if (isset($_SERVER['HTTP_REFERER']) && !isset($_SESSION['fbHTTPS']['HTTP_REFERER'])) {
	$_SESSION['fbHTTPS']['HTTP_REFERER'] = $_SERVER['HTTP_REFERER'];
}

$path = isset($_SESSION['fbHTTPS']['HTTP_REFERER']) ? $_SESSION['fbHTTPS']['HTTP_REFERER'] : '';

echo html_header_demo($title, $path);

echo $https->isHttps() ? 'Secure (HTTPS)' : 'Not secure (HTTP)';
echo '<br />';

$h = $_SERVER['HTTP_HOST'];

$a_hrefs = array();

$delims = array(
	'',
	'\'',
	'"',
);

$urls = array(
	$_SERVER['PHP_SELF'],
	basename($_SERVER['PHP_SELF']),
	dirname($_SERVER['PHP_SELF']),
	dirname($_SERVER['PHP_SELF']) . '/',
	'.',
	'/',
	'',
);

$hosts = array(
	"",
	"http://$h",
	"https://$h",
	"http://$h:80",
	"https://$h:443",
);

$a_hrefs = array();

foreach ($hosts as $host) {
	foreach ($urls as $url) {
		foreach ($delims as $delim) {
			if ($host && substr($url, 0, 1) != '/') {
				continue;
			}
			
			$a_hrefs[] = '<a href=' . $delim . $host . $url . $delim . '>';
/*
			$a_hrefs[] = '<a href=' . $delim . $host . $url . $delim . ' >';
			$a_hrefs[] = '<a href= ' . $delim . $host . $url . $delim . '>';
			$a_hrefs[] = '<a href= ' . $delim . $host . $url . $delim . ' >';

			$a_hrefs[] = '<a href =' . $delim . $host . $url . $delim . '>';
			$a_hrefs[] = '<a href =' . $delim . $host . $url . $delim . ' >';
			$a_hrefs[] = '<a href = ' . $delim . $host . $url . $delim . '>';
			$a_hrefs[] = '<a href = ' . $delim . $host . $url . $delim . ' >';
*/
		}
	}
}

$a_hrefs_html = '';
foreach($a_hrefs as $a_href) {
	$a_hrefs_html .= $a_href . htmlspecialchars($a_href) . "</a><br />\n";
}

if (preg_match('/wget/i', $_SERVER['HTTP_USER_AGENT'])) {
	echo "</body></html>";
	exit(0);
}

$i_srcs = array(
	'<input src="../img/php-small-trans-light.gif" type="image" />',
	'<input src=../img/php-small-trans-light.gif type="image"/>',
	'<input src=../img/php-small-trans-light.gif type="image">',
	'<input src=../img/php-small-trans-light.gif type="image" >',
	'<img src="../img/php-small-trans-light.gif" />',
	'<img src=../img/php-small-trans-light.gif/>',
	'<img src=../img/php-small-trans-light.gif />',
);

$i_srcs_html = '';
foreach($i_srcs as $i_src) {
	$i_srcs_html .= $i_src . ' (' . htmlspecialchars($i_src) . ")<br />\n";
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
<br />
<br />

<?php 
echo $a_hrefs_html;
echo "<br />\n";
echo $i_srcs_html;
?>

</form>

<pre>
<?php
	ksort($_SERVER);
	print_r($_SERVER);
?>
</pre>
<br />
<address>
$CVSHeader: _freebeer/www/demo/HTTPS.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>

</body>
</html>
