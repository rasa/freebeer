#!/bin/sh

# $CVSHeader: _freebeer/sql/adodb_sessions/adodb_sessions.sh,v 1.1.1.1 2004/01/18 00:12:05 ross Exp $

mysqladmin drop adodb_sessions
mysqladmin create adodb_sessions
mysql <adodb_sessions_mysql.sql
sqlplus scott/tiger @adodb_sessions_oracle_clob.sql
sqlite adodb_sessions_sqlite.sql
