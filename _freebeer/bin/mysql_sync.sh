#!/bin/sh -x

# $CVSHeader: _freebeer/bin/mysql_sync.sh,v 1.1.1.1 2004/01/18 00:12:03 ross Exp $

# Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
# Licensed under the BSD or LGPL License. See doc/license.txt for details.

DB=$1
HOST=$2
if [ -z "$HOST" ]; then
	HOST="example.com"
fi
(echo "SET FOREIGN_KEY_CHECKS=0;"; ssh $HOST "mysqldump --opt $DB") | mysql $DB
