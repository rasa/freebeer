-- $CVSHeader: _freebeer/bin/add_headers.php,v 1.2 2004/03/07 17:51:14 ross Exp $

-- Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
-- Licensed under the BSD or LGPL License. See license.txt for details.

DROP TABLE /*! IF EXISTS */ sessions;

CREATE TABLE /*! IF NOT EXISTS */ sessions (
	sesskey		CHAR(32)	/*! BINARY */ NOT NULL DEFAULT '',
	expiry		INT(11)		/*! UNSIGNED */ NOT NULL DEFAULT 0,
	expireref	VARCHAR(64)	DEFAULT '',
	data		LONGTEXT	DEFAULT '',
	PRIMARY KEY	(sesskey),
	INDEX expiry (expiry)
);
