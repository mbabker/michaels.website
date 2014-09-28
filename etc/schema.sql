--
-- Table setup order:
-- #__users
-- #__articles
--

--
-- Table structure for table `#__users`
--

CREATE TABLE IF NOT EXISTS `#__users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT 'The user\'s name',
  `username` varchar(150) NOT NULL DEFAULT '' COMMENT 'The user\'s username',
	`password` varchar(64) NOT NULL COMMENT 'The user\'s password',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT 'The user\'s e-mail',
  `params` text NOT NULL COMMENT 'Parameters',
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`),
  KEY `username` (`username`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `#__articles`
--

CREATE TABLE IF NOT EXISTS `#__articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
  `owner` int(11) NOT NULL COMMENT 'Foreign toek to #__users.id',
  `title` varchar(250) NOT NULL COMMENT 'The article title',
  `alias` varchar(250) NOT NULL COMMENT 'The article alias',
  `text` text NOT NULL COMMENT 'The article text',
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'The created date.',
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`),
  CONSTRAINT `#__articles_fk_owner` FOREIGN KEY (`owner`) REFERENCES `#__users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
