-- $CVSHeader: _freebeer/bin/add_headers.php,v 1.2 2004/03/07 17:51:14 ross Exp $

-- Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
-- Licensed under the BSD or LGPL License. See license.txt for details.

DROP TABLE adodb_sessions;

CREATE TABLE sessions (
	sesskey		CHAR(32)	DEFAULT '' NOT NULL,
	expiry		INT		DEFAULT 0 NOT NULL,
	expireref	VARCHAR(64)	DEFAULT '',
	data		VARCHAR(4000)	DEFAULT '',
	PRIMARY KEY	(sesskey),
	INDEX expiry (expiry)
);

CREATE INDEX ix_expiry ON sessions (expiry);

QUIT;
