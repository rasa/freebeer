#!/bin/sh

# $CVSHeader: _freebeer/bin/getapps/apps/overlib.sh,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

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

if [ -z "$OVERLIBVER" ]; then
	OVERLIBVER=351
fi
RTAG=R_$OVERLIBVER

APP=overlib
DIR=$APP
ZIPDIR=$APP
FILE=overlib${OVERLIBVER}.zip
URLROOT=http://www.bosrup.com/web/overlib
IMPORTDIR=
DOCDIR=
DOCFILES=
RMFILES=
FILES=

URL=$URLROOT/$FILE

. $FREEBEER_BASE/bin/getapps/wget.sh
