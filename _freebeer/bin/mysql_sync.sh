#!/bin/sh -x

# $CVSHeader: _freebeer/bin/mysql_sync.sh,v 1.2 2004/03/07 17:51:14 ross Exp $

# Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
# Licensed under the BSD or LGPL License. See license.txt for details.

DB=$1
HOST=$2
if [ -z "$HOST" ]; then
	HOST="example.com"
fi
(echo "SET FOREIGN_KEY_CHECKS=0;"; ssh $HOST "mysqldump --opt $DB") | mysql $DB
