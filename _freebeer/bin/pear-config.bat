:: @echo off

:: $CVSHeader: _freebeer/bin/pear-config.bat,v 1.1.1.1 2004/01/18 00:12:03 ross Exp $

verify other 2>nul
setlocal enableextensions
setlocal enabledelayedexpansion

if "%FREEBEER_BASE%"=="" set FREEBEER_BASE=%~dp0..

if not exist "%FREEBEER_BASE%\lib\System.php" goto nolibfreebeer

set GOPEAR=%PHPRC%\PEAR\go-pear.php

if exist "%GOPEAR%" goto gopear

set GOPEAR=%PHP%\PEAR\go-pear.php

if exist "%GOPEAR%" goto gopear

set GOPEAR=c:\php\PEAR\go-pear.php

if exist "%GOPEAR%" goto gopear

set GOPEAR=c:\php4\PEAR\go-pear.php

if exist "%GOPEAR%" goto gopear

set GOPEAR=c:\program files\php\PEAR\go-pear.php

if exist "%GOPEAR%" goto gopear

goto nopear

:gopear

set DIRS="%FREEBEER_BASE%\opt\pear\bin" "%FREEBEER_BASE%\var\cache\pear" "%FREEBEER_BASE%\opt\pear\data" "%FREEBEER_BASE%\opt\pear\doc" "%FREEBEER_BASE%\opt\pear\ext" "%FREEBEER_BASE%\opt\pear" "%FREEBEER_BASE%\opt\pear\tests"

for %%i in (%DIRS%) do if not exist %%i mkdir %%i

call pear config-set bin_dir	"%FREEBEER_BASE%\opt\pear\bin"
call pear config-set cache_dir	"%FREEBEER_BASE%\var\cache\pear"
call pear config-set data_dir	"%FREEBEER_BASE%\opt\pear\data"
call pear config-set doc_dir	"%FREEBEER_BASE%\opt\pear\doc"
call pear config-set ext_dir	"%FREEBEER_BASE%\opt\pear\ext"
call pear config-set php_dir	"%FREEBEER_BASE%\opt\pear"
call pear config-set test_dir	"%FREEBEER_BASE%\opt\pear\tests"
call pear config-show

goto eof

:nopear

echo Can't find file "PEAR\go-pear.php"

goto eof

:nolibfreebeer

echo Can't find file "%FREEBEER_BASE%\lib\System.php"

echo.
echo Try setting FREEBEER_BASE to be the directory where Freebeer lives.
echo.
echo For example, type:
echo.
echo SET FREEBEER_BASE=c:\path\to\freebeer
echo.
goto eof

:eof
