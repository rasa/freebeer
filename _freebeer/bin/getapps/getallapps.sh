#!/bin/sh

# $CVSHeader: _freebeer/bin/getapps/getallapps.sh,v 1.1.1.1 2004/01/18 00:12:04 ross Exp $

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

PATH=$FREEBEER_BASE/bin:$FREEBEER_BASE/bin/getapps:$PATH

for file in $FREEBEER_BASE/bin/getapps/apps/*.sh
do
	. $file
done
