#!/bin/sh

# $CVSHeader: _freebeer/bin/test.sh,v 1.1.1.1 2004/01/18 00:12:03 ross Exp $

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

if [ -z "$PHPCLI" ]; then
	if [ ! -z "`which phpcli`" ]; then
		PHPCLI=`which phpcli`
	else
		if [ ! -z "`which php4`" ]; then
			PHPCLI=`which php4`
		else
			PHPCLI=php
		fi
	fi
fi

$PHPCLI $FREEBEER_BASE/lib/tests/index.php
