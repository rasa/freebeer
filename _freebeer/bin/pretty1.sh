#!/bin/sh

# $CVSHeader: _freebeer/bin/pretty1.sh,v 1.2 2004/03/07 17:51:14 ross Exp $

# Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
# Licensed under the BSD or LGPL License. See license.txt for details.

if [ -f `dirname $0`/local.sh ]; then
	. `dirname $0`/local.sh
else
	echo export TRITA_KEY="your-trita-key-here" >`dirname $0`/local.sh
	chmod +x `dirname $0`/local.sh
fi

if [ -z "$TRITA_KEY" ]; then
	echo The \$TRITA_KEY environment variable is not set!
	echo To correct, add your key to `dirname $0`/local.sh, as follows:
	echo export TRITA_KEY="your-trita-key-here" 
	exit 1
fi

if [ -z "$TRITA_HOME" ]; then
	TRITA_HOME="C:\\Progra~1\\trita"
fi

WIN=`cygpath -w $1`
TMP=$1.tmp

rm -fr $TMP
cp -p $WIN $TMP
tritaRunner -integration-use-key $TRITA_KEY -trita-home $TRITA_HOME $WIN
if [ "$?" = "0" ]; then
	touch -r=$TMP $WIN
	rm -f $TMP
else
	mv -f $TMP $WIN
fi
