::@echo off

:: $CVSHeader: _freebeer/bin/geo.bat,v 1.1.1.1 2004/01/18 00:12:03 ross Exp $

:: Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
:: Licensed under the BSD or LGPL License. See doc/license.txt for details.

if "%PHP%" == "" set PHP=phpcli.exe

set etc=..\etc\geo

if exist %etc%\geo-ips.txt        del %etc%\geo-ips.txt
if exist %etc%\geo-ips-binary.txt del %etc%\geo-ips-binary.txt

%PHP% -q geo-ip-convert.php %etc%\ips-ascii.txt %etc%\geo-ips.txt
	
%PHP% -q geo-ip-convert.php %etc%\ips-ascii.txt %etc%\geo-ips-binary.txt "%%c%%c%%c%%c%%c%%c%%c%%c%%2s"

:: sort %etc%\geo-ips.txt >%temp%\geo-ips.tmp
:: comp %temp%\geo-ips.tmp %etc%\geo-ips.txt
:: del %temp%\geo-ips.tmp
