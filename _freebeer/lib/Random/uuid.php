<?php

	error_reporting(2047);
	@set_time_limit(0);
	@ob_implicit_flush(true);
	@ini_set('html_errors', false);
	
	if (!extension_loaded('Win32 API')) {
		trigger_error('The Win32 API extension (php_w32api.dll) is not loaded');
		return false;
	}

	$win32 = new win32;
	assert('!is_null($win32)');

	$win32->registerfunction("int UuidCreateSequential (string &uuid) From rpcrt4.dll");
	$len = 255;                   // set the length your variable should have
	$uuid = str_repeat("\0", $len); // prepare an empty string
	$rv = $win32->UuidCreateSequential($uuid, $len);
	echo "uuid=$uuid\n";
	echo "UuidCreateSequential()=$rv\n";

?>