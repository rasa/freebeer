#!/bin/sh

# $CVSHeader: _freebeer/bin/lint.sh,v 1.1.1.1 2004/01/18 00:12:03 ross Exp $

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

if [ -z "$PHPCLI" ]; then
	if [ ! -z "`which phpcli`" ]; then
		PHPCLI=`which phpcli`
	else
		PHPCLI=php
	fi
fi

DIRS="bin lib www"

if [ ! -z "$*" ]; then
	DIRS=$*
fi

# cygwin doesn't appear to support pushd/popd
CWD=`pwd`
#pushd $FREEBEER_BASE
cd $FREEBEER_BASE

for dir in $DIRS
do
	find $dir -name '*.php' -exec $PHPCLI -l {} \; |\
	        grep -v "No syntax errors" |\
	        grep -v "Errors parsing" |\
	        grep -v "Cannot redeclare" |\
	        tr -s "\012" |\
	        perl -n -e 'if (m|(.*)\s+(\S+\.php)\s+on\s+line\s+(\d+)|) { print $2,"(",$3,"):\t",$1,"\n"; } else { print };'
done

cd $CWD
#popd
