-- $CVSHeader: _freebeer/bin/add_headers.php,v 1.2 2004/03/07 17:51:14 ross Exp $

-- Copyright (c) 2002-2004, Ross Smith.  All rights reserved.
-- Licensed under the BSD or LGPL License. See license.txt for details.

DROP TABLE IF EXISTS state;
CREATE TABLE IF NOT EXISTS state (
	id	CHAR(2)		NOT NULL AUTO_INCREMENT, -- COMMENT 'State ID',
	name	CHAR(20)	NOT NULL, -- COMMENT 'State name',
	PRIMARY KEY (id),
	UNIQUE INDEX ux_name (name)
);

DROP TABLE IF EXISTS event_type;
CREATE TABLE IF NOT EXISTS event_type (
	id	INT(11)		NOT NULL AUTO_INCREMENT, -- COMMENT 'Event type ID',
	name	CHAR(40)	NOT NULL, -- COMMENT 'Event type name',
	PRIMARY KEY (id),
	UNIQUE INDEX ux_name (name)
);

DROP TABLE IF EXISTS location;

CREATE TABLE IF NOT EXISTS location (
	id		INT(11)		NOT NULL AUTO_INCREMENT, -- COMMENT 'Event type ID',
	address		VARCHAR(40)	NOT NULL,
	address2	VARCHAR(40)	NOT NULL,
	city    	VARCHAR(40)	NOT NULL,
	state_id   	CHAR(2)		NOT NULL,
	region		VARCHAR(40)	NOT NULL,
	zip     	VARCHAR(16)	NOT NULL,
	country_id   	CHAR(2)		NOT NULL DEFAULT 'US',
	PRIMARY KEY (id)
	CONSTRAINT FOREIGN KEY fk_state (state_id) REFERENCES state (id),

);

DROP TABLE IF EXISTS event;
CREATE TABLE IF NOT EXISTS event (
	id		INT(11)		NOT NULL AUTO_INCREMENT, -- COMMENT 'Event ID',
	user_id		INT(11)		NOT NULL, -- COMMENT 'User ID of host',
	event_name	CHAR(80)	NOT NULL, -- COMMENT 'Event name',
	poker_game_id	INT(11)		NOT NULL, -- COMMENT '',
	event_type_id	INT(11)		NOT NULL, -- COMMENT '',
	location_id	INT(11)		NOT NULL, -- COMMENT '',
	PRIMARY KEY	(id),
	INDEX		(user_id),
	INDEX		(event_name),	// should this be full text indexed?
	INDEX		(poker_game_id),
	INDEX		(event_type_id),
	
	CONSTRAINT FOREIGN KEY fk_user_id (user_id) REFERENCES user_table (user_id),
	CONSTRAINT FOREIGN KEY fk_poker_game_id (poker_game_id) REFERENCES poker_game (poker_game_id),
	CONSTRAINT FOREIGN KEY fk_event_type_id (event_type_id) REFERENCES event_type (event_type_id)
);
