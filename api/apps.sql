
-- This is done on a separate database for production synccit
-- It can be in the same database as the rest of synccit though
-- This is only for requesting a list of support applications
-- It is not needed for synccit to function
CREATE TABLE  `apps` (
`id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 128 ) NOT NULL ,
`description` TEXT NULL ,
`link` TEXT NOT NULL ,
`platform` VARCHAR( 64 ) NOT NULL ,
INDEX (  `platform` ) ,
UNIQUE (
`name`
)
) ENGINE = MYISAM ;