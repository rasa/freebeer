#!/bin/sh

# $CVSHeader: CVSROOT/export.sh,v 1.1 2004/01/18 00:10:28 ross Exp $

CWD=`pwd`
cd $*
# Cygwin doesn't support pushd/popd
# pushd $*
if [ -f Makefile ]; then
	make
fi
cd $CWD
#popd

