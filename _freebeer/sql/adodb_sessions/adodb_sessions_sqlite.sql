-- $CVSHeader: _freebeer/sql/adodb_sessions/adodb_sessions_sqlite.sql,v 1.2 2004/03/07 17:51:25 ross Exp $

-- Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
-- Licensed under the BSD or LGPL License. See license.txt for details.

	sesskey		CHAR(32)	NOT NULL DEFAULT '',
	expiry		INT(11)		NOT NULL DEFAULT 0,
	expireref	VARCHAR(64)	DEFAULT '',
	data		LONGTEXT	DEFAULT '',
	PRIMARY KEY	(sesskey)
);

CREATE INDEX ix_expiry ON sessions (expiry);
