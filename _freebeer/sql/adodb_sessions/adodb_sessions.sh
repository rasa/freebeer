#!/bin/sh

# $CVSHeader: _freebeer/bin/add_headers.php,v 1.2 2004/03/07 17:51:14 ross Exp $

# Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
# Licensed under the BSD or LGPL License. See license.txt for details.

mysqladmin drop adodb_sessions
mysqladmin create adodb_sessions
mysql <adodb_sessions_mysql.sql
sqlplus scott/tiger @adodb_sessions_oracle_clob.sql
sqlite adodb_sessions_sqlite.sql
