@echo off

:: $CVSHeader: _freebeer/bin/testjs.bat,v 1.2 2004/03/07 17:51:14 ross Exp $

:: Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
:: Licensed under the BSD or LGPL License. See license.txt for details.

:: \todo find and set FREEBEER_BASE

cd ..\www\opt\jsunit.net

:: \todo rewrite properties as needed in /opt/jsunit.net/jsunit.properties:
::	browserFileNames=C:\\Program Files\\mozilla.org\\Mozilla\\mozilla.exe
::	url=http://localhost/opt/jsunit.net/testRunner.html?testPage=http://localhost/lib/tests/index.php&autoRun=true&submitResults=true
::	logsDirectory=c:\ross\freebeer\var\log\jsunit.net


set CLASSPATH=%CLASSPATH%;.\results\bin\jsunit.jar;.\results\lib\org.mortbay.jetty-jdk1.2.jar;.\results\lib\jdom.jar;.\results\lib\javax.servlet.jar;.\results\lib\junit.jar;.\results\lib\xerces.jar;.\results\lib\junit.jar
java junit.swingui.TestRunner -noloading net.jsunit.StandaloneTest
