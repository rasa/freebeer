-- $CVSHeader: _freebeer/sql/adodb_sessions/adodb_sessions_sqlite.sql,v 1.1.1.1 2004/01/18 00:12:05 ross Exp $

DROP TABLE sessions;

CREATE TABLE sessions (
	sesskey		CHAR(32)	NOT NULL DEFAULT '',
	expiry		INT(11)		NOT NULL DEFAULT 0,
	expireref	VARCHAR(64)	DEFAULT '',
	data		LONGTEXT	DEFAULT '',
	PRIMARY KEY	(sesskey)
);

CREATE INDEX ix_expiry ON sessions (expiry);
