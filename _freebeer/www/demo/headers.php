<?php

// $CVSHeader: _freebeer/www/demo/headers.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

// Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See doc/license.txt for details.

require_once './_demo.php';

$html_header = html_header_demo('HTTP Cache Headers tester', null, null, false);

if (preg_match('/wget/i', $_SERVER['HTTP_USER_AGENT'])) {
	echo "</body></html>";
	exit(0);
}

$headers = array(
	'Expires: Thu, 01 Jan 1970 00:00:00 GMT',
	'Last-Modified: Tue, 19 Jan 2038 03:14:07 GMT',
	'Cache-Control: no-store, no-cache, must-revalidate',
	'Cache-Control: post-check=0, pre-check=0',
	'Pragma: no-cache',
);

$submit_headers = array();

//$_REQUEST['h'] = '0';

if (isset($_REQUEST['h'])) {
	$h = $_REQUEST['h'];
	if (strpos($h, ',')) {
		$h = split(',', $h);
	} else {
		$h = array($h);
	}

	foreach ($h as $n) {
		$submit_headers[] = $headers[$n];
	}
}

if (@$_REQUEST['headers']) {
	$submit_headers = $_REQUEST['headers'];
}

function toRFC1123($time = null) {
	if (is_null($time)) {
		$time = time();
	}
	
	return gmdate('D, d M Y H:i:s \G\M\T', $time);
}

$xheader = 'Expires: ' . toRFC1123(time() + 5);

if (@$_REQUEST['x']) {
	$submit_headers = array($xheader);
}

foreach($submit_headers as $header) {
	header($header);
}

//session_start();
//@$_SESSION['counter']++;

$headers_html = '';
foreach ($headers as $header) {
	$headers_html .= "<option value=\"$header\">$header</option>\n";
}

$body_text = "<pre>Headers submitted:\n" . join("\n", $submit_headers);
$body_text .= "\n" . strftime('%c') . "\n";
$body_text .= "\n" . $_SERVER['UNIQUE_ID'] . "\n";

$body_text .= @"This page has been viewed {$_SESSION['counter']} times in this session\n";

$body_text .= "<a href=\"{$_SERVER['PHP_SELF']}\">{$_SERVER['PHP_SELF']}</a>\n";

$body_text .= "<a href=\"{$_SERVER['PHP_SELF']}?x=1\">$xheader</a>\n";

$n = 0;
foreach ($headers as $header) {
	$body_text .= "<a href=\"{$_SERVER['PHP_SELF']}?h={$n}\">$header</a>\n";
	++$n;
}

echo $html_header;

echo $body_text;

?>

<form method="post" name='frm_headers'>
<table>

<tr>
<td>
&nbsp;
</td>
<td>
<select size="5" name="headers[]" multiple="multiple">
<? echo $headers_html ?>
</select>
</td>
</tr>
<tr>

<tr>
<td>
Text 1:
</td>
<td>
<input type="text" name="txt_field1" value="" />
</td>
</tr>
<tr>


<tr>
<td>
Text 2:
</td>
<td>
<input type="text" name="txt_field2" value="" />
</td>
</tr>
<tr>

<tr>
<td>
&nbsp;
</td>
<td>
<input type="submit" value="Submit" name='btn_submit' />
</td>
</tr>
<tr>

</table>
</form>

<?php print_r($_SERVER); ?>
<?php /* phpinfo(INFO_MODULES) */ ?>

<address>
$CVSHeader: _freebeer/www/demo/headers.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>

</body>
</html>
