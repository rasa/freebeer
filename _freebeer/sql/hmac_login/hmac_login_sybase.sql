-- 
-- Created by SQL::Translator::Producer::Sybase
-- Created on Mon Sep 15 13:00:48 2003
-- 
--
-- Table: challenges
--

-- $CVSHeader: _freebeer/sql/hmac_login/hmac_login_sybase.sql,v 1.1.1.1 2004/01/18 00:12:05 ross Exp $

-- Copyright (c) 2001-2003, Ross Smith.  All rights reserved.

-- Licensed under the BSD or LGPL License. See doc/license.txt for details.

DROP TABLE challenges;
CREATE TABLE challenges (
  id IDENTITY bigint(20) DEFAULT '0' NOT NULL,
  challenge char(22) DEFAULT '' NOT NULL,
  used char(1) DEFAULT '' NOT NULL,
  login varchar(80) DEFAULT '' NOT NULL,
  ip_address varchar(30) DEFAULT '' NOT NULL,
  user_agent varchar(80) DEFAULT '' NOT NULL,
  referer varchar(255) DEFAULT '' NOT NULL,
  created datetime DEFAULT '0' NOT NULL,
  modified datetime DEFAULT '0' NOT NULL,
  CONSTRAINT ux_challenge UNIQUE (challenge),
  CONSTRAINT pk_challenges PRIMARY KEY (id)
);

CREATE INDEX ix_used ON challenges (used);

CREATE INDEX ix_modified ON challenges (modified);

CREATE INDEX ix_login ON challenges (login);

--
-- Table: logins
--

DROP TABLE logins;
CREATE TABLE logins (
  id IDENTITY int(11) DEFAULT '0' NOT NULL,
  login varchar(80) DEFAULT '' NOT NULL,
  password varchar(80) DEFAULT '' NOT NULL,
  CONSTRAINT ux_login UNIQUE (login),
  CONSTRAINT pk_logins PRIMARY KEY (id)
);

