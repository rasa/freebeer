-- 
-- Created by SQL::Translator::Producer::MySQL
-- Created on Mon Sep 15 12:18:06 2003
-- 
SET foreign_key_checks=0;

--
-- Table: challenges
--
DROP TABLE IF EXISTS challenges;
CREATE TABLE challenges (
  id BIGINT(20) unsigned NOT NULL DEFAULT '0' auto_increment,
  challenge CHAR(22) binary NOT NULL DEFAULT '',
  used CHAR(1) NOT NULL DEFAULT '',
  login VARCHAR(80) NOT NULL DEFAULT '',
  ip_address VARCHAR(30) NOT NULL DEFAULT '',
  user_agent VARCHAR(80) NOT NULL DEFAULT '',
  referer VARCHAR(255) NOT NULL DEFAULT '',
  created DATETIME NOT NULL DEFAULT '0',
  modified DATETIME NOT NULL DEFAULT '0',
  INDEX ix_used (used),
  INDEX ix_modified (modified),
  INDEX ix_login (login),
  UNIQUE (challenge),
  PRIMARY KEY (id)
);

--
-- Table: logins
--
DROP TABLE IF EXISTS logins;
CREATE TABLE logins (
  id INT(11) unsigned NOT NULL DEFAULT '0' auto_increment,
  login VARCHAR(80) binary NOT NULL DEFAULT '',
  password VARCHAR(80) binary NOT NULL DEFAULT '',
  UNIQUE (login),
  PRIMARY KEY (id)
);

