#!/bin/sh

# $CVSHeader: _freebeer/bin/getapps/cvs.sh,v 1.4 2004/03/07 19:32:21 ross Exp $

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

pushd $FREEBEER_BASE/var/tmp

# rm -fr $DIR ${DIR}_docs

while [ true ];
do
	cvs -z3 -d$CVS_ROOT export -r $CVS_TAG -d $DIR ${DIR}$CVS_SUBDIR && break
	sleep 10
done

. $FREEBEER_BASE/bin/getapps/cvsimport.sh

popd
