<?php

// $CVSHeader: _freebeer/www/demo/adodb-session-demo.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $

error_reporting(2047);

class Http {
	function getRequestVar($key, $default = null) {
		if (isset($_REQUEST[$key]) && !is_null($_REQUEST[$key])) {
			return $_REQUEST[$key];
		}

		return $default;
	}

	function noCache() {
		header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');				// Oldest date, always expired
		header('Last-Modified: Tue, 19 Jan 2038 03:14:07 GMT');			// Newest date, always modified
		header('Cache-Control: no-store, no-cache, must-revalidate');	// HTTP/1.1
		header('Cache-Control: post-check=0, pre-check=0', false);		// HTTP/1.1
		header('Pragma: no-cache');										// HTTP/1.0
	}

}

function html_header($title) {
echo <<<EOF
<html lang='en-US' xmlns='http://www.w3.org/1999/xhtml'>
<head>
<title>$title</title>
</head>
<body>
EOF;
}

if (!function_exists('scandir')) {
	function scandir($directory, $sorting_order = 0) {
		$files = array();
		$fh = opendir($directory);
		if (!$fh) {
			return false;
		}
		while (($filename = readdir($fh)) !== false) {
			array_push($files, $filename);
		}
		closedir($fh);
		if ($sorting_order) {
			rsort($files);
		} else {
			sort($files);
		}
		return $files;
	}
}

class ADODB {
	function getDrivers() {
		$dir = ADODB_DIR . '/drivers';
		if (!is_dir($dir)) {
			return false;
		}

		$files = scandir($dir);

		if (!$files) {
			return false;
		}

		$rv = array();
		foreach($files as $file) {
			if (preg_match('/adodb-(.*)\.inc\.php/i', $file, $matches)) {
				$rv[$matches[1]] = $file;
			}
		}

		return $rv;
	}
}

$dirs = array(
	'../../lib/adodb',
	'../../opt/adodb',
	'.',
	'..',
);

foreach ($dirs as $dir) {
	if (!defined('_ADODB_LAYER')) {
		if (is_file($dir . '/adodb.inc.php')) {
			include_once $dir . '/adodb.inc.php';
			break;
		}
	}
}

$dirs[] = ADODB_DIR;

foreach ($dirs as $dir) {
	if (is_file($dir . '/adodb-session.php')) {
		@define('ADODB_SESSION_CODE_DIR', $dir);
		break;
	}
}

include_once ADODB_SESSION_CODE_DIR . '/adodb-session.php';

$title = 'ADODB Sessions';

ob_start();

// to test the emulation code:
// require_once ADODB_SESSION_CODE_DIR . '/adodb-session-clob.php';
// require_once ADODB_SESSION_CODE_DIR . '/adodb-cryptsession.php';

// to test the original code:
// require_once ADODB_DIR . '/adodb-session.php';
// require_once ADODB_DIR . '/adodb-session-clob.php';
// require_once ADODB_DIR . '/adodb-cryptsession.php';

$gzip			= (int) Http::getRequestVar('gzip');
$bzip2			= (int) Http::getRequestVar('bzip2');
$md5			= (int) Http::getRequestVar('md5');
$mcrypt			= (int) Http::getRequestVar('mcrypt');
$secret			= (int) Http::getRequestVar('secret');

$filters = array();
if ($gzip) {
	if (include_once('adodb/adodb-compress-gzip.php')) {
		$filters[] = &new ADODB_Compress_Gzip();
	}
}
if ($bzip2) {
	if (include_once('adodb/adodb-compress-bzip2.php')) {
		$filters[] = &new ADODB_Compress_Bzip2();
	}
}
if ($md5) {
	if (include_once('adodb/adodb-encrypt-md5.php')) {
		$filters[] = &new ADODB_Encrypt_Md5();
	}
}
if ($mcrypt) {
	if (include_once('adodb/adodb-encrypt-mcrypt.php')) {
		$filters[] = &new ADODB_Encrypt_MCrypt();
	}
}
if ($secret) {
	if (include_once('adodb/adodb-encrypt-secret.php')) {
		$filters[] = &new ADODB_Encrypt_Secret();
	}
}

// ADODB_Session::filter($filters);

// required for Opera 7.x
Http::noCache();

$adodb_drivers = adodb::getDrivers();

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
	$lifetime			= ADODB_Session::lifetime();
	$optimize			= ADODB_Session::optimize();
	$sync_seconds		= ADODB_Session::syncSeconds();
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
$defaults['odbc_oracle']	= $defaults['oracle'];

$driver			= 'mysqlt';

extract($defaults[$driver]);

$driver			= Http::getRequestVar('driver',				$driver);

if (isset($defaults[$driver])) {
	extract($defaults[$driver]);
}

$clob				= Http::getRequestVar('clob',					$clob);
$data_field_name	= Http::getRequestVar('data_field_name',		$data_field_name);
$database			= Http::getRequestVar('database',				$database);
$debug				= (int) Http::getRequestVar('debug',			$debug);
$expire_notify		= (int) Http::getRequestVar('expire_notify',	$expire_notify);
$host				= Http::getRequestVar('host',					$host);
$lifetime			= (int) Http::getRequestVar('lifetime',		$lifetime);
$optimize			= (int) Http::getRequestVar('optimize',		$optimize);
$password			= Http::getRequestVar('password',				$password);
$sync_seconds		= (int)Http::getRequestVar('sync_seconds',	$sync_seconds);
$table				= Http::getRequestVar('table',				$table);
$user				= Http::getRequestVar('user',					$user);

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

session_start();

$register = true;

if (!empty($_REQUEST['submit'])) {
	switch ($_REQUEST['submit']) {
		case 'Change Driver':
		case 'Delete Session':
			$_SESSION = array();
			setcookie(session_name(), '', (time() - 2592000), '/', '', 0);
			session_destroy();
			$register = false;
			break;

		default:
	}
}

if ($register) {
	session_register('avar');
	session_register('bigvar');
}

echo html_header($title);

if ($register) {
	if (!isset($_SESSION['avar'])) {
		$_SESSION['avar'] = 0;
	}

	$_SESSION['avar']++;
	$_SESSION['bigdata'] = str_repeat('A', 10000);

	echo "\$_SESSION['avar']={$_SESSION['avar']}<br />\n";
	echo "\$_SESSION['bigdata']={$_SESSION['bigdata']}<br />\n";
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
$CVSHeader: _freebeer/www/demo/adodb-session-demo.php,v 1.1.1.1 2004/01/18 00:12:07 ross Exp $
</address>

</body>
</html>
