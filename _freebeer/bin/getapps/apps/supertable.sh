#!/bin/sh

# $CVSHeader: _freebeer/bin/getapps/apps/supertable.sh,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

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

NOW=`date +"%Y%m%dT%H%MZ%z" | tr "+" ""`

if [ -z "$SUPERTABLE_TAG" ]; then
	SUPERTABLE_TAG=HEAD
fi
CVS_TAG=$SUPERTABLE_TAG
RTAG=${CVS_TAG}_$NOW

APP=supertable
DIR=$APP
CVS_ROOT=:pserver:anonymous@cvs.sourceforge.net:/cvsroot/$APP
CVS_SUBDIR=

IMPORTDIR=examples
# DOCDIR=
# DOCFILES="../LICENSE.txt ../NEWS.txt ../README.txt ../TODO.txt *.shtml css_js/* Templates/*"

IMPORTDIR=
# DOCDIR=
# DOCFILES="*.txt"

RMFILES=

. $FREEBEER_BASE/bin/getapps/cvs.sh
