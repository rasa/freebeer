-- $CVSHeader: _freebeer/bin/add_headers.php,v 1.2 2004/03/07 17:51:14 ross Exp $

-- Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
-- Licensed under the BSD or LGPL License. See license.txt for details.

DROP TABLE IF EXISTS challenges;

CREATE TABLE IF NOT EXISTS challenges (
	id		BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	challenge	CHAR(22) BINARY NOT NULL DEFAULT '',
	used		CHAR(1) NOT NULL DEFAULT '',
	login		VARCHAR(80) NOT NULL DEFAULT '',
	ip_address	VARCHAR(30) NOT NULL DEFAULT '',
	user_agent	VARCHAR(80) NOT NULL DEFAULT '',
	referer		VARCHAR(255) NOT NULL DEFAULT '',
	created		DATETIME NOT NULL DEFAULT 0,
	modified	DATETIME NOT NULL DEFAULT 0,
	UNIQUE ux_challenge (challenge),
	INDEX ix_used (used),
	INDEX ix_modified (modified),
	INDEX ix_login (login),
	PRIMARY KEY (id)
);

DROP TABLE IF EXISTS logins;

CREATE TABLE IF NOT EXISTS logins (
	id		INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	login		VARCHAR(80) BINARY NOT NULL DEFAULT '',
	password	VARCHAR(80) BINARY NOT NULL DEFAULT '',
	UNIQUE ux_login (login),
	PRIMARY KEY (id)
);

INSERT INTO logins VALUES (NULL, 'login', 'password');
