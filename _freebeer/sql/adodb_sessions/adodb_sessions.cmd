:: $CVSHeader: _freebeer/sql/adodb_sessions/adodb_sessions.cmd,v 1.2 2004/03/07 17:51:25 ross Exp $

:: Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
:: Licensed under the BSD or LGPL License. See license.txt for details.

sqlplus scott/tiger @adodb_sessions_oracle_clob.sql
sqlite adodb_sessions_sqlite.sql
