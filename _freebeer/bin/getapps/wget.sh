#!/bin/sh

# $CVSHeader: _freebeer/bin/getapps/wget.sh,v 1.4 2004/03/07 19:32:21 ross Exp $

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

VTAG=$APP

pushd $FREEBEER_BASE/var/tmp

# rm -fr $DIR ${APP}_docs

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

popd
