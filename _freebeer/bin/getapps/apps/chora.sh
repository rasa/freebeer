#!/bin/sh

# $CVSHeader: _freebeer/bin/getapps/apps/chora.sh,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

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

NOW=`date +"%Y%m%d%H%M"`

if [ -z "$CHORA_TAG" ]; then
	CHORA_TAG=HEAD
fi
CVS_TAG=$CHORA_TAG
RTAG=${CVS_TAG}_$NOW

APP=chora
DIR=$APP
CVS_ROOT=:pserver:cvsread:horde@anoncvs.horde.org:/repository
CVS_SUBDIR=

IMPORTDIR=
# DOCDIR="docs"
# DOCFILES="COPYING README"
RMFILES=

. $FREEBEER_BASE/bin/getapps/cvs.sh
