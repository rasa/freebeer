#!/bin/sh

# $CVSHeader: _freebeer/bin/cvschangeroot.sh,v 1.2 2004/03/07 17:51:14 ross Exp $

# Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
# Licensed under the BSD or LGPL License. See license.txt for details.

if [ -z "$1" ]; then
        echo "Usage:   $0 CVSROOT"
        echo "Example: $0 :ext:mylogin@cvs.alpine.sourceforge.net:/cvsroot/alpine"
        exit 1
fi

if [ -z "$TEMP" ]; then
	TEMP=/tmp
fi
TMP0=$TEMP/`basename $0`.$$.tmp
echo echo \$2 \>\$1 >$TMP0
chmod +x $TMP0

find . -name 'Root' -exec $TMP0 {} $1 \;
rm -f $TMP0
