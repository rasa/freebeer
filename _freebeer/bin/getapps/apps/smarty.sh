#!/bin/sh

# $CVSHeader: _freebeer/bin/getapps/apps/smarty.sh,v 1.3 2004/03/07 17:51:15 ross Exp $

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

NOW=`date +"%Y%m%d%H%M"`

if [ -z "$SMARTY_TAG" ]; then
	SMARTY_TAG=Smarty_2_6_2
fi
CVS_TAG=$SMARTY_TAG
RTAG=${CVS_TAG}_$NOW

APP=smarty
DIR=$APP
CVS_ROOT=:pserver:cvsread:phpfi@cvs.php.net:/repository
CVS_SUBDIR=/libs

IMPORTDIR=
# DOCDIR=
# DOCFILES=
RMFILES=

. $FREEBEER_BASE/bin/getapps/cvs.sh
