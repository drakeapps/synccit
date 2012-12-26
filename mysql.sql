--
-- Database: `redditsync`
--

-- --------------------------------------------------------

--
-- Table structure for table `authcodes`
--

CREATE TABLE IF NOT EXISTS `authcodes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `username` varchar(30) NOT NULL,
  `authhash` text NOT NULL,
  `description` text NOT NULL,
  `lastused` int(10) unsigned DEFAULT NULL,
  `created` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
  PRIMARY KEY (`id`),
  KEY `linkid` (`linkid`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
  `lastlogin` int(10) unsigned DEFAULT NULL,
  `lastactivity` int(10) unsigned DEFAULT NULL,
  `lastip` varchar(15) DEFAULT NULL,
  `numlink` int(8) unsigned DEFAULT NULL,
  `numcomments` int(8) unsigned DEFAULT NULL,
  `loginattempts` int(2) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;