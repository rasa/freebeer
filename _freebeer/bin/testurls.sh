#!/bin/sh

# $CVSHeader: _freebeer/bin/testurls.sh,v 1.3 2004/03/07 19:32:21 ross Exp $

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
		PHPCLI=php
	fi
fi

pushd $FREEBEER_BASE

$PHPCLI $FREEBEER_BASE/bin/testurls.php

popd
