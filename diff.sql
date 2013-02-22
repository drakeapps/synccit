
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



-- ----------------------------------------------


-- after c4e3f2b


-- add createdby to user and auth tables when user created by API
ALTER TABLE  `user` ADD  `createdby` TEXT NULL DEFAULT NULL;
ALTER TABLE  `authcodes` ADD  `createdby` TEXT NULL DEFAULT NULL;


-- ----------------------------------------------

-- after 32b8aa2

-- add index for logincode authhash for account API
-- must change `logincodes` to a varchar instead of text to allow for index
-- similar to the change of authhash on the authcodes tables
-- it's a 64 character hash
-- note, this isn't required for it to function, just should make it faster
ALTER TABLE  `logincodes` CHANGE  `authhash`  `authhash` VARCHAR( 64 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE  `logincodes` ADD INDEX (  `authhash` );


-- ----------------------------------------------



-- after 67185d8

-- add faq table, and all the content
-- this can be myisam or innodb, and afaik performance shouldn't be affected
-- we aren't doing single row writes or anything

CREATE TABLE IF NOT EXISTS `faq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM ;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`id`, `question`, `answer`) VALUES
(1, 'What''s with devices and auth codes?', 'The point of creating an auth code for each device is to allow better account security. If one device or app gets compromised, you can revoke the device''s auth code to help minimize it''s effect'),
(2, 'What do I enter into an app?', 'You''ll need to enter your username and the device''s auth code you created for it'),
(3, 'My history isn''t syncing!', 'Most likely, you entered the auth code incorrectly. Make sure the code matches or try creating a new code'),
(4, 'My auth code is correct, but it still doesn''t work!', 'Make sure you entered your username, and not the device name, into the username section. Device name is just a way to keep track of the auth codes.'),
(5, 'I forgot my password', 'Currently, this can only be manually done. Email james@drakeapps.com from your account email'),
(6, 'How do I change synccit info in the browser add ons?', 'Scroll to the bottom of a reddit page and click the synccit link'),
(7, 'My favorite reddit app doesn''t support synccit. What can I do?', 'Suggest their support. The API is <a href="https://github.com/drakeapps/synccit#api-docs">open and documented</a>, and the more support they see, the more likely it is that they''ll add support.');
