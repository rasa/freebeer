#!/bin/sh

# $CVSHeader: _freebeer/bin/getapps/apps/phpsniff.sh,v 1.2 2004/03/07 17:51:15 ross Exp $

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

if [ -z "$PHPSNIFF_TAG" ]; then
	PHPSNIFF_TAG=HEAD
fi
CVS_TAG=$PHPSNIFF_TAG
RTAG=${CVS_TAG}_$NOW

APP=phpsniff
DIR=$APP
CVS_ROOT=:pserver:anonymous@cvs.sourceforge.net:/cvsroot/$APP
CVS_SUBDIR=

IMPORTDIR=
# DOCDIR=
# DOCFILES="CHANGES LICENSE"
RMFILES=

. $FREEBEER_BASE/bin/getapps/cvs.sh
