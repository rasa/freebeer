#!/bin/sh

# $CVSHeader: _freebeer/bin/pear-install.sh,v 1.1.1.1 2004/01/18 00:12:03 ross Exp $

# Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
# Licensed under the BSD or LGPL License. See doc/license.txt for details.

# HTML/Calendar.php: Date HTML_Common HTML_Form HTML_Table

# what requires:
#	Net_Socket \
#	Net_SMTP \
#	Mail \
# phpmailer?

PACKAGES="Date \
	DB \
	HTML_Common \
	HTML_Form \
	HTML_Table \
	HTTP \
	HTTP_Header \
	Log \
	Net_Socket \
	Net_SMTP \
	Mail \
	Net_UserAgent_Detect \
	http://pear.php.net/get/PHPUnit-0.6.2.tgz"

# PEAR-1.3b5 requires:
#	Archive_Tar >= 1.1
#	Console_Getopt >= 1.2
#	XML_RPC >= 1.0.4

# fbConfig requires:
#	Config
#	Config 1.10 requires:
# 		XML_Parser
# 		XML_Util

# fbHTML_Calendar requires:
#	Date
#	HTML_Form
#	HTML_Table
#	HTML_Table requires:
#		HTML_Common

# fbHTTP_OutputBuffering requires:
#	Net_UserAgent_Detect

# PHPUnit-1.0.0 requires PHP 5.0.0

PACKAGES="\
		Archive_Tar \
		Console_Getopt \
		XML_RPC \
	PEAR \
		XML_Util \
		XML_Parser \
	Config \
	http://pear.php.net/get/PHPUnit-0.6.2.tgz \
		DB \
	Log \
	Date \
	HTML_Form \
		HTML_Common \
	HTML_Table \
	Net_UserAgent_Detect \
"

if [ -z "$FREEBEER_BASE" ]; then
	FREEBEER_BASE=`dirname $0`
	if [ "$FREEBEER_BASE" = "." ]; then
		FREEBEER_BASE=`pwd`
	fi
	while [ ! -f "$FREEBEER_BASE/lib/System.php" ];
	do
		FREEBEER_BASE=`dirname $FREEBEER_BASE`
	done
fi

cd $FREEBEER_BASE

COMMAND=install

if [ ! -z "$1" ]; then
	COMMAND=$1
fi

# Remove 1.0.0-alpha which is PHP 5.0 specific
pear uninstall PHPUnit

for PACKAGE in $PACKAGES
do
	pear $COMMAND $PACKAGE
done
