#!/bin/sh
# $CVSHeader: _freebeer/bin/getapps/apps/adodb.sh,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

set -x

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

if [ -z "$ADODBVER" ]; then
	ADODBVER=410
fi
RTAG=R_$ADODBVER

APP=adodb
DIR=$APP
ZIPDIR=
FILE=${APP}${ADODBVER}.tgz
URLROOT=http://phplens.com/lens/dl
IMPORTDIR=
# DOCDIR=
# DOCFILES="*.txt *.htm* tests cute_icons_for_site"
RMFILES="*.zip"

URL=$URLROOT/$FILE

. $FREEBEER_BASE/bin/getapps/wget.sh
