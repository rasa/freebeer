<?php

// $CVSHeader: _freebeer/www/demo/ADOdb.ADOdb.php,v 1.2 2004/03/07 17:51:33 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/Debug.php';
require_once FREEBEER_BASE . '/lib/ErrorHandler.php';
require_once FREEBEER_BASE . '/lib/ADOdb/ADOdb.php';

fbErrorHandler::init();

fbDebug::setLevel(FB_DEBUG_ALL);

echo html_header_demo('fbADOConnection Class');

// echo "<pre>";

$adodb_drivers = fbADOdb::getDrivers();

$yesno_choices = array(
	'Yes'	=> 1,
	'No'	=> 0,
);

$debug = 0;

$defaults = array(
	'mysql' => array(
		'host'			=> 'localhost',
		'password'		=> '',
		'user'			=> 'root',
	),
	'oracle' => array(
		'host'			=> '',
		'password'		=> 'tiger',
		'user'			=> 'scott',
	),
	'sqlite' => array(
		'host'			=> 'c:\sqlite\sqlite.db',
		'password'		=> '',
		'user'			=> '',
	),
);
$defaults['mysqlt']			= $defaults['mysql'];
$defaults['oci8']			= $defaults['oracle'];
$defaults['oci805']			= $defaults['oracle'];
$defaults['oci8po']			= $defaults['oracle'];

$defaults['mysqlt_debug']	= $defaults['mysql'];

$driver			= 'mysqlt';

extract($defaults[$driver]);

$driver			= fbHTTP::getRequestVar('driver',				$driver);

if (isset($defaults[$driver])) {
	extract($defaults[$driver]);
}

$debug				= (int) fbHTTP::getRequestVar('debug',			$debug);
$host				= fbHTTP::getRequestVar('host',					$host);
$password			= fbHTTP::getRequestVar('password',				$password);
$user				= fbHTTP::getRequestVar('user',					$user);

if (!empty($_REQUEST['submit'])) {
	switch ($_REQUEST['submit']) {
		case 'Change Driver':
			if (isset($defaults[$driver])) {
				extract($defaults[$driver]);
			}
	}
}

fbDebug::setLevel(FB_DEBUG_ALL);

?>
<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<input type="hidden" name="debug"			value="<?php echo $debug ?>" />
<input type="hidden" name="driver"			value="<?php echo $driver ?>" />
<input type="hidden" name="host"			value="<?php echo $host ?>" />
<input type="hidden" name="password"		value="<?php echo $password ?>" />
<input type="hidden" name="user"			value="<?php echo $user ?>" />

<input type="submit" name="submit"	value="Refresh" />

</form>

<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<input type="hidden" name="driver"		value="<?php echo $driver ?>" />

<table border="0">
<tr>
<td>
Driver:
</td>
<td>
<select name="driver">
<?php

foreach ($adodb_drivers as $key => $value) {
	$selected = ($key == $driver) ? 'selected=\'selected\'' : '';
	echo "<option value='$key' $selected>$key</option>\n";
}

?>
</select>
</td>
</tr>

<tr>
<td>
Host:
</td>
<td>
<input type="text" name="host"		value="<?php echo $host ?>" />
</td>
</tr>

<tr>
<td>
User:
</td>
<td>
<input type="text" name="user"		value="<?php echo $user ?>" />
</td>
</tr>

<tr>
<td>
Password:
</td>
<td>
<input type="text" name="password"	value="<?php echo $password ?>" />
</td>
</tr>

<tr>
<td>
Debug:
</td>
<td>
<select name="debug">
<?php
foreach ($yesno_choices as $key => $value) {
	$selected = ($value === $debug) ? 'selected=\'selected\'' : '';
	echo "<option value='$value' $selected>$key</option>\n";
}
?>
</select>
</td>
</tr>

<tr>
<td>
&nbsp;
</td>
<td>
<input type="submit" name="submit"	value="Change Parameters" />
</td>
</tr>
</table>
</form>
</p>

<p>
<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<table>
<tr>
<td>
Driver:
</td>
<td>
<select name="driver">
<?php

foreach ($defaults as $key => $value) {
	$selected = ($key == $driver) ? 'selected=\'selected\'' : '';
	echo "<option value='$key' $selected>$key</option>\n";
}

?>
</select>
<input type="submit" name="submit"	value="Change Driver" />

</td>
</tr>
</table>
</form>
</p>

<?php

flush();

$dbh = &fbADONewConnection($driver);
if ($dbh) {
	$dbh->debug = $debug;
	$rv = $dbh->Connect();
	$dbh->selectDB('hmac_login');
//	$rv = $dbh->PConnect($host, $user, $password, 'hmac_login');
}

$rv = $dbh->Execute("select * from challenges");

print "<pre>";
print_r($rv);
?>

<address>
$CVSHeader: _freebeer/www/demo/ADOdb.ADOdb.php,v 1.2 2004/03/07 17:51:33 ross Exp $
</address>

</body>
</html>
