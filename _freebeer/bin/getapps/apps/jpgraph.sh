#!/bin/sh

# $CVSHeader: _freebeer/bin/getapps/apps/jpgraph.sh,v 1.2 2004/03/07 17:51:15 ross Exp $

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

if [ -z "$JPGRAPHVER" ]; then
	JPGRAPHVER=1.14
fi
RTAG=R_`echo $JPGRAPHVER | tr '.' '_'`

# http://members.chello.se/jpgraph/jpgdownloads/jpgraph-1.14.tar.gz

APP=jpgraph
DIR=${APP}-$JPGRAPHVER
FILE=${DIR}.tar.gz
URLROOT=http://members.chello.se/jpgraph/jpgdownloads
IMPORTDIR=src/
# DOCDIR=docs
# DOCFILES="README Changelog Examples"
RMFILES=

URL=$URLROOT/$FILE

. $FREEBEER_BASE/bin/getapps/wget.sh
