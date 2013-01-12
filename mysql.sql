

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `rddtsync`
--

-- --------------------------------------------------------

--
-- Table structure for table `authcodes`
--

CREATE TABLE IF NOT EXISTS `authcodes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `username` varchar(30) NOT NULL,
  `authhash` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `lastused` int(10) unsigned DEFAULT '0',
  `created` int(10) unsigned DEFAULT '0',
  `createdby` text,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `authhash` (`authhash`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `linkid` varchar(20) NOT NULL,
  `userid` int(10) unsigned NOT NULL,
  `lastvisit` int(10) unsigned NOT NULL,
  `lastcommenttime` int(10) unsigned NOT NULL,
  `lastcommentcount` int(6) unsigned NOT NULL,
  `firstvisit` int(10) unsigned NOT NULL,
  `lastcall` text NOT NULL,
  `developers` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `linkid` (`linkid`,`userid`),
  KEY `links` (`linkid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `logincodes`
--

CREATE TABLE IF NOT EXISTS `logincodes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `authhash` text NOT NULL,
  `lastlogin` int(10) unsigned NOT NULL,
  `created` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `passhash` text NOT NULL,
  `salt` text NOT NULL,
  `email` text,
  `lastlogin` int(10) unsigned DEFAULT '0',
  `lastactivity` int(10) unsigned DEFAULT '0',
  `lastip` varchar(15) DEFAULT NULL,
  `numlink` int(8) unsigned DEFAULT '0',
  `numcomments` int(8) unsigned DEFAULT '0',
  `loginattempts` int(2) unsigned DEFAULT '0',
  `created` int(10) unsigned NOT NULL DEFAULT '0',
  `createdby` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
