
CREATE TABLE parks (
	park_id SERIAL,
	park_name VARCHAR(20) NOT NULL UNIQUE,
	park_desc TEXT NULL,
	park_updated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	park_location TEXT NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE spaces (
	space_id SERIAL,
	space_park_id BIGINT UNSIGNED,
	space_pi_id BIGINT NOT NULL,
	space_area_code INT NOT NULL,
	space_status_id INT NOT NULL DEFAULT 0,
	space_time_added TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	
	FOREIGN KEY ( space_park_id ) REFERENCES parks ( park_id )
		ON DELETE SET NULL
		ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE updates (
	update_id SERIAL,
	update_space_id BIGINT UNSIGNED,
	update_time TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	update_status INT(1) NOT NULL DEFAULT 0,
	
	FOREIGN KEY ( update_space_id ) REFERENCES spaces ( space_id )
		ON DELETE CASCADE
		ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

		