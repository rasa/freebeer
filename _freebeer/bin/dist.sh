#!/bin/sh

# $CVSHeader: _freebeer/bin/dist.sh,v 1.3 2004/03/07 19:32:21 ross Exp $

# Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
# Licensed under the BSD or LGPL License. See license.txt for details.

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

pushd $FREEBEER_BASE/var/tmp

DIR=freebeer_$VER
rm -fr $DIR
cvs -z3 -q export -d $DIR -r $TAG freebeer

pushd $DIR
make
popd

tar -czf $DIR.tar.gz  $DIR
tar -cjf $DIR.tar.bz2 $DIR
rm -fr $DIR
ls -l

popd
