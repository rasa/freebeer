-- $Horde: horde/scripts/db/category.sql,v 1.18 2003/07/14 16:33:08 mdjukic Exp $

CREATE TABLE horde_categories (
    category_id INT NOT NULL,
    group_uid VARCHAR(255) NOT NULL,
    user_uid VARCHAR(255) NOT NULL,
    category_name VARCHAR(255) NOT NULL,
    category_parents VARCHAR(255) NOT NULL,
    category_order INT,
-- There is no portable way to do this apparently. If your db doesn't allow varchars
-- greater than 255 characters, then maybe it allows TEXT columns, so try the second
-- line.
    category_data VARCHAR(2048),
--  category_data TEXT,
    category_serialized SMALLINT DEFAULT 0 NOT NULL,
    category_updated TIMESTAMP,

    PRIMARY KEY (category_id)
);

CREATE INDEX category_category_name_idx ON horde_categories (category_name);
CREATE INDEX category_group_idx ON horde_categories (group_uid);
CREATE INDEX category_user_idx ON horde_categories (user_uid);
CREATE INDEX category_order_idx ON horde_categories (category_order);
CREATE INDEX category_serialized_idx ON horde_categories (category_serialized);


CREATE TABLE horde_category_attributes (
    category_id INT NOT NULL,
    attribute_name VARCHAR(255) NOT NULL,
    attribute_key VARCHAR(255),
    attribute_value TEXT
);

CREATE INDEX category_attribute_idx ON horde_category_attributes (category_id);
CREATE INDEX category_attribute_name_idx ON horde_category_attributes (attribute_name);
CREATE INDEX category_attribute_key_idx ON horde_category_attributes (attribute_key);


GRANT SELECT, INSERT, UPDATE, DELETE ON horde_categories TO horde;
GRANT SELECT, INSERT, UPDATE, DELETE ON horde_category_attributes TO horde;
