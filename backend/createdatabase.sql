CREATE TABLE  IF NOT EXISTS person (
   id       MEDIUMINT NOT NULL AUTO_INCREMENT primary key,
   name     VARCHAR(512),
   email    VARCHAR(512),
   dateofbirth DATE
);