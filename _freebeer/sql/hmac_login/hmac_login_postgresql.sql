-- 
-- Created by SQL::Translator::Producer::PostgreSQL
-- Created on Mon Sep 15 13:00:30 2003
-- 
--
-- Table: challenges
--

DROP TABLE "challenges";
CREATE TABLE "challenges" (
  "id" bigserial DEFAULT '0' NOT NULL,
  "challenge" char(22) DEFAULT '' NOT NULL,
  "used" char(1) DEFAULT '' NOT NULL,
  "login" character varying(80) DEFAULT '' NOT NULL,
  "ip_address" character varying(30) DEFAULT '' NOT NULL,
  "user_agent" character varying(80) DEFAULT '' NOT NULL,
  "referer" character varying(255) DEFAULT '' NOT NULL,
  "created" timestamp DEFAULT '0' NOT NULL,
  "modified" timestamp DEFAULT '0' NOT NULL,
  Constraint "ux_challenge" UNIQUE ("challenge"),
  Constraint "pk_challenges" PRIMARY KEY ("id")
);

CREATE INDEX "ix_used" on challenges ("used");

CREATE INDEX "ix_modified" on challenges ("modified");

CREATE INDEX "ix_login" on challenges ("login");

--
-- Table: logins
--

DROP TABLE "logins";
CREATE TABLE "logins" (
  "id" serial DEFAULT '0' NOT NULL,
  "login" character varying(80) DEFAULT '' NOT NULL,
  "password" character varying(80) DEFAULT '' NOT NULL,
  Constraint "ux_login" UNIQUE ("login"),
  Constraint "pk_logins" PRIMARY KEY ("id")
);

