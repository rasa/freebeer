::@echo off

:: $CVSHeader: _freebeer/bin/geo.bat,v 1.2 2004/03/07 17:51:14 ross Exp $

:: Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
:: Licensed under the BSD or LGPL License. See license.txt for details.

if "%PHP%" == "" set PHP=phpcli.exe

set etc=..\etc\geo

if exist %etc%\geo-ips.txt        del %etc%\geo-ips.txt
if exist %etc%\geo-ips-binary.txt del %etc%\geo-ips-binary.txt

%PHP% -q geo-ip-convert.php %etc%\ips-ascii.txt %etc%\geo-ips.txt
	
%PHP% -q geo-ip-convert.php %etc%\ips-ascii.txt %etc%\geo-ips-binary.txt "%%c%%c%%c%%c%%c%%c%%c%%c%%2s"

:: sort %etc%\geo-ips.txt >%temp%\geo-ips.tmp
:: comp %temp%\geo-ips.tmp %etc%\geo-ips.txt
:: del %temp%\geo-ips.tmp
