#!/bin/sh

# $CVSHeader: _freebeer/bin/getapps/apps/jsrs.sh,v 1.2 2004/03/07 17:51:15 ross Exp $

# Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
# Licensed under the BSD or LGPL License. See license.txt for details.

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

if [ -z "$JSRSVER" ]; then
	JSRSVER=23
fi
RTAG=R_$JSRSVER

APP=jsrs
DIR=$APP
ZIPDIR=$APP
FILE=jsrs${JSRSVER}.zip
URLROOT=http://www.ashleyit.com/rs/jsrs/
IMPORTDIR=
# DOCDIR=
# DOCFILES="*.txt test*.*"
RMFILES=
FILES=

URL=$URLROOT/$FILE

. $FREEBEER_BASE/bin/getapps/wget.sh
