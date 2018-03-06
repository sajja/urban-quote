CREATE DATABASE  urbanste_master;

CREATE TABLE item (
     name CHAR(30) NOT NULL,
     description CHAR(255),
     total SMALLINT,
     recovery_time SMALLINT,
     hire_cost SMALLINT NOT NULL,
     PRIMARY KEY (name)
);

CREATE TABLE component (
     id SMALLINT  NOT NULL AUTO_INCREMENT,
     name CHAR(30) NOT NULL UNIQUE,
     description VARCHAR(400),
     PRIMARY KEY (id)
);

CREATE TABLE component_item (
    component_id SMALLINT,
    item CHAR(30),
    hire_cost SMALLINT NOT NULL,
    FOREIGN KEY (component_id) REFERENCES component(id),
    FOREIGN KEY (item) REFERENCES item(name),
    PRIMARY KEY (component_id, item)
);

CREATE TABLE quotation (
  id SMALLINT NOT NULL  AUTO_INCREMENT,
  client_name char(50) NOT NULL UNIQUE,
  description VARCHAR(50),
  quote_date DATE,
  wedding_date DATE,
  event_type CHAR(20),
  location CHAR(15),
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
    name CHAR (20),
    buy_rate SMALLINT NOT NULL,
    comm_rate SMALLINT NOT NULL,
    sell_rate SMALLINT NOT NULL,
    PRIMARY KEY (name)
);
CREATE TABLE labour (
    type CHAR(20),
    rate SMALLINT NOT NULL,
    PRIMARY KEY (type)
);

CREATE TABLE employee (
    name CHAR(20),
    salary SMALLINT NOT NULL,
    PRIMARY KEY (name)
);

CREATE TABLE other_cost_master (
    name CHAR(30),
    total SMALLINT NOT NULL,
    PRIMARY KEY (name)
);