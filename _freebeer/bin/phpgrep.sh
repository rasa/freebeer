#!/bin/sh

# $CVSHeader: _freebeer/bin/phpgrep.sh,v 1.1.1.1 2004/01/18 00:12:03 ross Exp $

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

DIRS="lib www"

# cygwin doesn't appear to support pushd/popd
CWD=`pwd`
#pushd $FREEBEER_BASE
cd $FREEBEER_BASE

for dir in $DIRS
do
	find $dir -type f -name \*.php | xargs egrep -n $*
done

cd $CWD
#popd
