#!/bin/sh

# $CVSHeader: _freebeer/bin/getapps/cvs.sh,v 1.2 2004/01/18 02:40:51 ross Exp $

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

# cygwin doesn't appear to support pushd/popd
CWD=`pwd`
#pushd $FREEBEER_BASE/var/tmp
cd $FREEBEER_BASE/var/tmp

rm -fr $DIR ${DIR}_docs
while [ true ];
do
	cvs -z3 -d$CVS_ROOT export -r $CVS_TAG -d $DIR ${DIR}$CVS_SUBDIR && break
	sleep 10
done

. $FREEBEER_BASE/bin/getapps/cvsimport.sh

cd $CWD
#popd
