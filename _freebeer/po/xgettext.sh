#!/bin/sh

# $CVSHeader: _freebeer/po/xgettext.sh,v 1.2 2004/03/07 17:51:25 ross Exp $

# Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
# Licensed under the BSD or LGPL License. See license.txt for details.

cd `dirname $0`;
perl extract.pl | xgettext --keyword=_ -C --no-location -
