#!/bin/sh

# $CVSHeader: _freebeer/bin/test.sh,v 1.2 2004/03/07 17:51:14 ross Exp $

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
