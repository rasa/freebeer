#!/bin/sh

# $CVSHeader: _freebeer/bin/dist.sh,v 1.1.1.1 2004/01/18 00:12:03 ross Exp $

# Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
# Licensed under the BSD or LGPL License. See doc/license.txt for details.

if [ -z "$TAG" ]; then
	TAG=$1
fi

if [ -z "$VER" ]; then
	VER=$2
fi

if [ -z "$VER" ]; then
	echo Usage: $0 tag ver
	exit 1
fi

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

# cygwin doesn't appear to support pushd/popd
CWD=`pwd`
#pushd $FREEBEER_BASE/var/tmp
cd $FREEBEER_BASE/var/tmp

DIR=freebeer_$VER
rm -fr $DIR
cvs -z3 -q export -d $DIR -r $TAG freebeer

CWD2=`pwd`
#pushd $DIR
cd $DIR
make
#popd
cd $CWD2

tar -czf $DIR.tar.gz  $DIR
tar -cjf $DIR.tar.bz2 $DIR
rm -fr $DIR
ls -l

#popd
cd $CWD

