#!/usr/bin/perl -w
# **************************************
# *                                    *
# * test                               *
# *                                    *
# **************************************

do "jsrsServer.pl";

jsrsDispatch("testPerl");

sub testPerl {
	$param1 = $_[0];
	$param2 = $_[1];

	$answer = "Parameter 1 was " . $param1 . " and parameter 2 is " . $param2;
	return $answer;
}