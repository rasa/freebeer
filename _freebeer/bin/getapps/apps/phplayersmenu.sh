#!/bin/sh

# $CVSHeader: _freebeer/bin/getapps/apps/phplayersmenu.sh,v 1.2 2004/03/07 17:51:15 ross Exp $

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

if [ -z "$PHPLAYERSMENUVER" ]; then
	PHPLAYERSMENUVER=3.1.1
fi
RTAG=R_`echo $PHPLAYERSMENUVER | tr '.' '_'`

APP=phplayersmenu
DIR=${APP}-$PHPLAYERSMENUVER
FILE=${DIR}.tar.gz
ZIPDIR=
URLROOT=http://ftp1.sourceforge.net/phplayersmenu/
IMPORTDIR=
# DOCDIR=
# DOCFILES="ACKNOWLEDGEMENTS DOCS CHANGELOG COPYING LICENSE README* PATCHES/README PATCHES/ONCLICK/README PATCHES/SPALLETTI/README"
RMFILES=

URL=$URLROOT/$FILE

. $FREEBEER_BASE/bin/getapps/wget.sh
