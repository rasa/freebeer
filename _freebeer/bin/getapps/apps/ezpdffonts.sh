#!/bin/sh

# $CVSHeader: _freebeer/bin/getapps/apps/ezpdffonts.sh,v 1.2 2004/03/07 17:51:15 ross Exp $

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

if [ -z "$EZPDFVER" ]; then
	EZPDFVER=009e
fi
RTAG=R_$EZPDFVER

DIR=ezpdf
APP=ezpdffonts
ZIPDIR=$DIR
FILE=pdfClassesAndFonts_${EZPDFVER}.zip
URLROOT=http://www.ros.co.nz/pdf
IMPORTDIR=fonts
# DOCDIR=
# DOCFILES="*.txt readme.* *.jpg"
RMFILES=

URL=$URLROOT/$FILE

. $FREEBEER_BASE/bin/getapps/wget.sh
