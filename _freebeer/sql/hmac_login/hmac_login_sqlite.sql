-- $CVSHeader: _freebeer/sql/hmac_login/hmac_login_sqlite.sql,v 1.2 2004/03/07 17:51:26 ross Exp $

-- Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
-- Licensed under the BSD or LGPL License. See license.txt for details.

-- 
-- Created by SQL::Translator::Producer::SQLite
-- Created on Mon Sep 15 13:00:39 2003
-- 
--
-- Table: challenges
--
DROP TABLE challenges;
CREATE TABLE challenges (
  id INTEGER PRIMARY KEY NOT NULL DEFAULT '0',
  challenge CHAR(22) NOT NULL DEFAULT '',
  used CHAR(1) NOT NULL DEFAULT '',
  login VARCHAR(80) NOT NULL DEFAULT '',
  ip_address VARCHAR(30) NOT NULL DEFAULT '',
  user_agent VARCHAR(80) NOT NULL DEFAULT '',
  referer VARCHAR(255) NOT NULL DEFAULT '',
  created DATETIME NOT NULL DEFAULT '0',
  modified DATETIME NOT NULL DEFAULT '0'
);
CREATE INDEX ix_used_challenges on challenges (used);
CREATE INDEX ix_modified_challenges on challenges (modified);
CREATE INDEX ix_login_challenges on challenges (login);
CREATE UNIQUE INDEX ux_challenge_challenges on challenges (challenge);

--
-- Table: logins
--
DROP TABLE logins;
CREATE TABLE logins (
  id INTEGER PRIMARY KEY NOT NULL DEFAULT '0',
  login VARCHAR(80) NOT NULL DEFAULT '',
  password VARCHAR(80) NOT NULL DEFAULT ''
);
CREATE UNIQUE INDEX ux_login_logins on logins (login);

