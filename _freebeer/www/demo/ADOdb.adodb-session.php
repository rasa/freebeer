<?php

// $CVSHeader: _freebeer/www/demo/ADOdb.adodb-session.php,v 1.4 2004/03/08 04:29:18 ross Exp $

// Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
// Licensed under the BSD or LGPL License. See license.txt for details.

require_once './_demo.php';

require_once FREEBEER_BASE . '/lib/ADOdb/ADOdb.php';
require_once FREEBEER_BASE . '/opt/adodb/session/adodb-session.php';

$html_header = html_header_demo('ADODB_Session Class');

// to test the emulation code:
// require_once 'adodb/adodb-session-clob.php';
// require_once 'adodb/adodb-cryptsession.php';

// ADODB_Session::data_field_name('session_data');

// to test the original code:
// require_once ADODB_DIR . '/adodb-session.php';
// require_once ADODB_DIR . '/adodb-session-clob.php';
// require_once ADODB_DIR . '/adodb-cryptsession.php';

$gzip			= (int) fbHTTP::getRequestVar('gzip');
$bzip2			= (int) fbHTTP::getRequestVar('bzip2');
$md5			= (int) fbHTTP::getRequestVar('md5');
$mcrypt			= (int) fbHTTP::getRequestVar('mcrypt');
$secret			= (int) fbHTTP::getRequestVar('secret');

$filters = array();
if ($gzip) {
	if (include_once(ADODB_DIR . '/session/adodb-compress-gzip.php')) {
		$filters[] = &new ADODB_Compress_Gzip();
	}
}
if ($bzip2) {
	if (include_once(ADODB_DIR . '/session/adodb-compress-bzip2.php')) {
		$filters[] = &new ADODB_Compress_Bzip2();
	}
}
if ($md5) {
	if (include_once(ADODB_DIR . '/session/adodb-encrypt-md5.php')) {
		$filters[] = &new ADODB_Encrypt_Md5();
	}
}
if ($mcrypt) {
	if (include_once(ADODB_DIR . '/session/adodb-encrypt-mcrypt.php')) {
		$filters[] = &new ADODB_Encrypt_MCrypt();
	}
}
if ($secret) {
	if (include_once(FREEBEER_BASE . '/lib/ADOdb/adodb-encrypt-secret.php')) {
		$filters[] = &new ADODB_Encrypt_Secret();
	}
}

// ADODB_Session::filter($filters);

$adodb_drivers = fbADOdb::getDrivers();

$clob_choices = array(
	''		=> '',
	'CLOB'	=> 'CLOB',
	'BLOB'	=> 'BLOB',
);

$yesno_choices = array(
	'Yes'	=> 1,
	'No'	=> 0,
);

if (class_exists('ADODB_Session')) {
	$clob				= ADODB_Session::clob();
	$data_field_name	= ADODB_Session::dataFieldName();
	$debug				= (int) ADODB_Session::debug();
	$expire_notify		= ADODB_Session::expireNotify();
	$lifetime			= (int) ADODB_Session::lifetime();
	$optimize			= (int) ADODB_Session::optimize();
	$sync_seconds		= (int) ADODB_Session::syncSeconds();
	$table				= ADODB_Session::table();
}

$defaults = array(
	'mysql' => array(
		'clob'			=> '',
		'database'		=> 'adodb_sessions',
		'host'			=> 'localhost',
		'password'		=> '',
		'user'			=> 'root',
	),
	'oracle' => array(
		'clob'			=> 'CLOB',
		'database'		=> '',
		'host'			=> '',
		'password'		=> 'tiger',
		'user'			=> 'scott',
	),
	'sqlite' => array(
		'clob'			=> '',
		'database'		=> '',
		'host'			=> 'c:\sqlite\sqlite.db',
		'password'		=> '',
		'user'			=> '',
	),
);
$defaults['mysqlt']			= $defaults['mysql'];
$defaults['oci8']			= $defaults['oracle'];
$defaults['oci805']			= $defaults['oracle'];
$defaults['oci8po']			= $defaults['oracle'];

$driver			= 'mysql';

extract($defaults[$driver]);

$driver			= fbHTTP::getRequestVar('driver',				$driver);

if (isset($defaults[$driver])) {
	extract($defaults[$driver]);
}

$clob				= fbHTTP::getRequestVar('clob',					$clob);
$data_field_name	= fbHTTP::getRequestVar('data_field_name',		$data_field_name);
$database			= fbHTTP::getRequestVar('database',				$database);
$debug				= (int) fbHTTP::getRequestVar('debug',			$debug);
$expire_notify		= (int) fbHTTP::getRequestVar('expire_notify',	$expire_notify);
$host				= fbHTTP::getRequestVar('host',					$host);
$lifetime			= (int) fbHTTP::getRequestVar('lifetime',		$lifetime);
$optimize			= (int) fbHTTP::getRequestVar('optimize',		$optimize);
$password			= fbHTTP::getRequestVar('password',				$password);
$sync_seconds		= (int) fbHTTP::getRequestVar('sync_seconds',	$sync_seconds);
$table				= fbHTTP::getRequestVar('table',				$table);
$user				= fbHTTP::getRequestVar('user',					$user);

if (!empty($_REQUEST['submit'])) {
	switch ($_REQUEST['submit']) {
		case 'Change Driver':
			if (isset($defaults[$driver])) {
				extract($defaults[$driver]);
			}
	}
}

// to test the original code
$ADODB_SESSION_CONNECT	= $host;
$ADODB_SESSION_DB		= $database;
$ADODB_SESSION_DRIVER	= $driver;
$ADODB_SESSION_PWD		= $password;
$ADODB_SESSION_TBL		= $table;
$ADODB_SESSION_USER		= $user;
$ADODB_SESSION_USE_LOBS	= $clob;
$ADODB_SESS_DEBUG		= $debug;
$ADODB_SESS_LIFE		= $lifetime;

if ($optimize) {
	define('ADODB_SESSION_OPTIMIZE', $optimize);
}
define('ADODB_SESSION_SYNCH_SECS', $sync_seconds);

if (class_exists('ADODB_Session')) {
	ADODB_Session::clob($clob);
	ADODB_Session::dataFieldName($data_field_name);
	ADODB_Session::database($database);
	ADODB_Session::debug($debug);
	ADODB_Session::driver($driver);
	ADODB_Session::filter($filters);
	ADODB_Session::host($host);
	ADODB_Session::lifetime($lifetime);
	ADODB_Session::optimize($optimize);
	ADODB_Session::password($password);
	ADODB_Session::syncSeconds($sync_seconds);
	ADODB_Session::table($table);
	ADODB_Session::user($user);
}

function NotifyFn($var, $sesskey) {
	echo "NotifyFn($var, $sesskey) called<br />\n";
}

if ($expire_notify) {
	$ADODB_SESSION_EXPIRE_NOTIFY = array('debug', 'NotifyFn');
	if (class_exists('ADODB_Session')) {
		ADODB_Session::expireNotify(array('debug', 'NotifyFn'));
	}
}

$session = false;

if (!empty($_REQUEST['submit'])) {
	switch ($_REQUEST['submit']) {
		case 'Change Driver':
		case 'Delete Session':
		case 'Disconnect':
			session_start();
			$_SESSION = array();
			setcookie(session_name(), '', (time() - 2592000), '/', '', 0);
			session_destroy();
			break;

		case 'Connect':
			session_start();
			$session = true;
			break;

		default:
			$session = true;
			session_start();
	}
}

echo $html_header;

if ($session) {
	session_register('avar');
	session_register('bigvar');
}

if ($session) {
	if (!isset($_SESSION['avar'])) {
		$_SESSION['avar'] = 0;
	}

	$_SESSION['avar']++;
	$_SESSION['bigdata'] = str_repeat('A', 10000);

	echo "\$_SESSION['avar']={$_SESSION['avar']}<br />\n";
	echo "\$_SESSION['bigdata']={$_SESSION['bigdata']}<br />\n";

	session_write_close();
}

?>

<p>
<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<input type="hidden" name="clob"			value="<?php echo $clob ?>" />
<input type="hidden" name="data_field_name"	value="<?php echo $data_field_name ?>" />
<input type="hidden" name="database"		value="<?php echo $database ?>" />
<input type="hidden" name="debug"			value="<?php echo $debug ?>" />
<input type="hidden" name="driver"			value="<?php echo $driver ?>" />
<input type="hidden" name="expire_notify"	value="<?php echo $expire_notify ?>" />
<input type="hidden" name="host"			value="<?php echo $host ?>" />
<input type="hidden" name="lifetime"		value="<?php echo $lifetime ?>" />
<input type="hidden" name="optimize"		value="<?php echo $optimize ?>" />
<input type="hidden" name="password"		value="<?php echo $password ?>" />
<input type="hidden" name="sync_seconds"	value="<?php echo $sync_seconds ?>" />
<input type="hidden" name="table"			value="<?php echo $table ?>" />
<input type="hidden" name="user"			value="<?php echo $user ?>" />

<input type="hidden" name="gzip"			value="<?php echo $gzip ?>" />
<input type="hidden" name="bzip2"			value="<?php echo $bzip2 ?>" />
<input type="hidden" name="md5"				value="<?php echo $md5 ?>" />
<input type="hidden" name="mcrypt"			value="<?php echo $mcrypt ?>" />
<input type="hidden" name="secret"			value="<?php echo $secret ?>" />

<input type="submit" name="submit"	value="Refresh" />

<input type="submit" name="submit"	value="Delete Session" />

</form>
</p>

<form method="get" action="<?php echo $_SERVER['PHP_SELF'] ?>">
<input type="hidden" name="driver"		value="<?php echo $driver ?>" />

<input type="submit" name="submit"	value="Connect" />
&nbsp;
<input type="submit" name="submit"	value="Disconnect" />

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
Database:
</td>
<td>
<input type="text" name="database"	value="<?php echo $database ?>" />
</td>
</tr>

<tr>
<td>
Table:
</td>
<td>
<input type="text" name="table"		value="<?php echo $table ?>" />
</td>
</tr>

<tr>
<td>
Session Data Field Name:
</td>
<td>
<input type="text" name="data_field_name"		value="<?php echo $data_field_name ?>" />
</td>
</tr>

<tr>
<td>
Clob:
</td>
<td>
<select name="clob">
<?php
foreach ($clob_choices as $key => $value) {
	$selected = ($value === $clob) ? 'selected=\'selected\'' : '';
	echo "<option value='$value' $selected>$key</option>\n";
}
?>
</select>
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
Session Lifetime:
</td>
<td>
<input type="text" name="lifetime"		value="<?php echo $lifetime ?>" />
</td>
</tr>

<tr>
<td>
Optimize:
</td>
<td>
<select name="optimize">
<?php
foreach ($yesno_choices as $key => $value) {
	$selected = ($value === $optimize) ? 'selected=\'selected\'' : '';
	echo "<option value='$value' $selected>$key</option>\n";
}
?>
</select>
</td>
</tr>

<tr>
<td>
Expire Notify:
</td>
<td>
<select name="expire_notify">
<?php
foreach ($yesno_choices as $key => $value) {
	$selected = ($value === $expire_notify) ? 'selected=\'selected\'' : '';
	echo "<option value='$value' $selected>$key</option>\n";
}
?>
</select>
</td>
</tr>

<tr>
<td>
Sync Seconds:
</td>
<td>
<input type="text" name="sync_seconds"		value="<?php echo $sync_seconds ?>" />
</td>
</tr>

<tr>
<td>
GZip Compression
<i>(requires Gzip)</i>:
</td>
<td>
<select name="gzip">
<?php
foreach ($yesno_choices as $key => $value) {
	$selected = ($value === $gzip) ? 'selected=\'selected\'' : '';
	echo "<option value='$value' $selected>$key</option>\n";
}
?>
</select>
</td>
</tr>

<tr>
<td>
BZip2 Compression
<i>(requires Bzip2)</i>:
</td>
<td>
<select name="bzip2">
<?php
foreach ($yesno_choices as $key => $value) {
	$selected = ($value === $bzip2) ? 'selected=\'selected\'' : '';
	echo "<option value='$value' $selected>$key</option>\n";
}
?>
</select>
</td>
</tr>

<tr>
<td>
MD5Crypt Encryption:
</td>
<td>
<select name="md5">
<?php
foreach ($yesno_choices as $key => $value) {
	$selected = ($value === $md5) ? 'selected=\'selected\'' : '';
	echo "<option value='$value' $selected>$key</option>\n";
}
?>
</select>
</td>
</tr>

<tr>
<td>
MCrypt Encryption
<i>(requires MCrypt)</i>:
</td>
<td>
<select name="mcrypt">
<?php
foreach ($yesno_choices as $key => $value) {
	$selected = ($value === $mcrypt) ? 'selected=\'selected\'' : '';
	echo "<option value='$value' $selected>$key</option>\n";
}
?>
</select>
</td>
</tr>

<tr>
<td>
Secret Encryption
<i>(requires Horde)</i><br/>
<i>(buggy on Windows)</i>:
</td>
<td>
<select name="secret">
<?php
foreach ($yesno_choices as $key => $value) {
	$selected = ($value === $secret) ? 'selected=\'selected\'' : '';
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
&nbsp;
<input type="submit" name="submit"	value="Connect" />
&nbsp;
<input type="submit" name="submit"	value="Disconnect" />
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

<p>
See
<br />
<a target='_blank' href='http://php.weblogs.com/ADODB'>ADOdb Database Library for PHP</a>
<br />
<a target='_blank' href='http://php.weblogs.com/adodb-sessions'>PHP4 Session Handler using ADOdb</a>

</p>

<address>
$CVSHeader: _freebeer/www/demo/ADOdb.adodb-session.php,v 1.4 2004/03/08 04:29:18 ross Exp $
</address>

</body>
</html>
