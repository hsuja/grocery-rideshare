CREATE TABLE `locations`(
	`id` int NOT NULL AUTO_INCREMENT,
	`GPS_long` decimal(11,8) NOT NULL,
	`GPS_lat` decimal(11,8) NOT NULL,
	`name` varchar(20),
	PRIMARY KEY(`id`)
) ENGINE=InnoDB;

CREATE TABLE `rideshares`(
	`id` int NOT NULL AUTO_INCREMENT,
	`pickup_id` int NOT NULL,
	`destination_id` int NOT NULL,
	`owner_id` int NOT NULL,
	`capacity`int,
	PRIMARY KEY(`id`),
	FOREIGN KEY(`pickup_id`) REFERENCES `locations`(`id`)
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	FOREIGN KEY(`destination_id`) REFERENCES `locations`(`id`)
		ON UPDATE CASCADE
		ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `gcrs_users`(
	`id` int NOT NULL AUTO_INCREMENT,
	`name` varchar(20) NOT NULL,
	`location_id` int NOT NULL,
	PRIMARY KEY(`id`),
	FOREIGN KEY(`location_id`) REFERENCES `locations`(`id`)
		ON UPDATE CASCADE
		ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `distances`(
	`id` int NOT NULL AUTO_INCREMENT,
	`user_id` int NOT NULL,
	`location_id` int NOT NULL,
	`distance` decimal(10,2),
	PRIMARY KEY(`id`),
	FOREIGN KEY(`user_id`) REFERENCES `gcrs_users`(`id`)
		ON UPDATE CASCADE
		ON DELETE CASCADE,
	FOREIGN KEY(`location_id`) REFERENCES `locations`(`id`)
		ON UPDATE CASCADE
		ON DELETE CASCADE
) ENGINE=InnoDB;






