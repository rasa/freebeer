#!/bin/sh

# $CVSHeader: _freebeer/bin/getapps/apps/pajhome.sh,v 1.2 2004/03/07 17:51:15 ross Exp $

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

NOW=`date +"%Y%m%dT%H%MZ%z" | tr -d "+"`
NOW=`date +"%Y%m%dT%H%MZ%z"`

if [ -z "$PAJHOMEVER" ]; then
	PAJHOMEVER=$NOW
fi
RTAG=R_$PAJHOMEVER

APP=pajhome
DIR=$APP
ZIPDIR=
FILE=pajhome.org.uk.tgz
URLROOT=http://pajhome.org.uk/crypt/md5/
IMPORTDIR=
DOCDIR=
DOCFILES=
RMFILES=
FILES="md4.js md5.js sha1.js"

URL="$URLROOT/md4.js $URLROOT/md5.js $URLROOT/sha1.js"

. $FREEBEER_BASE/bin/getapps/wget.sh
