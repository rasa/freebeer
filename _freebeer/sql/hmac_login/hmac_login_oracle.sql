-- 
-- Created by SQL::Translator::Producer::Oracle
-- Created on Mon Sep 15 13:00:20 2003
-- 
-- We assume that default NLS_DATE_FORMAT has been changed
-- but we set it here anyway to be self-consistent.
ALTER SESSION SET NLS_DATE_FORMAT = 'YYYY-MM-DD HH24:MI:SS';

--
-- Table: challenges
--

DROP TABLE challenges;
CREATE TABLE challenges (
  id number(20) DEFAULT '0' CONSTRAINT nn_challenges_id NOT NULL,
  challenge char(22) DEFAULT '' CONSTRAINT nn_challenges_challenge NOT NULL,
  used char(1) DEFAULT '' CONSTRAINT nn_challenges_used NOT NULL,
  login varchar2(80) DEFAULT '' CONSTRAINT nn_challenges_login NOT NULL,
  ip_address varchar2(30) DEFAULT '' CONSTRAINT nn_challenges_ip_address NOT NULL,
  user_agent varchar2(80) DEFAULT '' CONSTRAINT nn_challenges_user_agent NOT NULL,
  referer varchar2(255) DEFAULT '' CONSTRAINT nn_challenges_referer NOT NULL,
  created date DEFAULT '0' CONSTRAINT nn_challenges_created NOT NULL,
  modified date DEFAULT '0' CONSTRAINT nn_challenges_modified NOT NULL,
  CONSTRAINT ux_challenge UNIQUE (challenge),
  CONSTRAINT pk_challenges PRIMARY KEY (id)
);

CREATE SEQUENCE sq_challenges_id;
CREATE OR REPLACE TRIGGER ai_challenges_id
BEFORE INSERT ON challenges
FOR EACH ROW WHEN (
 new.id IS NULL OR new.id = 0
)
BEGIN
 SELECT sq_challenges_id.nextval
 INTO :new.id
 FROM dual;
END;
/

CREATE INDEX ix_used on challenges (used);

CREATE INDEX ix_modified on challenges (modified);

CREATE INDEX ix_login on challenges (login);

COMMENT ON TABLE challenges is
  '$CVSHeader: _freebeer/sql/hmac_login/hmac_login_oracle.sql,v 1.1.1.1 2004/03/03 22:48:41 ross Exp $';

COMMENT ON TABLE challenges is
  'Copyright (c) 2001-2003, Ross Smith.  All rights reserved.';

COMMENT ON TABLE challenges is
  'Licensed under the BSD or LGPL License. See doc/license.txt for details.';

--
-- Table: logins
--

DROP TABLE logins;
CREATE TABLE logins (
  id number(11) DEFAULT '0' CONSTRAINT nn_logins_id NOT NULL,
  login varchar2(80) DEFAULT '' CONSTRAINT nn_logins_login NOT NULL,
  password varchar2(80) DEFAULT '' CONSTRAINT nn_logins_password NOT NULL,
  CONSTRAINT ux_login UNIQUE (login),
  CONSTRAINT pk_logins PRIMARY KEY (id)
);

CREATE SEQUENCE sq_logins_id;
CREATE OR REPLACE TRIGGER ai_logins_id
BEFORE INSERT ON logins
FOR EACH ROW WHEN (
 new.id IS NULL OR new.id = 0
)
BEGIN
 SELECT sq_logins_id.nextval
 INTO :new.id
 FROM dual;
END;
/

