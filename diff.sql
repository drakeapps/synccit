
-- after 5c8a7a2

-- add userid index to `links`
ALTER TABLE  `links` ADD INDEX (  `userid` );

-- change tables over to innodb
ALTER TABLE  `authcodes` ENGINE = INNODB;
ALTER TABLE  `links` ENGINE = INNODB;
ALTER TABLE  `logincodes` ENGINE = INNODB;
ALTER TABLE  `user` ENGINE = INNODB;


-- change `authhash` to varchar and index
ALTER TABLE  `authcodes` CHANGE  `authhash`  `authhash` VARCHAR( 20 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE  `authcodes` ADD INDEX (  `authhash` );

