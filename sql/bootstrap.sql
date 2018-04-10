CREATE DATABASE  urbanste_master;

CREATE TABLE item (
     id SMALLINT AUTO_INCREMENT,
     name CHAR(30) NOT NULL UNIQUE,
     description CHAR(255),
     total INT,
     recovery_time SMALLINT,
     hire_cost SMALLINT NOT NULL,
     PRIMARY KEY (id)
);
--
-- CREATE TABLE component (
--      id SMALLINT  NOT NULL AUTO_INCREMENT,
--      name CHAR(30) NOT NULL UNIQUE,
--      description VARCHAR(400),
--      PRIMARY KEY (id)
-- );

CREATE TABLE quotation (
  id SMALLINT NOT NULL  AUTO_INCREMENT,
  client_name char(50) NOT NULL UNIQUE,
  description VARCHAR(50),
  quote_date DATE,
  wedding_date DATE,
  event_type CHAR(20),
  location CHAR(15),
  approved SMALLINT DEFAULT 0,
  event_time CHAR(15),
  PRIMARY KEY (id)
);

CREATE TABLE quotation_data (
  id SMALLINT NOT NULL  AUTO_INCREMENT,
  quotation_id SMALLINT NOT NULL,
  data VARCHAR(10000),
  PRIMARY KEY (id),
  FOREIGN KEY (quotation_id) REFERENCES quotation(id)
);

CREATE TABLE fresh_flower (
    id SMALLINT AUTO_INCREMENT,
    name CHAR (20) NOT NULL UNIQUE ,
    buy_rate SMALLINT NOT NULL,
    comm_rate SMALLINT NOT NULL,
    sell_rate SMALLINT NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE labour (
    id SMALLINT AUTO_INCREMENT,
    type CHAR(20) NOT NULL UNIQUE ,
    rate INT NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE employee (
    id SMALLINT NOT NULL  AUTO_INCREMENT,
    name CHAR(20) UNIQUE,
    salary INT NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE utility(
    id SMALLINT NOT NULL  AUTO_INCREMENT,
    name CHAR(30),
    total INT NOT NULL,
    PRIMARY KEY (id)
);