#!/bin/sh

# $CVSHeader: _freebeer/bin/getapps/wget.sh,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

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

VTAG=$APP

# cygwin doesn't appear to support pushd/popd
CWD=`pwd`
#pushd $FREEBEER_BASE/var/tmp
cd $FREEBEER_BASE/var/tmp

rm -fr $DIR ${APP}_docs

if [ ! -f "$FILE" ]; then
	wget $URL
fi
if [ -n "$FILES" ]; then
	mkdir $DIR
	mv $FILES $DIR
	tar cvzf ${FILE} $DIR
fi
if [ -f "$FILE" ]; then
	if [ `echo "$FILE" | grep "\.zip"` ]; then
		if [ -z "$ZIPDIR" ]; then
			ZIPDIR=.
		fi
		unzip -d $ZIPDIR $FILE
	else
		if [ -n "$ZIPDIR" ]; then
			mkdir $ZIPDIR
			TAROPTS="-C $ZIPDIR"
		fi
		tar $TAROPTS -xvzf $FILE
	fi
fi

. $FREEBEER_BASE/bin/getapps/cvsimport.sh

cd $CWD
#popd
