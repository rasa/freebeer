#!/bin/sh

# $CVSHeader: _freebeer/bin/testjs.sh,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

# Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
# Licensed under the BSD or LGPL License. See doc/license.txt for details.

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

cd $FREEBEER_BASE/www/opt/jsunit.net

# /// \todo rewrite properties as needed in /opt/jsunit.net/jsunit.properties:
#	browserFileNames=C:\\Program Files\\mozilla.org\\Mozilla\\mozilla.exe
#	url=http://localhost/opt/jsunit.net/testRunner.html?testPage=http://localhost/lib/tests/index.php&autoRun=true&submitResults=true
#	logsDirectory=c:\ross\freebeer\var\log\jsunit.net

CLASSPATH=$CLASSPATH;./results/bin/jsunit.jar;./results/lib/org.mortbay.jetty-jdk1.2.jar;./results/lib/jdom.jar;./results/lib/javax.servlet.jar;./results/lib/junit.jar;./results/lib/xerces.jar;./results/lib/junit.jar
java junit.swingui.TestRunner -noloading net.jsunit.StandaloneTest