-- $Horde: horde/scripts/db/category_mysql.sql,v 1.12 2003/07/12 16:55:58 mdjukic Exp $

CREATE TABLE horde_categories (
       category_id INT NOT NULL,
       group_uid VARCHAR(255) NOT NULL,
       user_uid VARCHAR(255) NOT NULL,
       category_name VARCHAR(255) NOT NULL,
       category_parents VARCHAR(255) NOT NULL,
       category_order INT,
       category_data TEXT,
       category_serialized SMALLINT DEFAULT 0 NOT NULL,
       category_updated TIMESTAMP,
       PRIMARY KEY (category_id)
);

CREATE INDEX category_category_name_idx ON horde_categories (category_name);
CREATE INDEX category_group_idx ON horde_categories (group_uid);
CREATE INDEX category_user_idx ON horde_categories (user_uid);
CREATE INDEX category_serialized_idx ON horde_categories (category_serialized);

CREATE TABLE horde_category_attributes (
    category_id INT NOT NULL,
    attribute_name VARCHAR(255) NOT NULL,
    attribute_key VARCHAR(255) DEFAULT '' NOT NULL,
    attribute_value TEXT
);

CREATE INDEX category_attribute_idx ON horde_category_attributes (category_id);
CREATE INDEX category_attribute_name_idx ON horde_category_attributes (attribute_name);
CREATE INDEX category_attribute_key_idx ON horde_category_attributes (attribute_key);

GRANT SELECT, INSERT, UPDATE, DELETE ON horde_categories TO horde@localhost;
GRANT SELECT, INSERT, UPDATE, DELETE ON horde_category_attributes TO horde@localhost;
