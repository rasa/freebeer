#!/bin/sh

# $CVSHeader: _freebeer/bin/getapps/cvsimport.sh,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

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

VTAG=`echo "$APP" | tr "$,.:;@" "_"`

if [ -d "$DIR" ]; then
	mkdir ${APP}_docs
	cd $DIR
	if [ -n "$DOCDIR" ]; then
		mv -f $DOCDIR ../${APP}_docs
	fi
	if [ -n "$DOCFILES" ]; then
		if [ -n "$IMPORTDIR" ]; then
			cd $IMPORTDIR
			mv -f $DOCFILES ../../${APP}_docs
			cd ..
		else
			mv -f $DOCFILES ../${APP}_docs
		fi
	fi
	cd ..
	if [ -n "$DOCDIR" -o -n "$DOCFILES" ]; then
		cd ${APP}_docs
		cvs import -I ! -m "Import of $URL to _${APP}_docs" _${APP}_docs $VTAG $RTAG
		cd ..
	fi
	cd $DIR/$IMPORTDIR
	if [ -n "$RMFILES" ]; then
		rm -fr $RMFILES
	fi
	cvs import -I ! -m "Import of $URL to _$APP" _$APP $VTAG $RTAG
	cd ..
#	rm -fr $DIR ${APP}_docs
fi
