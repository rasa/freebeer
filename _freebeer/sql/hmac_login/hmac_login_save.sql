-- $CVSHeader: _freebeer/sql/hmac_login/hmac_login_save.sql,v 1.1.1.1 2004/01/18 00:12:05 ross Exp $

-- Copyright (c) 2001-2003, Ross Smith.  All rights reserved.
-- Licensed under the BSD or LGPL License. See doc/license.txt for details.

/*! DROP DATABASE IF EXISTS hmac_login; */
/*! CREATE DATABASE IF NOT EXISTS hmac_login; */
/*! USE hmac_login; */

DROP TABLE IF EXISTS challenges;

CREATE TABLE IF NOT EXISTS challenges (
	id		BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	challenge	CHAR(22) BINARY NOT NULL,
	used		CHAR(1) NOT NULL,
	login		VARCHAR(80) NOT NULL,
	ip_address	VARCHAR(30) NOT NULL,
	user_agent	VARCHAR(80) NOT NULL,
	referer		VARCHAR(255) NOT NULL,
	created		DATETIME NOT NULL,
	modified	DATETIME NOT NULL,
	UNIQUE ux_challenge (challenge),
	INDEX ix_used (used),		/* optional, speeds up purging old 
					   records */
	INDEX ix_modified (modified),	/* optional, speeds up purging old
					-- records */
	INDEX ix_login (login),		/* optional, speeds lookups of a */
					/* user's login history */
	PRIMARY KEY (id)
);

DROP TABLE IF EXISTS logins;

CREATE TABLE IF NOT EXISTS logins (
	id		INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	login		VARCHAR(80) BINARY NOT NULL,
	password	VARCHAR(80) BINARY NOT NULL,
	UNIQUE ux_login (login),
	PRIMARY KEY (id)
);

/*! INSERT INTO logins VALUES (NULL, 'login', 'password'); */
