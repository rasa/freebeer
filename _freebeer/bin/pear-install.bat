::@echo off

:: $CVSHeader: _freebeer/bin/pear-install.bat,v 1.2 2004/03/07 17:51:14 ross Exp $

:: Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
:: Licensed under the BSD or LGPL License. See license.txt for details.

set PACKAGES=Archive_Tar Console_Getopt XML_RPC PEAR XML_Util XML_Parser Config http://pear.php.net/get/PHPUnit-0.6.2.tgz DB Log Date HTML_Form HTML_Common HTML_Table Net_UserAgent_Detect 

if "%FREEBEER_BASE%"=="" set FREEBEER_BASE=%~dp0..

pushd %FREEBEER_BASE%

cd %FREEBEER_BASE%

set PEAR_COMMAND=install

if not "%1" == "" set PEAR_COMMAND=%1

:: Remove 1.0.0-alpha which is PHP 5.0 specific
call pear uninstall PHPUnit

for %%i in (%PACKAGES%) do call pear %PEAR_COMMAND% %%i

popd
