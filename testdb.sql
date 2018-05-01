DROP DATABASE IF EXISTS testdb;
CREATE DATABASE testdb;


DROP TABLE IF EXISTS testdb.users;

CREATE TABLE testdb.users(
	id		INT(10) PRIMARY KEY AUTO_INCREMENT,
	firstName	VARCHAR(255),
    lastName    VARCHAR(255),
    email       VARCHAR(255)
);